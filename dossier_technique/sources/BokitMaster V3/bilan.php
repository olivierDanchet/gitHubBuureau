<!DOCTYPE html>
<?php
    // Connection a la base de données
        include 'include/database.php';
        global $db;

    //recupération de la table bilan
    $sql = $db->prepare("SELECT * FROM bilan ORDER BY date DESC;");
    $sql->execute([]);
    $bilans=$sql->fetchAll();

    //Date
    date_default_timezone_set('America/Guadeloupe'); //fuseau horaire Guadeloupe

    $jour = date('d');
    $mois = date('m');
    $annee = date('Y');
    



    // Bénéfice annuel
    $sql_annuel = $db->prepare("SELECT SUM(benefice) AS benefice_annuel FROM bilan WHERE YEAR(date) = :annee;");
    $sql_annuel->execute([
        ':annee'=>$annee        
    ]);
    $resultat_annuelle = $sql_annuel->fetch();
    $annuelle = $resultat_annuelle['benefice_annuel'];

    // Bénéfice mensuel
    $sql_mensuel = $db->prepare("SELECT SUM(benefice) AS benefice_mensuel FROM bilan WHERE YEAR(date) = :annee AND MONTH(date) = :mois;");
    $sql_mensuel->execute([        
        ':annee'=>$annee,
        ':mois'=>$mois
    ]);

    $resultat_mensuelle = $sql_mensuel->fetch();
    $mensuelle = $resultat_mensuelle['benefice_mensuel'];

    // Bénéfice journalier
    $sql_journalier = $db->prepare("SELECT SUM(benefice) AS benefice_journalier FROM bilan WHERE YEAR(date) = :annee AND MONTH(date) = :mois AND DAY(date) = :jour;");
    $sql_journalier->execute([
        ':annee'=>$annee,
        ':mois'=>$mois,
        ':jour'=>$jour
    ]);
    $resultat_journaliere = $sql_journalier->fetch();
    $journaliere = $resultat_journaliere['benefice_journalier'];

    //changement d'etape a 4
    if(isset($_POST['bouton-cuisine'])){
        $commande_id=$_POST['bouton-cuisine'];
        $etape=4;
        $sql=$db->prepare("UPDATE `bilan` SET `etape`=:etape WHERE id=:commande_id");
        $sql->execute([
            ':commande_id'=>$commande_id,
            ':etape'=>$etape,
        ]);     
    header('location:bilan.php');
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
                    <th>Date</th>
                    <th>Article</th>
                    <th>Quantité</th>
                    <th>Revenue</th>
                    <th>Dépense</th>
                    <th>Bénéfice</th>
                </tr>
            <?php foreach($bilans as $bilan){?>
                <tr>
                    <td><?php echo $bilan['id'] ?></td>
                    <td style="width:300px"><?php echo $bilan['date'] ?></td>
                    <td><?php echo $bilan['article'] ?></td>
                    <td><?php echo $bilan['quantite'] ?></td>
                    <td>+<?php echo $bilan['revenue'] ?> €</td>
                    <td>-<?php echo $bilan['depense'] ?> €</td>
                    <td>+<?php echo $bilan['benefice'] ?> €</td>
                    <?php if($bilan['etape']==3){?>
                    <td><form action="" method="post"><button type="submit" value="<?php echo $bilan['id']?>" name="bouton-cuisine" class="bouton-cuisine" style="width:50px; height:50px;margin:0px -20px 0px 0;font-size:20px"><i class="bi bi-check"></i></button></form></td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </table>
            <div class="panier-commande" style="margin-top: 20px;">
            <div class="panier-commades-box">
                <label>Recette Journaliere :</label>
                <label style="font-size:25px"><?php echo $journaliere?>€</label>
            </div>
            <div class="panier-commades-box">
                <label>Recette Hebdomadaire :</label>
                <label style="font-size:25px"><?php echo $mensuelle?>€</label>
            </div>
            <div class="panier-commades-box">
                <label>Recette Annuelle :</label>
                <label style="font-size:25px"><?php echo $annuelle?>€</label>
            </div>
        </div>
        </div>
        </div>

    <?php include 'include/footer.php' ?>
    </body>
</html>