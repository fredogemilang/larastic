<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Localization / Multi-language
    |--------------------------------------------------------------------------
    */
    'locales' => [
        'id' => ['name' => 'Indonesia', 'native' => 'Bahasa Indonesia', 'flag' => '🇮🇩'],
        'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇬🇧'],
    ],
    'default_locale' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel
    |--------------------------------------------------------------------------
    */
    'admin_prefix' => env('CMS_ADMIN_PREFIX', 'dns-ctrl'),

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    */
    'export' => [
        'output_path' => storage_path('app/exports'),
        'keep_last' => 5, // keep last N export ZIPs
    ],

    /*
    |--------------------------------------------------------------------------
    | Preview Settings
    |--------------------------------------------------------------------------
    */
    'preview' => [
        'output_path' => storage_path('app/previews'),
        'expiry_minutes' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | CSP Configuration
    |--------------------------------------------------------------------------
    */
    'csp' => [
        'mode' => env('CMS_CSP_MODE', 'warning'), // 'strict' or 'warning'

        'base_policy' => [
            'default-src' => "'self'",
            'script-src'  => "'self'",
            'style-src'   => "'self'",
            'img-src'     => "'self' data:",
            'font-src'    => "'self'",
            'connect-src' => "'self'",
            'object-src'  => "'none'",
            'base-uri'    => "'self'",
            'frame-ancestors' => "'none'",
        ],

        // Auto-merged when analytics are enabled
        'analytics_domains' => [
            'gtm' => [
                'script-src'  => ['*.googletagmanager.com'],
                'connect-src' => ['*.google-analytics.com', '*.analytics.google.com'],
                'img-src'     => ['*.google-analytics.com'],
            ],
            'ga' => [
                'script-src'  => ['*.googletagmanager.com', '*.google-analytics.com'],
                'connect-src' => ['*.google-analytics.com', '*.analytics.google.com'],
                'img-src'     => ['*.google-analytics.com'],
            ],
            'clarity' => [
                'script-src'  => ['*.clarity.ms'],
                'connect-src' => ['*.clarity.ms'],
            ],

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Media Settings
    |--------------------------------------------------------------------------
    */
    'media' => [
        'disk' => 'public',
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif', 'svg'],
        'max_upload_size' => 10240, // KB
        'responsive_sizes' => [
            'thumbnail' => ['width' => 300,  'height' => 300],
            'medium'    => ['width' => 768,  'height' => null],
            'large'     => ['width' => 1200, 'height' => null],
        ],
        'generate_webp' => true,
        'generate_avif' => false, // requires AVIF support
        'quality' => 85,
    ],

    /*
    |--------------------------------------------------------------------------
    | Blog Settings
    |--------------------------------------------------------------------------
    */
    'blog' => [
        'url_prefix' => 'blog',
        'posts_per_page' => 12,
    ],
];
