<?php

//  PRENDO i dati che mi servono
require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Calendar.php';

requireQuotaValida();
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
    <?php 
    
    $titoloPagina = 'Calendario Partite'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

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
