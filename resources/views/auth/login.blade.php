@extends('layouts.auth')

@section('content')
<div class="split-card gsap-reveal">
    <!-- FORM SIDE -->
    <div class="form-side">
        <div class="gsap-item">
            <h1 class="auth-title">Log-In</h1>
            <p class="auth-subtitle">Access your intelligence dashboard.</p>
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

        @if(session('flash_toast'))
            <div class="bg-accent text-[#111] p-4 mb-8 border-2 border-black font-black uppercase text-[10px] tracking-widest gsap-item shadow-[4px_4px_0_black]">
                <div class="flex items-center gap-2">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('flash_toast')['message'] }}
                </div>
            </div>
        @endif

        <form method="post" action="{{ route('login') }}" class="gsap-item">
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
                <input type="email" name="email" placeholder="example@gmail.com" required class="premium-input"
                    value="{{ old('email') }}">
            </div>

            <div class="mb-2">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white">
                        PASSWORD
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                    <span class="text-accent-600 label-req">*required</span>
                </label>
                <input type="password" name="password" placeholder="••••••••" required class="premium-input">
            </div>

            <a href="{{ route('password.request') }}"
                class="text-accent-500 hover:text-accent-400 font-black text-xs uppercase tracking-widest inline-block mb-8">Forgot
                Password?</a>

            <button type="submit" class="btn-brutal">
                LOG-IN
            </button>

            <div class="mt-12 text-center">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                    New Here? <a href="{{ route('register') }}"
                        class="text-accent-500 hover:text-white transition-colors duration-300">Sign-Up</a>
                </p>
            </div>
        </form>
    </div>

    <!-- BRAND SIDE -->
    <div class="brand-side">
        <div class="gsap-item">
            <div class="brand-title">Challora</div>
            <div class="brand-quote">
                Intelligence is not just about what you know, but how you connect the dots.
            </div>
        </div>

        <div class="brand-footer gsap-item">
            AUTH PROTOCOL — V2.2.2
        </div>
    </div>
</div>
@endsection
