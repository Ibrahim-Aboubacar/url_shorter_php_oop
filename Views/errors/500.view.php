<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous" />

    <title>500 - SERVER SIDE ERROR</title>
</head>

<body class="bg-dark text-white d-flex justify-content-center align-items-center"
    style="height: 100vh; --bs-primary-rgb: 255,30,30;">

    <div class="position-relative">
        <span class="position-absolute"
            style="z-index: -1;top: 50%; left: 50%; transform: translate(-50%, -50%);font-weight: 700; font-size: 50vw; text-shadow: 0px 0px 70px rgba(var(--bs-primary-rgb),var(--bs-text-opacity)); opacity: .3; color: rgba(var(--bs-dark-rgb),var(--bs-bg-opacity))!important;">500</span>
        <h3 class="text-center position-relative text-primary"
            style="font-size: 20rem; margin-bottom: -5.5rem; z-index: -1; opacity: .8;">
            500
        </h3>
        <h2 class="text-center position-relative">Une erreur s'est produite côde serveur.<br><span
                style="font-size: 1.5rem; opacity: .7"><?= $message ?? '' ?></span></h2>
    </div>

</body>

</html>