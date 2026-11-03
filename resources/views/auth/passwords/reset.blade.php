@extends('layouts.auth')

@section('content')
<div class="split-card gsap-reveal">
    <!-- FORM SIDE -->
    <div class="form-side">
        <div class="gsap-item">
            <h1 class="auth-title">Reset Password</h1>
            <p class="auth-subtitle">Enter your new password to secure your account.</p>
        </div>

        @if($errors->any())
            <div class="error-msg">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <form method="post" action="{{ route('password.update') }}" class="gsap-item">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-2">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white">
                        EMAIL ADDRESS
                    </span>
                </label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                    class="premium-input opacity-50 cursor-not-allowed">
            </div>

            <div class="mb-2">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white">
                        NEW PASSWORD
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                    <span class="text-accent-600 label-req">*min 8</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" required
                    autocomplete="new-password" class="premium-input">
            </div>

            <div class="mb-2">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white">
                        CONFIRM PASSWORD
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                </label>
                <input type="password" name="password_confirmation" placeholder="••••••••" required
                    autocomplete="new-password" class="premium-input">
            </div>

            <button type="submit" class="btn-brutal mt-6">
                UPDATE PASSWORD
            </button>

            <div class="mt-10 text-center font-mono">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                    <a href="{{ route('login') }}" class="text-accent-500 hover:text-white transition-colors duration-300">← Back to Sign In</a>
                </p>
            </div>
        </form>
    </div>

    <!-- BRAND SIDE -->
    <div class="brand-side">
        <div class="gsap-item">
            <div class="flex items-center gap-3 mb-2">
                <img src="{{ asset('web-icon.png') }}" alt="Challora" class="w-10 h-10 object-contain border-2 border-black">
                <div class="brand-title">Challora</div>
            </div>
            <div class="brand-quote">
                “A fresh start. Set a new password to secure your workspace.”
            </div>
        </div>
        <div class="brand-footer gsap-item">
            CHALLORA HR // CREDENTIALS
        </div>
    </div>
</div>
@endsection
