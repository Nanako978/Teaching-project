<?php 
$pageTitle = 'Mes notes'; 
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1>Mes notes</h1>
    
    <div class="content-wrapper">
        <?php if ($averageGrade): ?>
            <div class="stat-box" style="max-width: 300px; margin: 0 auto 2rem;">
                <h3><?php echo number_format($averageGrade, 2); ?>/20</h3>
                <p>Moyenne générale</p>
            </div>
        <?php endif; ?>
        
        <?php if (empty($grades)): ?>
            <div class="alert alert-info">
                Vous n'avez pas encore de notes.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Thématique</th>
                        <th>Professeur</th>
                        <th>Date d'inscription</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($grades as $grade): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($grade['intitule']); ?></td>
                            <td><?php echo htmlspecialchars($grade['thematique']); ?></td>
                            <td><?php echo htmlspecialchars($grade['prof_prenom'] . ' ' . $grade['prof_nom']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($grade['date_inscription_cours'])); ?></td>
                            <td>
                                <?php if ($grade['note']): ?>
                                    <strong class="text-highlight"><?php echo number_format($grade['note'], 2); ?>/20</strong>
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