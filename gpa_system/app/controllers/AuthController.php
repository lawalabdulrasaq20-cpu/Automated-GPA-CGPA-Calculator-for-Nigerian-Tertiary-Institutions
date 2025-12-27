<?php

// Load User model
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /* =====================
       AUTHENTICATION
    ====================== */

    public function register(array $data): array
    {
        $errors = $this->validateRegistration($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($this->userModel->findByEmail($data['email'])) {
            return ['success' => false, 'errors' => ['email' => 'Email already registered']];
        }

        $userId = $this->userModel->create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => $data['password'],
            'matric_number'  => $data['matric_number'] ?? null,
            'department'     => $data['department'] ?? null,
            'level'          => $data['level'] ?? null
        ]);

        return ['success' => true, 'user_id' => $userId];
    }

    public function login(string $email, string $password): array
    {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'error' => 'Email and password are required'];
        }

        $user = $this->userModel->verifyPassword($email, $password);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid email or password'];
        }

        $_SESSION['user'] = [
            'id'             => $user['id'],
            'name'           => $user['name'],
            'email'          => $user['email'],
            'matric_number'  => $user['matric_number'],
            'department'     => $user['department'],
            'level'          => $user['level']
        ];

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
    }

    /* =====================
       PROFILE
    ====================== */

    public function updateProfile(int $userId, array $data): array
    {
        $errors = $this->validateProfileUpdate($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $updated = $this->userModel->update($userId, $data);

        if ($updated) {
            $user = $this->userModel->findById($userId);
            $_SESSION['user'] = [
                'id'             => $user['id'],
                'name'           => $user['name'],
                'email'          => $user['email'],
                'matric_number'  => $user['matric_number'],
                'department'     => $user['department'],
                'level'          => $user['level']
            ];
        }

        return ['success' => $updated];
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        $user = $this->userModel->findById($userId);

        if (!password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'error' => 'Current password is incorrect'];
        }

        if (strlen($newPassword) < 6) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters'];
        }

        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = db()->prepare($sql);
        $updated = $stmt->execute([
            password_hash($newPassword, PASSWORD_BCRYPT),
            $userId
        ]);

        return ['success' => $updated];
    }

    /* =====================
       VALIDATION
    ====================== */

    private function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = 'Name must be at least 3 characters';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!empty($data['matric_number']) && strlen($data['matric_number']) < 3) {
            $errors['matric_number'] = 'Matric number must be at least 3 characters';
        }

        return $errors;
    }

    private function validateProfileUpdate(array $data): array
    {
        $errors = [];

        if (empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = 'Name must be at least 3 characters';
        }

        if (!empty($data['matric_number']) && strlen($data['matric_number']) < 3) {
            $errors['matric_number'] = 'Matric number must be at least 3 characters';
        }

        return $errors;
    }
}
