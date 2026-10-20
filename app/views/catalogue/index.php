<div class="p-4 mb-4 bg-body-tertiary rounded-3">
    <h1 class="display-6 fw-bold">Bienvenue à la bibliothèque de l'INPTIC</h1>
    <p class="lead mb-0">
        Consultez le catalogue des ouvrages et vérifiez leur disponibilité en ligne.
    </p>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center h-100"><div class="card-body">
            <div class="display-6"><?= $this->e($nbLivres) ?></div>
            <div class="text-muted">Livres au catalogue</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100"><div class="card-body">
            <div class="display-6"><?= $this->e($nbAdherents) ?></div>
            <div class="text-muted">Adhérents</div>
        </div></div>
    </div>
    <div class="col-md-4">
        <div class="card text-center h-100"><div class="card-body">
            <div class="display-6"><?= $this->e($nbEnCours) ?></div>
            <div class="text-muted">Emprunts en cours</div>
        </div></div>
    </div>
</div>

<h2 class="h4 mb-3">Catalogue</h2>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Année</th>
                <th>Disponibilité</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($livres)): ?>
            <tr><td colspan="5" class="text-center text-muted">Aucun livre pour le moment.</td></tr>
        <?php endif; ?>
        <?php foreach ($livres as $livre): ?>
            <tr>
                <td><?= $this->e($livre['titre']) ?></td>
                <td><?= $this->e($livre['auteur']) ?></td>
                <td><?= $this->e($livre['categorie']) ?></td>
                <td><?= $this->e($livre['annee']) ?></td>
                <td>
                    <?php if ((int) $livre['disponibles'] > 0): ?>
                        <span class="badge text-bg-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge text-bg-danger">Indisponible</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
