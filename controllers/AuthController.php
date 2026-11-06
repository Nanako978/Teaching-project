<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';

class AuthController {
    
    public function showLogin() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['role']);
            return;
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs.';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $userModel = new User();
        $result = $userModel->login($email, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id_user'];
            $_SESSION['email'] = $result['user']['email'];
            $_SESSION['role'] = $result['user']['role'];
            $_SESSION['success'] = 'Connexion réussie !';

            $this->redirectToDashboard($result['user']['role']);
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . BASE_URL . '/login');
        }
        exit;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? 'student';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        // Validation
        if (empty($email) || empty($password) || empty($nom) || empty($prenom)) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas.';
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères.';
            header('Location: ' . BASE_URL . '/register');
            exit;
        }

        $userModel = new User();
        $result = $userModel->register($email, $password, $role);

        if ($result['success']) {
            $userId = $result['user_id'];

            if ($role === 'student') {
                $dateNaissance = $_POST['date_naissance'] ?? '';
                $studentModel = new Student();
                $studentModel->create($userId, $nom, $prenom, $dateNaissance);
            } elseif ($role === 'teacher') {
                $specialite = trim($_POST['specialite'] ?? '');
                $teacherModel = new Teacher();
                $teacherModel->create($userId, $nom, $prenom, $specialite);
            }

            $_SESSION['success'] = $result['message'];
            header('Location: ' . BASE_URL . '/login');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . BASE_URL . '/register');
        }
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    private function redirectToDashboard($role) {
        switch ($role) {
            case 'student':
                header('Location: ' . BASE_URL . '/student/dashboard');
                break;
            case 'teacher':
                header('Location: ' . BASE_URL . '/teacher/dashboard');
                break;
            case 'admin':
                header('Location: ' . BASE_URL . '/admin/dashboard');
                break;
        }
        exit;
    }
}
?>