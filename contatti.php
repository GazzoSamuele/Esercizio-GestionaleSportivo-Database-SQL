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
            $errors[] = "Name is required";
        }

        //email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] ="email is a invalid email address";
        }

        //phone
        if($phone === ''){
            $errors[] = "Phone is required";
        }

        //categoria
        if(!in_array($categoria, ['1', '2', '3', '4', '5', '6'], true)){
            $errors[] = 'Invalid category';
        }

        if(empty($errors)){
            // così facendo richiamo i parametri che mi servono
            InfoRequest::create($name, $email, $phone, $categoria, $messaggio);

                $success = "Request created!";

                $_POST = [];
        }
    }
?>

<?php include 'header.php'?>

<section class="pagehead">
  <div class="container">
    <span class="tag">Contatti</span>
    <h1>Contatti & iscrizione</h1>
  </div>
</section>

<section class="sec sec--white">
  <div class="container">
    <div class="forms">

      <!-- FORM 1 — Richiesta informazioni -->
    
  <div class="container py-5">
    
    <!-- ALERT DI RICHIESTA INVIATA DEL FORM -->
            <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
              <?php if(!empty($errors)): ?>
                <div class="alert alert-danger"><ul class="mb-0">
                  <?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
                </ul></div>
              <?php endif; ?>

    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="contact-wrapper">
          <div class="row g-0">
            <div class="col-md-5">
              <div class="contact-info h-100">
                <h3 class="mb-4">Non sei sicuro e hai bisogno di confrontarti con qualcuno?</h3>
                <p class="mb-4">Puoi richiedere informazioni nel modulo qua di fianco <i class="fa-solid fa-angles-right"></i></p>
                <br>
                <p class="mb-4"><i class="fa-solid fa-bell"></i>Oppure contattarci attraverso diversi canali</p>

                <div class="contact-item">
                  <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Indirizzo</h6>
                    <p class="mb-0">123 Business Avenue, Suite 100<br>New York, NY 10001</p>
                  </div>
                </div>

                <div class="contact-item">
                  <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Telefono</h6>
                    <p class="mb-0">+1 (555) 123-4567</p>
                  </div>
                </div>

                <div class="contact-item">
                  <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                  </div>
                  <div>
                    <h6 class="mb-0">Email</h6>
                    <p class="mb-0">contact@company.com</p>
                  </div>
                </div>

                <div class="social-links">
                  <h6 class="mb-3">Seguici sui nostri social</h6>
                  <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                  <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                </div>
              </div>
            </div>

            <div class="col-md-7">
              <div class="contact-form">
                <h3 class="mb-4">Compila i campi sottostanti per avere più info!</h3>
                <form action="contatti.php" method="post">
                  <input type="hidden" name="action" value="create">
                  <div class="row">
                    <div class="col-md-6 mb-3">
                      <label class="form-label">Nome</label>
                      <input type="text" name="name" class="form-control" placeholder="John">
                    </div>

                    <div class="col-md-6 mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" placeholder="Doe">
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label class="form-label">Numero di telefono</label>
                    <input type="text" name="phone" class="form-control" placeholder="How can we help?">
                  </div>

                  <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select class="form-select" name="categoria" aria-label="Default select example">
                      <option selected>Scegli la categoria in base alla tua fascia d'età</option>
                      <option value="1">Giovanili: 6-13 anni</option>
                      <option value="2">Cadetti: 14-15 anni</option>
                      <option value="3">Allievi: 16-17 anni</option>
                      <option value="4">Juniores: 18-19 anni</option>
                      <option value="5">Promesse: 20-22 anni</option>
                      <option value="6">Seniores: dai 23 anni in poi</option>
                    </select>
                    <p>Per info sull'inserimento alla categoria, chiedere direttamente al club</p>
                  </div>
                  
                  <div class="mb-4">
                    <label class="form-label">Scrivi qua la tua richiesta</label>
                    <textarea class="form-control"  name="messaggio" rows="5" placeholder="Your message here..."></textarea>
                  </div>
                  
                  <button type="submit" class="btn btn-submit text-white">Invia Richiesta</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
      <!-- FORM 2 — Registrazione al club (con codice) -->
      <div class="formcard is-locked">
        <h2>Registrati al club</h2>
        <p class="sub">Accesso all'area personale del sito.</p>

        <div class="cost">
          <h4>Quota societaria — cosa è incluso</h4>
          <ul>
            <li>Calendario completo (collegabile a Google Calendar)</li>
            <li>Avvisi trasferte e riunioni societarie</li>
            <li>Schede di allenamento del preparatore atletico</li>
            <li>Gestione del pagamento della quota annuale</li>
            <li>Avvisi sulla nuova attrezzatura</li>
          </ul>
        </div>

        <div class="code-banner">
          <h2>🔒 Per registrarti serve il codice univoco rilasciato dalla società dopo il pagamento della quota.</h2>
          <br>
          <a href="register.php" class="btn btn--accent btn--block">Vai alla registrazione</a>
        </div>
    </div>
  </div>
</section>

<?php include 'footer.php'?>