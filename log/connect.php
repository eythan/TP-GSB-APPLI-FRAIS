<?php
	
	// Définitions de constantes pour la connexion à MySQL
	define ('SERVEUR', 'localhost');  
	define ('BASE', 'log');
	define ('NOM', 'root');
	define ('MOTPASSE', '');

	// Connection au serveur
	try {
			$connexion = new PDO("mysql:host=".SERVEUR.";dbname=".BASE,NOM,MOTPASSE);
	} catch ( Exception $e ) {
		  die ("\n Connection à ".SERVEUR." impossible :  ".$e->getMessage());  
	}
?>
