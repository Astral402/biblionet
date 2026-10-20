<?php

class CatalogueController extends Controller
{
    public function index(): void
    {
        $this->render('catalogue/index', [
            'livres'     => Livre::all(),
            'nbLivres'   => Livre::count(),
            'nbAdherents'=> Adherent::count(),
            'nbEnCours'  => Emprunt::countEnCours(),
        ], 'Accueil');
    }
}
