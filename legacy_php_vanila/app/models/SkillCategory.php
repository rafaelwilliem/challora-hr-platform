<?php
class SkillCategory {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function all(): array {
        $stmt = $this->db->query('SELECT id, name FROM skill_categories ORDER BY name');
        return $stmt->fetchAll();
    }
}
