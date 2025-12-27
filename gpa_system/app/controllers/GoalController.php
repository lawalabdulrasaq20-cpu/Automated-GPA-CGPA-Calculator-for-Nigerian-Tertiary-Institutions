<?php

class GoalController {
    private $goalModel;
    private $semesterModel;
    
    public function __construct() {
        $this->goalModel = new Goal();
        $this->semesterModel = new Semester();
    }
    
    public function create(array $data): array {
        // Validate input
        $errors = $this->validateGoal($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        // Create goal
        $goalId = $this->goalModel->create($data);
        
        if ($goalId) {
            return ['success' => true, 'goal_id' => $goalId];
        }
        
        return ['success' => false, 'error' => 'Failed to create goal'];
    }
    
    public function update(int $goalId, array $data): array {
        $goal = $this->goalModel->findById($goalId);
        if (!$goal) {
            return ['success' => false, 'error' => 'Goal not found'];
        }
        
        // Validate input
        $errors = $this->validateGoal($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }
        
        $updated = $this->goalModel->update($goalId, $data);
        
        return ['success' => $updated];
    }
    
    public function delete(int $goalId): array {
        $goal = $this->goalModel->findById($goalId);
        if (!$goal) {
            return ['success' => false, 'error' => 'Goal not found'];
        }
        
        $deleted = $this->goalModel->delete($goalId);
        
        return ['success' => $deleted];
    }
    
    public function getGoalProgress(int $userId, int $goalId): array {
        $goal = $this->goalModel->findById($goalId);
        if (!$goal || $goal['user_id'] != $userId) {
            return ['success' => false, 'error' => 'Goal not found'];
        }
        
        // Get current CGPA
        $cgpaResult = GPAHelper::calculateCGPA($userId);
        $currentCGPA = $cgpaResult['cgpa'];
        
        // Calculate progress
        $progress = ($currentCGPA / $goal['target_cgpa']) * 100;
        $progress = min(100, max(0, $progress));
        
        // Determine status
        $status = 'active';
        if ($currentCGPA >= $goal['target_cgpa']) {
            $status = 'achieved';
        }
        
        // Get required performance
        $totalSemesters = $this->semesterModel->getTotalSemesters($userId);
        $remainingSemesters = max(1, $goal['target_semesters'] - $totalSemesters);
        
        $requiredGPA = GPAHelper::getRequiredGPAForTarget(
            $currentCGPA,
            $goal['target_cgpa'],
            $totalSemesters,
            $remainingSemesters
        );
        
        $canAchieve = GPAHelper::canAchieveTarget(
            $currentCGPA,
            $goal['target_cgpa'],
            $totalSemesters,
            $remainingSemesters
        );
        
        return [
            'success' => true,
            'goal' => $goal,
            'current_cgpa' => $currentCGPA,
            'progress' => $progress,
            'status' => $status,
            'required_gpa' => $requiredGPA,
            'can_achieve' => $canAchieve,
            'remaining_semesters' => $remainingSemesters
        ];
    }
    
    public function getGoalsWithProgress(int $userId): array {
        $goals = $this->goalModel->findByUser($userId);
        $goalsWithProgress = [];
        
        foreach ($goals as $goal) {
            $progress = $this->getGoalProgress($userId, $goal['id']);
            if ($progress['success']) {
                $goalsWithProgress[] = $progress;
            }
        }
        
        return $goalsWithProgress;
    }
    
    private function validateGoal(array $data): array {
        $errors = [];
        
        if (empty($data['target_gpa']) || !is_numeric($data['target_gpa'])) {
            $errors['target_gpa'] = 'Target GPA is required and must be a number';
        } elseif ($data['target_gpa'] < 0 || $data['target_gpa'] > 5.0) {
            $errors['target_gpa'] = 'Target GPA must be between 0 and 5.0';
        }
        
        if (empty($data['target_cgpa']) || !is_numeric($data['target_cgpa'])) {
            $errors['target_cgpa'] = 'Target CGPA is required and must be a number';
        } elseif ($data['target_cgpa'] < 0 || $data['target_cgpa'] > 5.0) {
            $errors['target_cgpa'] = 'Target CGPA must be between 0 and 5.0';
        }
        
        if (!empty($data['target_semesters']) && (!is_numeric($data['target_semesters']) || $data['target_semesters'] < 1)) {
            $errors['target_semesters'] = 'Target semesters must be a positive integer';
        }
        
        return $errors;
    }
}