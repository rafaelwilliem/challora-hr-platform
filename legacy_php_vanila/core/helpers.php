<?php
/**
 * Helpers: escape, redirect, auth, render
 */

function e(?string $s): string {
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url, int $code = 302): void {
    if ($url !== '' && $url[0] === '/' && defined('BASE_URL')) {
        $path = parse_url($url, PHP_URL_PATH);
        $query = parse_url($url, PHP_URL_QUERY);
        $path = trim($path, '/');
        $url = $path === '' ? BASE_URL . '/' : (BASE_URL . '/index.php?url=' . $path . ($query ? '&' . $query : ''));
    }
    header('Location: ' . $url, true, $code);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect('/auth/login');
    }
}

function requireRole(string $role): void {
    requireLogin();
    if (($_SESSION['role'] ?? '') !== $role) {
        redirect($role === 'hr' ? '/hr/jobs' : '/jobs');
    }
}

function currentUserId(): int {
    return (int) ($_SESSION['user_id'] ?? 0);
}

function currentRole(): string {
    return $_SESSION['role'] ?? '';
}

/**
 * Route path aktif dari query parameter `url`.
 */
function currentRoutePath(string $fallback = ''): string {
    $path = trim((string) ($_GET['url'] ?? ''), '/');
    if ($path !== '') {
        return $path;
    }
    return trim($fallback, '/');
}

/**
 * Mapping status lamaran untuk label + badge.
 *
 * @return array{label:string,badge:string}
 */
function applicationStatusMeta(?string $status): array {
    $key = strtolower(trim((string) $status));
    $map = [
        'pending' => ['label' => 'Pending', 'badge' => 'bg-primary text-secondary'],
        'reviewed' => ['label' => 'CV review', 'badge' => 'bg-warning'],
        'accepted' => ['label' => 'Accepted', 'badge' => 'bg-success'],
        'rejected' => ['label' => 'Rejected', 'badge' => 'bg-danger'],
    ];
    return $map[$key] ?? ['label' => ($status !== null && trim((string) $status) !== '' ? (string) $status : '-'), 'badge' => 'bg-secondary text-accent'];
}

/**
 * Opsi status lamaran untuk form update status.
 *
 * @return array<string,string>
 */
function applicationStatusOptions(): array {
    return [
        'pending' => 'Pending',
        'reviewed' => 'CV review',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ];
}

/** Create subject and content for application status emails */
function applicationStatusEmailTemplate(string $name, string $jobTitle, string $status): array {
    $subject = 'Application Result: ' . $jobTitle . ' - ' . ($status === 'accepted' ? 'Hired' : 'Not Accepted');
    if ($status === 'accepted') {
        $body = "Dear {$name},\n\nCongratulations! You have PASSED the selection process for the position of {$jobTitle}.\n\nPlease contact us for the next steps.\n\nThank you.";
    } else {
        $body = "Dear {$name},\n\nThank you for applying for the position of {$jobTitle}.\n\nWe regret to inform you that we are unable to move forward with your application for this position at this time.\n\nKeep up the spirit and thank you.";
    }
    return ['subject' => $subject, 'body' => $body];
}


/** URL img avatar untuk user yang login; null jika tidak ada foto profil */
function currentUserAvatarImgSrc(): ?string {
    if (!isLoggedIn() || currentRole() !== 'user') {
        return null;
    }
    if (empty($_SESSION['user_avatar_path'])) {
        return null;
    }
    $v = rawurlencode((string) ($_SESSION['user_avatar_ver'] ?? '0'));
    return BASE_URL . '/index.php?url=download/avatar&v=' . $v;
}

/**
 * Cek apakah profil user sudah lengkap (field wajib saja, tidak termasuk opsional)
 */
function isProfileComplete(): bool {
    if (!isLoggedIn() || currentRole() !== 'user') {
        return true;
    }
    $user = (new User())->findById(currentUserId());
    if (!$user) return true;
    $required = ['name', 'phone', 'address', 'gender', 'religion', 'birth_place', 'birth_date', 'marital_status',
        'education_level', 'graduation_year', 'education_major', 'education_university'];
    foreach ($required as $field) {
        $v = trim((string) ($user[$field] ?? ''));
        if ($v === '') return false;
    }
    return true;
}

/**
 * Render view with layout. Layout auto-detected: hr/* => sidebar, else => clean
 */
function render_view(string $view, array $data = []): void {
    extract($data);
    ob_start();
    require APP_PATH . '/views/' . $view . '.php';
    $content = ob_get_clean();
    $layout = (strpos($view, 'hr/') === 0) ? 'hr' : (
        in_array($view, ['auth/login', 'auth/forgot', 'auth/register', 'auth/reset'], true) ? 'auth' : 'user'
    );
    require APP_PATH . '/views/layouts/' . $layout . '.php';
}

/** CSRF: Generate token */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** CSRF: Generate hidden field */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/** CSRF: Validate token */
function validate_csrf(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if ($token === '' || !hash_equals(csrf_token(), $token)) {
            http_response_code(403);
            die('CSRF token mismatch.');
        }
    }
}

/** Password Policy: min 8 chars, 1 uppercase, 1 lowercase, 1 number */
function validatePasswordStrength(string $password): ?string {
    if (strlen($password) < 8) {
        return 'Password minimal 8 karakter.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return 'Password harus mengandung huruf besar.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        return 'Password harus mengandung huruf kecil.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        return 'Password harus mengandung angka.';
    }
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        return 'Password harus mengandung karakter spesial.';
    }
    return null;
}

/**
 * XSS Protection / Sanitization
 */
function s(?string $input): string {
    return htmlspecialchars((string) $input, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate string length
 */
function validate_length(string $input, int $max, string $field): ?string {
    if (mb_strlen($input) > $max) {
        return "$field tidak boleh lebih dari $max karakter.";
    }
    return null;
}
