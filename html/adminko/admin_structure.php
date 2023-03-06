<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}

/*********************************************************************************************/

echo '<h3>Štruktúra</h3>';

/*********************************************************************************************/
if(echoErrors($_REQUEST)){
	echo echoErrors($_REQUEST);
	$_REQUEST['state'] = 'detail';
	// vlozit clanok
} else {
	if($_REQUEST['action']=='save'){
		//update alebo new
		if($_REQUEST['structure_id']>0){
			$name;
			$fullname;
			$keywords;
			foreach($lang as $key => $langItem){
				$name .= 'name_'.$langItem.'= ';
				$fullname .= 'fullname_'.$langItem.'= ';
				$name .= '"'.($_REQUEST['_name_'.$langItem]).'", ';
				$fullname .= '"'.($_REQUEST['_fullname_'.$langItem]).'", ';
				$keywords .= 'keywords_'.$langItem.'= ';
				$keywords .= '"'.($_REQUEST['keywords_'.$langItem]).'", ';

			}

			$sql = '
				UPDATE structure SET 
				 '.$name.'
				 '.$fullname.'
				 '.$keywords.'
				 parent_id = "'.$_REQUEST['_parent'].'", 
				 position = "'.$_REQUEST['_position'].'" 
				 WHERE structure_id = '.$_REQUEST['structure_id'];

			psw_mysql_query($sql);

			//nebudem realizovat, musim hold vzdy rucne presunut
			/*if($_REQUEST['_parent'] != $_REQUEST['old_parent']){
				alert("Vykonal si najzlozitejsiu operaciu v systeme. Nieje realizovatelne presuvat vsetky fotoalbumy vsetkych clankov v sekcii. Bud vrat operaciu spat, alebo rucne nahraj vsetky fotoalbumy cez ftp na svoj PC a potom podla spravneho ID cez FTP naspat.");
			}*/
				

		}else{
			//ci uz nahodou neexistuje
			if ( ! psw_mysql_fetch_array( psw_mysql_query('SELECT structure_id FROM structure WHERE name_sk = "'.$_REQUEST['_name_sk'].'" ') ) ) {
					
				$nazov;
				$fullnazov;
				$keywords;
				foreach($lang as $key => $langItem){
					$nazov[0] .= 'name_'.$langItem.', ';
					$nazov[1] .= '"'.($_REQUEST['_name_'.$langItem]).'", ';
					$fullnazov[0] .= 'fullname_'.$langItem.', ';
					$fullnazov[1] .= '"'.($_REQUEST['_fullname_'.$langItem]).'", ';
					$keywords[0] .= 'keywords_'.$langItem.', ';
					$keywords[1] .= '"'.($_REQUEST['keywords_'.$langItem]).'", ';
				}
				$sql='
				INSERT INTO structure 
				(
					'.$nazov[0].'					
					'.$fullnazov[0].'
					'.$keywords[0].'
					position, 
					parent_id
				) 
				VALUES 
				(
					'.$nazov[1].'
					'.$fullnazov[1].'
					'.$keywords[1].'
					"'.$_REQUEST['_position'].'", 
					"'.($_REQUEST['_parent']).'" 
				) ';

				psw_mysql_query($sql);

				$id;
				if($id = psw_mysql_fetch_array(psw_mysql_query('SELECT * FROM structure ORDER BY structure_id DESC LIMIT 0, 1'))){
					$id = $id['structure_id'];
				}else{
					$id = 1;
				}
				$sql = 'ALTER TABLE `user` ADD `str_' .$id. '` TINYINT NOT NULL DEFAULT "0" ';
				psw_mysql_query($sql);


			} else {
				alert("Uzol s rovnakym nazvom uz existuje!");
			}
		}
	}
	if($_REQUEST['action']=='delete'){
		//ak su v nej clanky
		if ( ! psw_mysql_fetch_array( psw_mysql_query('SELECT * FROM clanok c WHERE c.structure_id = "' .$_REQUEST['structure_id']. '"') ) ){
			if ( ! psw_mysql_fetch_array( psw_mysql_query('SELECT * FROM structure WHERE parent_id = "' .$_REQUEST['structure_id']. '" ') ) ){

				$sql = 'DELETE FROM structure WHERE structure_id = '.$_REQUEST['structure_id'];
				psw_mysql_query($sql);
				$sql = 'ALTER TABLE user DROP str_' .$_REQUEST['structure_id']. ' ';
				psw_mysql_query($sql);
			} else {
				alert("Uzol obsahuje poduzly ktore treba najprv vymazat!");
			}
		} else {
			alert("Uzol obsahuje clanky, alebo fotoalbumy ktore treba najprv vymazat!");
		}
	}
	if(mysql_error()){echo mysql_error();}
}
/*********************************************************************************************/
// LIST
if($_REQUEST['state']=='detail'){

	//ak je id tak nacitam
	$data;
	if(is_numeric($_REQUEST['structure_id'])){
		$data = getTableRow('structure', 'structure_id', $_REQUEST['structure_id']);
		$data = $data[0];
	}

	//toto je detail uzlu, tu sa bude vkladat novy uzol, alebo upravovat stavajuci, zalezi od predaneho id a statusuu
	echo '
		<form action="'.$_SERVER['php_self'].'?sekcia=structure" method="post">
		 <fieldset><legend><b>&nbsp;'.(is_numeric($data['parent_id'])?'Upraviť uzol (id:'.$data['structure_id'].')':'Vložiť nový uzol').'&nbsp;</b></legend>
	        <input type="hidden" name="action" value="save" />
	        <input type="hidden" name="state" value="list" />
	        <input type="hidden" name="old_parent" value="'.$data['parent_id'].'" />	        
	        <input type="hidden" name="structure_id" value="'.$_REQUEST['structure_id'].'" />
			<table>
			 <tr><td style="width:160px;">&nbsp;</td><td>&nbsp;</td></tr>';

	//nemozem vypisat tie co su potomkovia

	$tree = transformTreeArray(getTreeArray());
	writeFormObject('Rodič', 'select', '_parent', ($_REQUEST['_parent']?$_REQUEST['_parent']:$data['parent_id']), false, '', $tree, true, false);

	$value=Array();
	foreach($lang as $langItem){$value[$langItem] = ($_REQUEST['_name_'.$langItem]?$_REQUEST['_name_'.$langItem]:$data['name_'.$langItem]);}
	writeFormObject('Názov uzlu', 'text', '_name', $value, true);

	$value=Array();
	foreach($lang as $langItem){$value[$langItem] = ($_REQUEST['_fullname_'.$langItem]?$_REQUEST['_fullname_'.$langItem]:$data['fullname_'.$langItem]);}
	writeFormObject('Plný názov uzlu', 'text', '_fullname', $value, true);

	$value=Array();
	foreach($lang as $langItem){$value[$langItem] = ($_REQUEST['keywords_'.$langItem]?$_REQUEST['keywords_'.$langItem]:$data['keywords_'.$langItem]);}
	writeFormObject('Keywords', 'text', 'keywords', $value, true);

	writeFormObject('Pozícia', 'text', '_position', ($_REQUEST['_position']?$_REQUEST['_position']:($data['position']?$data['position']:'10')));

	echo '
			<tr><td colspan=2"><input type="submit" value="'.(is_numeric($data['parent_id'])?'Ulož zmeny':'Vlož nový uzol').'" /></td></tr>
			</table>
		   </fieldset>
	      </form><br />';
	if($_REQUEST['structure_id']>0){
		echo '
		  <form action="'.$_SERVER['php_self'].'?sekcia=structure" method="post">
		   <fieldset><legend><b>&nbsp;Zmazať uzol&nbsp;</b></legend>
	        <input type="hidden" name="state" value="list" />
	        <input type="hidden" name="action" value="delete" />
	        <input type="hidden" name="structure_id" value="'.$_REQUEST['structure_id'].'" />
			<input type="submit" value="Zmazať uzol" onClick="if(!confirm(\'Ste si istý, že chcete zmazať uzol?\')){return false;}"/>
		   </fieldset>
	      </form>';
	}


} else {

	//toto je list stromu, tu sa vypise cela stromova struktura pekne urobena s tym ze sa bude dat u kazdeho upravit, zmazat bude vovnutri

	//naspodu bude tlacitko pre vlozenie noveho uzlu
	$tree = transformTreeArray(getTreeArray());

	//vypisem si kazdy zaznam, v kazdom zazneme vypisem kazdy uzol ako blok, ale nazov len u posledneho
	echo '
	<div class="adm_tree">';

	foreach($tree as $key => $treeItem){
		echo '<div class="tree_row">';
		$treeItemItem = explode('/', $key);
		for($i=1; $i<(count($treeItemItem)-1); $i++){
			if($i==(count($treeItemItem)-2)){
				echo '<div class="tree_item"><a href="'.$_SERVER['php_self'].'?sekcia=structure&amp;state=detail&amp;structure_id='.$treeItem.'">'.$treeItemItem[$i].'</a></div>';
			}else{
				echo '<div class="tree_item_empty">&nbsp;</div>';
			}

		}
		echo '<div class="cleaner"></div>';
		echo '</div>';
		echo '<div class="cleaner"></div>';
	}

	echo '
	</div>
	<br />
	<form action="'.$_SERVER['php_self'].'?sekcia=structure" method="post">
	        <input type="hidden" name="action" value="new" />
	        <input type="hidden" name="state" value="detail" />
			<input type="submit" value="Vložiť nový uzol" />
	      </form>';
}

?>