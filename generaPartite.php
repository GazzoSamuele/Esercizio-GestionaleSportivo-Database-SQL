<?php

require_once __DIR__ . '/DB/classes/Calendar.php';
// svuoto la tabella: così a ogni lancio riparto da 30 partite di default
Calendar::deleteAll();
// dati fittizzi di scqaudre ancora più fittizzie
$squadre = ['AOSTA', 'TORINO', 'GENOVA', 'MILANO', 'TRENTO', 
            'TRIESTE', 'VENEZIA', 'BOLOGNA', 'FIRENZE', 'ANCONA',
            'PERUGIA', 'ACQUILA','ROMA', 'NAPOLI', 'CAMPOBASSO', 
            'BARI', 'POTENZA','CATANZARO','PALERMO', 'CAGLIARI' ];
$categorie = ['Pulcini', 'Giovanile', 'Under 19', 'Under 21', 'Terza Categoria', 'Prima Squadra'];

// generazione di 30 partite fittizzie
for ($i = 0; $i < 30; $i++){

    //pesco una scquadra a caso per la casa
    $casa = $squadre[array_rand($squadre)];

    // pesco una scqudra ospite diversa dalla casa
    // ripesca l'ospite finché è diverso dalla casa (niente "Milano vs Milano")

    do {
        $ospite = $squadre[array_rand($squadre)];
    }while ($ospite === $casa);

    // numero casuale di gol della scquadra di casa fino ad un max di 7
    $golCasa = rand(0, 7);

    // numero casuale di gol della scquadra ospite fino ad un max di 7
    $golOspite = rand(0, 7);

    // data casuale negli ultimi 90 giorni, formato AAAA-MM-GG (per la colonna DATE)
    $dataCasualePartita = date('Y-m-d', strtotime('-' . rand(0, 90) . ' days'));

    $categoria = $categorie[array_rand($categorie)];

    Calendar::create($casa, $ospite, $dataCasualePartita, 
    $categoria, $golCasa, $golOspite);


}