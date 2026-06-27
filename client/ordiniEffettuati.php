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

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Lista degli miei ordini </h1>
            <p>Rappresentazione di tutti gli ordini effettuati </p>
        </header>

    <section class="dash-panel">
        <div class="dash-panel-head">
            <h2><i class="fas fa-receipt"></i> I miei ordini</h2>
        </div>
        <table class="dash-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Immagine</th>
                    <th>Prodotto</th>
                    <th>Prezzo</th>
                    <th>Stato</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ordini as $ord): ?>
                    <tr>
                        <td>#<?= (int) $ord['id'] ?></td>
                        <td>
                            <?php if(!empty($ord['immagine'])): ?>
                                <img src="<?= htmlspecialchars($ord['immagine']) ?>" alt="<?= htmlspecialchars($ord['prodotto']) ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <span class="dash-current">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ord['prodotto']) ?></td>
                        <td>€ <?= htmlspecialchars($ord['prezzo_pagato']) ?></td>
                        <td><?= htmlspecialchars($ord['status']) ?></td>
                        <td><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($ordini)): ?>
                    <tr><td colspan="6">Nessun ordine effettuato.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

</div>

</body>
</html>
