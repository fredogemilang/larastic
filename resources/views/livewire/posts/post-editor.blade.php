<div>
    @section('title', $postId ? 'Edit Post' : 'Create Post')

    <form wire:submit="save">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem;">
            <!-- Main Content Area -->
            <div>
                <!-- Title -->
                <div class="card" style="margin-bottom: 1rem;">
                    <input type="text" wire:model.live.debounce.500ms="title" placeholder="Post title..."
                        style="width: 100%; padding: 0.75rem 0; background: transparent; border: none; color: #f1f5f9; font-size: 1.5rem; font-weight: 700; outline: none; box-sizing: border-box;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem;">
                        Slug: <input type="text" wire:model="slug" style="background: transparent; border: none; color: #94a3b8; font-size: 0.75rem; outline: none; width: auto;">
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="card" style="margin-bottom: 1rem;">
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.5rem;">Excerpt</label>
                    <textarea wire:model="excerpt" rows="2" placeholder="Short description for listings & SEO..."
                        style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
                </div>

                <!-- TinyMCE Editor -->
                <div class="card" style="margin-bottom: 1rem;">
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.5rem;">Content</label>
                    <div
                        wire:ignore
                        x-data="tinymceEditor(@js($content))"
                        style="border-radius: 0.5rem; overflow: hidden;"
                    >
                        <div x-ref="editorElement"></div>
                    </div>
                </div>

                <!-- SEO Fields -->
                <div class="card">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #64748b;">search</span> SEO Settings</h3>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">SEO Title</label>
                        <input type="text" wire:model="seo_title" placeholder="Custom title for search engines..."
                            style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">SEO Description</label>
                        <textarea wire:model="seo_description" rows="2" placeholder="Meta description for search results..."
                            style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
                    </div>

                    <div>
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Canonical URL</label>
                        <input type="url" wire:model="canonical_url" placeholder="https://..."
                            style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Publish Box -->
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">Publish</h3>

                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Status</label>
                        <select wire:model="status"
                            style="width: 100%; padding: 0.625rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>

                    @if($status === 'scheduled' || $status === 'published')
                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Publish Date</label>
                        <input type="datetime-local" wire:model="published_at"
                            style="width: 100%; padding: 0.625rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; box-sizing: border-box;">
                    </div>
                    @endif

                    @if($lastSaved)
                    <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.75rem;">
                        Last saved: {{ $lastSaved }}
                    </div>
                    @endif

                    <div style="display: flex; gap: 0.5rem;">
                        <button type="submit" wire:loading.attr="disabled"
                            style="flex: 1; padding: 0.625rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                            <span wire:loading wire:target="save">
                                <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </span>
                            <span wire:loading wire:target="save">Saving...</span>
                            <span wire:loading.remove wire:target="save">{{ $postId ? 'Update' : 'Save' }}</span>
                        </button>
                        <a href="{{ route('admin.posts.index') }}"
                            style="padding: 0.625rem 1rem; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.2); color: #94a3b8; border-radius: 0.5rem; font-size: 0.875rem; text-decoration: none;">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Language / Locale -->
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #38bdf8;">translate</span> Language</h3>
                    <div style="margin-bottom: 0.75rem;">
                        <select wire:model.live="locale"
                            style="width: 100%; padding: 0.625rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                            @foreach($locales as $code => $info)
                            <option value="{{ $code }}">{{ $info['flag'] }} {{ $info['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($postId)
                    {{-- Show linked translations --}}
                    @php $translations = $this->translations; @endphp
                    @if(count($translations) > 0)
                    <div style="margin-bottom: 0.75rem;">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.375rem; font-weight: 500;">Linked Translations</div>
                        @foreach($translations as $tr)
                        <a href="{{ route('admin.posts.edit', $tr['id']) }}"
                            style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: rgba(56,189,248,0.08); border: 1px solid rgba(56,189,248,0.15); border-radius: 0.375rem; color: #7dd3fc; font-size: 0.8125rem; text-decoration: none; margin-bottom: 0.375rem; transition: background 0.15s;"
                            onmouseover="this.style.background='rgba(56,189,248,0.15)'" onmouseout="this.style.background='rgba(56,189,248,0.08)'">
                            <span style="font-weight: 600; text-transform: uppercase; font-size: 0.6875rem; background: rgba(56,189,248,0.2); padding: 0.125rem 0.375rem; border-radius: 0.25rem;">{{ $tr['locale'] }}</span>
                            <span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $tr['title'] }}</span>
                            <span style="font-size: 0.6875rem; color: {{ $tr['status'] === 'published' ? '#34d399' : '#94a3b8' }};">{{ $tr['status'] }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Create / Link translation --}}
                    @php
                        $otherLocale = $locale === 'id' ? 'en' : 'id';
                        $otherLocaleName = $locales[$otherLocale]['name'] ?? strtoupper($otherLocale);
                        $hasOtherTranslation = collect($translations)->where('locale', $otherLocale)->isNotEmpty();
                    @endphp
                    @if(!$hasOtherTranslation)
                    <div style="border-top: 1px solid rgba(148,163,184,0.1); padding-top: 0.75rem;">
                        <button type="button" wire:click="createTranslation('{{ $otherLocale }}')"
                            style="width: 100%; padding: 0.5rem; background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2); color: #7dd3fc; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer; font-weight: 500; margin-bottom: 0.5rem;"
                            wire:loading.attr="disabled">
                            <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; margin-right: 0.25rem;">add</span>
                            Create {{ $otherLocaleName }} Translation
                        </button>
                    </div>
                    @endif
                    @endif
                </div>

                <!-- Categories -->
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #fcd34d;">folder</span> Categories</h3>
                    <div style="max-height: 200px; overflow-y: auto;">
                        @foreach($categories as $cat)
                        <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.375rem 0; font-size: 0.8125rem; color: #cbd5e1; cursor: pointer;">
                            <input type="checkbox" wire:model="selectedCategories" value="{{ $cat->id }}" style="accent-color: #6366f1;">
                            {{ $cat->name }}
                        </label>
                        @endforeach
                        @if($categories->isEmpty())
                            <p style="color: #64748b; font-size: 0.8125rem;">No categories yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Tags -->
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #a5b4fc;">label</span> Tags</h3>
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <input type="text" wire:model="tagInput" wire:keydown.enter.prevent="addTag" placeholder="Add tag..."
                            style="flex: 1; padding: 0.5rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.375rem; color: #f1f5f9; font-size: 0.8125rem; outline: none;">
                        <button type="button" wire:click="addTag"
                            style="padding: 0.5rem 0.75rem; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">+</button>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.375rem;">
                        @foreach($selectedTags as $index => $tag)
                        <span style="padding: 0.25rem 0.5rem; background: rgba(99,102,241,0.15); color: #a5b4fc; border-radius: 9999px; font-size: 0.75rem; display: flex; align-items: center; gap: 0.25rem;">
                            {{ $tag }}
                            <button type="button" wire:click="removeTag({{ $index }})" style="background: none; border: none; color: #a5b4fc; cursor: pointer; font-size: 0.75rem; padding: 0;">×</button>
                        </span>
                        @endforeach
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="card">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #f9a8d4;">image</span> Featured Image</h3>
                    @if($featured_image_id)
                        @php $img = \App\Models\Media::find($featured_image_id); @endphp
                        @if($img)
                        <div style="position: relative; margin-bottom: 0.5rem;">
                            <img src="{{ $img->url }}" alt="{{ $img->alt_text }}" style="width: 100%; border-radius: 0.5rem; aspect-ratio: 16/9; object-fit: cover;">
                            <button type="button" wire:click="removeFeaturedImage" style="position: absolute; top: 0.5rem; right: 0.5rem; background: rgba(239,68,68,0.9); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; font-size: 0.75rem;">×</button>
                        </div>
                        @endif
                    @else
                        <button type="button" x-data x-on:click="$dispatch('open-media-picker', { targetField: 'featured_image' })"
                            style="width: 100%; padding: 2rem; background: rgba(15,23,42,0.6); border: 2px dashed rgba(148,163,184,0.2); border-radius: 0.5rem; color: #64748b; font-size: 0.8125rem; cursor: pointer; text-align: center;">
                            Click to select image
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </form>

    <!-- Media Picker Modal (reusable) -->
    @livewire('media.media-picker')

    @push('styles')
    <style>
        /* TinyMCE container styling */
        .tox-tinymce { border-color: rgba(148,163,184,0.2) !important; border-radius: 0.5rem !important; }
    </style>
    @endpush

    @push('scripts')
    @vite('resources/js/tinymce-editor.js')
    @endpush
</div>
