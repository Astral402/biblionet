<?php

class LivreController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $this->render('livres/index', [
            'titre' => 'Gestion des livres',
            'livres' => Livre::all(),
            'flash' => $this->pullFlash(),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->showForm('Ajouter un livre', '/livres/store', $this->emptyLivre(), []);
    }

    public function store(): void
    {
        $this->requireLogin();
        $this->requirePost('/livres');

        $data = $this->input();
        $errors = $this->validate($data);

        if ($errors) {
            $this->showForm('Ajouter un livre', '/livres/store', $data, $errors);
            return;
        }

        Livre::create($data);
        $this->flash('success', 'Le livre a été ajouté.');
        $this->to('/livres');
    }

    public function edit($id = 0): void
    {
        $this->requireLogin();

        $livre = $this->findOrLeave((int) $id);

        $this->showForm('Modifier un livre', '/livres/update/' . $livre['id'], $livre, []);
    }

    public function update($id = 0): void
    {
        $this->requireLogin();
        $this->requirePost('/livres');

        $livre = $this->findOrLeave((int) $id);
        $data = $this->input();
        $errors = $this->validate($data, Livre::empruntsEnCours((int) $livre['id']));

        if ($errors) {
            $this->showForm('Modifier un livre', '/livres/update/' . $livre['id'], $data, $errors);
            return;
        }

        Livre::update((int) $livre['id'], $data);
        $this->flash('success', 'Le livre a été modifié.');
        $this->to('/livres');
    }

    public function delete($id = 0): void
    {
        $this->requireLogin();
        $this->requirePost('/livres');

        $livre = $this->findOrLeave((int) $id);

        if (Livre::totalEmprunts((int) $livre['id']) > 0) {
            $this->flash('danger', 'Suppression impossible : ce livre possède un historique d’emprunts.');
            $this->to('/livres');
        }

        Livre::delete((int) $livre['id']);
        $this->flash('success', 'Le livre a été supprimé.');
        $this->to('/livres');
    }

    private function findOrLeave(int $id): array
    {
        $livre = Livre::find($id);

        if ($livre === null) {
            $this->flash('danger', 'Livre introuvable.');
            $this->to('/livres');
        }

        return $livre;
    }

    private function emptyLivre(): array
    {
        return ['titre' => '', 'auteur' => '', 'categorie' => '', 'annee' => '', 'nb_exemplaires' => '1'];
    }

    private function input(): array
    {
        return [
            'titre' => trim($_POST['titre'] ?? ''),
            'auteur' => trim($_POST['auteur'] ?? ''),
            'categorie' => trim($_POST['categorie'] ?? ''),
            'annee' => trim($_POST['annee'] ?? ''),
            'nb_exemplaires' => trim($_POST['nb_exemplaires'] ?? ''),
        ];
    }

    private function validate(array $data, ?int $empruntsEnCours = null): array
    {
        $errors = [];

        if ($data['titre'] === '') {
            $errors['titre'] = 'Le titre est obligatoire.';
        } elseif (mb_strlen($data['titre']) > 255) {
            $errors['titre'] = 'Le titre ne doit pas dépasser 255 caractères.';
        }

        if ($data['auteur'] === '') {
            $errors['auteur'] = 'L’auteur est obligatoire.';
        } elseif (mb_strlen($data['auteur']) > 150) {
            $errors['auteur'] = 'L’auteur ne doit pas dépasser 150 caractères.';
        }

        if ($data['categorie'] === '') {
            $errors['categorie'] = 'La catégorie est obligatoire.';
        } elseif (mb_strlen($data['categorie']) > 100) {
            $errors['categorie'] = 'La catégorie ne doit pas dépasser 100 caractères.';
        }

        $anneeMax = (int) date('Y') + 1;
        $annee = filter_var($data['annee'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1000, 'max_range' => $anneeMax],
        ]);

        if ($annee === false) {
            $errors['annee'] = 'L’année doit être comprise entre 1000 et ' . $anneeMax . '.';
        }

        $nombre = filter_var($data['nb_exemplaires'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 1000],
        ]);

        if ($nombre === false) {
            $errors['nb_exemplaires'] = 'Le nombre d’exemplaires doit être compris entre 1 et 1000.';
        } elseif ($empruntsEnCours !== null && $nombre < $empruntsEnCours) {
            $errors['nb_exemplaires'] = 'Impossible : ' . $empruntsEnCours . ' exemplaire(s) sont actuellement empruntés.';
        }

        return $errors;
    }

    private function showForm(string $titre, string $action, array $livre, array $errors): void
    {
        $this->render('livres/form', [
            'titre' => $titre,
            'action' => $action,
            'livre' => $livre,
            'errors' => $errors,
        ]);
    }
}
