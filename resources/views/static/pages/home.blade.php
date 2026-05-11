@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('head')
<link rel="preload" as="image" href="/assets/img/bg1.webp" fetchpriority="high">
@endsection

@section('content')
@php $b = $page->content_blocks ?? []; @endphp

<!-- BEGIN: Hero Section -->
<section class="relative bg-gradient-to-r from-neutral-800 to-neutral-700 overflow-hidden min-h-[500px] md:min-h-[600px] flex items-center" data-purpose="hero-banner">
    <div class="absolute inset-0 bg-[#333] transform skew-y-[-6deg] origin-top-right translate-y-24 pointer-events-none"></div>

    <div class="relative w-full z-10 overflow-hidden" id="hero-slider">
        <div class="flex transition-transform duration-500 ease-in-out" id="hero-slider-track">
            @if(!empty($b['slides']))
                @foreach($b['slides'] as $i => $slide)
                <div class="relative w-full flex-shrink-0 flex items-center py-16 min-h-[500px] md:min-h-[600px]">
                    <img alt="Background Slide {{ $i + 1 }}" class="absolute inset-0 w-full h-full object-cover -z-10"
                        src="{{ $slide['image'] ?? '/assets/img/bg1.webp' }}" {{ $i === 0 ? 'fetchpriority=high' : 'loading=lazy' }} />
                    <div class="absolute inset-0 bg-neutral-900/60 -z-10 pointer-events-none"></div>
                    <div class="max-w-[1140px] mx-auto px-4 w-full grid md:grid-cols-2 gap-12 items-center">
                        <div class="text-white">
                            @if($i === 0)
                            <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-6">{{ $slide['title'] ?? '' }}</h1>
                            @else
                            <h2 class="text-3xl md:text-4xl font-bold leading-tight mb-6">{{ $slide['title'] ?? '' }}</h2>
                            @endif
                            <p class="text-lg text-gray-300 max-w-lg mb-8">{{ $slide['subtitle'] ?? '' }}</p>
                            @if(!empty($slide['cta_text']))
                            <a href="{{ $slide['cta_url'] ?? '#' }}" class="inline-block bg-defenxor-red text-white font-semibold text-sm px-6 py-3 hover:bg-neutral-900 transition-colors tracking-wide no-underline">{{ $slide['cta_text'] }}</a>
                            @endif
                        </div>
                        @if(!empty($slide['side_image']))
                        <div class="relative w-full aspect-video md:aspect-[4/3]">
                            @php
                                $sideImage = $slide['side_image'];
                                $mobileImage = '';
                                if (str_starts_with($sideImage, '/assets/img/')) {
                                    $potentialMobile = str_replace('.webp', '-mobile.webp', $sideImage);
                                    if (file_exists(resource_path('views/static' . $potentialMobile))) {
                                        $mobileImage = $potentialMobile;
                                    }
                                }
                            @endphp
                            @if($mobileImage)
                            <picture class="absolute inset-0 w-full h-full">
                                <source media="(max-width: 768px)" srcset="{{ $mobileImage }}">
                                <img alt="{{ $slide['title'] ?? 'Feature' }}" class="w-full h-full object-{{ str_contains($sideImage, 'DISI') ? 'contain' : 'cover' }}"
                                    src="{{ $sideImage }}" {{ $i === 0 ? 'fetchpriority=high' : 'loading=lazy' }} />
                            </picture>
                            @else
                            <img alt="{{ $slide['title'] ?? 'Feature' }}" class="absolute inset-0 w-full h-full object-{{ str_contains($sideImage, 'DISI') ? 'contain' : 'cover' }}"
                                src="{{ $sideImage }}" {{ $i === 0 ? 'fetchpriority=high' : 'loading=lazy' }} />
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <div class="min-w-full flex-shrink-0 relative">
                    <div class="max-w-[1140px] mx-auto w-full px-4 py-20 md:py-32">
                        <div class="max-w-3xl">
                            <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight">{{ $b['hero']['title'] ?? 'Managed Security Services Provider' }}</h2>
                            <p class="text-white text-lg md:text-xl opacity-80 mb-8 leading-relaxed max-w-xl">{{ $b['hero']['subtitle'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <button id="slider-prev" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center bg-black/40 hover:bg-black/70 text-white rounded-full transition-colors backdrop-blur-sm focus:outline-none" aria-label="Previous Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </button>
        <button id="slider-next" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 flex items-center justify-center bg-black/40 hover:bg-black/70 text-white rounded-full transition-colors backdrop-blur-sm focus:outline-none" aria-label="Next Slide">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </button>
    </div>
</section>
<!-- END: Hero Section -->

<!-- BEGIN: Siapa Kami Section -->
<section class="py-20 bg-gray-50" data-purpose="about-us">
    <div class="max-w-[1140px] mx-auto px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">{{ $b['siapa_kami']['title'] ?? 'Siapa Kami ?' }}</h2>
            <p class="text-gray-600 mb-12">{{ $b['siapa_kami']['body'] ?? 'Kami adalah Defender Nusa Semesta (DNS), sebuah bisnis yang berfokus pada keamanan TI.' }}</p>
        </div>
        @php $videoUrl = $b['siapa_kami']['video'] ?? ''; @endphp
        @if(!empty($videoUrl))
        <div class="relative rounded-xl overflow-hidden shadow-2xl aspect-video bg-black group">
            <video id="siapaKamiVideo" controls preload="metadata" class="absolute inset-0 w-full h-full outline-none z-10" src="{{ $videoUrl }}">
                Your browser does not support the video tag.
            </video>
            <div id="videoOverlay" class="absolute inset-0 z-20 flex items-center justify-center cursor-pointer bg-black/10 transition-colors pointer-events-auto">
                <svg class="w-16 h-16 opacity-90 group-hover:opacity-100 transition-all transform group-hover:scale-110 duration-300 drop-shadow-lg" viewBox="0 0 68 48">
                    <path d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,0.13,34,0,34,0S12.21,0.13,6.9,1.55C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z" fill="#FF0000"/>
                    <path d="M 45,24 27,14 27,34" fill="#fff"/>
                </svg>
            </div>
        </div>
        @endif
    </div>
</section>
<!-- END: Siapa Kami Section -->

<!-- BEGIN: Mengapa Defenxor Section -->
<section class="relative py-24 text-white overflow-hidden bg-neutral-900" data-purpose="features-grid">
    <div class="absolute inset-0 z-0 hidden md:block">
        <img alt="Office Background" width="1140" height="700" loading="lazy" class="w-full h-full object-cover"
            src="/assets/img/mengapa-defenxor/bg_services.webp" />
        <div class="absolute inset-0 bg-black/80"></div>
    </div>
    <div class="max-w-[1140px] mx-auto px-4 w-full relative z-10">
        <div class="text-center max-w-4xl mx-auto mb-16">
            <h2 class="text-3xl font-bold mb-6">{{ $b['why_section']['title'] ?? 'Mengapa Defenxor ?' }}</h2>
            <p class="text-gray-300 text-sm leading-relaxed">{{ $b['why_section']['subtitle'] ?? '' }}</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(!empty($b['features']))
                @foreach($b['features'] as $i => $feature)
                <div class="bg-white p-6 md:p-8 flex items-start space-x-5 shadow-sm h-full">
                    <div class="flex-shrink-0">
                        @php
                            $img = $feature['img'] ?? '';
                            $src = $img ? (str_starts_with($img, '/') || str_starts_with($img, 'http') ? $img : '/assets/img/mengapa-defenxor/' . $img) : '/assets/img/mengapa-defenxor/' . (($i % 6) + 1) . '.webp';
                        @endphp
                        <img alt="{{ $feature['title'] }}" width="64" height="64" loading="lazy"
                            class="w-16 h-16 object-contain" src="{{ $src }}" />
                    </div>
                    <div>
                        <h3 class="text-gray-600 font-normal text-[17px] md:text-xl leading-tight mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 text-xs md:text-sm leading-relaxed mb-0">{{ $feature['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</section>
<!-- END: Mengapa Defenxor Section -->

<!-- BEGIN: Contact CTA Section -->
<section class="bg-white py-12" data-purpose="contact-banner">
    <div class="max-w-[1140px] mx-auto w-full px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center space-x-6 max-w-2xl">
                <div class="bg-neutral-800 p-4 rounded-xl text-white flex-shrink-0">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-2">{{ $b['cta']['title'] ?? 'Kami Senang Menjawab Pertanyaan Anda' }}</h4>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $b['cta']['description'] ?? 'Hubungilah kami sekarang melalui telepon di (+62 21) 2902 3055' }}</p>
                </div>
            </div>
            <div class="flex -space-x-4 items-end">
                <div class="relative w-24 h-24 md:w-40 md:h-40">
                    <img alt="Support Center" width="160" height="160" loading="lazy" class="w-full h-full object-contain" src="/assets/img/contactus.webp" />
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END: Contact CTA Section -->
@endsection
