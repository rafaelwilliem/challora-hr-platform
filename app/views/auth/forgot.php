<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ChalloraV2</title>

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
                    <p>Password Recovery</p>
                </div>
            </div>

            <div class="form-wrap">

                <h1>Lupa Password</h1>
                <p class="subtitle">
                    Masukkan email anda untuk menerima link reset password.
                </p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= e($success) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($link)): ?>
                    <div class="alert alert-info">
                        <a href="<?= e($link) ?>" target="_blank"><?= e($link) ?></a>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/forgot" class="login-form">

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-box">
                            <input
                                type="email"
                                name="email"
                                placeholder="Masukkan email anda"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-login">
                        Kirim Link Reset
                    </button>

                    <div class="extra-links">
                        <p>
                            Sudah ingat password?
                            <a href="<?= BASE_URL ?>/auth/login">Login</a>
                        </p>
                    </div>

                </form>

            </div>

        </div>

        <div class="login-right">

            <div class="overlay"></div>

            <div class="right-content">
                <span class="mini-badge">Password Recovery</span>

                <h2>Reset password dengan aman</h2>

                <p>
                    Kami akan mengirimkan link reset password ke email anda.
                    Silakan cek inbox setelah mengirim permintaan reset.
                </p>
            </div>

        </div>

    </div>
</div>

</body>
</html>
