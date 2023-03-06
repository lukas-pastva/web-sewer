<?php
/*********************************************************************************************/
if (! userGetAccess($_SESSION['meno_uzivatela'], "structure") ) {
	header("location: index.php");
	die;
}
/*********************************************************************************************/

$size = '300 x 250';
$bannerType = 'banner3';

include('admin_banner_base.php');

?>