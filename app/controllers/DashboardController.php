<?php

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('dashboard/index', [
            'title'   => 'Tableau de bord',
            'stats'   => Emprunt::stats(),
            'top'     => Emprunt::topLivres(),
            'retards' => Emprunt::retards(),
            'equipe'  => Emprunt::parBibliothecaire(),
        ]);
    }
}
