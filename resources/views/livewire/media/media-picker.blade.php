<div>
    @if($showModal)
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.75); backdrop-filter: blur(4px); z-index: 200; display: flex; align-items: center; justify-content: center; padding: 1rem;" wire:click.self="closePicker">
            <div style="background: #1e293b; border: 1px solid rgba(148,163,184,0.15); border-radius: 0.75rem; width: 100%; max-width: 900px; height: 85vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); overflow: hidden;">

                <!-- Header -->
                <div style="padding: 1rem 1.5rem 0 1.5rem; border-bottom: 1px solid rgba(148,163,184,0.1); display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; background: #1e293b;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #f1f5f9; margin: 0; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span class="material-symbols-outlined" style="font-size: 1.25rem; color: #6366f1;">image</span>
                        Select Media
                    </h3>
                    <button type="button" wire:click="closePicker" style="background: none; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer; line-height: 1; padding: 0; margin-bottom: 1rem;">&times;</button>
                </div>

                <!-- Tabs -->
                <div style="display: flex; padding: 0 1.5rem; border-bottom: 1px solid rgba(148,163,184,0.1); gap: 1.5rem; background: #1e293b;">
                    <button type="button" wire:click="$set('activeTab', 'upload')" style="background: none; border: none; border-bottom: 2px solid {{ $activeTab === 'upload' ? '#6366f1' : 'transparent' }}; color: {{ $activeTab === 'upload' ? '#f1f5f9' : '#94a3b8' }}; padding: 1rem 0; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;">
                        Upload Files
                    </button>
                    <button type="button" wire:click="$set('activeTab', 'library')" style="background: none; border: none; border-bottom: 2px solid {{ $activeTab === 'library' ? '#6366f1' : 'transparent' }}; color: {{ $activeTab === 'library' ? '#f1f5f9' : '#94a3b8' }}; padding: 1rem 0; cursor: pointer; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;">
                        Media Library
                    </button>
                </div>

                <!-- Content Area -->
                <div style="flex: 1; display: flex; flex-direction: column; overflow: hidden; background: #0f172a;">
                    @if($activeTab === 'upload')
                        <!-- Tab: Upload -->
                        <div x-data="{ isDragging: false }" 
                             x-on:dragover.prevent="isDragging = true"
                             x-on:dragleave="isDragging = false"
                             x-on:drop.prevent="isDragging = false; $refs.pickerInput.files = $event.dataTransfer.files; $refs.pickerInput.dispatchEvent(new Event('change'))"
                             style="padding: 2rem; flex: 1; display: flex; align-items: center; justify-content: center; flex-direction: column; position: relative;">

                            <!-- Full screen drop overlay -->
                            <div x-show="isDragging" style="position: absolute; inset: 1rem; border: 2px dashed #6366f1; border-radius: 1rem; background: rgba(99,102,241,0.05); z-index: 10; pointer-events: none; transition: all 0.2s;"></div>

                            <div style="width: 100%; max-width: 600px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 1.5rem; padding: 4rem 2rem; border: 2px dashed rgba(148,163,184,0.25); border-radius: 0.75rem; background: rgba(30,41,59,0.5); position: relative; transition: all 0.2s; text-align: center;"
                                 :style="{ borderColor: isDragging ? '#6366f1' : 'rgba(148,163,184,0.25)', backgroundColor: isDragging ? 'rgba(99,102,241,0.08)' : 'rgba(30,41,59,0.5)', transform: isDragging ? 'scale(1.02)' : 'none' }">

                                <span class="material-symbols-outlined" style="font-size: 4rem; color: #6366f1; transition: transform 0.2s;" :style="{ transform: isDragging ? 'translateY(-10px)' : 'none' }">cloud_upload</span>

                                <div style="text-align: center;">
                                    <div style="font-size: 1.25rem; color: #f1f5f9; font-weight: 600; margin-bottom: 0.5rem;">
                                        Drop files here to upload
                                    </div>
                                    <div style="font-size: 0.875rem; color: #94a3b8;">
                                        or click the button below to browse your computer
                                    </div>
                                </div>

                                <label style="cursor: pointer; padding: 0.75rem 2rem; background: #6366f1; color: white; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; display: inline-block; transition: background 0.2s; margin-top: 20px;" onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                                    <input type="file" x-ref="pickerInput" wire:model="uploads" multiple accept="image/*,video/mp4,video/webm" style="display: none;">
                                    Select Files
                                </label>

                                <!-- Uploading state overlay -->
                                <div wire:loading.flex wire:target="uploads" style="position: absolute; inset: 0; background: rgba(30,41,59,0.9); border-radius: 0.75rem; align-items: center; justify-content: center; flex-direction: column; gap: 0.75rem; color: #a5b4fc; font-weight: 500; z-index: 20;">
                                    <span class="material-symbols-outlined" style="font-size: 2.5rem; animation: spin 2s linear infinite;">pending</span>
                                    <span>Uploading to Media Library...</span>
                                </div>
                            </div>

                            <div style="margin-top: 2rem; color: #64748b; font-size: 0.8125rem; text-align: center; max-width: 400px;">
                                Max upload size: 50MB. Supported formats: JPG, PNG, GIF, WEBP, MP4, WEBM.
                                You will automatically be redirected to the library once your upload finishes.
                            </div>
                        </div>
                    @else
                    <!-- Tab: Media Library -->
                    <!-- Search Bar -->
                    <div style="padding: 1rem 1.5rem; border-bottom: 1px solid rgba(148,163,184,0.1); flex-shrink: 0; background: #1e293b;">
                        <div style="position: relative; max-width: 300px;">
                            <span class="material-symbols-outlined" style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); font-size: 1.125rem; color: #64748b;">search</span>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search media..."
                                style="width: 100%; padding: 0.625rem 0.75rem 0.625rem 2.25rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.8125rem; outline: none; box-sizing: border-box; transition: border-color 0.2s;"
                                onfocus="this.style.borderColor='rgba(99,102,241,0.5)'"
                                onblur="this.style.borderColor='rgba(148,163,184,0.2)'">
                        </div>
                    </div>

                    <!-- Image Grid (scrollable) -->
                    <div style="flex: 1; overflow-y: auto; padding: 1.25rem 1.5rem;">
                        @if($mediaItems->count())
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 1rem;">
                            @foreach($mediaItems as $index => $media)
                            <div wire:click="selectImage({{ $media->id }})"
                                 style="position: relative; border-radius: 0.5rem; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.15s; aspect-ratio: 1; background: rgba(15,23,42,0.6); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);"
                                 onmouseover="this.style.borderColor='#6366f1'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.2), 0 4px 6px -2px rgba(0,0,0,0.1)'"
                                 onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06)'">
                                @if(str_starts_with($media->mime_type, 'video/'))
                                    <video src="{{ $media->url }}" style="width: 100%; height: 100%; object-fit: cover;" muted></video>
                                    <div style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                        <span class="material-symbols-outlined" style="font-size: 1rem; color: #fff;">play_arrow</span>
                                    </div>
                                @else
                                    <img src="{{ $media->url }}" alt="{{ $media->alt_text ?? $media->filename }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @endif

                                {{-- Add a "NEW" badge if it was just uploaded (e.g. first item and on page 1) --}}
                                @if($index === 0 && empty($search) && $mediaItems->currentPage() === 1 && $media->created_at->diffInMinutes(now()) < 5)
                                <div style="position: absolute; top: 0.5rem; left: 0.5rem; background: #10b981; color: white; font-size: 0.625rem; font-weight: 700; padding: 0.125rem 0.375rem; border-radius: 0.25rem; text-transform: uppercase; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    New
                                </div>
                                @endif

                                <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 0.5rem; background: linear-gradient(transparent, rgba(0,0,0,0.85)); font-size: 0.6875rem; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-shadow: 0 1px 2px rgba(0,0,0,0.8);">
                                    {{ $media->filename }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($mediaItems->hasPages())
                        <div style="margin-top: 1.5rem; border-top: 1px solid rgba(148,163,184,0.1); padding-top: 1rem;">
                            {{ $mediaItems->links() }}
                        </div>
                        @endif
                        @else
                        <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #64748b; padding: 2rem;">
                            <span class="material-symbols-outlined" style="font-size: 4rem; display: block; margin-bottom: 1rem; opacity: 0.5;">image_search</span>
                            @if($search)
                                <p style="margin: 0 0 0.5rem; font-size: 1.125rem; color: #94a3b8;">No results found</p>
                                <p style="margin: 0; font-size: 0.875rem;">We couldn't find any files matching "{{ $search }}".</p>
                            @else
                                <p style="margin: 0 0 0.5rem; font-size: 1.125rem; color: #94a3b8;">Your library is empty</p>
                                <p style="margin: 0; font-size: 0.875rem;">Switch to the "Upload Files" tab to add media.</p>
                                <button type="button" wire:click="$set('activeTab', 'upload')" style="margin-top: 1rem; padding: 0.5rem 1rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.375rem; font-size: 0.875rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(99,102,241,0.2)'" onmouseout="this.style.background='rgba(99,102,241,0.1)'">
                                    Upload Now
                                </button>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Footer hint -->
                <div style="padding: 0.75rem 1.5rem; border-top: 1px solid rgba(148,163,184,0.1); flex-shrink: 0; background: #1e293b; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.75rem; color: #64748b; display: flex; align-items: center; gap: 0.375rem;">
                        <span class="material-symbols-outlined" style="font-size: 1rem;">info</span>
                        Select an image for: <span style="color: #a5b4fc; font-weight: 500; text-transform: capitalize;">{{ str_replace('_', ' ', $targetField) }}</span>
                    </span>
                    @if($activeTab === 'library')
                        <span style="font-size: 0.75rem; color: #64748b;">Click an item to use it</span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
