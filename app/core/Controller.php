<?php

class Controller
{
    protected function render(string $vue, array $data = [], string $titre = 'BiblioNet'): void
    {
        $titre = $data['titre'] ?? $titre;
        extract($data, EXTR_SKIP);

        ob_start();
        require APP_PATH . '/views/' . $vue . '.php';
        $content = ob_get_clean();

        require APP_PATH . '/views/layouts/main.php';
    }

    public function renderMessage(string $titre, string $type, string $message): void
    {
        $content = '<div class="alert alert-' . $this->e($type) . '"><h1 class="h4">'
                 . $this->e($titre) . '</h1><p class="mb-0">' . $this->e($message) . '</p></div>';

        require APP_PATH . '/views/layouts/main.php';
    }

    public function e(mixed $valeur): string
    {
        return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
    }

    public function url(string $chemin = ''): string
    {
        return BASE_URL . '/' . ltrim($chemin, '/');
    }

    protected function redirect(string $chemin): never
    {
        header('Location: ' . $this->url($chemin));
        exit;
    }

    protected function to(string $chemin): never
    {
        $this->redirect($chemin);
    }

    protected function estConnecte(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    protected function requireLogin(): void
    {
        if (!$this->estConnecte()) {
            $this->to('/login');
        }
    }

    protected function requirePost(string $retour = '/'): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->to($retour);
        }
    }

    protected function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    protected function pullFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return $flash;
    }
}
