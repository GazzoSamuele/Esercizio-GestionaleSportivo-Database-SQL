<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';

require_once __DIR__ . '/../DB/classes/Impegni.php';

// dico al pc che sono l'amministratore
requireQuotaValida();
// mi dice chi sono, se admin o client
$user = currentUser();

   // filtro per tipo (vuoto = tutte)
    $tipo = trim($_GET['tipo'] ?? '');
    if($tipo !== ''){
        $impegni = Impegni::findByTipo($tipo); 
    } else {
        $impegni = Impegni::findAllImpegni();
    }

$eventi  = [];
foreach ($impegni as $imp) {
    $eventi[] = [
        'title' => $imp['titolo'],
        'start' => $imp['data'] . 'T' . $imp['ora'],  // data con orario
    ];
}

?>
    <?php 
    
     $titoloPagina = 'Impegni'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bacheca della società</h1>
            <p>Visualizza ogni impegno imminente o futuro</p>
        </header>

        <div class="dash-chart-card">
            <h3>Calendario impegni</h3>
            <!-- ---- filtro per il tipo di impegno ---- -->
            <section class="dash-panel">
                <div class="dash-panel-head">
                    <div class="filter">
                        <a href="mostraImpegni.php" class="tab <?= $tipo === '' ? 'active' : '' ?>">Tutti Gli Impegni</a>
                        <a href="?tipo=allenamento" class="tab <?= $tipo === 'allenamento' ? 'active' : '' ?>">Allenamento</a>
                        <a href="?tipo=partita" class="tab <?= $tipo === 'partita' ? 'active' : '' ?>">Partite</a>
                        <a href="?tipo=coppa" class="tab <?= $tipo === 'coppa' ? 'active' : '' ?>">Coppa</a>
                        <a href="?tipo=riunione" class="tab <?= $tipo === 'riunione' ? 'active' : '' ?>">Riunione</a>
                        <a href="?tipo=altro" class="tab <?= $tipo === 'altro' ? 'active' : '' ?>">Altro</a>
                    </div>
                </div>
            </section>
            <div id="calendar"></div>
        </div>

    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const el = document.getElementById('calendar');
      const calendar = new FullCalendar.Calendar(el, {
          initialView: 'dayGridMonth',
          events: <?= json_encode($eventi) ?>
      });
      calendar.render();
  });
</script>
</body>
</html>
