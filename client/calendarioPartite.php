<?php

//  PRENDO i dati che mi servono
require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Calendar.php';

requireQuotaValida();
$user = currentUser();                  // utente loggato (id, name, role)

// $partite = Calendar::findAllPartite();  // tutte le partite

// FILTRO PER CATEGORIA

// categoria scelta (vuota = tutte)
$cat1 = trim($_GET['cat1'] ?? '');
$cat2 = trim($_GET['cat2'] ?? '');
$cat3 = trim($_GET['cat3'] ?? '');
// se c'è una categoria → filtro; altrimenti prendo tutte
$partite = $cat1 !== '' ? Calendar::findByCategoria($cat1) : Calendar::findAllPartite();

$ultimeCampionato = Calendar::findUltimeCampionato($cat2, 5);

$ultimeCoppa = Calendar::findUltimeCoppa($cat3, 5);

$iniziale = strtoupper(substr($user['name'], 0, 1));
?>
    <?php 
    
    $titoloPagina = 'Calendario Partite'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
                <h1>Calendario di tutte le partite</h1>
                <p>Rappresentazione di tutti gli impegni in base alla categoria</p>
        </header>

        <!-- ---- calendario tutte le partite ---- -->
    <div class="alg-form-results">
        <section class="dash-panel width-30">
            <div class="dash-panel-head">
                <h2>Tutte le partite</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?cat2=<?= urlencode($cat2) ?>&cat3=<?= urlencode($cat3) ?>" class="tab <?= $cat1 === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?cat1=<?= urlencode($cat) ?>&cat2=<?= urlencode($cat2) ?>&cat3=<?= urlencode($cat3) ?>"
                        class="tab <?= $cat1 === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
        <section class="dash-panel width-30">
            <div class="dash-panel-head">
                <h2>Partite di campionato</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?cat1=<?= urlencode($cat1) ?>&cat3=<?= urlencode($cat3) ?>" class="tab <?= $cat2 === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?cat1=<?= urlencode($cat1) ?>&cat2=<?= urlencode($cat) ?>&cat3=<?= urlencode($cat3) ?>"
                        class="tab <?= $cat2 === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
                    <?php foreach($ultimeCampionato as $ulp): ?>
                        <tr>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_casa']) ?></td>
                            <td class="text-align-center"><?= (int) $ulp['gol_casa'] ?> - <?= (int) $ulp['gol_ospite'] ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_ospite']) ?></td>
                            <td class="text-align-center"><?= date('d M Y', strtotime($ulp['data'])) ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($ultimeCampionato)): ?>
                        <tr><td colspan="5">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- ---- calendario ultime partite di coppa ---- -->
        <section class="dash-panel width-30">
            <div class="dash-panel-head">
                <h2>Partite di coppa</h2>
            </div>
            <!-- FILTRO PER SCEGLIERE LA CATEGORIA PREFERITA -->
                <?php $categorie = ['Pulcini','Giovanile',
                      'Under 19','Under 21','Terza Categoria',
                      'Prima Squadra']; ?>

                <div class="filter margin-left">
                    <a href="?cat1=<?= urlencode($cat1) ?>&cat2=<?= urlencode($cat2) ?>" class="tab <?= $cat3 === '' ? 'active' : '' ?>">Tutte</a>
                    <?php foreach($categorie as $cat): ?>
                        <a href="?cat1=<?= urlencode($cat1) ?>&cat2=<?= urlencode($cat2) ?>&cat3=<?= urlencode($cat) ?>"
                        class="tab <?= $cat3 === $cat ? 'active' : '' ?>"><?= htmlspecialchars($cat) ?></a>
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
                    <?php foreach($ultimeCoppa as $ulp): ?>
                        <tr>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_casa']) ?></td>
                            <td class="text-align-center"><?= (int) $ulp['gol_casa'] ?> - <?= (int) $ulp['gol_ospite'] ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['squadra_ospite']) ?></td>
                            <td class="text-align-center"><?= date('d M Y', strtotime($ulp['data'])) ?></td>
                            <td class="text-align-center"><?= htmlspecialchars($ulp['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($ultimeCoppa)): ?>
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
