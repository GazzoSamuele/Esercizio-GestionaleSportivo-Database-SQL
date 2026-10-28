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

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / Calendario partite</span>
        <h1>Calendario partite</h1>
        <p>Tutte le partite del club, filtrabili per categoria.</p>
    </div>
    
</header>

<?php $categorie = ['Pulcini', 'Giovanile', 'Under 19', 'Under 21', 'Terza Categoria', 'Prima Squadra']; ?>

<!-- ---- tutte le partite ---- -->
<section class="panel">
    <div class="panel__head">
        <h2>Tutte le partite</h2>
        <small><?= count($partite) ?> partite</small>
    </div>
    <div class="panel__toolbar">
        <nav class="segmented" aria-label="Filtra per categoria">
                <a href="?cat2=<?= urlencode($cat2) ?>&cat3=<?= urlencode($cat3) ?>" class="segmented__btn <?= $cat1 === '' ? 'is-active' : '' ?>" <?= $cat1 === '' ? 'aria-current="true"' : '' ?>>Tutte</a>
                <?php foreach ($categorie as $cat): ?>
                    <a href="?cat1=<?= urlencode($cat) ?>&cat2=<?= urlencode($cat2) ?>&cat3=<?= urlencode($cat3) ?>" class="segmented__btn <?= $cat1 === $cat ? 'is-active' : '' ?>" <?= $cat1 === $cat ? 'aria-current="true"' : '' ?>><?= htmlspecialchars($cat) ?></a>
                <?php endforeach; ?>
            </nav>
    </div>
    <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col" class="num">Casa</th>
                        <th scope="col" class="center">Risultato</th>
                        <th scope="col">Ospite</th>
                        <th scope="col">Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partite as $p): ?>
                        <tr>
                            <td style="white-space: nowrap"><?= dataBreve($p['data']) ?></td>
                            <td class="strong num"><?= htmlspecialchars($p['squadra_casa']) ?></td>
                            <td class="center"><span class="score"><?= (int) $p['gol_casa'] ?> – <?= (int) $p['gol_ospite'] ?></span></td>
                            <td class="strong"><?= htmlspecialchars($p['squadra_ospite']) ?></td>
                            <td><?= htmlspecialchars($p['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($partite)): ?>
                        <tr><td colspan="5" class="center">Nessuna partita per questa categoria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
</section>

<div class="dash-grid-2">
    <!-- ---- ultime di campionato ---- -->
    <section class="panel">
        <div class="panel__head"><h2>Ultime di campionato</h2></div>
        <div class="panel__toolbar">
            <nav class="segmented" aria-label="Filtra per categoria">
                <a href="?cat1=<?= urlencode($cat1) ?>&cat3=<?= urlencode($cat3) ?>" class="segmented__btn <?= $cat2 === '' ? 'is-active' : '' ?>" <?= $cat2 === '' ? 'aria-current="true"' : '' ?>>Tutte</a>
                <?php foreach ($categorie as $cat): ?>
                    <a href="?cat2=<?= urlencode($cat) ?>&cat1=<?= urlencode($cat1) ?>&cat3=<?= urlencode($cat3) ?>" class="segmented__btn <?= $cat2 === $cat ? 'is-active' : '' ?>" <?= $cat2 === $cat ? 'aria-current="true"' : '' ?>><?= htmlspecialchars($cat) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col" class="num">Casa</th>
                        <th scope="col" class="center">Risultato</th>
                        <th scope="col">Ospite</th>
                        <th scope="col">Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimeCampionato as $ulp): ?>
                        <tr>
                            <td style="white-space: nowrap"><?= dataBreve($ulp['data']) ?></td>
                            <td class="strong num"><?= htmlspecialchars($ulp['squadra_casa']) ?></td>
                            <td class="center"><span class="score"><?= (int) $ulp['gol_casa'] ?> – <?= (int) $ulp['gol_ospite'] ?></span></td>
                            <td class="strong"><?= htmlspecialchars($ulp['squadra_ospite']) ?></td>
                            <td><?= htmlspecialchars($ulp['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ultimeCampionato)): ?>
                        <tr><td colspan="5" class="center">Nessuna partita di campionato.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ---- ultime di coppa ---- -->
    <section class="panel">
        <div class="panel__head"><h2>Ultime di coppa</h2></div>
        <div class="panel__toolbar">
            <nav class="segmented" aria-label="Filtra per categoria">
                <a href="?cat1=<?= urlencode($cat1) ?>&cat2=<?= urlencode($cat2) ?>" class="segmented__btn <?= $cat3 === '' ? 'is-active' : '' ?>" <?= $cat3 === '' ? 'aria-current="true"' : '' ?>>Tutte</a>
                <?php foreach ($categorie as $cat): ?>
                    <a href="?cat3=<?= urlencode($cat) ?>&cat1=<?= urlencode($cat1) ?>&cat2=<?= urlencode($cat2) ?>" class="segmented__btn <?= $cat3 === $cat ? 'is-active' : '' ?>" <?= $cat3 === $cat ? 'aria-current="true"' : '' ?>><?= htmlspecialchars($cat) ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Data</th>
                        <th scope="col" class="num">Casa</th>
                        <th scope="col" class="center">Risultato</th>
                        <th scope="col">Ospite</th>
                        <th scope="col">Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimeCoppa as $ulc): ?>
                        <tr>
                            <td style="white-space: nowrap"><?= dataBreve($ulc['data']) ?></td>
                            <td class="strong num"><?= htmlspecialchars($ulc['squadra_casa']) ?></td>
                            <td class="center"><span class="score"><?= (int) $ulc['gol_casa'] ?> – <?= (int) $ulc['gol_ospite'] ?></span></td>
                            <td class="strong"><?= htmlspecialchars($ulc['squadra_ospite']) ?></td>
                            <td><?= htmlspecialchars($ulc['categoria']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ultimeCoppa)): ?>
                        <tr><td colspan="5" class="center">Nessuna partita di coppa.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../admin/footerDashboard.php'; ?>
