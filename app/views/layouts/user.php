<?php require APP_PATH . '/views/layouts/header.php'; ?>
<?php
$activeUserPath = currentRoutePath('jobs');
?>
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

<div class="min-h-screen bg-surface flex flex-col">
    <header class="user-nav">
        <a href="<?= BASE_URL ?>/jobs" class="nav-brand">
            <div class="nav-brand-logo">C</div>
            <span class="nav-brand-text">challora</span>
        </a>

        <nav class="nav-links">
            <a href="<?= BASE_URL ?>/jobs"
                class="nav-link <?= str_starts_with($activeUserPath, 'jobs') && !str_contains($activeUserPath, 'saved') ? 'active' : '' ?>">Job
                Listings</a>
            <a href="<?= BASE_URL ?>/applications"
                class="nav-link <?= str_contains($activeUserPath, 'applications') ? 'active' : '' ?>">Applied Jobs</a>
            <a href="<?= BASE_URL ?>/jobs/saved"
                class="nav-link <?= str_contains($activeUserPath, 'jobs/saved') ? 'active' : '' ?>">Saved Board</a>
        </nav>

        <div class="user-actions">
            <?php if (isLoggedIn() && currentRole() === 'user'): ?>
                <div style="position: relative; display: inline-block;" id="user-menu-root">
                    <button type="button" id="user-menu-toggle" class="profile-trigger" style="display: flex; align-items: center; gap: 8px; background: none; border: none; cursor: pointer; color: inherit; padding: 0;">
                        <div class="avatar-pill">
                            <?= e(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="font-bold text-sm hidden md:block"><?= e($_SESSION['user_name'] ?? 'Account') ?></span>
                        <svg width="12" height="12" class="text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- Ultra Premium Brutalist Dropdown -->
                    <div id="user-menu-dropdown"
                        class="absolute z-50 overflow-hidden rounded-none transition-all"
                        style="display: none; position: absolute; top: 100%; right: 0; margin-top: 12px; width: 260px; border: 4px solid black; background: #111111; box-shadow: 8px 8px 0 0 black;">
                        <div class="px-5 py-4 border-b-4 border-black bg-secondary">
                            <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em] mb-1">Signed in as</p>
                            <p class="text-sm font-black truncate text-accent"><?= e($_SESSION['user_email'] ?? '') ?></p>
                        </div>
                        <!-- Navigation Links -->
                        <div class="menu-items-group" style="display: flex; flex-direction: column;">
                            <a href="<?= BASE_URL ?>/user/settings"
                                class="flex items-center gap-3 px-5 py-4 text-sm font-black border-b-4 border-black hover:bg-black hover:text-white transition-all group"
                                style="text-decoration: none !important; color: #ffffff !important; display: flex; align-items: center; border-bottom: 4px solid black; background: transparent;">
                                <svg width="18" height="18" class="group-hover:rotate-90 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg> 
                                <span style="letter-spacing: 0.5px;">Account Settings</span>
                            </a>
                        </div>
                        <?php if (currentRole() === 'hr' || (isset($_SESSION['user_roles']) && in_array('hr', $_SESSION['user_roles']))): ?>
                            <a href="<?= BASE_URL ?>/hr/jobs"
                                class="flex items-center gap-3 px-5 py-4 text-sm font-black border-b-4 border-black hover:bg-black hover:text-white transition-all group"
                                style="text-decoration: none !important; color: #ffffff !important; display: flex; align-items: center; border-bottom: 4px solid black; background: transparent;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink: 0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg> 
                                <span style="letter-spacing: 0.5px;">Switch to HR Panel</span>
                            </a>
                        <?php endif; ?>
                        <div>
                            <form method="POST" action="<?= BASE_URL ?>/auth/logout">
                                <button type="submit"
                                    class="w-full flex items-center justify-between px-5 py-4 text-xs font-black uppercase tracking-[0.2em] bg-accent text-primary hover:bg-black hover:text-accent transition-all group/logout"
                                    style="border: none; cursor: pointer;">
                                    <span>Terminate Session</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login"
                    class="bg-accent text-surface px-6 py-2 font-black uppercase tracking-tighter border-2 border-black shadow-flat hover:shadow-raised transition-all">Sign
                    In</a>
            <?php endif; ?>
        </div>
    </header>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php
        $flashType = $_SESSION['flash_type'] ?? 'success';
        $isError = $flashType === 'error';
        ?>
        <div class="mx-10 mt-8 flex items-center justify-between p-4 border-4 border-black shadow-[6px_6px_0_0_black] <?= $isError ? 'bg-danger-bg text-danger-text' : 'bg-success-bg text-success-text' ?> font-bold"
            id="flash-alert">
            <div class="flex items-center gap-3">
                <svg width="24" height="24" class="<?= $isError ? 'text-danger-text' : 'text-success-text' ?>" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="tracking-tight uppercase"><?= e($_SESSION['flash']) ?></span>
            </div>
            <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100 transition-opacity bg-black text-white p-1 rounded-sm">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <?php unset($_SESSION['flash'], $_SESSION['flash_type']); ?>
    <?php endif; ?>

    <main class="main-container flex-1">
        <?= $content ?? '' ?>
    </main>

    <footer class="p-10 border-t-2 border-border text-center">
        <p class="text-sm font-bold text-text-muted lowercase tracking-tight">challora
            v<?= e(defined('APP_VERSION') ? APP_VERSION : '2.2.1') ?> &nbsp;·&nbsp; no compromises in recruitment.</p>
    </footer>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('user-menu-toggle');
        const dropdown = document.getElementById('user-menu-dropdown');
        let isOpen = false;

        if (toggle && dropdown) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (!isOpen) {
                    dropdown.style.display = 'block';
                    isOpen = true;
                    gsap.fromTo(dropdown, 
                        { y: -10, opacity: 0, scale: 0.9, transformOrigin: 'top right' }, 
                        { y: 0, opacity: 1, scale: 1, duration: 0.25, ease: 'back.out(1.7)' }
                    );
                } else {
                    gsap.to(dropdown, {
                        y: -5, opacity: 0, scale: 0.95, duration: 0.15, ease: 'power2.in',
                        onComplete: () => { dropdown.style.display = 'none'; isOpen = false; }
                    });
                }
            });

            document.addEventListener('click', (e) => {
                if (isOpen && !dropdown.contains(e.target) && !toggle.contains(e.target)) {
                    gsap.to(dropdown, {
                        y: -5, opacity: 0, scale: 0.95, duration: 0.15, ease: 'power2.in',
                        onComplete: () => { dropdown.style.display = 'none'; isOpen = false; }
                    });
                }
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

<?php require APP_PATH . '/views/layouts/footer.php'; ?>