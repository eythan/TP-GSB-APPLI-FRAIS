<?php

// Appel du script de connexion au serveur et � la base de donn�es
	require("connect.php");

// On r�cup�re les donn�es saisies dans le formulaire
	$nomSaisi = $_POST["nom"];
	$motPasseSaisi = $_POST["mdp"];

// On r�cup�re dans la base de donn�es le mot de passe qui correspond au nom saisi par le visiteur  
	$reqSQL = "SELECT  motDePasse  FROM utilisateur WHERE nom = '$nomSaisi'"; 
	
	$res = $connexion->query($reqSQL); 
    
	//$parcours du jeu d'enrgistrements : selection du premier enregistrement 
    $ligne = $res->fetch();
	// On affecte la valeur de chaque cellule du tebleau � une variable
		
	$motPasseBdd =$ligne['motDePasse'];

// On v�rifie que le mot de passe saisi est identique � celui enregistr� dans la base de donn�es

	if  ($motPasseSaisi!=$motPasseBdd)
	// Le mot de passe est diff�rent de celui de la base utilisateur
	{
		echo "Votre saisie est erron�e, Recommencez SVP...";

		// On inclut le formulaire d identification (index.html)
		include('index.html');
				
	}
	else
	// Le mot de passe saisi correspond � celui de la base utilisateur
	{
		// Démarrage d'un session
		session_start();

		// Création d'une variable de session
		$_SESSION["ok"] = "oui";

		// Redirection vers la page d'entrée du site
		header("location:entrer.php");
		
				
	}
	//on lib�re le jeu d'enregistrement
	$res->closeCursor(); 
	// on ferme la connexion au SGBD
	$connexion = null;

?>
