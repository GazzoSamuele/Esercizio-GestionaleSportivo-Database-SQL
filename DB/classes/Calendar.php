<?php 

require_once __DIR__ . '/Db.php';


class Calendar 
{
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

    public static function findUltimeCampionato(string $categoria = '', int $n = 5): array 
    {
        $pdo = Db::connect();

        if ($categoria !== '') {

        $stmt = $pdo->prepare(

            "SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
            FROM calendar
            WHERE tipo = 'campionato' AND categoria = :categoria
            ORDER BY data DESC LIMIT :n"
        );
            $stmt->bindValue(':categoria', $categoria);
            } else {
                $stmt = $pdo->prepare(
                    "SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
                     FROM calendar
                     WHERE tipo = 'campionato'
                     ORDER BY data DESC LIMIT :n"
                );
            }
            $stmt->bindValue(':n', $n, PDO::PARAM_INT);
            $stmt->execute();
            
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findUltimeCoppa(string $categoria = '', int $n = 5): array
    {
        $pdo = Db::connect();

        if ($categoria !== '') {

        $stmt = $pdo->prepare(

            "SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
             FROM calendar
             WHERE tipo = 'coppa' AND categoria = :categoria
             ORDER BY data DESC LIMIT :n"
        );
            $stmt->bindValue(':categoria', $categoria);
            } else {
                $stmt = $pdo->prepare(
                    "SELECT id, squadra_casa, squadra_ospite, data, categoria, gol_casa, gol_ospite
                     FROM calendar
                     WHERE tipo = 'coppa'
                     ORDER BY data DESC LIMIT :n"
                );
            }
            $stmt->bindValue(':n', $n, PDO::PARAM_INT);
            $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public static function create(string $squadra_casa, string $squadra_ospite, string $data, string $categoria, int $gol_casa, int $gol_ospite, string $tipo = 'campionato'): bool
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'INSERT INTO calendar (squadra_casa, squadra_ospite, 
                         data, categoria, tipo, gol_casa, gol_ospite)
             VALUES (:casa, :ospite, :data, :categoria, :tipo, :gc, :go)'
        );

        return $stmt->execute([
            ':casa' => $squadra_casa, 
            ':ospite' => $squadra_ospite, 
            ':data' => $data,
            ':categoria' => $categoria, 
            ':tipo' => $tipo, 
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