<?php 

require_once __DIR__ . '/Db.php';

class Products 
{
    public static function findById(int $id): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, name, description, price, is_active, image_path, created_at
             FROM products
             WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        $products = $stmt->fetch();
        
        return $products ?: null;
    }


    public static function findByName(string $name): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, name, description, price, is_active, image_path, created_at
             FROM products
             WHERE name = :name'
        );

        $stmt->execute([':name' => $name]);

        $products = $stmt->fetch();
        return $products ?: null;
    }

    public static function findByPrice(int $price): ?array
    {
        
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, name, description, price, is_active, image_path, created_at
             FROM products
             WHERE price = :price'
        );

        $stmt->execute([':price' => $price]);

        $products = $stmt->fetch();
        return $products ?: null;
    }

    public static function findIsActive(bool $isActive): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(
            'SELECT id, name, description, price, is_active, image_path, created_at
            FROM products
            WHERE is_active = :is_active'
        );

        $stmt->execute([
            ':is_active' => $isActive ? 1 : 0
        ]);

        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $products ?: null;
    }

     public static function findAllProducts(): array
    {
            $pdo = Db::connect();

            $stmt = $pdo->query(
                'SELECT id, name, description, price, is_active, image_path, created_at
                 FROM products
                 ORDER BY created_at ASC'
            //prende tutti i parametri che ci servono e gli ordina in modo decrescente
            );
        return $stmt->fetchAll();
    }

    // CRUD functions

    // funzione della classe che CREA un PRODOTTO

    // int in fondo perchè l'utente restuisce un id
    public static function create(string $name, string $description, float $price, int $is_active = 1, ?string $image_path = null): ?int
    {   
        //controllo se esiste già un prodotto con quel nome inserito
        if(self::findByName($name)){
            return null;
        }
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'INSERT INTO products (name, description, price, is_active, image_path)
             VALUES (:name, :description, :price, :is_active, :image_path)'
        );

        //execute (esecuzione della query)

        $stmt->execute([
            ':name' => $name, 
            ':image_path' => $image_path,
            ':description' => $description,
            ':price' => $price,
            ':is_active' => $is_active,
        ]);

        return (int) $pdo->lastInsertId();

    }

    public static function updateProduct(int $id, string $name, string $description, float $price, int $is_active = 1, ?string $image_path = null): bool
        {
           //controllo se il nome non sia già utlizzato da un'altro prodotto
           $existing = self::findByName($name);

           if($existing && (int) $existing ['id'] !== $id){
             return false; // name già utilizzato da qualcun'altro
           }

           $pdo = Db::connect();

           $stmt = $pdo->prepare(
                //query su update di nome 
                'UPDATE products
                 SET name = :name, description = :description, price = :price,
                     is_active = :is_active, image_path = :image_path
                 WHERE id = :id'
    );
            
           return $stmt->execute([
            ':id' => $id,
            ':name' => $name, 
            ':description' => $description, 
            ':price' => $price,
            ':is_active' => $is_active, 
            ':image_path' => $image_path, 
    ]);
}
    public static function delete(int $id): bool
        {
            $pdo = Db::connect();

            $stmt = $pdo->prepare(
                'DELETE FROM products WHERE id = :id'
            );

            return $stmt->execute([':id' => $id]);
            
        }
    
}