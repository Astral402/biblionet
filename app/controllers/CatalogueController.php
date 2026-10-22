<?php

class CatalogueController extends Controller
{
    public function index(): void
    {
        $q = get_string('q');
        $page = $this->page();

        $this->render('catalogue/index', [
            'title'   => 'Catalogue',
            'q'       => $q,
            'page'    => $page,
            'livres'  => Livre::search($q, $page),
            'total'   => Livre::count($q),
            'perPage' => Livre::perPage(),
        ]);
        $q = get_string('q');
        $categorie = get_string('categorie');
        $dispo = isset($_GET['dispo']);
        $page = $this->page();

        $this->render('catalogue/index', [
            'title'      => 'Catalogue',
            'q'          => $q,
            'page'       => $page,
            'categorie'  => $categorie,
            'dispo'      => $dispo,
            'categories' => Livre::categories(),
            'livres'     => Livre::search($q, $page, $categorie, $dispo),
            'total'      => Livre::count($q, $categorie, $dispo),
            'perPage'    => Livre::perPage(),
        ]);
    }
}
