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
        Page::updateOrCreate(
            ['slug' => 'disc'],
            [
                'title' => 'Defenxor Intelligence Security Consulting (DISC)',
                'template' => 'services',
                'status' => 'published',
                'seo_title' => 'DISC — Defenxor Intelligence Security Consulting',
                'seo_description' => 'Layanan konsultasi keamanan profesional dari Defenxor. Penetration testing, security assessment, dan compliance consulting.',
                'sort_order' => 3,
                'content_blocks' => [
                    'hero' => [
                        'title' => 'Defenxor Intelligence Security Consulting (DISC)',
                        'subtitle' => 'Integrated Security Consulting',
                    ],
                    'description' => '<h2>Apa Itu <span class="text-defenxor-red font-medium">DISC ?</span></h2><p>Tantangan yang nyata dihadapi bagi semua pebisnis adalah sebagai berikut: \'Apakah Bisnis saya aman?\'</p><p>Anda pasti akan tahu jawabannya secara pasti jika Anda tidak tahu bagaimana memulai proses pengamanan, melakukan review keamanan secara reguler, kami dan mumpun Anda tidak mengetahui apakah sistem Anda benar-benar terekspos dan berskala dari apa yang perlu dilakukan untuk membuat sistem Anda lebih aman. Kami juga dapat membantu Anda untuk mematuhi regulasi dan security compliance.</p><p>Tak perlu cemas-cemas karena Anda tidak dapat berkompromi soal keamanan data. DISC akan memberikan kepercayaan diri Anda untuk mengamankan bisnis. DISC membantu Anda untuk capai tidak henti dengan solusi komprehensif.</p>',
                    'description_image' => '',
                    'features_title' => 'FITUR UTAMA',
                    'features_image' => '',
                    'features' => [
                        ['title' => 'Pengembangan Kebijakan Security dan Penggunaannya', 'description' => 'Artinya pengidentifikasian kelemahan yang bernilai tinggi, kebijakan dan penggunaannya pun menjadi secukupnya dan mungkin hal ini secara kebiasan yang ada dapat membantu Anda lebih sadar dan berani dari serangan. Kurangi risiko serangan dengan mengidentifikasikan jalan secara tepat dan menutupi secara optimal.'],
                        ['title' => 'Pemeriksaan Security', 'description' => 'Apakah keamanan bisnis Anda telah memenuhi standar internasional? Sebuah security audit dapat membantu Anda mengevaluasi postur security Anda dan mengidentifikasi kelemahan yang ada saat ini untuk memenuhi standar ISO 27001.'],
                        ['title' => 'Bantuan Perihal Security', 'description' => 'Kami menyediakan bantuan bagi tim IT security internal Anda agar mereka dapat meningkatkan kemampuannya dan memastikan bahwa setiap implementasi sesuai dengan standar internasional. Kami juga memastikan dukungan untuk mengoptimalkan dan menghidupkan Security Operation Center (SOC) internal Anda untuk dapat memenuhi target yang telah ditentukan.'],
                        ['title' => 'Respon & Manajemen Perihal Insiden', 'description' => 'Dengan DISC, Anda bisa mendapatkan respon cepat dari tim kami secara 24/7/365 cepat. Ketika dikombinasikan dengan solusi DIMS, bisnis Anda akan mendapatkan layanan IT security yang komprehensif serta notifikasi secara langsung ketika sebuah insiden ditimbulkan dan kami cepat melakukan proses mitigasi secepatnya.'],
                        ['title' => 'Penetration Testing & Compromised Testing', 'description' => 'Kami melakukan pengujian yang menyeluruh dengan mengikuti standar dan kerangka kerja internasional demi memastikan IT milik Anda bebas dari kerentanan di setiap waktu.'],
                        ['title' => 'Sertifikasi Security', 'description' => 'Baik sertifikasi PCI-DSS ataupun ISO 27000, tim kami dapat membantu Anda memenuhinya.'],
                    ],
                    'advantages_title' => 'KEUNGGULAN KAMI',
                    'advantages_image' => '',
                    'advantages' => [
                        ['title' => '', 'description' => 'Kami mengevaluasi bagian dari sisi human, proses, dan IT Anda'],
                        ['title' => '', 'description' => 'Kerangka pengujian berstandar Internasional'],
                        ['title' => '', 'description' => 'Solusi yang kami berikan didukung dengan konsultasi secara komprehensif, cepat dan profesional'],
                        ['title' => '', 'description' => 'Pelaporan perkembangan yang komprehensif disertai dengan analitis dari pakar internal kami'],
                        ['title' => '', 'description' => 'Penggunaan praktik-praktik terbaik industri serta portofolio kami untuk penyesuaian bisnis Anda dan ancaman'],
                        ['title' => '', 'description' => 'Kami adalah ISO 27001 (Sistem Manajemen Keamanan Informasi), ISO 9001 (Manajemen Mutu), PCI-DSS dengan tim bersertifikasi dari industri IT security'],
                        ['title' => '', 'description' => 'Program perlindungan komprehensif produk security. Konsultasi dengan pakar penanganan insiden kami tentang manajemen identitas dan security, ini di maningkankan pemahaman perihal security yang Anda miliki'],
                        ['title' => '', 'description' => 'Layanan yang responsif dan fleksibel yang memenuhi kebutuhan security di organisasi Anda'],
                    ],
                ],
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
