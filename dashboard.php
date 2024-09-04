<?php
$pageTitle = "Tableau de bord";
include('includes/_header.php'); // Inclusion de l'en-tête

ensure_logged_in(); // Vérifier si l'utilisateur est connecté

$user_id = $_SESSION['user_id'];

// Récupérer les statistiques de jeu de l'utilisateur
$sql = "SELECT COUNT(*) AS games_played, MIN(score) AS best_score, MAX(score) AS worst_score FROM scores WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<h2 class="text-center">Tableau de bord</h2>
<div class="mt-4">
    <h4>Nombre de parties jouées : <?php echo $stats['games_played']; ?></h4>
    <h4>Meilleur score : <?php echo $stats['best_score']; ?></h4>
    <h4>Pire score : <?php echo $stats['worst_score']; ?></h4>
</div>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
