<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentRevision extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'revisionable_type',
        'revisionable_id',
        'action',
        'user_id',
        'summary',
        'old_values',
        'new_values',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // --- Relationships ---

    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // --- Helpers ---

    /**
     * Get a human-readable label for the content type.
     */
    public function getContentTypeLabelAttribute(): string
    {
        return match ($this->revisionable_type) {
            'App\\Models\\Post' => 'Post',
            'App\\Models\\Page' => 'Page',
            default => class_basename($this->revisionable_type),
        };
    }

    /**
     * Get the icon for the action type.
     */
    public function getActionIconAttribute(): string
    {
        return match ($this->action) {
            'created' => 'add_circle',
            'updated' => 'edit',
            'deleted' => 'delete',
            default => 'change_circle',
        };
    }

    /**
     * Get the color for the action type.
     */
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created' => '#22c55e',
            'updated' => '#f59e0b',
            'deleted' => '#ef4444',
            default => '#64748b',
        };
    }

    // --- Scopes ---

    /**
     * Get revisions since a given timestamp.
     */
    public function scopeSince($query, $datetime)
    {
        return $query->where('created_at', '>', $datetime);
    }

    /**
     * Get revisions for a specific content type.
     */
    public function scopeForType($query, string $type)
    {
        return $query->where('revisionable_type', $type);
    }
}
