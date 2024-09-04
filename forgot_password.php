<?php
$pageTitle = "Mot de passe oublié";
include('includes/_header.php'); // Inclusion de l'en-tête

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);

    // Vérifier si l'email existe dans la base de données
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Générer un token unique
        $token = bin2hex(random_bytes(50));
        $update_sql = "UPDATE users SET reset_token = ?, reset_requested_at = NOW() WHERE email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $token, $email);

        if ($update_stmt->execute()) {
            // Simuler l'envoi d'un email avec le lien de réinitialisation
            // En production, utilisez une véritable fonction d'envoi de mail comme PHPMailer
            $reset_link = "http://localhost/memory/reset_password.php?token=$token";
            set_flash_message("Un lien de réinitialisation a été envoyé à votre adresse e-mail : <a href='$reset_link'>$reset_link</a>");
            header("Location: forgot_password.php");
            exit();
        } else {
            set_flash_message("Erreur lors de la génération du lien de réinitialisation.", 'danger');
        }
        $update_stmt->close();
    } else {
        set_flash_message("Aucun compte trouvé avec cet email.", 'danger');
    }

    $stmt->close();
}

$conn->close();
?>

<h2 class="text-center">Mot de passe oublié</h2>
<form action="forgot_password.php" method="post" class="mt-4">
    <?php generate_csrf_token(); ?> <!-- Ajout du token CSRF -->
    <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Envoyer le lien de réinitialisation</button>
</form>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
