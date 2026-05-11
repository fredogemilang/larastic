@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
    @php $b = $page->content_blocks ?? []; @endphp

    <main class="pt-24 pb-12">
        <!-- Hero Title -->
        <div class="max-w-[1140px] mx-auto px-4 text-center mt-8 mb-8">
            <h1 class="text-3xl md:text-4xl font-normal text-gray-800 mb-4">{{ $b['hero']['title'] ?? $page->title }}</h1>
            @if(!empty($b['hero']['subtitle']))
            <p class="text-lg text-gray-500">{{ $b['hero']['subtitle'] }}</p>
            @endif
        </div>

        <!-- Section 1: Apa Itu DIMS ? -->
        @if(!empty($b['description']))
        <section class="py-12">
            <div class="max-w-[1140px] mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Text Left -->
                    <div>
                        {!! str_replace(['<h2>', '<p>'], ['<h2 class="text-3xl font-light text-gray-800 mb-6">', '<p class="text-gray-600 mb-4 leading-relaxed">'], $b['description']) !!}
                    </div>
                    <!-- Image Right -->
                    <div class="flex justify-center">
                        <img src="{{ $b['description_image'] ?? 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                            alt="{{ $page->title }} Concept"
                            class="max-w-full" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Section 2: FITUR UTAMA -->
        @if(!empty($b['features']))
            <section class="py-12 bg-gray-50">
                <div class="max-w-[1140px] mx-auto px-4">
                    <div class="grid md:grid-cols-2 gap-12 items-center">
                        <!-- Image Left -->
                        <div class="flex justify-center order-2 md:order-1">
                            <img src="{{ $b['features_image'] ?? 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                                alt="Fitur Utama Diagram"
                                class="max-w-full h-auto rounded-xl shadow-lg aspect-[4/3] object-contain" loading="lazy">
                        </div>
                        <!-- Text Right -->
                        <div class="order-1 md:order-2">
                            <h2 class="text-2xl font-light text-gray-800 mb-8 uppercase tracking-widest">{{ $b['features_title'] ?? 'FITUR UTAMA' }}</h2>
                            <ul class="space-y-6 text-gray-600 list-disc pl-5">
                                @foreach($b['features'] as $feature)
                                <li class="leading-relaxed">
                                    @if(!empty($feature['title']))
                                    <strong class="font-medium text-gray-800">{{ $feature['title'] }}</strong><br>
                                    @endif
                                    <span class="text-sm">{{ $feature['description'] ?? '' }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <!-- Section 3: KEUNGGULAN KAMI -->
        @if(!empty($b['advantages']))
        <section class="py-12">
            <div class="max-w-[1140px] mx-auto px-4">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <!-- Text Left -->
                    <div>
                        <h2 class="text-2xl font-light text-gray-800 mb-8 uppercase tracking-widest">{{ $b['advantages_title'] ?? 'KEUNGGULAN KAMI' }}</h2>
                        <ul class="space-y-6 text-gray-600 list-disc pl-5 text-sm">
                            @foreach($b['advantages'] as $adv)
                            <li class="leading-relaxed">
                                @if(!empty($adv['title']))
                                <strong class="font-medium text-gray-800">{{ $adv['title'] }}</strong><br>
                                @endif
                                <span>{{ $adv['description'] ?? '' }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Image Right -->
                    <div class="flex justify-center">
                        <img src="{{ $b['advantages_image'] ?? 'https://images.unsplash.com/photo-1573164713988-8665fc963095?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}"
                            alt="Keunggulan Kami Illustration"
                            class="max-w-full h-auto rounded-xl shadow-lg aspect-[4/3] object-contain" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>

    <!-- BEGIN: Pre-Footer -->
    <section class="bg-gray-500 py-12 text-white border-t border-gray-400">
        <div class="max-w-[1140px] mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <!-- Siapa Kami -->
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ ($locale ?? app()->getLocale()) === 'en' ? 'Who We Are' : 'Siapa Kami?' }}</h3>
                    <p class="text-sm leading-relaxed mb-0 text-gray-100">
                        @if(($locale ?? app()->getLocale()) === 'en')
                            We are Defender Nusa Semesta (DNS), a business focused on IT security. We are the team that created Defenxor, an integrated security provider for businesses.
                        @else
                            Kami adalah Defender Nusa Semesta (DNS), sebuah bisnis yang berfokus pada keamanan TI. Kami
                            adalah tim yang menciptakan Defenxor, sebuah penyedia keamanan terintegrasi untuk bisnis.
                        @endif
                    </p>
                </div>
                <!-- Mengapa Defenxor -->
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ ($locale ?? app()->getLocale()) === 'en' ? 'Why Defenxor?' : 'Mengapa Defenxor ?' }}</h3>
                    <p class="text-sm leading-relaxed mb-0 text-gray-100">
                        @if(($locale ?? app()->getLocale()) === 'en')
                            Through IT, your business is connected to customers globally. Therefore, IT-related issues must be prevented as they will negatively impact your business. Threats against IT systems are constantly evolving. Securing your IT infrastructure requires high expertise and investment. Defenxor removes complexity and lowers the cost-of-ownership in using a security platform that ensures your business runs smoothly. This way, you can focus more on other aspects of your business.
                        @else
                            Melalui TI, bisnis Anda terhubung dengan pelanggan secara global. Oleh karenanya, permasalahan
                            terkait TI haruslah dicegah karena akan berdampak negatif pada bisnis Anda. Ancaman terhadap
                            sistem TI teruslah berkembang. Mengamankan bisnis TI Anda membutuhkan keahlian serta investasi
                            yang tinggi. Defenxor menghilangkan kompleksitas dan menurunkan cost-of-ownership dalam
                            menggunakan security platform yang dapat memastikan bisnis berjalan lancar. Dengan demikian,
                            Anda dapat lebih fokus dengan aspek bisnis Anda yang lain.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Pre-Footer -->

@endsection
