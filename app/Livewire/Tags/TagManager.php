<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Tags')]
class TagManager extends Component
{
    public string $name = '';
    public string $slug = '';
    public ?int $editingId = null;
    public bool $showForm = false;

    protected function rules(): array
    {
        $uniqueRule = $this->editingId
            ? 'unique:tags,slug,' . $this->editingId
            : 'unique:tags,slug';

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|' . $uniqueRule,
        ];
    }

    public function updatedName(string $value): void
    {
        if (!$this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $tag = Tag::findOrFail($id);
            $this->editingId = $tag->id;
            $this->name = $tag->name;
            $this->slug = $tag->slug;
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        Tag::updateOrCreate(
            ['id' => $this->editingId],
            ['name' => $this->name, 'slug' => $this->slug]
        );

        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Tag updated.' : 'Tag created.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        $tag = Tag::findOrFail($id);
        $tag->posts()->detach();
        $tag->delete();
        $this->dispatch('notify', type: 'success', message: 'Tag deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
    }

    public function render(): View
    {
        return view('livewire.tags.tag-manager', [
            'tags' => Tag::withCount('posts')->orderBy('name')->get(),
        ]);
    }
}
