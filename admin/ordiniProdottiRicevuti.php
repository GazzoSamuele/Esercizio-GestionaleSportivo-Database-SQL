<?php

//richiamo le funzioni requireLogin e currentUser in auth.php
require_once __DIR__ . '/../DB/helpers/auth.php';
//richiamo le funzioni degli users
require_once __DIR__ . '/../DB/classes/Products.php';

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
            $errors[] = "Name is required";
        }

        // descrizione
        if($descrizione === ''){
            $errors[] = "Description is required";
        }

        // immagine
        if($immagine === ''){
            $errors[] = "Immagine is required";
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
                $success = "Product created!";

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
            $errors[] = "Name is required";
        }

        // descrizione
        if($descrizione === ''){
            $errors[] = "Description is required";
        }

        // immagine
        if($immagine === ''){
            $errors[] = "Immagine is required";
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
                $success = "Product updated!";
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
        $success = "Product deleted!";
    }else{
        $errors[] = "Failed to delete product!";
    }

    }

    $products = Products::findAllProducts();

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Gestionale-Hockey/style.css">
    <title>Gestione Utenti — Gestionale Società Sportiva</title>
</head>
<body>

<div class="dash">

    <!-- ============ SIDEBAR (uguale alla dashboard) ============ -->
    <aside class="dash-sidebar">
        <div class="dash-brand">
            <i class="fas fa-hockey-puck"></i>
            <span>Gestionale Società Sportiva</span>
        </div>

        <nav class="dash-nav">
            <a href="/Gestionale-Hockey/dashboard.php" class="dash-link">
                <i class="fas fa-house"></i><span>Dashboard</span>
            </a>
            <hr>
            <!-- VERSIONE DASHBOARD CLIENT -->
            <?php if($user['role'] === 'client'): ?>
            <a href="/Gestionale-Hockey/client/calendarioPartite.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Calendario</span>
            </a>

            <a href="/Gestionale-Hockey/client/ordiniEffettuati.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>Ordini</span>
            </a>

            <!-- <a href="/Gestionale-Hockey/client/news.php" class="dash-link">
                <i class="fas fa-calendar-days"></i><span>News</span>
            </a> -->
        
            <?php endif; ?>

            <!-- SE L'UTENTE è ADMIN, MI MOSTRI QUESTO -->
            <?php if($user['role'] === 'admin'): ?>
            <a href="/Gestionale-Hockey/admin/users.php" class="dash-link">
                <i class="fas fa-user-shield"></i><span>Utenti Registrati</span>
            </a>
            <a href="/Gestionale-Hockey/admin/ordiniProdottiRicevuti.php" class="dash-link active">
                <i class="fas fa-user-shield"></i><span>Ordini Effettuati</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="dash-user">
            <div class="dash-avatar"><?= htmlspecialchars(strtoupper(substr($user['name'], 0, 1))) ?></div>
            <div class="dash-user-info">
                <strong><?= htmlspecialchars($user['name']) ?></strong>
                <small><?= htmlspecialchars($user['role']) ?></small>
            </div>
        </div>
        <a href="/Gestionale-Hockey/logout.php" class="dash-logout">
            <i class="fas fa-right-from-bracket"></i> Logout
        </a>
    </aside>

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
                <h2><i class="fas fa-users"></i> Ordini Effettuati</h2>
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
                                    title="Edit products"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditProduct<?= (int) $p['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <?php include __DIR__ . '/modalEdit.php'; ?>

                                <!-- PULSANTE DI DELETE -->
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Delete products"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDeleteProduct<?= (int) $p['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                </button>
                                <?php include __DIR__ . '/modalDelete.php'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($products)): ?>
                    <tr><td colspan="6" class="dash-empty">Nessun nuovo ordine effettutato</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</div>

<script src="/Gestionale-Hockey/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
