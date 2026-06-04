<?php

namespace App\Livewire\Export;

use App\Jobs\ProcessExport;
use App\Models\ContentRevision;
use App\Models\Export;
use App\Services\CspValidator;
use App\Services\ExportBuilder;
use App\Services\ExportScopeDetector;
use App\Services\GitDeployService;
use App\Services\StaticRenderer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;
use App\Models\Post;

#[Layout('layouts.admin')]
#[Title('Export')]
class ExportManager extends Component
{
    public bool $isExporting = false;
    public bool $isDeploying = false;
    public ?array $cspPreview = null;
    public bool $showCspDetail = false;
    public ?array $purifyResult = null;
    public string $cspHash = '';
    public bool $showChangeLog = false;

    public function mount(): void
    {
        $this->cspHash = \App\Services\AnalyticsService::getHash();

        // Auto-fail stuck exports (e.g., killed by shared hosting timeout)
        Export::whereIn('status', ['processing', 'pending'])
            ->where('created_at', '<', now()->subMinutes(10))
            ->each(function (Export $export) {
                $export->update([
                    'status' => 'failed',
                    'errors' => ['Export timed out. The server may have killed the process due to execution time or memory limits. Check your hosting plan\'s PHP limits (max_execution_time, memory_limit).'],
                    'completed_at' => now(),
                ]);
                Log::warning("Export #{$export->id} auto-failed: stuck in '{$export->getOriginal('status')}' status for over 10 minutes.");
            });
    }

    /**
     * Run CSP validation preview (no export).
     */
    public function runCspCheck(): void
    {
        $renderer = app(StaticRenderer::class);
        $validator = app(CspValidator::class);

        $manifest = $renderer->renderAll();
        $this->cspPreview = $validator->validateAll($manifest);
    }

    /**
     * Get the export scope analysis (cached per request via computed property).
     */
    public function getExportScopeProperty(): array
    {
        return app(ExportScopeDetector::class)->detect();
    }

    /**
     * Start a full export (synchronous for now, can be queued).
     */
    public function startFullExport(): void
    {
        $this->isExporting = true;

        $scope = $this->exportScope;

        $export = Export::create([
            'type' => 'full',
            'status' => 'pending',
            'triggered_by' => auth()->id(),
            'scope_details' => [
                'mode' => 'full',
                'reason' => $scope['can_partial'] ? 'User chose full export' : 'System required full export',
                'force_reasons' => $scope['force_full_reasons'],
            ],
        ]);

        try {
            $builder = app(ExportBuilder::class);
            $builder->build($export);
        } catch (\Throwable $e) {
            Log::error('Full export Livewire handler failed: ' . $e->getMessage(), [
                'export_id' => $export->id,
                'exception' => $e,
            ]);
            $export->refresh();
            if ($export->status !== 'failed') {
                $export->update([
                    'status' => 'failed',
                    'errors' => [$e->getMessage()],
                    'completed_at' => now(),
                ]);
            }
        }

        $export->refresh();

        $this->isExporting = false;
        $this->dispatch('export-completed');

        if ($export->status === 'failed') {
            $errorMsg = is_array($export->errors) ? implode('; ', $export->errors) : 'Unknown error';
            $this->dispatch('notify', type: 'error', message: 'Export failed: ' . \Illuminate\Support\Str::limit($errorMsg, 150));
        } else {
            $this->dispatch('notify', type: 'success', message: 'Full export completed! Download your static site below.');
        }
    }

    /**
     * Start a partial export — only re-renders changed items.
     */
    public function startPartialExport(): void
    {
        $scope = $this->exportScope;

        if (!$scope['can_partial']) {
            $this->dispatch('notify', type: 'error', message: 'Partial export not available. A full export is required.');
            return;
        }

        if (empty($scope['partial_items'])) {
            $this->dispatch('notify', type: 'error', message: 'No changed items found for partial export.');
            return;
        }

        $this->isExporting = true;

        $export = Export::create([
            'type' => 'partial',
            'status' => 'pending',
            'triggered_by' => auth()->id(),
        ]);

        try {
            $builder = app(ExportBuilder::class);
            $builder->buildPartial($export, $scope['partial_items']);
        } catch (\Throwable $e) {
            Log::error('Partial export Livewire handler failed: ' . $e->getMessage(), [
                'export_id' => $export->id,
                'exception' => $e,
            ]);
            $export->refresh();
            if ($export->status !== 'failed') {
                $export->update([
                    'status' => 'failed',
                    'errors' => [$e->getMessage()],
                    'completed_at' => now(),
                ]);
            }
        }

        $export->refresh();

        $this->isExporting = false;
        $this->dispatch('export-completed');

        if ($export->status === 'failed') {
            $errorMsg = is_array($export->errors) ? implode('; ', $export->errors) : 'Unknown error';
            $this->dispatch('notify', type: 'error', message: 'Partial export failed: ' . \Illuminate\Support\Str::limit($errorMsg, 150));
        } else {
            $this->dispatch('notify', type: 'success', message: 'Partial export completed! Only changed items were re-rendered.');
        }
    }

    /**
     * Download an export ZIP file.
     */
    public function downloadExport(int $exportId)
    {
        $export = Export::findOrFail($exportId);

        // Ownership check: only triggerer or super_admin can download
        if ($export->triggered_by !== auth()->id()
            && !auth()->user()->hasRole('super_admin')) {
            abort(403, 'You are not authorized to download this export.');
        }

        if (!$export->output_path || !Storage::exists($export->output_path)) {
            $this->dispatch('notify', type: 'error', message: 'Export file not found.');
            return;
        }

        return Storage::download($export->output_path, 'static-site-' . $export->completed_at->format('Y-m-d') . '.zip');
    }

    /**
     * Delete an export record and its ZIP file.
     */
    public function deleteExport(int $exportId): void
    {
        $export = Export::findOrFail($exportId);

        if ($export->output_path && Storage::exists($export->output_path)) {
            Storage::delete($export->output_path);
        }

        $export->delete();
        $this->dispatch('notify', type: 'success', message: 'Export deleted.');
    }

    /**
     * Re-purify all post content to strip inline styles, scripts, and disallowed HTML.
     */
    public function purifyAllPosts(): void
    {
        $posts = Post::all();
        $changed = 0;
        $details = [];

        foreach ($posts as $post) {
            $original = $post->content ?? '';
            $cleaned = Purifier::clean($original);

            if ($original !== $cleaned) {
                $post->content = $cleaned;
                $post->saveQuietly();
                $changed++;
                $details[] = $post->title;
            }
        }

        $this->purifyResult = [
            'total' => $posts->count(),
            'changed' => $changed,
            'posts' => $details,
        ];

        if ($changed > 0) {
            $this->dispatch('notify', type: 'success', message: "{$changed} posts re-purified successfully.");
        } else {
            $this->dispatch('notify', type: 'success', message: 'All posts are already clean!');
        }
    }

    /**
     * Get pending content changes since last successful export.
     */
    public function getPendingChangesProperty(): array
    {
        $lastExport = Export::where('status', 'completed')
            ->latest('completed_at')
            ->first();

        $since = $lastExport?->completed_at;

        $query = ContentRevision::query()
            ->when($since, fn ($q) => $q->where('created_at', '>', $since))
            ->orderByDesc('created_at');

        $revisions = $query->get();

        // Group and count
        $postCreated = $revisions->where('revisionable_type', 'App\\Models\\Post')->where('action', 'created')->count();
        $postUpdated = $revisions->where('revisionable_type', 'App\\Models\\Post')->where('action', 'updated')->count();
        $postDeleted = $revisions->where('revisionable_type', 'App\\Models\\Post')->where('action', 'deleted')->count();
        $pageCreated = $revisions->where('revisionable_type', 'App\\Models\\Page')->where('action', 'created')->count();
        $pageUpdated = $revisions->where('revisionable_type', 'App\\Models\\Page')->where('action', 'updated')->count();
        $pageDeleted = $revisions->where('revisionable_type', 'App\\Models\\Page')->where('action', 'deleted')->count();

        return [
            'total' => $revisions->count(),
            'since' => $since,
            'post_created' => $postCreated,
            'post_updated' => $postUpdated,
            'post_deleted' => $postDeleted,
            'page_created' => $pageCreated,
            'page_updated' => $pageUpdated,
            'page_deleted' => $pageDeleted,
            'recent' => $revisions->take(20)->map(fn ($r) => [
                'id' => $r->id,
                'action' => $r->action,
                'action_icon' => $r->action_icon,
                'action_color' => $r->action_color,
                'type_label' => $r->content_type_label,
                'summary' => $r->summary,
                'user' => $r->user?->name ?? 'System',
                'created_at' => $r->created_at->diffForHumans(),
            ])->toArray(),
        ];
    }

    /**
     * Deploy a completed export to GitHub via PR.
     */
    public function deployToGithub(int $exportId): void
    {
        $export = Export::findOrFail($exportId);

        if ($export->status !== 'completed' || !$export->output_path) {
            $this->dispatch('notify', type: 'error', message: 'Only completed exports can be deployed.');
            return;
        }

        if (!GitDeployService::isConfigured()) {
            $this->dispatch('notify', type: 'error', message: 'GitHub deploy is not configured. Go to Settings → Deploy.');
            return;
        }

        $this->isDeploying = true;
        $export->update(['deploy_status' => 'deploying']);

        try {
            $service = app(GitDeployService::class);
            $result = $service->deploy($export);

            if (!empty($result['skipped'])) {
                $export->update(['deploy_status' => null]);
                $this->dispatch('notify', type: 'success', message: 'No changes detected — deployment skipped.');
            } else {
                $export->update([
                    'deploy_status' => $result['merged'] ? 'deployed' : 'pr_created',
                    'deploy_pr_url' => $result['pr_url'],
                    'deployed_at' => $result['merged'] ? now() : null,
                ]);

                if ($result['merged']) {
                    $this->dispatch('notify', type: 'success', message: 'Deployed! PR created and auto-merged.');
                } else {
                    $this->dispatch('notify', type: 'success', message: 'PR created! Waiting for manual review and merge on GitHub.');
                }
            }
        } catch (\Throwable $e) {
            Log::error("Deploy to GitHub failed for Export #{$exportId}: {$e->getMessage()}", [
                'exception' => $e,
            ]);
            $export->update([
                'deploy_status' => 'failed',
            ]);
            $this->dispatch('notify', type: 'error', message: 'Deploy failed: ' . \Illuminate\Support\Str::limit($e->getMessage(), 150));
        }

        $this->isDeploying = false;
    }

    /**
     * Get the count of pending changes (for sidebar badge via View Composer).
     */
    public static function getPendingChangeCount(): int
    {
        $lastExport = Export::where('status', 'completed')
            ->latest('completed_at')
            ->first();

        $query = ContentRevision::query();
        if ($lastExport?->completed_at) {
            $query->where('created_at', '>', $lastExport->completed_at);
        }

        return $query->count();
    }

    public function render(): View
    {
        return view('livewire.export.export-manager', [
            'exports' => Export::with('triggeredBy')
                ->orderByDesc('created_at')
                ->take(20)
                ->get(),
            'latestCompleted' => Export::where('status', 'completed')
                ->latest('completed_at')
                ->first(),
        ]);
    }
}

