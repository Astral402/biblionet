<div class="page-head">
  <form class="inline-search" method="get" action="<?= e(url('livres')) ?>">
    <input name="q" value="<?= e($q) ?>" placeholder="Rechercher par titre ou auteur" aria-label="Recherche">
    <button class="btn btn-outline-accent">Rechercher</button>
  </form>
  <a href="<?= e(url('livres/create')) ?>" class="btn btn-accent">+ Ajouter un livre</a>
</div>

<div class="panel p-0"><div class="table-responsive">
<table class="table table-dark-custom align-middle mb-0">
  <thead>
    <tr><th>Livre</th><th>Catégorie</th><th>Exemplaires</th><th class="text-end">Actions</th></tr>
  </thead>
  <tbody>
  <?php foreach ($livres as $l): $ok = $l['disponibles'] > 0; ?>
    <tr>
      <td><strong><?= e($l['titre']) ?></strong><br><small class="text-muted"><?= e($l['auteur']) ?> · <?= e($l['annee']) ?></small></td>
      <td><span class="tag"><?= e($l['categorie']) ?></span></td>
      <td><span class="badge-soft <?= $ok ? 'ok' : 'ko' ?>"><?= e($l['disponibles']) ?> / <?= e($l['nb_exemplaires']) ?></span></td>
      <td class="text-end text-nowrap">
        <?php if ($ok): ?><a class="btn btn-sm btn-outline-accent" href="<?= e(url('emprunts/create?livre=' . $l['id'])) ?>">Emprunter</a><?php endif; ?>
        <a class="btn btn-sm btn-ghost" href="<?= e(url('livres/edit/' . $l['id'])) ?>">Modifier</a>
        <form method="post" action="<?= e(url('livres/delete/' . $l['id'])) ?>" class="d-inline" data-confirm="Supprimer ce livre ?">
          <?= csrf_field() ?><button class="btn btn-sm btn-ghost danger">Supprimer</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$livres): ?><tr><td colspan="4" class="text-center text-muted py-4">Aucun livre.</td></tr><?php endif; ?>
  </tbody>
</table>
</div></div>

<?= pagination($page, $total, $perPage, 'livres', array_filter(['q' => $q])) ?>
