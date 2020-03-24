<?php
session_start();

if (isset($_SESSION['id_user'])) header('Location: accueil.php');

if(isset($_GET['step'])) {
    if ($_GET['step'] == 1 && !isset($_SESSION['forget_id_user']) ) {
        header('Location: mdpforget.php');
    }
    if ($_GET['step'] == 2 && !isset($_SESSION['forget_authorized']) ) {
        header('Location: mdpforget.php?step=1');
    }
}

try
{
    $bdd = new PDO('mysql:host=localhost;dbname=projdwcj_gbaf;charset=utf8', 'projdwcj_root', 'taOR2T6T^M7]');

}
catch(Exception $e)
{

        die('Erreur : '.$e->getMessage());
}

    if(isset($_POST['recup_pseudo'])) {

        $recup_pseudo = htmlspecialchars($_POST['recup_pseudo']);
        
        $user_check = $bdd->prepare('SELECT * FROM account WHERE username = ?');
        $user_check->execute(array($recup_pseudo));
        $pseudoexist = $user_check->rowCount();
        if($pseudoexist){
            $user_id = $user_check->fetch();

            $_SESSION['forget_id_user'] = $user_id["id_user"];
            $_SESSION['forget_question_user'] = $user_id["question"];
            $_SESSION['forget_reponse_user'] = $user_id["reponse"];

            header('Location: mdpforget.php?step=1');
        } else {
            $mdpmess = "Ce pseudo n'existe pas";
        }
    }

    if(isset($_POST['recup_reponse'])) {

        if($_POST['recup_reponse'] == $_SESSION['forget_reponse_user']) {

            $_SESSION['forget_authorized'] = true;
            header('Location: mdpforget.php?step=2');

        } else {
            $mdpmess = "Ce n'est pas la bonne réponse";
        }
    }

    if(isset($_POST['recup_nouveau']))
    {
        if (!empty($_POST['recup_nouveau']) AND !empty($_POST['recup_nouveau_repeat']))
    { 
     
        if($_POST['recup_nouveau'] == $_POST['recup_nouveau_repeat'])
        {

            $pass_hache = password_hash($_POST['recup_nouveau'], PASSWORD_DEFAULT);
            $insertpseudo = $bdd->prepare("UPDATE account SET pass = ? WHERE id_user = ?");
            $insertpseudo->execute(array($pass_hache, $_SESSION['forget_id_user']));

    
        
            session_destroy();
            header('Location:connexion.php');
            $mdpmess = "Votre mot de passe à bien été changé";

        } else {
            $mdpmess = "Les 2 mots de passe doivent être identiques";
        } 
       
    } else {
        $mdpmess = "Tous les champs doivent être remplis";
    } 
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<header class="identite">

    <img src="logo/logo.png" alt="logo" class="petitlogo">
    <p class="identite"></p>

</header>

<ul class ="menu">

    <li><a href="index.php">Accueil</a></li>

</ul><br><br>

<?php

if(!isset($_GET['step'])) {

?>

<form method="post" class="formulaire">

    <div class="form-control">

        <input type="text" autofocus name="recup_pseudo" placeholder="Votre pseudo">

    </div>

    <div class="valider">

        <input type="submit" value="Valider">

    </div>

</form>

<?php

} elseif($_GET['step'] == 1) {

    switch ($_SESSION['forget_question_user']) {
        case 'animal':
            $question = "Quel est le nom de votre premier animal de compagnie ?";
            break;

        case 'pere':
            $question = "Où est né votre père ?";
            break;

        case 'voiture':
            $question = "Quelle est la marque de votre première voiture ?";
            break;

        case 'couleur':
            $question = "Quelle est votre couleur préférée ?";
            break;
        
    }
?>

<form method="post" class="formulaire">

Question : <?php echo $question; ?>

    <div class="form-control">

        <input type="text" autofocus name="recup_reponse" placeholder="Votre réponse">

    </div>

    <div class="valider">

        <input type="submit" value="Valider">

    </div>

</form>

<?php

} elseif($_GET['step'] == 2) {

?>

<form method="post" class="formulaire">

    <div class="form-control">

        <input type="password" autofocus name="recup_nouveau" placeholder="Nouveau mot de passe">

    </div>

    <div class="form-control">

        <input type="password" autofocus name="recup_nouveau_repeat" placeholder="Retapez le mot de passe">

    </div>

    <div class="valider">

        <input type="submit" value="Valider">

    </div>
</form>

<?php
} 
?>
<?php

if(isset($mdpmess))
{
    echo "<font color='red'><strong> $mdpmess </strong></font>";
}
?>

<footer class="footer">

 <a href="#">| Mentions légales |</a>
 <a href="#"> Contacts |</a>

</footer>
</body>
</html>