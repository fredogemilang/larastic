<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'published_at',
        'author_id',
        'featured_image_id',
        'seo_title',
        'seo_description',
        'canonical_url',
        'og_image_id',
        'wp_original_id',
        'import_source',
        'locale',
        'translation_group_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // --- Relationships ---

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    public function ogImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'og_image_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'post_category');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'metable');
    }

    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisionable');
    }

    // --- Translation Relationships ---

    /**
     * Get all translations of this post (including self).
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

    // --- Scopes ---

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                     ->where('published_at', '>', now());
    }

    public function scopeLocale($query, ?string $locale = null)
    {
        $locale = $locale ?? config('static-cms.default_locale', 'id');
        return $query->where('locale', $locale);
    }

    // --- Helpers ---

    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getUrlAttribute(): string
    {
        $prefix = $this->locale === 'en' ? '/en' : '';
        return $prefix . '/' . config('static-cms.blog.url_prefix') . '/' . $this->slug;
    }
}
