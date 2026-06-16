<?php

// quando definiamo un oggetto stiamo facendo un'estreazione 
// delle proprietà e dei valori dell'oggetto 

// si prende il file di configurazione di riferimento 

require_once __DIR__ .'/../config/config.php';

// cosi facendo mi creo la composizione delle mie credenziali di accesso
// al database, attraverso la suddivisione di tutte le parti che compongno
// l'accesso in singole parti, questo rende l'accesso e la possibile intrusione
// di terzi, più sicura

class Db{

    // unica istanza condivisa(pattern Singleton)
    // facendo un Singleton è una sola connessione per tutta l'applicazione
    
    // si evita di fare il POST_ ogni volta che un utente fa un'accesso 
    // ed "apre" il database. Rappresenta una porta che deve essere chiusa 
    // ogni volta che viene utlizzata(aperta)

    private static ?PDO $instance = null;

    // costruttore (così nessuno può fare 'new Db')
    private function __construct() {}

    public static function connect(): PDO
    {
        if(self::$instance === null){

            // prende i dati di riferimento e contusicre in parti un oggetto 
            // che rappresenta il metodo di login al database da parte del admin
            
            // $dns rappresenta 'data source name'
            $dsn = 'mysql:host=' . DB_HOST 
                    . ';dbname=' . DB_NAME
                    . ';charset=' . DB_CHARSET;

            $options = [

                // errori che lanciano eccezzioni invece di fallire silenziosamente
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            try{
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e){

                // 'die' rappreenta un comando che da un messaggio di connessione
                // fallita e fa uscire dal login
                die('Connessione fallita: ' . $e->getMessage());

            }
            
        }

        return self::$instance;

    }
}
?>