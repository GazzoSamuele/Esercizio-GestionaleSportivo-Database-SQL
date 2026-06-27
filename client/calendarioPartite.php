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
$partite = $categoria !== '' ? Calendar::findByCategoria($categoria) : Calendar::findAllPartite();

$categoria2 = trim($_GET['categoria2'] ?? '');

$base2 = $categoria2 !== '' ? Calendar::findByCategoria($categoria2) : Calendar::findAllPartite();

$iniziale = strtoupper(substr($user['name'], 0, 1));

$ultimePartite = array_slice(array_reverse($base2), 0, 5);
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
        <!-- <div class="dash-stats">
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
        </div> -->

        <!-- ---- calendario tutte le partite ---- -->
    <div class="alg-form-results">
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-clock-rotate-left"></i> Calendario di tutte le partite</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?categoria2=<?= urlencode($categoria2) ?>" class="tab <?= $categoria === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?categoria=<?= urlencode($cat) ?>&categoria2=<?= urlencode($categoria2) ?>"
                        class="tab <?= $categoria === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
                            <td class="text-align-center"><?= htmlspecialchars($p['squadra_casa']) ?></td>
                            <td class="text-align-center"><?= (int) $p['gol_casa'] ?> - <?= (int) $p['gol_ospite'] ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($p['squadra_ospite']) ?></td>
                            <td class="text-align-center"><?= date('d M Y', strtotime($p['data'])) ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($p['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($partite)): ?>
                        <tr><td colspan="5">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- ---- calendario ultime partite di campionato ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-clock-rotate-left"></i> Calendario ultime partite di campionato</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?categoria=<?= urlencode($categoria) ?>" class="tab <?= $categoria2 === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?categoria=<?= urlencode($categoria) ?>&categoria2=<?= urlencode($cat) ?>"
                        class="tab <?= $categoria2 === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
                    <?php foreach($ultimePartite as $ulp): ?>
                        <tr>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_casa']) ?></td>
                            <td class="text-align-center"><?= (int) $ulp['gol_casa'] ?> - <?= (int) $ulp['gol_ospite'] ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_ospite']) ?></td>
                            <td class="text-align-center"><?= date('d M Y', strtotime($ulp['data'])) ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($ultimePartite)): ?>
                        <tr><td colspan="5">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- ---- calendario ultime partite di coppa ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-clock-rotate-left"></i> Calendario ultime partite di coppa</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?categoria=<?= urlencode($categoria) ?>" class="tab <?= $categoria2 === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?categoria=<?= urlencode($categoria) ?>&categoria2=<?= urlencode($cat) ?>"
                        class="tab <?= $categoria2 === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
                    <?php foreach($ultimePartite as $ulp): ?>
                        <tr>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_casa']) ?></td>
                            <td class="text-align-center"><?= (int) $ulp['gol_casa'] ?> - <?= (int) $ulp['gol_ospite'] ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_ospite']) ?></td>
                            <td class="text-align-center"><?= date('d M Y', strtotime($ulp['data'])) ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($ultimePartite)): ?>
                        <tr><td colspan="5">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>



    </div>
    </main>
</div>

</body>
</html>
