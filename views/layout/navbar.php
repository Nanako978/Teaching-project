<header>
    <nav>
        <div class="logo">
            <a href="<?php echo BASE_URL; ?>">École en Ligne</a>
        </div>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <ul class="nav-links">
                <?php if ($_SESSION['role'] === 'student'): ?>
                    <li><a href="<?php echo BASE_URL; ?>/student/dashboard">Tableau de bord</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/student/courses">Tous les cours</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/student/my-courses">Mes cours</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/student/grades">Mes notes</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/student/profile">Mon profil</a></li>
                
                <?php elseif ($_SESSION['role'] === 'teacher'): ?>
                    <li><a href="<?php echo BASE_URL; ?>/teacher/dashboard">Tableau de bord</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/teacher/my-courses">Mes cours</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/teacher/create-course">Créer un cours</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/teacher/profile">Mon profil</a></li>
                
                <?php elseif ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="<?php echo BASE_URL; ?>/admin/dashboard">Tableau de bord</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/users">Utilisateurs</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/pending">En attente</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/admin/courses">Cours</a></li>
                <?php endif; ?>
                
                <li><a href="<?php echo BASE_URL; ?>/logout">Déconnexion</a></li>
            </ul>
        <?php else: ?>
            <ul class="nav-links">
                <li><a href="<?php echo BASE_URL; ?>/login">Connexion</a></li>
                <li><a href="<?php echo BASE_URL; ?>/register">Inscription</a></li>
            </ul>
        <?php endif; ?>
    </nav>
</header>