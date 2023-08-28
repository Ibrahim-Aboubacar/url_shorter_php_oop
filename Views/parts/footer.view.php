<div class="container mt-auto">
    <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 border-top">
        <div class="row col-12 col-sm-8 align-items-center">
            <p class="col-12 col-sm-6 mb-0 text-white text-center text-sm-left">© 2023 | Chargé en
                <span style="color: <?= 1000 * (microtime(true) -  DEBUG_TIME) >= 500 ? 'red' : 'green' ?>"">
                    <?= number_format(1000 * (microtime(true) -  DEBUG_TIME), 2, ',', ' ') ?> Ms
                </span>
            </p>

            <div class=" col-12 col-sm-6 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto">
                    <a href="<?= ROUTER->url('home') ?>" class="link-body-emphasis text-white fs-3 fw-bold text-decoration-none">
                        <?= APP_NAME ?>
                    </a>
        </div>
</div>

<ul class="nav col-12 col-sm-4 justify-content-center justify-sm-content-end">
    <li class="nav-item">
        <a href="<?= ROUTER->url('home') ?>" class="nav-link px-2 text-white">
            Accueil
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= ROUTER->url('links.show') ?>" class="nav-link px-2 text-white">
            Mes liens
        </a>
    </li>
</ul>
</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
</script>
</body>

</html>