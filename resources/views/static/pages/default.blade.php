@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
@php $blocks = $page->content_blocks ?? []; @endphp

<main class="pt-16 pb-20 bg-white">
    <section class="max-w-[1140px] mx-auto px-4 text-center mb-16">
        @if(!empty($blocks['hero']))
        <h1 class="text-3xl md:text-[40px] font-semibold text-gray-700 mb-10">{{ $blocks['hero']['title'] ?? $page->title }}</h1>
        @if(!empty($blocks['hero']['subtitle']))
        <p class="text-gray-500 max-w-2xl mx-auto mb-10">{{ $blocks['hero']['subtitle'] }}</p>
        @endif
        @else
        <h1 class="text-3xl md:text-[40px] font-semibold text-gray-700 mb-10">{{ $page->title }}</h1>
        @endif
    </section>

    @if(!empty($blocks['body']))
    <section class="max-w-4xl mx-auto px-4 mb-16">
        <div class="prose max-w-none text-gray-600 text-sm md:text-[15px] leading-relaxed">
            {!! $blocks['body'] !!}
        </div>
    </section>
    @endif
</main>
@endsection
