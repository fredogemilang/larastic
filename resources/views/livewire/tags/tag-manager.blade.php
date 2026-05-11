<div>
    @section('title', 'Tags')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1rem; color: #94a3b8; margin: 0;">Manage your post tags</h2>
        <button wire:click="openForm()" style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">+ New Tag</button>
    </div>

    @if($showForm)
    <div class="card" style="margin-bottom: 1.5rem;">
        <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">{{ $editingId ? 'Edit' : 'New' }} Tag</h3>
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

    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
        @forelse($tags as $tag)
        <div class="card" style="padding: 1rem; display: flex; align-items: center; gap: 1rem; min-width: 200px;">
            <div style="flex: 1;">
                <div style="font-size: 0.9375rem; font-weight: 500; color: #e2e8f0; display: flex; align-items: center; gap: 0.25rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #a5b4fc;">label</span> {{ $tag->name }}</div>
                <div style="font-size: 0.75rem; color: #64748b;">{{ $tag->posts_count }} posts · /{{ $tag->slug }}</div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button wire:click="openForm({{ $tag->id }})" style="color: #6366f1; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Edit</button>
                <button type="button" x-data x-on:click="console.log('Delete tag clicked. Dispatching event...'); $dispatch('open-modal', { title: 'Delete Tag', message: 'Delete tag \'{{ addslashes($tag->name) }}\'?', onConfirm: () => { console.log('Executing Livewire delete tag method'); $wire.delete({{ $tag->id }}) } })" style="color: #ef4444; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Delete</button>
            </div>
        </div>
        @empty
        <div class="card" style="width: 100%; text-align: center; padding: 3rem;">
            <p style="color: #64748b;">No tags yet. Create your first tag!</p>
        </div>
        @endforelse
    </div>
</div>
