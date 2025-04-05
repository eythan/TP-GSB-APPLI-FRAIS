<?php
    session_start();

    // Script de connexion BDD
    require("../../src/includes/database.php");

    // Récupération des données du formulaire
    $mail=$_POST["mail"];
    $password=$_POST["password"];
    $lastname=$_POST["lastname"];
    $firstname=$_POST["firstname"];
    $address=$_POST["address"];
    $postal=$_POST["postal"];
    $city=$_POST["city"];

    // Vérifié les champs vides
    if (empty($mail) || empty($password) || empty($lastname) || empty($firstname) || empty($address) || empty($postal) || empty($city)) {
        $_SESSION["errorMessage"] = "Il y a un champ vide";
        header("Location: ../../php/inscription.php");
    // Vérifié le format de l'email
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errorMessage"] = "Email invalide";
        header("Location: ../../php/inscription.php");
    } else {
        // Hachage du mot de passe
        $securePassword = hash("sha256", $password);

        // Écriture de la requête SQL
        $insertSQL = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, adresse, code_postal, ville) VALUES ('$lastname', '$firstname', '$mail', '$securePassword', 'visiteur', '$address', '$postal', '$city')";

        // Envoie de la requête
        $db->exec($insertSQL);

        $_SESSION["user_email"] = $mail;
        $_SESSION["user_role"] = $role;
        $_SESSION["username"] = $firstname . " " . $lastname;

        header("Location: ../../php/formSaisieFrais.php");
    }

    exit();
?>
