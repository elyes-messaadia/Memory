<?php
// Fonction pour enregistrer un message flash dans la session
function set_flash_message($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_message_type'] = $type;
}

// Fonction pour afficher le message flash stocké dans la session
function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = htmlspecialchars($_SESSION['flash_message']);
        $type = htmlspecialchars($_SESSION['flash_message_type']);
        echo "<div class='alert alert-$type' role='alert'>$message</div>";
        // Supprimer le message après l'avoir affiché
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_message_type']);
    }
}

// Fonction pour générer un token CSRF
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}

// Fonction pour vérifier le token CSRF
function verify_csrf_token() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Token CSRF invalide.');
    }
}

// Fonction pour vérifier si un utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
function ensure_logged_in() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Fonction pour sécuriser les sorties HTML
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
