<?php
    session_start();

    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    if ($_SESSION["user_role"] != "Visiteur médical" && $_SESSION["user_role"] != "Comptable") {
        header("location: ../index.php");
    }
    
    $role = $_SESSION["user_role"];
    $username = $_SESSION["username"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de consultation des frais">
    <title>Consultation des frais</title>
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
            <div id="haut">
                <h1>Création de frais</h1>
            </div>
            <div>
            <form name="formPeriode" method="post" action="../../src/controllers/submit-expenses.php">
                <h2>Périodes</h2>
                <label for="titre">Mois / Année :</label>
                <input type="month" name="dateConsult" class="zone" required>
                <br>
                <input type="submit" value="Valider la période" class="zone">
                <h2>Frais au forfait</h2>
                <table>
                    <tr>
                        <th>Repas midi</th>
                        <th>Nuitée</th>
                        <th>Etape</th>
                        <th>Km</th>
                        <th>Situation</th>
                        <th>Date opération</th>
                        <th>Remboursement</th>
                    </tr>
                    <tr>
                        <td><label name="repas"></label><?php echo isset($_SESSION['consult_repas']) ? $_SESSION['consult_repas'] : '0'; ?></td>
                        <td><label name="nuitee"></label><?php echo isset($_SESSION['consult_nuit']) ? $_SESSION['consult_nuit'] : '0'; ?></td>
                        <td><label name="etape"></label><?php echo isset($_SESSION['consult_etape']) ? $_SESSION['consult_etape'] : '0'; ?></td>
                        <td><label name="km"></label><?php echo isset($_SESSION['consult_kilometre']) ? $_SESSION['consult_kilometre'] : '0'; ?></td>
                        <td><label name="situation"></label></td>
                        <td><label name="dateOper"></label></td>
                        <td><label name="remboursement"></label></td>
                    </tr>
                </table>
                <br>
                <h2>Hors Forfait</h2>

                <table>
                    <tr>
                        <th>Date</th>
                        <th>Libellé</th>
                        <th>Montant</th>
                        <th>Situation</th>
                        <th>Date opération</th>
                    </tr>
                    <?php
                        if (isset($_SESSION["consult_hors_forfait"]) && !empty($_SESSION["consult_hors_forfait"])) {
                            foreach ($_SESSION["consult_hors_forfait"] as $frais) {
                                echo "<tr>
                                    <td><label name='hfDate1'>".$frais["date"]."</label></td>
                                    <td><label name='hfLib1'>".$frais["description"]."</label></td>
                                    <td><label name='hfMont1'>".$frais["montant"]."</label></td>
                                    <td><label name='hfSitu1'>".$frais["etat"]."</label></td>
                                    <td><label name='hfDateOper1'>".$frais["date_modification"]."</label></td>
                                </tr>";
                            }
                        }
                    ?>
                </table>

            </form>
            </div>
        </div>
        <script src="../js/script.js"></script>
</body>

</html>