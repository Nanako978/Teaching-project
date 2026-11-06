<?php
require_once __DIR__ . '/../config/Database.php';

class Admin {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getPendingTeachers() {
        $query = "SELECT u.id_user, u.email, u.created_at, p.nom, p.prenom, p.specialite
                  FROM user u
                  LEFT JOIN professeur p ON u.id_user = p.id_user
                  WHERE u.role = 'teacher' AND u.status = 'pending'
                  ORDER BY u.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function approveTeacher($userId) {
        $query = "UPDATE user SET status = 'active' WHERE id_user = :id AND role = 'teacher'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Professeur approuvé avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'approbation.'];
    }

    public function rejectTeacher($userId) {
        // Supprimer l'utilisateur et ses données associées (CASCADE)
        $query = "DELETE FROM user WHERE id_user = :id AND role = 'teacher' AND status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Demande rejetée et supprimée.'];
        }

        return ['success' => false, 'message' => 'Erreur lors du rejet.'];
    }

    public function getAllUsers() {
        $query = "SELECT u.id_user, u.email, u.role, u.status, u.created_at,
                  COALESCE(e.nom, p.nom) as nom,
                  COALESCE(e.prenom, p.prenom) as prenom,
                  p.specialite
                  FROM user u
                  LEFT JOIN etudiant e ON u.id_user = e.id_user
                  LEFT JOIN professeur p ON u.id_user = p.id_user
                  WHERE u.role != 'admin'
                  ORDER BY u.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function suspendUser($userId) {
        $query = "UPDATE user SET status = 'suspended' WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Utilisateur suspendu.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suspension.'];
    }

    public function activateUser($userId) {
        $query = "UPDATE user SET status = 'active' WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Utilisateur activé.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'activation.'];
    }

    public function deleteUser($userId) {
        $query = "DELETE FROM user WHERE id_user = :id AND role != 'admin'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Utilisateur supprimé.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
    }

    public function getStatistics() {
        $stats = [];

        // Nombre d'étudiants
        $query1 = "SELECT COUNT(*) as nb FROM user WHERE role = 'student'";
        $stmt1 = $this->conn->prepare($query1);
        $stmt1->execute();
        $stats['nb_students'] = $stmt1->fetch()['nb'];

        // Nombre de professeurs actifs
        $query2 = "SELECT COUNT(*) as nb FROM user WHERE role = 'teacher' AND status = 'active'";
        $stmt2 = $this->conn->prepare($query2);
        $stmt2->execute();
        $stats['nb_teachers'] = $stmt2->fetch()['nb'];

        // Nombre de professeurs en attente
        $query3 = "SELECT COUNT(*) as nb FROM user WHERE role = 'teacher' AND status = 'pending'";
        $stmt3 = $this->conn->prepare($query3);
        $stmt3->execute();
        $stats['nb_pending'] = $stmt3->fetch()['nb'];

        // Nombre de cours
        $query4 = "SELECT COUNT(*) as nb FROM cours";
        $stmt4 = $this->conn->prepare($query4);
        $stmt4->execute();
        $stats['nb_courses'] = $stmt4->fetch()['nb'];

        // Nombre total d'inscriptions
        $query5 = "SELECT COUNT(*) as nb FROM inscription";
        $stmt5 = $this->conn->prepare($query5);
        $stmt5->execute();
        $stats['nb_enrollments'] = $stmt5->fetch()['nb'];

        return $stats;
    }

    public function getAllCourses() {
        $query = "SELECT c.*, p.nom as prof_nom, p.prenom as prof_prenom, p.specialite,
                  (SELECT COUNT(*) FROM inscription WHERE id_cours = c.id_cours) as nb_etudiants
                  FROM cours c
                  JOIN professeur p ON c.id_professeur = p.id_professeur
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function deleteCourse($courseId) {
        $query = "DELETE FROM cours WHERE id_cours = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $courseId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Cours supprimé.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suppression.'];
    }
}
?>