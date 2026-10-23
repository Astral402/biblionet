<div class="page-head">
  <a class="btn btn-ghost" href="<?= e(url('adherents')) ?>">← Adhérents</a>
  <div class="d-flex gap-2">
    <a class="btn btn-ghost" href="<?= e(url('adherents/edit/' . $adherent['id'])) ?>">Modifier</a>
    <a class="btn btn-accent" href="<?= e(url('emprunts/create?adherent=' . $adherent['id'])) ?>">+ Nouvel emprunt</a>
  </div>
</div>

<div class="panel mb-3">
  <div class="d-flex align-items-center gap-3 flex-wrap">
    <span class="avatar"><?= e(initial($adherent['prenom'])) ?></span>
    <div>
      <h2 class="h4 mb-1"><?= e($adherent['prenom'] . ' ' . $adherent['nom']) ?></h2>
      <span class="tag"><?= e($adherent['classe']) ?></span>
      <?php if ($adherent['telephone']): ?><span class="text-muted ms-2"><?= e($adherent['telephone']) ?></span><?php endif; ?>
    </div>
  </div>
</div>

<div class="stat-grid">
  <div class="stat"><i>🧾</i><div><b><?= e(count($emprunts)) ?></b><small>Emprunts au total</small></div></div>
  <div class="stat accent"><i>🔄</i><div><b><?= e($enCours) ?> / <?= e(Emprunt::LIMITE) ?></b><small>En cours (limite <?= e(Emprunt::LIMITE) ?>)</small></div></div>
  <div class="stat <?= $retards > 0 ? 'danger' : '' ?>"><i>⏰</i><div><b><?= e($retards) ?></b><small>En retard</small></div></div>
</div>

<div class="panel p-0"><div class="table-responsive">
<table class="table table-dark-custom align-middle mb-0">
  <thead>
    <tr><th>Livre</th><th>Emprunté le</th><th>Retourné le</th><th>Statut</th><th>Emprunt enregistré par</th><th>Retour enregistré par</th><th class="text-end">Fiche</th></tr>
  </thead>
  <tbody>
  <?php foreach ($emprunts as $m): ?>
    <?php $rendu = $m['date_retour'] !== null; $late = !$rendu && $m['jours'] > Emprunt::JOURS_MAX; ?>
    <tr class="<?= $late ? 'row-late' : '' ?>">
      <td><strong><?= e($m['titre']) ?></strong><br><small class="text-muted"><?= e($m['auteur']) ?></small></td>
      <td><?= e(date('d/m/Y', strtotime($m['date_emprunt']))) ?></td>
      <td><?= $rendu ? e(date('d/m/Y', strtotime($m['date_retour']))) : '—' ?></td>
      <td>
        <?php if ($rendu): ?><span class="badge-soft ok">Rendu</span>
        <?php elseif ($late): ?><span class="badge-soft ko">Retard · <?= e($m['jours']) ?> j</span>
        <?php else: ?><span class="tag">En cours · <?= e($m['jours']) ?> j</span><?php endif; ?>
      </td>
      <td><?= e($m['emprunte_par'] ?? '—') ?></td>
      <td><?= $rendu ? e($m['retour_par'] ?? '—') : '—' ?></td>
      <td class="text-end"><a class="btn btn-sm btn-ghost" href="<?= e(url('emprunts/fiche/' . $m['id'])) ?>">Fiche</a></td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$emprunts): ?><tr><td colspan="7" class="text-center text-muted py-4">Cet adhérent n'a encore rien emprunté.</td></tr><?php endif; ?>
  </tbody>
</table>
</div></div>
