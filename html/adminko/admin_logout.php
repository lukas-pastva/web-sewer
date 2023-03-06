<?php
  //session_start();
  
  $_SESSION['meno_uzivatela']="asdf";
  //session_register('stav');

  session_destroy();      
  
  header("location: index.php");
?>