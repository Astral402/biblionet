<?php

class Adherent
{
    public static function count(): int
    {
        return (int) Database::query('SELECT COUNT(*) FROM adherents')->fetchColumn();
    }
}
