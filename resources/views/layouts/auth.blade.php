<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Auth — Challora' }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login-register-style.css') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body>
    @yield('content')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from(".gsap-reveal", {
                opacity: 0,
                scale: 0.98,
                duration: 1.2,
                ease: "power4.out"
            });
            gsap.from(".gsap-item", {
                opacity: 0,
                x: -20,
                stagger: 0.1,
                duration: 0.8,
                ease: "power3.out",
                delay: 0.3
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
