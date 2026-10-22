<div class="page-head">
  <span class="count"><?= count($emprunts) ?> emprunt<?= count($emprunts) > 1 ? 's' : '' ?></span>
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= e(url('emprunts/historique')) ?>" class="btn btn-ghost">🗂 Historique</a>
    <a href="<?= e(url('emprunts/export')) ?>" class="btn btn-ghost">⬇ Export CSV</a>
    <a href="<?= e(url('emprunts/create')) ?>" class="btn btn-accent">+ Nouvel emprunt</a>
  </div>
</div>

<div class="panel p-0"><div class="table-responsive">
<table class="table table-dark-custom align-middle mb-0">
  <thead>
    <tr><th>Livre</th><th>Adhérent</th><th>Emprunté le</th><th>Enregistré par</th><th>Durée</th><th class="text-end">Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($emprunts as $m): ?>
    <?php $late = $m['jours'] > Emprunt::JOURS_MAX; ?>
    <tr class="<?= $late ? 'row-late' : '' ?>">
      <td><strong><?= e($m['titre']) ?></strong><br><small class="text-muted"><?= e($m['auteur']) ?></small></td>
      <td>
        <a href="<?= e(url('adherents/show/' . $m['adherent_id'])) ?>"><?= e($m['prenom'] . ' ' . $m['nom']) ?></a>
        <br><small class="text-muted"><?= e($m['classe']) ?></small>
      </td>
      <td><?= e(date('d/m/Y', strtotime($m['date_emprunt']))) ?></td>
      <td><?= e($m['emprunte_par'] ?? '—') ?></td>
      <td><?= e($m['jours']) ?> j <?php if ($late): ?><span class="badge-soft ko">Retard</span><?php endif; ?></td>
      <td class="text-end text-nowrap">
        <a class="btn btn-sm btn-ghost" href="<?= e(url('emprunts/fiche/' . $m['id'])) ?>">Fiche</a>
        <form method="post" action="<?= e(url('emprunts/retour/' . $m['id'])) ?>" class="d-inline" data-confirm="Enregistrer le retour de ce livre ?">
          <?= csrf_field() ?><button class="btn btn-sm btn-outline-accent">Retour</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$emprunts): ?><tr><td colspan="6" class="text-center text-muted py-4">Aucun emprunt à afficher.</td></tr><?php endif; ?>
  </tbody>
</table>
</div></div>
