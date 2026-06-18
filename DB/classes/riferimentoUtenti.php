<?php 

require_once __DIR__ . '/Db.php';

class User 
{
    // funzione della classe cerca per ID (INT ID) e restituisce un array dell'id
    public static function findById(int $id): ?array
    {
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, name, email, role, created_at
             FROM users
             WHERE id = :id'
        );

        //execute (esecuzione della query)

        $stmt->execute([':id' => $id]);

        //fetch
        $user = $stmt->fetch();
        //return
        return $user ?: null;
    }


    // funzione della classe cerca per NAME (INT NAME) e restituisce un array dell'name
    public static function findByName(string $name): ?array
    {
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, name, email, role, created_at
             FROM users
             WHERE name = :name'
        );

        //execute (esecuzione della query)

        $stmt->execute([':name' => $name]);

        //fetch
        $user = $stmt->fetch();
        //return
        return $user ?: null;
    }


    // funzione della classe cerca per EMAIL (STRING EMAIL) e restituisce un array dell'email
    public static function findByEmail(string $email): ?array
    {
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, name, email, role, created_at
             FROM users
             WHERE email = :email'
        );

        //execute (esecuzione della query)

        $stmt->execute([':email' => $email]);

        //fetch
        $user = $stmt->fetch();
        //return
        return $user ?: null;
    }


    // funzione della classe cerca per ROLE (INT ROLE) e restituisce un array dell'role
    public static function findByRole(string $role): bool
    {
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'SELECT id, name, email, role, created_at
             FROM users
             WHERE role = :role'
        );

        //execute (esecuzione della query)

        $stmt->execute([':role' => $role]);

        //fetch
        $user = $stmt->fetch();
        //return
        return $user !== false;
    }

    // funzione della classe che CREA un UTENTE

    // int in fondo perchè l'utente restuisce un id
    public static function create(string $name, string $email, string $password, string $role = 'client'): ?int
    {   
        //controllo se esiste già un utente con quell'email inserita
        if(self::findByEmail($email)){
            return null;
        }
        // connettere il tutto al db

        $pdo = Db::connect();

        // preparare la query per il db

        $stmt = $pdo->prepare(

            //la query per andare a prendere l'id:
            'INSERT INTO users (name, email, password, role)
             VALUES (:name, :email, :password, :role)'
        );

        //execute (esecuzione della query)

        $stmt->execute([
            ':name' => $name, 
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => $role,
        ]);

        return (int) $pdo->lastInsertId();

    }

    public static function findAllUsers(): array
    {
            $pdo = Db::connect();

            $stmt = $pdo->query(
                'SELECT id, name, email, role, created_at
                 FROM users
                 ORDER BY created_at ASC'
            //prende tutti i parametri che ci servono e gli ordina in modo decrescente
            );
        return $stmt->fetchAll();
    }
    public static function countAll(): ?int
        {
        // connettere il tutto al db

            $pdo = Db::connect();

            // sto inviando una funzione, quindi inseriamo solo query

            $stmt = $pdo->query(
                //la query per andare a prendere l'id:
                'SELECT COUNT(*) AS total FROM users'            
            );
            // si fa il fetch per ritornare al totale utenti e dare il risultato della funzione
            return (int) $stmt->fetchColumn();
        }
    public static function updateRole(int $id, string $role): bool
        {
            // controllo se l'utente ha un ruolo
            if (!in_array($role, ['client', 'admin'], true)){

            // se ha un ruolo, ritorna false

                return false;
            }

            $pdo = Db::connect();

            // aggiorna nella tabella utenti settando il ruolo prendendo l'id
             $stmt = $pdo->prepare(
            'UPDATE users SET role = :role WHERE id= :id'

           );
            return $stmt->execute([':role' => $role, ':id' => $id]);
            
        }
    public static function updateUser(int $id, string $name, string $email, string $role): bool
        {
           //controllo se l'email non sia già utlizzata da un'altro utente
           $existing = self::findByEmail($email);

           if($existing && (int) $existing ['id'] !== $id){
             return false; // email già utilizzata da qualcun'altro
           }

           $pdo = Db::connect();

           $stmt = $pdo->prepare(
                //query su update di nome 
                'UPDATE users
                 SET name = :name, email= :email, role= :role
                 WHERE id = :id'
           );
            
           return $stmt->execute([
            ':id' => $id,
            ':name' => $name, 
            ':email' => $email,
            ':role' => $role,

           ]);
        }
    public static function delete(int $id): bool
        {
            $pdo = Db::connect();

            $stmt = $pdo->prepare(
                'DELETE FROM users WHERE id = :id'
            );

            return $stmt->execute([':id' => $id]);
            
        }
     
}
