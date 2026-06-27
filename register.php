<?php

    // faccio partire la sessione
    // funzione nativa di php
    session_start();

    //richiamo del singleton per attivare le PDO
    require_once __DIR__ . '/DB/classes/Db.php';

    // raccolgo tutti gli errori e imposto succes=false
    $errors = [];
    $success = false;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // recuperare dagli inout i valori del form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $conferma_password = trim($_POST['confermaPassword'] ?? '');
        $codice = trim($_POST['codice'] ?? '');

        // validazione con messaggi

        // se è vuoto lascio un'errore
        if ($name === '') {
            $errors[] = 'Il nome è obbligatorio';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email non valida';
        }
    
        if (strlen($password) < 8) {
            $errors[] = 'Password must be longer than 8';
        }
        if ($conferma_password !== $password) {
            $errors[] = 'Passwords do not match';
        }

        if ($codice === '') {
            $errors[] = 'il codice societario è obbligatorio';
        }

        // se la validazione passa interroghiamo il Db
        // se il container dell'errore è vuoto 
        if(empty($errors)) {
            // connetto il db
            $connettoreDb = Db::connect();

            // controllo che l'email non sia registrata già
            $stmt = $connettoreDb->prepare('SELECT id FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);

            if($stmt->fetch()){
                $errors[] = 'This email is already registered';
        }
            // il codice esiste? è ancora libero?

            $codeRow = null;
            if (empty($errors)) {
                $stmt = $connettoreDb->prepare(
                    'SELECT id 
                     FROM registration_codes
                     WHERE code = :code AND used = 0'
                );
                $stmt->execute([':code' => $codice]);
                $codeRow = $stmt->fetch();
                if (!$codeRow) {
                    $errors[] = 'Codice non valido o già utilizzato';
                }
            }

            // funzione che crea l'utente e consuma il codice legandolo all'user per sempre

            if(empty($errors)) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $quotaScadenza = date('Y-m-d', strtotime('+10 months'));

                $stmt = $connettoreDb->prepare(
                    'INSERT INTO users(
                        name, email, password, role, quota_scadenza)
                     VALUES (:name, :email, :password, :role, :quota_scadenza)'
                );
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash,
                    ':role' => 'client',
                    ':quota_scadenza' => $quotaScadenza,
                ]);

                $newUserId = (int) $connettoreDb->lastInsertId();

                //evidenzio il codice come usato da questo nuovo utente

                $stmt = $connettoreDb->prepare(
                    // used = 1 segna il codice come usato. Da ora la SELECT ... WHERE used = 0 non lo troverà più 
                    // non si potrà più registrare un altro account con quel codice.
                    'UPDATE registration_codes SET used = 1,
                    used_by = :uid WHERE id = :cid'

                    // used_by = :uid → scrive quale utente l'ha usato 
                    // WHERE id = :cid → aggiorna solo quella riga del codice
                );
                $stmt->execute([':uid' => $newUserId, ':cid' => $codeRow['id']]);

                $success = true;
            }
        }
    }
?>

<?php include 'header.php'?>

<main class="mainProject">
    <section class="bg_personal min-vh-100 py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="register-head">
                        <h2><i class="fas fa-user-plus"></i> Crea Account</h2>
                    </div>

                    <div class="register-body">
                        <!-- se success esiste -->
                        <?php if($success): ?>
                            <div class="alert alert-success">
                                Registrazione completata! Ora puoi accedere <a href="login.php" class="alert-link">Login</a>
                            </div>
                        <?php endif; ?>

                        <!-- se errors è diverso da vuoto -->
                        <?php if(!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form class="frm-register" action="" method="post">

                            <div class="mb-3">
                                <label for="name">Nome</label>
                                <input type="text"
                                       class="form-control"
                                       name="name"
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email"
                                       class="form-control"
                                       name="email"
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirm">Conferma Password</label>
                                <input type="password"
                                       class="form-control"
                                       name="confermaPassword"
                                       value="<?= htmlspecialchars($_POST['confermaPassword'] ?? '') ?>"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="codice">Codice societario</label>
                                <input type="text"
                                    class="form-control"
                                    name="codice"
                                    value="<?= htmlspecialchars($_POST['codice'] ?? '') ?>"
                                    placeholder="Inserisci il tuo codice dato dalla società dopo il pagamento della quota"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-warning">Crea Account</button>

                            <p class="mt-3" style="color: white;">Se hai già un account vai alla pagina login <a class="a-login-register" href="login.php">Login</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'?>


