<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

$size = '468 x 60';
$bannerType = 'banner2';

include('admin_banner_base.php');

?>