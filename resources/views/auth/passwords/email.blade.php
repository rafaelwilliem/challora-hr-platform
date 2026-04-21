@extends('layouts.auth')

@section('title', 'Forgot Password - Challora')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold mb-2 lowercase" style="letter-spacing: -2px;">forgot password</h1>
    <p class="font-semibold opacity-75 lowercase">enter your email to receive a reset link.</p>
</div>

@if (session('status'))
    <div class="brutalist-alert bg-green-600 text-white mb-6">
        {{ session('status') }}
    </div>
    @if (session('demo_link'))
        <div class="brutalist-alert bg-blue-600 text-white mt-4 break-all lowercase">
            copy this link: <a href="{{ session('demo_link') }}" target="_blank" class="underline">{{ session('demo_link') }}</a>
        </div>
    @endif
@endif

@if ($errors->any())
    <div class="brutalist-alert bg-red-600 text-white mb-6">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="post" action="{{ route('password.email') }}" class="space-y-6">
    @csrf
    <div>
        <label class="brutalist-label lowercase">email address</label>
        <input
            type="email"
            name="email"
            placeholder="john.doe@gmail.com"
            required
            class="brutalist-input"
            value="{{ old('email') }}"
        >
    </div>

    <button type="submit" class="brutalist-btn lowercase">
        send reset link
    </button>

    <p class="text-sm text-center font-semibold pt-2 lowercase">
        remembered your password?
        <a href="{{ route('login') }}" class="text-accent underline hover:text-white transition" style="text-underline-offset: 4px;">sign in</a>
    </p>
</form>
@endsection
