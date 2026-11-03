<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name', 'Challora') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web-icon.png') }}">

    <!-- Fonts: Sans-serif (Big Titles), Serif (Main Body), Monospace (Mini Text & Badges) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Poppins:wght@500;600;700;800;900&family=Space+Grotesk:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">

    @stack('styles')

    <style>
        :root {
            --nav-height: 80px;
        }

        .main-container,
        .hr-main-content {
            width: 100%;
            min-height: calc(100vh - var(--nav-height));
            max-width: 1360px;
            margin: 0 auto;
            padding: 48px 20px 80px;
        }

        @media (min-width: 768px) {
            .main-container,
            .hr-main-content {
                padding: 56px 48px 96px;
            }
        }

        /* Landing-only: merge hero with navbar */
        body.landing-page .brutal-header {
            position: fixed !important;
            background: rgba(7, 7, 7, 0.82) !important;
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #1d1d1d !important;
        }

        body.landing-page .main-container {
            max-width: none;
            padding-top: 0;
            padding-left: 0;
            padding-right: 0;
            padding-bottom: 0;
        }
    </style>
</head>

<body class="antialiased bg-[#070707] text-[#E5E5DF] {{ request()->routeIs('landing') ? 'landing-page' : '' }}">
    <div class="min-h-screen bg-[#070707] flex flex-col">
        @include('partials.navbar')

        @if(session('flash_toast'))
            <div class="max-w-[1360px] w-full mx-auto px-5 md:px-12 mt-6" id="flash-alert">
                <div class="flex items-center justify-between p-4 border border-[#22c55e]/40 bg-[#090909] text-[#4ade80]">
                    <div class="flex items-center gap-3">
                        <span class="w-2 h-2 bg-[#4ade80]"></span>
                        <span class="c-mono text-xs font-bold uppercase tracking-widest">{{ session('flash_toast')['message'] }}</span>
                    </div>
                    <button onclick="document.getElementById('flash-alert').remove()"
                        class="c-mono text-xs text-gray-400 hover:text-white px-2 py-1 border border-[#262626]">
                        ✕
                    </button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-[1360px] w-full mx-auto px-5 md:px-12 mt-6">
                <div class="flex flex-col gap-2 p-4 border border-[#ef4444]/40 bg-[#090909] text-[#f87171]">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 bg-[#f87171]"></span>
                            <span class="c-mono text-xs font-bold uppercase tracking-wider">{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <main class="main-container flex-1">
            @yield('content')
        </main>

        <!-- ARCHITECTURAL FOOTER (MATCHING LANDING PAGE) -->
        <footer class="bg-[#070707] border-t border-[#1d1d1d]">
            <div class="max-w-[1360px] mx-auto px-6 md:px-12 py-16">
                <div class="grid grid-cols-1 md:grid-cols-12 border border-[#1d1d1d] bg-[#090909]">
                    <!-- Brand Column -->
                    <div class="md:col-span-6 p-8 md:p-10 border-b md:border-b-0 md:border-r border-[#1d1d1d] flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <img src="{{ asset('web-icon.png') }}" alt="Challora" class="w-10 h-10 object-contain">
                                <span class="c-title-card text-white text-2xl lowercase tracking-tight">challora</span>
                            </div>
                            <p class="c-motto text-base max-w-md mb-6">
                                A focused talent acquisition platform — matching candidate potential with company growth through objective intelligence.
                            </p>
                        </div>
                        <div class="pt-6 border-t border-[#181818] flex items-center justify-between">
                            <span class="c-mono text-[11px] text-[#777] uppercase">INQUIRIES //</span>
                            <a href="mailto:admin.challora@gmail.com" class="c-mono text-xs text-[#E5E5DF] hover:text-[#FF4D30] transition-colors">admin.challora@gmail.com</a>
                        </div>
                    </div>

                    <!-- Navigation Column -->
                    <div class="md:col-span-3 p-8 md:p-10 border-b md:border-b-0 md:border-r border-[#1d1d1d]">
                        <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-5">// 01 &middot; PLATFORM</span>
                        <ul class="space-y-3 c-mono text-xs uppercase tracking-wider">
                            <li><a href="{{ route('jobs.index') }}" class="text-gray-400 hover:text-white transition-colors">Browse Positions &rarr;</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Candidate Portal &rarr;</a></li>
                            <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white transition-colors">Recruiter Access &rarr;</a></li>
                            <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition-colors">Sign In &rarr;</a></li>
                        </ul>
                    </div>

                    <!-- System Metadata Column -->
                    <div class="md:col-span-3 p-8 md:p-10 flex flex-col justify-between">
                        <div>
                            <span class="c-mono text-[10px] text-[#FF4D30] font-bold tracking-widest uppercase block mb-5">// 02 &middot; ARCHITECTURE</span>
                            <p class="c-serif text-sm text-gray-400 leading-relaxed">
                                End-to-end applicant tracking, contextual CV parsing, structured dossiers, and candidate coaching.
                            </p>
                        </div>
                        <div class="pt-6 border-t border-[#181818] mt-6">
                            <div class="c-mono text-[11px] text-gray-500 flex items-center justify-between">
                                <span>RELEASE</span>
                                <span class="text-white font-bold">v{{ $appVersion }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Legal Bar -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 c-mono text-[11px] text-[#666] uppercase tracking-wider">
                    <div>&copy; {{ date('Y') }} CHALLORA HR PLATFORM. ALL RIGHTS RESERVED.</div>
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="{{ route('jobs.index') }}" class="hover:text-white transition-colors">OPEN ROLES</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition-colors">RECRUITER ACCESS</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                gsap.from(".main-container", {
                    opacity: 0,
                    y: 20,
                    duration: 0.8,
                    ease: "power3.out"
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>