<?php $rendu = $emprunt['date_retour'] !== null; ?>
<div class="page-head no-print">
  <a class="btn btn-ghost" href="<?= e(url('emprunts')) ?>">← Retour aux emprunts</a>
  <button type="button" class="btn btn-accent" data-print>🖨 Imprimer la fiche</button>
</div>

<article class="sheet">
  <header class="sheet-head">
    <div>
      <strong class="sheet-brand">BiblioNet</strong>
      <div>Bibliothèque de l'INPTIC</div>
    </div>
    <div class="text-end">
      <div class="sheet-title">FICHE DE PRÊT</div>
      <div>N° <?= e(str_pad((string) $emprunt['id'], 5, '0', STR_PAD_LEFT)) ?></div>
    </div>
  </header>

  <table class="sheet-table">
    <tr><th>Adhérent</th><td><?= e($emprunt['prenom'] . ' ' . $emprunt['nom']) ?> (<?= e($emprunt['classe']) ?>)</td></tr>
    <tr><th>Livre</th><td><?= e($emprunt['titre']) ?></td></tr>
    <tr><th>Auteur</th><td><?= e($emprunt['auteur']) ?></td></tr>
    <tr><th>Date d'emprunt</th><td><?= e(date('d/m/Y', strtotime($emprunt['date_emprunt']))) ?></td></tr>
    <tr><th>À rendre avant le</th><td><strong><?= e($limite->format('d/m/Y')) ?></strong> (<?= e(Emprunt::JOURS_MAX) ?> jours)</td></tr>
    <tr><th>Enregistré par</th><td><?= e($emprunt['emprunte_par'] ?? '—') ?></td></tr>
    <?php if ($rendu): ?>
      <tr><th>Rendu le</th><td><?= e(date('d/m/Y', strtotime($emprunt['date_retour']))) ?> (retour enregistré par <?= e($emprunt['retour_par'] ?? '—') ?>)</td></tr>
    <?php endif; ?>
  </table>

  <p class="sheet-note">L'adhérent s'engage à rendre le livre en bon état dans le délai indiqué.</p>

  <div class="sheet-sign">
    <div><span>Signature de l'adhérent</span></div>
    <div><span>Signature du bibliothécaire</span></div>
  </div>
</article>
