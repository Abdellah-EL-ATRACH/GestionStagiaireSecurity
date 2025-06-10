<?php
// Toujours démarrer la session en premier
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('../config/connexiondb.php');

$login = trim($_POST['login'] ?? '');
$pwd = $_POST['pwd'] ?? '';

if (empty($login) || empty($pwd)) {
    $_SESSION['erreurLogin'] = "Login et mot de passe requis";
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT iduser, login, email, role, etat FROM utilisateur WHERE login = ? AND pwd = MD5(?)");
$stmt->execute([$login, $pwd]);
$user = $stmt->fetch();

if ($user) {
    if ($user['etat'] == 1) {
        // Stockage minimal et sécurisé en session
        $_SESSION['user'] = [
            'iduser' => (int)$user['iduser'],
            'login' => htmlspecialchars($user['login']),
            'role' => $user['role'] // Doit valider 'ADMIN' ou 'VISITEUR'
        ];
        
        // Régénération de l'ID de session
        session_regenerate_id(true);
        
        // Redirection ABSOLUE
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/GestionStagiaireSecurity/index.php');
        exit();
    } else {
        $_SESSION['erreurLogin'] = "Compte désactivé";
    }
} else {
    $_SESSION['erreurLogin'] = "Identifiants incorrects";
}

header('Location: login.php');
exit();
?>