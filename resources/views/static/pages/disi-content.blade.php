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
                {{ $isEn ? 'Comprehensive Security Integrator & Infrastructure | Defenxor DISI' : 'Comprehensive Security Integrator & Infrastructure I Defenxor DISI' }}
            </h1>
            <p class="text-sm md:text-base text-slate-500 font-normal" style="color: #64748b; font-size: 15px; margin: 0;">
                Comprehensive Security Integrator
            </p>
        </div>
    </div>

    <!-- Hero Section (Plain White Background) -->
    <section class="py-12 md:py-16 bg-white" style="background-color: #ffffff; padding: 48px 16px;">
        <div class="max-w-[1140px] mx-auto text-center" style="max-width: 1140px; margin: 0 auto; text-align: center;">
            <h2 class="text-2xl md:text-3xl lg:text-[34px] font-semibold text-slate-900 leading-snug mb-4" style="color: #0f172a; font-size: 28px; line-height: 1.35; margin-bottom: 16px; font-weight: 600;">
                Comprehensive Security Integrator for Enterprise
            </h2>
            <p class="max-w-3xl mx-auto text-sm md:text-[15px] text-slate-600 leading-relaxed mb-6 font-normal" style="max-width: 760px; margin: 0 auto 24px auto; color: #475569; font-size: 15px; line-height: 1.6;">
                @if($isEn)
                    Defenxor Intelligence Security Integrator (DISI) unifies diverse cybersecurity technologies into a cohesive, integrated defense ecosystem.
                @else
                    Defenxor Intelligence Security Integrator (DISI) menyatukan berbagai teknologi keamanan menjadi satu defense ecosystem yang terintegrasi.
                @endif
            </p>
            <div>
                <a href="#apa-itu-disi" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; font-size: 14px; font-weight: 600; padding: 10px 24px; border-radius: 4px; text-decoration: none;">
                    Build Your Security Ecosystem
                </a>
            </div>
        </div>
    </section>

    <!-- Section 1: Apa Itu Defenxor DISI ? -->
    <section id="apa-itu-disi" class="py-12 bg-white" style="background-color: #ffffff; padding: 48px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Text -->
                <div>
                    <h3 class="text-2xl md:text-3xl font-light text-slate-800 mb-6" style="color: #1e293b; font-size: 28px; font-weight: 300; margin-bottom: 24px;">
                        @if($isEn)
                            What is <span style="color: #ae3033; font-weight: 600;">Defenxor DISI ?</span>
                        @else
                            Apa Itu <span style="color: #ae3033; font-weight: 600;">Defenxor DISI ?</span>
                        @endif
                    </h3>
                    <p class="text-sm md:text-base leading-relaxed text-slate-600 mb-4" style="color: #475569; font-size: 15px; line-height: 1.7; margin-bottom: 16px;">
                        @if($isEn)
                            Defenxor Intelligence Security Integrator (DISI) helps enterprises in Indonesia design, implement, integrate, and optimize multi-vendor security infrastructures to be more scalable, effective, and prepared for evolving threats.
                        @else
                            Defenxor Intelligence Security Integrator (DISI) membantu enterprise di Indonesia merancang, mengimplementasikan, mengintegrasikan, dan mengoptimalkan infrastruktur keamanan multi-vendor agar lebih scalable, efektif, dan siap menghadapi ancaman yang terus berkembang.
                        @endif
                    </p>
                </div>

                <!-- Right: DISI Puzzle Graphic -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disi-logo.webp" alt="Defenxor Intelligence Security Integrator" class="max-w-[360px] w-full h-auto object-contain" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 2: MENGAPA PILIH DISI? (Differentiation Chart) -->
    <section class="py-16" style="background-color: #f8fafc; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Points -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'WHY CHOOSE DISI?' : 'MENGAPA PILIH DISI?' }}
                    </h2>

                    <div class="space-y-6">
                        <!-- Point 1 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Interconnected Security Tools' : 'Security Tools Saling Terhubung' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'DISI unites various security tools across network, endpoint, identity, cloud, and threat intelligence into an integrated, connected security ecosystem.' : 'DISI menyatukan berbagai security tools untuk network, endpoint, identity, cloud, dan threat intelligence menjadi satu ekosistem keamanan yang terintegrasi dan saling terhubung.' }}
                            </p>
                        </div>

                        <!-- Point 2 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Design
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'DISI is engineered with security architecture tailored to business and infrastructure needs.' : 'DISI dirancang dengan arsitektur security sesuai kebutuhan bisnis dan infrastruktur.' }}
                            </p>
                        </div>

                        <!-- Point 3 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Integrate
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Seamlessly connect multi-vendor technologies into a single cohesive ecosystem.' : 'Hubungkan teknologi multi-vendor menjadi satu ekosistem.' }}
                            </p>
                        </div>

                        <!-- Point 4 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Optimize
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Continuously optimize configurations, performance, and security posture.' : 'Optimalkan konfigurasi, performa, dan security posture secara berkelanjutan.' }}
                            </p>
                        </div>

                        <!-- Point 5 -->
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Security Mindset
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'We are dedicated cybersecurity experts. Our team not only understands implementation, but also how attackers operate, and how to defend against them effectively.' : 'Kami adalah perusahaan yang fokus pada keamanan siber. Tim bukan hanya paham cara implementasi, tapi juga mengerti bagaimana cara kerja penyerang, dan mencegahnya.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: Chart Image -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disi-differentiation.webp" alt="DISI Differentiation Chart" class="max-w-[480px] w-full h-auto object-contain drop-shadow-sm" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: BAGAIMANA CARA KERJA DISI? (Architecture Diagram) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Architecture Diagram -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disi-architecture.webp" alt="DISI Architecture Framework" class="max-w-[480px] w-full h-auto object-contain drop-shadow-sm" loading="lazy">
                </div>

                <!-- Right: 5 Steps -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'HOW DOES DISI WORK?' : 'BAGAIMANA CARA KERJA DISI?' }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Assess' : 'Access' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Understand infrastructure state, security tools, and business requirements.' : 'Pahami kondisi infrastruktur, security tools, dan kebutuhan bisnis.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Design
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Develop tailored security architecture and determine optimal technologies.' : 'Susun security architecture dan teknologi yang paling sesuai.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Integrate
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Implement and interconnect multi-vendor security solutions.' : 'Implementasikan dan hubungkan solusi multi-vendor.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Optimize
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Fine-tune configuration, hardening, and performance.' : 'Fine-tune configuration, hardening, dan performance.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • Evolve
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Adapt and grow the security ecosystem alongside business demands and emerging threats.' : 'Kembangkan security ecosystem dengan kebutuhan bisnis dan ancaman baru.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: MENGAPA HARUS MENGINTEGRASIKAN SECURITY STACK? -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto" style="max-width: 1140px; margin: 0 auto;">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: 4 Points -->
                <div>
                    <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-8" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 28px;">
                        {{ $isEn ? 'WHY INTEGRATE YOUR SECURITY STACK?' : 'MENGAPA HARUS MENGINTEGRASIKAN SECURITY STACK?' }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Reduce Siloed Dependencies' : 'Mengurangi Ketergantungan Silo' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Connect diverse security technologies within a single ecosystem.' : 'Hubungkan berbagai security technologies dalam satu ecosystem.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Eliminate Security Gaps' : 'Kurangi Security Gaps' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Minimize vulnerabilities caused by fragmented tools and disconnected infrastructure.' : 'Kurangi celah yang muncul akibat tools dan infrastructure yang berjalan terpisah.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Streamline Operations' : 'Sederhanakan Operasional' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Make the security environment easier to manage, monitor, and optimize.' : 'Buat environment security lebih mudah dikelola dan dioptimalkan.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="font-bold text-slate-900 text-base mb-1" style="color: #0f172a; font-size: 15px; font-weight: 700; margin-bottom: 4px;">
                                • {{ $isEn ? 'Support Business Growth' : 'Mendukung Pertumbuhan Bisnis' }}
                            </h3>
                            <p class="text-sm leading-relaxed text-slate-600" style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; padding-left: 14px;">
                                {{ $isEn ? 'Establish an agile architecture that scales with enterprise demands.' : 'Bangun architecture yang dapat berkembang mengikuti kebutuhan enterprise.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right: 3D Stack Graphic -->
                <div class="flex justify-center items-center">
                    <img src="/assets/img/disi-security-stack.webp" alt="Integrated Security Stack" class="max-w-[440px] w-full h-auto object-cover rounded-xl shadow-md" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: PERBEDAAN DISI DAN DIMS (Comparison Table) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 64px 16px;">
        <div class="max-w-[1140px] mx-auto text-center" style="max-width: 1140px; margin: 0 auto; text-align: center;">
            <h2 class="text-xl md:text-2xl font-light tracking-[0.2em] text-slate-800 uppercase mb-12" style="color: #1e293b; font-size: 20px; letter-spacing: 0.18em; font-weight: 300; margin-bottom: 40px;">
                {{ $isEn ? 'DIFFERENCE BETWEEN DISI AND DIMS' : 'PERBEDAAN DISI DAN DIMS' }}
            </h2>

            <div style="max-width: 720px; margin: 0 auto; display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                <!-- Column 1: DISI -->
                <div style="display: flex; flex-direction: column;">
                    <div style="background-color: #1b2a47; color: #ffffff; padding: 14px 16px; font-weight: 700; font-size: 15px; letter-spacing: 0.05em; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                        DISI
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Security integration
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Design & architecture
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Deployment & implementation
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Multi-vendor integration
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                        Security optimization
                    </div>
                </div>

                <!-- Column 2: DIMS -->
                <div style="display: flex; flex-direction: column;">
                    <div style="background-color: #1b2a47; color: #ffffff; padding: 14px 16px; font-weight: 700; font-size: 15px; letter-spacing: 0.05em; border-top-left-radius: 4px; border-top-right-radius: 4px;">
                        DIMS
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Managed security
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Monitoring & management
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Ongoing security operations
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom: 1px solid rgba(255,255,255,0.15);">
                        Managed security services
                    </div>
                    <div style="background-color: #6e7987; color: #ffffff; padding: 14px 16px; font-size: 13.5px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px;">
                        Continuous monitoring & response
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 6: Frequently Asked Question (FAQ) -->
    <section class="py-16 bg-white" style="background-color: #ffffff; padding: 48px 16px 64px 16px;">
        <div style="max-width: 960px; margin: 0 auto;">
            <h2 class="text-2xl md:text-3xl font-light text-center text-slate-800 mb-12" style="color: #1e293b; font-size: 26px; font-weight: 400; text-align: center; margin-bottom: 40px;">
                Frequently Asked Question
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 16px; align-items: start;">
                <!-- Column 1 (3 items) -->
                <div class="space-y-4" style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- FAQ 1 -->
                    <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                        <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                            <span style="padding-right: 16px;">{{ $isEn ? 'What is a Security Integrator?' : 'Apa itu Security Integrator?' }}</span>
                            <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            {{ $isEn ? 'A security integrator is a specialized partner that designs, deploys, and harmonizes multiple security technologies to operate cohesively as a unified security ecosystem.' : 'Security integrator adalah partner yang merancang dan menggabungkan berbagai teknologi keamanan agar dapat bekerja sebagai satu ekosistem yang terintegrasi.' }}
                        </p>
                    </details>

                    <!-- FAQ 2 -->
                    <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                        <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                            <span style="padding-right: 16px;">{{ $isEn ? "What is Defenxor's role as a Security Integrator?" : 'Apa peran Defenxor sebagai Security Integrator?' }}</span>
                            <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            {{ $isEn ? 'Defenxor empowers enterprises to design, implement, integrate, and optimize diverse security technologies tailored to specific infrastructure and business requirements.' : 'Defenxor membantu enterprise merancang, mengimplementasikan, mengintegrasikan, dan mengoptimalkan berbagai teknologi keamanan sesuai kebutuhan infrastruktur dan bisnis.' }}
                        </p>
                    </details>

                    <!-- FAQ 3 -->
                    <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                        <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                            <span style="padding-right: 16px;">{{ $isEn ? 'What are the benefits of multi-vendor security integration?' : 'Apa manfaat multi-vendor security integration?' }}</span>
                            <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            {{ $isEn ? 'Multi-vendor integration dismantles security silos, elevates visibility, and enables disparate security technologies to work in seamless harmony within a single architecture.' : 'Integrasi multi-vendor membantu mengurangi security silo, meningkatkan visibility, dan membuat berbagai security technologies bekerja lebih harmonis dalam satu architecture.' }}
                        </p>
                    </details>
                </div>

                <!-- Column 2 (2 items) -->
                <div class="space-y-4" style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- FAQ 4 -->
                    <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                        <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                            <span style="padding-right: 16px;">{{ $isEn ? 'Does DISI only supply security hardware?' : 'Apakah DISI hanya menyediakan security hardware?' }}</span>
                            <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            {{ $isEn ? 'No. DISI delivers end-to-end security architecture, deployment, integration, and optimization services, far beyond simply supplying physical appliances.' : 'Tidak. DISI sebaiknya diposisikan sebagai layanan security architecture, deployment, integration, dan optimization, bukan sekadar penyedia perangkat. Ini juga merupakan perubahan positioning utama yang disarankan dari halaman saat ini.' }}
                        </p>
                    </details>

                    <!-- FAQ 5 -->
                    <details class="group p-5 transition-all" style="background-color: #ffffff; border: 1px solid #f1f5f9; box-shadow: 0 4px 14px rgba(0,0,0,0.04); border-radius: 8px; padding: 20px 24px;">
                        <summary class="flex justify-between items-center cursor-pointer select-none font-medium text-slate-900 text-sm md:text-[15px]" style="cursor: pointer; list-style: none; font-size: 14.5px; font-weight: 600; color: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                            <span style="padding-right: 16px;">{{ $isEn ? 'What is the difference between DISI and DIMS?' : 'Apa perbedaan DISI dan DIMS?' }}</span>
                            <span class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-light text-base shrink-0 group-open:rotate-45 transition-transform duration-200" style="width: 28px; height: 28px; min-width: 28px; border-radius: 50%; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #475569;">+</span>
                        </summary>
                        <p class="mt-3 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3" style="color: #475569; font-size: 13.5px; line-height: 1.6; margin-top: 14px; padding-top: 14px; border-top: 1px solid #f1f5f9;">
                            {{ $isEn ? 'DISI focuses on the design, deployment, and integration of security infrastructure, whereas DIMS focuses on 24/7 managed security operations and environment monitoring.' : 'DISI berfokus pada design, deployment, dan integration security infrastructure, sedangkan DIMS berfokus pada managed security operations dan pengelolaan security environment.' }}
                        </p>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 7: CTA Banner (Security Stack Works as One - Full Width Gray Background) -->
    <section class="w-full" style="background: linear-gradient(105deg, #f8f9fa 0%, #eaedf1 50%, #d5dbe2 100%); padding: 64px 16px; border-top: 1px solid #e2e8f0;">
        <div style="max-width: 1040px; margin: 0 auto; padding: 0 16px;">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Left: Text & Button -->
                <div class="text-left">
                    <h2 class="text-2xl md:text-3xl font-semibold mb-3" style="color: #0f172a; margin-bottom: 12px; font-weight: 600; font-size: 28px;">
                        Security Stack Works as One
                    </h2>
                    <p class="text-sm md:text-base leading-relaxed mb-6 max-w-lg" style="color: #475569; margin-bottom: 24px; line-height: 1.6; font-size: 15px;">
                        {{ $isEn ? 'Unify your entire security stack, eliminate vulnerabilities, and build a resilient defense ecosystem ready to scale with your business.' : 'Satukan semua security stack, kurangi security gaps, dan bangun defense ecosystem yang siap mendukung pertumbuhan bisnis Anda.' }}
                    </p>
                    <div>
                        <a href="https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl" target="_blank" rel="noopener noreferrer" class="inline-block text-white font-semibold text-sm transition-opacity hover:opacity-90 shadow-sm" style="background-color: #ae3033; color: #ffffff; padding: 10px 24px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: 600;">
                            Design Your Security Ecosystem
                        </a>
                    </div>
                </div>

                <!-- Right: Isometric Server with Shields Illustration -->
                <div class="flex justify-center md:justify-end items-center">
                    <img src="/assets/img/disi-server-shields.webp" alt="Integrated Security Stack Server" class="w-full h-auto object-contain drop-shadow-md" style="max-width: 280px;" loading="lazy">
                </div>
            </div>
        </div>
    </section>
</div>
