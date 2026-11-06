<?php
require_once __DIR__ . '/../config/Database.php';

class Inscription {
    private $conn;
    private $table = 'inscription';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function isEnrolled($studentId, $courseId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function enroll($studentId, $courseId) {
        if ($this->isEnrolled($studentId, $courseId)) {
            return ['success' => false, 'message' => 'Vous êtes déjà inscrit à ce cours.'];
        }

        $query = "INSERT INTO " . $this->table . " (id_etudiant, id_cours, date_inscription_cours) 
                  VALUES (:id_etudiant, :id_cours, CURDATE())";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Inscription réussie !'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
    }

    public function unenroll($studentId, $courseId) {
        $query = "DELETE FROM " . $this->table . " WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Désinscription réussie.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la désinscription.'];
    }

    public function getGrade($studentId, $courseId) {
        $query = "SELECT note FROM " . $this->table . " WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? $result['note'] : null;
    }

    public function updateGrade($studentId, $courseId, $note) {
        if ($note < 0 || $note > 20) {
            return ['success' => false, 'message' => 'La note doit être entre 0 et 20.'];
        }

        $query = "UPDATE " . $this->table . " SET note = :note WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->bindParam(':id_cours', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Note mise à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour de la note.'];
    }

    public function getStudentsByCourse($courseId) {
        $query = "SELECT e.*, i.note, i.date_inscription_cours
                  FROM " . $this->table . " i
                  JOIN etudiant e ON i.id_etudiant = e.id_etudiant
                  WHERE i.id_cours = :id_cours
                  ORDER BY e.nom, e.prenom";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_cours', $courseId);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCoursesByStudent($studentId) {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom, i.note, i.date_inscription_cours
                  FROM " . $this->table . " i
                  JOIN cours c ON i.id_cours = c.id_cours
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE i.id_etudiant = :id_etudiant
                  ORDER BY i.date_inscription_cours DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_etudiant', $studentId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>