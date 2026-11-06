<?php 
$pageTitle = 'Mon profil'; 
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
    
    <h1>Mon profil</h1>
    
    <div class="content-wrapper">
        <h2>Informations personnelles</h2>
        <form action="<?php echo BASE_URL; ?>/student/update-profile" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($student['nom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($student['prenom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?php echo $student['date_naissance']; ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    
    <div class="content-wrapper">
        <h2>Changer l'email</h2>
        <form action="<?php echo BASE_URL; ?>/student/update-email" method="POST">
            <div class="form-group">
                <label for="email">Nouvel email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-secondary">Mettre à jour l'email</button>
        </form>
    </div>
    
    <div class="content-wrapper">
        <h2>Changer le mot de passe</h2>
        <form action="<?php echo BASE_URL; ?>/student/update-password" method="POST">
            <div class="form-group">
                <label for="old_password">Ancien mot de passe</label>
                <input type="password" id="old_password" name="old_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-accent">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>