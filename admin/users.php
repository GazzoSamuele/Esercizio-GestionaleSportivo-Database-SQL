<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';
//richiamo le funzioni degli users
require_once __DIR__ . '/../DB/classes/riferimentoUtenti.php';
//richiamo le funzioni degli InfoRequest
require_once __DIR__ . '/../DB/classes/InfoRequest.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

$errors = [];
$success = '';

//GESTIONE FORM

// FUNZIONE CHE CREA L'UTENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

    verificaCsrf();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'client';

    // validazione degli input

    //nome
    if ($name === '') {
        $errors[] = "Il nome è obbligatorio";
    }

    //email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email non valida";
    }

    //password
    if (strlen($password) < 8) {
        $errors[] = 'Password must be 8 char!';
    }

    //role
    if (!in_array($role, ['admin', 'client'], true)) {
        $errors[] = 'Invalid Role';
    }

    // se non ho l'array di errors popolato,
    // procedo con la creazione di un nuovo utente

    if (empty($errors)) {
        // così facendo richiamo i parametri che mi servono
        $newId = User::create($name, $email, $password, $role);

        if ($newId === null) {
            $errors[] = 'This email is already registered';
        } else {
            $success = "Utente creato!";

            // una volta creato l'utente svuoto la post
            $_POST = [];
        }
    }
}

// FUNZIONE CHE MODIFICA L'UTENTE

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {

    verificaCsrf();

    // mettimo zero(in fondo) perchè è un int
    $id = (int) ($_POST['user_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? 'client';

    if ($name === '') {
        $errors[] = "Il nome è obbligatorio";
    }

    //email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email non valida";
    }

    //role
    if (!in_array($role, ['admin', 'client'], true)) {
        $errors[] = 'Invalid Role';
    }

    // se non ho l'array di errors popolato,
    // procedo con la creazione di un nuovo utente

    if (empty($errors)) {
        // così facendo richiamo i parametri che mi servono
        if (User::updateUser($id, $name, $email, $role)) {
            $success = "Utente aggiornato!";
        } else {
            $errors[] = 'Email già usata da un altro utente';
        };
    }
}

// FUNZIONE CHE ELIMINA L'UTENTE

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {

    verificaCsrf();

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['user_id'] ?? 0);

    // mi dice se che se l'id è uguale all'user(cioè l'user loggato in quel momento)
    if ($targetID === (int) $user['id']) {
        $errors[] = "Non puoi eliminare te stesso!";
    } elseif (User::delete($targetID)) {

        $success = "Utente eliminato!";
    } else {
        $errors[] = "Eliminazione utente fallita!";
    }
}

// FUNZIONE CHE CAMBIA IL RUOLO (toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_role') {

    verificaCsrf();
    $id      = (int) ($_POST['user_id'] ?? 0);
    $newRole = $_POST['new_role'] ?? '';

    if ($id === (int) $user['id']) {
        $errors[] = "Non puoi cambiare il ruolo a te stesso";
    } elseif (User::updateRole($id, $newRole)) {
        $success = "Ruolo aggiornato!";
    } else {
        $errors[] = "Ruolo non valido";
    }
}

$request = InfoRequest::findAllRequest();

$users = User::findAllUsers();

?>
<?php

$titoloPagina = 'Gestione Utenti';

include __DIR__ . '/headerDashboard.php';
?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / Utenti</span>
        <h1>Gestione utenti</h1>
        <p>Crea nuovi account e gestisci quelli esistenti.</p>
    </div>
</header>

<?php mostraEsito($errors, $success); ?>

<?php
$numAdmin = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
?>
<div class="stat-strip">
    <div class="stat"><span class="stat__value"><?= count($users) ?></span><span class="stat__label">Utenti registrati</span></div>
    <div class="stat"><span class="stat__value"><?= count($users) - $numAdmin ?></span><span class="stat__label">Soci</span></div>
    <div class="stat"><span class="stat__value"><?= $numAdmin ?></span><span class="stat__label">Amministratori</span></div>
    <div class="stat"><span class="stat__value"><?= count($request) ?></span><span class="stat__label">Richieste informazioni</span></div>
</div>

<div class="dash-grid-form">
    <!-- ---- form nuovo utente ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Nuovo utente</h2>
        </div>
        <form class="panel__body" action="" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
            <input type="hidden" name="action" value="create">

            <label class="field" for="name">Nome
                <input type="text" class="input" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </label>
            <label class="field" for="email">Email
                <input type="email" class="input" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </label>
            <label class="field" for="password">Password
                <input type="password" class="input" id="password" name="password" minlength="8" required>
                <span class="field__hint">Almeno 8 caratteri</span>
            </label>
            <label class="field" for="role">Ruolo
                <select name="role" id="role" class="input">
                    <option value="client">Socio</option>
                    <option value="admin">Amministratore</option>
                </select>
            </label>
            <button class="button button--ink button--block" type="submit">Crea utente</button>
        </form>
    </section>

    <!-- ---- richieste informazioni ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Richieste informazioni</h2>
            <small><?= count($request) ?> totali</small>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Contatti</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Messaggio</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($request as $req): ?>
                        <tr>
                            <td>#<?= (int) $req['id'] ?></td>
                            <td class="strong"><?= htmlspecialchars($req['name']) ?></td>
                            <td>
                                <a href="mailto:<?= htmlspecialchars($req['email']) ?>"><?= htmlspecialchars($req['email']) ?></a><br>
                                <span class="muted"><?= htmlspecialchars($req['phone']) ?></span>
                            </td>
                            <td><?= htmlspecialchars($req['categoria']) ?></td>
                            <td><span class="clip" title="<?= htmlspecialchars($req['messaggio']) ?>"><?= htmlspecialchars($req['messaggio']) ?></span></td>
                            <td style="white-space: nowrap"><?= dataBreve($req['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($request)): ?>
                        <tr>
                            <td colspan="6" class="center">Nessuna nuova richiesta di informazioni.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- ---- tabella utenti ---- -->
<section class="panel">
    <div class="panel__head">
        <h2>Utenti registrati</h2>
        <small><?= count($users) ?> account</small>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Email</th>
                    <th scope="col">Ruolo</th>
                    <th scope="col">Iscritto il</th>
                    <th scope="col" class="num">Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <?php $sonoIo = (int) $u['id'] === (int) $user['id']; ?>
                    <tr>
                        <td>#<?= (int) $u['id'] ?></td>
                        <td class="strong">
                            <?= htmlspecialchars($u['name']) ?>
                            <?php if ($sonoIo): ?><span class="pill pill--wait">Tu</span><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <?= $u['role'] === 'admin'
                                ? '<span class="pill pill--dark">Amministratore</span>'
                                : '<span class="pill pill--ok">Socio</span>' ?>
                        </td>
                        <td style="white-space: nowrap"><?= dataBreve($u['created_at']) ?></td>
                        <td class="num">
                            <?php if ($sonoIo): ?>
                                <span class="muted">Account corrente</span>
                            <?php else: ?>
                                <div class="actions">
                                    <button type="button" class="icon-btn" title="Modifica utente" aria-label="Modifica <?= htmlspecialchars($u['name']) ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalEdit<?= (int) $u['id'] ?>">
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                    </button>

                                    <!-- cambio ruolo (toggle) -->
                                    <form action="" method="post">
                                        <input type="hidden" name="action" value="toggle_role">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
                                        <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                        <input type="hidden" name="new_role" value="<?= $u['role'] === 'admin' ? 'client' : 'admin' ?>">
                                        <button type="submit" class="icon-btn"
                                            title="Rendi <?= $u['role'] === 'admin' ? 'socio' : 'amministratore' ?>"
                                            aria-label="Cambia ruolo di <?= htmlspecialchars($u['name']) ?>"
                                            onclick="return confirm('Cambiare il ruolo di <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>?');">
                                            <i class="fa-solid fa-right-left" aria-hidden="true"></i>
                                        </button>
                                    </form>

                                    <button type="button" class="icon-btn icon-btn--danger" title="Elimina utente" aria-label="Elimina <?= htmlspecialchars($u['name']) ?>"
                                        data-bs-toggle="modal" data-bs-target="#modalDelete<?= (int) $u['id'] ?>">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="center">Nessun utente registrato.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- modali: stanno fuori dalla tabella, una coppia per ogni utente -->
<?php foreach ($users as $u): ?>
    <?php if ((int) $u['id'] !== (int) $user['id']): ?>
        <?php include __DIR__ . '/modali/UserEditModal.php'; ?>
        <?php include __DIR__ . '/modali/UserDeleteModal.php'; ?>
    <?php endif; ?>
<?php endforeach; ?>

<?php include __DIR__ . '/footerDashboard.php'; ?>
