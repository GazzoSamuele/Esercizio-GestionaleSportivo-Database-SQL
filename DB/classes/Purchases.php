<?php 
// funzione di tutti gli ordini LATO ADMIN

require_once __DIR__ . '/Db.php';


class Purchases 
    {
        public static function findAll(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(
                'SELECT purc.id, u.name AS cliente, p.name AS prodotto, p.image_path 
                        AS immagine, purc.prezzo_pagato, purc.status, purc.created_at
                FROM purchases purc
                JOIN users u    ON purc.user_id = u.id
                JOIN products p ON purc.product_id = p.id
                ORDER BY purc.created_at DESC'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    
    // funzione di tutti gli ordini LATO CLIENT 

        public static function findByUser(int $userId): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare(
               'SELECT purc.id, p.name AS prodotto, p.image_path AS immagine,
                purc.prezzo_pagato, purc.status, purc.created_at, purc.pronto_ritiro
                FROM purchases purc
                JOIN products p ON purc.product_id = p.id
                WHERE purc.user_id = :uid
                ORDER BY purc.created_at DESC'
            );
            $stmt->execute([':uid' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // FUNZIONI PER LA DASHBORD E PER I GRAFICI

        // visualizzazione in dashboard di grafici e tabelle
        public static function incassoPerMese(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(
                // SUM(prezzo_pagato) è la funzione di aggregazione(somma), serve per avere il totale della colonna di tutti i singoli incassi
                "SELECT DATE_FORMAT(created_at, '%Y-%m') AS mese, SUM(prezzo_pagato) AS totale
                FROM purchases
                WHERE status = 'pagato'
                GROUP BY mese
                ORDER BY mese"
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // visualizzazione in dashboard di grafici e tabelle
        public static function contaPerStato(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(

                'SELECT status, COUNT(*) AS total FROM purchases
                 GROUP BY status'     
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // visualizzazione in dashboard di grafici e tabelle
        public static function topProdotti(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(

               'SELECT p.name, COUNT(*) AS total 
                FROM purchases purc
                JOIN products p ON purc.product_id = p.id
                GROUP BY p.id
                ORDER BY COUNT(*) DESC LIMIT 5'
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // FINE FUNZIONI PER LA DASHBORD E PER I GRAFICI

        // funzione che crea un nuovo prodotto da inserire nel DB

        public static function create(int $userId, int $productId, string $pricePaid, string $status = 'pending'): int
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare(
               'INSERT INTO purchases (user_id, product_id, prezzo_pagato, status)
                VALUES (:uid, :pid, :price, :status)'
            );
            $stmt->execute([
                ':uid' => $userId,
                ':pid' => $productId,
                ':price' => $pricePaid,
                ':status' => $status,
            ]);

            return (int) $pdo->lastInsertId();
        }

        public static function delete(int $id): bool
        {
            $pdo = Db::connect();

            $stmt = $pdo->prepare(
                'DELETE FROM purchases WHERE id = :id'
            );

            return $stmt->execute([':id' => $id]);
            
        }
    }

   
//purchase.php

// incassoPerMese()

// Cosa rappresenta: quanti soldi ha incassato la società ogni mese, tra prodotti venuti ed iscrizioni fatte.
// Dove finisce: un grafico a linee 

// contaPerStato()

// Cosa rappresenta: quanti ordini sono In attesa, pagato, Rimborsato.
// Dove finisce: in una card che mostra gli iscritti o le richieste di informazioni ancora da visualizzare. essa si collega alla pagina ordiniProdottiRicevuti.php

// topProdotti()

// Cosa rappresenta: i 5 prodotti più venduti.
// Dove finisce: una ciambella che rappresenta i 5 prodotti più venduti

// //riferimentoUtenti.php
// iscrittiPerMese()

// Cosa rappresenta: quanti nuovi tesserati si sono iscritti ogni mese.
// Dove finisce: un grafico a linee che mostra se la società sta crescendo o no.

// contaQuote()

// Cosa rappresenta: quanti soci hanno la quota valida / in scadenza / scaduta.
// Dove finisce: 3 stat-card

// //calendar.php

// bilancioRisultati()

// Cosa rappresenta: quante partite la squadra ha vinto / pareggiato / perso.
// Dove finisce: una ciambella sportiva (verde vittorie, giallo pari, rosso sconfitte).

// partitePerCategoria()

// Cosa rappresenta: quante partite per categoria (es. Under14, Senior...).
// Dove finisce: un grafico a barre.