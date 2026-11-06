<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/User.php';

class AdminController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAuth();
        
        $adminModel = new Admin();
        $stats = $adminModel->getStatistics();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users() {
        $this->checkAuth();
        
        $adminModel = new Admin();
        $users = $adminModel->getAllUsers();

        require_once __DIR__ . '/../views/admin/users.php';
    }

    public function pending() {
        $this->checkAuth();
        
        $adminModel = new Admin();
        $pendingTeachers = $adminModel->getPendingTeachers();

        require_once __DIR__ . '/../views/admin/pending.php';
    }

    public function approveTeacher() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/pending');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->approveTeacher($userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/pending');
        exit;
    }

    public function rejectTeacher() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/pending');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->rejectTeacher($userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/pending');
        exit;
    }

    public function suspendUser() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->suspendUser($userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function activateUser() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->activateUser($userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function deleteUser() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/users');
            exit;
        }

        $userId = intval($_POST['user_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->deleteUser($userId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/users');
        exit;
    }

    public function courses() {
        $this->checkAuth();
        
        $adminModel = new Admin();
        $courses = $adminModel->getAllCourses();

        require_once __DIR__ . '/../views/admin/courses.php';
    }

    public function deleteCourse() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin/courses');
            exit;
        }

        $courseId = intval($_POST['course_id'] ?? 0);

        $adminModel = new Admin();
        $result = $adminModel->deleteCourse($courseId);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/admin/courses');
        exit;
    }

    public function profile() {
        $this->checkAuth();
        
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/admin/profile.php';
    }
}
?>