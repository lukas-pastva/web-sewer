<?php
//session_cache_limiter('private');
session_cache_expire(60);
session_start();


ob_start();
/*********************************************************************************************/
if ( ! $_SESSION['meno_uzivatela'] ) {
	header("location: admin_login.php");
	die;
}
/*********************************************************************************************/
include_once('../db.inc.php');
include_once('admin_functions.php');


echo '
<!doctype html public "-//w3c//dtd html 4.01 transitional//en">
<html>
 <head>
  <link rel="stylesheet" type="text/css" href="admin_style.css">
  <link rel="stylesheet" type="text/css" href="../resources/calendar.css">
  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
	/*<![CDATA[*/
	google.load("jquery","1.5.2");
	google.load("jqueryui","1.8.14");
	/*]]>*/
	</script>
  <script type="text/javascript" src="../resources/uploader/swfupload.js"></script>
  <script type="text/javascript" src="../resources/uploader/swfupload.queue.js"></script>
  <script type="text/javascript" src="../resources/uploader/fileprogress.js"></script>
  <script type="text/javascript" src="../resources/uploader/handlers.js"></script>
  <link rel="stylesheet" type="text/css" href="../resources/uploader/default.css">
    <script language="javascript" type="text/javascript" src="../resources/admin.js"></script>
	<script language="javascript" type="text/javascript" src="../resources/js.js"></script>
	<script language="javascript" type="text/javascript" src="../resources/uff.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/core.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/events.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/css.js"></script>';
	if($_REQUEST['sekcia']=='clanok_list'){
		if($_REQUEST['id']>0){
			echo '
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/coordinates.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/drag.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/dragsort.js"></script>
	<script language="JavaScript" type="text/javascript" src="../resources/tool-man/cookies.js"></script>
	<script language="JavaScript" type="text/javascript"><!--
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
	}

	function speak(id, what) {
		var element = document.getElementById(id);
		element.innerHTML = \'Clicked \' + what;
	}

	function saveOrder(item) {
		var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register(\'dragend\', function() {
			ToolMan.cookies().set("list-" + id, 
					junkdrawer.serializeList(list), 365)
		})
	}
	     	
	$(document).ready(function() {      
		$("#bold_btn").click(function(){$(\'textarea\').insertRoundCaret(\'strong\')});
		$("#italic_btn").click(function(){$(\'textarea\').insertRoundCaret(\'i\')});
		$("#underline_btn").click(function(){$(\'textarea\').insertRoundCaret(\'u\')});
		$("#image_btn").click(function(){$(\'textarea\').insertRoundCaret(\'img\')});
		$("#href_btn").click(function(){$(\'textarea\').insertRoundCaret(\'a\')});
		$("#yt_btn").click(function(){$(\'textarea\').insertRoundCaret(\'iframe\')});	

		junkdrawer.restoreListOrder("fotografie");
		dragsort.makeListSortable(document.getElementById("fotografie"), saveOrder);
				
	});

	//-->
	</script>';
		}
	}
	echo '
	<script language="JavaScript" type="text/javascript"><!--
    function getPath(){
      return \''.$path.'\';
    }
    
    	function insertext(text,area){
     		document.getElementById(area).focus();
     		document.getElementById(area).value=document.getElementById(area).value +" "+ text;
     		document.getElementById(area).focus() 
    	}	
	//--></script>
    
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Administrácia ['.SITENAME.']</title>
 </head>
 <body>
  <div class="sajtah">
   <div class="top">
    
    <a href="index.php?sekcia=main" class="admin_home"><span>Home</span></a>
   </div>
   <div class="menu">';


$data = psw_mysql_fetch_array( psw_mysql_query('SELECT * FROM user WHERE nick = "' .$_SESSION['meno_uzivatela']. '" ') );

echo ($data['clanok'       ] == "1"?'<a href="index.php?sekcia=clanok_list&amp;action=list"  rel="external"       >Články</a><br />':'');
echo ($data['clanok'       ] == "1"?'<a href="index.php?sekcia=clanok_najcitanejsie&amp;action=list"   >Najčít.&nbsp;články</a><br /><br />':'');

echo ($data['partylist'    ] == "1"?'<a href="index.php?sekcia=partylist"           >Party list</a><br /><br />':'');

echo '<a href="index.php?sekcia=users"             >Užívateľ</a><br /><br />';
//echo '<a href="index.php?sekcia=sablony"           >Šablóny </a><br /><br />';

echo ($data['structure'    ] == "1"?'<a href="index.php?sekcia=flyer"               >Flyer</a><br />':'');
echo ($data['structure'    ] == "1"?'<a href="index.php?sekcia=banner"              >Banner 468 x 90</a><br />':'');
echo ($data['structure'    ] == "1"?'<a href="index.php?sekcia=banner2"              >Banner 468 x 60</a><br />':'');
echo ($data['structure'    ] == "1"?'<a href="index.php?sekcia=banner3"              >Banner 300 x 250</a><br />':'');

echo ($data['odkazy'       ] == "1"?'<br /><a href="index.php?sekcia=odkazy"              >Partneri</a><br />':'');
echo ($data['banlist'      ] == "1"?'<a href="index.php?sekcia=banlist"             >Ban List</a><br />':'');
echo ($data['structure'    ] == "1"?'<a href="index.php?sekcia=structure"           >Štruktúra</a><br />':'');
echo ($data['userlogin'    ] == "1"?'<a href="index.php?sekcia=user_login"          >Admin počítadlo</a><br />':'');
echo '<br /><br /><a href="../sekcia/home" target="_blank" style="float: left">Verejné rozhranie</a>
<br /><a href="index.php?sekcia=logout"              >Odhlásiť sa</a><br />

    </div>
    <div class="site">';

if($_REQUEST['warning']){
	alert($_REQUEST['warning']);
}

if($_REQUEST['sekcia']){
	if(is_file('admin_'.$_REQUEST['sekcia'].'.php')){
		include('admin_'.$_REQUEST['sekcia'].'.php');
	}
} else {
	include("admin_clanok_list.php");
}

echo '
    </div>
  </div>
 </body>
</html>';

ob_end_flush();

?>