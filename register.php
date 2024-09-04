<?php
$pageTitle = "Inscription";
include('includes/_header.php'); // Inclusion de l'en-tête

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $profile_image = 'default.jpg';

    if (!empty($_FILES['profile_image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_image']['type'], $allowed_types) && $_FILES['profile_image']['size'] <= 1048576) {
            $profile_image = basename($_FILES['profile_image']['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . $profile_image;
            $profile_image = $conn->real_escape_string($profile_image);

            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                set_flash_message("Erreur lors du téléchargement de l'image.", 'danger');
                header("Location: register.php");
                exit();
            }
        } else {
            set_flash_message("Format de fichier non valide ou taille trop grande.", 'danger');
        }
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password, $profile_image);

    if ($stmt->execute()) {
        set_flash_message("Inscription réussie !");
        header("Location: login.php");
        exit();
    } else {
        set_flash_message("Erreur : " . $conn->error, 'danger');
    }

    $stmt->close();
    $conn->close();
}
?>

<h2 class="text-center">Inscription</h2>
<form action="register.php" method="post" enctype="multipart/form-data" class="mt-4">
    <?php generate_csrf_token(); ?> <!-- Ajout du token CSRF -->
    <div class="mb-3">
        <label for="username" class="form-label">Nom d'utilisateur :</label>
        <input type="text" class="form-control" name="username" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email :</label>
        <input type="email" class="form-control" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe :</label>
        <input type="password" class="form-control" name="password" required minlength="8">
    </div>
    <div class="mb-3">
        <label for="profile_image" class="form-label">Image de profil (Max 1MB) :</label>
        <input type="file" class="form-control" name="profile_image" accept=".jpg, .jpeg, .png, .gif">
    </div>
    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
</form>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
