<?php
session_start();
require_once("../../src/includes/database.inc.php");

$mail = $_POST["mail"];
$password = $_POST["password"];
$lastname = $_POST["lastname"];
$firstname = $_POST["firstname"];
$address = $_POST["address"];
$postal = $_POST["postal"];
$city = $_POST["city"];

if (empty($mail) || empty($password) || empty($lastname) || empty($firstname) || empty($address) || empty($postal) || empty($city)) {
    $_SESSION["erreurInscription"] = "Il y a un champ vide";
    header("Location: ../../php/formulaire-inscription.php");
    exit();
}

if (!preg_match('/^[0-9]{5}$/', $postal)) {
    $_SESSION["erreurInscription"] = "le code postal doit contenir 5 chiffres";
    header("Location: ../../php/formulaire-inscription.php");
    exit();
}

if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["erreurInscription"] = "Format de l'email invalide";
    header("Location: ../../php/formulaire-inscription.php");
    exit();
}

$selectSQL = "SELECT id_utilisateur FROM utilisateurs WHERE email = '$mail'";
$result = $db->query($selectSQL);
$ligne = $result->fetch();

if ($ligne) {
    $_SESSION["erreurInscription"] = "Mail déja utilisé";
    header("Location: ../../php/formulaire-inscription.php");
    exit();
}

// Hachage du mot de passe
$securePassword = hash("sha256", $password);

$insertSQL = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, adresse, code_postal, ville) VALUES ('$lastname', '$firstname', '$mail', '$securePassword', 'Visiteur médical', '$address', '$postal', '$city')";
$db->exec($insertSQL);

$_SESSION["emailUtilisateur"] = $mail;
$_SESSION["roleUtilisateur"] = $role;
$_SESSION["nomUtilisateur"] = $firstname . " " . $lastname;

header("Location: ../../php/formulaire-saisie-frais.php");
exit();
?>