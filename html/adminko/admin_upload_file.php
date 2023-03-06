<?php
ob_start();

session_start();
$_SESSION['meno_uzivatela']="asdf";
/*********************************************************************************************/
if ( ! $_SESSION['meno_uzivatela'] ) {
  include_once('../db.inc.php');
	include_once('admin_functions.php');
	if (! userGetAccess($_SESSION['meno_uzivatela'], "clanok") ) {
		header("location: index.php");
		die;
	}
}
/*********************************************************************************************/
include_once('../db.inc.php');
include_once("admin_functions.php");

/*********************************************************************************************/

if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
} else {
	if($_REQUEST['action']=='upload_file'){
		//if($_FILES['_subor']['size']<2000000){
			if((mb_substr($_FILES['_subor']['name'], -4)!='.php')&&(mb_substr($_FILES['_subor']['name'], -5)!='.phtml')&&(mb_substr($_FILES['_subor']['name'], -3)!='.js')){
				if(copy($_FILES['_subor']['tmp_name'], UPLOADS.strtolower($_FILES['_subor']['name']) )){
					$err = 'Súbor úspešne nahraný';
					
					echo 'error recording dir: '.UPLOADS.strtolower($_FILES['_subor']['name']).'   <br />';
					echo (is_file(UPLOADS.strtolower($_FILES['_subor']['name']))?' ok dir':' no ok dir');
				}else{
					echo 'error recording dir: '.UPLOADS.strtolower($_FILES['_subor']['name']).'   <br />';
					echo (is_file(UPLOADS.strtolower($_FILES['_subor']['name']))?' ok dir':' no ok dir');
					echo (is_dir(UPLOADS)?' it is dir'.UPLOADS:' it is no5 dir');
				}
			} else {
				$err = 'Súbor nesmie mať príponu .php, .phtml, .js';
			}
		//} else {
			//$err = 'Súbor nesmie byť väčší ako 2 MB';
		//}
	}
}
/*********************************************************************************************/
echo '
<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="admin_style.css">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>A.D.M.I.N. [Sewer + LZK]</title>
 </head>
 <body style="background-image: none; background-color: #999999;">
  <h3>1. Nahraj súbor</h3>
 <form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data">
	<br /><br />
	<table>
	 <tr>
	  <td><b>Súbor (max:'.ini_get('post_max_size').'):</b></td>
	  <td><input type="file" name="_subor" size="40" /></td>
	 </tr>
	 <tr>
	  <td><br /><input type="hidden" name="action" value="upload_file" /><input type="submit" value="Nahraj súbor" /></td>
	 </tr>
	</table>
	</form>
 <h3>2. Nájdi nahraný súbor, označ si ho a skopíruj jeho meno</h3>
 <h3>3. Zatvor toto okno, v editore vlož meno súboru a stlač tlačítko Image!</h3>
';
echo '<span style="color: #bf0000;">'.$err.'</span><br />';
//readnem vsetky subory, potom vypisem.
$handle = opendir(UPLOADS);
while (($file = readdir($handle))!==false) {
	if(!(($file=='.')||($file=='..'))){
		echo '<a href="/uploads/'.$file.'" target="_blank">'.$file.'</a><br />';
	}
}

echo '
	
 </body>
</html>';

ob_end_flush();
?>