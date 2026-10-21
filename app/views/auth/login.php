<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 mb-4 text-center">Connexion bibliothécaire</h1>

                <?php if ($erreur): ?>
                    <div class="alert alert-danger"><?= $this->e($erreur) ?></div>
                <?php endif; ?>

                <form action="<?= $this->e($this->url('login')) ?>" method="post" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="<?= $this->e($email) ?>" autocomplete="username" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" id="password" name="password" class="form-control"
                               autocomplete="current-password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
