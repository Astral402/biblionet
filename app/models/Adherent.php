<?php

class Adherent
{
    public static function all(): array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id, nom, prenom, classe, telephone FROM adherents ORDER BY nom ASC, prenom ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT id, nom, prenom, classe, telephone FROM adherents WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $adherent = $stmt->fetch(PDO::FETCH_ASSOC);

        return $adherent ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO adherents (nom, prenom, classe, telephone)
             VALUES (:nom, :prenom, :classe, :telephone)'
        );
        $stmt->execute(self::params($data));

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE adherents
             SET nom = :nom, prenom = :prenom, classe = :classe, telephone = :telephone
             WHERE id = :id'
        );

        return $stmt->execute(self::params($data) + ['id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM adherents WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public static function totalEmprunts(int $id): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM emprunts WHERE adherent_id = :id'
        );
        $stmt->execute(['id' => $id]);

        return (int) $stmt->fetchColumn();
    }

    public static function count(): int
    {
        return (int) Database::getConnection()->query('SELECT COUNT(*) FROM adherents')->fetchColumn();
    }

    private static function params(array $data): array
    {
        return [
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'classe' => $data['classe'],
            'telephone' => $data['telephone'] === '' ? null : $data['telephone'],
        ];
    }
}
