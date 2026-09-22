<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use MatthiasMullie\Minify\CSS;

/**
 * Static theme assets (CSS, JS, fonts, images) shipped with every export.
 */
class ThemeAssets
{
    protected static ?string $inlineCss = null;

    /**
     * Absolute path to a file inside resources/views/static/assets.
     */
    public static function path(string $relative = ''): string
    {
        $base = resource_path('views/static/assets');

        return $relative === '' ? $base : $base . '/' . ltrim($relative, '/');
    }

    /**
     * Minified theme CSS, ready to be inlined into <style id="theme-css">.
     *
     * Trimmed so the bytes are identical before and after HTML minification
     * (HtmlMin strips whitespace around element content) — the CSP style-src
     * hash in firebase.json is computed from this exact string.
     */
    public static function inlineCss(): string
    {
        if (self::$inlineCss === null) {
            $source = self::path('theme.css');
            self::$inlineCss = File::exists($source) ? trim((new CSS($source))->minify()) : '';
        }

        return self::$inlineCss;
    }

    /**
     * CSP hash source for the inlined theme CSS.
     */
    public static function inlineCssHash(): string
    {
        return 'sha256-' . base64_encode(hash('sha256', self::inlineCss(), true));
    }

    /**
     * Most recent modification time (unix) across all theme files.
     */
    public static function lastModified(): int
    {
        $latest = 0;
        foreach (File::allFiles(self::path()) as $file) {
            $latest = max($latest, $file->getMTime());
        }

        return $latest;
    }

    /**
     * Replace the content of the <style id="theme-css"> placeholder with the
     * current theme CSS. Safe to run on already-filled pages (partial exports).
     */
    public static function injectInlineCss(string $html): string
    {
        $css = self::inlineCss();

        // Callback keeps the CSS literal — preg_replace would interpret the
        // "\:" escapes in Tailwind class names as backreferences.
        return preg_replace_callback(
            '/<style id="?theme-css"?>.*?<\/style>/s',
            fn () => '<style id=theme-css>' . $css . '</style>',
            $html
        );
    }
}
