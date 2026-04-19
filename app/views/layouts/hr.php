<?php require APP_PATH . '/views/layouts/header.php'; ?>
<?php
$activeHrMenu = currentRoutePath('hr/jobs');
$activeHrKey = 'dashboard';
if (str_starts_with($activeHrMenu, 'hr/applications/accepted')) {
    $activeHrKey = 'accepted';
} elseif ($activeHrMenu === 'hr/applications') {
    $activeHrKey = 'review';
} elseif (str_starts_with($activeHrMenu, 'hr/jobs/create')) {
    $activeHrKey = 'recruitment';
} elseif (str_starts_with($activeHrMenu, 'hr/jobs/applicants')) {
    $activeHrKey = 'applicants';
}
$hrUserName = $_SESSION['user_name'] ?? 'HR';
$initials = strtoupper(substr($hrUserName, 0, 1));
?>
<style>
    :root {
        --hr-sidebar-width: 280px;
    }

    body {
        background-color: var(--color-surface);
        color: var(--color-text);
        overflow-x: hidden;
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

    .hr-nav-link.active i {
        color: var(--color-accent);
    }

    /* --- Topbar --- */
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

    /* --- Responsive --- */
    @media (max-width: 1024px) {
        .hr-sidebar { transform: translateX(-100%); }
        .hr-sidebar.open { transform: translateX(0); }
        .hr-topbar { left: 0; width: 100%; padding: 0 20px; }
        .hr-main-content { margin-left: 0; padding: 20px; }
    }
</style>

<div class="hr-shell">
    <aside class="hr-sidebar" id="hrSidebar">
        <a href="<?= BASE_URL ?>/hr/jobs" class="hr-brand">
            <div class="hr-brand-logo">C</div>
            <span class="hr-brand-text">challora</span>
        </a>

        <nav class="hr-nav">
            <span class="hr-nav-label">Management</span>
            <a href="<?= BASE_URL ?>/hr/dashboard" class="hr-nav-link <?= str_contains($activeHrMenu, 'dashboard') ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Analytics
            </a>
            <a href="<?= BASE_URL ?>/hr/jobs" class="hr-nav-link <?= $activeHrKey === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-briefcase-fill"></i> Position Listings
            </a>
            <a href="<?= BASE_URL ?>/hr/applications" class="hr-nav-link <?= $activeHrKey === 'review' ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Talent Pipeline
            </a>

            <span class="hr-nav-label">Strategy</span>
            <a href="<?= BASE_URL ?>/hr/analytics" class="hr-nav-link <?= str_contains($activeHrMenu, 'analytics') ? 'active' : '' ?>">
                <i class="bi bi-lightning-charge-fill"></i> Intelligence
            </a>
            
            <span class="hr-nav-label">System</span>
            <a href="<?= BASE_URL ?>/user/settings" class="hr-nav-link">
                <i class="bi bi-gear-fill"></i> Account Settings
            </a>
            <a href="<?= BASE_URL ?>/jobs" class="hr-nav-link">
                <i class="bi bi-arrow-left-right"></i> Switch to Candidate
            </a>
        </nav>
    </aside>

    <div class="hr-content-wrap">
        <header class="hr-topbar">
            <div class="flex items-center gap-4">
                <button id="sidebarToggle" class="lg:hidden p-2 text-2xl"><i class="bi bi-list"></i></button>
                <h1 class="text-xl font-bold tracking-tight"><?= e($pageTitle ?? 'HR Intelligence') ?></h1>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications (Stub) -->
                <button class="relative p-2 text-text-muted hover:text-accent transition-colors">
                    <i class="bi bi-bell text-xl"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-accent rounded-full border-2 border-surface"></span>
                </button>

                <div class="relative group">
                    <div class="hr-user-menu" id="hrUserMenuToggle">
                        <div class="hr-avatar"><?= e($initials) ?></div>
                        <span class="text-sm font-bold hidden md:block"><?= e($hrUserName) ?></span>
                        <i class="bi bi-chevron-down text-xs opacity-50"></i>
                    </div>
                    
                    <div id="hrUserDropdown" class="absolute right-0 mt-3 w-48 bg-surface-raised border-2 border-border shadow-lg hidden">
                        <a href="<?= BASE_URL ?>/auth/logout" class="block px-4 py-3 text-sm font-bold text-danger-text hover:bg-danger-bg transition-colors">
                            <i class="bi bi-box-arrow-right mr-2"></i> Log Out
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="hr-main-content">
            <?php if (!empty($_SESSION['flash'])): ?>
                <?php $isErr = ($_SESSION['flash_type'] ?? '') === 'error'; ?>
                <div class="mb-8 p-4 border-2 <?= $isErr ? 'bg-danger-bg text-danger-text border-danger-text/20' : 'bg-success-bg text-success-text border-success-text/20' ?> font-bold flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="bi <?= $isErr ? 'bi-exclamation-circle-fill' : 'bi-check-circle-fill' ?> text-lg"></i>
                        <?= e($_SESSION['flash']) ?>
                    </div>
                    <button onclick="this.parentElement.remove()" class="opacity-50 hover:opacity-100"><i class="bi bi-x-lg"></i></button>
                </div>
                <?php unset($_SESSION['flash'], $_SESSION['flash_type']); ?>
            <?php endif; ?>

            <div id="hr-view-content">
                <?= $content ?? '' ?>
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

<?php require APP_PATH . '/views/layouts/footer.php'; ?>
