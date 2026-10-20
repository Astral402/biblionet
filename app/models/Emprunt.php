<?php

class Emprunt
{
    public static function countEnCours(): int
    {
        $sql = 'SELECT COUNT(*) FROM emprunts WHERE date_retour IS NULL';
        return (int) Database::query($sql)->fetchColumn();
    }
}
