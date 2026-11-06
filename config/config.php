<?php
// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecole_en_ligne');
define('DB_USER', 'root');
define('DB_PASS', ''); 

// Configuration de l'application
define('BASE_URL', 'http://localhost/teaching-project');
define('BASE_PATH', __DIR__ . '/..');

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Europe/Paris');
?>