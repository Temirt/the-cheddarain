<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal — The Cheddarloin')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @stack('styles')
</head>
<body style="background: var(--bg-base);">

    <!-- Mobile Admin Top Bar (Visible on mobile/tablet) -->
    <header class="admin-mobile-header">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo" style="gap: 0.5rem;">
            <div class="brand-icon" style="width: 32px; height: 32px; font-size: 1rem;">🧀</div>
            <div class="brand-text" style="font-size: 1.1rem;">Cheddar<span>Admin</span></div>
        </a>
        <div style="display: flex; align-items: center; gap: 0.6rem;">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm" title="View Storefront" style="padding: 0.4rem 0.6rem; font-size: 0.8rem;">
                🌐 Store
            </a>
            <button type="button" class="admin-menu-toggle" id="adminMenuToggle" aria-label="Toggle admin navigation">
                <span>☰</span>
            </button>
        </div>
    </header>

    <!-- Mobile Drawer Backdrop -->
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>

    <div class="admin-shell">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
                <a href="{{ route('admin.dashboard') }}" class="brand-logo">
                    <div class="brand-icon">🧀</div>
                    <div class="brand-text">Cheddar<span>Admin</span></div>
                </a>
                <button type="button" class="admin-sidebar-close" id="adminSidebarClose" aria-label="Close menu">&times;</button>
            </div>

            <div style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-dim); margin-bottom: 0.6rem;">
                Store Management
            </div>

            <ul class="admin-sidebar-nav">
                <li class="admin-nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <span>📊</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="admin-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">
                        <span>📦</span>
                        <span>Order Manager</span>
                    </a>
                </li>

                <li class="admin-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">
                        <span>🍔</span>
                        <span>Menu & Products</span>
                    </a>
                </li>

                <li class="admin-nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings.index') }}">
                        <span>⚙️</span>
                        <span>Store Settings</span>
                    </a>
                </li>

                <li class="admin-nav-item" style="margin-top: 1.5rem; border-top: 1px solid var(--border-subtle); padding-top: 1.2rem;">
                    <a href="{{ route('home') }}" target="_blank">
                        <span>🌐</span>
                        <span>View Customer Site &rarr;</span>
                    </a>
                </li>
            </ul>

            <!-- Administrator Profile & Logout -->
            <div style="padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); margin-top: auto;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.8rem;">
                    <div>
                        <div style="font-weight: 700; color: var(--text-cream); font-size: 0.9rem;">{{ auth()->user()->name ?? 'Administrator' }}</div>
                        <div style="font-size: 0.75rem; color: var(--cheddar-bright); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Administrator</div>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary btn-sm btn-block" style="font-size: 0.82rem;">
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <span>✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('admin_content')
        </main>
    </div>

    <!-- Mobile Drawer JS -->
    <script>
        const menuToggle = document.getElementById('adminMenuToggle');
        const sidebarClose = document.getElementById('adminSidebarClose');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminSidebarBackdrop');

        function openSidebar() {
            if (sidebar) sidebar.classList.add('open');
            if (backdrop) backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('open');
            if (backdrop) backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (menuToggle) menuToggle.addEventListener('click', openSidebar);
        if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
    </script>
    @stack('scripts')
</body>
</html>
