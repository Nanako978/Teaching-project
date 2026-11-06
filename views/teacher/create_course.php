<?php 
$pageTitle = 'Créer un cours'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Créer un nouveau cours</h1>
    
    <div class="content-wrapper">
        <form action="<?php echo BASE_URL; ?>/teacher/create-course" method="POST">
            <div class="form-group">
                <label for="thematique">Thématique</label>
                <input type="text" id="thematique" name="thematique" placeholder="Ex: Mathématiques, Informatique" required>
            </div>
            
            <div class="form-group">
                <label for="intitule">Intitulé du cours</label>
                <input type="text" id="intitule" name="intitule" placeholder="Ex: Introduction à l'algèbre linéaire" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Décrivez le contenu du cours..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="duree_heures">Durée (en heures)</label>
                <input type="number" id="duree_heures" name="duree_heures" min="1" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Créer le cours</button>
            <a href="<?php echo BASE_URL; ?>/teacher/my-courses" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>