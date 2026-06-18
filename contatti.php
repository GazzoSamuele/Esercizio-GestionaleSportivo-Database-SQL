<?php include 'header.php'?>

<section class="pagehead">
  <div class="container">
    <span class="tag">Contatti</span>
    <h1>Contatti & iscrizione</h1>
  </div>
</section>

<section class="sec sec--white">
  <div class="container">
    <div class="forms">

      <!-- FORM 1 — Richiesta informazioni -->
      <div class="formcard">
        <h2>Richiedi informazioni</h2>
        <p class="sub">Compila il form per ricevere informazioni sull'iscrizione.</p>

        <div class="field"><label>Nome e cognome</label><input type="text" placeholder="Mario Rossi"></div>
        <div class="field"><label>Email</label><input type="email" placeholder="mario@email.com"></div>
        <div class="field"><label>Telefono</label><input type="tel" placeholder="+39 ..."></div>
        <div class="field"><label>Categoria di interesse</label>
          <select><option>Prima squadra</option><option>Under 19</option><option>Under 15</option><option>Under 13</option></select>
        </div>
        <div class="field"><label>Messaggio</label><textarea placeholder="Scrivi la tua richiesta..."></textarea></div>
        <a class="btn btn--primary btn--block">Invia richiesta</a>
      </div>

      <!-- FORM 2 — Registrazione al club (con codice) -->
      <div class="formcard is-locked">
        <h2>Registrati al club</h2>
        <p class="sub">Accesso all'area personale del sito.</p>

        <div class="cost">
          <h4>Quota societaria — cosa è incluso</h4>
          <ul>
            <li>Calendario completo (collegabile a Google Calendar)</li>
            <li>Avvisi trasferte e riunioni societarie</li>
            <li>Schede di allenamento del preparatore atletico</li>
            <li>Gestione del pagamento della quota annuale</li>
            <li>Avvisi sulla nuova attrezzatura</li>
          </ul>
        </div>

        <div class="code-banner">
          🔒 Per registrarti serve il codice univoco rilasciato dalla società dopo il pagamento della quota.
        </div>

        <div class="field"><label>Codice univoco</label><input type="text" placeholder="ES. HC-2026-XXXX"></div>
        <div class="field"><label>Nome e cognome del giocatore</label><input type="text" placeholder="Nome giocatore"></div>
        <div class="field"><label>Email</label><input type="email" placeholder="email@email.com"></div>
        <div class="field"><label>Password</label><input type="password" placeholder="••••••••"></div>
        <a class="btn btn--accent btn--block">Verifica codice e registrati</a>
        <p class="note" style="text-align:center">Non hai il codice? Paga prima la quota societaria.</p>
      </div>

    </div>
  </div>
</section>

<?php include 'footer.php'?>