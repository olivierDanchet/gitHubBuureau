
<?php 
    include 'include/database.php';
    global $db;

    // Selection de tout les comptes de la table table_test_bokit_master
	$stmt = $db->prepare("SELECT * FROM table_test_bokit_master");
	$stmt->execute();
	$comptes = $stmt->fetchAll();

    // AJOUTER UN COMPTE

    if (isset($_POST['compte_add'])) {

        if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && $password===$cpassword) {
            echo "formulaire send";
            echo $_POST['nom'];}
            $options = [
                'cost' => 12,
            ];
    
        if (isset($_POST['compte_add'])) {
            $nom = $_POST['nom_add'];
            $prenom = $_POST['prenom_add'];
            $email = $_POST['email_add'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT,$options);
            $fidelite = $_POST['points_add'];
            echo $nom;
            $sql = $db->prepare("INSERT INTO table_test_bokit_master(nom,prenom,email,password,fidelite) VALUES(:nom,:prenom,:email,:password,:fidelite)");
            $sql->execute([
                'nom'=> $nom,
                'prenom'=> $prenom,
                'email'=>$email,
                'password'=>$password,
                'fidelite'=>$fidelite,
            ]);
            header("location:comptes.php");
    
    
    }	
    }
    


    //MODIFIER COMPTE 

    if(isset($_POST['compte_update'])){
        $nom_update = $_POST['nom_update'];
        $prenom_update = $_POST['prenom_update'];
        $points_update = $_POST['points_update'];
        $email_update = $_POST['email_update'];
        $compte_update=$_POST['compte_update'];
            
        $sql = $db->prepare("UPDATE `table_test_bokit_master` SET `nom`=:nom_update,`prenom`=:prenom_update,`email`=:email_update,`fidelite`=:points_update WHERE `id`=:compte_update");
        $sql->execute([
            'nom_update'=> $nom_update,
            'prenom_update'=> $prenom_update,
            'points_update'=> $points_update,
            'email_update'=> $email_update,
            'compte_update' => $compte_update,
    ]);
    header('Location:comptes.php');
    }	

    //SUPPRIMER COMPTE
    if(isset($_POST['compte_delete'])){
        $compte_delete = $_POST['compte_delete'];
        $sql = $db -> prepare("DELETE FROM `table_test_bokit_master` WHERE `table_test_bokit_master`.`id` = :compte_delete");
        $sql->execute([
            'compte_delete' => $compte_delete,
        ]);
    header('Location:comptes.php');
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
<form method="post">
<div class="form-box">
    <div class="form-compte" style="background:#2F2B2A;">
        <div class="npep2">
            <div class="profil-np">
                <i style="color: #D1A085;" class="bi bi-person-add"></i>
            <button id="compte_add" type="submit" name="compte_add" class="compte_add">Créer</button>
            </div>
            <div class="profil-np">
                <div class="nom-prenom">
                    <div class="input-box" style="margin:0 0 0 0;color: #D1A085;border-bottom-color:#D1A085">
                        <span style="color: #D1A085;"class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="nom_add" id="nom_add" style="caret-color: #D1A085;color:#D1A085" autocomplete="off" required>
                        <label style="color: #D1A085;">Nom</label>
                    </div>
                    <div style="height:20px"></div>
                    <div class="input-box" style="margin:0 0 0 0;color: #D1A085;border-bottom-color:#D1A085">
                        <span style="color: #D1A085;" class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="prenom_add" id="prenom_add" style="caret-color: #D1A085;color:#D1A085" autocomplete="off" required>
                        <label style="color: #D1A085;">Prénom</label>
                    </div>
                    <div style="height:20px"></div>
                    <div class="input-box" style="margin:0 0 0 0;color: #D1A085;border-bottom-color:#D1A085">
                        <span style="color: #D1A085;" class="icon"><i class="bi bi-stars"></i></i></span>
                        <input type="text" name="points_add" id="points_add" style="caret-color: #D1A085;color:#D1A085" autocomplete="off" required>
                        <label style="color: #D1A085;">Points</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="npep">
            <div class="input-box" style="margin:0 0 0 0;color: #D1A085;border-bottom-color:#D1A085">
                <span style="color: #D1A085;" class="icon"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email_add" id="email_add" style="caret-color: #D1A085;color:#D1A085" autocomplete="off" required>
                <label style="color: #D1A085">Email</label>
            </div>
            <div style="height:20px"></div>
            <div class="input-box" style="margin:0 0 0 0;color: #D1A085;border-bottom-color:#D1A085">
                <span style="color: #D1A085;" class="icon"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" id="password" autocomplete="off" style="caret-color: #D1A085;color:#D1A085" required>
                <label style="color: #D1A085;">Mot de passe</label>
            </div>
        </div>
    </div>
</form>
</div>
<div class="ingredients-form">
    
    <?php foreach ($comptes as $compte): ?>
        <form method="post">
        <div class="form-compte">
            <div class="npep2">
                <div class="profil-np">
                    <i class="bi bi-person"></i>
                <button type="submit" id="<?php echo $compte['id']?>" name="compte_update" class="compte_update" value="<?php echo $compte['id']?>">Enregistrer</button>
                <button type="submit" name="compte_delete" id="<?php echo $compte['id']?>" class="compte_delete" value="<?php echo $compte['id']?>"  title="Supprimer"><i class="bi bi-x-lg"></i></button>  
                </div>
                <div class="profil-np">
                    <div class="nom-prenom">
                        <div class="input-box" style="margin:0 0 0 0;">
                            <span class="icon"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="nom_update" id="nom_update" autocomplete="off" value="<?php echo $compte['nom']?>" required>
                        </div>
                        <div style="height:20px"></div>
                        <div class="input-box" style="margin:0 0 0 0;">
                            <span class="icon"><i class="bi bi-person-fill"></i></span>
                            <input type="text" name="prenom_update" id="prenom_update" autocomplete="off" value="<?php echo $compte['prenom']?>" required>
                        </div>
                        <div style="height:20px"></div>
                        <div class="input-box" style="margin:0 0 0 0;">
                            <span class="icon"><i class="bi bi-stars"></i></i></span>
                            <input type="text" name="points_update" id="points_update" autocomplete="off" value="<?php echo $compte['fidelite']?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="npep">
                <div class="input-box" style="margin:0 0 0 0;">
                    <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email_update" id="email_update" autocomplete="off" value="<?php echo $compte['email']?>">
                </div>
            </div>
        </div>

        </form>
    <?php endforeach; ?>
    
</div>
    </div>        
        <?php include 'include/footer.php'?>
    </body>
</html>