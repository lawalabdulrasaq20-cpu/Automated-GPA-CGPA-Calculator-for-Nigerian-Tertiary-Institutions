<?php

class GPAHelper {
    
    public static function calculateGPA(array $courses): array {
        if (empty($courses)) {
            return ['gpa' => 0.00, 'total_units' => 0, 'total_points' => 0];
        }
        
        $totalUnits = 0;
        $totalPoints = 0;
        
        foreach ($courses as $course) {
            $totalUnits += $course['units'];
            $totalPoints += $course['quality_points'];
        }
        
        if ($totalUnits === 0) {
            return ['gpa' => 0.00, 'total_units' => 0, 'total_points' => 0];
        }
        
        $gpa = round($totalPoints / $totalUnits, 2);
        
        return [
            'gpa' => $gpa,
            'total_units' => $totalUnits,
            'total_points' => $totalPoints
        ];
    }
    
    public static function calculateCGPA(int $userId): array {
        $semesterModel = new Semester();
        $courseModel = new Course();
        
        $semesters = $semesterModel->findByUser($userId);
        
        if (empty($semesters)) {
            return ['cgpa' => 0.00, 'total_units' => 0, 'total_points' => 0];
        }
        
        $cumulativeUnits = 0;
        $cumulativePoints = 0;
        
        foreach ($semesters as $semester) {
            $courses = $courseModel->findByUserAndSemester($userId, $semester['id']);
            $semesterResult = self::calculateGPA($courses);
            
            $cumulativeUnits += $semesterResult['total_units'];
            $cumulativePoints += $semesterResult['total_points'];
        }
        
        if ($cumulativeUnits === 0) {
            return ['cgpa' => 0.00, 'total_units' => 0, 'total_points' => 0];
        }
        
        $cgpa = round($cumulativePoints / $cumulativeUnits, 2);
        
        return [
            'cgpa' => $cgpa,
            'total_units' => $cumulativeUnits,
            'total_points' => $cumulativePoints
        ];
    }
    
    public static function getGradePoint(string $grade): int {
        $gradingConfig = grading_config('grade_scale');
        return $gradingConfig[$grade]['points'] ?? 0;
    }
    
    public static function getGradeFromScore(int $score): string {
        $gradingConfig = grading_config('grade_scale');
        
        foreach ($gradingConfig as $grade => $config) {
            if ($score >= $config['min'] && $score <= $config['max']) {
                return $grade;
            }
        }
        
        return 'F';
    }
    
    public static function recalculateGPA(int $userId): void {
        $semesterModel = new Semester();
        $courseModel = new Course();
        
        $semesters = $semesterModel->findByUser($userId);
        
        foreach ($semesters as $semester) {
            $courses = $courseModel->findByUserAndSemester($userId, $semester['id']);
            $result = self::calculateGPA($courses);
            
            $semesterModel->updateGPA(
                $semester['id'],
                $result['gpa'],
                $result['total_units'],
                $result['total_points']
            );
        }
    }
    
    public static function getAcademicStanding(float $cgpa): string {
        $standing = grading_config('standing');
        
        if ($cgpa >= $standing['first_class']) {
            return 'First Class';
        } elseif ($cgpa >= $standing['second_class_upper']) {
            return 'Second Class Upper';
        } elseif ($cgpa >= $standing['second_class_lower']) {
            return 'Second Class Lower';
        } elseif ($cgpa >= $standing['third_class']) {
            return 'Third Class';
        } elseif ($cgpa >= $standing['pass']) {
            return 'Pass';
        } else {
            return 'Fail';
        }
    }
    
    public static function getRequiredGPAForTarget(float $currentCGPA, float $targetCGPA, int $completedSemesters, int $remainingSemesters): float {
        if ($remainingSemesters <= 0) {
            return 0.0;
        }
        
        $totalSemesters = $completedSemesters + $remainingSemesters;
        $requiredTotal = ($targetCGPA * $totalSemesters) - ($currentCGPA * $completedSemesters);
        $requiredGPA = $requiredTotal / $remainingSemesters;
        
        return max(0.0, min(5.0, $requiredGPA));
    }
    
    public static function canAchieveTarget(float $currentCGPA, float $targetCGPA, int $completedSemesters, int $remainingSemesters): bool {
        $requiredGPA = self::getRequiredGPAForTarget($currentCGPA, $targetCGPA, $completedSemesters, $remainingSemesters);
        return $requiredGPA <= 5.0;
    }
}