<?php
    session_start();

    // Script de connexion BDD
    require("../includes/database.php");

    // Vérifié si l'utilisateur est connecté
    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    // Vérifié si l'utilisateur est un visiteur ou un comptable
    if ($_SESSION["user_role"] != "visiteur" && $_SESSION["user_role"] != "comptable") {
        header("location: ../index.php");
    }

    // Récupération des données du formulaire
    if (isset($_POST["FRA_MOIS"]) && isset($_POST["FRA_AN"])) {
        $mois = $_POST["FRA_MOIS"];
        $annee = $_POST["FRA_AN"];
        $_SESSION["FRA_MOIS"] = $mois; 
        $_SESSION["FRA_AN"] = $annee;
        header("Location: ../../php/formSaisieFrais.php");
    }

    // Récupération des données du formulaire
    $mois = $_SESSION["FRA_MOIS"];
    $annee = $_SESSION["FRA_AN"];
    $repas = $_POST["FRA_REPAS"];
    $nuit = $_POST["FRA_NUIT"];
    $etape = $_POST["FRA_ETAP"];
    $kilometre = $_POST["FRA_KM"];
    $montant_valide = 0;
    $nombre_justificatifs = 5;

    
    // Vérification des données et mise en forme
    if (!preg_match("/^\d{2}$/", $mois) || $mois < 1 || $mois > 12) {
        $_SESSION["errorDate"] = "Le mois doit être compris entre 01 et 12.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    if (!preg_match("/^\d{4}$/", $annee)) {
        $_SESSION["errorDate"] = "Le format de l'année n'est pas correct.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    if (!is_numeric($repas) || $repas < 0) {
        $_SESSION["errorMessage"] = "Le nombre de repas doit être positif.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    if (!is_numeric($nuit) || $nuit < 0) {
        $_SESSION["errorMessage"] = "Le nombre de nuit doit être positif.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    if (!is_numeric($etape) || $etape < 0) {
        $_SESSION["errorMessage"] = "Le nombre d'etape' doit être positif.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    if (!is_numeric($kilometre) || $kilometre < 0) {
        $_SESSION["errorMessage"] = "Le nombre de kilometre doit être positif.";
        header("Location: ../../php/formSaisieFrais.php");
    }

    // Récuperer l'ID de l'utilisateur
    $id_utilisateur = $_SESSION["user_id"];

    // Mettre un 0 devant si besoin
    $mois = str_pad($mois, 2, "0", STR_PAD_LEFT);
    
    // Écriture de la requête SQL
    $selectSQL = "SELECT id_frais, quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

    // Envoie de la requête, stockage dans $result
    $result = $db->query($selectSQL);

    $_SESSION["repas"] = 0;
    $_SESSION["nuit"] = 0;
    $_SESSION["etape"] = 0;
    $_SESSION["kilometre"] = 0;

    while ($ligne = $result->fetch()) {
        $id_frais = $ligne["id_frais"];
        $quantite = $ligne["quantite"];

        if ($id_frais == 1) {
            $_SESSION["repas"] = $quantite;
        } elseif ($id_frais == 2) {
            $_SESSION["nuit"] = $quantite;
        } elseif ($id_frais == 3) {
            $_SESSION["etape"] = $quantite;
        } elseif ($id_frais == 4) {
            $_SESSION["kilometre"] = $quantite;
        }
    }

    // Écriture de la requête SQL
    $selectForfaitSQL = "SELECT id_frais, montant, description FROM frais_forfait";

    // Envoie de la requête, stockage dans $resultForfait
    $resultForfait = $db->query($selectForfaitSQL);

    // Écriture de la requête SQL
    $selectSQL = "SELECT id_utilisateur, mois, annee FROM fiche_frais WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

    // Envoie de la requête, stockage dans $result
    $result = $db->query($selectSQL);

    // Stockage du résultat dans un tableau
    $ligne = $result->fetch();

    // Mettre à jour si la fiche existe
    if (!$ligne) {
        // Écriture de la requête SQL
        $insertSQL = "INSERT INTO fiche_frais (id_utilisateur, mois, annee, id_etat) VALUES ($id_utilisateur, $mois, $annee, 1)";

        // Envoie de la requête
        $db->exec($insertSQL);
    }

    // Effectuer le traitement sur chaque frais forfait
    foreach ($resultForfait as $ligneForfait) {
        // Récupération les données de la table frais_forfait
        $id_frais = $ligneForfait["id_frais"];
        $montant = $ligneForfait["montant"];
        $description = $ligneForfait["description"];

        // Vérifier la description et ajouter au montant valide
        if ($description == "repas" && $repas != 0) {
            $montant_valide = $montant_valide + $repas * $montant;
                // Appel de la fonction updateLigneFraisForfait pour ligne_frais_forfait
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $repas);
        } elseif ($description == "nuit" && $nuit != 0) {
            $montant_valide = $montant_valide + $nuit * $montant;
                // Appel de la fonction updateLigneFraisForfait pour ligne_frais_forfait
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $nuit);
        } elseif ($description == "etape" && $etape != 0) {
            $montant_valide = $montant_valide + $etape * $montant;
                // Appel de la fonction updateLigneFraisForfait pour ligne_frais_forfait
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $etape);
        } elseif ($description == "kilométrage" && $kilometre != 0) {
            $montant_valide = $montant_valide + $kilometre * $montant;
                // Appel de la fonction updateLigneFraisForfait pour ligne_frais_forfait
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $kilometre);
        }
    }

    // Appel de la fonction updateFicheFrais
    updateFicheFrais($db, $id_utilisateur, $mois, $annee, $nombre_justificatifs, $montant_valide);

    $_SESSION["errorDate"] = "";
    $_SESSION["errorMessage"] = "";
    header("Location: ../../php/formSaisieFrais.php");

    // Fonction pour mettre les valeur dans la fiche
    function updateFicheFrais($db, $id_utilisateur, $mois, $annee, $nombre_justificatifs, $montant_valide) {
        // Mise à jour de la requête SQL
        $updateSQL = "UPDATE fiche_frais SET nombre_justificatifs = nombre_justificatifs + $nombre_justificatifs, montant_valide = montant_valide + $montant_valide, date_modification = current_timestamp() WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

        // Envoie de la requête
        $db->exec($updateSQL);
    }

    // Fonction pour mettre les valeur dans la ligne_frais_forfait
    function updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $quantite) {
        // Écriture de la requête SQL
        $selectSQL = "SELECT quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND id_frais = $id_frais";

        // Envoie de la requête, stockage dans $result
        $result = $db->query($selectSQL);

        // Stockage du résultat dans un tableau
        $ligne = $result->fetch();
    
        // Vérifier si il y a déjà des valeurs
        if ($ligne) {
            $updateSQL = "UPDATE ligne_frais_forfait SET quantite = $quantite WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND id_frais = $id_frais";
            $db->exec($updateSQL);
        } else {
            $insertSQL = "INSERT INTO ligne_frais_forfait (id_utilisateur, mois, annee, id_frais, quantite) VALUES ($id_utilisateur, $mois, $annee, $id_frais, $quantite)";
            $db->exec($insertSQL);
        }
    }
?>