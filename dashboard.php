<?php

require_once __DIR__ . '/DB/helpers/auth.php';
require_once __DIR__ . '/DB/classes/Calendar.php';
require_once __DIR__ . '/DB/classes/riferimentoUtenti.php';
require_once __DIR__ . '/DB/classes/Purchases.php';
require_once __DIR__ . '/DB/classes/InfoRequest.php';
require_once __DIR__ . '/DB/classes/Impegni.php';

require_once __DIR__ . '/DB/classes/News.php';

requireQuotaValida();

$user = currentUser();                  // utente loggato (id, name, role)
$partite = Calendar::findAllPartite();  // tutte le partite

?>

    <?php if($user['role'] === 'admin'){ 

        // rappresentano tutte le chiamate da fare per avere i dati da inseririre nei grafici di chart.js
        $incassi      = Purchases::incassoPerMese();
        $ordiniStato  = Purchases::contaPerStato();
        $topProdotti  = Purchases::topProdotti();
        $iscritti     = User::iscrittiPerMese();
        $quote        = User::contaQuote();
        $classifica   = Calendar::bilancioRisultati();
        $perCategoria = Calendar::partitePerCategoria();
        $numRichieste = InfoRequest::countAll();

        // partite per categorie 
        $catLabels = array_column($perCategoria, 'categoria'); 
        $catValori = array_column($perCategoria, 'total');  

        // incassi per mese
        $incassiLabels = array_column($incassi, 'mese');
        $incassiValori = array_column($incassi, 'totale');

        // prodotti più venduti
        $prodottiLabels = array_column($topProdotti, 'name');
        $prodottiValori = array_column($topProdotti, 'total');

        // classifica delle scquadre
        $classificaLabels = array_column($classifica, 'squadra');
        $classificaValori = array_column($classifica, 'punti_totali');

        // rappresentazione degli iscritti per mese
        $iscrittiLabels = array_column($iscritti, 'mese');
        $iscrittiValori = array_column($iscritti, 'totale');

        // rappresentazione degli ordini in sospeso
        $inSospeso = 0;
        foreach ($ordiniStato as $riga) {
            if ($riga['status'] === 'pending') {
                $inSospeso = $riga['total'];
            }
        }
        // si creano 3 variabili perchè fetchALL mi restuisce un array singolo con tutti i dati
        $quoteScadute    = $quote[0]['conteggio_rifiutato'];
        $quoteValide     = $quote[0]['conteggio_approvati'];
        $quoteInScadenza = $quote[0]['conteggio_InScadenza'];
    }
?>

    <?php if ($user['role'] === 'client') {

        // prendo le comunicazioni
        $comunicazioni = News::findByTipo('comunicazione');
        // rappresenta le comunicazioni filtrate a massimo 3 visulizzate in pagina
        $comunicazioni = array_slice(array_reverse($comunicazioni), 0, 3);

        // prende tutti gli acquisti attraverso l'user selezionato
        $tuttiOrdini = Purchases::findByUser($user['id']);
        $numOrdini = count($tuttiOrdini);                                   // quanti ordini ha fatto
         // rappresenta solo alcuni degli acquisti fatti (non tutti)
        $mieiOrdini = array_slice(array_reverse($tuttiOrdini), 0, 4);

        $prossimiImpegni = Impegni::findProssimiImpegni();
        $prossimoImpegno = $prossimiImpegni[0] ?? null;                     // il primo impegno futuro

        // scadenza quota e giorni mancanti
        $quotaScadenza = User::quotaScadenza($user['id']);
        $giorniAllaScadenza = null;
        if ($quotaScadenza) {
            $oggi = new DateTime('today');
            $scad = new DateTime($quotaScadenza);
            $giorniAllaScadenza = (int) $oggi->diff($scad)->format('%r%a'); // giorni alla scadenza (con segno)
        }
    }
?>

<?php include __DIR__ . '/admin/headerDashboard.php'; ?>

<?php
$giorniSettimana = ['domenica', 'lunedì', 'martedì', 'mercoledì', 'giovedì', 'venerdì', 'sabato'];
$oggiTesto = ucfirst($giorniSettimana[(int) date('w')]) . ' ' . dataBreve(date('Y-m-d'));
?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb"><?= $oggiTesto ?></span>
        <h1>Bentornato, <?= htmlspecialchars($user['name']) ?></h1>
    </div>
    <div class="dash-head__actions">
        <?php if ($user['role'] === 'admin'): ?>
            <a class="button button--ink button--sm" href="<?= BASE_URL ?>/admin/gestioneNewsAndComunicazioni.php"><i class="fa-solid fa-plus" aria-hidden="true"></i> Nuova comunicazione</a>
        <?php else: ?>
            <a class="button button--line button--sm" href="<?= BASE_URL ?>/prodotti.php"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> Vai allo shop</a>
        <?php endif; ?>
    </div>
</header>

<?php if ($user['role'] === 'admin'): ?>

    <!-- ---- numeri principali ---- -->
    <div class="stat-strip">
        <div class="stat"><span class="stat__value"><?= count($partite) ?></span><span class="stat__label">Partite in calendario</span></div>
        <div class="stat"><span class="stat__value"><?= (int) $inSospeso ?></span><span class="stat__label"><a href="<?= BASE_URL ?>/admin/ordiniProdottiRicevuti.php">Ordini in attesa</a></span></div>
        <div class="stat"><span class="stat__value"><?= (int) $numRichieste ?></span><span class="stat__label"><a href="<?= BASE_URL ?>/admin/users.php">Richieste informazioni</a></span></div>
        <div class="stat"><span class="stat__value"><?= (int) $quoteValide ?></span><span class="stat__label">Quote valide</span></div>
    </div>

    <!-- ---- incassi + quote ---- -->
    <div class="dash-grid-side" style="grid-template-columns: minmax(0, 2fr) minmax(0, 1fr)">
        <section class="panel">
            <div class="panel__head">
                <h2>Incassi per mese</h2><small>€</small>
            </div>
            <div class="panel__body">
                <div class="chart-box"><canvas id="chartIncassi" aria-label="Grafico incassi per mese" role="img"></canvas></div>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2>Quote associative</h2>
                <small><?= (int) $quoteValide + (int) $quoteInScadenza + (int) $quoteScadute ?> soci</small>
            </div>
            <ul class="item-list">
                <li class="item-list__row"><span class="item-list__title">Valide</span><span class="pill pill--ok"><?= (int) $quoteValide ?></span></li>
                <li class="item-list__row"><span class="item-list__title">In scadenza</span><span class="pill pill--wait"><?= (int) $quoteInScadenza ?></span></li>
                <li class="item-list__row"><span class="item-list__title">Scadute</span><span class="pill pill--dark"><?= (int) $quoteScadute ?></span></li>
            </ul>
            <div class="panel__body" style="padding-top: 0">
                <a class="text-link" href="<?= BASE_URL ?>/admin/users.php">Gestisci utenti <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </section>
    </div>

    <!-- ---- altri grafici ---- -->
    <div class="dash-grid-2">
        <section class="panel">
            <div class="panel__head"><h2>Partite per categoria</h2></div>
            <div class="panel__body"><div class="chart-box"><canvas id="chartCategorie" aria-label="Grafico partite per categoria" role="img"></canvas></div></div>
        </section>
        <section class="panel">
            <div class="panel__head"><h2>Classifica squadre</h2><small>Punti</small></div>
            <div class="panel__body"><div class="chart-box"><canvas id="chartCalendario" aria-label="Grafico classifica squadre" role="img"></canvas></div></div>
        </section>
        <section class="panel">
            <div class="panel__head"><h2>Prodotti più venduti</h2></div>
            <div class="panel__body"><div class="chart-box"><canvas id="chartProdotti" aria-label="Grafico prodotti più venduti" role="img"></canvas></div></div>
        </section>
        <section class="panel">
            <div class="panel__head"><h2>Iscritti per mese</h2></div>
            <div class="panel__body"><div class="chart-box"><canvas id="chartIscritti" aria-label="Grafico iscritti per mese" role="img"></canvas></div></div>
        </section>
    </div>

    <script>
        // colori della palette per tutti i grafici
        Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
        Chart.defaults.color = '#5c6b4a';
        Chart.defaults.borderColor = '#eef0e6';
        Chart.defaults.plugins.legend.display = false;
        const PALETTE = ['#668041', '#cf9b4e', '#24371c', '#adb97c', '#a9721f', '#3d562a'];
        const base = { maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } } };

        new Chart(document.getElementById('chartIncassi'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($incassiLabels) ?>,
                datasets: [{ label: 'Incassi €', data: <?= json_encode($incassiValori) ?>, backgroundColor: '#668041', borderRadius: 4, maxBarThickness: 56 }]
            },
            options: base
        });

        new Chart(document.getElementById('chartCategorie'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($catLabels) ?>,
                datasets: [{ label: 'Partite', data: <?= json_encode($catValori) ?>, backgroundColor: '#668041', borderRadius: 4, maxBarThickness: 18 }]
            },
            options: { ...base, indexAxis: 'y', scales: { x: { beginAtZero: true, ticks: { precision: 0 } }, y: { grid: { display: false } } } }
        });

        new Chart(document.getElementById('chartCalendario'), {
            type: 'bar',
            data: {
                labels: <?= json_encode($classificaLabels) ?>,
                datasets: [{ label: 'Punti', data: <?= json_encode($classificaValori) ?>, backgroundColor: '#24371c', borderRadius: 4, maxBarThickness: 40 }]
            },
            options: base
        });

        new Chart(document.getElementById('chartProdotti'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($prodottiLabels) ?>,
                datasets: [{ label: 'Prodotti venduti', data: <?= json_encode($prodottiValori) ?>, backgroundColor: PALETTE, borderColor: '#fff', borderWidth: 2 }]
            },
            options: { maintainAspectRatio: false, cutout: '62%', plugins: { legend: { display: true, position: 'right' } } }
        });

        new Chart(document.getElementById('chartIscritti'), {
            type: 'line',
            data: {
                labels: <?= json_encode($iscrittiLabels) ?>,
                datasets: [{ label: 'Iscritti', data: <?= json_encode($iscrittiValori) ?>, borderColor: '#668041', backgroundColor: 'rgba(102,128,65,.12)', fill: true, tension: .3, pointRadius: 3 }]
            },
            options: base
        });
    </script>

<?php else: ?>

    <!-- ---- numeri principali del socio ---- -->
    <div class="dash-grid-3">
        <div class="kpi <?= ($giorniAllaScadenza !== null && $giorniAllaScadenza <= 30) ? 'kpi--warn' : '' ?>">
            <div class="kpi__body">
                <span class="kpi__label">Scadenza quota societaria</span>
                <span class="kpi__value"><?= $giorniAllaScadenza !== null ? (int) $giorniAllaScadenza . ' giorni' : '—' ?></span>
                <span class="kpi__note"><?= $quotaScadenza ? 'Valida fino al ' . dataBreve($quotaScadenza) : 'Nessuna scadenza registrata' ?></span>
            </div>
        </div>
        <div class="kpi">
            <div class="kpi__body">
                <span class="kpi__label">Ordini effettuati</span>
                <span class="kpi__value"><?= (int) $numOrdini ?></span>
                <span class="kpi__note"><a href="<?= BASE_URL ?>/client/ordiniEffettuati.php">Vedi i tuoi ordini</a></span>
            </div>
        </div>
        <div class="kpi">
            <div class="kpi__body">
                <span class="kpi__label">Prossimo impegno</span>
                <span class="kpi__value"><?= $prossimoImpegno ? dataBreve($prossimoImpegno['data'], false) : '—' ?></span>
                <span class="kpi__note"><?= $prossimoImpegno ? htmlspecialchars($prossimoImpegno['titolo']) : 'Nessun impegno in programma' ?></span>
            </div>
        </div>
    </div>

    <div class="dash-grid-3">
        <!-- ultime comunicazioni della società -->
        <section class="panel">
            <div class="panel__head">
                <h2>Ultime comunicazioni</h2>
                <a href="<?= BASE_URL ?>/client/mostraNewsAndComunicazioni.php">Tutte</a>
            </div>
            <ul class="item-list">
                <?php foreach ($comunicazioni as $c): ?>
                    <li>
                        <div class="item-list__row">
                            <span class="item-list__title"><?= htmlspecialchars($c['title']) ?></span>
                            <span class="item-list__meta"><?= dataBreve($c['data']) ?></span>
                        </div>
                        <p class="item-list__text"><?= htmlspecialchars($c['description']) ?></p>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($comunicazioni)): ?>
                    <li class="item-list__empty">Nessuna comunicazione.</li>
                <?php endif; ?>
            </ul>
        </section>

        <!-- ultimi acquisti -->
        <section class="panel">
            <div class="panel__head">
                <h2>Ultimi acquisti</h2>
                <a href="<?= BASE_URL ?>/client/ordiniEffettuati.php">Tutti</a>
            </div>
            <ul class="item-list">
                <?php foreach ($mieiOrdini as $o): ?>
                    <li class="item-list__row">
                        <span class="item-list__title"><?= htmlspecialchars($o['prodotto']) ?></span>
                        <?php if ($o['pronto_ritiro']): ?>
                            <span class="pill pill--ok">Pronto al ritiro</span>
                        <?php else: ?>
                            <span class="pill pill--wait">In preparazione</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($mieiOrdini)): ?>
                    <li class="item-list__empty">Nessun ordine effettuato.</li>
                <?php endif; ?>
            </ul>
        </section>

        <!-- prossimi impegni -->
        <section class="panel">
            <div class="panel__head">
                <h2>Prossimi impegni</h2>
                <a href="<?= BASE_URL ?>/client/mostraImpegni.php">Calendario</a>
            </div>
            <ul class="item-list">
                <?php foreach ($prossimiImpegni as $imp): ?>
                    <?php [$giorno, $mese] = explode(' ', dataBreve($imp['data'], false)); ?>
                    <li class="with-chip">
                        <span class="date-chip"><b><?= $giorno ?></b><span><?= $mese ?></span></span>
                        <span>
                            <span class="item-list__title" style="display:block"><?= htmlspecialchars($imp['titolo']) ?></span>
                            <span class="muted"><?= htmlspecialchars(substr($imp['ora'], 0, 5)) ?></span>
                        </span>
                        <?= pillImpegno($imp['tipo']) ?>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($prossimiImpegni)): ?>
                    <li class="item-list__empty">Nessun impegno in programma.</li>
                <?php endif; ?>
            </ul>
        </section>
    </div>

<?php endif; ?>

<?php include __DIR__ . '/admin/footerDashboard.php'; ?>
