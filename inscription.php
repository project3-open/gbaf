<?php
require_once("database.php");


if( isset($_POST['nom']) ) 
{
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $motdepasse = $_POST['motdepasse'];
    $motdepasseagain = $_POST['motdepasseagain'];
    $pass_hache = password_hash($motdepasse, PASSWORD_DEFAULT);    
    $question = $_POST['question'];
    $reponse = $_POST['reponse'];

    $pseudo = htmlspecialchars($_POST['pseudo']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $reponse = htmlspecialchars($_POST['reponse']);

if (!empty($_POST['nom']) AND !empty($_POST['prenom']) AND !empty($_POST['pseudo']) AND !empty($_POST['motdepasse']) AND !empty($_POST['motdepasseagain']) AND !empty($_POST['reponse']))
{
       
    $pseudolength = strlen($pseudo);
    if($pseudolength <= 255)
    {
        $reqpseudo = $bdd->prepare("SELECT * FROM account WHERE username = ?");
        $reqpseudo->execute(array($pseudo));
        $pseudoexist = $reqpseudo->rowCount();

    if($pseudoexist == 0)
    {
        
    if($motdepasse == $motdepasseagain)
    {
    $insertaccount = $bdd->prepare('INSERT INTO account (nom, prenom, username, pass, question, reponse) VALUES (:nom, :prenom, :username, :pass, :question, :reponse)');
    $insertaccount->execute(array(
    'nom' => $nom,
    'prenom' => $prenom,
    'username' => $pseudo,
    'pass' => $pass_hache,
    'question' => $question,
    'reponse' => $reponse));
                
    header('Location: connexion.php');
                
    }

        else
        {
                $erreur = "Vos mots de passes ne correspondent pas !";
            }
    }
        else
        {
            $erreur = "Le pseudo choisit est déjà utilisé";
        }
    }
        else
        {
           $erreur = "Votre pseudo ne doit pas dépasser 255 caractères !"; 
        }
        

    }
    else
    {
        $erreur = "Tous les champs doivent être complétés !";

    }   
    
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <header class="identite">

        <img src="logo/logo.png" alt="logo" class="petitlogo">

    </header>

<ul class ="menu">

    <li><a href="connexion.php">Se connecter</a></li>
    <li><a href="mdpforget.php">Mot de passe oublié</a></li>

</ul><br><br>


<form action="inscription.php" method="post"  class="formulaire">

    <div class="form-control">
    
        <label for="nom">Nom</label>
        <input type="text" id='nom' autofocus name='nom' value="<?php if(isset($nom)) { echo $nom; } ?>">

    </div>

    <div class="form-control">

        <label for="prenom">Prénom</label>
        <input type="text" id='prenom' autofocus name='prenom' value="<?php if(isset($prenom)) { echo $prenom; }?>">

    </div>

    <div class="form-control">

        <label for="pseudo">Pseudo</label>
        <input type="text" id='pseudo' autofocus name='pseudo' value="<?php if(isset($pseudo)) { echo $pseudo; }?>">

    </div>

    <div class="form-control">

        <label for="motdepasse">Mot de passe</label>
        <input type="password" id='motdepasse' autofocus name='motdepasse'>

    </div>

    <div class="form-control">

        <label for="motdepasseagain">Retapez votre mot de passe</label>
        <input type="password" id='motdepasseagain' autofocus name='motdepasseagain'>

    </div>

    <div class="form-control">

        <label for="question">Question secrète</label>
        <SELECT name="question" size="1">
        <OPTION>Quel est le nom de votre premier animal de compagnie ?
        <OPTION>Où est né votre père ?
        <OPTION>Quelle est la marque de votre première voiture ?
        <OPTION>Quelle est votre couleur préférée ?
        </SELECT>
    
    </div>

    <div class="form-control">

        <label for="reponse">Réponse à la question secrète</label>
        <input type="text" id='reponse' autofocus name='reponse' value="<?php if(isset($reponse)) { echo $reponse; }?>">

    </div>

    <div class="valider">

        <input type="submit" value="S'inscrire">

    </div>

</form>

<?php
if(isset($erreur))
{
    echo "<font color='red'><strong> $erreur </strong></font>";;
}
?>

<?php require 'footer.php'; ?>  
</body>
</html>