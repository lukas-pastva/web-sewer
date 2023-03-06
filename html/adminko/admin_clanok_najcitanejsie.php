<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "clanok") ) {
	header("location: index.php");
	die;
}
if(!isset($_REQUEST['limit'])){
	$_REQUEST['limit'] = 999;
}


// LIST
//<script language="javascript" type="text/javascript" src="../resources/calendar_db.js"></script>
echo '<h3>Najčítanejšie články za posledný rok!</h3>
	';

	echoNajcitanejsieClanky();
	echo '<br />'; 
	


?>