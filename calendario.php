<?php

  require_once __DIR__ . '/DB/classes/News.php';

// le ultime 3 news/comunicazioni
// findAllNews è in ordine crescente (vecchie → nuove): inverto e prendo le prime 3
$newsOnSite = array_slice(array_reverse(News::findAllNews()), 0, 3);

// immagine dedicata in base al titolo della news 
$immaginiNews = [
    "L'Under 19 conquista la vetta della classifica" => '/Gestionale-Hockey/assets/img/img-calendario-1.png',
    'Vittoria in rimonta della Prima Squadra'        => '/Gestionale-Hockey/assets/img/img-calendario-2.png',
    'Andrea Conti firma una tripletta'               => '/Gestionale-Hockey/assets/img/img-calendario-3.png',
];

?>

<?php require_once __DIR__ . '/DB/data/datiFittizi.php'; ?>

<?php include __DIR__ . '/includes/header.php'?>

<!-- ============ SEZIONE 1 — News ============ -->
<section class="sec sec--white">
  <div class="container">
    <h2>News & informazioni</h2>
    <p class="sub">Informazioni principali per il club e non solo.</p>

    <div class="news-grid">
      <?php foreach($newsOnSite as $n): ?>
        <article class="news">
          <?php if(isset($immaginiNews[$n['title']])): ?>
            <img class="img-news-calendario" src="<?= $immaginiNews[$n['title']] ?>"
                 alt="<?= htmlspecialchars($n['title']) ?>">
          <?php elseif(!empty($n['image_path'])): ?>
            <img class="img-news-calendario" src="<?= htmlspecialchars($n['image_path']) ?>"
                 alt="<?= htmlspecialchars($n['title']) ?>">
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
    <div class="results-row">

      <!-- Risultati Squadre -->
      <div class="results-col">
      <h2>Risultati Squadre</h2>

      <div class="categories" >
        <button class="category active" data-tab="primaSquadra">Prima squadra</button>
        <button class="category" data-tab="under19">Under 19</button>
        <button class="category" data-tab="under15">Under 15</button>
        <button class="category" data-tab="under13">Under 13</button>
      </div> 
      
      <!-- PRIMA SCQUADRA -->

      <div class="tab-panel" data-panel="primaSquadra">
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Posizione</th>
                <th>Squadra</th>
                <th>Partite Totali</th>
                <th>Gol Fatti</th>
                <th>Gol Subiti</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="pos"><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($squadre[$i]) ?></td>
              <td><?= rand(1, 35) ?></td>
              <td><?= rand(0, 40) ?></td>
              <td><?= rand(0, 30) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
      <!-- under19 -->
     <div class="tab-panel" data-panel="under19" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Posizione</th>
                <th>Squadra</th>
                <th>Partite Totali</th>
                <th>Gol Fatti</th>
                <th>Gol Subiti</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="pos"><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($squadre[$i]) ?></td>
              <td><?= rand(1, 30) ?></td>
              <td><?= rand(0, 50) ?></td>
              <td><?= rand(0, 50) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
      
      <!-- under15 -->
     <div class="tab-panel" data-panel="under15" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Posizione</th>
                <th>Squadra</th>
                <th>Partite Totali</th>
                <th>Gol Fatti</th>
                <th>Gol Subiti</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="pos"><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($squadre[$i]) ?></td>
              <td><?= rand(1, 20) ?></td>
              <td><?= rand(0, 35) ?></td>
              <td><?= rand(0, 30) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
      
      <!-- under13 -->
     <div class="tab-panel" data-panel="under13" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Posizione</th>
                <th>Squadra</th>
                <th>Partite Totali</th>
                <th>Gol Fatti</th>
                <th>Gol Subiti</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="pos"><?= $i + 1 ?></td>
              <td><?= htmlspecialchars($squadre[$i]) ?></td>
              <td><?= rand(1, 15) ?></td>
              <td><?= rand(0, 25) ?></td>
              <td><?= rand(0, 25) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>          
      </div><!-- /results-col -->

      <!-- Classifica Del Campionato -->
      <div class="results-col">
    <h2>Classifica Del Campionato</h2>

    <div class="results" >
      <button class="result active" data-tab="prima_squadra">Prima squadra</button>
      <button class="result" data-tab="under_19">Under 19</button>
      <button class="result" data-tab="under_15">Under 15</button>
      <button class="result" data-tab="under_13">Under 13</button>
    </div> 
    
    <!-- PRIMA SCQUADRA -->

      <div class="results-panel" data-panel="prima_squadra">
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Squadra Casa</th>
                <th>Risultato</th>
                <th>Squadra Ospite</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="team"><?= htmlspecialchars($squadre[$i]) ?></td>
              <td class="score"><?= rand(0,8) ?> - <?= rand(0,8) ?></td>
              <td class="team"><?= htmlspecialchars($squadre[$i + 6]) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- under19 -->
      <div class="results-panel" data-panel="under_19" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Squadra Casa</th>
                <th>Risultato</th>
                <th>Squadra Ospite</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="team"><?= htmlspecialchars($squadre[$i]) ?></td>
              <td class="score"><?= rand(0,8) ?> - <?= rand(0,8) ?></td>
              <td class="team"><?= htmlspecialchars($squadre[$i + 6]) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
      
      <!-- under15 -->
      <div class="results-panel" data-panel="under_15" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Squadra Casa</th>
                <th>Risultato</th>
                <th>Squadra Ospite</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="team"><?= htmlspecialchars($squadre[$i]) ?></td>
              <td class="score"><?= rand(0,8) ?> - <?= rand(0,8) ?></td>
              <td class="team"><?= htmlspecialchars($squadre[$i + 6]) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>
      
      <!-- under13 -->
      <div class="results-panel" data-panel="under_13" hidden>
        <div class="table"> 
        <table>
          <thead>
              <tr>
                <th>Squadra Casa</th>
                <th>Risultato</th>
                <th>Squadra Ospite</th>
              </tr>
          </thead>
          <tbody>
          <?php shuffle($squadre);?>
            
            <?php for($i = 0; $i < 10; $i++): ?>
            <tr>
              <td class="team"><?= htmlspecialchars($squadre[$i]) ?></td>
              <td class="score"><?= rand(0,8) ?> - <?= rand(0,8) ?></td>
              <td class="team"><?= htmlspecialchars($squadre[$i + 6]) ?></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>        

      </div><!-- /results-col -->
    </div><!-- /results-row -->
  </div><!-- /container -->
</section>

<?php include __DIR__ . '/includes/footer.php'?>
