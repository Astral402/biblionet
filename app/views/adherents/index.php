<div class="page-head">
  <span class="count"><?= e($total) ?> adhérent<?= $total > 1 ? 's' : '' ?></span>
  <a href="<?= e(url('adherents/create')) ?>" class="btn btn-accent">+ Ajouter un adhérent</a>
</div>

<div class="panel p-0"><div class="table-responsive">
<table class="table table-dark-custom align-middle mb-0">
  <thead>
    <tr><th>Adhérent</th><th>Classe</th><th>Téléphone</th><th class="text-end">Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($adherents as $a): ?>
    <tr>
      <td><span class="avatar sm"><?= e(initial($a['prenom'])) ?></span> <a href="<?= e(url('adherents/show/' . $a['id'])) ?>"><strong><?= e($a['prenom'] . ' ' . $a['nom']) ?></strong></a></td>
      <td><span class="tag"><?= e($a['classe']) ?></span></td>
      <td><?= e($a['telephone']) ?></td>
      <td class="text-end text-nowrap">
        <a class="btn btn-sm btn-ghost" href="<?= e(url('adherents/show/' . $a['id'])) ?>">Fiche</a>
        <a class="btn btn-sm btn-ghost" href="<?= e(url('adherents/edit/' . $a['id'])) ?>">Modifier</a>
        <form method="post" action="<?= e(url('adherents/delete/' . $a['id'])) ?>" class="d-inline" data-confirm="Supprimer cet adhérent ?">
          <?= csrf_field() ?><button class="btn btn-sm btn-ghost danger">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$adherents): ?><tr><td colspan="4" class="text-center text-muted py-4">Aucun adhérent.</td></tr><?php endif; ?>
  </tbody>
</table>
</div></div>

<?= pagination($page, $total, $perPage, 'adherents') ?>
