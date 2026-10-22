<?php foreach ($errors as $error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" action="<?= e(url($action)) ?>" class="panel form-panel row g-3">
  <?= csrf_field() ?>
  <div class="col-12">
    <label class="form-label" for="titre">Titre</label>
    <input class="form-control" id="titre" name="titre" value="<?= e(old($livre, 'titre')) ?>" required maxlength="255">
  </div>
  <div class="col-md-6">
    <label class="form-label" for="auteur">Auteur</label>
    <input class="form-control" id="auteur" name="auteur" value="<?= e(old($livre, 'auteur')) ?>" required maxlength="150">
  </div>
  <div class="col-md-6">
    <label class="form-label" for="categorie">Catégorie</label>
    <input class="form-control" id="categorie" name="categorie" list="categories" value="<?= e(old($livre, 'categorie')) ?>" required maxlength="100">
    <datalist id="categories">
      <?php foreach ($categories as $c): ?><option value="<?= e($c) ?>"><?php endforeach; ?>
    </datalist>
  </div>
  <div class="col-md-6">
    <label class="form-label" for="annee">Année</label>
    <input type="number" class="form-control" id="annee" name="annee" value="<?= e(old($livre, 'annee', date('Y'))) ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label" for="nb_exemplaires">Nombre d'exemplaires</label>
    <input type="number" min="1" class="form-control" id="nb_exemplaires" name="nb_exemplaires" value="<?= e(old($livre, 'nb_exemplaires', 1)) ?>" required>
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-accent">Enregistrer</button>
    <a class="btn btn-ghost" href="<?= e(url('livres')) ?>">Annuler</a>
  </div>
</form>
