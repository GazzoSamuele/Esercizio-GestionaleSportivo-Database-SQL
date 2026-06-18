<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';
//richiamo le funzioni degli users
require_once __DIR__ . '/../DB/classes/riferimentoUtenti.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

    $errors = [];
    $success = '';

    //GESTIONE FORM

    // FUNZIONE CHE CREA L'UTENTE
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role =$_POST['role'] ?? 'client';

        // validazione degli input

        //nome
        if($name === ''){
            $errors[] = "Name is required";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="email is a invalid email address";
        }

        //password
        if (strlen($password) < 8){
            $errors[] = 'Password must be 8 char!';
        }

        //role
        if(!in_array($role, ['admin', 'client'], false)){
            $errors[] = 'Invalid Role';
        }

        // se non ho l'array di errors popolato,
        // procedo con la creazione di un nuovo utente

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            $newId = User::create($name, $email, $password, $role);

            if($newId === null){
                $errors[] = 'This email is already registered';
            }else{
                $success = "User created!";

                // una volta creato l'utente svuoto la post
                $_POST = [];
            }
        }
    }

    // FUNZIONE CHE MODIFICA L'UTENTE

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit'){

        // mettimo zero(in fondo) perchè è un int
        $id = (int) ($_POST['user_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role =$_POST['role'] ?? 'client';

        if($name === ''){
            $errors[] = "Name is required";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="email is a invalid email address";
        }

        //role
        if(!in_array($role, ['admin', 'client'], true)){
            $errors[] = 'Invalid Role';
        }

        // se non ho l'array di errors popolato,
        // procedo con la creazione di un nuovo utente

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            if(User::updateUser($id, $name, $email, $role)){
                $success = "User updated!";
            }else{
                $errors[] = 'You have not change anything';
            };
        }
    }

    // FUNZIONE CHE ELIMINA L'UTENTE

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete'){

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['user_id'] ?? 0);

    // mi dice se che se l'id è uguale all'user(cioè l'user loggato in quel momento)
    if($targetID === (int) $user['id']){
        $errors[] = "You can't delete yourself !";
    }elseif (User::delete($targetID)){

        $success = "User deleted!";
    }else{
        $errors[] = "Failed delete user!";
    }

    }

    $users = User::findAllUsers();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/style.css">
    <title>Gestione Utenti — Gestionale Società Sportiva</title>
</head>
<body>

<div class="dash">

    <!-- ============ SIDEBAR (uguale alla dashboard) ============ -->
    <aside class="dash-sidebar">
        <div class="dash-brand">
            <i class="fas fa-hockey-puck"></i>
            <span>Gestionale Società Sportiva</span>
        </div>

        <nav class="dash-nav">
            <a href="/Gestionale-Hockey/dashboard.php" class="dash-link">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>
            <hr>
            <!-- VERSIONE DASHBOARD CLIENT -->
            <?php if($user['role'] === 'client'): ?>
            <a href="/Gestionale-Hockey/client/calendarioPartite.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Calendario</span>
            </a>

            <a href="/Gestionale-Hockey/client/ordiniEffettuati.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Ordini</span>
            </a>

            <!-- <a href="/Gestionale-Hockey/client/news.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>News</span>
            </a> -->
        
            <?php endif; ?>

            <!-- SE L'UTENTE è ADMIN, MI MOSTRI QUESTO -->
            <?php if($user['role'] === 'admin'): ?>
            <a href="/Gestionale-Hockey/admin/users.php" class="dash-link active">
                <i class="fas fa-user-shield"></i><span>Utenti Registrati</span>
            </a>
            <a href="/Gestionale-Hockey/admin/ordiniProdottiRicevuti.php" class="dash-link">
                <i class="fas fa-user-shield"></i><span>Ordini Effettuati</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="dash-user">
            <div class="dash-avatar"><?= htmlspecialchars(strtoupper(substr($user['name'], 0, 1))) ?></div>
            <div class="dash-user-info">
                <strong><?= htmlspecialchars($user['name']) ?></strong>
                <small><?= htmlspecialchars($user['role']) ?></small>
            </div>
        </div>
        <a href="/Gestionale-Hockey/logout.php" class="dash-logout">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </aside>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Gestione Utenti</h1>
            <p>Crea un nuovo utente e gestisci gli account esistenti.</p>
        </header>

        <!-- messaggi di esito (prima venivano calcolati ma non mostrati) -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- ---- form nuovo utente ---- -->
        <section class="dash-panel dash-formcard">
            <div class="dash-panel-head">
                <h2><i class="fas fa-user-plus"></i> Nuovo Utente</h2>
            </div>
            <div class="dash-formbody">
                <!-- Serve per attivare la funzione di aggiunta utenti alla tabella tramite il form -->
                <form action="" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="8" required>
                    </div>
                    <div class="mb-3">
                        <label for="role">Ruolo</label>
                        <select name="role" id="role" class="form-select">
                            <option value="client">Client</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button class="btn btn-warning" type="submit">Crea utente</button>
                </form>
            </div>
        </section>

        <!-- ---- tabella utenti ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i> Utenti registrati</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ruolo</th>
                        <th>Iscritto</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <!-- prendo l'id dell'utente singolo -->
                        <td>#<?= (int) $u['id'] ?></td>

                        <td>
                            <?= htmlspecialchars($u['name']) ?>
                            <!-- Se siamo noi gli utenti in tabella colora di warning -->
                            <?php if((int) $u['id'] === (int) $user['id']): ?>
                            <small class="text-warning">(tu)</small>
                            <?php endif ?>
                        </td>

                        <td><?= htmlspecialchars($u['email']) ?></td>

                        <td><?= htmlspecialchars($u['role']) ?></td>

                        <!-- rappresentazione di quando abbiamo loggato/joinato sottoforma di date-->
                        <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>

                        <td class="text-end">
                            <?php if((int) $u['id'] !== (int) $user['id']): ?>

                                <!-- PULSANTE DI EDIT -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-info" 
                                    title="Edit user"
                                    data-bs-toggle="modal" data-bs-target="#modalEdit<?= (int) $u['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <?php include __DIR__ . '/modalEdit.php'; ?>

                                <!-- PULSANTE DI DELETE -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Delete user"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDelete<?= (int) $u['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                </button>
                                <?php include __DIR__ . '/modalDelete.php'; ?>

                                <!-- TOGGLE ROLE  ENT_QUOTES -> converte anche gli apostrofi cosi la stringa JS non si rompe -->
                                <form action="" method="post" class="d-inline">
                                    <input type="hidden" name="action"   value="toggle_role">
                                    <input type="hidden" name="user_id"  value="<?= (int) $u['id'] ?>">
                                    <input type="hidden" name="new_role" value="<?= $u['role'] === 'admin' ? 'client' : 'admin' ?>">
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Make <?= $u['role'] === 'admin' ? 'client' : 'admin' ?>"
                                            onclick="return confirm('Change role of <?= htmlspecialchars($u['name'], ENT_QUOTES) ?>?');">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                </form>

                            <?php else: ?>
                                <span class="dash-current">— sei tu —</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="dash-empty">Nessun utente registrato.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
