<?php
  // Démarrage une session
  session_start();

  // test vérifiant la présence de la variable session
  if ($_SESSION['ok'] != 'oui') {
    header("location:index.html");
  }
?>

<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<style type="text/css">
<!--
.Style1 {font-family: "Comic Sans MS"} 
.Style2 {
	color: #990000;
	font-size: xx-large; 
}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000"> 

<h1 align="center" class="Style2">Bienvenue sur le site de notre association</h1>
<p class="Style1">&nbsp;</p>
<p class="Style1">Nous vous proposons les activit&eacute;s suivantes :</p>
<ul>
  <li class="Style1">xxxxxxxxx</li>
  <li class="Style1">xxxxxxxxxx</li>
  <li class="Style1">xxxxxxxxx</li>
  <li class="Style1">xxxxxxxxx</li>
  <li class="Style1">xxxxxxxxxxx</li>
  <li class="Style1">xxxxxxxxxx</li>
</ul>
<p class="Style1">Bonne visite et &agrave; bient&ocirc;t peut-&ecirc;tre dans nos locaux ! </p>
<p class="Style1">&nbsp; </p>
<p>&nbsp;</p>
</body>
</html>
