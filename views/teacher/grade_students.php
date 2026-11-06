<?php 
$pageTitle = 'Noter les étudiants'; 
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
    
    <h1>Noter les étudiants</h1>
    
    <div class="content-wrapper">
        <h2><?php echo htmlspecialchars($course['intitule']); ?></h2>
        <p><strong>Thématique :</strong> <?php echo htmlspecialchars($course['thematique']); ?></p>
        
        <?php if (empty($students)): ?>
            <div class="alert alert-info">
                Aucun étudiant n'est inscrit à ce cours.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Note actuelle</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['nom']); ?></td>
                            <td><?php echo htmlspecialchars($student['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($student['date_inscription_cours'])); ?></td>
                            <td>
                                <?php if ($student['note']): ?>
                                    <strong class="text-highlight"><?php echo number_format($student['note'], 2); ?>/20</strong>
                                <?php else: ?>
                                    <span class="text-danger">Non notée</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>/teacher/submit-grade" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id_cours']; ?>">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id_etudiant']; ?>">
                                    <input type="number" name="note" step="0.01" min="0" max="20" placeholder="Note" 
                                           value="<?php echo $student['note'] ?? ''; ?>" style="width: 80px;" required>
                                    <button type="submit" class="btn btn-highlight btn-small">Enregistrer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div class="mt-2">
            <a href="<?php echo BASE_URL; ?>/teacher/my-courses" class="btn btn-secondary">Retour à mes cours</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>