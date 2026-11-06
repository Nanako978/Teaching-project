-- Supprimer la base de données si elle existe
DROP DATABASE IF EXISTS ecole_en_ligne;

-- Créer la base de données
CREATE DATABASE ecole_en_ligne CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecole_en_ligne;

-- Table USER (gère tous les types d'utilisateurs)
CREATE TABLE user (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL,
    status ENUM('active', 'pending', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table ETUDIANT
CREATE TABLE etudiant (
    id_etudiant INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    date_inscription_plateforme DATE NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);

-- Table PROFESSEUR
CREATE TABLE professeur (
    id_professeur INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);

-- Table COURS
CREATE TABLE cours (
    id_cours INT PRIMARY KEY AUTO_INCREMENT,
    thematique VARCHAR(100) NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    description TEXT NULL,
    duree_heures INT NOT NULL,
    id_professeur INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_professeur) REFERENCES professeur(id_professeur) ON DELETE CASCADE
);

-- Table INSCRIPTION
CREATE TABLE inscription (
    id_etudiant INT NOT NULL,
    id_cours INT NOT NULL,
    date_inscription_cours DATE NOT NULL,
    note DECIMAL(4,2) NULL CHECK (note BETWEEN 0 AND 20),
    PRIMARY KEY (id_etudiant, id_cours),
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id_etudiant) ON DELETE CASCADE,
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON DELETE CASCADE
);

-- Créer un admin par défaut
-- Mot de passe : admin123 (hashé avec password_hash)
INSERT INTO user (email, password, role, status) VALUES 
('admin@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Données de test pour PROFESSEURS
INSERT INTO user (email, password, role, status) VALUES 
('prof1@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof2@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof3@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof4@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof5@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof_pending@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'pending');

INSERT INTO professeur (id_user, nom, prenom, specialite) VALUES 
(2, 'Dupont', 'Marie', 'Mathématiques'),
(3, 'Martin', 'Jean', 'Physique'),
(4, 'Bernard', 'Sophie', 'Informatique'),
(5, 'Dubois', 'Pierre', 'Chimie'),
(6, 'Laurent', 'Claire', 'Biologie');

-- Données de test pour ETUDIANTS
INSERT INTO user (email, password, role, status) VALUES 
('etudiant1@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant2@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant3@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant4@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant5@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant6@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant7@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant8@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant9@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('etudiant10@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active');

INSERT INTO etudiant (id_user, nom, prenom, date_naissance, date_inscription_plateforme) VALUES 
(8, 'Lefebvre', 'Lucas', '2000-03-15', '2024-01-10'),
(9, 'Moreau', 'Emma', '2001-07-22', '2024-01-12'),
(10, 'Simon', 'Hugo', '1999-11-05', '2024-01-15'),
(11, 'Michel', 'Léa', '2002-02-18', '2024-01-18'),
(12, 'Garcia', 'Nathan', '2000-09-30', '2024-01-20'),
(13, 'Rodriguez', 'Chloé', '2001-04-12', '2024-01-22'),
(14, 'Martinez', 'Tom', '2000-06-25', '2024-01-25'),
(15, 'Fernandez', 'Sarah', '2002-01-08', '2024-01-28'),
(16, 'Lopez', 'Alexandre', '1999-12-14', '2024-02-01'),
(17, 'Gonzalez', 'Julie', '2001-08-19', '2024-02-05');

-- Données de test pour COURS (au moins 10 cours, au moins 1 prof avec 2+ cours)
INSERT INTO cours (thematique, intitule, description, duree_heures, id_professeur) VALUES 
('Mathématiques', 'Algèbre linéaire', 'Introduction aux espaces vectoriels et matrices', 40, 1),
('Mathématiques', 'Analyse mathématique', 'Étude des fonctions, limites et dérivées', 45, 1),
('Physique', 'Mécanique classique', 'Étude des forces et du mouvement', 50, 2),
('Physique', 'Électromagnétisme', 'Champs électriques et magnétiques', 45, 2),
('Informatique', 'Programmation Python', 'Bases de la programmation en Python', 60, 3),
('Informatique', 'Base de données', 'Conception et gestion de bases de données', 50, 3),
('Chimie', 'Chimie organique', 'Étude des composés organiques', 55, 4),
('Chimie', 'Thermodynamique', 'Principes de la thermodynamique chimique', 40, 4),
('Biologie', 'Biologie cellulaire', 'Structure et fonction des cellules', 45, 5),
('Biologie', 'Génétique', 'Principes de l\'hérédité et de l\'ADN', 50, 5);

-- Données de test pour INSCRIPTIONS (au moins 1 étudiant sans note)
INSERT INTO inscription (id_etudiant, id_cours, date_inscription_cours, note) VALUES 
-- Etudiant 1 (Lucas) - avec notes
(1, 1, '2024-01-15', 15.50),
(1, 2, '2024-01-15', 14.00),
(1, 5, '2024-01-20', 16.75),
-- Etudiant 2 (Emma) - avec notes
(2, 1, '2024-01-16', 17.25),
(2, 3, '2024-01-16', 15.00),
(2, 6, '2024-01-22', 18.50),
-- Etudiant 3 (Hugo) - SANS NOTE (répond à la contrainte)
(3, 2, '2024-01-18', NULL),
(3, 4, '2024-01-18', NULL),
-- Etudiant 4 (Léa) - avec notes
(4, 5, '2024-01-20', 13.50),
(4, 7, '2024-01-25', 12.75),
-- Etudiant 5 (Nathan) - avec notes
(5, 1, '2024-01-22', 14.25),
(5, 9, '2024-01-28', 16.00),
-- Etudiant 6 (Chloé) - avec notes
(6, 3, '2024-01-24', 15.75),
(6, 8, '2024-01-30', 14.50),
-- Etudiant 7 (Tom) - avec notes
(7, 2, '2024-01-26', 13.00),
(7, 10, '2024-02-02', 17.00),
-- Etudiant 8 (Sarah) - avec notes
(8, 4, '2024-01-28', 16.25),
(8, 6, '2024-02-03', 15.50),
-- Etudiant 9 (Alexandre) - avec notes
(9, 7, '2024-02-01', 14.75),
(9, 9, '2024-02-05', 13.25),
-- Etudiant 10 (Julie) - avec notes
(10, 5, '2024-02-03', 18.00),
(10, 10, '2024-02-08', 16.50);

-- Vérification des contraintes
-- Au moins 10 étudiants : ✓ (10 étudiants)
-- Au moins 5 professeurs : ✓ (5 professeurs + 1 pending)
-- Au moins 10 cours : ✓ (10 cours)
-- Au moins 1 étudiant sans note : ✓ (Etudiant 3 - Hugo)
-- Au moins 1 prof avec 2+ cours : ✓ (Tous les profs ont 2 cours)