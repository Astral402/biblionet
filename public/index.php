<?php

if (PHP_SAPI === 'cli-server') {
    $fichier = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($fichier)) {
        return false;
    }
}

session_start();

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $base);

$config = require ROOT_PATH . '/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', $config['app_env'] === 'production' ? '0' : '1');

spl_autoload_register(function (string $classe): void {
    foreach (['core', 'controllers', 'models'] as $dossier) {
        $fichier = APP_PATH . "/$dossier/$classe.php";
        if (is_file($fichier)) {
            require $fichier;
            return;
        }
    }
});

Database::configure($config['db']);

try {
    $routeur = new Router();
    $routeur->dispatch();
} catch (Throwable $e) {
    http_response_code(500);
    $message = $config['app_env'] === 'production'
        ? 'Une erreur est survenue. Veuillez réessayer plus tard.'
        : $e->getMessage();
    (new Controller())->renderMessage('Erreur', 'danger', $message);
}
