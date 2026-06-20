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
                        AS immagine, purc.price_paid, purc.status, purc.created_at
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
                purc.price_paid, purc.status, purc.created_at
                FROM purchases purc
                JOIN products p ON purc.product_id = p.id
                WHERE purc.user_id = :uid
                ORDER BY purc.created_at DESC'
            );
            $stmt->execute([':uid' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // funzione che crea un nuovo prodotto da inserire nel DB

        public static function create(int $userId, int $productId, string $pricePaid, string $status = 'pending'): int
        {
            $pdo = Db::connect();
            $stmt = $pdo->prepare(
               'INSERT INTO purchases (user_id, product_id, price_paid, status)
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