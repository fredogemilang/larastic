<div>
    @section('title', 'Users')
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
        <h2 style="font-size:1rem;color:#94a3b8;margin:0;">Manage CMS users and roles</h2>
        <button wire:click="openForm()" style="padding:0.625rem 1.25rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;">+ New User</button>
    </div>

    @if($showForm)
    <div class="card" style="margin-bottom:1.5rem;">
        <h3 style="font-size:0.9375rem;font-weight:600;color:#f1f5f9;margin:0 0 1rem;">{{ $editingId ? 'Edit' : 'New' }} User</h3>
        <form wire:submit="save">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="font-size:0.8125rem;color:#94a3b8;display:block;margin-bottom:0.375rem;">Name</label>
                    <input type="text" wire:model="name" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                    @error('name')<span style="font-size:0.75rem;color:#fca5a5;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="font-size:0.8125rem;color:#94a3b8;display:block;margin-bottom:0.375rem;">Email</label>
                    <input type="email" wire:model="email" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                    @error('email')<span style="font-size:0.75rem;color:#fca5a5;">{{ $message }}</span>@enderror
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
                <div>
                    <label style="font-size:0.8125rem;color:#94a3b8;display:block;margin-bottom:0.375rem;">Password {{ $editingId ? '(leave blank to keep)' : '' }}</label>
                    <input type="password" wire:model="password" style="width:100%;padding:0.625rem;background:rgba(15,23,42,0.6);border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;outline:none;box-sizing:border-box;">
                    @error('password')<span style="font-size:0.75rem;color:#fca5a5;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label style="font-size:0.8125rem;color:#94a3b8;display:block;margin-bottom:0.375rem;">Role</label>
                    <select wire:model="role" style="width:100%;padding:0.625rem;background:#0f172a;border:1px solid rgba(148,163,184,0.2);border-radius:0.5rem;color:#f1f5f9;font-size:0.875rem;">
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="author">Author</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" wire:loading.attr="disabled" style="padding:0.625rem 1.25rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border:none;border-radius:0.5rem;font-size:0.875rem;font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:0.5rem;">
                    <span wire:loading wire:target="save">
                        <svg class="animate-spin" style="width: 1rem; height: 1rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="save">Saving...</span>
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update' : 'Create' }}</span>
                </button>
                <button type="button" wire:click="$set('showForm',false)" style="padding:0.625rem 1rem;background:rgba(148,163,184,0.1);border:1px solid rgba(148,163,184,0.2);color:#94a3b8;border-radius:0.5rem;font-size:0.875rem;cursor:pointer;">Cancel</button>
            </div>
        </form>
    </div>
    @endif

    <div class="card" style="padding:0;overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid rgba(148,163,184,0.1);">
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">User</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Email</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Role</th>
                    <th style="padding:0.75rem 1rem;text-align:left;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Joined</th>
                    <th style="padding:0.75rem 1rem;text-align:right;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr style="border-bottom:1px solid rgba(148,163,184,0.05);">
                    <td style="padding:0.75rem 1rem;display:flex;align-items:center;gap:0.75rem;">
                        <div style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:600;color:white;">{{ strtoupper(substr($u->name,0,2)) }}</div>
                        <span style="color:#e2e8f0;font-size:0.875rem;font-weight:500;">{{ $u->name }}</span>
                    </td>
                    <td style="padding:0.75rem 1rem;color:#94a3b8;font-size:0.8125rem;">{{ $u->email }}</td>
                    <td style="padding:0.75rem 1rem;"><span style="padding:0.25rem 0.625rem;background:rgba(99,102,241,0.15);color:#a5b4fc;border-radius:9999px;font-size:0.6875rem;font-weight:600;">{{ ucfirst(str_replace('_',' ',$u->roles->first()?->name ?? 'none')) }}</span></td>
                    <td style="padding:0.75rem 1rem;color:#64748b;font-size:0.8125rem;">{{ $u->created_at->format('M d, Y') }}</td>
                    <td style="padding:0.75rem 1rem;text-align:right;">
                        <button wire:click="openForm({{ $u->id }})" style="color:#6366f1;background:none;border:none;font-size:0.8125rem;cursor:pointer;margin-right:0.5rem;">Edit</button>
                        @if($u->id !== auth()->id())
                        <button type="button" x-data x-on:click="$dispatch('open-modal', { title: 'Delete User', message: 'Delete user {{ addslashes($u->name) }}?', onConfirm: () => $wire.deleteUser({{ $u->id }}) })" style="color:#ef4444;background:none;border:none;font-size:0.8125rem;cursor:pointer;">Delete</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
