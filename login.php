<?php
session_start();
include_once 'includes/_nav.php';
require_once 'Player.php';

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=memory_game", "root", "");

// Vérifie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Requête pour vérifier le joueur dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM players WHERE username = ?");
    $stmt->execute([$username]);
    $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifie si l'utilisateur existe et que le mot de passe correspond
    if ($playerData && password_verify($password, $playerData['password'])) {
        session_regenerate_id(); // Renouvelle l'ID de session pour éviter les conflits de session
        $_SESSION['player_id'] = $playerData['id']; // Définit l'ID du joueur dans la session
        header("Location: profile.php"); // Redirige vers le profil
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="Assets/css/index.css">
</head>

<body>
    <?php
    // Affiche le message de succès si présent
    if (isset($_SESSION['success_message'])) {
        echo "<p style='color: green;'>" . htmlspecialchars($_SESSION['success_message']) . "</p>";
        unset($_SESSION['success_message']);
    }
    ?>
    <h1>Connexion</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
    <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
</body>

</html>