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
if ($tipo !== '') {
    $impegni = Impegni::findByTipo($tipo);
} else {
    $impegni = Impegni::findAllImpegni();
}

$eventi  = [];

$colori = [
    'allenamento' => ['#668041', '#ffffff'],
    'partita'     => ['#24371c', '#ffffff'],
    'coppa'       => ['#cf9b4e', '#24371c'],
    'riunione'    => ['#d2d1bd', '#24371c'],
    'altro'       => ['#8a8f7a', '#ffffff'],
];

foreach ($impegni as $imp) {
    [$colore, $testo] = $colori[$imp['tipo']] ?? ['#8a8f7a', '#ffffff'];
    $eventi[] = [
        'title' => $imp['titolo'],
        'start' => $imp['data'] . 'T' . $imp['ora'],
        'backgroundColor' => $colore,
        'borderColor'     => $colore,
        'textColor'       => $testo,
    ];
}

$errors = [];
$success = '';

//GESTIONE FORM

// FUNZIONE CHE CREA L'IMPEGNO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

    verificaCsrf();

    $titolo = trim($_POST['titolo'] ?? '');
    $data = $_POST['data'] ?? '';
    $ora = $_POST['ora'] ?? '';
    $appunti = trim($_POST['appunti'] ?? '');
    $tipo = $_POST['tipo'] ?? 'allenamento extra';

    //titolo
    if ($titolo === '') {
        $errors[] = "Il nome è obbligatorio";
    }

    //data
    if ($data === '') {
        $errors[] = "La data è obbligatoria";
    }

    //ora
    if ($ora === '') {
        $errors[] = "L'ora' è obbligatoria";
    }

    //role
    if (!in_array($tipo, ['fisioterapista', 'nutrizionista', 'massaggiatore', 'palestra', 'allenamento extra', 'altro'], true)) {
        $errors[] = 'Tipo di impegno non selezionato';
    }

    if (empty($errors)) {
        if (AppuntamentiPersonali::create($user['id'], $titolo, $data, $ora, $appunti, $tipo)) {
            $success = "Impegno creato!";
            $_POST = [];
        } else {
            $errors[] = 'Errore nel salvataggio';
        }
    }
}

$prossimi = AppuntamentiPersonali::findProssimiByUserId($user['id']);

// gli appuntamenti personali hanno tutti lo stesso stile (bianco con bordo), così si distinguono da quelli della società
$coloriAppuntamenti = [];


$appuntamenti = AppuntamentiPersonali::findByUserId($user['id']);

foreach ($appuntamenti as $app) {
    $eventi[] = [
        'title'           => $app['titolo'],
        'start'           => $app['data'] . 'T' . $app['ora'],
        'backgroundColor' => '#ffffff',
        'borderColor'     => '#3d562a',
        'textColor'       => '#3d562a',
    ];
}
?>
<?php

$titoloPagina = 'Impegni';

include __DIR__ . '/../admin/headerDashboard.php'; ?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / I miei impegni</span>
        <h1>Calendario impegni</h1>
        <p>Gli impegni della società e i tuoi appuntamenti personali.</p>
    </div>
    
</header>

<?php mostraEsito($errors, $success); ?>

<?php
$filtriTipo = ['' => 'Tutti', 'allenamento' => 'Allenamento', 'partita' => 'Partite', 'coppa' => 'Coppa', 'riunione' => 'Riunione', 'altro' => 'Altro'];
$tipoAttivo = trim($_GET['tipo'] ?? '');
?>

<div class="dash-grid-form" style="grid-template-columns: 320px minmax(0, 1fr)">
    <div style="display: flex; flex-direction: column; gap: 20px; min-width: 0">
        <!-- ---- form nuovo appuntamento ---- -->
        <section class="panel">
            <div class="panel__head">
                <h2>Nuovo appuntamento</h2>
            </div>
            <form class="panel__body" action="" method="post">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

                <label class="field" for="titolo">Titolo
                    <input type="text" class="input" id="titolo" name="titolo" value="<?= htmlspecialchars($_POST['titolo'] ?? '') ?>" required>
                </label>
                <div class="field-row">
                    <label class="field" for="data">Data
                        <input type="date" class="input" id="data" name="data" required>
                    </label>
                    <label class="field" for="ora">Ora
                        <input type="time" class="input" id="ora" name="ora" min="08:00" max="18:00" required>
                    </label>
                </div>
                <label class="field" for="tipo">Tipo
                    <select name="tipo" id="tipo" class="input">
                        <option value="fisioterapista">Fisioterapista</option>
                        <option value="nutrizionista">Nutrizionista</option>
                        <option value="massaggiatore">Massaggiatore</option>
                        <option value="palestra">Palestra</option>
                        <option value="allenamento extra">Allenamento extra</option>
                        <option value="altro">Altro</option>
                    </select>
                </label>
                <label class="field" for="appunti">Appunti
                    <textarea class="input" id="appunti" name="appunti" rows="3"><?= htmlspecialchars($_POST['appunti'] ?? '') ?></textarea>
                </label>
                <button class="button button--ink button--block" type="submit">Aggiungi al calendario</button>
            </form>
        </section>

        <!-- ---- prossimi appuntamenti personali ---- -->
        <section class="panel">
            <div class="panel__head">
                <h2>Prossimi appuntamenti</h2>
            </div>
            <ul class="item-list">
                <?php foreach ($prossimi as $app): ?>
                    <?php [$giorno, $mese] = explode(' ', dataBreve($app['data'], false)); ?>
                    <li class="with-chip" style="grid-template-columns: 56px minmax(0, 1fr)">
                        <span class="date-chip"><b><?= $giorno ?></b><span><?= $mese ?></span></span>
                        <span>
                            <span class="item-list__title" style="display: block"><?= htmlspecialchars($app['titolo']) ?></span>
                            <span class="muted"><?= htmlspecialchars(substr($app['ora'], 0, 5)) ?> · <?= htmlspecialchars(ucfirst($app['tipo'])) ?></span>
                        </span>
                    </li>
                <?php endforeach; ?>
                <?php if (empty($prossimi)): ?>
                    <li class="item-list__empty">Nessun appuntamento personale.</li>
                <?php endif; ?>
            </ul>
        </section>
    </div>

    <!-- ---- calendario ---- -->
    <section class="panel">
        <div class="panel__head">
            <nav class="segmented" aria-label="Filtra per tipo di impegno">
                <?php foreach ($filtriTipo as $valore => $etichetta): ?>
                    <a href="<?= $valore === '' ? 'mostraImpegni.php' : '?tipo=' . urlencode($valore) ?>"
                        class="segmented__btn <?= $tipoAttivo === $valore ? 'is-active' : '' ?>"
                        <?= $tipoAttivo === $valore ? 'aria-current="true"' : '' ?>><?= $etichetta ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="legend" aria-label="Legenda colori">
                <span><i style="background: #668041"></i>Allenamento</span>
                <span><i style="background: #24371c"></i>Partita</span>
                <span><i style="background: #cf9b4e"></i>Coppa</span>
                <span><i style="background: #d2d1bd"></i>Riunione</span>
                <span><i style="background: #fff; box-shadow: inset 0 0 0 1px #3d562a"></i>Personale</span>
            </div>
        </div>
        <div class="calendar-wrap">
            <div id="calendar"></div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/it.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            locale: 'it',
            firstDay: 1,
            height: 'auto',
            buttonText: { today: 'Oggi', month: 'Mese', week: 'Settimana' },
            headerToolbar: { start: 'prev,next today', center: 'title', end: 'dayGridMonth,timeGridWeek' },
            eventDisplay: 'block',
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
            events: <?= json_encode($eventi) ?>
        });
        calendar.render();
    });
</script>

<?php include __DIR__ . '/../admin/footerDashboard.php'; ?>
