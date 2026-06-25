<?php

require_once __DIR__ . '/Db.php';

class Impegni {

    public static function findProssimiImpegni(): array
    {
        $pdo = Db::connect();

        $stmt = $pdo->query(
            // in questo caso >= CURDATE() tiene conto solo degli impegni futuri
            // ORDER BY data, ora mette tutto in ordine cronologico con un massimo di 5 date
            'SELECT id, titolo, descrizione, tipo, data, ora, luogo
             FROM impegni 
             WHERE data >= CURDATE()
             ORDER BY data, ora LIMIT 5 '
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public static function findAllImpegni(): array
    {
            $pdo = Db::connect();

            $stmt = $pdo->query(
                'SELECT id, titolo, descrizione, tipo, data, ora, luogo, created_at
                 FROM impegni
                 ORDER BY created_at ASC'
            );
        return $stmt->fetchAll();
    }
    
    public static function findByTipo(string $tipo): array
    {

        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, titolo, descrizione, tipo, data, ora, luogo, created_at
             FROM impegni
             WHERE tipo = :tipo'
        );

        $stmt->execute([':tipo' => $tipo]);
        $impegni = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //return
        return $impegni;
    }

    // CRUD functions

    public static function createImpegno(string $titolo, string $descrizione, string $tipo, string $data, string $ora, string $luogo): ?int
    {   
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'INSERT INTO impegni (
                titolo, descrizione, tipo, data, ora, luogo)
             VALUES (
                :titolo, :descrizione, :tipo, :data, :ora, :luogo)'
        );

        $stmt->execute([
            ':titolo' => $titolo, 
            ':descrizione' => $descrizione,
            ':tipo' => $tipo,
            ':data' => $data,
            ':ora' => $ora,
            ':luogo' => $luogo,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function updateImpegno(int $id, string $titolo, string $descrizione, string $tipo, string $data, string $ora, string $luogo): bool
    {   
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'UPDATE impegni 
             SET titolo = :titolo, 
                 descrizione = :descrizione, 
                 tipo = :tipo,
                 data = :data, 
                 ora = :ora, 
                 luogo = :luogo

             WHERE id = :id'
        );

        return $stmt->execute([
            ':id'   => $id,
            ':titolo' => $titolo, 
            ':descrizione' => $descrizione,
            ':tipo' => $tipo,
            ':data' => $data,
            ':ora' => $ora,
            ':luogo' => $luogo,
        ]);
    }

    // delete 

    public static function delete(int $id): bool
        {
            $pdo = Db::connect();

            $stmt = $pdo->prepare(
                'DELETE FROM impegni WHERE id = :id'
            );

            return $stmt->execute([':id' => $id]);
            
        }

}