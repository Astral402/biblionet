<?php

class EmpruntController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $this->renderMessage('Gestion des emprunts', 'info', 'Module en cours de développement (prévu J3).');
    }
}
