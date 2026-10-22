<?php

class AuthController extends Controller
{
    private const MAX_ECHECS = 5;
    private const BLOCAGE_SECONDES = 30;

    public function login(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect(admin_home());
        }

        $email = '';
        $error = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->requirePost();

            $email = field($_POST, 'email');
            $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

            if ($this->attente() === 0) {
                $user = ($email !== '' && $password !== '') ? User::findByEmail($email) : null;

                if ($user !== null && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    unset($_SESSION['login_echecs'], $_SESSION['login_bloque_jusqua']);
                    $_SESSION['user'] = ['id' => (int) $user['id'], 'nom' => $user['nom'], 'email' => $user['email']];
                    $this->flash('success', 'Bienvenue, ' . $user['nom'] . ' !');
                    $this->redirect(admin_home());
                }

                $this->enregistrerEchec();
                $error = 'Email ou mot de passe incorrect.';
            }
        }

        $attente = $this->attente();

        if ($attente > 0) {
            $error = 'Trop de tentatives échouées. Réessayez dans ' . $attente . ' seconde' . ($attente > 1 ? 's' : '') . '.';
        }

        $this->render('auth/login', ['title' => 'Connexion', 'error' => $error, 'email' => $email]);
    }

    public function logout(): void
    {
        $this->requirePost();

        $_SESSION = [];
        session_destroy();
        session_start();

        $this->flash('info', 'Vous êtes déconnecté.');
        $this->redirect('catalogue');
    }

    private function attente(): int
    {
        return max(0, (int) ($_SESSION['login_bloque_jusqua'] ?? 0) - time());
    }

    private function enregistrerEchec(): void
    {
        $echecs = (int) ($_SESSION['login_echecs'] ?? 0) + 1;

        if ($echecs >= self::MAX_ECHECS) {
            $_SESSION['login_bloque_jusqua'] = time() + self::BLOCAGE_SECONDES;
            $echecs = 0;
        }

        $_SESSION['login_echecs'] = $echecs;
    }
}
