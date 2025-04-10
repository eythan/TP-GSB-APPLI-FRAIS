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
                <a href="expense-entry.php" style="color: #F5E1A4;">Création de frais</a>
                <a href="consult-expenses.php">Consultation des frais</a>
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
            <div id="content">
                <form name="formPeriode" method="post" action="../../src/controllers/insert-expenses.php">
                    <h2>Périodes</h2>
                    <label>Mois :</label><input type="number" name="FRA_MOIS" class="zone" min="1" max="12" placeholder="Mois" value="<?php echo $mois; ?>" required>
                    <label>Année :</label><input type="number" name="FRA_AN" class="zone" min="2000" max="2100" placeholder="Année" value="<?php echo $annee; ?>" required>
                    <input type="submit" value="Valider la période" class="zone">
                    <br>
                        <?php 
                            if (!empty($errorDate)) {
                                echo '<label for="error" style="color: red; display: block; text-align: center;">'.$errorDate.'</label>';
                            }
                        ?>
                    </br>
                </form>

                <form name="formSaisieFrais" method="post" action="../../src/controllers/insert-expenses.php">
                    <h2>Frais forfaitaires</h2>
                    <label class="titre">Repas :</label><input type="number" name="FRA_REPAS" class="zone" min="0" max="999999" placeholder="Nombre de repas" value="<?php echo isset($_SESSION['repas']) ? $_SESSION['repas'] : '0'; ?>">
                    <label class="titre">Nuitées :</label><input type="number" name="FRA_NUIT" class="zone" min="0" max="999999" placeholder="Nombre de nuitées" value="<?php echo isset($_SESSION['nuit']) ? $_SESSION['nuit'] : '0'; ?>">
                    <label class="titre">Étape :</label><input type="number" name="FRA_ETAP" class="zone" min="0" max="999999" placeholder="Nombre d'étapes" value="<?php echo isset($_SESSION['etape']) ? $_SESSION['etape'] : '0'; ?>">
                    <label class="titre">Km :</label><input type="number" name="FRA_KM" class="zone" min="0" max="999999" placeholder="Distance en km" value="<?php echo isset($_SESSION['kilometre']) ? $_SESSION['kilometre'] : '0'; ?>">
                    <br></br>

                    <h2>Frais supplémentaires</h2>
                    <div id="lignes">
                        <label class="titre">Frais numéro 1</label>
                        <input type="date" name="FRA_AUT_DAT1" class="zone" placeholder="Date">
                        <input type="texte" name="FRA_AUT_LIB1" class="zone" placeholder="Libellé">
                        <input type="number" name="FRA_AUT_MONT1" class="zone" placeholder="Montant">
                        <input type="button" id="but1" value="+" onclick="ajoutLigne(1);" class="zone">
                    </div>
                    <br>
                    <?php 
                        if (!empty($errorMessage)) {
                            echo '<label for="error" style="color: red; display: block; text-align: center;">'.$errorMessage.'</label>';
                        }
                    ?>
                    </br>
                    <input type="reset" class="zone">
                    <input type="submit" class="zone">
                </form>
            </div>
        </div>
        <script src="../js/script.js"></script>
</body>

</html>