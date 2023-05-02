<!DOCTYPE html>

<?php
    // Connection a la base de données
        include 'include/database.php';
        global $db;
    if(!empty($_COOKIE['id'])){
        //Récupération de la table commande
    $compte_id=$_COOKIE['id'];
    $sql=$db->prepare("SELECT * FROM commandes WHERE compte_id=:compte_id");
    $sql->execute([
        ':compte_id'=>$compte_id
    ]);
    $commandes=$sql->fetchAll();

    $sql=$db->prepare("SELECT * FROM bilan WHERE compte_id=:compte_id ORDER BY date DESC;");
    $sql->execute([
        ':compte_id'=>$compte_id
    ]);
    $bilans=$sql->fetchAll();

    if(isset($_POST['bouton-annuler'])){
        //recupération du bilan du compte
        $sql=$db->prepare("SELECT * FROM commandes WHERE compte_id=:compte_id");
        $sql->execute([
            ':compte_id'=>$compte_id
        ]);
        $commandes=$sql->fetchAll();

        foreach($commandes as $commande){
        $id_menu=$commande['menu_id'];
        $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
        $sql->execute([
            ':id_menu'=>$id_menu,
        ]);
        $menus=$sql->fetchAll();

        foreach($menus as $menu){
        $sql=$db->prepare("SELECT * FROM `menu_ingredient` WHERE `menu_id`=:menu_id");
            $sql->execute([':menu_id'=>$menu['menu_id']]);
            $id_ingredients=$sql->fetchAll();
            
            foreach($id_ingredients as $id_ingredient){
                $sql=$db->prepare("SELECT * FROM `ingredients` WHERE `id`=:ingredient_id");
                $sql->execute([':ingredient_id'=>$id_ingredient['ingredient_id']]);
                $ingredients=$sql->fetchAll();

                foreach($ingredients as $ingredient){
                    $nouveau_stock=$ingredient['stock_ingredient']+$id_ingredient['stock']*$commande['nombre'];
                    $sql=$db->prepare("UPDATE `ingredients` SET `stock_ingredient`=:nouveau_stock WHERE id=:ingredient_id");
                    $sql->execute([
                        ':ingredient_id' => $ingredient['id'],
                        ':nouveau_stock' => $nouveau_stock,
                    ]); 
                }
            }
        }
    //récupération et diminution de la fidelite du compte
    if($_COOKIE['fidelite']!=11){
        $sql=$db->prepare("SELECT * FROM table_test_bokit_master WHERE id=$compte_id");
        $sql->execute();
        $compte_fidelite=$sql->fetch();

        $nbr_cmd=$compte_fidelite['nbr_cmd']-$commande['nombre'];
        $fidelite=$compte_fidelite['fidelite'];

        if($nbr_cmd>=50){
            $fidelite = 50;
        }elseif($nbr_cmd>=40){
            $fidelite = 40;
        }elseif($nbr_cmd>=30){
            $fidelite = 30;
        }elseif($nbr_cmd>=20){
            $fidelite = 20;
        }elseif($nbr_cmd>=10){
            $fidelite = 10;
        }elseif($nbr_cmd<10){
            $fidelite = 1;
        }
        $fidelite-=1;

        $sql=$db->prepare("UPDATE table_test_bokit_master SET `fidelite`=$fidelite,`nbr_cmd`=$nbr_cmd WHERE id=$compte_id");
        $sql->execute();
    }

    }
    $sql=$db->prepare("DELETE FROM `commandes` WHERE id=:commande_id");
        $sql->execute([
            ':commande_id'=> $_POST['bouton-annuler']
        ]); 

        header('location:suivi.php');
}
    }else{
        header('location:index.php');
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

    <?php foreach($commandes as $commande){ 
            //Récupération de la menu_test
            $sql=$db->prepare("SELECT name FROM menu_test WHERE menu_id=:menu_id");
            $sql->execute([
                ':menu_id'=>$commande['menu_id']
            ]);
            $menu=$sql->fetch()
        ?>
        <div class="box_suivi">
            <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px"><?php echo $menu['name']?></label>
            <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px"><?php echo $commande['date']?></label>
            <progress id="progressBar" class="progressBar" value="<?php echo $commande['etape']?>" max="3"></progress>
            <?php if($commande['etape']==1){ ?>

            <form action="" method="post">
            <button type="submit" name="bouton-annuler" id="submit" class="menu_add" value="<?php echo $commande['id'] ?>" style="margin: 5px auto 0 auto;width:200px"> Annuler la commande</button>
            </form>

            <?php }elseif($commande['etape']==2){
                ?><label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px">Votre commande à été prise en compte.</label><?php
            }elseif($commande['etape']==3){
                ?><label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px">Votre commande est prête.</label><?php
            }; ?>
        </div>
    <?php }; ?>
    <?php foreach($bilans as $bilan){ 
            //Récupération de la menu_test
            $sql=$db->prepare("SELECT name FROM menu_test WHERE menu_id=:menu_id");
            $sql->execute([
                ':menu_id'=>$bilan['article']
            ]);
            $menu=$sql->fetch()
        ?>
        <div class="box_suivi">
            <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px"><?php echo $menu['name']?></label>
            <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px"><?php echo $bilan['date']?></label>
            <progress id="progressBar" class="progressBar" value="3" max="3"></progress>
            <?php if(!empty($bilan && $bilan['etape']==3)){ ?>
                <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px">Votre commande est prête.</label>
            <?php }elseif(!empty($bilan) && $bilan['etape']==4){?>
                <label style="color:#D1A085;display:flex;justify-content:center;padding:10px 0 0 0;font-size:20px">Vous avez récupérer cette commande</label>
                <?php } ?>
        </div>
    <?php }; ?>

    <div style="height:350px;background:transparent;display:flex"></div>
    <?php include 'include/footer.php' ?>
    </body>
</html>