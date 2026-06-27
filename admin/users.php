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
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role =$_POST['role'] ?? 'client';

        // validazione degli input

        //nome
        if($name === ''){
            $errors[] = "Il nome è obbligatorio";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="Email non valida";
        }

        //password
        if (strlen($password) < 8){
            $errors[] = 'Password must be 8 char!';
        }

        //role
        if(!in_array($role, ['admin', 'client'], true)){
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
                $success = "Utente creato!";

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
            $errors[] = "Il nome è obbligatorio";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="Email non valida";
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
                $success = "Utente aggiornato!";
            }else{
                $errors[] = 'Email già usata da un altro utente';
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
            $errors[] = "Non puoi eliminare te stesso!";
        }elseif (User::delete($targetID)){

            $success = "Utente eliminato!";
        }else{
            $errors[] = "Eliminazione utente fallita!";
        }

    }

    // FUNZIONE CHE CAMBIA IL RUOLO (toggle)
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'toggle_role'){
        $id      = (int) ($_POST['user_id'] ?? 0);
        $newRole = $_POST['new_role'] ?? '';

        if($id === (int) $user['id']){
            $errors[] = "Non puoi cambiare il ruolo a te stesso";
        } elseif(User::updateRole($id, $newRole)){
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
        <div class="alg-form-results">
            <section class="dash-panel-form dash-formcard">
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

                    <!-- ---- tabella richieste informazioni ---- -->
            <section class="dash-panel-info">
                <div class="dash-panel-head">
                    <h2><i class="fas fa-users"></i> Richieste informazioni</h2>
                </div>
                <table class="dash-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefono</th>
                            <th>Categoria</th>
                            <th>Messaggio</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($request as $req): ?>
                        <tr>
                            <td>#<?= (int) $req['id'] ?></td>

                            <td>
                                <?= htmlspecialchars($req['name']) ?>
                            </td>

                            <td><?= htmlspecialchars($req['email']) ?></td>

                            <td><?= htmlspecialchars($req['phone']) ?></td>

                            <td><?= htmlspecialchars($req['categoria']) ?></td>

                            <td><?= htmlspecialchars($req['messaggio']) ?></td>

                            <!-- rappresentazione di quando abbiamo loggato/joinato sottoforma di date-->
                            <td><?= date('d M Y', strtotime($req['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($request)): ?>
                    <tr><td colspan="7" class="dash-empty">Nessuna nuova richiesta di informazioni</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </div>
        
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
                            title="Modifica utente"
                            data-bs-toggle="modal" data-bs-target="#modalEdit<?= (int) $u['id'] ?>">
                                <i class="fas fa-edit"></i>
                        </button>
                        <?php include __DIR__ . '/modali/UserEditModal.php'; ?>

                        <!-- PULSANTE DI DELETE -->
                        <button 
                            type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            title="Elimina utente"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalDelete<?= (int) $u['id'] ?>">
                                <i class="fas fa-trash"></i>
                        </button>
                        <?php include __DIR__ . '/modali/UserDeleteModal.php'; ?>

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
