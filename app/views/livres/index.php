<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Livres</h1>
    <a href="<?= $this->e($this->url('livres/create')) ?>" class="btn btn-primary">+ Ajouter un livre</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= $this->e($flash['type']) ?>"><?= $this->e($flash['message']) ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Année</th>
                <th>Exemplaires</th>
                <th>Disponibles</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($livres)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">Aucun livre pour le moment.</td></tr>
            <?php endif; ?>
            <?php foreach ($livres as $livre): ?>
                <tr>
                    <td><?= $this->e($livre['id']) ?></td>
                    <td><?= $this->e($livre['titre']) ?></td>
                    <td><?= $this->e($livre['auteur']) ?></td>
                    <td><?= $this->e($livre['categorie']) ?></td>
                    <td><?= $this->e($livre['annee']) ?></td>
                    <td><?= $this->e($livre['nb_exemplaires']) ?></td>
                    <td>
                        <?php if ((int) $livre['disponibles'] > 0): ?>
                            <span class="badge text-bg-success"><?= $this->e($livre['disponibles']) ?></span>
                        <?php else: ?>
                            <span class="badge text-bg-danger">0</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= $this->e($this->url('livres/edit/' . $livre['id'])) ?>"
                           class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="<?= $this->e($this->url('livres/delete/' . $livre['id'])) ?>" method="post"
                              class="d-inline" data-confirm="Supprimer ce livre ?">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
