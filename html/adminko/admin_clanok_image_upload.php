<?php
ob_start();

session_start();
$_SESSION['meno_uzivatela']="asdf";
include_once('../db.inc.php');
include_once("admin_functions.php");
/*********************************************************************************************/
if ( ! $_SESSION['meno_uzivatela'] ) {
	if (! userGetAccess($_SESSION['meno_uzivatela'], "clanok") ) {
		header("location: index.php");die;
	}
}

/*********************************************************************************************/
if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
} else {
	if($_REQUEST['state']=='resize'){
		if($_FILES['_subor']['size']<(int)ini_get('post_max_size')*1024*1024){
			if(mb_substr($_FILES['_subor']['name'], -4)=='.jpg'){

				//if image width is less than 660 px, increase to 660px
				$size_img = getimagesize($_FILES['_subor']['tmp_name']);
				if($size_img[0]<$avatar[2]['width']){
					$src_img  = imagecreatefromjpeg($_FILES['_subor']['tmp_name']);
					$dst_height = ($avatar[2]['width']/$size_img[0])*$size_img[1];
					$dst_img_tmp = imageCreateTrueColor($avatar[2]['width'], $dst_height);
					imagecopyresampled($dst_img_tmp, $src_img, 0, 0, 0, 0, $avatar[2]['width'], $dst_height, $size_img[0], $size_img[1]);
					imagejpeg($dst_img_tmp, '../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg', BIG_PICTURE_QUALITY);
				}else{
					copy($_FILES['_subor']['tmp_name'], '../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg');
				}
			} else {
				$err = 'Súbor musí mať príponu .jpg';
			}
		} else {
			$err = 'Súbor nesmie byť väčší ako '.ini_get('post_max_size').'resize';
		}
	}
}
/*********************************************************************************************/

echo '<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="admin_style.css">
  <link rel="stylesheet" type="text/css" href="../resources/photo_uploader.css">  
  <script type="text/javascript" src="../resources/photo_uploader.js"></script>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>resizer</title>
 </head>
 <body style="background-image: none; background-color: #ccc;">
 <style type="text/css">';

$pomer=$avatar[$_REQUEST['typ']]['width']/$avatar[$_REQUEST['typ']]['height'];

echo '
	.css_zmenafoto .css_nahlad p,.css_zmenafoto .css_nahlad p span {
		width: 120px;
		height: '.(120/$pomer).'px;
	}
 </style>
 <span style="color: #bf0000;">'.$err.'</span><br />';

echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data">';
if(!$_REQUEST['state']){
	echo '<table>
	 <tr>
	  <td><b>Súbor (max:'.ini_get('post_max_size').'):</b></td>
	  <td><input type="file" name="_subor" size="40" /></td>
	 </tr>
	 <tr>
	  <td><br />
	   <input type="hidden" name="state" value="resize" />
	   <input type="hidden" name="id" value="'.$_REQUEST['id'].'" />
	   <input type="hidden" name="resizing" value="1" />	   
	   <input type="submit" value="Nahraj súbor" />
	  </td>
	 </tr>
	</table>';
}else 
if($_REQUEST['state']=='resize'){

	$size_img = getimagesize('../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg');

	//big_image
	echo '
		<div class="css_superupload css_zmenafoto">
	      <form action="'.$_SERVER['PHP_SELF'].'" method="post">
	        <fieldset>
	         
	          <div class="css_plocha">
	            <div class="css_fotka">
	                <img src="../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg" alt="fotka" id="cropbox" />
	            </div>
				<script language="Javascript">
						jQuery(window).load(function(){
							jQuery(\'#cropbox\').Jcrop({
								onChange: showPreview,
								onSelect: showPreview,
								bgOpacity: .6,
								minSize: ['.$avatar[$_REQUEST['resizing']]['width'].', '.$avatar[$_REQUEST['resizing']]['height'].'],
								setSelect: [ 0, 0, '.$avatar[$_REQUEST['resizing']]['width'].', '.$avatar[$_REQUEST['resizing']]['height'].' ],
								aspectRatio: '.($avatar[$_REQUEST['resizing']]['width']/$avatar[$_REQUEST['resizing']]['height']).',
								boxWidth: 536, boxHeight: 402
							});
						});
						function showPreview(coords) {
							showCoords(coords);
							var rx = '.$avatar[$_REQUEST['resizing']]['width'].' / coords.w;
							var ry = '.$avatar[$_REQUEST['resizing']]['height'].' / coords.h;
							jQuery(\'#preview\').css({
								width: Math.round(rx * '.$size_img[0].') + \'px\',
								height: Math.round(ry * '.$size_img[1].') + \'px\',
								marginLeft: \'-\' + Math.round(rx * coords.x) + \'px\',
								marginTop: \'-\' + Math.round(ry * coords.y) + \'px\'
							});
						}
						function showCoords(c) {
							jQuery(\'#x1\').val(c.x);
							jQuery(\'#y1\').val(c.y);
							jQuery(\'#x2\').val(c.x2);
							jQuery(\'#y2\').val(c.y2);
							jQuery(\'#width\').val(c.w);
							jQuery(\'#height\').val(c.h);
						};
					</script>
	            
	          </div>
	        </fieldset>
	        <input type="hidden" name="x1" id="x1" />
	        <input type="hidden" name="y1" id="y1" />
	        <input type="hidden" name="x2" id="x2" />
	        <input type="hidden" name="y2" id="y2" />
   			<input type="hidden" name="resizing" value="'.$_REQUEST['resizing'].'" />
	        <input type="hidden" name="width" id="width" />
	        <input type="hidden" name="height" id="height" />
	        <input type="hidden" name="state" value="finish" />
	        <input type="hidden" name="id" value="'.$_REQUEST['id'].'" />
	        <input type="submit" value="Pokračovať" />
	    </div>
		';

}elseif($_REQUEST['state']=='finish'){
	//resiznem subor, potom ho ulozim do databazy


	$src_img  = imagecreatefromjpeg('../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg');
	$size_img = getimagesize('../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg');

	//Praca s thumbnailom
	$dst_img_thumb = imageCreateTrueColor($avatar[$_REQUEST['resizing']]['width'],$avatar[$_REQUEST['resizing']]['height']);
	imagecopyresampled($dst_img_thumb, $src_img, 0, 0, $_REQUEST['x1'], $_REQUEST['y1'], $avatar[$_REQUEST['resizing']]['width'], $avatar[$_REQUEST['resizing']]['height'], $_REQUEST['width'], $_REQUEST['height']);
	if(file_exists('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg')){
		unlink('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg');
	}
	imagejpeg($dst_img_thumb, '../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg', THUMB_PICTURE_QUALITY);

	if($_REQUEST['resizing']=='2'){
		//resize a ulozenie
		$src_img  = imagecreatefromjpeg('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg');
		$size_img = getimagesize('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg');
		$thumb_height = $size_img[1] / ( $size_img[0] / $avatar[3]['width'] );
		$dst_img_thumb = imageCreateTrueColor($avatar[3]['width'],$avatar[3]['height']);
		imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $avatar[3]['width'], $avatar[3]['height'], $size_img[0], $size_img[1]);
		imagejpeg($dst_img_thumb, '../clanky/avatar_3_'.$_REQUEST['id'].'.jpg', THUMB_PICTURE_QUALITY);

		//resize a ulozenie
		$src_img  = imagecreatefromjpeg('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg');
		$size_img = getimagesize('../clanky/avatar_'.$_REQUEST['resizing'].'_'.$_REQUEST['id'].'.jpg');
		$thumb_height = $size_img[1] / ( $size_img[0] / $avatar[4]['width'] );
		$dst_img_thumb = imageCreateTrueColor($avatar[4]['width'],$avatar[4]['height']);
		imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $avatar[4]['width'], $avatar[4]['height'], $size_img[0], $size_img[1]);
		imagejpeg($dst_img_thumb, '../clanky/avatar_4_'.$_REQUEST['id'].'.jpg', THUMB_PICTURE_QUALITY);
		
	}

	$nextResizing = true;
	if($_REQUEST['resizing']>1){
		$nextResizing = false;
	}

	echo '
	<form action="'.$_SERVER['PHP_SELF'].'" method="post" name="form" id="form">
		'.($nextResizing?'<input type="hidden" name="state" value="resize" />':'<input type="hidden" name="state" value="finishtotal" />').'
		<input type="hidden" name="resizing" value="'.($_REQUEST['resizing']+1).'" />
		<input type="hidden" name="id" value="'.$_REQUEST['id'].'" />
 		<input type="submit" value="Pokračovať" />
	</form>
	<script>
	<!--
		jQuery(window).load(function(){
		document.forms[0].submit();
	});
	-->
	</script>
';

}elseif($_REQUEST['state']=='finishtotal'){

	//este musim odstranit tmp
	unlink('../fotoalbumy/tmp'.$_REQUEST['id'].'.jpg');

	echo '
		Avatar 1: <img src="../clanky/avatar_1_'.$_REQUEST['id'].'.jpg" /><br /><br />
		Avatar 2: <img src="../clanky/avatar_2_'.$_REQUEST['id'].'.jpg" /><br /><br />
		Avatar 3: <img src="../clanky/avatar_3_'.$_REQUEST['id'].'.jpg" /><br /><br />
		Avatar 4: <img src="../clanky/avatar_4_'.$_REQUEST['id'].'.jpg" /><br /><br />
		<input type="button" value="Zatvor okno" onclick="window.close();" />';


}
echo '
  </form>
 </body>
</html>';
ob_end_flush();
?>