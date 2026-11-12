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
                {{ $isEn ? 'Cyber Security Consultant Indonesia | Defenxor DISC' : 'Cyber Security Consultant Indonesia I Defenxor DISC' }}
            </h1>
            <p class="text-sm md:text-base text-slate-500 font-normal" style="color: #64748b; font-size: 15px; margin: 0;">
                Integrated Security Consulting
            </p>
        </div>
    </div>

    <!-- Hero Section (Plain White Background) -->
    <section class="py-12 md:py-16 bg-white" style="background-color: #ffffff; padding: 48px 16px;">
        <div class="max-w-[1140px] mx-auto text-center" style="max-width: 1140px; margin: 0 auto; text-align: center;">
            <h2 class="text-2xl md:text-3xl lg:text-[34px] font-semibold text-slate-900 leading-snug mb-4" style="color: #0f172a; font-size: 28px; line-height: 1.35; margin-bottom: 16px; font-weight: 600;">
                {{ $isEn ? 'Cyber Security Consultant Indonesia' : 'Cyber Security Consultant Indonesia' }}<br>
                {{ $isEn ? 'for Integrated Security' : 'untuk Keamanan Terintegrasi' }}
            </h2>
            <p class="max-w-3xl mx-auto text-sm md:text-[15px] text-slate-600 leading-relaxed mb-6 font-normal" style="max-width: 760px; margin: 0 auto 24px auto; color: #475569; font-size: 15px; line-height: 1.6;">
                @if($isEn)
                    Defenxor Intelligence Security Consulting (DISC) helps enterprises identify security gaps, manage risks, fulfill compliance requirements, and build an integrated, targeted security architecture.
                @else
                    Defenxor Intelligence Security Consulting (DISC) membantu enterprise mengidentifikasi celah keamanan, mengelola risiko, memenuhi compliance, dan membangun security architecture yang terintegrasi serta lebih terarah.
                @endif
            </p>
            <div>
                <a href="#apa-itu-disc" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; font-size: 14px; font-weight: 600; padding: 10px 24px; border-radius: 4px; text-decoration: none;">
                    Assess Your Security
                </a>
            </div>
        </div>
    </section>

    <!-- Section: Apa Itu Defenxor DISC ? -->
    <section id="apa-itu-disc" class="py-12 bg-white" style="background-color: #ffffff; padding: 48px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Text -->
                <div>
                    <h3 class="text-2xl md:text-3xl font-light text-slate-800 mb-6" style="color: #1e293b; font-size: 28px; font-weight: 300; margin-bottom: 24px;">
                        @if($isEn)
                            What is <span style="color: #ae3033; font-weight: 600;">Defenxor DISC ?</span>
                        @else
                            Apa Itu <span style="color: #ae3033; font-weight: 600;">Defenxor DISC ?</span>
                        @endif
                    </h3>
                    <p class="text-sm md:text-base leading-relaxed text-slate-600 mb-4" style="color: #475569; font-size: 15px; line-height: 1.7; margin-bottom: 16px;">
                        @if($isEn)
                            Defenxor Intelligence Security Consulting (DISC) is a cyber security consulting service that helps organizations understand their security posture, discover security gaps, and define targeted remediation steps.
                        @else
                            Defenxor Intelligence Security Consulting (DISC) adalah layanan cyber security consulting yang membantu perusahaan memahami kondisi keamanan, menemukan security gaps, dan menentukan langkah perbaikan yang tepat.
                        @endif
                    </p>
                    <p class="text-sm md:text-base leading-relaxed text-slate-600" style="color: #475569; font-size: 15px; line-height: 1.7; margin: 0;">
                        @if($isEn)
                            DISC covers security assessment, security audit, security advisory, penetration testing, incident response, and security certification to help enterprises establish a resilient security posture.
                        @else
                            DISC mencakup security assessment, security audit, security advisory, penetration testing, incident response, dan security certification untuk membantu enterprise membangun security posture yang lebih kuat.
                        @endif
                    </p>
                </div>

                <!-- Right: DISC Glasses Logo Graphic -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disc-logo.webp" alt="Defenxor Intelligence Security Consulting" class="max-w-[340px] w-full h-auto object-contain" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section: KENAPA DISC? (Shopping Cart Section) -->
    <section class="py-16" style="background-color: #f8fafc; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Shopping Cart Illustration -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disc-cart.webp" alt="DISC Capabilities" class="max-w-[320px] w-full h-auto object-contain" loading="lazy">
                </div>

                <!-- Right: Content & Button -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'WHY DISC?' : 'KENAPA DISC?' }}
                    </h2>

                    <ul class="space-y-3 mb-8 text-slate-700 text-sm md:text-[15px]" style="margin-bottom: 32px; list-style: disc; padding-left: 20px; color: #334155; font-size: 15px; line-height: 1.8;">
                        <li>{{ $isEn ? 'Identify security gaps' : 'Identifikasi security gaps' }}</li>
                        <li>{{ $isEn ? 'Prioritize critical risks' : 'Prioritaskan risiko kritikal' }}</li>
                        <li>{{ $isEn ? 'Comply with relevant regulations' : 'Sesuai dengan regulasi yang berlaku' }}</li>
                        <li>{{ $isEn ? 'Build a more resilient security posture' : 'Membangun security posture yang lebih andal' }}</li>
                    </ul>

                    <div>
                        <a href="https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl" target="_blank" rel="noopener noreferrer" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 600;">
                            {{ $isEn ? 'Consult Your Security Needs' : 'Konsultasikan Kebutuhan Security Anda' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: MENGAPA PILIH DISC? (3 Circles Section) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: 4 Reason Points -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'WHY CHOOSE DISC?' : 'MENGAPA PILIH DISC?' }}
                    </h2>

                    <div class="space-y-6">
                        <!-- Point 1 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Discover Vulnerabilities Before They Become Incidents' : 'Temukan Celah Sebelum Menjadi Insiden' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600 mb-1.5" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-bottom: 6px; padding-left: 14px;">
                                {{ $isEn ? 'Evaluate security posture, systems, applications, and configurations to identify vulnerabilities and areas requiring remediation.' : 'Evaluasi security posture, sistem, aplikasi, dan konfigurasi untuk mengidentifikasi vulnerability dan area yang perlu diperbaiki.' }}
                            </p>
                            <p class="text-xs text-slate-700 font-semibold" style="color: #334155; font-size: 12px; font-weight: 600; padding-left: 14px; margin: 0;">
                                Security Audit • Vulnerability Assessment • Penetration Testing
                            </p>
                        </div>

                        <!-- Point 2 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Comprehensive Security Support' : 'Bantuan Perihal Security' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600 mb-1.5" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-bottom: 6px; padding-left: 14px;">
                                {{ $isEn ? 'Identify risks, prioritize mitigation efforts, and align security controls with compliance and regulatory demands. DISC also encompasses security policy development and certification support, including PCI-DSS and ISO 27001.' : 'Identifikasi risiko, susun prioritas mitigasi, dan selaraskan kontrol keamanan dengan kebutuhan compliance dan regulasi yang relevan. DISC existing juga mencakup security policy development dan certification support, termasuk PCI-DSS dan ISO 27001.' }}
                            </p>
                            <p class="text-xs text-slate-700 font-semibold" style="color: #334155; font-size: 12px; font-weight: 600; padding-left: 14px; margin: 0;">
                                Risk Assessment • Security Policy • ISO 27001 • PCI-DSS • {{ $isEn ? 'Personal Data Protection' : 'UU PDP' }}
                            </p>
                        </div>

                        <!-- Point 3 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Integrated Security Architecture' : 'Arsitektur Keamanan Terintegrasi' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600 mb-1.5" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-bottom: 6px; padding-left: 14px;">
                                {{ $isEn ? 'Design security architectures that seamlessly integrate technologies, controls, and workflows for end-to-end organizational protection.' : 'Rancang security architecture yang mengintegrasikan teknologi, kontrol, dan proses agar perlindungan lebih menyeluruh sesuai kebutuhan bisnis.' }}
                            </p>
                            <p class="text-xs text-slate-700 font-semibold" style="color: #334155; font-size: 12px; font-weight: 600; padding-left: 14px; margin: 0;">
                                Architecture Review • Security Strategy • Technology Alignment
                            </p>
                        </div>

                        <!-- Point 4 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Security Advisory & Incident Response' : 'Security Advisory & Incident Response' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600 mb-1.5" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-bottom: 6px; padding-left: 14px;">
                                {{ $isEn ? 'Receive dedicated expert advisory from cybersecurity consultants along with rapid response support to manage and mitigate incidents.' : 'Dapatkan advisory dari security consultant dan dukungan response untuk menangani serta memitigasi insiden.' }}
                            </p>
                            <p class="text-xs text-slate-700 font-semibold" style="color: #334155; font-size: 12px; font-weight: 600; padding-left: 14px; margin: 0;">
                                Security Advisory • Incident Response • SOC Advisory
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: 3 Circles Diagram -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disc-circles.webp" alt="DISC Architecture Pillars" class="max-w-[380px] w-full h-auto object-contain" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section: BAGAIMANA CARA KERJA DISC? (Digital City Section) -->
    <section class="py-16" style="background-color: #f8fafc; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Digital City Photo -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disc-city.webp" alt="Cyber Security Infrastructure" class="max-w-[440px] w-full h-auto object-cover rounded-xl shadow-md" loading="lazy">
                </div>

                <!-- Right: 4 Steps -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'HOW DOES DISC WORK?' : 'BAGAIMANA CARA KERJA DISC?' }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Discovery & Assessment
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Map your security landscape by identifying vulnerabilities, exposure points, and security gaps.' : 'Petakan kondisi keamanan dengan mengidentifikasi vulnerability, exposure, dan security gaps.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Strategy & Architecture
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Define and establish security priorities aligned with risk thresholds and organizational business goals.' : 'Bangun dan tentukan prioritas keamanan berdasarkan risiko serta arah kebutuhan bisnis.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Implementation Roadmap' : 'Roadmap Implementasi' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Develop an actionable roadmap enabling internal teams to translate strategic recommendations into tangible results.' : 'Susun roadmap yang dapat digunakan tim internal untuk mengubah rekomendasi menjadi aksi.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Continuous Review & Advisory' : 'Review & Advisory Berkelanjutan' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Regularly review security postures to ensure sustainable resilience as evolving threat landscapes and business requirements change.' : 'Review security secara berkala untuk memastikan posture terus berkembang sesuai riset dan kebutuhan bisnis.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: MENGAPA DEFENXOR? (Team Photo Section) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: 4 Highlights -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'WHY DEFENXOR?' : 'MENGAPA DEFENXOR?' }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Certified Security Expert Team' : 'Tim Ahli Keamanan Bersertifikat' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Backed by experienced professionals holding industry-leading certifications including CISSP, CISA, ISO 27001 LA, ISO 20000 LA, OSCP, CEH Master, CHFI, and PCI QSA.' : 'Didukung profesional dengan sertifikasi seperti CISSP, CISA, ISO 27001LA, ISO 20000 LA, OSCP, CEH Master, CHFI dan PCI QSA.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'International Frameworks & Standards' : 'Standar Internasional' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Assessment and testing methodologies strictly adhere to globally recognized frameworks including OWASP, CIS Controls, and NIST.' : 'Pendekatan assessment dan testing mengacu pada framework seperti OWASP, CIS dan NIST.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Actionable Recommendations' : 'Rekomendasi Dapat Ditindaklanjuti' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'More than just finding vulnerabilities, you receive in-depth analysis, comprehensive risk ratings, and actionable remediation roadmaps.' : 'Anda bukan sekadar menemukan masalah, tetapi juga mendapatkan analisis lengkap, risk rating, dan rekomendasi yang dapat ditindaklanjuti.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Tailored to Organizational Needs' : 'Sesuai Kebutuhan Organisasi' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Consulting engagements are customized to your specific security objectives, compliance requirements, operational scale, and infrastructure realities.' : 'Engagement disesuaikan dengan kebutuhan security, compliance, skala, dan kondisi organisasi.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: Team Illustration -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disc-team.webp" alt="Defenxor Security Consultants Team" class="max-w-[440px] w-full h-auto object-cover rounded-xl shadow-md" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Frequently Asked Question (FAQ) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 48px 16px 64px 16px;">
        <div style="max-width: 960px; margin: 0 auto;">
            <h2 class="text-2xl md:text-3xl font-light text-center text-slate-800 mb-12" style="color: #1e293b; font-size: 26px; font-weight: 400; text-align: center; margin-bottom: 40px;">
                Frequently Asked Question
            </h2>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 16px;">
                <!-- FAQ 1 -->
                <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                    <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                        <span style="padding-right: 16px;">{{ $isEn ? 'Why do enterprises in Indonesia need a cyber security consultant?' : 'Mengapa perusahaan enterprise di Indonesia butuh cyber security consultant?' }}</span>
                        <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                        {{ $isEn ? 'Cyber threats in Indonesia continue to rise, and regulations (such as UU PDP) mandate strict data protection. Cybersecurity consultants help identify blind spots missed by internal teams, provide independent validation, and ensure optimal regulatory compliance.' : 'Ancaman siber di Indonesia terus meningkat dan regulasi (seperti UU PDP) mewajibkan perlindungan data yang ketat. Cyber security consultant membantu mengidentifikasi celah yang terlewat oleh tim internal, memberikan panduan independen, dan memastikan kepatuhan regulasi secara optimal.' }}
                    </p>
                </details>

                <!-- FAQ 2 -->
                <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                    <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                        <span style="padding-right: 16px;">{{ $isEn ? 'When should an enterprise conduct a security assessment?' : 'Kapan perusahaan perlu melakukan security assessment?' }}</span>
                        <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                        {{ $isEn ? 'Security assessments should be performed periodically (at least 1–2 times a year), before launching new critical systems or applications, after major infrastructure updates, or when preparing for compliance audits (such as ISO 27001 and PCI-DSS).' : 'Security assessment disarankan dilakukan secara berkala (minimal 1-2 kali setahun), sebelum peluncuran sistem atau aplikasi baru, setelah perubahan infrastruktur besar, atau saat mempersiapkan sertifikasi kepatuhan (seperti ISO 27001 dan PCI-DSS).' }}
                    </p>
                </details>

                <!-- FAQ 3 -->
                <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                    <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                        <span style="padding-right: 16px;">{{ $isEn ? 'What does a Cyber Security Consultant do?' : 'Apa yang dilakukan Cyber Security Consultant?' }}</span>
                        <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                        {{ $isEn ? 'Consultants evaluate security architectures, conduct penetration testing, assess compliance risks, design security governance policies, and provide practical roadmaps to fortify your organization\'s cyber defense posture.' : 'Konsultan mengevaluasi arsitektur keamanan, melakukan penetration testing, menilai risiko kepatuhan, merancang kebijakan keamanan informasi, dan memberikan rekomendasi praktis untuk memperkuat postur pertahanan siber organisasi Anda.' }}
                    </p>
                </details>

                <!-- FAQ 4 -->
                <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                    <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                        <span style="padding-right: 16px;">{{ $isEn ? 'Is DISC only for large enterprises?' : 'Apakah DISC hanya untuk perusahaan besar?' }}</span>
                        <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                    </summary>
                    <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                        {{ $isEn ? 'No. DISC services are designed to be flexible and scalable, adaptable for mid-market businesses to large enterprise organizations across banking, fintech, healthcare, logistics, and digital services.' : 'Tidak. Layanan DISC dirancang fleksibel dan scalable, dapat disesuaikan untuk perusahaan skala menengah hingga enterprise besar di berbagai sektor seperti perbankan, fintech, logistik, dan e-commerce.' }}
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Section: Pahami Risiko Security Posture (CTA Banner - Full Width Gray Background, Contained Content) -->
    <section class="w-full" style="background: linear-gradient(105deg, #f8f9fa 0%, #eaedf1 50%, #d5dbe2 100%); padding: 64px 16px; border-top: 1px solid #e2e8f0;">
        <div style="max-width: 1040px; margin: 0 auto; padding: 0 16px;">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Left: Text & Button -->
                <div class="text-left">
                    <h2 class="text-2xl md:text-3xl font-semibold mb-3" style="color: #0f172a; margin-bottom: 12px; font-weight: 600; font-size: 28px;">
                        {{ $isEn ? 'Understand Your Security Posture Risks' : 'Pahami Risiko Security Posture' }}
                    </h2>
                    <p class="text-sm md:text-base leading-relaxed mb-6 max-w-lg" style="color: #475569; margin-bottom: 24px; line-height: 1.6; font-size: 15px;">
                        {{ $isEn ? 'Discover vulnerabilities, understand risks, and define your next strategic steps with DISC.' : 'Temukan celah, pahami risiko, dan tentukan langkah berikutnya bersama DISC.' }}
                    </p>
                    <div>
                        <a href="https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl" target="_blank" rel="noopener noreferrer" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 600;">
                            {{ $isEn ? 'Free Consultation' : 'Konsultasi Gratis' }}
                        </a>
                    </div>
                </div>

                <!-- Right: 3D Red Shield Illustration -->
                <div class="flex justify-center md:justify-end items-center">
                    <img src="/assets/img/disc-shield.webp" alt="Defenxor Security Shield" class="w-full h-auto object-contain drop-shadow-md" style="max-width: 260px;" loading="lazy">
                </div>
            </div>
        </div>
    </section>
</div>
