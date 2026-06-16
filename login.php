<?php   
    // faccio partire la sessione
    // funzione nativa di php
    session_start();

    //richiamo del singleton per attivare le PDO
    require_once __DIR__ . '/classes/Db.php';

    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        // recuperare dagli inout i valori del form
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        //validazione di entrambi i campi
        if($email === '' || $password === ''){
            $errors = 'Email and Password are required';
        }else {
            $connettoreDb = Db::connect();

            // controllo che l'email non sia registrata già
            $stmt = $connettoreDb->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);

            $user = $stmt->fetch();

            //verifico se la password con password_verify

            if($user && password_verify($password, $user['password'])){

                //rigenero la sessione
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                //login riuscito
                header('Location: dashboard.php');
                exit;
                
                }

            $errors = 'Credentials Invalid!';

        }
    }
?>
<?php include 'header.php'?>

<form action="" method="post" class="row g-3">
        <!-- se success esiste -->
        <?php if($success): ?>

            <div class="alert alert-success">
                Registrazione succesfull! You can now <a href="login.php" class="alert-link">Login</a>
            </div>
        
        <?php endif; ?>

  <div class="col-md-6">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" class="form-control" name="email">
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" class="form-control" name="password">
  </div>

  <div class="col-12">
    <button type="submit" class="btn btn-primary">Entra</button>
  </div>
</form>

<?php include 'footer.php'?>