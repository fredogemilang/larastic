<div>
    @section('title', 'Posts')

    <!-- Header Actions -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div style="display: flex; gap: 0.75rem; flex: 1;">
            <!-- Search -->
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search posts..."
                style="padding: 0.625rem 1rem; background: rgba(15,23,42,0.6); border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem; width: 280px; outline: none;">

            <!-- Status Filter -->
            <select wire:model.live="status"
                style="padding: 0.625rem 1rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                <option value="">All Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="scheduled">Scheduled</option>
            </select>

            <!-- Category Filter -->
            <select wire:model.live="category"
                style="padding: 0.625rem 1rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <!-- Language Filter -->
            <select wire:model.live="locale"
                style="padding: 0.625rem 1rem; background: #0f172a; border: 1px solid rgba(148,163,184,0.2); border-radius: 0.5rem; color: #f1f5f9; font-size: 0.875rem;">
                <option value="">All Languages</option>
                @foreach($locales as $code => $info)
                    <option value="{{ $code }}">{{ $info['flag'] }} {{ $info['name'] }}</option>
                @endforeach
            </select>
        </div>

        <a href="{{ route('admin.posts.create') }}"
            style="padding: 0.625rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 600; text-decoration: none; white-space: nowrap;">
            + New Post
        </a>
    </div>

    <!-- Bulk Actions -->
    @if(count($selected) > 0)
    <div style="background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.3); border-radius: 0.5rem; padding: 0.75rem 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem;">
        <span style="font-size: 0.875rem; color: #a5b4fc;">{{ count($selected) }} selected</span>
        <button type="button" wire:click="bulkUpdateStatus('published')" style="padding: 0.375rem 0.75rem; background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3); color: #86efac; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Publish</button>
        <button type="button" wire:click="bulkUpdateStatus('draft')" style="padding: 0.375rem 0.75rem; background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fcd34d; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Set Draft</button>
        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Bulk Delete Posts', message: 'Are you sure you want to delete the selected posts? This action cannot be undone.', onConfirm: () => $wire.deleteSelected() })" style="padding: 0.375rem 0.75rem; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; border-radius: 0.375rem; font-size: 0.8125rem; cursor: pointer;">Delete</button>
    </div>
    @endif

    <!-- Posts Table -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <th style="padding: 0.75rem 1rem; text-align: left; width: 40px;">
                        <input type="checkbox" wire:model.live="selectAll" style="accent-color: #6366f1;">
                    </th>
                    <th wire:click="sortBy('title')" style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; cursor: pointer;">
                        Title @if($sortField === 'title') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Author</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Categories</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; width: 50px;">Lang</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                    <th wire:click="sortBy('published_at')" style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; cursor: pointer;">
                        Date @if($sortField === 'published_at') {{ $sortDirection === 'asc' ? '↑' : '↓' }} @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr wire:key="post-{{ $post->id }}" style="border-bottom: 1px solid rgba(148,163,184,0.05);" onmouseover="this.style.background='rgba(99,102,241,0.05)'; this.querySelector('.row-actions').style.opacity=1;" onmouseout="this.style.background='transparent'; this.querySelector('.row-actions').style.opacity=0;">
                    <td style="padding: 0.75rem 1rem;">
                        <input type="checkbox" wire:model.live="selected" value="{{ $post->id }}" style="accent-color: #6366f1;">
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        <a href="{{ route('admin.posts.edit', $post->id) }}" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.875rem;">{{ $post->title }}</a>
                        <div class="row-actions" style="font-size: 0.75rem; margin-top: 0.25rem; opacity: 0; transition: opacity 0.2s; display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.posts.edit', $post->id) }}" style="color: #6366f1; text-decoration: none;">Edit</a>
                            <span style="color: #475569;">|</span>
                            <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Post', message: 'Are you sure you want to delete this post?', onConfirm: () => $wire.deletePost({{ $post->id }}) })" style="color: #ef4444; background: none; border: none; padding: 0; cursor: pointer; font-size: inherit;">Delete</button>
                            <span style="color: #475569;">|</span>
                            <a href="{{ url('/' . config('static-cms.blog.url_prefix', 'blog') . '/' . $post->slug) }}" target="_blank" style="color: #94a3b8; text-decoration: none;">View</a>
                        </div>
                    </td>
                    <td style="padding: 0.75rem 1rem; font-size: 0.8125rem; color: #94a3b8;">{{ $post->author?->name ?? '-' }}</td>
                    <td style="padding: 0.75rem 1rem;">
                        @foreach($post->categories as $cat)
                            <span style="padding: 0.125rem 0.5rem; background: rgba(99,102,241,0.1); color: #a5b4fc; border-radius: 9999px; font-size: 0.6875rem; margin-right: 0.25rem;">{{ $cat->name }}</span>
                        @endforeach
                    </td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <span style="padding: 0.125rem 0.375rem; border-radius: 0.25rem; font-size: 0.6875rem; font-weight: 700; text-transform: uppercase;
                            {{ ($post->locale ?? 'id') === 'en' ? 'background: rgba(56,189,248,0.15); color: #7dd3fc;' : 'background: rgba(249,115,22,0.15); color: #fdba74;' }}">
                            {{ $post->locale ?? 'id' }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        <span style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600;
                            {{ $post->status === 'published' ? 'background: rgba(34,197,94,0.15); color: #86efac;' : ($post->status === 'scheduled' ? 'background: rgba(14,165,233,0.15); color: #7dd3fc;' : 'background: rgba(251,191,36,0.15); color: #fcd34d;') }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td style="padding: 0.75rem 1rem; font-size: 0.8125rem; color: #94a3b8;">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Created: ' . $post->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 3rem; text-align: center; color: #64748b;">
                        <div class="material-symbols-outlined" style="font-size: 2.5rem; margin-bottom: 0.5rem; color: #64748b;">edit_document</div>
                        No posts found. <a href="{{ route('admin.posts.create') }}" style="color: #6366f1;">Create your first post</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 1rem;">
        {{ $posts->links() }}
    </div>
</div>
