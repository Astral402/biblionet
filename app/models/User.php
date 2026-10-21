<?php

class User
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id, nom, email, password FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function authenticate(string $email, string $motDePasse): ?array
    {
        $user = self::findByEmail($email);

        if ($user === null || !password_verify($motDePasse, $user['password'])) {
            return null;
        }

        return $user;
    }
}
