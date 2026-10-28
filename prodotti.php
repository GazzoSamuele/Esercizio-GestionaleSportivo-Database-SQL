<?php
session_start();

require_once __DIR__ . '/DB/helpers/auth.php';
require_once __DIR__ . '/DB/classes/Products.php';
require_once __DIR__ . '/DB/classes/Purchases.php';

$prodotti = Products::findIsActive(true) ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_to_cart') {

  verificaCsrf();

  $prodId = (int) ($_POST['product_id'] ?? 0);
  $_SESSION['cart'][] = $prodId;
  header('Location: prodotti.php#prodotti');
  exit;

  // ORA E' CON IL REDIRECT DELLA PAGINA, POI CAMBIO LA FUNZIONE AL SEMPLICE
  // LOG DELL'UTENTE, PERCHE' IL CARICAMENTO CHE TI SPOSTA DA FASTIDIO
  //(SOLO PER VERIFICARE LA POSIZIONE DELLA PAGINA)
}

// Questo blocco scatta solo quando premi "Prenota (ritiro)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout') {

  verificaCsrf();

  $u = currentUser();  // Un ordine deve essere legato a un utente.
  if (!$u) {
    //se non sei loggato, non posso creare l'ordine, ti mando al login
    header('Location: login.php');
    exit;
  }

  // scorro ogni id nel carrello
  foreach (($_SESSION['cart'] ?? []) as $prodId) {
    // per quell'id, recupero il prodotto dal DB 
    $prodotto = Products::findById($prodId);

    if ($prodotto) {
      // creo una riga d'ordine: questo utente, questo prodotto, 
      // questo prezzo, stato "pending". Una per ogni prodotto del carrello.
      Purchases::create($u['id'], $prodId, $prodotto['price'], 'pending');
    }
  }

  // finito di ordinare, svuoto il carrello 
  $_SESSION['cart'] = [];
  //  ricarico la pagina pulita
  header('Location: prodotti.php?ordine=ok#prodotti');
  exit;
}

?>

<?php include __DIR__ . '/includes/header.php' ?>

<?php
// prodotti nel carrello: li recupero una volta sola per lista e totale
$cart = $_SESSION['cart'] ?? [];   // il carrello (array di id), [] se vuoto
$prodottiCarrello = [];
$totale = 0;
foreach ($cart as $pid) {
  $prod = Products::findById($pid);
  if ($prod) {
    $prodottiCarrello[] = $prod;
    $totale += (float) $prod['price'];
  }
}

// categorie mostrate in alto: immagine => [titolo, descrizione]
$categorieShop = [
  'attrezzatura-allenamento.png' => ["Attrezzatura d'allenamento", 'Tutto il necessario per allenarsi oltre alle classiche sessioni settimanali.'],
  'attrezzatura-mista.png'       => ['Attrezzatura giocatore', "L'occorrente per le partite più impegnative, scelto per qualità e resistenza."],
  'attrezzatura-portieri.png'    => ['Attrezzatura portieri', 'Protezioni complete per affrontare ogni parata in totale sicurezza.'],
  'borsone-sportivo.png'         => ['Borsoni della società', "Capienti e resistenti, con i colori del club, per portare l'attrezzatura ovunque."],
];
?>

<section class="page-hero">
  <div class="wrap page-hero__inner">
    <nav class="breadcrumb-nav" aria-label="Percorso">
      <a href="<?= BASE_URL ?>/index.php">Home</a><span aria-hidden="true">/</span><span aria-current="page">Prodotti</span>
    </nav>
    <h1>Shop del club</h1>
    <p>Scegli il prodotto, prenotalo online e ritiralo direttamente in club house. Il pagamento avviene al ritiro.</p>
  </div>
</section>

<!-- ============ SEZIONE 1 — Categorie ============ -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>Le nostre categorie</h2>
      <p>Attrezzatura per il campo e per fuori dal campo.</p>
    </div>

    <div class="grid-4">
      <?php foreach ($categorieShop as $img => [$titolo, $descrizione]): ?>
        <a class="card cat-card" href="#prodotti">
          <img src="<?= BASE_URL ?>/assets/img/<?= $img ?>" alt="">
          <div class="cat-card__body">
            <h3><?= $titolo ?></h3>
            <p><?= $descrizione ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Prodotti + carrello ============ -->
<section class="section section--white" id="prodotti">
  <div class="wrap">
    <div class="section-head">
      <h2>I prodotti</h2>
      <p>Aggiungi i prodotti alla tua selezione: la prenotazione arriva direttamente al club.</p>
    </div>

    <?php if (($_GET['ordine'] ?? '') === 'ok'): ?>
      <div class="notice notice--ok" role="status"><i class="fa-solid fa-check" aria-hidden="true"></i> Ordine effettuato! Ritiro e pagamento in club house.</div>
    <?php endif; ?>

    <div class="shop-layout">
      <!-- griglia prodotti -->
      <div class="grid-3">
        <?php foreach ($prodotti as $prod): ?>
          <article class="card product">
            <img src="<?= htmlspecialchars($prod['image_path']) ?>" alt="">
            <div class="product__body">
              <h3><?= htmlspecialchars($prod['name']) ?></h3>
              <p class="product__desc"><?= htmlspecialchars($prod['description']) ?></p>
              <div class="product__foot">
                <span class="price">€ <?= number_format((float) $prod['price'], 2, ',', '.') ?></span>

                <?php if ($prod['is_active']): ?>
                  <form action="prodotti.php" method="post">
                    <input type="hidden" name="action" value="add_to_cart">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
                    <input type="hidden" name="product_id" value="<?= (int) $prod['id'] ?>">
                    <button type="submit" class="button button--ink button--sm" aria-label="Prenota <?= htmlspecialchars($prod['name']) ?>">Prenota</button>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>

        <?php if (empty($prodotti)): ?>
          <p class="field__hint">Nessun prodotto disponibile al momento.</p>
        <?php endif; ?>
      </div>

      <!-- carrello -->
      <aside class="card cart" aria-labelledby="titolo-carrello">
        <div class="cart__head">
          <h3 id="titolo-carrello">Prodotti selezionati</h3>
          <span class="count-badge" aria-label="<?= count($prodottiCarrello) ?> prodotti"><?= count($prodottiCarrello) ?></span>
        </div>

        <div class="cart__body">
          <?php if (empty($prodottiCarrello)): ?>
            <p class="cart__empty">Nessun prodotto selezionato.</p>
          <?php endif; ?>

          <?php foreach ($prodottiCarrello as $prod): ?>
            <div class="cart-item">
              <img src="<?= htmlspecialchars($prod['image_path']) ?>" alt="">
              <div>
                <b><?= htmlspecialchars($prod['name']) ?></b>
                <small>€ <?= number_format((float) $prod['price'], 2, ',', '.') ?></small>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="cart__total">
            <span>Totale</span>
            <strong>€ <?= number_format($totale, 2, ',', '.') ?></strong>
          </div>

          <!-- il bottone checkout -->
          <form action="prodotti.php" method="post">
            <input type="hidden" name="action" value="checkout">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrfToken()) ?>">
            <button type="submit" class="button button--ink button--lg button--block" <?= empty($prodottiCarrello) ? 'disabled' : '' ?>>Conferma prenotazione</button>
          </form>
          <p class="cart__note">Ritiro e pagamento in club house</p>

          <div class="helper">
            <b>Non sai cosa scegliere?</b>
            Scrivi direttamente all'allenatore o passa in club house durante un allenamento.
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
