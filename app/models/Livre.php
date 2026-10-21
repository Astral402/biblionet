<?php

class Livre
{
    private static function selectBase(): string
    {
        return 'SELECT l.id, l.titre, l.auteur, l.categorie, l.annee, l.nb_exemplaires,
                       GREATEST(l.nb_exemplaires - COUNT(e.id), 0) AS disponibles
                FROM livres l
                LEFT JOIN emprunts e ON e.livre_id = l.id AND e.date_retour IS NULL';
    }

    public static function all(): array
    {
        $stmt = Database::getConnection()->prepare(
            self::selectBase() . ' GROUP BY l.id ORDER BY l.titre ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::getConnection()->prepare(
            self::selectBase() . ' WHERE l.id = :id GROUP BY l.id'
        );
        $stmt->execute(['id' => $id]);
        $livre = $stmt->fetch(PDO::FETCH_ASSOC);

        return $livre ?: null;
    }

    public static function search(string $terme): array
    {
        $terme = trim($terme);

        if ($terme === '') {
            return self::all();
        }

        $motif = '%' . addcslashes($terme, '\\%_') . '%';

        $stmt = Database::getConnection()->prepare(
            self::selectBase() . ' WHERE l.titre LIKE :titre OR l.auteur LIKE :auteur
                                  GROUP BY l.id ORDER BY l.titre ASC'
        );
        $stmt->execute(['titre' => $motif, 'auteur' => $motif]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            'INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires)
             VALUES (:titre, :auteur, :categorie, :annee, :nb_exemplaires)'
        );
        $stmt->execute($data);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = Database::getConnection()->prepare(
            'UPDATE livres
             SET titre = :titre, auteur = :auteur, categorie = :categorie,
                 annee = :annee, nb_exemplaires = :nb_exemplaires
             WHERE id = :id'
        );

        return $stmt->execute($data + ['id' => $id]);
    }

    public static function delete(int $id): bool
    {
        $stmt = Database::getConnection()->prepare('DELETE FROM livres WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }

    public static function empruntsEnCours(int $id): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM emprunts WHERE livre_id = :id AND date_retour IS NULL'
        );
        $stmt->execute(['id' => $id]);

        return (int) $stmt->fetchColumn();
    }

    public static function totalEmprunts(int $id): int
    {
        $stmt = Database::getConnection()->prepare(
            'SELECT COUNT(*) FROM emprunts WHERE livre_id = :id'
        );
        $stmt->execute(['id' => $id]);

        return (int) $stmt->fetchColumn();
    }
}
