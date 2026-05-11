<?php

namespace App\Jobs;

use App\Models\Export;
use App\Services\ExportBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutes
    public int $tries = 1;

    public function __construct(public Export $export)
    {
    }

    public function handle(ExportBuilder $builder): void
    {
        $builder->build($this->export);
    }

    public function failed(\Throwable $exception): void
    {
        $this->export->update([
            'status' => 'failed',
            'errors' => [$exception->getMessage()],
            'completed_at' => now(),
        ]);
    }
}
