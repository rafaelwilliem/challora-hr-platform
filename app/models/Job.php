<?php
class Job {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function all(): array {
        $stmt = $this->db->query('
            SELECT j.*, u.name AS created_by_name
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            ORDER BY j.created_at DESC
        ');
        return $stmt->fetchAll();
    }

    /**
     * Search & filter jobs (user-side)
     * salary: cari lowongan yang range gajinya mencakup nilai ini (misal job 8-10 jt, cari 9 → muncul)
     * @param array{q?: string, location?: string, salary?: string|int} $params
     */
    public function searchAndFilter(array $params): array {
        $conditions = [];
        $bind = [];

        if (!empty(trim($params['q'] ?? ''))) {
            $q = '%' . trim($params['q']) . '%';
            $conditions[] = '(j.title LIKE ? OR j.description LIKE ? OR j.short_description LIKE ? OR j.location LIKE ?)';
            $bind[] = $q;
            $bind[] = $q;
            $bind[] = $q;
            $bind[] = $q;
        }
        if (!empty(trim($params['location'] ?? ''))) {
            $loc = '%' . trim($params['location']) . '%';
            $conditions[] = '(j.location LIKE ? OR j.provinsi LIKE ? OR j.kota LIKE ? OR j.kecamatan LIKE ?)';
            $bind[] = $loc;
            $bind[] = $loc;
            $bind[] = $loc;
            $bind[] = $loc;
        }
        $salary = (int) ($params['salary'] ?? 0);
        if ($salary > 0) {
            // Job muncul jika range gajinya mencakup nilai yang dicari (min <= X <= max)
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary <= ?) AND (j.max_salary IS NULL OR j.max_salary >= ?)';
            $bind[] = $salary;
            $bind[] = $salary;
        }

        $where = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
        $sql = "SELECT j.*, u.name AS created_by_name
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            $where
            ORDER BY j.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetchAll();
    }

    /** Count jobs matching search/filter (for pagination) */
    public function countSearchAndFilter(array $params): int {
        $conditions = [];
        $bind = [];
        if (!empty(trim($params['q'] ?? ''))) {
            $q = '%' . trim($params['q']) . '%';
            $conditions[] = '(j.title LIKE ? OR j.description LIKE ? OR j.short_description LIKE ? OR j.location LIKE ?)';
            $bind[] = $q; $bind[] = $q; $bind[] = $q; $bind[] = $q;
        }
        if (!empty(trim($params['location'] ?? ''))) {
            $loc = '%' . trim($params['location']) . '%';
            $conditions[] = '(j.location LIKE ? OR j.provinsi LIKE ? OR j.kota LIKE ? OR j.kecamatan LIKE ?)';
            $bind[] = $loc; $bind[] = $loc; $bind[] = $loc; $bind[] = $loc;
        }
        $salary = (int) ($params['salary'] ?? 0);
        if ($salary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary <= ?) AND (j.max_salary IS NULL OR j.max_salary >= ?)';
            $bind[] = $salary; $bind[] = $salary;
        }
        $where = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM jobs j $where");
        $stmt->execute($bind);
        return (int) $stmt->fetchColumn();
    }

    /** Search & filter with pagination */
    public function searchAndFilterPaginated(array $params, int $page = 1, int $perPage = 20): array {
        $conditions = [];
        $bind = [];
        if (!empty(trim($params['q'] ?? ''))) {
            $q = '%' . trim($params['q']) . '%';
            $conditions[] = '(j.title LIKE ? OR j.description LIKE ? OR j.short_description LIKE ? OR j.location LIKE ?)';
            $bind[] = $q; $bind[] = $q; $bind[] = $q; $bind[] = $q;
        }
        if (!empty(trim($params['location'] ?? ''))) {
            $loc = '%' . trim($params['location']) . '%';
            $conditions[] = '(j.location LIKE ? OR j.provinsi LIKE ? OR j.kota LIKE ? OR j.kecamatan LIKE ?)';
            $bind[] = $loc; $bind[] = $loc; $bind[] = $loc; $bind[] = $loc;
        }
        $salary = (int) ($params['salary'] ?? 0);
        if ($salary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary <= ?) AND (j.max_salary IS NULL OR j.max_salary >= ?)';
            $bind[] = $salary; $bind[] = $salary;
        }
        $where = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT j.*, u.name AS created_by_name FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by $where
            ORDER BY j.created_at DESC LIMIT " . (int) $perPage . " OFFSET " . (int) $offset;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($bind);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('
            SELECT j.*, u.name AS created_by_name
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            WHERE j.id = ?
        ');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByCreator(int $createdBy): array {
        $stmt = $this->db->prepare('SELECT * FROM jobs WHERE created_by = ? ORDER BY created_at DESC');
        $stmt->execute([$createdBy]);
        return $stmt->fetchAll();
    }

    public function countByCreator(int $createdBy): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM jobs WHERE created_by = ?');
        $stmt->execute([$createdBy]);
        return (int) $stmt->fetchColumn();
    }

    /** Filter: all, no_apply, has_apply, has_accepted */
    public function countByCreatorFiltered(int $createdBy, string $filter): int {
        $filter = $this->normalizeFilter($filter);
        $base = 'SELECT COUNT(*) FROM jobs j WHERE j.created_by = ?';
        $where = $this->filterWhereClause($filter);
        $sql = $base . $where;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$createdBy]);
        return (int) $stmt->fetchColumn();
    }

    private function normalizeFilter(string $filter): string {
        $allowed = ['all', 'no_apply', 'has_apply', 'has_accepted'];
        return in_array($filter, $allowed, true) ? $filter : 'all';
    }

    private function filterWhereClause(string $filter): string {
        if ($filter === 'no_apply') {
            return ' AND j.id NOT IN (SELECT job_id FROM applications)';
        }
        if ($filter === 'has_apply') {
            return ' AND j.id IN (SELECT job_id FROM applications)';
        }
        if ($filter === 'has_accepted') {
            return " AND j.id IN (SELECT job_id FROM applications WHERE status = 'accepted')";
        }
        return '';
    }

    public function findByCreatorPaginated(int $createdBy, int $page = 1, int $perPage = 10, string $filter = 'all'): array {
        $filter = $this->normalizeFilter($filter);
        $offset = max(0, ($page - 1) * $perPage);
        $perPage = (int) $perPage;
        $offset = (int) $offset;
        $where = $this->filterWhereClause($filter);
        $sql = "SELECT j.*, u.name AS created_by_name
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            WHERE j.created_by = ? $where
            ORDER BY j.created_at DESC
            LIMIT $perPage OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$createdBy]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        $skillsJson = $this->encodeSkillsBenefits($data['skills'] ?? []);
        $benefitsJson = $this->encodeSkillsBenefits($data['benefits'] ?? []);
        $stmt = $this->db->prepare('
            INSERT INTO jobs (title, description, short_description, location, salary_range, min_salary, max_salary,
                job_type, min_education, is_urgent, provinsi, kota, kecamatan,
                deadline, max_applicants, skills_json, benefits_json, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['title'],
            $data['description'],
            !empty($data['short_description']) ? mb_substr(trim($data['short_description']), 0, 255) : null,
            $data['location'] ?? null,
            $data['salary_range'] ?? null,
            !empty($data['min_salary']) ? (int) $data['min_salary'] : null,
            !empty($data['max_salary']) ? (int) $data['max_salary'] : null,
            !empty($data['job_type']) ? $data['job_type'] : null,
            !empty($data['min_education']) ? $data['min_education'] : null,
            !empty($data['is_urgent']) ? 1 : 0,
            !empty($data['provinsi']) ? $data['provinsi'] : null,
            !empty($data['kota']) ? $data['kota'] : null,
            !empty($data['kecamatan']) ? $data['kecamatan'] : null,
            !empty($data['deadline']) ? $data['deadline'] : null,
            !empty($data['max_applicants']) ? (int) $data['max_applicants'] : null,
            $skillsJson,
            $benefitsJson,
            $data['created_by'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $skillsJson = $this->encodeSkillsBenefits($data['skills'] ?? []);
        $benefitsJson = $this->encodeSkillsBenefits($data['benefits'] ?? []);
        $stmt = $this->db->prepare('
            UPDATE jobs SET title = ?, description = ?, short_description = ?, location = ?, salary_range = ?,
                min_salary = ?, max_salary = ?,
                job_type = ?, min_education = ?, is_urgent = ?,
                provinsi = ?, kota = ?, kecamatan = ?,
                deadline = ?, max_applicants = ?, skills_json = ?, benefits_json = ?
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['title'],
            $data['description'],
            !empty($data['short_description']) ? mb_substr(trim($data['short_description']), 0, 255) : null,
            $data['location'] ?? null,
            $data['salary_range'] ?? null,
            !empty($data['min_salary']) ? (int) $data['min_salary'] : null,
            !empty($data['max_salary']) ? (int) $data['max_salary'] : null,
            !empty($data['job_type']) ? $data['job_type'] : null,
            !empty($data['min_education']) ? $data['min_education'] : null,
            !empty($data['is_urgent']) ? 1 : 0,
            !empty($data['provinsi']) ? $data['provinsi'] : null,
            !empty($data['kota']) ? $data['kota'] : null,
            !empty($data['kecamatan']) ? $data['kecamatan'] : null,
            !empty($data['deadline']) ? $data['deadline'] : null,
            !empty($data['max_applicants']) ? (int) $data['max_applicants'] : null,
            $skillsJson,
            $benefitsJson,
            $id,
        ]);
    }

    private function encodeSkillsBenefits(array $items): ?string {
        $arr = array_values(array_filter(array_map('trim', $items), fn($x) => $x !== ''));
        return $arr === [] ? null : json_encode($arr);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM jobs WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function isCreatedBy(int $jobId, int $userId): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM jobs WHERE id = ? AND created_by = ?');
        $stmt->execute([$jobId, $userId]);
        return (bool) $stmt->fetch();
    }

    /** @return string[] array of skill keywords */
    public function getSkills(int $jobId): array {
        $job = $this->findById($jobId);
        if (!$job || empty($job['skills_json'])) return [];
        $decoded = json_decode($job['skills_json'], true);
        return is_array($decoded) ? array_values($decoded) : [];
    }

    /** @return string[] array of benefit keywords */
    public function getBenefits(int $jobId): array {
        $job = $this->findById($jobId);
        if (!$job || empty($job['benefits_json'])) return [];
        $decoded = json_decode($job['benefits_json'], true);
        return is_array($decoded) ? array_values($decoded) : [];
    }
}
