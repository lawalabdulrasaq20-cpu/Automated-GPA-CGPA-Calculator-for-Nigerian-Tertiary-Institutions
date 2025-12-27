<?php
// =======================================
// Application configuration
// =======================================

// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set timezone
date_default_timezone_set('Africa/Lagos');

// Environment (development / production)
$env = $_ENV['APP_ENV'] ?? 'development';

if ($env === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// =======================================
// Application constants
// =======================================

define('APP_NAME', 'GPA & CGPA Calculator');
define('APP_VERSION', '1.0.0');

// ✅ CORRECT BASE URL (VERY IMPORTANT)
define('BASE_URL', $_ENV['BASE_URL'] ?? 'http://localhost/gpa_system/public');

// =======================================
// Load grading configuration
// =======================================

$gradingConfig = require __DIR__ . '/grading.php';

// =======================================
// Helper functions
// =======================================

function grading_config(string $key = null) {
    global $gradingConfig;
    return $key === null ? $gradingConfig : ($gradingConfig[$key] ?? null);
}

/**
 * Redirect helper
 * Usage:
 *   redirect();            → dashboard (index.php)
 *   redirect('login.php'); → login page
 */
function redirect(string $path = ''): void {
    $url = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    header("Location: $url");
    exit;
}

function dd($data): void {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
}

function e(string $string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function old(string $key, string $default = ''): string {
    return $_SESSION['old'][$key] ?? $default;
}

function flash(string $key, string $message = null): ?string {
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    return null;
}

function auth(): ?array {
    return $_SESSION['user'] ?? null;
}

function require_auth(): void {
    if (!auth()) {
        flash('error', 'Please login to access this page.');
        redirect('login.php');
    }
}
