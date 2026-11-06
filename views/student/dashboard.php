<?php 
$pageTitle = 'Tableau de bord - Étudiant'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Tableau de bord étudiant</h1>
    
    <div class="content-wrapper">
        <h2>Bonjour, <?php echo htmlspecialchars($student['prenom'] . ' ' . $student['nom']); ?></h2>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3><?php echo count($enrolledCourses); ?></h3>
                <p>Cours suivis</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $averageGrade ? number_format($averageGrade, 2) : 'N/A'; ?></h3>
                <p>Moyenne générale</p>
            </div>
        </div>
        
        <h2>Mes cours récents</h2>
        
        <?php if (empty($enrolledCourses)): ?>
            <div class="alert alert-info">
                Vous n'êtes inscrit à aucun cours pour le moment.
                <a href="<?php echo BASE_URL; ?>/student/courses">Découvrir les cours disponibles</a>
            </div>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach (array_slice($enrolledCourses, 0, 4) as $course): ?>
                    <div class="card">
                        <h3 class="card-title"><?php echo htmlspecialchars($course['intitule']); ?></h3>
                        <div class="card-content">
                            <p><strong>Thématique :</strong> <?php echo htmlspecialchars($course['thematique']); ?></p>
                            <p><strong>Professeur :</strong> <?php echo htmlspecialchars($course['prof_prenom'] . ' ' . $course['prof_nom']); ?></p>
                            <p><strong>Durée :</strong> <?php echo $course['duree_heures']; ?> heures</p>
                            <?php if ($course['note']): ?>
                                <p><strong>Note :</strong> <span class="text-highlight"><?php echo number_format($course['note'], 2); ?>/20</span></p>
                            <?php else: ?>
                                <p><strong>Note :</strong> <span class="text-danger">Non notée</span></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($enrolledCourses) > 4): ?>
                <div class="text-center mt-2">
                    <a href="<?php echo BASE_URL; ?>/student/my-courses" class="btn btn-primary">Voir tous mes cours</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>