<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';

require_once __DIR__ . '/../DB/classes/News.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

$errors = [];
$success = '';

//GESTIONE FORM

// FUNZIONE CHE CREA UNA NUOVA NEWS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

    verificaCsrf();

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path = $_POST['image_path'] ?? '';
    $tipo = $_POST['tipo'] ?? 'notizia';
    $data = $_POST['data'] ?? date('Y-m-d');

    // validazione degli input

    //titolo
    if ($title === '') {
        $errors[] = "Il titolo è obbligatorio";
    }

    //descrizione
    if ($description === '') {
        $errors[] = "La descrizione è obbligatoria";
    }

    //tipo
    if (!in_array($tipo, ['notizia', 'comunicazione'], true)) {
        $errors[] = 'Tipo non valido';
    }

    // se non ho l'array di errors popolato,
    // procedo con la creazione di una nuova news

    if (empty($errors)) {
        // così facendo richiamo i parametri che mi servono
        News::createNews($title, $description, $data, $tipo, $image_path);

        $success = "News creata!";
        // una volta creato l'utente svuoto la post
        $_POST = [];
    }
}


// FUNZIONE CHE MODIFICA LA NEWS

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {

    verificaCsrf();

    // mettimo zero(in fondo) perchè è un int
    $id = (int) ($_POST['news_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image_path = $_POST['image_path'] ?? '';
    $tipo = $_POST['tipo'] ?? 'notizia';
    $data = $_POST['data'] ?? date('Y-m-d');

    if ($title === '') {
        $errors[] = "Il titolo è obbligatorio";
    }

    if ($description === '') {
        $errors[] = "La descrizione è obbligatoria";
    }

    if (!in_array($tipo, ['notizia', 'comunicazione'], true)) {
        $errors[] = 'Tipo non valido';
    }

    // se non ho l'array di errors popolato,
    // procedo con la creazione di un nuovo utente

    if (empty($errors)) {
        // così facendo richiamo i parametri che mi servono
        News::updateNews($id, $title, $description, $data, $tipo, $image_path);

        $success = "News aggiornata!";
        // una volta creato l'utente svuoto la post
        $_POST = [];
    }
}


// FUNZIONE CHE ELIMINA LA NEWS

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {

    verificaCsrf();

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['news_id'] ?? 0);

    if (News::delete($targetID)) {
        $success = "News eliminata!";
    } else {
        $errors[] = "Eliminazione news fallita!";
    }
}

$news = News::findAllNews();

$ultimeNews = array_slice(array_reverse($news), 0, 5);

?>
<?php

$titoloPagina = 'Gestione News e Comunicazioni';

include __DIR__ . '/headerDashboard.php';
?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / News e comunicazioni</span>
        <h1>News e comunicazioni</h1>
        <p>Pubblica notizie sul sito e comunicazioni interne per i soci.</p>
    </div>
</header>

<?php mostraEsito($errors, $success); ?>

<div class="dash-grid-form">
    <!-- ---- form nuova pubblicazione ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Nuova pubblicazione</h2>
        </div>
        <form class="panel__body" action="" method="post">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

            <label class="field" for="title">Titolo
                <input type="text" class="input" id="title" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
            </label>
            <label class="field" for="description">Descrizione
                <textarea class="input" id="description" name="description" rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </label>
            <label class="field" for="url_img">URL immagine
                <input type="url" class="input" id="url_img" name="image_path" placeholder="https://" value="<?= htmlspecialchars($_POST['image_path'] ?? '') ?>" required>
            </label>
            <label class="field" for="tipo">Tipo
                <select name="tipo" id="tipo" class="input">
                    <option value="notizia">Notizia (sito pubblico)</option>
                    <option value="comunicazione">Comunicazione (solo soci)</option>
                </select>
            </label>
            <button class="button button--ink button--block" type="submit">Pubblica</button>
        </form>
    </section>

    <!-- ---- ultime pubblicazioni ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Ultime pubblicazioni</h2>
        </div>
        <ul class="item-list">
            <?php foreach ($ultimeNews as $nw): ?>
                <li>
                    <div class="item-list__row">
                        <span class="item-list__title"><?= htmlspecialchars($nw['title']) ?></span>
                        <?= pillNews($nw['tipo']) ?>
                    </div>
                    <p class="item-list__text"><?= dataBreve($nw['data']) ?> · <?= htmlspecialchars($nw['description']) ?></p>
                </li>
            <?php endforeach; ?>
            <?php if (empty($ultimeNews)): ?>
                <li class="item-list__empty">Nessuna pubblicazione.</li>
            <?php endif; ?>
        </ul>
    </section>
</div>

<!-- ---- tutte le pubblicazioni ---- -->
<section class="panel">
    <div class="panel__head">
        <h2>Tutte le pubblicazioni</h2>
        <small><?= count($news) ?> totali</small>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col">Data</th>
                    <th scope="col">Tipo</th>
                    <th scope="col" class="num">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news as $nw): ?>
                    <tr>
                        <td>#<?= (int) $nw['id'] ?></td>
                        <td>
                            <span class="with-thumb"><?= miniatura($nw['image_path'] ?? null, 'fa-newspaper') ?><span class="strong"><?= htmlspecialchars($nw['title']) ?></span></span>
                        </td>
                        <td><span class="clip" title="<?= htmlspecialchars($nw['description']) ?>"><?= htmlspecialchars($nw['description']) ?></span></td>
                        <td style="white-space: nowrap"><?= dataBreve($nw['data']) ?></td>
                        <td><?= pillNews($nw['tipo']) ?></td>
                        <td class="num">
                            <div class="actions">
                                <button type="button" class="icon-btn" title="Modifica" aria-label="Modifica <?= htmlspecialchars($nw['title']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEditNews<?= (int) $nw['id'] ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="icon-btn icon-btn--danger" title="Elimina" aria-label="Elimina <?= htmlspecialchars($nw['title']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalDeleteNews<?= (int) $nw['id'] ?>">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($news)): ?>
                    <tr>
                        <td colspan="6" class="center">Nessuna news registrata.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- modali: una coppia per ogni pubblicazione -->
<?php foreach ($news as $nw): ?>
    <?php include __DIR__ . '/modali/NewsEditModal.php'; ?>
    <?php include __DIR__ . '/modali/NewsDeleteModal.php'; ?>
<?php endforeach; ?>

<?php include __DIR__ . '/footerDashboard.php'; ?>
