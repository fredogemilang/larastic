<?php

namespace App\Services;

use App\Models\ContentRevision;
use App\Models\Export;
use App\Models\Setting;

class ExportScopeDetector
{
    /**
     * Analyze changes since last export and determine recommended export mode.
     *
     * @return array{
     *   recommended_mode: string,
     *   can_partial: bool,
     *   has_previous_export: bool,
     *   force_full_reasons: array<string>,
     *   partial_items: array<array{type: string, id: int, title: string, fields: array}>,
     *   change_summary: array
     * }
     */
    public function detect(): array
    {
        $lastExport = Export::where('status', 'completed')
            ->latest('completed_at')
            ->first();

        $result = [
            'recommended_mode' => 'full',
            'can_partial' => false,
            'has_previous_export' => (bool) $lastExport,
            'force_full_reasons' => [],
            'partial_items' => [],
            'change_summary' => [],
        ];

        // No previous export → must be full
        if (!$lastExport) {
            $result['force_full_reasons'][] = 'No previous export exists — first export must be full.';
            return $result;
        }

        $since = $lastExport->completed_at;

        // 1. Check if Settings changed since last export
        if ($this->settingsChangedSince($since)) {
            $result['force_full_reasons'][] = 'Site settings have been modified (analytics, general, or social).';
        }

        // 2. Get all content revisions since last export
        $revisions = ContentRevision::where('created_at', '>', $since)
            ->orderByDesc('created_at')
            ->get();

        if ($revisions->isEmpty() && empty($result['force_full_reasons'])) {
            // Nothing changed at all — no export needed
            $result['can_partial'] = false;
            $result['recommended_mode'] = 'none';
            return $result;
        }

        // 3. Analyze each revision
        $partialCandidates = [];

        foreach ($revisions as $revision) {
            $forceReason = $this->checkRevisionRequiresFull($revision);

            if ($forceReason) {
                $result['force_full_reasons'][] = $forceReason;
            } else {
                // This revision qualifies for partial
                $key = $revision->revisionable_type . ':' . $revision->revisionable_id;
                if (!isset($partialCandidates[$key])) {
                    $partialCandidates[$key] = [
                        'type' => $revision->content_type_label,
                        'model_type' => $revision->revisionable_type,
                        'id' => $revision->revisionable_id,
                        'title' => $this->extractTitle($revision),
                        'fields' => [],
                    ];
                }
                // Merge changed fields
                if ($revision->new_values) {
                    $partialCandidates[$key]['fields'] = array_unique(
                        array_merge($partialCandidates[$key]['fields'], array_keys($revision->new_values))
                    );
                }
            }
        }

        $result['partial_items'] = array_values($partialCandidates);

        // Build change summary
        $result['change_summary'] = [
            'total_revisions' => $revisions->count(),
            'post_changes' => $revisions->where('revisionable_type', 'App\\Models\\Post')->count(),
            'page_changes' => $revisions->where('revisionable_type', 'App\\Models\\Page')->count(),
        ];

        // Determine final recommendation
        if (!empty($result['force_full_reasons'])) {
            $result['recommended_mode'] = 'full';
            $result['can_partial'] = false;
            // Deduplicate reasons
            $result['force_full_reasons'] = array_values(array_unique($result['force_full_reasons']));
        } elseif (!empty($partialCandidates)) {
            $result['recommended_mode'] = 'partial';
            $result['can_partial'] = true;
        }

        return $result;
    }

    /**
     * Check if a single revision requires a full export.
     * Returns null if partial is okay, or a reason string if full is required.
     */
    protected function checkRevisionRequiresFull(ContentRevision $revision): ?string
    {
        $type = $revision->content_type_label;

        // Created or Deleted always require full
        if ($revision->action === 'created') {
            return "{$type} created: \"{$this->extractTitle($revision)}\" — affects navigation, sitemap, and index pages.";
        }

        if ($revision->action === 'deleted') {
            return "{$type} deleted: \"{$this->extractTitle($revision)}\" — must remove from export and update sitemap.";
        }

        // For updates, check which fields changed
        if ($revision->action === 'updated' && $revision->new_values) {
            $changedFields = array_keys($revision->new_values);

            // Fields that force full export
            $fullRequiredFields = ['slug', 'status', 'url', 'template', 'locale', 'sort_order'];

            $intersection = array_intersect($changedFields, $fullRequiredFields);

            if (!empty($intersection)) {
                $fieldList = implode(', ', $intersection);
                return "{$type} \"{$this->extractTitle($revision)}\": structural field(s) changed ({$fieldList}).";
            }
        }

        return null;
    }

    /**
     * Check if any setting has been modified since the given timestamp.
     */
    protected function settingsChangedSince(\Carbon\Carbon $since): bool
    {
        return Setting::where('updated_at', '>', $since)->exists();
    }

    /**
     * Extract a human-readable title from a revision.
     */
    protected function extractTitle(ContentRevision $revision): string
    {
        // Try to get from new_values or old_values
        $title = $revision->new_values['title']
            ?? $revision->old_values['title']
            ?? null;

        if ($title) {
            return $title;
        }

        // Try to load the model
        $model = $revision->revisionable;
        if ($model) {
            return $model->title ?? $model->name ?? "#{$revision->revisionable_id}";
        }

        // Fallback: extract from summary
        return $revision->summary ?? "#{$revision->revisionable_id}";
    }
}
