<div class="card mx-auto my-auto" style="max-width: 400px;">
    <div class="card-body">
        <h1 class="card-title h4 mb-4">Reset Password</h1>
        <?php if (!empty($error)): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <?php if (empty($error)): ?>
        <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/reset">
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <div class="mb-3">
                <label for="password" class="form-label">Password baru</label>
                <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Konfirmasi password</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required autocomplete="new-password">
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button type="submit" class="btn btn-primary">Ubah Password</button>
                <a href="<?= BASE_URL ?>/auth/login" class="btn btn-link">Kembali ke login</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>
