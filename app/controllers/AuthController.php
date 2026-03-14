<?php
class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(): void {
        if (isLoggedIn()) {
            redirect(currentRole() === 'hr' ? '/hr/jobs' : '/jobs');
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if ($email === '' || $password === '') {
                $error = 'Email dan password wajib diisi.';
            } else {
                $user = $this->userModel->findByEmail($email);
                if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
                    $error = 'Email atau password salah.';
                } else {
                    $_SESSION['user_id'] = (int) $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_name'] = $user['name'];
                    if ($user['role'] === 'hr') {
                        redirect('/hr/jobs');
                    }
                    redirect('/jobs');
                }
            }
        }
        render_view('auth/login', ['error' => $error, 'pageTitle' => 'Login']);
    }

    public function register(): void {
        if (isLoggedIn()) {
            redirect(currentRole() === 'hr' ? '/hr/jobs' : '/jobs');
        }
        $error = '';
        $old = ['name' => '', 'email' => '', 'phone' => '', 'address' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old['name'] = trim($_POST['name'] ?? '');
            $old['email'] = trim($_POST['email'] ?? '');
            $old['phone'] = trim($_POST['phone'] ?? '');
            $old['address'] = trim($_POST['address'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            if ($old['name'] === '' || $old['email'] === '' || $password === '') {
                $error = 'Nama, email, dan password wajib diisi.';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter.';
            } elseif ($password !== $password_confirm) {
                $error = 'Konfirmasi password tidak cocok.';
            } elseif ($this->userModel->findByEmail($old['email'])) {
                $error = 'Email sudah terdaftar.';
            } else {
                $this->userModel->create($old['name'], $old['email'], $password, 'user', $old['phone'] ?: null, $old['address'] ?: null);
                $_SESSION['flash'] = 'Registrasi berhasil. Silakan login.';
                redirect('/auth/login');
            }
        }
        render_view('auth/register', ['error' => $error, 'old' => $old, 'pageTitle' => 'Daftar']);
    }

    public function logout(): void {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect('/auth/login');
    }

    public function forgot(): void {
        if (isLoggedIn()) {
            redirect(currentRole() === 'hr' ? '/hr/jobs' : '/jobs');
        }
        $error = '';
        $success = '';
        $link = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if ($email === '') {
                $error = 'Email wajib diisi.';
            } else {
                $user = $this->userModel->findByEmail($email);
                if ($user) {
                    $token = $this->userModel->createPasswordResetToken((int)$user['id']);
                    $link  = BASE_URL . '/auth/reset?token=' . urlencode($token);

                    $mail = new MailService();
                    if ($mail->isEnabled()) {
                        $sent = $mail->sendPasswordReset($user['email'], $user['name'], $link);
                        if ($sent) {
                            $success = 'Instruksi reset password telah dikirim ke email Anda.';
                        } else {
                            $success = 'Gagal mengirim email, gunakan tautan berikut untuk mereset password:';
                        }
                    } else {
                        $success = 'Mail dinonaktifkan; gunakan tautan berikut untuk mereset password:';
                    }
                }
                if ($success === '') {
                    $success = 'Jika email terdaftar, instruksi reset password telah dikirim.';
                }
            }
        }
        render_view('auth/forgot', [
            'error' => $error,
            'success' => $success,
            'link' => $link,
            'pageTitle' => 'Lupa Password'
        ]);
    }

    public function reset(): void {
        if (isLoggedIn()) {
            redirect(currentRole() === 'hr' ? '/hr/jobs' : '/jobs');
        }
        $token = $_GET['token'] ?? $_POST['token'] ?? '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['password_confirm'] ?? '';
            if ($password === '' || $confirm === '') {
                $error = 'Password dan konfirmasi wajib diisi.';
            } elseif ($password !== $confirm) {
                $error = 'Konfirmasi tidak cocok.';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter.';
            } else {
                $reset = $this->userModel->getPasswordResetByToken($token);
                if ($reset) {
                    $this->userModel->updatePassword((int)$reset['user_id'], $password);
                    $this->userModel->invalidatePasswordResetToken($token);
                    $_SESSION['flash'] = 'Password berhasil diubah. Silakan login.';
                    redirect('/auth/login');
                } else {
                    $error = 'Token tidak valid atau sudah kadaluarsa.';
                }
            }
        } else {
            if ($token !== '') {
                $reset = $this->userModel->getPasswordResetByToken($token);
                if (!$reset) {
                    $error = 'Token tidak valid atau sudah kadaluarsa.';
                }
            } else {
                $error = 'Token tidak tersedia.';
            }
        }
        render_view('auth/reset', ['error' => $error, 'token' => $token, 'pageTitle' => 'Reset Password']);
    }
}
