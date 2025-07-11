<?php 
    require_once('../../auth/identifier.php');
?>
<! DOCTYPE HTML>
<HTML>
    <head>
        <meta charset="utf-8">
        <title>Nouvelle filière</title>
        <link rel="icon" type="image/x-icon" href="../../assets/images/logo_icon.ico">
        <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/monstyle.css">
    </head>
    <body>
        <?php include("../../includes/menu.php"); ?>
        
        <div class="container">
                       
             <div class="panel panel-primary margetop60">
                <div class="panel-heading">Veuillez saisir les données de la nouvelle filère</div>
                <div class="panel-body">
                    <form method="post" action="insertFiliere.php" class="form">
						
                        <div class="form-group">
                             <label for="niveau">Nom de la filière:</label>
                            <input type="text" name="nomF" 
                                   placeholder="Nom de la filière"
                                   class="form-control"/>
                        </div>
                        
                        <div class="form-group">
                            <label for="niveau">Niveau:</label>
				            <select name="niveau" class="form-control" id="niveau">
                                <option value="q">Qualification</option>
                                <option value="t">Technicien</option>
                                <option value="ts" selected>Technicien Spécialisé</option>
                                <option value="l">Licence</option>
                                <option value="m">Master</option> 
				            </select>
                        </div>
                        
				        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-save"></span>
                            Enregistrer
                        </button> 
                      
					</form>
                </div>
            </div>
            
        </div>      
    </body>
</HTML>