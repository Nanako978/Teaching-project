<?php 
$pageTitle = 'Modifier le cours'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <h1>Modifier le cours</h1>
    
    <div class="content-wrapper">
        <form action="<?php echo BASE_URL; ?>/teacher/update-course/<?php echo $course['id_cours']; ?>" method="POST">
            <div class="form-group">
                <label for="thematique">Thématique</label>
                <input type="text" id="thematique" name="thematique" value="<?php echo htmlspecialchars($course['thematique']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="intitule">Intitulé du cours</label>
                <input type="text" id="intitule" name="intitule" value="<?php echo htmlspecialchars($course['intitule']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description"><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="duree_heures">Durée (en heures)</label>
                <input type="number" id="duree_heures" name="duree_heures" value="<?php echo $course['duree_heures']; ?>" min="1" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="<?php echo BASE_URL; ?>/teacher/my-courses" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>