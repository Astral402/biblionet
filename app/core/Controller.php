<?php

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $user = $_SESSION['user'] ?? null;

        ob_start();
        require APP_PATH . '/views/' . $view . '.php';
        $content = ob_get_clean();

        require APP_PATH . '/views/layouts/main.php';
    }

    public function renderMessage(string $title, string $type, string $message): void
    {
        $flash = null;
        $user = $_SESSION['user'] ?? null;
        $content = '<div class="alert alert-' . e($type) . '"><h1 class="h4">' . e($title)
                 . '</h1><p class="mb-0">' . e($message) . '</p></div>';

        require APP_PATH . '/views/layouts/main.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->flash('warning', 'Veuillez vous connecter.');
            $this->redirect('auth/login');
        }
    }

    protected function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        $token = $_POST['_csrf'] ?? '';

        if (!is_string($token) || !hash_equals($_SESSION['csrf'] ?? '', $token)) {
            http_response_code(419);
            exit('Jeton CSRF invalide. Rechargez la page.');
        }
    }

    protected function page(): int
    {
        return max(1, (int) ($_GET['page'] ?? 1));
    }
}
