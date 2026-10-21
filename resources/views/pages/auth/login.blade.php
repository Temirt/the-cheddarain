@extends('layouts.app')

@section('title', 'Sign In — The Cheddarain')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <span class="badge badge-cheddar">Welcome Back</span>
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
@endsection
