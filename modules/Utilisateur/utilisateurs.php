<?php
    require_once('../../includes/role.php');
    require_once("../../config/connexiondb.php");

    $login = isset($_GET['login']) ? trim($_GET['login']) : "";
    
    $size = isset($_GET['size']) ? max(1, min(50, (int)$_GET['size'])) : 3;
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($page - 1) * $size;
   
    // Use prepared statements to prevent SQL injection
    if (!empty($login)) {
        $requeteUser = "SELECT * FROM utilisateur WHERE login LIKE :login LIMIT :offset, :size";
        $requeteCount = "SELECT COUNT(*) as countUser FROM utilisateur WHERE login LIKE :login";
        $stmt = $pdo->prepare($requeteUser);
        $stmtCount = $pdo->prepare($requeteCount);
        $searchLogin = '%' . $login . '%';
        $stmt->bindParam(':login', $searchLogin, PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
        $stmtCount->bindParam(':login', $searchLogin, PDO::PARAM_STR);
    } else {
        $requeteUser = "SELECT * FROM utilisateur LIMIT :offset, :size";
        $requeteCount = "SELECT COUNT(*) as countUser FROM utilisateur";
        $stmt = $pdo->prepare($requeteUser);
        $stmtCount = $pdo->prepare($requeteCount);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':size', $size, PDO::PARAM_INT);
    }
    
    $stmt->execute();
    $stmtCount->execute();
    
    $resultatUser = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $tabCount = $stmtCount->fetch();
    $nbrUser = $tabCount['countUser'];
    
    $reste = $nbrUser % $size;   
    if($reste === 0) 
        $nbrPage = $nbrUser / $size;   
    else
        $nbrPage = floor($nbrUser / $size) + 1;  
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Gestion des utilisateurs</title>
        <link rel="icon" type="image/x-icon" href="../../assets/images/logo_icon.ico">
        <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/monstyle.css">
    </head>
    <body>
        <?php include("../../includes/menu.php"); ?>
        
        <div class="container">
            <div class="panel panel-success margetop60">
				<div class="panel-heading">Rechercher des utilisateurs</div>
				<div class="panel-body">
					<form method="get" action="utilisateurs.php" class="form-inline">
						<div class="form-group">
                            <input type="text" name="login" 
                                   placeholder="Login"
                                   class="form-control"
                                   value="<?php echo htmlspecialchars($login) ?>"/>
                        </div>
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search"></span>
                            Chercher...
                        </button> 
					</form>
				</div>
			</div>
            
            <div class="panel panel-primary">
                <div class="panel-heading">Liste des utilisateurs (<?php echo $nbrUser ?> utilisateurs)</div>
                <div class="panel-body">
                    <?php if (empty($resultatUser)): ?>
                        <div class="alert alert-info">
                            <span class="glyphicon glyphicon-info-sign"></span>
                            Aucun utilisateur trouvé.
                        </div>
                    <?php else: ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Login</th> 
                                <th>Email</th> 
                                <th>Role</th> 
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <?php foreach($resultatUser as $user): ?>
                                <tr class="<?php echo $user['etat'] == 1 ? 'success' : 'danger' ?>">
                                    <td><?php echo htmlspecialchars($user['login']) ?></td>
                                    <td><?php echo htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <span class="label label-<?php echo $user['role'] === 'ADMIN' ? 'danger' : 'info' ?>">
                                            <?php echo htmlspecialchars($user['role']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="label label-<?php echo $user['etat'] == 1 ? 'success' : 'default' ?>">
                                            <?php echo $user['etat'] == 1 ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </td>  
                                    <td>
                                        <a href="editerUtilisateur.php?id=<?php echo $user['iduser'] ?>" 
                                           class="btn btn-xs btn-warning" 
                                           title="Modifier">
                                            <span class="glyphicon glyphicon-edit"></span>
                                        </a>
                                        
                                        <a onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')"
                                           href="supprimerUtilisateur.php?idUser=<?php echo $user['iduser'] ?>"
                                           class="btn btn-xs btn-danger"
                                           title="Supprimer">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </a>
                                        
                                        <a href="activerUtilisateur.php?idUser=<?php echo $user['iduser'] ?>&etat=<?php echo $user['etat'] ?>" 
                                           class="btn btn-xs btn-<?php echo $user['etat'] == 1 ? 'default' : 'success' ?>"
                                           title="<?php echo $user['etat'] == 1 ? 'Désactiver' : 'Activer' ?>">
                                            <?php  
                                                if($user['etat'] == 1)
                                                    echo '<span class="glyphicon glyphicon-remove"></span>';
                                                else 
                                                    echo '<span class="glyphicon glyphicon-ok"></span>';
                                            ?>
                                        </a>
                                    </td>       
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
                                    <a href="utilisateurs.php?page=<?php echo $page - 1 ?>&login=<?php echo urlencode($login) ?>&size=<?php echo $size ?>" 
                                       aria-label="Précédent">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php 
                            $start = max(1, $page - 2);
                            $end = min($nbrPage, $page + 2);
                            
                            for($i = $start; $i <= $end; $i++): ?>
                                <li class="<?php if($i == $page) echo 'active' ?>"> 
                                    <a href="utilisateurs.php?page=<?php echo $i ?>&login=<?php echo urlencode($login) ?>&size=<?php echo $size ?>">
                                        <?php echo $i ?>
                                    </a> 
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $nbrPage): ?>
                                <li>
                                    <a href="utilisateurs.php?page=<?php echo $page + 1 ?>&login=<?php echo urlencode($login) ?>&size=<?php echo $size ?>" 
                                       aria-label="Suivant">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        
                        <div class="text-muted">
                            Page <?php echo $page ?> sur <?php echo $nbrPage ?> 
                            (<?php echo $nbrUser ?> utilisateur<?php echo $nbrUser > 1 ? 's' : '' ?> au total)
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>