<?php

namespace App\Services;

use App\Models\Export;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use voku\helper\HtmlMin;
use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;

class ExportBuilder
{
    protected StaticRenderer $renderer;
    protected CspValidator $cspValidator;
    protected string $buildPath;
    protected array $errors = [];

    public function __construct(StaticRenderer $renderer, CspValidator $cspValidator)
    {
        $this->renderer = $renderer;
        $this->cspValidator = $cspValidator;
    }

    /**
     * Run the full export pipeline.
     */
    public function build(Export $export): Export
    {
        $export->update(['status' => 'processing', 'started_at' => now()]);

        try {
            // Increase limits for export process (shared hosting safety)
            @set_time_limit(600);
            @ini_set('memory_limit', '512M');

            Log::info("Export #{$export->id} started (type: {$export->type})");

            // Pre-flight checks
            if (!class_exists(\ZipArchive::class)) {
                throw new \Exception('PHP zip extension is not installed or enabled. Please enable ext-zip in php.ini.');
            }

            $exportsDir = storage_path('app/exports');
            if (!File::isDirectory($exportsDir)) {
                File::makeDirectory($exportsDir, 0755, true);
            }
            if (!is_writable($exportsDir)) {
                throw new \Exception("Storage directory is not writable: {$exportsDir}. Check file permissions (chmod 755 or 775).");
            }

            // 1. Create temp build directory
            $this->buildPath = storage_path('app/exports/build_' . $export->id);
            File::ensureDirectoryExists($this->buildPath);

            if (!File::isDirectory($this->buildPath)) {
                throw new \Exception("Failed to create build directory: {$this->buildPath}. Check disk space and permissions.");
            }

            // 2. Render all content
            $manifest = $this->renderer->renderAll(includeDrafts: false);

            // 3. Run CSP validation
            $cspReport = $this->cspValidator->validateAll($manifest);
            $export->update(['csp_report' => $cspReport]);

            if ($this->cspValidator->shouldAbort($cspReport)) {
                throw new \Exception("CSP validation failed in strict mode. {$cspReport['total_violations']} violation(s) found.");
            }

            // 4. Write HTML files to build directory
            $appUrl = rtrim(config('app.url', 'http://localhost'), '/');
            $siteUrl = rtrim(Setting::get('site_url', $appUrl), '/');
            
            Log::info("Export URL rewriting: APP_URL={$appUrl} → Site URL={$siteUrl}");
            
            // Initialize HTML Minifier
            $htmlMin = new HtmlMin();
            
            foreach ($manifest as $item) {
                // Block path traversal attempts
                if (str_contains($item['url'], '..')) {
                    $this->errors[] = "Skipped unsafe URL: {$item['url']}";
                    continue;
                }

                $filePath = $this->buildPath . $item['url'];

                // Verify resolved path stays within build directory
                File::ensureDirectoryExists(dirname($filePath));
                $realFilePath = realpath(dirname($filePath));
                $realBuildPath = realpath($this->buildPath);
                if ($realFilePath && $realBuildPath && !str_starts_with($realFilePath, $realBuildPath)) {
                    $this->errors[] = "Path traversal blocked: {$item['url']}";
                    continue;
                }
                
                $html = $item['html'];
                
                // Rewrite all CMS domain URLs to production site URL
                $html = $this->rewriteUrls($html, $appUrl, $siteUrl);
                
                // Minify HTML if it's an HTML file
                if (str_ends_with($filePath, '.html')) {
                    $html = $htmlMin->minify($html);
                }
                
                File::put($filePath, $html);
            }

            // 5. Copy public assets (CSS, JS, images)
            $this->copyAssets();

            // 6. Copy media files
            $this->copyMedia();

            // 7. (Analytics loader is now inline in base.blade.php)

            // 8. Create ZIP
            $zipPath = $this->createZip($export);

            // 9. Cleanup build directory
            File::deleteDirectory($this->buildPath);

            // 10. Update export record
            $export->update([
                'status' => 'completed',
                'output_path' => $zipPath,
                'file_size' => Storage::size($zipPath),
                'completed_at' => now(),
            ]);

            // Cleanup old exports
            $this->cleanupOldExports();

        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();
            Log::error("Export #{$export->id} failed: {$e->getMessage()}", [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            $export->update([
                'status' => 'failed',
                'errors' => $this->errors,
                'completed_at' => now(),
            ]);

            // Cleanup on failure
            if (isset($this->buildPath) && File::exists($this->buildPath)) {
                File::deleteDirectory($this->buildPath);
            }
        }

        return $export->fresh();
    }

    /**
     * Run a partial export pipeline.
     * Extracts previous export ZIP as base, then overwrites only changed items.
     */
    public function buildPartial(Export $export, array $changedItems): Export
    {
        $export->update(['status' => 'processing', 'started_at' => now()]);

        try {
            // Increase limits for export process (shared hosting safety)
            @set_time_limit(600);
            @ini_set('memory_limit', '512M');

            Log::info("Partial Export #{$export->id} started");
            // 1. Find the last successful export to use as base
            $baseExport = Export::where('status', 'completed')
                ->whereNotNull('output_path')
                ->latest('completed_at')
                ->first();

            if (!$baseExport || !Storage::exists($baseExport->output_path)) {
                throw new \Exception('No previous export found to base partial export on. Run a full export first.');
            }

            $export->update(['based_on_export_id' => $baseExport->id]);

            // 2. Create temp build directory and extract base ZIP
            $this->buildPath = storage_path('app/exports/build_' . $export->id);
            File::ensureDirectoryExists($this->buildPath);
            $this->extractFromZip(Storage::path($baseExport->output_path), $this->buildPath);

            // 3. Render only the changed items
            $renderedItems = $this->renderSpecificItems($changedItems);

            // 4. Run CSP validation on changed items only
            $cspReport = $this->cspValidator->validateAll($renderedItems);
            $export->update(['csp_report' => $cspReport]);

            if ($this->cspValidator->shouldAbort($cspReport)) {
                throw new \Exception("CSP validation failed in strict mode. {$cspReport['total_violations']} violation(s) found.");
            }

            // 5. Overwrite changed HTML files in build directory
            $appUrl = rtrim(config('app.url', 'http://localhost'), '/');
            $siteUrl = rtrim(Setting::get('site_url', $appUrl), '/');
            $htmlMin = new HtmlMin();

            foreach ($renderedItems as $item) {
                if (str_contains($item['url'], '..')) {
                    $this->errors[] = "Skipped unsafe URL: {$item['url']}";
                    continue;
                }

                $filePath = $this->buildPath . $item['url'];
                File::ensureDirectoryExists(dirname($filePath));

                $realFilePath = realpath(dirname($filePath));
                $realBuildPath = realpath($this->buildPath);
                if ($realFilePath && $realBuildPath && !str_starts_with($realFilePath, $realBuildPath)) {
                    $this->errors[] = "Path traversal blocked: {$item['url']}";
                    continue;
                }

                $html = $item['html'];
                
                // Rewrite all CMS domain URLs to production site URL
                $html = $this->rewriteUrls($html, $appUrl, $siteUrl);

                if (str_ends_with($filePath, '.html')) {
                    $html = $htmlMin->minify($html);
                }

                File::put($filePath, $html);
            }

            // 6. Copy all media (always full copy for simplicity)
            $this->copyMedia();

            // 7. Create ZIP
            $zipPath = $this->createZip($export);

            // 8. Cleanup build directory
            File::deleteDirectory($this->buildPath);

            // 9. Update export record
            $export->update([
                'status' => 'completed',
                'output_path' => $zipPath,
                'file_size' => Storage::size($zipPath),
                'completed_at' => now(),
                'scope_details' => [
                    'mode' => 'partial',
                    'items_count' => count($changedItems),
                    'items' => collect($changedItems)->map(fn ($item) => [
                        'type' => $item['type'],
                        'id' => $item['id'],
                        'title' => $item['title'] ?? '',
                    ])->toArray(),
                    'based_on_export' => $baseExport->id,
                ],
            ]);

            $this->cleanupOldExports();

        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();
            Log::error("Partial Export #{$export->id} failed: {$e->getMessage()}", [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            $export->update([
                'status' => 'failed',
                'errors' => $this->errors,
                'completed_at' => now(),
            ]);

            if (isset($this->buildPath) && File::exists($this->buildPath)) {
                File::deleteDirectory($this->buildPath);
            }
        }

        return $export->fresh();
    }

    /**
     * Render specific changed items (posts/pages) + always regenerate sitemap and RSS.
     */
    protected function renderSpecificItems(array $changedItems): array
    {
        $manifest = [];
        $blogPrefix = config('static-cms.blog.url_prefix', 'blog');

        foreach ($changedItems as $item) {
            if ($item['model_type'] === 'App\\Models\\Post') {
                $post = Post::with(['author', 'categories', 'tags', 'featuredImage'])->find($item['id']);
                if (!$post || !$post->isPublished()) {
                    continue;
                }

                $availableLocales = [$post->locale];
                if ($post->translation_group_id) {
                    $availableLocales = Post::where('translation_group_id', $post->translation_group_id)
                        ->where('status', 'published')
                        ->pluck('locale')->unique()->values()->toArray();
                }

                $currentUrl = '/' . ltrim($post->url, '/');
                $idUrl = null;
                $enUrl = null;
                if ($post->locale === 'id') {
                    $idUrl = $currentUrl;
                    $enPost = $post->translation('en');
                    if ($enPost && $enPost->status === 'published') {
                        $enUrl = '/' . ltrim($enPost->url, '/');
                    }
                } else {
                    $enUrl = $currentUrl;
                    $idPost = $post->translation('id');
                    if ($idPost && $idPost->status === 'published') {
                        $idUrl = '/' . ltrim($idPost->url, '/');
                    }
                }

                $html = view('static.blog.show', [
                    'post' => $post,
                    'locale' => $post->locale,
                    'localePrefix' => $post->locale === 'en' ? '/en' : '',
                    'availableLocales' => $availableLocales,
                    'siteName' => Setting::get('site_name', config('app.name')),
                    'currentUrl' => $currentUrl,
                    'idUrl' => $idUrl,
                    'enUrl' => $enUrl,
                ])->render();

                $urlPrefix = $post->locale === 'en' ? '/en' : '';
                $url = "{$urlPrefix}/{$blogPrefix}/{$post->slug}/index.html";
                $url = '/' . ltrim(preg_replace('#/+#', '/', $url), '/');

                $manifest[] = ['url' => $url, 'html' => $html, 'type' => 'post'];

            } elseif ($item['model_type'] === 'App\\Models\\Page') {
                $page = Page::find($item['id']);
                if (!$page || $page->status !== 'published') {
                    continue;
                }

                $templatePath = "static.pages.{$page->template}";
                if (!\Illuminate\Support\Facades\View::exists($templatePath)) {
                    $templatePath = 'static.pages.default';
                }

                $availableLocales = [$page->locale];
                if ($page->translation_group_id) {
                    $availableLocales = Page::where('translation_group_id', $page->translation_group_id)
                        ->where('status', 'published')
                        ->pluck('locale')->unique()->values()->toArray();
                }

                $currentUrl = '/' . ltrim($page->url, '/');
                if ($currentUrl === '') $currentUrl = '/';

                $idUrl = null;
                $enUrl = null;
                if ($page->locale === 'id') {
                    $idUrl = $currentUrl;
                    $enPage = $page->translation('en');
                    if ($enPage && $enPage->status === 'published') {
                        $enUrl = '/' . ltrim($enPage->url, '/');
                    }
                } else {
                    $enUrl = $currentUrl;
                    $idPage = $page->translation('id');
                    if ($idPage && $idPage->status === 'published') {
                        $idUrl = '/' . ltrim($idPage->url, '/');
                    }
                }

                $html = view($templatePath, [
                    'page' => $page,
                    'locale' => $page->locale,
                    'localePrefix' => $page->locale === 'en' ? '/en' : '',
                    'availableLocales' => $availableLocales,
                    'siteName' => Setting::get('site_name', config('app.name')),
                    'siteTagline' => Setting::get('site_tagline', ''),
                    'currentUrl' => $currentUrl,
                    'idUrl' => $idUrl,
                    'enUrl' => $enUrl,
                ])->render();

                $urlPrefix = $page->locale === 'en' ? '/en' : '';
                $url = $page->slug === 'home'
                    ? "{$urlPrefix}/index.html"
                    : "{$urlPrefix}/{$page->slug}/index.html";
                $url = '/' . ltrim(preg_replace('#/+#', '/', $url), '/');

                $manifest[] = ['url' => $url, 'html' => $html, 'type' => 'page'];
            }
        }

        // Always regenerate sitemap and RSS for partial exports
        $pages = Page::where('status', 'published')->get();
        $posts = Post::published()->get();
        $siteUrl = rtrim(Setting::get('site_url', config('app.url', 'https://example.com')), '/');

        $sitemapHtml = view('static.sitemap', [
            'pages' => $pages,
            'posts' => $posts,
            'siteUrl' => $siteUrl,
            'blogPrefix' => $blogPrefix,
        ])->render();
        $manifest[] = ['url' => '/sitemap.xml', 'html' => $sitemapHtml, 'type' => 'xml'];

        $robotsContent = "User-agent: *\nAllow: /\nSitemap: {$siteUrl}/sitemap.xml\n";
        $manifest[] = ['url' => '/robots.txt', 'html' => $robotsContent, 'type' => 'txt'];

        $rssPosts = Post::published()->with('author')->orderByDesc('published_at')->take(20)->get();
        $rssHtml = view('static.rss', [
            'posts' => $rssPosts,
            'siteName' => Setting::get('site_name', config('app.name')),
            'siteUrl' => $siteUrl,
            'blogPrefix' => $blogPrefix,
        ])->render();
        $manifest[] = ['url' => '/rss.xml', 'html' => $rssHtml, 'type' => 'xml'];

        return $manifest;
    }

    /**
     * Extract a ZIP file into a directory.
     */
    protected function extractFromZip(string $zipPath, string $destination): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Cannot open base export ZIP for partial export.');
        }
        $zip->extractTo($destination);
        $zip->close();
    }

    protected function copyAssets(): void
    {
        // Copy and Minify theme CSS
        $themeCssSource = resource_path('views/static/assets/theme.css');
        $themeCssDest = $this->buildPath . '/assets/css/theme.css';
        File::ensureDirectoryExists(dirname($themeCssDest));
        if (File::exists($themeCssSource)) {
            $cssMinifier = new CSS($themeCssSource);
            $cssMinifier->minify($themeCssDest);
        }

        // Copy and Minify main.js
        $mainJsSource = resource_path('views/static/assets/main.js');
        $jsDir = $this->buildPath . '/assets/js';
        File::ensureDirectoryExists($jsDir);
        if (File::exists($mainJsSource)) {
            $jsMinifier = new JS($mainJsSource);
            $jsMinifier->minify($jsDir . '/main.js');
        }

        // No longer generating analytics-loader.js as it is now inline

        // Copy template images
        $imgSource = resource_path('views/static/assets/img');
        if (File::exists($imgSource)) {
            File::copyDirectory($imgSource, $this->buildPath . '/assets/img');
        }
    }

    /**
     * Rewrite all CMS domain URLs in HTML to production site URLs.
     *
     * Converts storage paths to /assets/media/ and replaces the CMS
     * server domain (APP_URL) with the production Site URL from settings.
     * Also handles URL-encoded variants (for social share links).
     */
    protected function rewriteUrls(string $html, string $appUrl, string $siteUrl): string
    {
        // Build alternate protocol URL (http↔https)
        $altUrl = str_starts_with($appUrl, 'https://') 
            ? str_replace('https://', 'http://', $appUrl) 
            : str_replace('http://', 'https://', $appUrl);
        
        // Protocol-relative variant (//domain.com)
        $domainOnly = preg_replace('#^https?:#', '', $appUrl);
        
        // --- Phase 1: Rewrite /storage/ paths to /assets/media/ ---
        
        // Full domain + /storage/media/
        $html = str_replace($appUrl . '/storage/media/', $siteUrl . '/assets/media/', $html);
        $html = str_replace($altUrl . '/storage/media/', $siteUrl . '/assets/media/', $html);
        $html = str_replace($domainOnly . '/storage/media/', $siteUrl . '/assets/media/', $html);
        
        // Root-relative /storage/media/
        $html = str_replace('/storage/media/', '/assets/media/', $html);
        
        // Full domain + /storage/ (non-media: videos, docs, etc.)
        $html = str_replace($appUrl . '/storage/', $siteUrl . '/assets/media/', $html);
        $html = str_replace($altUrl . '/storage/', $siteUrl . '/assets/media/', $html);
        $html = str_replace($domainOnly . '/storage/', $siteUrl . '/assets/media/', $html);
        
        // Remaining root-relative /storage/ paths
        $html = str_replace('/storage/', '/assets/media/', $html);
        
        // --- Phase 2: Replace CMS domain with production Site URL ---
        // This catches og:url, canonical, share links, any hardcoded CMS URLs
        $html = str_replace($appUrl, $siteUrl, $html);
        $html = str_replace($altUrl, $siteUrl, $html);
        $html = str_replace($domainOnly, preg_replace('#^https?:#', '', $siteUrl), $html);
        
        // --- Phase 3: Handle URL-encoded variants (share button URLs) ---
        // e.g. https%3A%2F%2Fpost.ctizen.id%2F... → https%3A%2F%2Fdefenxor.com%2F...
        $html = str_replace(urlencode($appUrl), urlencode($siteUrl), $html);
        $html = str_replace(urlencode($altUrl), urlencode($siteUrl), $html);
        
        return $html;
    }

    protected function copyMedia(): void
    {
        // Copy main media directory
        $mediaPath = storage_path('app/public/media');
        if (File::exists($mediaPath)) {
            File::copyDirectory($mediaPath, $this->buildPath . '/assets/media');
        }
        
        // Also copy other public storage files (videos, documents, etc.)
        // that may be referenced via /storage/ paths in content
        $publicStoragePath = storage_path('app/public');
        if (File::exists($publicStoragePath)) {
            foreach (File::directories($publicStoragePath) as $dir) {
                $dirName = basename($dir);
                if ($dirName === 'media') {
                    continue; // Already copied above
                }
                $destPath = $this->buildPath . '/assets/media/' . $dirName;
                if (!File::exists($destPath)) {
                    File::copyDirectory($dir, $destPath);
                }
            }
            
            // Copy any files directly in public storage root
            foreach (File::files($publicStoragePath) as $file) {
                $destFile = $this->buildPath . '/assets/media/' . $file->getFilename();
                if (!File::exists($destFile)) {
                    File::copy($file->getPathname(), $destFile);
                }
            }
        }
    }



    protected function createZip(Export $export): string
    {
        $zipDir = 'exports';
        Storage::makeDirectory($zipDir);

        $zipFilename = "export_{$export->id}_" . date('Ymd_His') . '.zip';
        $relativePath = "{$zipDir}/{$zipFilename}";
        $zipPath = Storage::path($relativePath);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \Exception('Cannot create ZIP file.');
        }

        $this->addDirectoryToZip($zip, $this->buildPath, '');
        $zip->close();

        return $relativePath;
    }

    protected function addDirectoryToZip(ZipArchive $zip, string $directory, string $prefix): void
    {
        $files = File::allFiles($directory);
        foreach ($files as $file) {
            $relativePath = $prefix ? $prefix . '/' . $file->getRelativePathname() : $file->getRelativePathname();
            $zip->addFile($file->getRealPath(), str_replace('\\', '/', $relativePath));
        }
    }

    protected function cleanupOldExports(): void
    {
        $keepLast = config('static-cms.export.keep_last', 5);

        // Cleanup old full exports
        $oldFullExports = Export::where('type', 'full')
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->skip($keepLast)
            ->take(100)
            ->get();

        foreach ($oldFullExports as $old) {
            if ($old->output_path && Storage::exists($old->output_path)) {
                Storage::delete($old->output_path);
            }
            $old->delete();
        }

        // Cleanup old partial exports (keep fewer since they are incremental)
        $keepPartial = max(2, $keepLast - 2);
        $oldPartialExports = Export::where('type', 'partial')
            ->where('status', 'completed')
            ->orderByDesc('completed_at')
            ->skip($keepPartial)
            ->take(100)
            ->get();

        foreach ($oldPartialExports as $old) {
            if ($old->output_path && Storage::exists($old->output_path)) {
                Storage::delete($old->output_path);
            }
            $old->delete();
        }
    }
}
