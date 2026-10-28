<?php
$pagina = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/../DB/helpers/auth.php';

$isLoggato = currentUser();

// voci del menu principale: file => etichetta
$vociMenu = [
    'index.php'      => 'Home',
    'calendario.php' => 'Calendario',
    'prodotti.php'   => 'Prodotti',
    'contatti.php'   => 'Contatti',
];
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="icon" type="image/png" sizes="16x16" href="<?= BASE_URL ?>/favicons/apple-touch-icon-60x60.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL ?>/favicons/apple-touch-icon-72x72.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= BASE_URL ?>/favicons/apple-touch-icon-96x96.png">

    <link rel="stylesheet" href="<?= BASE_URL ?>/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <title>Gestionale società sportiva</title>
</head>

<body>
    <a class="sr-only" href="#contenuto">Vai al contenuto</a>

    <header class="site-header">
        <div class="wrap site-header__inner">
            <a class="logo" href="<?= BASE_URL ?>/index.php" aria-label="Olimpia Sport Club — Home">LOGO</a>

            <nav class="site-nav" id="site-nav" aria-label="Navigazione principale">
                <?php foreach ($vociMenu as $file => $etichetta): ?>
                    <a class="site-nav__link <?= $pagina === $file ? 'is-active' : '' ?>"
                        href="<?= BASE_URL ?>/<?= $file ?>"
                        <?= $pagina === $file ? 'aria-current="page"' : '' ?>><?= $etichetta ?></a>
                <?php endforeach; ?>
            </nav>

            <div class="site-header__actions">
                <?php if ($isLoggato): ?>
                    <a class="button button--accent button--sm" href="<?= BASE_URL ?>/dashboard.php">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        <span>Ciao, <?= htmlspecialchars($isLoggato['name']) ?></span>
                    </a>
                <?php else: ?>
                    <a class="button button--accent button--sm" href="<?= BASE_URL ?>/login.php">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <span>Area riservata</span>
                    </a>
                <?php endif; ?>

                <button class="nav-toggle" type="button" aria-controls="site-nav" aria-expanded="false" aria-label="Apri il menu">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </header>

    <main id="contenuto">