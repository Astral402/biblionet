<?php

class EmpruntController extends Controller
{
    public function __construct()
    {
        $this->requireAuth();
    }

    public function index(): void
    {
        $this->render('emprunts/index', [
            'title'    => 'Emprunts en cours',
            'emprunts' => Emprunt::enCours(),
        ]);
    }

    public function retards(): void
    {
        $this->render('emprunts/index', [
            'title'    => 'Retards (plus de ' . Emprunt::JOURS_MAX . ' jours)',
            'emprunts' => Emprunt::retards(),
        ]);
    }

    public function historique(): void
    {
        $page = $this->page();
        $filtres = $this->filtres();
        [$rows, $total] = Emprunt::recherche($filtres, $page);

        $this->render('emprunts/historique', [
            'title'     => 'Historique des emprunts',
            'rows'      => $rows,
            'total'     => $total,
            'page'      => $page,
            'perPage'   => Emprunt::perPage(),
            'filtres'   => $filtres,
            'actifs'    => $this->actifs($filtres),
            'adherents' => Adherent::all(),
            'users'     => User::all(),
        ]);
    }

    public function create(): void
    {
        $this->render('emprunts/form', [
            'title'      => 'Nouvel emprunt',
            'livres'     => Livre::disponibles(),
            'adherents'  => Adherent::all(),
            'livreId'    => (int) ($_GET['livre'] ?? 0),
            'adherentId' => (int) ($_GET['adherent'] ?? 0),
        ]);
    }

    public function store(): void
    {
        $this->requirePost();

        $livreId = (int) ($_POST['livre_id'] ?? 0);
        $adherentId = (int) ($_POST['adherent_id'] ?? 0);

        if (Livre::find($livreId) === null || Adherent::find($adherentId) === null) {
            $this->flash('danger', 'Livre ou adhérent invalide.');
            $this->redirect('emprunts/create');
        }

        $resultat = Emprunt::create($livreId, $adherentId, (int) $_SESSION['user']['id']);

        if ($resultat === 'limite') {
            $this->flash('danger', 'Emprunt refusé : cet adhérent a déjà ' . Emprunt::LIMITE . ' livres non rendus.');
            $this->redirect('emprunts/create');
        }

        if ($resultat !== true) {
            $this->flash('danger', 'Emprunt refusé : aucun exemplaire disponible pour ce livre.');
            $this->redirect('emprunts/create');
        }

        $this->flash('success', 'Emprunt enregistré.');
        $this->redirect('emprunts');
    }

    public function retour(string $id = ''): void
    {
        $this->requirePost();

        if (Emprunt::retour((int) $id, (int) $_SESSION['user']['id'])) {
            $this->flash('success', 'Retour enregistré.');
        } else {
            $this->flash('warning', 'Cet emprunt est déjà clôturé ou introuvable.');
        }

        $this->redirect('emprunts');
    }

    public function fiche(string $id = ''): void
    {
        $emprunt = Emprunt::find((int) $id);

        if ($emprunt === null) {
            $this->flash('danger', 'Emprunt introuvable.');
            $this->redirect('emprunts');
        }

        $limite = (new DateTime($emprunt['date_emprunt']))->modify('+' . Emprunt::JOURS_MAX . ' days');

        $this->render('emprunts/fiche', [
            'title'   => 'Fiche de prêt n° ' . $emprunt['id'],
            'emprunt' => $emprunt,
            'limite'  => $limite,
        ]);
    }

    public function export(): void
    {
        $rows = Emprunt::filtrer($this->filtres());

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="emprunts_' . date('Y-m-d') . '.csv"');

        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, [
            'ID', 'Livre', 'Auteur', 'Adhérent', 'Classe', 'Date emprunt', 'Date retour',
            'Emprunt enregistré par', 'Retour enregistré par',
        ], ';');

        $safe = fn($value) => preg_match('/^[=+\-@]/', (string) $value) ? "'" . $value : $value;

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['id'],
                $safe($r['titre']),
                $safe($r['auteur']),
                $safe($r['prenom'] . ' ' . $r['nom']),
                $safe($r['classe']),
                $r['date_emprunt'],
                $r['date_retour'] ?? '',
                $safe($r['emprunte_par'] ?? ''),
                $safe($r['retour_par'] ?? ''),
            ], ';');
        }

        fclose($out);
        exit;
    }

    private function filtres(): array
    {
        $statut = get_string('statut', 20);

        return [
            'adherent' => max(0, (int) get_string('adherent', 10)),
            'par'      => max(0, (int) get_string('par', 10)),
            'livre'    => get_string('livre', 100),
            'du'       => $this->date(get_string('du', 10)),
            'au'       => $this->date(get_string('au', 10)),
            'statut'   => in_array($statut, ['en_cours', 'rendus', 'retard'], true) ? $statut : '',
        ];
    }

    private function actifs(array $filtres): array
    {
        return array_filter($filtres, fn($valeur) => $valeur !== '' && $valeur !== 0);
    }

    private function date(string $valeur): string
    {
        $date = DateTime::createFromFormat('Y-m-d', $valeur);

        return $date && $date->format('Y-m-d') === $valeur ? $valeur : '';
    }
}
