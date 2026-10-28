<?php

require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Impegni.php';

function validaImpegno($titolo, $descrizione, $tipo)
{
    $errors = [];

    if ($titolo === '') {
        $errors[] = "Il titolo è obbligatorio";
    }

    if ($descrizione === '') {
        $errors[] = "La descrizione è obbligatoria";
    }

    if (!in_array($tipo, ['allenamento', 'partita', 'coppa', 'riunione', 'altro'], true)) {
        $errors[] = 'Tipo non valido';
    }

    return $errors;
}

// dico al pc che sono l'amministratore
requireAdmin();

// mi dice chi sono, se admin o client
$user = currentUser();

$errors = [];
$messages = array(
    'creato' => 'Impegno Creato!',
    'modificato' => 'Impegno Modificato!',
    'eliminato' => 'Impegno Eliminato!',
);
$esito = $_GET['esito'] ?? '';
$success = $messages[$esito] ?? '';

//GESTIONE FORM NUOVO IMPEGNO 

// FUNZIONE CHE CREA UN NUOVO IMPEGNO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

    verificaCsrf();

    $titolo = trim($_POST['titolo'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');
    $ora = $_POST['ora'] ?? '';
    $luogo = $_POST['luogo'] ?? '';

    $errors = validaImpegno($titolo, $descrizione, $tipo);

    if (empty($errors)) {
        Impegni::createImpegno($titolo, $descrizione, $tipo, $data, $ora, $luogo);

        header('Location: gestioneImpegni.php?esito=creato');
        exit;
    }
}


// FUNZIONE CHE MODIFICA GLI IMPEGNI ESISTENTI

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {

    verificaCsrf();

    $id     = (int) ($_POST['impegno_id'] ?? 0);
    $titolo = trim($_POST['titolo'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $data = $_POST['data'] ?? date('Y-m-d');
    $ora = $_POST['ora'] ?? '';
    $luogo = $_POST['luogo'] ?? '';

    $errors = validaImpegno($titolo, $descrizione, $tipo);

    if (empty($errors)) {
        Impegni::updateImpegno($id, $titolo, $descrizione, $tipo, $data, $ora, $luogo);

        header('Location: gestioneImpegni.php?esito=modificato');
        exit;
    }
}


// FUNZIONE CHE ELIMINA L'IMPEGNO

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {

    verificaCsrf();

    $targetImpegno = (int) ($_POST['impegno_id'] ?? 0);

    if (Impegni::delete($targetImpegno)) {
        header('Location: gestioneImpegni.php?esito=eliminato');
        exit;
    } else {
        $errors[] = "Fallito tentativo di rimozione di un impegno!";
    }
}

$impegni = Impegni::findAllImpegni();

$lastImpegni = Impegni::findProssimiImpegni();

$ultimiImpegni = array_slice(array_reverse($lastImpegni), 0, 5);

?>
<?php

$titoloPagina = 'Gestione Impegni Sportivi';

include __DIR__ . '/headerDashboard.php';
?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / Impegni</span>
        <h1>Gestione impegni</h1>
        <p>Pianifica allenamenti, partite, coppe e riunioni della società.</p>
    </div>
</header>

<?php mostraEsito($errors, $success); ?>

<div class="dash-grid-form">
    <!-- ---- form nuovo impegno ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Nuovo impegno</h2>
        </div>
        <form class="panel__body" action="" method="post">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

            <label class="field" for="titolo">Titolo
                <input type="text" class="input" id="titolo" name="titolo" value="<?= htmlspecialchars($_POST['titolo'] ?? '') ?>" required>
            </label>
            <label class="field" for="descrizione">Descrizione
                <textarea class="input" id="descrizione" name="descrizione" rows="3" required><?= htmlspecialchars($_POST['descrizione'] ?? '') ?></textarea>
            </label>
            <label class="field" for="tipo">Tipo di impegno
                <select name="tipo" id="tipo" class="input">
                    <option value="allenamento">Allenamento</option>
                    <option value="partita">Partita</option>
                    <option value="coppa">Coppa</option>
                    <option value="riunione">Riunione</option>
                    <option value="altro">Altro</option>
                </select>
            </label>
            <div class="field-row">
                <label class="field" for="data">Data
                    <input type="date" class="input" id="data" name="data" required>
                </label>
                <label class="field" for="ora">Ora
                    <input type="time" class="input" id="ora" name="ora" min="08:00" max="18:00" required>
                </label>
            </div>
            <label class="field" for="luogo">Luogo
                <input type="text" class="input" id="luogo" name="luogo" value="<?= htmlspecialchars($_POST['luogo'] ?? '') ?>" required>
            </label>
            <button class="button button--ink button--block" type="submit">Crea impegno</button>
        </form>
    </section>

    <!-- ---- prossimi impegni ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Prossimi impegni</h2>
        </div>
        <ul class="item-list">
            <?php foreach ($ultimiImpegni as $imp): ?>
                <?php [$giorno, $mese] = explode(' ', dataBreve($imp['data'], false)); ?>
                <li class="with-chip">
                    <span class="date-chip"><b><?= $giorno ?></b><span><?= $mese ?></span></span>
                    <span>
                        <span class="item-list__title" style="display:block"><?= htmlspecialchars($imp['titolo']) ?></span>
                        <span class="muted"><?= htmlspecialchars(substr($imp['ora'], 0, 5)) ?> · <?= htmlspecialchars($imp['luogo']) ?></span>
                    </span>
                    <?= pillImpegno($imp['tipo']) ?>
                </li>
            <?php endforeach; ?>
            <?php if (empty($ultimiImpegni)): ?>
                <li class="item-list__empty">Nessun impegno in programma.</li>
            <?php endif; ?>
        </ul>
    </section>
</div>

<!-- ---- tutti gli impegni ---- -->
<section class="panel">
    <div class="panel__head">
        <h2>Tutti gli impegni</h2>
        <small><?= count($impegni) ?> totali</small>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Data</th>
                    <th scope="col">Ora</th>
                    <th scope="col">Luogo</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col" class="num">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($impegni as $imp): ?>
                    <tr>
                        <td>#<?= (int) $imp['id'] ?></td>
                        <td class="strong"><?= htmlspecialchars($imp['titolo']) ?></td>
                        <td><?= pillImpegno($imp['tipo']) ?></td>
                        <td style="white-space: nowrap"><?= dataBreve($imp['data']) ?></td>
                        <td><?= htmlspecialchars(substr($imp['ora'], 0, 5)) ?></td>
                        <td><?= htmlspecialchars($imp['luogo']) ?></td>
                        <td><span class="clip" title="<?= htmlspecialchars($imp['descrizione']) ?>"><?= htmlspecialchars($imp['descrizione']) ?></span></td>
                        <td class="num">
                            <div class="actions">
                                <button type="button" class="icon-btn" title="Modifica" aria-label="Modifica <?= htmlspecialchars($imp['titolo']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEditImpegni<?= (int) $imp['id'] ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="icon-btn icon-btn--danger" title="Elimina" aria-label="Elimina <?= htmlspecialchars($imp['titolo']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalDeleteImpegni<?= (int) $imp['id'] ?>">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($impegni)): ?>
                    <tr>
                        <td colspan="8" class="center">Nessun impegno registrato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- modali: una coppia per ogni impegno -->
<?php foreach ($impegni as $imp): ?>
    <?php include __DIR__ . '/modali/ImpegniEditModal.php'; ?>
    <?php include __DIR__ . '/modali/ImpegniDeleteModal.php'; ?>
<?php endforeach; ?>

<?php include __DIR__ . '/footerDashboard.php'; ?>
