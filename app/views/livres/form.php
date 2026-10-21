<?php
$e = fn($valeur) => htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
$classe = fn($champ) => isset($errors[$champ]) ? ' is-invalid' : '';
?>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <h1 class="h3 mb-4"><?= $e($titre) ?></h1>

        <form action="<?= $e($this->url($action)) ?>" method="post" novalidate>
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" id="titre" name="titre" class="form-control<?= $classe('titre') ?>"
                       value="<?= $e($livre['titre'] ?? '') ?>" maxlength="255" required>
                <?php if (isset($errors['titre'])): ?>
                    <div class="invalid-feedback"><?= $e($errors['titre']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="auteur" class="form-label">Auteur</label>
                <input type="text" id="auteur" name="auteur" class="form-control<?= $classe('auteur') ?>"
                       value="<?= $e($livre['auteur'] ?? '') ?>" maxlength="150" required>
                <?php if (isset($errors['auteur'])): ?>
                    <div class="invalid-feedback"><?= $e($errors['auteur']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="categorie" class="form-label">Catégorie</label>
                <input type="text" id="categorie" name="categorie" class="form-control<?= $classe('categorie') ?>"
                       value="<?= $e($livre['categorie'] ?? '') ?>" maxlength="100" required>
                <?php if (isset($errors['categorie'])): ?>
                    <div class="invalid-feedback"><?= $e($errors['categorie']) ?></div>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-6 mb-3">
                    <label for="annee" class="form-label">Année</label>
                    <input type="number" id="annee" name="annee" class="form-control<?= $classe('annee') ?>"
                           value="<?= $e($livre['annee'] ?? '') ?>" required>
                    <?php if (isset($errors['annee'])): ?>
                        <div class="invalid-feedback"><?= $e($errors['annee']) ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-6 mb-3">
                    <label for="nb_exemplaires" class="form-label">Exemplaires</label>
                    <input type="number" id="nb_exemplaires" name="nb_exemplaires"
                           class="form-control<?= $classe('nb_exemplaires') ?>"
                           value="<?= $e($livre['nb_exemplaires'] ?? '') ?>" min="1" required>
                    <?php if (isset($errors['nb_exemplaires'])): ?>
                        <div class="invalid-feedback"><?= $e($errors['nb_exemplaires']) ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="<?= $e($this->url('livres')) ?>" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
