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
        <h2>Informations professionnelles</h2>
        <form action="<?php echo BASE_URL; ?>/teacher/update-profile" method="POST">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($teacher['nom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($teacher['prenom']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="specialite">Spécialité</label>
                <input type="text" id="specialite" name="specialite" value="<?php echo htmlspecialchars($teacher['specialite']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    
    <div class="content-wrapper">
        <h2>Email du compte</h2>
        <p><strong>Email actuel :</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="text-danger">Pour modifier votre email, contactez un administrateur.</p>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>