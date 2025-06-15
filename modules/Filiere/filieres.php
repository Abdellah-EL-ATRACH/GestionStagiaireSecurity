<?php
    require_once('../../auth/identifier.php');
    require_once("../../config/connexiondb.php");
    
    $nomf = isset($_GET['nomF']) ? trim($_GET['nomF']) : "";
    $niveau = isset($_GET['niveau']) ? $_GET['niveau'] : "all";
    
    $size = isset($_GET['size']) ? max(1, min(50, (int)$_GET['size'])) : 6;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $size;
    
    // Use prepared statements to prevent SQL injection
    if ($niveau == "all") {
        if (!empty($nomf)) {
            $requete = "SELECT * FROM filiere 
                       WHERE nomFiliere LIKE :nomf 
                       LIMIT :size OFFSET :offset";
            $requeteCount = "SELECT COUNT(*) as countF FROM filiere 
                            WHERE nomFiliere LIKE :nomf";
            $stmt = $pdo->prepare($requete);
            $stmtCount = $pdo->prepare($requeteCount);
            $searchNomf = '%' . $nomf . '%';
            $stmt->bindParam(':nomf', $searchNomf, PDO::PARAM_STR);
            $stmtCount->bindParam(':nomf', $searchNomf, PDO::PARAM_STR);
        } else {
            $requete = "SELECT * FROM filiere 
                       LIMIT :size OFFSET :offset";
            $requeteCount = "SELECT COUNT(*) as countF FROM filiere";
            $stmt = $pdo->prepare($requete);
            $stmtCount = $pdo->prepare($requeteCount);
        }
    } else {
        if (!empty($nomf)) {
            $requete = "SELECT * FROM filiere 
                       WHERE nomFiliere LIKE :nomf 
                       AND niveau = :niveau 
                       LIMIT :size OFFSET :offset";
            $requeteCount = "SELECT COUNT(*) as countF FROM filiere 
                            WHERE nomFiliere LIKE :nomf 
                            AND niveau = :niveau";
            $stmt = $pdo->prepare($requete);
            $stmtCount = $pdo->prepare($requeteCount);
            $searchNomf = '%' . $nomf . '%';
            $stmt->bindParam(':nomf', $searchNomf, PDO::PARAM_STR);
            $stmt->bindParam(':niveau', $niveau, PDO::PARAM_STR);
            $stmtCount->bindParam(':nomf', $searchNomf, PDO::PARAM_STR);
            $stmtCount->bindParam(':niveau', $niveau, PDO::PARAM_STR);
        } else {
            $requete = "SELECT * FROM filiere 
                       WHERE niveau = :niveau 
                       LIMIT :size OFFSET :offset";
            $requeteCount = "SELECT COUNT(*) as countF FROM filiere 
                            WHERE niveau = :niveau";
            $stmt = $pdo->prepare($requete);
            $stmtCount = $pdo->prepare($requeteCount);
            $stmt->bindParam(':niveau', $niveau, PDO::PARAM_STR);
            $stmtCount->bindParam(':niveau', $niveau, PDO::PARAM_STR);
        }
    }
    
    $stmt->bindParam(':size', $size, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $stmtCount->execute();
    
    $resultatF = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tabCount = $stmtCount->fetch();
    $nbrFiliere = $tabCount['countF'];
    
    $reste = $nbrFiliere % $size;
    if ($reste === 0)
        $nbrPage = $nbrFiliere / $size;   
    else
        $nbrPage = floor($nbrFiliere / $size) + 1;
        
    // Fonction pour afficher le niveau complet
    function getNiveauComplet($niveau) {
        $niveaux = [
            'q' => 'Qualification',
            't' => 'Technicien',
            'ts' => 'Technicien Spécialisé',
            'l' => 'Licence',
            'm' => 'Master'
        ];
        return isset($niveaux[$niveau]) ? $niveaux[$niveau] : $niveau;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gestion des filières</title>
        <link rel="icon" type="image/x-icon" href="../../assets/images/logo_icon.ico">
        <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/monstyle.css">

        <!-- Content Security Policy -->
        <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ajax.googleapis.com https://code.jquery.com; style-src 'self' 'unsafe-inline' https://maxcdn.bootstrapcdn.com; img-src 'self' data: https://www.google.com https://www.gstatic.com; font-src 'self' data: https://maxcdn.bootstrapcdn.com;">
        <script src="../../assets/js/jquery-3.6.0.min.js"></script>
        <script src="../../assets/js/bootstrap.min.js"></script>
        <script src="../../assets/js/monjs.js"></script>
    </head>
    <body>
        <?php include("../../includes/menu.php"); ?>
        
        <div class="container">
            <div class="panel panel-success margetop60">
                <div class="panel-heading">Rechercher des filières</div>
                <div class="panel-body">
                    <form method="get" action="filieres.php" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="nomF" 
                                   placeholder="Nom de la filière"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($nomf) ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="niveau">Niveau:</label>
                            <select name="niveau" class="form-control" id="niveau"
                                    onchange="this.form.submit()">
                                <option value="all" <?php if($niveau === "all") echo "selected" ?>>Tous les niveaux</option>
                                <option value="q"   <?php if($niveau === "q")   echo "selected" ?>>Qualification</option>
                                <option value="t"   <?php if($niveau === "t")   echo "selected" ?>>Technicien</option>
                                <option value="ts"  <?php if($niveau === "ts")  echo "selected" ?>>Technicien Spécialisé</option>
                                <option value="l"   <?php if($niveau === "l")   echo "selected" ?>>Licence</option>
                                <option value="m"   <?php if($niveau === "m")   echo "selected" ?>>Master</option> 
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
                        
                        <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                            <a href="nouvelleFiliere.php" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus"></span>
                                Nouvelle filière
                            </a>
                        <?php endif; ?>                 
                    </form>
                </div>
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des filières (<?php echo $nbrFiliere ?> filière<?php echo $nbrFiliere > 1 ? 's' : '' ?>)</div>
                <div class="panel-body">
                    <?php if (empty($resultatF)): ?>
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>
                            Aucune filière trouvée.
                        </div>
                    <?php else: ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID Filière</th>
                                <th>Nom Filière</th>
                                <th>Niveau</th>
                                <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach($resultatF as $filiere): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <?php echo htmlspecialchars($filiere['idFiliere']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($filiere['nomFiliere']) ?></strong>
                                    </td>
                                    <td>
                                        <span class="label label-info">
                                            <?php echo htmlspecialchars(getNiveauComplet($filiere['niveau'])) ?>
                                        </span>
                                    </td> 
                                    
                                    <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                                        <td>
                                            <a href="editerFiliere.php?idF=<?php echo $filiere['idFiliere'] ?>" 
                                               class="btn btn-xs btn-warning" 
                                               title="Modifier">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            
                                            <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette filière ?')"
                                               href="supprimerFiliere.php?idF=<?php echo $filiere['idFiliere'] ?>"
                                               class="btn btn-xs btn-danger"
                                               title="Supprimer">
                                                <span class="glyphicon glyphicon-trash"></span>
                                            </a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                       </tbody>
                    </table>
                    <?php endif; ?>
                    
                    <?php if ($nbrPage > 1): ?>
                    <div>
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li>
                                    <a href="filieres.php?page=1&nomF=<?php echo urlencode($nomf) ?>&niveau=<?php echo urlencode($niveau) ?>&size=<?php echo $size ?>" 
                                       title="Première page">
                                        <span>&laquo;&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="filieres.php?page=<?php echo $page - 1 ?>&nomF=<?php echo urlencode($nomf) ?>&niveau=<?php echo urlencode($niveau) ?>&size=<?php echo $size ?>" 
                                       title="Page précédente">
                                        <span>&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $nbrPage; $i++): ?>
                                <li class="<?php if($i == $page) echo 'active' ?>"> 
                                    <a href="filieres.php?page=<?php echo $i ?>&nomF=<?php echo urlencode($nomf) ?>&niveau=<?php echo urlencode($niveau) ?>&size=<?php echo $size ?>">
                                        <?php echo $i ?>
                                    </a> 
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $nbrPage): ?>
                                <li>
                                    <a href="filieres.php?page=<?php echo $page + 1 ?>&nomF=<?php echo urlencode($nomf) ?>&niveau=<?php echo urlencode($niveau) ?>&size=<?php echo $size ?>" 
                                       title="Page suivante">
                                        <span>&raquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="filieres.php?page=<?php echo $nbrPage ?>&nomF=<?php echo urlencode($nomf) ?>&niveau=<?php echo urlencode($niveau) ?>&size=<?php echo $size ?>" 
                                       title="Dernière page">
                                        <span>&raquo;&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                        <div class="pagination-info">
                            <small class="text-muted">
                                Affichage <?php echo ($offset + 1) ?> à <?php echo min($offset + $size, $nbrFiliere) ?> 
                                sur <?php echo $nbrFiliere ?> filière<?php echo $nbrFiliere > 1 ? 's' : '' ?>
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>