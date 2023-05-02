<!DOCTYPE html>
<?php 
// Connection a la base de données
        include 'include/database.php';
        global $db;
        ?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>BokitMaster</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <?php include 'include/header.php'?>
        <script>  $('#acceuil').css('border-bottom-color',' #2ECC40')</script>
    </head>
    <body>
    <div class="accueil">
        <h1>Bienvenue sur le site de Bokit Master !</h1>
        <div class="projet">
        <h2 style="text-align:left;margin:10px 0 0 0">Instruction :</h2><br>
        <p>Pour utiliser ce site, voici quelques instructions importantes à suivre. Tout d'abord, je vous conseille de supprimer ou de renommer la base de données "bokit_master" si elle existe déjà.
            En effet, lors de l'ouverture du site, une exécution automatique crée cette base de données ainsi que ses tables dans le serveur Wamp. 
            Si vous souhaitez modifier ces informations, vous pouvez accéder au fichier "include/database.php".<br><br>
            <b>Si vous souhaitez vous connecter en tant qu'administrateur, veuillez utiliser les identifiants suivants : email "admin@admin" et mot de passe "admin".</b>
            <br><br>Veuillez noter que certaines fonctions conventionnelles, telles que "remember me" lors de la connexion, "accepter les conditions d'utilisation" lors de l'inscription, 
        ainsi que la fonction "contact", ne sont pas encore opérationnelles.</p>
        </div>

        <div class="site">
        <h2 style="text-align:left;margin:30px 0 0 0">Site :</h2>
        <p>Le site de commandes en ligne pour un restaurant de bokits est une solution innovante qui offre de nombreux avantages pour les clients et les administrateurs du restaurant. 
            Grâce à cette plateforme, les clients peuvent commander des menus personnalisés en quelques clics, sans avoir à se déplacer ou à attendre en ligne.<br><br>
            Les administrateurs ont la possibilité de gérer les ingrédients, les menus et les commandes en temps réel, 
            en visualisant les informations détaillées de chaque commande et en la suivant tout au long du processus, de la préparation à la livraison.<br><br>
            La gestion des stocks est également optimisée grâce à ce site de commandes en ligne. 
            Les administrateurs peuvent gérer les stocks en temps réel et ajouter de nouveaux ingrédients et menus facilement, 
            ce qui permet d'optimiser la production et d'éviter les ruptures de stock.<br><br>
            En outre, la plateforme de commande en ligne offre un système de compte fidélité, permettant aux clients de bénéficier de réductions et d'offres spéciales. 
            Cela encourage les clients à commander régulièrement et à fidéliser leur relation avec le restaurant.<br><br>
            En réduisant les erreurs de commande et en optimisant la gestion des stocks, le restaurant peut augmenter sa rentabilité tout en offrant un service de qualité à ses clients. 
            Grâce à cette plateforme innovante, le restaurant peut améliorer son expérience client et sa performance opérationnelle, tout en gagnant en visibilité et en compétitivité.
        </div></p>
    </div>
    
    <?php include 'include/footer.php'?>
    </body>
</html>
