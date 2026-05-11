<div>
    @section('title', 'Media Library')

    <!-- Upload Area -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div
            x-data="{ isDragging: false }"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragleave="isDragging = false"
            x-on:drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
            :style="{ borderColor: isDragging ? '#6366f1' : 'rgba(148,163,184,0.2)', backgroundColor: isDragging ? 'rgba(99,102,241,0.05)' : 'transparent' }"
            style="border: 2px dashed rgba(148,163,184,0.2); border-radius: 0.75rem; padding: 2rem; text-align: center; transition: all 0.2s; cursor: pointer;"
            @click="$refs.fileInput.click()"
        >
            <input type="file" x-ref="fileInput" wire:model="uploads" multiple accept="image/*,.svg,.pdf,.doc,.docx" style="display: none;">
            <div class="material-symbols-outlined" style="font-size: 2.5rem; margin-bottom: 0.5rem; color: #64748b;">folder</div>
            <p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">
                <span style="color: #6366f1; font-weight: 600;">Click to upload</span> or drag and drop
            </p>
            <p style="color: #64748b; font-size: 0.75rem; margin: 0.25rem 0 0;">Max {{ config('static-cms.media.max_upload_size', 10240) / 1024 }}MB per file</p>
        </div>
        <div wire:loading wire:target="uploads" style="margin-top: 0.75rem; color: #a5b4fc; font-size: 0.875rem; text-align: center;">
            <span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; animation: spin 2s linear infinite;">pending</span> Uploading...
        </div>
    </div>

    @if(count($selected) > 0)
    <div class="card" style="margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1.5rem; border-color: rgba(239,68,68,0.3); background: rgba(239,68,68,0.05);">
        <span style="font-size: 0.875rem; color: #fca5a5;">{{ count($selected) }} items selected</span>
        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Bulk Delete Media', message: 'Are you sure you want to delete the selected files? This action cannot be undone.', onConfirm: () => $wire.deleteSelected() })" style="padding: 0.375rem 0.75rem; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Delete Selected</button>
    </div>
    @endif

    <!-- Filters -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <div style="display: flex; gap: 0.5rem;">
            <button wire:click="$set('filterType', '')" style="padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer; {{ $filterType === '' ? 'background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc;' : 'background: transparent; border: 1px solid rgba(148,163,184,0.2); color: #94a3b8;' }}">All</button>
            <button wire:click="$set('filterType', 'image')" style="padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; {{ $filterType === 'image' ? 'background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc;' : 'background: transparent; border: 1px solid rgba(148,163,184,0.2); color: #94a3b8;' }}"><span class="material-symbols-outlined" style="font-size: 1.125rem;">image</span> Images</button>
            <button wire:click="$set('filterType', 'document')" style="padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; {{ $filterType === 'document' ? 'background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc;' : 'background: transparent; border: 1px solid rgba(148,163,184,0.2); color: #94a3b8;' }}"><span class="material-symbols-outlined" style="font-size: 1.125rem;">description</span> Documents</button>
        </div>
        <div style="display: flex; gap: 0.5rem; align-items: center;">
            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8125rem; color: #94a3b8; cursor: pointer; margin-right: 1rem;">
                <input type="checkbox" wire:model.live="selectAll" style="accent-color: #6366f1;"> Select All
            </label>
            <button wire:click="$set('viewMode', 'grid')" style="padding: 0.5rem; background: {{ $viewMode === 'grid' ? 'rgba(99,102,241,0.15)' : 'transparent' }}; border: none; color: #94a3b8; cursor: pointer; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined" style="font-size: 1.25rem;">grid_view</span></button>
            <button wire:click="$set('viewMode', 'list')" style="padding: 0.5rem; background: {{ $viewMode === 'list' ? 'rgba(99,102,241,0.15)' : 'transparent' }}; border: none; color: #94a3b8; cursor: pointer; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined" style="font-size: 1.25rem;">view_list</span></button>
        </div>
    </div>

    <!-- Alt Text Edit Modal -->
    @if($editingId)
    <div class="card" style="margin-bottom: 1rem; border-color: rgba(99,102,241,0.3);">
        <div style="display: flex; gap: 1rem; align-items: center;">
            <input type="text" wire:model="editAltText" placeholder="Enter alt text..." style="flex: 1; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none;">
            <button wire:click="updateAltText" style="padding: 0.5rem 1rem; background: #6366f1; color: white; border: none; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Save</button>
            <button wire:click="$set('editingId', null)" style="padding: 0.5rem 1rem; background: transparent; border: 1px solid rgba(148,163,184,0.2); color: #94a3b8; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Cancel</button>
        </div>
    </div>
    @endif

    <!-- Grid View -->
    @if($viewMode === 'grid')
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
        @forelse($mediaItems as $media)
        <div class="card" style="padding: 0; overflow: hidden; position: relative;" onmouseover="this.querySelector('.media-actions').style.opacity=1" onmouseout="this.querySelector('.media-actions').style.opacity=0">
            <input type="checkbox" wire:model.live="selected" value="{{ $media->id }}" style="position: absolute; top: 0.5rem; left: 0.5rem; z-index: 10;">
            @if($media->isImage())
                <img src="{{ $media->url }}" alt="{{ $media->alt_text }}" style="width: 100%; aspect-ratio: 1; object-fit: cover;">
            @else
                <div style="width: 100%; aspect-ratio: 1; display: flex; align-items: center; justify-content: center; background: rgba(15,23,42,0.6);"><span class="material-symbols-outlined" style="font-size: 2.5rem; color: #64748b;">description</span></div>
            @endif
            <div style="padding: 0.625rem;">
                <div style="font-size: 0.75rem; color: #e2e8f0; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $media->filename }}</div>
                <div style="font-size: 0.6875rem; color: #64748b;">{{ $media->human_size }} {{ $media->width ? "· {$media->width}×{$media->height}" : '' }}</div>
            </div>
            <div class="media-actions" style="position: absolute; top: 0.5rem; right: 0.5rem; display: flex; gap: 0.25rem; opacity: 0; transition: opacity 0.2s;">
                <button wire:click="editMedia({{ $media->id }})" style="background: rgba(0,0,0,0.7); color: white; border: none; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.6875rem; cursor: pointer;">Alt</button>
                <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Media', message: 'Delete this file?', onConfirm: () => $wire.deleteMedia({{ $media->id }}) })" style="background: rgba(239,68,68,0.9); color: white; border: none; border-radius: 0.25rem; padding: 0.25rem 0.5rem; font-size: 0.6875rem; cursor: pointer;">&times;</button>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #64748b;">
            <div class="material-symbols-outlined" style="font-size: 3rem; margin-bottom: 0.5rem; color: #64748b;">image</div>
            No media files yet. Upload your first file!
        </div>
        @endforelse
    </div>
    @else
    <!-- List View -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <th style="padding: 0.75rem 1rem; width: 40px;">
                        <input type="checkbox" wire:model.live="selectAll" style="accent-color: #6366f1;">
                    </th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">File</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Type</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Size</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Dimensions</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Date</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mediaItems as $media)
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.05);">
                    <td style="padding: 0.75rem 1rem;"><input type="checkbox" wire:model.live="selected" value="{{ $media->id }}"></td>
                    <td style="padding: 0.75rem 1rem; display: flex; align-items: center; gap: 0.75rem;">
                        @if($media->isImage())
                            <img src="{{ $media->url }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 0.25rem;">
                        @else
                            <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #64748b;">description</span>
                        @endif
                        <span style="color: #e2e8f0; font-size: 0.875rem;">{{ $media->filename }}</span>
                    </td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $media->mime_type }}</td>
                    <td style="padding: 0.75rem 1rem; color: #94a3b8; font-size: 0.8125rem;">{{ $media->human_size }}</td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $media->width ? "{$media->width}×{$media->height}" : '—' }}</td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $media->created_at->format('M d, Y') }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: right;">
                        <button wire:click="editMedia({{ $media->id }})" style="color: #6366f1; background: none; border: none; font-size: 0.8125rem; cursor: pointer; margin-right: 0.5rem;">Alt</button>
                        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Media', message: 'Delete this file?', onConfirm: () => $wire.deleteMedia({{ $media->id }}) })" style="color: #ef4444; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div style="margin-top: 1rem;">{{ $mediaItems->links() }}</div>
</div>
