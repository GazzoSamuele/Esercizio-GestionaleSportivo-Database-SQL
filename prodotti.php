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
          // questo prezzo, stato "In attesa". Una per ogni prodotto del carrello.
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

<!-- ============ SEZIONE 1 ============ -->
<section class="sec sec--white ">
  <div class="container">
    <h2>Le nostre categorie</h2>
    <p class="sub">Le nostre categorie di prodotto che possiamo offrirti, sia per fuori che per dentro il campo</p>

    <div class="cat-cards">

        <div class="cat">
          <img class="cat__img" src="attrezzatura-allenamento.png" alt="Attrezzatura d'allenamento">
          <div class="cat__body">
            <h3>Attrezzatura d'allenamento</h3>
            <p>Tutto il necessario per allenarsi oltre alle classiche sessioni settimanali</p>
          </div>
        </div>

        <div class="cat">
          <img class="cat__img" src="attrezzatura-mista.png" alt="Attrezzatura giocatore">
          <div class="cat__body">
            <h3>Attrezzatura giocatore</h3>
            <p>Ampia scelta di massima qualità di tutto l'occorente utile durante le partite più impegnative</p>
          </div>
        </div>

        <div class="cat">
          <img class="cat__img" src="attrezzatura-portieri.png" alt="Attrezzatura portieri">
          <div class="cat__body">
            <h3>Attrezzatura portieri</h3>
            <p>VAsta gamma di protezioni per affrontare ogni parata in totale sicurezza</p>
          </div>
        </div>

        <div class="cat">
          <img class="cat__img" src="borsone-sportivo.png" alt="Borsoni della società">
          <div class="cat__body">
            <h3>Borsoni della società</h3>
            <p>Borsoni capienti e resistenti con i colori della società, per portare l'attrezzatura ovunque</p>
          </div>
        </div>

    </div>
  </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Prodotti + sidebar ============ -->
<section class="sec sec--bg" id="prodotti">
  <div class="container">
    <h2>I prodotti</h2>
    <p class="sub">Rappresentazione dei nostri prodotti e possibilità di aggiungerli al nostro carrello virtuale collegato direttamente con il club</p>

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