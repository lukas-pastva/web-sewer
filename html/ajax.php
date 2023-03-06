<?php
error_reporting(E_ALL ^ E_WARNING);
include_once('db.inc.php');
include_once(ADMIN_LOCATION.'admin_functions.php');

if( ($_REQUEST['ajaxDoStrankaClankov']=='1') && is_numeric($_REQUEST['from']) && (strlen($_REQUEST['section'])>0) ){

	echoArticlesAjax($_REQUEST['section'], $_REQUEST['from']);

}

if( $_REQUEST['newFBbox']=='1'){

$srv =  print_r($_SERVER, true);
$rqst = print_r($_REQUEST, true);

         $text = '   
kliklo sa na likebox
response: '.$_REQUEST['resp'].'
datetime: '.date('Y-m-d- G:i:s').'
$_SERVER: '.$srv.'
$_REQUEST: '.$rqst.'                                     

';
         psw_mysql_query($sql = 'INSERT INTO fb SET datetime=now(), data = "'.$text.'" ');
         
} 

if( (is_numeric($_REQUEST['ajaxDoNajcitanejsie'])&&($_REQUEST['ajaxDoNajcitanejsie']<300))) {

	echoNajcitanejsieClanky(true, $_REQUEST['ajaxDoNajcitanejsie'], true);

}


if( ($_REQUEST['ajaxDoScrollArticles']=='1') && is_numeric($_REQUEST['from']) && is_numeric($_REQUEST['limit']) && (strlen($_REQUEST['section'])>0) ){

	echoArticlesAjax($_REQUEST['section'], $_REQUEST['from'], $_REQUEST['limit']);

}

?>