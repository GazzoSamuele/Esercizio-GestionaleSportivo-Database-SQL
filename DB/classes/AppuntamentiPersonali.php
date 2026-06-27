<?php 

require_once __DIR__ . '/Db.php';


class AppuntamentiPersonali 
{
    public static function findByUserId(int $userId): array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, user_id, titolo, tipo, data, ora, appunti
             FROM appuntamenti_personali 
             WHERE user_id = :user_id'
        );

        $stmt->execute([':user_id' => $userId]);

        $appuntamenti_personali = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $appuntamenti_personali;
    }

    // CRUD

    //create

    public static function create(int $user_id, string $titolo, string $data, string $ora, string $appunti, string $tipo = 'allenamento extra'): bool
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'INSERT INTO appuntamenti_personali (user_id, titolo, 
                         data, ora, appunti, tipo)
             VALUES (:user_id, :titolo, :data, :ora, :appunti, :tipo)'
        );

        return $stmt->execute([
            ':user_id' => $user_id, 
            ':titolo' => $titolo, 
            ':data' => $data,
            ':ora' => $ora, 
            ':appunti' => $appunti, 
            ':tipo' => $tipo, 
        ]);
    }

    // delete(in questo caso per lo svuotamento dei campi)

    public static function delete(int $id, int $userId): bool
    {
        $pdo = Db::connect();
        $stmt = $pdo->prepare(
            'DELETE FROM appuntamenti_personali 
             WHERE id = :id 
             AND user_id = :uid'
        );
        return $stmt->execute([':id' => $id, ':uid' => $userId]);
    }
}