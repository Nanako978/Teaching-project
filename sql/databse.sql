-- ============================================================
-- Études : base de données ecole_en_ligne
-- MySQL 8.0.16+ ou MariaDB 10.2+
-- ATTENTION : ce script supprime puis recrée la base ecole_en_ligne
-- ============================================================

DROP DATABASE IF EXISTS ecole_en_ligne;
CREATE DATABASE ecole_en_ligne CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecole_en_ligne;

-- ------------------------------------------------------------
-- Table USER : comptes de connexion de tous les rôles
-- ------------------------------------------------------------
CREATE TABLE user (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher', 'admin') NOT NULL,
    status ENUM('pending', 'active', 'suspended', 'rejected', 'archived') NOT NULL DEFAULT 'active',
    last_login DATETIME NULL,
    archived_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Table ETUDIANT : profil étudiant, lié à un compte user
-- ------------------------------------------------------------
CREATE TABLE etudiant (
    id_etudiant INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    date_inscription_plateforme DATE NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Table PROFESSEUR : profil professeur, lié à un compte user
-- ------------------------------------------------------------
CREATE TABLE professeur (
    id_professeur INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    specialite VARCHAR(100) NOT NULL,
    bio TEXT NULL,
    photo VARCHAR(255) NULL,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Table COURS
-- ------------------------------------------------------------
CREATE TABLE cours (
    id_cours INT PRIMARY KEY AUTO_INCREMENT,
    id_professeur INT NOT NULL,
    thematique VARCHAR(100) NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    duree_heures INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_professeur) REFERENCES professeur(id_professeur) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- Table INSCRIPTION : relie étudiants et cours, porte la note
-- (un étudiant ne peut s'inscrire qu'une fois à un même cours)
-- ------------------------------------------------------------
CREATE TABLE inscription (
    id_inscription INT PRIMARY KEY AUTO_INCREMENT,
    id_etudiant INT NOT NULL,
    id_cours INT NOT NULL,
    date_inscription_cours DATE NOT NULL,
    note DECIMAL(5,2) NULL CHECK (note BETWEEN 0 AND 20),
    UNIQUE (id_etudiant, id_cours),
    FOREIGN KEY (id_etudiant) REFERENCES etudiant(id_etudiant) ON DELETE CASCADE,
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON DELETE CASCADE
);

-- ============================================================
-- DONNÉES DE TEST
-- Mot de passe des comptes étudiants et professeurs : password
-- ============================================================

-- Administrateur : le mot de passe n'est pas publié.
-- Remplacer REMPLACER_PAR_VOTRE_HASH par un hash généré avec :
--   php -r 'echo password_hash("VotreMotDePasse", PASSWORD_DEFAULT);'
INSERT INTO user (email, password, role, status) VALUES
('admin@ecole.com', 'REMPLACER_PAR_VOTRE_HASH', 'admin', 'active');

-- Comptes professeurs (id_user 2 à 7, le dernier est en attente d'approbation)
INSERT INTO user (email, password, role, status) VALUES
('prof1@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof2@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof3@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof4@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof5@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('prof_pending@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'pending');

INSERT INTO professeur (id_user, nom, prenom, specialite, bio, photo) VALUES
(2, 'Dupont', 'Marie', 'Mathématiques', 'Enseigne l''algèbre et l''analyse depuis dix ans.', NULL),
(3, 'Martin', 'Jean', 'Physique', 'Passionné par la mécanique et l''électromagnétisme.', NULL),
(4, 'Bernard', 'Sophie', 'Informatique', 'Développeuse et formatrice en programmation et bases de données.', NULL),
(5, 'Dubois', 'Pierre', 'Chimie', 'Chimiste, spécialiste de chimie organique.', NULL),
(6, 'Laurent', 'Claire', 'Biologie', 'Biologiste, spécialiste de la cellule et de la génétique.', NULL);

-- Comptes étudiants (id_user 8 à 17)
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

-- Cours : 2 par professeur
INSERT INTO cours (id_professeur, thematique, intitule, description, duree_heures) VALUES
(1, 'Mathématiques', 'Algèbre linéaire', 'Introduction aux espaces vectoriels et matrices', 40),
(1, 'Mathématiques', 'Analyse mathématique', 'Étude des fonctions, limites et dérivées', 45),
(2, 'Physique', 'Mécanique classique', 'Étude des forces et du mouvement', 50),
(2, 'Physique', 'Électromagnétisme', 'Champs électriques et magnétiques', 45),
(3, 'Informatique', 'Programmation Python', 'Bases de la programmation en Python', 60),
(3, 'Informatique', 'Base de données', 'Conception et gestion de bases de données', 50),
(4, 'Chimie', 'Chimie organique', 'Étude des composés organiques', 55),
(4, 'Chimie', 'Thermodynamique', 'Principes de la thermodynamique chimique', 40),
(5, 'Biologie', 'Biologie cellulaire', 'Structure et fonction des cellules', 45),
(5, 'Biologie', 'Génétique', 'Principes de l''hérédité et de l''ADN', 50);

-- Inscriptions (l'étudiant 3, Hugo, n'a encore aucune note)
INSERT INTO inscription (id_etudiant, id_cours, date_inscription_cours, note) VALUES
(1, 1, '2024-01-15', 15.50),
(1, 2, '2024-01-15', 14.00),
(1, 5, '2024-01-20', 16.75),
(2, 1, '2024-01-16', 17.25),
(2, 3, '2024-01-16', 15.00),
(2, 6, '2024-01-22', 18.50),
(3, 2, '2024-01-18', NULL),
(3, 4, '2024-01-18', NULL),
(4, 5, '2024-01-20', 13.50),
(4, 7, '2024-01-25', 12.75),
(5, 1, '2024-01-22', 14.25),
(5, 9, '2024-01-28', 16.00),
(6, 3, '2024-01-24', 15.75),
(6, 8, '2024-01-30', 14.50),
(7, 2, '2024-01-26', 13.00),
(7, 10, '2024-02-02', 17.00),
(8, 4, '2024-01-28', 16.25),
(8, 6, '2024-02-03', 15.50),
(9, 7, '2024-02-01', 14.75),
(9, 9, '2024-02-05', 13.25),
(10, 5, '2024-02-03', 18.00),
(10, 10, '2024-02-08', 16.50);
