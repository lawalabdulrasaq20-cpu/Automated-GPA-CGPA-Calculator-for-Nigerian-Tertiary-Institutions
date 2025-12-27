<?php

class CourseController {
    private $courseModel;
    private $semesterModel;
    
    public function __construct() {
        $this->courseModel = new Course();
        $this->semesterModel = new Semester();
    }
    
    public function create(array $data): array {
        // Validate input
        $errors = $this->validateCourse($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check if course already exists in this semester
        if ($this->courseModel->courseCodeExists($data['user_id'], $data['semester_id'], $data['course_code'])) {
            return ['success' => false, 'error' => 'Course already exists in this semester'];
        }
        
        // Create course
        $courseId = $this->courseModel->create($data);
        
        if ($courseId) {
            // Recalculate GPA for the user
            GPAHelper::recalculateGPA($data['user_id']);
            
            return ['success' => true, 'course_id' => $courseId];
        }
        
        return ['success' => false, 'error' => 'Failed to create course'];
    }
    
    public function update(int $courseId, array $data): array {
        $course = $this->courseModel->findById($courseId);
        if (!$course) {
            return ['success' => false, 'error' => 'Course not found'];
        }
        
        // Validate input
        $errors = $this->validateCourse($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Check if course code already exists (excluding current course)
        if ($this->courseModel->courseCodeExists($data['user_id'], $data['semester_id'], $data['course_code'], $courseId)) {
            return ['success' => false, 'error' => 'Course code already exists in this semester'];
        }
        
        // Update course
        $updated = $this->courseModel->update($courseId, $data);
        
        if ($updated) {
            // Recalculate GPA for the user
            GPAHelper::recalculateGPA($data['user_id']);
            
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'Failed to update course'];
    }
    
    public function delete(int $courseId, int $userId): array {
        $course = $this->courseModel->findById($courseId);
        if (!$course || $course['user_id'] != $userId) {
            return ['success' => false, 'error' => 'Course not found or access denied'];
        }
        
        $deleted = $this->courseModel->delete($courseId);
        
        if ($deleted) {
            // Recalculate GPA for the user
            GPAHelper::recalculateGPA($userId);
            
            return ['success' => true];
        }
        
        return ['success' => false, 'error' => 'Failed to delete course'];
    }
    
    public function getCoursesBySemester(int $userId, int $semesterId): array {
        return $this->courseModel->findByUserAndSemester($userId, $semesterId);
    }
    
    public function getCoursesByUser(int $userId): array {
        return $this->courseModel->getByUser($userId);
    }
    
    private function validateCourse(array $data): array {
        $errors = [];
        
        if (empty($data['course_code'])) {
            $errors['course_code'] = 'Course code is required';
        } elseif (strlen($data['course_code']) < 2 || strlen($data['course_code']) > 20) {
            $errors['course_code'] = 'Course code must be between 2 and 20 characters';
        }
        
        if (empty($data['course_title'])) {
            $errors['course_title'] = 'Course title is required';
        } elseif (strlen($data['course_title']) < 3 || strlen($data['course_title']) > 100) {
            $errors['course_title'] = 'Course title must be between 3 and 100 characters';
        }
        
        if (empty($data['units']) || !is_numeric($data['units'])) {
            $errors['units'] = 'Course units is required and must be a number';
        } elseif ($data['units'] < 1 || $data['units'] > 6) {
            $errors['units'] = 'Course units must be between 1 and 6';
        }
        
        if (empty($data['grade'])) {
            $errors['grade'] = 'Grade is required';
        } elseif (!in_array($data['grade'], ['A', 'B', 'C', 'D', 'E', 'F'])) {
            $errors['grade'] = 'Invalid grade selected';
        }
        
        if (!empty($data['score'])) {
            if (!is_numeric($data['score'])) {
                $errors['score'] = 'Score must be a number';
            } elseif ($data['score'] < 0 || $data['score'] > 100) {
                $errors['score'] = 'Score must be between 0 and 100';
            }
        }
        
        return $errors;
    }
}