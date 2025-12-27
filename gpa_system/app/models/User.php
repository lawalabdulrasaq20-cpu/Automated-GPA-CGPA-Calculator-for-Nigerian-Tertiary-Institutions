<?php

// Load database config / helper
require_once __DIR__ . '/../../config/database.php';

        $stmt->execute([
            $data['name'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['matric_number'] ?? null,
            $data['department'] ?? null,
            $data['level'] ?? null
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users
             SET name = ?, matric_number = ?, department = ?, level = ?, updated_at = CURRENT_TIMESTAMP
             WHERE id = ?"
        );

        return $stmt->execute([
            $data['name'],
            $data['matric_number'] ?? null,
            $data['department'] ?? null,
            $data['level'] ?? null,
            $id
        ]);
    }

    public function verifyPassword(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    public function getTotalStudents(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $row = $stmt->fetch();

        return (int) ($row['total'] ?? 0);
    }
}
