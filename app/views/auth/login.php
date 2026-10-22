<div class="login-wrap">
  <div class="panel login-card">
    <div class="text-center mb-3">
      <div class="brand-logo lg">📚</div>
      <h1 class="h4 mt-2">Espace bibliothécaire</h1>
      <p class="text-muted small">Connectez-vous pour gérer les livres, les adhérents et les emprunts.</p>
    </div>
    <?php if ($error): ?><div class="alert alert-danger py-2"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="<?= e(url('auth/login')) ?>">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label" for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= e($email) ?>" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label" for="password">Mot de passe</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button class="btn btn-accent w-100">Se connecter</button>
    </form>
  </div>
</div>
