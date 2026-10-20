<?php

class AuthController extends Controller
{
    public function index(): void
    {
        $this->renderMessage('Authentification', 'info', 'Module en cours de développement (prévu J2).');
    }
}
