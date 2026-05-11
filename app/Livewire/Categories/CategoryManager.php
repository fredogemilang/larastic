<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

#[Layout('layouts.admin')]
#[Title('Categories')]
class CategoryManager extends Component
{
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $parent_id = null;
    public ?int $editingId = null;

    public bool $showForm = false;

    protected function rules(): array
    {
        $uniqueRule = $this->editingId
            ? 'unique:categories,slug,' . $this->editingId
            : 'unique:categories,slug';

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|' . $uniqueRule,
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:categories,id',
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
            $category = Category::findOrFail($id);
            $this->editingId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description ?? '';
            $this->parent_id = $category->parent_id;
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        Category::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'parent_id' => $this->parent_id,
            ]
        );

        $this->dispatch('notify', type: 'success', message: $this->editingId ? 'Category updated.' : 'Category created.');
        $this->resetForm();
        $this->showForm = false;
    }

    public function setDefaultCategory(int $id): void
    {
        $category = Category::findOrFail($id);

        // Remove default status from all categories
        Category::where('is_default', true)->update(['is_default' => false]);

        // Set the selected category as default
        $category->update(['is_default' => true]);

        $this->dispatch('notify', type: 'success', message: 'Default category updated.');
    }

    public function delete(int $id): void
    {
        $category = Category::findOrFail($id);

        if ($category->is_default) {
            $this->dispatch('notify', type: 'error', message: 'Cannot delete the default category. Please assign a new default category first.');
            return;
        }

        if ($category->children()->count() > 0) {
            $this->dispatch('notify', type: 'error', message: 'Cannot delete category with subcategories.');
            return;
        }

        $category->posts()->detach();
        $category->delete();
        $this->dispatch('notify', type: 'success', message: 'Category deleted.');
    }

    public function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->parent_id = null;
    }

    public function render(): View
    {
        return view('livewire.categories.category-manager', [
            'categories' => Category::with(['parent', 'children'])
                ->withCount('posts')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'allCategories' => Category::orderBy('name')->get(),
        ]);
    }
}
