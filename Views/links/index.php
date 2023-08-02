<?php

use Source\Constant; ?>
<h1 class="text-center mt-2">
    Mes liens
</h1>

<section class="row">
    <div class="col-10 m-auto">
        <div class="row justify-content-end">
            <div class="col-3 mr-auto text-end">
                <a href="<?= $router->url('link.create')
                            ?>" class="btn btn-primary">Créer</a>
            </div>
        </div>
        <table class="table table-striped table-responsive table-dark">
            <thead>
                <tr>
                    <th scope="col" class="d-none d-md-table-cell">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Lien Original</th>
                    <th scope="col" class="d-none d-md-table-cell">Lien court</th>
                    <th scope="col" class="text-end" style="max-width: 200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // CHECKING IF THE USER HAS SOME LINKS
                if (count($links)) :

                    // LOOPING THROUGH THE USER'S LINKS
                    for ($i = count($links) - 1; $i >= 0; $i--) :
                        $link = $links[$i];
                        // endfor;
                        // foreach ($links as $link) : 
                ?>
                        <tr>
                            <th scope="row" class="d-none d-md-table-cell"><?= $link->id ?></th>
                            <td><?= $link->name ?></td>
                            <td class="">
                                <p class="text-truncate mb-0" style="max-width: 300px; width: 90%;">
                                    <?= $link->original_link ?>
                                </p>
                                <span class="badge text-bg-<?php if ($link->state != 0) {
                                                                echo 'success';
                                                            } else {
                                                                echo 'danger';
                                                            }  ?>">
                                    <?php if ($link->state != 0) {
                                        echo 'Actif';
                                    } else {
                                        echo 'Inactif';
                                    } ?>
                                </span>
                            </td>
                            <td class="d-none d-md-table-cell">
                                <a href="<?= $router->url('link.short', ['id' => $link->id])  ?>" target="_blank">

                                    <?= $router->url('link.short', ['id' => $link->id])  ?>
                                </a>
                                <!-- BONNUS -->
                                <span class="badge text-bg-light">
                                    visites: <?= $link->visite; ?>
                                </span>
                            </td>
                            <td class="text-end" style="max-width: 200px;">
                                <a href="<?= $router->url('links.edite', ['id' => $link->id]) ?>" class="btn btn-sm my-1 btn-primary">Modifer</a>

                                <form method="POST" action="<?= $router->url('links.delete', ['id' => $link->id]) ?>">
                                    @DELETE
                                    <button data-link-id="<?= $link->id ?>" class="btn btn-sm my-1 btn-danger delete-link-btn">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    endfor;
                // endforeach;
                // DISPLAY MESSAGE IF THE USER DOES NOT HAVE A SHORTED LINK
                else : ?>
                    <tr>
                        <th colspan="5" class="text-center">Aucun lien raccourci, veuillez en <a href="<?= $router->url('link.create'); ?>">créer</a></th>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</section>

<script>
    // LINK DELETE FUNCTION
    const linksBtn = document.querySelectorAll('.delete-link-btn');

    linksBtn.forEach(btn => {

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            // CONFIMATION MESSAGE BEFORE DELETING A LINK
            const conf = confirm('Voulez vous vraiment supprimer ce lien ?');

            if (conf) {
                e.target.parentNode.submit()
            }
        })

    });
</script>