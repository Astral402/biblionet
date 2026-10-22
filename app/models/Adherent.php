<?php

class Adherent
{
    private const PER_PAGE = 10;

    public static function perPage(): int
    {
        return self::PER_PAGE;
    }

    public static function page(int $page = 1): array
    {
        $offset = max(0, ($page - 1)) * self::PER_PAGE;

        return Database::query(
            'SELECT * FROM adherents ORDER BY nom, prenom LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset
        )->fetchAll();
    }

    public static function count(): int
    {
        return (int) Database::query('SELECT COUNT(*) FROM adherents')->fetchColumn();
    }

    public static function all(): array
    {
        return Database::query('SELECT * FROM adherents ORDER BY nom, prenom')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        return Database::query('SELECT * FROM adherents WHERE id = ?', [$id])->fetch() ?: null;
    }

    public static function create(array $d): int
    {
        Database::query(
            'INSERT INTO adherents (nom, prenom, classe, telephone) VALUES (?, ?, ?, ?)',
            [$d['nom'], $d['prenom'], $d['classe'], $d['telephone'] === '' ? null : $d['telephone']]
        );

        return (int) Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        Database::query(
            'UPDATE adherents SET nom = ?, prenom = ?, classe = ?, telephone = ? WHERE id = ?',
            [$d['nom'], $d['prenom'], $d['classe'], $d['telephone'] === '' ? null : $d['telephone'], $id]
        );
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM adherents WHERE id = ?', [$id]);
    }

    public static function totalEmprunts(int $id): int
    {
        return (int) Database::query('SELECT COUNT(*) FROM emprunts WHERE adherent_id = ?', [$id])->fetchColumn();
    }

    public static function validate(array $input): array
    {
        $d = [
            'nom'       => field($input, 'nom'),
            'prenom'    => field($input, 'prenom'),
            'classe'    => field($input, 'classe'),
            'telephone' => field($input, 'telephone'),
        ];
        $errors = [];

        if ($d['nom'] === '') {
            $errors[] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($d['nom']) > 80) {
            $errors[] = 'Le nom ne doit pas dépasser 80 caractères.';
        }

        if ($d['prenom'] === '') {
            $errors[] = 'Le prénom est obligatoire.';
        } elseif (mb_strlen($d['prenom']) > 80) {
            $errors[] = 'Le prénom ne doit pas dépasser 80 caractères.';
        }

        if ($d['classe'] === '') {
            $errors[] = 'La classe est obligatoire.';
        } elseif (mb_strlen($d['classe']) > 50) {
            $errors[] = 'La classe ne doit pas dépasser 50 caractères.';
        }

        if ($d['telephone'] !== '' && !preg_match('/^\+?[0-9][0-9 .\-()]{5,29}$/', $d['telephone'])) {
            $errors[] = 'Le numéro de téléphone est invalide.';
        }

        return [$d, $errors];
    }
}
