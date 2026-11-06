<?php 
$pageTitle = 'Gérer les utilisateurs'; 
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
    
    <h1>Gérer les utilisateurs</h1>
    
    <div class="content-wrapper">
        <?php if (empty($users)): ?>
            <div class="alert alert-info">
                Aucun utilisateur enregistré.
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php if ($user['role'] === 'student'): ?>
                                    Étudiant
                                <?php elseif ($user['role'] === 'teacher'): ?>
                                    Professeur <?php if ($user['specialite']): ?>(<?php echo htmlspecialchars($user['specialite']); ?>)<?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($user['status'] === 'active'): ?>
                                    <span class="badge badge-active">Actif</span>
                                <?php elseif ($user['status'] === 'pending'): ?>
                                    <span class="badge badge-pending">En attente</span>
                                <?php elseif ($user['status'] === 'suspended'): ?>
                                    <span class="badge badge-suspended">Suspendu</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['status'] === 'active'): ?>
                                    <form action="<?php echo BASE_URL; ?>/admin/suspend-user" method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
                                        <button type="submit" class="btn btn-accent btn-small">Suspendre</button>
                                    </form>
                                <?php elseif ($user['status'] === 'suspended'): ?>
                                    <form action="<?php echo BASE_URL; ?>/admin/activate-user" method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
                                        <button type="submit" class="btn btn-success btn-small">Activer</button>
                                    </form>
                                <?php endif; ?>
                                
                                <form action="<?php echo BASE_URL; ?>/admin/delete-user" method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id_user']; ?>">
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