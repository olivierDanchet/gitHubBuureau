<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/style.css">
        <script src="js/script.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
        <?php 
                if(!empty($_COOKIE['fidelite'])){
                    if($_COOKIE['fidelite']==11){
        ?>  <div class="admin_navbar">
                <a href="comptes.php" id="admin_a" class="comptes">Comptes</a>
                <a href="ingredients.php" id="admin_a" class="ingredients">Ingrédients</a>
                <a href="gestionmenus.php" id="admin_a" class="menus">Menus Adm</a>
                <a href="commandes.php" id="admin_a" class="commandes">Commandes</a>
                <a href="bilan.php" id="admin_a" class="bilan">Bilan</a>
                
            </div>
            <?php }} ?>
        

        <nav id='navbar'>
            <ul>
                <li>
                    <i class="bi-list"></i>
                </li>
                <li class="uncollapsed" ><a id="acceuil" href="index.php">Accueil</a></li>
                <li class="uncollapsed" ><a id="menu" href="menu.php">Menu</a></li>
                <li class="uncollapsed" ><a id="contact" href="../connexion.php">Contact</a></li>
                <?php if(!empty($_COOKIE['id'])){ ?>
                <li class="uncollapsed" ><a id="suivi" href="suivi.php">Suivi</a></li>  
                <?php } ?>              
                <!-- GESTION --> 
                
                
                <div class="collapsed" style="width:100%"></div>
                <li><i class="bi-person-circle"></i></li>
                <li><a style="border:none" href="panier.php" class="panier_head"><i class="bi-bag"></i></a></li>
                <?php
                if(!empty($_COOKIE['fidelite'])){
                    $compte_id=$_COOKIE['id'];
                    //recuperer la table panier ou il y a le compte
                    $sql = $db->prepare("SELECT * FROM panier WHERE compte_id=:compte_id");
                    $sql->execute([
                        ':compte_id'=> $compte_id,
                    ]);
                    $results=$sql->fetchAll();
                    $compteur_menus=0;
                    foreach($results as $result):
                        $compteur_menus+=$result['nombre'];
                    endforeach;
                 ?>
                <div class="compteur">
                    <label><?php echo $compteur_menus?></label>
                </div>
           <?php }; ?>
            </ul>
        </nav>

        <nav class="more_menu">
            <ul>
                <li class="collapsed"  ><a id="acceuil" href="index.php">Accueil</a></li>
                <li class="collapsed"  ><a id="menu" href="menu.php">Menu</a></li>
                <li class="collapsed"  ><a id="contact" href="#">Contact</a></li>
                <?php if(!empty($_COOKIE['id'])){ ?>
                <li><a style="font-size: 13px;" href="suivi.php">Suivi</a></li>
                <?php } ?> 
                <!-- GESTION --> 
                
                <?php 
                if(!empty($_COOKIE['fidelite'])){
                    if($_COOKIE['fidelite']==11){
                ?><li><a style="font-size: 13px;" href="comptes.php" id="admin_a" class="comptes">Comptes</a></li>
                <li><a style="font-size: 13px;" href="ingredients.php" id="admin_a" class="ingredients">Ingrédients</a></li>
                <li><a style="font-size: 13px;" href="gestionmenus.php" id="admin_a" class="menus">Menus</a></li>
                <li><a style="font-size: 13px;" href="commandes.php" id="admin_a" class="commandes">Commandes</a></li>
                <li><a style="font-size: 13px;" href="bilan.php" id="admin_a" class="bilan">bilan</a></li>
                
                
                <?php }} ?>
                <div class="collapsed" style="width:100%"></div>
            </ul>
        </nav>

        <!-- MORE PROFIL -->
        <form method="post">
            <nav class="more_profil">

        <?php if(!empty($_COOKIE['nom'])){
            ?>
            <div class="zero">
            <div class="un">
                <div class="deux"><i class="bi-person-fill" style="margin: 10px 20px 0 10px; font-size: 50px; color: #fff; display: flex;"></i></div>
                <div class="deux">
                    <div class="trois">
                        <div class="quatre" style="text-transform: uppercase"><?php echo $_COOKIE['nom'];?></div>
                        <div class="quatre"><?php echo $_COOKIE['prenom'];?></div>
                    </div>
                    <div class="trois"><p>Points :  </p><?php echo $_COOKIE['fidelite']?></div>
                </div>
            </div>
            <div class="un">
                <a href="logout.php" class="deconnexion"><p>Déconnexion</p></a>
                <a href="settings.php" class="settings"><i class="bi-gear-wide-connected"></i></a>
            </div>
        </div>
            <?php

        }else{?>
        <div class="login">
        <p class="link_register"style="color:#fff;margin-bottom:10px">Veuillez vous connecter. <a href="connexion.php" style="color: #2ECC40;">Se connecter</a></p>
        </div>
<?php

        }
        
        ?>
        </nav>
        </form>
        

        <img style="display:block; margin:auto;width:450px" src="../media/logo.jpeg">

    </head>
    <body>

    </body>
    
</html>