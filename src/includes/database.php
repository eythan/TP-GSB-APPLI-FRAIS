<?php
    $host = "localhost";
    $dbname = "app";
    $username = "root";
    $password = "";

    try { 
        $db= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        #echo "<span style='color: green;'>Connexion réussie à la base de données </span>";
    } catch ( Exception $e ) { 
        die("<span style='color: red;'>Connexion impossible avec la base de données : ".$e->getMessage()."</span>");
    }
?>