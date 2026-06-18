<?php

//  ZONA 1 (in alto) — PRENDO i dati
require_once __DIR__ . '/DB/helpers/auth.php';
require_once __DIR__ . '/DB/classes/Calendar.php';

requireLogin();

$user = currentUser();                  // utente loggato (id, name, role)
$partite = Calendar::findAllPartite();  // tutte le partite

// iniziale per l'avatar tondo della sidebar
$iniziale = strtoupper(substr($user['name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/style.css">
    <title>Dashboard — Gestionale Società Sportiva</title>
</head>
<body>

<div class="dash">

    <!-- ============ SIDEBAR ============ -->
    <aside class="dash-sidebar">
        <div class="dash-brand">
            <i class="fas fa-hockey-puck"></i>
            <span>Gestionale Società Sportiva</span>
        </div>

         <nav class="dash-nav">
            <a href="/Gestionale-Hockey/dashboard.php" class="dash-link active">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>
            <hr>
            <!-- VERSIONE DASHBOARD CLIENT -->
            <?php if($user['role'] === 'client'): ?>
            <a href="/Gestionale-Hockey/client/calendarioPartite.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Calendario</span>
            </a>

            <a href="/Gestionale-Hockey/client/ordiniEffettuati.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Ordini</span>
            </a>

            <!-- <a href="/Gestionale-Hockey/client/news.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>News</span>
            </a> -->
        
            <?php endif; ?>

            <!-- SE L'UTENTE è ADMIN, MI MOSTRI QUESTO -->
            <?php if($user['role'] === 'admin'): ?>
            <a href="/Gestionale-Hockey/admin/users.php" class="dash-link">
                <i class="fas fa-user-shield"></i><span>Utenti Registrati</span>
            </a>
            <a href="/Gestionale-Hockey/admin/ordiniProdottiRicevuti.php" class="dash-link">
                <i class="fas fa-user-shield"></i><span>Ordini Effettuati</span>
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

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bentornato, <?= htmlspecialchars($user['name']) ?>!</h1>
        </header>

        <!-- ---- stat cards ---- -->
        <div class="dash-stats">
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-calendar-check"></i></span>
                <div>
                    <b><?= count($partite) ?></b>
                    <small>Partite in calendario</small>
                </div>
            </div>
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-id-badge"></i></span>
                <div>
                    <b><?= htmlspecialchars(ucfirst($user['role'])) ?></b>
                    <small>Tipo account</small>
                </div>
            </div>
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-hashtag"></i></span>
                <div>
                    <b>#<?= (int) $user['id'] ?></b>
                    <small>User ID</small>
                </div>
            </div>
        </div>
    </main>

</div>

</body>
</html>
