<?php

    $pagina        = basename($_SERVER['PHP_SELF']);
    $titoloPagina  = $titoloPagina ?? 'Dashboard';
    $iniziale      = strtoupper(substr($user['name'] ?? '', 0, 1));
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <link rel="stylesheet" href="/Gestionale-Hockey/assets/css/style-dashboard.css">
    <title><?= htmlspecialchars($titoloPagina) ?> — Gestionale Società Sportiva</title>
</head>
<body>
<div class="dash">
    <aside class="dash-sidebar">
        <div class="dash-brand">
            <span>Gestionale Società Sportiva</span>
        </div>

        <nav class="dash-nav">
            <a href="/Gestionale-Hockey/dashboard.php" class="dash-link <?= $pagina === 'dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>
            <hr>

            <?php if($user['role'] === 'client'): ?>
            <a href="/Gestionale-Hockey/client/calendarioPartite.php" class="dash-link <?= $pagina === 'calendarioPartite.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-days"></i><span>Calendario</span>
            </a>
            <a href="/Gestionale-Hockey/client/ordiniEffettuati.php" class="dash-link <?= $pagina === 'ordiniEffettuati.php' ? 'active' : '' ?>">
                <i class="fas fa-receipt"></i><span>Ordini</span>
            </a>
            <a href="/Gestionale-Hockey/client/mostraNewsAndComunicazioni.php" class="dash-link <?= $pagina === 'mostraNewsAndComunicazioni.php' ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i><span>News &amp; Comunicazioni</span>
            </a>
            <a href="/Gestionale-Hockey/client/mostraImpegni.php" class="dash-link <?= $pagina === 'mostraImpegni.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-day"></i><span>Mostra Impegni</span>
            </a>
            <?php endif; ?>

            <?php if($user['role'] === 'admin'): ?>
            <a href="/Gestionale-Hockey/admin/users.php" class="dash-link <?= $pagina === 'users.php' ? 'active' : '' ?>">
                <i class="fas fa-user-shield"></i><span>Utenti Registrati</span>
            </a>
            <a href="/Gestionale-Hockey/admin/ordiniProdottiRicevuti.php" class="dash-link <?= $pagina === 'ordiniProdottiRicevuti.php' ? 'active' : '' ?>">
                <i class="fas fa-receipt"></i><span>Ordini Effettuati</span>
            </a>
            <a href="/Gestionale-Hockey/admin/gestioneNewsAndComunicazioni.php" class="dash-link <?= $pagina === 'gestioneNewsAndComunicazioni.php' ? 'active' : '' ?>">
                <i class="fas fa-newspaper"></i><span>News &amp; Comunicazioni</span>
            </a>
            <a href="/Gestionale-Hockey/admin/gestioneImpegni.php" class="dash-link <?= $pagina === 'gestioneImpegni.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-day"></i><span>Gestione Impegni</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="dash-user">
            <div class="dash-avatar"><?= htmlspecialchars($iniziale) ?></div>
            <div class="dash-user-info">
                <strong><?= htmlspecialchars($user['name']) ?></strong>
                <small><?= htmlspecialchars($user['role']) ?></small>
            </div>
        </div>
        <a href="/Gestionale-Hockey/logout.php" class="dash-logout">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </aside>