<?php
// Start session
session_start();

// Include configuration files
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

// Include models and controllers
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Redirect if already logged in
if (auth()) {
    redirect('/');
}

$error = '';
$success = '';

// Handle login submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $authController = new AuthController();
    $result = $authController->login($email, $password);
    
    if ($result['success']) {
        flash('success', 'Welcome back, ' . $result['user']['name'] . '!');
        redirect('/');
    } else {
        $error = $result['error'];
    }
}

// Handle registration submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'confirm_password' => $_POST['confirm_password'] ?? '',
        'matric_number' => trim($_POST['matric_number'] ?? ''),
        'department' => trim($_POST['department'] ?? ''),
        'level' => trim($_POST['level'] ?? '')
    ];
    
    // Validate password confirmation
    if ($data['password'] !== $data['confirm_password']) {
        $error = 'Passwords do not match';
    } else {
        $authController = new AuthController();
        $result = $authController->register($data);
        
        if ($result['success']) {
            $success = 'Registration successful! Please login.';
        } else {
            $error = $result['errors'][array_key_first($result['errors'])] ?? 'Registration failed';
        }
    }
}

// Show login or register form
$showRegister = $_GET['register'] ?? false;

include __DIR__ . '/../app/Views/auth/login.php';