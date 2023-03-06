<?
include_once('../db.inc.php');
include_once('admin_functions.php');
/*$f = fopen('bbb'.time(), 'w+');
fwrite($f, print_r($_REQUEST, true), strlen(print_r($_SERVER, true)));
fclose($f);
*/
if ($_REQUEST['id']){

	foreach($_FILES as $filee){


		//Ak uz dany obrazok existuje
		if (psw_mysql_fetch_array(psw_mysql_query('SELECT * FROM picture WHERE filename  = "' .normalizeFilename($filee['name']). '" AND clanok_id = "' .$_REQUEST['id']. '" '))){
			//alert("Takato foto uz existuje");
		} else {

			//Cesta k fotkam
			$dir = "../fotoalbumy/alb_".$_REQUEST['id'].'/';


			$filename_norm  = $dir.normalizeFilename($filee['name']);
			$filename_thumb = $dir."thumbs/".normalizeFilename($filee['name']);

			//Ak sa nepodari nahrat subor
			if ( ! (move_uploaded_file($filee['tmp_name'],$filename_norm) || (copy($filename_norm,$filename_thumb)) ) ){
				//die;
			}

			//Zmena rozlisenia obrazku
			$src_img  = imagecreatefromjpeg($filename_norm);
			$size_img = getimagesize($filename_norm);

			//Praca s thumbnailom
			/*$thumb_width = $size_img[0] / ( $size_img[1] /THUMB_PICTURE_HEIGHT );
			 $dst_img_thumb = imageCreateTrueColor($thumb_width,THUMB_PICTURE_HEIGHT);
			 imagecopyresampled($dst_img_thumb, $src_img, 0, 0, 0, 0, $thumb_width, THUMB_PICTURE_HEIGHT, $size_img[0], $size_img[1]);
			 imagejpeg($dst_img_thumb, $filename_thumb, THUMB_PICTURE_QUALITY);
			 */


			//avatar 1
			//unlink('../fotoalbumy/alb_'.$img['clanok_id'].'/thumbs/'.$img['filename'].'');
			//$src_img = imagecreatefromjpeg('../fotoalbumy/alb_'.$img['clanok_id'].'/'.$img['filename'].'');
			//$size_img = getimagesize('../fotoalbumy/alb_'.$img['clanok_id'].'/'.$img['filename'].'');
			//$srcY = ($size_img[1]/2)-(FOTOALBUM_THUMBNAIL_HEIGHT/2);
			$avatarRandName = '../foto/tmp_avatar_'.rand(10,100).'.jpg';
			
			//1.resize
			$pomer = $size_img[0]/FOTOALBUM_THUMBNAIL_WIDTH;
			$dst_img = imageCreateTrueColor(FOTOALBUM_THUMBNAIL_WIDTH, $size_img[1]/$pomer );
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, FOTOALBUM_THUMBNAIL_WIDTH, $size_img[1]/$pomer, $size_img[0], $size_img[1]);
			imagejpeg($dst_img, $avatarRandName, THUMB_PICTURE_QUALITY);


			//2. crop
			$src_img2 = imagecreatefromjpeg($avatarRandName);

			$dst_img = imageCreateTrueColor(FOTOALBUM_THUMBNAIL_WIDTH, FOTOALBUM_THUMBNAIL_HEIGHT );
			imagecopyresampled($dst_img, $src_img2, 0, 0, 0, 0, FOTOALBUM_THUMBNAIL_WIDTH, FOTOALBUM_THUMBNAIL_HEIGHT, FOTOALBUM_THUMBNAIL_WIDTH, FOTOALBUM_THUMBNAIL_HEIGHT);
			imagejpeg($dst_img, $filename_thumb, THUMB_PICTURE_QUALITY);
			unlink($avatarRandName);



			//otestujem ci sa ide resizovat a rozvetvim
			if($_REQUEST['resize'] == 'on'){
				if( (!is_numeric($_REQUEST['width_1'])) || (!is_numeric($_REQUEST['width_2'])) ){
					$_REQUEST['width_1'] = BIG_PICTURE_WIDTH;
					$_REQUEST['width_2'] = BIG_PICTURE_WIDTH_2;
				} else {

					//zistim orientaciu
					$naSirku = false;
					if( ($size_img[0]/$size_img[1])>1 ){
						$naSirku = true;
					}


					if($naSirku && ($size_img[0]>$_REQUEST['width_1'])){

						//na sirku
						$big_height = $size_img[1] / ( $size_img[0] / $_REQUEST['width_1'] );
						$dst_img_big = imageCreateTrueColor($_REQUEST['width_1'], $big_height);
						imagecopyresampled($dst_img_big, $src_img, 0, 0, 0, 0, $_REQUEST['width_1'], $big_height, $size_img[0], $size_img[1]);
						imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
						imagedestroy($dst_img_big);
						imagedestroy($src_img);
						if($_REQUEST['logo']=="yes"){
							//podla pozicie musim urcit oneee
							$src_img_logo  = imagecreatefrompng('../img/logo_img.png');
							$size_img_logo = getimagesize('../img/logo_img.png');
							$dst_img_big  = imagecreatefromjpeg($filename_norm);
							$dst_img_big_size = getimagesize($filename_norm);
							$logo_pos_x = 7;
							$logo_pos_y = 7;
							if($_REQUEST['logo_position']=='a'){
								$logo_pos_x = 7;
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='b'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='c'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}elseif($_REQUEST['logo_position']=='d'){
								$logo_pos_x = 7;
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}

							imagecopy($dst_img_big, $src_img_logo, $logo_pos_x, $logo_pos_y, 0, 0, $size_img_logo[0], $size_img_logo[1]);
							imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
							imagedestroy($dst_img_big);
							imagedestroy($src_img_logo);
						}
					} else if(!$naSirku && ($size_img[0]>$_REQUEST['width_2'])){

						//na vysku
						$big_height = $size_img[1] / ( $size_img[0] / $_REQUEST['width_2'] );
						$dst_img_big = imageCreateTrueColor($_REQUEST['width_2'], $big_height);
						imagecopyresampled($dst_img_big, $src_img, 0, 0, 0, 0, $_REQUEST['width_2'], $big_height, $size_img[0], $size_img[1]);
						imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
						imagedestroy($dst_img_big);
						imagedestroy($src_img);
						if($_REQUEST['logo']){
							//podla pozicie musim urcit oneee
							$src_img_logo  = imagecreatefrompng('../img/logo_img.png');
							$size_img_logo = getimagesize('../img/logo_img.png');
							$dst_img_big  = imagecreatefromjpeg($filename_norm);
							$dst_img_big_size = getimagesize($filename_norm);
							$logo_pos_x = 7;
							$logo_pos_y = 7;
							if($_REQUEST['logo_position']=='a'){
								$logo_pos_x = 7;
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='b'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='c'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}elseif($_REQUEST['logo_position']=='d'){
								$logo_pos_x = 7;
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}

							imagecopy($dst_img_big, $src_img_logo, $logo_pos_x, $logo_pos_y, 0, 0, $size_img_logo[0], $size_img_logo[1]);
							imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
							imagedestroy($dst_img_big);
							imagedestroy($src_img_logo);
						}
					} else {

						//nerisajzuje sa
						$dst_img_big = imageCreateTrueColor($size_img[0], $size_img[1]);
						imagecopyresampled($dst_img_big, $src_img, 0, 0, 0, 0, $size_img[0], $size_img[1], $size_img[0], $size_img[1]);
						imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
						imagedestroy($dst_img_big);
						imagedestroy($src_img);
						if($_REQUEST['logo']){
							//podla pozicie musim urcit oneee
							$src_img_logo  = imagecreatefrompng('../img/logo_img.png');
							$size_img_logo = getimagesize('../img/logo_img.png');
							$dst_img_big  = imagecreatefromjpeg($filename_norm);
							$dst_img_big_size = getimagesize($filename_norm);
							$logo_pos_x = 7;
							$logo_pos_y = 7;
							if($_REQUEST['logo_position']=='a'){
								$logo_pos_x = 7;
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='b'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = 7;
							}elseif($_REQUEST['logo_position']=='c'){
								$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}elseif($_REQUEST['logo_position']=='d'){
								$logo_pos_x = 7;
								$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
							}

							imagecopy($dst_img_big, $src_img_logo, $logo_pos_x, $logo_pos_y, 0, 0, $size_img_logo[0], $size_img_logo[1]);
							imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
							imagedestroy($dst_img_big);
							imagedestroy($src_img_logo);
						}
					}
				}
			} else {
				$dst_img_big = imageCreateTrueColor($size_img[0], $size_img[1]);
				imagecopyresampled($dst_img_big, $src_img, 0, 0, 0, 0, $size_img[0], $size_img[1], $size_img[0], $size_img[1]);
				imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
				imagedestroy($dst_img_big);
				imagedestroy($src_img);
				if($_REQUEST['logo']){
					//podla pozicie musim urcit oneee
					$src_img_logo  = imagecreatefrompng('../img/logo_img.png');
					$size_img_logo = getimagesize('../img/logo_img.png');
					$dst_img_big  = imagecreatefromjpeg($filename_norm);
					$dst_img_big_size = getimagesize($filename_norm);
					$logo_pos_x = 7;
					$logo_pos_y = 7;
					if($_REQUEST['logo_position']=='a'){
						$logo_pos_x = 7;
						$logo_pos_y = 7;
					}elseif($_REQUEST['logo_position']=='b'){
						$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
						$logo_pos_y = 7;
					}elseif($_REQUEST['logo_position']=='c'){
						$logo_pos_x = $dst_img_big_size[0]-7-$size_img_logo[0];
						$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
					}elseif($_REQUEST['logo_position']=='d'){
						$logo_pos_x = 7;
						$logo_pos_y = $dst_img_big_size[1]-7-$size_img_logo[1];
					}

					imagecopy($dst_img_big, $src_img_logo, $logo_pos_x, $logo_pos_y, 0, 0, $size_img_logo[0], $size_img_logo[1]);
					imagejpeg($dst_img_big, $filename_norm, BIG_PICTURE_QUALITY);
					imagedestroy($dst_img_big);
					imagedestroy($src_img_logo);
				}
			}

			psw_mysql_query('INSERT INTO picture (clanok_id, filename) VALUES ( "' .$_REQUEST['id']. '", "' .normalizeFilename($filee['name']). '" ) ');
		}
	}
}

?>