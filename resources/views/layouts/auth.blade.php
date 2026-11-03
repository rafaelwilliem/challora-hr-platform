<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Auth — Challora' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web-icon.png') }}">
    
    <!-- Fonts: Sans-serif (Big Titles), Serif (Main Body), Monospace (Mini Text & Badges) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Poppins:wght@500;600;700;800;900&family=Space+Grotesk:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login-register-style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
</head>

<body class="auth-dotgrid-page">
    <div id="auth-dotgrid-react-root" class="auth-dotgrid-react-root" aria-hidden="true"></div>
    <div class="auth-content-shell">
        @yield('content')
    </div>

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

            // ── Eye toggle for all password inputs ──────────────────────
            document.querySelectorAll('input[type="password"]').forEach(input => {
                const wrapper = document.createElement('div');
                wrapper.style.cssText = 'position:relative;';
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('aria-label', 'Toggle password visibility');
                btn.style.cssText = 'position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#888;padding:0;display:flex;align-items:center;';
                btn.innerHTML = `<svg class="eye-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>`;
                wrapper.appendChild(btn);

                btn.addEventListener('click', () => {
                    const isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    btn.style.color = isPassword ? 'var(--color-accent, #FF4D30)' : '#888';
                });
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
