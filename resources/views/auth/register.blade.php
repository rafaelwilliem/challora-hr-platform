@extends('layouts.auth')

@section('content')
<div class="split-card register-wide gsap-reveal">
    <!-- FORM SIDE -->
    <div class="form-side">
        <div class="gsap-item">
            <h1 class="auth-title">Register</h1>
            <p class="auth-subtitle">Join the professional intelligence network.</p>
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

        <form method="post" action="{{ route('register') }}" class="gsap-item">
            @csrf

            @php
                $selectedRole = old('role', 'user');
                $requiresCode = $requiresHrInviteCode ?? true;
            @endphp
            <div class="mb-6">
                <label class="premium-label">
                    <span class="flex items-center gap-2 text-white text-[10px]">
                        ACCOUNT ROLE
                    </span>
                    <span class="text-accent-600 label-req">*select role</span>
                </label>
                <input type="hidden" name="role" id="role-input" value="{{ $selectedRole }}">
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" data-role-btn="user"
                        class="role-selector-btn {{ $selectedRole === 'user' ? 'active' : '' }}">
                        CANDIDATE
                    </button>
                    <button type="button" data-role-btn="hr"
                        class="role-selector-btn {{ $selectedRole === 'hr' ? 'active' : '' }}">
                        RECRUITER (HR)
                    </button>
                </div>
            </div>

            <div id="hr-invite-wrapper" data-requires-code="{{ $requiresCode ? '1' : '0' }}" class="mb-4 {{ $selectedRole === 'hr' ? '' : 'hidden' }}">
                @if($requiresCode)
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white text-[10px]">
                            HR INVITE CODE
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                </path>
                            </svg>
                        </span>
                        <span class="text-accent-600 label-req">*ask an existing recruiter</span>
                    </label>
                    <input type="password" name="hr_invite_code" id="hr-invite-input"
                        placeholder="Enter HR invite code from a colleague's dashboard"
                        value="{{ old('hr_invite_code') }}"
                        {{ $selectedRole === 'hr' ? 'required' : '' }}
                        class="premium-input">
                @else
                    <div class="p-3 border border-accent-600 bg-black text-xs font-bold text-gray-300 uppercase tracking-wider">
                        First Recruiter Bootstrap: No invite code required to claim the initial HR account.
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white text-[10px]">
                            FULL NAME
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </span>
                        <span class="text-accent-600 label-req">*required</span>
                    </label>
                    <input type="text" name="name" placeholder="John Doe" required
                        value="{{ old('name') }}" class="premium-input">
                </div>

                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white text-[10px]">
                            PHONE NUMBER
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </span>
                    </label>
                    <input type="text" name="phone" placeholder="0812...." value="{{ old('phone') }}"
                        class="premium-input">
                </div>
            </div>

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
                    value="{{ old('email') }}" class="premium-input">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white text-[10px]">
                            PASSWORD
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </span>
                        <span class="text-accent-600 label-req">*min 8</span>
                    </label>
                    <input type="password" name="password" placeholder="••••••••" required class="premium-input">
                </div>

                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white text-[10px]">
                            CONFIRM
                            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </span>
                    </label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required
                        class="premium-input">
                </div>
            </div>

            <button type="submit" class="btn-brutal">
                CREATE ACCOUNT
            </button>

            <div class="mt-10 text-center font-mono">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                    Already Have An Account? <a href="{{ route('login') }}"
                        class="text-accent-500 hover:text-white transition-colors duration-300">Log-In</a>
                </p>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mt-3">
                    <a href="{{ route('landing') }}" class="hover:text-white transition-colors duration-300">← Back to Home</a>
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
                “Build your professional profile and unlock verified career opportunities.”
            </div>
        </div>

        <div class="brand-footer gsap-item">
            CHALLORA HR // REGISTRATION
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleInput = document.getElementById('role-input');
        const inviteWrapper = document.getElementById('hr-invite-wrapper');
        const inviteInput = document.getElementById('hr-invite-input');
        const roleButtons = document.querySelectorAll('[data-role-btn]');

        roleButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const role = btn.getAttribute('data-role-btn');
                roleInput.value = role;

                roleButtons.forEach(function (b) {
                    b.classList.toggle('active', b === btn);
                });

                if (role === 'hr') {
                    inviteWrapper.classList.remove('hidden');
                    if (inviteInput) {
                        inviteInput.setAttribute('required', 'required');
                    }
                } else {
                    inviteWrapper.classList.add('hidden');
                    if (inviteInput) {
                        inviteInput.removeAttribute('required');
                        inviteInput.value = '';
                    }
                }
            });
        });
    });
</script>
@endsection
