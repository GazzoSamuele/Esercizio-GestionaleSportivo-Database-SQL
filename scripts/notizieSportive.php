<?php

require_once __DIR__ . '/../DB/helpers/auth.php';
require_once __DIR__ . '/../DB/classes/News.php';

// solo un admin loggato può rigenerare le news (operazione distruttiva)
requireAdmin();

// svuoto la tabella: così a ogni lancio riparto da capo
News::deleteAll();

// squadre fittizie (le stesse usate per le partite)
$squadre = ['AOSTA', 'TORINO', 'GENOVA', 'MILANO', 'TRENTO',
            'TRIESTE', 'VENEZIA', 'BOLOGNA', 'FIRENZE', 'ANCONA',
            'PERUGIA', 'AQUILA', 'ROMA', 'NAPOLI', 'CAMPOBASSO',
            'BARI', 'POTENZA', 'CATANZARO', 'PALERMO', 'CAGLIARI'];

// nomi di giocatori fittizi da inserire nelle notizie
$giocatori = ['Marco Rossi', 'Luca Bianchi', 'Andrea Conti', 'Davide Ferrari',
              'Simone Greco', 'Matteo Russo', 'Federico Costa', 'Alessio Moretti',
              'Giorgio Romano', 'Nicolò Gallo'];

// stagione sportiva e una data futura usata in alcuni testi (es. assemblee)
$stagione = '2026/2027';
$dataFutura = date('d/m/Y', strtotime('+' . rand(7, 30) . ' days'));

// notizie sportive fittizie: ogni voce ha un title e un description
// i segnaposto {SQUADRA} e {GIOCATORE} vengono sostituiti in maniera casuale
$notizie = [
    [
        'title' => 'Vittoria in rimonta della Prima Squadra',
        'description'  => 'Una prestazione di grande carattere permette ai nostri ragazzi di ribaltare il risultato nel finale e portare a casa tre punti pesantissimi contro {SQUADRA}.',
    ],
    [
        'title' => '{GIOCATORE} firma una tripletta',
        'description'  => 'Serata da incorniciare per {GIOCATORE}, autore di tre reti nella sfida contro {SQUADRA}. Una prova che lo conferma tra i migliori marcatori del campionato.',
    ],
    [
        'title' => 'L\'Under 19 conquista la vetta della classifica',
        'description'  => 'Con l\'ennesima vittoria stagionale la nostra Under 19 si porta al primo posto in solitaria. Complimenti a tutto lo staff tecnico per il lavoro svolto.',
    ],
    [
        'title' => 'Sconfitta di misura nel derby contro {SQUADRA}',
        'description'  => 'Nonostante una buona prova, la squadra cede di un solo gol nel derby contro {SQUADRA}. C\'è rammarico, ma anche tanti segnali positivi in vista delle prossime gare.',
    ],
    [
        'title' => 'Pareggio spettacolare per la Prima Squadra',
        'description'  => 'Sei reti totali e tante emozioni nella sfida contro {SQUADRA}, terminata in parità. Un punto che muove la classifica e tiene alto il morale dello spogliatoio.',
    ],
    [
        'title' => '{GIOCATORE} premiato come miglior portiere del mese',
        'description'  => 'Riconoscimento meritato per {GIOCATORE}, eletto miglior portiere del mese dalla giuria della lega. Decisive le sue parate nelle ultime giornate.',
    ],
    [
        'title' => 'Esordio positivo per i Pulcini nel torneo regionale',
        'description'  => 'I più piccoli della società hanno disputato il loro primo torneo regionale con entusiasmo e impegno. Una giornata di sport e divertimento per tutti.',
    ],
    [
        'title' => 'La Prima Squadra vola in finale di Coppa',
        'description'  => 'Superato lo scoglio della semifinale contro {SQUADRA}, i nostri ragazzi staccano il pass per la finale. Appuntamento da non perdere per tutti i tifosi.',
    ],
    [
        'title' => 'Grande prova di carattere contro {SQUADRA}',
        'description'  => 'In dieci uomini per oltre un tempo, la squadra ha saputo stringere i denti e conquistare un risultato prezioso contro {SQUADRA}. Cuore e grinta da vendere.',
    ],
    [
        'title' => '{GIOCATORE} convocato nella rappresentativa nazionale',
        'description'  => 'Grande soddisfazione in casa societaria: {GIOCATORE} è stato convocato per il prossimo raduno della rappresentativa nazionale. Un orgoglio per tutta la società.',
    ],
];

// comunicazioni societarie fittizie
// segnaposto {STAGIONE} e {DATA} sostituiti con i valori generati sopra
$comunicazioni = [
    [
        'title' => 'Aperte le iscrizioni per la stagione {STAGIONE}',
        'description'  => 'Sono ufficialmente aperte le iscrizioni alla stagione {STAGIONE}. Le famiglie interessate possono rivolgersi alla segreteria per tutte le informazioni sui corsi e sulle quote.',
    ],
    [
        'title' => 'Convocazione assemblea ordinaria dei soci',
        'description'  => 'Si comunica che l\'assemblea ordinaria dei soci si terrà il giorno {DATA} presso la sede societaria. La presenza di tutti i soci è vivamente richiesta.',
    ],
    [
        'title' => 'Modifica degli orari di allenamento',
        'description'  => 'A partire dalla prossima settimana gli orari di allenamento subiranno alcune variazioni. Il nuovo calendario è disponibile in bacheca e presso la segreteria.',
    ],
    [
        'title' => 'Nuova convenzione con lo sponsor ufficiale',
        'description'  => 'La società è lieta di annunciare il rinnovo dell\'accordo con il proprio sponsor ufficiale. Un sostegno importante che ci accompagnerà per tutta la stagione {STAGIONE}.',
    ],
    [
        'title' => 'Chiusura della segreteria per le festività',
        'description'  => 'Si avvisano i soci che la segreteria resterà chiusa durante il periodo delle festività. Le attività riprenderanno regolarmente come da calendario.',
    ],
    [
        'title' => 'Campagna abbonamenti {STAGIONE} disponibile',
        'description'  => 'È attiva la campagna abbonamenti per la stagione {STAGIONE}. Sottoscrivendo l\'abbonamento sosterrai la squadra in tutte le gare casalinghe a un prezzo agevolato.',
    ],
    [
        'title' => 'Open day gratuito per i nuovi tesserati',
        'description'  => 'Il giorno {DATA} la società organizza un open day gratuito aperto a tutti i ragazzi che desiderano provare l\'hockey. Vi aspettiamo numerosi sul ghiaccio!',
    ],
    [
        'title' => 'Visite mediche obbligatorie: comunicazione importante',
        'description'  => 'Si ricorda a tutti gli atleti che le visite mediche per l\'idoneità agonistica sono obbligatorie. Chi non risulta in regola non potrà prendere parte agli allenamenti.',
    ],
    [
        'title' => 'Inaugurazione della nuova palestra societaria',
        'description'  => 'Siamo orgogliosi di annunciare l\'apertura della nuova palestra dedicata alla preparazione atletica. Un investimento importante per la crescita dei nostri tesserati.',
    ],
    [
        'title' => 'Variazione del campo per la prossima gara',
        'description'  => 'Si comunica che la prossima gara interna si disputerà in un impianto diverso da quello abituale. Tutti i dettagli sono pubblicati sui canali ufficiali della società.',
    ],
];

// generazione di 25 voci fittizie tra notizie e comunicazioni
for ($i = 0; $i < 25; $i++) {

    // decido a caso se questa voce è una notizia o una comunicazione
    $tipo = (rand(0, 1) === 0) ? 'notizia' : 'comunicazione';

    // pesco il template giusto in base al tipo
    if ($tipo === 'notizia') {
        $voce = $notizie[array_rand($notizie)];
    } else {
        $voce = $comunicazioni[array_rand($comunicazioni)];
    }

    // valori casuali da inserire al posto dei segnaposto
    $squadra   = $squadre[array_rand($squadre)];
    $giocatore = $giocatori[array_rand($giocatori)];

    // sostituisco i segnaposto sia nel title che nel description
    $cerca     = ['{SQUADRA}', '{GIOCATORE}', '{STAGIONE}', '{DATA}'];
    $sostituti = [$squadra, $giocatore, $stagione, $dataFutura];

    $title = str_replace($cerca, $sostituti, $voce['title']);
    $description  = str_replace($cerca, $sostituti, $voce['description']);

    // data casuale negli ultimi 90 giorni, formato AAAA-MM-GG (per la colonna DATE)
    $data = date('Y-m-d', strtotime('-' . rand(0, 90) . ' days'));

    News::createNews($title, $description, $data, $tipo);
}
