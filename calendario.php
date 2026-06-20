<?php
require_once __DIR__ . '/DB/classes/News.php';

// le ultime 3 news/comunicazioni
// findAllNews è in ordine crescente (vecchie → nuove): inverto e prendo le prime 3
$newsOnSite = array_slice(array_reverse(News::findAllNews()), 0, 3);
?>

<?php include 'header.php'?>

<!-- intestazione pagina -->
<section class="pagehead">
  <div class="container">
    <span class="tag">Calendario</span>
    <h1>Risultati & calendario</h1>
  </div>
</section>

<!-- ============ SEZIONE 1 — News (100vh) ============ -->
<section class="sec sec--white">
  <div class="container">
    <h2>News & informazioni</h2>
    <p class="sub">Informazioni principali per il club e non solo.</p>

    <div class="news-grid">
      <?php foreach($newsOnSite as $n): ?>
        <article class="news">
          <?php if(!empty($n['image_path'])): ?>
            <img src="<?= htmlspecialchars($n['image_path']) ?>"
                 alt="<?= htmlspecialchars($n['title']) ?>"
                 style="width:100%;height:140px;object-fit:cover;border-radius:8px;">
          <?php else: ?>
            <div class="ph"><small>IMG</small></div>
          <?php endif; ?>
            <span class="badge"><?= htmlspecialchars(ucfirst($n['tipo'])) ?></span>
            <h3><?= htmlspecialchars($n['title']) ?></h3>
            <p><?= htmlspecialchars($n['description']) ?></p>
          </article>
          <?php endforeach; ?>

          <?php if(empty($newsOnSite)): ?>
            <p class="note">Nessuna news al momento.</p>
        <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Risultati & Classifica ============ -->
<section class="sec sec--bg">
  <div class="container">
    <span class="tag tag--vh">Sezione 2 · 100vh</span>
    <h2>Partite & classifica</h2>
    <p class="sub">Tabella dinamica: a sinistra i risultati delle partite, a destra la classifica. Cambia in base alla categoria scelta.</p>

    <div class="filter">
      <span>Categoria:</span>
      <button class="tab active">Prima squadra</button>
      <button class="tab">Under 19</button>
      <button class="tab">Under 15</button>
      <button class="tab">Under 13</button>
    </div>

    <div class="two-col">
      <!-- risultati -->
      <div class="panel">
        <div class="panel__head">Risultati partite</div>
        <div class="match"><span class="team"><span class="dot"></span> Squadra Casa</span><span class="score">3 - 1</span><span class="team">Squadra Ospite <span class="dot"></span></span></div>
        <div class="match"><span class="team"><span class="dot"></span> Squadra Casa</span><span class="score">2 - 2</span><span class="team">Squadra Ospite <span class="dot"></span></span></div>
        <div class="match"><span class="team"><span class="dot"></span> Squadra Casa</span><span class="score">0 - 4</span><span class="team">Squadra Ospite <span class="dot"></span></span></div>
        <div class="match"><span class="team"><span class="dot"></span> Squadra Casa</span><span class="score">5 - 2</span><span class="team">Squadra Ospite <span class="dot"></span></span></div>
        <div class="match"><span class="team"><span class="dot"></span> Squadra Casa</span><span class="score">1 - 1</span><span class="team">Squadra Ospite <span class="dot"></span></span></div>
      </div>

      <!-- classifica -->
      <div class="panel">
        <div class="panel__head">Classifica campionato</div>
        <table class="standings">
          <thead><tr><th>#</th><th>Squadra</th><th>G</th><th>Pt</th></tr></thead>
          <tbody>
            <tr><td class="pos">1</td><td>Team A</td><td>12</td><td>27</td></tr>
            <tr><td class="pos">2</td><td>Team B</td><td>12</td><td>24</td></tr>
            <tr><td class="pos">3</td><td>Team C</td><td>12</td><td>21</td></tr>
            <tr><td class="pos">4</td><td>Team D</td><td>12</td><td>18</td></tr>
            <tr><td class="pos">5</td><td>Team E</td><td>12</td><td>15</td></tr>
            <tr><td class="pos">6</td><td>Team F</td><td>12</td><td>12</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <p class="note">Di default si mostrano i risultati e la classifica della prima squadra.</p>
  </div>
</section>

<?php include 'footer.php'?>
