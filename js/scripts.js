document.addEventListener('DOMContentLoaded', function() {
    // Confirmation de déconnexion
    const logoutForm = document.querySelector('form[action="logout.php"]');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function(e) {
            if (!confirm('Voulez-vous vraiment vous déconnecter ?')) {
                e.preventDefault();
            }
        });
    }

    // Prévisualisation de l'image de profil
    const profileImageInput = document.querySelector('input[name="profile_image"]');
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                const preview = document.querySelector('img[alt="Profile Image"]');
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                }
            }
        });
    }

    // Gestion du thème avec deux boutons Light et Dark
    const lightThemeBtn = document.getElementById('theme-light');
    const darkThemeBtn = document.getElementById('theme-dark');

    if (localStorage.getItem('theme') === 'dark') {
        document.documentElement.classList.add('dark-theme');
    }

    lightThemeBtn.addEventListener('click', function() {
        document.documentElement.classList.remove('dark-theme');
        localStorage.setItem('theme', 'light');
    });

    darkThemeBtn.addEventListener('click', function() {
        document.documentElement.classList.add('dark-theme');
        localStorage.setItem('theme', 'dark');
    });
});
