<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'The Cheddarloin — Premium Burgers, Loaded Fries & Signature Sauces')</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @stack('styles')
</head>
<body>

    <!-- Main Navigation Header -->
    <header class="site-header">
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="brand-logo">
                <div class="brand-icon">🧀</div>
                <div class="brand-text">The <span>Cheddarloin</span></div>
            </a>

            <nav>
                <ul class="nav-links" id="nav-links">
                    <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu') || request()->routeIs('product.detail') ? 'active' : '' }}">Menu</a></li>
                    <li><a href="{{ route('order.track') }}" class="nav-link {{ request()->routeIs('order.track*') ? 'active' : '' }}">Track My Order</a></li>
                    <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}">FAQ</a></li>
                    @if(auth()->check() && auth()->user()->isStaff())
                        <li><a href="{{ route('admin.dashboard') }}" class="badge badge-cheddar" style="text-decoration: none;">Admin Portal</a></li>
                    @endif
                    @auth
                        <li class="mobile-only-nav"><a href="{{ route('account') }}" class="nav-link {{ request()->routeIs('account') ? 'active' : '' }}">My Account</a></li>
                        <li class="mobile-only-nav">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link" style="background: none; border: none; padding: 0.4rem 0; font-family: inherit; font-size: inherit; font-weight: inherit; color: var(--danger); cursor: pointer; text-align: left; width: 100%;">
                                    Sign Out
                                </button>
                            </form>
                        </li>
                    @else
                        <li class="mobile-only-nav"><a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Customer Sign In</a></li>
                        <li class="mobile-only-nav"><a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                        <li class="mobile-only-nav"><a href="{{ route('admin.login') }}" class="nav-link" style="color: var(--gold-bright);">🛡️ Admin Login</a></li>
                    @endauth
                </ul>
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('account') }}" class="badge badge-gold nav-auth-desktop" style="text-decoration: none; padding: 0.5rem 0.8rem; font-size: 0.85rem;">
                        👤 {{ auth()->user()->name }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="nav-auth-desktop" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.45rem 0.8rem; font-size: 0.8rem;">Sign Out</button>
                    </form>
                @else
                    <div class="nav-auth-desktop" style="display: flex; gap: 0.6rem; align-items: center;">
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="text-decoration: none; padding: 0.5rem 0.9rem;">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="text-decoration: none; padding: 0.5rem 0.9rem;">Register</a>
                        <a href="{{ route('admin.login') }}" class="btn btn-outline-gold btn-sm" style="text-decoration: none; padding: 0.5rem 0.85rem; font-size: 0.82rem;">🛡️ Admin</a>
                    </div>
                @endauth
                <a href="{{ route('cart') }}" class="cart-btn-trigger" aria-label="View Shopping Cart">
                    <span>🛒</span>
                    <span style="font-size: 0.9rem;">Cart</span>
                    <span class="cart-count-badge" style="display: none;">0</span>
                </a>
                <button type="button" class="nav-toggle-btn" id="nav-toggle" aria-label="Toggle Navigation">☰</button>
            </div>
        </div>
    </header>

    <!-- Main Body Content -->
    <main class="page-wrapper">
        @if(session('success'))
            <div class="container" style="padding-top: 1.5rem;">
                <div class="alert alert-success">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="padding-top: 1.5rem;">
                <div class="alert alert-danger">
                    <span>✕</span>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Interactive Customization Modal -->
    <div class="modal-backdrop" id="customization-modal">
        <div class="modal-card">
            <div class="modal-header">
                <div>
                    <h3 id="modal-product-title" style="font-size: 1.35rem; color: var(--text-cream);">Customize Item</h3>
                    <div id="modal-base-price" style="color: var(--gold-bright); font-weight: 700; margin-top: 0.2rem;">$0.00</div>
                </div>
                <button type="button" class="modal-close-btn" id="modal-close-btn">✕</button>
            </div>
            <div class="modal-body">
                <p id="modal-product-desc" style="color: var(--text-cream-muted); font-size: 0.92rem; margin-bottom: 1.5rem;"></p>
                <div id="modal-options-container">
                    <!-- Loaded dynamically via JavaScript -->
                </div>
                <div style="margin-top: 2rem;">
                    <button type="button" class="btn btn-primary btn-block btn-lg" id="modal-add-to-cart-btn">
                        Add to Cart • $0.00
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Support Button -->
    @php
        $rawWa = \App\Models\RestaurantSetting::get('whatsapp_number', env('WHATSAPP_NUMBER', '15552433327'));
        $cleanWa = preg_replace('/[^0-9]/', '', $rawWa);
    @endphp
    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Hello The Cheddarloin! I would like to make an enquiry.') }}"
       target="_blank"
       rel="noopener noreferrer"
       class="floating-whatsapp-btn"
       style="position: fixed; bottom: 25px; left: 25px; z-index: 999; display: flex; align-items: center; gap: 0.6rem; background: #25D366; color: #fff; padding: 0.8rem 1.4rem; border-radius: 9999px; font-weight: 700; font-size: 0.9rem; box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); text-decoration: none; transition: transform 0.2s ease;">
        <span style="font-size: 1.3rem;">💬</span>
        <span>WhatsApp Us</span>
    </a>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <div class="brand-logo" style="margin-bottom: 1rem;">
                        <div class="brand-icon">🧀</div>
                        <div class="brand-text">The <span>Cheddarloin</span></div>
                    </div>
                    <p style="font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem; color: var(--text-cream-muted);">
                        Handcrafted smashed Angus burgers, crispy double-fried russet fries, and our world-renowned melted cheddar cascades.
                    </p>
                    <div style="display: flex; gap: 0.8rem;">
                        <span class="badge badge-cheddar">Fresh Daily</span>
                        <span class="badge badge-gold">100% Angus</span>
                    </div>
                </div>

                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('menu') }}">Artisan Menu</a></li>
                        <li><a href="{{ route('order.track') }}">Live Order Tracker</a></li>
                        <li><a href="{{ route('about') }}">Our Cheddar Story</a></li>
                        <li><a href="{{ route('faq') }}">Frequently Asked Questions</a></li>
                        <li><a href="{{ route('contact') }}">Store Location & Hours</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Menu Highlights</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('menu', ['category' => 'burgers']) }}">The Cheddar Lava</a></li>
                        <li><a href="{{ route('menu', ['category' => 'burgers']) }}">Smokey Truffle Melt</a></li>
                        <li><a href="{{ route('menu', ['category' => 'fries']) }}">Loaded Cheddar Bacon Fries</a></li>
                        <li><a href="{{ route('menu', ['category' => 'signature-sauces']) }}">Liquid Gold Dipping Pot</a></li>
                        <li><a href="{{ route('menu', ['category' => 'combos-and-meals']) }}">King Cheddar Feast</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h4>Restaurant Details</h4>
                    <p style="font-size: 0.9rem; margin-bottom: 0.6rem; color: var(--text-cream-soft);">
                        📍 {{ \App\Models\RestaurantSetting::get('restaurant_address', '108 Artisan Burger Way, Melt District') }}
                    </p>
                    <p style="font-size: 0.9rem; margin-bottom: 0.6rem; color: var(--text-cream-soft);">
                        📞 {{ \App\Models\RestaurantSetting::get('restaurant_phone', '+1 (555) 243-3327') }}
                    </p>
                    <p style="font-size: 0.9rem; margin-bottom: 0.6rem; color: var(--text-cream-soft);">
                        🕒 {{ \App\Models\RestaurantSetting::get('opening_hours', 'Mon - Sun: 11:00 AM - 11:00 PM') }}
                    </p>
                    <div style="margin-top: 1rem;">
                        <a href="{{ route('admin.login') }}" style="font-size: 0.8rem; color: var(--text-dim);">Admin Login &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <div>&copy; {{ date('Y') }} The Cheddarloin. All rights reserved. Crafted with melted gold.</div>
                <div>Strictly pure Vanilla HTML5, CSS3 & JavaScript architecture.</div>
            </div>
        </div>
    </footer>

    <!-- Core Vanilla JavaScript Modules -->
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>
