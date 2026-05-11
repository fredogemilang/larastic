<?php

namespace App\Livewire\Import;

use App\Jobs\ProcessWpImport;
use App\Services\WpImporter as WpImporterService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('WordPress Import')]
class WpImporter extends Component
{
    public string $url = '';
    public bool $connectionOk = false;
    public string $connectionMessage = '';
    public bool $isImporting = false;
    public int $progress = 0;
    public string $importStatus = '';
    public string $importMessage = '';
    public array $importLogs = [];

    public function testConnection(WpImporterService $importer)
    {
        $this->validate([
            'url' => 'required|url'
        ]);

        $this->connectionMessage = 'Testing connection...';
        
        if ($importer->setBaseUrl($this->url)->testConnection()) {
            $this->connectionOk = true;
            $this->connectionMessage = 'Connection successful! REST API is accessible.';
        } else {
            $this->connectionOk = false;
            $this->connectionMessage = 'Failed to connect. Make sure the URL is correct and WP REST API is enabled.';
        }
    }

    public function startImport()
    {
        if (!$this->connectionOk) return;

        // Reset cache
        $cacheKey = "wp_import_progress_" . Auth::id();
        Cache::forget($cacheKey);

        ProcessWpImport::dispatch($this->url, Auth::id());
        
        $this->isImporting = true;
        $this->importStatus = 'running';
        $this->progress = 0;
        $this->importMessage = 'Import job queued...';
    }

    public function checkProgress()
    {
        if (!$this->isImporting) return;

        $cacheKey = "wp_import_progress_" . Auth::id();
        $data = Cache::get($cacheKey);

        if ($data) {
            $this->progress = $data['progress'] ?? 0;
            $this->importStatus = $data['status'] ?? 'running';
            $this->importMessage = $data['message'] ?? '';
            $this->importLogs = array_reverse($data['logs'] ?? []); // newest first

            if (in_array($this->importStatus, ['completed', 'failed'])) {
                $this->isImporting = false;
                $this->connectionOk = false; // Reset so they have to test again for a new import
            }
        }
    }

    public function render(): View
    {
        return view('livewire.import.wp-importer');
    }
}
