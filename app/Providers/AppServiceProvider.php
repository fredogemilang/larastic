<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Post;
use App\Observers\ContentRevisionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Track content changes for versioning / export notifications
        Post::observe(ContentRevisionObserver::class);
        Page::observe(ContentRevisionObserver::class);
    }
}
