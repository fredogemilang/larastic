@php
    $b = $page->content_blocks ?? [];
    $loc = $locale ?? app()->getLocale();
    $isEn = $loc === 'en';
@endphp

<div class="w-full bg-white text-gray-800">
    <!-- Top Title & Subtitle Breadcrumb (Soft Gray Background) -->
    <div style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 36px 16px; text-align: center;">
        <div style="max-width: 1140px; margin: 0 auto;">
            <h1 class="text-2xl md:text-3xl font-normal text-slate-800 mb-1.5" style="color: #1e293b; font-size: 26px; font-weight: 400; margin-bottom: 6px;">
                {{ $isEn ? 'Managed Security Service Provider (MSSP) 24/7 SOC | Defenxor DIMS' : 'Managed Security Service Provider (MSSP) 24/7 SOC I Defenxor DIMS' }}
            </h1>
            <p class="text-sm md:text-base text-slate-500 font-normal" style="color: #64748b; font-size: 15px; margin: 0;">
                Managed Security Service Provider (MSSP)
            </p>
        </div>
    </div>

    <!-- Hero Section (Plain White Background) -->
    <section class="py-12 md:py-16 bg-white" style="background-color: #ffffff; padding: 48px 16px;">
        <div class="max-w-[1140px] mx-auto text-center" style="max-width: 1140px; margin: 0 auto; text-align: center;">
            <h2 class="text-2xl md:text-3xl lg:text-[34px] font-semibold text-slate-900 leading-snug mb-4" style="color: #0f172a; font-size: 28px; line-height: 1.35; margin-bottom: 16px; font-weight: 600;">
                Managed Security Service Provider (MSSP) 24/7 SOC<br>
                Defenxor Intelligence Managed Security (DIMS)
            </h2>
            <p class="max-w-3xl mx-auto text-sm md:text-[15px] text-slate-600 leading-relaxed mb-6 font-normal" style="max-width: 760px; margin: 0 auto 24px auto; color: #475569; font-size: 15px; line-height: 1.6;">
                @if($isEn)
                    Defenxor Intelligence Managed Security (DIMS) is a Managed Security Service Provider (MSSP) supported by a 24/7 Security Operations Center (SOC) to help organizations detect threats, monitor IT environments, and respond to incidents rapidly.
                @else
                    Defenxor Intelligence Managed Security (DIMS) adalah layanan Managed Service Provider (MSSP) didukung Security Operations Center (SOC) 24/7 untuk membantu perusahaan mendeteksi ancaman, memantau lingkungan IT, dan merespons insiden dengan cepat.
                @endif
            </p>
            <div>
                <a href="#kemampuan-dims" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; font-size: 14px; font-weight: 600; padding: 10px 24px; border-radius: 4px; text-decoration: none;">
                    {{ $isEn ? 'Explore Managed SOC' : 'Jelajahi Managed SOC' }}
                </a>
            </div>
        </div>
    </section>

    <!-- Section: Apa saja Kemampuan DIMS ? -->
    <section id="kemampuan-dims" class="py-12 bg-white" style="background-color: #ffffff;">
        <div class="max-w-[1140px] mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Capabilities List -->
                <div>
                    <h3 class="text-2xl md:text-3xl font-light text-slate-800 mb-8">
                        @if($isEn)
                            What are <span style="color: #ae3033; font-weight: 600;">DIMS Capabilities ?</span>
                        @else
                            Apa saja <span style="color: #ae3033; font-weight: 600;">Kemampuan DIMS ?</span>
                        @endif
                    </h3>

                    <ul class="space-y-6">
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h4 class="font-bold text-slate-900 text-base mb-1">
                                    24/7 SOC Monitoring
                                </h4>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    @if($isEn)
                                        Monitor security activities in real-time, 24/7, with qualified security analyst support to detect suspicious activities faster.
                                    @else
                                        Pantau aktivitas keamanan secara real-time, 24/7, dengan dukungan security analyst untuk mendeteksi aktivitas mencurigakan lebih cepat.
                                    @endif
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h4 class="font-bold text-slate-900 text-base mb-1">
                                    Intelligence-Driven Threat Detection & Response
                                </h4>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    @if($isEn)
                                        Identify threats faster with correlated log management, SIEM, threat intelligence, and behavioral analytics to prioritize risks.
                                    @else
                                        Identifikasi ancaman lebih cepat dengan korelasi log management, SIEM, threat intelligence, dan analisis aktivitas untuk memprioritaskan risiko.
                                    @endif
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h4 class="font-bold text-slate-900 text-base mb-1">
                                    Incident Response & Remediation
                                </h4>
                                <p class="text-sm leading-relaxed text-slate-600">
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
                    <img src="/assets/img/dims-di-ms.webp" alt="Defenxor Intelligence Managed Security" class="max-w-[320px] w-full h-auto object-contain" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section: MEMBANDINGKAN IN-HOUSE SOC VS MANAGED SOC -->
    <section class="py-16" style="background-color: #f8fafc; padding: 64px 16px;">
        <div style="max-width: 700px; margin: 0 auto;">
            <h2 class="text-xl md:text-2xl font-light text-center uppercase tracking-[0.2em] mb-10" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; text-align: center; margin-bottom: 32px; font-weight: 300;">
                {{ $isEn ? 'COMPARING IN-HOUSE SOC VS MANAGED SOC' : 'MEMBANDINGKAN IN-HOUSE SOC VS MANAGED SOC' }}
            </h2>

            <div class="overflow-x-auto">
                <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 540px;">
                    <thead>
                        <tr>
                            <th style="width: 25%; padding: 14px 16px; border: none; background: transparent;"></th>
                            <th style="width: 37.5%; padding: 14px 16px; text-align: center; font-weight: 700; font-size: 14px; color: #1e293b; background-color: #cbd5e1; border: none;">
                                In-house SOC
                            </th>
                            <th style="width: 37.5%; padding: 14px 16px; text-align: center; font-weight: 700; font-size: 14px; color: #ffffff; background-color: #162647; border: none;">
                                DIMS
                            </th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13.5px;">
                        <tr>
                            <td style="padding: 15px 16px; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; background: transparent;">
                                {{ $isEn ? 'Operations' : 'Operasional' }}
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #334155; background-color: #f1f2f4; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #ffffff;">
                                {{ $isEn ? 'Need to build internal shift and coverage' : 'Perlu membangun shift dan coverage internal' }}
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #ffffff; background-color: #64748b; font-weight: 400; border-bottom: 1px solid rgba(255,255,255,0.25);">
                                {{ $isEn ? '24/7 SOC Monitoring' : 'Monitoring SOC 24/7' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 15px 16px; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; background: transparent;">
                                Security Talent
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #334155; background-color: #f1f2f4; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #ffffff;">
                                {{ $isEn ? 'Recruit - Train - Retain your own team' : 'Rekrut - Latih - Pertahankan tim sendiri' }}
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #ffffff; background-color: #64748b; font-weight: 400; border-bottom: 1px solid rgba(255,255,255,0.25);">
                                {{ $isEn ? 'Supported by certified security professionals' : 'Didukung security professionals' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 15px 16px; font-weight: 700; color: #1e293b; border-bottom: 1px solid #e2e8f0; background: transparent;">
                                Security Technology
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #334155; background-color: #f1f2f4; border-bottom: 1px solid #e2e8f0; border-right: 1px solid #ffffff;">
                                {{ $isEn ? 'Self-funded investment, integration, and management' : 'Investasi, integrasi, dan pengelolaan sendiri' }}
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #ffffff; background-color: #64748b; font-weight: 400; border-bottom: 1px solid rgba(255,255,255,0.25);">
                                {{ $isEn ? 'Managed security platform & SIEM' : 'Managed security platform & SIEM' }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 15px 16px; font-weight: 700; color: #1e293b; border: none; background: transparent;">
                                Incident Response
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #334155; background-color: #f1f2f4; border: none; border-right: 1px solid #ffffff;">
                                {{ $isEn ? 'Dependent on internal readiness' : 'Bergantung pada kesiapan internal' }}
                            </td>
                            <td style="padding: 15px 16px; text-align: center; color: #ffffff; background-color: #64748b; font-weight: 400; border: none;">
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
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8">
                        {{ $isEn ? 'WHY DIMS?' : 'MENGAPA DIMS?' }}
                    </h2>

                    <ul class="space-y-6">
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">
                                    24/7 Security Monitoring
                                </h3>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $isEn ? 'Security is monitored in real-time and nonstop by SOC analysts.' : 'Keamanan dipantau real-time dan nonstop oleh SOC analyst.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">
                                    {{ $isEn ? 'Certified Professional Team' : 'Tim Profesional Bersertifikat' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $isEn ? 'Supported by professionals with certifications and extensive experience in IT security.' : 'Didukung profesional dengan sertifikasi dan pengalaman di bidang IT security.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">
                                    {{ $isEn ? 'Operations in Indonesia' : 'Operasional di Indonesia' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $isEn ? 'Indonesia-based operations to support localized compliance, security requirements, and closer access.' : 'Operasional berbasis di Indonesia untuk mendukung kebutuhan keamanan dan akses yang lebih dekat.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">
                                    Scalable
                                </h3>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $isEn ? 'Services can be tailored to organizational needs without the complexity of building a SOC from scratch.' : 'Layanan dapat disesuaikan dengan kebutuhan organisasi tanpa kompleksitas membangun SOC dari awal.' }}
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="text-xl font-bold mr-3 leading-none select-none" style="color: #ae3033;">•</span>
                            <div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">
                                    {{ $isEn ? 'Integrated' : 'Terintegrasi' }}
                                </h3>
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $isEn ? 'Monitoring, log management, vulnerability management, and incident response within a single unified service.' : 'Monitoring, log management, vulnerability management, dan incident response dalam satu layanan.' }}
                                </p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Belum Siap Membangun SOC Sendiri? (CTA Banner - Full Width Gray Background, Contained Content) -->
    <section class="w-full" style="background: linear-gradient(105deg, #f8f9fa 0%, #eaedf1 50%, #d5dbe2 100%); padding: 64px 16px; border-top: 1px solid #e2e8f0;">
        <div style="max-width: 1040px; margin: 0 auto; padding: 0 16px;">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Left: Text & Button -->
                <div class="text-left">
                    <h2 class="text-2xl md:text-3xl font-bold mb-3" style="color: #0f172a; margin-bottom: 12px; font-weight: 700; font-size: 26px;">
                        {{ $isEn ? 'Not Ready to Build Your Own SOC?' : 'Belum Siap Membangun SOC Sendiri?' }}
                    </h2>
                    <p class="text-sm md:text-base leading-relaxed mb-6 max-w-lg" style="color: #475569; margin-bottom: 24px; line-height: 1.6; font-size: 15px;">
                        {{ $isEn ? 'Gain 24/7 SOC capabilities without increasing team complexity, technology overhead, and operational costs.' : 'Dapatkan kapabilitas SOC 24/7 tanpa harus menambah kompleksitas tim, teknologi, dan biaya operasional.' }}
                    </p>
                    <div>
                        <a href="https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl" target="_blank" rel="noopener noreferrer" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 600;">
                            {{ $isEn ? 'Free SOC Consultation' : 'Konsultasi Kebutuhan SOC Gratis' }}
                        </a>
                    </div>
                </div>

                <!-- Right: 3D Illustration -->
                <div class="flex justify-center md:justify-end items-center">
                    <img src="/assets/img/dims-laptop-soc.webp" alt="SOC Security Operations Center Illustration" class="w-full h-auto object-contain" style="max-width: 380px;" loading="lazy">
                </div>
            </div>
        </div>
    </section>
</div>
