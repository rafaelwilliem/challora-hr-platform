<?php require APP_PATH . '/views/layouts/header.php'; ?>
<?php $activeUserPath = currentRoutePath('jobs'); ?>
<style>
.user-shell{min-height:100vh;background:var(--color-primary);--user-content-pad-x:10vw;--user-bar-pad-x:5vw;display:flex;flex-direction:column;}
.user-topnav{background:var(--color-secondary);color:var(--color-on-secondary);border-bottom:1px solid rgba(255,255,255,.28);}
.user-topnav-inner{width:100%;padding:0 var(--user-bar-pad-x);min-height:84px;display:flex;align-items:stretch;justify-content:space-between;gap:22px;}
.user-brand{display:flex;align-items:center;font-size:28px;font-weight:700;line-height:1;color:var(--color-primary);text-decoration:none;letter-spacing:.4px;}
.user-mainnav{display:flex;align-items:stretch;gap:8px;flex-grow:1;}
.user-mainnav-link{display:flex;align-items:center;padding:0 22px;color:#edf3f8;text-decoration:none;font-weight:400;font-size:18px;border-bottom:5px solid transparent;}
.user-mainnav-link.active{border-bottom-color:#fff;font-weight:600;}
.user-mainnav-link:hover{color:#fff;background:rgba(255,255,255,.04);}
.user-avatar-btn{width:40px;height:40px;border-radius:999px;background:#fff;color:var(--color-secondary);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;border:none;}
.user-main-wrap{width:100%;padding:16px var(--user-content-pad-x) 24px;flex:1 0 auto;}
#user-menu-root{position:relative;z-index:9998;}
#user-menu-dropdown{z-index:9999 !important;}
.user-footer{margin-top:24px;border-top:1px solid color-mix(in srgb,var(--color-secondary) 15%,transparent);background:var(--color-secondary);}
.user-footer-inner{width:100%;padding:16px var(--user-content-pad-x);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
.user-footer-copy{font-size:13px;color:var(--color-text-muted);}
.user-footer-copy strong{color:var(--color-accent);font-weight:700;}
.user-footer-links{display:flex;align-items:center;gap:16px;flex-wrap:wrap;}
.user-footer-link{font-size:13px;color:var(--color-accent);text-decoration:none;transition:color .2s ease;}
.user-footer-link:hover{color:var(--color-accent-hover);text-decoration:underline;}
@media (max-width:992px){
    .user-shell{--user-content-pad-x:6vw;--user-bar-pad-x:4vw;}
}
@media (max-width:768px){
    .user-footer-inner{justify-content:center;text-align:center;}
}
</style>
<div class="user-shell">
<header class="user-topnav">
    <div class="user-topnav-inner">
        <a href="<?= BASE_URL ?>/jobs" class="user-brand">CHALLORA</a>
        <nav class="user-mainnav flex-grow-1">
            <a class="user-mainnav-link ml-20 <?= str_starts_with($activeUserPath, 'jobs') && !str_starts_with($activeUserPath, 'jobs/saved') ? 'active' : '' ?>" href="<?= BASE_URL ?>/jobs">Lowongan</a>
            <a class="user-mainnav-link <?= str_contains($activeUserPath, 'applications') ? 'active' : '' ?>" href="<?= BASE_URL ?>/applications">Sudah Dilamar</a>
            <a class="user-mainnav-link <?= str_starts_with($activeUserPath, 'jobs/saved') ? 'active' : '' ?>" href="<?= BASE_URL ?>/jobs/saved">Lowongan Tersimpan</a>
        </nav>
        <div class="flex items-center gap-3">
            <?php if (isLoggedIn() && currentRole() === 'user'): ?>
                <?php
                $initial = mb_strtoupper(mb_substr($_SESSION['user_name'] ?? 'U', 0, 1));
                $headerAvatarSrc = currentUserAvatarImgSrc();
                ?>
                <div class="relative" id="user-menu-root">
                    <button type="button" id="user-menu-toggle" class="user-avatar-btn">
                        <?php if ($headerAvatarSrc): ?>
                            <img src="<?= e($headerAvatarSrc) ?>" alt="" class="w-full h-full rounded-full object-cover" width="40" height="40">
                        <?php else: ?>
                            <?= e($initial) ?>
                        <?php endif; ?>
                    </button>
                    <div
                        id="user-menu-dropdown"
                        class="absolute right-0 mt-2 w-40 bg-surface rounded-xl shadow-lg border border-muted py-1 text-xs text-muted hidden"
                    >
                        <a href="<?= BASE_URL ?>/user/settings" class="block px-3 py-2 hover:bg-muted">
                            Pengaturan user
                        </a>
                        <a href="<?= BASE_URL ?>/auth/logout" class="block px-3 py-2 hover:bg-muted">
                            Logout
                        </a>
                    </div>
                </div>
            <?php elseif (!isLoggedIn()): ?>
                <a href="<?= BASE_URL ?>/auth/login" class="text-xs text-muted hover:text-default">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>
<main class="user-main-wrap">

    <?php if (!empty($_SESSION['flash']) && empty($_SESSION['flash_toast'])): ?>
        <?php
        $flashType = (string) ($_SESSION['flash_type'] ?? 'success');
        $flashClass = $flashType === 'error' ? 'danger' : ($flashType === 'info' ? 'info' : 'success');
        ?>
        <div class="alert alert-<?= e($flashClass) ?>"><?= e($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
        <?php unset($_SESSION['flash_type']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= e($_SESSION['flash_error']) ?></div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>
    <?php if (isLoggedIn() && currentRole() === 'user' && !($hideProfileBar ?? false) && !isProfileComplete()): ?>
        <div class="alert alert-warning d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <span>Datamu belum dilengkapi, lengkapi agar HR semakin yakin untuk menerima mu.</span>
            <a href="<?= BASE_URL ?>/user/settings/edit" class="btn btn-warning btn-sm">Lengkapi Data</a>
        </div>
    <?php endif; ?>
    <?= $content ?? '' ?>
</main>
<footer class="user-footer" aria-label="Footer user">
    <div class="user-footer-inner">
        <p class="user-footer-copy mb-0">
            <strong>CHALLORA</strong> &copy; <?= date('Y') ?>. Semua hak dilindungi.
        </p>
        <nav class="user-footer-links" aria-label="Tautan cepat">
            <a class="user-footer-link" href="<?= BASE_URL ?>/jobs">Lowongan</a>
            <a class="user-footer-link" href="<?= BASE_URL ?>/applications">Lamaran</a>
            <a class="user-footer-link" href="<?= BASE_URL ?>/jobs/saved">Tersimpan</a>
        </nav>
    </div>
</footer>
</div>
<?php if (isLoggedIn() && currentRole() === 'user' && !empty($_SESSION['flash_toast'])): ?>
<?php
$toast = $_SESSION['flash_toast'];
unset($_SESSION['flash_toast']);
$toastMessage = is_array($toast) ? (string) ($toast['message'] ?? '') : '';
$toastUndo = is_array($toast) && !empty($toast['undo']) && is_array($toast['undo']) ? $toast['undo'] : null;
?>
<?php if ($toastMessage !== ''): ?>
<div id="toast-user" class="toast-user" role="alert">
    <div class="toast-user-inner">
        <span class="toast-user-msg"><?= e($toastMessage) ?></span>
        <div class="toast-user-actions">
            <?php if (!empty($toastUndo['url'])): ?>
            <form id="toast-undo-form" method="post" action="<?= e((string) $toastUndo['url']) ?>" class="d-inline">
                <?php foreach (($toastUndo['fields'] ?? []) as $k => $v): ?>
                <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
                <?php endforeach; ?>
                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold toast-undo-btn"><?= e((string) ($toastUndo['label'] ?? 'Undo')) ?></button>
            </form>
            <span class="toast-user-sep">|</span>
            <?php endif; ?>
            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold toast-close-btn" aria-label="Tutup">&times;</button>
        </div>
    </div>
</div>
<style>
.toast-user {
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    z-index: 1050;
    min-width: 280px;
    max-width: 400px;
    padding: 1rem 1.25rem;
    background: var(--color-secondary-hover);
    color: var(--color-surface);
    border-radius: 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.25);
    animation: toastSlideIn 0.3s ease-out;
}
.toast-user.toast-out {
    animation: toastSlideOut 0.3s ease-in forwards;
}
.toast-user-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.toast-user-msg {
    flex: 1;
}
.toast-user-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-shrink: 0;
}
.toast-user-sep {
    opacity: 0.6;
}
.toast-close-btn, .toast-undo-btn {
    color: var(--color-surface) !important;
    text-decoration: none !important;
    font-weight: bold;
    opacity: 0.9;
}
.toast-close-btn:hover, .toast-undo-btn:hover {
    opacity: 1;
}
@keyframes toastSlideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}
@keyframes toastSlideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
</style>
<script>
(function() {
    var toast = document.getElementById('toast-user');
    if (!toast) return;
    var close = toast.querySelector('.toast-close-btn');
    var undoForm = document.getElementById('toast-undo-form');
    function dismiss() {
        toast.classList.add('toast-out');
        setTimeout(function() { toast.remove(); }, 300);
    }
    if (close) close.addEventListener('click', dismiss);
    setTimeout(dismiss, 5000);
})();
</script>
<?php endif; ?>
<?php endif; ?>
<script>
// simple user dropdown (selalu aktif untuk user)
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('user-menu-toggle');
    var dropdown = document.getElementById('user-menu-dropdown');
    if (!toggle || !dropdown) return;
    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
    });
    document.addEventListener('click', function () {
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
