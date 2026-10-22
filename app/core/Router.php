<?php

class Router
{
    private const ROUTES = [
        'catalogue' => 'CatalogueController',
        'auth'      => 'AuthController',
        'livres'    => 'LivreController',
        'adherents' => 'AdherentController',
        'emprunts'  => 'EmpruntController',
        'dashboard' => 'DashboardController',
    ];

    public function dispatch(): void
    {
        $segments = request_segments();

        $name   = strtolower($segments[0] ?? 'catalogue');
        $action = strtolower($segments[1] ?? 'index');
        $params = array_slice($segments, 2);

        if (!isset(self::ROUTES[$name]) || !preg_match('/^[a-z]+$/', $action)) {
            $this->notFound();
            return;
        }

        $class = self::ROUTES[$name];

        if (!class_exists($class) || !method_exists($class, $action)) {
            $this->notFound();
            return;
        }

        $method = new ReflectionMethod($class, $action);

        if (!$method->isPublic()
            || $method->getDeclaringClass()->getName() !== $class
            || count($params) < $method->getNumberOfRequiredParameters()) {
            $this->notFound();
            return;
        }

        $params = array_slice($params, 0, $method->getNumberOfParameters());
        $method->invokeArgs(new $class(), $params);
    }

    private function notFound(): void
    {
        http_response_code(404);
        (new Controller())->renderMessage('Page introuvable', 'warning', "La page demandée n'existe pas.");
    }
}
