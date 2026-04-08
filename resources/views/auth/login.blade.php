@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen">

    {{-- ═══ LEFT PANEL ═══ --}}
    <div class="bg-left flex flex-col justify-center flex-1 relative overflow-hidden
                px-6 py-10
                sm:px-10 sm:py-12
                lg:px-16 lg:py-16
                xl:px-32 xl:py-24
                2xl:px-40 2xl:py-28">

        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-10 sm:mb-14 lg:mb-16 xl:mb-20">
            <div class="w-9 h-9 xl:w-12 xl:h-12 rounded-lg bg-purple-600
                        flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 xl:w-7 xl:h-7" viewBox="0 0 20 20" fill="none">
                    <rect x="2"  y="2"  width="7" height="7" rx="1.5" fill="white" />
                    <rect x="11" y="2"  width="7" height="7" rx="1.5" fill="white" />
                    <rect x="2"  y="11" width="7" height="7" rx="1.5" fill="white" />
                    <rect x="11" y="11" width="7" height="7" rx="1.5" fill="white" />
                </svg>
            </div>
            <span class="text-white font-bold text-xl xl:text-2xl 2xl:text-3xl tracking-tight">
                Attendance<span class="text-purple-400 font-semibold">Pro</span>
            </span>
        </div>

        {{-- Headline --}}
        <h1 class="font-black text-white leading-tight mb-4
                   text-3xl sm:text-4xl lg:text-5xl xl:text-6xl 2xl:text-7xl">
            Precision workforce<br />
            <span class="text-purple-500">management.</span>
        </h1>

        {{-- Subtext --}}
        <p class="text-gray-400 leading-relaxed mb-10 sm:mb-12 xl:mb-14
                  text-sm sm:text-base xl:text-lg
                  max-w-xs sm:max-w-sm lg:max-w-xs xl:max-w-md 2xl:max-w-lg">
            The ultimate high-performance dashboard for modern enterprise teams.
            Real-time insights, automated reporting, and seamless integration.
        </p>

        {{-- Stat Card --}}
        <div class="stat-card rounded-xl px-4 py-3 sm:px-5 sm:py-4 xl:px-7 xl:py-5
                    flex items-center gap-4 xl:gap-5
                    w-full max-w-xs sm:max-w-sm xl:max-w-md">
            <div class="flex items-center flex-shrink-0">
                <img class="avatar xl:!w-11 xl:!h-11" src="https://i.pravatar.cc/36?img=11" alt="" />
                <img class="avatar xl:!w-11 xl:!h-11" src="https://i.pravatar.cc/36?img=22" alt="" />
                <img class="avatar xl:!w-11 xl:!h-11" src="https://i.pravatar.cc/36?img=33" alt="" />
            </div>
            <div>
                <p class="text-white font-bold text-sm xl:text-base">94% Attendance</p>
                <p class="text-gray-500 text-xs xl:text-sm">Real-time team performance active</p>
            </div>
        </div>
    </div>

    {{-- ═══ RIGHT PANEL / FORM ═══ --}}
    <div class="form-card flex flex-col justify-center
                px-6 py-10
                sm:px-10 sm:py-12
                lg:px-12 lg:py-16
                xl:px-16 xl:py-24
                2xl:px-20 2xl:py-28
                w-full
                md:w-[340px] lg:w-[400px] xl:w-[520px] 2xl:w-[580px]
                md:flex-shrink-0">

        <h2 class="text-white font-bold mb-1 text-xl sm:text-2xl xl:text-3xl 2xl:text-4xl">
            Welcome Back
        </h2>
        <p class="text-gray-400 text-sm xl:text-base mb-7 sm:mb-8 xl:mb-10">
            Enter your professional credentials to continue.
        </p>

        {{-- Session errors --}}
        @if ($errors->any())
            <div class="mb-5 p-3 rounded-lg bg-red-500/10 border border-red-500/30">
                <p class="text-red-400 text-sm">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            {{-- Email --}}
            <div class="mb-5 xl:mb-6">
                <label class="block text-gray-300 text-xs xl:text-sm font-semibold mb-2 tracking-wide">
                    Work Email Address
                </label>
                <div class="relative">
                    <span class="absolute left-3 xl:left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 7-10 7L2 7" />
                        </svg>
                    </span>
                    <input type="email" name="email" placeholder="name@company.com"
                           value="{{ old('email') }}"
                           class="input-field rounded-lg pl-10 xl:pl-12 pr-4 py-3 xl:py-4 text-sm xl:text-base"
                           autocomplete="email" required />
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-5 xl:mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-gray-300 text-xs xl:text-sm font-semibold tracking-wide">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-purple-400 text-xs xl:text-sm hover:text-purple-300 transition-colors">
                            Forgot Password?
                        </a>
                    @endif
                </div>
                <div class="relative">
                    <span class="absolute left-3 xl:left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <svg class="w-4 h-4 xl:w-5 xl:h-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    <input type="password" id="pwInput" name="password"
                           class="input-field rounded-lg pl-10 xl:pl-12 pr-11 py-3 xl:py-4 text-sm xl:text-base"
                           autocomplete="current-password" required />
                    <button type="button" onclick="togglePw()"
                            class="absolute right-3 xl:right-4 top-1/2 -translate-y-1/2
                                   text-gray-500 hover:text-gray-300 transition-colors">
                        <svg id="eyeIcon" class="w-4 h-4 xl:w-5 xl:h-5" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="flex items-center gap-2 mb-7 xl:mb-8">
                <input type="checkbox" id="remember" name="remember"
                       class="w-4 h-4 xl:w-5 xl:h-5 rounded border-gray-600 bg-transparent
                              accent-purple-600 cursor-pointer" />
                <label for="remember" class="text-gray-400 text-sm xl:text-base cursor-pointer select-none">
                    Remember this session
                </label>
            </div>

            {{-- Sign In Button --}}
            <button type="submit"
                    class="btn-signin w-full py-3 xl:py-4 rounded-lg text-white font-semibold
                           text-sm xl:text-base flex items-center justify-center gap-2">
                Sign In
                <svg class="w-4 h-4 xl:w-5 xl:h-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </button>

        </form>

        {{-- Contact Admin --}}
        <p class="text-center text-gray-500 text-xs xl:text-sm mt-6 xl:mt-8">
            Don't have an account?
            <a href="mailto:admin@company.com"
               class="text-purple-400 font-semibold hover:text-purple-300 transition-colors">
                Contact Administrator
            </a>
        </p>

        {{-- Footer Links --}}
        <div class="flex justify-center gap-5 mt-8 xl:mt-10">
            <a href="#" class="text-gray-600 text-xs xl:text-sm hover:text-gray-400 transition-colors">
                Privacy Policy
            </a>
            <a href="#" class="text-gray-600 text-xs xl:text-sm hover:text-gray-400 transition-colors">
                Terms of Service
            </a>
        </div>

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