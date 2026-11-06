<?php 
$pageTitle = 'Tableau de bord - Admin'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Tableau de bord administrateur</h1>
    
    <div class="content-wrapper">
        <h2>Statistiques de la plateforme</h2>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3><?php echo $stats['nb_students']; ?></h3>
                <p>Étudiants</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_teachers']; ?></h3>
                <p>Professeurs actifs</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_pending']; ?></h3>
                <p>Professeurs en attente</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_courses']; ?></h3>
                <p>Cours disponibles</p>
            </div>
            
            <div class="stat-box">
                <h3><?php echo $stats['nb_enrollments']; ?></h3>
                <p>Inscriptions totales</p>
            </div>
        </div>
        
        <?php if ($stats['nb_pending'] > 0): ?>
            <div class="alert alert-info">
                <strong>Attention :</strong> <?php echo $stats['nb_pending']; ?> demande(s) de professeur(s) en attente d'approbation.
                <a href="<?php echo BASE_URL; ?>/admin/pending">Voir les demandes</a>
            </div>
        <?php endif; ?>
        
        <div class="grid grid-3 mt-2">
            <a href="<?php echo BASE_URL; ?>/admin/users" class="card">
                <h3 class="card-title">Gérer les utilisateurs</h3>
                <p>Voir, suspendre ou supprimer des comptes</p>
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/pending" class="card">
                <h3 class="card-title">Demandes en attente</h3>
                <p>Approuver ou rejeter des professeurs</p>
            </a>
            
            <a href="<?php echo BASE_URL; ?>/admin/courses" class="card">
                <h3 class="card-title">Gérer les cours</h3>
                <p>Vue d'ensemble de tous les cours</p>
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>