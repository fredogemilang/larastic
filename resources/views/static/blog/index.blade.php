@extends('static.layouts.base', [
    'title' => 'Blog', 
    'seoTitle' => 'Blog — ' . $siteName,
    'seoDescription' => $locale === 'en' 
        ? 'Stay updated with the latest news, insights, and articles about cybersecurity and managed security services from Defenxor.' 
        : 'Dapatkan berita, wawasan, dan artikel terbaru seputar keamanan siber dan layanan managed security dari Defenxor.'
])

@section('content')
    @php
        $locale = $locale ?? 'id';
        $localePrefix = $localePrefix ?? '';
    @endphp

    <!-- Hero Banner Blog -->
    <section class="relative h-[400px] md:h-[500px] bg-neutral-900 overflow-hidden flex items-center" data-purpose="blog-hero">
        <div class="absolute inset-0 z-0">
            <img src="/storage/media/Blog-1.webp" alt="Blog Banner" class="w-full h-full object-cover opacity-60" loading="eager">
            <div class="absolute inset-0 bg-defenxor-red mix-blend-multiply opacity-50"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
        </div>
        <div class="max-w-[1140px] mx-auto w-full px-4 relative z-10 text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-2">BLOG</h1>
            <p class="text-xl md:text-2xl font-light text-gray-200">{{ $locale === 'en' ? 'News and Information of Security' : 'Berita dan Informasi Keamanan' }}</p>
        </div>
    </section>

    @php
    $blogPrefix = config('static-cms.blog.url_prefix', 'blog');
    $items = collect($posts->items());

    $featured = null;
    $sidePosts = collect();
    $restPosts = $items;

    if ($posts->currentPage() === 1 && $items->count() > 0) {
        $featured = $items->first();
        $sidePosts = $items->slice(1, 2);
        $restPosts = $items->slice(3);
    }

    $readMore = $locale === 'en' ? 'Read More' : 'Selengkapnya';
    $noArticles = $locale === 'en' ? 'No articles yet. Stay tuned!' : 'Belum ada artikel. Nantikan segera!';
    @endphp

    <!-- Articles Grid -->
    <main class="py-16 bg-gray-50">
        <div class="max-w-[1140px] mx-auto px-4">
            @if($featured)
            <!-- Featured + Side Articles -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                <!-- Featured Article -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group">
                    @if($featured->featuredImage)
                    <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $featured->slug }}/" class="block relative h-60 md:h-72 overflow-hidden">
                        <img src="{{ $featured->featuredImage->url }}" alt="{{ $featured->featuredImage->alt_text ?? $featured->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="eager" />
                    </a>
                    @endif
                    <div class="p-6 md:p-8">
                        <div class="flex flex-wrap items-center mb-3">
                            @foreach($featured->categories as $cat)
                            <span class="text-xs bg-red-50 text-defenxor-red px-2 py-1 rounded font-medium mr-3">{{ $cat->name }}</span>
                            @endforeach
                            <time class="text-xs text-gray-400" datetime="{{ $featured->published_at->toISOString() }}">{{ $featured->published_at->format('d M Y') }}</time>
                        </div>
                        <h2 class="text-xl md:text-2xl font-semibold text-gray-800 mb-3 leading-tight">
                            <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $featured->slug }}/" class="hover:text-defenxor-red transition-colors">{{ $featured->title }}</a>
                        </h2>
                        @if($featured->excerpt)
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">{{ $featured->excerpt }}</p>
                        @endif
                        <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $featured->slug }}/" class="flex items-center w-max text-defenxor-red font-semibold text-sm hover:underline">
                            {{ $readMore }}&nbsp;<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Side Articles -->
                <div class="flex flex-col gap-8">
                    @foreach($sidePosts as $sidePost)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group flex-1">
                        @if($sidePost->featuredImage)
                        <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $sidePost->slug }}/" class="block relative h-36 overflow-hidden">
                            <img src="{{ $sidePost->featuredImage->url }}" alt="{{ $sidePost->featuredImage->alt_text ?? $sidePost->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" />
                        </a>
                        @endif
                        <div class="p-5">
                            <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 hover:text-defenxor-red transition-colors">
                                <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $sidePost->slug }}/">{{ $sidePost->title }}</a>
                            </h3>
                            <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $sidePost->slug }}/" class="text-defenxor-red font-medium text-xs flex items-center w-max mt-2 hover:underline">
                                {{ $readMore }}&nbsp;<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Rest of Posts Grid -->
            @if($restPosts->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($restPosts as $post)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-md group">
                    @if($post->featuredImage)
                    <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $post->slug }}/" class="block relative h-48 overflow-hidden">
                        <img src="{{ $post->featuredImage->url }}" alt="{{ $post->featuredImage->alt_text ?? $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" />
                    </a>
                    @endif
                    <div class="p-5">
                        <div class="flex flex-wrap items-center mb-2">
                            @foreach($post->categories as $cat)
                            <span class="text-xs bg-red-50 text-defenxor-red px-2 py-0.5 rounded font-medium mr-2">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                        <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 hover:text-defenxor-red transition-colors">
                            <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $post->slug }}/">{{ $post->title }}</a>
                        </h3>
                        @if($post->excerpt)
                        <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-3">{{ $post->excerpt }}</p>
                        @endif
                        <a href="{{ $localePrefix }}/{{ $blogPrefix }}/{{ $post->slug }}/" class="text-defenxor-red font-medium text-xs flex items-center w-max hover:underline">
                            {{ $readMore }}&nbsp;<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($posts->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-400 text-lg">{{ $noArticles }}</p>
            </div>
            @endif

            <!-- Pagination -->
            @if($posts->hasPages())
            @php
        $getPageUrl = function ($pageNum) use ($blogPrefix, $localePrefix) {
            $base = $localePrefix . "/{$blogPrefix}";
            return $pageNum == 1 ? $base . '/' : "{$base}/page/{$pageNum}/";
        };
            @endphp
            <div class="flex justify-center items-center space-x-2 mt-16">
                @if ($posts->onFirstPage())
                    <span class="px-3 py-1 text-gray-400 font-medium text-sm cursor-not-allowed">{{ $locale === 'en' ? 'Prev' : 'Prev' }}</span>
                @else
                    <a href="{{ $getPageUrl($posts->currentPage() - 1) }}" class="px-3 py-1 text-gray-500 hover:text-defenxor-red transition-colors font-medium text-sm">Prev</a>
                @endif

                @foreach (range(max(1, $posts->currentPage() - 2), min($posts->lastPage(), $posts->currentPage() + 2)) as $page)
                    @if ($page == $posts->currentPage())
                        <span class="px-3 py-1 rounded text-defenxor-red bg-red-50 font-medium text-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $getPageUrl($page) }}" class="px-3 py-1 rounded text-gray-600 hover:bg-gray-100 transition-colors font-medium text-sm">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($posts->hasMorePages())
                    <a href="{{ $getPageUrl($posts->currentPage() + 1) }}" class="px-3 py-1 text-defenxor-red hover:text-red-700 transition-colors font-medium text-sm">Next</a>
                @else
                    <span class="px-3 py-1 text-gray-400 font-medium text-sm cursor-not-allowed">Next</span>
                @endif
            </div>
            @endif
        </div>
    </main>
@endsection
