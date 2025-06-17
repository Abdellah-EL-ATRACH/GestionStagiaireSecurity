<?php

require_once("../../config/connexiondb.php");
require_once("../../functions/fonctions.php");

//echo 'Nombre des  user1 :  '.rechercher_par_login('user1');
//echo 'Nombre des  user1@gmail.com :  '.rechercher_par_email('user1@gmail.com');
$validationErrors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = $_POST['login'];
    $pwd1 = $_POST['pwd1'];
    $pwd2 = $_POST['pwd2'];
    $email = $_POST['email'];


    if (isset($login)) {
        $filtredLogin = filter_var($login, FILTER_SANITIZE_STRING);

        if (strlen($filtredLogin) < 4) {
            $validationErrors[] = "Erreur!!! Le login doit contenir au moins 4 caratères";
        }
    }

    if (isset($pwd1) && isset($pwd2)) {

        if (empty($pwd1)) {
            $validationErrors[] = "Erreur!!! Le mot ne doit pas etre vide";
        }

        if (md5($pwd1) !== md5($pwd2)) {
            $validationErrors[] = "Erreur!!! les deux mot de passe ne sont pas identiques";

        }
    }

    if (isset($email)) {
        $filtredEmail = filter_var($login, FILTER_SANITIZE_EMAIL);

        if ($filtredEmail != true) {
            $validationErrors[] = "Erreur!!! Email  non valid";

        }
    }

    if (empty($validationErrors)) {
        if (rechercher_par_login($login) == 0 & rechercher_par_email($email) == 0) {
            $requete = $pdo->prepare("INSERT INTO utilisateur(login,email,pwd,role,etat) 
                                        VALUES(:plogin,:pemail,:ppwd,:prole,:petat)");

            $requete->execute(array('plogin' => $login,
                'pemail' => $email,
                'ppwd' => md5($pwd1),
                'prole' => $_POST['role'],     // <-- Vulnérabilité ici
                'petat' => $_POST['etat']));

            $success_msg = "Félicitation, votre compte est crée, mais temporairement inactif jusqu'a activation par l'admin";
        } else {
            if (rechercher_par_login($login) > 0) {
                $validationErrors[] = 'Désolé le login exsite deja';
            }
            if (rechercher_par_email($email) > 0) {
                $validationErrors[] = 'Désolé cet email exsite deja';
            }
        }

    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nouvel utilisateur - Gestion des Stagiaires</title>
    <link rel="icon" type="image/x-icon" href="../../assets/images/logo_icon.ico">
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../assets/css/monstyle.css">
    <link rel="stylesheet" type="text/css" href="../../assets/new_account.css">
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="signup-container">
        <div class="signup-header">
            <div class="icon">
                <i class="fa fa-user-plus"></i>
            </div>
            <h2>Inscription</h2>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 14px;">Création d'un nouveau compte</p>
        </div>
        
        <div class="signup-body">
            <form method="post" class="form" id="signupForm">
                <?php 
                if (isset($validationErrors) && !empty($validationErrors)) {
                    foreach ($validationErrors as $error) {
                        echo '<div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i>
                                ' . htmlspecialchars($error) . '
                              </div>';
                    }
                }

                if (isset($success_msg) && !empty($success_msg)) {
                    echo '<div class="alert alert-success">
                            <i class="fa fa-check-circle"></i>
                            ' . htmlspecialchars($success_msg) . '
                          </div>';
                }
                ?>

                <div class="form-group">
                    <input type="text" name="login" class="form-control" placeholder=" " 
                           autocomplete="username" required id="loginInput"
                           minlength="4" title="Le login doit contenir au moins 4 caractères...">
                    <i class="input-icon fa fa-user"></i>
                    <label class="form-label">Nom d'utilisateur</label>
                </div>

                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder=" " 
                           autocomplete="email" required id="emailInput">
                    <i class="input-icon fa fa-envelope"></i>
                    <label class="form-label">Adresse email</label>
                </div>

                <div class="form-group">
                    <input type="password" name="pwd1" class="form-control" placeholder=" " 
                           autocomplete="new-password" required id="passwordInput1"
                           minlength="3" title="Le mot de passe doit contenir au moins 3 caractères...">
                    <i class="input-icon fa fa-lock"></i>
                    <label class="form-label">Mot de passe</label>
                    <i class="password-toggle fa fa-eye" id="togglePassword1"></i>
                </div>

                <div class="form-group">
                    <input type="password" name="pwd2" class="form-control" placeholder=" " 
                           autocomplete="new-password" required id="passwordInput2"
                           minlength="3">
                    <i class="input-icon fa fa-lock"></i>
                    <label class="form-label">Confirmer le mot de passe</label>
                    <i class="password-toggle fa fa-eye" id="togglePassword2"></i>
                </div>

                <input type="hidden" name="etat" value="0">
                <input type="hidden" name="role" value="Visiteur">

                <button type="submit" class="btn btn-signup" id="submitBtn">
                    <div class="loading"></div>
                    <i class="fa fa-user-plus"></i>
                    Créer mon compte
                </button>

                <div class="login-links">
                    <a href="../../auth/login.php">
                        <i class="fa fa-sign-in"></i>
                        Vous avez déjà un compte ? Se connecter
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="../../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../../assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword1').click(function() {
                const passwordInput = $('#passwordInput1');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            $('#togglePassword2').click(function() {
                const passwordInput = $('#passwordInput2');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                
                $(this).toggleClass('fa-eye fa-eye-slash');
            });

            // Form submission with loading state
            $('#signupForm').submit(function() {
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

            // Password confirmation validation
            function validatePasswords() {
                const pwd1 = $('#passwordInput1').val();
                const pwd2 = $('#passwordInput2').val();
                
                if (pwd1 && pwd2) {
                    if (pwd1 !== pwd2) {
                        $('#passwordInput2')[0].setCustomValidity('Les mots de passe ne correspondent pas');
                    } else {
                        $('#passwordInput2')[0].setCustomValidity('');
                    }
                }
            }
            
            $('#passwordInput1, #passwordInput2').on('input', validatePasswords);

            // Add shake animation to form on error
            <?php if (isset($validationErrors) && !empty($validationErrors)) { ?>
                $('.signup-container').addClass('shake');
                setTimeout(function() {
                    $('.signup-container').removeClass('shake');
                }, 500);
            <?php } ?>

            // Auto-redirect after successful registration
            <?php if (isset($success_msg) && !empty($success_msg)) { ?>
                // Show countdown in success message
                let countdown = 5;
                const countdownInterval = setInterval(function() {
                    countdown--;
                    if (countdown > 0) {
                        $('.alert-success').html('<i class="fa fa-check-circle"></i> <?php echo addslashes($success_msg); ?> <br><small>Redirection dans ' + countdown + ' secondes...</small>');
                    } else {
                        clearInterval(countdownInterval);
                        window.location.href = 'login.php';
                    }
                }, 1000);
            <?php } ?>
        });
    </script>
</body>
</html>