<?php

declare(strict_types=1);

/**
 * Database-backed Rate Limiter to prevent race conditions (TOCTOU)
 */
final class RateLimiter {
    /**
     * @param string $key Unique key for the action (e.g. "login:127.0.0.1")
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @return bool True if allowed, false if throttled
     */
    public static function attempt(string $key, int $maxAttempts, int $decaySeconds): bool {
        $db = Database::get();
        $now = time();
        
        try {
            Database::beginTransaction();
            
            // SELECT FOR UPDATE to lock the row and prevent race conditions
            $stmt = $db->prepare("SELECT * FROM rate_limits WHERE key_name = ? FOR UPDATE");
            $stmt->execute([$key]);
            $row = $stmt->fetch();
            
            if (!$row) {
                // First attempt
                $stmt = $db->prepare("INSERT INTO rate_limits (key_name, count, last_attempt, expires_at) VALUES (?, 1, ?, ?)");
                $stmt->execute([$key, $now, $now + $decaySeconds]);
                Database::commit();
                return true;
            }
            
            if ($now > $row['expires_at']) {
                // Reset after decay
                $stmt = $db->prepare("UPDATE rate_limits SET count = 1, last_attempt = ?, expires_at = ? WHERE key_name = ?");
                $stmt->execute([$now, $now + $decaySeconds, $key]);
                Database::commit();
                return true;
            }
            
            if ($row['count'] >= $maxAttempts) {
                Database::rollBack();
                return false;
            }
            
            // Increment
            $stmt = $db->prepare("UPDATE rate_limits SET count = count + 1, last_attempt = ? WHERE key_name = ?");
            $stmt->execute([$now, $key]);
            
            Database::commit();
            return true;
            
        } catch (Throwable $e) {
            Database::rollBack();
            Logger::error("RateLimiter error for key '$key': " . $e->getMessage());
            return true; // Fail open to avoid blocking users on DB issues, but log it.
        }
    }

    public static function clear(string $key): void {
        try {
            $db = Database::get();
            $stmt = $db->prepare("DELETE FROM rate_limits WHERE key_name = ?");
            $stmt->execute([$key]);
        } catch (Throwable $e) {
            Logger::error("RateLimiter clear error for key '$key': " . $e->getMessage());
        }
    }
}
