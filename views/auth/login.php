<?php 
$pageTitle = 'Connexion'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="auth-container">
    <h1>Connexion</h1>
    
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
    
    <form action="<?php echo BASE_URL; ?>/login" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Se connecter</button>
    </form>
    
    <div class="auth-links">
        <p>Pas encore de compte ? <a href="<?php echo BASE_URL; ?>/register">Inscrivez-vous</a></p>
    </div>
    
    <div class="alert alert-info mt-2">
        <strong>Comptes de test :</strong><br>
        Admin : admin@ecole.com / admin123<br>
        Étudiant : etudiant1@ecole.com / admin123<br>
        Professeur : prof1@ecole.com / admin123
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>