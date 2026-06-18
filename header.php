<?php $pagina = basename($_SERVER['PHP_SELF']); ?>

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
        <nav class="navbar navbar-expand-lg navbar-dark navbar-gradient">
        <div class="container">
            <a class="navbar-brand logo" href="index.php"></a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav2">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav2">
                <ul class="navbar-nav ms-auto">
                    <a class="nav-link <?= $pagina === 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                    <a class="nav-link <?= $pagina === 'calendario.php' ? 'active' : '' ?>" href="calendario.php">Calendario</a>
                    <a class="nav-link <?= $pagina === 'prodotti.php' ? 'active' : '' ?>" href="prodotti.php">Prodotti</a>
                    <a class="nav-link <?= $pagina === 'contatti.php' ? 'active' : '' ?>" href="contatti.php">Contatti</a>
                    <a class="nav-link-login <?= $pagina === 'login.php' ? 'active' : '' ?>" href="login.php">Login</a>
                </ul>
            </div>
        </div>
        </nav>
    </header>
