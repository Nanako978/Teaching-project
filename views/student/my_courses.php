<?php 
$pageTitle = 'Mes cours'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1>Mes cours inscrits</h1>
    
    <div class="content-wrapper">
        <?php if (empty($enrolledCourses)): ?>
            <div class="alert alert-info">
                Vous n'êtes inscrit à aucun cours.
                <a href="<?php echo BASE_URL; ?>/student/courses">Parcourir les cours disponibles</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Thématique</th>
                        <th>Professeur</th>
                        <th>Durée</th>
                        <th>Date d'inscription</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrolledCourses as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['intitule']); ?></td>
                            <td><?php echo htmlspecialchars($course['thematique']); ?></td>
                            <td><?php echo htmlspecialchars($course['prof_prenom'] . ' ' . $course['prof_nom']); ?></td>
                            <td><?php echo $course['duree_heures']; ?>h</td>
                            <td><?php echo date('d/m/Y', strtotime($course['date_inscription_cours'])); ?></td>
                            <td>
                                <?php if ($course['note']): ?>
                                    <strong class="text-highlight"><?php echo number_format($course['note'], 2); ?>/20</strong>
                                <?php else: ?>
                                    <span class="text-danger">Non notée</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>