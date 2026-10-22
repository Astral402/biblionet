<section class="hero" id="recherche">
  <h1>Catalogue de la bibliothèque de l'INPTIC</h1>
  <p>Consultez les ouvrages et leur disponibilité.</p>
  <form class="hero-search" method="get" action="<?= e(url('catalogue')) ?>">
    <input name="q" value="<?= e($q) ?>" placeholder="Titre ou auteur…" aria-label="Recherche">
    <?php if ($categorie !== ''): ?><input type="hidden" name="categorie" value="<?= e($categorie) ?>"><?php endif; ?>
    <button class="btn btn-accent">Rechercher</button>
  </form>
</section>

<div class="chips" aria-label="Catégories">
  <a class="chip<?= $categorie === '' ? ' active' : '' ?>" href="<?= e(url('catalogue' . ($q !== '' ? '?q=' . urlencode($q) : ''))) ?>">Tout</a>
  <?php foreach ($categories as $c): ?>
    <a class="chip<?= $c === $categorie ? ' active' : '' ?>"
       href="<?= e(url('catalogue?' . http_build_query(array_filter(['q' => $q, 'categorie' => $c])))) ?>"><?= e($c) ?></a>
  <?php endforeach; ?>
</div>

<div class="chips" aria-label="Disponibilité">
  <a class="chip<?= $dispo ? ' active' : '' ?>"
     href="<?= e(url('catalogue?' . http_build_query(array_filter(['q' => $q, 'categorie' => $categorie, 'dispo' => $dispo ? null : 1])))) ?>">✅ Disponibles uniquement</a>
</div>

<div class="section-head">
  <h2>Livres</h2>
  <span class="count"><?= e($total) ?> livre<?= $total > 1 ? 's' : '' ?></span>
</div>

<?php if (!$livres): ?>
  <div class="empty-state">
    <div class="big">😕</div>
    <p>Aucun livre ne correspond à votre recherche.</p>
    <a class="btn btn-outline-accent" href="<?= e(url('catalogue')) ?>">Réinitialiser</a>
  </div>
<?php else: ?>
  <div class="book-grid">
    <?php foreach ($livres as $l): $ok = $l['disponibles'] > 0; ?>
      <article class="book-card">
        <div class="cover" style="<?= e(cover_style($l['categorie'])) ?>">
          <span class="cover-cat"><?= e($l['categorie']) ?></span>
          <span class="cover-initial"><?= e(initial($l['titre'])) ?></span>
          <span class="status <?= $ok ? 'ok' : 'ko' ?>"><?= $ok ? 'Disponible' : 'Indisponible' ?></span>
        </div>
        <div class="book-body">
          <h3 title="<?= e($l['titre']) ?>"><?= e($l['titre']) ?></h3>
          <p class="author"><?= e($l['auteur']) ?> · <?= e($l['annee']) ?></p>
          <p class="stock"><?= $ok ? e($l['disponibles']) . ' exemplaire' . ($l['disponibles'] > 1 ? 's' : '') . ' disponible' . ($l['disponibles'] > 1 ? 's' : '') : 'Tous les exemplaires sont empruntés' ?></p>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?= pagination($page, $total, $perPage, 'catalogue', array_filter(['q' => $q])) ?>

<?= pagination($page, $total, $perPage, 'catalogue', array_filter(['q' => $q, 'categorie' => $categorie, 'dispo' => $dispo ? 1 : null])) ?>
