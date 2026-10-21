<?php
$classe = fn($champ) => isset($errors[$champ]) ? ' is-invalid' : '';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h1 class="h3 mb-4"><?= $this->e($titre) ?></h1>

        <form action="<?= $this->e($this->url($action)) ?>" method="post" novalidate>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-control<?= $classe('nom') ?>"
                           value="<?= $this->e($adherent['nom'] ?? '') ?>" maxlength="80" required>
                    <?php if (isset($errors['nom'])): ?>
                        <div class="invalid-feedback"><?= $this->e($errors['nom']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-control<?= $classe('prenom') ?>"
                           value="<?= $this->e($adherent['prenom'] ?? '') ?>" maxlength="80" required>
                    <?php if (isset($errors['prenom'])): ?>
                        <div class="invalid-feedback"><?= $this->e($errors['prenom']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="classe" class="form-label">Classe</label>
                <input type="text" id="classe" name="classe" class="form-control<?= $classe('classe') ?>"
                       value="<?= $this->e($adherent['classe'] ?? '') ?>" maxlength="50" required>
                <?php if (isset($errors['classe'])): ?>
                    <div class="invalid-feedback"><?= $this->e($errors['classe']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-control<?= $classe('telephone') ?>"
                       value="<?= $this->e($adherent['telephone'] ?? '') ?>" maxlength="30">
                <?php if (isset($errors['telephone'])): ?>
                    <div class="invalid-feedback"><?= $this->e($errors['telephone']) ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= $this->e($this->url('adherents')) ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
