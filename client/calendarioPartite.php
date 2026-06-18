<?php

//  PRENDO i dati che mi servono
require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Calendar.php';

requireLogin();
$user = currentUser();                  // utente loggato (id, name, role)

// $partite = Calendar::findAllPartite();  // tutte le partite


// FILTRO PER CATEGORIA

// categoria scelta (vuota = tutte)
$categoria = trim($_GET['categoria'] ?? '');

// se c'è una categoria → filtro; altrimenti prendo tutte
if ($categoria !== '') {
    $partite = Calendar::findByCategoria($categoria);
} else {
    $partite = Calendar::findAllPartite();
}

$iniziale = strtoupper(substr($user['name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/style.css">
    <title>Calendario — Gestionale Società Sportiva</title>
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
            <a href="/Gestionale-Hockey/dashboard.php" class="dash-link">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>
            <a href="/Gestionale-Hockey/calendario.php" class="dash-link active">
                <i class="fas fa-calendar-days"></i><span>Calendario</span>
            </a>
            <a href="/Gestionale-Hockey/prodotti.php" class="dash-link">
                <i class="fas fa-bag-shopping"></i><span>Prodotti</span>
            </a>
            <?php if($user['role'] === 'admin'): ?>
            <div class="sidebar-divider hide-on-collapse"></div>
                <!--
                <a href="admin.php" class="sidebar-link">
                    <i class="fas fa-user-shield"></i>
                    <span class="hide-on-collapse">Admin Panel</span>
                </a>-->
                <a href="/Gestionale-Hockey/admin/users.php" class="dash-link">
                    <i class="fas fa-user-shield"></i>
                    <span class="hide-on-collapse">Manage Users</span>
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

        <!-- ---- calendario partite ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-clock-rotate-left"></i> Calendario partite</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter">
                    <a href="calendarioPartite.php" class="tab <?= $categoria === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?categoria=<?= urlencode($cat) ?>"
                        class="tab <?= $categoria === $cat ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Casa</th>
                        <th>Risultato</th>
                        <th>Ospite</th>
                        <th>Data</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($partite as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['squadra_casa']) ?></td>
                            <td><?= (int) $p['gol_casa'] ?> - <?= (int) $p['gol_ospite'] ?></td>
                            <td><?= htmlspecialchars($p['squadra_ospite']) ?></td>
                            <td><?= date('d M Y', strtotime($p['data'])) ?></td>
                            <td><?= htmlspecialchars($p['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($partite)): ?>
                        <tr><td colspan="5">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</div>

</body>
</html>
