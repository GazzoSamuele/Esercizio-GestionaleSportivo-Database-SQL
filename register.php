<?php

// faccio partire la sessione
// funzione nativa di php
session_start();

//richiamo del singleton per attivare le PDO
require_once __DIR__ . '/DB/classes/Db.php';
// serve per csrfToken() e verificaCsrf() usati dal form
require_once __DIR__ . '/DB/helpers/auth.php';

// raccolgo tutti gli errori e imposto succes=false
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    verificaCsrf();
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
    if (empty($errors)) {
        // connetto il db
        $connettoreDb = Db::connect();

        // controllo che l'email non sia registrata già
        $stmt = $connettoreDb->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
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

        if (empty($errors)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $quotaScadenza = date('Y-m-d', strtotime('+10 months'));

            $connettoreDb->beginTransaction();
            try {
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

                $connettoreDb->commit();
                $success = true;
            } catch (PDOException $e) {
                $connettoreDb->rollBack();
                error_log('Registrazione fallita: ' . $e->getMessage());
                $errors[] = 'Registrazione non riuscita, riprova più tardi';
            }
        }
    }
}
?>

<?php include __DIR__ . '/includes/header.php' ?>

<section class="auth">
  <div class="wrap">
    <div class="card auth__box auth__box--wide">
      <form class="auth__form" action="" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">

        <div class="auth__intro">
          <h1>Crea il tuo account</h1>
          <p>La registrazione è riservata ai soci con quota societaria attiva.</p>
        </div>

        <?php if ($success): ?>
          <div class="notice notice--ok" role="status">
            <i class="fa-solid fa-check" aria-hidden="true"></i>
            <span>Registrazione completata! Ora puoi <a href="<?= BASE_URL ?>/login.php"><strong>accedere</strong></a>.</span>
          </div>
        <?php endif; ?>

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

        <label class="field" for="name">Nome e cognome
          <input type="text" class="input" name="name" id="name" autocomplete="name"
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
        </label>

        <label class="field" for="email">Email
          <input type="email" class="input" name="email" id="email" autocomplete="email"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </label>

        <div class="field-row">
          <label class="field" for="password">Password
            <input type="password" class="input" name="password" id="password" autocomplete="new-password" minlength="8" required>
            <span class="field__hint">Almeno 8 caratteri</span>
          </label>
          <label class="field" for="password_confirm">Conferma password
            <input type="password" class="input" name="confermaPassword" id="password_confirm" autocomplete="new-password" required>
          </label>
        </div>

        <label class="field" for="codice">Codice societario
          <input type="text" class="input" name="codice" id="codice"
            value="<?= htmlspecialchars($_POST['codice'] ?? '') ?>" required>
          <span class="field__hint">Il codice ti viene consegnato dalla società dopo il pagamento della quota.</span>
        </label>

        <div class="auth__submit">
          <button type="submit" class="button button--ink">Crea account</button>
          <span>Hai già un account? <a href="<?= BASE_URL ?>/login.php"><strong>Accedi</strong></a></span>
        </div>
      </form>

      <div class="auth__steps">
        <h2>Come funziona</h2>
        <div class="step"><small>Passo 1</small><strong>Paga la quota</strong><span>Salda la quota societaria annuale direttamente in club house.</span></div>
        <div class="step"><small>Passo 2</small><strong>Ricevi il codice</strong><span>La società ti consegna un codice univoco, valido per un solo account.</span></div>
        <div class="step"><small>Passo 3</small><strong>Crea il tuo account</strong><span>Inserisci il codice nel modulo e accedi subito alla tua area.</span></div>
        <p class="auth__foot">Hai perso il codice? Scrivi a info@hockeyclub.it o passa in club house.</p>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
