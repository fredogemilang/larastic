<div>
    @section('title', 'Categories')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1rem; color: #94a3b8; margin: 0;">Manage your post categories</h2>
        <button wire:click="openForm()" style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">+ New Category</button>
    </div>

    @if($showForm)
    <div class="card" style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">{{ $editingId ? 'Edit' : 'New' }} Category</h3>
        <form wire:submit="save">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Name</label>
                    <input type="text" wire:model.live.debounce.300ms="name" style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                    @error('name') <span style="font-size: 0.75rem; color: #fca5a5;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Slug</label>
                    <input type="text" wire:model="slug" style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                    @error('slug') <span style="font-size: 0.75rem; color: #fca5a5;">{{ $message }}</span> @enderror
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Description</label>
                    <input type="text" wire:model="description" placeholder="Optional description..." style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Parent</label>
                    <select wire:model="parent_id" style="width: 100%; padding: 0.625rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                        <option value="">None (Top Level)</option>
                        @foreach($allCategories as $cat)
                            @if($cat->id !== $editingId)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" wire:loading.attr="disabled" style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save">Saving...</span>
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Create' }}</span>
                </button>
                <button type="button" wire:click="$set('showForm', false)" style="padding: 0.625rem 1rem; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.2); color: #94a3b8; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer;">Cancel</button>
            </div>
        </form>
    </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Name</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Slug</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Parent</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Posts</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.05);">
                    <td style="padding: 0.75rem 1rem; color: #e2e8f0; font-weight: 500; font-size: 0.875rem;">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; vertical-align: middle; color: #fcd34d; margin-right: 0.25rem;">folder</span> {{ $category->name }}
                        @if($category->is_default)
                        <span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700; text-transform: uppercase; margin-left: 0.5rem; vertical-align: middle;">Default</span>
                        @endif
                        @foreach($category->children as $child)
                        <div style="padding-left: 1.5rem; margin-top: 0.5rem; color: #94a3b8; font-weight: 400; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                └ {{ $child->name }}
                                @if($child->is_default)
                                <span style="background: rgba(16,185,129,0.1); color: #10b981; padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 700; text-transform: uppercase; margin-left: 0.5rem;">Default</span>
                                @endif
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                @if(!$child->is_default)
                                <button wire:click="setDefaultCategory({{ $child->id }})" title="Set as Default" style="color: #10b981; background: none; border: none; font-size: 0.75rem; cursor: pointer;">Set Default</button>
                                @endif
                                <button wire:click="openForm({{ $child->id }})" title="Edit" style="color: #6366f1; background: none; border: none; font-size: 0.75rem; cursor: pointer;">Edit</button>
                                <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Category', message: 'Delete this subcategory?', onConfirm: () => $wire.delete({{ $child->id }}) })" title="Delete" style="color: #ef4444; background: none; border: none; font-size: 0.75rem; cursor: pointer;">Del</button>
                            </div>
                        </div>
                        @endforeach
                    </td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $category->slug }}</td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $category->parent?->name ?? '—' }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: center; color: #94a3b8; font-size: 0.875rem;">{{ $category->posts_count }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: right;">
                        @if(!$category->is_default)
                        <button wire:click="setDefaultCategory({{ $category->id }})" style="color: #10b981; background: none; border: none; font-size: 0.8125rem; cursor: pointer; margin-right: 0.75rem;">Set Default</button>
                        @endif
                        <button wire:click="openForm({{ $category->id }})" style="color: #6366f1; background: none; border: none; font-size: 0.8125rem; cursor: pointer; margin-right: 0.75rem;">Edit</button>
                        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Category', message: 'Delete this category?', onConfirm: () => $wire.delete({{ $category->id }}) })" style="color: #ef4444; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: #64748b;">No categories yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
