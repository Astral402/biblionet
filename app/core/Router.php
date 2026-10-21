<?php

class Router
{
    private const ROUTES = [
        ''          => 'CatalogueController',
        'catalogue' => 'CatalogueController',
        'auth'      => 'AuthController',
        'livres'    => 'LivreController',
        'adherents' => 'AdherentController',
        'emprunts'  => 'EmpruntController',
        'login'     => 'AuthController',
        'logout'    => 'AuthController',
    ];

    private const METHODES_ALIAS = [
        'login'  => 'login',
        'logout' => 'logout',
    ];

    public function dispatch(): void
    {
        $segments = $this->segments();

        $cle     = strtolower(array_shift($segments) ?? '');
        $methode = strtolower(array_shift($segments) ?? 'index');
        $params  = $segments;

        if (isset(self::METHODES_ALIAS[$cle])) {
            if ($methode !== 'index' || $params) {
                $this->notFound();
                return;
            }

            $methode = self::METHODES_ALIAS[$cle];
        }

        if (!isset(self::ROUTES[$cle])) {
            $this->notFound();
            return;
        }

        $classe = self::ROUTES[$cle];

        if (str_starts_with($methode, '_') || !method_exists($classe, $methode)) {
            $this->notFound();
            return;
        }

        $reflexion = new ReflectionMethod($classe, $methode);
        if (!$reflexion->isPublic() || $reflexion->getDeclaringClass()->getName() !== $classe || count($params) < $reflexion->getNumberOfRequiredParameters()) {
            $this->notFound();
            return;
        }

        call_user_func_array([new $classe(), $methode], $params);
    }

    private function segments(): array
    {
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
        } else {
            $chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
            $url = substr($chemin, strlen(BASE_URL));
        }

        $url = trim($url, '/');
        return $url === '' ? [] : explode('/', $url);
    }

    private function notFound(): void
    {
        http_response_code(404);
        (new Controller())->renderMessage('Page introuvable', 'warning', "La page demandée n'existe pas.");
    }
}
