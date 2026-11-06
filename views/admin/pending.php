<?php 
$pageTitle = 'Demandes en attente'; 
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
    
    <h1>Demandes de professeurs en attente</h1>
    
    <div class="content-wrapper">
        <?php if (empty($pendingTeachers)): ?>
            <div class="alert alert-success">
                Aucune demande en attente.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Spécialité</th>
                        <th>Date de demande</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingTeachers as $teacher): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($teacher['nom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($teacher['prenom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                            <td><?php echo htmlspecialchars($teacher['specialite'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($teacher['created_at'])); ?></td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>/admin/approve-teacher" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $teacher['id_user']; ?>">
                                    <button type="submit" class="btn btn-success btn-small">Approuver</button>
                                </form>
                                
                                <form action="<?php echo BASE_URL; ?>/admin/reject-teacher" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $teacher['id_user']; ?>">
                                    <button type="submit" class="btn btn-danger btn-small btn-delete">Rejeter</button>
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