@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
@php $b = $page->content_blocks ?? []; @endphp

<main class="pt-16 pb-32 bg-white">
    <!-- Title and Image -->
    <section class="max-w-[1140px] mx-auto px-4 text-center mb-16">
        <h1 class="text-3xl md:text-[40px] font-semibold text-gray-700 mb-10">{{ $b['hero_title'] ?? $page->title }}</h1>
        @if(!empty($b['hero_image']))
        <div class="flex justify-center mb-16">
            <img src="{{ $b['hero_image'] }}" 
                 alt="{{ $page->title }}" class="w-full max-w-3xl h-auto object-cover rounded-lg shadow-sm" loading="lazy">
        </div>
        @endif
        
        @if(!empty($b['body']))
        <div class="max-w-4xl mx-auto text-left text-gray-600 text-sm md:text-[15px] leading-relaxed space-y-6 prose max-w-none">
            {!! $b['body'] !!}
        </div>
        @endif
    </section>
</main>
@endsection
