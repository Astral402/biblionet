<?php
$segment = current_segment();
$adminShell = $user && !in_array($segment, ['catalogue', 'auth'], true);
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0b0d14">
  <title><?= e($title ?? 'BiblioNet') ?> — BiblioNet</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= e(asset('css/style.css')) ?>" rel="stylesheet">
</head>
<body class="<?= $adminShell ? 'shell-admin' : 'shell-public' ?>">

<?php if ($adminShell): ?>
<aside class="sidebar" id="sidebar">
  <a class="brand" href="<?= e(url('catalogue')) ?>"><span class="brand-logo">📚</span><span class="brand-text">Biblio<b>Net</b></span></a>

  <nav class="side-nav" aria-label="Navigation principale">
    <div class="side-title">Pilotage</div>
    <a class="side-link<?= nav_active('dashboard') ?>" href="<?= e(url('dashboard')) ?>"><i>📊</i><span>Tableau de bord</span></a>

    <div class="side-title">Catalogue</div>
    <a class="side-link<?= nav_active('livres') ?>" href="<?= e(url('livres')) ?>"><i>📖</i><span>Livres</span></a>
    <a class="side-link<?= nav_active('adherents') ?>" href="<?= e(url('adherents')) ?>"><i>👥</i><span>Adhérents</span></a>

    <div class="side-title">Emprunts</div>
    <a class="side-link<?= nav_active('emprunts') ?>" href="<?= e(url('emprunts')) ?>"><i>🔄</i><span>En cours</span></a>
    <a class="side-link<?= nav_active('emprunts', 'historique') ?>" href="<?= e(url('emprunts/historique')) ?>"><i>🗂</i><span>Historique</span></a>
    <a class="side-link<?= nav_active('emprunts', 'retards') ?>" href="<?= e(url('emprunts/retards')) ?>"><i>⏰</i><span>Retards</span></a>

    <div class="side-title">Site</div>
    <a class="side-link" href="<?= e(url('catalogue')) ?>"><i>🌐</i><span>Catalogue public</span></a>
  </nav>

  <div class="side-user">
    <div class="avatar"><?= e(initial($user['nom'])) ?></div>
    <div class="who"><strong><?= e($user['nom']) ?></strong><small><?= e($user['email']) ?></small></div>
    <form method="post" action="<?= e(url('auth/logout')) ?>" class="m-0">
      <?= csrf_field() ?>
      <button class="btn-icon" title="Déconnexion" aria-label="Déconnexion">⏻</button>
    </form>
  </div>
</aside>
<div class="sidebar-backdrop" id="backdrop"></div>

<div class="admin-main">
  <header class="topbar">
    <button class="btn-icon d-lg-none" id="menuBtn" aria-label="Ouvrir le menu">☰</button>
    <h1 class="topbar-title"><?= e($title ?? '') ?></h1>
    <a class="btn btn-sm btn-accent ms-auto" href="<?= e(url('emprunts/create')) ?>">+ Emprunt</a>
  </header>
  <main class="content">
    <?php if ($flash): ?>
      <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= e($flash['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
      </div>
    <?php endif; ?>
    <?= $content ?>
  </main>
</div>

<?php else: ?>
<header class="public-header">
  <div class="container d-flex align-items-center gap-3">
    <a class="brand" href="<?= e(url('catalogue')) ?>"><span class="brand-logo">📚</span><span class="brand-text">Biblio<b>Net</b></span></a>
    <nav class="pub-nav d-none d-md-flex">
      <a class="pub-link<?= nav_active('catalogue') ?>" href="<?= e(url('catalogue')) ?>">Catalogue</a>
      <a class="pub-link<?= $segment === 'catalogue' && isset($_GET['dispo']) ? ' active' : '' ?>" href="<?= e(url('catalogue?dispo=1')) ?>">Disponibles</a>
    </nav>
    <form class="pub-search ms-auto d-none d-md-flex" method="get" action="<?= e(url('catalogue')) ?>">
      <input name="q" value="<?= e(get_string('q')) ?>" placeholder="Rechercher un titre, un auteur…" aria-label="Recherche">
      <button aria-label="Rechercher">🔍</button>
    </form>
    <?php if ($user): ?>
      <a class="btn btn-sm btn-accent ms-auto" href="<?= e(url(admin_home())) ?>">Espace bibliothécaire</a>
    <?php else: ?>
      <a class="btn btn-sm btn-accent ms-auto ms-md-0" href="<?= e(url('auth/login')) ?>">Connexion</a>
    <?php endif; ?>
  </div>
</header>

<main class="container public-content">
  <?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
      <?= e($flash['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  <?php endif; ?>
  <?= $content ?>
</main>

<footer class="public-footer">BiblioNet — Bibliothèque de l'INPTIC · Mini-projet MVC <?= date('Y') ?></footer>

<nav class="tabbar d-md-none" aria-label="Navigation mobile">
  <a class="<?= $segment === 'catalogue' && !isset($_GET['q']) ? 'active' : '' ?>" href="<?= e(url('catalogue')) ?>"><i>🏠</i><span>Accueil</span></a>
  <a href="<?= e(url('catalogue')) ?>#recherche"><i>🔍</i><span>Recherche</span></a>
  <a href="<?= e(url('catalogue?dispo=1')) ?>"><i>✅</i><span>Dispo</span></a>
  <a class="<?= $segment === 'auth' ? 'active' : '' ?>" href="<?= e(url($user ? admin_home() : 'auth/login')) ?>"><i>👤</i><span><?= $user ? 'Admin' : 'Connexion' ?></span></a>
</nav>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
