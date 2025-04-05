<?php
    session_start();

    // Script de connexion BDD
    require("../includes/database.php");

    // Récupération des données du formulaire
    $mail = $_POST["mail"];
    $password = $_POST["password"];
    
    // Vérifié les champs vides
    if (empty($mail) || empty($password)) {
        $_SESSION["errorMessage"] = "Il y a un champ vide";
        header("Location: ../../index.php");
    // Vérifié le format de l'email
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["errorMessage"] = "Email invalide";
        header("Location: ../../index.php");
    } else {
        // Hachage du mot de passe
        $securePassword = hash("sha256", $password);

        // Écriture de la requête SQL
        $selectSQL = "SELECT * FROM utilisateurs WHERE email = '$mail' AND mot_de_passe = '$securePassword'";

        // Envoie de la requête, stockage dans $result
        $result = $db->query($selectSQL);

        // Stockage du résultat dans un tableau
        $ligne = $result->fetch();

        // Vérifié le résultat dans la base de données
        if ($ligne) {
            // Connexion reussie
            $_SESSION["user_email"] = $ligne["email"];
            $_SESSION["user_id"] = $ligne["id_utilisateur"];
            $_SESSION["user_role"] = $ligne["role"];
            $_SESSION["username"] = $ligne["prenom"] . " " . $ligne["nom"];
            // Connexion reussie
            header("Location: ../../php/formSaisieFrais.php");
        } else {
            // Erreur de connexion
            $_SESSION["errorMessage"] = "Vérifier votre email et mot de passe";
            header("Location: ../../index.php");
        }
    }

    exit();
?>