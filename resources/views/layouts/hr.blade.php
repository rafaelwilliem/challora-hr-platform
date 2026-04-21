<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'HR Panel — Challora' }}</title>
    
    <link rel="stylesheet" href="{{ asset('css/design-tokens.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
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
                    },
                },
            },
        };
    </script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
    
    <style>
        :root {
            --hr-sidebar-width: 280px;
        }

        body {
            background-color: var(--color-surface);
            color: var(--color-text);
            overflow-x: hidden;
            font-family: var(--font-sans);
        }

        /* --- Sidebar Premium --- */
        .hr-sidebar {
            width: var(--hr-sidebar-width);
            background: var(--color-primary);
            border-right: 2px solid var(--color-border);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hr-brand {
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .hr-brand-logo {
            background: var(--color-accent);
            color: var(--color-surface);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border: 2px solid black;
            box-shadow: 4px 4px 0 black;
        }

        .hr-brand-text {
            font-weight: 800;
            font-size: 22px;
            color: var(--color-text);
            letter-spacing: -1px;
            text-transform: lowercase;
        }

        .hr-nav {
            flex: 1;
            padding: 0 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .hr-nav-label {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--color-text-muted);
            padding: 24px 16px 8px;
        }

        .hr-nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            color: var(--color-text-muted);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all 0.2s;
        }

        .hr-nav-link i {
            font-size: 18px;
            transition: transform 0.2s;
        }

        .hr-nav-link:hover {
            background: var(--color-secondary);
            color: var(--color-text);
        }

        .hr-nav-link:hover i {
            transform: scale(1.1);
        }

        .hr-nav-link.active {
            background: var(--color-accent-muted);
            color: var(--color-accent);
            border: 1px solid var(--color-accent);
        }

        .hr-topbar {
            height: 80px;
            background: var(--color-surface);
            border-bottom: 2px solid var(--color-border);
            position: sticky;
            top: 0;
            left: var(--hr-sidebar-width);
            width: calc(100% - var(--hr-sidebar-width));
            z-index: 900;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hr-main-content {
            margin-left: var(--hr-sidebar-width);
            padding: 40px;
            min-height: calc(100vh - 80px);
        }

        .hr-user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            padding: 8px 16px;
            background: var(--color-secondary);
            border: 2px solid var(--color-border);
            transition: all 0.2s;
        }

        .hr-user-menu:hover {
            border-color: var(--color-accent);
        }

        .hr-avatar {
            width: 32px;
            height: 32px;
            background: var(--color-accent);
            color: var(--color-surface);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            border: 1px solid black;
        }

        @media (max-width: 1024px) {
            .hr-sidebar { transform: translateX(-100%); }
            .hr-sidebar.open { transform: translateX(0); }
            .hr-topbar { left: 0; width: 100%; padding: 0 20px; }
            .hr-main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>

<body class="antialiased">
    <div class="hr-shell">
        <aside class="hr-sidebar" id="hrSidebar">
            <a href="{{ route('hr.dashboard') }}" class="hr-brand">
                <div class="hr-brand-logo">C</div>
                <span class="hr-brand-text">challora</span>
            </a>

            <nav class="hr-nav">
                <span class="hr-nav-label">Management</span>
                <a href="{{ route('hr.dashboard') }}" class="hr-nav-link {{ request()->routeIs('hr.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i> Analytics
                </a>
                <a href="{{ route('hr.jobs.index') }}" class="hr-nav-link {{ request()->routeIs('hr.jobs.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase-fill"></i> Position Listings
                </a>
                <a href="{{ route('hr.applications.index') }}" class="hr-nav-link {{ request()->routeIs('hr.applications.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Talent Pipeline
                </a>

                <span class="hr-nav-label">Strategy</span>
                <a href="#" class="hr-nav-link">
                    <i class="bi bi-lightning-charge-fill"></i> Intelligence
                </a>
                
                <span class="hr-nav-label">System</span>
                <a href="{{ route('user.settings.edit') }}" class="hr-nav-link">
                    <i class="bi bi-gear-fill"></i> Account Settings
                </a>
                <a href="{{ route('jobs.index') }}" class="hr-nav-link">
                    <i class="bi bi-arrow-left-right"></i> Switch to Candidate
                </a>
            </nav>
        </aside>

        <div class="hr-content-wrap">
            <header class="hr-topbar">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="lg:hidden p-2 text-2xl"><i class="bi bi-list"></i></button>
                    <h1 class="text-xl font-bold tracking-tight">{{ $pageTitle ?? 'HR Intelligence' }}</h1>
                </div>

                <div class="flex items-center gap-6">
                    <button class="relative p-2 text-text-muted hover:text-accent transition-colors">
                        <i class="bi bi-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-accent rounded-full border-2 border-surface"></span>
                    </button>

                    <div class="relative group">
                        <div class="hr-user-menu" id="hrUserMenuToggle">
                            <div class="hr-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                            <span class="text-sm font-bold hidden md:block">{{ auth()->user()->name }}</span>
                            <i class="bi bi-chevron-down text-xs opacity-50"></i>
                        </div>
                        
                        <div id="hrUserDropdown" class="absolute right-0 mt-3 w-48 bg-surface border-2 border-border shadow-lg hidden z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="bi bi-box-arrow-right mr-2"></i> Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="hr-main-content">
                @if(session('flash_toast'))
                    <div class="mb-8 p-4 border-2 bg-green-50 text-green-700 border-green-200 font-bold flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-check-circle-fill text-lg"></i>
                            {{ session('flash_toast')['message'] }}
                        </div>
                        <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-8 p-4 border-2 bg-red-50 text-red-700 border-red-200 font-bold">
                        @foreach ($errors->all() as $error)
                            <div class="flex items-center gap-3">
                                <i class="bi bi-exclamation-circle-fill text-lg"></i>
                                {{ $error }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <div id="hr-view-content">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('hrUserMenuToggle');
            const dropdown = document.getElementById('hrUserDropdown');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('hrSidebar');

            if (toggle && dropdown) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdown.classList.toggle('hidden');
                    gsap.from(dropdown, { y: -10, opacity: 0, duration: 0.2, ease: "power2.out" });
                });
                document.addEventListener('click', () => dropdown.classList.add('hidden'));
            }

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
