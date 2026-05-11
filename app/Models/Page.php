<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'url',
        'template',
        'content_blocks',
        'status',
        'seo_title',
        'seo_description',
        'seo_image',
        'canonical_url',
        'sort_order',
        'locale',
        'translation_group_id',
    ];

    protected $casts = [
        'content_blocks' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'metable');
    }

    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisionable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeLocale($query, ?string $locale = null)
    {
        $locale = $locale ?? config('static-cms.default_locale', 'id');
        return $query->where('locale', $locale);
    }

    // --- Translation Relationships ---

    /**
     * Get all translations of this page (including self).
     */
    public function translations()
    {
        if (!$this->translation_group_id) {
            return self::where('id', $this->id);
        }
        return self::where('translation_group_id', $this->translation_group_id);
    }

    /**
     * Get the translation in a specific locale.
     */
    public function translation(string $locale): ?self
    {
        if ($this->locale === $locale) {
            return $this;
        }
        if (!$this->translation_group_id) {
            return null;
        }
        return self::where('translation_group_id', $this->translation_group_id)
            ->where('locale', $locale)
            ->first();
    }

    /**
     * Check if a translation exists for a given locale.
     */
    public function hasTranslation(string $locale): bool
    {
        if ($this->locale === $locale) {
            return true;
        }
        if (!$this->translation_group_id) {
            return false;
        }
        return self::where('translation_group_id', $this->translation_group_id)
            ->where('locale', $locale)
            ->exists();
    }

    public function getUrlAttribute(): string
    {
        $locale = $this->attributes['locale'] ?? 'id';
        $prefix = $locale === 'en' ? '/en' : '';

        // Force home to always be root, regardless of user input
        if ($this->slug === 'home') {
            return $prefix ?: '/';
        }

        $url = $this->attributes['url'] ?? null;
        if ($url) {
            return $prefix . '/' . ltrim($url, '/');
        }

        return $prefix . '/' . $this->slug;
    }

    /**
     * Get a content block by key.
     */
    public function block(string $key, mixed $default = null): mixed
    {
        return data_get($this->content_blocks, $key, $default);
    }
}
