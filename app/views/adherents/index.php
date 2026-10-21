<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Adhérents</h1>
    <a href="<?= $this->e($this->url('adherents/create')) ?>" class="btn btn-primary">+ Ajouter un adhérent</a>
</div>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= $this->e($flash['type']) ?>"><?= $this->e($flash['message']) ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Classe</th>
                <th>Téléphone</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($adherents)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Aucun adhérent pour le moment.</td></tr>
            <?php endif; ?>
            <?php foreach ($adherents as $adherent): ?>
                <tr>
                    <td><?= $this->e($adherent['id']) ?></td>
                    <td><?= $this->e($adherent['nom']) ?></td>
                    <td><?= $this->e($adherent['prenom']) ?></td>
                    <td><?= $this->e($adherent['classe']) ?></td>
                    <td><?= $this->e($adherent['telephone']) ?></td>
                    <td class="text-end">
                        <a href="<?= $this->e($this->url('adherents/edit/' . $adherent['id'])) ?>"
                           class="btn btn-sm btn-outline-primary">Modifier</a>
                        <form action="<?= $this->e($this->url('adherents/delete/' . $adherent['id'])) ?>" method="post"
                              class="d-inline" data-confirm="Supprimer cet adhérent ?">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
