<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

$size = '468 x 30';
$bannerType = 'banner';

include('admin_banner_base.php');

?>