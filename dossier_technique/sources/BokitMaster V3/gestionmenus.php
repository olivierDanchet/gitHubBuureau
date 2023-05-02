
<?php

// Connection a la base de données
include 'include/database.php';
global $db;

// Vérifié si "form" est envoyé
if (isset($_POST['submit'])) {
  // Prend le nom du menu et les ingrédients sélectionner
  $menu_name = $_POST['menu_name'];
  $ingredients = $_POST['ingredients'];
  $description =$_POST['description-ajout'];
  
  // Inserer le menu dans la base menu_test
  $stmt = $db->prepare("INSERT INTO menu_test (name,description) VALUES (:name,:description)");
  $stmt->execute(array(':name' => $menu_name,':description'=>$description));
  
  // Récupération de l'ID du derniere menu insérer
  $menu_id = $db->lastInsertId();
  
  // Insérer chaque ingrédient dans la table menu_ingredient
foreach ($_POST['ingredients'] as $ingredient) {
    if(!empty($ingredient['id'])){
    $ingredient_id = $ingredient['id'];
    $stock_necessaire = $ingredient['stock'];
    $stmt = $db->prepare("INSERT INTO menu_ingredient (menu_id, ingredient_id, stock) VALUES (:menu_id, :ingredient_id, :stock)");
    $stmt->execute(array(':menu_id' => $menu_id, ':ingredient_id' => $ingredient_id, ':stock' => $stock_necessaire));
}
}

// Calculer le prix total de chaque ingrédient
$total_price = 0;
foreach ($ingredients as $ingredient) {
  $stmt = $db->prepare("SELECT price FROM ingredients WHERE id = :id");
  $stmt->execute(array(':id' => $ingredient['id']));
  $ingredient_price = $stmt->fetchColumn();
  $total_price += $ingredient_price*$ingredient['stock'];
}

// Calculer le prix final du menu avec une marge de 70%
$final_price = $total_price * 1.7;

// Mise a jour du prix du menu dans la table
$stmt = $db->prepare("UPDATE menu_test SET price = :price WHERE menu_id = :id");
$stmt->execute(array(':price' => $final_price, ':id' => $menu_id));

header('location:gestionmenus.php');
}
  
// Selection de tout les ingrédient de la table ingredient
$stmt = $db->prepare("SELECT * FROM ingredients");
$stmt->execute();
$ingredients = $stmt->fetchAll();

// Selection de tout les menus de la table menu_test
$stmt = $db->prepare("SELECT * FROM menu_test");
$stmt->execute();
$menus = $stmt->fetchAll();

    //SUPPRIMER MENU
    if(isset($_POST['menu_delete'])){
        $menu_delete = $_POST['menu_delete'];
        $sql = $db -> prepare("DELETE FROM `menu_test` WHERE `menu_test`.`menu_id` = :menu_delete; DELETE FROM `menu_ingredient` WHERE `menu_ingredient`.`menu_id` = :menu_delete");
        $sql->execute([
            'menu_delete' => $menu_delete,
        ]);
    header('Location:gestionmenus.php');
    }

    //MODIFIER MENU 

    if(isset($_POST['menu_update'])){
        $menu_id =$_POST['menu_update'];
        $menu_name=$_POST['menu_name'];
        $menu_price=$_POST['menu_prix'];
        $menu_stock=$_POST['menu_stock'];
        $ingredients_update = $_POST['ingredients']; 
        $description=$_POST['description-update'];
        
           
        $sql = $db->prepare("UPDATE `menu_test` SET `name`=:menu_name,`price`=:menu_price,`stock`=:menu_stock,`description`=:description WHERE `menu_id`=:menu_update");
        $sql->execute([
            ':menu_name'=> $menu_name,
            ':menu_price'=> $menu_price,
            ':menu_stock'=> $menu_stock,
            ':menu_update' => $menu_id, 
            ':description' => $description
    ]);
    // Supprimer tous les ingrédients liés à ce menu
    $stmt = $db->prepare("DELETE FROM `menu_ingredient` WHERE `menu_id` = :menu_id");
    $stmt->execute([':menu_id' => $menu_id]);

    // Ajouter les nouveaux ingrédients sélectionnés pour ce menu
    foreach ($ingredients_update as $ingredient_id) {
        if(!empty($ingredient_id['id'])){
        $stock_necessaire = $ingredient_id['stock'];
        $stmt = $db->prepare("INSERT INTO `menu_ingredient` (`menu_id`, `ingredient_id`,`stock`) VALUES (:menu_id, :ingredient_id, :stock)");
        $stmt->execute([':menu_id' => $menu_id, ':ingredient_id' => $ingredient_id['id'], ':stock' => $stock_necessaire]);
    }
    }
    //Supprimer tout les ingredients menu qui ont une valeur ingredient_id == NULL
    $stmt = $db->prepare("DELETE FROM `menu_ingredient` WHERE `ingredient_id` IS NULL");
    $stmt->execute();
    

    header('Location:gestionmenus.php');
    }	




?>

<!DOCTYPE html>
<html>
    <head>
	</div>


        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>BokitMaster</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <?php include 'include/header.php';?>

    </head>
    <body>
        <!-- CREER MENU OVERLAY -->
        <form action="" method="post">
            <div class="overlay responsive">
                <div class="img-menu">
                    <img src="media/menu_default.jpg" class="img-menu_default">
                    <div class="input-box" style="border-bottom: 2px solid #D1A085">
                        <span class="icon"><i style="color:#D1A085" class="bi bi-tag-fill"></i></span>
                        <input style="color:#D1A085" type="text" name="menu_name" id="menu_name" autocomplete="off" required>
                        <label style="color:#D1A085">Nom du menu</label>
                    </div>
                </div>
                <div class="box-bas" style="display:flex;flex-direction:row">
                    <div class="param-menu">
                    <?php foreach ($ingredients as $ingredient): ?>
                        <div class="checkbox-label">
                            <input type="checkbox" id="<?php echo $ingredient['id']; ?>" name="ingredients[<?php echo $ingredient['id']; ?>][id]" value="<?php echo $ingredient['id']; ?>">
                            <label for="<?php echo $ingredient['id']; ?>"><?php echo $ingredient['name']; ?></label>
                            <input type="float" name="ingredients[<?php echo $ingredient['id']; ?>][stock]" value="0" style="margin:0 5px 0 30px; width:30px; border:none;background:transparent;outline:none;color:#D1A085"></input>
                        </div>
                    <?php endforeach; ?>

                    </div>
                    <div class="description-ajout">
                        <p>Description</p>
                        <textarea class="desc-ajout"style="width: 100%; overflow-wrap: break-word;" name="description-ajout"></textarea>
                    </div>
                </div>
                

                <button type="submit" name="submit" id="submit"class="menu_add"> Ajouter</button>
            </form>
        </div>
        <!-- FIN CREER MENU OVERLAY FIN -->
        <?php foreach($menus as $menu):?>
            <form action="" method="post">
                <div class="menu-update responsive">
                    <div class="ligne-img">
                        <img src="media/menu_default.jpg" alt="" style="margin:10px 0 0 10px;width:65px;border-radius:10px">
                        <div style="height:2px;width:auto;background:#2F2B2A;margin:5px 0 -20px 0;"></div>
                    </div>
                    <div class="box_param-ingredients">
                        <div class="box_param">
                            <!-- NOM -->
                            <div class="box_nom-menu">
                                <div class="input-box" style="border-bottom: 2px solid #2F2B2A">
                                    <span class="icon"><i style="color:#2F2B2A" class="bi bi-tag-fill"></i></span>
                                    <input style="color:#2F2B2A" type="text" name="menu_name" id="menu_name" autocomplete="off" value="<?php echo $menu['name']?>" required>
                                </div>
                            </div>
                            <!-- STOCK -->
                            <?php 
                            //calcul Max
                                $stmt=$db->prepare("SELECT * FROM `menu_ingredient` WHERE `menu_id`=:menu_id");
                                $stmt -> execute([':menu_id' => $menu['menu_id']]);
                                $menu_ingredients=$stmt->fetchAll();
                                unset($max);
                                foreach($menu_ingredients as $menu_ing){
                                $stmt=$db->prepare("SELECT * FROM `ingredients` WHERE `id`=:ingredient_id");
                                $stmt->execute([':ingredient_id'=>$menu_ing['ingredient_id']]);
                                $ingredients2=$stmt->fetch();
                                $min=$ingredients2['stock_ingredient']/$menu_ing['stock'];
                                if(!empty($max)){
                                    if($max>=$min){
                                        $max=$min;
                                        $max=floor($max);
                                    }
                                }else{
                                    $max=$min;
                                    $max=floor($max);
                                }
                                
                                }
                                
                                
                            ?>


                            <div class="box_stock-menu">
                            <div class="input-box" style="border-bottom: 2px solid #2F2B2A">
                                    <span class="icon"><i style="color:#2F2B2A" class="bi bi-archive-fill"></i></span>
                                    <input style="color:#2F2B2A" type="text" name="menu_stock" id="menu_stock" autocomplete="off" value="<?php if(!empty($max)){ echo $max; }else{ echo 0; } ?>" required>
                                </div>
                            </div>
                            <?php  ?>
                            <!-- PRIX -->
                            <div class="box_prix-menu">
                                <div class="input-box" style="border-bottom: 2px solid #2F2B2A">
                                    <span class="icon"><i style="color:#2F2B2A" class="bi bi-cash-coin"></i></span>
                                    <input style="color:#2F2B2A" type="text" name="menu_prix" id="menu_prix" autocomplete="off" value="<?php echo $menu['price']?>" required>
                                </div>
                            </div>
                            <button style="margin:55px 10px 0 10px" type="submit" name="menu_delete" id="<?php echo $menu['menu_id']?>" class="compte_delete" value="<?php echo $menu['menu_id']?>"  title="Supprimer"><i class="bi bi-x-lg"></i></button>
                            <button style="margin-left: 50px" type="submit" name="menu_update" id="submit" class="menu_update" value="<?php echo $menu['menu_id']?>"> Enregistrer</button>
                        </div>
                        <div class="description">
                            <p>Description</p>
                            <textarea class="desc"style="width: 100%; overflow-wrap: break-word;"name="description-update"><?php echo $menu['description']?></textarea>

                        </div>
                        <div class="box_ingredients">
                            <div class="param-menu" style="overflow:scroll;margin:10px 0 0 30px;height:200px">
                            <?php
                            //Selection de tout les ingredients de la table menu_ingredient
                                $stmt = $db->prepare("SELECT * FROM menu_ingredient WHERE menu_id=:menu_id");
                                $stmt->execute(array(
                                    ':menu_id'=>$menu['menu_id']
                                ));
                                $menu_ingredient = $stmt->fetchAll();
                            
                            ?>
                            <?php foreach ($ingredients as $ingredient): ?>
                                    <div class="checkbox-label">
                                        <?php
                                            // Vérifie si l'ingrédient est déjà présent dans le menu
                                            $checked = false;
                                            foreach ($menu_ingredient as $menuIngredient) {
                                                if ($menuIngredient['ingredient_id'] == $ingredient['id']) {
                                                    $checked = true;
                                                    break;
                                                }else{
                                                    $checked = false;
                                                };
                                            }
                                        ?>
                                        <input type="checkbox" id="<?php echo $ingredient['id']; ?>" name="ingredients[<?php echo $ingredient['id']; ?>][id]" value="<?php echo $ingredient['id']; ?>" <?php echo ($checked) ? 'checked' : ''; ?>>
                                        <label for="<?php echo $ingredient['id']; ?>"><?php echo $ingredient['name']; ?></label>
                                        <input type="float" name="ingredients[<?php echo $ingredient['id']; ?>][stock]" value="<?php echo ($checked) ? $menuIngredient['stock'] : '0'; ?>" style="margin:0 5px 0 30px; width:30px; border:none;background:transparent;outline:none;" <?php echo ($checked) ? 'checked' : ''; ?>></input>
                                    </div>
                            <?php endforeach; ?>

                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        <?php endforeach;?>
        <div style="height:350px;background:transparent;display:flex"></div>
        <?php include 'include/footer.php'?>
    </body>
</html>