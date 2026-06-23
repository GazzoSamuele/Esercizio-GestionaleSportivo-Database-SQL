<?php
// ============================================================
//  DATI FITTIZI CONDIVISI
//  Questo file SOLO definisce array di dati finti — niente query,
//  niente deleteAll, nessun effetto collaterale. Si può includere
//  ovunque serva (pagine web index.php/calendario.php, e volendo
//  anche i generatori notizieSportive.php / generaPartite.php).
//
//  Uso:  require_once __DIR__ . '/DB/data/datiFittizi.php';
//        (il percorso cambia in base alla cartella della pagina)
// ============================================================

// squadre (città) fittizie
$squadre = ['AOSTA', 'TORINO', 'GENOVA', 'MILANO', 'TRENTO',
            'TRIESTE', 'VENEZIA', 'BOLOGNA', 'FIRENZE', 'ANCONA',
            'PERUGIA', 'AQUILA', 'ROMA', 'NAPOLI', 'CAMPOBASSO',
            'BARI', 'POTENZA', 'CATANZARO', 'PALERMO', 'CAGLIARI'];

// nomi di giocatori fittizi
$giocatori = ['Marco Rossi', 'Luca Bianchi', 'Andrea Conti', 'Davide Ferrari',
              'Simone Greco', 'Matteo Russo', 'Federico Costa', 'Alessio Moretti',
              'Giorgio Romano', 'Nicolò Gallo'];

// categorie di gioco
$categorie = ['Pulcini', 'Giovanile', 'Under 19', 'Under 21', 'Terza Categoria', 'Prima Squadra'];

// ---- dati extra utili al palmarès / alle tabelle ----

// stagioni sportive
$stagioni = ['2021/22', '2022/23', '2023/24', '2024/25', '2025/26'];

// risultati possibili
$risultati = ['1° posto', '2° posto', '3° posto', 'Finalista', 'Semifinalista', 'Quarti di finale'];

// note / contesto del risultato
$note = ['Campionato regionale', 'Play-off', 'Coppa Italia', 'Final Four', 'Coppa regionale', 'Torneo nazionale'];

// ruoli dei giocatori
$ruoli = ['Portiere', 'Difensore', 'Centrocampista', 'Attaccante', 'Ala', 'Capitano'];
