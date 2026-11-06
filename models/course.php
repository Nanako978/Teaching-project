<?php
require_once __DIR__ . '/../config/Database.php';

class Course {
    private $conn;
    private $table = 'cours';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($thematique, $intitule, $description, $dureeHeures, $idProfesseur) {
        $query = "INSERT INTO " . $this->table . " (thematique, intitule, description, duree_heures, id_professeur) 
                  VALUES (:thematique, :intitule, :description, :duree_heures, :id_professeur)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':thematique', $thematique);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':duree_heures', $dureeHeures);
        $stmt->bindParam(':id_professeur', $idProfesseur);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cours créé avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la création du cours.'];
    }

    public function getAll() {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants
                  FROM " . $this->table . " c
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($courseId) {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom, p.specialite,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants
                  FROM " . $this->table . " c
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE c.id_cours = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $courseId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($courseId, $thematique, $intitule, $description, $dureeHeures, $idProfesseur) {
        // Vérifier que le cours appartient au professeur
        $checkQuery = "SELECT id_cours FROM " . $this->table . " WHERE id_cours = :id AND id_professeur = :id_professeur";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $courseId);
        $checkStmt->bindParam(':id_professeur', $idProfesseur);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'Vous n\'avez pas accès à ce cours.'];
        }

        $query = "UPDATE " . $this->table . " SET thematique = :thematique, intitule = :intitule, 
                  description = :description, duree_heures = :duree_heures 
                  WHERE id_cours = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':thematique', $thematique);
        $stmt->bindParam(':intitule', $intitule);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':duree_heures', $dureeHeures);
        $stmt->bindParam(':id', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cours mis à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
    }

    public function delete($courseId, $idProfesseur) {
        // Vérifier que le cours appartient au professeur
        $checkQuery = "SELECT id_cours FROM " . $this->table . " WHERE id_cours = :id AND id_professeur = :id_professeur";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':id', $courseId);
        $checkStmt->bindParam(':id_professeur', $idProfesseur);
        $checkStmt->execute();

        if ($checkStmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'Vous n\'avez pas accès à ce cours.'];
        }

        $query = "DELETE FROM " . $this->table . " WHERE id_cours = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cours supprimé avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
    }

    public function search($keyword) {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants
                  FROM " . $this->table . " c
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  WHERE c.intitule LIKE :keyword OR c.thematique LIKE :keyword OR c.description LIKE :keyword
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bindParam(':keyword', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>