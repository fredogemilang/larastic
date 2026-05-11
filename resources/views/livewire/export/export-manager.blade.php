<div>
    @section('title', 'Export')

    <!-- Pending Changes Banner -->
    @php $changes = $this->pendingChanges; @endphp
    @if($changes['total'] > 0)
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, rgba(251,191,36,0.1), rgba(245,158,11,0.05)); border: 1px solid rgba(251,191,36,0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b); animation: shimmer 2s ease-in-out infinite;"></div>
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 44px; height: 44px; background: rgba(251,191,36,0.2); border-radius: 0.625rem; display: flex; align-items: center; justify-content: center; animation: pulse-glow 2s ease-in-out infinite;">
                    <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #fbbf24;">sync</span>
                </div>
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: #fcd34d; margin: 0;">
                        {{ $changes['total'] }} pending {{ Str::plural('change', $changes['total']) }}
                    </h3>
                    <p style="font-size: 0.8125rem; color: #94a3b8; margin: 0.125rem 0 0;">
                        @if($changes['since'])
                            Since last export {{ $changes['since']->diffForHumans() }}
                        @else
                            No export has been run yet
                        @endif
                    </p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                @foreach([
                    ['post_created', 'Post Created', '#22c55e'],
                    ['post_updated', 'Post Updated', '#f59e0b'],
                    ['post_deleted', 'Post Deleted', '#ef4444'],
                    ['page_created', 'Page Created', '#22c55e'],
                    ['page_updated', 'Page Updated', '#f59e0b'],
                    ['page_deleted', 'Page Deleted', '#ef4444'],
                ] as [$key, $label, $color])
                    @if($changes[$key] > 0)
                    <span style="padding: 0.25rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: {{ $color }}15; color: {{ $color }}; border: 1px solid {{ $color }}30;">
                        {{ $changes[$key] }} {{ $label }}
                    </span>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Expandable Change Log -->
        <div style="margin-top: 0.75rem;">
            <button wire:click="$toggle('showChangeLog')" style="color: #fbbf24; background: none; border: none; font-size: 0.8125rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; padding: 0;">
                <span class="material-symbols-outlined" style="font-size: 1rem; transition: transform 0.2s; {{ $showChangeLog ? 'transform: rotate(90deg);' : '' }}">chevron_right</span>
                {{ $showChangeLog ? 'Hide change log' : 'Show change log' }}
            </button>
        </div>

        @if($showChangeLog && count($changes['recent']) > 0)
        <div style="margin-top: 0.75rem; max-height: 280px; overflow-y: auto; border-top: 1px solid rgba(251,191,36,0.15); padding-top: 0.75rem;">
            @foreach($changes['recent'] as $rev)
            <div style="display: flex; align-items: flex-start; gap: 0.625rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(148,163,184,0.05);">
                <span class="material-symbols-outlined" style="font-size: 1.125rem; color: {{ $rev['action_color'] }}; margin-top: 0.0625rem;">{{ $rev['action_icon'] }}</span>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 0.8125rem; color: #e2e8f0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $rev['summary'] }}</div>
                    <div style="font-size: 0.6875rem; color: #64748b;">{{ $rev['user'] }} · {{ $rev['created_at'] }}</div>
                </div>
                <span style="padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.625rem; font-weight: 600; background: rgba(148,163,184,0.1); color: #94a3b8; flex-shrink: 0;">{{ $rev['type_label'] }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @else
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(16,185,129,0.04)); border: 1px solid rgba(34,197,94,0.25);">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 44px; height: 44px; background: rgba(34,197,94,0.15); border-radius: 0.625rem; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #22c55e;">check_circle</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; font-weight: 600; color: #86efac; margin: 0;">Up to date</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin: 0.125rem 0 0;">No content changes since the last export.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Export Scope Analysis -->
    @php $scope = $this->exportScope; @endphp
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(135deg, rgba(99,102,241,0.08), rgba(139,92,246,0.04)); border: 1px solid rgba(99,102,241,0.2); position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #6366f1, #8b5cf6, #6366f1);"></div>

        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <div style="width: 44px; height: 44px; background: rgba(99,102,241,0.2); border-radius: 0.625rem; display: flex; align-items: center; justify-content: center;">
                <span class="material-symbols-outlined" style="font-size: 1.5rem; color: #a5b4fc;">analytics</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; font-weight: 700; color: #e2e8f0; margin: 0;">Export Scope Analysis</h3>
                <p style="font-size: 0.8125rem; color: #64748b; margin: 0.125rem 0 0;">
                    System recommendation based on pending changes
                </p>
            </div>
            @if($scope['can_partial'])
            <span style="margin-left: auto; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3);">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; margin-right: 0.125rem;">speed</span>
                Partial Available
            </span>
            @elseif($scope['recommended_mode'] === 'none')
            <span style="margin-left: auto; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(34,197,94,0.1); color: #86efac; border: 1px solid rgba(34,197,94,0.2);">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; margin-right: 0.125rem;">check_circle</span>
                Up to Date
            </span>
            @elseif(!empty($scope['force_full_reasons']))
            <span style="margin-left: auto; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(251,191,36,0.15); color: #fcd34d; border: 1px solid rgba(251,191,36,0.3);">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; margin-right: 0.125rem;">priority_high</span>
                Full Export Required
            </span>
            @endif
        </div>

        {{-- Force Full Reasons --}}
        @if(!$scope['can_partial'] && !empty($scope['force_full_reasons']))
        <div style="background: rgba(251,191,36,0.05); border: 1px solid rgba(251,191,36,0.15); border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem;">
            <div style="font-size: 0.75rem; font-weight: 600; color: #fcd34d; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem;">warning</span>
                Reasons requiring full export:
            </div>
            @foreach($scope['force_full_reasons'] as $reason)
            <div style="font-size: 0.8125rem; color: #94a3b8; padding: 0.25rem 0 0.25rem 1rem; display: flex; align-items: flex-start; gap: 0.375rem;">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; color: #f59e0b; flex-shrink: 0; margin-top: 0.1rem;">arrow_right</span>
                {{ $reason }}
            </div>
            @endforeach
        </div>
        @endif

        {{-- Partial Items Preview --}}
        @if($scope['can_partial'] && !empty($scope['partial_items']))
        <div style="background: rgba(34,197,94,0.05); border: 1px solid rgba(34,197,94,0.15); border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem;">
            <div style="font-size: 0.75rem; font-weight: 600; color: #86efac; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                <span class="material-symbols-outlined" style="font-size: 1rem;">checklist</span>
                Items to re-render ({{ count($scope['partial_items']) }}):
            </div>
            @foreach($scope['partial_items'] as $pItem)
            <div style="font-size: 0.8125rem; color: #94a3b8; padding: 0.25rem 0 0.25rem 1rem; display: flex; align-items: flex-start; gap: 0.375rem;">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; color: #22c55e; flex-shrink: 0; margin-top: 0.1rem;">{{ $pItem['type'] === 'Post' ? 'article' : 'description' }}</span>
                <span>
                    <span style="color: #e2e8f0; font-weight: 500;">{{ $pItem['title'] }}</span>
                    <span style="font-size: 0.6875rem; color: #64748b;"> ({{ $pItem['type'] }}, fields: {{ implode(', ', $pItem['fields']) }})</span>
                </span>
            </div>
            @endforeach
            <div style="font-size: 0.6875rem; color: #64748b; margin-top: 0.5rem; padding-left: 1rem;">
                + sitemap.xml, robots.txt, rss.xml will also be regenerated
            </div>
        </div>
        @endif

        {{-- Export Buttons --}}
        <div style="display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;">
            {{-- Partial Export Button --}}
            @if($scope['can_partial'])
            <button wire:click="startPartialExport" wire:loading.attr="disabled" wire:target="startPartialExport, startFullExport"
                style="padding: 0.75rem 1.75rem; background: linear-gradient(135deg, #22c55e, #10b981); color: white; border: none; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ $changes['total'] > 0 ? 'animation: pulse-btn-green 2s ease-in-out infinite;' : '' }}">
                <span wire:loading.remove wire:target="startPartialExport"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">speed</span> Partial Export</span>
                <span wire:loading wire:target="startPartialExport"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem; animation: spin 2s linear infinite;">pending</span> Exporting...</span>
            </button>
            @else
            <button disabled
                style="padding: 0.75rem 1.75rem; background: rgba(148,163,184,0.1); color: #475569; border: 1px solid rgba(148,163,184,0.15); border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: not-allowed;">
                <span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">speed</span> Partial Export
            </button>
            @endif

            {{-- Full Export Button --}}
            <button wire:click="startFullExport" wire:loading.attr="disabled" wire:target="startFullExport, startPartialExport"
                style="padding: 0.75rem 1.75rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border: none; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: pointer; transition: all 0.2s; {{ !$scope['can_partial'] && $changes['total'] > 0 ? 'animation: pulse-btn 2s ease-in-out infinite;' : '' }}">
                <span wire:loading.remove wire:target="startFullExport"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">rocket_launch</span> Full Export</span>
                <span wire:loading wire:target="startFullExport"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem; animation: spin 2s linear infinite;">pending</span> Exporting...</span>
            </button>

            @if($scope['can_partial'])
            <span style="font-size: 0.75rem; color: #64748b;">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle;">info</span>
                Partial is faster. Full is always available.
            </span>
            @endif
        </div>
    </div>

    <!-- Tools Panel -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- CSP Validator -->
        <div class="card" style="text-align: center; padding: 2rem;">
            <div class="material-symbols-outlined" style="font-size: 3rem; margin-bottom: 0.75rem; color: #8b5cf6;">security</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.5rem;">CSP Validator</h3>
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem;">Check all pages for Content Security Policy compliance.</p>
            <button wire:click="runCspCheck" wire:loading.attr="disabled" wire:target="runCspCheck"
                style="padding: 0.75rem 2rem; background: rgba(99,102,241,0.15); border: 1px solid rgba(99,102,241,0.3); color: #a5b4fc; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: pointer;">
                <span wire:loading.remove wire:target="runCspCheck"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">search</span> Run Validation</span>
                <span wire:loading wire:target="runCspCheck"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem; animation: spin 2s linear infinite;">pending</span> Scanning...</span>
            </button>
        </div>

        <!-- Purify Content -->
        <div class="card" style="text-align: center; padding: 2rem;">
            <div class="material-symbols-outlined" style="font-size: 3rem; margin-bottom: 0.75rem; color: #10b981;">cleaning_services</div>
            <h3 style="font-size: 1.125rem; font-weight: 600; color: #f1f5f9; margin: 0 0 0.5rem;">Purify Content</h3>
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem;">Strip inline styles, scripts, and disallowed HTML from all posts.</p>
            <button wire:click="purifyAllPosts" wire:loading.attr="disabled" wire:target="purifyAllPosts"
                style="padding: 0.75rem 2rem; background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; cursor: pointer;">
                <span wire:loading.remove wire:target="purifyAllPosts"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem;">cleaning_services</span> Purify All</span>
                <span wire:loading wire:target="purifyAllPosts"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; margin-right: 0.25rem; animation: spin 2s linear infinite;">pending</span> Purifying...</span>
            </button>
        </div>
    </div>


    <!-- Ultra-Strict CSP Snippet -->
    <div class="card" style="margin-bottom: 1.5rem; background: linear-gradient(to right, rgba(15,23,42,0.8), rgba(30,41,59,0.8)); border-left: 4px solid #10b981;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="material-symbols-outlined" style="font-size: 1.25rem; color: #10b981;">verified_user</span>
                <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0;">Ultra-Strict CSP Configuration</h3>
            </div>
            <span style="font-size: 0.75rem; background: rgba(16,185,129,0.2); color: #6ee7b7; padding: 0.25rem 0.5rem; border-radius: 9999px; font-weight: 600;">100% Secure</span>
        </div>
        <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
            Copy and paste this snippet into your static server's <code>.htaccess</code> file. 
            The <code>script-src</code> dynamically includes your latest Analytics/GTM Hash (<b>{{ $cspHash }}</b>) using <code>'strict-dynamic'</code>.
        </p>
        <div style="position: relative;">
            <pre style="background: #0f172a; padding: 1rem; border-radius: 0.5rem; border: 1px solid rgba(148,163,184,0.1); overflow-x: auto; color: #e2e8f0; font-size: 0.8125rem; line-height: 1.5;"><code id="csp-code">&lt;IfModule mod_headers.c&gt;
Header set Content-Security-Policy "default-src 'self'; script-src '{{ $cspHash }}' 'strict-dynamic' 'unsafe-inline' https://www.googletagmanager.com https://www.google-analytics.com https://www.clarity.ms https://analytics.ahrefs.com https://static.cloudflareinsights.com https://challenges.cloudflare.com https://ajax.cloudflare.com https: http:; style-src 'self' https://fonts.googleapis.com; font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https://*.google-analytics.com https://*.analytics.google.com https://*.googletagmanager.com https://*.clarity.ms https://analytics.ahrefs.com https://cloudflareinsights.com; object-src 'none'; base-uri 'self'; frame-ancestors 'none'; frame-src 'self' https://challenges.cloudflare.com; upgrade-insecure-requests;"
&lt;/IfModule&gt;</code></pre>
            <button onclick="navigator.clipboard.writeText(document.getElementById('csp-code').innerText); alert('CSP Copied to Clipboard!');" style="position: absolute; top: 0.5rem; right: 0.5rem; padding: 0.375rem 0.75rem; background: rgba(255,255,255,0.1); color: #f1f5f9; border: none; border-radius: 0.25rem; font-size: 0.75rem; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <span class="material-symbols-outlined" style="font-size: 1rem;">content_copy</span> Copy
            </button>
        </div>
    </div>

    <!-- Purify Result -->
    @if($purifyResult)
    <div class="card" style="margin-bottom: 1.5rem; border-color: {{ $purifyResult['changed'] > 0 ? 'rgba(16,185,129,0.3)' : 'rgba(34,197,94,0.3)' }};">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
            <span class="material-symbols-outlined" style="font-size: 1.25rem; color: #10b981;">check_circle</span>
            <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0;">
                Purify Complete — {{ $purifyResult['changed'] }} of {{ $purifyResult['total'] }} posts cleaned
            </h3>
        </div>
        @if($purifyResult['changed'] > 0)
        <div style="max-height: 200px; overflow-y: auto;">
            @foreach($purifyResult['posts'] as $title)
            <div style="padding: 0.25rem 0 0.25rem 1rem; font-size: 0.8125rem; color: #94a3b8;">
                <span class="material-symbols-outlined" style="font-size: 0.875rem; vertical-align: middle; color: #10b981; margin-right: 0.25rem;">check</span> {{ $title }}
            </div>
            @endforeach
        </div>
        @else
        <p style="color: #6ee7b7; font-size: 0.875rem; margin: 0;">All posts are already clean!</p>
        @endif
    </div>
    @endif

    <!-- CSP Report Preview -->
    @if($cspPreview)
    <div class="card" style="margin-bottom: 1.5rem; border-color: {{ $cspPreview['passed'] ? 'rgba(34,197,94,0.3)' : 'rgba(251,191,36,0.3)' }};">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0;">
                @if($cspPreview['passed'])
                    <span class="material-symbols-outlined" style="font-size: 1.25rem; vertical-align: middle; color: #10b981; margin-right: 0.25rem;">check_circle</span> CSP Validation Passed
                @else
                    <span class="material-symbols-outlined" style="font-size: 1.25rem; vertical-align: middle; color: #f59e0b; margin-right: 0.25rem;">warning</span> CSP Violations Found
                @endif
            </h3>
            <span style="font-size: 0.8125rem; color: #64748b;">Mode: {{ ucfirst($cspPreview['mode']) }}</span>
        </div>

        @if(!$cspPreview['passed'])
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
            @foreach($cspPreview['summary'] as $type => $count)
            @if($count > 0)
            <div style="background: rgba(251,191,36,0.1); border: 1px solid rgba(251,191,36,0.2); border-radius: 0.5rem; padding: 0.75rem; text-align: center;">
                <div style="font-size: 1.25rem; font-weight: 700; color: #fcd34d;">{{ $count }}</div>
                <div style="font-size: 0.6875rem; color: #94a3b8; text-transform: capitalize;">{{ str_replace('_', ' ', $type) }}</div>
            </div>
            @endif
            @endforeach
        </div>

        <button wire:click="$toggle('showCspDetail')" style="color: #6366f1; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">
            {{ $showCspDetail ? 'Hide Details' : 'Show Details' }}
        </button>

        @if($showCspDetail)
        <div style="margin-top: 1rem; max-height: 300px; overflow-y: auto;">
            @foreach($cspPreview['per_page'] as $url => $violations)
            <div style="margin-bottom: 0.75rem;">
                <div style="font-size: 0.8125rem; font-weight: 600; color: #e2e8f0;"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: bottom; color: #94a3b8; margin-right: 0.25rem;">description</span> {{ $url }}</div>
                @foreach($violations as $v)
                <div style="padding: 0.375rem 0 0.375rem 1rem; font-size: 0.75rem; color: #94a3b8;">
                    <span class="material-symbols-outlined" style="font-size: 1rem; vertical-align: bottom; color: #f59e0b; margin-right: 0.25rem;">warning</span> [{{ $v['type'] }}] {{ $v['message'] }}
                    @if($v['snippet'])<br><code style="color: #64748b; font-size: 0.6875rem;">{{ $v['snippet'] }}</code>@endif
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
        @endif
        @endif
    </div>
    @endif

    <!-- Export History -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid rgba(148,163,184,0.1);">
            <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0;">Export History</h3>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">ID</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Type</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Size</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Triggered By</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Date</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Deploy</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Actions</th>
                </tr>
            </thead>
            @forelse($exports as $export)
            <tbody x-data="{ showErrors: false }">
                <tr style="border-bottom: {{ $export->status === 'failed' && $export->errors ? 'none' : '1px solid rgba(148,163,184,0.05)' }};">
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">#{{ $export->id }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        <span style="padding: 0.125rem 0.5rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600;
                            {{ $export->type === 'partial' ? 'background: rgba(34,197,94,0.15); color: #86efac;' : 'background: rgba(99,102,241,0.15); color: #a5b4fc;' }}">
                            {{ ucfirst($export->type) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($export->status === 'failed' && $export->errors)
                        <button @click="showErrors = !showErrors" style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;">
                            <span class="material-symbols-outlined" style="font-size: 0.875rem;">error</span>
                            Failed
                            <span class="material-symbols-outlined" style="font-size: 0.75rem; transition: transform 0.2s;" :style="showErrors ? 'transform: rotate(180deg)' : ''">expand_more</span>
                        </button>
                        @else
                        <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600;
                            {{ $export->status === 'completed' ? 'background: rgba(34,197,94,0.15); color: #86efac;' : ($export->status === 'failed' ? 'background: rgba(239,68,68,0.15); color: #fca5a5;' : 'background: rgba(251,191,36,0.15); color: #fcd34d;') }}">
                            {{ ucfirst($export->status) }}
                        </span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; color: #94a3b8; font-size: 0.8125rem;">{{ $export->file_size ? number_format($export->file_size / 1024, 1) . ' KB' : '—' }}</td>
                    <td style="padding: 0.75rem 1rem; color: #94a3b8; font-size: 0.8125rem;">{{ $export->triggeredBy?->name ?? 'CLI' }}</td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $export->created_at->format('M d, H:i') }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        @if($export->status === 'completed')
                            @if($export->deploy_status === 'deployed')
                            <a href="{{ $export->deploy_pr_url }}" target="_blank" style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(34,197,94,0.15); color: #86efac; border: 1px solid rgba(34,197,94,0.3); text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">check_circle</span>
                                Deployed
                            </a>
                            @elseif($export->deploy_status === 'deploying')
                            <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(251,191,36,0.15); color: #fcd34d; border: 1px solid rgba(251,191,36,0.3); display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem; animation: spin 2s linear infinite;">pending</span>
                                Deploying...
                            </span>
                            @elseif($export->deploy_status === 'failed')
                            <button wire:click="deployToGithub({{ $export->id }})" wire:loading.attr="disabled" wire:target="deployToGithub({{ $export->id }})" style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;">
                                <span class="material-symbols-outlined" style="font-size: 0.875rem;">refresh</span>
                                Retry
                            </button>
                            @else
                            <button wire:click="deployToGithub({{ $export->id }})" wire:loading.attr="disabled" wire:target="deployToGithub({{ $export->id }})" style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; background: rgba(99,102,241,0.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,0.3); cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; transition: all 0.2s;">
                                <span wire:loading.remove wire:target="deployToGithub({{ $export->id }})">
                                    <span class="material-symbols-outlined" style="font-size: 0.875rem;">cloud_upload</span>
                                    Deploy
                                </span>
                                <span wire:loading wire:target="deployToGithub({{ $export->id }})">
                                    <span class="material-symbols-outlined" style="font-size: 0.875rem; animation: spin 2s linear infinite;">pending</span>
                                    Deploying...
                                </span>
                            </button>
                            @endif
                        @else
                            <span style="color: #475569; font-size: 0.6875rem;">—</span>
                        @endif
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: right;">
                        @if($export->status === 'completed' && $export->output_path)
                        <button wire:click="downloadExport({{ $export->id }})" style="color: #6366f1; background: none; border: none; font-size: 0.8125rem; cursor: pointer; margin-right: 0.5rem;">Download</button>
                        @endif
                        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Export', message: 'Delete this export?', onConfirm: () => $wire.deleteExport({{ $export->id }}) })" style="color: #ef4444; background: none; border: none; font-size: 0.8125rem; cursor: pointer;">Delete</button>
                    </td>
                </tr>
                @if($export->status === 'failed' && $export->errors)
                <tr x-show="showErrors" x-cloak
                    style="background: rgba(239,68,68,0.03); border-bottom: 1px solid rgba(148,163,184,0.05);">
                    <td colspan="8" style="padding: 0 1rem 0.75rem 1rem;">
                        <div style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 0.5rem; padding: 0.75rem 1rem;">
                            <div style="display: flex; align-items: center; gap: 0.375rem; margin-bottom: 0.5rem;">
                                <span class="material-symbols-outlined" style="font-size: 1rem; color: #ef4444;">bug_report</span>
                                <span style="font-size: 0.75rem; font-weight: 600; color: #fca5a5;">Error Details</span>
                            </div>
                            @foreach($export->errors as $error)
                            <div style="font-size: 0.8125rem; color: #f87171; padding: 0.25rem 0; font-family: monospace; word-break: break-all;">{{ $error }}</div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
            @empty
            <tr>
                <td colspan="8" style="padding: 3rem; text-align: center; color: #64748b;">No exports yet. Run your first export!</td>
            </tr>
            @endforelse
        </table>
    </div>

    <style>
        @keyframes shimmer { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
        @keyframes pulse-glow { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.8; } }
        @keyframes pulse-btn { 0%, 100% { box-shadow: 0 0 0 0 rgba(99,102,241,0.4); } 50% { box-shadow: 0 0 0 8px rgba(99,102,241,0); } }
        @keyframes pulse-btn-green { 0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0.4); } 50% { box-shadow: 0 0 0 8px rgba(34,197,94,0); } }
    </style>
</div>
