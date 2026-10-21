<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->e($titre) ?> — BiblioNet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $this->url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $this->url() ?>">📚 BiblioNet</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu"
                aria-controls="menu" aria-expanded="false" aria-label="Afficher le menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="<?= $this->url() ?>">Catalogue</a></li>
                <?php if ($this->estConnecte()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= $this->url('livres') ?>">Livres</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $this->url('adherents') ?>">Adhérents</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= $this->url('emprunts') ?>">Emprunts</a></li>
                <?php endif; ?>
            </ul>
            <?php if ($this->estConnecte()): ?>
                <span class="navbar-text text-white me-3"><?= $this->e($_SESSION['user_nom'] ?? '') ?></span>
                <form action="<?= $this->url('logout') ?>" method="post" class="d-inline">
                    <button type="submit" class="btn btn-light btn-sm">Déconnexion</button>
                </form>
            <?php else: ?>
                <a class="btn btn-light btn-sm" href="<?= $this->url('login') ?>">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    <?= $content ?>
</main>

<footer class="bg-light border-top py-3 mt-auto">
    <div class="container text-center text-muted small">
        BiblioNet — Bibliothèque de l'INPTIC · Mini-projet MVC <?= date('Y') ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $this->url('assets/js/app.js') ?>"></script>
</body>
</html>
