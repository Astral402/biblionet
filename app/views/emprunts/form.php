<?php if (!$livres): ?><div class="alert alert-warning">Aucun livre n'est disponible actuellement.</div><?php endif; ?>
<form method="post" action="<?= e(url('emprunts/store')) ?>" class="panel form-panel row g-3">
  <?= csrf_field() ?>
  <div class="col-12">
    <label class="form-label" for="livre_id">Livre (exemplaires disponibles uniquement)</label>
    <select class="form-select" id="livre_id" name="livre_id" required>
      <option value="">— Choisir un livre —</option>
      <?php foreach ($livres as $l): ?>
        <option value="<?= e($l['id']) ?>" <?= (int) $l['id'] === $livreId ? 'selected' : '' ?>>
          <?= e($l['titre'] . ' — ' . $l['auteur'] . ' (' . $l['disponibles'] . ' dispo.)') ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12">
    <label class="form-label" for="adherent_id">Adhérent</label>
    <select class="form-select" id="adherent_id" name="adherent_id" required>
      <option value="">— Choisir un adhérent —</option>
      <?php foreach ($adherents as $a): ?>
        <option value="<?= e($a['id']) ?>" <?= (int) $a['id'] === $adherentId ? 'selected' : '' ?>><?= e($a['nom'] . ' ' . $a['prenom'] . ' (' . $a['classe'] . ')') ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12"><small class="text-muted">Un adhérent peut avoir au maximum <?= e(Emprunt::LIMITE) ?> livres non rendus.</small></div>
  <div class="col-12 d-flex gap-2">
    <button class="btn btn-accent">Enregistrer l'emprunt</button>
    <a class="btn btn-ghost" href="<?= e(url('emprunts')) ?>">Annuler</a>
  </div>
</form>
