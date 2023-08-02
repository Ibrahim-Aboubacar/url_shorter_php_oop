<main class="d-flex align-items-center justify-content-center py-4 mt-5">
    <div class="form-signin col-10 col-md-8 col-lg-5 m-auto">
        <form method="POST" action="<?= $router->url('user.login.action') ?>">
            @PUT
            <?php if (isset($message)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>OUPS!</strong> <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <h1 class="h3 mb-3 fw-normal">Formulaire de connexion</h1>

            <div class="form-floating mt-3 has-validation">
                <input type="email" name="email" class="form-control <?= isset($error['email']) ? "is-invalid" : ""; ?>" id="email" placeholder="name@example.com" value="<?= $old_email ?>">
                <label for="email" style="color: #303030;">Adresse Email</label>
                <?php if (isset($error['email'])) : ?>
                    <div class="invalid-feedback">
                        <?= $error['email'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-floating mt-3 has-validation">
                <input type="password" name="password" class="form-control" id="pass" placeholder="Password">
                <label for="pass" style="color: #303030;">Mot de passe</label>

            </div>

            <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Se connecter</button>

        </form>
        <hr>
        <div class="row justify-content-center align-items-center g-2">
            <div class="col-8 col-sm-5 m-auto">
                <p class="text-sm text-muted text-center">Je n'ai pas de compte.</p>
                <a href="<?= $router->url('user.register'); ?>" class="btn btn-secondary col-5 w-100">S'inscrire</a>

            </div>
        </div>
    </div>
</main>