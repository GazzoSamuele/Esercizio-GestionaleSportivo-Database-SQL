<?php require_once __DIR__ . '/DB/data/datiFittizi.php'; ?>
<?php
require_once __DIR__ . '/DB/classes/Products.php';
// prendo i prodotti per mostrare le loro foto nei riquadri dello shop
$shopProdotti = Products::findAllProducts();

// stampa la foto di un prodotto reale nel riquadro; se manca, lascia il placeholder grigio
function shopThumb(array $prodotti, int $i): void {
    if (!empty($prodotti[$i]['image_path'])) {
        echo '<img class="thumb" src="' . htmlspecialchars($prodotti[$i]['image_path'])
           . '" alt="' . htmlspecialchars($prodotti[$i]['name'] ?? '') . '">';
    } else {
        echo '<span class="ph thumb"></span>';
    }
}
?>

<?php include __DIR__ . '/includes/header.php'?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="container_hero">
    <video autoplay loop muted playsinline class="video-background">
    <source src="/Gestionale-Hockey/assets/video/hero-sport.mp4" type="video/mp4">
  </video>
    <div class="hero_content">
      <h1>Gestionale società sportiva</h1>
      <p class="lead">Informa, fa conoscere e rende più facile l'accesso ai risultati delle partite per chi fa parte del club.</p>
      <div class="minicards">
        
        <a href="#storia"><div class="minicard"><b>Storia</b></div></a>
        <a href="#servizi"><div class="minicard"><b>Servizi</b></div></a>
        <a href="#palmares"><div class="minicard"><b>Palmarès</b></div></a>
        <a href="#shop"><div class="minicard"><b>Shop</b></div></a>

      </div>
      <div class="hero__cta">
        <a href="#servizi" class="btn btn--primary">Scopri i servizi</a>
        <a href="contatti.php" class="btn btn--accent">Contattaci</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 1 — Società ============ -->
<section class="sec sec--white" id="storia">
  <div class="container_sez1">
    <div class="main-title-sez1">
      <h2>La nostra società</h2>
    <p class="sub">Rappresentazione della storia della società e del club, dagli albori ai giorni nostri</p>
    </div>
    
      <!-- testo a sinistra, immagine a destra -->
      <div class="split">
        <div class="split__text">
          <h3>Storia società</h3>
          <p>Racconto delle origini del club, dei valori e dei traguardi raggiunti negli anni. Testo modificabile.</p>
        </div>
        <img class="split__img" src="/Gestionale-Hockey/assets/img/storia-club-index.png" alt="Storia della società">
      </div>

      <!-- invertita: immagine a sinistra, testo a destra -->
      <div class="split split--reverse">
        <div class="split__text">
          <h3>Club house</h3>
          <p>Descrizione degli spazi, dei servizi e del punto di ritrovo della società. Qui avviene anche il ritiro dei prodotti acquistati.</p>
        </div>
        <img class="split__img" src="/Gestionale-Hockey/assets/img/club-house.png" alt="Club house della società">
      </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Servizi ============ -->
<section class="sec sec--bg" id="servizi">
  <div class="container">
    <h2>I servizi che offriamo</h2>
    <p class="sub">Descrizione di tutti i servizi disponibili nel club, con collegamento alla pagina completa di riferimento</p>

    <div class="grid-3">
      <div class="svc">
        <span class="icon">📊</span>
        <h3>Risultati & calendario</h3>
        <p>Risultati recenti e calendario delle categorie di giocatori, sempre aggiornati.</p>
        <a class="link" href="calendario.php">Vai al Calendario →</a>
      </div>
      
      <div class="svc">
        <span class="icon">📝</span>
        <h3>Iscrizione squadre</h3>
        <p>Iscrizione alle squadre in base all'età, con accesso all'area personale.</p>
        <a class="link" href="contatti.php">Vai ai Contatti →</a>
      </div>

      <div class="svc">
        <span class="icon">🛒</span>
        <h3>Vendita prodotti</h3>
        <p>Attrezzatura per portieri, bastoni e protezioni. Pagamento in club house.</p>
        <a class="link" href="prodotti.php">Vai ai Prodotti →</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 3 — Palmarès  ============ -->
<section class="sec sec--palmares" id="palmares">
  <div class="container">
    <h2>Palmarès della società</h2>

    <div class="tabs">
      <button class="tab active" data-tab="generali">Risultati generali</button>
      <button class="tab" data-tab="attuali">Risultati attuali</button>
      <button class="tab" data-tab="giocatori">Giocatori iscritti</button>
    </div>

    <!-- pannello 1 -->
    <div class="tab-panel" data-panel="generali">
      <div class="table">
        <table>
            <thead>
              <tr>
                <th>Stagione</th>
                <th>Categoria</th>
                <th>Risultato</th>
                <th>Note</th>
              </tr>
          </thead>
          <tbody>
          <?php for($i = 0; $i < 5; $i++): ?>
            <tr>
              <td><?= htmlspecialchars($stagioni[array_rand($stagioni)]) ?></td>
              <td><?= htmlspecialchars($categorie[array_rand($categorie)]) ?></td>
              <td><?= htmlspecialchars($risultati[array_rand($risultati)]) ?></td>
              <td><?= htmlspecialchars($note[array_rand($note)]) ?></td>
            </tr>
          <?php endfor; ?>
        </tbody>
      </table>
      </div>
    </div>

    <!-- pannello 2 -->
    <div class="tab-panel" data-panel="attuali" hidden>
      <div class="table">
        <table>
            <thead>
              <tr>
                <th>Stagione</th>
                <th>Categoria</th>
                <th>Risultato</th>
                <th>Note</th>
              </tr>
          </thead>
            <tbody>
            <?php for($i = 0; $i < 5; $i++): ?>
              <tr>
                <td><?= htmlspecialchars($stagioni[array_rand($stagioni)]) ?></td>
                <td><?= htmlspecialchars($categorie[array_rand($categorie)]) ?></td>
                <td><?= htmlspecialchars($risultati[array_rand($risultati)]) ?></td>
                <td><?= htmlspecialchars($note[array_rand($note)]) ?></td>
              </tr>
            <?php endfor; ?>
          </tbody>
      </table>
      </div>
    </div>

    <!-- pannello 3 -->
    <div class="tab-panel" data-panel="giocatori" hidden>
      <div class="table">
       <table>
            <thead>
              <tr>
                <th>Stagione</th>
                <th>Categoria</th>
                <th>Nome</th>
                <th>Ruolo</th>
              </tr>
          </thead>
          <tbody>
            <?php for($i = 0; $i < 5; $i++): ?>
              <tr>
                <td><?= htmlspecialchars($stagioni[array_rand($stagioni)]) ?></td>
                <td><?= htmlspecialchars($categorie[array_rand($categorie)]) ?></td>
                <td><?= htmlspecialchars($giocatori[array_rand($giocatori)]) ?></td>
                <td><?= htmlspecialchars($ruoli[array_rand($ruoli)]) ?></td>
              </tr>
            <?php endfor; ?>
          </tbody>
      </table>
      </div>
    </div>

    <div>
      <a href="calendario.php" class="btn btn--primary">Scopri di più nella sezione dedicata →</a>
    </div>
  </div>
</section>
<!-- ============ SEZIONE 4 — Shop ============ -->
<section class="sec sec-custom sec--dark" id="shop">
  <div class="container">
    <h2>Lo shop del club</h2>
    <p class="sub-shop-index ">La nostra sezione dove poter scegliere il prodotto preferito e farlo arrivare direttamente al club</p>

    <div class="shop">
      <div class="shop__list">
        <div class="shop__item"><b>Attrezzatura d'allenamento</b></div>
        <div class="shop__item"><b>Attrezzatura giocatore</b></div>
        <div class="shop__item"><b>Attrezzatura portieri</b></div>
        <div class="shop__item"><b>Borsoni della società</b></div>
        
      </div>
      <div class="accordion">

        <div class="acc">
          <button type="button" class="acc__head"> Come avviene il pagamento?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Il pagamento avviene in club house dopo aver ritirato i prodotti acquistati.</p>
          </div>
        </div>

        <div class="acc">
          <button type="button" class="acc__head">Dove ritiro i prodotti? 
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body"><p>Direttamente presso la club house della società.</p></div>
       </div>

        <div class="acc">
          <button type="button" class="acc__head"> Quali categorie di prodotti vendete?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Solo attrezzatura portieri, bastoni e protezioni. La sezione per l'abbigliamento sportivo l'aggiungeremo in seguito</p>
          </div>
        </div>

        <div class="acc">
          <button type="button" class="acc__head">Come scelgo la taglia giusta?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body"><p>Puoi venire direttamente al club a testare il tuo prodotto durente un allenamento oppure puoi scrivere all'allenatore per avere maggiori informazioni sull'acquisto</p></div>
       </div>

    </div>

    <div>
      <a href="prodotti.php" class="btn btn--accent">Scopri di più nella sezione dedicata →</a>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 5 — Contatti  ============ -->
<section class="sec sec-custom sec--white" id="iscrizione">
  <div class="container">
    <h2>Entra a far parte del club</h2>
    <p class="sub">Iscriviti e accedi a tutti gli strumenti riservati ai soci.</p>

    <div class="benefits-grid">
      <div class="svc">
        <span class="icon">📊</span>
        <h3>Dashboard personale</h3>
        <p>Statistiche, grafici e la tua area riservata, sempre aggiornata.</p>
      </div>
      <div class="svc">
        <span class="icon">🛒</span>
        <h3>Ordini diretti al club</h3>
        <p>Acquisti i prodotti online e li ritiri comodamente in club house.</p>
      </div>
      <div class="svc">
        <span class="icon">📅</span>
        <h3>Calendario dinamico</h3>
        <p>Partite, allenamenti ed eventi della società sempre a portata di mano.</p>
      </div>
      <div class="svc">
        <span class="icon">📰</span>
        <h3>News &amp; comunicazioni</h3>
        <p>Resta aggiornato su avvisi e novità ufficiali del club.</p>
      </div>
    </div>

    <div>
      <a href="contatti.php" class="btn btn--primary">Scopri di più nella sezione dedicata →</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'?>