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
</head>
<frameset cols="200,*" frameborder="YES" border="0" framespacing="0" rows="*"> 
  <frame name="leftFrame" scrolling="NO" noresize src="gauche.php">
  <frame name="mainFrame" src="droite.php">
</frameset>
<noframes>
<body bgcolor="#FFFFFF" text="#000000">   
</body>
</noframes> 
</html>
