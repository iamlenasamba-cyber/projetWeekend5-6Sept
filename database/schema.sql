CREATE DATABASE IF NOT EXISTS mon_projet_weekend 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE mon_projet_weekend;

DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS salles;
DROP TABLE IF EXISTS types_salle;

CREATE TABLE types_salle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO types_salle (nom) VALUES
('cours'),
('informatique'),
('laboratoire'),
('amphitheatre'),
('reunion');

CREATE TABLE salles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    batiment VARCHAR(100) NOT NULL,
    capacite INT NOT NULL,
    type_id INT NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_salles_types 
        FOREIGN KEY (type_id) 
        REFERENCES types_salle(id) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    salle_id INT NOT NULL,
    responsable VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    motif VARCHAR(255) NOT NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NOT NULL,
    statut ENUM('confirmee', 'annulee') NOT NULL DEFAULT 'confirmee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_reservations_salles 
        FOREIGN KEY (salle_id) 
        REFERENCES salles(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO salles (nom, batiment, capacite, type_id, active) VALUES
('Dev Web', 'Bâtiment Informatique', 40, (SELECT id FROM types_salle WHERE nom = 'cours'), 1),
('Ref Dig', 'Bâtiment Informatique', 25, (SELECT id FROM types_salle WHERE nom = 'informatique'), 1),
('Hackeuse', 'Bâtiment Informatique', 200, (SELECT id FROM types_salle WHERE nom = 'amphitheatre'), 1),
('Dev Data', 'Bâtiment Informatique', 12, (SELECT id FROM types_salle WHERE nom = 'cours'), 0);

INSERT INTO reservations (salle_id, responsable, email, motif, date_debut, date_fin, statut) VALUES
(1, 'Mamadou Diallo', 'mamadou.diallo@example.com', 'Cours d algorithmique avancee', '2026-09-10 08:00:00', '2026-09-10 10:00:00', 'confirmee'),
(2, 'Awa Ndiaye', 'awa.ndiaye@example.com', 'TP de programmation Web PHP', '2026-09-10 10:30:00', '2026-09-10 12:30:00', 'confirmee');