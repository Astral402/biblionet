<?php

if (PHP_SAPI === 'cli-server') {
    $file = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if (is_file($file)) {
        return false;
    }
}

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

$config = require ROOT_PATH . '/config/config.php';
$production = $config['env'] === 'production';

error_reporting(E_ALL);
ini_set('display_errors', $production ? '0' : '1');
ini_set('log_errors', '1');

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure'   => !empty($_SERVER['HTTPS']),
]);
session_start();

spl_autoload_register(function (string $class): void {
    foreach (['core', 'controllers', 'models'] as $dir) {
        $file = APP_PATH . "/$dir/$class.php";

        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

require APP_PATH . '/core/helpers.php';

Database::configure($config['db']);

try {
    (new Router())->dispatch();
} catch (Throwable $e) {
    error_log((string) $e);
    http_response_code(500);
    (new Controller())->renderMessage(
        'Erreur',
        'danger',
        $production ? 'Une erreur est survenue. Veuillez réessayer plus tard.' : $e->getMessage()
    );
}
