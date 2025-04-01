<?php
  // Démarrage une session
    session_start();

    header("location:index.html");
    session_destroy();
  
?>