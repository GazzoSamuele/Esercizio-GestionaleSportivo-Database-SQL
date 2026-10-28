<?php

require_once __DIR__ . '/DB/classes/News.php';

// le ultime 3 news/comunicazioni
// findAllNews è in ordine crescente (vecchie → nuove): inverto e prendo le prime 3
$newsOnSite = array_slice(array_reverse(News::findAllNews()), 0, 3);

// immagine dedicata in base al titolo della news
$immaginiNews = [
  "L'Under 19 conquista la vetta della classifica" => BASE_URL . '/assets/img/img-calendario-1.png',
  'Vittoria in rimonta della Prima Squadra'        => BASE_URL . '/assets/img/img-calendario-2.png',
  'Andrea Conti firma una tripletta'               => BASE_URL . '/assets/img/img-calendario-3.png',
];

require_once __DIR__ . '/DB/data/datiFittizi.php';

// categorie mostrate in classifica/risultati: id => [etichetta, max partite, max gol fatti, max gol subiti]
$categorieRisultati = [
  'primaSquadra' => ['Prima squadra', 35, 40, 30],
  'under19'      => ['Under 19', 30, 50, 50],
  'under15'      => ['Under 15', 20, 35, 30],
  'under13'      => ['Under 13', 15, 25, 25],
];
?>

<?php include __DIR__ . '/includes/header.php' ?>

<section class="page-hero">
  <div class="wrap page-hero__inner">
    <nav class="breadcrumb-nav" aria-label="Percorso">
      <a href="<?= BASE_URL ?>/index.php">Home</a><span aria-hidden="true">/</span><span aria-current="page">Calendario</span>
    </nav>
    <h1>Calendario e risultati</h1>
    <p>Notizie dal club, classifiche e ultimi risultati di tutte le categorie, sempre aggiornati.</p>
  </div>
</section>

<!-- ============ SEZIONE 1 — News ============ -->
<section class="section">
  <div class="wrap">
    <div class="section-head">
      <h2>News e informazioni</h2>
      <p>Le notizie principali dal club e non solo.</p>
    </div>

    <?php if (empty($newsOnSite)): ?>
      <p class="field__hint">Nessuna news al momento.</p>
    <?php else: ?>
      <div class="grid-3">
        <?php foreach ($newsOnSite as $n): ?>
          <article class="card news-card">
            <?php if (isset($immaginiNews[$n['title']])): ?>
              <img class="news-card__img" src="<?= $immaginiNews[$n['title']] ?>" alt="">
            <?php elseif (!empty($n['image_path'])): ?>
              <img class="news-card__img" src="<?= htmlspecialchars($n['image_path']) ?>" alt="">
            <?php else: ?>
              <div class="news-card__ph" aria-hidden="true"><i class="fa-regular fa-newspaper"></i></div>
            <?php endif; ?>
            <div class="news-card__body">
              <div class="news-card__meta">
                <span class="pill <?= $n['tipo'] === 'notizia' ? 'pill--wait' : 'pill--ok' ?>"><?= htmlspecialchars(ucfirst($n['tipo'])) ?></span>
                <?php if (!empty($n['data'])): ?><span><?= date('d/m/Y', strtotime($n['data'])) ?></span><?php endif; ?>
              </div>
              <h3><?= htmlspecialchars($n['title']) ?></h3>
              <p><?= htmlspecialchars($n['description']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ============ SEZIONE 2 — Classifiche e risultati ============ -->
<section class="section section--white">
  <div class="wrap">
    <div class="section-head section-head--row">
      <div>
        <h2>Classifiche e risultati</h2>
        <p>Scegli la categoria per vedere classifica e ultime partite.</p>
      </div>
      <div class="segmented" role="tablist" aria-label="Categoria" data-tabs="categoria">
        <?php $primo = true; ?>
        <?php foreach ($categorieRisultati as $id => [$etichetta]): ?>
          <button type="button" role="tab" class="segmented__btn <?= $primo ? 'is-active' : '' ?>"
            data-tab="<?= $id ?>" aria-selected="<?= $primo ? 'true' : 'false' ?>"><?= $etichetta ?></button>
          <?php $primo = false; ?>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="grid-2">
      <!-- Classifica -->
      <div class="board-col">
        <h3>Classifica · <span data-tab-label="categoria">Prima squadra</span></h3>

        <?php $primo = true; ?>
        <?php foreach ($categorieRisultati as $id => [$etichetta, $maxPartite, $maxFatti, $maxSubiti]): ?>
          <div class="table-card" data-panel-group="categoria" data-panel="<?= $id ?>" <?= $primo ? '' : 'hidden' ?>>
            <div class="table-scroll">
              <table class="data-table">
                <caption class="sr-only">Classifica <?= $etichetta ?></caption>
                <thead>
                  <tr>
                    <th scope="col">Pos.</th>
                    <th scope="col">Squadra</th>
                    <th scope="col" class="num"><abbr title="Partite giocate">PG</abbr></th>
                    <th scope="col" class="num"><abbr title="Gol fatti">GF</abbr></th>
                    <th scope="col" class="num"><abbr title="Gol subiti">GS</abbr></th>
                  </tr>
                </thead>
                <tbody>
                  <?php shuffle($squadre); ?>
                  <?php for ($i = 0; $i < 10; $i++): ?>
                    <tr>
                      <td class="num" style="text-align:left"><?= $i + 1 ?></td>
                      <td class="strong"><?= htmlspecialchars($squadre[$i]) ?></td>
                      <td class="num"><?= rand(1, $maxPartite) ?></td>
                      <td class="num"><?= rand(0, $maxFatti) ?></td>
                      <td class="num"><?= rand(0, $maxSubiti) ?></td>
                    </tr>
                  <?php endfor; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php $primo = false; ?>
        <?php endforeach; ?>
      </div>

      <!-- Ultimi risultati -->
      <div class="board-col">
        <h3>Ultimi risultati · <span data-tab-label="categoria">Prima squadra</span></h3>

        <?php $primo = true; ?>
        <?php foreach ($categorieRisultati as $id => [$etichetta]): ?>
          <div class="table-card" data-panel-group="categoria" data-panel="<?= $id ?>" <?= $primo ? '' : 'hidden' ?>>
            <div class="table-scroll">
              <table class="data-table">
                <caption class="sr-only">Ultimi risultati <?= $etichetta ?></caption>
                <thead>
                  <tr>
                    <th scope="col" class="num">Casa</th>
                    <th scope="col" class="center">Risultato</th>
                    <th scope="col">Ospite</th>
                  </tr>
                </thead>
                <tbody>
                  <?php shuffle($squadre); ?>
                  <?php for ($i = 0; $i < 10; $i++): ?>
                    <tr>
                      <td class="strong num"><?= htmlspecialchars($squadre[$i]) ?></td>
                      <td class="center"><span class="score"><?= rand(0, 8) ?> – <?= rand(0, 8) ?></span></td>
                      <td class="strong"><?= htmlspecialchars($squadre[$i + 6]) ?></td>
                    </tr>
                  <?php endfor; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php $primo = false; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
