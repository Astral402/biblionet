<?php

class LivreController extends Controller
{
    public function index(): void
    {
        $this->renderMessage('Gestion des livres', 'info', 'Module en cours de développement (prévu J2).');
    }
}
