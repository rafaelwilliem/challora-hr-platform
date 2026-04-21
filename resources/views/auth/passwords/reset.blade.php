@extends('layouts.auth')

@section('title', 'Reset Password - Challora')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 lowercase" style="letter-spacing: -2px;">reset password</h1>
    <p class="font-semibold opacity-75 lowercase">enter your new password to secure your account.</p>
</div>

@if ($errors->any())
    <div class="brutalist-alert bg-red-600 text-white mb-6">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div>
        <label for="email" class="brutalist-label lowercase">email address</label>
        <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="brutalist-input opacity-50 cursor-not-allowed">
    </div>

    <div>
        <label for="password" class="brutalist-label lowercase">new password (min 8 chars)</label>
        <input type="password" id="password" name="password" placeholder="enter new password" required autocomplete="new-password" class="brutalist-input">
    </div>
    
    <div>
        <label for="password_confirm" class="brutalist-label lowercase">confirm password</label>
        <input type="password" id="password_confirm" name="password_confirmation" placeholder="confirm new password" required autocomplete="new-password" class="brutalist-input">
    </div>
    
    <button type="submit" class="brutalist-btn lowercase">
        update password
    </button>
    
    <p class="text-sm text-center font-semibold pt-2 lowercase">
        <a href="{{ route('login') }}" class="text-accent underline hover:text-white transition" style="text-underline-offset: 4px;">back to sign in</a>
    </p>
</form>
@endsection
