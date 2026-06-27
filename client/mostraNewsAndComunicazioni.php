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

?>
    <?php 
    
     $titoloPagina = 'News e Comunicazioni'; 

    include __DIR__ . '/../admin/headerDashboard.php'; ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bacheca della società</h1>
            <p>Visualizza ogni news e comunicazione societaria</p>
        </header>

        <!-- ---- tabella news ---- -->
        <section class="dash-panel panel-mid-0-auto">
            <div class="dash-panel-head">
                <div class="filter">
                    <a href="mostraNewsAndComunicazioni.php" class="tab <?= $tipo === '' ? 'active' : '' ?>">Tutte</a>
                    <a href="?tipo=notizia" class="tab <?= $tipo === 'notizia' ? 'active' : '' ?>">Notizie</a>
                    <a href="?tipo=comunicazione" class="tab <?= $tipo === 'comunicazione' ? 'active' : '' ?>">Comunicazioni</a>
                </div>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <!-- <th>Immagine</th> -->
                        <th>Descrizione</th>
                        <th>Data</th>
                        <th>Tipo di Comunicazione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($news as $nw): ?>
                    <tr>
                        <!-- prendo l'id dell'utente singolo -->
                        <td class="text-align-center">#<?= (int) $nw['id'] ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($nw['title']) ?></td>

                        <!-- MOSTRA IMMAGINE(se non c'è, viene mostrato un fallback) -->
                        <!-- <td>
                            <?php if(!empty($nw['image_path'])): ?>
                                <img src="<?= htmlspecialchars($nw['image_path']) ?>" alt="<?= htmlspecialchars($nw['title']) ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <span class="dash-current">—</span>
                            <?php endif; ?>
                        </td> -->

                        <td class="td-width"><?= htmlspecialchars($nw['description']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($nw['data']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($nw['tipo']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
