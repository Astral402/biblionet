<?php

class AdherentController extends Controller
{
    public function index(): void
    {
        $this->renderMessage('Gestion des adhérents', 'info', 'Module en cours de développement (prévu J2).');
    }
}
