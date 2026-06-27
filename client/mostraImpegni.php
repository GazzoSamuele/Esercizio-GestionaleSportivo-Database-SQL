<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';

require_once __DIR__ . '/../DB/classes/Impegni.php';

require_once __DIR__ . '/../DB/classes/AppuntamentiPersonali.php';

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

    $colori = [
        'allenamento' => '#668041',
        'partita'     => '#bd4d63',
        'coppa'       => '#e0a800',
        'riunione'    => '#3b82f6',
        'altro'       => '#6b7280',
    ];

    foreach ($impegni as $imp) {
        $colore = $colori[$imp['tipo']] ?? '#6b7280';
        $eventi[] = [
            'title' => $imp['titolo'],
            'start' => $imp['data'] . 'T' . $imp['ora'],  
            'backgroundColor' => $colore,
            'borderColor'     => $colore,
        ];
    }

    $errors = [];
    $success = '';

    //GESTIONE FORM

    // FUNZIONE CHE CREA L'IMPEGNO
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $titolo = trim($_POST['titolo'] ?? '');
        $data = $_POST['data'] ?? '';
        $ora = $_POST['ora'] ?? '';
        $appunti = trim($_POST['appunti'] ?? '');
        $tipo =$_POST['tipo'] ?? 'allenamento extra';

        //titolo
        if($titolo === ''){
            $errors[] = "Il nome è obbligatorio";
        }

        //data
        if($data === ''){
            $errors[] = "La data è obbligatoria";
        }

        //ora
        if($ora === ''){
            $errors[] = "L'ora' è obbligatoria";
        }

        //role
        if(!in_array($tipo, ['fisioterapista','nutrizionista','palestra','allenamento extra','altro'], true)){
            $errors[] = 'Tipo di impegno non selezionato';
        }

        if(empty($errors)) {
            if(AppuntamentiPersonali::create($user['id'], $titolo, $data, $ora, $appunti,$tipo)) {
                $success = "Impegno creato!";
                $_POST = [];
            } else {
                $errors[] = 'Errore nel salvataggio';
            }
        }
    }

    $appuntamenti = AppuntamentiPersonali::findByUserId($user['id']);

    foreach ($appuntamenti as $app) {
        $eventi[] = [
            'title'           => $app['titolo'],
            'start'           => $app['data'] . 'T' . $app['ora'],
            'backgroundColor' => '#7c3aed',
            'borderColor'     => '#7c3aed',
        ];
}
?>
    <?php 
    
     $titoloPagina = 'Impegni'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Calendario impegni</h1>
            <p>Visualizza ogni impegno imminente o futuro</p>
        </header>

        <!-- ---- form nuovo utente ---- -->
    <div class="alg-cal-impegni">
        <div class="form-nuovi-impegni height-fit-content">
            <section class="dash-tab-impegni dash-formcard height-fit-content">
                <div class="dash-panel-head">
                    <h2><i class="fas fa-user-plus"></i>Nuovo Appuntamento</h2>
                </div>
                <div class="dash-formbody">
                    <form action="" method="post">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-3">
                            <label for="name">Titolo</label>
                            <input type="text" class="form-control" id="titolo" name="titolo" required>
                        </div>
                        <div class="mb-3">
                            <label for="data">Data</label>
                            <input type="date" class="form-control" id="data" name="data" required>
                        </div>
                        <div class="mb-3">
                            <label for="ora">Ora</label>
                            <input type="time" id="ora" name="ora" min="08:00" max="18:00" class="form-control" required>
                        </div>
                            <div class="mb-3">
                            <label for="tipo">Tipo di impegno</label>
                            <select name="tipo" id="tipo" class="form-select">
                                <option value="fisioterapista">Fisioterapista</option>
                                <option value="nutrizionista">Nutrizionista</option>
                                <option value="palestra">Palestra</option>
                                <option value="allenamento extra">Allenamento extra</option>
                                <option value="altro">Altro</option>
                            </select>
                        </div>
                        <button class="btn btn-warning" type="submit">Crea Appuntamento</button>
                    </form>
                </div>
            </section>

            <section class="tab-impegni-prossimi height-fit-content">
                <div class="dash-panel-head">
                    <h2><i class="fas fa-calendar-check"></i> Prossimi appuntamenti</h2>
                        </div>
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>Titolo</th>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th>Ora</th>
                                    </tr>
                                </thead>
                            <tbody>
                            <?php foreach($appuntamenti as $app): ?>
                                <tr>
                                    <td><?= htmlspecialchars($app['titolo']) ?></td>
                                    <td><?= htmlspecialchars($app['tipo']) ?></td>
                                    <td><?= date('d M Y', strtotime($app['data'])) ?></td>
                                    <td><?= htmlspecialchars($app['ora']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if(empty($appuntamenti)): ?>
                            <tr><td colspan="4">Nessun appuntamento personale.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
        
            <!-- calendario interattivo -->
            <div id="calendar"></div>
        </div>
    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
      const tipoAttuale = new URLSearchParams(window.location.search).get('tipo') ?? '';
      
      const el = document.getElementById('calendar');

      const calendar = new FullCalendar.Calendar(el, {
            initialView: 'dayGridMonth',
            height: 1000,
            width: "100%",
            headerToolbar: {
                start: 'prev,next today',
                center: 'title',
                end: 'tuttiBtn,allenamentoBtn,partiteBtn,coppaBtn,riunioneBtn,altroBtn'
            },
            customButtons: {
                tuttiBtn:        { text: 'Tutti',        click: () => location.href = 'mostraImpegni.php' },
                allenamentoBtn:  { text: 'Allenamento',  click: () => location.href = '?tipo=allenamento' },
                partiteBtn:      { text: 'Partite',      click: () => location.href = '?tipo=partita' },
                coppaBtn:        { text: 'Coppa',        click: () => location.href = '?tipo=coppa' },
                riunioneBtn:     { text: 'Riunione',     click: () => location.href = '?tipo=riunione' },
                altroBtn:        { text: 'Altro',        click: () => location.href = '?tipo=altro' },
            },
        events: <?= json_encode($eventi) ?>
    });
    calendar.render();

    const mappa = { '': 'tuttiBtn', 'allenamento': 'allenamentoBtn', 'partita': 'partiteBtn', 'coppa': 'coppaBtn', 'riunione': 'riunioneBtn', 'altro': 'altroBtn' };
    const nomeAttivo = mappa[tipoAttuale] ?? 'tuttiBtn';
    document.querySelector(`.fc-${nomeAttivo}-button`)?.classList.add('fc-button-active');  
        });
</script>
</body>
</html>
