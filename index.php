<?php
session_start();
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');

}
catch(Exception $e)
{

        die('Erreur : '.$e->getMessage());
}

if (!isset($_SESSION['id_user'])) header('Location: connexion.php');

    $liste_acteurs = $bdd->query('SELECT * FROM acteur');
    
    
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil GBAF</title>
    <link rel="stylesheet" href="assets/css/style.css" >

</head>

<body>

<ul class ="menu">
    <li><a href="profil.php">Mon profil</a></li>
    <li><a href="deconnexion.php">Se déconnecter</a></li>
    
</ul><br><br>
<div class="entete">

    <h1>Site du GBAF !</h1>

        <p>Même s’il existe une forte concurrence entre ces entités, elles vont toutes travailler
de la même façon pour gérer près de 80 millions de comptes sur le territoire
national.
Le GBAF est le représentant de la profession bancaire et des assureurs sur tous
les axes de la réglementation financière française. Sa mission est de promouvoir
l'activité bancaire à l’échelle nationale. C’est aussi un interlocuteur privilégié des
pouvoirs publics.
        </p>

            <img src="logo/logo.png" alt="logo" class="logo">
</div>

    <h2><center>Les différents acteurs</center></h2>


<?php
foreach ($liste_acteurs as $acteur){
?>
<div class="acteurs">

    <img src="<?php echo $acteur['logo'];?>" alt="logo" class="logoacteur">
   
    <h2><center><?php echo $acteur['acteur']; ?><br/></center></h2>
    <p><?php echo substr($acteur['description'], 0, 150);?>...</p>
    
    

<a href="pageacteur.php?acteur=<?php echo $acteur['id_acteur'];?>">Lire la suite</a>

</div>
<?php
}
?>

</body>
</html>
