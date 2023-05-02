<!DOCTYPE html>
<?php 

if (isset($_POST['formsend'])) {

    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($password) && $password===$cpassword) {
        echo "formulaire send";
        echo $_POST['nom'];}
        $options = [
            'cost' => 12,
        ];

    include 'include/database.php';
    global $db;

    if (isset($_POST['formsend'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT,$options);
        $cpassword = $_POST['cpassword'];
        
        $sql = $db->prepare("INSERT INTO table_test_bokit_master(nom,prenom,email,password) VALUES(:nom,:prenom,:email,:password)");
        $sql->execute([
            'nom'=> $nom,
            'prenom'=> $prenom,
            'email'=>$email,
            'password'=>$password
        ]);
        header('location:index.php');


}	
}


?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>BokitMaster</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <?php include 'include/header.php'?>
    </head>
    <body>
        
        <div class="wrapper" style="height:620px">
            <div class="form-box login">
                <h2>Inscription</h2>
                <form method="post">
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="nom" id="nom" autocomplete="off" required>
                        <label>Nom</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="prenom" id="prenom" autocomplete="off" required>
                        <label>Prénom</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="email" id="email" autocomplete="off" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="password" autocomplete="off" required>
                        <label>Mot de passe</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="cpassword" id="cpassword" autocomplete="off" required>
                        <label>Confirmez votre mot de passe</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox">J'acceptes les conditions d'utilisation</label>
                    </div>
                    <button type="submit" name="formsend" id="formsend" class="register">S'inscrire</button>
                    <div class="login-register">
                        <p>Déja inscris ? <a href="" class="register-link">Se connecter</a></p>
                    </div>
                </form>
            </div>
        </div>

    <?php include 'include/footer.php'?>
    </body>
</html>