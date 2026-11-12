@extends('static.layouts.base', ['title' => $page->title, 'seoTitle' => $page->seo_title, 'seoDescription' => $page->seo_description, 'seoImage' => $page->seo_image, 'canonicalUrl' => $page->canonical_url])

@section('content')
    @include('static.pages.disi-content')

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
