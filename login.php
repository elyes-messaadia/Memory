<?php
$pageTitle = "Connexion";
include('includes/_header.php'); // Inclusion de l'en-tête

display_flash_message(); // Afficher les messages flash

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_token(); // Vérification du token CSRF

    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_image'] = $user['profile_image'];

            set_flash_message("Connexion réussie !");
            header("Location: profile.php");
            exit();
        } else {
            set_flash_message("Mot de passe incorrect.", 'danger');
        }
    } else {
        set_flash_message("Aucun utilisateur trouvé avec cet email.", 'danger');
    }

    $stmt->close();
}

$conn->close();
?>

<h2 class="text-center">Connexion</h2>
<form action="login.php" method="post" class="mt-4">
    <?php generate_csrf_token(); ?> <!-- Ajout du token CSRF -->
    <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe :</label>
        <input type="password" class="form-control" name="password" required minlength="8">
    </div>
    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
</form>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
