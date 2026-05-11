<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Page;

$page = Page::find(1);
if (!$page) {
    echo "Page 1 not found.\n";
    exit;
}

$content = [
    'hero' => [
        'title' => '',
        'subtitle' => ''
    ],
    'slides' => [
        [
            'image' => '/assets/img/bg1.webp',
            'title' => 'Defenxor Percaya bahwa Security Haruslah Mudah.',
            'subtitle' => 'Kami dapat membantu Anda mengurangi kompleksitas dalam mengurus kemanan TI, memastikan respon yang lebih baik dan cepat terhadap insiden, serta mematuhi persyaratan dan regulasi yang ada.',
            'cta_text' => '',
            'cta_url' => '',
            'side_image' => '/assets/img/DIMS-Main-Feature-1.webp'
        ],
        [
            'image' => '/assets/img/bg2.webp',
            'title' => 'Dari Hardware hingga Software Keamanan IT, Semua Kebutuhan Anda Terpenuhi',
            'subtitle' => 'Defenxor membantu Anda menghindari biaya investasi awal (CAPEX) yang besar untuk perangkat keamanan dengan program cicilan kami, sehingga dapat dialihkan menjadi biaya operasional (OPEX) demi memaksimalkan ROI bisnis Anda.',
            'cta_text' => '',
            'cta_url' => '',
            'side_image' => '/assets/img/DISI-Main-Feature-1024x534.webp'
        ]
    ],
    'siapa_kami' => [
        'title' => 'Siapa Kami ?',
        'body' => 'Kami adalah Defender Nusa Semesta (DNS), sebuah bisnis yang berfokus pada keamanan TI. Kami adalah tim yang menciptakan Defenxor, sebuah penyedia keamanan terintegrasi untuk bisnis.',
        'youtube_id' => 'iat5nHMo9t0'
    ],
    'why_section' => [
        'title' => 'Mengapa Defenxor ?',
        'subtitle' => 'Melalui TI, bisnis Anda terhubung dengan pelanggan secara global. Oleh karenanya, permasalahan terkait TI haruslah dicegah karena akan berdampak negatif pada bisnis Anda. Ancaman terhadap sistem TI terus berkembang. Mengamankan bisnis TI Anda membutuhkan keahlian serta investasi yang tinggi. Defenxor menghilangkan kompleksitas dan menurunkan cost-of-ownership dalam menggunakan security platform yang dapat memastikan bisnis berjalan lancar. Dengan demikian, Anda dapat lebih fokus dengan aspek bisnis Anda yang lain.'
    ],
    'features' => [
        [
            'img' => '1.webp',
            'title' => 'Pemantauan Keamanan',
            'description' => 'Memonitor peristiwa keamanan selama 24/7 oleh para analis yang mumpuni.'
        ],
        [
            'img' => '2.webp',
            'title' => 'Respon dan Manajemen Insiden',
            'description' => 'Merespon dengan cepat agar dapat diminimalisir dampak dari pembobolan dan kehilangan data.'
        ],
        [
            'img' => '3.webp',
            'title' => 'Manajemen Kerentanan (vulnerability)',
            'description' => 'Secara proaktif mencari kelemahan serta solusi untuk mengatasinya.'
        ],
        [
            'img' => '4.webp',
            'title' => 'Aplikasi mobile',
            'description' => 'Konsultasi secara online dengan para analis, pantau perkembangan, serta dapatkan pemberitahuan secara real-time perihal permasalahan keamanan yang timbul.'
        ],
        [
            'img' => '5.webp',
            'title' => 'Pengoleksian dan Manajemen log',
            'description' => 'Kumpulkan, korelasikan, dan lakukan analisis log dengan SIEM dan teknologi pencarian teks.'
        ],
        [
            'img' => '6.webp',
            'title' => 'Mencatat data jaringan & forensik',
            'description' => 'Mencatat traffic jaringan untuk menemukan ancaman serta kebutuhan forensik.'
        ]
    ],
    'cta' => [
        'title' => 'Kami Senang Menjawab Pertanyaan Anda',
        'description' => 'Apakah Anda punya pertanyaan terkait layanan kami? Anda ingin mendapatkan layanan dan penawaran yang sesuai dengan kebutuhan Anda? Hubungilah kami sekarang untuk memenuhi segala kebutuhan Anda. Kami telah menyediakan beberapa cara untuk Anda menghubungi kami, melalui telepon di (+62 21) 2902 3055'
    ]
];

$page->update(['content_blocks' => $content]);
echo "Page 1 content successfully seeded!\n";
