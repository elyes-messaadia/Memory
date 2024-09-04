<?php
$pageTitle = "Profil";
include('includes/_header.php'); // Inclusion de l'en-tête

display_flash_message(); // Afficher les messages flash

ensure_logged_in(); // Vérifier si l'utilisateur est connecté

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_token(); // Vérification du token CSRF

    $username = $conn->real_escape_string($_POST['username']);
    $profile_image = $_SESSION['profile_image'];

    if (!empty($_FILES['profile_image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_image']['type'], $allowed_types) && $_FILES['profile_image']['size'] <= 1048576) {
            $profile_image = basename($_FILES['profile_image']['name']);
            $target_dir = "uploads/";
            $target_file = $target_dir . $profile_image;
            $profile_image = $conn->real_escape_string($profile_image);

            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                set_flash_message("Erreur lors du téléchargement de l'image.", 'danger');
                header("Location: profile.php");
                exit();
            }
            $_SESSION['profile_image'] = $profile_image;
        } else {
            set_flash_message("Format de fichier non valide ou taille trop grande.", 'danger');
        }
    }

    $sql = "UPDATE users SET username = ?, profile_image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $profile_image, $user_id);

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql = "UPDATE users SET username = ?, profile_image = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $profile_image, $password, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        set_flash_message("Mise à jour réussie !");
        header("Location: profile.php");
        exit();
    } else {
        set_flash_message("Erreur lors de la mise à jour : " . $conn->error, 'danger');
    }

    $stmt->close();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<form action="profile.php" method="post" enctype="multipart/form-data" class="mt-4">
    <?php generate_csrf_token(); ?> <!-- Ajout du token CSRF -->
    <div class="mb-3">
        <label for="username" class="form-label">Nom d'utilisateur :</label>
        <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="profile_image" class="form-label">Nouvelle image de profil (Max 1MB) :</label>
        <input type="file" class="form-control" name="profile_image" accept=".jpg, .jpeg, .png, .gif">
        <img src="uploads/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Image" class="img-thumbnail mt-2" width="150">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
        <input type="password" class="form-control" name="password" minlength="8">
    </div>
    <button type="submit" class="btn btn-success w-100">Mettre à jour le profil</button>
</form>

<form action="logout.php" method="post" class="mt-3">
    <button type="submit" class="btn btn-danger w-100">Se déconnecter</button>
</form>

<?php include('includes/_footer.php'); // Inclusion du pied de page ?>
