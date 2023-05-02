<!-- <?php
	define('HOST', 'bokitmaster2');
	define('DB_NAME','projetbokitmaster_bokitmaster');
	define('USER','root');
	define('PASS','');

	try {
		// Connexion à MySQL
		$db = new PDO("mysql:host=".HOST, USER, PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Vérification de l'existence de la base de données
		$result = $db->query("SHOW DATABASES LIKE '".DB_NAME."'");
		if ($result->rowCount() == 0) {
			// Création de la base de données
			$db->query("CREATE DATABASE ".DB_NAME);
		} else {
		}

		// Connexion à la base de données
		$db = new PDO("mysql:host=".HOST.";dbname=".DB_NAME, USER, PASS);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		// Création des tables si elles n'existent pas
		// ...

	} catch(PDOException $e) {
		echo "Erreur : " . $e->getMessage();
	}

	//Requetes

	$commandes=$db->prepare("CREATE TABLE commandes (
		id INT(11) NOT NULL AUTO_INCREMENT,
		compte_id INT(11) NOT NULL,
		menu_id INT(11) NOT NULL,
		nombre INT(11) NOT NULL,
		etape INT(11) NOT NULL DEFAULT 1,
		date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
		revenue float NOT NULL,
		depense float NOT NULL,
		benefice float NOT NULL,
		PRIMARY KEY (id)
	  );");
	  
	$ingredients=$db->prepare("CREATE TABLE ingredients (
		id int(11) NOT NULL AUTO_INCREMENT,
		name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		price decimal(10,2) NOT NULL,
		stock_ingredient int(11) NOT NULL,
		PRIMARY KEY (id),
		UNIQUE (name)
	  );");

	$menu_ingredient=$db->prepare("CREATE TABLE menu_ingredient (
		id int(11) NOT NULL AUTO_INCREMENT,
		menu_id int(11) NOT NULL,
		ingredient_id int(11) NOT NULL,
		stock float NOT NULL DEFAULT 0,
		PRIMARY KEY (id)
	  );");

	$menu_test=$db->prepare("CREATE TABLE menu_test (
		menu_id int(11) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		price int(11) NOT NULL DEFAULT 1,
		stock float NOT NULL DEFAULT 0,
		description text,
		PRIMARY KEY (menu_id)
	  );
	  ");

	$panier=$db->prepare("CREATE TABLE panier (
		id int(11) NOT NULL AUTO_INCREMENT,
		compte_id int(11) NOT NULL,
		menu_id int(11) NOT NULL,
		nombre int(11) NOT NULL,
		date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
	  );
	  ");

	$bilan=$db->prepare("CREATE TABLE `bilan` (`id` INT NOT NULL AUTO_INCREMENT , `article` INT NOT NULL , `quantite` INT NOT NULL , `prix` INT NOT NULL , `revenue` INT NOT NULL , `depense` INT NOT NULL , `benefice` INT NOT NULL , `date` DATE NOT NULL DEFAULT CURRENT_TIMESTAMP , `compte_id` INT NOT NULL, `etape` INT NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM;
	");

	$table_test_bokit_master=$db->prepare("CREATE TABLE table_test_bokit_master (
		id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
		nom varchar(30) COLLATE utf8_general_ci NOT NULL,
		prenom varchar(30) COLLATE utf8_general_ci NOT NULL,
		email varchar(50) COLLATE utf8_general_ci NOT NULL,
		password varchar(255) COLLATE utf8_general_ci NOT NULL,
		fidelite int(2) NOT NULL DEFAULT '1',
		nbr_cmd int(6) NOT NULL DEFAULT '0',
		PRIMARY KEY (id)
	  ); 
	  ");
	$options = ['cost' => 12,];
	$password = password_hash('admin', PASSWORD_BCRYPT,$options);
	$compteadmin=$db->prepare("INSERT INTO `table_test_bokit_master`(`nom`, `prenom`, `email`, `password`, `fidelite`) VALUES ('admin','admin','admin@admin',:password,'11')");


try{

    // requête pour vérifier si la table existe
    $result = $db->query("SHOW TABLES LIKE 'ingredients'");
    // vérifier le nombre de résultats
    if ($result->rowCount() > 0) {
    } else {
        $ingredients->execute();
    }
} catch(PDOException $e) {
	echo "Erreur : " . $e->getMessage();
}

try{
	// requête pour vérifier si la table existe
	$result = $db->query("SHOW TABLES LIKE 'menu_ingredient'");
	// vérifier le nombre de résultats
	if ($result->rowCount() > 0) {
	} else {
		$menu_ingredient->execute();
	}
} catch(PDOException $e) {
	echo "Erreur : " . $e->getMessage();
}
try{
	// requête pour vérifier si la table existe
	$result = $db->query("SHOW TABLES LIKE 'bilan'");
	// vérifier le nombre de résultats
	if ($result->rowCount() > 0) {
	} else {
		$bilan->execute();
	}
} catch(PDOException $e) {
	echo "Erreur : " . $e->getMessage();
}

try{
	// requête pour vérifier si la table existe
	$result = $db->query("SHOW TABLES LIKE 'panier'");
	// vérifier le nombre de résultats
	if ($result->rowCount() > 0) {
	} else {
		$panier->execute();
	}
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

try{
    // requête pour vérifier si la table existe
    $result = $db->query("SHOW TABLES LIKE 'menu_test'");
    // vérifier le nombre de résultats
    if ($result->rowCount() > 0) {
    } else {
        $menu_test->execute();
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

try{
    // requête pour vérifier si la table existe
    $result = $db->query("SHOW TABLES LIKE 'table_test_bokit_master'");
    // vérifier le nombre de résultats
    if ($result->rowCount() > 0) {
    } else {
        $table_test_bokit_master->execute();
		$compteadmin->execute([':password'=>$password]);
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

try{
    // requête pour vérifier si la table existe
    $result = $db->query("SHOW TABLES LIKE 'commandes'");
    // vérifier le nombre de résultats
    if ($result->rowCount() > 0) {
    } else {
        $commandes->execute();
    }
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}






?>
 -->
