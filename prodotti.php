<?php
  require_once __DIR__ . '/DB/helpers/auth.php';
  require_once __DIR__ . '/DB/classes/Products.php';
  require_once __DIR__ . '/DB/classes/Purchases.php'; 

  $prodotti = Products::findIsActive(true);

  if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'prenota')
    {
      $u = currentUser();

      // per ordinare devi essere loggato
      if(!$u){
        heaeder('Location: login.php');
        exit;
      }
    
    $productId = (int) ($_POST['product_id'] ?? 0);
    // recupero il prodotto (per il prezzo)
    $prod = Products::findById($productId);

    if($prod){
      Purchases::create($u['id'], $productId, $prod['price'], 'pending');
    }

    header('Location: prodotti.php?ok=1');
    exit;
  }
?>

<?php include 'header.php'?>

<section class="pagehead">
  <div class="container">
    <span class="tag">Prodotti</span>
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
<section class="sec sec--bg">
  <div class="container">
    <span class="tag">Sezione 2</span>
    <h2>I prodotti</h2>
    <p class="sub">Rappresentazione tramite card. Seleziona i prodotti: compaiono nella barra laterale.</p>

    <div class="layout-shop">
      <!-- griglia prodotti -->
      <div class="prod-grid">
        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Casco portiere</h3>
            <p class="desc">Descrizione del prodotto, materiali e caratteristiche.</p>
            <div class="opts"><span class="opt">S</span><span class="opt">M</span><span class="opt">L</span></div>
            <div class="swatch"><i style="background:#0B2545"></i><i style="background:#E63946"></i><i style="background:#fff"></i></div>
            <div class="product__foot"><span class="price">€ 120</span><button class="add">+</button></div>
          </div>
        </article>

        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Bastone composito</h3>
            <p class="desc">Descrizione del prodotto, flex e curva disponibili.</p>
            <div class="opts"><span class="opt">Junior</span><span class="opt">Senior</span></div>
            <div class="swatch"><i style="background:#111"></i><i style="background:#E63946"></i></div>
            <div class="product__foot"><span class="price">€ 89</span><button class="add">+</button></div>
          </div>
        </article>

        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Spallacci protettivi</h3>
            <p class="desc">Descrizione del prodotto, vestibilità e protezione.</p>
            <div class="opts"><span class="opt">S</span><span class="opt">M</span><span class="opt">L</span><span class="opt">XL</span></div>
            <div class="swatch"><i style="background:#0B2545"></i><i style="background:#fff"></i></div>
            <div class="product__foot"><span class="price">€ 65</span><button class="add">+</button></div>
          </div>
        </article>

        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Guanti portiere</h3>
            <p class="desc">Descrizione del prodotto e taglie disponibili.</p>
            <div class="opts"><span class="opt">M</span><span class="opt">L</span></div>
            <div class="swatch"><i style="background:#0B2545"></i><i style="background:#E63946"></i></div>
            <div class="product__foot"><span class="price">€ 95</span><button class="add">+</button></div>
          </div>
        </article>

        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Gomitiere</h3>
            <p class="desc">Descrizione del prodotto, protezione e comfort.</p>
            <div class="opts"><span class="opt">S</span><span class="opt">M</span><span class="opt">L</span></div>
            <div class="swatch"><i style="background:#111"></i><i style="background:#fff"></i></div>
            <div class="product__foot"><span class="price">€ 40</span><button class="add">+</button></div>
          </div>
        </article>

        <article class="product">
          <div class="ph"><small>FOTO PRODOTTO</small></div>
          <div class="product__body">
            <h3>Paradenti</h3>
            <p class="desc">Descrizione del prodotto e colori disponibili.</p>
            <div class="opts"><span class="opt">Unica</span></div>
            <div class="swatch"><i style="background:#0B2545"></i><i style="background:#E63946"></i><i style="background:#fff"></i></div>
            <div class="product__foot"><span class="price">€ 15</span><button class="add">+</button></div>
          </div>
        </article>
      </div>

      <!-- sidebar selezione -->
      <aside class="sidebar">
        <h3>Prodotti selezionati</h3>
        <div class="cart-item"><span class="ph thumb"></span><div><b>Casco portiere</b><br><small>Taglia M · 1×</small></div></div>
        <div class="cart-item"><span class="ph thumb"></span><div><b>Bastone composito</b><br><small>Senior · 1×</small></div></div>
        <div class="sidebar__total"><span>Totale</span><span>€ 209</span></div>
        <a class="btn btn--primary btn--block">Prenota (ritiro in club house)</a>

        <div class="helper">
          <b>Non sai cosa scegliere?</b>
          Scrivi direttamente all'allenatore per capire quale attrezzatura scegliere in base alle tue abilità.
          <div style="margin-top:10px"><a class="btn btn--ghost btn--block">Scrivi all'allenatore</a></div>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php include 'footer.php'?>