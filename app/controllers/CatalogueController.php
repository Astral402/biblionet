<?php

class CatalogueController extends Controller
{
    public function index(): void
    {
        $recherche = trim($_GET['q'] ?? '');

        $this->render('catalogue/index', [
            'titre' => 'Catalogue',
            'recherche' => $recherche,
            'livres' => Livre::search($recherche),
        ]);
    }

    public function search(): void
    {
        $recherche = trim($_GET['q'] ?? '');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(Livre::search($recherche), JSON_UNESCAPED_UNICODE);
        exit;
    }
}
