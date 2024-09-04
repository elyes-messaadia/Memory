<?php
$pageTitle = "Réinitialiser le mot de passe";
include('includes/_header.php'); // Inclusion de l'en-tête

if (isset($_GET['token'])) {
    $token = $conn->real_escape_string($_GET['token']);

    // Vérifier si le token est valide
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_requested_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf_token(); // Vérification du token CSRF

            // Valider le mot de passe
            $password = $_POST['password'];
            if (strlen($password) < 8) {
                set_flash_message("Le mot de passe doit contenir au moins 8 caractères.", 'danger');
            } else {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_requested_at = NULL WHERE reset_token = ?");
                $update_stmt->bind_param("ss", $hashed_password, $token);

                if ($update_stmt->execute()) {
                    set_flash_message("Votre mot de passe a été réinitialisé avec succès !");
                    header("Location: login.php");
                    exit();
                } else {
                    set_flash_message("Erreur lors de la réinitialisation du mot de passe.", 'danger');
                }
            }
        }
    } else {
        set_flash_message("Lien de réinitialisation invalide ou expiré.", 'danger');
        header("Location: forgot_password.php");
        exit();
    }
} else {
    header("Location: forgot_password.php");
    exit();
}

$conn->close();
?>

<h2 class="text-center">Réinitialiser le mot de passe</h2>
<form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post" class="mt-4">
    <?php generate_csrf_token(); ?> <!-- Ajout du token CSRF -->
    <div class="mb-3">
        <label for="password" class="form-label">Nouveau mot de passe :</label>
        <input type="password" class="form-control" name="password" required minlength="8">
    </div>
    <button type="submit" class="btn btn-success w-100">Réinitialiser le mot de passe</button>
</form>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
