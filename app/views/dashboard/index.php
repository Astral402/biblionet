<?php
$cards = [
    ['📖', 'Titres', $stats['livres'], ''],
    ['📦', 'Exemplaires', $stats['exemplaires'], ''],
    ['👥', 'Adhérents', $stats['adherents'], ''],
    ['🔄', 'Emprunts en cours', $stats['en_cours'], 'accent'],
    ['⏰', 'En retard', $stats['retards'], $stats['retards'] > 0 ? 'danger' : ''],
    ['🧾', 'Emprunts au total', $stats['total'], ''],
];
?>
<div class="stat-grid">
  <?php foreach ($cards as [$icon, $label, $value, $class]): ?>
    <div class="stat <?= e($class) ?>"><i><?= $icon ?></i><div><b><?= e($value) ?></b><small><?= e($label) ?></small></div></div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="panel">
      <h2 class="panel-title">🏆 Top 5 des livres empruntés</h2>
      <ol class="rank">
        <?php foreach ($top as $t): ?>
          <li><span><?= e($t['titre']) ?> <small><?= e($t['auteur']) ?></small></span><em><?= e($t['nb']) ?></em></li>
        <?php endforeach; ?>
        <?php if (!$top): ?><li class="text-muted">Aucun emprunt pour l'instant.</li><?php endif; ?>
      </ol>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="panel">
      <h2 class="panel-title">⏰ Retards à relancer</h2>
      <ul class="rank plain">
        <?php foreach (array_slice($retards, 0, 5) as $r): ?>
          <li>
            <span><strong><?= e($r['prenom'] . ' ' . $r['nom']) ?></strong> <small><?= e($r['classe']) ?> · <?= e($r['titre']) ?></small></span>
            <em class="late"><?= e($r['jours']) ?> j</em>
          </li>
        <?php endforeach; ?>
        <?php if (!$retards): ?><li class="text-success">Aucun retard 🎉</li><?php endif; ?>
      </ul>
      <?php if ($retards): ?>
        <a class="btn btn-sm btn-outline-accent mt-2" href="<?= e(url('emprunts/retards')) ?>">Voir tous les retards →</a>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="panel mt-4">
  <h2 class="panel-title">🧑‍💼 Activité par bibliothécaire</h2>
  <div class="table-responsive">
    <table class="table table-dark-custom align-middle mb-0">
      <thead><tr><th>Bibliothécaire</th><th class="text-end">Emprunts enregistrés</th><th class="text-end">Retours enregistrés</th></tr></thead>
      <tbody>
        <?php foreach ($equipe as $u): ?>
          <tr><td><?= e($u['nom']) ?></td><td class="text-end"><?= e($u['emprunts']) ?></td><td class="text-end"><?= e($u['retours']) ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <a class="btn btn-sm btn-outline-accent mt-3" href="<?= e(url('emprunts/historique')) ?>">Voir l'historique complet →</a>
</div>
