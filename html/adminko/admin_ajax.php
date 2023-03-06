<?php

session_cache_expire(60);
session_start();
$_SESSION['meno_uzivatela']="asdf";

ob_start();
/*********************************************************************************************/
if ( ! $_SESSION['meno_uzivatela'] ) {
	header("location: admin_login.php");
	die;
}
/*********************************************************************************************/
include_once('../db.inc.php');
include_once('admin_functions.php');


if($_REQUEST['typ']=='clanok_suvisiace_insert'){

	psw_mysql_query($sql='INSERT INTO clanok_suvisiace (clanok_id, clanok_id_suvisiace) VALUES ("'.$_REQUEST['clanok_id'].'", "'.$_REQUEST['clanok_id_suvisiace'].'")');
	clankySuvisiace($_REQUEST['clanok_id']);

}elseif($_REQUEST['typ']=='clanok_suvisiace_delete'){

	psw_mysql_query($sql='DELETE FROM clanok_suvisiace WHERE clanok_suvisiace_id = "'.$_REQUEST['clanok_suvisiace_id'].'" ');
	clankySuvisiace($_REQUEST['clanok_id']);

}elseif($_REQUEST['typ']=='clanok_suvisiace_search'){

	//najdem a potom iba vypisem riadky s onclick
	if(str_replace(' ','',$_REQUEST['text'])!=''){
		$sql = 'SELECT * FROM clanok WHERE nazov_sk LIKE "%'.$_REQUEST['text'].'%" AND clanok_id != "'.$_REQUEST['clanok_id'].'" ORDER BY datetime, nazov_sk DESC';
		$searchArr = Array();
		$query = psw_mysql_query($sql);
		while($data = psw_mysql_fetch_array($query)) $searchArr[] =  $data;

		foreach($searchArr as $searchArrItem){
			//nevypisem ten isty
			if($searchArrItem['clanok_id_suvisiace']!=$_REQUEST['clanok_id']){
				//nevypisem ten co uz obsahuje
				$pocetTmp = mysql_fetch_Array(psw_mysql_query($sql='SELECT count(*) AS pocet FROM clanok_suvisiace WHERE clanok_id = "'.$_REQUEST['clanok_id'].'" AND clanok_id_suvisiace = "'.$searchArrItem['clanok_id'].'" '));
				if($pocetTmp['pocet']==0){
					echo '<a href="#" onclick="doSuvisiaciClanok(\''.$_REQUEST['clanok_id'].'\', \''.$searchArrItem['clanok_id'].'\', \'clanok_suvisiace_insert\', \'0\'); document.getElementById(\'nasepkavac\').style.display = \'none\'; return false;"><span><b>'.$searchArrItem['nazov_sk'].'</b> ('.substr($searchArrItem['datetime'],0,-3).')</span></a>';
				}
			}
		}
	}

}else if($_REQUEST['typ']=='clanok_avatar_delete'){

	if(file_exists('../clanky/avatar_'.$_REQUEST['avatar_typ'].'_'.$_REQUEST['clanok_id'].'.jpg')){
		unlink('../clanky/avatar_'.$_REQUEST['avatar_typ'].'_'.$_REQUEST['clanok_id'].'.jpg');
	}
	if($_REQUEST['avatar_typ']==3) {
		if(file_exists('../clanky/avatar_4_'.$_REQUEST['clanok_id'].'.jpg')){
			unlink('../clanky/avatar_4_'.$_REQUEST['clanok_id'].'.jpg');
		}
		psw_mysql_query('UPDATE clanok SET banner = 0 WHERE clanok_id = "'.$_REQUEST['clanok_id'].'"  ');
	}

}elseif($_REQUEST['typ']=='fotografie_vymaz'){

	$picture_info1 = psw_mysql_fetch_array(psw_mysql_query('SELECT * FROM picture WHERE picture_id = "' .$_REQUEST['id']. '" '));

	$dir = "../fotoalbumy/alb_".$_REQUEST['clanok_id'].'/';
                                                        
	unlink($dir.'/'.$picture_info1['filename']);
	unlink($dir.'/thumbs/'.$picture_info1['filename']);


	if(psw_mysql_query('DELETE FROM picture WHERE picture_id ="' .$_REQUEST['id']. '" ')){
		echo $_REQUEST['id'].'|index.php?sekcia=clanok_list&state=detail&id='.$_REQUEST['clanok_id'].'#fotoalbumHead';
	}

}elseif($_REQUEST['typ']=='fotografie_zorad'){

	$ids=explode('|', $_REQUEST['pole']);

	foreach($ids as $key => $id){
		psw_mysql_query('UPDATE picture SET ordering = "'. ($key+1).'" WHERE picture_id = "' .$id. '" ');
	}
}elseif($_REQUEST['typ']=='clanok_zdroj_insert'){

	psw_mysql_query($sql='INSERT INTO clanok_zdroj (clanok_id, zdroj) VALUES ("'.$_REQUEST['clanok_id'].'", "'.$_REQUEST['zdroj'].'")');
	clankyZdroj($_REQUEST['clanok_id']);

}elseif($_REQUEST['typ']=='clanok_zdroj_delete'){

	psw_mysql_query($sql='DELETE FROM clanok_zdroj WHERE zdroj = "'.$_REQUEST['zdroj'].'" AND clanok_id = "'.$_REQUEST['clanok_id'].'" ');
	clankyZdroj($_REQUEST['clanok_id']);

}elseif($_REQUEST['typ']=='clanok_zdroj_search'){

	//najdem a potom iba vypisem riadky s onclick
	if(str_replace(' ','',$_REQUEST['text'])!=''){
		$sql = 'SELECT * FROM clanok_zdroj WHERE zdroj LIKE "'.$_REQUEST['text'].'%" GROUP BY zdroj ORDER BY zdroj DESC';
		$searchArr = Array();
		$query = psw_mysql_query($sql);
		while($data = psw_mysql_fetch_array($query)) $searchArr[] =  $data;

		foreach($searchArr as $searchArrItem){
			//nevypisem ten co uz obsahuje
			$pocetTmp = mysql_fetch_Array(psw_mysql_query($sql='SELECT count(*) AS pocet FROM clanok_zdroj WHERE clanok_id = "'.$_REQUEST['clanok_id'].'" AND zdroj = "'.$searchArrItem['zdroj'].'" '));
			if($pocetTmp['pocet']==0){
				echo '<a href="#" onclick="doZdrojClanok(\''.$_REQUEST['clanok_id'].'\', \''.$searchArrItem['zdroj'].'\', \'clanok_zdroj_insert\'); document.getElementById(\'zdroj\').style.display = \'none\'; return false;"><span>'.$searchArrItem['zdroj'].'</span></a>';
			}
		}
	}

}
ob_end_flush();

?>