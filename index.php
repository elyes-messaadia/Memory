<?php
session_start();
require_once 'Card.php';
require_once 'Player.php';
include_once 'nav.php';

//  require_once pour les fichiers critiques 
// dont la page dépend pour éviter des comportements inattendus.


// include_once pour des éléments optionnels, 
// comme la navigation (nav.php), qui ne bloqueront pas l’ensemble du script
// si le fichier n’est pas trouvé.


// Connexion à la base de données avec PDO
$pdo = new PDO("mysql:host=localhost;dbname=memory_game", "root", "");

// Récupérer le nombre de paires sélectionné (par défaut à 3 paires)
$numPairs = isset($_GET['numPairs']) ? (int)$_GET['numPairs'] : 3;

// Charger les cartes uniques et limiter par le nombre de paires choisi
$uniqueCards = array_slice(Card::getAllCards($pdo), 0, $numPairs);

// Dupliquer chaque carte pour créer des paires
$cards = [];
foreach ($uniqueCards as $card) {
    $cards[] = $card;
    $cards[] = clone $card;
}

// Mélanger les cartes pour un affichage aléatoire
shuffle($cards);

// Charger le classement des meilleurs joueurs
$topPlayers = Player::getTopPlayers($pdo, 10);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Jeu Memory</title>
    <link rel="stylesheet" href="Assets/css/index.css">
</head>

<body>
    <div class="scroll-container">
        <h1>Memory</h1>

        <!-- Formulaire de sélection du nombre de paires -->
        <form id="pairSelection" action="index.php" method="GET">
            <label for="numPairs">Nombre de paires :</label>
            <select name="numPairs" id="numPairs">
                <?php for ($i = 3; $i <= 12; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $i === $numPairs ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
            <button type="submit">Commencer le jeu</button>
        </form>

        <div class="memory-game">
            <?php foreach ($cards as $card): ?>
                <div class="card" onclick="flipCard(this, <?php echo $card->getId(); ?>)">
                    <div class="card-inner">
                        <div class="card-front">
                            <img src="Assets/img/dos-base.png" alt="Dos de la carte">
                        </div>
                        <div class="card-back">
                            <img src="<?php echo $card->getImagePath(); ?>" alt="Carte">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Affichage du classement des meilleurs joueurs -->
        <h2>Classement des Meilleurs Joueurs</h2>
        <table>
            <tr>
                <th>Joueur</th>
                <th>Score</th>
            </tr>
            <?php foreach ($topPlayers as $player): ?>
                <tr>
                    <td><?php echo htmlspecialchars($player['username']); ?></td>
                    <td><?php echo htmlspecialchars($player['score']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script src="Assets/js/main.js"></script>
</body>

</html>