<?php
session_start();
if (isset($_SESSION['erreurLogin']))
    $erreurLogin = $_SESSION['erreurLogin'];
else {
    $erreurLogin = "";
}
session_destroy(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Gestion des Stagiaires</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo_icon.ico">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/monstyle.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/login.css">
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="icon">
                <i class="fa fa-user-circle"></i>
            </div>
            <h2>Connexion</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 14px;">Gestion des Stagiaires</p>
        </div>
        
        <div class="login-body">
            <form method="post" action="seConnecter.php" class="form" id="loginForm">
                <?php if (!empty($erreurLogin)) { ?>
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($erreurLogin) ?>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <input type="text" name="login" class="form-control" placeholder=" " 
                           autocomplete="username" required id="loginInput">
                    <i class="input-icon fa fa-user"></i>
                    <label class="form-label">Nom d'utilisateur</label>
                </div>

                <div class="form-group">
                    <input type="password" name="pwd" class="form-control" placeholder=" " 
                           autocomplete="current-password" required id="passwordInput">
                    <i class="input-icon fa fa-lock"></i>
                    <label class="form-label">Mot de passe</label>
                    <i class="password-toggle fa fa-eye" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn btn-login" id="submitBtn">
                    <div class="loading"></div>
                    <i class="fa fa-sign-in"></i>
                    Se connecter
                </button>

                <div class="login-links">
                    <a href="InitialiserPwd.php">
                        <i class="fa fa-key"></i>
                        Mot de passe oublié ?
                    </a>
                    <a href="../modules/utilisateur/nouvelUtilisateur.php">
                        <i class="fa fa-user-plus"></i>
                        Créer un compte
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordInput = $('#passwordInput');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // Form submission with loading state
            $('#loginForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('loading');
                submitBtn.prop('disabled', true);
                
                // Remove loading state after 3 seconds (in case of slow response)
                setTimeout(function() {
                    submitBtn.removeClass('loading');
                    submitBtn.prop('disabled', false);
                }, 3000);
            });

            // Input focus effects
            $('.form-control').on('focus blur', function() {
                $(this).parent().toggleClass('focused');
            });

            // Auto-focus on first input
            $('#loginInput').focus();

            // Add shake animation to form on error
            <?php if (!empty($erreurLogin)) { ?>
                $('.login-container').addClass('shake');
                setTimeout(function() {
                    $('.login-container').removeClass('shake');
                }, 500);
            <?php } ?>
        });
    </script>
</body>
</html>