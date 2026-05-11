<?php

namespace App\Console\Commands;

use App\Services\CspValidator;
use App\Services\StaticRenderer;
use Illuminate\Console\Command;

class ValidateCsp extends Command
{
    protected $signature = 'cms:validate-csp';
    protected $description = 'Run CSP validation on all rendered pages';

    public function handle(StaticRenderer $renderer, CspValidator $validator): int
    {
        $this->info('🔍 Rendering pages for CSP validation...');

        $manifest = $renderer->renderAll();
        $report = $validator->validateAll($manifest);

        if ($report['passed']) {
            $this->info('✅ All pages pass CSP validation!');
            return self::SUCCESS;
        }

        $this->warn("⚠️  {$report['total_violations']} CSP violation(s) found:");
        $this->newLine();

        foreach ($report['per_page'] as $url => $violations) {
            $this->line("  📄 {$url}");
            foreach ($violations as $v) {
                $this->line("     ⚠ [{$v['type']}] {$v['message']}");
                if ($v['snippet']) {
                    $this->line("       → " . $v['snippet']);
                }
            }
            $this->newLine();
        }

        $this->table(
            ['Type', 'Count'],
            collect($report['summary'])->map(fn($count, $type) => [$type, $count])->values()->toArray()
        );

        return $report['mode'] === 'strict' ? self::FAILURE : self::SUCCESS;
    }
}
