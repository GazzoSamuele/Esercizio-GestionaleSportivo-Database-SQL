<?php

require_once __DIR__ . '/Db.php';


class News 
    {
       public static function findById(int $id): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, title, description, tipo, data, image_path, created_at
             FROM news
             WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        $news = $stmt->fetch();
        
        return $news ?: null;
    }


    public static function findByTitle(string $title): ?array
    {
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, title, description, tipo, data, image_path, created_at
             FROM news
             WHERE title = :title'
        );

        $stmt->execute([':title' => $title]);

        $news = $stmt->fetch();
        return $news ?: null;
    }

    public static function findByDate(string $data): ?array
    {
        
        $pdo = Db::connect();

        $stmt = $pdo->prepare(

            'SELECT id, title, description, tipo, data, image_path, created_at
             FROM news
             WHERE data = :data'
        );

        $stmt->execute([':data' => $data]);

        $news = $stmt->fetch();
        return $news ?: null;
    }

        // funzione della classe cerca per TIPO (tipo di notizia) 
    public static function findByTipo(string $tipo): array
    {
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, title, description, tipo, data, image_path, created_at
             FROM news
             WHERE tipo = :tipo'
        );

        //execute (esecuzione della query)

        $stmt->execute([':tipo' => $tipo]);

        //fetch
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //return
        return $news;
    }

     public static function findAllNews(): array
    {
            $pdo = Db::connect();

            $stmt = $pdo->query(
                'SELECT id, title, description, tipo, data, image_path, created_at
                 FROM news
                 ORDER BY created_at ASC'
            //prende tutti i parametri che ci servono e gli ordina in modo decrescente
            );
        return $stmt->fetchAll();
    }

    // CRUD functions

    // funzione della classe che CREA una NEWS
    // int in fondo perchè l'utente restuisce un id
    public static function createNews(string $title, string $description, string $data, string $tipo = 'notizia', ?string $image_path = null): ?int
    {   
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'INSERT INTO news (
                title, description, tipo, data, image_path)
             VALUES (
                :title, :description, :tipo, :data, :image_path)'
        );

        //execute (esecuzione della query)

        $stmt->execute([
            ':title' => $title, 
            ':description' => $description,
            ':tipo' => $tipo,
            ':data' => $data,
            ':image_path' => $image_path,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function updateNews(int $id, string $title, string $description, string $data, string $tipo = 'notizia', ?string $image_path = null): bool
    {   
        $existing = self::findByTitle($title);
            if ($existing && (int)$existing['id'] !== $id) {
                return false; // un'altra news ha già questo titolo
            }

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'UPDATE news 
             SET title = :title, 
                 description = :description, 
                 image_path = :image_path, 
                 tipo = :tipo, 
                 data = :data

             WHERE id = :id'
        );

        //execute (esecuzione della query)

        return $stmt->execute([
            ':id'   => $id,
            ':image_path' => $image_path,
            ':title' => $title, 
            ':description' => $description,
            ':tipo' => $tipo,
            ':data' => $data,
        ]);
    }
    // delete UFFICIALE

    public static function delete(int $id): bool
        {
            $pdo = Db::connect();

            $stmt = $pdo->prepare(
                'DELETE FROM news WHERE id = :id'
            );

            return $stmt->execute([':id' => $id]);
            
        }

    // delete(in questo caso per lo svuotamento dei campi)

    public static function deleteAll(): void
    {
        $pdo = Db::connect();
        $pdo->exec('DELETE FROM news');
    }
    }