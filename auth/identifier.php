 <?php
    session_start();
    
    if(!isset($_SESSION['user'])) {
        header('location: ../../auth/login.php');
        exit();

    }

?>
