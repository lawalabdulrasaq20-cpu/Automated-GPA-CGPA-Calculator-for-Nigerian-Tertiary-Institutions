<?php

class Semester {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function create(array $data): int {
        $sql = "INSERT INTO semesters (user_id, semester_name, session) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['semester_name'],
            $data['session'] ?? null
        ]);
        return $this->db->lastInsertId();
    }
    
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM semesters WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    
    public function findByUser(int $userId): array {
        $sql = "SELECT * FROM semesters WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function findByUserAndName(int $userId, string $semesterName): ?array {
        $sql = "SELECT * FROM semesters WHERE user_id = ? AND semester_name = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $semesterName]);
        return $stmt->fetch() ?: null;
    }
    
    public function updateGPA(int $semesterId, float $gpa, int $totalUnits, int $totalPoints): bool {
        $sql = "UPDATE semesters SET gpa = ?, total_units = ?, total_points = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$gpa, $totalUnits, $totalPoints, $semesterId]);
    }
    
    public function update(int $id, array $data): bool {
        $sql = "UPDATE semesters SET semester_name = ?, session = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['semester_name'],
            $data['session'] ?? null,
            $id
        ]);
    }
    
    public function delete(int $id): bool {
        // First delete all courses in this semester
        $courseModel = new Course();
        $courseModel->deleteBySemester($id);
        
        $sql = "DELETE FROM semesters WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getTotalSemesters(int $userId): int {
        $sql = "SELECT COUNT(*) as total FROM semesters WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    public function semesterNameExists(int $userId, string $semesterName, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM semesters WHERE user_id = ? AND semester_name = ?";
        $params = [$userId, $semesterName];
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return ($result['count'] ?? 0) > 0;
    }
}