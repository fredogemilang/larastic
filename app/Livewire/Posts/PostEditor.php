<?php

namespace App\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;

#[Layout('layouts.admin')]
#[Title('Post Editor')]
class PostEditor extends Component
{
    #[Locked]
    public ?int $postId = null;
    
    public bool $isCreatePage = false;

    public string $title = '';
    public string $slug = '';
    public string $excerpt = '';
    public string $content = '';
    public string $status = 'draft';
    public ?string $published_at = null;
    public ?int $featured_image_id = null;

    // SEO
    public string $seo_title = '';
    public string $seo_description = '';
    public string $canonical_url = '';

    // Locale
    public string $locale = 'id';
    public ?string $translation_group_id = null;
    public ?int $linkedTranslationId = null;

    // Relations
    public array $selectedCategories = [];
    public string $tagInput = '';
    public array $selectedTags = [];

    // UI State
    public bool $isSaving = false;
    public string $lastSaved = '';

    public function mount(?int $id = null): void
    {
        $this->isCreatePage = !$id;

        if ($id) {
            $post = Post::with(['categories', 'tags'])->findOrFail($id);

            // Authors can only edit own posts
            if (!auth()->user()->can('edit_all_posts') && $post->author_id !== auth()->id()) {
                abort(403);
            }

            $this->postId = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->excerpt = $post->excerpt ?? '';
            $this->content = $post->content ?? '';
            $this->status = $post->status;
            $this->published_at = $post->published_at?->format('Y-m-d\TH:i');
            $this->featured_image_id = $post->featured_image_id;
            $this->seo_title = $post->seo_title ?? '';
            $this->seo_description = $post->seo_description ?? '';
            $this->canonical_url = $post->canonical_url ?? '';
            $this->locale = $post->locale ?? 'id';
            $this->translation_group_id = $post->translation_group_id;
            $this->selectedCategories = $post->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
            $this->selectedTags = $post->tags->pluck('name')->toArray();
        } else {
            // Pre-select default category for new posts
            $defaultCategory = Category::where('is_default', true)->first();
            if ($defaultCategory) {
                $this->selectedCategories = [(string) $defaultCategory->id];
            }
            if (auth()->user()->can('publish_posts')) {
                $this->status = 'published';
            }
        }
    }

    public function updatedTitle(string $value): void
    {
        if (!$this->postId) {
            $this->slug = Str::slug($value);
        }
    }

    public function addTag(): void
    {
        $tag = trim($this->tagInput);
        if ($tag && !in_array($tag, $this->selectedTags)) {
            $this->selectedTags[] = $tag;
        }
        $this->tagInput = '';
    }

    public function removeTag(int $index): void
    {
        unset($this->selectedTags[$index]);
        $this->selectedTags = array_values($this->selectedTags);
    }

    #[On('media-selected')]
    public function handleMediaSelected(string $url, string $field, ?int $id = null): void
    {
        if ($field === 'featured_image' && $id) {
            $this->featured_image_id = $id;
        }
    }

    public function removeFeaturedImage(): void
    {
        $this->featured_image_id = null;
    }

    /**
     * Create a translation of the current post in the target locale.
     */
    public function createTranslation(string $targetLocale): void
    {
        if (!$this->postId) return;

        $post = Post::findOrFail($this->postId);

        // Ensure the current post has a translation_group_id
        if (!$post->translation_group_id) {
            $post->translation_group_id = (string) Str::uuid();
            $post->save();
            $this->translation_group_id = $post->translation_group_id;
        }

        // Check if translation already exists
        if ($post->hasTranslation($targetLocale)) {
            $existing = $post->translation($targetLocale);
            $this->redirect(route('admin.posts.edit', $existing->id), navigate: true);
            return;
        }

        // Create new post as translation
        $newPost = Post::create([
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'status' => 'draft',
            'published_at' => null,
            'author_id' => auth()->id(),
            'featured_image_id' => $post->featured_image_id,
            'locale' => $targetLocale,
            'translation_group_id' => $post->translation_group_id,
        ]);

        // Copy categories and tags
        $newPost->categories()->sync($post->categories->pluck('id'));
        $newPost->tags()->sync($post->tags->pluck('id'));

        $this->dispatch('notify', type: 'success', message: 'Translation created. Redirecting to editor...');
        $this->redirect(route('admin.posts.edit', $newPost->id), navigate: true);
    }

    /**
     * Link an existing post as a translation of this post.
     */
    public function linkTranslation(): void
    {
        if (!$this->postId || !$this->linkedTranslationId) return;

        $currentPost = Post::findOrFail($this->postId);
        $targetPost = Post::findOrFail($this->linkedTranslationId);

        // They must be different locales
        if ($currentPost->locale === $targetPost->locale) {
            $this->dispatch('notify', type: 'error', message: 'Cannot link posts with the same locale.');
            return;
        }

        // Ensure the current post has a translation_group_id
        if (!$currentPost->translation_group_id) {
            $currentPost->translation_group_id = (string) Str::uuid();
            $currentPost->save();
        }

        // Assign the same group to the target post
        $targetPost->translation_group_id = $currentPost->translation_group_id;
        $targetPost->save();

        $this->translation_group_id = $currentPost->translation_group_id;
        $this->linkedTranslationId = null;

        $this->dispatch('notify', type: 'success', message: 'Translation linked successfully.');
    }

    public function save(): void
    {
        // Sanitize slug to prevent path traversal before validation
        $this->slug = Str::slug($this->slug);

        // Re-verify ownership on save to prevent IDOR via postId manipulation
        if ($this->postId) {
            $post = Post::find($this->postId);
            if (!$post || ($post->author_id !== auth()->id() && !auth()->user()->can('edit_all_posts'))) {
                abort(403);
            }
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/',
                \Illuminate\Validation\Rule::unique('posts', 'slug')
                    ->where('locale', $this->locale)
                    ->ignore($this->postId),
            ],
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'locale' => 'required|in:' . implode(',', array_keys(config('static-cms.locales', ['id' => [], 'en' => []]))),
        ]);

        // Check publish permission
        if ($this->status === 'published' && !auth()->user()->can('publish_posts')) {
            $this->status = 'draft';
            $this->dispatch('notify', type: 'error', message: 'You do not have permission to publish. Saved as draft.');
        }

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: null,
            'content' => Purifier::clean($this->content),
            'status' => $this->status,
            'published_at' => $this->status === 'published' && !$this->published_at
                ? now()
                : ($this->published_at ?: null),
            'seo_title' => $this->seo_title ?: null,
            'seo_description' => $this->seo_description ?: null,
            'canonical_url' => $this->canonical_url ?: null,
            'featured_image_id' => $this->featured_image_id,
            'locale' => $this->locale,
            'translation_group_id' => $this->translation_group_id,
        ];

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
        } else {
            $data['author_id'] = auth()->id();
            $post = Post::create($data);
            $this->postId = $post->id;
        }

        // Sync categories
        if (empty($this->selectedCategories)) {
            $defaultCategory = Category::where('is_default', true)->first();
            if ($defaultCategory) {
                $this->selectedCategories = [(string) $defaultCategory->id];
            }
        }
        $post->categories()->sync($this->selectedCategories);

        // Sync tags (create if not exists)
        $tagIds = [];
        foreach ($this->selectedTags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);

        $this->lastSaved = now()->format('H:i:s');
        $this->dispatch('notify', type: 'success', message: 'Post saved successfully.');

        if ($this->isCreatePage) {
            $this->redirect(route('admin.posts.edit', $this->postId), navigate: true);
        }
    }

    public function autosave(): void
    {
        if (!$this->title) return;

        $slug = $this->slug ?: Str::slug($this->title);

        // Check slug uniqueness for new posts
        if (!$this->postId) {
            $exists = Post::where('slug', $slug)
                ->where('locale', $this->locale)
                ->exists();
            if ($exists) {
                $slug = $slug . '-' . Str::random(4);
            }
        }

        $data = [
            'title' => $this->title,
            'slug' => $slug,
            'excerpt' => $this->excerpt ?: null,
            'content' => Purifier::clean($this->content),
            'status' => 'draft',
            'locale' => $this->locale,
        ];

        try {
            if ($this->postId) {
                $post = Post::findOrFail($this->postId);
                if ($post->status === 'draft') {
                    $post->update($data);
                }
            } else {
                $data['author_id'] = auth()->id();
                $post = Post::create($data);
                $this->postId = $post->id;
            }

            $this->lastSaved = now()->format('H:i:s');
        } catch (\Exception $e) {
            Log::warning('Autosave failed: ' . $e->getMessage());
        }
    }

    /**
     * Get translation info for display in the sidebar.
     */
    public function getTranslationsProperty(): array
    {
        if (!$this->postId) return [];

        $post = Post::find($this->postId);
        if (!$post || !$post->translation_group_id) return [];

        return Post::where('translation_group_id', $post->translation_group_id)
            ->where('id', '!=', $this->postId)
            ->get(['id', 'title', 'locale', 'status'])
            ->toArray();
    }

    /**
     * Get available posts for linking as translations.
     */
    public function getLinkablePostsProperty(): \Illuminate\Support\Collection
    {
        if (!$this->postId) return collect();

        $otherLocale = $this->locale === 'id' ? 'en' : 'id';

        return Post::where(function ($query) use ($otherLocale) {
            $query->where('locale', $otherLocale)
                  ->where(function ($q) {
                      $q->whereNull('translation_group_id')
                        ->orWhere('translation_group_id', '!=', $this->translation_group_id ?? '___');
                  });
        })
        ->orderBy('title')
        ->limit(100)
        ->get(['id', 'title', 'locale']);
    }

    public function render(): View
    {
        return view('livewire.posts.post-editor', [
            'categories' => Category::orderBy('name')->get(),
            'allTags' => Tag::orderBy('name')->pluck('name')->toArray(),
            'locales' => config('static-cms.locales', []),
        ]);
    }
}
