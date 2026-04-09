@extends('layouts.guest')

@section('title', 'Sign In')

@push('styles')
<style>
    body { background: #1a1b1f; }

    .login-page {
        min-height: 100vh;
        background: #1a1b1f;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Logo */
    .logo-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        margin-bottom: 28px;
    }
    .logo-icon {
        width: 44px;
        height: 44px;
        background: #7c3aed;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-name {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .logo-sub {
        color: #6b7280;
        font-size: 13px;
    }

    /* Card */
    .login-card {
        background: #16171c;
        border: 1px solid #2a2b32;
        border-radius: 14px;
        padding: 32px 28px;
        width: 100%;
        max-width: 400px;
    }
    .login-card h2 {
        color: #fff;
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    .login-card .card-sub {
        color: #6b7280;
        font-size: 13px;
        margin-bottom: 24px;
    }

    /* Error */
    .error-box {
        margin-bottom: 16px;
        padding: 10px 14px;
        border-radius: 8px;
        background: rgba(239,68,68,0.1);
        border: 1px solid rgba(239,68,68,0.3);
        color: #f87171;
        font-size: 13px;
    }

    /* Fields */
    .field { margin-bottom: 18px; }
    .field label {
        display: block;
        color: #d1d5db;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 7px;
    }
    .field .label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 7px;
    }
    .field .label-row label { margin-bottom: 0; }
    .forgot-link {
        color: #a78bfa;
        font-size: 12px;
        text-decoration: none;
        transition: color .2s;
    }
    .forgot-link:hover { color: #c4b5fd; }

    .input-wrap { position: relative; }
    .input-wrap .icon-left {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 15px;
        height: 15px;
        color: #4b5563;
        pointer-events: none;
    }
    .input-field {
        width: 100%;
        background: #0f1013;
        border: 1px solid #2a2b32;
        border-radius: 8px;
        color: #e5e7eb;
        font-size: 13.5px;
        padding: 11px 38px;
        outline: none;
        transition: border-color .2s;
    }
    .input-field::placeholder { color: #374151; }
    .input-field:focus { border-color: #7c3aed; }
    .btn-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #4b5563;
        padding: 0;
        display: flex;
        align-items: center;
        transition: color .2s;
    }
    .btn-eye:hover { color: #9ca3af; }

    /* Remember */
    .remember-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }
    .remember-label {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .remember-label input[type="checkbox"] {
        width: 15px;
        height: 15px;
        accent-color: #7c3aed;
        cursor: pointer;
    }
    .remember-label span { color: #9ca3af; font-size: 13px; }

    /* Sign In Button */
    .btn-signin {
        width: 100%;
        background: #7c3aed;
        border: none;
        border-radius: 8px;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        padding: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background .2s;
    }
    .btn-signin:hover { background: #6d28d9; }

    /* Bottom */
    .contact-text {
        text-align: center;
        color: #6b7280;
        font-size: 12px;
        margin-top: 18px;
    }
    .contact-text a {
        color: #a78bfa;
        font-weight: 600;
        text-decoration: none;
    }
    .footer-links {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 22px;
    }
    .footer-links a {
        color: #4b5563;
        font-size: 11px;
        text-decoration: none;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        transition: color .2s;
    }
    .footer-links a:hover { color: #9ca3af; }
</style>
@endpush

@section('content')
<div class="login-page">

    {{-- Logo --}}
    <div class="logo-wrap">
        <div style="display: flex; gap: 8px; margin-bottom: 6px;">
            <img src="{{ asset('images/logo_cni.png') }}" alt="Logo CNI" style="width: 50px; height: 50px; object-fit: contain;">
            <img src="{{ asset('images/logo_csi.png') }}" alt="Logo CSI" style="width: 50px; height: 50px; object-fit: contain;">
        </div>
        <span class="logo-name">AttendancePro</span>
        <span class="logo-sub">Manage your attendance and shifts</span>
    </div>

    {{-- Card --}}
    <div class="login-card">
        <h2>Welcome Back</h2>
        <p class="card-sub">Please enter your details to access your account.</p>

        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            {{-- Email --}}
            <div class="field">
                <label>Email Address</label>
                <div class="input-wrap">
                    <svg class="icon-left" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-10 7L2 7"/>
                    </svg>
                    <input type="email" name="email" class="input-field"
                           placeholder="name@company.com"
                           value="{{ old('email') }}"
                           autocomplete="email" required />
                </div>
            </div>

            {{-- Password --}}
            <div class="field">
                <div class="label-row">
                    <label>Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                <div class="input-wrap">
                    <svg class="icon-left" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input type="password" id="pwInput" name="password"
                           placeholder="Enter your password"
                           class="input-field" style="padding-right: 40px;"
                           autocomplete="current-password" required />
                    <button type="button" class="btn-eye" onclick="togglePw()">
                        <svg id="eyeIcon" width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" />
                    <span>Remember me</span>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-signin">
                Sign In
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="white" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </form>

        <p class="contact-text">
            Don't have an account yet?
            <a href="mailto:admin@company.com">Contact HR</a>
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer-links">
        <a href="#">Terms</a>
        <a href="#">Privacy</a>
        <a href="#">Support</a>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function togglePw() {
        const inp  = document.getElementById('pwInput');
        const icon = document.getElementById('eyeIcon');
        if (inp.type === 'password') {
            inp.type = 'text';
            icon.innerHTML = `
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>`;
        } else {
            inp.type = 'password';
            icon.innerHTML = `
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>`;
        }
    }
</script>
@endpush