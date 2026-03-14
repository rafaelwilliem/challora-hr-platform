<?php require APP_PATH . '/views/layouts/header.php'; ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>"><?= e($siteName ?? 'Challora Recruitment Platform') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isLoggedIn() && currentRole() === 'hr'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/hr/jobs">Dashboard HR</a></li>
                <?php elseif (isLoggedIn() && currentRole() === 'user'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/jobs">Lowongan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/jobs/saved">Tersimpan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/applications">Yang sudah dilamar</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <?php if (currentRole() === 'user'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?= e($_SESSION['user_name'] ?? 'User') ?></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/user/settings">Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/logout">Log out</a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item"><a class="nav-link disabled"><?= e($_SESSION['user_name'] ?? 'User') ?></a></li>
                    <div class="dropdown-divider"></div>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/logout">Logout</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/register">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?php if (!empty($_SESSION['flash']) && empty($_SESSION['flash_toast'])): ?>
        <div class="alert alert-success"><?= e($_SESSION['flash']) ?></div>
        <?php unset($_SESSION['flash']); ?>
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
<?php if (isLoggedIn() && currentRole() === 'user' && !empty($_SESSION['flash_toast'])): ?>
<?php
$toast = $_SESSION['flash_toast'];
unset($_SESSION['flash_toast']);
?>
<div id="toast-user" class="toast-user" role="alert">
    <div class="toast-user-inner">
        <span class="toast-user-msg"><?= e($toast['message']) ?></span>
        <div class="toast-user-actions">
            <?php if (!empty($toast['undo'])): ?>
            <form id="toast-undo-form" method="post" action="<?= e($toast['undo']['url']) ?>" class="d-inline">
                <?php foreach ($toast['undo']['fields'] ?? [] as $k => $v): ?>
                <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
                <?php endforeach; ?>
                <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none fw-bold toast-undo-btn"><?= e($toast['undo']['label'] ?? 'Undo') ?></button>
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
    background: #212529;
    color: #fff;
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
    color: #fff !important;
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
<?php require APP_PATH . '/views/layouts/footer.php'; ?>
