<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name', 'Challora') }}</title>

    <!-- Token tema (warna, font, radius) -->
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">

    <!-- TailwinCSS & GSAP -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['var(--font-sans)'],
                    },
                    borderRadius: {
                        brand: 'var(--radius-xl)',
                        'brand-lg': 'var(--radius-2xl)',
                    },
                    boxShadow: {
                        brand: 'var(--shadow-md)',
                        'brand-lg': 'var(--shadow-lg)',
                    },
                    colors: {
                        primary: {
                            DEFAULT: 'var(--color-primary)',
                            hover: 'var(--color-primary-hover)',
                            muted: 'var(--color-primary-muted)',
                        },
                        secondary: {
                            DEFAULT: 'var(--color-secondary)',
                            hover: 'var(--color-secondary-hover)',
                        },
                        accent: {
                            DEFAULT: 'var(--color-accent)',
                            hover: 'var(--color-accent-hover)',
                        },
                        surface: 'var(--color-surface)',
                        border: 'var(--color-border)',
                        text: {
                            DEFAULT: 'var(--color-text)',
                            muted: 'var(--color-text-muted)',
                        }
                    },
                },
            },
        };
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;700;800&display=swap"
        rel="stylesheet">

    @stack('styles')

    <style>
        :root {
            --nav-height: 80px;
        }

        /* --- Image-Matched Horizontal Navbar --- */
        .brutal-header {
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--nav-height);
            background: #000000;
            border-bottom: 2px solid #1a1a1a;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px; /* Internal padding for header content */
        }

        .brand-accent-box {
            height: 100%;
            background: var(--color-accent);
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-right: 2px solid black;
            text-decoration: none;
            gap: 12px;
        }

        .brand-logo-c {
            background: black;
            color: var(--color-accent);
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 18px;
            border: 2px solid white;
        }

        .brand-name-text {
            color: white;
            font-weight: 800;
            font-size: 24px;
            letter-spacing: -1.5px;
            text-transform: lowercase;
        }

        .nav-link-image {
            color: #999;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            text-transform: capitalize;
            transition: color 0.2s;
        }

        .nav-link-image:hover, .nav-link-image.active {
            color: white;
        }

        .signin-btn-image {
            background: var(--color-accent);
            color: white;
            padding: 10px 24px;
            font-weight: 900;
            font-size: 12px;
            text-transform: uppercase;
            text-decoration: none;
            border: none;
        }

        /* --- User Profile Trigger --- */
        .user-nav-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 16px;
            background: #111;
            border: 1px solid #333;
        }

        .nav-avatar-inner {
            width: 28px;
            height: 28px;
            background: var(--color-accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .dropdown-menu-image {
            position: absolute;
            top: 70px;
            right: 24px;
            background: #111;
            border: 1px solid #333;
            width: 200px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
            z-index: 1100;
        }

        .dropdown-link-image {
            display: block;
            padding: 12px 20px;
            color: #aaa;
            font-weight: 700;
            text-decoration: none;
            font-size: 13px;
            border-bottom: 1px solid #222;
        }

        .dropdown-link-image:hover { background: #222; color: white; }

        .main-container {
            width: 100%;
            min-height: calc(100vh - var(--nav-height));
            max-width: 1600px;
            margin: 0 auto;
            padding: 60px 80px; /* Desired horizontal padding */
        }
    </style>
</head>

<body class="antialiased bg-primary text-text font-sans">
    <div class="min-h-screen bg-surface flex flex-col">
        <!-- Image Matched Navbar -->
        <header class="brutal-header">
            <div class="flex h-full items-center">
                <a href="{{ route('landing') }}" class="brand-accent-box">
                    <div class="brand-logo-c">C</div>
                    <span class="brand-name-text">challora</span>
                </a>

                <nav class="ml-12 hidden md:flex items-center gap-8">
                    <a href="{{ route('jobs.index') }}" class="nav-link-image {{ request()->routeIs('jobs.*') && !request()->routeIs('user.jobs.saved') ? 'active' : '' }}">
                        Job Listings
                    </a>
                    <a href="{{ route('user.applications.index') }}" class="nav-link-image {{ request()->routeIs('user.applications.*') ? 'active' : '' }}">
                        Applied Jobs
                    </a>
                    <a href="{{ route('user.jobs.saved') }}" class="nav-link-image {{ request()->routeIs('user.jobs.saved') ? 'active' : '' }}">
                        Saved Board
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-6 pr-4">
                @auth
                    <div class="relative">
                        <div class="user-nav-trigger" id="user-menu-toggle">
                            <div class="nav-avatar-inner">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="text-xs font-bold text-white uppercase hidden sm:block">{{ auth()->user()->name }}</span>
                            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="opacity-50">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        <div id="user-menu-dropdown" class="dropdown-menu-image hidden">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('hr.dashboard') }}" class="dropdown-link-image">Switch to HR</a>
                            @endif
                            <a href="{{ route('user.settings.edit') }}" class="dropdown-link-image">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-link-image w-full text-left font-bold text-red-500">Sign Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="signin-btn-image">
                        SIGN IN
                    </a>
                @endauth
            </div>
        </header>

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

        <footer class="p-10 border-t-2 border-border text-center">
            <p class="text-sm font-bold text-text-muted lowercase tracking-tight">challora
                v2.2.2 &nbsp;·&nbsp; no compromises in recruitment.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('user-menu-toggle');
            const dropdown = document.getElementById('user-menu-dropdown');

            if (toggle && dropdown) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        gsap.fromTo(dropdown, { y: -10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.2, ease: "power2.out" });
                    } else {
                        dropdown.classList.add('hidden');
                    }
                });

                document.addEventListener('click', () => {
                    dropdown.classList.add('hidden');
                });
            }

            // Entrance animation for main content
            gsap.from(".main-container", {
                opacity: 0,
                y: 20,
                duration: 0.8,
                ease: "power3.out"
            });
        });
    </script>
    @stack('scripts')
</body>

</html>