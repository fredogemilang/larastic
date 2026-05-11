<div>
    @section('title', 'Edit: ' . $title)

    <form wire:submit="save">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem;">
            <!-- Content Blocks -->
            <div>
                <div class="card" style="margin-bottom: 1rem;">
                    <input type="text" wire:model.live.debounce.500ms="title" placeholder="Page title..."
                        style="width: 100%; padding: 0.75rem 0; background: transparent; border: none; color: #f1f5f9; font-size: 1.5rem; font-weight: 700; outline: none; box-sizing: border-box;">
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span class="material-symbols-outlined" style="font-size: 0.875rem; color: #475569;">link</span>
                        <span style="color: #475569;">/</span>
                        <input type="text"
                            x-data
                            x-on:input="$wire.slugManuallyEdited = true"
                            wire:model.live.debounce.300ms="slugDisplay" placeholder="url-slug"
                            style="background: transparent; border: none; color: #94a3b8; font-size: 0.75rem; outline: none; min-width: 120px; flex: 1;">
                        @if($slugManuallyEdited)
                            <span title="Slug manually edited — click Reset to sync with title" style="color: #64748b;">
                                <span class="material-symbols-outlined" style="font-size: 0.75rem;">lock</span>
                            </span>
                            <button type="button" wire:click="resetSlugToTitle"
                                style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.2); color: #a5b4fc; border-radius: 0.25rem; font-size: 0.6875rem; cursor: pointer; padding: 0.125rem 0.375rem;">
                                Reset
                            </button>
                        @else
                            <span title="Slug auto-generated from title" style="color: #64748b;">
                                <span class="material-symbols-outlined" style="font-size: 0.75rem;">auto_awesome</span>
                            </span>
                        @endif
                        <span style="color: #334155;">·</span>
                        <span style="color: #a5b4fc;">{{ $template }}</span>
                    </div>
                </div>

                @foreach($this->availableBlocks as $blockKey => $blockDef)
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem; text-transform: capitalize;">{{ str_replace('_', ' ', $blockKey) }}</h3>

                    @if(($blockDef['type'] ?? 'object') === 'html')
                        <textarea wire:model="content_blocks.{{ $blockKey }}" rows="8" placeholder="{{ $blockDef['label'] ?? 'Content' }}..."
                            style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; resize: vertical; box-sizing: border-box; font-family: monospace;"></textarea>

                    @elseif(($blockDef['type'] ?? 'object') === 'text')
                        <input type="text" wire:model="content_blocks.{{ $blockKey }}" placeholder="{{ $blockDef['label'] ?? $blockKey }}..."
                            style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">

                    @elseif(($blockDef['type'] ?? 'object') === 'image')
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <!-- Image Preview -->
                            <div style="width: 160px; height: 120px; border-radius: 0.5rem; overflow: hidden; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($content_blocks[$blockKey]))
                                    <img src="{{ $content_blocks[$blockKey] }}" alt="{{ $blockDef['label'] ?? $blockKey }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="material-symbols-outlined" style="font-size: 2.5rem; color: #475569;">image</span>
                                @endif
                            </div>
                            <!-- Controls -->
                            <div style="flex: 1; display: flex; flex-direction: column; gap: 0.5rem;">
                                <span style="font-size: 0.75rem; color: #64748b;">{{ $blockDef['label'] ?? 'Image' }}</span>
                                <button type="button"
                                    x-data
                                    x-on:click="$dispatch('open-media-picker', { targetField: '{{ $blockKey }}' })"
                                    style="padding: 0.5rem 1rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.5rem; font-size: 0.8125rem; cursor: pointer; display: flex; align-items: center; gap: 0.375rem; width: fit-content;">
                                    <span class="material-symbols-outlined" style="font-size: 1rem;">photo_library</span>
                                    Select Image
                                </button>
                                @if(!empty($content_blocks[$blockKey]))
                                <button type="button" wire:click="clearImage('{{ $blockKey }}')"
                                    style="padding: 0.375rem 0.75rem; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer; width: fit-content;">
                                    Remove Image
                                </button>
                                @endif
                                @if(!empty($content_blocks[$blockKey]))
                                <span style="font-size: 0.6875rem; color: #64748b; word-break: break-all;">{{ $content_blocks[$blockKey] }}</span>
                                @else
                                <span style="font-size: 0.6875rem; color: #475569; font-style: italic;">No image set — will use default fallback</span>
                                @endif
                            </div>
                        </div>

                    @elseif(($blockDef['type'] ?? 'object') === 'list')
                        @foreach($content_blocks[$blockKey] ?? [] as $idx => $item)
                        <div style="background: rgba(15,23,42,0.4); border: 1px solid rgba(148,163,184,0.1); border-radius: 0.5rem; padding: 1rem; margin-bottom: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                                <span style="font-size: 0.75rem; color: #64748b;">Item #{{ $idx + 1 }}</span>
                                <button type="button" wire:click="removeListItem('{{ $blockKey }}', {{ $idx }})" style="color: #ef4444; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Remove</button>
                            </div>
                            @foreach($blockDef['fields'] as $field)
                                @if(str_ends_with($field, 'image') || str_ends_with($field, 'img') || str_ends_with($field, 'photo') || str_ends_with($field, 'icon'))
                                    <div style="margin-bottom: 0.75rem;">
                                        <label style="font-size: 0.75rem; color: #94a3b8; display: block; margin-bottom: 0.25rem; text-transform: capitalize;">{{ str_replace('_', ' ', $field) }}</label>
                                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                                            <!-- Image Preview -->
                                            <div style="width: 100px; height: 75px; border-radius: 0.375rem; overflow: hidden; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                                @if(!empty($content_blocks[$blockKey][$idx][$field]))
                                                    <img src="{{ $content_blocks[$blockKey][$idx][$field] }}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #475569;">image</span>
                                                @endif
                                            </div>
                                            <!-- Controls -->
                                            <div style="flex: 1; display: flex; flex-direction: column; gap: 0.5rem;">
                                                <button type="button"
                                                    x-data
                                                    x-on:click="$dispatch('open-media-picker', { targetField: '{{ $blockKey }}.{{ $idx }}.{{ $field }}' })"
                                                    style="padding: 0.375rem 0.75rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; width: fit-content;">
                                                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">photo_library</span> Select
                                                </button>
                                                @if(!empty($content_blocks[$blockKey][$idx][$field]))
                                                <button type="button" wire:click="clearImage('{{ $blockKey }}.{{ $idx }}.{{ $field }}')"
                                                    style="padding: 0.25rem 0.5rem; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; border-radius: 0.25rem; font-size: 0.6875rem; cursor: pointer; width: fit-content;">
                                                    Remove
                                                </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="margin-bottom: 0.5rem;">
                                        <label style="font-size: 0.75rem; color: #94a3b8; display: block; margin-bottom: 0.25rem; text-transform: capitalize;">{{ str_replace('_', ' ', $field) }}</label>
                                        <input type="text" wire:model="content_blocks.{{ $blockKey }}.{{ $idx }}.{{ $field }}" style="width: 100%; padding: 0.5rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.375rem; color: #f1f5f9; font-size: 0.8125rem; outline: none; box-sizing: border-box;">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @endforeach
                        <button type="button" wire:click="addListItem('{{ $blockKey }}')" style="padding: 0.5rem 1rem; background: rgba(99,102,241,0.1); border: 1px dashed rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.5rem; font-size: 0.8125rem; cursor: pointer; width: 100%;">+ Add Item</button>

                    @else
                        @foreach($blockDef['fields'] ?? [] as $field)
                        <div style="margin-bottom: 0.75rem;">
                            <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem; text-transform: capitalize;">{{ str_replace('_', ' ', $field) }}</label>
                            @if(str_ends_with($field, 'video') || str_ends_with($field, 'video_url'))
                                <div style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="text" wire:model="content_blocks.{{ $blockKey }}.{{ $field }}" placeholder="Select a video..." style="flex: 1; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                                    <button type="button"
                                        x-data
                                        x-on:click="$dispatch('open-media-picker', { targetField: '{{ $blockKey }}.{{ $field }}' })"
                                        style="padding: 0.625rem 1rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer; display: flex; align-items: center; gap: 0.375rem; white-space: nowrap;">
                                        <span class="material-symbols-outlined" style="font-size: 1.125rem;">video_library</span> Select Video
                                    </button>
                                </div>
                            @elseif($field === 'body' || $field === 'description')
                                <textarea wire:model="content_blocks.{{ $blockKey }}.{{ $field }}" rows="4" style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box; resize: vertical;"></textarea>
                            @else
                                <input type="text" wire:model="content_blocks.{{ $blockKey }}.{{ $field }}" style="width: 100%; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;">
                            @endif
                        </div>
                        @endforeach
                    @endif
                </div>
                @endforeach
            </div>

            <!-- Sidebar -->
            <div>
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">Page Settings</h3>
                    <div style="margin-bottom: 1rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">Status</label>
                        <select wire:model="status" style="width: 100%; padding: 0.625rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>
                    <button type="submit" wire:loading.attr="disabled" style="width: 100%; padding: 0.625rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <span wire:loading>
                            <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                        <span wire:loading wire:loading.remove>Saving...</span>
                        <span wire:loading.remove wire:loading.class="hidden">Save Page</span>
                    </button>
                </div>

                <!-- Language / Locale -->
                <div class="card" style="margin-bottom: 1rem;">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.75rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #38bdf8;">translate</span> Language</h3>

                    {{-- Current locale badge (read-only) --}}
                    @php $currentLocaleInfo = $locales[$locale] ?? ['flag' => '🏳️', 'name' => strtoupper($locale)]; @endphp
                    <div style="padding: 0.625rem; background: rgba(56,189,248,0.08); border: 1px solid rgba(56,189,248,0.15); border-radius: 0.5rem; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                        <span style="font-size: 1.25rem;">{{ $currentLocaleInfo['flag'] }}</span>
                        <span style="color: #7dd3fc; font-weight: 600; font-size: 0.875rem;">{{ $currentLocaleInfo['name'] }}</span>
                        <span style="margin-left: auto; font-size: 0.6875rem; color: #64748b; text-transform: uppercase; font-weight: 700; background: rgba(56,189,248,0.15); padding: 0.125rem 0.375rem; border-radius: 0.25rem;">{{ $locale }}</span>
                    </div>

                    {{-- Linked translations --}}
                    @php $translations = $this->translations; @endphp
                    @if(count($translations) > 0)
                    <div style="margin-bottom: 0.75rem;">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.375rem; font-weight: 500;">Linked Translations</div>
                        @foreach($translations as $tr)
                        <a href="{{ route('admin.pages.edit', $tr['id']) }}"
                            style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: rgba(56,189,248,0.08); border: 1px solid rgba(56,189,248,0.15); border-radius: 0.375rem; color: #7dd3fc; font-size: 0.8125rem; text-decoration: none; margin-bottom: 0.375rem; transition: background 0.15s;"
                            onmouseover="this.style.background='rgba(56,189,248,0.15)'" onmouseout="this.style.background='rgba(56,189,248,0.08)'">
                            <span style="font-weight: 600; text-transform: uppercase; font-size: 0.6875rem; background: rgba(56,189,248,0.2); padding: 0.125rem 0.375rem; border-radius: 0.25rem;">{{ $tr['locale'] }}</span>
                            <span style="flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $tr['title'] }}</span>
                            <span style="font-size: 0.6875rem; color: {{ $tr['status'] === 'published' ? '#34d399' : '#94a3b8' }};">{{ $tr['status'] }}</span>
                        </a>
                        @endforeach
                    </div>
                    @endif

                    {{-- Create translation button --}}
                    @php
                        $otherLocale = $locale === 'id' ? 'en' : 'id';
                        $otherLocaleInfo = $locales[$otherLocale] ?? ['name' => strtoupper($otherLocale)];
                        $hasOtherTranslation = collect($translations)->where('locale', $otherLocale)->isNotEmpty();
                    @endphp
                    @if(!$hasOtherTranslation)
                    <div style="{{ count($translations) > 0 ? 'border-top: 1px solid rgba(148,163,184,0.1); padding-top: 0.75rem;' : '' }}">
                        <button type="button" wire:click="createTranslation('{{ $otherLocale }}')"
                            style="width: 100%; padding: 0.5rem; background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.2); color: #7dd3fc; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 0.375rem;"
                            wire:loading.attr="disabled">
                            <span class="material-symbols-outlined" style="font-size: 0.875rem;">add</span>
                            Create {{ $otherLocaleInfo['name'] }} Version
                        </button>
                        <div style="font-size: 0.6875rem; color: #64748b; margin-top: 0.375rem; text-align: center;">
                            Creates a new page linked as translation
                        </div>
                    </div>
                    @endif
                </div>

                <!-- SEO -->
                <div class="card">
                    <h3 style="font-size: 0.9375rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem; display: flex; align-items: center; gap: 0.375rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; color: #64748b;">search</span> SEO</h3>
                    <div style="margin-bottom: 0.75rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.25rem;">SEO Title</label>
                        <input type="text" wire:model="seo_title" style="width: 100%; padding: 0.5rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.375rem; color: #f1f5f9; font-size: 0.8125rem; outline: none; box-sizing: border-box;">
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.25rem;">SEO Description</label>
                        <textarea wire:model="seo_description" rows="2" style="width: 100%; padding: 0.5rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.375rem; color: #f1f5f9; font-size: 0.8125rem; outline: none; resize: vertical; box-sizing: border-box;"></textarea>
                    </div>
                    <div style="margin-bottom: 0.75rem;">
                        <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.25rem;">SEO Image</label>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            @if(!empty($seo_image))
                            <div style="width: 100%; height: 120px; border-radius: 0.375rem; overflow: hidden; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2);">
                                <img src="{{ $seo_image }}" alt="SEO Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            @endif
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button type="button"
                                    x-data
                                    x-on:click="$dispatch('open-media-picker', { targetField: 'seo_image' })"
                                    style="padding: 0.375rem 0.75rem; background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">photo_library</span> Select
                                </button>
                                @if(!empty($seo_image))
                                <button type="button" wire:click="clearImage('seo_image')"
                                    style="padding: 0.375rem 0.75rem; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; border-radius: 0.375rem; font-size: 0.75rem; cursor: pointer;">
                                    Remove
                                </button>
                                @endif
                            </div>
                            @if(empty($seo_image))
                            <span style="font-size: 0.6875rem; color: #475569; font-style: italic;">Optional image for social sharing</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Media Picker Modal (reusable) -->
    @livewire('media.media-picker')
</div>
