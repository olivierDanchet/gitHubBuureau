<!DOCTYPE html>
<?php
// Connexion a la base de données
include 'include/database.php';
global $db;
if(!empty($_COOKIE['id'])){
$compte_id=$_COOKIE['id'];

//recupération du compte
$sql=$db->prepare("SELECT * FROM table_test_bokit_master WHERE id=:compte_id");
$sql->execute([
    ':compte_id'=>$compte_id
]);
$compte=$sql->fetch();
$fidelite=$compte['fidelite'];

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


//recupération du panier du compte
$sql=$db->prepare("SELECT * FROM panier WHERE compte_id=:compte_id");
$sql->execute([
    ':compte_id'=>$compte_id
]);
$paniers=$sql->fetchAll();

    //calcul du prix total
    $prix_total=0;
    foreach($paniers as $panier):
        $id_menu=$panier['menu_id'];
        $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
        $sql->execute([
            ':id_menu'=>$id_menu,
        ]);
        $menus=$sql->fetchAll();
        foreach($menus as $menu):
            $prix_total+=$menu['price']*(1-($compte['fidelite']/100))*$panier['nombre'];
        endforeach;
    endforeach;

//payement transfert de la base panier a commandes
if(isset($_POST['payement'])){
    $nbr_cmd=$compte['nbr_cmd']+$compteur_menus;
    if($fidelite!=11){
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
    }}

    $sql=$db->prepare("UPDATE `table_test_bokit_master` SET `nbr_cmd`=:nbr_cmd,`fidelite`=:fidelite WHERE id=:compte_id");
    $sql->execute([
        ':compte_id'=>$compte_id,
        ':nbr_cmd'=>$nbr_cmd,
        ':fidelite'=>$fidelite
    ]); 



    foreach($paniers as $panier):
        $id_menu=$panier['menu_id'];
        $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
        $sql->execute([
            ':id_menu'=>$id_menu,
        ]);
        $menus=$sql->fetchAll();
        $depense=0;
        //recupération des ingrédients pour calculer le prix des dépenses
        foreach($menus as $menu):
            $depense+=$menu['price']/1.7;
        endforeach;
            
        foreach($menus as $menu):
            $sql=$db->prepare("INSERT INTO `commandes`(`compte_id`, `menu_id`, `nombre`,`revenue`,`depense`,`benefice`) VALUES (:compte_id,:menu_id,:nombre,:revenue,:depense,:benefice);DELETE FROM `panier` WHERE compte_id=:compte_id");
            $sql->execute([
                ':compte_id'=>$panier['compte_id'],
                ':menu_id'=> $menu['menu_id'],
                ':nombre'=>$panier['nombre'],
                ':revenue' => $prix_total, 
                ':depense' =>  $depense,
                ':benefice' =>  $prix_total-$depense, 
            ]);

            $sql=$db->prepare("SELECT * FROM `menu_ingredient` WHERE `menu_id`=:menu_id");
            $sql->execute([':menu_id'=>$menu['menu_id']]);
            $id_ingredients=$sql->fetchAll();
            
            foreach($id_ingredients as $id_ingredient){
                $sql=$db->prepare("SELECT * FROM `ingredients` WHERE `id`=:ingredient_id");
                $sql->execute([':ingredient_id'=>$id_ingredient['ingredient_id']]);
                $ingredients=$sql->fetchAll();

                foreach($ingredients as $ingredient){
                    $nouveau_stock=$ingredient['stock_ingredient']-$id_ingredient['stock']*$panier['nombre'];
                    $sql=$db->prepare("UPDATE `ingredients` SET `stock_ingredient`=:nouveau_stock WHERE id=:ingredient_id");
                    $sql->execute([
                        ':ingredient_id' => $ingredient['id'],
                        ':nouveau_stock' => $nouveau_stock,
                    ]); 
                }
            }


        endforeach;
    endforeach;
    ?>
        <script>
            alert("Votre commande a bien été envoyer !");
            window.location.href = "menu.php";
        </script>
        <?php

    //Réduire le stock des ingrédients



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
        <div style="width:650px;height:500px;margin:auto">
            <table class="facture" style="border:2px">
                <tr>
                    <th>Réference</th>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Prix Totale</th>
                    <th>Réduction</th>
                </tr>
                <?php foreach($paniers as $panier):
                $id_menu=$panier['menu_id'];
                $sql=$db->prepare("SELECT * FROM menu_test WHERE menu_id=:id_menu");
                $sql->execute([
                    ':id_menu'=>$id_menu,
                ]);
                $menus=$sql->fetchAll();
                    
                foreach($menus as $menu):?>
                <tr>
                    <td><?php echo $menu['menu_id']?></td>
                    <td><?php echo $menu['name']?></td>
                    <td>x<?php echo $panier['nombre']?></td>
                    <td><?php echo $menu['price']?>€</td>
                    <td><?php echo $menu['price']*$panier['nombre']?>€</td>
                    <td>-<?php echo $compte['fidelite']?>%</td>
                </tr>
                <?php endforeach;endforeach;?>
            </table>
            <div class="panier-commande" style="margin-top: 20px;">
            <div class="panier-commades-box">
                <label>Nombres d'articles :</label>
                <label style="font-size:25px"><?php echo $compteur_menus?></label>
            </div>
            <div class="panier-commades-box">
                <label>Somme :</label>
                <label style="font-size:25px"><?php echo $prix_total?>€</label>
            </div>
            <form action="" method="post">
            <button name="payement" href="payement.php" class="panier-commades-box paye" style=" border:none; font-size: 18px;display:flex;flex-direction:row">
                Payer<i style="margin:0 0 0 5px"class="bi bi-check"></i>
                </button>
                </form>
        </div>
        </div>

        <?php include 'include/footer.php'?>
    </body>
</html>