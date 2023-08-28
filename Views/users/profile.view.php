<main class="d-flex align-items-center justify-content-center py-4 mt-5">
    <div class="form-signin col-10 col-md-8  m-auto">
        <?php

        use Source\App;

        if ($message) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>OUPS!</strong> <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <section>
            <form method="post" action="<?= ROUTER->url('user.profile.info.action') ?>">
                <h1 class="h3 mb-3 fw-normal">Information Peronnelles</h1>
                <div class="row">
                    @PATCH
                    <div class="col-12 col-md-6">
                        <div class="form-floating has-validation">
                            <input type="text" name="username" class="form-control <?= isset($error['username']) ? "is-invalid" : ""; ?>" id="username" placeholder="John Doe" value="<?= $old_username ?>">
                            <label for="username" style="color: #303030;">Nom d'utilisateur</label>
                            <?php if (isset($error['username'])) : ?>
                                <div class="invalid-feedback">
                                    <?= $error['username'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating mt-3 mt-md-0 has-validation">
                            <input type="email" name="email" class="form-control <?= isset($error['email']) ? "is-invalid" : ""; ?>" id="email" placeholder="john@doe.com" value="<?= $old_email ?>">
                            <label for="email" style="color: #303030;">Adresse Email</label>
                            <?php if (isset($error['email'])) : ?>
                                <div class="invalid-feedback">
                                    <?= $error['email'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <button class="btn btn-primary" type="submit">Sauvegarder</button>
                    </div>
                </div>
            </form>
        </section>
        <hr>

        <section>
            <form method="post" action="<?= ROUTER->url('user.profile.password.action') ?>">
                <h1 class="h3 mb-3 fw-normal">Mot de passe</h1>
                <div class="row">
                    @PATCH
                    <div class="col-12 col-md-4">
                        <div class="form-floating mt-3 has-validation">
                            <input type="password" name="anc_password" class="form-control <?= isset($error['anc_password']) ? "is-invalid" : ""; ?>" id="anc_password" placeholder="Password" value="<?= $old_anc_password ?>">
                            <label for="pass" style="color: #303030;">Mot de passe actuel</label>
                            <?php if (isset($error['anc_password'])) : ?>
                                <div class="invalid-feedback">
                                    <?= $error['anc_password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-floating mt-3 has-validation">
                            <input type="password" name="password" class="form-control <?= isset($error['password']) ? "is-invalid" : ""; ?>" id="pass" placeholder="Password">
                            <label for="pass" style="color: #303030;">Mot de passe</label>
                            <?php if (isset($error['password'])) : ?>
                                <div class="invalid-feedback">
                                    <?= $error['password'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
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
                <div class="row mt-3">
                    <div class="col-4">
                        <button class="btn btn-primary" type="submit">Modifier</button>
                    </div>
                </div>
            </form>
        </section>



        <hr>
        <div class="row justify-content-center align-items-center g-2">
            <div class="col-10 col-sm-6 m-auto">
                <p class="text-sm text-white text-center">Je souhaite suprimer mon compte.</p>
                <!-- <a href="<?= ROUTER->url('user.login'); ?>" class="btn btn-secondary ">Se connecter</a> -->
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger col-5 w-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    Suprimer mon compte
                </button>

            </div>


            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Surpression de compte</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                Bienvenue sur notre plateforme de raccourcissement de liens. Nous comprenons que vos
                                besoins peuvent évoluer et vous pourriez envisager de supprimer votre compte. Veuillez
                                noter que si vous décidez de procéder à cette action, tous les liens que vous avez
                                raccourcis seront également supprimés de notre système.
                            </p>
                            <p>Voulez-vous vraiment suprimer votre compte ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Annuler</button>
                            <form action="<?= App::$router->url('user.profile.delete') ?>" method="post">
                                <button type="submit" class="btn btn-danger">Surprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>