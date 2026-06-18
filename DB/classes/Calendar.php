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