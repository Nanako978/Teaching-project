<?php
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/User.php';

class TeacherController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function dashboard() {
        $this->checkAuth();
        
        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);
        
        if (!$teacher) {
            $_SESSION['error'] = 'Profil professeur introuvable.';
            header('Location: ' . BASE_URL . '/logout');
            exit;
        }

        $courses = $teacherModel->getMyCourses($teacher['id_professeur']);
        $stats = $teacherModel->getStatistics($teacher['id_professeur']);

        require_once __DIR__ . '/../views/teacher/dashboard.php';
    }

    public function myCourses() {
        $this->checkAuth();
        
        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);
        $courses = $teacherModel->getMyCourses($teacher['id_professeur']);

        require_once __DIR__ . '/../views/teacher/my_courses.php';
    }

    public function createCourse() {
        $this->checkAuth();
        require_once __DIR__ . '/../views/teacher/create_course.php';
    }

    public function storeCourse() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/teacher/create-course');
            exit;
        }

        $thematique = trim($_POST['thematique'] ?? '');
        $intitule = trim($_POST['intitule'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dureeHeures = intval($_POST['duree_heures'] ?? 0);

        if (empty($thematique) || empty($intitule) || $dureeHeures <= 0) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs obligatoires.';
            header('Location: ' . BASE_URL . '/teacher/create-course');
            exit;
        }

        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);

        $courseModel = new Course();
        $result = $courseModel->create($thematique, $intitule, $description, $dureeHeures, $teacher['id_professeur']);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
            header('Location: ' . BASE_URL . '/teacher/my-courses');
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: ' . BASE_URL . '/teacher/create-course');
        }
        exit;
    }

    public function editCourse($courseId) {
        $this->checkAuth();
        
        $courseModel = new Course();
        $course = $courseModel->getById($courseId);

        if (!$course) {
            $_SESSION['error'] = 'Cours introuvable.';
            header('Location: ' . BASE_URL . '/teacher/my-courses');
            exit;
        }

        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);

        if ($course['id_professeur'] != $teacher['id_professeur']) {
            $_SESSION['error'] = 'Vous n\'avez pas accès à ce cours.';
            header('Location: ' . BASE_URL . '/teacher/my-courses');
            exit;
        }

        require_once __DIR__ . '/../views/teacher/edit_course.php';
    }

    public function updateCourse($courseId) {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/teacher/my-courses');
            exit;
        }

        $thematique = trim($_POST['thematique'] ?? '');
        $intitule = trim($_POST['intitule'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $dureeHeures = intval($_POST['duree_heures'] ?? 0);

        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);

        $courseModel = new Course();
        $result = $courseModel->update($courseId, $thematique, $intitule, $description, $dureeHeures, $teacher['id_professeur']);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/teacher/my-courses');
        exit;
    }

    public function deleteCourse($courseId) {
        $this->checkAuth();
        
        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);

        $courseModel = new Course();
        $result = $courseModel->delete($courseId, $teacher['id_professeur']);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/teacher/my-courses');
        exit;
    }

    public function gradeStudents($courseId) {
        $this->checkAuth();
        
        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);
        
        $courseModel = new Course();
        $course = $courseModel->getById($courseId);

        if (!$course || $course['id_professeur'] != $teacher['id_professeur']) {
            $_SESSION['error'] = 'Vous n\'avez pas accès à ce cours.';
            header('Location: ' . BASE_URL . '/teacher/my-courses');
            exit;
        }

        $students = $teacherModel->getCourseStudents($courseId, $teacher['id_professeur']);

        require_once __DIR__ . '/../views/teacher/grade_students.php';
    }

    public function submitGrade() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/teacher/my-courses');
            exit;
        }

        $courseId = intval($_POST['course_id'] ?? 0);
        $studentId = intval($_POST['student_id'] ?? 0);
        $note = floatval($_POST['note'] ?? 0);

        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);

        $result = $teacherModel->addGrade($courseId, $studentId, $teacher['id_professeur'], $note);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/teacher/grade-students/' . $courseId);
        exit;
    }

    public function profile() {
        $this->checkAuth();
        
        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);
        
        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/teacher/profile.php';
    }

    public function updateProfile() {
        $this->checkAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/teacher/profile');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $specialite = trim($_POST['specialite'] ?? '');

        $teacherModel = new Teacher();
        $teacher = $teacherModel->getByUserId($_SESSION['user_id']);
        
        $result = $teacherModel->updateProfile($teacher['id_professeur'], $nom, $prenom, $specialite);

        if ($result['success']) {
            $_SESSION['success'] = $result['message'];
        } else {
            $_SESSION['error'] = $result['message'];
        }

        header('Location: ' . BASE_URL . '/teacher/profile');
        exit;
    }
}
?>