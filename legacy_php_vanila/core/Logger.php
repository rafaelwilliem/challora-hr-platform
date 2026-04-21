<?php

declare(strict_types=1);

/**
 * Simple Logger utility
 */
final class Logger {
    private static ?string $logPath = null;

    private static function init(): void {
        if (self::$logPath === null) {
            self::$logPath = BASE_PATH . '/storage/logs/app.log';
            $dir = dirname(self::$logPath);
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
        }
    }

    public static function log(string $level, string $message, array $context = []): void {
        self::init();
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
        $entry = "[$timestamp] [$level] $message$contextStr" . PHP_EOL;
        @file_put_contents(self::$logPath, $entry, FILE_APPEND);
    }

    public static function info(string $message, array $context = []): void {
        self::log('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void {
        self::log('ERROR', $message, $context);
    }

    public static function debug(string $message, array $context = []): void {
        self::log('DEBUG', $message, $context);
    }
}
