<?php
    if ($_POST) {
        $mail=$_POST["mail"];

        $password=$_POST["password"];

        $lastname=$_POST["lastname"];

        $firstname=$_POST["firstname"];

        $address=$_POST["address"];

        $postal=$_POST["postal"];

        $city=$_POST["city"];

        header('Location: ../html/formSaisieFrais.php');
    }
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Inscription</h1>
            <br>
            <form method="post" action="">

                <label for="mail">Email</label>

                <input type="text" id="mail" name="mail" class="zone" placeholder="Votre email" required>

                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" class="zone" placeholder="Votre mot de passe" required>

                <label for="lastname">Nom</label>

                <input type="text" id="lastname" name="lastname" class="zone" placeholder="Votre nom" required>

                <label for="firstname">Prenom</label>

                <input type="text" id="firstname" name="firstname" class="zone" placeholder="Votre prénom" required>

                <label for="address">Adresse</label>

                <input type="text" id="address" name="address" class="zone" placeholder="Votre adresse" required>

                <label for="postal">Code Postal</label>

                <input type="text" id="postal" name="postal" class="zone" placeholder="Votre code postal" required>

                <label for="city">Ville</label>

                <input type="text" id="city" name="city" class="zone" placeholder="Votre ville" required>

                <br><br>
                <input type="submit" value="Créer un compte" class="zone">
            </form>
            <br>
            <a href="../index.php"><button class="bouton">Se connecter</button></a>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>