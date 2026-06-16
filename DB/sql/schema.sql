-- CREO IL DATABASE

    -- creo il db se non esiste dal nome mentoring_app
    CREATE DATABASE IF NOT EXISTS dbSocietaSportiva_app
        CHARACTER SET utf8mb4
        COLLATE utf8mb4_unicode_ci;

-- UNA VOLTA CREATO, UTILIZZALO

USE dbSocietaSportiva_app;

--  TABELLA UTENTI

    CREATE TABLE IF NOT EXISTS users (

        -- id che aumenterà di valore all'aumentare degli utenti
        -- e al suo interno contiene una chiave primaria
        id INT AUTO_INCREMENT PRIMARY KEY,

        -- nome come variabile striga di 100 caratterie
        -- è un requisito, non può essere omessa
        -- VARCHAR rappresenta la stringa di sql
        name VARCHAR(100) NOT NULL, 

        -- stessa cosa del nome ma l'email è un valore univoco
        -- non posso avere due utenti che si registrano al database con la stessa email
        email VARCHAR(100) NOT NULL UNIQUE, 

        password VARCHAR(255) NOT NULL,

        -- ENUM rappresenta un dato che può avere due valori
        -- inserendo ENUM il database rifiuta ogni tipo di dato a parte che 'client' e 'admin'?
        -- il valore di DEFAULT stampato sarà 'client'
        role ENUM('client', 'admin') NOT NULL DEFAULT 'client',

        -- è un placeholder che rappresenta quando(a che data) è stato creato l'user
        -- TIMESTAMP rappresenta il "timbro" con cui salvo la data
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    )ENGINE=InnoDB;

     -- quando si creano le query controllare l'ordine delle proprietà che stiamo inserendo
     -- perchè se nella tabella c'è: nome, cognome, eta, email
     -- bisogna inserire le proprietà in ordine altrimenti si rischia di inserire un 
     -- valore di una proprietà diversa da quella in cui vogliamo effettivamente inserirla

     --esempio di inserimento di un nuovo utente(non è da scrivere in questo file però)

 TABELLA CLIENTI CREATA
--   CREATE TABLE clienti(
	
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(50),
--     cognome VARCHAR(50),
--     email VARCHAR(100),
--     eta INT
-- );
DATI INSERITI DEI CLIENTI DELLA TABELLA 
-- INSERT INTO clienti(nome , cognome, email, eta)

-- VALUES  ('Luca', 'Bianchi','luca@test.it', 48),
-- 		('Marcello', 'Gallo','marcello@test.it', 34),
--         ('Giovanni', 'Verdi','giovanni@test.it', 45);

--SESSION Types

CREATE TABLE IF NOT EXISTS session_types(

    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_hours INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


--Bookings 

CREATE TABLE IF NOT EXISTS bookings (

    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_type_id INT NOT NULL,

    scheduled_at DATETIME NOT NULL,
    price_paid DECIMAL(10,2) NOT NULL,
    status ENUM('pending','paid','cencelled','completed') NOT NULL DEFAULT 'pending',
    payment_ref VARCHAR(100) DEFAULT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- tabella aggiuntiva creata per fare riferimento ad un'altra determinata tabella
    -- user(id) viene cancellato a cascata tramite ON DELETE CASCADE
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,

    FOREIGN KEY(session_type_id) REFERENCES session_types(id)

) ENGINE=InnoDB;

-- serve per cancellare un determinato database da phpmyadimn
-- DROP DATABASE dbSocietaSportiva_app;

-- per ricreare un database
-- 1) cmd
-- 2) // C:\xampp\htdocs\Gestionale-Hockey>C:\xampp\mysql\bin\mysql.exe -u root -p < sql\schema.sql



--Products

CREATE TABLE IF NOT EXISTS products(

    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    file_path VARCHAR(255) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


--Purchases

CREATE TABLE IF NOT EXISTS purchases(

    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    price_paid DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'refunded') NOT NULL DEFAULT 'pending',
    payment_ref VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(product_id) REFERENCES products(id)

) ENGINE=InnoDB;