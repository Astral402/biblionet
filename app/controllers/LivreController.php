<?php

class LivreController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $q = get_string('q');
        $page = $this->page();

        $this->render('livres/index', [
            'title'   => 'Livres',
            'q'       => $q,
            'page'    => $page,
            'livres'  => Livre::search($q, $page),
            'total'   => Livre::count($q),
            'perPage' => Livre::perPage(),
        ]);
    }

    public function create(): void
    {
        $this->showForm('Ajouter un livre', 'livres/store', [], []);
    }

    public function store(): void
    {
        $this->requirePost();
        [$data, $errors] = Livre::validate($_POST);

        if ($errors) {
            $this->showForm('Ajouter un livre', 'livres/store', $data, $errors);
            return;
        }

        Livre::create($data);
        $this->flash('success', 'Le livre a été ajouté.');
        $this->redirect('livres');
    }

    public function edit(string $id = ''): void
    {
        $livre = $this->findOrLeave((int) $id);
        $this->showForm('Modifier le livre', 'livres/update/' . $livre['id'], $livre, []);
    }

    public function update(string $id = ''): void
    {
        $this->requirePost();

        $livre = $this->findOrLeave((int) $id);
        [$data, $errors] = Livre::validate($_POST);

        $empruntes = Livre::empruntsEnCours((int) $livre['id']);

        if (!$errors && (int) $data['nb_exemplaires'] < $empruntes) {
            $errors[] = "Impossible : $empruntes exemplaire(s) sont actuellement empruntés.";
        }

        if ($errors) {
            $this->showForm('Modifier le livre', 'livres/update/' . $livre['id'], $data, $errors);
            return;
        }

        Livre::update((int) $livre['id'], $data);
        $this->flash('success', 'Le livre a été modifié.');
        $this->redirect('livres');
    }

    public function delete(string $id = ''): void
    {
        $this->requirePost();

        $livre = $this->findOrLeave((int) $id);

        if (Livre::totalEmprunts((int) $livre['id']) > 0) {
            $this->flash('danger', "Suppression impossible : ce livre a un historique d'emprunts.");
        } else {
            Livre::delete((int) $livre['id']);
            $this->flash('success', 'Le livre a été supprimé.');
        }

        $this->redirect('livres');
    }

    private function findOrLeave(int $id): array
    {
        $livre = Livre::find($id);

        if ($livre === null) {
            $this->flash('danger', 'Livre introuvable.');
            $this->redirect('livres');
        }

        return $livre;
    }

    private function showForm(string $title, string $action, array $livre, array $errors): void
    {
        $this->render('livres/form', [
            'title'      => $title,
            'action'     => $action,
            'livre'      => $livre,
            'errors'     => $errors,
            'categories' => Livre::categories(),
        ]);
    }
}
