
<html>
<body>
<?php
		// Appel du script de connexion
		include('connect.php');

		// On r�cup�re dans des variables les donn�es saisies par l'utilisateur 
		$denomination=$_POST["denomination"]; 
		$mel=$_POST["mel"];
		$mdp=$_POST["mot"];

		// Ecriture de la requ�te d insertion en SQL
		$reqSQL="INSERT INTO utilisateur VALUES ('$denomination','$mdp','$mel')";

		// Ex�cution de la requ�te
	    $connexion->exec($reqSQL) or die ("erreur dans la requete sql");
					
		// On affiche le r�sultat pour le visiteur
		echo( "insertion reussie, vous etes membre de l'association, vous pouvez vous connecter "); 

		// On ferme la connexion
		$connexion=null; 
		?>

<a href = "index.html"> Retour � la page d'accueil </a> 
</body>
</html>
