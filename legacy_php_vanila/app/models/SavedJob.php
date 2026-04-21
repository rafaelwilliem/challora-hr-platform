<?php
class SavedJob {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function save(int $userId, int $jobId): bool {
        try {
            $stmt = $this->db->prepare('INSERT IGNORE INTO saved_jobs (user_id, job_id) VALUES (?, ?)');
            $stmt->execute([$userId, $jobId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function unsave(int $userId, int $jobId): bool {
        $stmt = $this->db->prepare('DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?');
        return $stmt->execute([$userId, $jobId]);
    }

    public function isSaved(int $userId, int $jobId): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM saved_jobs WHERE user_id = ? AND job_id = ?');
        $stmt->execute([$userId, $jobId]);
        return (bool) $stmt->fetch();
    }

    /** @return int[] */
    public function getSavedJobIds(int $userId): array {
        $stmt = $this->db->prepare('SELECT job_id FROM saved_jobs WHERE user_id = ?');
        $stmt->execute([$userId]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }

    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare('
            SELECT j.*, u.name AS created_by_name, sj.created_at AS saved_at
            FROM saved_jobs sj
            JOIN jobs j ON j.id = sj.job_id
            LEFT JOIN users u ON u.id = j.created_by
            WHERE sj.user_id = ?
            ORDER BY sj.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
