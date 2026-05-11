<?php

namespace App\Jobs;

use App\Services\WpImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessWpImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour max
    
    protected string $url;
    protected int $userId;

    public function __construct(string $url, int $userId)
    {
        $this->url = $url;
        $this->userId = $userId;
    }

    public function handle(WpImporter $importer): void
    {
        $jobId = $this->job ? $this->job->getJobId() : 'sync';
        $cacheKey = "wp_import_progress_{$this->userId}";

        // Initialization
        Cache::put($cacheKey, [
            'status' => 'running',
            'progress' => 0,
            'message' => 'Starting import...',
            'logs' => [],
        ], now()->addHours(2));

        $importer->setBaseUrl($this->url)
                 ->setUserId($this->userId)
                 ->setProgressCallback(function($message) use ($cacheKey) {
                     $data = Cache::get($cacheKey, []);
                     if (!isset($data['logs'])) $data['logs'] = [];
                     
                     // Keep only last 50 logs to avoid huge payload
                     if (count($data['logs']) > 50) {
                         array_shift($data['logs']);
                     }
                     $data['logs'][] = "[" . date('H:i:s') . "] " . $message;
                     $data['message'] = $message;
                     
                     Cache::put($cacheKey, $data, now()->addHours(2));
                 });

        try {
            // Step 1: Test connection & detect Polylang
            Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                'progress' => 5,
                'message' => 'Testing connection & detecting Polylang...',
            ]), now()->addHours(2));
            $importer->testConnection();

            $hasPolylang = $importer->hasPolylangSupport();
            $languages = $importer->getPolylangLanguages();

            if ($hasPolylang) {
                $langList = implode(', ', $languages);
                Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                    'progress' => 8,
                    'message' => "Polylang Pro detected! Languages: {$langList}",
                ]), now()->addHours(2));
            }

            // Step 2: Import categories
            Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                'progress' => 10,
                'message' => 'Importing categories...',
            ]), now()->addHours(2));
            $catMap = $importer->importCategories();

            // Step 3: Import tags
            Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                'progress' => 25,
                'message' => 'Importing tags...',
            ]), now()->addHours(2));
            $tagMap = $importer->importTags();

            // Step 4: Import posts (multilingual or single)
            $postMessage = $hasPolylang
                ? 'Importing posts (multilingual: ' . implode(', ', $languages) . ')...'
                : 'Importing posts and media...';
            Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                'progress' => 40,
                'message' => $postMessage,
            ]), now()->addHours(2));
            $importer->importPosts($catMap, $tagMap);

            // Done
            $completedMsg = $hasPolylang
                ? 'Import completed successfully. Posts imported in ' . count($languages) . ' languages with translations linked.'
                : 'Import completed successfully.';
            Cache::put($cacheKey, array_merge(Cache::get($cacheKey), [
                'progress' => 100,
                'status' => 'completed',
                'message' => $completedMsg,
            ]), now()->addHours(2));

        } catch (\Exception $e) {
            $data = Cache::get($cacheKey);
            $data['status'] = 'failed';
            $data['message'] = 'Import failed: ' . $e->getMessage();
            $data['logs'][] = "[" . date('H:i:s') . "] ERROR: " . $e->getMessage();
            Cache::put($cacheKey, $data, now()->addHours(2));
            throw $e;
        }
    }
}
