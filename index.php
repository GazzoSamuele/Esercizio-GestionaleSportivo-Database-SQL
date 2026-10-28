<?php require_once __DIR__ . '/DB/data/datiFittizi.php'; ?>
<?php
require_once __DIR__ . '/DB/classes/Products.php';
// prendo i prodotti per mostrare le loro foto nei riquadri dello shop
$shopProdotti = Products::findAllProducts();

// tab del palmarès: id => [etichetta, intestazioni colonne]
$tabPalmares = [
  'generali'  => ['Risultati generali', ['Stagione', 'Categoria', 'Risultato', 'Competizione']],
  'attuali'   => ['Stagione in corso', ['Stagione', 'Categoria', 'Risultato', 'Competizione']],
  'giocatori' => ['Giocatori iscritti', ['Stagione', 'Categoria', 'Giocatore', 'Ruolo']],
];
?>

<?php include __DIR__ . '/includes/header.php' ?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="wrap hero__grid">
    <div class="hero__text">
      <h1>Il club, i risultati e la vita di squadra in un unico posto.</h1>
      <p class="hero__lead">Informa, fa conoscere e rende più facile l'accesso ai risultati delle partite per chi fa parte del club.</p>
      <div class="hero__cta">
        <a href="#servizi" class="button button--accent button--lg">Scopri i servizi <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        <a href="<?= BASE_URL ?>/contatti.php" class="button button--ghost button--lg">Contattaci</a>
      </div>
    </div>
    <img class="hero__img" src="<?= BASE_URL ?>/assets/img/storia-club-index.png" alt="La squadra dell'Olimpia Sport Club">
  </div>

  <div class="facts">
    <div class="wrap facts__grid">
      <div class="stat"><span class="stat__value">1985</span><span class="stat__label">Anno di fondazione</span></div>
      <div class="stat"><span class="stat__value">350+</span><span class="stat__label">Iscritti al club</span></div>
      <div class="stat"><span class="stat__value"><?= count($categorie) ?></span><span class="stat__label">Categorie, dai Pulcini alla Prima Squadra</span></div>
      <div class="stat"><span class="stat__value">Club House</span><span class="stat__label">Punto d'incontro e ritiro ordini</span></div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 1 — Società ============ -->
<section class="section" id="storia">
  <div class="wrap">
    <div class="intro">
      <h2>Dal 1985, un punto di riferimento per lo sport sul territorio.</h2>
      <p>Più di una società sportiva: una grande famiglia basata su rispetto, inclusione e lealtà, con uno staff tecnico qualificato e un settore giovanile in crescita.</p>
    </div>

    <div class="story">
      <img class="story__img" src="<?= BASE_URL ?>/assets/img/club-house.png" alt="La club house della società">
      <div class="timeline">
        <div class="timeline__item">
          <span class="timeline__year">1985</span>
          <div>
            <h3>Le origini</h3>
            <p>Un gruppo di appassionati fonda l'Olimpia Sport Club con un obiettivo semplice ma ambizioso: creare un punto di riferimento per lo sport sul territorio e promuovere uno stile di vita sano e condiviso.</p>
          </div>
        </div>
        <div class="timeline__item">
          <span class="timeline__year">350+</span>
          <div>
            <h3>La crescita</h3>
            <p>Anno dopo anno il club arriva a oltre 350 iscritti. Le vittorie sul campo e il settore giovanile trasformano l'entusiasmo iniziale in una realtà solida e organizzata.</p>
          </div>
        </div>
        <div class="timeline__item">
          <span class="timeline__year">Oggi</span>
          <div>
            <h3>La Club House</h3>
            <p>Il punto di ritrovo ufficiale per atleti, famiglie e tifosi: area relax e ristoro, zona studio con Wi-Fi gratuito, sala polifunzionale per eventi e il punto ritiro dello store.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Servizi ============ -->
<section class="section section--white" id="servizi">
  <div class="wrap">
    <div class="section-head">
      <h2>Tutto quello che il club mette a disposizione.</h2>
    </div>

    <div class="services">
      <div class="service">
        <span class="service__icon"><i class="fa-regular fa-calendar" aria-hidden="true"></i></span>
        <h3>Risultati e calendario</h3>
        <p>Risultati recenti e calendario di tutte le categorie, sempre aggiornati.</p>
        <a class="text-link" href="<?= BASE_URL ?>/calendario.php">Vai al calendario <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
      <div class="service">
        <span class="service__icon"><i class="fa-solid fa-user-plus" aria-hidden="true"></i></span>
        <h3>Iscrizione alle squadre</h3>
        <p>Iscrizione in base all'età, con accesso immediato all'area personale del socio.</p>
        <a class="text-link" href="<?= BASE_URL ?>/contatti.php">Vai ai contatti <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
      <div class="service">
        <span class="service__icon"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i></span>
        <h3>Vendita prodotti</h3>
        <p>Attrezzatura per portieri, bastoni e protezioni. Ordini online, pagamento in club house.</p>
        <a class="text-link" href="<?= BASE_URL ?>/prodotti.php">Vai ai prodotti <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 3 — Palmarès ============ -->
<section class="section" id="palmares">
  <div class="wrap">
    <div class="section-head section-head--row">
      <div>
        <h2>Risultati e rosa</h2>
      </div>
      <div class="segmented" role="tablist" aria-label="Sezioni palmarès" data-tabs="palmares">
        <?php $primo = true; ?>
        <?php foreach ($tabPalmares as $id => [$etichetta]): ?>
          <button type="button" role="tab" class="segmented__btn <?= $primo ? 'is-active' : '' ?>"
            data-tab="<?= $id ?>" aria-selected="<?= $primo ? 'true' : 'false' ?>"><?= $etichetta ?></button>
          <?php $primo = false; ?>
        <?php endforeach; ?>
      </div>
    </div>

    <?php $primo = true; ?>
    <?php foreach ($tabPalmares as $id => [$etichetta, $colonne]): ?>
      <div class="table-card" data-panel-group="palmares" data-panel="<?= $id ?>" <?= $primo ? '' : 'hidden' ?>>
        <div class="table-scroll">
          <table class="data-table">
            <caption class="sr-only"><?= $etichetta ?></caption>
            <thead>
              <tr>
                <?php foreach ($colonne as $col): ?>
                  <th scope="col"><?= $col ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php for ($i = 0; $i < 5; $i++): ?>
                <tr>
                  <td><?= htmlspecialchars($stagioni[array_rand($stagioni)]) ?></td>
                  <td class="strong"><?= htmlspecialchars($categorie[array_rand($categorie)]) ?></td>
                  <?php if ($id === 'giocatori'): ?>
                    <td class="em"><?= htmlspecialchars($giocatori[array_rand($giocatori)]) ?></td>
                    <td><?= htmlspecialchars($ruoli[array_rand($ruoli)]) ?></td>
                  <?php else: ?>
                    <td class="em"><?= htmlspecialchars($risultati[array_rand($risultati)]) ?></td>
                    <td><?= htmlspecialchars($note[array_rand($note)]) ?></td>
                  <?php endif; ?>
                </tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php $primo = false; ?>
    <?php endforeach; ?>

    <div>
      <a href="<?= BASE_URL ?>/calendario.php" class="button button--line">Calendario completo <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 4 — Shop ============ -->
<section class="section section--dark" id="shop">
  <div class="wrap">
    <div class="shop-band">
      <div class="shop-band__text">
        <h2>Ordina online, ritira in club house.</h2>
        <p>Scegli il prodotto e fallo arrivare direttamente al club. Nessuna spedizione, nessun pagamento online.</p>
        <ul class="link-list" aria-label="Categorie dello shop">
          <li><a href="<?= BASE_URL ?>/prodotti.php">Attrezzatura d'allenamento <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
          <li><a href="<?= BASE_URL ?>/prodotti.php">Attrezzatura giocatore <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
          <li><a href="<?= BASE_URL ?>/prodotti.php">Attrezzatura portieri <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
          <li><a href="<?= BASE_URL ?>/prodotti.php">Borsoni della società <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a></li>
        </ul>
        <div>
          <a href="<?= BASE_URL ?>/prodotti.php" class="button button--accent button--lg">Vai allo shop <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>

      <div class="shop-band__gallery">
        <figure>
          <img src="<?= BASE_URL ?>/assets/img/attrezzatura-allenamento.png" alt="Attrezzatura d'allenamento">
          <figcaption>Allenamento</figcaption>
        </figure>
        <figure>
          <img src="<?= BASE_URL ?>/assets/img/attrezzatura-portieri.png" alt="Attrezzatura per portieri">
          <figcaption>Portieri</figcaption>
        </figure>
      </div>
    </div>

    <div class="faq">
      <div>
        <h3>Come avviene il pagamento?</h3>
        <p>In club house, al momento del ritiro dei prodotti.</p>
      </div>
      <div>
        <h3>Dove ritiro i prodotti?</h3>
        <p>Direttamente presso la club house, negli orari di apertura.</p>
      </div>
      <div>
        <h3>Cosa vendete?</h3>
        <p>Attrezzatura portieri, bastoni e protezioni. L'abbigliamento sportivo arriverà in seguito.</p>
      </div>
      <div>
        <h3>Come scelgo la taglia?</h3>
        <p>Provala al club durante un allenamento o scrivi all'allenatore per un consiglio.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 5 — Iscrizione ============ -->
<section class="section" id="iscrizione">
  <div class="wrap">
    <div class="join">
      <div class="join__text">
        <h2>Entra a far parte del club.</h2>
        <p>Iscriviti e accedi a tutti gli strumenti riservati ai soci.</p>
        <div class="hero__cta">
          <a class="button button--ink button--lg" href="<?= BASE_URL ?>/contatti.php">Richiedi l'iscrizione</a>
          <a class="button button--line button--lg" href="<?= BASE_URL ?>/login.php">Ho già un account</a>
        </div>
      </div>
      <ul class="join__list check-list">
        <li><i class="fa-solid fa-check" aria-hidden="true"></i>
          <div>
            <h3>Dashboard personale</h3>
            <p>Statistiche, grafici e la tua area riservata, sempre aggiornata.</p>
          </div>
        </li>
        <li><i class="fa-solid fa-check" aria-hidden="true"></i>
          <div>
            <h3>Ordini diretti al club</h3>
            <p>Acquisti online e ritiri comodamente in club house.</p>
          </div>
        </li>
        <li><i class="fa-solid fa-check" aria-hidden="true"></i>
          <div>
            <h3>Calendario dinamico</h3>
            <p>Partite, allenamenti ed eventi sempre a portata di mano.</p>
          </div>
        </li>
        <li><i class="fa-solid fa-check" aria-hidden="true"></i>
          <div>
            <h3>News e comunicazioni</h3>
            <p>Avvisi e novità ufficiali della società.</p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
