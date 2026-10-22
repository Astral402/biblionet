<?php

class AdherentController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $page = $this->page();

        $this->render('adherents/index', [
            'title'     => 'Adhérents',
            'page'      => $page,
            'adherents' => Adherent::page($page),
            'total'     => Adherent::count(),
            'perPage'   => Adherent::perPage(),
        ]);
    }

    public function show(string $id = ''): void
    {
        $adherent = $this->findOrLeave((int) $id);
        $emprunts = Emprunt::parAdherent((int) $adherent['id']);

        $enCours = array_filter($emprunts, fn($m) => $m['date_retour'] === null);
        $retards = array_filter($enCours, fn($m) => $m['jours'] > Emprunt::JOURS_MAX);

        $this->render('adherents/show', [
            'title'    => 'Fiche adhérent',
            'adherent' => $adherent,
            'emprunts' => $emprunts,
            'enCours'  => count($enCours),
            'retards'  => count($retards),
        ]);
    }

    public function create(): void
    {
        $this->showForm('Ajouter un adhérent', 'adherents/store', [], []);
    }

    public function store(): void
    {
        $this->requirePost();
        [$data, $errors] = Adherent::validate($_POST);

        if ($errors) {
            $this->showForm('Ajouter un adhérent', 'adherents/store', $data, $errors);
            return;
        }

        Adherent::create($data);
        $this->flash('success', "L'adhérent a été ajouté.");
        $this->redirect('adherents');
    }

    public function edit(string $id = ''): void
    {
        $adherent = $this->findOrLeave((int) $id);
        $this->showForm("Modifier l'adhérent", 'adherents/update/' . $adherent['id'], $adherent, []);
    }

    public function update(string $id = ''): void
    {
        $this->requirePost();

        $adherent = $this->findOrLeave((int) $id);
        [$data, $errors] = Adherent::validate($_POST);

        if ($errors) {
            $this->showForm("Modifier l'adhérent", 'adherents/update/' . $adherent['id'], $data, $errors);
            return;
        }

        Adherent::update((int) $adherent['id'], $data);
        $this->flash('success', "L'adhérent a été modifié.");
        $this->redirect('adherents');
    }

    public function delete(string $id = ''): void
    {
        $this->requirePost();

        $adherent = $this->findOrLeave((int) $id);

        if (Adherent::totalEmprunts((int) $adherent['id']) > 0) {
            $this->flash('danger', "Suppression impossible : cet adhérent a un historique d'emprunts.");
        } else {
            Adherent::delete((int) $adherent['id']);
            $this->flash('success', "L'adhérent a été supprimé.");
        }

        $this->redirect('adherents');
    }

    private function findOrLeave(int $id): array
    {
        $adherent = Adherent::find($id);

        if ($adherent === null) {
            $this->flash('danger', 'Adhérent introuvable.');
            $this->redirect('adherents');
        }

        return $adherent;
    }

    private function showForm(string $title, string $action, array $adherent, array $errors): void
    {
        $this->render('adherents/form', [
            'title'    => $title,
            'action'   => $action,
            'adherent' => $adherent,
            'errors'   => $errors,
        ]);
    }
}
