<?php

class ResultController {
    private $courseModel;
    private $semesterModel;
    
    public function __construct() {
        $this->courseModel = new Course();
        $this->semesterModel = new Semester();
    }
    
    public function getSemesterResults(int $userId, int $semesterId): array {
        $courses = $this->courseModel->findByUserAndSemester($userId, $semesterId);
        return GPAHelper::calculateGPA($courses);
    }
    
    public function getCumulativeResults(int $userId): array {
        return GPAHelper::calculateCGPA($userId);
    }
    
    public function getAcademicStanding(int $userId): string {
        $cgpaResult = GPAHelper::calculateCGPA($userId);
        return GPAHelper::getAcademicStanding($cgpaResult['cgpa']);
    }
    
    public function getPerformanceTrend(int $userId): array {
        $semesters = $this->semesterModel->findByUser($userId);
        $trend = [];
        
        foreach ($semesters as $semester) {
            $courses = $this->courseModel->findByUserAndSemester($userId, $semester['id']);
            $result = GPAHelper::calculateGPA($courses);
            
            $trend[] = [
                'semester' => $semester['semester_name'],
                'gpa' => $result['gpa'],
                'units' => $result['total_units'],
                'points' => $result['total_points']
            ];
        }
        
        return $trend;
    }
    
    public function generateTranscript(int $userId): array {
        $pdfResult = PDFHelper::generateResultPDF($userId);
        
        if ($pdfResult['success']) {
            return [
                'success' => true,
                'file_path' => $pdfResult['file_path'],
                'message' => 'Transcript generated successfully'
            ];
        }
        
        return [
            'success' => false,
            'error' => $pdfResult['error'] ?? 'Failed to generate transcript'
        ];
    }
    
    public function getGradeDistribution(int $userId): array {
        $courses = $this->courseModel->getByUser($userId);
        $distribution = [
            'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'E' => 0, 'F' => 0
        ];
        
        foreach ($courses as $course) {
            if (isset($distribution[$course['grade']])) {
                $distribution[$course['grade']]++;
            }
        }
        
        return $distribution;
    }
    
    public function getStatistics(int $userId): array {
        $cgpaResult = GPAHelper::calculateCGPA($userId);
        $academicStanding = GPAHelper::getAcademicStanding($cgpaResult['cgpa']);
        $gradeDistribution = $this->getGradeDistribution($userId);
        
        return [
            'cgpa' => $cgpaResult['cgpa'],
            'total_units' => $cgpaResult['total_units'],
            'total_points' => $cgpaResult['total_points'],
            'academic_standing' => $academicStanding,
            'grade_distribution' => $gradeDistribution,
            'semesters_count' => $this->semesterModel->getTotalSemesters($userId)
        ];
    }
}