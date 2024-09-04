</div> <!-- Fin du container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> <!-- Toastr JS -->
    <script src="js/scripts.js"></script> <!-- Script personnalisé pour la gestion des thèmes, confirmation, etc. -->

    <script>
        // Affichage des messages flash avec Toastr
        <?php if (isset($_SESSION['flash_message'])): ?>
            toastr.<?php echo htmlspecialchars($_SESSION['flash_message_type']); ?>('<?php echo htmlspecialchars($_SESSION['flash_message']); ?>');
            <?php 
                // Nettoyage des messages flash après affichage
                unset($_SESSION['flash_message']);
                unset($_SESSION['flash_message_type']); 
            ?>
        <?php endif; ?>
    </script>
</body>
</html>
