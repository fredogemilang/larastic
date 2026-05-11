<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\Tag;
use DOMDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class WpImporter
{
    protected string $baseUrl;
    protected int $userId;
    protected \Closure $progressCallback;

    /** Whether the remote WP site uses Polylang Pro (detected during testConnection). */
    protected bool $hasPolylang = false;

    /** Available languages reported by Polylang (e.g. ['id', 'en']). */
    protected array $polylangLanguages = [];

    public function __construct()
    {
        $this->progressCallback = function ($message) {
            Log::info("WpImporter: " . $message);
        };
    }

    public function setBaseUrl(string $url): self
    {
        $this->baseUrl = rtrim($url, '/');
        return $this;
    }

    public function setUserId(int $id): self
    {
        $this->userId = $id;
        return $this;
    }

    public function setProgressCallback(\Closure $callback): self
    {
        $this->progressCallback = $callback;
        return $this;
    }

    protected function log(string $message): void
    {
        ($this->progressCallback)($message);
    }

    // ─── Connection ──────────────────────────────────────────────────

    protected function httpClient(): \Illuminate\Http\Client\PendingRequest
    {
        $http = Http::timeout(30);
        if (app()->environment('local', 'development')
            && config('static-cms.import.skip_ssl_verify', false)) {
            $http = $http->withoutVerifying();
        }
        return $http;
    }

    public function testConnection(): bool
    {
        try {
            $url = "{$this->baseUrl}/wp-json/wp/v2/";
            Log::debug("Testing WP connection to: " . $url);
            $response = $this->httpClient()->timeout(10)->get($url);
            
            if (!$response->successful()) {
                Log::error("WP connection failed. Status: " . $response->status() . " Body: " . $response->body());
                return false;
            }

            $data = $response->json();
            if (!isset($data['namespace']) || $data['namespace'] !== 'wp/v2') {
                Log::error("WP connection failed. 'namespace' missing or invalid in response. Data: " . json_encode($data));
                return false;
            }

            // Detect Polylang Pro — check for language taxonomy endpoint
            $this->detectPolylang();

            return true;
        } catch (\Exception $e) {
            Log::error("WP connection exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            return false;
        }
    }

    /**
     * Detect if the WordPress site has Polylang Pro REST API support.
     */
    protected function detectPolylang(): void
    {
        try {
            // Polylang Pro exposes a languages endpoint or adds lang param support.
            // Try fetching a single post to see if the `lang` field is present.
            $response = $this->httpClient()->timeout(10)->get(
                "{$this->baseUrl}/wp-json/wp/v2/posts",
                ['per_page' => 1]
            );

            if ($response->successful() && !empty($response->json())) {
                $firstPost = $response->json()[0];
                if (isset($firstPost['lang'])) {
                    $this->hasPolylang = true;
                    $this->log("Polylang Pro detected! Posts contain 'lang' field.");

                    // Collect available languages from translations field
                    if (isset($firstPost['translations']) && is_array($firstPost['translations'])) {
                        $this->polylangLanguages = array_keys($firstPost['translations']);
                        $this->log("Available languages: " . implode(', ', $this->polylangLanguages));
                    }
                } else {
                    $this->log("Polylang not detected. All posts will be imported as default locale.");
                }
            }
        } catch (\Exception $e) {
            Log::warning("Polylang detection failed: " . $e->getMessage());
        }
    }

    public function hasPolylangSupport(): bool
    {
        return $this->hasPolylang;
    }

    public function getPolylangLanguages(): array
    {
        return $this->polylangLanguages;
    }

    // ─── Categories ──────────────────────────────────────────────────

    public function importCategories(): array
    {
        $this->log("Fetching categories...");
        $page = 1;
        $importedCount = 0;
        $mapped = []; // wp_id => local_id

        while (true) {
            $response = $this->httpClient()->timeout(30)->get("{$this->baseUrl}/wp-json/wp/v2/categories", [
                'per_page' => 100,
                'page' => $page,
            ]);

            if (!$response->successful() || empty($response->json())) {
                break;
            }

            foreach ($response->json() as $wpCat) {
                $category = Category::firstOrCreate(
                    ['slug' => $wpCat['slug']],
                    [
                        'name' => $wpCat['name'],
                        'description' => $wpCat['description'] ?? null,
                    ]
                );
                $mapped[$wpCat['id']] = $category->id;
                $importedCount++;
            }

            $page++;
        }

        $this->log("Imported {$importedCount} categories.");
        return $mapped;
    }

    // ─── Tags ────────────────────────────────────────────────────────

    public function importTags(): array
    {
        $this->log("Fetching tags...");
        $page = 1;
        $importedCount = 0;
        $mapped = [];

        while (true) {
            $response = $this->httpClient()->timeout(30)->get("{$this->baseUrl}/wp-json/wp/v2/tags", [
                'per_page' => 100,
                'page' => $page,
            ]);

            if (!$response->successful() || empty($response->json())) {
                break;
            }

            foreach ($response->json() as $wpTag) {
                $tag = Tag::firstOrCreate(
                    ['slug' => $wpTag['slug']],
                    ['name' => $wpTag['name']]
                );
                $mapped[$wpTag['id']] = $tag->id;
                $importedCount++;
            }

            $page++;
        }

        $this->log("Imported {$importedCount} tags.");
        return $mapped;
    }

    // ─── Posts ────────────────────────────────────────────────────────

    /**
     * Import posts — with or without Polylang multilingual support.
     *
     * When Polylang is detected:
     * 1. Import posts for each language using ?lang=xx
     * 2. Link translations using translation_group_id (UUID)
     */
    public function importPosts(array $catMap, array $tagMap): void
    {
        if ($this->hasPolylang && !empty($this->polylangLanguages)) {
            $this->importPostsMultilingual($catMap, $tagMap);
        } else {
            $this->importPostsSingleLocale($catMap, $tagMap, 'id');
        }
    }

    /**
     * Import posts for all Polylang languages and link translations.
     */
    protected function importPostsMultilingual(array $catMap, array $tagMap): void
    {
        // Map: wp_original_id => translation_group_id (UUID)
        // We'll use the WP translations data to group them.
        $wpIdToGroupId = [];

        $defaultLocale = config('static-cms.default_locale', 'id');
        $configuredLocales = array_keys(config('static-cms.locales', ['id' => [], 'en' => []]));

        // Only import languages that are both in Polylang AND in our config
        $languagesToImport = array_intersect($this->polylangLanguages, $configuredLocales);

        if (empty($languagesToImport)) {
            $this->log("No matching languages between Polylang and CMS config. Importing all as default.");
            $this->importPostsSingleLocale($catMap, $tagMap, $defaultLocale);
            return;
        }

        // Import default locale first
        if (in_array($defaultLocale, $languagesToImport)) {
            $ordered = array_merge([$defaultLocale], array_diff($languagesToImport, [$defaultLocale]));
        } else {
            $ordered = $languagesToImport;
        }

        foreach ($ordered as $lang) {
            $this->log("── Importing posts for locale: {$lang} ──");
            $this->importPostsForLocale($lang, $catMap, $tagMap, $wpIdToGroupId);
        }

        $this->log("Multilingual import complete. Translation groups created: " . count(array_unique(array_values($wpIdToGroupId))));
    }

    /**
     * Import posts for a specific Polylang language.
     */
    protected function importPostsForLocale(string $locale, array $catMap, array $tagMap, array &$wpIdToGroupId): void
    {
        $page = 1;
        $importedCount = 0;

        while (true) {
            $params = [
                'per_page' => 20,
                'page' => $page,
                '_embed' => 'wp:featuredmedia',
                'lang' => $locale,
            ];

            $response = $this->httpClient()->timeout(30)->get(
                "{$this->baseUrl}/wp-json/wp/v2/posts",
                $params
            );

            if (!$response->successful() || empty($response->json())) {
                break;
            }

            foreach ($response->json() as $wpPost) {
                $wpId = $wpPost['id'];

                // Check if already imported
                $existing = Post::where('wp_original_id', $wpId)->first();
                if ($existing) {
                    $this->log("Skipping existing post [{$locale}]: {$wpPost['title']['rendered']}");

                    // Still record the group mapping if not set
                    if (!isset($wpIdToGroupId[$wpId]) && $existing->translation_group_id) {
                        $wpIdToGroupId[$wpId] = $existing->translation_group_id;
                    }
                    continue;
                }

                $this->log("Importing [{$locale}]: {$wpPost['title']['rendered']}");

                // Determine translation_group_id from WP translations data
                $groupId = $this->resolveTranslationGroup($wpPost, $wpIdToGroupId);

                $contentHtml = $wpPost['content']['rendered'] ?? '';
                $excerptHtml = $wpPost['excerpt']['rendered'] ?? '';

                // Download embedded images in content and update URLs
                $contentHtml = $this->processContentImages($contentHtml);

                // Handle Featured Image
                $featuredImageId = null;
                if (!empty($wpPost['_embedded']['wp:featuredmedia'][0])) {
                    $mediaData = $wpPost['_embedded']['wp:featuredmedia'][0];
                    $imageUrl = $mediaData['source_url'] ?? null;
                    if ($imageUrl) {
                        $featuredImageId = $this->downloadAndCreateMedia($imageUrl, $mediaData['alt_text'] ?? '');
                    }
                }

                $post = Post::create([
                    'title' => html_entity_decode(strip_tags($wpPost['title']['rendered'])),
                    'slug' => $wpPost['slug'],
                    'excerpt' => Str::limit(html_entity_decode(strip_tags($excerptHtml)), 200),
                    'content' => Purifier::clean($contentHtml),
                    'status' => $wpPost['status'] === 'publish' ? 'published' : 'draft',
                    'published_at' => \Carbon\Carbon::parse($wpPost['date']),
                    'author_id' => $this->userId,
                    'featured_image_id' => $featuredImageId,
                    'wp_original_id' => $wpId,
                    'import_source' => $this->baseUrl,
                    'locale' => $locale,
                    'translation_group_id' => $groupId,
                ]);

                // Record the mapping
                $wpIdToGroupId[$wpId] = $groupId;

                // Attach Categories
                if (!empty($wpPost['categories'])) {
                    $localCatIds = array_filter(array_map(fn($id) => $catMap[$id] ?? null, $wpPost['categories']));
                    if ($localCatIds) {
                        $post->categories()->sync($localCatIds);
                    }
                }

                // Attach Tags
                if (!empty($wpPost['tags'])) {
                    $localTagIds = array_filter(array_map(fn($id) => $tagMap[$id] ?? null, $wpPost['tags']));
                    if ($localTagIds) {
                        $post->tags()->sync($localTagIds);
                    }
                }

                $importedCount++;
            }
            $page++;
        }

        $this->log("Imported {$importedCount} posts for locale: {$locale}");
    }

    /**
     * Resolve a translation_group_id (UUID) for a WP post.
     * Uses the Polylang 'translations' field to find sibling WP IDs.
     */
    protected function resolveTranslationGroup(array $wpPost, array &$wpIdToGroupId): string
    {
        $wpId = $wpPost['id'];

        // If we already have a group for this WP ID, return it
        if (isset($wpIdToGroupId[$wpId])) {
            return $wpIdToGroupId[$wpId];
        }

        // Check if any sibling WP post already has a group
        if (isset($wpPost['translations']) && is_array($wpPost['translations'])) {
            foreach ($wpPost['translations'] as $lang => $siblingWpId) {
                if (isset($wpIdToGroupId[$siblingWpId])) {
                    // Re-use the existing group
                    $wpIdToGroupId[$wpId] = $wpIdToGroupId[$siblingWpId];
                    return $wpIdToGroupId[$siblingWpId];
                }
            }
        }

        // No existing group found — create a new UUID
        $newGroupId = (string) Str::uuid();
        $wpIdToGroupId[$wpId] = $newGroupId;

        // Also pre-register siblings so they share the same group
        if (isset($wpPost['translations']) && is_array($wpPost['translations'])) {
            foreach ($wpPost['translations'] as $lang => $siblingWpId) {
                $wpIdToGroupId[$siblingWpId] = $newGroupId;
            }
        }

        return $newGroupId;
    }

    /**
     * Fallback: import all posts as a single locale (no Polylang).
     */
    protected function importPostsSingleLocale(array $catMap, array $tagMap, string $locale = 'id'): void
    {
        $this->log("Fetching posts (single locale: {$locale})...");
        $page = 1;
        $importedCount = 0;

        while (true) {
            $response = $this->httpClient()->timeout(30)->get("{$this->baseUrl}/wp-json/wp/v2/posts", [
                'per_page' => 20,
                'page' => $page,
                '_embed' => 'wp:featuredmedia',
            ]);

            if (!$response->successful() || empty($response->json())) {
                break;
            }

            foreach ($response->json() as $wpPost) {
                // Check if already imported
                $existing = Post::where('wp_original_id', $wpPost['id'])->first();
                if ($existing) {
                    $this->log("Skipping existing post: {$wpPost['title']['rendered']}");
                    continue;
                }

                $this->log("Importing post: {$wpPost['title']['rendered']}");

                $contentHtml = $wpPost['content']['rendered'] ?? '';
                $excerptHtml = $wpPost['excerpt']['rendered'] ?? '';

                // Download embedded images in content and update URLs
                $contentHtml = $this->processContentImages($contentHtml);

                // Handle Featured Image
                $featuredImageId = null;
                if (!empty($wpPost['_embedded']['wp:featuredmedia'][0])) {
                    $mediaData = $wpPost['_embedded']['wp:featuredmedia'][0];
                    $imageUrl = $mediaData['source_url'] ?? null;
                    if ($imageUrl) {
                        $featuredImageId = $this->downloadAndCreateMedia($imageUrl, $mediaData['alt_text'] ?? '');
                    }
                }

                $post = Post::create([
                    'title' => html_entity_decode(strip_tags($wpPost['title']['rendered'])),
                    'slug' => $wpPost['slug'],
                    'excerpt' => Str::limit(html_entity_decode(strip_tags($excerptHtml)), 200),
                    'content' => Purifier::clean($contentHtml),
                    'status' => $wpPost['status'] === 'publish' ? 'published' : 'draft',
                    'published_at' => \Carbon\Carbon::parse($wpPost['date']),
                    'author_id' => $this->userId,
                    'featured_image_id' => $featuredImageId,
                    'wp_original_id' => $wpPost['id'],
                    'import_source' => $this->baseUrl,
                    'locale' => $locale,
                ]);

                // Attach Categories
                if (!empty($wpPost['categories'])) {
                    $localCatIds = array_filter(array_map(fn($id) => $catMap[$id] ?? null, $wpPost['categories']));
                    if ($localCatIds) {
                        $post->categories()->sync($localCatIds);
                    }
                }

                // Attach Tags
                if (!empty($wpPost['tags'])) {
                    $localTagIds = array_filter(array_map(fn($id) => $tagMap[$id] ?? null, $wpPost['tags']));
                    if ($localTagIds) {
                        $post->tags()->sync($localTagIds);
                    }
                }

                $importedCount++;
            }
            $page++;
        }

        $this->log("Imported {$importedCount} posts.");
    }

    // ─── Content Image Processing ────────────────────────────────────

    protected function processContentImages(string $html): string
    {
        if (empty($html)) return $html;

        // Use DOMDocument to parse HTML safely
        $dom = new DOMDocument();
        // Supress warnings from malformed HTML
        libxml_use_internal_errors(true);
        // Add meta tag to ensure UTF-8 encoding
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $images = $dom->getElementsByTagName('img');
        $changed = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');

            if ($src) {
                // Determine if it's an absolute URL
                if (!str_starts_with($src, 'http')) {
                    if (str_starts_with($src, '/')) {
                        $src = $this->baseUrl . $src;
                    } else {
                        continue;
                    }
                }

                $mediaId = $this->downloadAndCreateMedia($src, $alt);
                
                if ($mediaId) {
                    $media = Media::find($mediaId);
                    if ($media) {
                        $img->setAttribute('src', $media->url);
                        // Strip WordPress specific classes and srcset to keep it clean
                        $img->removeAttribute('srcset');
                        $img->removeAttribute('sizes');
                        $img->setAttribute('class', 'imported-image');
                        $changed = true;
                    }
                }
            }
        }

        if ($changed) {
            // Save HTML, stripping the XML encoding meta tag we added
            $newHtml = $dom->saveHTML();
            return str_replace('<?xml encoding="UTF-8">', '', $newHtml);
        }

        return $html;
    }

    // ─── Media Download ──────────────────────────────────────────────

    protected function downloadAndCreateMedia(string $url, string $altText = ''): ?int
    {
        try {
            // SSRF protection: block private/internal IP ranges
            $parsedUrl = parse_url($url);
            $host = $parsedUrl['host'] ?? '';
            // Block IPv6 loopback and IPv6 literal formats
            if (preg_match('/\[?::1\]?/', $host) || filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return null;
            }
            $ip = gethostbynamel($host);
            if ($ip) {
                $ip = $ip[0];
                // Block loopback
                if (str_starts_with($ip, '127.') || $ip === '::1' || $ip === 'localhost') return null;
                // Block private ranges (10.x, 172.16-31.x, 192.168.x)
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) return null;
            }

            $response = $this->httpClient()->timeout(15)->get($url);

            if (!$response->successful()) {
                return null;
            }

            $filename = basename(parse_url($url, PHP_URL_PATH));
            if (empty($filename)) {
                $filename = 'imported-' . Str::random(8) . '.jpg';
            }

            // Block dangerous extensions
            if (preg_match('/\.(php|phtml|phar|exe|sh|cgi|pl|py|rb|jar)$/i', $filename)) {
                return null;
            }

            // Clean filename
            $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $filename);
            if (empty($filename)) $filename = 'imported-' . Str::random(8) . '.jpg';

            $contentType = $response->header('Content-Type') ?? '';
            // Block executable content types
            $blockedTypes = ['application/x-sh', 'application/x-shockwave-flash', 'application/pdf', 'application/javascript'];
            foreach ($blockedTypes as $blocked) {
                if (stripos($contentType, $blocked) !== false) return null;
            }

            // Only allow safe image/video types
            $allowedMimePattern = '/^(image\/(jpeg|png|gif|webp)|video\/(mp4|webm))(\s|$|;)/i';
            if (!preg_match($allowedMimePattern, $contentType) && str_starts_with($contentType, 'image/')) {
                // Allow image/ prefix above, but reject svg+xml unless sanitized
                if (stripos($contentType, 'image/svg+xml') !== false) {
                    // SVG needs sanitization — skip for now
                    return null;
                }
            } elseif (!preg_match($allowedMimePattern, $contentType)) {
                return null;
            }

            $body = $response->body();

            // Prevent collisions
            $uniqueFilename = time() . '-' . $filename;
            $path = 'media/' . $uniqueFilename;

            Storage::disk('public')->put($path, $body);

            $size = strlen($body);
            $mimeType = $contentType ?: 'application/octet-stream';

            // Try to get dimensions if it's an image
            $width = null;
            $height = null;
            if (str_starts_with($mimeType, 'image/') && $mimeType !== 'image/svg+xml') {
                $info = @getimagesizefromstring($body);
                if ($info) {
                    $width = $info[0];
                    $height = $info[1];
                }
            }

            $media = Media::create([
                'filename' => $filename,
                'disk' => 'public',
                'path' => $path,
                'mime_type' => $mimeType,
                'size' => $size,
                'width' => $width,
                'height' => $height,
                'alt_text' => $altText,
                'uploaded_by' => $this->userId,
            ]);

            return $media->id;
        } catch (\Exception $e) {
            Log::warning("Failed to download image from {$url}: " . $e->getMessage());
            return null;
        }
    }
}
