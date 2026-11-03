@extends('layouts.auth')

@section('content')
<div class="split-card gsap-reveal">
    <!-- FORM SIDE -->
    <div class="form-side">
        <div class="gsap-item">
            <h1 class="auth-title">Forgot Password</h1>
            <p class="auth-subtitle">Enter your email and we'll send you a reset link.</p>
        </div>

        @if(session('status'))
            <div class="bg-green-600 text-white p-4 mb-8 border-2 border-black font-black uppercase text-[10px] tracking-widest gsap-item shadow-[4px_4px_0_black]">
                <div class="flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            </div>
        @endif

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

        <form method="post" action="{{ route('password.email') }}" class="gsap-item">
            @csrf

            <div class="mb-2">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white">
                        EMAIL ADDRESS
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </span>
                    <span class="text-accent-600 label-req">*required</span>
                </label>
                <input type="email" name="email" placeholder="example@gmail.com" required
                    class="premium-input" value="{{ old('email') }}">
            </div>

            <button type="submit" class="btn-brutal mt-6">
                SEND RESET LINK
            </button>

            <div class="mt-10 text-center font-mono">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                    Remembered your password?
                    <a href="{{ route('login') }}" class="text-accent-500 hover:text-white transition-colors duration-300">Sign In</a>
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
                “Account recovery is one step away. Check your inbox for the reset link.”
            </div>
        </div>
        <div class="brand-footer gsap-item">
            CHALLORA HR // RECOVERY
        </div>
    </div>
</div>
@endsection
