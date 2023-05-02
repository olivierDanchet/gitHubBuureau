
<?php 
    include 'include/database.php';
    global $db;

    // Selection de tout les ingrédient de la table ingredient
	$stmt = $db->prepare("SELECT * FROM ingredients");
	$stmt->execute();
	$ingredients = $stmt->fetchAll();

    // AJOUTER INGREDIENTS


    if (isset($_POST['ingredient_send'])) {

        if (!empty($nom_add) && !empty($prix_add)) {
            echo "formulaire send";
            echo $_POST['nom_add'];}

    

            $nom_add = $_POST['nom_add'];
            $prix_add = $_POST['prix_add'];
            $stock_add = $_POST['stock_add'];
            
            $sql = $db->prepare("INSERT INTO ingredients(name,price,stock_ingredient) VALUES(:nom_add,:prix_add,:stock_add)");
            $sql->execute([
                ':nom_add'=> $nom_add,
                ':prix_add'=> $prix_add,
                ':stock_add'=> $stock_add,
            ]);
            header('Location:ingredients.php');
        
    };

    //MODIFIER INGREDIENT 

    if(isset($_POST['ingredient_update'])){
        $nom_update = $_POST['nom_update'];
        $prix_update = $_POST['prix_update'];
        $stock_update = $_POST['stock_update'];
        $ingredient_update=$_POST['ingredient_update'];
            
        $sql = $db->prepare("UPDATE `ingredients` SET `name`=:nom_update,`price`=:prix_update,`stock_ingredient`=:stock_update WHERE `id`=:ingredient_update");
        $sql->execute([
            ':nom_update'=> $nom_update,
            ':prix_update'=> $prix_update,
            ':stock_update'=> $stock_update,
            ':ingredient_update' => $ingredient_update,
    ]);
    header('Location:ingredients.php');
    }	

    //SUPPRIMER INGREDIENT
    if(isset($_POST['ingredient_delete'])){
        $ingredient_delete = $_POST['ingredient_delete'];
        $sql = $db -> prepare("DELETE FROM `ingredients` WHERE `ingredients`.`id` = :ingredient_delete");
        $sql->execute([
            ':ingredient_delete' => $ingredient_delete,
        ]);
    header('Location:ingredients.php');
    }

?>


<!DOCTYPE html>
<html>
    <head>
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
<div class="ingredients-form">
<div class="ligne" style="background:#2F2B2A;">
<form method="post">
<i class="bi bi-plus-square-fill"></i>
        <div class="input-box"style="color: #D1A085;width:200px;display:inline-block;border-bottom-color:#D1A085">
            <input type="text" name="nom_add" id="nom_add" class="nom_add" autocomplete="off" style="caret-color: #D1A085;color:#D1A085" required >
            <label style="color: #D1A085;">Nom</label>
        </div>
        <div class="input-box"style="color: #D1A085;width:80px;display:inline-block;border-bottom-color:#D1A085">
            <input type="text" name="prix_add" id="prix_add" class="prix_add" autocomplete="off" style="caret-color: #D1A085;color:#D1A085" required >
            <label style="color: #D1A085;">Prix</label>
        </div><p style="width:1px;display:inline-block;margin:5px 5px 5px -20px;color: #D1A085;">€</p>
        <div class="input-box"style="width:80px;display:inline-block;border-bottom-color:#D1A085; margin: 0 0px 0 20px">
            <input type="text" name="stock_add" id="stock_add" class="stock_add" autocomplete="off" style="caret-color: #D1A085;color:#D1A085"required >
            <label style="color: #D1A085;">Stock</label>
        </div>
        <button type="submit" name="ingredient_send" class="ingredient_add">Ajouter<i class="bi bi-plus-circle" style="color: #2F2B2A;"></i></i></button>
        
    </div>
</form>

</div>


    <?php foreach ($ingredients as $ingredient): ?>
        
        <form method="post">
        <div class="ingredients-form">
        <div class="ligne" id="<?php echo $ingredient['id']?>">
            <i class="bi bi-dot"></i><input class="nom_update" name="nom_update" id="nom_update" value="<?php echo $ingredient['name']?>"> 
            <div style="width:10%;display:inline-block;background:red"></div>
            <input class="prix_update"  name="prix_update" id="prix_update" value="<?php echo $ingredient['price']?>"><p style="width:1px;display:inline-block;margin:5px 5px 5px -20px ">€</p>
            <input class="stock_update" name="stock_update" id="stock_update" style="margin: 0 0px 0 20px" value="<?php echo $ingredient['stock_ingredient']?>">
            <button type="submit" name="ingredient_update" id="<?php echo $ingredient['id']?>" class="ingredient_update" value="<?php echo $ingredient['id']?>">Confirmer<i class="bi bi-check-circle"></i></button>
            <button type="submit" name="ingredient_delete" id="<?php echo $ingredient['id']?>" class="ingredient_delete" value="<?php echo $ingredient['id']?>"title="Supprimer"><i class="bi bi-x-lg"></i></button>    
        </div>
        </div>
        </form>
    <?php endforeach; ?>


        <div style="height:350px;background:transparent;display:flex"></div>
        <?php include 'include/footer.php'?>
    </body>
</html>