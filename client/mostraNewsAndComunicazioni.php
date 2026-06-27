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

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Bacheca della società</h1>
            <p>Visualizza ogni news e comunicazione societaria</p>
        </header>
    <!-- ---- tabella tutte le news ---- -->
    <div class="alg-mostra-news">
        <section class="dash-panel-form panel-mostra-news dash-formcard">
            <div class="dash-panel-head">
                <div class="dash-panel-head width-100">
                <h2><i class="fas fa-users"></i>Tutte le news divise per categoria</h2>
                <div class="filter">
                    <a href="?tipo=notizia" class="tab <?= $tipo === 'notizia' ? 'active' : '' ?>">Notizie</a>
                    <a href="?tipo=comunicazione" class="tab <?= $tipo === 'comunicazione' ? 'active' : '' ?>">Comunicazioni</a>
                </div>
            </div>
            </div>    
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
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
         <!-- sezione ultime news registrate -->
        <section class="dash-panel-info height-fit-content">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i> Le ultime news o comunicazioni registrate</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Descrizione</th>
                        <th>Data</th>
                        <th>Tipo di Comunicazione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ultimeNews as $nw): ?>
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

    </div>
        
    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
