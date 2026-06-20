<?php

require_once __DIR__ . '/../classes/Db.php';
// Funzioni di supporto per autenticazione e gestione della sessione utente


// una funzione con 'void' non restituisce nulla
function requireLogin(): void
{
    if(session_status() === PHP_SESSION_NONE){

        session_start();
    }
     
    if(!isset($_SESSION['user_id'])){

    header('Location: login.php');
    exit;

    }
}

// verifica se sono un utente in base al ruolo
function requireAdmin(): void
{
    requireLogin();

    if(($_SESSION['user_role'] ?? '') !== 'admin'){

        header('Location: index.php');
        exit;
    }

}

// Blocca i client con la quota scaduta. Gli admin sono esenti.
function requireQuotaValida(): void
{
    requireLogin();

    // gli admin non pagano la quota → passano sempre
    if(($_SESSION['user_role'] ?? '') === 'admin'){
        return;
    }

    $pdo  = Db::connect();
    $stmt = $pdo->prepare('SELECT quota_scadenza FROM users WHERE id = :id');
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $scadenza = $stmt->fetchColumn();   // la data oppure false

    // quota assente o passata → fuori
    if(empty($scadenza) || $scadenza < date('Y-m-d')){
        header('Location: /Gestionale-Hockey/quotaScaduta.php');
        exit;
    }
}
// una funzione che restuisce il currentUser
// la funzione restuisce un array ma non è obbligatorio
function currentUser(): ?array
{
     if(session_status() === PHP_SESSION_NONE){

        session_start();
    }
     
    if(!isset($_SESSION['user_id'])){

    return null;

    }

    return [

        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'client',
    ];
}

function logOut(): void
{
    if(session_status() === PHP_SESSION_NONE){

        session_start();
    }

    $_SESSION = [];
    session_destroy();

    header('Location: login.php');
    exit;
}

?>
