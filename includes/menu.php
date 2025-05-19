<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
            <a href="../../index.php" class="navbar-brand" style="display: flex; align-items: center;">
                <img src="../../assets/images/logo.jpeg" alt="Logo" style="height: 40px; margin-right: 10px;">
                Gestion des stagiaires
            </a>
        </div>

        <ul class="nav navbar-nav">
            <li><a href="../../modules/Stagiaire/stagiaires.php">
                <i class="fa fa-vcard"></i> &nbsp;Les Stagiaires
            </a></li>

            <li><a href="../../modules/Filiere/filieres.php">
                <i class="fa fa-tags"></i> &nbsp;Les Filières
            </a></li>

            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'ADMIN') { ?>
                <li><a href="../../modules/Utilisateur/utilisateurs.php">
                    <i class="fa fa-users"></i> &nbsp;Les Utilisateurs
                </a></li>
            <?php } ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <?php if (isset($_SESSION['user'])) { ?>
                <li>
                    <a href="../../modules/Utilisateur/editerUtilisateur.php?id=<?php echo $_SESSION['user']['iduser']; ?>">
                        <i class="fa fa-user-circle-o"></i>
                        <?php echo '&nbsp;' . htmlspecialchars($_SESSION['user']['login']); ?>
                    </a>
                </li>
                <li>
                    <a href="../../auth/seDeconnecter.php">
                        <i class="fa fa-sign-out"></i> &nbsp;Se déconnecter
                    </a>
                </li>
            <?php } ?>
        </ul>

    </div>
</nav>
