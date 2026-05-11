<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PreviewController extends Controller
{
    /**
     * Resolve the current locale from the URL prefix.
     */
    protected function resolveLocale(): string
    {
        $prefix = request()->segment(1);
        $configuredLocales = array_keys(config('static-cms.locales', ['id' => [], 'en' => []]));
        $defaultLocale = config('static-cms.default_locale', 'id');

        if ($prefix && in_array($prefix, $configuredLocales) && $prefix !== $defaultLocale) {
            return $prefix;
        }

        return $defaultLocale;
    }

    /**
     * Get the URL prefix for the current locale.
     */
    protected function localePrefix(): string
    {
        $locale = $this->resolveLocale();
        $default = config('static-cms.default_locale', 'id');
        return $locale === $default ? '' : '/' . $locale;
    }

    public function home()
    {
        $locale = $this->resolveLocale();
        $defaultLocale = config('static-cms.default_locale', 'id');

        // Try exact slug+locale match first
        $page = Page::where('slug', 'home')->where('locale', $locale)->first();

        if (!$page) {
            // Find home page in default locale, then get its translation
            $defaultPage = Page::where('slug', 'home')->where('locale', $defaultLocale)->first()
                ?? Page::where('slug', 'home')->first();

            if ($defaultPage && $locale !== $defaultPage->locale) {
                $translated = $defaultPage->translation($locale);
                $page = $translated ?? $defaultPage; // fallback to default if no translation
            } else {
                $page = $defaultPage;
            }
        }

        if ($page) {
            return $this->renderPage($page, $locale);
        }
        
        return redirect()->route('login');
    }

    public function page($slug)
    {
        $locale = $this->resolveLocale();
        $defaultLocale = config('static-cms.default_locale', 'id');

        // 1. Try exact slug + locale match
        $page = Page::where('slug', $slug)->where('locale', $locale)->first();

        if (!$page) {
            // 2. Find page by slug in any locale, then resolve translation
            $anyPage = Page::where('slug', $slug)->first();

            if ($anyPage) {
                if ($locale !== $anyPage->locale) {
                    // Get translation for requested locale
                    $translated = $anyPage->translation($locale);
                    $page = $translated ?? $anyPage; // fallback to original
                } else {
                    $page = $anyPage;
                }
            }
        }

        if (!$page) {
            abort(404);
        }

        return $this->renderPage($page, $locale);
    }

    protected function renderPage(Page $page, string $locale)
    {
        $templatePath = "static.pages.{$page->template}";
        if (!view()->exists($templatePath)) {
            $templatePath = 'static.pages.default';
        }

        // Determine which locale translations are available
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

        return view($templatePath, [
            'page' => $page,
            'locale' => $locale,
            'localePrefix' => $this->localePrefix(),
            'availableLocales' => $availableLocales,
            'siteName' => Setting::get('site_name', config('app.name')),
            'siteTagline' => Setting::get('site_tagline', ''),
            'idUrl' => $idUrl,
            'enUrl' => $enUrl,
        ]);
    }

    public function blogIndex($page = null)
    {
        $locale = $this->resolveLocale();

        if ($page) {
            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($page) {
                return (int) $page;
            });
        }

        $posts = Post::published()
            ->locale($locale)
            ->with(['author', 'categories', 'featuredImage'])
            ->orderByDesc('published_at')
            ->paginate(9);

        // Blog index always supports both languages, even if empty
        $availableLocales = ['id', 'en'];

        return view('static.blog.index', [
            'posts' => $posts,
            'locale' => $locale,
            'localePrefix' => $this->localePrefix(),
            'availableLocales' => $availableLocales,
            'siteName' => Setting::get('site_name', config('app.name')),
        ]);
    }

    public function blogPost($slug)
    {
        $locale = $this->resolveLocale();

        // Try locale-specific post first
        $post = Post::where('slug', $slug)
            ->where('locale', $locale)
            ->with(['author', 'categories', 'tags', 'featuredImage'])
            ->first();

        // Fallback to any post with that slug
        if (!$post) {
            $post = Post::where('slug', $slug)
                ->with(['author', 'categories', 'tags', 'featuredImage'])
                ->firstOrFail();
        }

        // Determine available locales for this post
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

        return view('static.blog.show', [
            'post' => $post,
            'locale' => $locale,
            'localePrefix' => $this->localePrefix(),
            'availableLocales' => $availableLocales,
            'siteName' => Setting::get('site_name', config('app.name')),
            'idUrl' => $idUrl,
            'enUrl' => $enUrl,
        ]);
    }

    public function assetCss($file)
    {
        $allowed = ['theme.css'];
        if (in_array($file, $allowed)) {
            $path = resource_path("views/static/assets/{$file}");
            if (File::exists($path)) {
                return response()->file($path, ['Content-Type' => 'text/css']);
            }
        }
        abort(404);
    }

    public function assetJs($file)
    {
        $allowed = ['main.js', 'analytics-loader.js'];
        if (in_array($file, $allowed)) {
            if ($file === 'analytics-loader.js') {
                return response("// Development Preview: Analytics Disabled\n", 200, ['Content-Type' => 'application/javascript']);
            }
            $path = resource_path("views/static/assets/{$file}");
            if (File::exists($path)) {
                return response()->file($path, ['Content-Type' => 'application/javascript']);
            }
        }
        abort(404);
    }

    public function assetImg($path)
    {
        // Sanitize: remove directory traversal attempts
        $path = str_replace(['../', '..\\'], '', $path);
        $basePath = resource_path('views/static/assets/img');
        $filePath = realpath($basePath . DIRECTORY_SEPARATOR . $path);

        // Ensure resolved path is within base directory
        if (!$filePath || !str_starts_with($filePath, realpath($basePath))) {
            abort(404);
        }

        if (File::exists($filePath)) {
            $mime = File::mimeType($filePath);
            return response()->file($filePath, ['Content-Type' => $mime]);
        }
        abort(404);
    }

    public function assetMediaCatchAll($path)
    {
        // Sanitize: remove directory traversal attempts
        $path = str_replace(['../', '..\\'], '', $path);
        $basePath = storage_path('app/public/media');
        $filePath = realpath($basePath . DIRECTORY_SEPARATOR . $path);

        // Ensure resolved path is within base directory
        if (!$filePath || !str_starts_with($filePath, realpath($basePath))) {
            abort(404);
        }

        if (File::exists($filePath)) {
            $mime = File::mimeType($filePath);
            return response()->file($filePath, ['Content-Type' => $mime]);
        }
        abort(404);
    }
}
