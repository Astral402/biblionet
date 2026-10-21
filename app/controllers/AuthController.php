<?php

class AuthController extends Controller
{
    public function index(): void
    {
        $this->to('/login');
    }

    public function login(): void
    {
        if ($this->estConnecte()) {
            $this->to('/livres');
        }

        $email = '';
        $erreur = null;

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $motDePasse = (string) ($_POST['password'] ?? '');

            $user = ($email !== '' && $motDePasse !== '') ? User::authenticate($email, $motDePasse) : null;

            if ($user !== null) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_nom'] = $user['nom'];
                $this->to('/livres');
            }

            $erreur = 'Email ou mot de passe incorrect.';
        }

        $this->render('auth/login', [
            'titre' => 'Connexion',
            'email' => $email,
            'erreur' => $erreur,
        ]);
    }

    public function logout(): void
    {
        $this->requirePost('/');

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
        $this->to('/');
    }
}
