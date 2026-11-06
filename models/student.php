<?php
require_once __DIR__ . '/../config/Database.php';

class Student {
    private $conn;
    private $table = 'etudiant';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($userId, $nom, $prenom, $dateNaissance) {
        $query = "INSERT INTO " . $this->table . " (id_user, nom, prenom, date_naissance, date_inscription_plateforme) 
                  VALUES (:id_user, :nom, :prenom, :date_naissance, CURDATE())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $userId);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $dateNaissance);

        return $stmt->execute();
    }

    public function getByUserId($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_user = :id_user";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getById($studentId) {
        $query = "SELECT e.*, u.email FROM " . $this->table . " e
                  JOIN user u ON e.id_user = u.id_user
                  WHERE e.id_etudiant = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $studentId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getEnrolledCourses($studentId) {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom, i.note, i.date_inscription_cours
                  FROM inscription i
                  JOIN cours c ON i.id_cours = c.id_cours
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE i.id_etudiant = :id_etudiant
                  ORDER BY i.date_inscription_cours DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAvailableCourses($studentId) {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants
                  FROM cours c
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE c.id_cours NOT IN (
                      SELECT id_cours FROM inscription WHERE id_etudiant = :id_etudiant
                  )
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function enrollCourse($studentId, $courseId) {
        // Vérifier si déjà inscrit
        $check = "SELECT * FROM inscription WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $stmt = $this->conn->prepare($check);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours.'];
        }

        $query = "INSERT INTO inscription (id_etudiant, id_cours, date_inscription_cours) 
                  VALUES (:id_etudiant, :id_cours, CURDATE())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Inscription réussie !'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
    }

    public function getGrades($studentId) {
        $query = "SELECT c.intitule, c.thematique, i.note, i.date_inscription_cours, p.nom as prof_nom, p.prenom as prof_prenom
                  FROM inscription i
                  JOIN cours c ON i.id_cours = c.id_cours
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE i.id_etudiant = :id_etudiant
                  ORDER BY i.date_inscription_cours DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAverageGrade($studentId) {
        $query = "SELECT AVG(note) as moyenne FROM inscription WHERE id_etudiant = :id_etudiant AND note IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['moyenne'] ? round($result['moyenne'], 2) : null;
    }

    public function updateProfile($studentId, $nom, $prenom, $dateNaissance) {
        $query = "UPDATE " . $this->table . " SET nom = :nom, prenom = :prenom, date_naissance = :date_naissance 
                  WHERE id_etudiant = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':date_naissance', $dateNaissance);
        $stmt->bindParam(':id', $studentId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Profil mis à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
    }
}
?>