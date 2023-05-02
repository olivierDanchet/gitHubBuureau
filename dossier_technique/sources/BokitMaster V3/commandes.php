<!DOCTYPE html>
<?php
// Connection à la base de données
include 'include/database.php';
global $db;

//recuperer la table commandes
$sql = $db->prepare("SELECT * FROM commandes ORDER BY date ASC;");
$sql->execute([]);
$commandes=$sql->fetchAll();

//changer d'etape 2
if(isset($_POST['bouton-cuisine'])){
    $commande_id=$_POST['bouton-cuisine'];
    $etape=2;
    $sql=$db->prepare("UPDATE `commandes` SET `etape`=:etape WHERE id=:commande_id");
    $sql->execute([
        ':commande_id'=>$commande_id,
        ':etape'=>$etape,
    ]); 

}

//Transfert dans Bilan
if(isset($_POST['bouton-fini'])){
    $commande_id=$_POST['bouton-fini'];

    //recuperer la commandes a transferer
    $sql = $db->prepare("SELECT * FROM commandes WHERE id=:commande_id");
    $sql->execute([':commande_id'=>$commande_id]);
    $commande=$sql->fetch();
    $article= $commande['menu_id'];
    $quantite= $commande['nombre'];
    $revenue= $commande['revenue'];
    $depense= $commande['depense'];
    $benefice= $commande['benefice'];
    $compte_id=$commande['compte_id'];
    
    $etape=3;
    $sql=$db->prepare("INSERT INTO `bilan`(`article`, `quantite`, `revenue`, `depense`, `benefice`,`compte_id`,`etape`) 
    VALUES (:article,:quantite,:revenue,:depense,:benefice,:compte_id,:etape);
    DELETE FROM `commandes` WHERE id=:commande_id");
    $sql->execute([
        ':article'=>$article,
        ':quantite'=>$quantite,
        ':revenue'=>$revenue,
        ':depense'=>$depense,
        ':benefice'=>$benefice,
        ':compte_id'=>$compte_id,
        ':etape'=>$etape,
        ':commande_id'=>$commande_id
    ]); 
    header('location:commandes.php');
}
?>
<html>
    <head>
    <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <?php include 'include/header.php' ?>
    </head>
    <body>
            <!-- //foreach ici -->
            <div style="display:flex;flex-direction:row;justify-content:center">
            <div style="display:flex;flex-direction:column">
            <?php
            //recuperer la table commande ou il y a le compte
            $sql = $db->prepare("SELECT * FROM commandes");
            $sql->execute([]);
            $commandes=$sql->fetchAll();

            foreach($commandes as $commande):
            
            if($commande['etape']==1){
            $id_menu=$commande['menu_id'];
            $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
            $sql->execute([
                ':id_menu'=>$id_menu,
            ]);
            $menus=$sql->fetchAll();

            foreach($menus as $menu):
                $compte_id=$commande['compte_id'];
                $sql=$db->prepare("SELECT * FROM table_test_bokit_master WHERE id=:compte_id");
                $sql->execute([
                    ':compte_id'=> $compte_id,
                ]);
                $compte=$sql->fetch();
            ?>

            <div class="ligne-commande">
            <div style="margin: 0 0 10px 0;text-align:center;">
                <label style="border-bottom:2px solid black;font-size:20px;font-weight:800"><?php echo $compte['prenom'] ?></label>
            </div>
            <div style="display:flex;flex-direction:row">
            <div>
                <div style="margin:auto;text-align:center;border-radius:5px;background:#2F2B2A;width:30px;color:white">
                    <p style="margin:auto;font-size:20px,">x<?php echo $commande['nombre']?></p>
                </div>
                
                <label style="border-bottom:1px solid black;font-size:17px; margin:0 0 10px 0"><?php echo $menu['name']?></label>
                <div style='margin:10px 0 0 0'class="ingredient-client-menu">
                    <?php
                        $menu_id = $menu['menu_id'];
                        $sql = $db->prepare('SELECT ingredient_id FROM menu_ingredient WHERE menu_id=:menu_id');
                        $sql->execute(array(':menu_id'=>$menu_id,));
                        $ingredient_menu = $sql->fetchAll();

                        foreach($ingredient_menu as $ingredient):
                            $sql = $db->prepare('SELECT name FROM ingredients WHERE id=:id');
                            $sql->execute([':id'=>$ingredient['ingredient_id']]);
                            $result = $sql->fetch();
                            echo '- ', $result['name'],'<br>';
                        endforeach;
                    ?>
                    
                </div>
                
            </div>
                <form action="" method="post">
                    <button type="submit" value="<?php echo $commande['id']?>" name="bouton-cuisine" class="bouton-cuisine" style="width:50px; height:140px;margin:0px 2px 10px 10px;font-size:20px"><i class="bi bi-check"></i></button>

                </form>
            </div>

            </div>
            
            <?php endforeach;} endforeach; ?>
            </div>
            <div style="display:flex;flex-direction:column">
            <?php 
            foreach($commandes as $commande):
            if($commande['etape']==2){
                $id_menu=$commande['menu_id'];
                $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
                $sql->execute([
                    ':id_menu'=>$id_menu,
                ]);
                $menus=$sql->fetchAll();
    
                foreach($menus as $menu):
                    $compte_id=$commande['compte_id'];
                    $sql=$db->prepare("SELECT * FROM table_test_bokit_master WHERE id=:compte_id");
                    $sql->execute([
                        ':compte_id'=> $compte_id,
                    ]);
                    $compte=$sql->fetch();
                ?>
                <div style="margin-left:15px" class="ligne-commande">
                <div style="margin: 0 0 10px 0;text-align:center;">
                    <label style="border-bottom:2px solid black;font-size:20px;font-weight:800"><?php echo $compte['prenom'] ?></label>
                </div>
                <div style="display:flex;flex-direction:row">
                <div>
                    <div style="margin:auto;text-align:center;border-radius:5px;background:#2F2B2A;width:30px;color:white">
                        <p style="margin:auto;font-size:20px,">x<?php echo $commande['nombre']?></p>
                    </div>
                    
                    <label style="border-bottom:1px solid black;font-size:17px; margin:0 0 10px 0"><?php echo $menu['name']?></label>
                    <div style='margin:10px 0 0 0'class="ingredient-client-menu">
                        <?php
                            $menu_id = $menu['menu_id'];
                            $sql = $db->prepare('SELECT ingredient_id FROM menu_ingredient WHERE menu_id=:menu_id');
                            $sql->execute(array(':menu_id'=>$menu_id,));
                            $ingredient_menu = $sql->fetchAll();
    
                            foreach($ingredient_menu as $ingredient):
                                $sql = $db->prepare('SELECT name FROM ingredients WHERE id=:id');
                                $sql->execute([':id'=>$ingredient['ingredient_id']]);
                                $result = $sql->fetch();
                                echo '- ', $result['name'],'<br>';
                            endforeach;
                        ?>
                        
                    </div>
                    
                </div>
                    <form action="" method="post">
                        <button type="submit" value="<?php echo $commande['id']?>" name="bouton-fini" class="bouton-cuisine" style="width:50px; height:140px;margin:0px 2px 10px 10px;font-size:20px"><i class="bi bi-x"></i></button>
                        <div style="height:20px;background:#2ECC40;border-radius:5px"> <p>Accepté</p> </div>
                    </form>
                </div>
    
                </div>

            <?php endforeach; };  endforeach;?>
            </div>
            </div>
           <!-- foreach fin ici -->

            <?php include 'include/footer.php'?>        
    </body>
</html>