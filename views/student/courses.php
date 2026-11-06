<?php 
$pageTitle = 'Cours disponibles'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Cours disponibles</h1>
    
    <div class="content-wrapper">
        <?php if (empty($availableCourses)): ?>
            <div class="alert alert-info">
                Vous êtes inscrit à tous les cours disponibles ou aucun cours n'est disponible.
            </div>
        <?php else: ?>
            <p>Voici les cours auxquels vous pouvez vous inscrire :</p>
            
            <div class="grid grid-2">
                <?php foreach ($availableCourses as $course): ?>
                    <div class="card">
                        <h3 class="card-title"><?php echo htmlspecialchars($course['intitule']); ?></h3>
                        <div class="card-content">
                            <p><strong>Thématique :</strong> <?php echo htmlspecialchars($course['thematique']); ?></p>
                            <p><strong>Professeur :</strong> <?php echo htmlspecialchars($course['prof_prenom'] . ' ' . $course['prof_nom']); ?></p>
                            <p><strong>Durée :</strong> <?php echo $course['duree_heures']; ?> heures</p>
                            <p><strong>Étudiants inscrits :</strong> <?php echo $course['nb_etudiants']; ?></p>
                            <?php if ($course['description']): ?>
                                <p><?php echo nl2br(htmlspecialchars(substr($course['description'], 0, 150))); ?>...</p>
                            <?php endif; ?>
                        </div>
                        <div class="card-actions">
                            <form action="<?php echo BASE_URL; ?>/student/enroll" method="POST" style="display: inline;">
                                <input type="hidden" name="course_id" value="<?php echo $course['id_cours']; ?>">
                                <button type="submit" class="btn btn-highlight">S'inscrire</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>