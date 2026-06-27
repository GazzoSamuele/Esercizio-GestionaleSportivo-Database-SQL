<?php   
    // faccio partire la sessione
    // funzione nativa di php
    session_start();

    //richiamo del singleton per attivare le PDO
    require_once __DIR__ . '/DB/classes/Db.php';

    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // recuperare dagli inout i valori del form
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // validazione per singolo campo
        if($email === ''){
            $errors[] = 'L\'email è obbligatoria';
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'Formato email non valido';
        }

        if($password === ''){
            $errors[] = 'La password è obbligatoria';
        }

        // se i campi sono validi, controllo le credenziali nel DB
        if(empty($errors)){
            $connettoreDb = Db::connect();

            // recupero l'utente con quella email
            $stmt = $connettoreDb->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);

            $user = $stmt->fetch();

            // verifico la password con password_verify
            if($user && password_verify($password, $user['password'])){

                // rigenero la sessione
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // login riuscito
                header('Location: dashboard.php');
                exit;
            }

            // se arrivo qui, email o password non combaciano
            $errors[] = 'Credenziali non valide';
        }
    }
?>
<?php include 'header.php'?>

<section class="sec sec--white sec-login">
   <div class="container">
    <h2>Accedi al gestionale</h2>
    <p class="sub">Semplice form dove inserire le proprie credenziali per accedere alla dashboard oppure registrarsi attraverso il link sottostante</p>
  <div class="container-login">
    <div class="authbox">
      <form class="form-login" action="" method="post">

        <!-- messaggi di errore (validazione campi + credenziali) -->
        <?php if(!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach($errors as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>


        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-control"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-warning w-100">Entra</button>

        <p class="mt-3 text-center">
          Non hai un account? <a class="a-login-register" href="register.php">Registrati</a>
        </p>
      </form>
    </div>

    <div class="authbox no_padding">
      <video autoplay loop muted playsinline class="video-login">
        <source src="hero-sport.mp4" type="video/mp4">
      </video>
    </div>

  </div>
  </div>
</section>

<?php include 'footer.php'?>