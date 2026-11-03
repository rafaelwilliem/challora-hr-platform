<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'HR Panel — Challora' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web-icon.png') }}">

    <!-- Vite Assets (Tailwind & JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Poppins:wght@500;600;700;800;900&family=Space+Grotesk:wght@500;600;700;800&display=swap"
        rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --nav-height: 80px;
        }

        body {
            background-color: var(--color-surface);
            color: var(--color-text);
            overflow-x: hidden;
            font-family: var(--font-sans);
        }


        .hr-main-content {
            padding: 60px 80px; /* Fixed horizontal padding matching main app */
            min-height: calc(100vh - var(--nav-height));
            max-width: 1600px;
            margin: 0 auto;
        }


    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen bg-surface flex flex-col">
        @include('partials.navbar')

        <main id="swup" class="hr-main-content flex-1 transition-fade">
            @if(session('flash_toast'))
                <div class="mb-10 flex items-center justify-between p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-green-100 text-green-700 font-bold"
                    id="flash-alert">
                    <div class="flex items-center gap-3">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="tracking-tight uppercase">{{ session('flash_toast')['message'] }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100 transition-opacity bg-black text-white p-1 rounded-sm">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-10 flex flex-col p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-red-100 text-red-700 font-bold">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-3">
                            <i class="bi bi-exclamation-circle-fill text-lg"></i>
                            <span class="tracking-tight uppercase">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div id="hr-view-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {


            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('hrSidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('open');
                });
            }

            // Content entrance
            gsap.from("#hr-view-content", {
                opacity: 0,
                y: 10,
                duration: 0.6,
                ease: "power2.out"
            });
        });
    </script>
    @stack('scripts')
</body>

</html>