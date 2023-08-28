<main class="d-flex align-items-center justify-content-center py-4 mt-5">
    <div class="form-signin col-10 col-md-8 col-lg-6  m-auto">
        <form method="post" action="<?= ROUTER->url('user.register.action') ?>">
            <?php if (isset($message)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>OUPS!</strong> <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <h1 class="h3 mb-3 fw-normal">Formulaire d'inscription</h1>

            <div class="form-floating has-validation">
                <input type="text" name="username" class="form-control <?= isset($error['username']) ? "is-invalid" : ""; ?>" id="username" placeholder="John Doe" value="<?= $old_username ?>">
                <label for="username" style="color: #303030;">Nom d'utilisateur</label>


                <?php if (isset($error['username'])) : ?>
                    <div class="invalid-feedback">
                        <?= $error['username'] ?>
                    </div>
                <?php endif; ?>



            </div>
            <div class="form-floating mt-3 has-validation">
                <input type="email" name="email" class="form-control <?= isset($error['email']) ? "is-invalid" : ""; ?>" id="email" placeholder="john@doe.com" value="<?= $old_email ?>">
                <label for="email" style="color: #303030;">Adresse Email</label>
                <?php if (isset($error['email'])) : ?>
                    <div class="invalid-feedback">
                        <?= $error['email'] ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-floating mt-3 has-validation">
                        <input type="password" name="password" class="form-control <?= isset($error['password']) ? "is-invalid" : ""; ?>" id="pass" placeholder="Password" value="<?= $old_password ?>">
                        <label for="pass" style="color: #303030;">Mot de passe</label>
                        <?php if (isset($error['password'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['password'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-floating mt-3 has-validation">
                        <input type="password" name="c_password" class="form-control <?= isset($error['c_password']) ? "is-invalid" : ""; ?>" id="c_pass" placeholder="Password">
                        <label for="c_pass" style="color: #303030;">Mot de passe de confirmation</label>

                        <?php if (isset($error['c_password'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['c_password'] ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <button class="btn btn-primary w-100 py-2 mt-3" type="submit">S'incrire</button>
        </form>
        <hr>
        <div class="row justify-content-center align-items-center g-2">
            <div class="col-8 col-sm-5 m-auto">
                <p class="text-sm text-muted text-center">J'ai déjà un compte.</p>
                <a href="<?= ROUTER->url('user.login'); ?>" class="btn btn-secondary col-5 w-100">Se connecter</a>

            </div>
        </div>
    </div>

</main>