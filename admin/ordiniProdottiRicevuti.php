<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';
//richiamo le funzioni degli users
require_once __DIR__ . '/../DB/classes/Products.php';

require_once __DIR__ . '/../DB/classes/Purchases.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

$errors = [];
$success = '';

//GESTIONE FORM

// FUNZIONE CHE CREA IL PRODOTTO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

    verificaCsrf();

    $name        = trim($_POST['name'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $prezzo      = trim($_POST['prezzo'] ?? '');
    $isActive    = (int) ($_POST['isActive'] ?? 1);
    $immagine    = trim($_POST['immagine'] ?? '');

    // validazione degli input

    //nome
    if ($name === '') {
        $errors[] = "Il nome è obbligatorio";
    }

    // descrizione
    if ($descrizione === '') {
        $errors[] = "La descrizione è obbligatoria";
    }

    // immagine
    if ($immagine === '') {
        $errors[] = "L'immagine è obbligatoria";
    }

    //prezzo
    if ($prezzo === '' || !is_numeric($prezzo)) {
        $errors[] = "Prezzo non valido";
    }

    //disponibilità
    if (!in_array($isActive, [0, 1], true)) {
        $errors[] = 'Invalid disponibility';
    }

    // se non ho l'array di errors popolato,
    // procedo con la creazione di un nuovo utente

    if (empty($errors)) {
        // così facendo richiamo i parametri che mi servono
        $newId = Products::create($name, $descrizione, $prezzo, $isActive, $immagine);

        if ($newId === null) {
            $errors[] = 'This product is already registered';
        } else {
            $success = "Prodotto creato!";

            // una volta creato l'utente svuoto la post
            $_POST = [];
        }
    }
}

// FUNZIONE CHE MODIFICA UN PRODOTTO

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {

    verificaCsrf();

    // mettimo zero(in fondo) perchè è un int
    $name        = trim($_POST['name'] ?? '');
    $descrizione = trim($_POST['descrizione'] ?? '');
    $prezzo      = trim($_POST['prezzo'] ?? '');
    $isActive    = (int) ($_POST['isActive'] ?? 1);
    $immagine    = trim($_POST['immagine'] ?? '');

    // validazione degli input

    //nome
    if ($name === '') {
        $errors[] = "Il nome è obbligatorio";
    }

    // descrizione
    if ($descrizione === '') {
        $errors[] = "La descrizione è obbligatoria";
    }

    // immagine
    if ($immagine === '') {
        $errors[] = "L'immagine è obbligatoria";
    }

    //prezzo
    if ($prezzo === '' || !is_numeric($prezzo)) {
        $errors[] = "Prezzo non valido";
    }

    //disponibilità
    if (!in_array($isActive, [0, 1], true)) {
        $errors[] = 'Invalid disponibility';
    }
    // se non ho l'array di errors popolato,
    // procedo con la creazione di un nuovo utente

    if (empty($errors)) {
        $id = (int) ($_POST['product_id'] ?? 0);
        // così facendo richiamo i parametri che mi servono
        if (Products::updateProduct($id, $name, $descrizione, $prezzo, $isActive, $immagine)) {
            $success = "Prodotto aggiornato!";
        } else {
            $errors[] = 'You have not change anything';
        };
    }
}

// FUNZIONE CHE ELIMINA UN PRODOTTO

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {

    verificaCsrf();

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['product_id'] ?? 0);

    // mi dice se che se l'id è uguale all'user(cioè l'user loggato in quel momento)
    if (Products::delete($targetID)) {
        $success = "Prodotto eliminato!";
    } else {
        $errors[] = "Eliminazione prodotto fallita!";
    }
}

$products = Products::findAllProducts();

$ordini = Purchases::findAll();

?>

<?php

$titoloPagina = 'Gestione Prodotti Ricevuti';

include __DIR__ . '/headerDashboard.php';
?>

<?php
$ordiniInAttesa = count(array_filter($ordini, fn($o) => in_array($o['status'], ['pending', 'In attesa'], true)));
$nonDisponibili = count(array_filter($products, fn($p) => (int) $p['is_active'] === 0));
?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / Prodotti e ordini</span>
        <h1>Prodotti e ordini</h1>
        <p>Gestisci il catalogo e gli ordini arrivati dai soci.</p>
    </div>
</header>

<?php mostraEsito($errors, $success); ?>

<div class="stat-strip">
    <div class="stat"><span class="stat__value"><?= $ordiniInAttesa ?></span><span class="stat__label">Ordini in attesa di ritiro</span></div>
    <div class="stat"><span class="stat__value"><?= count($ordini) ?></span><span class="stat__label">Ordini totali</span></div>
    <div class="stat"><span class="stat__value"><?= count($products) ?></span><span class="stat__label">Prodotti a catalogo</span></div>
    <div class="stat"><span class="stat__value"><?= $nonDisponibili ?></span><span class="stat__label">Non disponibili</span></div>
</div>

<div class="dash-grid-form">
    <!-- ---- form nuovo prodotto ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Nuovo prodotto</h2>
        </div>
        <form class="panel__body" action="" method="post">
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

            <label class="field" for="name">Nome
                <input type="text" class="input" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </label>
            <label class="field" for="url_img">URL immagine
                <input type="url" class="input" id="url_img" name="immagine" placeholder="https://" value="<?= htmlspecialchars($_POST['immagine'] ?? '') ?>" required>
            </label>
            <label class="field" for="descrizione">Descrizione
                <textarea class="input" id="descrizione" name="descrizione" rows="3" required><?= htmlspecialchars($_POST['descrizione'] ?? '') ?></textarea>
            </label>
            <div class="field-row">
                <label class="field" for="price">Prezzo (€)
                    <input type="text" class="input" id="price" name="prezzo" inputmode="decimal" placeholder="0.00" value="<?= htmlspecialchars($_POST['prezzo'] ?? '') ?>" required>
                </label>
                <label class="field" for="isActive">Disponibilità
                    <select name="isActive" id="isActive" class="input">
                        <option value="1">Disponibile</option>
                        <option value="0">Non disponibile</option>
                    </select>
                </label>
            </div>
            <button class="button button--ink button--block" type="submit">Crea prodotto</button>
        </form>
    </section>

    <!-- ---- ordini ricevuti dai soci ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Ordini ricevuti</h2>
            <small><?= count($ordini) ?> ordini</small>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Socio</th>
                        <th scope="col">Prodotto</th>
                        <th scope="col" class="num">Importo</th>
                        <th scope="col">Stato</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ordini as $ord): ?>
                        <tr>
                            <td>#<?= (int) $ord['id'] ?></td>
                            <td class="strong"><?= htmlspecialchars($ord['cliente']) ?></td>
                            <td>
                                <span class="with-thumb"><?= miniatura($ord['immagine'] ?? null, 'fa-bag-shopping') ?><span class="strong"><?= htmlspecialchars($ord['prodotto']) ?></span></span>
                            </td>
                            <td class="num em">€ <?= number_format((float) $ord['prezzo_pagato'], 2, ',', '.') ?></td>
                            <td><?= pillStato($ord['status']) ?></td>
                            <td style="white-space: nowrap"><?= dataBreve($ord['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($ordini)): ?>
                        <tr>
                            <td colspan="6" class="center">Nessun ordine ricevuto.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- ---- catalogo prodotti ---- -->
<section class="panel">
    <div class="panel__head">
        <h2>Catalogo prodotti</h2>
        <small><?= count($products) ?> prodotti</small>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Prodotto</th>
                    <th scope="col">Descrizione</th>
                    <th scope="col" class="num">Prezzo</th>
                    <th scope="col">Disponibilità</th>
                    <th scope="col">Modificato</th>
                    <th scope="col" class="num">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>#<?= (int) $p['id'] ?></td>
                        <td>
                            <span class="with-thumb"><?= miniatura($p['image_path'] ?? null, 'fa-bag-shopping') ?><span class="strong"><?= htmlspecialchars($p['name']) ?></span></span>
                        </td>
                        <td><span class="clip" title="<?= htmlspecialchars($p['description']) ?>"><?= htmlspecialchars($p['description']) ?></span></td>
                        <td class="num em">€ <?= number_format((float) $p['price'], 2, ',', '.') ?></td>
                        <td>
                            <?= (int) $p['is_active'] === 1
                                ? '<span class="pill pill--ok">Disponibile</span>'
                                : '<span class="pill pill--neutral">Non disponibile</span>' ?>
                        </td>
                        <td style="white-space: nowrap"><?= dataBreve($p['created_at']) ?></td>
                        <td class="num">
                            <div class="actions">
                                <button type="button" class="icon-btn" title="Modifica prodotto" aria-label="Modifica <?= htmlspecialchars($p['name']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalEditProduct<?= (int) $p['id'] ?>">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="icon-btn icon-btn--danger" title="Elimina prodotto" aria-label="Elimina <?= htmlspecialchars($p['name']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#modalDeleteProduct<?= (int) $p['id'] ?>">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="7" class="center">Nessun prodotto registrato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- modali: una coppia per ogni prodotto -->
<?php foreach ($products as $p): ?>
    <?php include __DIR__ . '/modali/ProductEditModal.php'; ?>
    <?php include __DIR__ . '/modali/ProductDeleteModal.php'; ?>
<?php endforeach; ?>

<?php include __DIR__ . '/footerDashboard.php'; ?>
