<?php
// faccio partire la sessione
// funzione nativa di php
session_start();

//richiamo del singleton per attivare le PDO
require_once __DIR__ . '/DB/classes/Db.php';
// serve per csrfToken() e verificaCsrf() usati dal form
require_once __DIR__ . '/DB/helpers/auth.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  verificaCsrf();
  // recuperare dagli inout i valori del form
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  // validazione per singolo campo
  if ($email === '') {
    $errors[] = 'L\'email è obbligatoria';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Formato email non valido';
  }

  if ($password === '') {
    $errors[] = 'La password è obbligatoria';
  }

  // se i campi sono validi, controllo le credenziali nel DB
  if (empty($errors)) {
    $connettoreDb = Db::connect();

    // recupero l'utente con quella email
    $stmt = $connettoreDb->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch();

    // verifico la password con password_verify
    if ($user && password_verify($password, $user['password'])) {

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
<?php include __DIR__ . '/includes/header.php' ?>

<section class="auth">
  <div class="wrap">
    <div class="card auth__box">
      <form class="auth__form" action="" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

        <div class="auth__intro">
          <h1>Accedi al gestionale</h1>
          <p>Inserisci le tue credenziali per entrare nella tua area riservata.</p>
        </div>

        <!-- messaggi di errore (validazione campi + credenziali) -->
        <?php if (!empty($errors)): ?>
            <div class="notice notice--error" role="alert">
              <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
              <ul>
                <?php foreach ($errors as $e): ?>
                  <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

        <label class="field" for="email">Email
          <input type="email" id="email" name="email" class="input" autocomplete="email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </label>

        <label class="field" for="password">Password
          <input type="password" id="password" name="password" class="input" autocomplete="current-password" required>
        </label>

        <button type="submit" class="button button--ink button--lg button--block">Entra</button>

        <p class="auth__alt">Non hai un account? <a href="<?= BASE_URL ?>/register.php"><strong>Registrati con il codice societario</strong></a></p>
      </form>

      <div class="auth__media">
        <img src="<?= BASE_URL ?>/assets/img/storia-club-index.png" alt="">
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
