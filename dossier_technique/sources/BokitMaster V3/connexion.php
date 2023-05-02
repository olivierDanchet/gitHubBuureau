<?php 
session_start();
?>
<?php 
		include 'include/database.php';
		global $db;

	if (isset($_POST['formsend'])) {
		$email=$_POST['email'];
		$password=$_POST['password'];

		if (!empty($email) && !empty($password)) {
			
			$sql = $db->prepare("SELECT * FROM table_test_bokit_master WHERE email = :email");
			$sql ->execute(['email'=>$email]);
			$result = $sql-> fetch();

			if ($result == true) 
			{


				if (password_verify($password, $result['password']))
				{
					echo "mot de passe bon connexion en cours";
					
					$_SESSION['nom']=$result['nom'];
					$_SESSION['prenom']=$result['prenom'];
					$_SESSION['fidelite']=$result['fidelite'];
                    $_SESSION['id']=$result['id'];
                    setcookie('id',$_SESSION['id'],time()+3600);
					setcookie('nom',$_SESSION['nom'],time()+3600);
					setcookie('prenom',$_SESSION['prenom'],time()+3600);
					setcookie('fidelite',$_SESSION['fidelite'],time()+3600);
					// setcookie('nom','',time());
					// setcookie('prenom','',time());
					session_destroy();
					header("location:index.php");
					}
			}
		}



	}?>
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

        <?php include 'include/header.php'?>
    </head>
    <body>
        <div class="wrapper">
            <div class="form-box login">
                <h2>Connexion</h2>
                <form method="post">
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-envelope-fill"></i></span>
                        <input id="email" class="email" name="email" type="email" autocomplete="off" required>
                        <label>Email</label>
                    </div>
                    <div class="input-box">
                        <span class="icon"><i class="bi bi-lock-fill"></i></span>
                        <input id="password" class="password" name="password" type="password" autocomplete="off" required>
                        <label>Mot de passe</label>
                    </div>
                    <div class="remember-forgot">
                        <label><input type="checkbox">Remember me</label>
                        <a href="#">Mot de passe oublier ?</a>
                    </div>
                    <button type="submit" name="formsend" id="formsend" class="register">Se connecter</button>
                    <div class="login-register">
                        <p>Pas encore inscris ? <a href="inscription.php" class="register-link">S'inscrire</a></p>
                    </div>

                </form>
            </div>
        </div>


    <?php include 'include/footer.php'?>
    </body>
    
</html>