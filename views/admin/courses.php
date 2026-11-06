<?php 
$pageTitle = 'Gérer les cours'; 
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
    
    <h1>Gérer les cours</h1>
    
    <div class="content-wrapper">
        <?php if (empty($courses)): ?>
            <div class="alert alert-info">
                Aucun cours disponible.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Thématique</th>
                        <th>Professeur</th>
                        <th>Spécialité</th>
                        <th>Durée</th>
                        <th>Étudiants</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['intitule']); ?></td>
                            <td><?php echo htmlspecialchars($course['thematique']); ?></td>
                            <td><?php echo htmlspecialchars($course['prof_prenom'] . ' ' . $course['prof_nom']); ?></td>
                            <td><?php echo htmlspecialchars($course['specialite']); ?></td>
                            <td><?php echo $course['duree_heures']; ?>h</td>
                            <td><?php echo $course['nb_etudiants']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($course['created_at'])); ?></td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>/admin/delete-course" method="POST" style="display: inline;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id_cours']; ?>">
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