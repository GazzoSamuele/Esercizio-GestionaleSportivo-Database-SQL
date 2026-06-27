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
            if ($riga['status'] === 'In attesa') {
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

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bentornato, <?= htmlspecialchars($user['name']) ?>!</h1>
        </header>

        <!-- ---- stat cards ---- -->
        <?php if ($user['role'] === 'admin'): ?>
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
        <?php else: ?>
        <div class="dash-stats">
            <!-- scadenza quota (verde = ok, giallo = in scadenza) -->
            <div class="dash-stat <?= ($giorniAllaScadenza !== null && $giorniAllaScadenza <= 30) ? 'quota-warn' : 'quota-ok' ?>">
                    <span class="dash-stat-icon">
                        <i class="fas fa-id-card"></i>
                    </span>
                <div>
                    <h3>Tra quanto scade la quota societaria?</h3>
                    <b><?= $giorniAllaScadenza !== null ? (int) $giorniAllaScadenza . ' giorni' : '—' ?></b>
                    <small>Alla scadenza della quota</small>
                </div>
            </div>
            <!-- ordini effettuati -->
            <div class="dash-stat dash-stat-prodotti">
                <span class="dash-stat-icon"><i class="fas fa-receipt"></i></span>
                <div>
                    <h3>Totale ordini effettuati</h3>
                    <b><?= (int) $numOrdini ?></b>
                    <small>Ordini effettuati</small>
                </div>
            </div>
            <!-- prossimo impegno -->
            <div class="dash-stat dash-stat-impegni">
                <span class="dash-stat-icon"><i class="fas fa-calendar-day"></i></span>
                <div>
                    <h3>Quand'è la prossima partita?</h3>
                    <b><?= $prossimoImpegno ? date('d M', strtotime($prossimoImpegno['data'])) : '—' ?></b>
                    <small><?= $prossimoImpegno ? htmlspecialchars($prossimoImpegno['titolo']) : 'Nessun impegno in programma' ?></small>
                </div>
            </div>
        </div>
        <?php endif; ?>

    <?php if($user['role'] === 'admin'): ?>

        <div class="dash-charts">
        <!-- diagramma a barre per le categorie delle partite -->
        <div class="dash-chart-card" style="height:320px;">
            <h3>Partite per categoria</h3>
            <canvas id="chartCategorie"></canvas>
        </div>

        <!-------------------------------------------------->
        <!-------------------------------------------------->
        <!-------------------------------------------------->

        <!-- istogramma per il totale degli incassi del mese -->
        <div class="dash-chart-card" style="height:320px;">
            <h3>Incassi per mese</h3>
            <canvas id="chartIncassi"></canvas>
        </div>

        <!-------------------------------------------------->
        <!-------------------------------------------------->
        <!-------------------------------------------------->

        <!-- diagramma a torta per le i prodotti più venduti -->
        <div class="dash-chart-card" style="height:320px;">
            <h3>Prodotti più venuduti</h3>
            <canvas id="chartProdotti"></canvas>
        </div>

        <!-------------------------------------------------->
        <!-------------------------------------------------->
        <!-------------------------------------------------->

        <!-- diagramma a barre per le classifica delle scquadre -->
        <div class="dash-chart-card" style="height:320px;">
            <h3>Classifica delle squadre</h3>
            <canvas id="chartCalendario"></canvas>
        </div>

        <!-------------------------------------------------->
        <!-------------------------------------------------->
        <!-------------------------------------------------->

        <!-- diagramma a lina per rappresenatare il totale degli iscritti -->
        <div class="dash-chart-card" style="height:320px;">
            <h3>Totale iscritti</h3>
            <canvas id="chartIscritti"></canvas>
        </div>
        </div><!-- /dash-charts -->

        <div class="dash-stats">
            <!-- card che rappresenta il numero di richieste informazioni che sono arrivate -->
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-envelope"></i></span>
                <div>
                    <b><?= (int) $numRichieste ?></b>
                    <small>Richieste informazioni</small>
                </div>
            </div>

            <!-- card che rappresenta il numero di ordini in sospeso che sono arrivati -->
            <div class="dash-stat">
                <span class="dash-stat-icon"><i class="fas fa-hourglass-half"></i></span>
                <div>
                    <b><?= (int) $inSospeso ?></b>
                    <a class="link" href="admin/ordiniProdottiRicevuti.php">Ordini in sospeso</a>
                </div>
            </div>
        </div>

        <div class="all-card-quote">
            <h2>Tutte le quote </h2>
            <!-- card che rappresenta il numero delle quote scadute -->
            <div class="dash-stat">
                    <span class="dash-stat-icon"><i class="fas fa-envelope"></i></span>
                <div>
                    <b><?= (int) $quoteScadute ?></b>
                    <p>Quote Scadute</p>
                </div>
            </div>
            <!-- card che rappresenta il numero delle quote valide -->
            <div class="dash-stat">
                    <span class="dash-stat-icon"><i class="fas fa-envelope"></i></span>
                <div>
                    <b><?= (int) $quoteValide ?></b>
                    <p>Quote Correnti</p>
                </div>
            </div>
            <!-- card che rappresenta il numero delle quote in scadenza -->
            <div class="dash-stat">
                    <span class="dash-stat-icon"><i class="fas fa-envelope"></i></span>
                <div>
                    <b><?= (int) $quoteInScadenza ?></b>
                    <p>Quote In Scadenza</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if($user['role'] === 'client'): ?>

        <div class="dash-cards-grid">
        <!-- Rappresentazione delle ultime comunicazioni dalla società per il giocatore -->
        <div class="dash-news-card">
            <h3>Ultime comunicazioni</h3>
                <?php foreach ($comunicazioni as $c): ?>
                    <div class="news-item">
                        <strong><?= htmlspecialchars($c['title']) ?></strong>
                        <small><?= htmlspecialchars($c['data']) ?></small>
                        <p><?= htmlspecialchars($c['description']) ?></p>
                    </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Rappresentazione delle degli ultimi ordini effettuati dal giocatore -->
        <div class="dash-news-card">
            <h3>Ultimi Prodotti Acquistati</h3>
                <?php foreach ($mieiOrdini as $o): ?>
                    <div class="news-item">
                        <strong><?= htmlspecialchars($o['prodotto']) ?></strong>
                        <small><?= htmlspecialchars($o['status']) ?></small>
                            <?php if ($o['pronto_ritiro']): ?>
                                <span class="badge-ritiro">Pronto al ritiro al club ✅</span>
                            <?php else: ?>
                                <span class="badge-attesa">In preparazione…</span>
                            <?php endif; ?>
                    </div>
            <?php endforeach; ?>
        </div>

        <!-- Rappresentazione delle degli impegni settimanali -->
        <div class="dash-news-card">
            <h3>Prossimi Impegni Settimanali</h3>
                <?php foreach ($prossimiImpegni as $imp): ?>
                    <div class="news-item">
                        <strong><?= htmlspecialchars($imp['titolo']) ?></strong>
                        <small><?= htmlspecialchars($imp['tipo']) ?></small>
                        <p><?= htmlspecialchars($imp['data']) ?> — <?= htmlspecialchars($imp['ora']) ?></p>
                    </div>
            <?php endforeach; ?>
        </div>
        </div><!-- /dash-cards-grid -->

       <?php endif; ?>
    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<?php if($user['role'] === 'admin'): ?>
    
<!-- diagramma a barre per la categoria delle partite -->
<script>
  new Chart(document.getElementById('chartCategorie'), {
      type: 'bar',
      data: {
          labels: <?= json_encode($catLabels) ?>,
          datasets: [{
              label: 'Partite',
              data: <?= json_encode($catValori) ?>
          }]
      },
      options: { maintainAspectRatio: false }
  });
</script>


<!-- grafico che rappresenta gli incassi totali del mese -->
<script>
  new Chart(document.getElementById('chartIncassi'), {
      type: 'line',
      data: {
          labels: <?= json_encode($incassiLabels) ?>,
          datasets: [{ label: 'Incassi €', data: <?= json_encode($incassiValori) ?> }]
      },
      options: { maintainAspectRatio: false }
  });
</script>


<!-- grafico a torta che rappresenta i prodotti totali venduti -->
<script>
  new Chart(document.getElementById('chartProdotti'), {
      type: 'doughnut',
      data: {
          labels: <?= json_encode($prodottiLabels) ?>,
          datasets: [{ 
            label: 'Prodotti venduti', 
            data: <?= json_encode($prodottiValori) ?>,
            backgroundColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
            ],
        }],
      },
      options: { maintainAspectRatio: false }
  });
</script>


<!-- grafico a barre che rappresenta la classifica delle squadre -->
<script>
  new Chart(document.getElementById('chartCalendario'), {
      type: 'bar',
      data: {
          labels: <?= json_encode($classificaLabels) ?>,
          datasets: [{ label: 'Punti', data: <?= json_encode($classificaValori) ?> }]
      },
      options: { maintainAspectRatio: false }
  });
</script>


<!-- grafico che rappresenta gli iscritti totali -->
<script>
  new Chart(document.getElementById('chartIscritti'), {
      type: 'line',
      data: {
          labels: <?= json_encode($iscrittiLabels) ?>,
          datasets: [{ label: 'Numero Totale Iscritti', data: <?= json_encode($iscrittiValori) ?> }]
      },
      options: { maintainAspectRatio: false }
  });
</script>
<?php endif; ?>
</body>
</html>
