<?php

namespace App\Livewire;

use App\Models\Export;
use App\Models\Media;
use App\Models\Post;
use App\Models\Page;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render(): View
    {
        return view('livewire.dashboard', [
            'totalPosts' => Post::count(),
            'publishedPosts' => Post::where('status', 'published')->count(),
            'draftPosts' => Post::where('status', 'draft')->count(),
            'scheduledPosts' => Post::where('status', 'scheduled')->count(),
            'totalPages' => Page::count(),
            'totalMedia' => Media::count(),
            'recentPosts' => Post::with('author')->latest()->take(5)->get(),
            'recentExports' => Export::with('triggeredBy')->latest()->take(5)->get(),
        ]);
    }
}
