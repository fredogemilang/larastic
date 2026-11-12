@php
    $b = $page->content_blocks ?? [];
    $loc = $locale ?? app()->getLocale();
    $isEn = $loc === 'en';
@endphp

<main class="pt-24 pb-0">
    <!-- Top Title & Subtitle Breadcrumb -->
    <div class="max-w-[1140px] mx-auto px-4 text-center mt-8 mb-6">
        <h1 class="text-2xl md:text-3xl font-normal text-gray-800 mb-2">
            {{ $isEn ? 'Managed Security Service Provider (MSSP) 24/7 SOC | Defenxor DIMS' : 'Managed Security Service Provider (MSSP) 24/7 SOC I Defenxor DIMS' }}
        </h1>
        <p class="text-sm md:text-base text-gray-500">
            Managed Security Service Provider (MSSP)
        </p>
    </div>

    <!-- Hero Section -->
    <section class="py-8 md:py-12">
        <div class="max-w-[1140px] mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-semibold text-gray-900 leading-tight mb-4">
                Managed Security Service Provider (MSSP) 24/7 SOC<br>
                Defenxor Intelligence Managed Security (DIMS)
            </h2>
            <p class="max-w-3xl mx-auto text-sm md:text-base text-gray-600 leading-relaxed mb-6">
                @if($isEn)
                    Defenxor Intelligence Managed Security (DIMS) is a Managed Security Service Provider (MSSP) supported by a 24/7 Security Operations Center (SOC) to help organizations detect threats, monitor IT environments, and respond to incidents rapidly.
                @else
                    Defenxor Intelligence Managed Security (DIMS) adalah layanan Managed Service Provider (MSSP) didukung Security Operations Center (SOC) 24/7 untuk membantu perusahaan mendeteksi ancaman, memantau lingkungan IT, dan merespons insiden dengan cepat.
                @endif
            </p>
            <div>
                <a href="#kemampuan-dims" class="inline-block bg-defenxor-red text-white font-semibold text-sm px-6 py-2.5 rounded shadow-sm hover:bg-[#8e2427] transition-colors">
                    {{ $isEn ? 'Explore Managed SOC' : 'Jelajahi Managed SOC' }}
                </a>
            </div>
        </div>
    </section>

    <!-- Section: Apa saja Kemampuan DIMS ? -->
    <section id="kemampuan-dims" class="py-12 border-t border-gray-100">
        <div class="max-w-[1140px] mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Capabilities List -->
                <div>
                    <h3 class="text-2xl md:text-3xl font-light text-gray-800 mb-8">
                        @if($isEn)
                            What are <span class="text-defenxor-red font-medium">DIMS Capabilities ?</span>
                        @else
                            Apa saja <span class="text-defenxor-red font-medium">Kemampuan DIMS ?</span>
                        @endif
                    </h3>

                    <ul class="space-y-6 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">
                                    24/7 SOC Monitoring
                                </h4>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    @if($isEn)
                                        Monitor security activities in real-time, 24/7, with qualified security analyst support to detect suspicious activities faster.
                                    @else
                                        Pantau aktivitas keamanan secara real-time, 24/7, dengan dukungan security analyst untuk mendeteksi aktivitas mencurigakan lebih cepat.
                                    @endif
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">
                                    Intelligence-Driven Threat Detection & Response
                                </h4>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    @if($isEn)
                                        Identify threats faster with correlated log management, SIEM, threat intelligence, and behavioral analytics to prioritize risks.
                                    @else
                                        Identifikasi ancaman lebih cepat dengan korelasi log management, SIEM, threat intelligence, dan analisis aktivitas untuk memprioritaskan risiko.
                                    @endif
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h4 class="font-bold text-gray-900 text-base mb-1">
                                    Incident Response & Remediation
                                </h4>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    @if($isEn)
                                        When an incident occurs, the Defenxor team provides rapid response, containment, and remediation to minimize business impact.
                                    @else
                                        Saat terjadi insiden, tim Defenxor akan melakukan respons, containment, dan rapid remediation untuk meminimalkan dampak terhadap bisnis.
                                    @endif
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Right: DI-MS Logo Graphic -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/dims-di-ms.webp" alt="Defenxor Intelligence Managed Security" class="max-w-[340px] w-full h-auto object-contain" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section: MEMBANDINGKAN IN-HOUSE SOC VS MANAGED SOC -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-[1000px] mx-auto px-4">
            <h2 class="text-xl md:text-2xl font-light text-center uppercase tracking-[0.2em] text-gray-800 mb-10">
                {{ $isEn ? 'COMPARING IN-HOUSE SOC VS MANAGED SOC' : 'MEMBANDINGKAN IN-HOUSE SOC VS MANAGED SOC' }}
            </h2>

            <div class="overflow-x-auto shadow-sm rounded-xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="w-1/4 p-4"></th>
                            <th class="w-[37.5%] p-4 text-center font-bold text-gray-800 bg-[#e5e7eb] rounded-tl-xl text-sm md:text-base">
                                In-house SOC
                            </th>
                            <th class="w-[37.5%] p-4 text-center font-bold text-white bg-[#162647] rounded-tr-xl text-sm md:text-base">
                                DIMS
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr class="border-b border-gray-200">
                            <td class="p-4 font-bold text-gray-900 bg-transparent">
                                {{ $isEn ? 'Operations' : 'Operasional' }}
                            </td>
                            <td class="p-4 text-center text-gray-700 bg-[#f1f1f1] border-b border-white/60">
                                {{ $isEn ? 'Need to build internal shift and coverage' : 'Perlu membangun shift dan coverage internal' }}
                            </td>
                            <td class="p-4 text-center text-white bg-[#6c727f] border-b border-white/30 font-medium">
                                {{ $isEn ? '24/7 SOC Monitoring' : 'Monitoring SOC 24/7' }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="p-4 font-bold text-gray-900 bg-transparent">
                                Security Talent
                            </td>
                            <td class="p-4 text-center text-gray-700 bg-[#f1f1f1] border-b border-white/60">
                                {{ $isEn ? 'Recruit - Train - Retain your own team' : 'Rekrut - Latih - Pertahankan tim sendiri' }}
                            </td>
                            <td class="p-4 text-center text-white bg-[#6c727f] border-b border-white/30 font-medium">
                                {{ $isEn ? 'Supported by certified security professionals' : 'Didukung security professionals' }}
                            </td>
                        </tr>
                        <tr class="border-b border-gray-200">
                            <td class="p-4 font-bold text-gray-900 bg-transparent">
                                Security Technology
                            </td>
                            <td class="p-4 text-center text-gray-700 bg-[#f1f1f1] border-b border-white/60">
                                {{ $isEn ? 'Self-funded investment, integration, and management' : 'Investasi, integrasi, dan pengelolaan sendiri' }}
                            </td>
                            <td class="p-4 text-center text-white bg-[#6c727f] border-b border-white/30 font-medium">
                                {{ $isEn ? 'Managed security platform & SIEM' : 'Managed security platform & SIEM' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-4 font-bold text-gray-900 bg-transparent">
                                Incident Response
                            </td>
                            <td class="p-4 text-center text-gray-700 bg-[#f1f1f1] rounded-bl-xl">
                                {{ $isEn ? 'Dependent on internal readiness' : 'Bergantung pada kesiapan internal' }}
                            </td>
                            <td class="p-4 text-center text-white bg-[#6c727f] rounded-br-xl font-medium">
                                {{ $isEn ? 'Incident response & management support' : 'Dukungan incident response & management' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Section: MENGAPA DIMS? -->
    <section class="py-16 bg-white">
        <div class="max-w-[1140px] mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Wheel Diagram -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/DIMS-Main-Feature-1.webp" alt="Technology, Process, People Diagram" class="max-w-[420px] w-full h-auto object-contain" loading="lazy">
                </div>

                <!-- Right: Bullet Points -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-gray-800 uppercase mb-8">
                        {{ $isEn ? 'WHY DIMS?' : 'MENGAPA DIMS?' }}
                    </h2>

                    <ul class="space-y-6 text-gray-600">
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base mb-1">
                                    24/7 Security Monitoring
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    {{ $isEn ? 'Security is monitored in real-time and nonstop by SOC analysts.' : 'Keamanan dipantau real-time dan nonstop oleh SOC analyst.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base mb-1">
                                    {{ $isEn ? 'Certified Professional Team' : 'Tim Profesional Bersertifikat' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    {{ $isEn ? 'Supported by professionals with certifications and extensive experience in IT security.' : 'Didukung profesional dengan sertifikasi dan pengalaman di bidang IT security.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base mb-1">
                                    {{ $isEn ? 'Operations in Indonesia' : 'Operasional di Indonesia' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    {{ $isEn ? 'Indonesia-based operations to support localized compliance, security requirements, and closer access.' : 'Operasional berbasis di Indonesia untuk mendukung kebutuhan keamanan dan akses yang lebih dekat.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base mb-1">
                                    Scalable
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    {{ $isEn ? 'Services can be tailored to organizational needs without the complexity of building a SOC from scratch.' : 'Layanan dapat disesuaikan dengan kebutuhan organisasi tanpa kompleksitas membangun SOC dari awal.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-defenxor-red text-xl font-bold mr-3 leading-none">•</span>
                            <div>
                                <h3 class="font-bold text-gray-900 text-base mb-1">
                                    {{ $isEn ? 'Integrated' : 'Terintegrasi' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-gray-600">
                                    {{ $isEn ? 'Monitoring, log management, vulnerability management, and incident response within a single unified service.' : 'Monitoring, log management, vulnerability management, dan incident response dalam satu layanan.' }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Belum Siap Membangun SOC Sendiri? (CTA Banner) -->
    <section class="py-16 bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100 border-t border-gray-200">
        <div class="max-w-[1140px] mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Left: Text & Button -->
                <div class="text-left">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                        {{ $isEn ? 'Not Ready to Build Your Own SOC?' : 'Belum Siap Membangun SOC Sendiri?' }}
                    </h2>
                    <p class="text-gray-600 text-sm md:text-base leading-relaxed mb-6 max-w-lg">
                        {{ $isEn ? 'Gain 24/7 SOC capabilities without increasing team complexity, technology overhead, and operational costs.' : 'Dapatkan kapabilitas SOC 24/7 tanpa harus menambah kompleksitas tim, teknologi, dan biaya operasional.' }}
                    </p>
                    <div>
                        <a href="https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl" target="_blank" rel="noopener noreferrer" class="inline-block bg-defenxor-red text-white font-semibold text-sm px-6 py-3 rounded shadow-sm hover:bg-[#8e2427] transition-colors">
                            {{ $isEn ? 'Free SOC Consultation' : 'Konsultasi Kebutuhan SOC Gratis' }}
                        </a>
                    </div>
                </div>

                <!-- Right: 3D Illustration -->
                <div class="flex justify-center md:justify-end items-center">
                    <img src="/assets/img/dims-laptop-soc.webp" alt="SOC Security Operations Center Illustration" class="max-w-[360px] md:max-w-[420px] w-full h-auto object-contain drop-shadow-md" loading="lazy">
                </div>
            </div>
        </div>
    </section>
</main>
