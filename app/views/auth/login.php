<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-In — Challora</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/design-tokens.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/login-register-style.css">
</head>
<body>
    <div class="split-card gsap-reveal">
        <!-- FORM SIDE -->
        <div class="form-side">
            <div class="gsap-item">
                <h1 class="auth-title">Log-In</h1>
                <p class="auth-subtitle">Access your intelligence dashboard.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="bg-red-900/50 text-red-100 p-4 mb-8 border border-red-500 font-bold uppercase text-[10px] tracking-widest gsap-item">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="bg-orange-900/50 text-accent-100 p-4 mb-8 border border-orange-500 font-bold uppercase text-[10px] tracking-widest gsap-item">
                    <?= e($_SESSION['flash']) ?>
                </div>
                <?php unset($_SESSION['flash'], $_SESSION['flash_type']); ?>
            <?php endif; ?>

            <form method="post" action="<?= BASE_URL ?>/index.php?url=auth/login" class="gsap-item">
                <?= csrf_field() ?>
                
                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white">
                            EMAIL ADDRESS
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </span>
                        <span class="label-req">*required</span>
                    </label>
                    <input type="email" name="email" placeholder="example@gmail.com" required 
                        class="premium-input" value="<?= e($_POST['email'] ?? '') ?>">
                </div>

                <div class="mb-2">
                    <label class="premium-label">
                        <span class="flex items-center gap-2 text-white">
                            PASSWORD
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <span class="text-accent-600 label-req">*required</span>
                    </label>
                    <input type="password" name="password" placeholder="••••••••" required class="premium-input">
                </div>

                <a href="<?= BASE_URL ?>/auth/forgot" class="text-accent-500 hover:text-accent-400 font-black text-xs uppercase tracking-widest inline-block mb-8">Forgot Password?</a>

                <button type="submit" class="btn-brutal">
                    LOG-IN
                </button>

                <div class="mt-12 text-center">
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">
                        New Here? <a href="<?= BASE_URL ?>/auth/register" class="text-accent-500 hover:text-white transition-colors duration-300">Sign-Up</a>
                    </p>
                </div>
            </form>
        </div>

        <!-- BRAND SIDE -->
        <div class="brand-side">
            <div class="gsap-item">
                <div class="brand-title">Challora</div>
                <div class="brand-quote">
                    Intelligence is not just about what you know, but how you connect the dots.
                </div>
            </div>

            <div class="brand-footer gsap-item">
                AUTH PROTOCOL — V2.2.1
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.from(".gsap-reveal", { 
                opacity: 0, 
                scale: 0.98,
                duration: 1.2, 
                ease: "power4.out" 
            });
            gsap.from(".gsap-item", { 
                opacity: 0, 
                x: -20, 
                stagger: 0.1, 
                duration: 0.8, 
                ease: "power3.out", 
                delay: 0.3 
            });
        });
    </script>
</body>
</html>