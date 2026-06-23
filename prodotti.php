<?php
  session_start();

  require_once __DIR__ . '/DB/helpers/auth.php';
  require_once __DIR__ . '/DB/classes/Products.php';
  require_once __DIR__ . '/DB/classes/Purchases.php'; 

  $prodotti = Products::findIsActive(true) ?? [];

 if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_to_cart'){
    $prodId = (int) ($_POST['product_id'] ?? 0);
    $_SESSION['cart'][] = $prodId;
    header('Location: prodotti.php#prodotti');
    exit;

    // ORA E' CON IL REDIRECT DELLA PAGINA, POI CAMBIO LA FUNZIONE AL SEMPLICE
    // LOG DELL'UTENTE, PERCHE' IL CARICAMENTO CHE TI SPOSTA DA FASTIDIO
    //(SOLO PER VERIFICARE LA POSIZIONE DELLA PAGINA)
}

  // Questo blocco scatta solo quando premi "Prenota (ritiro)

  if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'checkout'){


      
      $u = currentUser();  // Un ordine deve essere legato a un utente.
      if(!$u){
      //se non sei loggato, non posso creare l'ordine, ti mando al login
        header('Location: login.php');
        exit;
      }

      // scorro ogni id nel carrello
      foreach(($_SESSION['cart'] ?? []) as $prodId){
        // per quell'id, recupero il prodotto dal DB 
        $prodotto = Products::findById($prodId);

        if($prodotto){
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

<?php include 'header.php'?>

<section class="pagehead">
  <div class="container">
    <h1>Lo shop del club</h1>
  </div>
</section>

<!-- ============ SEZIONE 1 ============ -->
<section class="sec sec--white">
  <div class="container">
    <h2>Scegli la categoria</h2>
    <p class="sub">Apri "Scopri di più" per allungare la card e vedere attrezzature e marche presenti nella sezione.</p>

    <div class="cat-cards">
      <div class="cat">
        <div class="ph" style="min-height:150px"><small>PORTIERI</small></div>
        <div class="cat__body">
          <h3>Attrezzatura portieri</h3>
          <p>Tutto il necessario per il ruolo di portiere.</p>
        </div>
        <input type="checkbox" id="c1">
        <div class="cat__more">
          <ul><li>Maschere e caschi</li><li>Parastinchi e gambali</li><li>Guanti e bloccatori</li><li>Marche: CCM, Bauer, Vaughn</li></ul>
        </div>
        <label for="c1">Scopri di più ▾</label>
      </div>

      <div class="cat">
        <div class="ph" style="min-height:150px"><small>BASTONI</small></div>
        <div class="cat__body">
          <h3>Bastoni</h3>
          <p>Bastoni per ogni categoria e livello.</p>
        </div>
        <input type="checkbox" id="c2">
        <div class="cat__more">
          <ul><li>Bastoni senior / junior</li><li>Curve e flex differenti</li><li>Composito e legno</li><li>Marche: Bauer, Warrior, CCM</li></ul>
        </div>
        <label for="c2">Scopri di più ▾</label>
      </div>

      <div class="cat">
        <div class="ph" style="min-height:150px"><small>PROTEZIONI</small></div>
        <div class="cat__body">
          <h3>Protezioni</h3>
          <p>Protezioni per giocatori di movimento.</p>
        </div>
        <input type="checkbox" id="c3">
        <div class="cat__more">
          <ul><li>Spallacci e gomitiere</li><li>Pantaloni protettivi</li><li>Paradenti e collari</li><li>Marche: Bauer, CCM, Warrior</li></ul>
        </div>
        <label for="c3">Scopri di più ▾</label>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Prodotti + sidebar ============ -->
<section class="sec sec--bg" id="prodotti">
  <div class="container">
    <h2>I prodotti</h2>
    <p class="sub">Rappresentazione tramite card. Seleziona i prodotti: compaiono nella barra laterale.</p>

    <?php if(($_GET['ordine'] ?? '') === 'ok'): ?>
      <div class="alert alert--success">Ordine effettuato! Ritiro in club house.</div>
    <?php endif; ?>
    <div class="layout-shop">
      <!-- griglia prodotti -->
        <div class="prod-grid">
          <?php foreach($prodotti as $prod): ?>
            <article class="product">
                <img src="<?= htmlspecialchars($prod['image_path']) ?>" alt="">
                <div class="product__body">
                    <h3><?= htmlspecialchars($prod['name']) ?></h3>
                    <p class="desc"><?= htmlspecialchars($prod['description']) ?></p>
                    <div class="product__foot">
                        <span class="price">€ <?= htmlspecialchars($prod['price']) ?></span>

                        <?php if($prod['is_active']): ?>
                        <form action="prodotti.php" method="post">
                            <input type="hidden" name="action" value="add_to_cart">
                            <input type="hidden" name="product_id" value="<?= (int) $prod['id'] ?>">
                            <button type="submit" class="btn btn--primary">Prenota</button>
                        </form>
                      <?php endif; ?>
                    </div>
                </div>
            </article>
          <?php endforeach; ?>
        </div>

      <!-- sidebar selezione -->
        <aside class="sidebar">
          <h3>Prodotti selezionati</h3>

          <?php
          $cart = $_SESSION['cart'] ?? [];   // il carrello (array di id), [] se vuoto
          $totale = 0;
          ?>

          <?php foreach($cart as $pid): ?>
              <?php $prod = Products::findById($pid); ?>
              <?php if($prod): ?>
                  <div class="cart-item">
                      <span class="ph thumb">
                          <img src="<?= htmlspecialchars($prod['image_path']) ?>" alt="">
                        </span>
                      <div>
                          <b><?= htmlspecialchars($prod['name']) ?></b><br>
                          <small>€ <?= htmlspecialchars($prod['price']) ?></small>
                      </div>
                  </div>
                  <?php $totale += (float) $prod['price']; ?>
              <?php endif; ?>
          <?php endforeach; ?>

          <?php if(empty($cart)): ?>
              <p class="note">Nessun prodotto selezionato.</p>
          <?php endif; ?>

          <div class="sidebar__total">
              <span>Totale</span>
              <span>€ <?= number_format($totale, 2) ?></span>
          </div>

          <!-- il bottone checkout -->
          <form action="prodotti.php" method="post">
              <input type="hidden" name="action" value="checkout">
              <button type="submit" class="btn btn--primary btn--block">Prenota (ritiro in club house)</button>
          </form>

          <div class="helper">
              <b>Non sai cosa scegliere?</b>
              Scrivi direttamente all'allenatore...
          </div>
    </aside>
    </div>
  </div>
</section>

<?php include 'footer.php'?>