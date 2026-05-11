@php
    $siteName = $siteName ?? config('app.name');
    $pageTitle = $seoTitle ?? ($title ?? 'Page');
    $finalTitle = str_contains($pageTitle, $siteName) ? $pageTitle : $pageTitle . ' — ' . $siteName;
    $metaDesc = $seoDescription ?? '';
    $canonical = $canonicalUrl ?? '';
    $footerText = str_replace('{year}', date('Y'), \App\Models\Setting::get('footer_text', '© {year} ' . $siteName));
    $socialLinks = [
        'facebook' => \App\Models\Setting::get('social_facebook'),
        'twitter' => \App\Models\Setting::get('social_twitter'),
        'instagram' => \App\Models\Setting::get('social_instagram'),
        'linkedin' => \App\Models\Setting::get('social_linkedin'),
        'youtube' => \App\Models\Setting::get('social_youtube'),
    ];
    $blogPrefix = config('static-cms.blog.url_prefix', 'blog');
    // Locale
    $locale = $locale ?? config('static-cms.default_locale', 'id');
    $localePrefix = $localePrefix ?? '';
    $defaultLocale = config('static-cms.default_locale', 'id');
    $otherLocale = $locale === 'id' ? 'en' : 'id';

    // The true web URL path, passed from StaticRenderer, fallback to request()->path()
    $currentUrl = $currentUrl ?? request()->path();
    // Normalize to not have leading slash for the logic below
    $currentUrlPath = ltrim($currentUrl, '/');

    // Available translations (passed from controller)
    $availableLocales = $availableLocales ?? [$locale];
    $hasEn = in_array('en', $availableLocales);
    $hasId = in_array('id', $availableLocales);
    $hasBothLocales = $hasEn && $hasId;

    // Build language switcher URLs (only used if translation exists)
    if (!isset($idUrl) || !isset($enUrl)) {
        if ($locale === 'en') {
            $idUrl = '/' . preg_replace('#^en/?#', '', $currentUrlPath);
            $enUrl = '/' . $currentUrlPath;
        } else {
            $idUrl = '/' . $currentUrlPath;
            $enUrl = '/en/' . ltrim($currentUrlPath, '/');
        }
    }
    $idUrl = $idUrl === '//' ? '/' : $idUrl;
    $enUrl = rtrim($enUrl, '/') . '/';
    if ($idUrl !== '/') $idUrl = rtrim($idUrl, '/') . '/';

    $currentPath = ltrim($currentUrl, '/');
    if ($currentPath === '') $currentPath = '/';

    // Identify pages by ID (1: Home, 5: DIMS, 6: DISC, 7: DISI)
    $menuKeys = [1, 5, 6, 7];
    $menuPages = \App\Models\Page::where('status', 'published')
        ->whereIn('id', $menuKeys)
        ->get(['id', 'title', 'slug', 'url'])
        ->sortBy(fn($p) => array_search($p->id, $menuKeys))
        ->values();

    // Menu labels based on locale
    $menuLabels = $locale === 'en'
        ? [1 => 'Home', 5 => 'DIMS', 6 => 'DISC', 7 => 'DISI']
        : [1 => 'Beranda', 5 => 'DIMS', 6 => 'DISC', 7 => 'DISI'];
        
    $assetVersion = time();
@endphp
<!DOCTYPE html>
<html lang="{{ $locale === 'en' ? 'en' : 'id' }}">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ $finalTitle }}</title>
    <link rel="icon" href="/assets/img/favicon.png?v={{ $assetVersion }}" type="image/png">
    <link rel="apple-touch-icon" href="/assets/img/favicon.png?v={{ $assetVersion }}">
    @if($metaDesc)
    <meta name="description" content="{{ $metaDesc }}">
    @endif
    @if($canonical)
    <link rel="canonical" href="{{ $canonical }}">
    @endif

    <!-- hreflang SEO — only output when both locales are available -->
    @if($hasBothLocales)
    <link rel="alternate" hreflang="id" href="{{ $idUrl }}" />
    <link rel="alternate" hreflang="en" href="{{ $enUrl }}" />
    <link rel="alternate" hreflang="x-default" href="{{ $idUrl }}" />
    @endif

    <!-- OpenGraph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ str_starts_with($currentUrl, '/') ? $currentUrl : '/' . $currentUrl }}" />
    <meta property="og:title" content="{{ $finalTitle }}" />
    <meta property="og:description" content="{{ $metaDesc }}" />
    <meta property="og:locale" content="{{ $locale === 'en' ? 'en_US' : 'id_ID' }}" />
    @if($hasBothLocales)
    <meta property="og:locale:alternate" content="{{ $locale === 'en' ? 'id_ID' : 'en_US' }}" />
    @endif
    @if(!empty($seoImage))
    <meta property="og:image" content="{{ str_starts_with($seoImage, '/') ? $seoImage : '/' . $seoImage }}" />
    @endif

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ str_starts_with($currentUrl, '/') ? $currentUrl : '/' . $currentUrl }}" />
    <meta property="twitter:title" content="{{ $finalTitle }}" />
    <meta property="twitter:description" content="{{ $metaDesc }}" />
    @if(!empty($seoImage))
    <meta property="twitter:image" content="{{ str_starts_with($seoImage, '/') ? $seoImage : '/' . $seoImage }}" />
    @endif
    <link rel="alternate" type="application/rss+xml" title="{{ $siteName }} RSS" href="/rss.xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="preload" as="style" href="/assets/css/theme.css?v={{ $assetVersion }}">
    <link rel="stylesheet" href="/assets/css/theme.css?v={{ $assetVersion }}">
    @yield('head')
</head>
<body class="bg-white text-gray-800">
    @if($gtmId = \App\Models\Setting::get('gtm_id'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ preg_replace('/[^a-zA-Z0-9\-_]/', '', $gtmId) }}" height="0" width="0" class="hidden invisible"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @endif

    <!-- Floating Contact Tab -->
    <a target="_blank"
        href="https://forms.office.com/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl"
        class="fixed right-0 top-1/2 -translate-y-1/2 z-[100] bg-defenxor-red text-white py-6 px-3 rounded-l-lg cursor-pointer font-bold text-sm tracking-widest shadow-2xl hover:bg-neutral-900 transition-colors flex flex-col items-center justify-center no-underline">
        <span class="inline-block vertical-text">
            Contact Defenxor Team
        </span>
    </a>

    <!-- BEGIN: Top Utility Bar -->
    <div class="bg-black text-white py-2" data-purpose="utility-bar">
        <div class="max-w-[1140px] mx-auto w-full px-4 text-xs flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <svg class="w-3 h-3 mr-2" fill="currentColor" viewbox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    (+62 21) 2902 3055
                </span>
            </div>
            <div class="flex items-center space-x-6">
                <a class="hidden md:inline-block hover:text-gray-300 {{ $currentPath === 'tentang-kami' || $currentPath === 'en/about-us' ? 'text-defenxor-red font-semibold' : '' }}" href="{{ $localePrefix }}/{{ $locale === 'en' ? 'about-us' : 'tentang-kami' }}/">{{ $locale === 'en' ? 'About Us' : 'Tentang Kami' }}</a>
                <a class="hidden md:inline-block hover:text-gray-300 {{ $currentPath === 'karir' || $currentPath === 'en/career' ? 'text-defenxor-red font-semibold' : '' }}" href="{{ $localePrefix }}/{{ $locale === 'en' ? 'career' : 'karir' }}/">{{ $locale === 'en' ? 'Careers' : 'Karir' }}</a>
                @if($hasBothLocales)
                <div class="flex items-center space-x-4 border-l border-gray-600 pl-4">
                    <a href="{{ $enUrl }}" class="flex items-center space-x-2 hover:text-gray-300 transition-colors {{ $locale === 'en' ? 'text-defenxor-red font-semibold' : '' }}">
                        <img alt="English" class="w-4 h-3"
                            src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSI1MCUiIHkxPSIwJSIgeDI9IjUwJSIgeTI9IjEwMCUiIGlkPSJhIj48c3RvcCBzdG9wLWNvbG9yPSIjRkZGIiBvZmZzZXQ9IjAlIi8+PHN0b3Agc3RvcC1jb2xvcj0iI0YwRjBGMCIgb2Zmc2V0PSIxMDAlIi8+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgeDE9IjUwJSIgeTE9IjAlIiB4Mj0iNTAlIiB5Mj0iMTAwJSIgaWQ9ImIiPjxzdG9wIHN0b3AtY29sb3I9IiMwQTE3QTciIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjMDMwRTg4IiBvZmZzZXQ9IjEwMCUiLz48L2xpbmVhckdyYWRpZW50PjxsaW5lYXJHcmFkaWVudCB4MT0iNTAlIiB5MT0iMCUiIHgyPSI1MCUiIHkyPSIxMDAlIiBpZD0iYyI+PHN0b3Agc3RvcC1jb2xvcj0iI0U2MjczRSIgb2Zmc2V0PSIwJSIvPjxzdG9wIHN0b3AtY29sb3I9IiNDRjE1MkIiIG9mZnNldD0iMTAwJSIvPjwvbGluZWFyR3JhZGllbnQ+PC9kZWZzPjxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+PHBhdGggZmlsbD0idXJsKCNhKSIgZD0iTTAgMGgyMXYxNUgweiIvPjxwYXRoIGZpbGw9InVybCgjYikiIGQ9Ik0tLjAwMiAwaDIxdjE1aC0yMXoiLz48cGF0aCBkPSJNNS4wMDMgMTBILS4wMDJWNWg1LjAwNUwtMi4wODIuMjJsMS4xMTgtMS42NTcgOC45NjIgNi4wNDVWLTFoNXY1LjYwOGw4Ljk2Mi02LjA0NUwyMy4wNzguMjIgMTUuOTkzIDVoNS4wMDV2NWgtNS4wMDVsNy4wODUgNC43OC0xLjExOCAxLjY1Ny04Ljk2Mi02LjA0NVYxNmgtNXYtNS42MDhsLTguOTYyIDYuMDQ1LTEuMTE4LTEuNjU4TDUuMDAzIDEweiIgZmlsbD0idXJsKCNhKSIvPjxwYXRoIGQ9Ik0xNC4xMzYgNC45NThsOS41LTYuMjVhLjI1LjI1IDAgMDAtLjI3NS0uNDE3bC05LjUgNi4yNWEuMjUuMjUgMCAxMC4yNzUuNDE3em0uNzMyIDUuNTIybDguNTE1IDUuNzRhLjI1LjI1IDAgMTAuMjgtLjQxNWwtOC41MTYtNS43NGEuMjUuMjUgMCAwMC0uMjc5LjQxNXpNNi4xNDIgNC41MjZMLTIuNzQtMS40NjFhLjI1LjI1IDAgMDAtLjI4LjQxNUw1Ljg2MyA0Ljk0YS4yNS4yNSgwIDAwLjI3OS0uNDE0em0uNjg1IDUuNDY5bC05Ljg0NSA2LjUzYS4yNS4yNSAwIDEwLjI3Ni40MTZsOS44NDYtNi41MjlhLjI1LjI1IDAgMDAtLjI3Ny0uNDE3eiIgZmlsbD0iI0RCMUYzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZmlsbD0idXJsKCNjKSIgZD0iTS0uMDAyIDloOXY2aDNWOWg5VjZoLTlWMGgtM3Y2aC05eiIvPjwvZz48L3N2Zz4=" />
                        <span>EN</span>
                    </a>
                    <a href="{{ $idUrl }}" class="flex items-center space-x-2 hover:text-gray-300 transition-colors {{ $locale === 'id' ? 'text-defenxor-red font-semibold' : '' }}">
                        <img alt="Indonesian" class="w-4 h-3"
                            src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjEiIGhlaWdodD0iMTUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSI1MCUiIHkxPSIwJSIgeDI9IjUwJSIgeTI9IjEwMCUiIGlkPSJhIj48c3RvcCBzdG9wLWNvbG9yPSIjRkZGIiBvZmZzZXQ9IjAlIi8+PHN0b3Agc3RvcC1jb2xvcj0iI0YwRjBGMCIgb2Zmc2V0PSIxMDAlIi8+PC9saW5lYXJHcmFkaWVudD48bGluZWFyR3JhZGllbnQgeDE9IjUwJSIgeTE9IjAlIiB4Mj0iNTAlIiB5Mj0iMTAwJSIgaWQ9ImIiPjxzdG9wIHN0b3AtY29sb3I9IiNFMTIyMzciIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjQ0UxMTI2IiBvZmZzZXQ9IjEwMCUiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGZpbGw9InVybCgjYSkiIGQ9Ik0wIDBoMjF2MTVIMHoiLz48cGF0aCBmaWxsPSJ1cmwoI2IpIiBkPSJNMCAwaDIxdjhIMHoiLz48cGF0aCBmaWxsPSJ1cmwoI2EpIiBkPSJNMCA4aDIxdjdIMHoiLz48L2c+PC9zdmc+" />
                        <span>ID</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END: Top Utility Bar -->

    <!-- BEGIN: MainHeader -->
    <header class="sticky top-0 z-50 bg-white shadow-sm" data-purpose="main-navigation">
        <div class="max-w-[1140px] mx-auto w-full px-4 py-4 flex justify-between items-center relative z-20 bg-white">
            <div class="flex flex-col">
                <a href="{{ $localePrefix ? $localePrefix . '/' : '/' }}">
                    <img alt="{{ $siteName }}" width="200" height="52" class="h-[52px] w-auto"
                        src="/assets/img/logo_dns.webp?v={{ $assetVersion }}" />
                </a>
            </div>

            <button id="mobile-menu-btn" class="md:hidden text-[#000033] focus:outline-none" aria-label="Toggle Menu">
                <svg id="icon-menu" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <svg id="icon-close" class="w-7 h-7 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>

            <nav class="hidden md:flex space-x-8 text-sm font-semibold text-gray-600">
                @foreach($menuPages as $p)
                @php $menuLabel = $menuLabels[$p->id] ?? $p->title; @endphp
                <a class="{{ $currentPath === $p->slug || ($p->id === 1 && ($currentPath === '/' || $currentPath === 'en')) ? 'text-defenxor-red' : 'hover:text-defenxor-red' }}"
                    href="{{ $p->id === 1 ? ($localePrefix ? $localePrefix . '/' : '/') : $localePrefix . rtrim($p->url, '/') . '/' }}">{{ $menuLabel }}</a>
                @endforeach
                <a class="{{ str_starts_with($currentPath, $blogPrefix) || str_starts_with($currentPath, 'en/' . $blogPrefix) ? 'text-defenxor-red' : 'hover:text-defenxor-red' }}" href="{{ $localePrefix }}/{{ $blogPrefix }}/">Blog</a>
            </nav>
        </div>

        <!-- Mobile Navigation Menu -->
        <nav id="mobile-nav"
            class="hidden md:hidden absolute top-full left-0 w-full bg-white shadow-md pb-12 pt-8 flex-col items-center space-y-8 z-10 transition-all border-t border-gray-100">
            @foreach($menuPages as $p)
            @php $menuLabel = $menuLabels[$p->id] ?? $p->title; @endphp
            <a class="{{ $currentPath === $p->slug || ($p->id === 1 && ($currentPath === '/' || $currentPath === 'en')) ? 'text-defenxor-red' : 'text-gray-800 hover:text-defenxor-red' }} text-xl font-normal"
                href="{{ $p->id === 1 ? ($localePrefix ? $localePrefix . '/' : '/') : $localePrefix . rtrim($p->url, '/') . '/' }}">{{ $menuLabel }}</a>
            @endforeach
            <a class="{{ str_starts_with($currentPath, $blogPrefix) || str_starts_with($currentPath, 'en/' . $blogPrefix) ? 'text-defenxor-red' : 'text-gray-800 hover:text-defenxor-red' }} text-xl font-normal" href="{{ $localePrefix }}/{{ $blogPrefix }}/">Blog</a>
        </nav>
    </header>
    <!-- END: MainHeader -->

    <main>
        @yield('content')
    </main>

    <!-- BEGIN: Footer -->
    <footer class="bg-neutral-900 py-4 border-t border-neutral-800" data-purpose="main-footer">
        <div class="max-w-[1140px] mx-auto w-full px-4">
            <div class="flex justify-start text-[11px] text-gray-400 font-medium">
                {!! $footerText !!}
            </div>
        </div>
    </footer>
    <!-- END: Footer -->

    <script>{!! \App\Services\AnalyticsService::getInlineScript() !!}</script>
</body>
</html>
