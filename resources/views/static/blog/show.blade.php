@extends('static.layouts.base', ['title' => $post->title, 'seoTitle' => $post->seo_title ?? $post->title, 'seoDescription' => $post->seo_description ?? $post->excerpt, 'seoImage' => $post->featuredImage?->url, 'canonicalUrl' => $post->canonical_url])

@section('content')
@php
    $locale = $locale ?? 'id';
    $localePrefix = $localePrefix ?? '';
    $blogPrefix = config('static-cms.blog.url_prefix', 'blog');

    // Get previous and next posts (same locale)
    $prevPost = \App\Models\Post::published()
        ->where('locale', $locale)
        ->where('published_at', '<', $post->published_at)
        ->orderByDesc('published_at')
        ->first();
    $nextPost = \App\Models\Post::published()
        ->where('locale', $locale)
        ->where('published_at', '>', $post->published_at)
        ->orderBy('published_at')
        ->first();

    // Related posts: same category, same locale, exclude current, max 3
    $relatedPosts = \App\Models\Post::published()
        ->where('locale', $locale)
        ->where('id', '!=', $post->id)
        ->whereHas('categories', function($q) use ($post) {
            $q->whereIn('categories.id', $post->categories->pluck('id'));
        })
        ->with('featuredImage', 'categories')
        ->orderByDesc('published_at')
        ->take(3)
        ->get();

    // Fallback if no related by category
    if ($relatedPosts->isEmpty()) {
        $relatedPosts = \App\Models\Post::published()
            ->where('locale', $locale)
            ->where('id', '!=', $post->id)
            ->with('featuredImage', 'categories')
            ->orderByDesc('published_at')
            ->take(3)
            ->get();
    }

    // Translation link
    $otherLocale = $locale === 'id' ? 'en' : 'id';
    $translatedPost = $post->translation($otherLocale);

    // Translatable strings
    $t = [
        'contents' => $locale === 'en' ? 'Contents' : 'Daftar Isi',
        'no_headings' => $locale === 'en' ? 'No headings were found on this page.' : 'Tidak ada heading ditemukan.',
        'share' => $locale === 'en' ? 'Share this post' : 'Bagikan artikel ini',
        'prev' => $locale === 'en' ? 'Previous Article' : 'Artikel Sebelumnya',
        'next' => $locale === 'en' ? 'Next Article' : 'Artikel Selanjutnya',
        'related' => $locale === 'en' ? 'Related Posts' : 'Artikel Terkait',
        'read_more' => $locale === 'en' ? 'Read More' : 'Selengkapnya',
        'read_in' => $locale === 'en' ? 'Read in Bahasa Indonesia' : 'Read in English',
    ];
@endphp

<main class="pt-24 pb-20">
    <article class="max-w-[1140px] mx-auto px-4">

        <!-- Language Switch Banner -->
        @if($translatedPost)
        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-xl flex flex-wrap items-center justify-between gap-4 transition-all hover:border-gray-300 hover:shadow-sm">
            <span class="text-sm text-gray-600 flex items-center gap-2 font-medium">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path></svg>
                {{ $t['read_in'] }}:
            </span>
            <a href="{{ $otherLocale === 'en' ? '/en' : '' }}/{{ $blogPrefix }}/{{ $translatedPost->slug }}/"
                class="text-sm font-semibold text-defenxor-red hover:text-red-700 transition-colors flex items-center gap-1.5 group">
                {{ $translatedPost->title }}
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
        @endif

        <!-- Post Header -->
        <header class="mb-10">
            <div class="flex flex-wrap items-center mb-4">
                @foreach($post->categories as $cat)
                <span class="text-xs bg-red-50 text-defenxor-red px-2 py-1 rounded font-medium mr-3">{{ $cat->name }}</span>
                @endforeach
                <time class="text-xs text-gray-400 mr-3" datetime="{{ $post->published_at?->toISOString() }}">{{ $post->published_at?->format('d F Y') }}</time>
                <span class="text-xs text-gray-400 mr-3">by {{ $post->author?->name ?? 'Admin' }}</span>
                <span class="text-xs font-bold px-1.5 py-0.5 rounded {{ $locale === 'en' ? 'bg-sky-100 text-sky-700' : 'bg-orange-100 text-orange-700' }}">{{ strtoupper($locale) }}</span>
            </div>
            <h1 class="text-3xl md:text-[40px] leading-tight font-semibold text-[#283b6b] mb-8">
                {{ $post->title }}
            </h1>
            @if($post->featuredImage)
            <div class="aspect-[21/9] w-full overflow-hidden rounded-xl shadow-sm">
                <img src="{{ $post->featuredImage->url }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" class="w-full h-full object-cover">
            </div>
            @endif
        </header>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Content Column -->
            <div class="lg:col-span-2 text-gray-700">
                <div class="prose max-w-none prose-lg">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count())
                <div class="flex flex-wrap gap-2 mt-10 pt-6 border-t border-gray-200">
                    @foreach($post->tags as $tag)
                    <span class="text-xs bg-gray-100 text-gray-600 px-3 py-1 rounded-full font-medium">{{ $tag->name }}</span>
                    @endforeach
                </div>
                @endif

                <!-- Next / Prev Article -->
                <div class="flex items-center justify-between mt-12 pt-8 border-t border-gray-200">
                    @if($prevPost)
                    <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $prevPost->slug }}/" class="group flex items-center text-gray-600 hover:text-defenxor-red transition-colors text-sm md:text-base font-medium">
                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span class="hidden sm:inline">{{ $t['prev'] }}</span>
                        <span class="sm:hidden">Prev</span>
                    </a>
                    @else
                    <span></span>
                    @endif
                    @if($nextPost)
                    <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $nextPost->slug }}/" class="group flex items-center text-gray-600 hover:text-defenxor-red transition-colors text-sm md:text-base font-medium">
                        <span class="hidden sm:inline">{{ $t['next'] }}</span>
                        <span class="sm:hidden">Next</span>
                        <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                    @else
                    <span></span>
                    @endif
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-1">
                <div class="sticky top-28">

                    <!-- Table of Contents (auto-filled by JS) -->
                    <div id="toc-container" class="border border-gray-200 rounded-lg p-5 mb-8 bg-white shadow-sm">
                        <div class="flex justify-between items-center mb-4 pb-4 border-b border-gray-100">
                            <h3 class="font-medium text-gray-800">{{ $t['contents'] }}</h3>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </div>
                        <div id="toc-content">
                            <p class="text-sm text-gray-500 italic">{{ $t['no_headings'] }}</p>
                        </div>
                    </div>

                    <!-- Share this post -->
                    <div class="border border-gray-200 rounded-lg p-5 bg-white shadow-sm">
                        <h3 class="font-medium text-gray-800 mb-4">{{ $t['share'] }}</h3>
                        <div class="space-y-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="flex items-center bg-[#3b5998] hover:bg-[#2d4373] text-white px-4 py-2.5 rounded transition-colors text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path></svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener noreferrer" class="flex items-center bg-[#1da1f2] hover:bg-[#0c85d0] text-white px-4 py-2.5 rounded transition-colors text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"></path></svg>
                                Twitter
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}" target="_blank" rel="noopener noreferrer" class="flex items-center bg-[#0077b5] hover:bg-[#005885] text-white px-4 py-2.5 rounded transition-colors text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 24 24"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"></path><circle cx="4" cy="4" r="2"></circle></svg>
                                LinkedIn
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" rel="noopener noreferrer" class="flex items-center bg-[#25d366] hover:bg-[#1da851] text-white px-4 py-2.5 rounded transition-colors text-sm font-medium">
                                <svg class="w-5 h-5 mr-3 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
                                WhatsApp
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </article>

    <!-- Related Posts -->
    @if($relatedPosts->count())
    <section class="max-w-[1140px] mx-auto px-4 mt-20 border-t border-gray-200 pt-16">
        <h3 class="text-2xl font-semibold text-[#283b6b] mb-8">{{ $t['related'] }}</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedPosts as $related)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-md group">
                @if($related->featuredImage)
                <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $related->slug }}/" class="block relative h-40 overflow-hidden">
                    <img src="{{ $related->featuredImage->url }}" alt="{{ $related->featuredImage->alt_text ?? $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" />
                </a>
                @endif
                <div class="p-5">
                    <h4 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 hover:text-defenxor-red transition-colors">
                        <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $related->slug }}/">{{ $related->title }}</a>
                    </h4>
                    <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $related->slug }}/" class="text-defenxor-red font-medium text-xs flex items-center w-max mt-3 hover:underline">
                        {{ $t['read_more'] }}&nbsp;<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</main>
@endsection
