<header class="brutal-header">
    <div class="flex h-full items-center">
        <a href="{{ auth()->check() && auth()->user()->isAdmin() ? route('hr.dashboard') : route('landing') }}"
            class="brand-accent-box">
            <div class="brand-logo-c">C</div>
            <span class="brand-name-text font-sans font-extrabold tracking-tight">challora</span>
        </a>

        <nav class="ml-10 hidden md:flex items-center gap-7">
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('hr.dashboard') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->is('hr/dashboard*') ? 'active' : '' }}">Analytics</a>
                <a href="{{ route('hr.jobs.index') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->is('hr/jobs*') ? 'active' : '' }}">Positions</a>
                <a href="{{ route('hr.applications.index') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->is('hr/applications*') ? 'active' : '' }}">Pipelines</a>
                <a href="{{ route('hr.intelligence') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->is('hr/intelligence*') ? 'active' : '' }}">Intelligence</a>
            @else
                <a href="{{ route('jobs.index') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ (request()->is('jobs*') || request()->routeIs('landing')) && !request()->routeIs('user.jobs.saved') ? 'active' : '' }}">Jobs</a>
                <a href="{{ route('user.applications.index') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->is('user/applications*') ? 'active' : '' }}">Applied</a>
                <a href="{{ route('user.jobs.saved') }}" class="nav-link-image c-mono text-[11px] uppercase tracking-widest {{ request()->routeIs('user.jobs.saved') ? 'active' : '' }}">Saved</a>
                <a href="{{ route('landing') }}#capabilities" class="nav-link-image c-mono text-[11px] uppercase tracking-widest text-[#777] hover:text-white">Architecture</a>
            @endif
        </nav>
    </div>

    <!-- Right Controls (Ryoku-Style Badges & Actions) -->
    <div class="flex items-center gap-3 pr-2">
        <!-- Ryoku-style Version & Star Badges (Hidden on mobile) -->
        <div class="hidden lg:flex items-center gap-2 mr-2">
            <span class="c-mono text-[10px] text-gray-300 bg-[#111] border border-[#2a2a2a] px-2.5 py-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-[#FF4D30]"></span> v5.1.0
            </span>
            <span class="c-mono text-[10px] text-gray-300 bg-[#111] border border-[#2a2a2a] px-2.5 py-1 flex items-center gap-1">
                <span class="text-[#FF4D30]">★</span> 2.4k
            </span>
        </div>

        @auth
            <div class="relative hidden md:block">
                <div class="user-nav-trigger" id="user-menu-toggle">
                    <div class="nav-avatar-inner">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <span class="c-mono text-xs font-bold text-white uppercase hidden sm:block">{{ auth()->user()->name }}</span>
                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div id="user-menu-dropdown" class="dropdown-menu-image hidden">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('hr.dashboard') }}" class="dropdown-link-image">HR Dashboard</a>
                        <a href="{{ route('jobs.index') }}" class="dropdown-link-image">Candidate View</a>
                    @else
                        <a href="{{ route('user.settings.index') }}" class="dropdown-link-image">Settings</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-link-image w-full text-left font-bold text-accent-800">Sign Out</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('jobs.index') }}" class="hidden sm:inline-flex c-mono text-[11px] font-bold text-gray-300 border border-[#333] hover:border-gray-400 px-3.5 py-2 uppercase tracking-wider transition-colors">
                DOCS
            </a>
            <a href="{{ route('login') }}" class="signin-btn-image c-mono text-[11px] font-bold tracking-wider hidden md:inline-flex">
                SIGN IN
            </a>
        @endauth

        <!-- Hamburger (mobile only) -->
        <button id="mobile-menu-toggle" class="md:hidden flex flex-col justify-center gap-1.5 p-2 z-50" aria-label="Menu">
            <span class="block w-6 h-0.5 bg-accent"></span>
            <span class="block w-6 h-0.5 bg-accent"></span>
            <span class="block w-6 h-0.5 bg-accent"></span>
        </button>
    </div>
</header>

<!-- Mobile Menu Drawer -->
<div id="mobile-menu" class="fixed inset-0 z-[9999] bg-black flex flex-col p-8 pt-20 hidden">
    <button id="mobile-menu-close" class="absolute top-5 right-5 text-accent-500 font-black text-3xl leading-none">✕</button>
    <nav class="flex flex-col gap-6 text-2xl font-black uppercase text-white mt-4 font-mono">
        @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('hr.dashboard') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Analytics</a>
            <a href="{{ route('hr.jobs.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Positions</a>
            <a href="{{ route('hr.applications.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Pipelines</a>
            <a href="{{ route('hr.intelligence') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Intelligence</a>
        @elseif(auth()->check())
            <a href="{{ route('jobs.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Job Listings</a>
            <a href="{{ route('user.applications.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Applied Jobs</a>
            <a href="{{ route('user.jobs.saved') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Saved Board</a>
            <a href="{{ route('user.settings.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Settings</a>
        @else
            <a href="{{ route('jobs.index') }}" class="hover:text-accent transition-colors border-b border-white/10 pb-4">Job Listings</a>
            <a href="{{ route('login') }}" class="text-center border border-accent text-accent bg-transparent font-bold uppercase py-4 px-6">Sign In</a>
            <a href="{{ route('register') }}" class="text-center border border-accent text-black bg-accent font-bold uppercase py-4 px-6">Register</a>
        @endif
        @auth
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="text-accent font-bold uppercase text-2xl">Sign Out</button>
        </form>
        @endauth
    </nav>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Desktop dropdown
            const toggle = document.getElementById('user-menu-toggle');
            const dropdown = document.getElementById('user-menu-dropdown');
            if (toggle && dropdown) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    if (dropdown.classList.contains('hidden')) {
                        dropdown.classList.remove('hidden');
                        if (window.gsap) {
                            gsap.fromTo(dropdown, { y: -10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.2, ease: "power2.out" });
                        }
                    } else {
                        dropdown.classList.add('hidden');
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

            // Mobile menu
            const mToggle = document.getElementById('mobile-menu-toggle');
            const mClose = document.getElementById('mobile-menu-close');
            const mMenu = document.getElementById('mobile-menu');
            if (mToggle && mClose && mMenu) {
                mToggle.addEventListener('click', () => {
                    mMenu.classList.remove('hidden');
                });
                mClose.addEventListener('click', () => {
                    mMenu.classList.add('hidden');
                });
            }
        });
    </script>
@endpush
