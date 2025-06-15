<?php
    require_once('../../auth/identifier.php');
    require_once("../../config/connexiondb.php");
    
    $nomPrenom = isset($_GET['nomPrenom']) ? trim($_GET['nomPrenom']) : "";
    $idfiliere = isset($_GET['idfiliere']) ? (int)$_GET['idfiliere'] : 0;
    $civilite = isset($_GET['civilite']) ? $_GET['civilite'] : "all";
    
    $size = isset($_GET['size']) ? max(1, min(50, (int)$_GET['size'])) : 6;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $size;
    
    // Requête pour récupérer les filières
    $requeteFiliere = "SELECT * FROM filiere ORDER BY nomFiliere";
    $stmtFiliere = $pdo->prepare($requeteFiliere);
    $stmtFiliere->execute();
    $resultatFiliere = $stmtFiliere->fetchAll(PDO::FETCH_ASSOC);
    
    // Construction des requêtes avec prepared statements
    $whereConditions = [];
    $params = [];
    
    // Condition pour nom/prénom
    if (!empty($nomPrenom)) {
        $whereConditions[] = "(s.nom LIKE :nomPrenom OR s.prenom LIKE :nomPrenom)";
        $params[':nomPrenom'] = '%' . $nomPrenom . '%';
    }
    
    // Condition pour filière
    if ($idfiliere != 0) {
        $whereConditions[] = "f.idFiliere = :idfiliere";
        $params[':idfiliere'] = $idfiliere;
    }
    
    // Condition pour civilité
    if ($civilite != "all") {
        $whereConditions[] = "s.civilite = :civilite";
        $params[':civilite'] = $civilite;
    }
    
    $whereClause = !empty($whereConditions) ? "WHERE " . implode(" AND ", $whereConditions) : "";
    
    // Requête principale
    $requeteStagiaire = "SELECT s.idStagiaire, s.nom, s.prenom, f.nomFiliere, s.photo, s.civilite 
                        FROM filiere f 
                        INNER JOIN stagiaire s ON f.idFiliere = s.idFiliere
                        $whereClause
                        ORDER BY s.idStagiaire
                        LIMIT :size OFFSET :offset";
    
    // Requête de comptage
    $requeteCount = "SELECT COUNT(*) as countS 
                    FROM filiere f 
                    INNER JOIN stagiaire s ON f.idFiliere = s.idFiliere
                    $whereClause";
    
    $stmt = $pdo->prepare($requeteStagiaire);
    $stmtCount = $pdo->prepare($requeteCount);
    
    // Bind des paramètres
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
        $stmtCount->bindValue($key, $value);
    }
    
    $stmt->bindValue(':size', $size, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $stmtCount->execute();
    
    $resultatStagiaire = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tabCount = $stmtCount->fetch();
    $nbrStagiaire = $tabCount['countS'];
    
    $reste = $nbrStagiaire % $size;
    if ($reste === 0)
        $nbrPage = $nbrStagiaire / $size;   
    else
        $nbrPage = floor($nbrStagiaire / $size) + 1;
        
    // Fonction pour afficher la civilité complète
    function getCiviliteComplet($civilite) {
        $civilites = [
            'M' => 'Monsieur',
            'F' => 'Madame',
            'Mlle' => 'Mademoiselle'
        ];
        return isset($civilites[$civilite]) ? $civilites[$civilite] : $civilite;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gestion des stagiaires</title>
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
                <div class="panel-heading">Rechercher des stagiaires</div>
                <div class="panel-body">
                    <form method="get" action="stagiaires.php" class="form-inline">
                        <div class="form-group">
                            <input type="text" name="nomPrenom" 
                                   placeholder="Nom et prénom"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($nomPrenom) ?>"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="idfiliere">Filière:</label>
                            <select name="idfiliere" class="form-control" id="idfiliere">
                                <option value="0" <?php if($idfiliere === 0) echo "selected" ?>>Toutes les filières</option>
                                <?php foreach ($resultatFiliere as $filiere): ?>
                                    <option value="<?php echo $filiere['idFiliere'] ?>"
                                        <?php if($filiere['idFiliere'] == $idfiliere) echo "selected" ?>>
                                        <?php echo htmlspecialchars($filiere['nomFiliere']) ?> 
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="civilite">Civilité:</label>
                            <select name="civilite" class="form-control" id="civilite">
                                <option value="all" <?php if($civilite === "all") echo "selected" ?>>Toutes les civilités</option>
                                <option value="M"   <?php if($civilite === "M")   echo "selected" ?>>Monsieur</option>
                                <option value="F" <?php if($civilite === "F") echo "selected" ?>>Madame</option>
                                <option value="Mlle"<?php if($civilite === "Mlle")echo "selected" ?>>Mademoiselle</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
                        
                        <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                            <a href="nouveauStagiaire.php" class="btn btn-primary">
                                <span class="glyphicon glyphicon-plus"></span>
                                Nouveau Stagiaire
                            </a>
                        <?php endif; ?>                 
                    </form>
                </div>
            </div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des Stagiaires (<?php echo $nbrStagiaire ?> stagiaire<?php echo $nbrStagiaire > 1 ? 's' : '' ?>)</div>
                <div class="panel-body">
                    <?php if (empty($resultatStagiaire)): ?>
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>
                            Aucun stagiaire trouvé.
                        </div>
                    <?php else: ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID Stagiaire</th>
                                <th>Civilité</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Filière</th>
                                <th>Photo</th>
                                <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach($resultatStagiaire as $stagiaire): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-secondary">
                                            <?php echo htmlspecialchars($stagiaire['idStagiaire']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-default">
                                            <?php echo htmlspecialchars(getCiviliteComplet($stagiaire['civilite'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($stagiaire['nom']) ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($stagiaire['prenom']) ?>
                                    </td>
                                    <td>
                                        <span class="label label-info">
                                            <?php echo htmlspecialchars($stagiaire['nomFiliere']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($stagiaire['photo'])): ?>
                                            <img src="../../assets/images/<?php echo htmlspecialchars($stagiaire['photo']) ?>"
                                                 width="50px" height="50px" class="img-circle img-thumbnail"
                                                 alt="Photo de <?php echo htmlspecialchars($stagiaire['prenom'] . ' ' . $stagiaire['nom']) ?>"
                                                 onerror="this.src='../../assets/images/default-avatar.png'">
                                        <?php else: ?>
                                            <img src="../../assets/images/default-avatar.png"
                                                 width="50px" height="50px" class="img-circle img-thumbnail"
                                                 alt="Photo par défaut">
                                        <?php endif; ?>
                                    </td>
                                    
                                    <?php if ($_SESSION['user']['role'] == 'ADMIN'): ?>
                                        <td>
                                            <a href="editerStagiaire.php?idS=<?php echo $stagiaire['idStagiaire'] ?>" 
                                               class="btn btn-xs btn-warning" 
                                               title="Modifier">
                                                <span class="glyphicon glyphicon-edit"></span>
                                            </a>
                                            
                                            <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce stagiaire ?')"
                                               href="supprimerStagiaire.php?idS=<?php echo $stagiaire['idStagiaire'] ?>"
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
                                    <a href="stagiaires.php?page=1&nomPrenom=<?php echo urlencode($nomPrenom) ?>&idfiliere=<?php echo $idfiliere ?>&civilite=<?php echo urlencode($civilite) ?>&size=<?php echo $size ?>" 
                                       title="Première page">
                                        <span>&laquo;&laquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="stagiaires.php?page=<?php echo $page - 1 ?>&nomPrenom=<?php echo urlencode($nomPrenom) ?>&idfiliere=<?php echo $idfiliere ?>&civilite=<?php echo urlencode($civilite) ?>&size=<?php echo $size ?>" 
                                       title="Page précédente">
                                        <span>&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for($i = 1; $i <= $nbrPage; $i++): ?>
                                <li class="<?php if($i == $page) echo 'active' ?>"> 
                                    <a href="stagiaires.php?page=<?php echo $i ?>&nomPrenom=<?php echo urlencode($nomPrenom) ?>&idfiliere=<?php echo $idfiliere ?>&civilite=<?php echo urlencode($civilite) ?>&size=<?php echo $size ?>">
                                        <?php echo $i ?>
                                    </a> 
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $nbrPage): ?>
                                <li>
                                    <a href="stagiaires.php?page=<?php echo $page + 1 ?>&nomPrenom=<?php echo urlencode($nomPrenom) ?>&idfiliere=<?php echo $idfiliere ?>&civilite=<?php echo urlencode($civilite) ?>&size=<?php echo $size ?>" 
                                       title="Page suivante">
                                        <span>&raquo;</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="stagiaires.php?page=<?php echo $nbrPage ?>&nomPrenom=<?php echo urlencode($nomPrenom) ?>&idfiliere=<?php echo $idfiliere ?>&civilite=<?php echo urlencode($civilite) ?>&size=<?php echo $size ?>" 
                                       title="Dernière page">
                                        <span>&raquo;&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                        <div class="pagination-info">
                            <small class="text-muted">
                                Affichage <?php echo ($offset + 1) ?> à <?php echo min($offset + $size, $nbrStagiaire) ?> 
                                sur <?php echo $nbrStagiaire ?> stagiaire<?php echo $nbrStagiaire > 1 ? 's' : '' ?>
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>