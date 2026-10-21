@extends('layouts.app')

@section('title', 'Sign In — The Cheddarain')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <div style="margin-bottom: 0.8rem;">
            <span class="badge badge-cheddar">Welcome Back</span>
        </div>
        <h2>Customer Sign In</h2>
        <p>Sign in to your account to view your past orders and enjoy faster checkout.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="max-width: 480px; margin: 0 auto 1.5rem auto;">
            <span>✕</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem; max-width: 480px; margin: 0 auto; box-shadow: var(--shadow-lg);">
        <!-- Portal Selector Tabs -->
        <div style="display: flex; background: var(--bg-surface); border-radius: var(--radius-lg); padding: 0.35rem; margin-bottom: 1.8rem; border: 1px solid var(--border-subtle); gap: 0.35rem;">
            <a href="{{ route('login') }}" class="btn btn-primary btn-sm" style="flex: 1; text-align: center; border-radius: var(--radius-md); font-size: 0.88rem; padding: 0.6rem 0.5rem; text-decoration: none;">
                👤 Customer Sign In
            </a>
            <a href="{{ route('admin.login') }}" class="btn btn-secondary btn-sm" style="flex: 1; text-align: center; border-radius: var(--radius-md); font-size: 0.88rem; padding: 0.6rem 0.5rem; text-decoration: none;">
                🛡️ Admin & Staff Login
            </a>
        </div>

        <!-- Demo Customer Credentials Helper -->
        <div style="background: rgba(255, 153, 0, 0.08); border: 1px dashed var(--cheddar-primary); border-radius: var(--radius-md); padding: 1.1rem; margin-bottom: 1.8rem; font-size: 0.88rem;">
            <div style="font-weight: 700; color: var(--gold-bright); margin-bottom: 0.4rem; display: flex; align-items: center; justify-content: space-between;">
                <span>💡 Demo Customer Account</span>
                <button type="button" onclick="fillDemoCustomer()" class="btn btn-secondary btn-sm" style="padding: 0.25rem 0.65rem; font-size: 0.75rem;">
                    Auto-Fill
                </button>
            </div>
            <div style="color: var(--text-cream-soft); font-size: 0.84rem; line-height: 1.5;">
                Email: <strong style="color: var(--text-cream);">customer@thecheddarain.com</strong><br>
                Password: <strong style="color: var(--text-cream);">customer123</strong>
            </div>
        </div>

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address *</label>
                <input type="email" name="email" id="email" class="form-input" required autofocus value="{{ old('email') }}" placeholder="e.g. name@example.com">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="password">Password *</label>
                <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.8rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: var(--text-cream-soft); cursor: pointer;">
                    <input type="checkbox" name="remember" checked style="accent-color: var(--cheddar-bright);">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <span>Sign In to Account &rarr;</span>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); font-size: 0.92rem; color: var(--text-cream-muted);">
            Don't have an account yet? 
            <a href="{{ route('register') }}" style="color: var(--cheddar-bright); font-weight: 700;">Create one here</a>
        </div>

        <div style="text-align: center; margin-top: 1.2rem; font-size: 0.82rem;">
            Restaurant staff or manager? 
            <a href="{{ route('admin.login') }}" style="color: var(--gold-bright);">Go to Staff Portal &rarr;</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function fillDemoCustomer() {
    document.getElementById('email').value = 'customer@thecheddarain.com';
    document.getElementById('password').value = 'customer123';
}
</script>
@endpush
@endsection
