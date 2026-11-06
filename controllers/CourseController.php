<?php
require_once __DIR__ . '/../models/Course.php';

class CourseController {
    
    public function list() {
        $courseModel = new Course();
        $courses = $courseModel->getAll();

        require_once __DIR__ . '/../views/courses/list.php';
    }

    public function view($courseId) {
        $courseModel = new Course();
        $course = $courseModel->getById($courseId);

        if (!$course) {
            $_SESSION['error'] = 'Cours introuvable.';
            header('Location: ' . BASE_URL . '/courses');
            exit;
        }

        require_once __DIR__ . '/../views/courses/view.php';
    }

    public function search() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/courses');
            exit;
        }

        $keyword = trim($_POST['keyword'] ?? '');

        $courseModel = new Course();
        $courses = empty($keyword) ? $courseModel->getAll() : $courseModel->search($keyword);

        require_once __DIR__ . '/../views/courses/list.php';
    }
}
?>