<?php
session_start();

if (!isset($_SESSION["emailUtilisateur"])) {
    header("location: ../index.php");
}

if ($_SESSION["roleUtilisateur"] != "Visiteur médical" && $_SESSION["roleUtilisateur"] != "Comptable") {
    header("location: ../index.php");
}

if (isset($_SESSION["erreurConsultation"])) {
    $erreurConsultation = $_SESSION["erreurConsultation"];
    unset($_SESSION["erreurConsultation"]);
}

$roleUtilisateur = $_SESSION["roleUtilisateur"];
$nomUtilisateur = $_SESSION["nomUtilisateur"];
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
                <div class="user">Compte : <?php echo strtolower($roleUtilisateur); ?></div>
                <a href="formulaire-saisie-frais.php">Création de frais</a>
                <a href="formulaire-consultation-frais.php" style="color: #F5E1A4;">Consultation des frais</a>
                <?php if ($roleUtilisateur == "Comptable") { ?>
                    <a href="formulaire-validation-frais.php">Gestion des frais</a>
                <?php } ?>
            </div>
            <div class="user">Bonjour <?php echo $nomUtilisateur; ?></div>
            <a href="../../src/controllers/deconnexion-utilisateur.php" class="logout">Déconnexion</a>
        </div>
        <div class="content">
            <div id="haut">
                <h1>Création de frais</h1>
            </div>
            <div>
                <form name="formPeriode" method="post" action="../../src/controllers/consultation-frais.php">
                    <h2>Périodes</h2>
                    <label for="titre">Mois / Année :</label>
                    <input type="month" name="dateConsult" class="zone" required>
                    <br>
                    <?php
                    if (!empty($erreurConsultation)) { ?>
                        <label style="color: red; display: block; text-align: center;"><?php echo $erreurConsultation ?></label>
                    <?php } ?>
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
                            <td><label name="repas"></label><?php echo $_SESSION['consultationRepas'] ?? '0' ?></td>
                            <td><label name="nuitee"></label><?php echo $_SESSION['consultationNuits'] ?? '0' ?></td>
                            <td><label name="etape"></label><?php echo $_SESSION['consultationEtapes'] ?? '0' ?></td>
                            <td><label name="km"></label><?php echo $_SESSION['consultationKilometres'] ?? '0' ?></td>
                            <td><label name="situation"></label></td>
                            <td><label name="dateOperation"></label></td>
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
                        if (isset($_SESSION["ConsultationHorsForfait"]) && !empty($_SESSION["ConsultationHorsForfait"])) {
                            foreach ($_SESSION["ConsultationHorsForfait"] as $frais) { ?>
                                <tr>
                                    <td><label name='dateHorsFrais'><?= $frais["date"] ?></label></td>
                                    <td><label name='descriptionHorsFrais'><?php echo $frais["description"]; ?></label></td>
                                    <td><label name='montantHorsFrais'><?php echo $frais["montant"]; ?></label></td>
                                    <td><label name='etatHorsFrais'><?php echo $frais["etat_description"]; ?></label></td>
                                    <td><label name='dateOperationHorsFrais'><?php echo $frais["date_modification"]; ?></label>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </table>
                </form>
            </div>
        </div>
</body>

</html>