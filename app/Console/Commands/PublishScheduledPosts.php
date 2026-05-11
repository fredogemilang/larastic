<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'cms:publish-scheduled';
    protected $description = 'Publish posts scheduled for publication';

    public function handle(): int
    {
        $posts = Post::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish.');
            return self::SUCCESS;
        }

        foreach ($posts as $post) {
            $post->update(['status' => 'published']);
            $this->info("✅ Published: {$post->title}");
        }

        $this->info("Published {$posts->count()} post(s).");

        return self::SUCCESS;
    }
}
