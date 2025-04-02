<?php
    if ($_POST) {
        // Script de connexion BDD
        include("../src/includes/database.php");

        // Récupération des données du formulaire
        $mail = $_POST["mail"];
        $password = $_POST["password"];

        // Vérifié les champs vides
        if (empty($mail) || empty($password)) {
            $errorMessage = "Il y a un champ vide";
        // Vérifié le format de l'email
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Email invalide";
        } else {
            // Hachage du mot de passe
            $securePassword = hash('sha256', $password);

            // Écriture de la requête SQL
            $selectSQL = "SELECT * FROM utilisateurs WHERE email = '$mail' AND mot_de_passe = '$securePassword'";

            // Envoie de la requête
            $result = $db->query($selectSQL);

            // Vérifié le résultat dans la base de données
            if ($result->rowCount() == 1) {
                // Connexion reussie
                header("Location: /html/formSaisieFrais.php");
            } else {
                // Erreur de connexion
                $errorMessage = "Vérifier votre email et mot de passe";
            }
        }
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
            <h1>Connexion</h1>
            <br>
            <form method="post" action="">
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
            <a href="html/inscription.php"><button class="bouton">Créez un compte</button></a>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>

</html>

<php>
    
</php>