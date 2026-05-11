<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Posts')]
class PostList extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $locale = '';

    public string $sortField = 'published_at';
    public string $sortDirection = 'desc';

    public array $selected = [];
    public bool $selectAll = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedLocale(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selected = $value
            ? $this->getPostsQuery()->pluck('id')->map(fn ($id) => (string) $id)->toArray()
            : [];
    }

    public function deleteSelected(): void
    {
        $query = Post::whereIn('id', $this->selected);

        // Authors can only delete their own posts
        if (!auth()->user()->can('edit_all_posts')) {
            $query->where('author_id', auth()->id());
        }

        $count = $query->delete();
        
        $this->selected = [];
        $this->selectAll = false;
        
        if ($count > 0) {
            $this->dispatch('notify', type: 'success', message: $count . ' post(s) deleted.');
        } else {
            $this->dispatch('notify', type: 'error', message: 'No posts deleted. You may not have permission.');
        }
    }

    public function deletePost(int $id): void
    {
        $post = Post::findOrFail($id);

        if (!auth()->user()->can('edit_all_posts') && $post->author_id !== auth()->id()) {
            $this->dispatch('notify', type: 'error', message: 'You can only delete your own posts.');
            return;
        }

        $post->delete();
        $this->dispatch('notify', type: 'success', message: 'Post deleted.');
    }

    public function bulkUpdateStatus(string $newStatus): void
    {
        if (!auth()->user()->can('publish_posts') && $newStatus === 'published') {
            $this->dispatch('notify', type: 'error', message: 'You do not have permission to publish posts.');
            return;
        }

        $query = Post::whereIn('id', $this->selected);
        if (!auth()->user()->can('edit_all_posts')) {
            $query->where('author_id', auth()->id());
        }

        $count = $query->update(['status' => $newStatus]);
        $this->selected = [];
        $this->selectAll = false;
        
        $this->dispatch('notify', type: 'success', message: $count . ' post(s) status updated.');
    }

    protected function getPostsQuery()
    {
        $query = Post::with(['author', 'categories']);

        // Authors can only see their own posts
        if (!auth()->user()->can('edit_all_posts')) {
            $query->where('author_id', auth()->id());
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->category) {
            $query->whereHas('categories', fn ($q) => $q->where('categories.id', $this->category));
        }

        if ($this->locale) {
            $query->where('locale', $this->locale);
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function render(): View
    {
        return view('livewire.posts.post-list', [
            'posts' => $this->getPostsQuery()->paginate(15),
            'categories' => Category::orderBy('name')->get(),
            'locales' => config('static-cms.locales', []),
        ]);
    }
}
