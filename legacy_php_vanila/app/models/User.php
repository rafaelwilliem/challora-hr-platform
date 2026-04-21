<?php
class User {
    private PDO $db;
    private static ?array $usersTableColumns = null;

    public function __construct() {
        $this->db = getDB();
    }

    public function create(string $name, string $email, string $password, string $role = 'user', ?string $phone = null, ?string $address = null): int {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, role, phone, address) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $hash, $role, $phone, $address]);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function verifyPassword(string $plain, string $hash): bool {
        return password_verify($plain, $hash);
    }

    public function update(int $id, array $data): bool {
        $allowed = [
            'name', 'phone', 'address',
            'father_name', 'mother_name', 'marital_status',
            'education_level', 'graduation_year', 'education_major', 'education_university',
            'gender', 'religion', 'social_media', 'birth_place', 'birth_date',
            'father_job', 'mother_job', 'father_education', 'mother_education',
            'father_phone', 'mother_phone',
            'address_type', 'address_family',
            'emergency_name', 'emergency_phone',
            'user_summary',
            'avatar_path',
            'cv_path',
            'diploma_path',
            'photo_path',
        ];
        $existingColumns = $this->getUsersTableColumns();
        $set = [];
        $params = [];
        foreach ($allowed as $k) {
            if (
                array_key_exists($k, $data) &&
                (empty($existingColumns) || in_array($k, $existingColumns, true))
            ) {
                $set[] = "`$k` = ?";
                $params[] = $data[$k];
            }
        }
        if (empty($set)) return false;
        $params[] = $id;
        $sql = 'UPDATE users SET ' . implode(', ', $set) . ' WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    private function getUsersTableColumns(): array {
        if (is_array(self::$usersTableColumns)) {
            return self::$usersTableColumns;
        }
        try {
            $stmt = $this->db->query('SHOW COLUMNS FROM users');
            $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            self::$usersTableColumns = array_values(array_filter(array_map(
                static fn(array $row): string => (string) ($row['Field'] ?? ''),
                $rows
            )));
            return self::$usersTableColumns;
        } catch (Throwable $e) {
            // Fallback: don't block update flow if schema inspection fails.
            self::$usersTableColumns = [];
            return self::$usersTableColumns;
        }
    }

    /** Work experiences */
    public function getWorkExperiences(int $userId): array {
        $stmt = $this->db->prepare('SELECT * FROM user_work_experiences WHERE user_id = ? ORDER BY sort_order, year_start DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /** Achievements (returns [] if table does not exist) */
    public function getAchievements(int $userId): array {
        try {
            $stmt = $this->db->prepare('SELECT * FROM user_achievements WHERE user_id = ? ORDER BY year DESC, id ASC');
            $stmt->execute([$userId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    /** Bulk fetch work experiences for multiple users */
    public function getWorkExperiencesForUsers(array $userIds): array {
        if (empty($userIds)) return [];
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        $stmt = $this->db->prepare("SELECT * FROM user_work_experiences WHERE user_id IN ($placeholders) ORDER BY sort_order, year_start DESC");
        $stmt->execute($userIds);
        $results = $stmt->fetchAll();
        $grouped = [];
        foreach ($results as $row) {
            $grouped[(int)$row['user_id']][] = $row;
        }
        return $grouped;
    }

    /** Bulk fetch achievements for multiple users */
    public function getAchievementsForUsers(array $userIds): array {
        if (empty($userIds)) return [];
        $placeholders = implode(',', array_fill(0, count($userIds), '?'));
        try {
            $stmt = $this->db->prepare("SELECT * FROM user_achievements WHERE user_id IN ($placeholders) ORDER BY year DESC, id ASC");
            $stmt->execute($userIds);
            $results = $stmt->fetchAll();
            $grouped = [];
            foreach ($results as $row) {
                $grouped[(int)$row['user_id']][] = $row;
            }
            return $grouped;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function setAchievements(int $userId, array $items): void {
        try {
            Database::beginTransaction();
            $this->db->prepare('DELETE FROM user_achievements WHERE user_id = ?')->execute([$userId]);
            $stmt = $this->db->prepare('INSERT INTO user_achievements (user_id, type, title, description, organizer, year, rank, level, certificate_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            foreach ($items as $row) {
                if (!empty(trim($row['title'] ?? ''))) {
                    $stmt->execute([
                        $userId,
                        trim($row['type'] ?? ''),
                        trim($row['title']),
                        trim($row['description'] ?? ''),
                        trim($row['organizer'] ?? ''),
                        trim($row['year'] ?? ''),
                        trim($row['rank'] ?? ''),
                        trim($row['level'] ?? ''),
                        trim($row['certificate_link'] ?? ''),
                    ]);
                }
            }
            Database::commit();
        } catch (Throwable $e) {
            Database::rollBack();
            Logger::error('Failed to set achievements: ' . $e->getMessage());
        }
    }

    public function setWorkExperiences(int $userId, array $items): void {
        try {
            Database::beginTransaction();
            $this->db->prepare('DELETE FROM user_work_experiences WHERE user_id = ?')->execute([$userId]);
            $stmt = $this->db->prepare('INSERT INTO user_work_experiences (user_id, title, company_name, year_start, year_end, description, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)');
            foreach ($items as $i => $row) {
                if (!empty(trim($row['title'] ?? ''))) {
                    $stmt->execute([
                        $userId,
                        trim($row['title']),
                        trim($row['company_name'] ?? ''),
                        trim($row['year_start'] ?? ''),
                        trim($row['year_end'] ?? ''),
                        trim($row['description'] ?? ''),
                        $i
                    ]);
                }
            }
            Database::commit();
        } catch (Throwable $e) {
            Database::rollBack();
            Logger::error('Failed to set work experiences: ' . $e->getMessage());
        }
    }

    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET password = ? WHERE id = ?');
        return $stmt->execute([$hash, $id]);
    }

    /** Password reset token management */
    public function createPasswordResetToken(int $userId, int $hoursValid = 1): string {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + $hoursValid * 3600);
        $stmt = $this->db->prepare('INSERT INTO password_resets (user_id, token, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$userId, $token, $expires]);
        return $token;
    }

    public function getPasswordResetByToken(string $token): ?array {
        $stmt = $this->db->prepare(
            'SELECT pr.user_id, pr.expires_at, u.email, u.name
             FROM password_resets pr
             JOIN users u ON u.id = pr.user_id
             WHERE pr.token = ?'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();
        if (!$row) return null;
        $expires = strtotime($row['expires_at']);
        if ($expires === false || $expires < time()) {
            $this->invalidatePasswordResetToken($token);
            return null;
        }
        return $row;
    }

    public function invalidatePasswordResetToken(string $token): void {
        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE token = ?');
        $stmt->execute([$token]);
    }
}
