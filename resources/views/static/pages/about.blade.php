@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
@php $b = $page->content_blocks ?? []; @endphp

<main class="pt-16 pb-20 bg-white">
    <!-- Title -->
    <section class="max-w-[1140px] mx-auto px-4 text-center mb-16">
        <h1 class="text-3xl md:text-[40px] font-semibold text-gray-700 mb-10">{{ $b['hero_title'] ?? $page->title }}</h1>
        @if(!empty($b['hero_image']))
        <div class="flex justify-center mb-10">
            <img src="{{ $b['hero_image'] }}" alt="{{ $page->title }}" class="w-64 h-auto object-contain" loading="lazy">
        </div>
        @endif

        @if(!empty($b['body']))
        <div class="max-w-5xl mx-auto text-left text-gray-600 text-sm md:text-[15px] leading-relaxed space-y-4 prose max-w-none">
            {!! $b['body'] !!}
        </div>
        @endif
    </section>

    <!-- Visi, Misi, Nilai -->
    @if(!empty($b['vision_text']) || !empty($b['mission_text']) || !empty($b['values_text']))
    <section class="max-w-[900px] mx-auto px-4 space-y-20 mb-24">
        @if(!empty($b['vision_text']))
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">
            @if(!empty($b['vision_image']))
            <div class="w-full md:w-1/3 flex justify-center">
                <img src="{{ $b['vision_image'] }}" alt="Visi Kami" class="w-48 h-auto object-contain rounded-lg" loading="lazy">
            </div>
            @endif
            <div class="w-full {{ !empty($b['vision_image']) ? 'md:w-2/3' : '' }} text-center md:text-left">
                <h2 class="text-2xl font-light text-gray-700 mb-4 uppercase tracking-widest">VISI KAMI</h2>
                <div class="text-gray-600 text-[15px] leading-relaxed prose max-w-none">{!! $b['vision_text'] ?? '' !!}</div>
            </div>
        </div>
        @endif

        @if(!empty($b['mission_text']))
        <div class="flex flex-col md:flex-row items-center gap-10 md:gap-16">
            @if(!empty($b['mission_image']))
            <div class="w-full md:w-1/3 flex justify-center">
                <img src="{{ $b['mission_image'] }}" alt="Misi Kami" class="w-48 h-auto object-contain rounded-lg" loading="lazy">
            </div>
            @endif
            <div class="w-full {{ !empty($b['mission_image']) ? 'md:w-2/3' : '' }} text-center md:text-left">
                <h2 class="text-2xl font-light text-gray-700 mb-4 uppercase tracking-widest">MISI KAMI</h2>
                <div class="text-gray-600 text-[15px] leading-relaxed prose max-w-none">{!! $b['mission_text'] ?? '' !!}</div>
            </div>
        </div>
        @endif

        @if(!empty($b['values_text']))
        <div class="flex flex-col md:flex-row items-start gap-10 md:gap-16">
            @if(!empty($b['values_image']))
            <div class="w-full md:w-1/3 flex justify-center mt-4">
                <img src="{{ $b['values_image'] }}" alt="Nilai Kami" class="w-48 h-auto object-contain rounded-lg" loading="lazy">
            </div>
            @endif
            <div class="w-full {{ !empty($b['values_image']) ? 'md:w-2/3' : '' }} text-left">
                <h2 class="text-2xl font-light text-gray-700 mb-6 uppercase tracking-widest text-center md:text-left">NILAI KAMI</h2>
                <div class="text-gray-600 text-[14px] leading-relaxed prose max-w-none">{!! $b['values_text'] ?? '' !!}</div>
            </div>
        </div>
        @endif
    </section>
    @endif

    <!-- Team Section -->
    @if(!empty($b['team']))
    <section class="max-w-[1140px] mx-auto px-4 text-center mt-20">
        <h2 class="text-3xl md:text-[36px] font-semibold text-gray-700 mb-12">{{ $b['team_title'] ?? 'Tim Manajemen Defenxor' }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left max-w-5xl mx-auto">
            @foreach($b['team'] as $member)
            <div class="border border-gray-200 p-6 shadow-sm bg-white">
                <div class="flex items-center gap-6 mb-6">
                    @if(!empty($member['photo']))
                    <img src="{{ $member['photo'] }}" alt="{{ $member['name'] ?? '' }}" class="w-24 h-24 object-cover">
                    @endif
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $member['name'] ?? '' }}</h3>
                        <p class="text-sm text-gray-800 font-medium mb-3">{{ $member['role'] ?? '' }}</p>
                        
                        @if(!empty($member['linkedin_url']) || !empty($member['facebook_url']))
                        <div class="flex space-x-2">
                            @if(!empty($member['linkedin_url']))
                            <a href="{{ $member['linkedin_url'] }}" target="_blank" class="bg-[#0077b5] text-white w-7 h-7 flex items-center justify-center rounded-full transition-colors hover:opacity-80">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                            @endif
                            @if(!empty($member['facebook_url']))
                            <a href="{{ $member['facebook_url'] }}" target="_blank" class="bg-[#1877f2] text-white w-7 h-7 flex items-center justify-center rounded-full transition-colors hover:opacity-80">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.597 0 0 .597 0 1.325v21.351C0 23.403.597 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.73 0 1.323-.597 1.323-1.324V1.325C24 .597 23.403 0 22.675 0z"/></svg>
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <p class="text-[13px] text-gray-600 leading-relaxed">{{ $member['bio'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</main>
@endsection
