<?php
$_SESSION['meno_uzivatela']="asdf";
/*********************************************************************************************/
if (! $_SESSION['meno_uzivatela'] ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

echo '<center><br /><br />';
  
include_once("admin_functions.php");
$id = psw_mysql_fetch_array(psw_mysql_query('SELECT id FROM user WHERE nick = "' .$_SESSION['meno_uzivatela']. '" '));
$logs = psw_mysql_fetch_array(psw_mysql_query('SELECT count(*) AS logs FROM user_login WHERE user_id = "' .$id['id']. '" '));
$last_log = psw_mysql_fetch_array(psw_mysql_query('SELECT time FROM user_login WHERE user_id = "' .$id['id']. '" AND time < (SELECT time FROM user_login WHERE user_id = "' .$id['id']. '" ORDER BY time DESC LIMIT 1 ) ORDER BY time DESC LIMIT 1'));
 
echo '
   Vitaj <b>' .$_SESSION['meno_uzivatela']. '</b>. Uz si tu bol <b>' .$logs['logs']. '</b> krat, z toho naposledy <b>' .date('j.n.Y / G:i:s', ($last_log['time'])). '</b>.<br />
   Klikni na niektoru z poloziek v menu.
</center>';

?>