<?php

echo '
<h3>Správa banneru '.$size.'</h3>
    <div class="clanok_autor">
     Bannere sa budu rotovat ak ich bude vlozenych viac, doba je nastavena na '.TOP_BANNER_ROTATION_TIME.' sekund.
    </div><br />
';

/*********************************************************************************************/
if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
	// vlozit banner
} else {
	/*********************************************************************************************/
	if($_REQUEST['action']=='insert'){
		if($_FILES['foto']['tmp_name']){
			$pripona = mb_substr($_FILES['foto']['name'],(strrpos($_FILES['foto']['name'], '.')+1));
			if(in_array($pripona, Array('jpg', 'swf'))){
				$takeFile = fopen($_FILES['foto']['tmp_name'], "r");
				$file = fread($takeFile, filesize($_FILES['foto']['tmp_name']));
				fclose($takeFile);
				$uploadedImage = chunk_split(base64_encode($file));
				psw_mysql_query($sql='
  						INSERT INTO '.$bannerType.' (alt, image, link, datetime) VALUES 
  						("'.$pripona.'", "'.$uploadedImage.'", "'.$_REQUEST['_link'].'", "'.$_REQUEST['_datetime'].'") ');
				if(mysql_error()){echo mysql_error();} else {$_REQUEST = null; echo '<span style="color: #bb0000;">banner pridaný</span><br />';}
			}
		}
	}
	/*********************************************************************************************/
	if($_REQUEST['action']=='update'){

		if($_FILES['foto']['tmp_name']){
			$pripona = mb_substr($_FILES['foto']['name'],(strrpos($_FILES['foto']['name'], '.')+1));
			$takeFile = fopen($_FILES['foto']['tmp_name'], "r");
			$file = fread($takeFile, filesize($_FILES['foto']['tmp_name']));
			fclose($takeFile);
			$uploadedImage = chunk_split(base64_encode($file));
			psw_mysql_query($sql = '
						UPDATE '.$bannerType.' SET 
						 alt = "'.$pripona.'",
						 link = "'.$_REQUEST['_link'].'",
						 datetime = "'.$_REQUEST['_datetime'].'",
						 image = "'.$uploadedImage.'" WHERE banner_id = '.$_REQUEST['banner_id'].'
						');	

		} else {
			psw_mysql_query($sql = '
				UPDATE '.$bannerType.' SET 
				 alt = "'.$_REQUEST['_alt'].'",
				 link = "'.$_REQUEST['_link'].'",
				 datetime = "'.$_REQUEST['_datetime'].'"
				 WHERE banner_id = '.$_REQUEST['banner_id'].'
				');
		}
		if(mysql_error()){echo mysql_error();} else {echo '<span style="color: #bb0000;">banner upravený</span><br /><br />';$_REQUEST = NULL; $_FILE = NULL;}

	}
	/*********************************************************************************************/
	if($_REQUEST['action']=='delete'){
		psw_mysql_query($sql = 'DELETE FROM '.$bannerType.' WHERE banner_id = '.$_REQUEST['banner_id']);
		if(mysql_error()){echo mysql_error();} else {
			echo '<span style="color: #bb0000;">banner vymazaný</span><br /><br />';
		}
	}
}
/*********************************************************************************************/
if($_REQUEST['state']!='detail'){
	echo '
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia='.$bannerType.'" method="post" enctype="multipart/form-data">
<table>
 <tr>
  <td>
   <b>Banner:<br />(jpg a swf) '.$size.'!:</b>
  </td>
  <td>
   <input type="file" name="foto" size="63" />
  </td>
 </tr> 
 <tr>
  <td>
   <b>Link (nefunguje na swf):</b>
  </td>
  <td>
   <input type="text" name="_link" value="' .(validateForm($_REQUEST['_link'])?validateForm($_REQUEST['_link']):'null'). '" size="80" />
  </td>
 </tr> 
 <tr>
  <td>
   <b>Trvanie do:</b>
  </td>
  <td>
   <input type="text" name="_datetime" value="' .($_REQUEST['_datetime']?validateForm($_REQUEST['_datetime']):date('Y-m-d G:i:s',(time()+36*36*24))). '" size="80" />
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <br />
   <input type="submit" value="Vlož banner" />
  </td>
 </tr>
<input type="hidden" name="action" value="insert" />
<table>
</form>
<br /><br />
';

	//getall rows
	$bannery = getTableRows(''.$bannerType.'','','banner_id ASC');
	echo '<table class="data_table">
  <tr class="even">
          <td><b>Obázok</b></td>
          <td><b>Link</b></td>
          
          <td><b>Trvanie do</b></td>
         </tr>';
	$even = false;
	foreach($bannery as $key => $banner){

		echo '<tr'.($even?' class="even"':'').'>';
		echo '<td>'.($banner['banner_id']).': <a href="'.$_SERVER['PHP_SELF'].'?sekcia='.$bannerType.'&state=detail&banner_id='.$banner['banner_id'].'" title="Upraviť">Upraviť</a></td>
		      <td>'.$banner['link'].'</td>
		      <td>'.$banner['datetime'].'</td>';
		echo '</tr>';
		if($even){$even=false;}else{$even=true;}
	}
	echo '</table>';



} else {

	//load
	$data = getTableRow(''.$bannerType.'', 'banner_id', $_REQUEST['banner_id']);
	$data = $data[0];

	echo '
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia='.$bannerType.'" method="post" enctype="multipart/form-data">
<table>
 <tr>
  <td>
    <b>Banner:<br />(jpg a flash):</b>
  </td>
  <td>
   Ak žiadny súbor nevyberieš, ostane pôvodný, prípadne žiadny.<br />
   <input type="file" name="foto" size="63" /><br />   
   '.($data['image']?'Súbor je vložený':'Súbor nie je vložený').'
  </td>
 </tr>
       <tr>
  <td>
   <b>Link: (nefunguje na swf)</b>
  </td>
  <td>
   <input type="text" name="_link" value="' .validateForm($data['link']). '" size="80" />
  </td>
 </tr>
  <tr>
  <td>
   <b>Trvanie do:</b>
  </td>
  <td>
   <input type="text" name="_datetime" value="' .validateForm($data['datetime']). '" size="80" />
  </td>	
 </tr>
 <tr>
  <td colspan="2">
   <input type="submit" value="Upraviť banner" />
  </td>
 </tr>
<input type="hidden" name="banner_id" value="'.$_REQUEST['banner_id'].'" />
<input type="hidden" name="action" value="update" />
<table>
</form>
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia='.$bannerType.'" method="post" enctype="multipart/form-data">
 <input type="submit" value="Vymazať banner" onClick="if(!confirm(\'Ste si istý, že chcete zmazať banner?\')){return false;}" />
 <input type="hidden" name="banner_id" value="' .$_REQUEST['banner_id']. '" />
 <input type="hidden" name="action" value="delete" />
</form> 
<br /><br />';
}



?>