<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Challora</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/design-tokens.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: var(--font-sans); background: var(--color-surface); color: var(--color-text); }
        .brutalist-card {
            border: 2px solid var(--color-border);
            box-shadow: 8px 8px 0 rgba(0,0,0,1);
            background: #0a0a0a;
            border-radius: 0;
            padding: 32px;
        }
        .brutalist-input {
            background: #111;
            border: 2px solid var(--color-border);
            border-radius: 0;
            color: var(--color-text);
            padding: 14px 16px;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 2px 2px 0 rgba(0,0,0,0.5);
            text-transform: lowercase;
            font-size: 16px;
        }
        .brutalist-input:focus {
            border-color: var(--color-accent);
            box-shadow: 4px 4px 0 var(--color-accent);
            outline: none;
            transform: translate(-2px, -2px);
        }
        .brutalist-btn {
            display: inline-block;
            width: 100%;
            background: var(--color-accent);
            color: var(--color-surface);
            padding: 16px 24px;
            font-weight: 800;
            font-size: 16px;
            text-transform: lowercase;
            text-decoration: none;
            text-align: center;
            border: 2px solid var(--color-border);
            border-radius: 0;
            cursor: pointer;
            box-shadow: 4px 4px 0 rgba(0,0,0,1);
            transition: all 0.2s ease;
        }
        .brutalist-btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 rgba(0,0,0,1);
        }
        .brutalist-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 0px 0px 0 rgba(0,0,0,1);
        }
        .brutalist-label {
            text-transform: lowercase;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        .brutalist-alert {
            background: var(--color-accent);
            color: var(--color-surface);
            padding: 16px;
            border: 2px solid var(--color-border);
            font-weight: bold;
            font-size: 14px;
            text-transform: lowercase;
            margin-bottom: 24px;
            box-shadow: 4px 4px 0 rgba(0,0,0,1);
        }
    </style>
</head>

<body class="min-h-screen lowercase flex items-center justify-center p-4">
    <div class="w-full max-w-lg brutalist-card">
        <div class="mb-8">
            <h1 class="text-4xl font-bold mb-2" style="letter-spacing: -2px;">forgot password</h1>
            <p class="font-semibold opacity-75">enter your email to receive a reset link.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="brutalist-alert bg-red-600 text-white">
                <?= e($error) ?>
            </div>
        <?php elseif (!empty($success)): ?>
            <div class="brutalist-alert bg-green-600 text-white">
                <?= e($success) ?>
            </div>
            <?php if (!empty($link)): ?>
                <div class="brutalist-alert bg-blue-600 text-white mt-4 break-all">
                    copy this link: <a href="<?= e($link) ?>" target="_blank" class="underline"><?= e($link) ?></a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/forgot" class="space-y-6">
            <?= csrf_field() ?>
            <div>
                <label class="brutalist-label">email address</label>
                <input
                    type="email"
                    name="email"
                    placeholder="john.doe@gmail.com"
                    required
                    class="brutalist-input"
                >
            </div>

            <button type="submit" class="brutalist-btn">
                send reset link
            </button>

            <p class="text-sm text-center font-semibold pt-2">
                remembered your password?
                <a href="<?= BASE_URL ?>/auth/login" class="text-accent underline hover:text-white transition" style="text-underline-offset: 4px;">sign in</a>
            </p>
        </form>
    </div>
</body>
</html>
