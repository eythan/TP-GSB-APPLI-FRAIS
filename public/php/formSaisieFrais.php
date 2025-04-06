<?php
    session_start();

    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    if ($_SESSION["user_role"] != "Visiteur médical" && $_SESSION["user_role"] != "Comptable") {
        header("location: ../index.php");
    }

    if (isset($_SESSION["errorDate"])) {
        $errorDate = $_SESSION["errorDate"];
        unset($_SESSION["errorDate"]);
    }

    if (isset($_SESSION["errorMessage"])) {
        $errorMessage = $_SESSION["errorMessage"];
        unset($_SESSION["errorMessage"]);
    }
    
    $role = $_SESSION["user_role"];
    $username = $_SESSION["username"];

    if (isset($_SESSION["FRA_MOIS"]) && isset($_SESSION["FRA_AN"])) {
        $mois = $_SESSION["FRA_MOIS"];
        $annee = $_SESSION["FRA_AN"];
    } else {
        $mois = date("m");
        $annee = date("Y");
    }
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
                <a href="#">Création de frais</a>
                <a href="#">Consultation des frais</a>
                <?php if ($role == "comptable") { ?>
                    <a href="#">Gestion des frais</a>
                <?php } ?>
            </div>
            <div class="user">Bonjour <?php echo $username; ?></div>
            <a href="../../src/controllers/deconnexion.php" class="logout">Déconnexion</a>
        </div>
        <div class="content">
            <div id="haut">
                <h1>Création de frais</h1>
            </div>
            <div id="content">
                <form name="formPeriode" method="post" action="../../src/controllers/insertSaisieFrais.php">
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

                <form name="formSaisieFrais" method="post" action="../../src/controllers/insertSaisieFrais.php">
                    <h2>Frais forfaitaires</h2>
                    <label class="titre">Repas :</label><input type="number" name="FRA_REPAS" class="zone" min="0" max="999999" placeholder="Nombre de repas" value="<?php echo isset($_SESSION['repas']) ? $_SESSION['repas'] : '0'; ?>">
                    <label class="titre">Nuitées :</label><input type="number" name="FRA_NUIT" class="zone" min="0" max="999999" placeholder="Nombre de nuitées" value="<?php echo isset($_SESSION['nuit']) ? $_SESSION['nuit'] : '0'; ?>">
                    <label class="titre">Étape :</label><input type="number" name="FRA_ETAP" class="zone" min="0" max="999999" placeholder="Nombre d'étapes" value="<?php echo isset($_SESSION['etape']) ? $_SESSION['etape'] : '0'; ?>">
                    <label class="titre">Km :</label><input type="number" name="FRA_KM" class="zone" min="0" max="999999" placeholder="Distance en km" value="<?php echo isset($_SESSION['kilometre']) ? $_SESSION['kilometre'] : '0'; ?>">
                    <br></br>

                    <h2>Frais supplémentaires</h2>
                    <div id="lignes">
                        <?php
                        if (isset($_SESSION["frais_hors_forfait"])) {
                            $frais_hors_forfait = $_SESSION["frais_hors_forfait"];
                            foreach ($frais_hors_forfait as $i => $frais) {
                                echo '<div id="lignes">';
                                echo '<label class="titre">Frais numéro ' . ($i + 1) . '</label>';
                                echo '<input type="date" name="FRA_AUT_DAT' . ($i + 1) . '" class="zone" value="' . $frais['date_frais'] . '">';
                                echo '<input type="text" name="FRA_AUT_LIB' . ($i + 1) . '" class="zone" value="' . $frais['description'] . '">';
                                echo '<input type="number" name="FRA_AUT_MONT' . ($i + 1) . '" class="zone" value="' . $frais['montant'] . '">';
                                echo '</div>';
                            }
                        } else {
                            echo '<div id="lignes">';
                            echo '<label class="titre">Frais numéro 1</label>';
                            echo '<input type="date" name="FRA_AUT_DAT1" class="zone" placeholder="Date">';
                            echo '<input type="text" name="FRA_AUT_LIB1" class="zone" placeholder="Libellé">';
                            echo '<input type="number" name="FRA_AUT_MONT1" class="zone" placeholder="Montant">';
                            echo '</div>';
                        }
                        ?>
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