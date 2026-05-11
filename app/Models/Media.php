<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'filename',
        'disk',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'alt_text',
        'variants',
        'uploaded_by',
    ];

    protected $casts = [
        'variants' => 'array',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full URL for this media item.
     */
    public function getUrlAttribute(): string
    {
        $url = Storage::disk($this->disk)->url($this->path);
        // Selalu kembalikan relative path (contoh: /storage/media/gambar.jpg) 
        // agar tidak tersimpan absolute URL beserta domain di database.
        return parse_url($url, PHP_URL_PATH) ?? $url;
    }

    /**
     * Get a variant URL (e.g., 'thumbnail', 'medium', 'webp').
     */
    public function variantUrl(string $variant): ?string
    {
        $path = data_get($this->variants, $variant);
        if (!$path) return null;
        
        $url = Storage::disk($this->disk)->url($path);
        return parse_url($url, PHP_URL_PATH) ?? $url;
    }

    /**
     * Check if this is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
