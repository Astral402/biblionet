<?php $e = fn($valeur) => htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8'); ?>
<h1 class="h3 mb-4">Catalogue de la bibliothèque</h1>

<form id="search-form" action="<?= $e($this->url('catalogue')) ?>" data-search-url="<?= $e($this->url('catalogue/search')) ?>" method="get" class="row g-2 mb-4" role="search">
    <div class="col-sm-9 col-md-10">
        <input type="search" id="search-input" name="q" class="form-control"
               placeholder="Rechercher par titre ou auteur" value="<?= $e($recherche) ?>" autocomplete="off">
    </div>
    <div class="col-sm-3 col-md-2 d-grid">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Année</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody id="catalogue-body">
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= $e($livre['titre']) ?></td>
                    <td><?= $e($livre['auteur']) ?></td>
                    <td><?= $e($livre['categorie']) ?></td>
                    <td><?= $e($livre['annee']) ?></td>
                    <td>
                        <?php if ((int) $livre['disponibles'] > 0): ?>
                            <span class="badge bg-success">Disponible</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Indisponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p id="catalogue-empty" class="text-center text-muted py-4<?= empty($livres) ? '' : ' d-none' ?>">
    Aucun livre ne correspond à votre recherche.
</p>

<script src="<?= $e($this->url('assets/js/catalogue.js')) ?>"></script>
