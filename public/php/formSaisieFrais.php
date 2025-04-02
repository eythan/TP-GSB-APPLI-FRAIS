<?php
    session_start();

    if (!isset($_SESSION["user_email"])) {
        header("location: ../index.php");
    }

    if ($_SESSION["user_role"] != "visiteur" && $_SESSION["user_role"] != "comptable") {
        header("location: ../index.php");
        exit();
    }

    if (isset($_SESSION["errorMessage"])) {
        $errorMessage = $_SESSION["errorMessage"];
        unset($_SESSION["errorMessage"]);
    }
    
    $role = $_SESSION["user_role"];
    $username = $_SESSION["username"];
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
                <?php if ($role == "visiteur") { ?>
                    <a href="#">Création de frais</a>
                    <a href="#">Consultation des frais</a>
                <?php } else { ?>
                    <a href="#">Création de frais</a>
                    <a href="#">Consultation des frais</a>
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
                <form name="formSaisieFrais" method="post" action="../../src/controllers/insertSaisieFrais.php">
                    <h2>Périodes</h2>
                    <label>Mois :</label><input type="text" name="FRA_MOIS" class="zone" min="1" max="12" placeholder="Mois" required>
                    <label>Année :</label><input type="number" name="FRA_AN" class="zone" min="2000" max="2100" placeholder="Année" required>
                    <br></br>

                    <h2>Frais forfaitaires</h2>
                    <label class="titre">Repas :</label><input type="number" name="FRA_REPAS" class="zone" min="0" max="999999" placeholder="Nombre de repas">
                    <label class="titre">Nuitées :</label><input type="number" name="FRA_NUIT" class="zone" min="0" max="999999" placeholder="Nombre de nuitées">
                    <label class="titre">Étape :</label><input type="number" name="FRA_ETAP" class="zone" min="0" max="999999" placeholder="Nombre d'étapes">
                    <label class="titre">Km :</label><input type="number" name="FRA_KM" class="zone" min="0" max="999999" placeholder="Distance en km">
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