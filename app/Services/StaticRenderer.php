<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class StaticRenderer
{
    protected array $manifest = [];

    /**
     * Render all publishable content into HTML strings.
     * Returns array of ['url' => string, 'html' => string, 'type' => string]
     */
    public function renderAll(bool $includeDrafts = false): array
    {
        $this->manifest = [];

        $this->renderPages($includeDrafts);
        $this->renderBlogIndex();
        $this->renderPosts($includeDrafts);
        $this->renderSitemap();
        $this->renderRobots();
        $this->renderRssFeed();

        return $this->manifest;
    }

    protected function renderPages(bool $includeDrafts): void
    {
        $query = Page::query();
        if (!$includeDrafts) {
            $query->where('status', 'published');
        }

        foreach ($query->orderBy('sort_order')->get() as $page) {
            $templatePath = "static.pages.{$page->template}";
            if (!View::exists($templatePath)) {
                $templatePath = 'static.pages.default';
            }

            // Determine available locales for language switcher
            $availableLocales = [$page->locale];
            if ($page->translation_group_id) {
                $availableLocales = Page::where('translation_group_id', $page->translation_group_id)
                    ->where('status', 'published')
                    ->pluck('locale')
                    ->unique()
                    ->values()
                    ->toArray();
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

            $html = $this->renderView($templatePath, [
                'page' => $page,
                'locale' => $page->locale,
                'localePrefix' => $page->locale === 'en' ? '/en' : '',
                'availableLocales' => $availableLocales,
                'siteName' => Setting::get('site_name', config('app.name')),
                'siteTagline' => Setting::get('site_tagline', ''),
                'currentUrl' => $currentUrl,
                'idUrl' => $idUrl,
                'enUrl' => $enUrl,
            ]);

            $urlPrefix = $page->locale === 'en' ? '/en' : '';
            // If the slug is 'home', we put it at the root of the locale
            $url = $page->slug === 'home' 
                ? "{$urlPrefix}/index.html" 
                : "{$urlPrefix}/{$page->slug}/index.html";
                
            // Clean up double slashes just in case
            $url = '/' . ltrim(preg_replace('#/+#', '/', $url), '/');

            $this->manifest[] = [
                'url' => $url,
                'html' => $html,
                'type' => 'page',
            ];
        }
    }

    protected function renderBlogIndex(): void
    {
        $locales = array_keys(config('static-cms.locales', ['id' => [], 'en' => []]));
        $prefix = config('static-cms.blog.url_prefix', 'blog');

        foreach ($locales as $locale) {
            $query = Post::published()
                ->locale($locale)
                ->with(['author', 'categories', 'featuredImage'])
                ->orderByDesc('published_at');

            // Find how many pages we need to render
            $paginator = $query->paginate(9);
            $lastPage = max(1, $paginator->lastPage());
            $availableLocales = ['id', 'en'];

            for ($page = 1; $page <= $lastPage; $page++) {
                \Illuminate\Pagination\Paginator::currentPageResolver(fn () => $page);
                $posts = $query->clone()->paginate(9);

                $urlPrefix = $locale === 'en' ? '/en' : '';
                $currentUrl = $page === 1 
                    ? "{$urlPrefix}/{$prefix}" 
                    : "{$urlPrefix}/{$prefix}/page/{$page}";
                if ($currentUrl === '') $currentUrl = '/';

                $html = $this->renderView('static.blog.index', [
                    'posts' => $posts,
                    'locale' => $locale,
                    'localePrefix' => $locale === 'en' ? '/en' : '',
                    'availableLocales' => $availableLocales,
                    'siteName' => Setting::get('site_name', config('app.name')),
                    'currentUrl' => $currentUrl,
                ]);

                $urlPrefix = $locale === 'en' ? '/en' : '';
                $url = $page === 1 
                    ? "{$urlPrefix}/{$prefix}/index.html" 
                    : "{$urlPrefix}/{$prefix}/page/{$page}/index.html";

                // Clean up double slashes
                $url = '/' . ltrim(preg_replace('#/+#', '/', $url), '/');

                $this->manifest[] = [
                    'url' => $url,
                    'html' => $html,
                    'type' => 'blog-index',
                ];
            }
        }
        
        // Reset paginator resolver so it doesn't affect other things
        \Illuminate\Pagination\Paginator::currentPageResolver(fn () => 1);
    }

    protected function renderPosts(bool $includeDrafts): void
    {
        $query = Post::with(['author', 'categories', 'tags', 'featuredImage']);
        if (!$includeDrafts) {
            $query->published();
        }

        $prefix = config('static-cms.blog.url_prefix', 'blog');

        foreach ($query->orderByDesc('published_at')->get() as $post) {
            
            $availableLocales = [$post->locale];
            if ($post->translation_group_id) {
                $availableLocales = Post::where('translation_group_id', $post->translation_group_id)
                    ->where('status', 'published')
                    ->pluck('locale')
                    ->unique()
                    ->values()
                    ->toArray();
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

            $html = $this->renderView('static.blog.show', [
                'post' => $post,
                'locale' => $post->locale,
                'localePrefix' => $post->locale === 'en' ? '/en' : '',
                'availableLocales' => $availableLocales,
                'siteName' => Setting::get('site_name', config('app.name')),
                'currentUrl' => $currentUrl,
                'idUrl' => $idUrl,
                'enUrl' => $enUrl,
            ]);

            $urlPrefix = $post->locale === 'en' ? '/en' : '';
            $url = "{$urlPrefix}/{$prefix}/{$post->slug}/index.html";
            $url = '/' . ltrim(preg_replace('#/+#', '/', $url), '/');

            $this->manifest[] = [
                'url' => $url,
                'html' => $html,
                'type' => 'post',
            ];
        }
    }

    protected function renderSitemap(): void
    {
        $pages = Page::where('status', 'published')->get();
        $posts = Post::published()->get();
        $siteUrl = rtrim(Setting::get('site_url', config('app.url', 'https://example.com')), '/');

        $html = $this->renderView('static.sitemap', [
            'pages' => $pages,
            'posts' => $posts,
            'siteUrl' => $siteUrl,
            'blogPrefix' => config('static-cms.blog.url_prefix', 'blog'),
        ]);

        $this->manifest[] = ['url' => '/sitemap.xml', 'html' => $html, 'type' => 'xml'];
    }

    protected function renderRobots(): void
    {
        $siteUrl = rtrim(Setting::get('site_url', config('app.url', 'https://example.com')), '/');
        $content = "User-agent: *\nAllow: /\nSitemap: {$siteUrl}/sitemap.xml\n";

        $this->manifest[] = ['url' => '/robots.txt', 'html' => $content, 'type' => 'txt'];
    }

    protected function renderRssFeed(): void
    {
        $posts = Post::published()
            ->with('author')
            ->orderByDesc('published_at')
            ->take(20)
            ->get();

        $html = $this->renderView('static.rss', [
            'posts' => $posts,
            'siteName' => Setting::get('site_name', config('app.name')),
            'siteUrl' => rtrim(Setting::get('site_url', config('app.url', 'https://example.com')), '/'),
            'blogPrefix' => config('static-cms.blog.url_prefix', 'blog'),
        ]);

        $this->manifest[] = ['url' => '/rss.xml', 'html' => $html, 'type' => 'xml'];
    }

    protected function renderView(string $view, array $data = []): string
    {
        return View::make($view, $data)->render();
    }
}
