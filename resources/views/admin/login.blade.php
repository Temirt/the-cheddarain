<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin & Staff Login — The Cheddarain</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: radial-gradient(circle at 50% 30%, rgba(255, 153, 0, 0.12) 0%, #0a0b0e 70%);">

    <div style="width: 100%; max-width: 460px; padding: 1.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ route('home') }}" class="brand-logo" style="justify-content: center; margin-bottom: 1.2rem;">
                <div class="brand-icon">🧀</div>
                <div class="brand-text">The <span>Cheddarain</span></div>
            </a>
            <div style="margin-bottom: 0.6rem;">
                <span class="badge badge-cheddar">Restaurant Management</span>
            </div>
            <h2 style="font-size: 1.85rem; margin-bottom: 0.5rem; color: var(--gold-bright);">Admin & Staff Login</h2>
            <p style="font-size: 0.95rem; color: var(--text-cream-muted);">Sign in to access order management, live kitchen pipeline, and store settings.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                <span>✕</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.2rem; box-shadow: var(--shadow-lg);">
            <!-- Portal Selector Tabs -->
            <div style="display: flex; background: var(--bg-surface); border-radius: var(--radius-lg); padding: 0.35rem; margin-bottom: 1.8rem; border: 1px solid var(--border-subtle); gap: 0.35rem;">
                <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="flex: 1; text-align: center; border-radius: var(--radius-md); font-size: 0.88rem; padding: 0.6rem 0.5rem; text-decoration: none;">
                    👤 Customer Sign In
                </a>
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm" style="flex: 1; text-align: center; border-radius: var(--radius-md); font-size: 0.88rem; padding: 0.6rem 0.5rem; text-decoration: none;">
                    🛡️ Admin & Staff Login
                </a>
            </div>
            <form action="{{ route('admin.login.submit') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Staff Email</label>
                    <input type="email" name="email" id="email" class="form-input" required value="{{ old('email', 'admin@thecheddarain.com') }}" placeholder="admin@thecheddarain.com">
                </div>

                <div class="form-group" style="margin-bottom: 1.8rem;">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••" value="admin123">
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.8rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: var(--text-cream-soft); cursor: pointer;">
                        <input type="checkbox" name="remember" checked style="accent-color: var(--cheddar-bright);">
                        <span>Keep me logged in</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <span>Sign In to Dashboard &rarr;</span>
                </button>
            </form>

            <!-- Pre-seeded Credentials Note -->
            <div style="margin-top: 1.8rem; padding: 1.1rem; background: var(--bg-surface); border-radius: var(--radius-md); border: 1px dashed var(--cheddar-primary); font-size: 0.85rem; color: var(--text-cream-muted);">
                <div style="font-weight: 700; color: var(--gold-bright); margin-bottom: 0.6rem;">Demo Staff Test Accounts:</div>
                <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
                    <button type="button" onclick="fillStaff('admin@thecheddarain.com', 'admin123')" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                        Auto-Fill Admin (admin123)
                    </button>
                    <button type="button" onclick="fillStaff('kitchen@thecheddarain.com', 'Kitchen2026!')" class="btn btn-secondary btn-sm" style="font-size: 0.78rem; padding: 0.35rem 0.75rem;">
                        Auto-Fill Kitchen Staff
                    </button>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('home') }}" style="font-size: 0.85rem; color: var(--text-dim);">&larr; Return to Customer Website</a>
        </div>
    </div>

<script>
function fillStaff(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
</body>
</html>
