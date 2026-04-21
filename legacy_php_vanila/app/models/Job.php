<?php
class Job {
    private PDO $db;
    /** @var array<string,bool>|null */
    private static ?array $jobsColumnMap = null;

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
     * Params yang didukung (semua opsional):
     * - q: keyword judul/deskripsi/lokasi
     * - location: lokasi (lokasi/provinsi/kota/kecamatan)
     * - salary: angka tunggal (legacy) → cari job yang mencakup nilai ini
     * - min_salary, max_salary: filter dengan range gaji minimum/maksimum
     * - job_type: jenis pekerjaan (full_time, part_time, freelance, remote, hybrid, onsite, dll)
     * - min_education: minimal pendidikan (sma, d3, s1, s2, s3)
     * - experience_level: level pengalaman (fresh_grad, junior_1_3, senior_5_10, dll)
     * - updated: rentang pembaruan (week, month, year, latest)
     * @param array<string,mixed> $params
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
            // Legacy: job muncul jika range gajinya mencakup nilai yang dicari (min <= X <= max)
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary <= ?) AND (j.max_salary IS NULL OR j.max_salary >= ?)';
            $bind[] = $salary;
            $bind[] = $salary;
        }
        $minSalary = (int) ($params['min_salary'] ?? 0);
        if ($minSalary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary >= ?)';
            $bind[] = $minSalary;
        }
        $maxSalary = (int) ($params['max_salary'] ?? 0);
        if ($maxSalary > 0) {
            $conditions[] = '(j.max_salary IS NULL OR j.max_salary <= ?)';
            $bind[] = $maxSalary;
        }
        $this->appendJobTypeCondition($conditions, $bind, $params);
        $this->appendMinEducationCondition($conditions, $bind, $params);
        $this->appendExperienceLevelCondition($conditions, $bind, $params);
        $this->appendUpdatedCondition($conditions, $params);

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
        $minSalary = (int) ($params['min_salary'] ?? 0);
        if ($minSalary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary >= ?)';
            $bind[] = $minSalary;
        }
        $maxSalary = (int) ($params['max_salary'] ?? 0);
        if ($maxSalary > 0) {
            $conditions[] = '(j.max_salary IS NULL OR j.max_salary <= ?)';
            $bind[] = $maxSalary;
        }
        $this->appendJobTypeCondition($conditions, $bind, $params);
        $this->appendMinEducationCondition($conditions, $bind, $params);
        $this->appendExperienceLevelCondition($conditions, $bind, $params);
        $this->appendUpdatedCondition($conditions, $params);
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
        $minSalary = (int) ($params['min_salary'] ?? 0);
        if ($minSalary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary >= ?)';
            $bind[] = $minSalary;
        }
        $maxSalary = (int) ($params['max_salary'] ?? 0);
        if ($maxSalary > 0) {
            $conditions[] = '(j.max_salary IS NULL OR j.max_salary <= ?)';
            $bind[] = $maxSalary;
        }
        $this->appendJobTypeCondition($conditions, $bind, $params);
        $this->appendMinEducationCondition($conditions, $bind, $params);
        $this->appendExperienceLevelCondition($conditions, $bind, $params);
        $this->appendUpdatedCondition($conditions, $params);
        $where = empty($conditions) ? '' : 'WHERE ' . implode(' AND ', $conditions);
        $offset = max(0, ($page - 1) * $perPage);
        $sql = "SELECT j.*, u.name AS created_by_name FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by $where
            ORDER BY j.created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $i => $v) {
            $stmt->bindValue($i + 1, $v);
        }
        $stmt->bindValue(count($bind) + 1, (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(count($bind) + 2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Count jobs matching search/filter for a specific user view:
     * - saved: only jobs saved by user
     * - applied: only jobs applied by user
     * @param array<string,mixed> $params
     */
    public function countSearchAndFilterByUserView(array $params, int $userId, string $jobView): int {
        $jobView = in_array($jobView, ['saved', 'applied'], true) ? $jobView : 'saved';

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
        $minSalary = (int) ($params['min_salary'] ?? 0);
        if ($minSalary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary >= ?)';
            $bind[] = $minSalary;
        }
        $maxSalary = (int) ($params['max_salary'] ?? 0);
        if ($maxSalary > 0) {
            $conditions[] = '(j.max_salary IS NULL OR j.max_salary <= ?)';
            $bind[] = $maxSalary;
        }
        $this->appendJobTypeCondition($conditions, $bind, $params);
        $this->appendMinEducationCondition($conditions, $bind, $params);
        $this->appendExperienceLevelCondition($conditions, $bind, $params);
        $this->appendUpdatedCondition($conditions, $params);

        $where = empty($conditions) ? '' : ' AND ' . implode(' AND ', $conditions);
        if ($jobView === 'saved') {
            $sql = "SELECT COUNT(*)
                FROM jobs j
                INNER JOIN saved_jobs sj ON sj.job_id = j.id AND sj.user_id = ?
                WHERE 1=1 $where";
        } else {
            $sql = "SELECT COUNT(DISTINCT j.id)
                FROM jobs j
                INNER JOIN applications a ON a.job_id = j.id AND a.user_id = ?
                WHERE 1=1 $where";
        }

        array_unshift($bind, $userId);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($bind);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Search & filter with pagination for a specific user view (saved/applied)
     * @param array<string,mixed> $params
     */
    public function searchAndFilterPaginatedByUserView(array $params, int $userId, string $jobView, int $page = 1, int $perPage = 20): array {
        $jobView = in_array($jobView, ['saved', 'applied'], true) ? $jobView : 'saved';

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
        $minSalary = (int) ($params['min_salary'] ?? 0);
        if ($minSalary > 0) {
            $conditions[] = '(j.min_salary IS NULL OR j.min_salary >= ?)';
            $bind[] = $minSalary;
        }
        $maxSalary = (int) ($params['max_salary'] ?? 0);
        if ($maxSalary > 0) {
            $conditions[] = '(j.max_salary IS NULL OR j.max_salary <= ?)';
            $bind[] = $maxSalary;
        }
        $this->appendJobTypeCondition($conditions, $bind, $params);
        $this->appendMinEducationCondition($conditions, $bind, $params);
        $this->appendExperienceLevelCondition($conditions, $bind, $params);
        $this->appendUpdatedCondition($conditions, $params);

        $where = empty($conditions) ? '' : ' AND ' . implode(' AND ', $conditions);
        $offset = max(0, ($page - 1) * $perPage);
        $perPage = (int) $perPage;
        $offset = (int) $offset;

        if ($jobView === 'saved') {
            $sql = "SELECT j.*, u.name AS created_by_name
                FROM jobs j
                LEFT JOIN users u ON u.id = j.created_by
                INNER JOIN saved_jobs sj ON sj.job_id = j.id AND sj.user_id = ?
                WHERE 1=1 $where
                ORDER BY j.created_at DESC
                LIMIT ? OFFSET ?";
        } else {
            $sql = "SELECT DISTINCT j.*, u.name AS created_by_name
                FROM jobs j
                LEFT JOIN users u ON u.id = j.created_by
                INNER JOIN applications a ON a.job_id = j.id AND a.user_id = ?
                WHERE 1=1 $where
                ORDER BY j.created_at DESC
                LIMIT ? OFFSET ?";
        }

        array_unshift($bind, $userId);
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $i => $v) {
            $stmt->bindValue($i + 1, $v);
        }
        $stmt->bindValue(count($bind) + 1, (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(count($bind) + 2, (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
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

    /**
     * Ambil lowongan serupa berdasarkan prioritas:
     * 1) nama pekerjaan, 2) tempat pekerjaan, 3) jenis pekerjaan, 4) gaji
     */
    public function findRelatedJobs(array $job, int $limit = 6): array {
        $jobId = (int) ($job['id'] ?? 0);
        if ($jobId < 1) {
            return [];
        }

        $title = trim((string) ($job['title'] ?? ''));
        $titleKeyword = '';
        if ($title !== '') {
            $titleParts = preg_split('/\s+/u', $title) ?: [];
            $titleKeyword = (string) ($titleParts[0] ?? '');
        }
        $location = trim((string) ($job['location'] ?? ''));
        $jobType = trim((string) ($job['job_type'] ?? ''));
        $minSalary = (int) ($job['min_salary'] ?? 0);
        $maxSalary = (int) ($job['max_salary'] ?? 0);
        $salaryPoint = $minSalary > 0 ? $minSalary : $maxSalary;

        $limit = max(1, min(20, $limit));
        $sql = "SELECT j.*, u.name AS created_by_name,
                (
                    CASE
                        WHEN ? <> '' AND LOWER(j.title) = LOWER(?) THEN 400
                        WHEN ? <> '' AND LOWER(j.title) LIKE LOWER(?) THEN 300
                        WHEN ? <> '' AND LOWER(j.title) LIKE LOWER(?) THEN 200
                        ELSE 0
                    END
                    +
                    CASE
                        WHEN ? <> '' AND LOWER(j.location) = LOWER(?) THEN 120
                        WHEN ? <> '' AND LOWER(j.location) LIKE LOWER(?) THEN 80
                        ELSE 0
                    END
                    +
                    CASE
                        WHEN ? <> '' AND j.job_type = ? THEN 60
                        ELSE 0
                    END
                    +
                    CASE
                        WHEN ? > 0 AND j.min_salary IS NOT NULL AND j.max_salary IS NOT NULL
                            AND j.min_salary <= ? AND j.max_salary >= ? THEN 40
                        ELSE 0
                    END
                ) AS similarity_score
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            WHERE j.id <> ?
            ORDER BY similarity_score DESC, j.created_at DESC
            LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $params = [
            $title, $title,
            $title, '%' . $title . '%',
            $titleKeyword, '%' . $titleKeyword . '%',
            $location, $location,
            $location, '%' . $location . '%',
            $jobType, $jobType,
            $salaryPoint, $salaryPoint, $salaryPoint,
            $jobId,
        ];
        foreach ($params as $i => $v) {
            $stmt->bindValue($i + 1, $v);
        }
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
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
    public function countAllFiltered(string $filter): int {
        $filter = $this->normalizeFilter($filter);
        $base = 'SELECT COUNT(*) FROM jobs j WHERE 1=1';
        $where = $this->filterWhereClause($filter);
        $sql = $base . $where;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    private function normalizeFilter(string $filter): string {
        $allowed = ['all', 'no_apply', 'has_apply', 'has_accepted'];
        return in_array($filter, $allowed, true) ? $filter : 'all';
    }

    private function filterWhereClause(string $filter): string {
        if ($filter === 'no_apply') {
            return ' AND NOT EXISTS (SELECT 1 FROM applications a WHERE a.job_id = j.id)';
        }
        if ($filter === 'has_apply') {
            return ' AND EXISTS (SELECT 1 FROM applications a WHERE a.job_id = j.id)';
        }
        if ($filter === 'has_accepted') {
            return " AND EXISTS (SELECT 1 FROM applications a WHERE a.job_id = j.id AND a.status = 'accepted')";
        }
        return '';
    }

    /**
     * Optimized query to fetch all jobs with their application statistics in a single call.
     * Removes the N+1 problem in the HR dashboard.
     */
    public function findAllPaginatedWithStats(int $page = 1, int $perPage = 10, string $filter = 'all'): array {
        $filter = $this->normalizeFilter($filter);
        $offset = max(0, ($page - 1) * $perPage);
        $perPage = (int) $perPage;
        $offset = (int) $offset;
        $where = $this->filterWhereClause($filter);
        
        $sql = "SELECT j.*, u.name AS created_by_name,
                COALESCE(stats.total_count, 0) as applicant_count,
                COALESCE(stats.accepted_count, 0) as applicant_accepted,
                COALESCE(stats.rejected_count, 0) as applicant_rejected
            FROM jobs j
            LEFT JOIN users u ON u.id = j.created_by
            LEFT JOIN (
                SELECT job_id, 
                       COUNT(*) as total_count,
                       SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted_count,
                       SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count
                FROM applications
                GROUP BY job_id
            ) AS stats ON stats.job_id = j.id
            WHERE 1=1 $where
            ORDER BY j.created_at DESC
            LIMIT :limit OFFSET :offset";
            
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): int {
        try {
            Database::beginTransaction();
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
            $id = (int) $this->db->lastInsertId();
            Database::commit();
            return $id;
        } catch (Throwable $e) {
            Database::rollBack();
            Logger::error('Failed to create job: ' . $e->getMessage());
            throw $e;
        }
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

    /**
     * Support single or multi value from `experience_level`.
     * Multi values are expected as comma-separated string.
     *
     * @param array<int,string> $conditions
     * @param array<int,mixed> $bind
     * @param array<string,mixed> $params
     */
    private function appendExperienceLevelCondition(array &$conditions, array &$bind, array $params): void {
        if (!$this->hasJobsColumn('experience_level')) {
            return;
        }
        $raw = trim((string) ($params['experience_level'] ?? ''));
        if ($raw === '') {
            return;
        }

        $levels = array_values(array_unique(array_filter(array_map('trim', explode(',', $raw)), fn($v) => $v !== '')));
        if ($levels === []) {
            return;
        }

        if (count($levels) === 1) {
            $conditions[] = 'j.experience_level = ?';
            $bind[] = $levels[0];
            return;
        }

        $placeholders = implode(', ', array_fill(0, count($levels), '?'));
        $conditions[] = "j.experience_level IN ($placeholders)";
        foreach ($levels as $lvl) {
            $bind[] = $lvl;
        }
    }

    /**
     * @param array<int,string> $conditions
     * @param array<int,mixed> $bind
     * @param array<string,mixed> $params
     */
    private function appendJobTypeCondition(array &$conditions, array &$bind, array $params): void {
        $this->appendCsvColumnCondition('j.job_type', $conditions, $bind, (string) ($params['job_type'] ?? ''));
    }

    /**
     * @param array<int,string> $conditions
     * @param array<int,mixed> $bind
     * @param array<string,mixed> $params
     */
    private function appendMinEducationCondition(array &$conditions, array &$bind, array $params): void {
        $this->appendCsvColumnCondition('j.min_education', $conditions, $bind, (string) ($params['min_education'] ?? ''));
    }

    /**
     * @param array<int,string> $conditions
     * @param array<int,mixed> $bind
     */
    private function appendCsvColumnCondition(string $column, array &$conditions, array &$bind, string $raw): void {
        if (preg_match('/^j\.([a-zA-Z_][a-zA-Z0-9_]*)$/', $column, $m) === 1) {
            if (!$this->hasJobsColumn($m[1])) {
                return;
            }
        }
        $raw = trim($raw);
        if ($raw === '') {
            return;
        }
        $values = array_values(array_unique(array_filter(array_map('trim', explode(',', $raw)), fn($v) => $v !== '')));
        if ($values === []) {
            return;
        }
        if (count($values) === 1) {
            $conditions[] = "$column = ?";
            $bind[] = $values[0];
            return;
        }
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $conditions[] = "$column IN ($placeholders)";
        foreach ($values as $value) {
            $bind[] = $value;
        }
    }

    /**
     * @param array<int,string> $conditions
     * @param array<string,mixed> $params
     */
    private function appendUpdatedCondition(array &$conditions, array $params): void {
        $updated = trim((string) ($params['updated'] ?? ''));
        if ($updated === 'week') {
            $conditions[] = 'YEARWEEK(j.created_at, 1) = YEARWEEK(CURDATE(), 1)';
            return;
        }
        if ($updated === 'month') {
            $conditions[] = 'YEAR(j.created_at) = YEAR(CURDATE()) AND MONTH(j.created_at) = MONTH(CURDATE())';
            return;
        }
        if ($updated === 'year') {
            $conditions[] = 'YEAR(j.created_at) = YEAR(CURDATE())';
        }
    }

    private function hasJobsColumn(string $column): bool {
        if ($column === '') {
            return false;
        }
        if (self::$jobsColumnMap === null) {
            self::$jobsColumnMap = [];
            try {
                $stmt = $this->db->query("
                    SELECT COLUMN_NAME
                    FROM INFORMATION_SCHEMA.COLUMNS
                    WHERE TABLE_SCHEMA = DATABASE()
                      AND TABLE_NAME = 'jobs'
                ");
                $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_COLUMN) : [];
                foreach ($rows as $col) {
                    self::$jobsColumnMap[(string) $col] = true;
                }
            } catch (Throwable $e) {
                // Fail-safe: avoid fatal query by treating optional columns as unavailable.
                self::$jobsColumnMap = [];
            }
        }
        return isset(self::$jobsColumnMap[$column]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM jobs WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function isCreatedBy(int $jobId, int $userId): bool {
        // Since the platform is global per company, we check if the user is an HR.
        // The original logic checks if the user is THE creator.
        // We'll return true if HR, but keep original for user-facing logic if needed.
        $stmt = $this->db->prepare('SELECT j.created_by, u.role FROM jobs j JOIN users u ON u.id = ? WHERE j.id = ?');
        $stmt->execute([$userId, $jobId]);
        $row = $stmt->fetch();
        if (!$row) return false;
        return $row['role'] === 'hr' || (int)$row['created_by'] === $userId;
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
