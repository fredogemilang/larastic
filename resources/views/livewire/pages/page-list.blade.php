<div>
    @section('title', 'Pages')

    <div style="margin-bottom: 1.5rem;">
        <p style="color: #94a3b8; font-size: 0.875rem; margin: 0;">Manage your static page content and SEO settings.</p>
    </div>

    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(148,163,184,0.1);">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Page</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Template</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">URL</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                <tr wire:key="page-{{ $page->id }}" style="border-bottom: 1px solid rgba(148,163,184,0.05);" onmouseover="this.style.background='rgba(99,102,241,0.05)'; this.querySelector('.row-actions').style.opacity=1;" onmouseout="this.style.background='transparent'; this.querySelector('.row-actions').style.opacity=0;">
                    <td style="padding: 0.75rem 1rem;">
                        <a href="{{ route('admin.pages.edit', $page->id) }}" style="color: #e2e8f0; text-decoration: none; font-weight: 500; font-size: 0.875rem;"><span class="material-symbols-outlined" style="font-size: 1.125rem; vertical-align: middle; color: #94a3b8; margin-right: 0.25rem;">description</span>{{ $page->title }}</a>
                        <div class="row-actions" style="font-size: 0.75rem; margin-top: 0.25rem; opacity: 0; transition: opacity 0.2s; display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" style="color: #6366f1; text-decoration: none;">Edit</a>
                            <span style="color: #475569;">|</span>
                            <a href="{{ $page->url }}" target="_blank" style="color: #94a3b8; text-decoration: none;">View</a>
                            <span style="color: #475569;">|</span>
                            <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete Page', message: 'Are you sure you want to delete this page?', onConfirm: () => $wire.deletePage({{ $page->id }}) })" style="color: #ef4444; background: none; border: none; padding: 0; cursor: pointer; font-size: inherit;">Delete</button>
                        </div>
                    </td>
                    <td style="padding: 0.75rem 1rem;">
                        <span style="padding: 0.25rem 0.5rem; background: rgba(99,102,241,0.1); color: #a5b4fc; border-radius: 0.25rem; font-size: 0.75rem;">{{ $page->template }}</span>
                    </td>
                    <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8125rem;">{{ $page->url }}</td>
                    <td style="padding: 0.75rem 1rem; text-align: center;">
                        <button wire:click="toggleStatus({{ $page->id }})" style="padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.6875rem; font-weight: 600; border: none; cursor: pointer;
                            {{ $page->status === 'published' ? 'background: rgba(34,197,94,0.15); color: #86efac;' : 'background: rgba(251,191,36,0.15); color: #fcd34d;' }}">
                            {{ ucfirst($page->status) }}
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: #64748b;">No pages configured yet. Run the page seeder to create default pages.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

