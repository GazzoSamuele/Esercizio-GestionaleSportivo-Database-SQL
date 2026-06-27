<?php

require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/Impegni.php';

// dico al pc che sono l'amministratore
requireAdmin();
// mi dice chi sono, se admin o client
$user = currentUser();

    $errors = [];
    $success = '';

    //GESTIONE FORM NUOVO IMPEGNO 

    // FUNZIONE CHE CREA UN NUOVO IMPEGNO
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $titolo = trim($_POST['titolo'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $data = $_POST['data'] ?? date('Y-m-d');
        $ora = $_POST['ora'] ?? '';
        $luogo = $_POST['luogo'] ?? '';

        //titolo
        if($titolo === ''){
            $errors[] = "Il titolo è obbligatorio";
        }
        
        //descrizione
        if($descrizione === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        //tipo
        if(!in_array($tipo, ['allenamento', 'partita', 'coppa', 'riunione', 'altro'], true)){
            $errors[] = 'Tipo non valido';
        }

        if(empty($errors)){
            Impegni::createImpegno($titolo, $descrizione, $tipo, $data, $ora, $luogo);

            $success = "Impegno creato!";
            $_POST = [];
            }
    }
    

    // FUNZIONE CHE MODIFICA GLI IMPEGNI ESISTENTI

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit'){
        
        $id     = (int) ($_POST['impegno_id'] ?? 0);
        $titolo = trim($_POST['titolo'] ?? '');
        $descrizione = trim($_POST['descrizione'] ?? '');
        $tipo = $_POST['tipo'] ?? '';
        $data = $_POST['data'] ?? date('Y-m-d');
        $ora = $_POST['ora'] ?? '';
        $luogo = $_POST['luogo'] ?? '';

        if($titolo === ''){
            $errors[] = "Il titolo è obbligatorio";
        }

        if($descrizione === ''){
            $errors[] = "La descrizione è obbligatoria";
        }

        //tipo
        if(!in_array($tipo, ['allenamento', 'partita', 'coppa', 'riunione', 'altro'], true)){
            $errors[] = 'Tipo non valido';
        }

        if(empty($errors)){
            Impegni::updateImpegno($id, $titolo, $descrizione, $tipo, $data, $ora, $luogo);

            $success = "Impegno modificato!";
            $_POST = [];
            }
    }
    

    // FUNZIONE CHE ELIMINA L'IMPEGNO

    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete'){

    $targetImpegno = (int) ($_POST['impegno_id'] ?? 0);

    if(Impegni::delete($targetImpegno)){
        $success = "Impegno Rimosso!";
    }else{
        $errors[] = "Fallito tentativo di rimozione di un impegno!";
    }

    }

    $impegni = Impegni::findAllImpegni();

    $lastImpegni = Impegni::findProssimiImpegni();

    $ultimiImpegni = array_slice(array_reverse($lastImpegni), 0, 5);

?>
    <?php 
  
    $titoloPagina = 'Gestione Impegni Sportivi'; 
  
    include __DIR__ . '/headerDashboard.php'; 
    
   ?>

    <!-- ============ MAIN ============ -->
    <main class="dash-main">
        <header class="dash-head">
            <h1>Gestione Impegni Sportivi</h1>
            <p>Crea un nuovo impegno sportivo</p>
        </header>

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

        <!-- ---- form nuovo impegno ---- -->
    <div class="alg-form-results">
        <section class="dash-panel-form dash-formcard">
            <div class="dash-panel-head">
                <h2><i class="fas fa-user-plus"></i>Nuovo Impegno</h2>
            </div>
            <div class="dash-formbody">
                <!-- Serve per attivare la funzione di aggiunta impegno alla tabella tramite il form -->
                <form action="" method="post">
                    <input type="hidden" name="action" value="create">
                    <div class="mb-3">
                        <label for="titolo">Titolo</label>
                        <input type="text" class="form-control" id="name" name="titolo" required>
                    </div>
                    <div class="mb-3">
                        <label for="descrizione">Descrizione</label>
                        <textarea name="descrizione" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tipo">Tipo di impegno</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="allenamento">Allenamento</option>
                            <option value="partita">Partita</option>
                            <option value="coppa">Coppa</option>
                            <option value="riunione">Riunione</option>
                            <option value="altro">Altro</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="data">Data</label>
                        <input type="date" class="form-control" id="data" name="data" required>
                    </div>
                    <div class="mb-3">
                        <label for="ora">Ora</label>
                        <input type="time" id="ora" name="ora" min="08:00" max="18:00" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="luogo">Luogo</label>
                        <input type="text" class="form-control" id="luogo" name="luogo" required>
                    </div>

                    <button class="btn btn-warning" type="submit">Crea Impegno</button>
                </form>
            </div>
        </section>

        <!-- elenco ultimi impegni -->
        <section class="dash-panel-info">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i>Ultimi Impegni Registrati</h2>
            </div>
            <table class="dash-table dash-table-impegni">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Ora</th>
                        <th>Luogo</th>
                        <th>Descrizione</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($ultimiImpegni as $imp): ?>
                    <tr>
                        <!-- prendo l'id dell'utente singolo -->
                        <td>#<?= (int) $imp['id'] ?></td>

                        <td><?= htmlspecialchars($imp['titolo']) ?></td>

                        <td><?= htmlspecialchars($imp['tipo']) ?></td>

                        <td><?= htmlspecialchars($imp['data']) ?></td>

                        <td><?= htmlspecialchars($imp['ora']) ?></td>

                        <td><?= htmlspecialchars($imp['luogo']) ?></td>

                        <td><?= htmlspecialchars($imp['descrizione']) ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
        <!-- ---- calendario impegni ---- -->
        <section class="dash-panel">
            <div class="dash-panel-head">
                <h2><i class="fas fa-users"></i>Tutti gli impegni</h2>
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titolo</th>
                        <th>Tipo</th>
                        <th>Data</th>
                        <th>Ora</th>
                        <th>Luogo</th>
                        <th>Descrizione</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($impegni as $imp): ?>
                    <tr>
                        <!-- prendo l'id dell'utente singolo -->
                        <td class="text-align-center">#<?= (int) $imp['id'] ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['titolo']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['tipo']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['data']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['ora']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['luogo']) ?></td>

                        <td class="text-align-center"><?= htmlspecialchars($imp['descrizione']) ?></td>

                        <td class="text-end">
                               
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-info" 
                                    title="Modifica impegno"
                                    data-bs-toggle="modal" data-bs-target="#modalEditImpegni<?= (int) $imp['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                </button>
                                <?php include __DIR__ . '/modali/ImpegniEditModal.php'; ?>

                                
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-outline-danger" 
                                    title="Elimina impegno"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalDeleteImpegni<?= (int) $imp['id'] ?>">
                                        <i class="fas fa-trash"></i>
                                </button>
                                <?php include __DIR__ . '/modali/ImpegniDeleteModal.php'; ?>
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

