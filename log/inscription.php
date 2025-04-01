
<html>
<body>
<?php
		// Appel du script de connexion
		include('connect.php');

		// On récupère dans des variables les données saisies par l'utilisateur 
		$denomination=$_POST["denomination"]; 
		$mel=$_POST["mel"];
		$mdp=$_POST["mot"];

		// Ecriture de la requête d insertion en SQL
		$reqSQL="INSERT INTO utilisateur VALUES ('$denomination','$mdp','$mel')";

		// Exécution de la requête
	    $connexion->exec($reqSQL) or die ("erreur dans la requete sql");
					
		// On affiche le résultat pour le visiteur
		echo( "insertion reussie, vous etes membre de l'association, vous pouvez vous connecter "); 

		// On ferme la connexion
		$connexion=null; 
		?>

<a href = "index.html"> Retour à la page d'accueil </a> 
</body>
</html>
