<?php
/**
 * Database configuration - PDO single connection
 */
define('DB_HOST', Env::get('DB_HOST', 'localhost'));
define('DB_NAME', Env::get('DB_NAME', 'challora_recruitment'));
define('DB_USER', Env::get('DB_USER', ''));
define('DB_PASS', Env::get('DB_PASS', ''));
define('DB_CHARSET', Env::get('DB_CHARSET', 'utf8mb4'));

function getDB(): PDO {
    return Database::get();
}
