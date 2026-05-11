<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Mews\Purifier\Facades\Purifier;

class PurifyPostContent extends Command
{
    protected $signature = 'posts:purify {--dry-run : Show what would be cleaned without saving}';
    protected $description = 'Re-sanitize all post content through HTMLPurifier to strip inline styles, scripts, and disallowed HTML';

    public function handle(): int
    {
        $posts = Post::all();
        $this->info("Processing {$posts->count()} posts...");

        $changed = 0;

        foreach ($posts as $post) {
            $original = $post->content ?? '';
            $cleaned = Purifier::clean($original);

            if ($original !== $cleaned) {
                $changed++;
                $this->warn("  [{$post->id}] {$post->title} — content changed");

                if (!$this->option('dry-run')) {
                    $post->content = $cleaned;
                    $post->saveQuietly(); // skip model events
                }
            } else {
                $this->line("  [{$post->id}] {$post->title} — already clean");
            }
        }

        if ($this->option('dry-run')) {
            $this->info("\nDry run complete. {$changed} posts would be updated.");
        } else {
            $this->info("\nDone. {$changed} posts re-purified.");
        }

        return 0;
    }
}
