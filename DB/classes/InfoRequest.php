<?php

require_once __DIR__ . '/Db.php';


class InfoRequest 
    {
        
    public static function findAllRequest(): array
    {
            $pdo = Db::connect();

            $stmt = $pdo->query(
                'SELECT id, name, email, phone, categoria, messaggio, created_at
                 FROM info_requests
                 ORDER BY created_at ASC'
            );
        return $stmt->fetchAll();
    }

    public static function create(string $name, string $email, string $phone, string $categoria, string $messaggio): ?int
    {   
        
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'INSERT INTO info_requests (name, email, phone, categoria, messaggio)
             VALUES (:name, :email, :phone, :categoria, :messaggio)'
        );

        $stmt->execute([
            ':name' => $name, 
            ':email' => $email,
            ':phone' => $phone,
            ':categoria' => $categoria,
            ':messaggio' => $messaggio,
        ]);

        return (int) $pdo->lastInsertId();

    }
}