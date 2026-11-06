<?php 
$pageTitle = 'Tableau de bord - Professeur'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Tableau de bord professeur</h1>
    
    <div class="content-wrapper">
        <h2>Bonjour, <?php echo htmlspecialchars($teacher['prenom'] . ' ' . $teacher['nom']); ?></h2>
        <p><strong>Spécialité :</strong> <?php echo htmlspecialchars($teacher['specialite']); ?></p>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3><?php echo $stats['nb_cours']; ?></h3>
                <p>Cours enseignés</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_etudiants']; ?></h3>
                <p>Étudiants au total</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_notes']; ?></h3>
                <p>Notes attribuées</p>
            </div>
        </div>
        
        <h2>Mes cours</h2>
        
        <?php if (empty($courses)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore créé de cours.
                <a href="<?php echo BASE_URL; ?>/teacher/create-course">Créer mon premier cours</a>
            </div>
        <?php else: ?>
            <div class="grid grid-2">
                <?php foreach ($courses as $course): ?>
                    <div class="card">
                        <h3 class="card-title"><?php echo htmlspecialchars($course['intitule']); ?></h3>
                        <div class="card-content">
                            <p><strong>Thématique :</strong> <?php echo htmlspecialchars($course['thematique']); ?></p>
                            <p><strong>Durée :</strong> <?php echo $course['duree_heures']; ?> heures</p>
                            <p><strong>Étudiants inscrits :</strong> <?php echo $course['nb_etudiants']; ?></p>
                            <p><strong>Notes données :</strong> <?php echo $course['nb_notes']; ?>/<?php echo $course['nb_etudiants']; ?></p>
                        </div>
                        <div class="card-actions">
                            <a href="<?php echo BASE_URL; ?>/teacher/edit-course/<?php echo $course['id_cours']; ?>" class="btn btn-secondary btn-small">Modifier</a>
                            <a href="<?php echo BASE_URL; ?>/teacher/grade-students/<?php echo $course['id_cours']; ?>" class="btn btn-highlight btn-small">Noter</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>