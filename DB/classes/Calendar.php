<?php 

require_once __DIR__ . '/Db.php';


class Calendar 
{
    public static function findById(int $id): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        $calendar = $stmt->fetch();
        
        return $calendar ?: null;
    }


    public static function findBySquadraCasa(string $squadra_casa): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE squadra_casa = :squadra_casa'
        );

        $stmt->execute([':squadra_casa' => $squadra_casa]);

        $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $calendar ?: null;
    }


    public static function findBySquadraOspite(string $squadra_ospite): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE squadra_ospite = :squadra_ospite'
        );

        $stmt->execute([':squadra_ospite' => $squadra_ospite]);

        $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $calendar ?: null;
    }


    public static function findByData(string $data): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE data = :data'
        );

        $stmt->execute([':data' => $data]);

        $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $calendar ?: null;
    }


    public static function findByCategoria(string $categoria): array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE categoria = :categoria'
        );

        $stmt->execute([':categoria' => $categoria]);

        $calendar = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $calendar;
    }


    public static function findAllPartite(): array
    {
        $pdo = Db::connect();

        $stmt = $pdo->query(

            'SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             -- ORDER BY data ordina le partite in ordine di data
             ORDER BY data'
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

     // FUNZIONI PER LA DASHBORD E PER I GRAFICI

        // visualizzazione in dashboard di grafici e tabelle
        public static function bilancioRisultati(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(

                // UNION ALL serve a impilare i risultati di due query una sotto l'altra, come se attaccassi due elenchi
                "SELECT squadra, SUM(punti) AS punti_totali
                FROM 
                (SELECT squadra_casa AS squadra,
                        CASE WHEN gol_casa > gol_ospite THEN 3
                        WHEN gol_casa = gol_ospite THEN 1
                        ELSE 0 END AS punti
                    FROM calendar  
                
                UNION ALL  
                SELECT squadra_ospite AS squadra,
                        CASE WHEN gol_ospite > gol_casa THEN 3
                        WHEN gol_ospite = gol_casa THEN 1
                        ELSE 0 END AS punti
                    FROM calendar) AS classifica
                GROUP BY squadra
                ORDER BY punti_totali DESC LIMIT 5"

            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // visualizzazione in dashboard di grafici e tabelle
        public static function partitePerCategoria(): array
        {
            $pdo = Db::connect();
            $stmt = $pdo->query(

                'SELECT categoria, COUNT(*) AS total FROM calendar
                 GROUP BY categoria'     
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // FINE FUNZIONI PER LA DASHBORD E PER I GRAFICI

    // CRUD

    //create

    public static function create(string $squadra_casa, string $squadra_ospite, string $data, string $categoria, int $gol_casa, int $gol_ospite): bool
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'INSERT INTO calendar (squadra_casa, squadra_ospite, 
                         data, categoria, gol_casa, gol_ospite)
             VALUES (:casa, :ospite, :data, :categoria, :gc, :go)'
        );

        return $stmt->execute([
            ':casa' => $squadra_casa, 
            ':ospite' => $squadra_ospite, 
            ':data' => $data,
            ':categoria' => $categoria, 
            ':gc' => $gol_casa, 
            ':go' => $gol_ospite,
        ]);
    }

    // delete(in questo caso per lo svuotamento dei campi)

    public static function deleteAll(): void
    {
        $pdo = Db::connect();
        $pdo->exec('DELETE FROM calendar');
    }
}