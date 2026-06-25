<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';
//richiamo le funzioni degli users
require_once __DIR__ . '/../DB/classes/Products.php';

require_once __DIR__ . '/../DB/classes/Purchases.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

    $errors = [];
    $success = '';

    //GESTIONE FORM

    // FUNZIONE CHE CREA IL PRODOTTO
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){
        $name        = trim($_POST['name'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $prezzo      = trim($_POST['prezzo'] ?? '');
        $isActive    = (int) ($_POST['isActive'] ?? 1);
        $immagine    = trim($_POST['immagine'] ?? '');

        // validazione degli input

        //nome
        if($name === ''){
            $errors[] = "Il nome è obbligatorio";
        }

        // descrizione
        if($descrizione === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        // immagine
        if($immagine === ''){
            $errors[] = "L'immagine è obbligatoria";
        }

        //prezzo
        if($prezzo === '' || !is_numeric($prezzo)){
            $errors[] = "Prezzo non valido";
        }

        //disponibilità
        if(!in_array($isActive, [0,1], true)){
            $errors[] = 'Invalid disponibility';
        }

        // se non ho l'array di errors popolato,
        // procedo con la creazione di un nuovo utente

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            $newId = Products::create($name, $descrizione, $prezzo, $isActive, $immagine);

            if($newId === null){
                $errors[] = 'This product is already registered';
            }else{
                $success = "Prodotto creato!";

                // una volta creato l'utente svuoto la post
                $_POST = [];
            }
        }
    }

    // FUNZIONE CHE MODIFICA UN PRODOTTO

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit'){

        // mettimo zero(in fondo) perchè è un int
        $name        = trim($_POST['name'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $prezzo      = trim($_POST['prezzo'] ?? '');
        $isActive    = (int) ($_POST['isActive'] ?? 1);
        $immagine    = trim($_POST['immagine'] ?? '');

         // validazione degli input

        //nome
        if($name === ''){
            $errors[] = "Il nome è obbligatorio";
        }

        // descrizione
        if($descrizione === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        // immagine
        if($immagine === ''){
            $errors[] = "L'immagine è obbligatoria";
        }

        //prezzo
        if($prezzo === '' || !is_numeric($prezzo)){
            $errors[] = "Prezzo non valido";
        }

        //disponibilità
        if(!in_array($isActive, [0,1], true)){
            $errors[] = 'Invalid disponibility';
        }
        // se non ho l'array di errors popolato,
        // procedo con la creazione di un nuovo utente

        if(empty($errors)){
            $id = (int) ($_POST['product_id'] ?? 0);
            // così facendo richiamo i parametri che mi servono
            if(Products::updateProduct($id, $name, $descrizione, $prezzo, $isActive, $immagine)){
                $success = "Prodotto aggiornato!";
            }else{
                $errors[] = 'You have not change anything';
            };
        }
    }

    // FUNZIONE CHE ELIMINA UN PRODOTTO

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete'){

    // prendiamo il target ID (cioè l'id dell'oggetto che deve essere cambiato)
    // mettimo zero(in fondo) perchè è un int
    $targetID = (int) ($_POST['product_id'] ?? 0);

    // mi dice se che se l'id è uguale all'user(cioè l'user loggato in quel momento)
    if(Products::delete($targetID)){
        $success = "Prodotto eliminato!";
    }else{
        $errors[] = "Eliminazione prodotto fallita!";
    }

    }

    $products = Products::findAllProducts();

    $ordini = Purchases::findAll();

?>

  <?php 
  
    $titoloPagina = 'Gestione Prodotti Ricevuti'; 
  
    include __DIR__ . '/headerDashboard.php'; 
    
   ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Gestione Prodotti</h1>
            <p>Crea un nuovo prodotto e gestisci gli ordini dei prodotti effettuati.</p>
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
                <h2><i class="fas fa-user-plus"></i> Nuovo Ordine Prodotto</h2>
            </div>
            <div class="dash-formbody">
                <!-- Serve per attivare la funzione di aggiunta utenti alla tabella tramite il form -->
                <form action="" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="img">Immagine</label>
                        <input type="url" class="form-control" id="url_img" name="immagine" required>
                    </div>
                    <div class="mb-3">
                        <label for="floatingTextarea">Descrizione</label>
                        <textarea class="form-control" id="floatingTextarea" name="descrizione" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price">Prezzo</label>
                        <input type="text" class="form-control" id="price" name="prezzo" required>
                    </div>
                    <div class="mb-3">
                        <label for="disponibility">Disponibilità</label>
                        <select name="isActive" id="isActive" class="form-select">
                            <option value="1">Disponibile</option>
                            <option value="0">Non Disponibile</option>
                        </select>
                    </div>
                    
                    <button class="btn btn-warning" type="submit">Crea Prodotto</button>
                </form>
            </div>
        </section>

        <!-- ---- tabella Prodotti ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i>Gestione Prodotti</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Immagine</th>
                        <th>Nome</th>
                        <th>Descrizione</th>
                        <th>Prezzo</th>
                        <th>Disponibilità</th>
                        <th>Data Modifica</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>

                        <td>
                            <?= htmlspecialchars($p['id']) ?>
                        </td>
                        
                        <!-- MOSTRA IMMAGINE(se non c'è, viene mostrato un fallback) -->
                        <td>
                            <?php if(!empty($p['image_path'])): ?>
                                <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <span class="dash-current">—</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($p['name']) ?></td>

                        <td><?= htmlspecialchars($p['description']) ?></td>

                        <td><?= htmlspecialchars($p['price']) ?></td>

                        <td><?= htmlspecialchars($p['is_active']) ?></td>

                        <!-- rappresentazione di quando abbiamo loggato/joinato sottoforma di date-->
                        <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>

                        <td class="text-end">
                                <!-- PULSANTE DI EDIT -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-info" 
                                    title="Modifica prodotto"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditProduct<?= (int) $p['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <?php include __DIR__ . '/modali/ProductEditModal.php'; ?>

                                <!-- PULSANTE DI DELETE -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Elimina prodotto"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDeleteProduct<?= (int) $p['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                </button>
                                <?php include __DIR__ . '/modali/ProductDeleteModal.php'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($products)): ?>
                    <tr><td colspan="8" class="dash-empty">Nessun prodotto registrato</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- ---- ordini ricevuti dai clienti ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-receipt"></i> Ordini ricevuti dai clienti</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
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
                        <td><?= htmlspecialchars($ord['cliente']) ?></td>
                        <td>
                            <?php if(!empty($ord['immagine'])): ?>
                                <img src="<?= htmlspecialchars($ord['immagine']) ?>" alt="<?= htmlspecialchars($ord['prodotto']) ?>"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <span class="dash-current">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($ord['prodotto']) ?></td>
                        <td>€ <?= htmlspecialchars($ord['price_paid']) ?></td>
                        <td><?= htmlspecialchars($ord['status']) ?></td>
                        <td><?= date('d M Y', strtotime($ord['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($ordini)): ?>
                    <tr><td colspan="7" class="dash-empty">Nessun ordine ricevuto.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
