<?
session_start();
ob_start();
include_once('../db.inc.php');
include_once('admin_functions.php');

echo '
<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="admin_style.css">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Administrácia ['.SITENAME.']</title>
 </head>
 <body>
  <div class="sajtah">
   <div class="top"></div>   
   <div class="site">
    <center>';

$nick = $_POST["nick"];
$nick = strip_tags($nick,'');
$pass = $_POST["pass"];
$pass = strip_tags($pass,'');
global $loggedFalseStillHacking;

if (! login($nick, $pass) ){

	
	echo '
 	 <form action="admin_login.php" method="post"> 
      Najskor sa musis prihlasit.
      <h2>Login</h2>
        meno:<br /><input type="text" name="nick" ><br />
        heslo:<br /><input type="password" name="pass"><br /><br /><br />
        <input type="submit" value="Prihlasit sa" />
     </form>
';

} else {
	header("location: index.php");
}

echo '
     </center>
    </div>
  </div>
 </body>
</html>';

ob_end_flush();

?>