<?php

namespace App\Console\Commands;

use App\Models\Export;
use App\Services\ExportBuilder;
use Illuminate\Console\Command;

class ExportSite extends Command
{
    protected $signature = 'cms:export {--sync : Run export synchronously instead of queuing}';
    protected $description = 'Export the static website as a ZIP archive';

    public function handle(ExportBuilder $builder): int
    {
        $this->info('🚀 Starting static site export...');

        $export = Export::create([
            'type' => 'full',
            'status' => 'pending',
            'triggered_by' => null, // CLI export
        ]);

        if ($this->option('sync')) {
            $this->info('Running export synchronously...');
            $result = $builder->build($export);

            if ($result->status === 'completed') {
                $this->info("✅ Export completed! Output: {$result->output_path}");
                $this->info("   File size: " . number_format($result->file_size / 1024, 1) . " KB");

                if ($result->csp_report && !$result->csp_report['passed']) {
                    $this->warn("⚠️  CSP violations found: {$result->csp_report['total_violations']}");
                    foreach ($result->csp_report['summary'] as $type => $count) {
                        if ($count > 0) {
                            $this->warn("   - {$type}: {$count}");
                        }
                    }
                }

                return self::SUCCESS;
            } else {
                $this->error("❌ Export failed: " . implode(', ', $result->errors ?? ['Unknown error']));
                return self::FAILURE;
            }
        } else {
            \App\Jobs\ProcessExport::dispatch($export);
            $this->info("✅ Export queued (ID: {$export->id}). Run 'php artisan queue:work' to process.");
            return self::SUCCESS;
        }
    }
}
