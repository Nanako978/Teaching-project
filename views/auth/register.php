<?php 
$pageTitle = 'Inscription'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="auth-container">
    <h1>Inscription</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form id="registerForm" action="<?php echo BASE_URL; ?>/register" method="POST">
        <div class="form-group">
            <label for="role">Je suis</label>
            <select id="role" name="role" required>
                <option value="student">Étudiant</option>
                <option value="teacher">Professeur</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div id="student-fields">
            <div class="form-group">
                <label for="date_naissance">Date de naissance</label>
                <input type="date" id="date_naissance" name="date_naissance">
            </div>
        </div>
        
        <div id="teacher-fields" style="display: none;">
            <div class="form-group">
                <label for="specialite">Spécialité</label>
                <input type="text" id="specialite" name="specialite" placeholder="Ex: Mathématiques, Informatique">
            </div>
            <div class="alert alert-info">
                Note : Les comptes professeurs doivent être approuvés par un administrateur.
            </div>
        </div>
        
        <div class="form-group">
            <label for="password">Mot de passe (min. 6 caractères)</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">S'inscrire</button>
    </form>
    
    <div class="auth-links">
        <p>Déjà un compte ? <a href="<?php echo BASE_URL; ?>/login">Connectez-vous</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>