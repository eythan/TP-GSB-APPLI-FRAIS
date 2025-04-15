<?php
    session_start();
    
    if (isset($_SESSION["errorMessage"])) {
        $errorMessage = $_SESSION["errorMessage"];
        unset($_SESSION["errorMessage"]);
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
            <form method="post" action="../src/controllers/check-login.php">
                <label for="mail">Email</label>

                <input type="text" id="mail" name="mail" class="zone" placeholder="Votre email" required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="zone" placeholder="Votre mot de passe" required>

                <br>
                <?php 
                    if (!empty($errorMessage)) {
                        echo '<label for="error" style="color: red;">'.$errorMessage.'</label>';
                    }
                ?>
                <br>
                <input type="submit" value="Se connecter" class="zone">
            </form>
            <br>
            <a href="php/registration.php"><button class="bouton">Créez un compte</button></a>
        </div>
    </div>
</body>

</html>