<?php
session_start();
include_once 'nav.php';
require_once 'Player.php';

// Connexion à la base de données avec PDO
$pdo = new PDO("mysql:host=localhost;dbname=memory_game", "root", "");

// Vérifie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Vérification des erreurs
    $errors = [];

    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    if ($password !== $confirmPassword) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Vérifie si l'email ou le nom d'utilisateur est déjà pris
    $stmt = $pdo->prepare("SELECT * FROM players WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        $errors[] = "Le nom d'utilisateur ou l'email est déjà pris.";
    }

    // Si aucune erreur, enregistrer le joueur
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO players (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashedPassword]);

        // Ajout du message de succès dans la session
        $_SESSION['success_message'] = "Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.";

        // Rediriger vers la page de connexion
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="Assets/css/index.css">
</head>

<body>
    <h1>Inscription</h1>
    <?php if (!empty($errors)): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="register.php" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirmer le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
</body>

</html>