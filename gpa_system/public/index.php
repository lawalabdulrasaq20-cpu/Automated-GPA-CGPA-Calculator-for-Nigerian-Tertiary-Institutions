<?php
// =======================================
// Dashboard (index.php)
// =======================================

// Load configuration & database
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Require authentication
require_auth();

// =======================================
// Load models
// =======================================

require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Course.php';
require_once __DIR__ . '/../app/Models/Semester.php';
require_once __DIR__ . '/../app/Models/Goal.php';

// =======================================
// Load helpers
// =======================================

require_once __DIR__ . '/../app/Helpers/GPAHelper.php';
require_once __DIR__ . '/../app/Helpers/PDFHelper.php';

// =======================================
// Load controllers
// =======================================

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/CourseController.php';
require_once __DIR__ . '/../app/Controllers/ResultController.php';
require_once __DIR__ . '/../app/Controllers/GoalController.php';

// =======================================
// Get authenticated user
// =======================================

$user = auth();

$semesterModel = new Semester();
$courseModel   = new Course();

// Get user's semesters
$semesters = $semesterModel->findByUser($user['id']);

// Calculate CGPA
$cgpaResult = GPAHelper::calculateCGPA($user['id']);
$academicStanding = GPAHelper::getAcademicStanding($cgpaResult['cgpa']);

// =======================================
// Handle semester selection
// =======================================

$selectedSemesterId = $_GET['semester'] ?? ($semesters[0]['id'] ?? null);

$courses = [];
$semesterGPA = [
    'gpa' => 0.00,
    'total_units' => 0,
    'total_points' => 0
];

if ($selectedSemesterId) {
    $courses = $courseModel->findByUserAndSemester($user['id'], $selectedSemesterId);
    $semesterGPA = GPAHelper::calculateGPA($courses);
}

// =======================================
// Handle POST actions
// =======================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    switch ($action) {

        case 'add_course':
            $courseController = new CourseController();

            $result = $courseController->create([
                'user_id'       => $user['id'],
                'semester_id'   => $_POST['semester_id'],
                'course_code'   => $_POST['course_code'],
                'course_title'  => $_POST['course_title'],
                'units'         => (int) $_POST['units'],
                'grade'         => $_POST['grade'],
                'score'         => !empty($_POST['score']) ? (int) $_POST['score'] : null
            ]);

            if ($result['success']) {
                flash('success', 'Course added successfully!');
            } else {
                flash('error', $result['error'] ?? 'Failed to add course');
            }

            redirect();
            break;

        case 'add_semester':
            $semesterName = trim($_POST['semester_name']);

            if ($semesterName === '') {
                flash('error', 'Semester name is required');
                redirect();
            }

            $semesterId = $semesterModel->create([
                'user_id'       => $user['id'],
                'semester_name' => $semesterName,
                'session'       => $_POST['session'] ?? null
            ]);

            if ($semesterId) {
                flash('success', 'Semester created successfully!');
                redirect('?semester=' . $semesterId);
            } else {
                flash('error', 'Failed to create semester');
                redirect();
            }
            break;
    }
}

// =======================================
// Load dashboard view
// =======================================

require __DIR__ . '/../app/Views/dashboard/index.php';
