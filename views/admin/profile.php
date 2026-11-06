<?php 
$pageTitle = 'Mon profil - Admin'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1>Profil administrateur</h1>
    
    <div class="content-wrapper">
        <h2>Informations du compte</h2>
        <p><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Rôle :</strong> Administrateur</p>
        <p><strong>Statut :</strong> <span class="badge badge-active">Actif</span></p>
        <p><strong>Membre depuis :</strong> <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
    </div>
    
    <div class="content-wrapper">
        <h2>Actions rapides</h2>
        <div class="grid grid-3">
            <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-primary">Gérer les utilisateurs</a>
            <a href="<?php echo BASE_URL; ?>/admin/pending" class="btn btn-highlight">Demandes en attente</a>
            <a href="<?php echo BASE_URL; ?>/admin/courses" class="btn btn-secondary">Gérer les cours</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>