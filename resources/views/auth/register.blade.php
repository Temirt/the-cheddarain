<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — The Cheddarain</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: radial-gradient(circle at 50% 30%, rgba(255, 153, 0, 0.12) 0%, #0a0b0e 70%);">

    <div style="width: 100%; max-width: 520px; padding: 1.5rem;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ route('home') }}" class="brand-logo" style="justify-content: center; margin-bottom: 1rem;">
                <div class="brand-icon">🧀</div>
                <div class="brand-text">The <span>Cheddarain</span></div>
            </a>
            <h2 style="font-size: 1.6rem; margin-bottom: 0.4rem;">Create your account</h2>
            <p style="font-size: 0.9rem; color: var(--text-cream-muted);">Join the Cheddarain family for faster ordering and tracking.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                <span>✕</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.2rem; box-shadow: var(--shadow-lg);">
            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Full name</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Phone number</label>
                    <input type="tel" name="phone" id="phone" class="form-input" value="{{ old('phone') }}" required placeholder="+1 (555) 123-4567">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-input" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.8rem;">
                    <label class="form-label" for="password_confirmation">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    <span>Create Account &rarr;</span>
                </button>
            </form>
        </div>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: var(--text-cream-soft);">
            Already have an account?
            <a href="{{ route('login') }}" style="color: var(--cheddar-bright); font-weight: 600;">Sign in</a>
        </div>

        <div style="text-align: center; margin-top: 1rem;">
            <a href="{{ route('home') }}" style="font-size: 0.85rem; color: var(--text-dim);">&larr; Return to site</a>
        </div>
    </div>

</body>
</html>
