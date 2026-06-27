-- CREO IL DATABASE

    -- creo il db se non esiste dal nome dbSocietaSportiva_app
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

        -- colonna della scadenza quota codice univoco
        -- DEFAULT NULL così gli utenti esistenti non si rompono
        quota_scadenza DATE DEFAULT NULL,

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

     -- esempio di inserimento di un nuovo utente(non è da scrivere in questo file però)

-- TABELLA CLIENTI CREATA
--   CREATE TABLE clienti(
	
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     nome VARCHAR(50),
--     cognome VARCHAR(50),
--     email VARCHAR(100),
--     eta INT
-- );
-- DATI INSERITI DEI CLIENTI DELLA TABELLA 
-- INSERT INTO clienti(nome , cognome, email, eta)

-- VALUES  ('Luca', 'Bianchi','luca@test.it', 48),
-- 		('Marcello', 'Gallo','marcello@test.it', 34),
--         ('Giovanni', 'Verdi','giovanni@test.it', 45);

-- SESSION Types

-- CREATE TABLE IF NOT EXISTS session_types(

--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(100) NOT NULL,
--     description TEXT,
--     duration_hours INT NOT NULL,
--     price DECIMAL(10,2) NOT NULL,
--     is_active BOOLEAN NOT NULL DEFAULT TRUE,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

-- ) ENGINE=InnoDB;


-- Bookings 

-- CREATE TABLE IF NOT EXISTS bookings (

--     id INT AUTO_INCREMENT PRIMARY KEY,
--     user_id INT NOT NULL,
--     session_type_id INT NOT NULL,

--     scheduled_at DATETIME NOT NULL,
--     prezzo_pagato DECIMAL(10,2) NOT NULL,
--     status ENUM('In attesa','pagato','cancelled','completed') NOT NULL DEFAULT 'In attesa',
--     payment_ref VARCHAR(100) DEFAULT NULL,
--     notes TEXT,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

--     -- tabella aggiuntiva creata per fare riferimento ad un'altra determinata tabella
--     -- user(id) viene cancellato a cascata tramite ON DELETE CASCADE
--     FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,

--     FOREIGN KEY(session_type_id) REFERENCES session_types(id)

-- ) ENGINE=InnoDB;

-- serve per cancellare un determinato database da phpmyadimn
-- DROP DATABASE dbSocietaSportiva_app;

-- per ricreare un database
-- 1) cmd
-- 2) // C:\xampp\htdocs\Gestionale-Hockey>C:\xampp\mysql\bin\mysql.exe -u root -p < DB\sql\schema.sql



-- Products

CREATE TABLE IF NOT EXISTS products(

    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    image_path VARCHAR(255) DEFAULT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'sport',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;


-- Purchases

CREATE TABLE IF NOT EXISTS purchases(

    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    prezzo_pagato DECIMAL(10,2) NOT NULL,
    status ENUM('In attesa', 'pagato', 'Rimborsato') NOT NULL DEFAULT 'In attesa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    pronto_ritiro BOOLEAN NOT NULL DEFAULT FALSE

    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(product_id) REFERENCES products(id)

) ENGINE=InnoDB;

-- Calendar

CREATE TABLE IF NOT EXISTS calendar(

    id INT AUTO_INCREMENT PRIMARY KEY,
    squadra_casa VARCHAR(50) NOT NULL,
    squadra_ospite VARCHAR(50) NOT NULL,
    data DATE NOT NULL,
    categoria VARCHAR(15) NOT NULL,
    gol_casa INT NOT NULL,
    gol_ospite INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;

-- News & Comunication

CREATE TABLE IF NOT EXISTS news(

    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    -- ENUM distingue le notizie sportive dalle comunicazioni societarie 
    -- una voce può essere solo 'notizia' o 'comunicazione'
    tipo ENUM('notizia', 'comunicazione') NOT NULL DEFAULT 'notizia',
    data DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;

-- Codice (monouso)
CREATE TABLE IF NOT EXISTS registration_codes(

    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    -- con vincolo UNIQUE serve per cercarlo e validarlo
    used BOOLEAN NOT NULL DEFAULT FALSE,
    -- used serve per utilizzare il codice una volta e eliminarlo
    used_by INT DEFAULT NULL,
    -- used_by per sapere quale utente ha usato quel codice
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY(used_by) REFERENCES users(id) ON DELETE SET NULL

) ENGINE=InnoDB;

-- Richiesta informazioni nel form dei contatti

CREATE TABLE IF NOT EXISTS info_requests(

    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL, 
    phone VARCHAR(30) DEFAULT NULL, 
    categoria VARCHAR(50) DEFAULT NULL,
    messaggio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
) ENGINE=InnoDB;

-- Impegni del giocatore(allenamenti, partite, ecc..)

CREATE TABLE IF NOT EXISTS impegni(

    id INT AUTO_INCREMENT PRIMARY KEY, 
    titolo VARCHAR(150) NOT NULL, 
    descrizione TEXT,
    tipo ENUM('allenamento', 'partita', 'coppa', 'riunione', 'altro') NOT NULL DEFAULT 'allenamento',
    data DATE NOT NULL, 
    ora TIME DEFAULT NULL, 
    luogo VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB;
