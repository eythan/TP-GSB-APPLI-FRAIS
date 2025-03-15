<?php
    // Script de connexion BDD
    include("../includes/database.php");

    // Récupération des données du formulaire
    $id_utilisateur = 1; ////// A MODIFIER ////// avec les cookie de l'utilisateur connecté
    $mois = $_POST["FRA_MOIS"];
    $annee = $_POST["FRA_AN"];
    $repas = $_POST["FRA_REPAS"];
    $nuit = $_POST["FRA_NUIT"];
    $etape = $_POST["FRA_ETAP"];
    $kilometre = $_POST["FRA_KM"];
    $montant_valide = 0;

    // Calcul du nombre de justificatifs
    $nombre_justificatifs = $repas + $nuit + $etape;

    // Écriture de la requête SQL
    $selectForfaitSQL = "SELECT id_frais, montant, description FROM frais_forfait";

    // Envoie de la requête, stockage dans $resultForfait
    $resultForfait = $db->query($selectForfaitSQL);

    // Effectuer le traitement sur chaque frais forfait
    foreach ($resultForfait as $ligneForfait) {
        // // Récupération les données de la table frais_forfait
        $id_frais = $ligneForfait['id_frais'];
        $montant = $ligneForfait['montant'];
        $description = $ligneForfait['description'];

        // Vérifier la description et ajouter au montant valide
        if ($description == "repas") {
            $montant_valide = $montant_valide + $repas * $montant;
        } elseif ($description == "nuit") {
            $montant_valide = $montant_valide + $nuit * $montant;
        } elseif ($description == "etape") {
            $montant_valide = $montant_valide + $etape * $montant;
        } elseif ($description == "kilométrage") {
            $montant_valide = $montant_valide + $kilometre * $montant;
        }
    }

    // Écriture de la requête SQL
    $selectSQL = "SELECT id_utilisateur, mois, annee FROM fiche_frais WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

    // Envoie de la requête, stockage dans $result
    $result = $db->query($selectSQL);

    // Stockage du résultat dans un tableau
    $ligne = $result->fetch();

    // Boolean de fiche existante 
    $existe = $ligne !== false;

    // Mettre à jour si la fiche existe
    if ($existe) {
        // Appel de la fonction updateFicheFrais
        updateFicheFrais($db, $id_utilisateur, $mois, $annee, $nombre_justificatifs, $montant_valide);
    } else {
        // Écriture de la requête SQL
        $insertSQL = "INSERT INTO fiche_frais (id_utilisateur, mois, annee, id_etat) VALUES ($id_utilisateur, $mois, $annee, 1)";

        // Envoie de la requête
        $db->exec($insertSQL);
        
        // Appel de la fonction updateFicheFrais
        updateFicheFrais($db, $id_utilisateur, $mois, $annee, $nombre_justificatifs, $montant_valide);
    }

    // Function pour mettre les valeur dans la fiche
    function updateFicheFrais($db, $id_utilisateur, $mois, $annee, $nombre_justificatifs, $montant_valide) {
        // Mise à jour de la requête SQL
        $updateSQL = "UPDATE fiche_frais SET nombre_justificatifs = nombre_justificatifs + $nombre_justificatifs, montant_valide = montant_valide + $montant_valide, date_modification = current_timestamp() WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

        // Envoie de la requête
        $db->exec($updateSQL);
    }
?>