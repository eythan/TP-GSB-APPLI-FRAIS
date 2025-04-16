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

// Récupération de la date du jour
$datefrais = date("d");
$mois = date("m");
$annee = date("Y");

// Date de création des fiches de frais
if ($datefrais == 16) {
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