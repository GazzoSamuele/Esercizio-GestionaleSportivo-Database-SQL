<?php
require_once __DIR__ . '/DB/helpers/auth.php';
// devi essere loggato per vedere questa pagina 
requireLogin();   
?>

<?php include __DIR__ . '/includes/header.php' ?>

<section class="auth">
  <div class="wrap">
    <div class="card message-card">
      <span class="icon-tile"><i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i></span>
      <h1>Quota societaria scaduta</h1>
      <p>La tua quota societaria è scaduta, quindi l'accesso all'area riservata è sospeso.</p>
      <div class="bar-note">
        <strong>Come riattivarla</strong>
        <span>Passa in club house e salda la quota: un responsabile della società rinnoverà il tuo accesso.</span>
      </div>
      <div class="hero__cta">
        <a href="<?= BASE_URL ?>/logout.php" class="button button--ink button--lg">Esci</a>
        <a href="<?= BASE_URL ?>/contatti.php" class="button button--line button--lg">Contatta il club</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php' ?>
