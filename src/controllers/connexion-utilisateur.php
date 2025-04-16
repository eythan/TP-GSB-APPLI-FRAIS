<?php
session_start();
require_once("../includes/database.inc.php");

$mail = $_POST["mail"];
$password = $_POST["password"];

if (empty($mail) || empty($password)) {
    $_SESSION["erreurConnexion"] = "Il y a un champ vide";
    header("Location: ../../index.php");
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erreurConnexion"] = "Email invalide";
    header("Location: ../../index.php");
}

// Hachage du mot de passe
$securePassword = hash("sha256", $password);

$selectSQL = "SELECT * FROM utilisateurs WHERE email = '$mail' AND mot_de_passe = '$securePassword'";
$result = $db->query($selectSQL);
$ligne = $result->fetch();

if ($ligne) {
    $_SESSION["emailUtilisateur"] = $ligne["email"];
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