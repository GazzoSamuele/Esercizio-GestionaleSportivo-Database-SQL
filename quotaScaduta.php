<?php
require_once __DIR__ . '/DB/helpers/auth.php';
// devi essere loggato per vedere questa pagina 
requireLogin();   
?>

<?php include __DIR__ . '/includes/header.php'?>

<section class="sec sec--white">
  <h2>Quota societaria scaduta</h2>
  <div class="container">
    <div class="authbox">
      <p>La tua quota societaria è scaduta, quindi l'accesso all'area riservata è sospeso.</p>
      <p>Per riattivarla, passa in club house e salda la quota: un responsabile della società rinnoverà il tuo accesso.</p>
      <a href="logout.php" class="btn btn--primary btn--block">Esci</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'?>