<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('arg_separator.output', '&amp;');
date_default_timezone_set('UTC');


$connId = mysqli_connect($_ENV['MYSQL_HOST'], $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD'], $_ENV['MYSQL_DATABASE']);

define('UPLOADS'                       ,'/upload/');

define("SITENAME", 'Sewer');
define("SITE_AUTHOR", 'www.sewer.sk');
define("SITE_DESCRIPTION", 'Multicultural community portal');
define("SITE_HOMETITLE", ' - Multicultural community portal');


//Velkosti a kvality obrazkov

define("PARTYLIST_THUMB_WIDTH"         ,214);
define("PARTYLIST_BIG_WIDTH"           ,800);

define("THUMB2_PARTYLIST_HEIGHT"       ,174);
define("THUMB_PICTURE_HEIGHT"          ,75);
define("THUMB_PICTURE_QUALITY"         ,100);
define("BIG_PICTURE_WIDTH"             ,1000);
define("BIG_PICTURE_WIDTH_2"           ,666);
define("BIG_WIDE_PICTURE_WIDTH"        ,1024);
define("BIG_PICTURE_QUALITY"           ,98);
define("PLAYLIST_POCET_NA_STRANU"      ,100);
define("POCET_OBRAZKOV_NA_STRANU"      ,25);
define("POCET_CLANKOV_NA_STRANU"       ,18);

define("FOTOALBUM_THUMBNAIL_WIDTH"     ,112);
define("FOTOALBUM_THUMBNAIL_HEIGHT"    ,76);

define("NAJCITANEJSIE_THUMBNAIL_WIDTH"     ,35);
define("NAJCITANEJSIE_THUMBNAIL_HEIGHT"    ,37);

define("NACITANEJSIE_DLZKA_NAZVU"      ,'33');
define("NAJNOVSIE_DLZKA_SPRAVY"        ,'22');

define("PARTNERI_TEXTOVO"              ,0);  // Ci sa maju partneri stranky zobrazovat textoto alebo formou obrazkov
define('DIGG'                          ,''); // PRidat Clanok na DIGG
define('TPL_NR_COMMENTS'               ,0);  // Povolenie zobrazenia poctu precitania clankov
define('COMMENTS'                      ,1);  // Povolenie komentarov
define('LOGGED_TIME'                   ,900); //user sa musi prihlasit po sekundach ak sa prihlasil viac ako LOGGED_NR krat
define('LOGGED_NR'                     ,5);
define('ADMIN_MAIL'                    ,'pastwo@sewer.sk');
define('ADMIN_LOCATION'                ,'adminko/'); //incl slashes relative to root
define('TOP_BANNER_ROTATION_TIME'      ,5);

define('ARTICLE_IMAGE_WIDTH'           ,600);


$avatar[1]['width']=210;
$avatar[1]['height']=200;
$avatar[2]['width']=660;
$avatar[2]['height']=280;
$avatar[3]['width']=494;
$avatar[3]['height']=210;
$avatar[4]['width']=76;
$avatar[4]['height']=32;

$bannerSize['width'][1] = 468;
$bannerSize['height'][1] = 90;
$bannerSize['width'][2] = 468;
$bannerSize['height'][2] = 60;
$bannerSize['width'][3] = 300;
$bannerSize['height'][3] = 250;

$notDisplayedSections = Array(27,25,26,43,44);
$notDisplayedArticles = Array();

//jazyk
$lang = Array(0 => 'sk');
$_SESSION['selectedLang'] = 'sk';

$swrSections = Array('home', 'music', 'graffiti', 'bike', 'lifestyle', 'board', 'bike', 'redakcia');

?>