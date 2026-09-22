<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\File;

/**
 * Builds firebase.json for the static site.
 *
 * Written to the root of every export (so the ZIP is deployable as-is with
 * `firebase deploy`) and regenerated in the GitHub deploy repo on each deploy.
 */
class FirebaseHostingConfig
{
    const CACHE_IMMUTABLE = 'public, max-age=31536000, immutable';
    const CACHE_SHORT = 'public, max-age=3600';
    const CACHE_REVALIDATE = 'no-cache';

    public static function build(): array
    {
        return [
            'hosting' => [
                'site' => Setting::get('firebase_site_id', 'defenxor-com'),
                'public' => '.',
                'ignore' => [
                    'firebase.json',
                    '**/.*',
                    '**/node_modules/**',
                    '**/.git/**',
                ],
                // Firebase applies every matching rule in order, so the
                // Cache-Control rules below are kept mutually exclusive.
                'headers' => [
                    // Security headers for all resources
                    [
                        'source' => '**',
                        'headers' => [
                            ['key' => 'X-Frame-Options', 'value' => 'SAMEORIGIN'],
                            ['key' => 'X-Content-Type-Options', 'value' => 'nosniff'],
                            ['key' => 'X-XSS-Protection', 'value' => '1; mode=block'],
                            ['key' => 'Referrer-Policy', 'value' => 'strict-origin-when-cross-origin'],
                            ['key' => 'Strict-Transport-Security', 'value' => 'max-age=63072000; includeSubDomains; preload'],
                            ['key' => 'Content-Security-Policy', 'value' => self::contentSecurityPolicy()],
                        ],
                    ],
                    // HTML pages: always revalidate so content updates propagate immediately.
                    // Pages are requested as clean URLs (/, /tentang-kami/, /blog/slug/) which
                    // never match "**/*.html", so any path without a file extension counts too.
                    [
                        'regex' => '^[^.?]*(\?|$)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_REVALIDATE],
                        ],
                    ],
                    [
                        'source' => '**/*.html',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_REVALIDATE],
                        ],
                    ],
                    // Images: long cache (1 year, immutable)
                    [
                        'source' => '**/*.@(webp|png|jpg|jpeg|gif|svg|ico|avif)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_IMMUTABLE],
                        ],
                    ],
                    // CSS & JS: long cache (1 year, immutable)
                    [
                        'source' => '**/*.@(css|js)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_IMMUTABLE],
                        ],
                    ],
                    // Fonts: long cache (1 year, immutable)
                    [
                        'source' => '**/*.@(woff|woff2|ttf|otf|eot)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_IMMUTABLE],
                        ],
                    ],
                    // Video, audio & documents from the media library: uploads get a
                    // random hashed filename, so the content behind a URL never changes.
                    [
                        'source' => '**/*.@(webm|mp4|m4v|ogv|ogg|mp3|m4a|wav|pdf)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_IMMUTABLE],
                        ],
                    ],
                    // XML/JSON/TXT sitemaps & manifests: short cache (1 hour)
                    [
                        'source' => '**/*.@(xml|json|txt)',
                        'headers' => [
                            ['key' => 'Cache-Control', 'value' => self::CACHE_SHORT],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * CSP with hashes for the two inline elements every page carries:
     * the analytics loader <script> and the inlined theme <style>.
     */
    public static function contentSecurityPolicy(): string
    {
        $scriptHash = AnalyticsService::getHash();
        $styleHash = ThemeAssets::inlineCssHash();

        return implode('; ', [
            "default-src 'self'",
            "script-src '{$scriptHash}' 'strict-dynamic' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://www.clarity.ms https://analytics.ahrefs.com https://static.cloudflareinsights.com https://challenges.cloudflare.com https://ajax.cloudflare.com https: http:",
            "style-src 'self' '{$styleHash}'",
            "font-src 'self' data:",
            "img-src 'self' data: https:",
            "connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com https://*.clarity.ms https://analytics.ahrefs.com https://cloudflareinsights.com",
            "object-src 'none'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
            "frame-src 'self' https://challenges.cloudflare.com",
            'upgrade-insecure-requests',
        ]) . ';';
    }

    /**
     * Write firebase.json into the given directory (export build root or deploy repo).
     */
    public static function write(string $directory): string
    {
        $path = rtrim($directory, '/\\') . '/firebase.json';

        File::put($path, json_encode(self::build(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        return $path;
    }
}
