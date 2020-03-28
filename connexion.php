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
if ( isset( $_POST['pseudoconnect'] ) ) 
{ 
    $motdepasseconnect = $_POST['motdepasseconnect'];
    $pseudoconnect = htmlspecialchars($_POST['pseudoconnect']);
    
    if(!empty($pseudoconnect) AND !empty($motdepasseconnect))
    {

        $reqpseudo = $bdd->prepare("SELECT * FROM account WHERE username = ?");
        $reqpseudo->execute(array($pseudoconnect));
        $pseudoexist = $reqpseudo->rowCount();

        if($reqpseudo->rowCount())
        {
            $userinfo = $reqpseudo->fetch();

            if( password_verify($motdepasseconnect, $userinfo['pass']) ) {

                $_SESSION['id_user'] = $userinfo['id_user'];
                $_SESSION['username'] = $userinfo['username'];
                $_SESSION['nom'] = $userinfo['nom']; 
                $_SESSION['prenom'] = $userinfo['prenom']; 
                $_SESSION['question'] = $userinfo['question'];
                $_SESSION['reponse'] = $userinfo['reponse'];
                
                header("Location: index.php");  

            }
            else
            {
                $c_connexion = "Mauvais mot de passe";
            }

        }
        else
        {
            $c_connexion = "Mauvais pseudo ou mot de passe";
        }
            
    }
    else
    {
        $c_connexion = " Tous les champs doivent être complétés !";
    }
    
       
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <header class="identite">

        <img src="logo/logo.png" alt="logo" class="petitlogo">
        <p class="identite"></p>

    </header>

<ul>

    <li><a href="inscription.php">S'inscrire</a></li>
    <li><a href="mdpforget.php">Mot de passe oublié</a></li>

</ul><br><br>

    <form action="connexion.php" method="post" class="formulaire">

        <div class="form-control">

        <label for="pseudo">Pseudo</label>
        <input type="text" id='pseudo' autofocus name="pseudoconnect">

        </div>

        <div class="form-control">

        <label for="motdepasse">Mot de passe</label>
        <input type="password" id='motdepasse' autofocus name="motdepasseconnect">

        </div>

        <div class="valider">

        <input type="submit" name="connect" value="Se connecter">

        </div>

    </form>

<?php
if(isset($c_connexion))
{
    echo "<font color='red'><strong> $c_connexion </strong></font>";
}
?>

<?php require 'footer.php'; ?>
</body>
</html>