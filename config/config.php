<?php

function loadEnv(string $path): array
{
    $vars = [];

    if (!is_readable($path)) {
        return $vars;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $vars[trim($key)] = trim($value, " \t\"'");
    }

    return $vars;
}

$env = loadEnv(__DIR__ . '/../.env');

return [
    'env' => $env['APP_ENV'] ?? 'production',
    'url' => $env['APP_URL'] ?? '',
    'db'  => [
        'host' => $env['DB_HOST'] ?? 'localhost',
        'name' => $env['DB_NAME'] ?? 'biblionet',
        'user' => $env['DB_USER'] ?? 'root',
        'pass' => $env['DB_PASS'] ?? '',
    ],
];
