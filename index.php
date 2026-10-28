<?php require_once __DIR__ . '/DB/data/datiFittizi.php'; ?>
<?php
require_once __DIR__ . '/DB/classes/Products.php';
// prendo i prodotti per mostrare le loro foto nei riquadri dello shop
$shopProdotti = Products::findAllProducts();

// stampa la foto di un prodotto reale nel riquadro; se manca, lascia il placeholder grigio
function shopThumb(array $prodotti, int $i): void
{
  if (!empty($prodotti[$i]['image_path'])) {
    echo '<img class="thumb" src="' . htmlspecialchars($prodotti[$i]['image_path'])
      . '" alt="' . htmlspecialchars($prodotti[$i]['name'] ?? '') . '">';
  } else {
    echo '<span class="ph thumb"></span>';
  }
}
?>

<?php include __DIR__ . '/includes/header.php' ?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="container_hero">
    <video autoplay loop muted playsinline class="video-background">
      <source src="<?= BASE_URL ?>/assets/video/hero-sport.mp4" type="video/mp4">
    </video>
    <div class="hero_content">
      <h1>Gestionale società sportiva</h1>
      <p class="lead">Informa, fa conoscere e rende più facile l'accesso ai risultati delle partite per chi fa parte del club.</p>
      <!-- <div class="minicards">
        
        <a href="#storia"><div class="minicard"><b>Storia</b></div></a>
        <a href="#servizi"><div class="minicard"><b>Servizi</b></div></a>
        <a href="#palmares"><div class="minicard"><b>Palmarès</b></div></a>
        <a href="#shop"><div class="minicard"><b>Shop</b></div></a>

      </div> -->
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
    </div>

    <div class="split">
      <div class="split__text">
        <h3>Le Origini</h3>
        <p>Tutto ha inizio nel 1985, quando un gruppo di appassionati decide di fondare l'Olimpia Sport Club.
          L'obiettivo era semplice ma ambizioso: creare un punto di riferimento per lo sport sul territorio e promuovere uno stile di vita sano e condiviso.</p>

        <h3>La Crescita</h3>
        <p>Anno dopo anno, il club è cresciuto fino a contare oltre 350 iscritti e uno staff tecnico altamente qualificato.
          Le vittorie sul campo e il consolidamento del settore giovanile hanno trasformato l'entusiasmo iniziale in una realtà solida e organizzata.</p>

        <h3>Oggi</h3>
        <p>Oggi l'Olimpia Sport Club è molto più di una società sportiva: è una grande famiglia basata su rispetto, inclusione e lealtà.
          Il viaggio continua, pronti a scrivere insieme le prossime pagine di questa storia.</p>
      </div>
      <img class="split__img" src="<?= BASE_URL ?>/assets/img/storia-club-index.png" alt="Storia della società">
    </div>

    <div class="split split--reverse">
      <div class="split__text">
        <h3>Il Cuore del Club</h3>
        <p>La nostra Club House è molto più di una struttura: è il punto di ritrovo ufficiale per atleti, famiglie e tifosi dell'Olimpia Sport Club.
          Uno spazio accogliente e informale pensato per rilassarsi, condividere la passione per lo sport e vivere la vita del club anche fuori dal campo.</p>

        <h3>Spazi e Servizi</h3>
        <ul style="list-style: none">
          <li><strong>Area Relax & Ristoro:</strong> Un bar interno attrezzato per colazioni, spuntini post-allenamento o per seguire insieme le partite.</li>
          <li><strong>Zona Studio e Smart Working:</strong> Tavoli con connessione Wi-Fi gratuita, ideali per i ragazzi che aspettano gli allenamenti o per i genitori.</li>
          <li><strong>Eventi e Riunioni:</strong> Una sala polifunzionale per assemblee, compleanni e serate sociali.</li>
        </ul>
        <h3>Punto Ritiro Store</h3>
        <p>Qui si trova anche la zona dedicata al ritiro dei prodotti acquistati online. Che si tratti del kit ufficiale da gioco,
          dell'abbigliamento da allenamento o del merchandising del club, il ritiro è rapido, gratuito e assistito dal nostro staff negli orari di apertura.</p>
      </div>
      <img class="split__img" src="<?= BASE_URL ?>/assets/img/club-house.png" alt="Club house della società">
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
            <?php for ($i = 0; $i < 5; $i++): ?>
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
            <?php for ($i = 0; $i < 5; $i++): ?>
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
            <?php for ($i = 0; $i < 5; $i++): ?>
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
          <button type="button" class="acc__head" aria-expanded="false"> Come avviene il pagamento?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Il pagamento avviene in club house dopo aver ritirato i prodotti acquistati.</p>
          </div>
        </div>

        <div class="acc">
          <button type="button" class="acc__head" aria-expanded="false">Dove ritiro i prodotti?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Direttamente presso la club house della società.</p>
          </div>
        </div>

        <div class="acc">
          <button type="button" class="acc__head" aria-expanded="false"> Quali categorie di prodotti vendete?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Solo attrezzatura portieri, bastoni e protezioni. La sezione per l'abbigliamento sportivo l'aggiungeremo in seguito</p>
          </div>
        </div>

        <div class="acc">
          <button type="button" class="acc__head" aria-expanded="false">Come scelgo la taglia giusta?
            <span class="acc__icon">+</span>
          </button>
          <div class="acc__body">
            <p>Puoi venire direttamente al club a testare il tuo prodotto durente un allenamento oppure puoi scrivere all'allenatore per avere maggiori informazioni sull'acquisto</p>
          </div>
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

<?php include __DIR__ . '/includes/footer.php' ?>