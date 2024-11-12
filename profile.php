<?php
session_start();
include_once 'nav.php';
require_once 'Player.php';

// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=memory_game", "root", "");

// Vérifie si le joueur est connecté
if (!isset($_SESSION['player_id'])) {
    echo "Vous devez être connecté pour voir votre profil.";
    exit;
}

// Récupère l'ID du joueur connecté
$playerId = $_SESSION['player_id'];
$player = Player::getById($pdo, $playerId);

// Vérifie si le joueur a été trouvé dans la base de données
if (!$player) {
    echo "Erreur : joueur non trouvé.";
    exit;
}

// Récupère les scores du joueur
$scores = $player->getScores();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Profil de <?php echo htmlspecialchars($player->username); ?></title>
    <link rel="stylesheet" href="Assets/css/profile.css">
</head>

<body>
    <div class="profile-container">
        <h1>Profil de <?php echo htmlspecialchars($player->username); ?></h1>
        <p>Email : <?php echo htmlspecialchars($player->email); ?></p>
        <h2>Historique des Scores</h2>
        <table>
            <tr>
                <th>Score</th>
                <th>Date</th>
            </tr>
            <?php foreach ($scores as $score): ?>
                <tr>
                    <td><?php echo htmlspecialchars($score['score']); ?></td>
                    <td><?php echo htmlspecialchars($score['date_played']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>