<?php
require_once('identifier.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Changement de Mot de Passe - Gestion des Stagiaires</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo_icon.ico">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/monstyle.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/editPwd.css">

</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="editpwd-container">
        <div class="editpwd-header">
            <div class="icon">
                <i class="fa fa-key"></i>
            </div>
            <h2>Changement</h2>
            <p>Compte: <?php echo htmlspecialchars($_SESSION['user']['login']) ?></p>
        </div>
        
        <div class="editpwd-body">
            <form method="post" action="updatePwd.php" class="form" id="editPwdForm">
                <div class="form-group">
                    <input type="password" name="oldpwd" class="form-control" placeholder=" " 
                           autocomplete="current-password" required id="oldPwdInput" minlength="4"/>
                    <i class="input-icon fa fa-lock"></i>
                    <label class="form-label">Ancien mot de passe</label>
                    <i class="password-toggle fa fa-eye" id="toggleOldPwd"></i>
                </div>
                <div class="form-group">
                    <input type="password" name="newpwd" class="form-control" placeholder=" " 
                           autocomplete="new-password" required id="newPwdInput" minlength="4"/>
                    <i class="input-icon fa fa-lock"></i>
                    <label class="form-label">Nouveau mot de passe</label>
                    <i class="password-toggle fa fa-eye" id="toggleNewPwd"></i>
                </div>
                <button type="submit" class="btn btn-save" id="submitBtn">
                    <div class="loading"></div>
                    <i class="fa fa-save"></i>
                    Enregistrer
                </button>
            </form>
        </div>
    </div>

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility for old password
            $('#toggleOldPwd').click(function() {
                const oldPwdInput = $('#oldPwdInput');
                const type = oldPwdInput.attr('type') === 'password' ? 'text' : 'password';
                oldPwdInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // Toggle password visibility for new password
            $('#toggleNewPwd').click(function() {
                const newPwdInput = $('#newPwdInput');
                const type = newPwdInput.attr('type') === 'password' ? 'text' : 'password';
                newPwdInput.attr('type', type);
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // Form submission with loading state
            $('#editPwdForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('loading');
                submitBtn.prop('disabled', true);
                
                setTimeout(function() {
                    submitBtn.removeClass('loading');
                    submitBtn.prop('disabled', false);
                }, 3000);
            });

            // Input focus effects
            $('.form-control').on('focus blur', function() {
                $(this).parent().toggleClass('focused');
            });

            // Auto-focus on old password input
            $('#oldPwdInput').focus();
        });
    </script>
</body>
</html>