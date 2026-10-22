<?php

class Emprunt
{
    public const JOURS_MAX = 14;
    public const LIMITE = 3;

    private const PER_PAGE = 15;

    private const SELECT = 'SELECT e.*, l.titre, l.auteur, l.categorie, a.nom, a.prenom, a.classe,
            DATEDIFF(CURDATE(), e.date_emprunt) AS jours,
            u.nom AS emprunte_par, ur.nom AS retour_par';

    private const FROM = ' FROM emprunts e
        JOIN livres l ON l.id = e.livre_id
        JOIN adherents a ON a.id = e.adherent_id
        LEFT JOIN users u ON u.id = e.user_id
        LEFT JOIN users ur ON ur.id = e.retour_user_id';

    private const JOIN = self::SELECT . self::FROM;

    public static function perPage(): int
    {
        return self::PER_PAGE;
    }

    public static function enCours(): array
    {
        return Database::query(self::JOIN . ' WHERE e.date_retour IS NULL ORDER BY e.date_emprunt')->fetchAll();
    }

    public static function find(int $id): ?array
    {
        return Database::query(self::JOIN . ' WHERE e.id = ?', [$id])->fetch() ?: null;
    }

    public static function parAdherent(int $adherentId): array
    {
        return Database::query(
            self::JOIN . ' WHERE e.adherent_id = ? ORDER BY e.date_emprunt DESC, e.id DESC',
            [$adherentId]
        )->fetchAll();
    }

    public static function create(int $livreId, int $adherentId, int $userId): bool|string
    {
        $pdo = Database::pdo();
        $pdo->beginTransaction();

        try {
            $nb = Database::query('SELECT nb_exemplaires FROM livres WHERE id = ? FOR UPDATE', [$livreId])
                ->fetchColumn();
            $adherent = Database::query('SELECT id FROM adherents WHERE id = ? FOR UPDATE', [$adherentId])
                ->fetchColumn();

            if ($nb === false || $adherent === false) {
                $pdo->rollBack();
                return 'invalide';
            }

            $pris = (int) Database::query(
                'SELECT COUNT(*) FROM emprunts WHERE livre_id = ? AND date_retour IS NULL',
                [$livreId]
            )->fetchColumn();

            if ((int) $nb - $pris <= 0) {
                $pdo->rollBack();
                return 'indisponible';
            }

            $detenus = (int) Database::query(
                'SELECT COUNT(*) FROM emprunts WHERE adherent_id = ? AND date_retour IS NULL',
                [$adherentId]
            )->fetchColumn();

            if ($detenus >= self::LIMITE) {
                $pdo->rollBack();
                return 'limite';
            }

            Database::query(
                'INSERT INTO emprunts (livre_id, adherent_id, date_emprunt, user_id) VALUES (?, ?, CURDATE(), ?)',
                [$livreId, $adherentId, $userId]
            );
            $pdo->commit();

            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }
    }

    public static function retour(int $id, int $userId): bool
    {
        $stmt = Database::query(
            'UPDATE emprunts SET date_retour = CURDATE(), retour_user_id = ? WHERE id = ? AND date_retour IS NULL',
            [$userId, $id]
        );

        return $stmt->rowCount() > 0;
    }

    public static function retards(): array
    {
        return Database::query(
            self::JOIN . ' WHERE e.date_retour IS NULL AND DATEDIFF(CURDATE(), e.date_emprunt) > ?
                ORDER BY e.date_emprunt',
            [self::JOURS_MAX]
        )->fetchAll();
    }

    public static function recherche(array $filtres, int $page): array
    {
        [$where, $params] = self::where($filtres);

        $total = (int) Database::query('SELECT COUNT(*)' . self::FROM . $where, $params)->fetchColumn();
        $offset = max(0, $page - 1) * self::PER_PAGE;

        $rows = Database::query(
            self::JOIN . $where . ' ORDER BY e.date_emprunt DESC, e.id DESC LIMIT ' . self::PER_PAGE . ' OFFSET ' . $offset,
            $params
        )->fetchAll();

        return [$rows, $total];
    }

    public static function filtrer(array $filtres = []): array
    {
        [$where, $params] = self::where($filtres);

        return Database::query(
            self::JOIN . $where . ' ORDER BY e.date_emprunt DESC, e.id DESC',
            $params
        )->fetchAll();
    }

    public static function tous(): array
    {
        return self::filtrer();
    }

    public static function stats(): array
    {
        $count = fn(string $sql, array $params = []): int => (int) Database::query($sql, $params)->fetchColumn();

        return [
            'livres'      => $count('SELECT COUNT(*) FROM livres'),
            'exemplaires' => $count('SELECT COALESCE(SUM(nb_exemplaires), 0) FROM livres'),
            'adherents'   => $count('SELECT COUNT(*) FROM adherents'),
            'en_cours'    => $count('SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL'),
            'retards'     => $count(
                'SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL AND DATEDIFF(CURDATE(), date_emprunt) > ?',
                [self::JOURS_MAX]
            ),
            'total'       => $count('SELECT COUNT(*) FROM emprunts'),
        ];
    }

    public static function topLivres(): array
    {
        return Database::query(
            'SELECT l.titre, l.auteur, COUNT(*) AS nb
             FROM emprunts e JOIN livres l ON l.id = e.livre_id
             GROUP BY l.id, l.titre, l.auteur
             ORDER BY nb DESC, l.titre LIMIT 5'
        )->fetchAll();
    }

    public static function parBibliothecaire(): array
    {
        return Database::query(
            'SELECT u.id, u.nom,
                    (SELECT COUNT(*) FROM emprunts e WHERE e.user_id = u.id) AS emprunts,
                    (SELECT COUNT(*) FROM emprunts e WHERE e.retour_user_id = u.id) AS retours
             FROM users u
             ORDER BY emprunts DESC, u.nom'
        )->fetchAll();
    }

    private static function where(array $f): array
    {
        $sql = [];
        $params = [];

        if (!empty($f['adherent'])) {
            $sql[] = 'e.adherent_id = ?';
            $params[] = (int) $f['adherent'];
        }

        if (!empty($f['par'])) {
            $sql[] = 'e.user_id = ?';
            $params[] = (int) $f['par'];
        }

        if (($f['livre'] ?? '') !== '') {
            $like = '%' . addcslashes($f['livre'], '%_\\') . '%';
            $sql[] = '(l.titre LIKE ? OR l.auteur LIKE ?)';
            $params[] = $like;
            $params[] = $like;
        }

        if (!empty($f['du'])) {
            $sql[] = 'e.date_emprunt >= ?';
            $params[] = $f['du'];
        }

        if (!empty($f['au'])) {
            $sql[] = 'e.date_emprunt <= ?';
            $params[] = $f['au'];
        }

        if (($f['statut'] ?? '') === 'en_cours') {
            $sql[] = 'e.date_retour IS NULL';
        } elseif (($f['statut'] ?? '') === 'rendus') {
            $sql[] = 'e.date_retour IS NOT NULL';
        } elseif (($f['statut'] ?? '') === 'retard') {
            $sql[] = 'e.date_retour IS NULL AND DATEDIFF(CURDATE(), e.date_emprunt) > ?';
            $params[] = self::JOURS_MAX;
        }

        return [$sql ? ' WHERE ' . implode(' AND ', $sql) : '', $params];
    }
}
