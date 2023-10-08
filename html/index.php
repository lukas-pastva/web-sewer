<?PHP
error_reporting(E_ALL ^ E_WARNING);
session_start();
mb_internal_encoding('UTF-8');

if($_SERVER['REQUEST_URI']=='/'){
	header('location:/sekcia/home');
	die;
}
 $showFlyer = false;
if(!isset($_COOKIE['flyer']) == '1'){
	$showFlyer = true;
	setcookie('flyer', '1', (time()+(1800)), '/');
}
$start_time = microtime(true);

include_once('db.inc.php');

include_once(ADMIN_LOCATION."admin_functions.php");
$path = getPath();

$tree = transformTreeArray(getTreeArray());

if(isset($id[0])){$_REQUEST['id'] = $id[0];}

if(!isset($_REQUEST['from'])){$_REQUEST['from']=0;}
if(isset($_REQUEST['id'])){
	$keywords = getKeywords($_REQUEST['id']);
}
if(!isset($_COOKIE['clanky'])){
	//setcookie('clanky', ',', time()+(86400*92), '/');
}


if(isset($_REQUEST['a']) && $_REQUEST['a']=='clanokDisable'){
	$clanok_id = $_REQUEST['clanok_id'];
	clanokDisable($clanok_id);

	die;
}

if(isset($_REQUEST['a']) && $_REQUEST['a']=='clanokEnable'){
	$clanok_id = $_REQUEST['clanok_id'];
	clanokEnable($clanok_id);

	die;
}


if(isDetail()){

	if(!strpos($_COOKIE['clanky'], $_REQUEST['id']) ){
		//setcookie('clanky', '', time() - 3600);
		//setcookie('clanky', $_COOKIE['clanky'].$_REQUEST['id'].',', time()+(86400*92), '/');
	}

	//id is now set, i can get the name of the article and compare it to the URI. If part between clanok/ and # is different, then just simply redirect to original uri
	$clanokCorrectURL = $_REQUEST['id']. '-'.normalizeClanokName(getClanokNameFromId($_REQUEST['id']));

	$clanokActualURI = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'],'clanok/')+7);
	if( (strpos($_SERVER['REQUEST_URI'], '?')>0) || ($clanokActualURI!=$clanokCorrectURL) ){
		header('location:/clanok/'.$clanokCorrectURL);
	}
}

$_REQUEST['selectedlang']= 'sk';

$title = SITENAME.(isset($_REQUEST['id'])?' - '.getNazovClankuFromId($_REQUEST['id']):SITE_HOMETITLE);
$description = str_replace('"','\'',(isDetail()?getDescriptionForClanok($_REQUEST['id']):SITE_DESCRIPTION));

//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
//<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="sk">
//<head>

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml" dir="ltr" lang="sk">
	<head profile="http://gmpg.org/xfn/11">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-YQ72Q66F80"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-YQ72Q66F80');
    </script>
	<meta name="robots" content="index, follow" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="sk" />
	<meta name="Author" content="'.SITE_AUTHOR.'" />
	<meta name="Description" content="'.$description.'" />
	<meta name="Keywords" content="'.(isset($keywords)?$keywords:'sewer, graffiti, rap, skate, bike, snow, lifestyle').'" />
	<meta property="fb:admins" content="joSko.swr"/>
	<meta property="fb:app_id" content="344015495160"/>
	<meta property="og:locale" content="sk_sk"/>
	<meta property="og:site_name" content="'.SITENAME.'"/>
	<meta property="og:title" content="'.$title.'"/>
	<meta property="og:description" content="'.$description.'"/>
	<link href="'.$path.'rss.xml" rel="alternate" type="application/rss+xml" title="RSS kanal sewer.sk" />';


if(isset($_REQUEST['id'])){
	echo '<meta property="og:image" content="http://'.$_SERVER["SERVER_NAME"].'/clanky/avatar_1_'.$_REQUEST['id'].'.jpg"/>
		<meta property="og:url" content="http://www.sewer.sk/clanok/'.$_REQUEST['id'].(($_REQUEST['id']<=1429)&&($_REQUEST['id']>=1569)?'-'.normalizeClanokName(getClanokNameFromId($_REQUEST['id'])):'').'"/>
		<meta property="og:type" content="article"/>';
	//?
}

//
//<script type="text/javascript">document.write(unescape("%3Cscript src=\'" + (("https:" == document.location.protocol) ? "https" : "http") + "://e.mouseflow.com/projects/51a3a938-bbaf-4ec9-b6c0-3809672f07be.js\' type=\'text/javascript\'%3E%3C/script%3E"));</script>

echo '
	<link rel="icon" href="'.$path.'img/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="'.$path.'img/favicon.ico" type="image/x-icon" />
	<link href="'.$path.'resources/style.css?cache=3" rel="stylesheet" type="text/css" />
	<link href="'.$path.'resources/prettyPhoto.css" rel="stylesheet" type="text/css" />  
	<link href="'.$path.'resources/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
	<title>'.$title.'</title>  
	
	<script type="text/javascript" src="'.$path.'resources/swfobject.js"></script>
	<script type="text/javascript" src="'.$path.'resources/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="'.$path.'resources/jquery-ui.js"></script>
	
	<script type="text/javascript" src="'.$path.'resources/jquery.prettyPhoto.js"></script>
	<script type="text/javascript" src="'.$path.'resources/hover-intent.js"></script>
	<script type="text/javascript">
	var GB_ROOT_DIR = "'.$path.'resources/greybox/";
	
	var listingFrom = '.POCET_CLANKOV_NA_STRANU.';
	var documentHeight = 0;
	
	function getPath(){
	return \''.$path.'\';
	}
	</script>
	<script type="text/javascript" src="'.$path.'resources/js.js"></script>
	<script type="text/javascript" src="'.$path.'resources/greybox/AJS.js"></script>
	<script type="text/javascript" src="'.$path.'resources/greybox/AJS_fx.js"></script>
	<script type="text/javascript" src="'.$path.'resources/greybox/gb_scripts.js"></script>';

if(!isset($_REQUEST['structure_id'] )){
	$_REQUEST['structure_id'] = getSectionIdFromName(getSectionByClanokId($_REQUEST['id']));
}
if(isset($_REQUEST['structure_id']) && $_REQUEST['structure_id'] == 0){
	$_REQUEST['structure_id'] = getSectionIdFromName($_REQUEST['section']);
}
if(! $_REQUEST['section'] && $_REQUEST['id'] ){
	$_REQUEST['section'] = getSectionByClanokId($_REQUEST['id']);
}

echo '
	</head>
	<body'.(isDetail()?' class="'.getSectionByClanokId($_REQUEST['id'], true).'"':'').'>';

if($showFlyer&&!isDetail()){
	if(printFlyer()){die;}
}

echo '
	<div id="fb-root"></div>
	<script type="text/javascript">
	/*<![CDATA[*/ 
	
	(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/sk_SK/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, \'script\', \'facebook-jssdk\'));
	
	/*]]>*/
	</script>
	<div class="container">
	<div class="containerbg">
    <div id="header">
	<div id="logolink">'.(isDetail()?'<h2>':'<h1>').'<a href="'.$path.'sekcia/home"><span>www.sewer.sk</span></a>'.(isDetail()?'</h2>':'</h1>').'</div>
	';

printBanner(1);

echo '
	<div id="social">
	<div id="facebook"><a href="http://www.facebook.com/sewer.sk" rel="external"><span>facebook.com</span></a></div>
	<div id="vimeo"><a href="http://vimeo.com/user5812042" rel="external"><span>vimeo.com</span></a></div>
	<div id="rss"><a href="'.$path.'rss.xml" title="Odoberaj novinky cez kanál RSS"><span>rss</span></a></div>
	<div class="cleaner"></div>
	<a id="topmenu-redakcia" href="'.$path.'clanok/1035-o-nas" >Inzercia / Kontakt</a>     
	</div>
	<div class="cleaner"></div>
    </div>
    ',printTopMenu($_REQUEST['structure_id'], $tree),'
    <div id="searchbar">
	<span class="arrow"><span>arrow</span></span>
	
	<div id="submenu-home">
	<a href="'.$path.'sekcia/home" '.($_REQUEST['section']=='home'?'class="hover"':'').'>Všetko</a>
	<a href="'.$path.'sekcia/home_novinky" '.($_REQUEST['section']=='home_novinky'?'class="hover"':'').'>Novinky</a>
	<a href="'.$path.'sekcia/home_rozhovory" '.($_REQUEST['section']=='home_rozhovory'?'class="hover"':'').'>Rozhovory</a>
	<a href="'.$path.'sekcia/home_reportaze" '.($_REQUEST['section']=='home_reportaze'?'class="hover"':'').'>Reportáže</a>
	<a href="'.$path.'sekcia/home_sutaze" '.($_REQUEST['section']=='home_sutaze'?'class="hover"':'').'>Súťaže</a>			                                   
	</div>
	<div id="submenu-music">
	<a href="'.$path.'sekcia/music" '.($_REQUEST['section']=='music'?'class="hover"':'').'>Všetko</a>
	<a href="'.$path.'sekcia/music_novinky" '.($_REQUEST['section']=='music_novinky'?'class="hover"':'').'>Novinky</a>
	<a href="'.$path.'sekcia/music_reportaze" '.($_REQUEST['section']=='music_reportaze'?'class="hover"':'').'>Reportáže</a>
	<a href="'.$path.'sekcia/music_rozhovory" '.($_REQUEST['section']=='music_rozhovory'?'class="hover"':'').'>Rozhovory</a>						                                   
	</div>
	<div id="submenu-graffiti">
	<a href="'.$path.'sekcia/graffiti" '.($_REQUEST['section']=='graffiti'?'class="hover"':'').'>Všetko</a>				
	<a href="'.$path.'sekcia/graffiti_novinky" '.($_REQUEST['section']=='graffiti_novinky'?'class="hover"':'').'>Novinky</a>		
	<a href="'.$path.'sekcia/graffiti_video" '.($_REQUEST['section']=='graffiti_video'?'class="hover"':'').'>Video</a>
	<a href="'.$path.'sekcia/graffiti_rozhovory" '.($_REQUEST['section']=='graffiti_rozhovory'?'class="hover"':'').'>Rozhovory</a>	                                   
	</div>
	<div id="submenu-bike">
	<a href="'.$path.'sekcia/bike" '.($_REQUEST['section']=='bike'?'class="hover"':'').'>Všetko</a>
	<a href="'.$path.'sekcia/bike_novinky" '.($_REQUEST['section']=='bike_novinky'?'class="hover"':'').'>Novinky</a>
	<a href="'.$path.'sekcia/bike_reportaze" '.($_REQUEST['section']=='bike_reportaze'?'class="hover"':'').'>Reportáže</a>
	<a href="'.$path.'sekcia/bike_rozhovory" '.($_REQUEST['section']=='bike_rozhovory'?'class="hover"':'').'>Rozhovory</a>				                                   
	</div>
	<div id="submenu-lifestyle">
	<a href="'.$path.'sekcia/lifestyle" '.($_REQUEST['section']=='skate'?'class="hover"':'').'>Všetko</a>
	<a href="'.$path.'sekcia/lifestyle_novinky" '.($_REQUEST['section']=='skate_novinky'?'class="hover"':'').'>Novinky</a>
	<a href="'.$path.'sekcia/lifestyle_reportaze" '.($_REQUEST['section']=='skate_reportaze'?'class="hover"':'').'>Reportáže</a>
	<a href="'.$path.'sekcia/lifestyle_rozhovory" '.($_REQUEST['section']=='skate_rozhovory'?'class="hover"':'').'>Rozhovory</a>				                                   
	</div>
	<div id="submenu-board">
	<a href="'.$path.'sekcia/board" '.($_REQUEST['section']=='board'?'class="hover"':'').'>Všetko</a>
	<a href="'.$path.'sekcia/board_novinky" '.($_REQUEST['section']=='board_novinky'?'class="hover"':'').'>Novinky</a>
	<a href="'.$path.'sekcia/board_reportaze" '.($_REQUEST['section']=='board_reportaze'?'class="hover"':'').'>Reportáže</a>
	<a href="'.$path.'sekcia/board_rozhovory" '.($_REQUEST['section']=='board_rozhovory'?'class="hover"':'').'>Rozhovory</a>				                                   
	</div>
	
	<form method="post" action="'.$path.'sekcia/search" class="search">
	<div class="body">       
	<input type="text" class="search_text" name="search_text" size="17" value="' .(isset($_REQUEST['search_text']) ? $_REQUEST['search_text'] : 'Hľadať...'). '" onfocus="if(this.value==\'Hľadať...\') this.value=\'\'"  onblur="if(this.value==\'\') this.value=\'Hľadať...\'"/>
	<input type="button" class="search_butt" name="ok" value="" onclick="submit();" />       
	</div>
	</form>
	</div>';


if(isDetail()){    
    $sid = getSectionNameFromClanokId($_REQUEST['id']);
	$breadcrumbs = '<b>Nachádzaš sa:</b>&nbsp;&nbsp;'.getSectionDirByName($sid, true);
	echo '<div id="breadcrumbs">'.$breadcrumbs.'</div>';
}else{
    
	$clankyBanner = getClankyForBanner(5, $_REQUEST['section'] );
	echo '<div class="banner" id="banner">
		<div class="left">';

	foreach($clankyBanner as $clankyBannerItem){
		//<span class="perex">'.mb_substr(strip_tags(normalizeText($clankyBannerItem['big_text_sk'])),0,180) .'...</span>
		$bannerURL = $path.'clanok/'.$clankyBannerItem['clanok_id'].'-'.normalizeClanokName($clankyBannerItem['nazov_sk']);
		echo '<div class="banner-image" id="banner-left-item-'.$clankyBannerItem['clanok_id'].'" style="background-image: url('.$path.'clanky/avatar_3_'.$clankyBannerItem['clanok_id'].'.jpg);">
			<a href="'.$bannerURL.'">
			
			</a>
			</div>
			';
	}

	echo '
		</div>
		<div class="right">';
	$tmp;
	foreach($clankyBanner as $clankyBannerItem){
		$sekcia = getSectionByClanokId($clankyBannerItem['clanok_id']);
			
		echo '
			<div class="item '.getSectionByClanokId($clankyBannerItem['clanok_id'], true).'" id="banner-item-'.$clankyBannerItem['clanok_id'].'">
			<div class="sipka"></div>
			<div class="avatar" style="background-image: url('.$path.'clanky/avatar_4_'.$clankyBannerItem['clanok_id'].'.jpg);"></div>
			<div class="text">'.normalizeText($clankyBannerItem['nazov_sk']).'</div>
			<div class="info">'/*.date('d.m.Y', strtotime($clankyBannerItem['datetime'])).' / '*/.getSectionDirByName($sekcia).'</div>
			</div>';
			
		$tmp .= '
			<script type="text/javascript">
			/*<![CDATA[*/
			$("#banner-item-'.$clankyBannerItem['clanok_id'].'").bind("click", function(){
			window.open("'.$path.'clanok/'.$clankyBannerItem['clanok_id'].'-'.normalizeClanokName($clankyBannerItem['nazov_sk']).'", "_self");
			});
			/*]]>*/
			</script>
			';
	}

	echo $tmp.'
		</div>
		</div>';
}

echo '
    <div class="cleaner"></div>
    <div id="outer1">';

if(isDetail()){

	echo '<div class="article" >';
	echoClanok($_REQUEST['id']);
	echo '</div>';
}else{
	echo '<div id="contentAjax">';

	/////////SEARCH//////////
	if ($_REQUEST['section'] == "search"){
		printSearching();
	}
	/////////PARTYLIST//////////
	else if ($_REQUEST['section'] == "partylist"){
		printPartyListSection();
	} else {
		echoArticlesAjax($_REQUEST['section'], $_REQUEST['from']);
	}
	echo '</div>';
}

//}
//
echo '</div>
	
	<div id="outer2" '.($_REQUEST['id']?'style="margin-top: 6px;"':'style="margin-top: 4px;"').'>
	<div class="fb-like-box" data-href="http://www.facebook.com/sewer.sk" data-width="300" data-show-faces="false" data-stream="false" data-border-color="white" data-header="false"></div>
	';


//najcitanejsie
echoNajcitanejsieClanky(true);

/*echo '
 <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" width="300" height="250" id="mymoviename">
 <param name="movie" value="/reklama/JSHH_BANNER_1-STRAPO.swf" />
 <param name="quality" value="high" /><param name="loop" value="true" /><param name="bgcolor" value="#ffffff" />
 <embed src="/reklama/JSHH_BANNER_1-STRAPO.swf" quality="high" bgcolor="#ffffff" width="300" height="250" name="mymoviename" align="" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object><p></p>
 ';*/
//printBanner(3);
echo '
	<p></p>
	<a href="http://www.sewer.sk/reklama/monster3.jpg" rel="external" ><img src="'.$path.'reklama/monster2.jpg" alt="www.monsterenergy.com" /></a>
	<div class="cleaner"></div>
	
	<div id="partylist">',printPartyList(),'
	<div class="cleaner"></div>
	<a class="partylist-btn" href="#" onclick="window.open(\''.$path.'akcie.php\', \'_blank\', \'width=400,height=400\'); return false;" title="Pridať akciu">Pridaj akciu</a>
	<a class="partylist-btn" href="'.$path.'sekcia/partylist" title="Zobraziť všetky akcie">Všetky akcie</a>
	<div class="cleaner"></div>
	</div>
	<div class="reklama-300">
	
	<a href="mailto:info@sewer.sk" ><img src="'.$path.'img/reklama.gif" alt="reklama" /></a>  <br />
	more info: <a href="mailto:info@sewer.sk" >info@sewer.sk</a><div class="cleaner"></div></div>';

if($_REQUEST['id']){

}
echo '
	<a href="#" onclick="return false;" id="up-arrow"><span>up</span></a>';

if(isDetail()){
	/*echo '<div id="fb-like-flow">
		<iframe scrolling="no" frameborder="0" allowtransparency="true" style="border:none; overflow:hidden; width:100px; height:63px;" src="http://www.facebook.com/plugins/like.php?locale=en_US&href=http://www.sewer.sk/clanok/'.$clanokCorrectURL.'&layout=box_count&show_faces=false&width=60&action=like&colorscheme=light&height=60"></iframe>
		</div>' ;*/
}

echo '
	</div><div class="cleaner"></div>
	<div id="loader-articles"><span>loader</span></div>
	<div id="footer">
	<div id="copyright">'.SITENAME.' &copy; 2012 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="'.$path.'sekcia/home" rel="external">Home</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'sekcia/bike" rel="external">Bike</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'sekcia/board" rel="external">Board</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'sekcia/music" rel="external">Music</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'sekcia/graffiti" rel="external">Graffiti</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'sekcia/lifestyle" rel="external">Lifestyle</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="'.$path.'clanok/1035-o-nas" rel="external">Inzercia / Kontakt</a></div>
	<div id="webdesign">webdesign: <a href="http://www.widegrafik.com" rel="external"><span>widegrafik</span></a></div>
	<div class="cleaner"></div>     
	</div>
	</div>
	<script type="text/javascript">
	/*<![CDATA[*/
	
	var bannerActiveItem = 1;
	var partylistActiveItem = 1;
	var timerGo = true;
	var timerGoPartylist = true;
	var t;
	var tPartylist;
	var firstBannerLoad = true;
	var nrOfPartylists = '.getPartyListNr().';
	var loadedNajcitanejsie = 5;
	
	
	$(document).ready(function() {
	
	documentHeight = $(document).height();		                  
	
	if($(window).scrollTop()>0){
	$("#up-arrow").animate({
	opacity: 1
	}, 1000, function() {});
	
	//$("#fb-like-flow").css("top", "6px");
	
	}
	
	
	/*
	window.fbAsyncInit = function () {
	FB.init({ appId: \'your-app-id\', cookie: true, xfbml: true, oauth: true });
	
	// *** here is my code ***
	if (typeof facebookInit == \'function\') {
	facebookInit();
	}
	};*/
	/*
	(function(d){
	var js, id = \'facebook-jssdk\'; if (d.getElementById(id)) {return;}
	js = d.createElement(\'script\'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName(\'head\')[0].appendChild(js);
	}(document));*/
	
    ';
if(false && $_REQUEST['id']){
	echo '
		FB.Event.subscribe(\'edge.create\',
		function(response) {
		
		$.ajax({
        url: \'../ajax.php?newFBbox=1&resp=\'+response,
        success: function(data) {
		alert(this);
        }
		});      
		}
		);   ';
}
echo '
	activateMenu(activeTopmenu);
	
	'.(isDetail()?'':'
	//switchBanner();
	$("#banner .banner-image:first").css("display", "block");	
	$("#banner .item:first").addClass("hover");
	').'	
	
	switchPartylist();
	
	$("#zdroj UL LI:nth-child(1)").addClass("active");
	$("#zdroj UL").hide();
	$("#zdroj UL:nth-child(1)").show();
	$("#zdroj UL:nth-child(2)").show();
	
	$("#zdroj UL LI").click(function(){
	$("#zdroj UL LI").removeClass("active");
	$(this).addClass("active");
	$("#zdroj UL:nth-child(2)").hide();
	$("#zdroj UL:nth-child(3)").hide();
	$("#zdroj UL:nth-child("+  (($(this).index())+2)  +")").show();   		
	});
	
	});
	var bannerGo = true;
	var configBanner = {over:function(){
	timerGo = false;
	clearTimeout(t);
	bannerActiveItem = ($("#banner .item").index(this))+1;
	bannerGo = true;										
	switchBanner();
	}, 
	timeout: 1, 
	out: function(){
	timerGo = true;
	clearTimeout(t);
	bannerActiveItem = ($("#banner .item").index(this))+1;
	bannerGo = false;
	switchBanner();
	}  
	};
	
	$("#banner .item").hoverIntent(configBanner); 
	
	function switchBanner(){
	
	if(bannerGo){
	
	var id = $("#banner .item:nth-child("+bannerActiveItem+")").attr("id");
	id = id.substring(12);	
	
	$("#banner .item").removeClass("hover");	
	$("#banner .item:nth-child("+bannerActiveItem+")").addClass("hover");	
	
	if(!firstBannerLoad){
	$("#banner .banner-image").fadeOut();
	}
	firstBannerLoad = false;
	
	$("#banner #banner-left-item-"+id).fadeIn();
	
	}else{
	bannerGo = true;
	}
	if(bannerActiveItem == 5){
	bannerActiveItem = 1;
	}else{
	bannerActiveItem++;
	}
	if(timerGo){
	t = setTimeout("switchBanner();",4000);
	}
	}
	
	$(window).blur(function() {
	timerGo = false;
	});
	'.(isDetail()?'':'$(window).focus(function() {
	timerGo = true;
	switchBanner();
	});').'
	$(".short_story_table").hover( function(){
	$(this).find(".lupa").fadeIn("fast");
	}
	, function(){
	$(this).find(".lupa").fadeOut("fast");
	});
	var timeMenuIntent = 80;';

foreach($swrSections as $swrSection){
	echo '
		var config'.$swrSection.' = {over: function(){ activateMenu("'.$swrSection.'"); }, timeout: timeMenuIntent, out: function(){ activateMenu("'.$swrSection.'"); } };
		$("#topmenu .'.$swrSection.'").hoverIntent( config'.$swrSection.' );
		';

}
echo '
	
	$("#up-arrow").bind("click", function(){
	$("html, body").animate({
	scrollTop:0
    }, 1000, function() {});
	
	
	});
	
	function activateMenu(menu){	
	var oldClass = $("#searchbar").attr("class");
	$("#searchbar").removeClass(oldClass);
	$("#searchbar").addClass(menu);
	$("#searchbar #submenu-"+oldClass).toggle();
	$("#searchbar #submenu-"+menu).toggle();
	
	$("#topmenu A").removeClass("hover");
	$("#topmenu A."+menu).addClass("hover");
	}
	
	$(".partylist-nr").bind("click", function(){
	
	timerGoPartylist = false;
	clearTimeout(tPartylist);
	
	partylistActiveItem = ($(".partylist-nr").index(this))+1;	
	
	switchPartylist()
	
	});
	
	
	$(".more-najcitanejsie #more-button").bind("click", function(){
	
	$("#loader-najcitanejsie").fadeIn();
	loadedNajcitanejsie+=5;
	$.ajax({
	
	url: \'../ajax.php?ajaxDoNajcitanejsie=\'+loadedNajcitanejsie,
	success: function(data) {
	$("#loader-najcitanejsie").hide();
	var hei = $(\'#najcitanejsie\').height();
	$(\'#najcitanejsie\').height(hei);
	$(\'.more-najcitanejsie\').before(data);          
	$(\'#najcitanejsie\').animate({height: hei+220}, {
	duration: 500,
	specialEasing: {      		
	height: \'easeOutBounce\'
	},
	complete: function() {}
	});  
	}
	});   
	
	return false;
	
	});
	function switchPartylist(){
	
	$(".partylist-nr").removeClass("hover"); 
	$(".partylist-nr:nth-child("+partylistActiveItem+")").addClass("hover");
	
	var className = $(".partylist-nr:nth-child("+partylistActiveItem+")").attr("id");
	
	$(".partylist_item").addClass("hider");
	
	$("."+className).toggleClass("hider");
	
	if( $(".partylist_item") ){
	$(".reklama-300").hide();
	}
	
	if(partylistActiveItem == nrOfPartylists){
	partylistActiveItem = 1;
	}else{
	partylistActiveItem++;
	}
	if(timerGoPartylist){
	
	tPartylist = setTimeout("switchPartylist();",6000);
	}
	}
	$(window).scroll(function(){
	$("#up-arrow").animate({"marginTop": ($(window).scrollTop() + 30) + "px"}, "normal" );
	
	if($(window).scrollTop()>10){
	$("#up-arrow").animate({
	opacity: 1
	}, 1000, function() {});
	}
	
	/*if($(window).scrollTop()<126){
	$("#fb-like-flow").css("top", (160-$(window).scrollTop()-30)+"px");
	}*/
	
	if($(window).scrollTop()<=10){
	$("#up-arrow").animate({
	opacity: 0
	}, 1000, function() {});              
	}
	';
if(!isDetail()){

	echo '
		
		//alert($(window).scrollTop() + $(window).height()+ ", "+documentHeight);
		if(($(window).scrollTop()+$(window).height()) >= documentHeight ) {
		
		//ajax load articles
		var urll = \'../ajax.php?ajaxDoScrollArticles=1&from=\'+listingFrom+\'&limit=3&section='.$_REQUEST['section'].'\';		
		listingFrom += 3;		
		documentHeight += 215;
		
		//$(window).unbind("scroll");				
		$.ajax( 
		{url :urll, 
		success : function(data) { 
		$("#loader-articles").fadeIn();
		setTimeout(function(){
		
		$(".short_story_table").hover( function(){ 
		$(this).find(".lupa").fadeIn("fast"); 
		},function(){ 
		$(this).find(".lupa").fadeOut("fast"); 
		}); 										
		$("#contentAjax").append(data);					
		$("#loader-articles").fadeOut();
		}, 200);
		//$(window).bind("scroll");
		}	
		});			
		}';
}

echo '
	});
	var _gaq = _gaq || [];
	_gaq.push([\'_setAccount\', \'UA-7675615-1\']);
	_gaq.push([\'_trackPageview\']);
	
	(function() {
    var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;
    ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';
    var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);
	})();
	
	/*]]>*/</script>
	<!-- '.SITENAME.' SITE GENERATET IN '.(microtime(true) - $start_time).' SECONDS -->     
	</body>
	</html>
	';
