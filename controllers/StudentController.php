<?php
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';

class StudentController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAuth();
        
        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        
        if (!$student) {
            $_SESSION['error'] = 'Profil étudiant introuvable.';
            header('Location: ' . BASE_URL . '/logout');
            exit;
        }

        $enrolledCourses = $studentModel->getEnrolledCourses($student['id_etudiant']);
        $averageGrade = $studentModel->getAverageGrade($student['id_etudiant']);

        require_once __DIR__ . '/../views/student/dashboard.php';
    }

    public function courses() {
        $this->checkAuth();
        
        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        $availableCourses = $studentModel->getAvailableCourses($student['id_etudiant']);

        require_once __DIR__ . '/../views/student/courses.php';
    }

    public function myCourses() {
        $this->checkAuth();
        
        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        $enrolledCourses = $studentModel->getEnrolledCourses($student['id_etudiant']);

        require_once __DIR__ . '/../views/student/my_courses.php';
    }

    public function enrollCourse() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/student/courses');
            exit;
        }

        $courseId = $_POST['course_id'] ?? null;
        
        if (!$courseId) {
            $_SESSION['error'] = 'Cours invalide.';
            header('Location: ' . BASE_URL . '/student/courses');
            exit;
        }

        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        $result = $studentModel->enrollCourse($student['id_etudiant'], $courseId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/student/courses');
        exit;
    }

    public function grades() {
        $this->checkAuth();
        
        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        $grades = $studentModel->getGrades($student['id_etudiant']);
        $averageGrade = $studentModel->getAverageGrade($student['id_etudiant']);

        require_once __DIR__ . '/../views/student/grades.php';
    }

    public function profile() {
        $this->checkAuth();
        
        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/student/profile.php';
    }

    public function updateProfile() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/student/profile');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $dateNaissance = $_POST['date_naissance'] ?? '';

        $studentModel = new Student();
        $student = $studentModel->getByUserId($_SESSION['user_id']);
        
        $result = $studentModel->updateProfile($student['id_etudiant'], $nom, $prenom, $dateNaissance);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/student/profile');
        exit;
    }

    public function updateEmail() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/student/profile');
            exit;
        }

        $newEmail = trim($_POST['email'] ?? '');

        $userModel = new User();
        $result = $userModel->updateEmail($_SESSION['user_id'], $newEmail);

        if ($result['success']) {
            $_SESSION['email'] = $newEmail;
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/student/profile');
        exit;
    }

    public function updatePassword() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/student/profile');
            exit;
        }

        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = 'Les nouveaux mots de passe ne correspondent pas.';
            header('Location: ' . BASE_URL . '/student/profile');
            exit;
        }

        $userModel = new User();
        $result = $userModel->updatePassword($_SESSION['user_id'], $oldPassword, $newPassword);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/student/profile');
        exit;
    }
}
?>