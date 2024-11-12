<?php
// Assure que la session est démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav>
    <a href="index.php">Accueil</a>
    <?php if (isset($_SESSION['player_id'])): ?>
        <a href="profile.php">Mon Profil</a>
        <a href="logout.php">Déconnexion</a>
    <?php else: ?>
        <a href="login.php">Connexion</a>
        <a href="register.php">Inscription</a>
    <?php endif; ?>
</nav>
