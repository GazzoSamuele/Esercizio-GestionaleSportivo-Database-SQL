<?php

require_once __DIR__ . '/DB/classes/InfoRequest.php';
// serve per csrfToken() e verificaCsrf() usati dal form
require_once __DIR__ . '/DB/helpers/auth.php';

// mappa categoria: il value del form (numero) → etichetta salvata nel DB
$categorieContatti = [
  '1' => 'Giovanili: 6-13 anni',
  '2' => 'Cadetti: 14-15 anni',
  '3' => 'Allievi: 16-17 anni',
  '4' => 'Juniores: 18-19 anni',
  '5' => 'Promesse: 20-22 anni',
  '6' => 'Seniores: dai 23 anni in poi',
];

$errors = [];
$success = '';

// FUNZIONE CHE CREA L'UTENTE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create') {

  verificaCsrf();

  $name = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $phone = $_POST['phone'] ?? '';
  $categoria = $_POST['categoria'] ?? '';
  $messaggio = $_POST['messaggio'] ?? '';

  // validazione degli input

  //nome
  if ($name === '') {
    $errors[] = "Il nome è obbligatorio";
  }

  //email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email non valida";
  }

  //phone
  if ($phone === '') {
    $errors[] = "Il telefono è obbligatorio";
  }

  //categoria
  if (!isset($categorieContatti[$categoria])) {
    $errors[] = 'Categoria non valida';
  }

  if (empty($errors)) {
    // salvo l'etichetta leggibile (es. "Cadetti: 14-15 anni"), non il numero
    InfoRequest::create($name, $email, $phone, $categorieContatti[$categoria], $messaggio);

    $success = "Richiesta inviata!";

    $_POST = [];
  }
}
?>

<?php include __DIR__ . '/includes/header.php' ?>

<section class="page-hero">
  <div class="wrap page-hero__inner">
    <nav class="breadcrumb-nav" aria-label="Percorso">
      <a href="<?= BASE_URL ?>/index.php">Home</a><span aria-hidden="true">/</span><span aria-current="page">Contatti</span>
    </nav>
    <h1>Contatti</h1>
    <p>Richiedi informazioni con il modulo qui sotto oppure contatta direttamente il club.</p>
  </div>
</section>

<!-- ============ Richiesta informazioni ============ -->
<section class="section">
  <div class="wrap">
    <div class="card contact">
      <div class="contact__info">
        <div>
          <h2>Hai bisogno di confrontarti con qualcuno?</h2>
          <p>Compila il modulo oppure contattaci direttamente attraverso uno di questi canali.</p>
        </div>

        <div class="contact-item">
          <span class="contact-item__icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
          <div><small>Indirizzo</small><span>Via dello Sport 12, 38100 Trento</span></div>
        </div>
        <div class="contact-item">
          <span class="contact-item__icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
          <div><small>Telefono</small><span>+39 0461 123456</span></div>
        </div>
        <div class="contact-item">
          <span class="contact-item__icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
          <div><small>Email</small><span>info@hockeyclub.it</span></div>
        </div>

        <div class="contact__social">
          <small>Seguici sui social</small>
          <div>
            <a href="#" class="button button--ghost button--sm"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i> Facebook</a>
            <a href="#" class="button button--ghost button--sm"><i class="fa-brands fa-instagram" aria-hidden="true"></i> Instagram</a>
          </div>
        </div>
      </div>

      <form class="contact__form" action="contatti.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
        <input type="hidden" name="action" value="create">

        <h2>Richiedi informazioni</h2>

        <!-- esito dell'invio -->
        <?php if ($success): ?>
          <div class="notice notice--ok" role="status"><i class="fa-solid fa-check" aria-hidden="true"></i> <?= htmlspecialchars($success) ?></div>
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

        <div class="field-row">
          <label class="field" for="name">Nome
            <input type="text" id="name" name="name" class="input" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
          </label>
          <label class="field" for="email">Email
            <input type="email" id="email" name="email" class="input" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
          </label>
        </div>

        <label class="field" for="phone">Numero di telefono
          <input type="tel" id="phone" name="phone" class="input" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </label>

        <label class="field" for="categoria">Categoria
          <select class="input" id="categoria" name="categoria" required>
            <option value="">Seleziona una categoria</option>
            <?php foreach ($categorieContatti as $val => $label): ?>
              <option value="<?= $val ?>" <?= ($_POST['categoria'] ?? '') === (string) $val ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
          </select>
          <span class="field__hint">Per l'inserimento in una categoria, chiedi direttamente al club.</span>
        </label>

        <label class="field" for="messaggio">La tua richiesta
          <textarea class="input" id="messaggio" name="messaggio" rows="5"><?= htmlspecialchars($_POST['messaggio'] ?? '') ?></textarea>
        </label>

        <div>
          <button type="submit" class="button button--accent">Invia richiesta</button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- ============ Quota societaria e registrazione ============ -->
<section class="section section--mint">
  <div class="wrap">
    <div class="quota">
      <div>
        <div class="section-head">
          <h2>Quota societaria</h2>
          <p>Cosa è incluso con l'iscrizione al club.</p>
        </div>
        <ul class="quota__list" style="margin-top: 16px">
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Dashboard personale con tutte le informazioni che ti servono</span></li>
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Calendario completo, collegabile a Google Calendar</span></li>
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Avvisi su trasferte e riunioni societarie</span></li>
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Schede di allenamento del preparatore atletico</span></li>
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Gestione del pagamento della quota annuale</span></li>
          <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>Avvisi sulla nuova attrezzatura</span></li>
        </ul>
      </div>

      <div class="card quota__card">
        <span class="icon-tile"><i class="fa-solid fa-key" aria-hidden="true"></i></span>
        <h3>Registrazione con codice</h3>
        <p>Per registrarti serve il codice univoco rilasciato dalla società dopo il pagamento della quota in club house.</p>
        <div>
          <a href="<?= BASE_URL ?>/register.php" class="button button--ink button--lg">Vai alla registrazione <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
