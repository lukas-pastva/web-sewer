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

if(strlen($_REQUEST['uniqueFilename'])==0){
	$uniqueFilename = '../clanky-foto/'.md5(microtime()).'.jpg';
}else{
	$uniqueFilename = $_REQUEST['uniqueFilename'];
}

if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
} else {
	if($_REQUEST['type']==3){
		if($_FILES['_subor']['size']<(int)ini_get('post_max_size')*1024*1024){
			if(mb_substr($_FILES['_subor']['name'], -4)=='.jpg'){

				//if image width is less than 660 px, increase to 660px
				$size_img = getimagesize($_FILES['_subor']['tmp_name']);
				if($size_img[0]<ARTICLE_IMAGE_WIDTH){
					$src_img  = imagecreatefromjpeg($_FILES['_subor']['tmp_name']);
					$dst_height = (ARTICLE_IMAGE_WIDTH/$size_img[0])*$size_img[1];
					$dst_img_tmp = imageCreateTrueColor(ARTICLE_IMAGE_WIDTH, $dst_height);
					imagecopyresampled($dst_img_tmp, $src_img, 0, 0, 0, 0, ARTICLE_IMAGE_WIDTH, $dst_height, $size_img[0], $size_img[1]);
					imagejpeg($dst_img_tmp, $uniqueFilename, BIG_PICTURE_QUALITY);
				}else{
					copy($_FILES['_subor']['tmp_name'], $uniqueFilename);
				}
			} else {
				$err = 'Súbor musí mať príponu .jpg, zatvor toto okno a opaku akciu.';
			}
		} else {
			$err = 'Súbor nesmie byť väčší ako '.ini_get('post_max_size').'resize, zatvor toto okno a opaku akciu.';
		}
	}
}
/*********************************************************************************************/

echo '<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="admin_style.css">
  <link rel="stylesheet" type="text/css" href="../resources/photo_uploader.css">
  <script type="text/javascript" src="../resources/admin.js"></script>  
  <script type="text/javascript" src="../resources/photo_uploader.js"></script>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>resizer</title>
 </head>
 <body style="background-image: none; background-color: #ccc;" class="resizer">
<form action="'.$_SERVER['PHP_SELF'].'" method="post" enctype="multipart/form-data" id="form-resize">
<span style="color: #bf0000;">'.$err.'</span>
<input type="hidden" value="" id="openinger" name="openinger"/>';

if($size_img[1]>0){
	$pomer=$size_img[0]/$size_img[1];
}
if($_REQUEST['type']==1){
	echo '
	<script language="JavaScript" type="text/javascript"><!--		
			//$(document).ready(function() {
					     		
	     		$("#openinger").change(function (){				
	       			//alert($("#openinger").val());
	       			
	       			window.parent.tmpValue = $("#openinger").val();
					window.parent.$(\'textarea\').insertRoundCaret(\'img_resize_btn\');	       			
	     		});
	     		
			//});		
		 //-->
		 </script>	
	<img style="margin: -8px 0 0 -8px;" src="pics/btn-img-resize-3.png" class="button" value="Vlož obrazok do článku" id="img_resize_btn" alt="Article image" title="Vyber foto, ktore nasledne resizni a stlač ok:-)"  onclick="insertResizedImage()" />';
}elseif($_REQUEST['type']==2){

	echo '<strong>Nahram obrazok, potom si ho budes moct resiznut ako sa Ti bude pacit...</strong><br />
		<script language="JavaScript" type="text/javascript"><!--		
			$(document).ready(function() {
				document.getElementById(\'_subor\').click();
				
				$("#_subor").change(function (){				
	       			if($(this).val()!=""){
	       				//submit form and send to type 3
	       				$("#form-resize").submit();
	       			}       
	     		});
	     			     		
			});		
		 //-->
		 </script>		 
		 <input type="file" name="_subor" size="40" id="_subor" style="display: none;" />	 
		 <input type="hidden" name="type" value="3" />
		 <input type="hidden" name="uniqueFilename" value="'.$uniqueFilename.'" />
		 ';

}elseif($_REQUEST['type']==3){


	$size_img = getimagesize($uniqueFilename);

	//big_image
	echo '
	 <style type="text/css">
	 	.css_zmenafoto .css_nahlad p,.css_zmenafoto .css_nahlad p span {
		width: 120px;
		height: '.(120/$pomer).'px;
	}
 </style>
 <strong>Resizni obrazok ako sa Ti paci</strong><br />
<div class="css_superupload css_zmenafoto" style="width: 600px; height: 420px;">
<form action="'.$_SERVER['PHP_SELF'].'" method="post">
<fieldset>

<div class="css_plocha">
<div class="css_fotka">
<img src="'.$uniqueFilename.'" alt="fotka" id="cropbox" />
</div>
<script language="Javascript">
jQuery(window).load(function(){
jQuery(\'#cropbox\').Jcrop({
onChange: showPreview,
onSelect: showPreview,
bgOpacity: .6,
minSize: ['.ARTICLE_IMAGE_WIDTH/($size_img[0]/ARTICLE_IMAGE_WIDTH).', '.(20).'],
setSelect: [ 0, 0, '.$size_img[0].', '.$size_img[1].' ],
boxWidth: '.(ARTICLE_IMAGE_WIDTH-70).', boxHeight: '.(ARTICLE_IMAGE_WIDTH/$pomer).'
});
});
function showPreview(coords) {
showCoords(coords);
var rx = '.ARTICLE_IMAGE_WIDTH.' / coords.w;
var ry = '.(($size_img[0]/$pomer)).' / coords.h;
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
<input type="hidden" name="width" id="width" />
<input type="hidden" name="height" id="height" />
<input type="hidden" name="type" value="4" />
<input type="hidden" name="uniqueFilename" value="'.$uniqueFilename.'" />
<input type="submit" value="Pokračovať" />
</div>
';

}elseif($_REQUEST['type']==4){

	$src_img  = imagecreatefromjpeg($uniqueFilename);
	$size_img = getimagesize($uniqueFilename);

	$dstHeight = ((ARTICLE_IMAGE_WIDTH/$_REQUEST['width'])*$_REQUEST['height']);
	$dst_img = imageCreateTrueColor(ARTICLE_IMAGE_WIDTH, $dstHeight);
	
	imagecopyresampled($dst_img, $src_img, 0, 0, $_REQUEST['x1'], $_REQUEST['y1'], ARTICLE_IMAGE_WIDTH, $dstHeight, $_REQUEST['width'], $_REQUEST['height']);
	imagejpeg($dst_img, $uniqueFilename, BIG_PICTURE_QUALITY);

	//zatvorit okno onload a predtym vratit data o uroven vyssie
	echo '
		<script language="JavaScript" type="text/javascript"><!--		
			$(document).ready(function() {
				window.close();
				window.opener.$("#openinger").val("'.$uniqueFilename.'").trigger(\'change\');		
			});		
		 //-->
		 </script>	
	';

}

echo '
  </form>
 </body>
</html>';
ob_end_flush();
?>