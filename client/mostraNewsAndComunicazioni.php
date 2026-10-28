<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';

require_once __DIR__ . '/../DB/classes/News.php';

// dico al pc che sono l'amministratore
requireQuotaValida();
// mi dice chi sono, se admin o client
$user = currentUser();

   // filtro per tipo (vuoto = tutte)
    $tipo = trim($_GET['tipo'] ?? '');
    if($tipo !== ''){
        $news = News::findByTipo($tipo);     // 'notizia' o 'comunicazione'
    } else {
        $news = News::findAllNews();
    }

    $ultimeNews = array_slice(array_reverse($news), 0, 5);
?>
    <?php 
    
     $titoloPagina = 'News e Comunicazioni'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / News e comunicazioni</span>
        <h1>Bacheca della società</h1>
        <p>Notizie dal club e comunicazioni riservate ai soci.</p>
    </div>
    
</header>

<?php $filtri = ['' => 'Tutte', 'notizia' => 'Notizie', 'comunicazione' => 'Comunicazioni']; ?>

<div class="dash-grid-side">
    <!-- ---- tutte le pubblicazioni ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Tutte le pubblicazioni</h2>
            <nav class="segmented" aria-label="Filtra per tipo">
                <?php foreach ($filtri as $valore => $etichetta): ?>
                    <a href="<?= $valore === '' ? 'mostraNewsAndComunicazioni.php' : '?tipo=' . $valore ?>"
                        class="segmented__btn <?= $tipo === $valore ? 'is-active' : '' ?>"
                        <?= $tipo === $valore ? 'aria-current="true"' : '' ?>><?= $etichetta ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
        <ul class="item-list">
            <?php foreach (array_reverse($news) as $nw): ?>
                <li class="with-thumb" style="align-items: flex-start; gap: 20px; padding: 20px 24px">
                    <?php if (!empty($nw['image_path'])): ?>
                        <img src="<?= htmlspecialchars($nw['image_path']) ?>" alt="" style="width: 120px; height: 80px; object-fit: cover; border-radius: var(--radius-sm); flex-shrink: 0">
                    <?php else: ?>
                        <span class="thumb thumb--ph" style="width: 120px; height: 80px; font-size: 24px" aria-hidden="true"><i class="fa-regular fa-newspaper"></i></span>
                    <?php endif; ?>
                    <div style="display: flex; flex-direction: column; gap: 6px; min-width: 0">
                        <div class="item-list__row" style="justify-content: flex-start">
                            <?= pillNews($nw['tipo']) ?>
                            <span class="item-list__meta"><?= dataBreve($nw['data']) ?></span>
                        </div>
                        <h3 style="font-size: 17px; font-weight: 600"><?= htmlspecialchars($nw['title']) ?></h3>
                        <p class="item-list__text" style="margin: 0"><?= htmlspecialchars($nw['description']) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
            <?php if (empty($news)): ?>
                <li class="item-list__empty">Nessuna pubblicazione per questo filtro.</li>
            <?php endif; ?>
        </ul>
    </section>

    <!-- ---- ultime registrate ---- -->
    <section class="panel">
        <div class="panel__head">
            <h2>Da non perdere</h2>
        </div>
        <ul class="item-list item-list--bar">
            <?php foreach ($ultimeNews as $nw): ?>
                <li>
                    <span class="item-list__title" style="display: block"><?= htmlspecialchars($nw['title']) ?></span>
                    <span class="item-list__meta"><?= dataBreve($nw['data']) ?></span>
                </li>
            <?php endforeach; ?>
            <?php if (empty($ultimeNews)): ?>
                <li class="item-list__empty">Nessuna pubblicazione.</li>
            <?php endif; ?>
        </ul>
    </section>
</div>

<?php include __DIR__ . '/../admin/footerDashboard.php'; ?>
