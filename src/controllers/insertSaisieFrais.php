<?php
    // Script de connexion BDD
    include("../includes/database.php");

    // Récupération des données du formulaire
    $id_utilisateur = 1; ////// A MODIFIER ////// avec les cookie de l'utilisateur connecté
    $mois = $_POST["FRA_MOIS"];
    $annee = $_POST["FRA_AN"];
    $repas = !empty($_POST["FRA_REPAS"]) ? $_POST["FRA_REPAS"] : 0; // Mettre 0 si aucune valeur
    $nuit = !empty($_POST["FRA_NUIT"]) ? $_POST["FRA_NUIT"] : 0; // Mettre 0 si aucune valeur
    $etape = !empty($_POST["FRA_ETAP"]) ? $_POST["FRA_ETAP"] : 0; // Mettre 0 si aucune valeur
    $kilometre = !empty($_POST["FRA_KM"]) ? $_POST["FRA_KM"] : 0; // Mettre 0 si aucune valeur
    $montant_valide = 0;

    // Vérification des données et mise en forme
    if (!preg_match("/^\d{2}$/", $mois) || $mois < 1 || $mois > 12) {
        die("Le mois doit être compris entre 01 et 12.");
    }

    if (!preg_match("/^\d{4}$/", $annee)) {
        die("Le format de l'année n'est pas correct.");
    }

    if (!is_numeric($repas) || $repas < 0) {
        die("Le nombre de repas doit être positif.");
    }

    if (!is_numeric($nuit) || $nuit < 0) {
        die("Le nombre de nuit doit être positif.");
    }

    if (!is_numeric($etape) || $etape < 0) {
        die("Le nombre d'etape' doit être positif.");
    }

    if (!is_numeric($kilometre) || $kilometre < 0) {
        die("Le nombre de kilometre doit être positif.");
    }

    // Calcul du nombre de justificatifs
    $nombre_justificatifs = $repas + $nuit + $etape;

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
        // // Récupération les données de la table frais_forfait
        $id_frais = $ligneForfait['id_frais'];
        $montant = $ligneForfait['montant'];
        $description = $ligneForfait['description'];

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
            $updateSQL = "UPDATE ligne_frais_forfait SET quantite = quantite + $quantite WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND id_frais = $id_frais";
            $db->exec($updateSQL);
        } else {
            $insertSQL = "INSERT INTO ligne_frais_forfait (id_utilisateur, mois, annee, id_frais, quantite) VALUES ($id_utilisateur, $mois, $annee, $id_frais, $quantite)";
            $db->exec($insertSQL);
        }
    }
?>