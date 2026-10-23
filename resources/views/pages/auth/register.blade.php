@extends('layouts.app')

@section('title', 'Create Account — The Cheddarain')

@section('content')
<div class="container container-narrow" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="section-title">
        <div style="margin-bottom: 0.8rem;">
            <span class="badge badge-cheddar">Join The Cheddar Club</span>
        </div>
        <h2>Create Your Account</h2>
        <p>Register to unlock express checkout, track orders in real time, and view order receipts.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" style="max-width: 520px; margin: 0 auto 1.5rem auto;">
            <div>
                <strong>Please fix the following:</strong>
                <ul style="margin-left: 1.5rem; margin-top: 0.4rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 2.5rem; max-width: 520px; margin: 0 auto; box-shadow: var(--shadow-lg);">
        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name *</label>
                <input type="text" name="name" id="name" class="form-input" required value="{{ old('name') }}" placeholder="e.g. Michael Sterling">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address *</label>
                <input type="email" name="email" id="email" class="form-input" required value="{{ old('email') }}" placeholder="e.g. michael@example.com">
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone Number * (Used for Live Order Tracking)</label>
                <input type="tel" name="phone" id="phone" class="form-input" required value="{{ old('phone') }}" placeholder="e.g. 555-123-4567">
                <span class="form-hint">Used for order updates and verification.</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password (Minimum 8 characters) *</label>
                <input type="password" name="password" id="password" class="form-input" required placeholder="••••••••">
            </div>

            <div class="form-group" style="margin-bottom: 2rem;">
                <label class="form-label" for="password_confirmation">Confirm Password *</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <span>Create Account & Join &rarr;</span>
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); font-size: 0.92rem; color: var(--text-cream-muted);">
            Already have an account? 
            <a href="{{ route('login') }}" style="color: var(--cheddar-bright); font-weight: 700;">Sign in here</a>
        </div>
    </div>
</div>
@endsection
