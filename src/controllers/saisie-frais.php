<?php
session_start();
require("../includes/database.inc.php");

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

// Fonction pour mettre les valeur dans la ligne_frais_forfait
function updateLigneFraisForfait($db, $id_utilisateur, $mois, $annee, $id_frais, $quantite)
{
    $selectSQL = "SELECT quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND id_frais = $id_frais";
    $result = $db->query($selectSQL);
    $ligne = $result->fetch();

    if ($ligne) {
        // Mise à jour de la ligne
        $updateSQL = "UPDATE ligne_frais_forfait SET quantite = $quantite WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND id_frais = $id_frais";
        $db->exec($updateSQL);
    } else {
        // Insertion d'une nouvelle ligne
        $insertSQL = "INSERT INTO ligne_frais_forfait (id_utilisateur, mois, annee, id_frais, quantite) VALUES ($id_utilisateur, $mois, $annee, $id_frais, $quantite)";
        $db->exec($insertSQL);
    }
}

// Fonction pour mettre les valeur dans la ligne_frais_hors_forfait
function updateLigneFraisHorsForfait($db, $id_utilisateur, $mois, $annee, $date, $libelle, $montant)
{
    $selectSQL = "SELECT id_hors_forfait FROM ligne_frais_hors_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee AND description = '$libelle' AND montant = $montant";
    $result = $db->query($selectSQL);
    $ligne = $result->fetch();

    if ($ligne) {
        // Mise à jour de la ligne
        $updateSQL = "UPDATE ligne_frais_hors_forfait SET montant = $montant, description = '$libelle' WHERE id_hors_forfait = " . $ligne['id_hors_forfait'];
        $db->exec($updateSQL);
    } else {
        // Insertion d'une nouvelle ligne
        $insertSQL = "INSERT INTO ligne_frais_hors_forfait (id_utilisateur, mois, annee, date_frais, montant, description) VALUES ($id_utilisateur, $mois, $annee, '$date', $montant, '$libelle')";
        $db->exec($insertSQL);
    }
}

// Récupération du formulaire de période
if (isset($_POST["FRA_MOIS"]) && isset($_POST["FRA_AN"])) {
    $mois = str_pad($_POST["FRA_MOIS"], 2, "0", STR_PAD_LEFT);
    $annee = $_POST["FRA_AN"];

    if (!preg_match("/^\d{2}$/", $mois) || $mois < 1 || $mois > 12) {
        $_SESSION["errorDate"] = "Le mois doit être compris entre 01 et 12.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (!preg_match("/^\d{4}$/", $annee)) {
        $_SESSION["errorDate"] = "Le format de l'année n'est pas correct.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    $_SESSION["FRA_MOIS"] = $mois;
    $_SESSION["FRA_AN"] = $annee;

    $id_utilisateur = $_SESSION["user_id"];

    // Récupération des frais forfaitaires de l'utilisateur
    $selectSQL = "SELECT id_frais, quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
    $result = $db->query($selectSQL);

    $_SESSION["repas"] = $_SESSION["nuit"] = $_SESSION["etape"] = $_SESSION["kilometre"] = 0;

    // Remplissage des valeurs des frais forfaitaires
    while ($ligne = $result->fetch()) {
        if ($ligne["id_frais"] == 1) {
            $_SESSION["repas"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 2) {
            $_SESSION["nuit"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 3) {
            $_SESSION["etape"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 4) {
            $_SESSION["kilometre"] = $ligne["quantite"];
        }
    }

    // Récupération des frais hors forfait de l'utilisateur
    $selectHorsForfaitSQL = "SELECT date_frais, description, montant FROM ligne_frais_hors_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
    $result = $db->query($selectHorsForfaitSQL);

    $_SESSION["frais_hors_forfait"] = [];

    // Remplissage des valeurs des frais hors forfait dans un tableaux
    while ($ligne = $result->fetch()) {
        $_SESSION["frais_hors_forfait"][] = [
            "date" => $ligne["date_frais"],
            "libelle" => $ligne["description"],
            "montant" => $ligne["montant"]
        ];
    }

    header("Location: ../../php/formulaire-saisie-frais.php");
    exit();
}

// Récupération du formulaire de frais
if (isset($_POST["FRA_REPAS"]) && isset($_POST["FRA_NUIT"]) && isset($_POST["FRA_ETAP"]) && isset($_POST["FRA_KM"])) {
    $id_utilisateur = $_SESSION["user_id"];
    $mois = $_SESSION["FRA_MOIS"] ?? date('m');
    $annee = $_SESSION["FRA_AN"] ?? date('Y');
    $repas = $_POST["FRA_REPAS"];
    $nuit = $_POST["FRA_NUIT"];
    $etape = $_POST["FRA_ETAP"];
    $kilometre = $_POST["FRA_KM"];

    if (!is_numeric($repas) || $repas < 0) {
        $_SESSION["errorForfait"] = "Le nombre de repas doit être positif.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (!is_numeric($nuit) || $nuit < 0) {
        $_SESSION["errorForfait"] = "Le nombre de nuit doit être positif.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (!is_numeric($etape) || $etape < 0) {
        $_SESSION["errorForfait"] = "Le nombre d'etape' doit être positif.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (!is_numeric($kilometre) || $kilometre < 0) {
        $_SESSION["errorForfait"] = "Le nombre de kilometre doit être positif.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    $selectForfaitSQL = "SELECT id_frais, montant, description FROM frais_forfait";
    $resultForfait = $db->query($selectForfaitSQL);

    $selectSQL = "SELECT id_utilisateur, mois, annee FROM fiche_frais WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
    $result = $db->query($selectSQL);
    $ligne = $result->fetch();

    if (!$ligne) {
        $insertSQL = "INSERT INTO fiche_frais (id_utilisateur, mois, annee, id_etat) VALUES ($id_utilisateur, $mois, $annee, 1)";
        $db->exec($insertSQL);
    }

    // Mise à jour des frais forfaitaires
    foreach ($resultForfait as $ligneForfait) {
        $id_frais = $ligneForfait["id_frais"];
        $description = $ligneForfait["description"];

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
}

// Traitement des frais hors forfait
$i = 1;
while (isset($_POST["FRA_AUT_DAT" . $i]) && isset($_POST["FRA_AUT_LIB" . $i]) && isset($_POST["FRA_AUT_MONT" . $i])) {
    $id_utilisateur = $_SESSION["user_id"];
    $mois = $_SESSION["FRA_MOIS"] ?? date('m');
    $annee = $_SESSION["FRA_AN"] ?? date('Y');
    $date = $_POST["FRA_AUT_DAT" . $i];
    $libelle = $_POST["FRA_AUT_LIB" . $i];
    $montant = $_POST["FRA_AUT_MONT" . $i];

    if (empty($date) || empty($libelle) || empty($montant)) {
        $_SESSION["errorHorsForfait"] = "Tous les champs des frais hors forfait doivent être renseignés.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }


    if (!is_numeric($montant) || $montant < 0) {
        $_SESSION["errorHorsForfait"] = "Le montant doit être un nombre positif.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (!(DateTime::createFromFormat('Y-m-d', $date) && DateTime::createFromFormat('Y-m-d', $date)->format('Y-m-d') === $date)) {
        $_SESSION["errorHorsForfait"] = "La date d'engagement doit être valide.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    if (strtotime($date) < strtotime('-1 year')) {
        $_SESSION["errorHorsForfait"] = "La date d'engagement doit se situer dans l’année écoulée.";
        header("Location: ../../php/formulaire-saisie-frais.php");
        exit();
    }

    $selectSQL = "SELECT id_utilisateur, mois, annee FROM fiche_frais WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
    $result = $db->query($selectSQL);
    $ligne = $result->fetch();

    if (!$ligne) {
        $insertSQL = "INSERT INTO fiche_frais (id_utilisateur, mois, annee, id_etat) VALUES ($id_utilisateur, $mois, $annee, 1)";
        $db->exec($insertSQL);
    }

    updateLigneFraisHorsForfait($db, $id_utilisateur, $mois, $annee, $date, $libelle, $montant);
    $i++;
}

// Mettre à jour la date de modification
$updateSQL = "UPDATE fiche_frais SET date_modification = current_timestamp() WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
$db->exec($updateSQL);

// Réinitialisation des session
$_SESSION["errorDate"] = "";
$_SESSION["errorForfait"] = "";
$_SESSION["errorHorsForfait"] = "";
$_SESSION["repas"] = $repas;
$_SESSION["nuit"] = $nuit;
$_SESSION["etape"] = $etape;
$_SESSION["kilometre"] = $kilometre;

// Redirection vers le formulaire
header("Location: ../../php/formulaire-saisie-frais.php");
exit();
