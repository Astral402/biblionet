<?php

class AdherentController extends Controller
{
    public function index(): void
    {
        $this->requireLogin();

        $this->render('adherents/index', [
            'titre' => 'Gestion des adhérents',
            'adherents' => Adherent::all(),
            'flash' => $this->pullFlash(),
        ]);
    }

    public function create(): void
    {
        $this->requireLogin();

        $this->showForm('Ajouter un adhérent', '/adherents/store', $this->emptyAdherent(), []);
    }

    public function store(): void
    {
        $this->requireLogin();
        $this->requirePost('/adherents');

        $data = $this->input();
        $errors = $this->validate($data);

        if ($errors) {
            $this->showForm('Ajouter un adhérent', '/adherents/store', $data, $errors);
            return;
        }

        Adherent::create($data);
        $this->flash('success', 'L’adhérent a été ajouté.');
        $this->to('/adherents');
    }

    public function edit($id = 0): void
    {
        $this->requireLogin();

        $adherent = $this->findOrLeave((int) $id);

        $this->showForm('Modifier un adhérent', '/adherents/update/' . $adherent['id'], $adherent, []);
    }

    public function update($id = 0): void
    {
        $this->requireLogin();
        $this->requirePost('/adherents');

        $adherent = $this->findOrLeave((int) $id);
        $data = $this->input();
        $errors = $this->validate($data);

        if ($errors) {
            $this->showForm('Modifier un adhérent', '/adherents/update/' . $adherent['id'], $data, $errors);
            return;
        }

        Adherent::update((int) $adherent['id'], $data);
        $this->flash('success', 'L’adhérent a été modifié.');
        $this->to('/adherents');
    }

    public function delete($id = 0): void
    {
        $this->requireLogin();
        $this->requirePost('/adherents');

        $adherent = $this->findOrLeave((int) $id);

        if (Adherent::totalEmprunts((int) $adherent['id']) > 0) {
            $this->flash('danger', 'Suppression impossible : cet adhérent possède un historique d’emprunts.');
            $this->to('/adherents');
        }

        Adherent::delete((int) $adherent['id']);
        $this->flash('success', 'L’adhérent a été supprimé.');
        $this->to('/adherents');
    }

    private function findOrLeave(int $id): array
    {
        $adherent = Adherent::find($id);

        if ($adherent === null) {
            $this->flash('danger', 'Adhérent introuvable.');
            $this->to('/adherents');
        }

        return $adherent;
    }

    private function emptyAdherent(): array
    {
        return ['nom' => '', 'prenom' => '', 'classe' => '', 'telephone' => ''];
    }

    private function input(): array
    {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'classe' => trim($_POST['classe'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
        ];
    }

    private function validate(array $data): array
    {
        $errors = [];

        if ($data['nom'] === '') {
            $errors['nom'] = 'Le nom est obligatoire.';
        } elseif (mb_strlen($data['nom']) > 80) {
            $errors['nom'] = 'Le nom ne doit pas dépasser 80 caractères.';
        }

        if ($data['prenom'] === '') {
            $errors['prenom'] = 'Le prénom est obligatoire.';
        } elseif (mb_strlen($data['prenom']) > 80) {
            $errors['prenom'] = 'Le prénom ne doit pas dépasser 80 caractères.';
        }

        if ($data['classe'] === '') {
            $errors['classe'] = 'La classe est obligatoire.';
        } elseif (mb_strlen($data['classe']) > 50) {
            $errors['classe'] = 'La classe ne doit pas dépasser 50 caractères.';
        }

        if ($data['telephone'] !== '' && !preg_match('/^\+?[0-9][0-9 .\-()]{5,29}$/', $data['telephone'])) {
            $errors['telephone'] = 'Numéro de téléphone invalide (chiffres, espaces et + uniquement).';
        }

        return $errors;
    }

    private function showForm(string $titre, string $action, array $adherent, array $errors): void
    {
        $this->render('adherents/form', [
            'titre' => $titre,
            'action' => $action,
            'adherent' => $adherent,
            'errors' => $errors,
        ]);
    }
}
