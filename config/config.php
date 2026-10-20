<?php

function charger_env(string $fichier): array
{
    $variables = [];

    if (!is_readable($fichier)) {
        return $variables;
    }

    foreach (file($fichier, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $ligne) {
        $ligne = trim($ligne);

        if ($ligne === '' || $ligne[0] === '#' || !str_contains($ligne, '=')) {
            continue;
        }

        [$cle, $valeur] = explode('=', $ligne, 2);
        $variables[trim($cle)] = trim($valeur, " \t\"'");
    }

    return $variables;
}

$env = charger_env(dirname(__DIR__) . '/.env');

return [
    'app_env' => $env['APP_ENV'] ?? 'production',
    'app_url' => $env['APP_URL'] ?? '',
    'db' => [
        'host' => $env['DB_HOST'] ?? 'localhost',
        'name' => $env['DB_NAME'] ?? 'biblionet',
        'user' => $env['DB_USER'] ?? 'root',
        'pass' => $env['DB_PASS'] ?? '',
    ],
];
