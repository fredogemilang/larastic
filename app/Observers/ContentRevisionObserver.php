<?php

namespace App\Observers;

use App\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;

class ContentRevisionObserver
{
    /**
     * Fields to track changes for (per model).
     * Only these fields will be diff'd — avoids noise from timestamps etc.
     */
    protected array $trackedFields = [
        'App\\Models\\Post' => [
            'title', 'slug', 'excerpt', 'content', 'status',
            'published_at', 'featured_image_id', 'seo_title',
            'seo_description', 'canonical_url', 'locale',
        ],
        'App\\Models\\Page' => [
            'title', 'slug', 'url', 'template', 'content_blocks',
            'status', 'seo_title', 'seo_description', 'seo_image',
            'canonical_url', 'sort_order', 'locale',
        ],
    ];

    public function created(Model $model): void
    {
        $this->logRevision($model, 'created');
    }

    public function updated(Model $model): void
    {
        $dirty = $model->getDirty();
        $tracked = $this->trackedFields[get_class($model)] ?? [];

        // Only log if tracked fields actually changed
        $changedTracked = array_intersect_key($dirty, array_flip($tracked));
        if (empty($changedTracked)) {
            return;
        }

        $this->logRevision($model, 'updated', $changedTracked);
    }

    public function deleted(Model $model): void
    {
        $this->logRevision($model, 'deleted');
    }

    /**
     * Create a ContentRevision record.
     */
    protected function logRevision(Model $model, string $action, array $changedFields = []): void
    {
        $title = $model->title ?? $model->name ?? "#{$model->getKey()}";

        $summary = match ($action) {
            'created' => "Created {$this->getTypeLabel($model)}: {$title}",
            'deleted' => "Deleted {$this->getTypeLabel($model)}: {$title}",
            'updated' => "Updated {$this->getTypeLabel($model)}: {$title} (" . implode(', ', array_keys($changedFields)) . ")",
        };

        $oldValues = null;
        $newValues = null;

        if ($action === 'updated') {
            $oldValues = [];
            $newValues = [];
            foreach ($changedFields as $field => $newVal) {
                $oldValues[$field] = $model->getOriginal($field);
                $newValues[$field] = $newVal;
            }
        } elseif ($action === 'deleted') {
            $tracked = $this->trackedFields[get_class($model)] ?? [];
            $oldValues = array_intersect_key($model->getAttributes(), array_flip($tracked));
        }

        ContentRevision::create([
            'revisionable_type' => get_class($model),
            'revisionable_id' => $model->getKey(),
            'action' => $action,
            'user_id' => auth()->id(),
            'summary' => $summary,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'created_at' => now(),
        ]);
    }

    protected function getTypeLabel(Model $model): string
    {
        return match (get_class($model)) {
            'App\\Models\\Post' => 'Post',
            'App\\Models\\Page' => 'Page',
            default => class_basename($model),
        };
    }
}
