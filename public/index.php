<?php
session_start();

if (isset($_SESSION["erreurConnexion"])) {
    $erreurConnexion = $_SESSION["erreurConnexion"];
    unset($_SESSION["erreurConnexion"]);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Page de connexion">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Connexion</h1>
            <br>
            <form method="post" action="../src/controllers/connexion-utilisateur.php">
                <label for="mail">Email</label>

                <input type="text" id="mail" name="mail" class="zone" placeholder="Votre email" required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="zone" placeholder="Votre mot de passe"
                    required>
                <br>
                <?php
                if (!empty($erreurConnexion)) { ?>
                    <label style="color: red; display: block; text-align: center;"><?php echo $erreurConnexion ?></label>
                <?php } ?>
                <br>
                <input type="submit" value="Se connecter" class="zone">
            </form>
            <br>
            <a href="php/formulaire-inscription.php"><button class="bouton">Créez un compte</button></a>
        </div>
    </div>
</body>

</html>