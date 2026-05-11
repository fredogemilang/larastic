<?php

/**
 * Script to find and replace absolute URLs in the database.
 * Run this script via command line: php replace_db_urls.php
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Page;
use App\Models\Post;

// ==============================================================================
// CONFIGURATION
// Ubah variabel di bawah ini sesuai dengan URL lama di database production Anda.
// ==============================================================================

// URL lama yang ingin diganti (contoh: 'https://post.ctizen.id')
// Hapus trailing slash (/) di akhir.
$oldUrl = 'https://dnscms.devs'; 

// URL baru atau biarkan KOSONG ('') jika ingin menjadikannya relative path (Sangat disarankan)
$newUrl = ''; 

// ==============================================================================

echo "==========================================\n";
echo " Database URL Replacer\n";
echo "==========================================\n";
echo "Mencari  : {$oldUrl}\n";
echo "Mengganti: " . ($newUrl === '' ? '[Relative Path]' : $newUrl) . "\n";
echo "==========================================\n\n";

$pageCount = 0;
$postCount = 0;

// 1. Update Pages Table (content_blocks & seo_image)
echo "Scanning Pages...\n";
foreach (Page::all() as $page) {
    $updated = false;
    
    // Replace in content_blocks (array/json)
    if (!empty($page->content_blocks)) {
        // Gunakan JSON_UNESCAPED_SLASHES agar "https://..." tidak menjadi "https:\/\/..."
        $json = json_encode($page->content_blocks, JSON_UNESCAPED_SLASHES);
        
        // Kita juga perlu escape versi lama dan baru berjaga-jaga jika ada nested escaped JSON
        $oldUrlEscaped = str_replace('/', '\/', $oldUrl);
        $newUrlEscaped = str_replace('/', '\/', $newUrl);
        
        if (str_contains($json, $oldUrl) || str_contains($json, $oldUrlEscaped)) {
            
            $newJson = str_replace(
                [$oldUrl, $oldUrlEscaped], 
                [$newUrl, $newUrlEscaped], 
                $json
            );
            
            $page->content_blocks = json_decode($newJson, true);
            $updated = true;
        }
    }
    
    // Replace in seo_image
    if ($page->seo_image && str_contains($page->seo_image, $oldUrl)) {
        $page->seo_image = str_replace($oldUrl, $newUrl, $page->seo_image);
        $updated = true;
    }
    
    if ($updated) {
        $page->timestamps = false; // Hindari update kolom updated_at agar tidak terdeteksi sebagai perubahan baru jika tidak perlu
        $page->save();
        echo "  [✓] Updated Page #{$page->id} ({$page->title})\n";
        $pageCount++;
    }
}

// 2. Update Posts Table (content)
echo "\nScanning Posts...\n";
foreach (Post::all() as $post) {
    $updated = false;
    
    // Replace in post content (WYSIWYG HTML)
    if ($post->content && str_contains($post->content, $oldUrl)) {
        $post->content = str_replace($oldUrl, $newUrl, $post->content);
        $updated = true;
    }
    
    if ($updated) {
        $post->timestamps = false; // Hindari update kolom updated_at
        $post->save();
        echo "  [✓] Updated Post #{$post->id} ({$post->title})\n";
        $postCount++;
    }
}

echo "\n==========================================\n";
echo " Selesai!\n";
echo " Berhasil update: {$pageCount} Pages dan {$postCount} Posts.\n";
echo "==========================================\n";
