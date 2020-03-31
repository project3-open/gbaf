<?php

try {

    $bdd = new PDO('mysql:host=localhost;dbname=gbaf;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} 
catch (PDOException $e) {

print "Erreur !: " . $e->getMessage() . "<br/>";
die();

}