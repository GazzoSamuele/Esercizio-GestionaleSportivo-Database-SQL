<?php 
    $pagina = basename($_SERVER['PHP_SELF']); 

    require_once __DIR__ . '/DB/helpers/auth.php';

    $isLoggato = currentUser();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/style.css">
    <title>Gestionale Club Sportivo</title>
    
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-gradient fixed-top">
            <div class="container-fluid">

            <!-- hamburger: appare solo sotto lg, apre l'offcanvas -->
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- offcanvas: inline su desktop (≥lg), a scomparsa su mobile -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link <?= $pagina === 'index.php' ? 'active' : '' ?>" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link <?= $pagina === 'calendario.php' ? 'active' : '' ?>" href="calendario.php">Calendario</a></li>
                        <li class="nav-item"><a class="nav-link <?= $pagina === 'prodotti.php' ? 'active' : '' ?>" href="prodotti.php">Prodotti</a></li>
                        <li class="nav-item"><a class="nav-link <?= $pagina === 'contatti.php' ? 'active' : '' ?>" href="contatti.php">Contatti</a></li>
                    <?php if($isLoggato): ?>
                        <li class="nav-item"><a class="nav-link-login" href="dashboard.php"><?= htmlspecialchars($isLoggato['name']) ?></a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link-login <?= $pagina === 'login.php' ? 'active' : '' ?>" href="login.php">Area riservata</a></li>
                    <?php endif; ?>
                </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
