<?php

    // faccio partire la sessione
    // funzione nativa di php
    session_start();

    //richiamo del singleton per attivare le PDO
    require_once __DIR__ . '/classes/Db.php';

    // raccolgo tutti gli errori e imposto succes=false
    $errors = [];
    $success = false;
    
    // CODICE RIPETIBILE!!!
    // se la chiamata è post
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // recuperare dagli inout i valori del form
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $conferma_password = trim($_POST['confermaPassword'] ?? '');

        // validazione con messaggi

        // se è vuoto lascio un'errore
        if ($name === '') {
            $errors[] = 'Name is required';
        }
    
        if (strlen($password) < 8) {
            $errors[] = 'Password must be longer than 8';
        }
        if ($conferma_password !== $password) {
            $errors[] = 'Passwords do not match';
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
            
            } else {
                // faccio l'hash della password
                // crea un numero alfanumerico di sicurezza
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                //inserimento del nuovo utente
                $stmt = $connettoreDb->prepare('INSERT INTO users(name, email, password, role)
                                       VALUES (:name, :email, :password, :role)');
                $stmt->execute([
                    ':name' => $name,
                    ':email' => $email,
                    ':password' => $passwordHash,
                    ':role' => 'client'

                ]);

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
                    <h2 class="fw-bold text-warning text-center mb-4">Registro</h2>

                    <!-- se success esiste -->
                    <?php if($success): ?>

                        <div class="alert alert-success">
                            Registrazione succesfull! You can now <a href="login.php" class="alert-link">Login</a>
                        </div>
                    
                    <?php endif; ?>

                    <!-- se errors è diverso da vuoto -->
                    <?php if(!empty($errors)): ?>

                        <div class="alert alert-danger">
                            <ul>
                                <!-- per ogni errore trovato nell'array degli errori
                                mi crei un li con l'htmlspecialchars che passerò
                                come error -->
                                <?php foreach ($errors as $error): ?>

                                    <li>
                                        <!-- alert che esce una volta completato con successo la registrzione -->
                                        <?= htmlspecialchars($error); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    
                    <?php endif; ?>


                    <form action="" method="post">

                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                                   required>
                        </div>
                        <!-- htmlspecialchars rappresenta valore della chiamata trasfomato in elemento html -->

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

                        <button type="submit" class="btn-warning btn-outline-warning w-100">Crea Account</button>

                        <p>Se hai già un account vai alla pagina login <a href="login.php" class="bg-warning border-danger"></a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'?>


