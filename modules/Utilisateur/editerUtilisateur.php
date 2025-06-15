<?php
    require_once('../../includes/role.php');
    require_once("../../config/connexiondb.php");

    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Use prepared statement to prevent SQL injection
    $requete = "SELECT * FROM utilisateur WHERE iduser = :id";
    $stmt = $pdo->prepare($requete);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    $utilisateur = $stmt->fetch();
    
    // Check if user exists
    if (!$utilisateur) {
        // Redirect or show error message if user not found
        header("Location: ../../index.php?error=user_not_found");
        exit();
    }
    
    $login = $utilisateur['login'];
    $email = $utilisateur['email'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Édition d'un Utilisateur - Gestion des Stagiaires</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo_icon.ico">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/monstyle.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/editerUtilisateur.css">
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="edit-container">
        <div class="edit-header">
            <div class="icon">
                <i class="fa fa-user-edit"></i>
            </div>
            <h2>Édition</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 14px;">Utilisateur</p>
        </div>
        
        <div class="edit-body">
            <form method="post" action="updateUtilisateur.php" class="form" id="editForm">
                <div class="form-group">
                    <input type="hidden" name="iduser" value="<?php echo htmlspecialchars($id) ?>"/>
                </div>
                <div class="form-group">
                    <input type="text" name="login" class="form-control" placeholder=" " 
                           autocomplete="username" required id="loginInput" value="<?php echo htmlspecialchars($login) ?>"/>
                    <i class="input-icon fa fa-user"></i>
                    <label class="form-label">Nom d'utilisateur</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder=" " 
                           autocomplete="email" required id="emailInput" value="<?php echo htmlspecialchars($email) ?>"/>
                    <i class="input-icon fa fa-envelope"></i>
                    <label class="form-label">Email</label>
                </div>
                <button type="submit" class="btn btn-save" id="submitBtn">
                    <div class="loading"></div>
                    <i class="fa fa-save"></i>
                    Enregistrer
                </button>
                <div class="edit-links">
                    <a href="../../auth/editPwd.php">
                        <i class="fa fa-key"></i>
                        Changer le mot de passe
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Form submission with loading state
            $('#editForm').submit(function() {
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

            // Auto-focus on login input
            $('#loginInput').focus();
        });
    </script>
</body>
</html>