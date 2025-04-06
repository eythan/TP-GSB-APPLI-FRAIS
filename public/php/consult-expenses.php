<?php
    session_start();

    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    if ($_SESSION["user_role"] != "Visiteur médical" && $_SESSION["user_role"] != "Comptable") {
        header("location: ../index.php");
    }

    $errorDate = $_SESSION["errorDate"] ?? "";
    $errorMessage = $_SESSION["errorMessage"] ?? "";
    unset($_SESSION["errorDate"], $_SESSION["errorMessage"]);
    
    
    $role = $_SESSION["user_role"];
    $username = $_SESSION["username"];
    $mois = $_SESSION["FRA_MOIS"] ?? date("m");
    $annee = $_SESSION["FRA_AN"] ?? date("Y");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <img src="../assets/images/logo.png" alt="Logo">
            <div class="menu">
            <div class="user">Compte : <?php echo strtolower($role); ?></div>
                <a href="expense-entry.php">Création de frais</a>
                <a href="consult-expenses.php" style="color: #F5E1A4;">Consultation des frais</a>
                <?php if ($role == "Comptable") { ?>
                    <a href="manage-expenses.php">Gestion des frais</a>
                <?php } ?>
            </div>
            <div class="user">Bonjour <?php echo $username; ?></div>
            <a href="../../src/controllers/logout.php" class="logout">Déconnexion</a>
        </div>
        <div class="content">

        </div>
        <script src="../js/script.js"></script>
</body>

</html>