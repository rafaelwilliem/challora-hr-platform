<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name', 'Challora') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('web-icon.png') }}">

    <!-- Token tema (warna, font, radius) -->
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Vite Assets (Tailwind & JS) -->
    <!-- Fonts: Sans-serif (Big Titles), Serif (Main Body), Monospace (Mini Text & Badges) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400;1,6..72,500&family=Poppins:wght@500;600;700;800;900&family=Space+Grotesk:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])

    @stack('styles')

    <style>
        :root {
            --nav-height: 80px;
        }



        .main-container,
        .hr-main-content {
            width: 100%;
            min-height: calc(100vh - var(--nav-height));
            max-width: 1600px;
            margin: 0 auto;
            padding: 60px 20px;
        }

        @media (min-width: 768px) {
            .main-container,
            .hr-main-content {
                padding: 60px 80px;
            }
        }

        /* Landing-only: merge hero with navbar */
        body.landing-page .brutal-header {
            position: fixed;
            background: rgba(0, 0, 0, 0.72);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        body.landing-page .main-container {
            max-width: none;
            padding-top: 0;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>

<body class="antialiased bg-primary text-text font-sans {{ request()->routeIs('landing') ? 'landing-page' : '' }}">
    <div class="min-h-screen bg-surface flex flex-col">
        @include('partials.navbar')

        @if(session('flash_toast'))
            <div class="mx-[80px] mt-8 flex items-center justify-between p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-success-bg text-success-text font-bold"
                id="flash-alert">
                <div class="flex items-center gap-3">
                    <svg width="24" height="24" class="text-success-text" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="tracking-tight uppercase">{{ session('flash_toast')['message'] }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="opacity-50 hover:opacity-100 transition-opacity bg-black text-white p-1 rounded-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div
                class="mx-[80px] mt-8 flex flex-col p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-red-100 text-red-700 font-bold">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-3">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="tracking-tight uppercase">{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <main class="main-container flex-1">
            @yield('content')
        </main>

        <footer class="bg-accent border-t border-black pt-10">
            <!-- LAYER 1: CONTACT & CTA (Orange BG, Black Text) -->
            <div class="px-8 md:px-20 pb-10 border-b border-black text-black">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Left: Contact info -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="col-span-full mb-4">
                            <h2 class="font-mono text-xl font-bold uppercase leading-tight">GET IN TOUCH<br>TO LEARN MORE.</h2>
                        </div>
                        <div>
                            <p class="font-mono text-xs uppercase font-bold mb-1 opacity-70">GENERAL INQUIRIES</p>
                            <p class="font-bold text-lg leading-tight hover:underline cursor-pointer">admin.challora@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- LAYER 2: THE CONTROL CENTER -->
            <div class="bg-[#1A1A1A] text-white rounded-[48px] m-4 md:m-8 p-10 md:p-20 relative flex flex-col justify-between" style="min-height: 60vh;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 relative z-10 mb-20">
                    <!-- Col 1: Logo -->
                    <div class="text-accent">
                        <div class="w-16 h-16 border-4 border-accent flex items-center justify-center font-black text-4xl">C</div>
                    </div>

                    <!-- Col 2: Platform links -->
                    <div>
                        <h3 class="font-mono text-xs uppercase tracking-widest text-[#888] mb-6 flex items-start gap-1">Platform <span class="text-[8px]">4</span></h3>
                        <ul class="space-y-4 font-bold text-base">
                            <li><a href="{{ route('jobs.index') }}" class="hover:text-accent transition-colors">↳ Browse Jobs</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-accent transition-colors">↳ Post a Job</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-accent transition-colors">↳ For Candidates</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-accent transition-colors">↳ For HR Teams</a></li>
                        </ul>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-end relative z-10 font-mono text-xs text-[#888] uppercase mb-10 md:mb-16">
                    <div class="flex items-center gap-3">
                        <span>BUILT FOR THE FUTURE OF WORK.</span>
                    </div>
                    <div class="text-left md:text-right mt-8 md:mt-0">
                        <p>CHALLORA &nbsp;·&nbsp; v{{ $appVersion }}</p>
                    </div>
                </div>

                <!-- Mega Wordmark — full height characters, zero gap to bottom edge -->
                <div class="absolute bottom-0 left-0 w-full pointer-events-none overflow-hidden rounded-b-[48px]" style="height: 13vw;">
                    <div class="font-black text-center tracking-tighter text-[#2a2a2a] select-none w-full" style="font-size: 15.5vw; line-height: 1; position: absolute; bottom: -0.15em; left: 0;">
                        CHALLORA
                    </div>
                </div>
            </div>

            <!-- LAYER 3: LEGAL STRIP -->
            <div class="bg-accent border-t border-black py-4 px-8 md:px-20 text-black font-mono text-xs font-bold uppercase flex flex-col md:flex-row justify-between items-center gap-4">
                <div>©2026 CHALLORA, INC.</div>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="#" class="hover:underline">LICENSE AGREEMENT</a>
                    <a href="#" class="hover:underline">PRIVACY POLICY</a>
                    <a href="#" class="hover:underline">TERMS OF USE</a>
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