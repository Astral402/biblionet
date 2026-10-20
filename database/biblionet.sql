CREATE DATABASE IF NOT EXISTS biblionet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblionet;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS emprunts;
DROP TABLE IF EXISTS adherents;
DROP TABLE IF EXISTS livres;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;
CREATE TABLE users (
    id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom       VARCHAR(100) NOT NULL,
    email     VARCHAR(150) NOT NULL,
    password  VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE livres (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre           VARCHAR(200) NOT NULL,
    auteur          VARCHAR(150) NOT NULL,
    categorie       VARCHAR(80)  NOT NULL,
    annee           SMALLINT UNSIGNED NULL,
    nb_exemplaires  INT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE adherents (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom        VARCHAR(80)  NOT NULL,
    prenom     VARCHAR(80)  NOT NULL,
    classe     VARCHAR(50)  NOT NULL,
    telephone  VARCHAR(30)  NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE emprunts (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    livre_id      INT UNSIGNED NOT NULL,
    adherent_id   INT UNSIGNED NOT NULL,
    date_emprunt  DATE NOT NULL,
    date_retour   DATE NULL DEFAULT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_emprunts_livre    FOREIGN KEY (livre_id)    REFERENCES livres (id),
    CONSTRAINT fk_emprunts_adherent FOREIGN KEY (adherent_id) REFERENCES adherents (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (nom, email, password) VALUES
('Bibliothécaire INPTIC', 'bibliothecaire@inptic.ga', '$2y$10$eNFoNyTlGZwz2mBMB5ew5.cnH7bFu1UJj5RrRowX/HsdchA4U2CI.');
INSERT INTO livres (titre, auteur, categorie, annee, nb_exemplaires) VALUES
('Le Petit Prince', 'Antoine de Saint-Exupéry', 'Roman', 1943, 3),
('L''Étranger', 'Albert Camus', 'Roman', 1942, 2),
('Germinal', 'Émile Zola', 'Roman', 1885, 2),
('Le Père Goriot', 'Honoré de Balzac', 'Roman', 1835, 2),
('Les Misérables', 'Victor Hugo', 'Roman', 1862, 1),
('Antigone', 'Jean Anouilh', 'Théâtre', 1944, 2),
('Cahier d''un retour au pays natal', 'Aimé Césaire', 'Poésie', 1939, 1),
('L''Aventure ambiguë', 'Cheikh Hamidou Kane', 'Littérature africaine', 1961, 3),
('Une si longue lettre', 'Mariama Bâ', 'Littérature africaine', 1979, 3),
('Les Soleils des indépendances', 'Ahmadou Kourouma', 'Littérature africaine', 1968, 2),
('Le Vieux Nègre et la médaille', 'Ferdinand Oyono', 'Littérature africaine', 1956, 2),
('Elonga', 'Angèle Rawiri', 'Littérature africaine', 1980, 2),
('Au bout du silence', 'Laurent Owondo', 'Littérature africaine', 1985, 1),
('Fureurs et cris de femmes', 'Angèle Rawiri', 'Littérature africaine', 1989, 1),
('Clean Code', 'Robert C. Martin', 'Informatique', 2008, 1),
('Design Patterns', 'Erich Gamma et al.', 'Informatique', 1994, 1),
('Le Langage C', 'Brian W. Kernighan, Dennis M. Ritchie', 'Informatique', 1978, 2),
('JavaScript: The Good Parts', 'Douglas Crockford', 'Informatique', 2008, 2),
('Réseaux', 'Andrew S. Tanenbaum', 'Réseaux', 2011, 2),
('Systèmes d''exploitation', 'Andrew S. Tanenbaum', 'Informatique', 2008, 1);
INSERT INTO adherents (nom, prenom, classe, telephone) VALUES
('Mba', 'Ella', 'L1 GI', '+241 06 00 00 01'),
('Obiang', 'Brice', 'L1 GI', '+241 06 00 00 02'),
('Nze', 'Grâce', 'L2 GI', '+241 06 00 00 03'),
('Ndong', 'Yannick', 'L2 GI', '+241 06 00 00 04'),
('Moussavou', 'Divine', 'L2 GI', '+241 06 00 00 05'),
('Mintsa', 'Prisca', 'L3 GI', '+241 06 00 00 06'),
('Ondo', 'Hervé', 'L3 GI', '+241 06 00 00 07'),
('Bouanga', 'Sandrine', 'L1 GI', '+241 06 00 00 08'),
('Nguema', 'Axel', 'L3 GI', '+241 06 00 00 09'),
('Mouketou', 'Merveille', 'L2 GI', '+241 06 00 00 10'),
('Ngoua', 'Landry', 'L1 GI', '+241 06 00 00 11'),
('Ndouna', 'Jordan', 'L3 GI', '+241 06 00 00 12');
INSERT INTO emprunts (livre_id, adherent_id, date_emprunt, date_retour) VALUES
(15,  3, DATE_SUB(CURDATE(), INTERVAL 20 DAY), NULL),
( 1,  1, DATE_SUB(CURDATE(), INTERVAL 5 DAY),  NULL),
( 8,  6, DATE_SUB(CURDATE(), INTERVAL 3 DAY),  NULL),
(19,  9, DATE_SUB(CURDATE(), INTERVAL 2 DAY),  NULL),
(17, 10, DATE_SUB(CURDATE(), INTERVAL 1 DAY),  NULL),
( 2,  2, DATE_SUB(CURDATE(), INTERVAL 40 DAY), DATE_SUB(CURDATE(), INTERVAL 30 DAY)),
( 9,  4, DATE_SUB(CURDATE(), INTERVAL 35 DAY), DATE_SUB(CURDATE(), INTERVAL 25 DAY)),
(12,  5, DATE_SUB(CURDATE(), INTERVAL 28 DAY), DATE_SUB(CURDATE(), INTERVAL 18 DAY)),
(16,  7, DATE_SUB(CURDATE(), INTERVAL 22 DAY), DATE_SUB(CURDATE(), INTERVAL 12 DAY));
