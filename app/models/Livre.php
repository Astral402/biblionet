<?php

class Livre
{
    public static function all(): array
    {
        $sql = 'SELECT l.id, l.titre, l.auteur, l.categorie, l.annee, l.nb_exemplaires,
                       l.nb_exemplaires - (
                           SELECT COUNT(*) FROM emprunts e
                           WHERE e.livre_id = l.id AND e.date_retour IS NULL
                       ) AS disponibles
                FROM livres l
                ORDER BY l.titre';

        return Database::query($sql)->fetchAll();
    }

    public static function count(): int
    {
        return (int) Database::query('SELECT COUNT(*) FROM livres')->fetchColumn();
    }
}
