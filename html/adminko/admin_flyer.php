<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

echo '
<h3>Správa partnerov</h3>
    <div class="clanok_autor">
     Flyer na uvode stranky.
    </div><br />
';

/*********************************************************************************************/
if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
	// vlozit flyer
} else {
	/*********************************************************************************************/
	if($_REQUEST['action']=='insert'){
		if($_FILES['foto']['size'] > 20*1024*1024){
			echo '<span style="color: #bb0000;">Súbor nesmie byť väčší ako 20Mb.</span><br />';
		} else {
			if($_FILES['foto']['tmp_name']){
				if(mb_substr($_FILES['foto']['name'],-4)=='.jpg'){
					$takeFile = fopen($_FILES['foto']['tmp_name'], "r");
					$file = fread($takeFile, filesize($_FILES['foto']['tmp_name']));
					fclose($takeFile);
					$uploadedImage = chunk_split(base64_encode($file));
					psw_mysql_query($sql='DELETE FROM flyer WHERE 1');
					psw_mysql_query($sql='
						INSERT INTO flyer (alt, image, link, datetime) VALUES 
						("'.$_REQUEST['_alt'].'", "'.$uploadedImage.'", "'.$_REQUEST['_link'].'", "'.$_REQUEST['_datetime'].'") ');
					if(mysql_error()){echo mysql_error();} else {$_REQUEST = null; echo '<span style="color: #bb0000;">flyer pridaný.</span><br />';}
				} else {
					echo '<span style="font-size: 11px; color: #bb0000;">Súbor musí mať príponu .jpg</span><br /><br />';
				}
			} else {
				psw_mysql_query($sql='DELETE FROM flyer WHERE 1');
				psw_mysql_query($sql='
						INSERT INTO flyer (alt, image, link, datetime) VALUES 
						("'.$_REQUEST['_alt'].'", "no image", "'.$_REQUEST['_link'].'", "'.$_REQUEST['_datetime'].'") ');
			}
		}
	}
	/*********************************************************************************************/
	if($_REQUEST['action']=='update'){

		if($_FILES['foto']['size'] > 307200){
			echo '<span style="color: #bb0000;">Súbor nesmie byť väčší ako 300kb.</span><br />';
			$_REQUEST['state'] = 'detail';
		} else {
			if($_FILES['foto']['tmp_name']){
				if(mb_substr($_FILES['foto']['name'],-4)=='.jpg'){
					$takeFile = fopen($_FILES['foto']['tmp_name'], "r");
					$file = fread($takeFile, filesize($_FILES['foto']['tmp_name']));
					fclose($takeFile);
					$uploadedImage = chunk_split(base64_encode($file));
					//psw_mysql_query($sql='DELETE FROM flyer WHERE 1');
					psw_mysql_query($sql = '
						UPDATE flyer SET 
						 alt = "'.$_REQUEST['_alt'].'",
						 link = "'.$_REQUEST['_link'].'",
						 datetime = "'.$_REQUEST['_datetime'].'",
						 image = "'.$uploadedImage.'" WHERE flyer_id = '.$_REQUEST['flyer_id'].'
						');	
				} else {
					echo '<span style="font-size: 11px; color: #bb0000;">Súbor musí mať príponu .jpg</span><br /><br />';
				}
			} else {
				//psw_mysql_query($sql='DELETE FROM flyer WHERE 1');
				psw_mysql_query($sql = '
				UPDATE flyer SET 
				 alt = "'.$_REQUEST['_alt'].'",
				 link = "'.$_REQUEST['_link'].'",
				 datetime = "'.$_REQUEST['_datetime'].'"
				 WHERE flyer_id = '.$_REQUEST['flyer_id'].'
				');
			}
			if(mysql_error()){echo mysql_error();} else {echo '<span style="color: #bb0000;">flyer upravený.</span><br /><br />';$_REQUEST = NULL; $_FILE = NULL;}
		}
	}
	/*********************************************************************************************/
	if($_REQUEST['action']=='delete'){
		psw_mysql_query($sql = 'DELETE FROM flyer WHERE flyer_id = '.$_REQUEST['flyer_id']);
		if(mysql_error()){echo mysql_error();} else {
			echo '<span style="color: #bb0000;">flyer vymazaný</span><br /><br />';
		}
	}
}
/*********************************************************************************************/
if($_REQUEST['state']!='detail'){
	echo '
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=flyer" method="post" enctype="multipart/form-data">
<table>
 <tr>
  <td>
   <b>Flyer:<br />(odporučam 600px x 400px):</b>
  </td>
  <td>
   <input type="file" name="foto" size="63" />
  </td>
 </tr> 
 <tr>
  <td>
   <b>Úvodný text:</b>
  </td>
  <td>
   <input type="text" name="_alt" value="' .validateForm($_REQUEST['_alt']). '" size="80" />
  </td>
 </tr> 
 <tr>
  <td>
   <b>Link:</b>
  </td>
  <td>
   <input type="text" name="_link" value="' .validateForm($_REQUEST['_link']). '" size="80" />
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
   <input type="submit" value="Vlož flyer (Vložením nového sa vymaže aktuálny)" />
  </td>
 </tr>
<input type="hidden" name="action" value="insert" />
<table>
</form>
<br /><br />
';

	//getall rows
	$flyery = getTableRows('flyer','','flyer_id ASC');
	echo '<table class="data_table">';
	echo ' <tr class="even">
          <td><b>Obázok</b></td>
          <td><b>Úvodný text</b></td>
          <td><b>Link</b></td>
          <td><b>Trvanie do</b></td>
         </tr>';
	$even = false;
	foreach($flyery as $key => $flyer){
		echo '<tr'.($even?' class="even"':'').'>';
		echo '<td>'.($flyer['flyer_id']).': <a href="'.$_SERVER['PHP_SELF'].'?sekcia=flyer&state=detail&flyer_id='.$flyer['flyer_id'].'" title="Upraviť"><img style="width: 90px;" src="../image/6/'.$flyer['flyer_id'].'.jpg" style="border: 1px solid #666666;" /></a></td>
		      <td>'.$flyer['alt'].'</td>
		      <td>'.$flyer['link'].'</td>
		      <td>'.$flyer['datetime'].'</td>';
		echo '</tr>';
		if($even){$even=false;}else{$even=true;}
	}
	echo '</table>';



} else {

	//load
	$data = getTableRow('flyer', 'flyer_id', $_REQUEST['flyer_id']);
	$data = $data[0];

	echo '
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=flyer" method="post" enctype="multipart/form-data">
<table>
 <tr>
  <td>
    <b>Flyer:<br />(odporučam 600px x 400px):</b>
  </td>
  <td>
   Ak žiadny súbor nevyberieš, ostane pôvodný, prípadne žiadny.<br />
   <input type="file" name="foto" size="63" /><br />   
   '.($data['image']?'<img src="../image/6/'.$_REQUEST['flyer_id'].'.jpg" style="border: 1px solid #666666;" /><br /><br />':'<b>Žiadna ikona</b><br /><br />').'
  </td>
 </tr> 
 <tr>
  <td>
   <b>Úvodný text:</b>
  </td>
  <td>
   <input type="text" name="_alt" value="' .validateForm($data['alt']). '" size="80" />
  </td>
 </tr> 
 <tr>
  <td>
   <b>Link:</b>
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
   <input type="submit" value="Upraviť flyer" />
  </td>
 </tr>
<input type="hidden" name="flyer_id" value="'.$_REQUEST['flyer_id'].'" />
<input type="hidden" name="action" value="update" />
<table>
</form>
<br />
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=flyer" method="post" enctype="multipart/form-data">
 <input type="submit" value="Vymazať flyer" onClick="if(!confirm(\'Ste si istý, že chcete zmazať flyer?\')){return false;}" />
 <input type="hidden" name="flyer_id" value="' .$_REQUEST['flyer_id']. '" />
 <input type="hidden" name="action" value="delete" />
</form> 
<br /><br />';
}



?>