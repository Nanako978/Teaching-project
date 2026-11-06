<?php 
$pageTitle = 'Mes cours'; 
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
    
    <h1>Mes cours</h1>
    
    <div class="content-wrapper">
        <a href="<?php echo BASE_URL; ?>/teacher/create-course" class="btn btn-primary mb-2">Créer un nouveau cours</a>
        
        <?php if (empty($courses)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore créé de cours.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Thématique</th>
                        <th>Durée</th>
                        <th>Étudiants</th>
                        <th>Notes données</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['intitule']); ?></td>
                            <td><?php echo htmlspecialchars($course['thematique']); ?></td>
                            <td><?php echo $course['duree_heures']; ?>h</td>
                            <td><?php echo $course['nb_etudiants']; ?></td>
                            <td><?php echo $course['nb_notes']; ?>/<?php echo $course['nb_etudiants']; ?></td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>/teacher/edit-course/<?php echo $course['id_cours']; ?>" class="btn btn-secondary btn-small">Modifier</a>
                                <a href="<?php echo BASE_URL; ?>/teacher/grade-students/<?php echo $course['id_cours']; ?>" class="btn btn-highlight btn-small">Noter</a>
                                <form action="<?php echo BASE_URL; ?>/teacher/delete-course/<?php echo $course['id_cours']; ?>" method="POST" style="display: inline;">
                                    <button type="submit" class="btn btn-danger btn-small btn-delete">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>