<div>
    @section('title', 'WordPress Import')

    <div style="margin-bottom: 1.5rem;">
        <p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">Migrate content from any WordPress site using its public REST API. This will import categories, tags, posts, and automatically download all images to your local media library.</p>
    </div>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        
        @if($importStatus === 'running')
            <div wire:poll.2s="checkProgress" style="text-align: center; padding: 2rem 0;">
                <h3 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">Importing Data...</h3>
                <p style="color: #94a3b8; margin-bottom: 1.5rem;">{{ $importMessage }}</p>
                
                <div style="background: rgba(148, 163, 184, 0.1); border-radius: 9999px; height: 8px; width: 100%; overflow: hidden; margin-bottom: 2rem;">
                    <div style="background: var(--color-primary); height: 100%; transition: width 0.5s ease; width: {{ $progress }}%;"></div>
                </div>

                <div style="text-align: left; background: rgba(0,0,0,0.2); padding: 1rem; border-radius: 0.5rem; font-family: monospace; font-size: 0.75rem; color: #cbd5e1; max-height: 200px; overflow-y: auto;">
                    @foreach($importLogs as $log)
                        <div>{{ $log }}</div>
                    @endforeach
                </div>
            </div>
        @elseif($importStatus === 'completed')
            <div style="text-align: center; padding: 2rem 0;">
                <div class="material-symbols-outlined" style="font-size: 3.5rem; color: #10b981; margin-bottom: 1rem;">check_circle</div>
                <h3 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">Import Completed!</h3>
                <p style="color: #94a3b8; margin-bottom: 1.5rem;">{{ $importMessage }}</p>
                <a href="{{ route('admin.posts.index') }}" style="padding: 0.625rem 1.5rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; display: inline-block;">View Posts</a>
            </div>
        @elseif($importStatus === 'failed')
            <div style="text-align: center; padding: 2rem 0;">
                <div class="material-symbols-outlined" style="font-size: 3.5rem; color: #ef4444; margin-bottom: 1rem;">cancel</div>
                <h3 style="font-size: 1.25rem; font-weight: 500; margin-bottom: 1rem;">Import Failed</h3>
                <p style="color: #f87171; margin-bottom: 1.5rem;">{{ $importMessage }}</p>
                <button wire:click="$set('importStatus', '')" style="padding: 0.625rem 1.5rem; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.2); color: #94a3b8; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; cursor: pointer;">Try Again</button>
            </div>
        @else
            <!-- Form -->
            <form wire:submit="testConnection">
                <div style="margin-bottom: 1rem;">
                    <label style="font-size: 0.8125rem; color: #94a3b8; font-weight: 500; display: block; margin-bottom: 0.375rem;">WordPress Site URL</label>
                    <div style="display: flex; gap: 1rem;">
                        <input type="url" wire:model="url" placeholder="https://example.com" required style="flex: 1; padding: 0.625rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; outline: none; box-sizing: border-box;" {{ $connectionOk ? 'disabled' : '' }}>
                        @if(!$connectionOk)
                            <button type="submit" style="padding: 0.625rem 1.25rem; background: rgba(148,163,184,0.1); border: 1px solid rgba(148,163,184,0.2); color: #94a3b8; border-radius: 0.5rem; font-size: 0.875rem; cursor: pointer; display: flex; align-items: center; justify-content: center; min-width: 150px; font-weight: 500;" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="testConnection">Test Connection</span>
                                <span wire:loading wire:target="testConnection">Testing...</span>
                            </button>
                        @endif
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">Must be a publicly accessible WordPress site with the REST API enabled.</div>
                </div>
            </form>

            @if($connectionMessage)
                <div style="margin-top: 1rem; padding: 1rem; border-radius: 0.5rem; background: {{ $connectionOk ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }}; color: {{ $connectionOk ? '#34d399' : '#f87171' }}; border: 1px solid {{ $connectionOk ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }};">
                    {{ $connectionMessage }}
                </div>
            @endif

            @if($connectionOk)
                <div style="margin-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.1); padding-top: 2rem;">
                    <h3 style="font-size: 1rem; font-weight: 500; margin-bottom: 1rem;">Ready to Import</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem; color: #94a3b8; font-size: 0.875rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #10b981;">✓</span> Categories & Tags
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #10b981;">✓</span> All Posts (Title, content, excerpt, status, publish date)
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="color: #10b981;">✓</span> Featured Images & Inline Content Images (Downloaded automatically)
                        </div>
                    </div>
                    
                    <button wire:click="startImport" style="width: 100%; padding: 0.75rem 2rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="startImport">Start Import Process</span>
                        <span wire:loading wire:target="startImport">Starting...</span>
                    </button>
                    <p style="text-align: center; font-size: 0.75rem; color: #64748b; margin-top: 1rem;">The import will run in the background. It may take several minutes depending on the size of the site and number of images to download.</p>
                </div>
            @endif
        @endif

    </div>
</div>
