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
{

   
if(isset($_POST['newpseudo']))
{
    $newpseudo = htmlspecialchars($_POST['newpseudo']);
    $newnom = htmlspecialchars($_POST['newnom']);
    $newprenom = htmlspecialchars($_POST['newprenom']);
    $newmotdepasse = $_POST['newmotdepasse'];
    $newmotdepasseagain = $_POST['newmotdepasseagain'];
    $pass_hache = password_hash($newmotdepasse, PASSWORD_DEFAULT); 
    $newquestion = htmlspecialchars($_POST['newquestion']);
    $newreponse = htmlspecialchars($_POST['newreponse']);

    
    $pseudolength = strlen($newpseudo);

    if($pseudolength > 255) 
    {
        $message["erreur"] = "Votre pseudo ne doit pas dépasser 255 caractères !"; 
    }
    
    if($newpseudo != $_SESSION['username']) {
        $reqpseudo = $bdd->prepare("SELECT * FROM account WHERE username = ?");
        $reqpseudo->execute(array($newpseudo));
        $pseudoexist = $reqpseudo->rowCount();
    
        if($pseudoexist)
        {
            $message["erreur"] = "Le pseudo choisit est déjà utilisé";
        }
    }
    
        
    if($newmotdepasse != $newmotdepasseagain)
    {
        $message["erreur"] = "Vos mots de passes ne correspondent pas !";
    }
    
    
    if (empty($_POST['newmotdepasse']) AND empty($_POST['newmotdepasseagain']))
        {
     
        $insertpseudo = $bdd->prepare("UPDATE account SET username = ?, nom = ?, prenom = ?, question = ?, reponse = ? WHERE id_user = ?");
        $insertpseudo->execute(array(
        $newpseudo, $newnom, $newprenom, $newquestion, $newreponse, $_SESSION['id_user']));
    
        $_SESSION['nom'] = $newnom;
        $_SESSION['prenom'] = $newprenom;
        $_SESSION['username'] = $newpseudo;
        $_SESSION['question'] = $newquestion;
        $_SESSION['reponse'] = $newreponse;


        $message["success"] = "Votre profil a bien été mis à jour";    
        
        }   
        if (!empty($_POST['newmotdepasse']) AND !empty($_POST['newmotdepasseagain']))
        {
     
        $insertpseudo = $bdd->prepare("UPDATE account SET pass = ? WHERE id_user = ?");
        $insertpseudo->execute(array(
        $pass_hache, $_SESSION['id_user']));
    
        $_SESSION['nom'] = $newnom;
        $_SESSION['prenom'] = $newprenom;
        $_SESSION['username'] = $newpseudo;
        $_SESSION['question'] = $newquestion;
        $_SESSION['reponse'] = $newreponse;


        $message["success"] = "Votre profil a bien été mis à jour";    
        
        }   

    }
    

       
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition de mon profil </title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<header class="identite">

    <img src="logo/logo.png" alt="logo" class="petitlogo">
    <p class="identite"><?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'] ;?></p>

</header> 

<ul class ="menu">

    <li><a href="index.php">Accueil</a></li>
    <li><a href="deconnexion.php">Se déconnecter</a></li> <br><br><br>
    
</ul><br><br>

<form method="post" class="formulaire">

<?php
    if(isset($message["erreur"]))
    {
        echo "<font color='red'><strong> {$message["erreur"]} </strong></font>";
    }
    if(isset($message["success"]))
    {
        echo "<font color='green'><strong> {$message["success"]} </strong></font>";
    }
    ?>   

<div class="form-control">
    
    
    <br><label for="nom">Nom</label>
    <input type="text" id='nom' autofocus="required=" name='newnom' value="<?php echo $_SESSION["nom"];?>">

</div>

<div class="form-control">
    

    <label for="prenom">Prénom</label>
    <input type="text" id='prenom' autofocus="required=" name='newprenom' value="<?php echo $_SESSION["prenom"];?>">

</div>

<div class="form-control">

    <label for="pseudo">Pseudo</label>
    <input type="text" id='pseudo' autofocus="required=" name='newpseudo' value="<?php echo $_SESSION["username"];?>">

</div>

<div class="form-control">

    <label for="motdepasse">Mot de passe</label>
    <input type="password" id='newmotdepasse' autofocus="required=" name='newmotdepasse'>
</div>
<div class="form-control">
    <label for="motdepasseagain">Retapez votre mot de passe</label>
    <input type="password" id='motdepasseagain' autofocus="required=" name='newmotdepasseagain'>

</div>

<div class="form-control">

    <label for="newquestion">Question secrète</label>
    <select name="newquestion" size="1">
    <option value='animal' <?php if($_SESSION['question'] == 'animal') echo 'selected';?>>Quel est le nom de votre premier animal de compagnie ?</option>
    <option value='pere' <?php if($_SESSION['question'] == 'pere') echo 'selected';?>>Où est né votre père ?</option>
    <option value='voiture' <?php if($_SESSION['question'] == 'voiture') echo 'selected';?>>Quelle est la marque de votre première voiture ?</option>
    <option value='couleur' <?php if($_SESSION['question'] == 'couleur') echo 'selected';?>>Quelle est votre couleur préférée ?</option>
    </select>



</div>

<div class="form-control">

    <label for="reponse">Réponse à la question secrète</label>
    <input type="text" id='reponse' autofocus="required=" name='newreponse' value="<?php echo $_SESSION['reponse'];?>">

</div>

<div class="valider">

    <input type="submit" name="valider" value="Mettre à jour mon profil">

</div>

</form>

</body>

<footer class="footer">

 <a href="#">| Mentions légales |</a>
 <a href="#"> Contacts |</a>

</footer>

</html>
