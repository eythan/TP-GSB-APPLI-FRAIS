<?php
session_start();
require_once("../includes/database.inc.php");

$emailUtilisateur = $_POST["emailUtilisateur"];
$motDePasse = $_POST["motDePasse"];

if (empty($emailUtilisateur) || empty($motDePasse)) {
    $_SESSION["erreurConnexion"] = "Il y a un champ vide";
    header("Location: ../../index.php");
    exit();
}

if (!filter_var($emailUtilisateur, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erreurConnexion"] = "Email invalide";
    header("Location: ../../index.php");
    exit();
}

// Hachage du mot de passe
$motDePasseHache = hash("sha256", $motDePasse);

$selectSQL = "SELECT * FROM utilisateurs WHERE email = '$emailUtilisateur' AND mot_de_passe = '$motDePasseHache'";
$result = $db->query($selectSQL);
$ligne = $result->fetch();

if ($ligne) {
    $_SESSION["idUtilisateur"] = $ligne["id_utilisateur"];
    $_SESSION["roleUtilisateur"] = $ligne["role"];
    $_SESSION["nomUtilisateur"] = $ligne["prenom"] . " " . $ligne["nom"];
    header("Location: ../../php/formulaire-saisie-frais.php");
    exit();
} else {
    $_SESSION["erreurConnexion"] = "Vérifier votre email et mot de passe";
    header("Location: ../../index.php");
    exit();
}
?>