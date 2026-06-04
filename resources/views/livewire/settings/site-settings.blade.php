<div>
    @section('title', 'Settings')

    <div style="display: flex; gap: 0.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid rgba(148,163,184,0.1);">
        @php
            $tabs = [
                'general' => ['icon' => 'settings', 'label' => 'General'],
                'analytics' => ['icon' => 'analytics', 'label' => 'Analytics'],
                'social' => ['icon' => 'link', 'label' => 'Social'],
                'export' => ['icon' => 'rocket_launch', 'label' => 'Export'],
                'deploy' => ['icon' => 'cloud_upload', 'label' => 'Deploy']
            ];
        @endphp
        @foreach($tabs as $tab => $data)
        <button wire:click="$set('activeTab', '{{ $tab }}')" style="padding: 0.75rem 1.25rem; border: none; font-size: 0.875rem; font-weight: 500; cursor: pointer; border-bottom: 2px solid {{ $activeTab === $tab ? '#6366f1' : 'transparent' }}; color: {{ $activeTab === $tab ? '#a5b4fc' : '#94a3b8' }}; background: transparent; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-outlined" style="font-size: 1.125rem;">{{ $data['icon'] }}</span>
            {{ $data['label'] }}
        </button>
        @endforeach
    </div>

    @if($activeTab === 'general')
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1.5rem;">General Settings</h3>
        <div style="max-width: 600px;">
            @foreach(['site_name' => 'Site Name', 'site_tagline' => 'Tagline', 'site_url' => 'Site URL'] as $f => $l)
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">{{ $l }}</label>
                <input type="{{ $f === 'site_url' ? 'url' : 'text' }}" wire:model="{{ $f }}" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
            </div>
            @endforeach
            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">Footer Text</label>
                <textarea wire:model="footer_text" rows="2" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;resize:vertical;box-sizing:border-box;"></textarea>
            </div>
            <button wire:click="saveGeneral" wire:loading.attr="disabled" style="padding:0.625rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                <span wire:loading wire:target="saveGeneral">
                    <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <span wire:loading wire:target="saveGeneral">Saving...</span>
                <span wire:loading.remove wire:target="saveGeneral">Save</span>
            </button>
        </div>
    </div>
    @elseif($activeTab === 'analytics')
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1.5rem;">Analytics</h3>
        <div style="max-width: 600px;">
            @foreach(['gtm_id' => 'GTM ID', 'ga_id' => 'GA ID', 'clarity_id' => 'Clarity ID', 'ahrefs_key' => 'Ahrefs Key'] as $f => $l)
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">{{ $l }}</label>
                <input type="text" wire:model="{{ $f }}" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
            </div>
            @endforeach
            <button wire:click="saveAnalytics" wire:loading.attr="disabled" style="padding:0.625rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                <span wire:loading wire:target="saveAnalytics">
                    <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <span wire:loading wire:target="saveAnalytics">Saving...</span>
                <span wire:loading.remove wire:target="saveAnalytics">Save</span>
            </button>
        </div>
    </div>
    @elseif($activeTab === 'social')
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1.5rem;">Social Links</h3>
        <div style="max-width: 600px;">
            @foreach(['social_facebook' => 'Facebook', 'social_twitter' => 'Twitter/X', 'social_instagram' => 'Instagram', 'social_linkedin' => 'LinkedIn', 'social_youtube' => 'YouTube'] as $f => $l)
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">{{ $l }}</label>
                <input type="url" wire:model="{{ $f }}" placeholder="https://..." style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
            </div>
            @endforeach
            <button wire:click="saveSocial" wire:loading.attr="disabled" style="padding:0.625rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                <span wire:loading wire:target="saveSocial">
                    <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <span wire:loading wire:target="saveSocial">Saving...</span>
                <span wire:loading.remove wire:target="saveSocial">Save</span>
            </button>
        </div>
    </div>
    @elseif($activeTab === 'export')
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1.5rem;">Export Settings</h3>
        <div style="max-width: 600px;">
            <div style="margin-bottom: 1.5rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">CSP Mode</label>
                <select wire:model="csp_mode" style="width:100%;padding:0.625rem;background:#0f172a;border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;">
                    <option value="strict">Strict — Fail on violations</option>
                    <option value="warning">Warning — Continue with warnings</option>
                </select>
            </div>
            <button wire:click="saveExport" wire:loading.attr="disabled" style="padding:0.625rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                <span wire:loading wire:target="saveExport">
                    <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                </span>
                <span wire:loading wire:target="saveExport">Saving...</span>
                <span wire:loading.remove wire:target="saveExport">Save</span>
            </button>
        </div>
    </div>
    @endif

    @if($activeTab === 'deploy')
    <div class="card">
        <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <span class="material-symbols-outlined" style="font-size: 1.25rem; color: #a5b4fc;">cloud_upload</span>
            GitHub Deploy Configuration
        </h3>
        <p style="color: #64748b; font-size: 0.8125rem; margin: 0 0 1.5rem;">Configure automatic deployment of static exports to a GitHub repository via Pull Request.</p>
        <div style="max-width: 600px;">
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">GitHub Repository <span style="color:#64748b;">(owner/repo)</span></label>
                <input type="text" wire:model="github_deploy_repo" placeholder="alfredrahardian/company-static-site" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                @error('github_deploy_repo') <span style="color:#f87171;font-size:0.75rem;">{{ $message }}</span> @enderror
            </div>
            <div style="margin-bottom: 1.25rem;">
                <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">GitHub Personal Access Token</label>
                <input type="password" wire:model="github_deploy_token" placeholder="github_pat_xxxxx..." style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;font-family:monospace;">
                <p style="font-size: 0.6875rem; color: #475569; margin: 0.375rem 0 0;">Fine-grained token with <b>Contents</b> (Read & Write) and <b>Pull Requests</b> (Read & Write) permissions.</p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">Target Branch</label>
                    <input type="text" wire:model="github_deploy_branch" placeholder="main" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                </div>
                <div>
                    <label style="font-size: 0.8125rem; color: #94a3b8; display: block; margin-bottom: 0.375rem;">Firebase Site ID</label>
                    <input type="text" wire:model="firebase_site_id" placeholder="defenxor-com" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                </div>
            </div>

            {{-- Auto-merge Toggle --}}
            <div style="margin-bottom: 1.25rem; padding: 1rem; background: rgba(15,23,42,0.4); border: 1px solid rgba(148,163,184,0.15); border-radius: 0.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <label style="font-size: 0.875rem; font-weight: 600; color: #e2e8f0; display: block; margin-bottom: 0.25rem;">Auto-merge Pull Requests</label>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0;">
                            @if($github_deploy_auto_merge)
                                PRs will be automatically merged after creation. Changes go live immediately.
                            @else
                                PRs will be left open for manual review on GitHub before merging.
                            @endif
                        </p>
                    </div>
                    <button wire:click="$toggle('github_deploy_auto_merge')" type="button"
                        style="position: relative; width: 48px; height: 26px; border-radius: 9999px; border: none; cursor: pointer; transition: background 0.2s; flex-shrink: 0;
                        {{ $github_deploy_auto_merge ? 'background: linear-gradient(135deg, #22c55e, #10b981);' : 'background: rgba(148,163,184,0.2);' }}">
                        <span style="position: absolute; top: 3px; width: 20px; height: 20px; border-radius: 50%; background: white; transition: left 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.3);
                            {{ $github_deploy_auto_merge ? 'left: 25px;' : 'left: 3px;' }}"></span>
                    </button>
                </div>
                @if(!$github_deploy_auto_merge)
                <div style="margin-top: 0.75rem; padding: 0.5rem 0.75rem; background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2); border-radius: 0.375rem; display: flex; align-items: center; gap: 0.375rem;">
                    <span class="material-symbols-outlined" style="font-size: 1rem; color: #fbbf24;">info</span>
                    <span style="font-size: 0.75rem; color: #fcd34d;">Editorial workflow: PRs require manual review and merge on GitHub.</span>
                </div>
                @endif
            </div>
            {{-- Connection Test Result --}}
            @if($connectionTestResult)
            <div style="margin-bottom: 1.25rem; padding: 0.75rem 1rem; border-radius: 0.5rem; {{ $connectionTestResult['success'] ? 'background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25); color: #86efac;' : 'background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5;' }} font-size: 0.8125rem; display: flex; align-items: center; gap: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem;">{{ $connectionTestResult['success'] ? 'check_circle' : 'error' }}</span>
                {{ $connectionTestResult['message'] }}
            </div>
            @endif

            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <button wire:click="saveDeploy" wire:loading.attr="disabled" style="padding:0.625rem 1.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                    <span wire:loading wire:target="saveDeploy">
                        <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="saveDeploy">Saving...</span>
                    <span wire:loading.remove wire:target="saveDeploy">Save</span>
                </button>
                <button wire:click="testGithubConnection" wire:loading.attr="disabled" wire:target="testGithubConnection" style="padding:0.625rem 1.5rem;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                    <span wire:loading wire:target="testGithubConnection">
                        <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="testGithubConnection">Testing...</span>
                    <span wire:loading.remove wire:target="testGithubConnection">
                        <span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: middle;">wifi_tethering</span> Test Connection
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
