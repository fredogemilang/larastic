<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive">
    <title>@yield('title', 'Dashboard') — Static CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            background: #0f172a;
            color: #e2e8f0;
        }

        /* --- Sidebar --- */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #1e293b;
            border-right: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 40;
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
        }
        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
            flex-shrink: 0;
        }
        .sidebar-brand h2 {
            font-size: 1rem;
            font-weight: 700;
            color: #f1f5f9;
            margin: 0;
        }
        .sidebar-brand span {
            font-size: 0.6875rem;
            color: #64748b;
        }
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }
        .nav-section {
            padding: 0 1rem;
            margin-bottom: 1.5rem;
        }
        .nav-section-title {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 0 0.75rem;
            margin-bottom: 0.5rem;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.15s;
        }
        .nav-link:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #c7d2fe;
        }
        .nav-link.active {
            background: rgba(99, 102, 241, 0.15);
            color: #a5b4fc;
        }
        .nav-link .icon {
            width: 24px;
            text-align: center;
            font-size: 1.25rem;
        }

        /* --- Top Bar --- */
        .topbar {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 64px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 30;
        }
        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topbar-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #f1f5f9;
        }
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: white;
        }
        .user-name {
            font-size: 0.875rem;
            font-weight: 500;
            color: #e2e8f0;
        }
        .user-role {
            font-size: 0.6875rem;
            color: #64748b;
        }
        .btn-logout {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* --- Main Content --- */
        .main-content {
            margin-left: 260px;
            margin-top: 64px;
            padding: 1.5rem;
            min-height: calc(100vh - 64px);
        }

        /* --- Mobile Toggle --- */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #e2e8f0;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 35;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .topbar { left: 0; }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; }
        }

        /* --- Utility Classes --- */
        .card {
            background: #1e293b;
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }
        .stat-card {
            background: #1e293b;
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 0.75rem;
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 0.625rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f1f5f9;
        }
        .stat-label {
            font-size: 0.8125rem;
            color: #64748b;
            margin-top: 0.125rem;
        }
        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        /* --- Custom Form Controls --- */
        input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 1.125rem;
            height: 1.125rem;
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 0.25rem;
            cursor: pointer;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            vertical-align: middle;
            transition: all 0.2s ease;
            flex-shrink: 0;
            margin: 0;
        }
        input[type="checkbox"]:hover {
            border-color: #a5b4fc;
            background-color: rgba(15, 23, 42, 0.8);
        }
        input[type="checkbox"]:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }
        input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            width: 0.3125rem;
            height: 0.5625rem;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            margin-bottom: 0.125rem;
        }
        input[type="checkbox"]:focus-visible {
            outline: 2px solid rgba(99, 102, 241, 0.5);
            outline-offset: 2px;
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 1.5rem;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .toast {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            animation: slideIn 0.3s ease;
        }
        .toast-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
        .toast-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse-badge {
            0%, 100% { transform: translateY(-50%) scale(1); }
            50% { transform: translateY(-50%) scale(1.15); }
        }

        /* Modal Styles */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-dialog {
            background: #1e293b;
            border: 1px solid rgba(148, 163, 184, 0.1);
            border-radius: 0.75rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #f1f5f9;
            margin: 0;
        }
        .modal-close {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.5rem;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            transition: color 0.15s;
        }
        .modal-close:hover { color: #f1f5f9; }
        .modal-body {
            padding: 1.5rem;
            color: #cbd5e1;
            font-size: 0.9375rem;
            line-height: 1.5;
            margin: 0;
        }
        .modal-footer {
            padding: 1.25rem 1.5rem;
            background: rgba(15, 23, 42, 0.5);
            border-top: 1px solid rgba(148, 163, 184, 0.1);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }
        .btn-cancel {
            padding: 0.5rem 1rem;
            background: #334155;
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: #f1f5f9;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-cancel:hover { background: #475569; }
        .btn-confirm {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-confirm:hover { background: rgba(239, 68, 68, 0.25); border-color: rgba(239, 68, 68, 0.5); color: #f87171; }
    </style>
    @stack('styles')
</head>
<body class="h-full">
    @php
        $user = auth()->user();
        $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
        $roleName = $user->roles->first()?->name ?? 'user';
        $currentRoute = request()->route()?->getName() ?? '';
    @endphp

    <!-- Sidebar Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon material-symbols-outlined" style="color: white; font-size: 1.5rem;">bolt</div>
            <div>
                <h2>Static CMS</h2>
                <span>Content Manager</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.dashboard' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">dashboard</span> Dashboard
                </a>
            </div>

            @if($user->canAny(['edit_own_posts', 'edit_all_posts', 'manage_categories', 'manage_tags']))
            <div class="nav-section">
                <div class="nav-section-title">Content</div>
                <a href="{{ route('admin.posts.index', [], false) }}" wire:navigate class="nav-link {{ str_starts_with($currentRoute, 'admin.posts') ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">edit_document</span> Posts
                </a>
                @can('manage_categories')
                <a href="{{ route('admin.categories.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.categories.index' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">folder</span> Categories
                </a>
                @endcan
                @can('manage_tags')
                <a href="{{ route('admin.tags.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.tags.index' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">label</span> Tags
                </a>
                @endcan
                @can('manage_pages')
                <a href="{{ route('admin.pages.index', [], false) }}" wire:navigate class="nav-link {{ str_starts_with($currentRoute, 'admin.pages') ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">draft</span> Pages
                </a>
                @endcan
            </div>
            @endif

            @can('manage_media')
            <div class="nav-section">
                <div class="nav-section-title">Assets</div>
                <a href="{{ route('admin.media.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.media.index' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">image</span> Media Library
                </a>
            </div>
            @endcan

            @can('export_website')
            @php $pendingChangeCount = \App\Livewire\Export\ExportManager::getPendingChangeCount(); @endphp
            <div class="nav-section">
                <div class="nav-section-title">Deploy</div>
                <a href="{{ route('admin.export.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.export.index' ? 'active' : '' }}" style="position: relative;">
                    <span class="icon material-symbols-outlined">rocket_launch</span> Export
                    @if($pendingChangeCount > 0)
                    <span x-data="{ show: true }" @export-completed.window="show = false" x-show="show" style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); min-width: 20px; height: 20px; background: linear-gradient(135deg, #f59e0b, #ef4444); color: white; border-radius: 9999px; font-size: 0.625rem; font-weight: 700; display: flex; align-items: center; justify-content: center; padding: 0 0.25rem; animation: pulse-badge 2s ease-in-out infinite;">
                        {{ $pendingChangeCount > 99 ? '99+' : $pendingChangeCount }}
                    </span>
                    @endif
                </a>
            </div>
            @endcan

            @if($user->hasRole('super_admin'))
            <div class="nav-section">
                <div class="nav-section-title">System</div>
                <a href="{{ route('admin.import.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.import.index' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">download</span> Import WP
                </a>
                <a href="{{ route('admin.settings', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.settings' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">settings</span> Settings
                </a>
                <a href="{{ route('admin.users.index', [], false) }}" wire:navigate class="nav-link {{ $currentRoute === 'admin.users.index' ? 'active' : '' }}">
                    <span class="icon material-symbols-outlined">group</span> Users
                </a>
            </div>
            @endif
        </nav>
    </aside>

    <!-- Top Bar -->
    <header class="topbar">
        <div class="topbar-left">
            <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>
            <h1 class="topbar-title">@yield('title', 'Dashboard')</h1>
        </div>
        <div class="topbar-right">
            <div class="user-info">
                <div>
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-role">{{ ucfirst(str_replace('_', ' ', $roleName)) }}</div>
                </div>
                <div class="user-avatar">{{ $initials }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Toast Notifications -->
        <div class="toast-container" x-data="{ toasts: [] }" 
             @notify.window="toasts.push({ id: Date.now(), type: $event.detail.type, text: $event.detail.message }); setTimeout(() => { toasts.shift() }, 3000)">
            
            <!-- Dynamic Toasts -->
            <template x-for="toast in toasts" :key="toast.id">
                <div class="toast" :class="toast.type === 'error' ? 'toast-error' : 'toast-success'" x-text="toast.text"></div>
            </template>

            <!-- Server-rendered static Toasts -->
            @if(session('success'))
            <div class="toast toast-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="toast toast-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">{{ session('error') }}</div>
            @endif
        </div>

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- Global Confirmation Modal -->
    <div x-data="{ show: false, title: '', message: '' }"
         x-init="window.currentConfirmCallback = null"
         @open-modal.window="console.log('Global Modal Event Received:', $event.detail); show = true; title = $event.detail.title; message = $event.detail.message; window.currentConfirmCallback = $event.detail.onConfirm;"
         x-show="show" 
         style="display: none;" 
         class="modal-backdrop"
         x-transition.opacity>
        <div class="modal-dialog" x-show="show" @click.away="show = false" x-transition>
            <div class="modal-header">
                <h3 class="modal-title" x-text="title"></h3>
                <button type="button" class="modal-close" @click="show = false">&times;</button>
            </div>
            <div class="modal-body">
                <p x-text="message" style="margin: 0;"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" @click="show = false">Cancel</button>
                <button type="button" class="btn-confirm" @click="if(window.currentConfirmCallback) window.currentConfirmCallback(); show = false">Delete</button>
            </div>
        </div>
    </div>

    @livewireScripts
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }
    </script>
    @stack('scripts')
</body>
</html>
