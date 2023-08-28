<section class="container mb-5 pb-5">
    <h1 class="my-5">
        Bienvenue sur notre plateforme de raccourcissement de liens.
    </h1>

    <section>
        <h2 class="my-3">
            Supression de compte:
        </h2>
        <p>
            Nous comprenons que vos besoins peuvent évoluer et vous pourriez envisager de supprimer votre compte.
            Veuillez noter que si vous décidez de procéder à cette action, tous les liens que vous avez raccourcis
            seront également supprimés de notre système.
        </p>
    </section>
    <section>
        <p>
            Afin de garantir la sécurité de ce processus, veuillez confirmer votre décision en saisissant votre mot de
            passe actuel. Cette étape vise à vérifier votre identité et à garantir que la suppression de votre compte
            est une action intentionnelle.
        </p>
        <p>
            Par ailleurs, nous aimerions vous inviter, si vous le souhaitez, à partager la raison de votre décision.
            Votre retour d'expérience est précieux pour nous et nous aide à améliorer continuellement nos services.
            Cependant, cette étape est totalement facultative et vous pouvez laisser ce champ vide si vous préférez.
        </p>
        <p>
            Veuillez également prendre en compte que, suite à la suppression de votre compte, vous ne pourrez pas créer
            un nouveau compte pendant les 6 prochains mois. Vos informations personnelles resteront enregistrées dans
            notre système pendant cette période, mais seront supprimées de manière permanente après ces 6 mois.
        </p>
        <p>
            Si, dans les 6 mois suivant la suppression de votre compte, vous décidez de le réactiver, vous pouvez le
            faire en contactant notre service client et en fournissant les informations nécessaires.
        </p>
        <p>
            Nous apprécions sincèrement votre utilisation de notre service et espérons que vous avez trouvé notre
            plateforme utile. Si vous avez des questions supplémentaires ou des préoccupations, n'hésitez pas à nous
            contacter.
        </p>
    </section>
    <section>
        <?php

        use Source\App;

        if ($message) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>OUPS!</strong> <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form method="post" action="<?= ROUTER->url('user.profile.delete.action') ?>">
            @DELETE
            <div class="row mt-3">
                <div class="col-12">
                    <div class="form-group mt-3 has-validation">

                        <label class="form-label mb-3" for="comment">La raison de votre décision:</label>
                        <textarea name="comment" class="form-control <?= isset($error['email']) ? "is-invalid" : ""; ?>" id="comment" cols="30" rows="3"><?= $old_comment ?? '' ?></textarea>
                        <?php if (isset($error['comment'])) : ?>
                            <div class="invalid-feedback">
                                <?= $error['comment'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-4">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger col-5 w-100" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        SUPRIMER
                    </button>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3 class="h3 mb-3 fw-normal">Mot de passe</h3>
                            <p>
                                Afin de garantir la sécurité de ce processus, veuillez confirmer votre décision en
                                saisissant votre mot de passe actuel. Cette étape vise à vérifier votre identité et à
                                garantir que la suppression de votre compte est une action intentionnelle.
                            </p>
                            <div class="row">
                                <div class="col-12">
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
                            </div>
                            <p class="mt-4">Voulez-vous vraiment suprimer votre compte ?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success p-3 " data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger p-3 ml-3"> SUPRIMER</button>
                        </div>
                    </div>
                </div>
            </div>




        </form>
    </section>
</section>