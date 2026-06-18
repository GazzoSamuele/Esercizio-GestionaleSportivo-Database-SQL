<?php

// la richiamo sia nei prodotti nella dashboard sia nei prodotti dela pagina del sito
require_once __DIR__ . '/DB/classes/Products.php';

//  scarica la risposta dell'API e così facendo richiama l'api Dummyjson per i dati delle attrezzature sportive
$json = file_get_contents('https://dummyjson.com/products/category/sports-accessories');

// l'API ha risposto?
// if ($json === false) {
//     die('❌ file_get_contents ha fallito (connessione/SSL).');
// }

//trasformo i dati $json raccolti in un array PHP
$data = json_decode($json, true);

// il JSON contiene prodotti?
// if (empty($data['products'])) {
//     die('❌ Nessun prodotto nel JSON. Risposta: ' . substr($json, 0, 300));
// }

//creo un ciclo foreach che per ogni prodotto dell'API, lo inserisco nel mio DB
foreach ($data['products'] as $prod) {
    Products::create(
        $prod['title'],
        $prod['description'],
        (string) $prod['price'],1,
        $prod['thumbnail']
    );
}

// echo "✅ Importati " . count($data['products']) . " prodotti!";

