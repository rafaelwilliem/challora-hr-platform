<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($pageTitle ?? config('app.name', 'Challora')); ?></title>

    <!-- Token tema (warna, font, radius) -->
    <link rel="stylesheet" href="<?php echo e(asset('css/design-tokens.css')); ?>">

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <?php echo $__env->yieldPushContent('styles'); ?>

    <style>
        .user-nav {
            background: var(--color-surface);
            border-bottom: 2px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0 40px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--color-text);
            transition: transform 0.2s ease;
        }

        .nav-brand:hover {
            transform: scale(1.02);
        }

        .nav-brand-logo {
            background: var(--color-accent);
            color: var(--color-surface);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 20px;
            border: 2px solid black;
            box-shadow: 4px 4px 0 black;
        }

        .nav-brand-text {
            font-weight: 800;
            letter-spacing: -1px;
            font-size: 24px;
            text-transform: lowercase;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
        }

        .nav-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-muted);
            text-decoration: none;
            position: relative;
            padding: 8px 0;
            transition: color 0.2s;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--color-text);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--color-accent);
            box-shadow: 0 0 10px var(--color-accent-muted);
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 12px;
            border: 2px solid transparent;
            transition: all 0.2s;
            cursor: pointer;
        }

        .profile-trigger:hover {
            background: var(--color-secondary);
            border-color: var(--color-border);
        }

        .avatar-pill {
            width: 32px;
            height: 32px;
            background: var(--color-accent-muted);
            color: var(--color-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            border: 1px solid var(--color-accent);
            overflow: hidden;
        }

        .main-container {
            min-height: 100vh;
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .user-nav {
                padding: 0 20px;
            }

            .nav-links {
                display: none;
            }
        }
    </style>
</head>

<body class="antialiased bg-primary text-text font-sans" style="min-height: 100vh;">
    <div class="min-h-screen bg-surface flex flex-col">
        <header class="user-nav">
            <a href="<?php echo e(route('jobs.index')); ?>" class="nav-brand">
                <div class="nav-brand-logo">C</div>
                <span class="nav-brand-text">challora</span>
            </a>

            <nav class="nav-links">
                <a href="<?php echo e(route('jobs.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('jobs.*') && !request()->routeIs('user.jobs.saved') ? 'active' : ''); ?>">Job
                    Listings</a>
                <a href="<?php echo e(route('user.applications.index')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('user.applications.*') ? 'active' : ''); ?>">Applied Jobs</a>
                <a href="<?php echo e(route('user.jobs.saved')); ?>"
                    class="nav-link <?php echo e(request()->routeIs('user.jobs.saved') ? 'active' : ''); ?>">Saved Board</a>
            </nav>

            <div class="user-actions">
                <?php if(auth()->guard()->check()): ?>
                    <div class="relative group" id="user-menu-root">
                        <button type="button" id="user-menu-toggle" class="profile-trigger">
                            <div class="avatar-pill">
                                <?php if(auth()->user()->avatar_path): ?>
                                    <img src="<?php echo e(route('avatar')); ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                                <?php endif; ?>
                            </div>
                            <span class="font-bold text-sm hidden md:block"><?php echo e(auth()->user()->name); ?></span>
                            <svg width="12" height="12" class="text-text-muted" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div id="user-menu-dropdown"
                            class="absolute right-0 mt-4 w-60 bg-surface border-4 border-black shadow-[8px_8px_0_0_black] hidden z-50 overflow-hidden rounded-none transition-all">
                            <div class="px-5 py-4 border-b-4 border-black bg-secondary">
                                <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-1">Signed in
                                    as</p>
                                <p class="text-sm font-black truncate text-accent"><?php echo e(auth()->user()->email); ?></p>
                            </div>
                            <a href="<?php echo e(route('user.settings.edit')); ?>"
                                class="flex items-center gap-3 px-5 py-4 text-sm font-bold border-b-4 border-black hover:bg-black hover:text-surface transition-colors group">
                                <svg width="16" height="16" class="group-hover:rotate-90 transition-transform duration-300"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Account Settings</span>
                            </a>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <a href="<?php echo e(route('hr.dashboard')); ?>"
                                    class="flex items-center gap-3 px-5 py-4 text-sm font-bold border-b-4 border-black hover:bg-black hover:text-surface transition-colors">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Switch to HR Panel</span>
                                </a>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-5 py-4 text-sm font-black text-red-600 hover:bg-red-600 hover:text-white transition-colors bg-surface">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <span class="uppercase tracking-widest">Terminate Session</span>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>"
                        class="bg-accent text-surface px-6 py-2 font-black uppercase tracking-tighter border-2 border-black shadow-[4px_4px_0_0_black] hover:shadow-[2px_2px_0_0_black] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">Sign
                        In</a>
                <?php endif; ?>
            </div>
        </header>

        <?php if(session('flash_toast')): ?>
            <div class="mx-10 mt-8 flex items-center justify-between p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-success-bg text-success-text font-bold"
                id="flash-alert">
                <div class="flex items-center gap-3">
                    <svg width="24" height="24" class="text-success-text" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="tracking-tight uppercase"><?php echo e(session('flash_toast')['message']); ?></span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="opacity-50 hover:opacity-100 transition-opacity bg-black text-white p-1 rounded-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div
                class="mx-10 mt-8 flex flex-col p-4 border-4 border-black shadow-[6px_6px_0_0_black] bg-red-100 text-red-700 font-bold">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="tracking-tight uppercase"><?php echo e($error); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <main class="main-container flex-1">
            <?php echo $__env->yieldContent('content'); ?>
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\challorav2\resources\views/layouts/app.blade.php ENDPATH**/ ?>