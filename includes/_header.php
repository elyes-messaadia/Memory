
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($pageTitle ?? 'Memory Game'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"> <!-- Toastr CSS -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://cdnjs.cloudflare.com;">
    
    <script>
        // Appliquer le thème immédiatement avant le rendu de la page pour éviter le "flash"
        (function() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            if (currentTheme === 'dark') {
                document.documentElement.classList.add('dark-theme');
            }
        })();
    </script>
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Memory Game</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($pageTitle == 'Tableau de bord') ? 'active' : ''; ?>" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($pageTitle == 'Profil') ? 'active' : ''; ?>" href="profile.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($pageTitle == 'Jouer') ? 'active' : ''; ?>" href="game.php">Jouer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                    <li class="nav-item">
                        <img id="theme-toggle" src="https://img.icons8.com/color/48/moon-satellite.png" alt="moon-satellite" style="cursor:pointer;" width="48" height="48"/>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
