@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
@php $b = $page->content_blocks ?? []; @endphp

<main class="pt-16 pb-20 bg-white">
    <section class="max-w-[1140px] mx-auto px-4 text-center mb-16">
        <h1 class="text-3xl md:text-[40px] font-semibold text-gray-700 mb-10">{{ $b['hero']['title'] ?? $page->title }}</h1>
        @if(!empty($b['hero']['subtitle']))
        <p class="text-gray-500 max-w-2xl mx-auto mb-10">{{ $b['hero']['subtitle'] }}</p>
        @endif
    </section>

    @if(!empty($b['info']))
    <section class="max-w-[1140px] mx-auto px-4 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @if(!empty($b['info']['email']))
            <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100">
                <div class="w-12 h-12 bg-defenxor-red rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Email</h3>
                <p class="text-gray-600 text-sm">{{ $b['info']['email'] }}</p>
            </div>
            @endif
            @if(!empty($b['info']['phone']))
            <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100">
                <div class="w-12 h-12 bg-defenxor-red rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Telepon</h3>
                <p class="text-gray-600 text-sm">{{ $b['info']['phone'] }}</p>
            </div>
            @endif
            @if(!empty($b['info']['address']))
            <div class="bg-gray-50 rounded-xl p-8 text-center border border-gray-100">
                <div class="w-12 h-12 bg-defenxor-red rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="font-semibold text-gray-800 mb-2">Alamat</h3>
                <p class="text-gray-600 text-sm">{{ $b['info']['address'] }}</p>
            </div>
            @endif
        </div>
    </section>
    @endif

    @if(!empty($b['body']))
    <section class="max-w-4xl mx-auto px-4">
        <div class="prose max-w-none">{!! $b['body'] !!}</div>
    </section>
    @endif
</main>
@endsection
