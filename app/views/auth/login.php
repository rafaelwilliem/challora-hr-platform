<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Challora</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login-modern.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="auth-body">
    <div class="login-page">
        <div class="login-card">
            <div class="login-left">
                <div class="brand">
                    <div class="brand-badge">CV</div>
                    <div>
                        <h2>Challora</h2>
                        <p>Halo! Silahkan login untuk melanjutkan.</p>
                    </div>
                </div>

                <div class="form-wrap">
                    <h1>Masuk ke Akun</h1>
                    <p class="subtitle">Kelola aplikasi dengan tampilan yang nyaman dan modern.</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= e($error) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['flash'])): ?>
                        <div class="alert alert-success"><?= e($_SESSION['flash']) ?></div>
                        <?php unset($_SESSION['flash']); ?>
                    <?php endif; ?>

                    <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/login" class="login-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-box">
                                <input type="email" id="email" name="email" placeholder="Masukkan email" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-box">
                                <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="form-row">
                            <a href="<?= BASE_URL ?>/auth/forgot" class="forgot-link">Lupa password?</a>
                        </div>
                        <button type="submit" class="btn-login">Login</button>
                        <div class="extra-links">
                            <p>Belum punya akun? <a href="<?= BASE_URL ?>/auth/register">Register</a></p>
                        </div>
                    </form>
                </div>
            </div>

            <div class="login-right">
                <div class="overlay"></div>
                <div class="right-content">
                    <span class="mini-badge">HR & Recruitment Platform</span>
                    <h2>Temukan kandidat terbaik dengan sistem yang efisien</h2>
                    <p>Kelola data pelamar, lowongan, dan proses seleksi dalam satu dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
