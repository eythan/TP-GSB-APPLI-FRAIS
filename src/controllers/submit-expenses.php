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

    if (isset($_POST["dateConsult"])) {
        $date = $_POST["dateConsult"];
        $id_utilisateur = $_SESSION["user_id"];
        
        list($annee, $mois) = explode("-", $date);

        // Récupération des frais forfaitaires de l'utilisateur
        $selectSQL = "SELECT id_frais, quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
        $result = $db->query($selectSQL);
            
        $_SESSION["consult_repas"] = $_SESSION["consult_nuit"] = $_SESSION["consult_etape"] = $_SESSION["consult_kilometre"] = 0;
        
        // Remplissage des valeurs des frais forfaitaires
        while ($ligne = $result->fetch()) {
            if ($ligne["id_frais"] == 1) {
                $_SESSION["consult_repas"] = $ligne["quantite"];
            } elseif ($ligne["id_frais"] == 2) {
                $_SESSION["consult_nuit"] = $ligne["quantite"];
            } elseif ($ligne["id_frais"] == 3) {
                $_SESSION["consult_etape"] = $ligne["quantite"];
            } elseif ($ligne["id_frais"] == 4) {
                $_SESSION["consult_kilometre"] = $ligne["quantite"];
            }
        }

        // Récuperation des frais hors forfait
        $selectSQL = "SELECT ligne_frais_hors_forfait.date_frais, ligne_frais_hors_forfait.description, ligne_frais_hors_forfait.montant, etat.description AS etat_description, fiche_frais.date_modification FROM ligne_frais_hors_forfait, etat, fiche_frais WHERE ligne_frais_hors_forfait.id_utilisateur = fiche_frais.id_utilisateur AND fiche_frais.id_etat = etat.id_etat AND ligne_frais_hors_forfait.id_utilisateur = $id_utilisateur AND fiche_frais.mois = $mois AND fiche_frais.annee = $annee";
        $result = $db->query($selectSQL);

        $_SESSION["consult_hors_forfait"] = [];

        foreach ($result as $ligne) {
            $_SESSION["consult_hors_forfait"][] = [
                "date" => $ligne["date_frais"],
                "description" => $ligne["description"],
                "montant" => $ligne["montant"],
                "etat" => $ligne["etat_description"],
                "date_modification" => $ligne["date_modification"]
            ];
        }
    }

    // Redirection vers le formulaire
    header("Location: ../../php/consult-expenses.php");
    exit();
?>
