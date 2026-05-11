<?php

namespace App\Livewire\Pages;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;

#[Layout('layouts.admin')]
#[Title('Edit Page')]
class PageEditor extends Component
{
    #[Locked]
    public int $pageId;
    public string $title = '';
    public string $slug = '';         // DB value — always URL-safe
    public string $slugDisplay = ''; // UI input — shows raw chars while typing
    public string $template = 'default';
    public bool $slugManuallyEdited = false;
    public bool $isSaving = false;
    public array $content_blocks = [];
    public string $status = 'draft';
    public string $seo_title = '';
    public string $seo_description = '';
    public string $seo_image = '';
    public string $canonical_url = '';
    public string $locale = 'id';
    public ?string $translation_group_id = null;

    protected array $templateBlocks = [
        'default' => [
            'hero' => ['type' => 'object', 'fields' => ['title', 'subtitle', 'description']],
            'body' => ['type' => 'html', 'label' => 'Body Content'],
        ],
        'home' => [
            'hero' => ['type' => 'object', 'fields' => ['title', 'subtitle']],
            'slides' => ['type' => 'list', 'fields' => ['image', 'title', 'subtitle', 'cta_text', 'cta_url', 'side_image']],
            'siapa_kami' => ['type' => 'object', 'fields' => ['title', 'body', 'video']],
            'why_section' => ['type' => 'object', 'fields' => ['title', 'subtitle']],
            'features' => ['type' => 'list', 'fields' => ['img', 'title', 'description']],
            'cta' => ['type' => 'object', 'fields' => ['title', 'description']],
        ],
        'about' => [
            'hero_title' => ['type' => 'text', 'label' => 'Hero Title'],
            'hero_image' => ['type' => 'image', 'label' => 'Hero Image'],
            'body' => ['type' => 'html', 'label' => 'About Content'],
            'vision_text' => ['type' => 'html', 'label' => 'Vision Text'],
            'vision_image' => ['type' => 'image', 'label' => 'Vision Image'],
            'mission_text' => ['type' => 'html', 'label' => 'Mission Text'],
            'mission_image' => ['type' => 'image', 'label' => 'Mission Image'],
            'values_text' => ['type' => 'html', 'label' => 'Values Text'],
            'values_image' => ['type' => 'image', 'label' => 'Values Image'],
            'team_title' => ['type' => 'text', 'label' => 'Team Section Title'],
            'team' => ['type' => 'list', 'fields' => ['name', 'role', 'photo', 'bio', 'linkedin_url', 'facebook_url']],
        ],
        'contact' => [
            'hero' => ['type' => 'object', 'fields' => ['title', 'subtitle']],
            'info' => ['type' => 'object', 'fields' => ['email', 'phone', 'address']],
        ],
        'services' => [
            'hero' => ['type' => 'object', 'fields' => ['title', 'subtitle']],
            'description' => ['type' => 'html', 'label' => 'Description (Apa Itu...)'],
            'description_image' => ['type' => 'image', 'label' => 'Description Image'],
            'features_title' => ['type' => 'text', 'label' => 'Features Section Title'],
            'features_image' => ['type' => 'image', 'label' => 'Features Image'],
            'features' => ['type' => 'list', 'fields' => ['title', 'description']],
            'advantages_title' => ['type' => 'text', 'label' => 'Advantages Section Title'],
            'advantages_image' => ['type' => 'image', 'label' => 'Advantages Image'],
            'advantages' => ['type' => 'list', 'fields' => ['title', 'description']],
        ],
        'career' => [
            'hero_title' => ['type' => 'text', 'label' => 'Hero Title'],
            'hero_image' => ['type' => 'image', 'label' => 'Hero Image'],
            'body' => ['type' => 'html', 'label' => 'Body Content'],
        ],
    ];

    public function mount(int $id): void
    {
        // Only admin/super_admin can edit pages
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'You do not have permission to edit pages.');
        }

        $page = Page::findOrFail($id);
        $this->pageId = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->slugDisplay = $page->slug;
        $this->slugManuallyEdited = true; // existing page — title changes won't touch slug
        $this->template = $page->template;
        $this->content_blocks = $page->content_blocks ?? [];
        $this->status = $page->status;
        $this->seo_title = $page->seo_title ?? '';
        $this->seo_description = $page->seo_description ?? '';
        $this->seo_image = $page->seo_image ?? '';
        $this->canonical_url = $page->canonical_url ?? '';
        $this->locale = $page->locale ?? 'id';
        $this->translation_group_id = $page->translation_group_id;
    }

    public function updatedTitle(string $value): void
    {
        if (!$this->slugManuallyEdited) {
            $this->slug = Str::slug($value);
            $this->slugDisplay = $this->slug;
        }
    }

    public function updatedSlugDisplay(string $value): void
    {
        $this->slugManuallyEdited = true;
        $this->slug = $this->toUrlSafeSlug($value);
    }

    public function resetSlugToTitle(): void
    {
        $this->slugManuallyEdited = false;
        $this->slug = Str::slug($this->title);
        $this->slugDisplay = $this->slug;
    }

    private function toUrlSafeSlug(string $input): string
    {
        $str = strip_tags($input);
        $str = preg_replace('/[^\p{L}\p{N}\s\-_]/u', '', $str);
        $str = preg_replace('/[\s_]+/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);
        $str = trim($str, '-');
        return mb_strtolower($str, 'UTF-8');
    }

    #[On('media-selected')]
    public function selectMedia(string $url, string $field): void
    {
        if ($field === 'seo_image') {
            $this->seo_image = $url;
            return;
        }

        if (str_contains($field, '.')) {
            $blocks = $this->content_blocks;
            data_set($blocks, $field, $url);
            $this->content_blocks = $blocks;
        } else {
            $this->content_blocks[$field] = $url;
        }
    }

    public function clearImage(string $blockKey): void
    {
        if ($blockKey === 'seo_image') {
            $this->seo_image = '';
            return;
        }

        if (str_contains($blockKey, '.')) {
            $blocks = $this->content_blocks;
            data_set($blocks, $blockKey, '');
            $this->content_blocks = $blocks;
        } else {
            $this->content_blocks[$blockKey] = '';
        }
    }

    public function updateBlock(string $blockKey, string $fieldKey, string $value): void
    {
        $this->content_blocks[$blockKey][$fieldKey] = $value;
    }

    public function addListItem(string $blockKey): void
    {
        $templateDef = $this->templateBlocks[$this->template][$blockKey] ?? null;
        if (!$templateDef || $templateDef['type'] !== 'list') return;

        $newItem = [];
        foreach ($templateDef['fields'] as $field) {
            $newItem[$field] = '';
        }
        $this->content_blocks[$blockKey][] = $newItem;
    }

    public function removeListItem(string $blockKey, int $index): void
    {
        unset($this->content_blocks[$blockKey][$index]);
        $this->content_blocks[$blockKey] = array_values($this->content_blocks[$blockKey] ?? []);
    }

    // ─── Translation ─────────────────────────────────────────────────

    /**
     * Create a translation of the current page in the target locale.
     * This creates a NEW page record linked via translation_group_id.
     */
    public function createTranslation(string $targetLocale): void
    {
        $page = Page::findOrFail($this->pageId);

        // Ensure the current page has a translation_group_id
        if (!$page->translation_group_id) {
            $page->translation_group_id = (string) Str::uuid();
            $page->save();
            $this->translation_group_id = $page->translation_group_id;
        }

        // Check if translation already exists
        if ($page->hasTranslation($targetLocale)) {
            $existing = $page->translation($targetLocale);
            $this->redirect(route('admin.pages.edit', $existing->id), navigate: true);
            return;
        }

        // Create new page as translation (copy content blocks & template)
        $newPage = Page::create([
            'title' => $page->title,
            'slug' => $page->slug,
            'url' => $page->slug,
            'template' => $page->template,
            'content_blocks' => $page->content_blocks, // Copy all content as starting point
            'status' => 'draft',
            'seo_title' => $page->seo_title,
            'seo_description' => $page->seo_description,
            'seo_image' => $page->seo_image,
            'canonical_url' => null,
            'sort_order' => $page->sort_order,
            'locale' => $targetLocale,
            'translation_group_id' => $page->translation_group_id,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Translation page created. You can now translate the content.');
        $this->redirect(route('admin.pages.edit', $newPage->id), navigate: true);
    }

    /**
     * Get translation info for display in the sidebar.
     */
    public function getTranslationsProperty(): array
    {
        $page = Page::find($this->pageId);
        if (!$page || !$page->translation_group_id) return [];

        return Page::where('translation_group_id', $page->translation_group_id)
            ->where('id', '!=', $this->pageId)
            ->get(['id', 'title', 'locale', 'status', 'slug'])
            ->toArray();
    }

    // ─── Save ────────────────────────────────────────────────────────

    /**
     * Recursively sanitize HTML fields in content_blocks using HTML Purifier.
     */
    protected function sanitizeBlocks(array $blocks): array
    {
        foreach ($blocks as $key => $value) {
            if (is_array($value)) {
                $blocks[$key] = $this->sanitizeBlocks($value);
            } elseif (is_string($value)) {
                $blocks[$key] = Purifier::clean($value);
            }
        }
        return $blocks;
    }

    public function save(): void
    {
        // Re-verify authorization on save
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'You do not have permission to edit pages.');
        }

        $this->isSaving = true;
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/',
                \Illuminate\Validation\Rule::unique('pages', 'slug')
                    ->where('locale', $this->locale)
                    ->ignore($this->pageId),
            ],
        ]);

        $page = Page::findOrFail($this->pageId);
        $page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => $this->slug,
            'template' => $this->template,
            'content_blocks' => $this->sanitizeBlocks($this->content_blocks),
            'status' => $this->status,
            'seo_title' => $this->seo_title ?: null,
            'seo_description' => $this->seo_description ?: null,
            'seo_image' => $this->seo_image ?: null,
            'canonical_url' => $this->canonical_url ?: null,
            'locale' => $this->locale,
            'translation_group_id' => $this->translation_group_id,
        ]);

        $this->dispatch('notify', type: 'success', message: 'Page saved successfully.');
        $this->isSaving = false;
    }

    public function getAvailableBlocksProperty(): array
    {
        return $this->templateBlocks[$this->template] ?? $this->templateBlocks['default'];
    }

    public function render(): View
    {
        return view('livewire.pages.page-editor', [
            'locales' => config('static-cms.locales', []),
        ]);
    }
}
