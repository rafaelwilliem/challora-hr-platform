<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Challora</title>

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
                        <p>Bergabung dan daftar sebagai kandidat.</p>
                    </div>
                </div>

                <div class="form-wrap">
                    <h1>Daftar Akun</h1>
                    <p class="subtitle">Lengkapi data untuk mendaftar sebagai kandidat.</p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= e($error) ?></div>
                    <?php endif; ?>

                    <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/register" class="login-form">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <div class="input-box">
                                <input type="text" id="name" name="name" placeholder="Masukkan nama" required value="<?= e($old['name'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-box">
                                <input type="email" id="email" name="email" placeholder="Masukkan email" required value="<?= e($old['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">No. HP</label>
                            <div class="input-box">
                                <input type="text" id="phone" name="phone" placeholder="Masukkan nomor HP" value="<?= e($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Alamat</label>
                            <div class="input-box">
                                <textarea id="address" name="address" rows="2" placeholder="Masukkan alamat"><?= e($old['address'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password (min. 6 karakter)</label>
                            <div class="input-box">
                                <input type="password" id="password" name="password" placeholder="Masukkan password" required minlength="6">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Password</label>
                            <div class="input-box">
                                <input type="password" id="password_confirm" name="password_confirm" placeholder="Konfirmasi password" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-login">Daftar</button>
                        <div class="extra-links">
                            <p>Sudah punya akun? <a href="<?= BASE_URL ?>/auth/login">Login</a></p>
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
