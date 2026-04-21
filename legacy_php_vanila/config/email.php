<?php
/**
 * Email configuration (untuk pengiriman otomatis)
 * Sesuaikan dengan SMTP Anda (Gmail, Outlook, dll)
 * Gmail: gunakan App Password, bukan password biasa
 */


return [
    'enabled' => Env::get('EMAIL_ENABLED', false),
    'from_email' => Env::get('EMAIL_FROM', 'noreply@challora.id'),
    'from_name' => Env::get('EMAIL_FROM_NAME', 'Challora Recruitment'),
    'smtp_host' => Env::get('SMTP_HOST', 'smtp.gmail.com'),
    'smtp_port' => (int) Env::get('SMTP_PORT', 587),
    'smtp_user' => Env::get('SMTP_USER', ''),
    'smtp_pass' => Env::get('SMTP_PASS', ''),
    'smtp_secure' => Env::get('SMTP_SECURE', 'tls'),
];
