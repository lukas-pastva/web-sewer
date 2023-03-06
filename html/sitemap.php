<?php

include_once('db.inc.php');
include_once(ADMIN_LOCATION.'admin_functions.php');

global $path;


$XML .= '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
';


$cmd = psw_mysql_query('SELECT * FROM structure');


while($section = psw_mysql_fetch_array($cmd)){
	if(!in_array($section['structure_id'],$notDisplayedSections)){
		$XML .= '
<url>
  <loc>http://www.sewer.sk/sekcia/'.$section['name_sk'].'</loc>
  <changefreq>hourly</changefreq>
</url>';
	}
}
$XML .= '
<url>
  <loc>http://www.sewer.sk/sekcia/partylist</loc>
  <changefreq>hourly</changefreq>
</url>
<url>
  <loc>http://www.sewer.sk/sekcia/home</loc>
  <changefreq>hourly</changefreq>
</url>
<url>
  <loc>http://www.sewer.sk/sekcia/home_sutaze</loc>
  <changefreq>hourly</changefreq>
</url>';

$cmd = psw_mysql_query('SELECT clanok_id, nazov_sk FROM clanok WHERE koncept = 0 ORDER BY datetime DESC');


while($clanok = psw_mysql_fetch_array($cmd)){
	
		$XML .= '
<url>
  <loc>http://www.sewer.sk/clanok/'.normalizeClanokName( $clanok['nazov_sk'] ).'</loc>
  <changefreq>weekly</changefreq>
</url>';
	
}
$XML .= '
</urlset>';


$XMLfile = fopen('sitemap.xml', 'w+');
fwrite($XMLfile, $XML, strlen($XML));
fclose($XMLfile);

?>