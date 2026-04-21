<?php
/**
 * Database - PDO single connection
 */
class Database {
    private static ?PDO $pdo = null;

    public static function get(): PDO {
        if (self::$pdo === null) {
            try {
                $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                Logger::error('Database connection failed: ' . $e->getMessage());
                if (Env::get('APP_DEBUG', false)) {
                     die('Database error: ' . $e->getMessage());
                } else {
                     die('We are experiencing technical difficulties. Please try again later.');
                }
            }
        }
        return self::$pdo;
    }

    public static function beginTransaction(): bool {
        return self::get()->beginTransaction();
    }

    public static function commit(): bool {
        return self::get()->commit();
    }

    public static function rollBack(): bool {
        return self::get()->rollBack();
    }
}
