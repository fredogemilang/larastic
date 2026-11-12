<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class DefenxorPagesSeeder extends Seeder
{
    public function run(): void
    {
        // ─── HOME PAGE ─────────────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Defenxor — Managed Security Services Provider',
                'template' => 'home',
                'status' => 'published',
                'seo_title' => 'Defenxor — Managed Security Services Provider',
                'seo_description' => 'Defenxor adalah penyedia keamanan terintegrasi untuk bisnis. Kami membantu Anda mengurangi kompleksitas dalam mengurus keamanan TI.',
                'sort_order' => 1,
                'content_blocks' => [
                    'slides' => [
                        [
                            'title' => 'Ancaman Terus Berkembang<br>Security Anda Harus Lebih Cepat',
                            'subtitle' => 'Defenxor membantu perusahaan di Indonesia mendeteksi, merespons, dan mengelola ancaman siber melalui 24/7 managed security, threat intelligence, security consulting, dan security integration.',
                            'image' => '/assets/img/bg1.webp',
                            'side_image' => '/assets/img/DIMS-Main-Feature-1.webp',
                        ],
                        [
                            'title' => 'Dari Hardware hingga Software Keamanan IT, Semua Kebutuhan Anda Terpenuhi',
                            'subtitle' => 'Defenxor membantu Anda menghindari biaya investasi awal (CAPEX) yang besar untuk perangkat keamanan dengan program cicilan kami, sehingga dapat dialihkan menjadi biaya operasional (OPEX) demi memaksimalkan ROI bisnis Anda.',
                            'image' => '/assets/img/bg2.webp',
                            'side_image' => '/assets/img/DISI-Main-Feature-1024x534.webp',
                        ],
                    ],
                    'siapa_kami' => [
                        'title' => 'About',
                        'body' => 'Berbasis di Jakarta, Defenxor menghadirkan DIMS (managed security), DISC (security consulting), dan DISI (security infrastructure integration) untuk membantu organisasi memantau, mengevaluasi, mengintegrasikan, dan memperkuat keamanan infrastruktur IT mereka.',
                        'cta_text' => 'Amankan Bisnis Anda',
                        'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
                        'video' => '/storage/media/2026/05/M7jluLTXqf634RZWXgg7jZNMkAahdDFYvEcfBQzc.webm',
                        'youtube_id' => 'iat5nHMo9t0',
                    ],
                    'why_section' => [
                        'title' => 'Mengapa Defenxor ?',
                        'subtitle' => 'Melalui TI, bisnis Anda terhubung dengan pelanggan secara global. Oleh karenanya, permasalahan terkait TI haruslah dicegah karena akan berdampak negatif pada bisnis Anda. Ancaman terhadap sistem TI terus berkembang. Mengamankan bisnis TI Anda membutuhkan keahlian serta investasi yang tinggi. Defenxor menghilangkan kompleksitas dan menurunkan cost-of-ownership dalam menggunakan security platform yang dapat memastikan bisnis berjalan lancar. Dengan demikian, Anda dapat lebih fokus dengan aspek bisnis Anda yang lain.',
                    ],
                    'features' => [
                        ['img' => '1.webp', 'title' => 'Pemantauan Keamanan', 'description' => 'Memonitor peristiwa keamanan selama 24/7 oleh para analis yang mumpuni.'],
                        ['img' => '2.webp', 'title' => 'Respon dan Manajemen Insiden', 'description' => 'Merespon dengan cepat agar dapat diminimalisir dampak dari pembobolan dan kehilangan data.'],
                        ['img' => '3.webp', 'title' => 'Manajemen Kerentanan (vulnerability)', 'description' => 'Secara proaktif mencari kelemahan serta solusi untuk mengatasinya.'],
                        ['img' => '4.webp', 'title' => 'Aplikasi mobile', 'description' => 'Konsultasi secara online dengan para analis, pantau perkembangan, serta dapatkan pemberitahuan secara real-time perihal permasalahan keamanan yang timbul.'],
                        ['img' => '5.webp', 'title' => 'Pengoleksian dan Manajemen log', 'description' => 'Kumpulkan, korelasikan, dan lakukan analisis log dengan SIEM dan teknologi pencarian teks.'],
                        ['img' => '6.webp', 'title' => 'Mencatat data jaringan & forensik', 'description' => 'Mencatat traffic jaringan untuk menemukan ancaman serta kebutuhan forensik.'],
                    ],
                    'cta' => [
                        'title' => 'Kami Senang Menjawab Pertanyaan Anda',
                        'description' => 'Apakah Anda punya pertanyaan terkait layanan kami? Anda ingin mendapatkan layanan dan penawaran yang sesuai dengan kebutuhan Anda? Hubungilah kami sekarang untuk memenuhi segala kebutuhan Anda. Kami telah menyediakan beberapa cara untuk Anda menghubungi kami, melalui telepon di (+62 21) 2902 3055',
                    ],
                ],
            ]
        );

        // ─── DIMS PAGE ─────────────────────────────────────────
        $dimsContentId = [
            'breadcrumb' => [
                'title' => 'Managed Security Service Provider (MSSP) 24/7 SOC I Defenxor DIMS',
                'subtitle' => 'Managed Security Service Provider (MSSP)',
            ],
            'hero' => [
                'title' => 'Managed Security Service Provider (MSSP) 24/7 SOC<br>Defenxor Intelligence Managed Security (DIMS)',
                'subtitle' => 'Defenxor Intelligence Managed Security (DIMS) adalah layanan Managed Service Provider (MSSP) didukung Security Operations Center (SOC) 24/7 untuk membantu perusahaan mendeteksi ancaman, memantau lingkungan IT, dan merespons insiden dengan cepat.',
                'cta_text' => 'Jelajahi Managed SOC',
                'cta_url' => '#kemampuan-dims',
            ],
            'capabilities' => [
                'title' => 'Apa saja <span class="text-defenxor-red font-medium">Kemampuan DIMS ?</span>',
                'image' => '/assets/img/dims-di-ms.webp',
                'items' => [
                    [
                        'title' => '24/7 SOC Monitoring',
                        'description' => 'Pantau aktivitas keamanan secara real-time, 24/7, dengan dukungan security analyst untuk mendeteksi aktivitas mencurigakan lebih cepat.',
                    ],
                    [
                        'title' => 'Intelligence-Driven Threat Detection & Response',
                        'description' => 'Identifikasi ancaman lebih cepat dengan korelasi log management, SIEM, threat intelligence, dan analisis aktivitas untuk memprioritaskan risiko.',
                    ],
                    [
                        'title' => 'Incident Response & Remediation',
                        'description' => 'Saat terjadi insiden, tim Defenxor akan melakukan respons, containment, dan rapid remediation untuk meminimalkan dampak terhadap bisnis.',
                    ],
                ],
            ],
            'comparison' => [
                'title' => 'MEMBANDINGKAN IN-HOUSE SOC VS MANAGED SOC',
                'rows' => [
                    [
                        'feature' => 'Operasional',
                        'in_house' => 'Perlu membangun shift dan coverage internal',
                        'dims' => 'Monitoring SOC 24/7',
                    ],
                    [
                        'feature' => 'Security Talent',
                        'in_house' => 'Rekrut - Latih - Pertahankan tim sendiri',
                        'dims' => 'Didukung security professionals',
                    ],
                    [
                        'feature' => 'Security Technology',
                        'in_house' => 'Investasi, integrasi, dan pengelolaan sendiri',
                        'dims' => 'Managed security platform & SIEM',
                    ],
                    [
                        'feature' => 'Incident Response',
                        'in_house' => 'Bergantung pada kesiapan internal',
                        'dims' => 'Dukungan incident response & management',
                    ],
                ],
            ],
            'why_dims' => [
                'title' => 'MENGAPA DIMS?',
                'image' => '/assets/img/DIMS-Main-Feature-1.webp',
                'items' => [
                    ['title' => '24/7 Security Monitoring', 'description' => 'Keamanan dipantau real-time dan nonstop oleh SOC analyst.'],
                    ['title' => 'Tim Profesional Bersertifikat', 'description' => 'Didukung profesional dengan sertifikasi dan pengalaman di bidang IT security.'],
                    ['title' => 'Operasional di Indonesia', 'description' => 'Operasional berbasis di Indonesia untuk mendukung kebutuhan keamanan dan akses yang lebih dekat.'],
                    ['title' => 'Scalable', 'description' => 'Layanan dapat disesuaikan dengan kebutuhan organisasi tanpa kompleksitas membangun SOC dari awal.'],
                    ['title' => 'Terintegrasi', 'description' => 'Monitoring, log management, vulnerability management, dan incident response dalam satu layanan.'],
                ],
            ],
            'cta_banner' => [
                'title' => 'Belum Siap Membangun SOC Sendiri?',
                'subtitle' => 'Dapatkan kapabilitas SOC 24/7 tanpa harus menambah kompleksitas tim, teknologi, dan biaya operasional.',
                'cta_text' => 'Konsultasi Kebutuhan SOC Gratis',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
                'image' => '/assets/img/dims-laptop-soc.webp',
            ],
        ];

        $dimsSlugs = ['dims', 'defenxor-intelligence-managed-security-dims'];
        foreach ($dimsSlugs as $slug) {
            Page::where('slug', $slug)->where('locale', 'id')->update([
                'template' => 'dims',
                'content_blocks' => $dimsContentId,
            ]);
        }

        Page::updateOrCreate(
            ['slug' => 'defenxor-intelligence-managed-security-dims', 'locale' => 'id'],
            [
                'title' => 'Defenxor Intelligence Managed Security (DIMS)',
                'template' => 'dims',
                'status' => 'published',
                'seo_title' => 'DIMS — Defenxor Intelligence Managed Security',
                'seo_description' => 'DIMS hadir untuk menghilangkan kompleksitas dalam mengelola fungsi security. Managed Security Service Provider (MSSP) untuk bisnis Anda.',
                'sort_order' => 2,
                'content_blocks' => $dimsContentId,
            ]
        );

        $dimsContentEn = [
            'breadcrumb' => [
                'title' => 'Managed Security Service Provider (MSSP) 24/7 SOC | Defenxor DIMS',
                'subtitle' => 'Managed Security Service Provider (MSSP)',
            ],
            'hero' => [
                'title' => 'Managed Security Service Provider (MSSP) 24/7 SOC<br>Defenxor Intelligence Managed Security (DIMS)',
                'subtitle' => 'Defenxor Intelligence Managed Security (DIMS) is a Managed Security Service Provider (MSSP) supported by a 24/7 Security Operations Center (SOC) to help organizations detect threats, monitor IT environments, and respond to incidents rapidly.',
                'cta_text' => 'Explore Managed SOC',
                'cta_url' => '#kemampuan-dims',
            ],
            'capabilities' => [
                'title' => 'What are <span class="text-defenxor-red font-medium">DIMS Capabilities ?</span>',
                'image' => '/assets/img/dims-di-ms.webp',
                'items' => [
                    [
                        'title' => '24/7 SOC Monitoring',
                        'description' => 'Monitor security activities in real-time, 24/7, with qualified security analyst support to detect suspicious activities faster.',
                    ],
                    [
                        'title' => 'Intelligence-Driven Threat Detection & Response',
                        'description' => 'Identify threats faster with correlated log management, SIEM, threat intelligence, and behavioral analytics to prioritize risks.',
                    ],
                    [
                        'title' => 'Incident Response & Remediation',
                        'description' => 'When an incident occurs, the Defenxor team provides rapid response, containment, and remediation to minimize business impact.',
                    ],
                ],
            ],
            'comparison' => [
                'title' => 'COMPARING IN-HOUSE SOC VS MANAGED SOC',
                'rows' => [
                    [
                        'feature' => 'Operations',
                        'in_house' => 'Need to build internal shift and coverage',
                        'dims' => '24/7 SOC Monitoring',
                    ],
                    [
                        'feature' => 'Security Talent',
                        'in_house' => 'Recruit - Train - Retain your own team',
                        'dims' => 'Supported by certified security professionals',
                    ],
                    [
                        'feature' => 'Security Technology',
                        'in_house' => 'Self-funded investment, integration, and management',
                        'dims' => 'Managed security platform & SIEM',
                    ],
                    [
                        'feature' => 'Incident Response',
                        'in_house' => 'Dependent on internal readiness',
                        'dims' => 'Incident response & management support',
                    ],
                ],
            ],
            'why_dims' => [
                'title' => 'WHY DIMS?',
                'image' => '/assets/img/DIMS-Main-Feature-1.webp',
                'items' => [
                    ['title' => '24/7 Security Monitoring', 'description' => 'Security is monitored in real-time and nonstop by SOC analysts.'],
                    ['title' => 'Certified Professional Team', 'description' => 'Supported by professionals with certifications and extensive experience in IT security.'],
                    ['title' => 'Operations in Indonesia', 'description' => 'Indonesia-based operations to support localized compliance, security requirements, and closer access.'],
                    ['title' => 'Scalable', 'description' => 'Services can be tailored to organizational needs without the complexity of building a SOC from scratch.'],
                    ['title' => 'Integrated', 'description' => 'Monitoring, log management, vulnerability management, and incident response within a single unified service.'],
                ],
            ],
            'cta_banner' => [
                'title' => 'Not Ready to Build Your Own SOC?',
                'subtitle' => 'Gain 24/7 SOC capabilities without increasing team complexity, technology overhead, and operational costs.',
                'cta_text' => 'Free SOC Consultation',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
                'image' => '/assets/img/dims-laptop-soc.webp',
            ],
        ];

        foreach ($dimsSlugs as $slug) {
            Page::where('slug', $slug)->where('locale', 'en')->update([
                'template' => 'dims',
                'content_blocks' => $dimsContentEn,
            ]);
        }

        Page::updateOrCreate(
            ['slug' => 'defenxor-intelligence-managed-security-dims', 'locale' => 'en'],
            [
                'title' => 'Defenxor Intelligence Managed Security (DIMS)',
                'template' => 'dims',
                'status' => 'published',
                'seo_title' => 'DIMS — Defenxor Intelligence Managed Security',
                'seo_description' => 'DIMS removes the complexity of managing security functions. Managed Security Service Provider (MSSP) for your business.',
                'sort_order' => 2,
                'content_blocks' => $dimsContentEn,
            ]
        );

        // ─── DISC PAGE ─────────────────────────────────────────
        $discContentId = [
            'breadcrumb' => [
                'title' => 'Cyber Security Consultant Indonesia I Defenxor DISC',
                'subtitle' => 'Integrated Security Consulting',
            ],
            'hero' => [
                'title' => "Cyber Security Consultant Indonesia<br>untuk Keamanan Terintegrasi",
                'subtitle' => 'Defenxor Intelligence Security Consulting (DISC) membantu enterprise mengidentifikasi celah keamanan, mengelola risiko, memenuhi compliance, dan membangun security architecture yang terintegrasi serta lebih terarah.',
                'cta_text' => 'Assess Your Security',
                'cta_url' => '#apa-itu-disc',
            ],
            'what_is' => [
                'title' => 'Apa Itu Defenxor DISC ?',
                'paragraphs' => [
                    'Defenxor Intelligence Security Consulting (DISC) adalah layanan cyber security consulting yang membantu perusahaan memahami kondisi keamanan, menemukan security gaps, dan menentukan langkah perbaikan yang tepat.',
                    'DISC mencakup security assessment, security audit, security advisory, penetration testing, incident response, dan security certification untuk membantu enterprise membangun security posture yang lebih kuat.',
                ],
                'image' => '/assets/img/disc-logo.webp',
            ],
            'why_disc' => [
                'title' => 'KENAPA DISC?',
                'image' => '/assets/img/disc-cart.webp',
                'points' => [
                    'Identifikasi security gaps',
                    'Prioritaskan risiko kritikal',
                    'Sesuai dengan regulasi yang berlaku',
                    'Membangun security posture yang lebih andal',
                ],
                'cta_text' => 'Konsultasikan Kebutuhan Security Anda',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
            ],
            'why_choose' => [
                'title' => 'MENGAPA PILIH DISC?',
                'image' => '/assets/img/disc-circles.webp',
                'items' => [
                    [
                        'title' => 'Temukan Celah Sebelum Menjadi Insiden',
                        'description' => 'Evaluasi security posture, sistem, aplikasi, dan konfigurasi untuk mengidentifikasi vulnerability dan area yang perlu diperbaiki.',
                        'tags' => 'Security Audit • Vulnerability Assessment • Penetration Testing',
                    ],
                    [
                        'title' => 'Bantuan Perihal Security',
                        'description' => 'Identifikasi risiko, susun prioritas mitigasi, dan selaraskan kontrol keamanan dengan kebutuhan compliance dan regulasi yang relevan. DISC existing juga mencakup security policy development dan certification support, termasuk PCI-DSS dan ISO 27001.',
                        'tags' => 'Risk Assessment • Security Policy • ISO 27001 • PCI-DSS • UU PDP',
                    ],
                    [
                        'title' => 'Arsitektur Keamanan Terintegrasi',
                        'description' => 'Rancang security architecture yang mengintegrasikan teknologi, kontrol, dan proses agar perlindungan lebih menyeluruh sesuai kebutuhan bisnis.',
                        'tags' => 'Architecture Review • Security Strategy • Technology Alignment',
                    ],
                    [
                        'title' => 'Security Advisory & Incident Response',
                        'description' => 'Dapatkan advisory dari security consultant dan dukungan response untuk menangani serta memitigasi insiden.',
                        'tags' => 'Security Advisory • Incident Response • SOC Advisory',
                    ],
                ],
            ],
            'how_it_works' => [
                'title' => 'BAGAIMANA CARA KERJA DISC?',
                'image' => '/assets/img/disc-city.webp',
                'items' => [
                    ['title' => 'Discovery & Assessment', 'description' => 'Petakan kondisi keamanan dengan mengidentifikasi vulnerability, exposure, dan security gaps.'],
                    ['title' => 'Strategy & Architecture', 'description' => 'Bangun dan tentukan prioritas keamanan berdasarkan risiko serta arah kebutuhan bisnis.'],
                    ['title' => 'Roadmap Implementasi', 'description' => 'Susun roadmap yang dapat digunakan tim internal untuk mengubah rekomendasi menjadi aksi.'],
                    ['title' => 'Review & Advisory Berkelanjutan', 'description' => 'Review security secara berkala untuk memastikan posture terus berkembang sesuai riset dan kebutuhan bisnis.'],
                ],
            ],
            'why_defenxor' => [
                'title' => 'MENGAPA DEFENXOR?',
                'image' => '/assets/img/disc-team.webp',
                'items' => [
                    ['title' => 'Tim Ahli Keamanan Bersertifikat', 'description' => 'Didukung profesional dengan sertifikasi seperti CISSP, CISA, ISO 27001LA, ISO 20000 LA, OSCP, CEH Master, CHFI dan PCI QSA.'],
                    ['title' => 'Standar Internasional', 'description' => 'Pendekatan assessment dan testing mengacu pada framework seperti OWASP, CIS dan NIST.'],
                    ['title' => 'Rekomendasi Dapat Ditindaklanjuti', 'description' => 'Anda bukan sekadar menemukan masalah, tetapi juga mendapatkan analisis lengkap, risk rating, dan rekomendasi yang dapat ditindaklanjuti.'],
                    ['title' => 'Sesuai Kebutuhan Organisasi', 'description' => 'Engagement disesuaikan dengan kebutuhan security, compliance, skala, dan kondisi organisasi.'],
                ],
            ],
            'faq' => [
                'title' => 'Frequently Asked Question',
                'items' => [
                    [
                        'question' => 'Mengapa perusahaan enterprise di Indonesia butuh cyber security consultant?',
                        'answer' => 'Ancaman siber di Indonesia terus meningkat dan regulasi (seperti UU PDP) mewajibkan perlindungan data yang ketat. Cyber security consultant membantu mengidentifikasi celah yang terlewat oleh tim internal, memberikan panduan independen, dan memastikan kepatuhan regulasi secara optimal.',
                    ],
                    [
                        'question' => 'Kapan perusahaan perlu melakukan security assessment?',
                        'answer' => 'Security assessment disarankan dilakukan secara berkala (minimal 1-2 kali setahun), sebelum peluncuran sistem atau aplikasi baru, setelah perubahan infrastruktur besar, atau saat mempersiapkan sertifikasi kepatuhan (seperti ISO 27001 dan PCI-DSS).',
                    ],
                    [
                        'question' => 'Apa yang dilakukan Cyber Security Consultant?',
                        'answer' => 'Konsultan mengevaluasi arsitektur keamanan, melakukan penetration testing, menilai risiko kepatuhan, merancang kebijakan keamanan informasi, dan memberikan rekomendasi praktis untuk memperkuat postur pertahanan siber organisasi Anda.',
                    ],
                    [
                        'question' => 'Apakah DISC hanya untuk perusahaan besar?',
                        'answer' => 'Tidak. Layanan DISC dirancang fleksibel dan scalable, dapat disesuaikan untuk perusahaan skala menengah hingga enterprise besar di berbagai sektor seperti perbankan, fintech, logistik, dan e-commerce.',
                    ],
                ],
            ],
            'cta_banner' => [
                'title' => 'Pahami Risiko Security Posture',
                'subtitle' => 'Temukan celah, pahami risiko, dan tentukan langkah berikutnya bersama DISC.',
                'cta_text' => 'Konsultasi Gratis',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
                'image' => '/assets/img/disc-shield.webp',
            ],
        ];

        $discSlugs = ['disc', 'defenxor-intelligence-security-consulting-disc'];
        foreach ($discSlugs as $slug) {
            Page::where('slug', $slug)->where('locale', 'id')->update([
                'template' => 'disc',
                'content_blocks' => $discContentId,
            ]);
        }

        Page::updateOrCreate(
            ['slug' => 'defenxor-intelligence-security-consulting-disc', 'locale' => 'id'],
            [
                'title' => 'Cyber Security Consultant Indonesia I Defenxor DISC',
                'template' => 'disc',
                'status' => 'published',
                'seo_title' => 'Cyber Security Consultant Indonesia I Defenxor DISC',
                'seo_description' => 'Defenxor Intelligence Security Consulting (DISC) membantu enterprise mengidentifikasi celah keamanan, mengelola risiko, memenuhi compliance, dan membangun security architecture yang terintegrasi.',
                'sort_order' => 3,
                'content_blocks' => $discContentId,
            ]
        );

        $discContentEn = [
            'breadcrumb' => [
                'title' => 'Cyber Security Consultant Indonesia | Defenxor DISC',
                'subtitle' => 'Integrated Security Consulting',
            ],
            'hero' => [
                'title' => "Cyber Security Consultant Indonesia<br>for Integrated Security",
                'subtitle' => 'Defenxor Intelligence Security Consulting (DISC) helps enterprises identify security gaps, manage risks, fulfill compliance requirements, and build an integrated, targeted security architecture.',
                'cta_text' => 'Assess Your Security',
                'cta_url' => '#apa-itu-disc',
            ],
            'what_is' => [
                'title' => 'What is Defenxor DISC ?',
                'paragraphs' => [
                    'Defenxor Intelligence Security Consulting (DISC) is a cyber security consulting service that helps organizations understand their security posture, discover security gaps, and define targeted remediation steps.',
                    'DISC covers security assessment, security audit, security advisory, penetration testing, incident response, and security certification to help enterprises establish a resilient security posture.',
                ],
                'image' => '/assets/img/disc-logo.webp',
            ],
            'why_disc' => [
                'title' => 'WHY DISC?',
                'image' => '/assets/img/disc-cart.webp',
                'points' => [
                    'Identify security gaps',
                    'Prioritize critical risks',
                    'Comply with relevant regulations',
                    'Build a more resilient security posture',
                ],
                'cta_text' => 'Consult Your Security Needs',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
            ],
            'why_choose' => [
                'title' => 'WHY CHOOSE DISC?',
                'image' => '/assets/img/disc-circles.webp',
                'items' => [
                    [
                        'title' => 'Discover Vulnerabilities Before They Become Incidents',
                        'description' => 'Evaluate security posture, systems, applications, and configurations to identify vulnerabilities and areas requiring remediation.',
                        'tags' => 'Security Audit • Vulnerability Assessment • Penetration Testing',
                    ],
                    [
                        'title' => 'Comprehensive Security Support',
                        'description' => 'Identify risks, prioritize mitigation efforts, and align security controls with compliance and regulatory demands. DISC also encompasses security policy development and certification support, including PCI-DSS and ISO 27001.',
                        'tags' => 'Risk Assessment • Security Policy • ISO 27001 • PCI-DSS • Personal Data Protection',
                    ],
                    [
                        'title' => 'Integrated Security Architecture',
                        'description' => 'Design security architectures that seamlessly integrate technologies, controls, and workflows for end-to-end organizational protection.',
                        'tags' => 'Architecture Review • Security Strategy • Technology Alignment',
                    ],
                    [
                        'title' => 'Security Advisory & Incident Response',
                        'description' => 'Receive dedicated expert advisory from cybersecurity consultants along with rapid response support to manage and mitigate incidents.',
                        'tags' => 'Security Advisory • Incident Response • SOC Advisory',
                    ],
                ],
            ],
            'how_it_works' => [
                'title' => 'HOW DOES DISC WORK?',
                'image' => '/assets/img/disc-city.webp',
                'items' => [
                    ['title' => 'Discovery & Assessment', 'description' => 'Map your security landscape by identifying vulnerabilities, exposure points, and security gaps.'],
                    ['title' => 'Strategy & Architecture', 'description' => 'Define and establish security priorities aligned with risk thresholds and organizational business goals.'],
                    ['title' => 'Implementation Roadmap', 'description' => 'Develop an actionable roadmap enabling internal teams to translate strategic recommendations into tangible results.'],
                    ['title' => 'Continuous Review & Advisory', 'description' => 'Regularly review security postures to ensure sustainable resilience as evolving threat landscapes and business requirements change.'],
                ],
            ],
            'why_defenxor' => [
                'title' => 'WHY DEFENXOR?',
                'image' => '/assets/img/disc-team.webp',
                'items' => [
                    ['title' => 'Certified Security Expert Team', 'description' => 'Backed by experienced professionals holding industry-leading certifications including CISSP, CISA, ISO 27001 LA, ISO 20000 LA, OSCP, CEH Master, CHFI, and PCI QSA.'],
                    ['title' => 'International Frameworks & Standards', 'description' => 'Assessment and testing methodologies strictly adhere to globally recognized frameworks including OWASP, CIS Controls, and NIST.'],
                    ['title' => 'Actionable Recommendations', 'description' => 'More than just finding vulnerabilities, you receive in-depth analysis, comprehensive risk ratings, and actionable remediation roadmaps.'],
                    ['title' => 'Tailored to Organizational Needs', 'description' => 'Consulting engagements are customized to your specific security objectives, compliance requirements, operational scale, and infrastructure realities.'],
                ],
            ],
            'faq' => [
                'title' => 'Frequently Asked Question',
                'items' => [
                    [
                        'question' => 'Why do enterprises in Indonesia need a cyber security consultant?',
                        'answer' => 'Cyber threats in Indonesia continue to rise, and regulations (such as UU PDP) mandate strict data protection. Cybersecurity consultants help identify blind spots missed by internal teams, provide independent validation, and ensure optimal regulatory compliance.',
                    ],
                    [
                        'question' => 'When should an enterprise conduct a security assessment?',
                        'answer' => 'Security assessments should be performed periodically (at least 1–2 times a year), before launching new critical systems or applications, after major infrastructure updates, or when preparing for compliance audits (such as ISO 27001 and PCI-DSS).',
                    ],
                    [
                        'question' => 'What does a Cyber Security Consultant do?',
                        'answer' => 'Consultants evaluate security architectures, conduct penetration testing, assess compliance risks, design security governance policies, and provide practical roadmaps to fortify your organization\'s cyber defense posture.',
                    ],
                    [
                        'question' => 'Is DISC only for large enterprises?',
                        'answer' => 'No. DISC services are designed to be flexible and scalable, adaptable for mid-market businesses to large enterprise organizations across banking, fintech, healthcare, logistics, and digital services.',
                    ],
                ],
            ],
            'cta_banner' => [
                'title' => 'Understand Your Security Posture Risks',
                'subtitle' => 'Discover vulnerabilities, understand risks, and define your next strategic steps with DISC.',
                'cta_text' => 'Free Consultation',
                'cta_url' => 'https://forms.cloud.microsoft/pages/responsepage.aspx?id=pmuMKTp9uEuBlt-qXfsxv71xA9X3CdBLgmi2RkLAy_ZUM045QlUzSDJGTzRRNzNSWlcxSVBXUkVVWS4u&route=shorturl',
                'image' => '/assets/img/disc-shield.webp',
            ],
        ];

        foreach ($discSlugs as $slug) {
            Page::where('slug', $slug)->where('locale', 'en')->update([
                'template' => 'disc',
                'content_blocks' => $discContentEn,
            ]);
        }

        Page::updateOrCreate(
            ['slug' => 'defenxor-intelligence-security-consulting-disc', 'locale' => 'en'],
            [
                'title' => 'Cyber Security Consultant Indonesia | Defenxor DISC',
                'template' => 'disc',
                'status' => 'published',
                'seo_title' => 'Cyber Security Consultant Indonesia | Defenxor DISC',
                'seo_description' => 'Defenxor Intelligence Security Consulting (DISC) helps enterprises identify security gaps, manage risks, fulfill compliance requirements, and build an integrated security architecture.',
                'sort_order' => 3,
                'content_blocks' => $discContentEn,
            ]
        );

        // ─── DISI PAGE ─────────────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'disi'],
            [
                'title' => 'Defenxor Intelligence Security Integrator (DISI)',
                'template' => 'services',
                'status' => 'published',
                'seo_title' => 'DISI — Defenxor Intelligence Security Integrator',
                'seo_description' => 'Dari hardware hingga software keamanan IT, semua kebutuhan Anda terpenuhi. Integrasi solusi keamanan end-to-end.',
                'sort_order' => 4,
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Defenxor Intelligence Security Integrator (DISI)',
                        'subtitle' => 'Comprehensive Security Integrator',
                    ],
                    'description' => '<h2>Apa Itu <span class="text-defenxor-red font-medium">DISI ?</span></h2><p>Berencana untuk menambahkan security hardware dalam bisnis Anda? Jangan khawatir, kami siap membantu! Defenxor memudahkan Anda untuk menghindari besarnya capital expenditure (CAPEX) security hardware melalui program cicilan kami. Dengan demikian, investasi Anda terhadap security hardware dapat dikonversikan menjadi operational expenditure (OPEX) bagi bisnis dan memastikan return on investment (ROI) yang optimal.</p><p>Selain menyediakan program cicilan, kami juga menambahkan nilai jual layanan dengan memasukkan layanan managed security ke dalam paket sehingga Anda mendapatkan value terbaik dengan biaya yang Anda keluarkan. Security haruslah terjangkau dan mudah dikelola agar Anda dapat lebih fokus kepada aspek bisnis yang lain.</p>',
                    'description_image' => '',
                    'features_title' => 'FITUR UTAMA',
                    'features_image' => '',
                    'features' => [
                        ['title' => 'Program Cicilan untuk Kepemilikan Perangkat Security', 'description' => 'Menurunkan CAPEX dan mengoptimasi OPEX agar bisnis Anda dapat mendapatkan ROI yang optimal.'],
                        ['title' => 'Harga yang Bersaing untuk Perangkat Security', 'description' => 'Manfaat dari jaringan distribusi hardware kami yang luas sehingga Anda akan selalu mendapatkan harga terbaik di pasar.'],
                        ['title' => 'Managed Services Tambahan', 'description' => 'Kami juga akan menyediakan pemantauan dan pengelolaan fungsi security yang disatukan dengan pembelian produk dari kami.'],
                    ],
                    'advantages_title' => 'KEUNGGULAN KAMI',
                    'advantages_image' => '',
                    'advantages' => [
                        ['title' => '', 'description' => 'Memiliki portofolio produk security yang luas.'],
                        ['title' => '', 'description' => 'Security engineer yang mumpuni dan bersertifikasi untuk meng-install dan menerapkan produk.'],
                        ['title' => '', 'description' => 'Merupakan pionir yang mencetuskan program pembayaran fleksibel dan memberikan nilai tambah bagi layanan bundled security.'],
                    ],
                ],
            ]
        );

        // ─── TENTANG KAMI PAGE ──────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kami',
                'template' => 'about',
                'status' => 'published',
                'seo_title' => 'Tentang Kami — Defenxor',
                'seo_description' => 'Defender Nusa Semesta (DNS) adalah bisnis yang berfokus pada keamanan TI. Kami menciptakan Defenxor, penyedia keamanan terintegrasi untuk bisnis.',
                'sort_order' => 5,
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Tentang Kami',
                    ],
                    'body' => '<p>Kami adalah Defender Nusa Semesta (DNS), sebuah bisnis yang berfokus pada keamanan TI. Kami adalah tim yang menciptakan Defenxor, sebuah penyedia keamanan terintegrasi untuk bisnis.</p><p>Melalui TI, bisnis Anda terhubung dengan pelanggan secara global. Oleh karenanya, permasalahan terkait TI haruslah dicegah karena akan berdampak negatif pada bisnis Anda. Ancaman terhadap sistem TI terus berkembang. Mengamankan bisnis TI Anda membutuhkan keahlian serta investasi yang tinggi.</p><p>Defenxor menghilangkan kompleksitas dan menurunkan cost-of-ownership dalam menggunakan security platform yang dapat memastikan bisnis berjalan lancar. Dengan demikian, Anda dapat lebih fokus dengan aspek bisnis Anda yang lain.</p>',
                    'vision' => [
                        'text' => 'Menjadi perusahaan terkemuka dalam menyediakan layanan keamanan TI terintegrasi yang inovatif dan dapat diandalkan, serta berkontribusi terhadap ekosistem digital yang aman di Indonesia dan Asia Tenggara.',
                    ],
                    'mission' => [
                        'text' => 'Menyediakan solusi keamanan TI yang komprehensif, mudah diakses, dan terjangkau bagi perusahaan-perusahaan di Indonesia. Memberdayakan bisnis untuk menghadapi ancaman siber dengan percaya diri melalui teknologi, keahlian, dan proses yang terstandarisasi.',
                    ],
                    'values' => [
                        'text' => '<ul><li><strong>Integritas</strong> — Menjunjung tinggi etika profesional dalam setiap aspek layanan kami.</li><li><strong>Inovasi</strong> — Terus mengembangkan teknologi dan metode terbaru untuk menghadapi ancaman yang selalu berubah.</li><li><strong>Kolaborasi</strong> — Membangun kemitraan yang kuat dengan klien, vendor, dan komunitas keamanan siber.</li><li><strong>Keunggulan</strong> — Berkomitmen untuk memberikan layanan terbaik dengan standar internasional.</li><li><strong>Tanggung Jawab</strong> — Bertanggung jawab penuh terhadap keamanan data dan privasi klien.</li></ul>',
                    ],
                    'team_title' => 'Tim Manajemen Defenxor',
                    'team' => [
                        [
                            'name' => 'Management Team',
                            'role' => 'Leadership',
                            'bio' => 'Tim manajemen Defenxor terdiri dari para profesional berpengalaman di bidang keamanan TI dengan berbagai sertifikasi internasional seperti CISSP, CISA, ISO 27001 LA, dan PCI QSA.',
                        ],
                    ],
                ],
            ]
        );

        // ─── KARIR PAGE ────────────────────────────────────────
        Page::updateOrCreate(
            ['slug' => 'karir'],
            [
                'title' => 'Karir',
                'template' => 'default',
                'status' => 'published',
                'seo_title' => 'Karir — Bergabung Bersama Defenxor',
                'seo_description' => 'Bergabunglah dengan tim Defenxor. Temukan peluang karir di bidang keamanan TI dan cyber security.',
                'sort_order' => 6,
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Karir di Defenxor',
                        'subtitle' => 'Bergabunglah dengan tim kami dan jadilah bagian dari solusi keamanan TI terdepan di Indonesia.',
                    ],
                    'body' => '<h2>Mengapa Bergabung dengan Defenxor?</h2><p>Di Defenxor, kami percaya bahwa karyawan adalah aset terbesar kami. Kami menawarkan lingkungan kerja yang dinamis, peluang pengembangan karir, dan kesempatan untuk bekerja dengan teknologi keamanan terkini.</p><h3>Benefit & Keuntungan</h3><ul><li>Gaji kompetitif dan bonus kinerja</li><li>Sertifikasi profesional ditanggung perusahaan (CISSP, CEH, OSCP, dll.)</li><li>Lingkungan kerja yang kolaboratif dan inovatif</li><li>Peluang pengembangan karir yang jelas</li><li>Asuransi kesehatan komprehensif</li></ul><h3>Posisi yang Tersedia</h3><p>Untuk informasi lowongan terkini, silakan hubungi tim HR kami melalui email atau kunjungi halaman karir kami secara berkala.</p><p>Kirimkan CV dan surat lamaran Anda ke: <strong>hr@defenxor.com</strong></p>',
                ],
            ]
        );

        // ─── CONTACT PAGE (update existing) ────────────────────
        Page::updateOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Hubungi Kami',
                'template' => 'contact',
                'status' => 'published',
                'seo_title' => 'Hubungi Kami — Defenxor',
                'seo_description' => 'Hubungi tim Defenxor untuk konsultasi keamanan TI. Telepon: (+62 21) 2902 3055.',
                'sort_order' => 7,
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Hubungi Kami',
                        'subtitle' => 'Kami siap membantu kebutuhan keamanan TI Anda',
                    ],
                    'info' => [
                        'email' => 'info@defenxor.com',
                        'phone' => '(+62 21) 2902 3055',
                        'address' => 'Jakarta, Indonesia',
                    ],
                    'body' => '<p>Apakah Anda punya pertanyaan terkait layanan kami? Anda ingin mendapatkan layanan dan penawaran yang sesuai dengan kebutuhan Anda? Hubungilah kami sekarang untuk memenuhi segala kebutuhan Anda.</p>',
                ],
            ]
        );

        // ─── DELETE old "services" and "about" page if they have wrong slug ─────
        Page::where('slug', 'services')->where('title', 'Services')->delete();
        Page::where('slug', 'about')->where('title', 'About')->delete();

        $this->command->info('✅ All Defenxor pages have been seeded/updated successfully.');
    }
}
