<?php include 'header.php'?>

<!-- ============ HERO ============ -->
<section class="hero">
  <div class="container_hero">
    <video autoplay loop muted playsinline class="video-background">
    <source src="hero-sport.mp4" type="video/mp4">
  </video>
    <div class="hero_content">
      <h1>Gestionale società sportiva</h1>
      <p class="lead">Informa, fa conoscere e rende più facile l'accesso ai risultati delle partite per chi fa parte del club.</p>
      <div class="minicards">
        <div class="minicard"><span class="icon">📖</span><b>Storia</b></div>
        <div class="minicard"><span class="icon">📅</span><b>Calendario risultati</b></div>
        <div class="minicard"><span class="icon">⚙️</span><b>Servizi</b></div>
        <div class="minicard"><span class="icon">🏆</span><b>Palmarès</b></div>
      </div>
      <div class="hero__cta">
        <a href="#servizi" class="btn btn--primary">Scopri i servizi</a>
        <a href="contatti.php" class="btn btn--ghost">Contattaci</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 1 — Società ============ -->
<section class="sec sec--white" id="storia">
  <div class="container">
    <h2>La nostra società</h2>
    <p class="sub">Breve descrizione della società con foto in alternanza.</p>

    <div class="split">
      <div class="ph split__img"><small>FOTO SOCIETÀ</small></div>
      <div>
        <h3>Storia società</h3>
        <p>Racconto delle origini del club, dei valori e dei traguardi raggiunti negli anni. Testo modificabile.</p>
      </div>
    </div>

    <div class="split">
      <div class="ph split__img"><small>FOTO CLUB HOUSE</small></div>
      <div>
        <h3>Club house</h3>
        <p>Descrizione degli spazi, dei servizi e del punto di ritrovo della società. Qui avviene anche il ritiro dei prodotti acquistati.</p>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 2 — Servizi ============ -->
<section class="sec sec--bg" id="servizi">
  <div class="container">
    <h2>I servizi che offriamo</h2>
    <p class="sub">Card con collegamento alla pagina di riferimento.</p>

    <div class="grid-3">
      <div class="svc">
        <span class="icon">📊</span>
        <h3>Risultati & calendario</h3>
        <p>Risultati recenti e calendario delle categorie di giocatori, sempre aggiornati.</p>
        <a class="link" href="calendario.php">Vai al Calendario →</a>
      </div>
      <div class="svc">
        <span class="icon">🛒</span>
        <h3>Vendita prodotti</h3>
        <p>Attrezzatura per portieri, bastoni e protezioni. Pagamento in club house.</p>
        <a class="link" href="prodotti.php">Vai ai Prodotti →</a>
      </div>
      <div class="svc">
        <span class="icon">📝</span>
        <h3>Iscrizione squadre</h3>
        <p>Iscrizione alle squadre in base all'età, con accesso all'area personale.</p>
        <a class="link" href="contatti.php">Vai ai Contatti →</a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 3 — Palmarès  ============ -->
<section class="sec sec--white" id="palmares">
  <div class="container">
    <h2>Palmarès della società</h2>
    <p class="sub">Tabella dinamica: si mostra solo la sezione che l'utente sceglie di vedere.</p>

    <div class="tabs">
      <button class="tab active">Risultati generali</button>
      <button class="tab">Risultati attuali</button>
      <button class="tab">Giocatori iscritti</button>
    </div>

    <div class="table">
      <table>
        <thead>
          <tr><th>Stagione</th><th>Categoria</th><th>Risultato</th><th>Note</th></tr>
        </thead>
        <tbody>
          <tr><td>2024/25</td><td>Prima squadra</td><td>1° posto</td><td>Campionato regionale</td></tr>
          <tr><td>2023/24</td><td>Under 19</td><td>2° posto</td><td>Play-off</td></tr>
          <tr><td>2022/23</td><td>Prima squadra</td><td>Finalista</td><td>Coppa</td></tr>
          <tr><td>2021/22</td><td>Under 15</td><td>1° posto</td><td>—</td></tr>
        </tbody>
      </table>
    </div>

    <div style="margin-top:24px">
      <a href="calendario.php" class="btn btn--primary">Scopri di più nella sezione dedicata →</a>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 4 — Shop ============ -->
<section class="sec sec--dark" id="shop">
  <div class="container">
    <h2>Lo shop del club</h2>
    <p class="sub">Piccolo e-commerce: ci si limita a vendere attrezzatura portieri, bastoni e protezioni. Pagamento in club house dopo il ritiro.</p>

    <div class="shop">
      <div class="shop__list">
        <div class="shop__item"><span class="ph thumb"></span><b>Attrezzatura portieri</b></div>
        <div class="shop__item"><span class="ph thumb"></span><b>Bastoni</b></div>
        <div class="shop__item"><span class="ph thumb"></span><b>Protezioni</b></div>
      </div>
      <div class="accordion">
        <details class="acc" open><summary>Come avviene il pagamento?</summary><p>Il pagamento avviene in club house dopo aver ritirato i prodotti acquistati.</p></details>
        <details class="acc"><summary>Dove ritiro i prodotti?</summary><p>Direttamente presso la club house della società.</p></details>
        <details class="acc"><summary>Quali categorie trattate?</summary><p>Solo attrezzatura portieri, bastoni e protezioni.</p></details>
        <details class="acc"><summary>Come scelgo la taglia giusta?</summary><p>Puoi scrivere all'allenatore dalla pagina Prodotti.</p></details>
      </div>
    </div>

    <div style="margin-top:28px">
      <a href="prodotti.php" class="btn btn--accent">Scopri di più nella sezione dedicata →</a>
    </div>
  </div>
</section>

<!-- ============ SEZIONE 5 — Contatti  ============ -->
<section class="sec sec--white" id="iscrizione">
  <div class="container">
    <h2>Entra a far parte del club</h2>
    <p class="sub">Spiegazione delle attività svolte dalla società e collegamento alla registrazione al club. La registrazione richiede un codice univoco rilasciato dopo il pagamento della quota.</p>
    <a href="contatti.php" class="btn btn--primary">Scopri di più nella sezione dedicata →</a>
  </div>
</section>

<?php include 'footer.php'?>