<?php

class Course {
    private $db;
    
    public function __construct() {
        $this->db = db();
    }
    
    public function create(array $data): int {
        $gradePoints = $this->getGradePoints($data['grade']);
        $qualityPoints = $data['units'] * $gradePoints;
        
        $sql = "INSERT INTO courses (user_id, semester_id, course_code, course_title, units, grade, score, grade_points, quality_points) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['semester_id'],
            strtoupper($data['course_code']),
            $data['course_title'],
            $data['units'],
            $data['grade'],
            $data['score'] ?? null,
            $gradePoints,
            $qualityPoints
        ]);
        return $this->db->lastInsertId();
    }
    
    public function findById(int $id): ?array {
        $sql = "SELECT * FROM courses WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
    
    public function findByUserAndSemester(int $userId, int $semesterId): array {
        $sql = "SELECT * FROM courses WHERE user_id = ? AND semester_id = ? ORDER BY course_code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $semesterId]);
        return $stmt->fetchAll();
    }
    
    public function getByUser(int $userId): array {
        $sql = "SELECT c.*, s.semester_name, s.session 
                FROM courses c 
                JOIN semesters s ON c.semester_id = s.id 
                WHERE c.user_id = ? 
                ORDER BY s.created_at DESC, c.course_code";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function update(int $id, array $data): bool {
        $gradePoints = $this->getGradePoints($data['grade']);
        $qualityPoints = $data['units'] * $gradePoints;
        
        $sql = "UPDATE courses SET course_code = ?, course_title = ?, units = ?, grade = ?, score = ?, grade_points = ?, quality_points = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            strtoupper($data['course_code']),
            $data['course_title'],
            $data['units'],
            $data['grade'],
            $data['score'] ?? null,
            $gradePoints,
            $qualityPoints,
            $id
        ]);
    }
    
    public function delete(int $id): bool {
        $sql = "DELETE FROM courses WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function deleteBySemester(int $semesterId): bool {
        $sql = "DELETE FROM courses WHERE semester_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$semesterId]);
    }
    
    public function getTotalUnitsBySemester(int $userId, int $semesterId): int {
        $sql = "SELECT SUM(units) as total FROM courses WHERE user_id = ? AND semester_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $semesterId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    public function getTotalPointsBySemester(int $userId, int $semesterId): int {
        $sql = "SELECT SUM(quality_points) as total FROM courses WHERE user_id = ? AND semester_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $semesterId]);
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
    
    private function getGradePoints(string $grade): int {
        $gradingConfig = grading_config('grade_scale');
        return $gradingConfig[$grade]['points'] ?? 0;
    }
    
    public function courseCodeExists(int $userId, int $semesterId, string $courseCode, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM courses WHERE user_id = ? AND semester_id = ? AND course_code = ?";
        $params = [$userId, $semesterId, strtoupper($courseCode)];
        
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