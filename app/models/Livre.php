<?php

class Livre
{
    private const PER_PAGE = 10;

    private const SELECT = 'SELECT l.*,
        GREATEST(l.nb_exemplaires - (SELECT COUNT(*) FROM emprunts e
            WHERE e.livre_id = l.id AND e.date_retour IS NULL), 0) AS disponibles
        FROM livres l';

    public static function all(): array
    {
        return Database::query(self::SELECT . ' ORDER BY l.titre')->fetchAll();
    }

    public static function perPage(): int
    {
        return self::PER_PAGE;
    }

    public static function search(string $q = '', int $page = 1, string $categorie = '', bool $dispo = false): array
    {
        [$where, $args] = self::filters($q, $categorie, $dispo);
        $offset = max(0, ($page - 1)) * self::PER_PAGE;

        return Database::query(
            self::SELECT . " WHERE $where ORDER BY l.titre LIMIT " . self::PER_PAGE . ' OFFSET ' . $offset,
            $args
        )->fetchAll();
    }

    public static function count(string $q = '', string $categorie = '', bool $dispo = false): int
    {
        [$where, $args] = self::filters($q, $categorie, $dispo);

        return (int) Database::query("SELECT COUNT(*) FROM livres l WHERE $where", $args)->fetchColumn();
    }

    private static function filters(string $q, string $categorie, bool $dispo): array
    {
        $like = '%' . addcslashes($q, '\\%_') . '%';
        $where = '(l.titre LIKE ? OR l.auteur LIKE ?)';
        $args = [$like, $like];

        if ($categorie !== '') {
            $where .= ' AND l.categorie = ?';
            $args[] = $categorie;
        }

        if ($dispo) {
            $where .= ' AND (l.nb_exemplaires - (SELECT COUNT(*) FROM emprunts e
                WHERE e.livre_id = l.id AND e.date_retour IS NULL)) > 0';
        }

        return [$where, $args];
    }

    public static function categories(): array
    {
        return Database::query('SELECT DISTINCT categorie FROM livres ORDER BY categorie')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function find(int $id): ?array
    {
        return Database::query(self::SELECT . ' WHERE l.id = ?', [$id])->fetch() ?: null;
    }

    public static function create(array $d): int
    {
        Database::query(
            'INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires) VALUES (?, ?, ?, ?, ?)',
            [$d['titre'], $d['auteur'], $d['categorie'], (int) $d['annee'], (int) $d['nb_exemplaires']]
        );

        return (int) Database::pdo()->lastInsertId();
    }

    public static function update(int $id, array $d): void
    {
        Database::query(
            'UPDATE livres SET titre = ?, auteur = ?, categorie = ?, annee = ?, nb_exemplaires = ? WHERE id = ?',
            [$d['titre'], $d['auteur'], $d['categorie'], (int) $d['annee'], (int) $d['nb_exemplaires'], $id]
        );
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM livres WHERE id = ?', [$id]);
    }

    public static function totalEmprunts(int $id): int
    {
        return (int) Database::query('SELECT COUNT(*) FROM emprunts WHERE livre_id = ?', [$id])->fetchColumn();
    }

    public static function empruntsEnCours(int $id): int
    {
        return (int) Database::query(
            'SELECT COUNT(*) FROM emprunts WHERE livre_id = ? AND date_retour IS NULL',
            [$id]
        )->fetchColumn();
    }

    public static function disponibles(): array
    {
        return Database::query(
            'SELECT * FROM (' . self::SELECT . ') t WHERE t.disponibles > 0 ORDER BY t.titre'
        )->fetchAll();
    }

    public static function validate(array $input): array
    {
        $d = [
            'titre'          => field($input, 'titre'),
            'auteur'         => field($input, 'auteur'),
            'categorie'      => field($input, 'categorie'),
            'annee'          => field($input, 'annee'),
            'nb_exemplaires' => field($input, 'nb_exemplaires'),
        ];
        $errors = [];

        if ($d['titre'] === '') {
            $errors[] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($d['titre']) > 255) {
            $errors[] = 'Le titre ne doit pas dépasser 255 caractères.';
        }

        if ($d['auteur'] === '') {
            $errors[] = "L'auteur est obligatoire.";
        } elseif (mb_strlen($d['auteur']) > 150) {
            $errors[] = "L'auteur ne doit pas dépasser 150 caractères.";
        }

        if ($d['categorie'] === '') {
            $errors[] = 'La catégorie est obligatoire.';
        } elseif (mb_strlen($d['categorie']) > 100) {
            $errors[] = 'La catégorie ne doit pas dépasser 100 caractères.';
        }

        $anneeMax = (int) date('Y') + 1;

        if (filter_var($d['annee'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1000, 'max_range' => $anneeMax]]) === false) {
            $errors[] = "L'année doit être comprise entre 1000 et $anneeMax.";
        }

        if (filter_var($d['nb_exemplaires'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 1000]]) === false) {
            $errors[] = "Le nombre d'exemplaires doit être compris entre 1 et 1000.";
        }

        return [$d, $errors];
    }
}
