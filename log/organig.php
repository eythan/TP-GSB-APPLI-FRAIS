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
.Style1 {
	font-size: large;
	font-weight: bold; 
	color: #990000;
}
.Style2 {font-size: xx-large}
-->
</style>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<div align="center" class="Style1">
  <p class="Style2">Organigramme</p>
  <p class="Style2">&nbsp;</p> 
  <p><img src="images/orga.JPG" width="512" height="384"></p>  
</div>
</body>
</html>
