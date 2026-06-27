<?php

    require_once __DIR__ . '/DB/classes/InfoRequest.php';

    $errors = [];
    $success = '';

    // FUNZIONE CHE CREA L'UTENTE
    if($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create'){

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone =$_POST['phone'] ?? '';
        $categoria =$_POST['categoria'] ?? '';
        $messaggio =$_POST['messaggio'] ?? '';

        // validazione degli input

        //nome
        if($name === ''){
            $errors[] = "Il nome è obbligatorio";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="Email non valida";
        }

        //phone
        if($phone === ''){
            $errors[] = "Il telefono è obbligatorio";
        }

        //categoria
        if(!in_array($categoria, ['1', '2', '3', '4', '5', '6'], true)){
            $errors[] = 'Invalid category';
        }

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            InfoRequest::create($name, $email, $phone, $categoria, $messaggio);

                $success = "Richiesta inviata!";

                $_POST = [];
        }
    }
?>

<?php include 'header.php'?>

<section class="sec sec--white">
  <div class="container">
    <h2>Contatti</h2>
    <p class="sub">Richiedi inforomazioni attraverso il form qua sotto oppure contattando direttamente il club</p>
  <div class="container-large container">
    <div class="forms">

      <!-- FORM 1 — Richiesta informazioni -->
      <div class="contact-block">

        <!-- ALERT DI RICHIESTA INVIATA DEL FORM -->
        <?php if($success): ?><div class="alert alert--success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if(!empty($errors)): ?>
          <div class="alert alert--danger"><ul>
            <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
          </ul></div>
        <?php endif; ?>

        <div class="contact-wrapper">
          <div class="contact-info">
            <h3 class="contact-info__title">Non sei sicuro e hai bisogno di confrontarti con qualcuno?</h3>
            <p>Puoi richiedere informazioni nel modulo qua di fianco <i class="fa-solid fa-angles-right"></i></p>
            <br>
            <p><i class="fa-solid fa-bell"></i> Oppure contattarci attraverso diversi canali</p>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <div>
                <h6>Indirizzo</h6>
                <p>Via dello Sport 12 38100 Trento</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-phone"></i>
              </div>
              <div>
                <h6>Telefono</h6>
                <p> +39 0461 123456</p>
              </div>
            </div>

            <div class="contact-item">
              <div class="contact-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div>
                <h6>Email</h6>
                <p>info@hockeyclub.it</p>
              </div>
            </div>

            <div class="social-links">
              <h6>Seguici sui nostri social</h6>
              <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
            </div>
          </div>

          <div class="contact-form">
            <h3 class="contact-form__title">Compila i campi sottostanti per avere più info!</h3>
            <form class="info-form" action="contatti.php" method="post">
              <input type="hidden" name="action" value="create">
              <div class="field-row">
                <div class="field">
                  <label class="form-label">Nome</label>
                  <input type="text" name="name" class="form-control" >
                </div>

                <div class="field">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control">
                </div>
              </div>

              <div class="field">
                <label class="form-label">Numero di telefono</label>
                <input type="text" name="phone" class="form-control" >
              </div>

              <div class="field">
                <label class="form-label">Categoria</label>
                <select class="form-control" name="categoria" aria-label="Categoria">
                  <option></option>
                  <option value="1">Giovanili: 6-13 anni</option>
                  <option value="2">Cadetti: 14-15 anni</option>
                  <option value="3">Allievi: 16-17 anni</option>
                  <option value="4">Juniores: 18-19 anni</option>
                  <option value="5">Promesse: 20-22 anni</option>
                  <option value="6">Seniores: dai 23 anni in poi</option>
                </select>
                <p class="field__hint">Per info sull'inserimento alla categoria, chiedere direttamente al club</p>
              </div>

              <div class="field">
                <label class="form-label">Scrivi qua la tua richiesta</label>
                <textarea class="form-control" name="messaggio" rows="5"></textarea>
              </div>

              <button type="submit" class="btn btn--accent">Invia Richiesta</button>
            </form>
          </div>
        </div>
      </div>

      <!-- FORM 2 — Registrazione al club (con codice) -->
      <div class="formcard is-locked">
        <div class="cost">
          <h4>Quota societaria — cosa è incluso</h4>
          <ul>
            <li>Dashboard personalizzata con tutte le informazioni che ti servono</li>
            <li>Calendario completo (collegabile a Google Calendar)</li>
            <li>Avvisi trasferte e riunioni societarie</li>
            <li>Schede di allenamento del preparatore atletico</li>
            <li>Gestione del pagamento della quota annuale</li>
            <li>Avvisi sulla nuova attrezzatura</li>
          </ul>
        </div>

        <div class="code-banner">
          <h3>🔒 Per registrarti serve il codice univoco rilasciato dalla società dopo il pagamento della quota.</h3>
        </div>  
          <a href="register.php" class="btn btn--accent btn--block">Vai alla registrazione</a>
        
      </div>

    </div>
  </div>
  </div>
</section>

<?php include 'footer.php'?>