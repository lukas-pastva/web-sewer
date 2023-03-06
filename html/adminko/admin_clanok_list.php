<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "clanok") ) {
	header("location: index.php");
	die;
}
if(!isset($_REQUEST['limit'])){
	$_REQUEST['limit'] = 999;
}

/*********************************************************************************************/
$errors;
if($errors = echoErrors($_REQUEST)){
	//echoErrors($_REQUEST);
	$_REQUEST['state'] = 'detail';
	// vlozit clanok
} else {
	if($_REQUEST['action']=='update'){
		/*$tmpErr1 = true;
		 $tmpErr2 = true;
		 if($_FILES['avatar_1']['tmp_name']){
			$tmpErr1 = false;
			}
			if($_FILES['avatar_2']['tmp_name']){
			$tmpErr2 = false;
			}
			if($tmpErr1&&file_exists('../clanky/avatar_1_'.$_REQUEST['id'].'.jpg')){
			$tmpErr1 = false;
			}
			if($tmpErr2&&file_exists('../clanky/avatar_2_'.$_REQUEST['id'].'.jpg')){
			$tmpErr2 = false;
			}

			if($tmpErr1 || $tmpErr2){
			echo '<span style="color: #bb0000;">Vlož Avatar 1 aj Avatar 2</span><br />';
			$_REQUEST['state'] = 'detail';
			}else{*/
		for($i=1;$i<=3;$i++){
			if($_FILES['avatar_'.$i]['tmp_name']){
				$takeFile = fopen($_FILES['avatar_'.$i]['tmp_name'], "r");
				$file = fread($takeFile, filesize($_FILES['avatar_'.$i]['tmp_name']));
				fclose($takeFile);
				if(file_exists('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg')){
					unlink('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg');
				}else{
					if($i==3){
						homeBannerInsertValue($_REQUEST['id']);

						//resize a ulozenie
						$src_img  = imagecreatefromjpeg($_FILES['avatar_3']['tmp_name']);
						$size_img = getimagesize($_FILES['avatar_3']['tmp_name']);
						$thumb_height = $size_img[1] / ( $size_img[0] / $avatar[4]['width'] );
						$dst_img_thumb = imageCreateTrueColor($avatar[4]['width'],$thumb_height);
						imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $avatar[4]['width'], $thumb_height, $size_img[0], $size_img[1]);
						imagejpeg($dst_img_thumb, '../clanky/avatar_4_'.$_REQUEST['id'].'.jpg', 85);

					}
				}
				$f = fopen('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg','wb');
				fwrite($f, $file, strlen($file));
				fclose($f);

			}
		}

		$nazov = '';
		$fotograf = '';
		$big_text = '';
		$keywords = '';
		
		foreach($lang as $key => $langItem){
			$nazov .= 'nazov_'.$langItem.'= ';
			$nazov .= '"'.($_REQUEST['_nazov_'.$langItem]).'", ';

			$big_text .= 'big_text_'.$langItem.'= ';
			$big_text .= '"'.($_REQUEST['big_text']).'", ';

			$keywords .= 'keywords_'.$langItem.'= ';
			$keywords .= '"'.($_REQUEST['_keywords_'.$langItem]).'", ';

		}

		$sql = '
				UPDATE clanok SET 
				 structure_id = '.$_REQUEST['_structure_id'].',
				 '.$nazov.'
				 fotograf= "'.($_REQUEST['fotograf']).'",
         banner= "'.($_REQUEST['banner']).'", 
				 '.$big_text.'
				 '.$keywords.'
				 datetime = "'.$_REQUEST['_date'].' '.$_REQUEST['_time'].':00", 
				 '.(userGetAccess($_SESSION['meno_uzivatela'], "uzivatelia")?'user = "'.$_REQUEST['_author'].'",':'').' 
				 comments = '.($_REQUEST['_comments']=='on'?'1':'0').' 
				 WHERE clanok_id = '.$_REQUEST['id'];

		psw_mysql_query($sql);
		if(mysql_error()){echo mysql_error();} else {$errors = '<span style="color: #bb0000;">Článok úspešne upravený</span>';}

		//}
	}
	if($_REQUEST['action']=='delete'){

		//Ak v clanku niesu ziadne obrazky
		if ( ! psw_mysql_fetch_array( psw_mysql_query('SELECT * FROM picture WHERE clanok_id = "' .$_REQUEST['id']. '" ') ) ){

			// zistim structure_id
			$dir = "../fotoalbumy/alb_".$_REQUEST['id'];

			rmdir($dir."/thumbs");
			rmdir($dir);

			psw_mysql_query($sql = 'DELETE FROM clanok WHERE clanok_id = '.$_REQUEST['id']);
			psw_mysql_query($sql = 'DELETE FROM clanok_suvisiace WHERE clanok_id = '.$_REQUEST['id']);
			psw_mysql_query($sql = 'DELETE FROM clanok_suvisiace WHERE clanok_id_suvisiace = '.$_REQUEST['id']);
			psw_mysql_query($sql = 'DELETE FROM clanok_zdroj WHERE clanok_id = '.$_REQUEST['id']);
			psw_mysql_query($sql = 'UPDATE clanok SET banner = 0 WHERE clanok_id = '.$_REQUEST['id']);

			for($i=1;$i<=4;$i++){
				if(file_exists('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg')){
					unlink('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg');
				}
			}
		} else {
			alert("Vo článku su fotky, ktore treba najprv vymazať!");
			$_REQUEST['state']='detail';
		}

	}
	if($_REQUEST['action']=='publish'){
		psw_mysql_query($sql = 'UPDATE clanok SET koncept = "'.$_REQUEST['koncept'].'" WHERE clanok_id = "'.$_REQUEST['id'].'" ');
		if($_REQUEST['state']=='detail')$errors = '<span style="color: #bb0000;">Článok '.($_REQUEST['koncept']=='1'?'odpublikovaný':'publikovaný (ak jeho dátum a čas je menší ako aktuálny)').'</span>';

	}
	if($_REQUEST['action']=='new'){
		$rootId = mysql_fetch_array(psw_mysql_query('SELECT structure_id FROM structure WHERE parent_id = 0 LIMIT 1'));
		$rootId = $rootId['structure_id'];
		psw_mysql_query($sql = 'INSERT INTO clanok
				(
				    structure_id,
					nazov_sk,
					fotograf,
					big_text_sk,
					user,
					datetime,
					koncept,
					comments,
          banner
				) 
				VALUES 
				(
					"'.$rootId.'",
					now(),
					"",
					"",
					"'.($_SESSION['meno_uzivatela']).'",
					now(),
					"1",
					"1",
          "0" 
					
				)');
		$id = mysql_fetch_array(psw_mysql_query('SELECT LAST_INSERT_ID() AS last'));
		$id = $id['last'];


		$dir = "../fotoalbumy/alb_".$id.'/';


		if( ! mkdir($dir, 0777) ){
			alert("Sekcia sa neda vytvorit!");
			die;
		} else {
			chmod($dir, 0777);
		}

		//Vytvori sa thumb priecinok
		if( ! mkdir($dir.'/thumbs', 0777) ){
			alert("Sekcia sa neda vytvorit!");
			die;
		} else {
			chmod($dir.'/thumbs', 0777);
		}

		header('location:index.php?sekcia=clanok_list&state=detail&id='.$id);
	}

	if(mysql_error()){echo mysql_error();}
}
/*********************************************************************************************/
// LIST
//<script language="javascript" type="text/javascript" src="../resources/calendar_db.js"></script>
echo '<h3>Správa článkov</h3>
	';
echo $errors;



if($_REQUEST['state']!='detail'){
	
	echo '<br /><input type="submit" onclick="window.open(\'index.php?sekcia=clanok_list&action=new\',\'_self\'); return false;" value="Vlož nový článok" /></a><br /><br />';
	echo '<span style="font-weight: normal; font-size: 11px;">Len koncepty</span> <input type="checkbox" onclick="window.open(\'index.php?sekcia=clanok_list'.($_REQUEST['filter']=='koncepty'?'':'&filter=koncepty').'\',\'_self\'); return false;" '.($_REQUEST['filter']=='koncepty'?'checked="checked"':'').'/></a><br />';

	echo '<span style="font-weight: normal; font-size: 11px;">Zobraz sekciu</span>
	';

	$tree = transformTreeArray(getTreeArray());


	writeFormObject(null, 'select_rights', 'filterSekcia', ($_REQUEST['filterSekcia']?$_REQUEST['filterSekcia']:''), false, '', $tree, true, false, 'id="filterSekcia"');
  
  echo '<input type="button" value="&gt;" onclick="window.open(\'index.php?sekcia=clanok_list&filterSekcia=\'+$(\'#filterSekcia\').val()+\'\',\'_self\'); return false;" />';
	//$sekcie = getSectionByUser($_SESSION['meno_uzivatela']);
	echo '
	<br /><br />';
  
	//getall rows dla prav

	$clanky = getClankyByUser($_SESSION['meno_uzivatela'], $_REQUEST['order_by'], $_REQUEST['from'], $_REQUEST['limit'], $_REQUEST['ordering'], ($_REQUEST['filter']=='koncepty'?true:false), $_REQUEST['filterSekcia']);
	echo '<table class="data_table">';
	echo ' <tr class="even">
          <td><a href="'.$_SERVER['PHP_SELF'].'?limit='.$_REQUEST['limit'].'&from='.$_REQUEST['from'].'&sekcia=clanok_list&order_by=nazov_'.$_SESSION['selectedLang'].'&ordering=1"><b>Názov</b></a></td>
          <td style="width:100px;"><a href="'.$_SERVER['PHP_SELF'].'?limit='.$_REQUEST['limit'].'&from='.$_REQUEST['from'].'&sekcia=clanok_list&order_by=datetime&ordering=1"><b>Dátum</b></a></td>
          <td style="width:100px;"><a href="'.$_SERVER['PHP_SELF'].'?limit='.$_REQUEST['limit'].'&from='.$_REQUEST['from'].'&sekcia=clanok_list&order_by=structure_id&ordering=1"><b>Sekcia</b></a></td>
          <td style="width:62px;"><a href="'.$_SERVER['PHP_SELF'].'?limit='.$_REQUEST['limit'].'&from='.$_REQUEST['from'].'&sekcia=clanok_list&order_by=user&ordering=1"><b>Vložil</b></td>
          <td style="width:30px;"><a href="'.$_SERVER['PHP_SELF'].'?limit='.$_REQUEST['limit'].'&from='.$_REQUEST['from'].'&sekcia=clanok_list&order_by=counter&ordering=1"><b>nr.</b></a></td>
          <td style="width:100px;"><b>Koncept</b></td>
         </tr>';
	$even = false;
	foreach($clanky as $key => $clanok){
		//fb nr comments

		echo '<tr class="'.($even?'even':'').''.($clanok['koncept']=='1'||$clanok['datetime']>date('Y-m-d G:i:s')?' unpublished':'').'">';
		echo '<td>'.($clanok['clanok_id']).': <a href="'.$_SERVER['PHP_SELF'].'?sekcia=clanok_list&state=detail&id='.$clanok['clanok_id'].'" title="Upraviť"><b>'.$clanok['nazov_sk'].'</b></a></td>
		      <td>'.substr($clanok['datetime'],0,-3).'</td>
		      <td>'.getSectionNameFromId($clanok['structure_id']).'</td>
		      <td>'.$clanok['user'].'</td>
		      <td>'.$clanok['counter'].'</td>
			   <td style="width:100px;">'.($clanok['koncept']=='1'?'<a href="'.$_SERVER['PHP_SELF'].'?sekcia=clanok_list&amp;action=publish&amp;koncept=0&amp;id='.$clanok['clanok_id'].'" >Publikovať</a>':'<a href="'.$_SERVER['PHP_SELF'].'?sekcia=clanok_list&amp;action=publish&amp;koncept=1&amp;id='.$clanok['clanok_id'].'" >Odpublikovať</a>').'&nbsp;&nbsp;<a href="../clanok/'.$clanok['clanok_id'].'" title="Zobraziť článok" target="_blank" >»</a>&nbsp;</td>';
		echo '</tr>';
		if($even){$even=false;}else{$even=true;}
	}
	echo '</table>';

	echoPaging('clanok', '', $_REQUEST['from'], $_REQUEST['limit'], 'sekcia=clanok_list&order_by='.$_REQUEST['order_by']);

	// DETAIL
} else {

	//load
	$data = getTableRow('clanok', 'clanok_id', $_REQUEST['id']);
	$data = $data[0];

	if($data['nazov_sk']=='.'){$data['nazov_sk']='';}

	echo '
<form action="' .$_SERVER['PHP_SELF']. '?sekcia=clanok_list&state=detail&id='.$_REQUEST['id'].'" method="post" enctype="multipart/form-data" name="form" id="form">
         <input type="hidden" name="old_structure_id" value="' .$data['structure_id']. '" />
<div style="text-align: right; margin: -40px 20px 10px 0;">
<a href="#" title="Uložiť článok" target="_blank" onclick="document.forms.form.submit(); return false;"><img style="border: 0px none;" src="pics/save.gif" alt="Uložiť článok" title="Uložiť článok" /></a>
 <a href="' .$_SERVER['PHP_SELF']. '?sekcia=clanok_list&amp;id='.$_REQUEST['id'].'&amp;action=delete" title="Vymazať článok" onClick="if(!confirm(\'Ste si istý, že chcete zmazať celý článok aj s komentármi?\')){return false;}"><img style="border: 0px none;" src="pics/delete.gif" alt="Vymazať článok" title="Vymazať článok" /></a>
 <a href="../clanok/'.$data['clanok_id'].'-'.normalizeClanokName(getClanokNameFromId($data['clanok_id'])).'" title="Zobraziť článok" target="_blank" ><img style="border: 0px none;" src="pics/view.gif" alt="Zobraziť článok" title="Zobraziť článok" /></a>
 '.($data['koncept']=='1'?'<a href="'.$_SERVER['PHP_SELF'].'?sekcia=clanok_list&amp;action=publish&amp;koncept=0&amp;id='.$data['clanok_id'].'&amp;state=detail" title="Publikovať"><img style="border: 0px none;" src="pics/publish.gif" alt="Publikovať" title="Publikovať" /></a>':'<a href="'.$_SERVER['PHP_SELF'].'?sekcia=clanok_list&amp;action=publish&amp;koncept=1&amp;id='.$data['clanok_id'].'&amp;state=detail" title="Odpublikovať"><img style="border: 0px none;" src="pics/unpublish.gif" alt="Odpublikovať" title="Odpublikovať" /></a>').'
</div>
<table>
';


	$tree = transformTreeArray(getTreeArray());
	writeFormObject('Sekcia', 'select_rights', '_structure_id', ($_REQUEST['_structure_id']?$_REQUEST['_structure_id']:$data['structure_id']), false, '', $tree, false);
        

	$value=Array();
	foreach($lang as $langItem){$value[$langItem] = $data['nazov_'.$langItem];}
	writeFormObject('Názov článku', 'text', '_nazov', $value, true, 'width: 500px');

  writeFormObject("Vložiť do banneru", 'select', 'banner', ($data['banner']?$data['banner']:''), false, '', Array('Nie'=>'0', 'Áno'=>'1'), false, false, 'id="banner"');
  
	echo '
 <tr>
  <td><b>Dátum</b>:</td>
  <td>
   <input type="text" name="_date" value="' .($data['datetime']?substr($data['datetime'],0,-9):date('Y-m-d')). '" size="13" />
   	<script language="JavaScript">
	/*new tcal ({
		// form name
		\'formname\': \'form\',
		// input name
		\'controlname\': \'_date\'
	});*/
	</script>
  </td>
 </tr>
 <tr><td><b>Čas:</b></td><td><input type="text" name="_time" value="' .($data['datetime']?substr($data['datetime'],11,-3):date('G:i')). '" size="6" /></td></tr>
 <tr><td></td><td>
 <br />
 <input type="submit" value="Sprievodca vložením všetkými avatarmi" onClick="window.open(\'admin_clanok_image_upload.php?id='.$_REQUEST['id'].'&amp;typ=1\', \'_blank\', \'toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=1,resizable=1,width=820 ,height=520,left=10,titlebar=0\'); return false;" /> 
 </td></tr>';

	for($i=1;$i<=4;$i++){
		echo '
	 <tr>
	  <td><b>Avatar '.$i.':</b>('.$avatar[$i]['width'].'x'.$avatar[$i]['height'].')</td>
      <td><input type="file" name="avatar_'.$i.'" size="20"/>
	  <div id="avatar'.$i.'" style="display: inline;">'.(file_exists('../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg')?' <a href="../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg" target="_blank"><img style="border: 0px none; line-height: 1px; height: 13px;" src="../clanky/avatar_'.$i.'_'.$_REQUEST['id'].'.jpg" /></a><input type="button" value="X" onclick="doClanokAvatarDelete(\''.$i.'\', \''.$_REQUEST['id'].'\')" class="button" />':'').'</div></td></tr>';
	}

	writeFormObject('Text', 'textarea', 'big_text', $data['big_text_sk']!=''?$data['big_text_sk']:str_replace("\\", '',$_REQUEST['big_text']), false, 'height: 340px');

	$value=Array();
	foreach($lang as $langItem){$value[$langItem] = $data['keywords_'.$langItem];}
	writeFormObject('Keywords *', 'text', '_keywords', $value, true, 'width: 471px', null, true, true, ' title="Kľúčové slova v článku oddelené čiakou - slová neskloňovať, musia byť obsiahnuté v článku." ');

	echo '
 <tr>
  <td>
   <b>Súvisiace články:</b>
  </td>
  <td>
   <input type="text" style="width: 300px" id="clanok_suvisiaci" onkeyup="doNajdiNazovClanku(this.value, \''.$_REQUEST['id'].'\');"/><br />
   <div id="nasepkavac"></div>
  </td>
 </tr>
 <tr>
  <td>
   &nbsp;
  </td>
  <td>
   <div id="clanok_suvisiace">';
	clankySuvisiace($_REQUEST['id']);
	echo '</div>
  </td>
 </tr>	
<tr>
  <td>
   <b>Zdroj článku:</b>
  </td>
  <td>
   <input type="text" style="width: 300px" id="clanok_zdroj_input" onkeyup="doNajdiZdroj(this.value, \''.$_REQUEST['id'].'\');"/>
   <input type="button" onclick="doZdrojClanok(\''.$_REQUEST['id'].'\', document.getElementById(\'clanok_zdroj_input\').value, \'clanok_zdroj_insert\'); document.getElementById(\'zdroj\').style.display = \'none\'; return false;" value="»" />
   <br />
   <div id="zdroj"></div>
  </td>
 </tr>
 <tr>
  <td>
   &nbsp;
  </td>
  <td>
   <div id="clanok_zdroj">';
	clankyZdroj($_REQUEST['id']);
	echo '</div>
  </td>
 </tr>';

	$value=Array();
	$value = $data['fotograf'];
	writeFormObject('Fotograf', 'text', 'fotograf', $value, false, 'width: 271px');



	echo '
		 <tr>
		  <td>
		   <b>Autor:</b>
		  </td>
		  <td>';
	if(userGetAccess($_SESSION['meno_uzivatela'], "uzivatelia")){
		echo '<input type="text" name="_author" value="' .($data['user']?$data['user']:$_SESSION['meno_uzivatela']). '" size="30" />';
	} else {
		echo '<b>'.$data['user'].'</b>';
	}
	echo '
		  </td>
		 </tr>'; 

	echo (COMMENTS==1?'
	<tr>
	<td>
	<b>Povoliť komentáre:</b>
	</td>
	<td>
	<input type="checkbox" name="_comments" '.($data['comments']=='1'?'checked="checked"':'').' />
	</td>
	</tr>':'<input type="hidden" name="_comments" value="off" />').'
	<input type="hidden" name="id" value="'.$_REQUEST['id'].'" />
	<input type="hidden" name="action" value="update" />
	<table>
	</form>
	<br /><br />';



	echo '
	
     <br />
     <script language="JavaScript">
	  function mysubmit(){
		var Flash;
		if(document.embeds && document.embeds.length>=1 && navigator.userAgent.indexOf("Safari") == -1)
			Flash = document.getElementById("EmbedFlashFilesUpload");
		else
			Flash = document.getElementById("FlashFilesUpload");
		var FormObj = document.getElementById("myform");

		var FormValues = \'\';
		for (var i = 0; i<FormObj.elements.length; i++)
			FormValues += escape(FormObj.elements[i].name) + \'=\' + escape(FormObj.elements[i].value) + ((i!=(FormObj.elements.length-1))?\'&\':\'\');
		Flash.SetVariable("SubmitFlash", FormValues); 
		return false;
	   }
     </script>
     <form onSubmit="return mysubmit();" id="myform" name="myform" action="" method="post">
      <input type="hidden" name="id" id="id" value="'.$_REQUEST['id'].'" />
	  
      <table>
       <tr>
        <td><input type="hidden" name="logo_position" id="logo_position" value="a" />
        <input type="checkbox" id="logo" name="logo" value="yes" checked="checked" onMouseUp="if(!this.checked){document.getElementById(\'logo_1\').disabled=false;document.getElementById(\'logo_2\').disabled=false;document.getElementById(\'logo_3\').disabled=false;document.getElementById(\'logo_4\').disabled=false;this.value=\'on\';}else{document.getElementById(\'logo_1\').disabled=true;document.getElementById(\'logo_2\').disabled=true;document.getElementById(\'logo_3\').disabled=true;document.getElementById(\'logo_4\').disabled=true;this.value=\'off\'; setAploudURL();}" /> Vkladať logo</td>
        <td></td>
       </tr>
       <tr>
        <td><input id="logo_1" type="radio" name="logo_position_tmp" value="a" checked="checked" onmouseup="document.getElementById(\'logo_position\').value=this.value; setAploudURL();" /> Umiestniť vľavo hore</td>
        <td></td>
       </tr>
       <tr>
        <td><input id="logo_2" type="radio" name="logo_position_tmp" value="b" onmouseup="document.getElementById(\'logo_position\').value=this.value; setAploudURL();" /> Umiestniť vpravo hore</td>
        <td></td>
       </tr>
       <tr>
        <td><input id="logo_3" type="radio" name="logo_position_tmp" value="c" onmouseup="document.getElementById(\'logo_position\').value=this.value; setAploudURL();" /> Umiestniť vpravo dole</td>
        <td></td>
       </tr>
       <tr>
        <td><input id="logo_4" type="radio" name="logo_position_tmp" value="d" onmouseup="document.getElementById(\'logo_position\').value=this.value; setAploudURL();" /> Umiestniť vľavo dole</td>
        <td></td>
       </tr>
       <tr>
        <td>&nbsp;</td>
        <td> </td>
       </tr>
       <tr>
        <td>Povoliť resizovanie fotografii (meniť ich rozmery)</td>
        <td><input type="checkbox" name="resize" checked="checked" onMouseUp="if(!this.checked){document.getElementById(\'width_1\').disabled=false;document.getElementById(\'width_2\').disabled=false;this.value=\'on\';}else{document.getElementById(\'width_1\').disabled=true;document.getElementById(\'width_2\').disabled=true;this.value=\'off\';}" /></td>
       </tr>
       <tr>
        <td>Maximálna šírka fotografie orientovanej na šírku:</td>
        <td><input id="width_1" type="text" name="width_1" value="'.BIG_PICTURE_WIDTH.'" size="5" /></td>
       </tr>
       <tr>
        <td>Maximálna šírka fotografie orientovanej na výšku:</td>
        <td><input id="width_2" type="text" name="width_2" value="'.BIG_PICTURE_WIDTH_2.'" size="5" /></td>
       </tr>
      </table>  
      
      
<script type="text/javascript">
	
		function setAploudURL(){
			var ttt = "admin_upload_images2.php?logo="+document.getElementById(\'logo\').value+"&logo_position="+document.getElementById(\'logo_position\').value;
			swfu.setUploadURL(ttt);
		}

		var swfu;
		var uploadURL = "admin_upload_images2.php";
		window.onload = function() {
			var settings = {
				flash_url : "../resources/uploader/flash/swfupload.swf",
				upload_url: uploadURL,
				post_params: {"id" : "'.$_REQUEST['id'].'"},
				file_size_limit : "200 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "../resources/uploader/images/TestImageNoText_65x29.png",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: \'<span class="theFont">Nahraj</span>\',
				button_text_style: "Arial { font-size: 18; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
	</script>
<br /><hr /><br />
<h3>Uploader</h3>
	<form id="form1" action="#" method="post" enctype="multipart/form-data">
			<div class="fieldset flash" id="fsUploadProgress">
			</div>
		<div id="divStatus">0 fotiek nahranych</div><br />
			<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="Zruš všetky uploady" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
	</form>
<br /><hr /><br />';

	$fotos = psw_mysql_query('SELECT * FROM picture WHERE clanok_id="' .$_REQUEST['id']. '" ORDER BY ordering ASC, picture_id DESC');
	$pocetFoto = mysqli_num_rows($fotos);
	echo '<div id="fotoalbumHead"><b>Počet foto:</b> '.$pocetFoto.'</div><ul id="fotografie">';

	while ($foto=psw_mysql_fetch_array($fotos)){
		$dir = "../fotoalbumy/alb_".$_REQUEST['id'].'/';

		$destination_norm  = $dir.$foto['filename'];
		$destination_thumb = $dir.'thumbs/'.$foto['filename'];
		//<a href="' .$destination_norm. '" target="_blank">
		echo '<li id="'.$foto['picture_id'].'" itemID="'.$foto['picture_id'].'"><img src="' .$destination_thumb. '" border="1"><br /><a href="'.$destination_norm.'" target="_blank" style="float: left;" />zobraz</a><a href="#" onclick="doFotografieVymaz('.$foto['picture_id'].', '.$data['structure_id'].', '.$_REQUEST['id'].'); return false;" style="float: right;" />X</a></li>
		';
	}
	echo '</ul>
			<div class="cleaner"></div>
			<br /><input class="inspector" type="button" value="Zoradiť fotografie" onclick="junkdrawer.inspectListOrder(\'fotografie\')" />';

	if(false && COMMENTS==1){
		echo '
		<hr />
		<h3>Komentáre:</h3>
		';

		//echo fb comments
		echo '
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/sk_SK/all.js#xfbml=1";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, \'script\', \'facebook-jssdk\'));</script>
		<div class="fb-comments" data-href="http://www.sewer.sk/clanok/'.$_REQUEST['id'].($_REQUEST['id']<=1429?'-'.normalizeClanokName(getClanokNameFromId($_REQUEST['id'])):'').'" data-num-posts="8" data-width="660"></div>
		';

	}
}
?>