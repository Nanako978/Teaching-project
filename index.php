<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/StudentController.php';
require_once __DIR__ . '/controllers/TeacherController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/CourseController.php';

// Récupérer l'URL demandée
$request = $_SERVER['REQUEST_URI'];
$baseUrl = '/teaching-project'; // Changez selon votre configuration
$request = str_replace($baseUrl, '', $request);
$request = strtok($request, '?'); // Supprimer les paramètres GET

// Router
switch ($request) {
    // Page d'accueil
    case '/':
    case '':
        header('Location: ' . BASE_URL . '/login');
        break;

    // Authentification
    case '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case '/register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // Routes Étudiant
    case '/student/dashboard':
        $controller = new StudentController();
        $controller->dashboard();
        break;

    case '/student/courses':
        $controller = new StudentController();
        $controller->courses();
        break;

    case '/student/my-courses':
        $controller = new StudentController();
        $controller->myCourses();
        break;

    case '/student/enroll':
        $controller = new StudentController();
        $controller->enrollCourse();
        break;

    case '/student/grades':
        $controller = new StudentController();
        $controller->grades();
        break;

    case '/student/profile':
        $controller = new StudentController();
        $controller->profile();
        break;

    case '/student/update-profile':
        $controller = new StudentController();
        $controller->updateProfile();
        break;

    case '/student/update-email':
        $controller = new StudentController();
        $controller->updateEmail();
        break;

    case '/student/update-password':
        $controller = new StudentController();
        $controller->updatePassword();
        break;

    // Routes Professeur
    case '/teacher/dashboard':
        $controller = new TeacherController();
        $controller->dashboard();
        break;

    case '/teacher/my-courses':
        $controller = new TeacherController();
        $controller->myCourses();
        break;

    case '/teacher/create-course':
        $controller = new TeacherController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->storeCourse();
        } else {
            $controller->createCourse();
        }
        break;

    case (preg_match('/^\/teacher\/edit-course\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new TeacherController();
        $controller->editCourse($matches[1]);
        break;

    case (preg_match('/^\/teacher\/update-course\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new TeacherController();
        $controller->updateCourse($matches[1]);
        break;

    case (preg_match('/^\/teacher\/delete-course\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new TeacherController();
        $controller->deleteCourse($matches[1]);
        break;

    case (preg_match('/^\/teacher\/grade-students\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new TeacherController();
        $controller->gradeStudents($matches[1]);
        break;

    case '/teacher/submit-grade':
        $controller = new TeacherController();
        $controller->submitGrade();
        break;

    case '/teacher/profile':
        $controller = new TeacherController();
        $controller->profile();
        break;

    case '/teacher/update-profile':
        $controller = new TeacherController();
        $controller->updateProfile();
        break;

    // Routes Admin
    case '/admin/dashboard':
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case '/admin/users':
        $controller = new AdminController();
        $controller->users();
        break;

    case '/admin/pending':
        $controller = new AdminController();
        $controller->pending();
        break;

    case '/admin/approve-teacher':
        $controller = new AdminController();
        $controller->approveTeacher();
        break;

    case '/admin/reject-teacher':
        $controller = new AdminController();
        $controller->rejectTeacher();
        break;

    case '/admin/suspend-user':
        $controller = new AdminController();
        $controller->suspendUser();
        break;

    case '/admin/activate-user':
        $controller = new AdminController();
        $controller->activateUser();
        break;

    case '/admin/delete-user':
        $controller = new AdminController();
        $controller->deleteUser();
        break;

    case '/admin/courses':
        $controller = new AdminController();
        $controller->courses();
        break;

    case '/admin/delete-course':
        $controller = new AdminController();
        $controller->deleteCourse();
        break;

    case '/admin/profile':
        $controller = new AdminController();
        $controller->profile();
        break;

    // Page 404
    default:
        http_response_code(404);
        echo '<h1>404 - Page non trouvée</h1>';
        echo '<p><a href="' . BASE_URL . '/login">Retour à l\'accueil</a></p>';
        break;
}
?>