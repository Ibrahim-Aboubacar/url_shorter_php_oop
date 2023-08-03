<?php

if (!isset($pageName)) {
    $pageName = '';
}

// dda($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= strtoupper($pageName) . ' - ECOLE-IT' ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title><?= $pageName ?></title>
</head>

<body class="bg-dark text-white">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-sm bg-body-tertiary">
            <div class="container-fluid">
                <!-- Web site name -->
                <a class="navbar-brand fw-bolder" href="#">ECOLE-IT</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <ul class="navbar-nav me-auto my-2 mb-lg-0 gap-2">
                        <li class="nav-item">
                            <a href="<?= $router->url('home') ?>" class="nav-link <?= $pageName == 'accueil' ? 'active' : '' ?>">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $router->url('links.show') ?>" class="nav-link  <?= $pageName == 'links' ? 'active' : '' ?>">Mes
                                liens</a>
                        </li>
                    </ul>

                    <ul class="navbar-nav nav-pills col-md-5 text-end p-0 my-2 gap-2 justify-content-end">
                        <?php if (isset($_SESSION['auth'])) : ?>
                            <div class="dropstart">
                                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?= $_SESSION['auth']['username'] ?>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="<?= $router->url('user.logout') ?>">Se déconnecter</a></li>
                                </ul>
                            </div>
                        <?php else : ?>
                            <a href="<?= $router->url('user.login') ?>" class="btn btn-outline-primary me-2">Se
                                connecter</a>
                            <a href="<?= $router->url('user.register') ?>" class="btn btn-primary">S'inscrire</a>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>