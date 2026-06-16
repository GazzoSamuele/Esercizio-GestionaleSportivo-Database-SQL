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
    <span class="tag tag--vh">Sezione 1 · 100vh</span>
    <h2>News & informazioni</h2>
    <p class="sub">Informazioni principali per il club e non solo.</p>

    <div class="news-grid">
      <article class="news">
        <div class="ph"><small>IMG</small></div>
        <span class="badge">Trasferta</span>
        <h3>Trasferta categoria U15</h3>
        <p>Ritrovo, orari e indicazioni per la prossima trasferta.</p>
      </article>
      <article class="news">
        <div class="ph"><small>IMG</small></div>
        <span class="badge">Società</span>
        <h3>Riunione societaria</h3>
        <p>Convocazione e ordine del giorno della prossima riunione.</p>
      </article>
      <article class="news">
        <div class="ph"><small>IMG</small></div>
        <span class="badge">Attrezzatura</span>
        <h3>Nuova attrezzatura disponibile</h3>
        <p>Avviso sulle nuove forniture disponibili in club house.</p>
      </article>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Risultati & Classifica (100vh) ============ -->
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
