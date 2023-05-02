<!DOCTYPE html>
<html>
<?php
// Connection à la base de données
include 'include/database.php';
global $db;

// Récupération de tous les menus de la table menu_test
$stmt = $db->prepare("SELECT * FROM menu_test");
$stmt->execute();
$menus = $stmt->fetchAll();

// Ajouter au panier
if(isset($_POST['ajouter'])){
    if(!empty($_COOKIE['fidelite'])){
        $menu_id = $_POST['ajouter'];
        $nombre = $_POST['quantite_'.$menu_id];
        $compte_id = $_COOKIE['id'];

        // Récupération de la table panier où il y a le compte
        $sql = $db->prepare("SELECT * FROM panier WHERE compte_id=:compte_id");
        $sql->execute([
            ':compte_id'=> $compte_id,
        ]);
        $results=$sql->fetchAll();

        // Si le menu est déjà présent dans la table
        foreach($results as $result){
            if($result['menu_id']==$menu_id){
                $panier_id=$result['id'];
                $nombre_old=$result['nombre'];
            }
        }

        // Update de la ligne
        if(!empty($panier_id)){
            $sql=$db->prepare("UPDATE `panier` SET `nombre`=:nombre WHERE id=:panier_id");
            $sql->execute([
                ':panier_id'=>$panier_id,
                ':nombre'=>$nombre_old+$nombre,
            ]); 
        } else { // Sinon insertion
            $sql=$db->prepare("INSERT INTO `panier`(`compte_id`, `menu_id`, `nombre`) VALUES (:compte_id,:menu_id,:nombre)");
            $sql->execute([
                ':compte_id'=>$compte_id,
                ':menu_id'=> $menu_id,
                ':nombre'=>$nombre,
            ]);
        }     

        // Actualisation de la page
        header('Location:menu.php');
    } else {
        ?>
        <script>
            alert("Veuillez vous connecter.");
            window.location.href = "connexion.php";
        </script>
        <?php
    }
}
?>

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

    <?php foreach($menus as $menu):
        $stmt=$db->prepare("SELECT * FROM `menu_ingredient` WHERE `menu_id`=:menu_id");
        $stmt -> execute([':menu_id' => $menu['menu_id']]);
        $menu_ingredients=$stmt->fetchAll();

        foreach($menu_ingredients as $menu_ing){
        $stmt=$db->prepare("SELECT * FROM `ingredients` WHERE `id`=:ingredient_id");
        $stmt->execute([':ingredient_id'=>$menu_ing['ingredient_id']]);
        $ingredients=$stmt->fetch();
        
        if($menu_ing['stock']<=$ingredients['stock_ingredient']){
            $disponible = True;
        }else{
            $disponible = False;
            break;
        };
        $min=$ingredients['stock_ingredient']/$menu_ing['stock'];
        
        if(!empty($max)){
            if($max>=$min){
                $max=$min;
            }
        }else{
            $max=$min;
        }
        
        }
        

    if($disponible==True){
    ?>
    

    <form action="" method="post">
        <div class="menu-client-box">
            <div class="menu-client">
                <img src="media/menu_default.jpg" alt="" style="margin:5px;width:65px;border-radius:10px">
                <p><?php echo $menu['name']?></p>
                <p style="font-size:20px"><?php echo $menu['price'] ?> €</p>

                <!-- Boutons quantité -->
                <div class="quantite">
                    <button type="button" onclick="increasequantite(<?php echo $menu['menu_id']?>)"><i class="bi bi-caret-up"></i></button>
                    <input type="number" id="quantite_<?php echo $menu['menu_id']?>" name="quantite_<?php echo $menu['menu_id']?>" value="1" max="<?php echo $max?>">
                    <button type="button" onclick="decreasequantite(<?php echo $menu['menu_id']?>)"><i class="bi bi-caret-down"></i></button>

                    <script>
                        function increasequantite(menu_id) {
                            var quantiteinput = document.getElementById("quantite_" + menu_id);
                            var quantiteactuelle = parseInt(quantiteinput.value);
                            quantiteinput.value = quantiteactuelle + 1;
                        }

                        function decreasequantite(menu_id) {
                            var quantiteinput = document.getElementById("quantite_" + menu_id);
                            var quantiteactuelle = parseInt(quantiteinput.value);
                            if (quantiteactuelle > 1) {
                                quantiteinput.value = quantiteactuelle - 1;
                            }
                        }
                    </script>
                </div>
                <!-- FIN Boutons quantité -->
                <button style="width:150px;margin:auto 10px auto -10px" type="submit" name="ajouter" id="submit" class="menu_update" value="<?php echo $menu['menu_id']?>"> Ajouter au panier</button>

            </div>
            <div style="display:flex;flex-direction:row;">
                <div class="description" style="width:460px;margin:-2px 10px 0 0;height:100px;display:flex;flex-direction:row;">
                    <p style="width: 100%; overflow-wrap: break-word;font-size:16px;" class="desc"><?php echo $menu['description'] ?></p>
                </div>
                <div class="ingredient-client-menu">
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
        </div>
    </form>

<?php }; endforeach;?>

        <?php include 'include/footer.php' ?>
    </body>
</html>