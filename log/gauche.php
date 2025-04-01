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

<body bgcolor="#FFFFFF" text="#000000">
<p><img src="images/logoadmove.gif" width="168" height="148"> 
</p>
<p>&nbsp;</p>
<p><a href="droite.php" target="mainFrame"><img src="images/bouton1.jpg" width="125" height="37" border="0" ></a></p>
<br>
<p><a href="organig.php" target="mainFrame"><img src="images/bouton2.jpg" width="125" height="37" border="0" ></a></p>  
<br>
<p><a href="bouton.php" target="_top"><img src="images/bouton3.jpg" width="125" height="37" border="0" ></a></p>  
</body>
</html>
