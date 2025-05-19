<?php
    require_once('../../auth/identifier.php');
    require_once('../../config/connexiondb.php');

    $idf=isset($_POST['idF'])?$_POST['idF']:0;

    $nomf=isset($_POST['nomF'])?$_POST['nomF']:"";

    $niveau=isset($_POST['niveau'])?strtoupper($_POST['niveau']):"";
    
    $requete="update filiere set nomFiliere=?,niveau=? where idFiliere=?";

    $params=array($nomf,$niveau,$idf);

    $resultat=$pdo->prepare($requete);

    $resultat->execute($params);
    
    header('location:filieres.php');
?>
