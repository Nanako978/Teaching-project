<?php
require_once __DIR__ . '/../config/Database.php';

class Teacher {
    private $conn;
    private $table = 'professeur';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($userId, $nom, $prenom, $specialite) {
        $query = "INSERT INTO " . $this->table . " (id_user, nom, prenom, specialite) 
                  VALUES (:id_user, :nom, :prenom, :specialite)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':specialite', $specialite);

        return $stmt->execute();
    }

    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getById($teacherId) {
        $query = "SELECT p.*, u.email FROM " . $this->table . " p
                  JOIN user u ON p.id_user = u.id_user
                  WHERE p.id_professeur = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $teacherId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getMyCourses($teacherId) {
        $query = "SELECT c.*, 
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours AND note IS NOT NULL) as nb_notes
                  FROM cours c
                  WHERE c.id_professeur = :id_professeur
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_professeur', $teacherId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCourseStudents($courseId, $teacherId) {
        // Vérifier que le cours appartient bien au professeur
        $checkQuery = "SELECT id_cours FROM cours WHERE id_cours = :id_cours AND id_professeur = :id_professeur";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id_cours', $courseId);
        $checkStmt->bindParam(':id_professeur', $teacherId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return [];
        }

        $query = "SELECT e.id_etudiant, e.nom, e.prenom, e.email, i.note, i.date_inscription_cours
                  FROM inscription i
                  JOIN etudiant e ON i.id_etudiant = e.id_etudiant
                  WHERE i.id_cours = :id_cours
                  ORDER BY e.nom, e.prenom";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function addGrade($courseId, $studentId, $teacherId, $note) {
        // Vérifier que le cours appartient au professeur
        $checkQuery = "SELECT id_cours FROM cours WHERE id_cours = :id_cours AND id_professeur = :id_professeur";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id_cours', $courseId);
        $checkStmt->bindParam(':id_professeur', $teacherId);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'Vous n\'avez pas accès à ce cours.'];
        }

        // Vérifier que la note est entre 0 et 20
        if ($note < 0 || $note > 20) {
            return ['success' => false, 'message' => 'La note doit être entre 0 et 20.'];
        }

        $query = "UPDATE inscription SET note = :note WHERE id_cours = :id_cours AND id_etudiant = :id_etudiant";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->bindParam(':id_etudiant', $studentId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Note ajoutée avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'ajout de la note.'];
    }

    public function getStatistics($teacherId) {
        $stats = [];

        // Nombre de cours
        $query1 = "SELECT COUNT(*) as nb_cours FROM cours WHERE id_professeur = :id";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam(':id', $teacherId);
        $stmt1->execute();
        $stats['nb_cours'] = $stmt1->fetch()['nb_cours'];

        // Nombre total d'étudiants
        $query2 = "SELECT COUNT(DISTINCT i.id_etudiant) as nb_etudiants 
                   FROM inscription i
                   JOIN cours c ON i.id_cours = c.id_cours
                   WHERE c.id_professeur = :id";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam(':id', $teacherId);
        $stmt2->execute();
        $stats['nb_etudiants'] = $stmt2->fetch()['nb_etudiants'];

        // Nombre de notes attribuées
        $query3 = "SELECT COUNT(*) as nb_notes 
                   FROM inscription i
                   JOIN cours c ON i.id_cours = c.id_cours
                   WHERE c.id_professeur = :id AND i.note IS NOT NULL";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->bindParam(':id', $teacherId);
        $stmt3->execute();
        $stats['nb_notes'] = $stmt3->fetch()['nb_notes'];

        return $stats;
    }

    public function updateProfile($teacherId, $nom, $prenom, $specialite) {
        $query = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, specialite = :specialite 
                  WHERE id_professeur = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':specialite', $specialite);
        $stmt->bindParam(':id', $teacherId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Profil mis à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
    }
}
?>