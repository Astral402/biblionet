<?php

class User
{
    public static function findByEmail(string $email): ?array
    {
        return Database::query('SELECT id, nom, email, password FROM users WHERE email = ? LIMIT 1', [$email])
            ->fetch() ?: null;
    }

    public static function all(): array
    {
        return Database::query('SELECT id, nom FROM users ORDER BY nom')->fetchAll();
    }
}
