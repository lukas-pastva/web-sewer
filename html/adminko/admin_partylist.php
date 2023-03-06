<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "partylist") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

echo '
<h3>Partylist</h3>
    <div class="clanok_autor">
     Akcie sa vkladaju cez verejne rozhranie. Tu máš možnosť akcie len zmazať.<br />
     Akciu je potrebné pre zobrazenie schváliť.<br />
     Akcia sa automaticky presunie do archívu, keď uplnynie deň v ktorý sa koná.
    </div><br />
';

/*********************************************************************************************/
if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
	$_REQUEST['state']='detail';
	// vlozit odkaz
} else {
	if($_REQUEST['action']=='update'){
		if($_FILES['plagat']['size'] > 20000000){
			echo '<span style="color: #bb0000;">Súbor nesmie byť väčší ako 20MB.</span><br />';
			$_REQUEST['state'] = 'detail';
		} else {

			$datum_cas = strtotime($_REQUEST['_datum'].' '.$_REQUEST['_cas']);
			$datum_cas = date('Y-m-d H:i:s', $datum_cas);

			if($_FILES['plagat']['tmp_name']){


				//Praca s velkym - treba zmensit
				//$takeFile = fopen($_FILES['plagat']['tmp_name'], "r");
				//$file = fread($takeFile, filesize($_FILES['plagat']['tmp_name']));
				//fclose($takeFile);
				//$plagat = chunk_split(base64_encode($file));

				$src_img  = imagecreatefromjpeg($_FILES['plagat']['tmp_name']);
				$size_img = getimagesize($_FILES['plagat']['tmp_name']);
				$thumb_height = $size_img[1] / ( $size_img[0] / PARTYLIST_BIG_WIDTH );
				$dst_img_thumb = imageCreateTrueColor(PARTYLIST_BIG_WIDTH,$thumb_height);
				imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, PARTYLIST_BIG_WIDTH, $thumb_height, $size_img[0], $size_img[1]);
				unlink($_FILES['plagat']['tmp_name']);
				imagejpeg($dst_img_thumb, $_FILES['plagat']['tmp_name'], BIG_PICTURE_QUALITY);
				$thumb_file = fopen($_FILES['plagat']['tmp_name'], "r");
				$thumb = fread($thumb_file, filesize($_FILES['plagat']['tmp_name']));
				fclose($thumb_file);
				$plagat = chunk_split(base64_encode($thumb));


				//Praca s thumbnailom
				$src_img  = imagecreatefromjpeg($_FILES['plagat']['tmp_name']);
				$size_img = getimagesize($_FILES['plagat']['tmp_name']);
				$thumb_height = $size_img[1] / ( $size_img[0] / PARTYLIST_THUMB_WIDTH );
				$dst_img_thumb = imageCreateTrueColor(PARTYLIST_THUMB_WIDTH,$thumb_height);
				imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, PARTYLIST_THUMB_WIDTH, $thumb_height, $size_img[0], $size_img[1]);
				unlink($_FILES['plagat']['tmp_name']);
				imagejpeg($dst_img_thumb, $_FILES['plagat']['tmp_name'], THUMB_PICTURE_QUALITY);
				$thumb_file = fopen($_FILES['plagat']['tmp_name'], "r");
				$thumb = fread($thumb_file, filesize($_FILES['plagat']['tmp_name']));
				fclose($thumb_file);
				$thumbnail = chunk_split(base64_encode($thumb));

					
				//musim resiznut obrazok ak je vyssi akoooo 174px
				if($thumb_height>THUMB2_PARTYLIST_HEIGHT){
					$src_img  = imagecreatefromjpeg($_FILES['plagat']['tmp_name']);
					$size_img = getimagesize($_FILES['plagat']['tmp_name']);
					$thumb_width = $size_img[0] / ( $size_img[1] / THUMB2_PARTYLIST_HEIGHT );
					$dst_img_thumb = imageCreateTrueColor($thumb_width,THUMB2_PARTYLIST_HEIGHT);
					imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $thumb_width, THUMB2_PARTYLIST_HEIGHT, $size_img[0], $size_img[1]);
					unlink($_FILES['plagat']['tmp_name']);
					imagejpeg($dst_img_thumb, $_FILES['plagat']['tmp_name'], THUMB_PICTURE_QUALITY);
					$thumb_file2 = fopen($_FILES['plagat']['tmp_name'], "r");
					$thumb2 = fread($thumb_file2, filesize($_FILES['plagat']['tmp_name']));
					fclose($thumb_file2);
					$thumbnail2 = chunk_split(base64_encode($thumb2));
				}else{
					$thumbnail2 = $thumbnail;
				}


				psw_mysql_query($sql = '
				UPDATE partylist SET 
				 title = "'.$_REQUEST['_title'].'",
				 link = "'.$_REQUEST['link'].'",
				 klub = "'.$_REQUEST['_klub'].'",		
				 mesto = "'.$_REQUEST['_mesto'].'",		
				 vstupne = "'.$_REQUEST['_vstupne'].'",				 
				 ordering = '.$_REQUEST['_ordering'].',			 
				 datetime = "'.$datum_cas.'", 
				 schvalene = "'.(($_REQUEST['schvalene']=='on'?'1':'0')).'",
				 thumb = "'.$thumbnail.'",
				 thumb2 = "'.$thumbnail2.'",
				 poster = "'.$plagat.'"
				  WHERE partylist_id = '.$_REQUEST['partylist_id'].'
				');
			} else {
				psw_mysql_query($sql = '
				UPDATE partylist SET 
				 title = "'.$_REQUEST['_title'].'",
				 link = "'.$_REQUEST['link'].'",
				 klub = "'.$_REQUEST['_klub'].'",		
				 mesto = "'.$_REQUEST['_mesto'].'",		
				 vstupne = "'.$_REQUEST['_vstupne'].'",
				 ordering = '.$_REQUEST['_ordering'].',	
				 datetime = "'.$datum_cas.'", 
				 schvalene = "'.(($_REQUEST['schvalene']=='on'?'1':'0')).'"
				  WHERE partylist_id = '.$_REQUEST['partylist_id'].'
				');
			}
			//debug($sql);
			if(mysql_error()){echo mysql_error();} else {echo '<span style="color: #bb0000;">Akcia upravená.</span><br /><br />';}
		}
	}
	if($_REQUEST['action']=='delete'){
		psw_mysql_query($sql = 'DELETE FROM partylist WHERE partylist_id = '.$_REQUEST['partylist_id']);
		if(mysql_error()){echo mysql_error();} else {
			echo '<span style="color: #bb0000;">Akcia vymazaná</span><br /><br />';
		}
	}
}
/*********************************************************************************************/
if($_REQUEST['state']!='detail'){


	//getall rows
	$akcie = getTableRows('partylist',' AND archiv = 0 ','ordering ASC');
	echo '<table class="data_table">';
	echo ' <tr class="even">
          <td><b>Názov akcie</b></td>
          <td><b>Dátum konania</b></td>          
          <td><b>Schvalene</b></td>
          <td><b>Poradie</b></td>
         </tr>';
	$even = false;
	foreach($akcie as $key => $akcia){
		echo '<tr'.($even?' class="even"':'').'>
		       <td><a href="'.$_SERVER['PHP_SELF'].'?sekcia=partylist&state=detail&partylist_id='.$akcia['partylist_id'].'" title="Upraviť">'.$akcia['title'].'</a></td>
		       <td><a href="'.$_SERVER['PHP_SELF'].'?sekcia=partylist&state=detail&partylist_id='.$akcia['partylist_id'].'" title="Upraviť">'.mb_substr($akcia['datetime'],0,10).'</a></td>		       
		       <td>'.(($akcia['schvalene']=='1')?'Yes':'No').'</td>
		       <td>'.$akcia['ordering'].'</td>
		      </tr>';
		if($even){$even=false;}else{$even=true;}
	}
	echo '</table>';


	//$akcie = getTableRows('partylist',' AND archiv <> 0 ','ordering ASC', 0, 100);
	$akcie = getTableRowsByAttribudes('partylist','partylist_id, title, datetime, ordering', 'AND archiv <> 0 ','ordering ASC', 0, 1000);
		
	echo '<br /><br /><br /><br /><br /><br /><b>Archív</b><table class="data_table">';
	echo ' <tr class="even">
          <td><b>Názov akcie</b></td>
         </tr>';
	
	$even = false;
	foreach($akcie as $key => $akcia){
		echo '<tr'.($even?' class="even"':'').'>
		       <td><a href="'.$_SERVER['PHP_SELF'].'?sekcia=partylist&state=detail&partylist_id='.$akcia['partylist_id'].'" title="Upraviť">'.$akcia['title'].' ('.mb_substr($akcia['datetime'],0,-3).')</a></td>		       		       
		      </tr>';
		if($even){$even=false;}else{$even=true;}
	}
	echo '</table>';


} else {

	//load
	$data = getTableRow('partylist', 'partylist_id', $_REQUEST['partylist_id']);
	$data = $data[0];

	echo '
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=partylist" method="post" enctype="multipart/form-data" name="form">
<table>  
 <tr>
  <td><b>Názov akcie:&nbsp;</b></td>
  <td><input type="text" name="_title" value="' .validateForm($data['title']). '" size="50" /></td>
 </tr> 
 <tr><td><b>Dátum:</b></td>
 <script language="javascript" type="text/javascript" src="../resources/calendar_db.js"></script>
 <td><input type="text" name="_datum" value="'.($_REQUEST['_datum']?validateForm($_REQUEST['_datum']):substr($data['datetime'],0,10)).'" size="13" />
 <script language="JavaScript">
	new tcal ({
		\'formname\': \'form\',
		\'controlname\': \'_datum\'
	});
 </script>
 </td></tr>
 <tr><td><b>Začiatok:</b></td><td><input type="text" name="_cas" value="' .($_REQUEST['_cas']?validateForm($_REQUEST['_cas']):substr($data['datetime'],11,5)). '" size="6" /></td></tr>
 
 <tr>
  <td><b>Klub:</b></td>
  <td><input type="text" name="_klub" value="' .validateForm($data['klub']). '" size="50" /></td>
 </tr>
 <tr>
  <td><b>Mesto:</b></td>
  <td><input type="text" name="_mesto" value="' .validateForm($data['mesto']). '" size="50" /></td>
 </tr>
 <tr>
  <td><b>Cena:</b></td>
  <td><input type="text" name="_vstupne" value="' .validateForm($data['vstupne']). '" size="20" /></td>
 </tr> 
 <tr>
  <td><b>Link:</b></td>
  <td><input type="text" name="link" value="' .validateForm($data['link']). '" size="50" /></td>
 </tr>
 <tr>
  <td>
   <b>Plagát:</b>
  </td>
  <td>
  <br />
   Ak žiadny súbor nevyberieš, ostane pôvodný.<br />
   <input type="file" name="plagat" size="63" /><br />   
   '.($data['thumb']?''.($data['poster']?'<a href="../image/4/'.$_REQUEST['partylist_id'].'.jpg" target="_blank" />':'').'<img src="../image/3/'.$_REQUEST['partylist_id'].'.jpg" style="border: 1px solid #666666;" />'.($data['poster']?'</a>':'').'':'<b>Žiadny obrázok</b><br />').'<br />   
  </td>
 </tr> 
 <tr>
  <td>
   <b>Schvalene?:</b>
  </td>
  <td>
   <input type="checkbox" name="schvalene" ' .($data['schvalene']?' checked="checked"':''). '" />
  </td>
 </tr>
 <tr>
  <td><b>Poradie:</b></td>
  <td><input type="text" name="_ordering" value="' .validateForm($data['ordering']). '" size="5" /></td>
 </tr> 
 <tr>
  <td colspan="2"><br />
   <input type="submit" value="Upraviť akciu" />
  </td>
 </tr>
<input type="hidden" name="partylist_id" value="'.$_REQUEST['partylist_id'].'" />
<input type="hidden" name="action" value="update" />
<table>
</form>
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=partylist" method="post" enctype="multipart/form-data">
 <input type="submit" value="Vymazať akciu" onClick="if(!confirm(\'Si si istý, že chceš zmazať akciu?\')){return false;}" />
 <input type="hidden" name="partylist_id" value="' .$_REQUEST['partylist_id']. '" />
 <input type="hidden" name="action" value="delete" />
</form> 
<br /><br />';
}
?>