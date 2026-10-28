<?php

//  PRENDO i dati che mi servono
require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Purchases.php';

requireQuotaValida();
$user = currentUser();                  // utente loggato (id, name, role)
$iniziale = strtoupper(substr($user['name'], 0, 1));

$ordini = Purchases::findByUser($user['id']);

?>
    <?php 
    
     $titoloPagina = 'Ordini Effettuati'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

<header class="dash-head">
    <div class="dash-head__text">
        <span class="dash-head__crumb">Area riservata / I miei ordini</span>
        <h1>I miei ordini</h1>
        <p>Tutti i prodotti prenotati e il loro stato di ritiro.</p>
    </div>
    <div class="dash-head__actions"><a class="button button--ink button--sm" href="<?= BASE_URL ?>/prodotti.php"><i class="fa-solid fa-plus" aria-hidden="true"></i> Nuovo ordine</a></div>
</header>

<?php
$totaleSpeso = array_sum(array_map(fn($o) => (float) $o['prezzo_pagato'], $ordini));
$prontiRitiro = count(array_filter($ordini, fn($o) => !empty($o['pronto_ritiro'])));
?>

<div class="stat-strip">
    <div class="stat"><span class="stat__value"><?= count($ordini) ?></span><span class="stat__label">Ordini effettuati</span></div>
    <div class="stat"><span class="stat__value">€ <?= number_format($totaleSpeso, 2, ',', '.') ?></span><span class="stat__label">Totale speso</span></div>
    <div class="stat"><span class="stat__value"><?= $prontiRitiro ?></span><span class="stat__label">Pronti al ritiro</span></div>
</div>

<section class="panel">
    <div class="panel__head">
        <h2>Storico ordini</h2>
    </div>
    <div class="table-scroll">
        <table class="data-table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Prodotto</th>
                    <th scope="col" class="num">Importo</th>
                    <th scope="col">Pagamento</th>
                    <th scope="col">Ritiro</th>
                    <th scope="col">Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ordini as $ord): ?>
                    <tr>
                        <td>#<?= (int) $ord['id'] ?></td>
                        <td>
                            <span class="with-thumb"><?= miniatura($ord['immagine'] ?? null, 'fa-bag-shopping') ?><span class="strong"><?= htmlspecialchars($ord['prodotto']) ?></span></span>
                        </td>
                        <td class="num em">€ <?= number_format((float) $ord['prezzo_pagato'], 2, ',', '.') ?></td>
                        <td><?= pillStato($ord['status']) ?></td>
                        <td>
                            <?= !empty($ord['pronto_ritiro'])
                                ? '<span class="pill pill--ok">Pronto al ritiro</span>'
                                : '<span class="pill pill--wait">In preparazione</span>' ?>
                        </td>
                        <td style="white-space: nowrap"><?= dataBreve($ord['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($ordini)): ?>
                    <tr><td colspan="6" class="center">Nessun ordine effettuato.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<div class="bar-note" style="background: #fff; border: 1px solid var(--line); border-left: var(--bar); border-radius: var(--radius); padding: 16px 20px;">
    <span><strong>Ritiro e pagamento in club house</strong> · negli orari di apertura, con l'assistenza dello staff.</span>
</div>

<?php include __DIR__ . '/../admin/footerDashboard.php'; ?>
