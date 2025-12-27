<?php
// Start session
session_start();

// Include configuration
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Logout user
$authController = new AuthController();
$authController->logout();

// Redirect to login
flash('success', 'You have been logged out successfully.');
redirect('/login.php');