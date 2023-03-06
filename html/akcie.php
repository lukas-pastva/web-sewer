<?php
ob_start();
error_reporting(E_ALL ^ E_WARNING);
include_once('db.inc.php');
include_once(ADMIN_LOCATION."admin_functions.php");

echo '<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="./resources/style.css">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Vlož akciu</title>
 </head>
 <body style="background-color: #dddddd; padding: 4px;"> 
 <link rel="stylesheet" type="text/css" href="./resources/calendar.css">
 <script language="javascript" type="text/javascript" src="./resources/calendar_db.js"></script>
 
';

/*********************************************************************************************/

if(echoErrors($_REQUEST)){
	echo '<span style="font-size: 11px;">'.echoErrors($_REQUEST).'</span><br />';
	// vlozit clanok
} else {
	if($_REQUEST['action']=='insert'){
		if($_FILES['_plagat']['size'] > 20000000){
			echo '<span style="font-size: 11px; color: #bb0000;">Súbor nesmie byť väčší ako 20MB.</span><br />';
		} else {
			if(strtolower(mb_substr($_FILES['_plagat']['name'],-4))=='.jpg'){
				$_nazov = ($_REQUEST['_nazov']);
				$datum_cas = strtotime($_REQUEST['_datum'].' '.$_REQUEST['_cas']);
				$datum_cas = date('Y-m-d H:i:s', $datum_cas);

				//Praca s velkym - treba zmensit
				//$takeFile = fopen($_FILES['_plagat']['tmp_name'], "r");
				//$file = fread($takeFile, filesize($_FILES['_plagat']['tmp_name']));
				//fclose($takeFile);
				//$plagat = chunk_split(base64_encode($file));

				$src_img  = imagecreatefromjpeg($_FILES['_plagat']['tmp_name']);
				$size_img = getimagesize($_FILES['_plagat']['tmp_name']);
				$thumb_height = $size_img[1] / ( $size_img[0] / PARTYLIST_BIG_WIDTH );
				$dst_img_thumb = imageCreateTrueColor(PARTYLIST_BIG_WIDTH,$thumb_height);
				imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, PARTYLIST_BIG_WIDTH, $thumb_height, $size_img[0], $size_img[1]);
				unlink($_FILES['_plagat']['tmp_name']);
				imagejpeg($dst_img_thumb, $_FILES['_plagat']['tmp_name'], BIG_PICTURE_QUALITY);
				$thumb_file = fopen($_FILES['_plagat']['tmp_name'], "r");
				$thumb = fread($thumb_file, filesize($_FILES['_plagat']['tmp_name']));
				fclose($thumb_file);
				$plagat = chunk_split(base64_encode($thumb));

				//Praca s thumbnailom
				$src_img  = imagecreatefromjpeg($_FILES['_plagat']['tmp_name']);
				$size_img = getimagesize($_FILES['_plagat']['tmp_name']);
				$thumb_height = $size_img[1] / ( $size_img[0] / PARTYLIST_THUMB_WIDTH );
				$dst_img_thumb = imageCreateTrueColor(PARTYLIST_THUMB_WIDTH,$thumb_height);
				imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, PARTYLIST_THUMB_WIDTH, $thumb_height, $size_img[0], $size_img[1]);
				unlink($_FILES['_plagat']['tmp_name']);
				imagejpeg($dst_img_thumb, $_FILES['_plagat']['tmp_name'], THUMB_PICTURE_QUALITY);
				$thumb_file = fopen($_FILES['_plagat']['tmp_name'], "r");
				$thumb = fread($thumb_file, filesize($_FILES['_plagat']['tmp_name']));
				fclose($thumb_file);
				$thumbnail = chunk_split(base64_encode($thumb));

					
				//musim resiznut obrazok ak je vyssi akoooo 174px
				if($thumb_height>THUMB2_PARTYLIST_HEIGHT){
					$src_img  = imagecreatefromjpeg($_FILES['_plagat']['tmp_name']);
					$size_img = getimagesize($_FILES['_plagat']['tmp_name']);
					$thumb_width = $size_img[0] / ( $size_img[1] / THUMB2_PARTYLIST_HEIGHT );
					$dst_img_thumb = imageCreateTrueColor($thumb_width,THUMB2_PARTYLIST_HEIGHT);
					imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $thumb_width, THUMB2_PARTYLIST_HEIGHT, $size_img[0], $size_img[1]);
					unlink($_FILES['_plagat']['tmp_name']);						
					imagejpeg($dst_img_thumb, $_FILES['_plagat']['tmp_name'], THUMB_PICTURE_QUALITY);
					$thumb_file2 = fopen($_FILES['_plagat']['tmp_name'], "r");
					$thumb2 = fread($thumb_file2, filesize($_FILES['_plagat']['tmp_name']));
					fclose($thumb_file2);
					$thumbnail2 = chunk_split(base64_encode($thumb2));
				}else{	
					$thumbnail2 = $thumbnail;
				}



				$q = psw_mysql_query($sql='
										INSERT INTO partylist 
										(title, datetime, link, klub, mesto, vstupne, thumb, thumb2, poster) 
										VALUES 
										("'.$_nazov.'", "'.$datum_cas.'", "'.$_REQUEST['link'].'", "'.$_REQUEST['_klub'].'", "'.$_REQUEST['_mesto'].'", "'.$_REQUEST['_vstupne'].'", "'.$thumbnail.'", "'.$thumbnail2.'", "'.$plagat.'") ');
				echo mysql_error();
				//debug($sql);
				$state='end';
			} else {
				echo '<span style="font-size: 11px; color: #bb0000;">Súbor musí mať príponu .jpg</span><br /><br />';
			}
		}
	}
}
/*********************************************************************************************/

if($state != 'end'){
	$now = date('Y-m-d G:i', (time()+(60*60*24)));

  echo '<h4>Vložiť akciu:</h4>';
	echo '
	
	Ak chceš informovať o neakej akcii, ktorá sa koná v tvojom okolí, alebo ktorú organizujež, tak vyplň a odošli tento formulár. 
	<form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data" name="form" >
	<table class="akcie">
	 <tr>
	  <td><br /><b>Názov:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
	  <td><br /><input type="text" name="_nazov" value="'.validateForm($_REQUEST['_nazov']).'" size="50" /></td>
	 </tr>
     <tr><td><b>Dátum:</b></td>
     <td><input type="text" name="_datum" value="'.($_REQUEST['_datum']?validateForm($_REQUEST['_datum']):'').'" size="13" />
   	  <script language="JavaScript">
		new tcal ({
			\'formname\': \'form\',
			\'controlname\': \'_datum\'
		});
		</script>
     </td></tr>
 	 <tr><td><b>Začiatok:</b></td><td><input type="text" name="_cas" value="' .($_REQUEST['_cas']?validateForm($_REQUEST['_cas']):date('H:').'00'). '" size="6" /></td></tr>
	 <tr>
	  <td><b>Klub:</b></td>
	  <td><input type="text" name="_klub" value="'.validateForm($_REQUEST['_klub']).'" size="30" /></td>
	 </tr>
	 <tr>
	  <td><b>Mesto:</b></td>
	  <td><input type="text" name="_mesto" value="'.validateForm($_REQUEST['_mesto']).'" size="30" /></td>
	 </tr>
	 <tr>
	  <td><b>Vstupné:</b></td>
	  <td><input type="text" name="_vstupne" value="'.validateForm($_REQUEST['_vstupne']).'" size="20" /><b> Eur</b></td>
	 </tr>
	 
	 <tr>
	  <td><b>Plagát:</b></td>
	  <td><input type="file" name="_plagat" size="33" /></td>
	 </tr>
	 <tr>
	  <td><b>Web:</b></td>
	  <td><input type="text" name="link" value="'.validateForm($_REQUEST['link']).'" size="43" /></td>
	 </tr>
	 
	 <tr>
	  <td colspan="2">
	   <br />
	   <input type="hidden" name="action" value="insert" />
	   <input type="submit" value="Vlož akciu" />
	  </td>
	 </tr>
	</table>
	</form>
	';
} else {
	echo '
	   <br /><br /><br />
		 <center>
		  <span style="font-size: 11px;">
		   Akcia úspešne pridaná.<br />
		   Bude zobrazená na webe akonáhle bude potvrdená administrátorom.<br />
		   <span style="cursor: pointer;" onClick="window.close();"><b>Zatvor okno</b></a></span>
		  </span>		   
		 </center>
		';
}
echo '
 </body>
</html>';

ob_end_flush();

?>