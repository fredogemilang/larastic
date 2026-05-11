<div>
    @section('title', 'Dashboard')

    <!-- Stats Grid -->
    <div class="grid-stats">
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(99, 102, 241, 0.15); color: #a5b4fc;">edit_document</div>
            <div>
                <div class="stat-value">{{ $totalPosts }}</div>
                <div class="stat-label">Total Posts</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(34, 197, 94, 0.15); color: #86efac;">check_circle</div>
            <div>
                <div class="stat-value">{{ $publishedPosts }}</div>
                <div class="stat-label">Published</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(251, 191, 36, 0.15); color: #fcd34d;">pending_actions</div>
            <div>
                <div class="stat-value">{{ $draftPosts }}</div>
                <div class="stat-label">Drafts</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(168, 85, 247, 0.15); color: #c4b5fd;">draft</div>
            <div>
                <div class="stat-value">{{ $totalPages }}</div>
                <div class="stat-label">Pages</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(236, 72, 153, 0.15); color: #f9a8d4;">image</div>
            <div>
                <div class="stat-value">{{ $totalMedia }}</div>
                <div class="stat-label">Media Files</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon material-symbols-outlined" style="background: rgba(14, 165, 233, 0.15); color: #7dd3fc;">schedule</div>
            <div>
                <div class="stat-value">{{ $scheduledPosts }}</div>
                <div class="stat-label">Scheduled</div>
            </div>
        </div>
    </div>

    <!-- Two-column layout -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Recent Posts -->
        <div class="card">
            <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">Recent Posts</h3>
            @forelse($recentPosts as $post)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <div>
                        <div style="font-size: 0.875rem; color: #e2e8f0; font-weight: 500;">{{ $post->title }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">by {{ $post->author?->name ?? 'Unknown' }} · {{ $post->created_at->diffForHumans() }}</div>
                    </div>
                    <span style="
                        padding: 0.25rem 0.625rem;
                        border-radius: 9999px;
                        font-size: 0.6875rem;
                        font-weight: 600;
                        {{ $post->status === 'published'
                            ? 'background: rgba(34,197,94,0.15); color: #86efac;'
                            : ($post->status === 'scheduled'
                                ? 'background: rgba(14,165,233,0.15); color: #7dd3fc;'
                                : 'background: rgba(251,191,36,0.15); color: #fcd34d;') }}
                    ">{{ ucfirst($post->status) }}</span>
                </div>
            @empty
                <p style="color: #64748b; font-size: 0.875rem; text-align: center; padding: 2rem 0;">No posts yet. Start creating!</p>
            @endforelse
        </div>

        <!-- Recent Exports -->
        <div class="card">
            <h3 style="font-size: 1rem; font-weight: 600; color: #f1f5f9; margin: 0 0 1rem;">Recent Exports</h3>
            @forelse($recentExports as $export)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <div>
                        <div style="font-size: 0.875rem; color: #e2e8f0; font-weight: 500;">{{ ucfirst($export->type) }} Export</div>
                        <div style="font-size: 0.75rem; color: #64748b;">by {{ $export->triggeredBy?->name ?? 'System' }} · {{ $export->created_at->diffForHumans() }}</div>
                    </div>
                    <span style="
                        padding: 0.25rem 0.625rem;
                        border-radius: 9999px;
                        font-size: 0.6875rem;
                        font-weight: 600;
                        {{ $export->status === 'completed'
                            ? 'background: rgba(34,197,94,0.15); color: #86efac;'
                            : ($export->status === 'failed'
                                ? 'background: rgba(239,68,68,0.15); color: #fca5a5;'
                                : 'background: rgba(251,191,36,0.15); color: #fcd34d;') }}
                    ">{{ ucfirst($export->status) }}</span>
                </div>
            @empty
                <p style="color: #64748b; font-size: 0.875rem; text-align: center; padding: 2rem 0;">No exports yet.</p>
            @endforelse
        </div>
    </div>
</div>
