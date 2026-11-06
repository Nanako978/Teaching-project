<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;
    private $table = 'user';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function register($email, $password, $role = 'student') {
        // Vérifier si l'email existe déjà
        if ($this->emailExists($email)) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
        }

        // Hash du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Déterminer le statut selon le rôle
        $status = ($role === 'teacher') ? 'pending' : 'active';

        $query = "INSERT INTO " . $this->table . " (email, password, role, status) VALUES (:email, :password, :role, :status)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $userId = $this->conn->lastInsertId();
            
            if ($role === 'teacher') {
                return ['success' => true, 'message' => 'Inscription réussie. Votre compte doit être approuvé par un administrateur.', 'user_id' => $userId];
            }
            
            return ['success' => true, 'message' => 'Inscription réussie !', 'user_id' => $userId];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'inscription.'];
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch();

            if ($user['status'] === 'pending') {
                return ['success' => false, 'message' => 'Votre compte est en attente d\'approbation.'];
            }

            if ($user['status'] === 'suspended') {
                return ['success' => false, 'message' => 'Votre compte a été suspendu.'];
            }

            if (password_verify($password, $user['password'])) {
                return ['success' => true, 'user' => $user];
            }

            return ['success' => false, 'message' => 'Mot de passe incorrect.'];
        }

        return ['success' => false, 'message' => 'Aucun compte trouvé avec cet email.'];
    }

    public function emailExists($email) {
        $query = "SELECT id_user FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function getUserById($userId) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateEmail($userId, $newEmail) {
        if ($this->emailExists($newEmail)) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
        }

        $query = "UPDATE " . $this->table . " SET email = :email WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $newEmail);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Email mis à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
    }

    public function updatePassword($userId, $oldPassword, $newPassword) {
        $user = $this->getUserById($userId);

        if (!password_verify($oldPassword, $user['password'])) {
            return ['success' => false, 'message' => 'Ancien mot de passe incorrect.'];
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = "UPDATE " . $this->table . " SET password = :password WHERE id_user = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Mot de passe mis à jour avec succès.'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
    }
}
?>