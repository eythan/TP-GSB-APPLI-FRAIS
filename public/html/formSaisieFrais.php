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
                <a href="#">Gestion des frais</a>
            </div>
        </div>
        <div class="content">
            <div id="haut">
                <h1>Création de frais</h1>
            </div>
            <div id="content">
                <form name="formSaisieFrais" method="post" action="../../src/controllers/insertSaisieFrais.php">
                    <h2>Périodes</h2>
                    <label>Mois :</label><input type="text" name="FRA_MOIS" class="zone" placeholder="Mois" required>
                    <label>Année :</label><input type="text" name="FRA_AN" class="zone" placeholder="Année" required>
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
                    <br></br>
                    <input type="reset" class="zone">
                    <input type="submit" class="zone">
                </form>
            </div>
        </div>
        <script src="../js/script.js"></script>
</body>

</html>