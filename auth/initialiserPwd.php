<?php
require_once('../config/connexiondb.php');
require_once('../functions/fonctions.php');

if (isset($_POST['email']))
    $email = $_POST['email'];
else
    $email = "";

$user = rechercher_user_par_email($email);

if ($user != null) {
    $id = $user['iduser'];
    $requete = $pdo->prepare("update utilisateur set pwd=MD5('0000') where iduser=$id");
    $requete->execute();

    $to = $user['email'];
    $objet = "Initialisation de votre mot de passe";
    $content = "Votre nouveau mot de passe est 0000, veuillez le modifier à la prochaine ouverture de session";
    $entetes = "From: GesStag" . "\r\n" . "CC: ABDELLAHELATRACH20@GMAIL.COM";

    mail($to, $objet, $content, $entetes);

    $erreur = "non";
    $msg = "Un message contenant votre nouveau mot de passe a été envoyé sur votre adresse Email.";
} else {
    $erreur = "oui";
    $msg = "<strong>Erreur!</strong> L'Email est incorrect!!!";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Initialisation du mot de passe - Gestion des Stagiaires</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/logo_icon.ico">
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/monstyle.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/initialiserPwd.css">
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="reset-container">
        <div class="reset-header">
            <div class="icon">
                <i class="fa fa-key"></i>
            </div>
            <h2>Réinitialisation</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 14px;">Mot de passe oublié</p>
        </div>
        
        <div class="reset-body">
            <form method="post" class="form" id="resetForm">
                <?php if (isset($erreur)) { ?>
                    <div class="alert <?php echo $erreur == "oui" ? 'alert-danger' : 'alert-success'; ?>">
                        <i class="fa <?php echo $erreur == "oui" ? 'fa-exclamation-triangle' : 'fa-check-circle'; ?>"></i>
                        <?php echo $msg; ?>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder=" " 
                           autocomplete="email" required id="emailInput" value="<?php echo htmlspecialchars($email); ?>">
                    <i class="input-icon fa fa-envelope"></i>
                    <label class="form-label">Adresse email</label>
                </div>

                <button type="submit" class="btn btn-reset" id="submitBtn">
                    <div class="loading"></div>
                    <i class="fa fa-paper-plane"></i>
                    Initialiser le mot de passe
                </button>

                <div class="reset-links">
                    <a href="login.php">
                        <i class="fa fa-arrow-left"></i>
                        Retour à la connexion
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resetForm').submit(function() {
                const submitBtn = $('#submitBtn');
                submitBtn.addClass('loading');
                submitBtn.prop('disabled', true);
                
                setTimeout(function() {
                    submitBtn.removeClass('loading');
                    submitBtn.prop('disabled', false);
                }, 3000);
            });

            $('.form-control').on('focus blur', function() {
                $(this).parent().toggleClass('focused');
            });

            $('#emailInput').focus();

            <?php if (isset($erreur)) { ?>
                $('.reset-container').addClass('shake');
                setTimeout(function() {
                    $('.reset-container').removeClass('shake');
                }, 500);
            <?php } ?>
        });
    </script>
</body>
</html>