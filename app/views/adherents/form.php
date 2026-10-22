<?php foreach ($errors as $error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" action="<?= e(url($action)) ?>" class="panel form-panel row g-3">
  <?= csrf_field() ?>
  <div class="col-md-6">
    <label class="form-label" for="nom">Nom</label>
    <input class="form-control" id="nom" name="nom" value="<?= e(old($adherent, 'nom')) ?>" required maxlength="80">
  </div>
  <div class="col-md-6">
    <label class="form-label" for="prenom">Prénom</label>
    <input class="form-control" id="prenom" name="prenom" value="<?= e(old($adherent, 'prenom')) ?>" required maxlength="80">
  </div>
  <div class="col-md-6">
    <label class="form-label" for="classe">Classe</label>
    <input class="form-control" id="classe" name="classe" value="<?= e(old($adherent, 'classe')) ?>" required maxlength="50">
  </div>
  <div class="col-md-6">
    <label class="form-label" for="telephone">Téléphone</label>
    <input class="form-control" id="telephone" name="telephone" value="<?= e(old($adherent, 'telephone')) ?>" maxlength="30">
  </div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-accent">Enregistrer</button>
    <a class="btn btn-ghost" href="<?= e(url('adherents')) ?>">Annuler</a>
  </div>
</form>
