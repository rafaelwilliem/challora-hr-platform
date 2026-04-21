<?php
class Application {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function create(int $userId, int $jobId, ?string $cvPath = null, ?string $diplomaPath = null, ?string $photoPath = null): int {
        $stmt = $this->db->prepare('INSERT INTO applications (user_id, job_id, cv_path, diploma_path, photo_path, status) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $jobId, $cvPath, $diplomaPath, $photoPath, 'pending']);
        return (int) $this->db->lastInsertId();
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    /** Cek sudah apply atau belum (unique user_id + job_id) */
    public function hasApplied(int $userId, int $jobId): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM applications WHERE user_id = ? AND job_id = ?');
        $stmt->execute([$userId, $jobId]);
        return (bool) $stmt->fetch();
    }

    /** Bulk check which jobs a user has applied to */
    public function getAppliedJobIds(int $userId, array $jobIds): array {
        if (empty($jobIds)) return [];
        $placeholders = implode(',', array_fill(0, count($jobIds), '?'));
        $stmt = $this->db->prepare("SELECT job_id FROM applications WHERE user_id = ? AND job_id IN ($placeholders)");
        $params = array_merge([$userId], $jobIds);
        $stmt->execute($params);
        return array_map(fn($row) => (int)$row['job_id'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Daftar applicant per job (untuk HR) - termasuk profil lengkap */
    public function getByJobId(int $jobId): array {
        $stmt = $this->db->prepare('
            SELECT a.*, u.name, u.email, u.phone, u.address,
                u.gender, u.religion, u.social_media, u.birth_place, u.birth_date,
                u.father_name, u.mother_name, u.marital_status,
                u.father_job, u.mother_job, u.father_education, u.mother_education,
                u.father_phone, u.mother_phone, u.address_type, u.address_family,
                u.emergency_name, u.emergency_phone, u.user_summary,
                u.education_level, u.graduation_year, u.education_major, u.education_university
            FROM applications a
            JOIN users u ON u.id = a.user_id
            WHERE a.job_id = ?
            ORDER BY a.created_at DESC
        ');
        $stmt->execute([$jobId]);
        return $stmt->fetchAll();
    }

    public function getApplicantsForHr(?string $status = null, ?string $searchQuery = null, ?int $jobId = null, int $page = 1, int $perPage = 20): array {
        $sql = '
            SELECT a.*, u.name, u.email, u.phone, u.address,
                u.gender, u.religion, u.social_media, u.birth_place, u.birth_date,
                u.father_name, u.mother_name, u.marital_status,
                u.father_job, u.mother_job, u.father_education, u.mother_education,
                u.father_phone, u.mother_phone, u.address_type, u.address_family,
                u.emergency_name, u.emergency_phone, u.user_summary,
                u.education_level, u.graduation_year, u.education_major, u.education_university,
                j.title AS job_title, j.location AS job_location
            FROM applications a
            JOIN users u ON u.id = a.user_id
            JOIN jobs j ON j.id = a.job_id
            WHERE 1 = 1
        ';
        $params = [];

        if ($status !== null && $status !== '') {
            $sql .= ' AND a.status = ?';
            $params[] = $status;
        }

        if ($jobId !== null && $jobId > 0) {
            $sql .= ' AND a.job_id = ?';
            $params[] = $jobId;
        }

        if ($searchQuery !== null && trim($searchQuery) !== '') {
            $term = '%' . strtolower(trim($searchQuery)) . '%';
            $sql .= ' AND (
                LOWER(u.name) LIKE ? OR LOWER(u.email) LIKE ? OR LOWER(j.title) LIKE ? OR LOWER(j.location) LIKE ?
            )';
            $params = array_merge($params, [$term, $term, $term, $term]);
        }

        $perPage = max(1, $perPage);
        $offset = max(0, ($page - 1) * $perPage);
        $sql .= " ORDER BY a.created_at DESC LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $i => $v) {
            $stmt->bindValue($i + 1, $v);
        }
        $stmt->bindValue(count($params) + 1, (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countApplicantsForHr(?string $status = null, ?string $searchQuery = null, ?int $jobId = null): int {
        $sql = '
            SELECT COUNT(*)
            FROM applications a
            JOIN jobs j ON j.id = a.job_id
            JOIN users u ON u.id = a.user_id
            WHERE 1 = 1
        ';
        $params = [];

        if ($status !== null && $status !== '') {
            $sql .= ' AND a.status = ?';
            $params[] = $status;
        }

        if ($jobId !== null && $jobId > 0) {
            $sql .= ' AND a.job_id = ?';
            $params[] = $jobId;
        }

        if ($searchQuery !== null && trim($searchQuery) !== '') {
            $term = '%' . strtolower(trim($searchQuery)) . '%';
            $sql .= ' AND (
                LOWER(u.name) LIKE ? OR LOWER(u.email) LIKE ? OR LOWER(j.title) LIKE ? OR LOWER(j.location) LIKE ?
            )';
            $params = array_merge($params, [$term, $term, $term, $term]);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function updateStatus(int $id, string $status): bool {
        $allowed = [self::STATUS_PENDING, self::STATUS_REVIEWED, self::STATUS_ACCEPTED, self::STATUS_REJECTED];
        if (!in_array($status, $allowed, true)) return false;
        $stmt = $this->db->prepare('UPDATE applications SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    /** Application milik job yang dibuat oleh any HR (platform global) */
    public function getApplicationForHrJob(int $applicationId): ?array {
        $stmt = $this->db->prepare('
            SELECT a.* FROM applications a
            JOIN jobs j ON j.id = a.job_id
            WHERE a.id = ?
        ');
        $stmt->execute([$applicationId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /** Count per status untuk satu job */
    public function getCountsByJobId(int $jobId): array {
        $stmt = $this->db->prepare('
            SELECT status, COUNT(*) AS cnt FROM applications WHERE job_id = ? GROUP BY status
        ');
        $stmt->execute([$jobId]);
        $rows = [];
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rows[$r['status']] = (int) $r['cnt'];
        }
        $pendingCount = (int) ($rows['pending'] ?? 0) + (int) ($rows['reviewed'] ?? 0);
        return [
            'total' => $pendingCount + (int) ($rows['accepted'] ?? 0) + (int) ($rows['rejected'] ?? 0),
            'accepted' => (int) ($rows['accepted'] ?? 0),
            'rejected' => (int) ($rows['rejected'] ?? 0),
            'pending' => $pendingCount,
        ];
    }

    /** Statistik applicant untuk semua job (global view) */
    public function getCountsByHrJobs(): array {
        $stmt = $this->db->prepare('
            SELECT status, COUNT(*) AS cnt
            FROM applications a
            JOIN jobs j ON j.id = a.job_id
            GROUP BY status
        ');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
        $pendingCount = (int) ($rows['pending'] ?? 0) + (int) ($rows['reviewed'] ?? 0);
        return [
            'total' => $pendingCount + (int) ($rows['accepted'] ?? 0) + (int) ($rows['rejected'] ?? 0),
            'accepted' => (int) ($rows['accepted'] ?? 0),
            'rejected' => (int) ($rows['rejected'] ?? 0),
            'pending' => $pendingCount,
        ];
    }

    /** Daftar applicant yang diterima untuk semua lowongan (global view) */
    public function getAcceptedApplicantsForHr(int $page = 1, int $perPage = 20): array {
        $offset = max(0, ($page - 1) * $perPage);
        $perPage = (int) $perPage;
        $offset = (int) $offset;
        $sql = "SELECT a.*, u.name, u.email, u.phone,
                j.title AS job_title, j.location AS job_location
            FROM applications a
            JOIN users u ON u.id = a.user_id
            JOIN jobs j ON j.id = a.job_id
            WHERE a.status = ?
            ORDER BY a.created_at DESC
            LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, 'accepted');
        $stmt->bindValue(2, (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(3, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAcceptedForHr(): int {
        $stmt = $this->db->prepare('
            SELECT COUNT(*)
            FROM applications a
            JOIN jobs j ON j.id = a.job_id
            WHERE a.status = ?
        ');
        $stmt->execute(['accepted']);
        return (int) $stmt->fetchColumn();
    }

    /** Top N daerah (kota) berdasarkan jumlah pelamar */
    public function getTopRegions(int $limit = 5): array {
        $limit = max(1, min(50, $limit));
        $sql = "
            SELECT COALESCE(NULLIF(TRIM(u.kota),''), NULLIF(TRIM(u.address),''), 'Tidak diketahui') AS region,
                   COUNT(*) AS total
            FROM applications a
            JOIN users u ON u.id = a.user_id
            GROUP BY region
            ORDER BY total DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Jobs diurutkan berdasarkan jumlah pelamar (untuk tabel filter) */
    public function getJobsOrderedByApplicantCount(string $order = 'desc', int $limit = 50): array {
        $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';
        $limit = max(1, min(200, $limit));
        $sql = "
            SELECT j.id, j.title, j.location, j.job_type, j.deadline,
                   COALESCE(cnt.total, 0) AS applicant_count,
                   COALESCE(cnt.accepted, 0) AS accepted_count
            FROM jobs j
            LEFT JOIN (
                SELECT job_id, COUNT(*) AS total,
                       SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) AS accepted
                FROM applications GROUP BY job_id
            ) AS cnt ON cnt.job_id = j.id
            ORDER BY applicant_count $order
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Monthly applicant trend (last 6 months) */
    public function getMonthlyTrend(int $months = 6): array {
        $stmt = $this->db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month_key,
                   DATE_FORMAT(created_at, '%b') AS month_label,
                   COUNT(*) AS total,
                   SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) AS accepted,
                   SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected
            FROM applications
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
            GROUP BY month_key, month_label
            ORDER BY month_key ASC
        ");
        $stmt->execute([$months]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Daftar apply user (untuk profile candidate) */
    public function getByUserId(int $userId): array {
        $stmt = $this->db->prepare('
            SELECT a.*, j.title AS job_title, j.short_description, j.location, j.currency, j.min_salary, j.max_salary, j.salary_range
            FROM applications a
            JOIN jobs j ON j.id = a.job_id
            WHERE a.user_id = ?
            ORDER BY a.created_at DESC
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
