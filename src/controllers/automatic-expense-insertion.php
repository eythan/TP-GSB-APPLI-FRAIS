<?php
    session_start();
    require("../includes/database.php");

    // Vérifié si l'utilisateur est connecté
    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
        exit();
    }

    // Vérifié si l'utilisateur est un visiteur ou un comptable
    if ($_SESSION["user_role"] != "Visiteur médical" && $_SESSION["user_role"] != "Comptable") {
        header("location: ../index.php");
        exit();
    }

    // Récupération de la date du jour
    $datefrais = date("d");
    $mois = date("m");
    $annee = date("Y");

    // Date de création des fiches de frais
    if($datefrais == 16) {
        // Écriture de la requête SQL
        $selectSQL = "SELECT id_utilisateur FROM utilisateurs";

        // Envoie de la requête, stockage dans $result
        $result = $db->query($selectSQL);

        // Effectuer le traitement sur chaque utilisateur
        foreach ($result as $ligne) {
            // Récupération de l'id de l'utilisateur
            $id_utilisateur = $ligne["id_utilisateur"];

            // Écriture de la requête SQL
            $insertSQL = "INSERT INTO fiche_frais (id_utilisateur, mois, annee, id_etat) VALUES ($id_utilisateur, $mois, $annee, 1)";

            // Envoie de la requête
            $db->exec($insertSQL);
        }
    }
    exit();
?>