<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';

require_once __DIR__ . '/../DB/classes/News.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

    $errors = [];
    $success = '';

    //GESTIONE FORM

    // FUNZIONE CHE CREA UNA NUOVA NEWS
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_path = $_POST['image_path'] ?? '';
        $tipo = $_POST['tipo'] ?? 'notizia';
        $data = $_POST['data'] ?? date('Y-m-d');

        // validazione degli input

        //titolo
        if($title === ''){
            $errors[] = "Il titolo è obbligatorio";
        }
        
        //descrizione
        if($description === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        //tipo
        if(!in_array($tipo, ['notizia', 'comunicazione'], true)){
            $errors[] = 'Tipo non valido';
        }

        // se non ho l'array di errors popolato,
        // procedo con la creazione di una nuova news

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            News::createNews($title, $description, $data, $tipo, $image_path);

            $success = "News creata!";
            // una volta creato l'utente svuoto la post
            $_POST = [];
            }
    }
    

    // FUNZIONE CHE MODIFICA LA NEWS

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit'){
        
        // mettimo zero(in fondo) perchè è un int
        $id = (int) ($_POST['news_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $image_path = $_POST['image_path'] ?? '';
        $tipo = $_POST['tipo'] ?? 'notizia';
        $data = $_POST['data'] ?? date('Y-m-d');

        if($title === ''){
            $errors[] = "Il titolo è obbligatorio";
        }

        if($description === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        if(!in_array($tipo, ['notizia', 'comunicazione'], true)){
            $errors[] = 'Tipo non valido';
        }

        // se non ho l'array di errors popolato,
        // procedo con la creazione di un nuovo utente

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            News::updateNews($id, $title, $description, $data, $tipo, $image_path);

            $success = "News aggiornata!";
            // una volta creato l'utente svuoto la post
            $_POST = [];
        }
    }
    

    // FUNZIONE CHE ELIMINA LA NEWS

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete'){

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['news_id'] ?? 0);

    if(News::delete($targetID)){
        $success = "News eliminata!";
    }else{
        $errors[] = "Eliminazione news fallita!";
    }

    }

    $news = News::findAllNews();

?>
    <?php 
  
    $titoloPagina = 'Gestione News e Comunicazioni'; 
  
    include __DIR__ . '/headerDashboard.php'; 
    
   ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Gestione News e Comunicazioni Societarie</h1>
            <p>Crea una nuova news e comunicazione societaria</p>
        </header>

        <!-- messaggi di esito (prima venivano calcolati ma non mostrati) -->
        <?php if(!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <!-- ---- form nuovo utente ---- -->
        <section class="dash-panel dash-formcard">
            <div class="dash-panel-head">
                <h2><i class="fas fa-user-plus"></i>Nuova News o Comunicazione</h2>
            </div>
            <div class="dash-formbody">
                <!-- Serve per attivare la funzione di aggiunta utenti alla tabella tramite il form -->
                <form action="" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="name">Titolo</label>
                        <input type="text" class="form-control" id="name" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description">Descrizione</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="img">Immagine</label>
                        <input type="url" class="form-control" id="url_img" name="image_path" required>
                    </div>
                    <div class="mb-3">
                        <label for="role">News oppure Comunicazione Interna</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="notizia">News</option>
                            <option value="comunicazione">Comunicazione</option>
                        </select>
                    </div>
                    <button class="btn btn-warning" type="submit">Crea News</button>
                </form>
            </div>
        </section>

        <!-- ---- tabella news ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i> Tutte le News</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Immagine</th>
                        <th>Descrizione</th>
                        <th>Data</th>
                        <th>Tipo di Comunicazione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($news as $nw): ?>
                    <tr>
                        <!-- prendo l'id dell'utente singolo -->
                        <td>#<?= (int) $nw['id'] ?></td>

                        <td><?= htmlspecialchars($nw['title']) ?></td>

                        <!-- MOSTRA IMMAGINE(se non c'è, viene mostrato un fallback) -->
                        <td>
                            <?php if(!empty($nw['image_path'])): ?>
                                <img src="<?= htmlspecialchars($nw['image_path']) ?>" alt="<?= htmlspecialchars($nw['title']) ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <span class="dash-current">—</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($nw['description']) ?></td>

                        <td><?= htmlspecialchars($nw['data']) ?></td>

                        <td><?= htmlspecialchars($nw['tipo']) ?></td>

                        <td class="text-end">
                                <!-- PULSANTE DI EDIT -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-info" 
                                    title="Modifica comunicazione"
                                    data-bs-toggle="modal" data-bs-target="#modalEditNews<?= (int) $nw['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <?php include __DIR__ . '/modali/NewsEditModal.php'; ?>

                                <!-- PULSANTE DI DELETE -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Elimina comunicazione"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDeleteNews<?= (int) $nw['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                </button>
                                <?php include __DIR__ . '/modali/NewsDeleteModal.php'; ?>
                            </td>
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
