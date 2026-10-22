<?php $exportUrl = url('emprunts/export') . ($actifs ? '?' . http_build_query($actifs) : ''); ?>
<form method="get" action="<?= e(url('emprunts/historique')) ?>" class="panel form-panel row g-3 mb-3">
  <div class="col-md-4">
    <label class="form-label" for="adherent">Adhérent</label>
    <select class="form-select" id="adherent" name="adherent">
      <option value="">Tous</option>
      <?php foreach ($adherents as $a): ?>
        <option value="<?= e($a['id']) ?>" <?= (int) $filtres['adherent'] === (int) $a['id'] ? 'selected' : '' ?>>
          <?= e($a['nom'] . ' ' . $a['prenom']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label" for="livre">Livre (titre ou auteur)</label>
    <input class="form-control" id="livre" name="livre" value="<?= e($filtres['livre']) ?>" maxlength="100">
  </div>
  <div class="col-md-4">
    <label class="form-label" for="par">Enregistré par</label>
    <select class="form-select" id="par" name="par">
      <option value="">Tous</option>
      <?php foreach ($users as $u): ?>
        <option value="<?= e($u['id']) ?>" <?= (int) $filtres['par'] === (int) $u['id'] ? 'selected' : '' ?>><?= e($u['nom']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label" for="du">Emprunté du</label>
    <input class="form-control" type="date" id="du" name="du" value="<?= e($filtres['du']) ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label" for="au">au</label>
    <input class="form-control" type="date" id="au" name="au" value="<?= e($filtres['au']) ?>">
  </div>
  <div class="col-md-3">
    <label class="form-label" for="statut">Statut</label>
    <select class="form-select" id="statut" name="statut">
      <option value="">Tous</option>
      <option value="en_cours" <?= $filtres['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
      <option value="retard" <?= $filtres['statut'] === 'retard' ? 'selected' : '' ?>>En retard</option>
      <option value="rendus" <?= $filtres['statut'] === 'rendus' ? 'selected' : '' ?>>Rendus</option>
    </select>
  </div>
  <div class="col-md-3 d-flex align-items-end gap-2">
    <button class="btn btn-accent">Filtrer</button>
    <a class="btn btn-ghost" href="<?= e(url('emprunts/historique')) ?>">Réinitialiser</a>
  </div>
</form>

<div class="page-head">
  <span class="count"><?= e($total) ?> résultat<?= $total > 1 ? 's' : '' ?></span>
  <a href="<?= e($exportUrl) ?>" class="btn btn-ghost">⬇ Export CSV<?= $actifs ? ' (filtres appliqués)' : '' ?></a>
</div>

<div class="panel p-0"><div class="table-responsive">
<table class="table table-dark-custom align-middle mb-0">
  <thead>
    <tr><th>Livre</th><th>Adhérent</th><th>Emprunté le</th><th>Retourné le</th><th>Statut</th><th>Emprunt par</th><th>Retour par</th><th class="text-end">Fiche</th></tr>
  </thead>
  <tbody>
  <?php foreach ($rows as $m): ?>
    <?php $rendu = $m['date_retour'] !== null; $late = !$rendu && $m['jours'] > Emprunt::JOURS_MAX; ?>
    <tr class="<?= $late ? 'row-late' : '' ?>">
      <td><strong><?= e($m['titre']) ?></strong><br><small class="text-muted"><?= e($m['auteur']) ?></small></td>
      <td>
        <a href="<?= e(url('adherents/show/' . $m['adherent_id'])) ?>"><?= e($m['prenom'] . ' ' . $m['nom']) ?></a>
        <br><small class="text-muted"><?= e($m['classe']) ?></small>
      </td>
      <td><?= e(date('d/m/Y', strtotime($m['date_emprunt']))) ?></td>
      <td><?= $rendu ? e(date('d/m/Y', strtotime($m['date_retour']))) : '—' ?></td>
      <td>
        <?php if ($rendu): ?><span class="badge-soft ok">Rendu</span>
        <?php elseif ($late): ?><span class="badge-soft ko">Retard</span>
        <?php else: ?><span class="tag">En cours</span><?php endif; ?>
      </td>
      <td><?= e($m['emprunte_par'] ?? '—') ?></td>
      <td><?= e($m['retour_par'] ?? '—') ?></td>
      <td class="text-end"><a class="btn btn-sm btn-ghost" href="<?= e(url('emprunts/fiche/' . $m['id'])) ?>">Fiche</a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="8" class="text-center text-muted py-4">Aucun emprunt ne correspond à ces critères.</td></tr><?php endif; ?>
  </tbody>
</table>
</div></div>

<?= pagination($page, $total, $perPage, 'emprunts/historique', $actifs) ?>
