<?php
session_start();

if (!isset($_SESSION["emailUtilisateur"])) {
    header("location: ../index.php");
}

if ($_SESSION["roleUtilisateur"] != "Comptable") {
    header("location: ../index.php");
}

$role = $_SESSION["roleUtilisateur"];
$nomUtilisateur = $_SESSION["nomUtilisateur"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de gestion des frais">
    <title>Gestion des frais</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <img src="../assets/images/logo.png" alt="Logo">
            <div class="menu">
                <div class="user">Compte : <?php echo strtolower($role); ?></div>
                <a href="formulaire-saisie-frais.php">Création de frais</a>
                <a href="formulaire-consultation-frais.php">Consultation des frais</a>
                <?php if ($role == "Comptable") { ?>
                    <a href="formulaire-validation-frais.php" style="color: #F5E1A4;">Gestion des frais</a>
                <?php } ?>
            </div>
            <div class="user">Bonjour <?php echo $nomUtilisateur; ?></div>
            <a href="../../src/controllers/deconnexion-utilisateur.php" class="logout">Déconnexion</a>
        </div>
        <div class="content">

        </div>
        <script src="../js/script.js"></script>
</body>

</html>