<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('location:../../auth/login.php');
    exit();
} else {
    if ($_SESSION['user']['role'] != 'ADMIN') {
        header('location:../../auth/seDeconnecter.php');
        exit();
    }
}

?>
