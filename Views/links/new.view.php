<main class="d-flex align-items-center justify-content-center py-4 mt-5">
    <div class="form-signin col-10 col-md-8 col-lg-5  m-auto">
        <form method="post" action="<?= $router->url('link.store')
                                    ?>">
            <?php if (isset($message)) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>OUPS!</strong> <?= $message ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <h1 class="h3 mb-3 fw-normal">Nouveau lien court</h1>

            <div class="form-floating has-validation">
                <input type="text" name="name" class="form-control <?= isset($error['name']) ? "is-invalid" : ""; ?>" id="name" placeholder="ex: Mes produits facebook" value="<?= $old_name ?>">
                <label for="name" style="color: #353535;">Nom du lien</label>


                <?php if (isset($error['name'])) : ?>
                    <div class="invalid-feedback">
                        <?= $error['name'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-floating mt-3 has-validation">
                <input type="text" name="link" class="form-control <?= isset($error['link']) ? "is-invalid" : ""; ?>" id="link" placeholder="ex: https://google.com" value="<?= $old_link ?>">
                <label for="link" style="color: #353535;">Lien Original</label>
                <?php if (isset($error['link'])) : ?>
                    <div class="invalid-feedback">
                        <?= $error['link'] ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" name="state" role="switch" id="flexSwitchCheckChecked" <?= $old_state ? 'checked' : '' ?>>
                <label class="form-check-label" for="flexSwitchCheckChecked"> Activé</label>
            </div>

            <button class="btn btn-primary w-100 py-2 mt-3" type="submit">Créer</button>
        </form>
    </div>

</main>