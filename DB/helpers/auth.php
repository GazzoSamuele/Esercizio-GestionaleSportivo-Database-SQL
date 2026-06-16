<?php

// Funzioni di supporto per autenticazione e gestione della sessione utente

//
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
}

?>
