<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal — The Cheddarain')</title>

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

    <div class="admin-shell">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="brand-logo" style="margin-bottom: 2rem;">
                <div class="brand-icon">🧀</div>
                <div class="brand-text">Cheddar<span>Admin</span></div>
            </a>

            <div style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-dim); margin-bottom: 0.6rem;">
                Management Portal
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

                <li class="admin-nav-item" style="margin-top: 2rem; border-top: 1px solid var(--border-subtle); padding-top: 1.5rem;">
                    <a href="{{ route('home') }}" target="_blank">
                        <span>🌐</span>
                        <span>View Customer Site &rarr;</span>
                    </a>
                </li>
            </ul>

            <!-- Staff User Badge & Logout -->
            <div style="padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); margin-top: auto;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.8rem;">
                    <div>
                        <div style="font-weight: 700; color: var(--text-cream); font-size: 0.9rem;">{{ auth()->user()->name ?? 'Staff User' }}</div>
                        <div style="font-size: 0.75rem; color: var(--cheddar-bright); text-transform: uppercase;">{{ auth()->user()->role ?? 'Admin' }}</div>
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

    @stack('scripts')
</body>
</html>
