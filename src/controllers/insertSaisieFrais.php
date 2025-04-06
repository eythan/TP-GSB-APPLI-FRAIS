<?php
    session_start();

    // Script de connexion BDD
    require("../includes/database.php");

    // Vérifié si l'utilisateur est connecté
    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    // Vérifié si l'utilisateur est un visiteur ou un comptable
    if ($_SESSION["user_role"] != "Visiteur médical" && $_SESSION["user_role"] != "Comptable") {
        header("location: ../index.php");
    }

    // Récupération des données du formulaire
    if (isset($_POST["FRA_MOIS"]) && isset($_POST["FRA_AN"])) {
        $mois = $_POST["FRA_MOIS"];
        $annee = $_POST["FRA_AN"];
        $_SESSION["FRA_MOIS"] = $mois; 
        $_SESSION["FRA_AN"] = $annee;

        // Mettre un 0 devant si besoin
        $mois = str_pad($mois, 2, "0", STR_PAD_LEFT);

        // Vérification des données et mise en forme
        if (!preg_match("/^\d{2}$/", $mois) || $mois < 1 || $mois > 12) {
            $_SESSION["errorDate"] = "Le mois doit être compris entre 01 et 12.";
            header("Location: ../../php/formSaisieFrais.php");
        }
    
        if (!preg_match("/^\d{4}$/", $annee)) {
            $_SESSION["errorDate"] = "Le format de l'année n'est pas correct.";
            header("Location: ../../php/formSaisieFrais.php");
        }

        // Récuperer l'ID de l'utilisateur
        $id_utilisateur = $_SESSION["user_id"];
    
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

        $selectHorsForfaitSQL = "SELECT id_hors_forfait, date_frais, montant, description FROM ligne_frais_hors_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
        $result = $db->query($selectHorsForfaitSQL);

        while ($ligne = $result->fetch()) {
            $_SESSION["frais_hors_forfait"][] = $ligne;
        }

        header("Location: ../../php/formSaisieFrais.php");
        exit();
    }

    if (isset($_POST["FRA_REPAS"]) && isset($_POST["FRA_NUIT"]) && isset($_POST["FRA_ETAP"]) && isset($_POST["FRA_KM"])) {
        // Récupération des données du formulaire
        $mois = $_SESSION["FRA_MOIS"];
        $annee = $_SESSION["FRA_AN"];
        $repas = $_POST["FRA_REPAS"];
        $nuit = $_POST["FRA_NUIT"];
        $etape = $_POST["FRA_ETAP"];
        $kilometre = $_POST["FRA_KM"];

        // Récuperer l'ID de l'utilisateur
        $id_utilisateur = $_SESSION["user_id"];

        // Mettre un 0 devant si besoin
        $mois = str_pad($mois, 2, "0", STR_PAD_LEFT);
        
        // Vérification des données et mise en forme
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

            // Vérifier la description et ajouter les ligne frais forfait
            if ($description == "repas" && $repas != 0) {
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $repas);
            } elseif ($description == "nuit" && $nuit != 0) {
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $nuit);
            } elseif ($description == "etape" && $etape != 0) {
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $etape);
            } elseif ($description == "kilométrage" && $kilometre != 0) {
                updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $kilometre);
            }
        }

        $i = 1;
        while (isset($_POST["FRA_AUT_DAT".$i]) && isset($_POST["FRA_AUT_LIB".$i]) && isset($_POST["FRA_AUT_MONT".$i])) {
            $date = $_POST["FRA_AUT_DAT".$i];
            $libelle = $_POST["FRA_AUT_LIB".$i];
            $montant = $_POST["FRA_AUT_MONT".$i];
        
            if (empty($date) || empty($libelle) || empty($montant)) {
                $_SESSION["errorMessage"] = "Tous les champs des frais hors forfait doivent être renseignés.";
                header("Location: ../../php/formSaisieFrais.php");
                exit();
            }
        
            if (!is_numeric($montant) || $montant < 0) {
                $_SESSION["errorMessage"] = "Le montant doit être un nombre positif.";
                header("Location: ../../php/formSaisieFrais.php");
                exit();
            }
        
            if (!(DateTime::createFromFormat('Y-m-d', $date) && DateTime::createFromFormat('Y-m-d', $date)->format('Y-m-d') === $date)) {
                $_SESSION["errorMessage"] = "La date d'engagement doit être valide.";
                header("Location: ../../php/formSaisieFrais.php");
                exit();
            }
        
            if (strtotime($date) < strtotime('-1 year')) {
                $_SESSION["errorMessage"] = "La date d'engagement doit se situer dans l’année écoulée.";
                header("Location: ../../php/formSaisieFrais.php");
                exit();
            }
        
            updateLigneFraisHorsForfait($db, $id_utilisateur, $mois, $annee, $date, $libelle, $montant);
        
            $i++;
        }

        $updateSQL = "UPDATE fiche_frais SET date_modification = current_timestamp() WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";

        // Envoie de la requête
        $db->exec($updateSQL);


        $_SESSION["errorDate"] = "";
        $_SESSION["errorMessage"] = "";
        $_SESSION["repas"] = $repas;
        $_SESSION["nuit"] = $nuit;
        $_SESSION["etape"] = $etape;
        $_SESSION["kilometre"] = $kilometre;
        header("Location: ../../php/formSaisieFrais.php");
        exit();
    }

    // Fonction pour mettre les valeur dans la ligne_frais_hors_forfait
    function updateLigneFraisHorsForfait($db, $id_utilisateur, $mois, $annee, $date, $libelle, $montant) {
        // Écriture de la requête SQL
        $selectSQL = "SELECT id_hors_forfait FROM ligne_frais_hors_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND description = '$libelle' AND montant = $montant";
    
        // Envoie de la requête, stockage dans $result
        $result = $db->query($selectSQL);
    
        // Stockage du résultat dans un tableau
        $ligne = $result->fetch();
    
        // Vérifier si il y a déjà des valeurs
        if ($ligne) {
            $updateSQL = "UPDATE ligne_frais_hors_forfait SET montant = $montant, description = '$libelle' WHERE id_hors_forfait = ".$ligne['id_hors_forfait'];
            $db->exec($updateSQL);
        } else {
            $insertSQL = "INSERT INTO ligne_frais_hors_forfait (id_utilisateur, mois, annee, date_frais, montant, description) VALUES ($id_utilisateur, $mois, $annee, '$date', $montant, '$libelle')";
            $db->exec($insertSQL);
        }
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