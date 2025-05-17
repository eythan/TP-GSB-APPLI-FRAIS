<?php
session_start();
require_once("../includes/database.inc.php");

// Vérifié si l'utilisateur est connecté
if (!isset($_SESSION["emailUtilisateur"])) {
    header("location: ../index.php");
    exit();
}

// Vérifié si l'utilisateur est un visiteur ou un comptable
if ($_SESSION["roleUtilisateur"] != "Visiteur médical" && $_SESSION["roleUtilisateur"] != "Comptable") {
    header("location: ../index.php");
    exit();
}

if (isset($_POST["dateConsult"])) {
    $date = $_POST["dateConsult"];
    $id_utilisateur = $_SESSION["idUtilisateur"];

    list($annee, $mois) = explode("-", $date);

    if (!preg_match("/^\d{2}$/", $mois) || $mois < 1 || $mois > 12) {
        $_SESSION["erreurConsultation"] = "Le mois doit être compris entre 01 et 12.";
        header("Location: ../../php/formulaire-consultation-frais.php");
        exit();
    }

    if (!preg_match("/^\d{4}$/", $annee)) {
        $_SESSION["erreurConsultation"] = "Le format de l'année n'est pas correct.";
        header("Location: ../../php/formulaire-consultation-frais.php");
        exit();
    }

    // Récupération des frais forfaitaires de l'utilisateur
    $selectSQL = "SELECT id_frais, quantite FROM ligne_frais_forfait WHERE id_utilisateur = $id_utilisateur AND mois = $mois AND annee = $annee";
    $result = $db->query($selectSQL);

    $_SESSION["consultationRepas"] = $_SESSION["consultationNuits"] = $_SESSION["consultationEtapes"] = $_SESSION["consultationKilometres"] = 0;

    // Remplissage des valeurs des frais forfaitaires
    while ($ligne = $result->fetch()) {
        if ($ligne["id_frais"] == 1) {
            $_SESSION["consultationRepas"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 2) {
            $_SESSION["consultationNuits"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 3) {
            $_SESSION["consultationEtapes"] = $ligne["quantite"];
        } elseif ($ligne["id_frais"] == 4) {
            $_SESSION["consultationKilometres"] = $ligne["quantite"];
        }
    }

    // Récuperation des frais hors forfait
    $selectSQL = "SELECT ligne_frais_hors_forfait.date_frais, ligne_frais_hors_forfait.description, ligne_frais_hors_forfait.montant, etat.description AS etat_description, fiche_frais.date_modification FROM ligne_frais_hors_forfait, etat, fiche_frais WHERE ligne_frais_hors_forfait.id_utilisateur = fiche_frais.id_utilisateur AND fiche_frais.id_etat = etat.id_etat AND ligne_frais_hors_forfait.mois = fiche_frais.mois AND ligne_frais_hors_forfait.annee = fiche_frais.annee AND ligne_frais_hors_forfait.id_utilisateur = $id_utilisateur AND fiche_frais.mois = $mois AND fiche_frais.annee = $annee";
    $result = $db->query($selectSQL);

    $_SESSION["ConsultationHorsForfait"] = [];

    foreach ($result as $ligne) {
        $_SESSION["ConsultationHorsForfait"][] = [
            "date" => $ligne["date_frais"],
            "description" => $ligne["description"],
            "montant" => $ligne["montant"],
            "etat_description" => $ligne["etat_description"],
            "date_modification" => $ligne["date_modification"]
        ];
    }

    // Récupération de la situation, date, remboursement
    $selectSQL = "SELECT etat.description AS etat_description, fiche_frais.date_modification, fiche_frais.id_etat FROM fiche_frais, etat WHERE fiche_frais.id_etat = etat.id_etat AND fiche_frais.id_utilisateur = $id_utilisateur AND fiche_frais.mois = $mois AND fiche_frais.annee = $annee";
    $result = $db->query($selectSQL);

    $ligne = $result->fetch();
    $_SESSION["situation"] = $ligne["etat_description"];
    $_SESSION["dateOperation"] = $ligne["date_modification"];
    $_SESSION["remboursement"] = $ligne["id_etat"] == 4 ? "Oui" : "Non";
}

// Récupération des visiteurs pour le menu déroulant
$reqSQL = "SELECT id_utilisateur, nom, prenom FROM utilisateurs WHERE role = 'Visiteur médical'";
$result = $db->query($reqSQL);
$_SESSION["visiteurs"] = $result->fetchAll();

// Redirection vers le formulaire
header("Location: ../../php/formulaire-consultation-frais.php");
exit();
?>