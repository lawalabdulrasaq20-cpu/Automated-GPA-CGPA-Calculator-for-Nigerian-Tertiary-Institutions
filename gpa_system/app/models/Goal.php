<?php

class Goal {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function create(array $data): int {
        $sql = "INSERT INTO goals (user_id, target_gpa, target_cgpa, target_semesters) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['target_gpa'],
            $data['target_cgpa'],
            $data['target_semesters'] ?? 1
        ]);
        return $this->db->lastInsertId();
    }
    
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM goals WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    
    public function findByUser(int $userId): array {
        $sql = "SELECT * FROM goals WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findActiveByUser(int $userId): array {
        $sql = "SELECT * FROM goals WHERE user_id = ? AND status = 'active' ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function updateProgress(int $goalId, float $currentProgress, string $status): bool {
        $sql = "UPDATE goals SET current_progress = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$currentProgress, $status, $goalId]);
    }
    
    public function update(int $id, array $data): bool {
        $sql = "UPDATE goals SET target_gpa = ?, target_cgpa = ?, target_semesters = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['target_gpa'],
            $data['target_cgpa'],
            $data['target_semesters'] ?? 1,
            $id
        ]);
    }
    
    public function delete(int $id): bool {
        $sql = "DELETE FROM goals WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}