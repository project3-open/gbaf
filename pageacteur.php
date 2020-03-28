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


if (!empty($_GET['acteur']) AND $_GET['acteur'] <= 4)
{

$id_acteur = $_GET['acteur'];
$c_msg = "";

    $liste_post = $bdd->prepare('SELECT * FROM account INNER JOIN post ON account.id_user = post.id_user WHERE id_acteur = ?');
    $liste_post->execute([$id_acteur]);
    $liste_post = $liste_post->fetchAll();

    $acteur = $bdd->prepare("SELECT * FROM acteur WHERE id_acteur = ?");
    $acteur->execute([$id_acteur]);
    $acteur=$acteur->fetch();

    
if(isset($_POST['commentaire'])) {

    if(isset($_POST['commentaire']) AND !empty($_POST['commentaire'])){

    $commentaire = htmlspecialchars($_POST['commentaire']);

    $postcheck = $bdd->prepare("SELECT * FROM post WHERE id_user = ? AND id_acteur = ?");
    $postcheck->execute(array($_SESSION['id_user'], $id_acteur));
    $post_exists = $postcheck->rowCount();

    if($post_exists) {

        $c_erreur .= 'Vous avez déjà commenté '; 

    } else {

    $ins = $bdd->prepare('INSERT INTO post (id_user, id_acteur, date_add, commentaire) VALUES (:id_user, :id_acteur,  NOW(), :commentaire)');
    $ins->execute(array(

            'id_user' => $_SESSION['id_user'],
            'id_acteur' => $id_acteur,
            'commentaire' => $commentaire));
            $c_msg .= 'Votre commentaire a bien été posté ';

    }        
    } else {

        $c_erreur .= "Tous les champs doivent être complétés";
    
    }
}

if(isset($_GET["vote"])) {

    $vote = $_GET["vote"];

    if(!is_numeric($vote)) die("Erreur");

    $votecheck = $bdd->prepare("SELECT * FROM vote WHERE id_user = ? AND id_acteur = ?");
    $votecheck->execute(array($_SESSION['id_user'], $id_acteur));
    $vote_exists = $votecheck->rowCount();

    if($vote_exists) {

        $c_erreur .= 'Vous avez déjà voté'; 
    } else {

        $insvote = $bdd->prepare('INSERT INTO vote (id_user, id_acteur, vote) VALUES (:id_user, :id_acteur, :vote)');
        $insvote->execute(array(
                'id_user' => $_SESSION['id_user'],
                'id_acteur' => $id_acteur,
                'vote' => $vote));
        $c_msg .= 'Merci d\'avoir voté'; 
    }
}

        $like = $bdd->prepare("SELECT * FROM vote WHERE id_acteur = ? AND vote = 1"); 
        $like->execute(array($id_acteur));
        $likes = $like->rowCount();

        $dislike = $bdd->prepare("SELECT * FROM vote WHERE id_acteur = ? AND vote = 0"); 
        $dislike->execute(array($id_acteur));
        $dislikes = $dislike->rowCount();

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $acteur['acteur']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<?php require 'header.php'; ?>

<ul class ="menu">

    <li><a href="index.php">Accueil</a></li>
    <li><a href="profil.php">Mon profil</a></li>
    <li><a href="deconnexion.php">Se déconnecter</a></li>

</ul><br><br>

<div>

    <a href="pageacteur.php?acteur=<?php echo $acteur['id_acteur'];?>"><h2><?php echo $acteur['acteur']; ?></h2></a>

    <img src="<?php echo $acteur['logo'];?>" alt="logo" class="groslogo">

    <p class="contenu"> 
    <?php echo $acteur['description']; ?>

</div>    

<div class="vote">

    <h2>Commentaire</h2>

<form method="post">

    <textarea name="commentaire" placeholder='Votre commentaire'rows="5" cols="33"></textarea><br><br>
    <input type="submit" value='Poster mon commentaire'><br><br>

</form>

    <a href="pageacteur.php?acteur=<?php echo $id_acteur;?>&vote=1">J'aime</a> (<?php echo $likes;?>)
    <a href="pageacteur.php?acteur=<?php echo $id_acteur;?>&vote=0">Je n'aime pas</a>(<?php echo $dislikes;?>)

</div>

<div>

    <?php
    if(isset($c_erreur)) { echo "<font color='red'><strong> $c_msg </strong></font>"; } ?>
    <?php
    if(isset($c_msg)) { echo "<font color='green'><strong> $c_msg </strong></font>"; } ?>

</div>

    <?php foreach ($liste_post as $post){ 

    ?>  


<div class='commentaire'>

    <p><strong><?php echo $post['prenom'] . ' ' . $post['nom'] ;?></strong></p> 
    <p><?php echo $post['commentaire'];?></p>
    <p class="italic"><?php echo date("d/m/Y H:i:s", strtotime( $post['date_add'] ));?></p>

</div>

<?php
}
?>

<?php require 'footer.php'; ?>
</div>
<?php
}
else
{
 echo 'Cette page n\'existe pas';
}
?>

</body>
</html>