<?php

include_once('db.inc.php');
include_once(ADMIN_LOCATION.'admin_functions.php');
global $path;

if($_REQUEST['type']=='rss'){

	/*
	 <?xml-stylesheet href="/plugins/system/jcemediabox/css/jcemediabox.css?v=1011" type="text/css"?>
	 <?xml-stylesheet href="/plugins/system/jcemediabox/themes/standard/css/style.css?version=1011" type="text/css"?>
	 */
	$echo = '';
	$echo .= '<?xml version="1.0" encoding="utf-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0">
	<channel>
		<title>Sewer.sk</title>
		<description>'.SITE_DESCRIPTION.'</description>
		<webMaster>pastwo@sewer.sk</webMaster>
		<link>http://www.sewer.sk/sekcia/home</link>
		<pubDate>'.date('D, d M Y G:i:s e').'</pubDate>
		<lastBuildDate>'.date('D, d M Y G:i:s e').'</lastBuildDate>
		<generator>Sewer.sk</generator>
		<language>sk-sk</language>';

	$rssClanky = getClankyForRSS(10);
	foreach($rssClanky as $rssClankyItem){
		$bannerURL = 'http://www.sewer.sk/clanok/'.$rssClankyItem['clanok_id'].'-'.normalizeClanokName($rssClankyItem['nazov_sk']);

		$echo .= '<item>
			<title>'.$rssClankyItem['nazov_sk'].'</title>
			<link>'.$bannerURL.'</link>
			<guid>'.$rssClankyItem['clanok_id'].'</guid>
			<description><![CDATA['.mb_substr($rssClankyItem['big_text_sk'],0,300).' ...]]></description>
			<author>'.$rssClankyItem['user'].'</author>
			<category domain="http://www.sewer.sk/sekcia/home">Home</category>
			<pubDate>'.date('d.m.Y', strtotime($rssClankyItem['datetime'])).'</pubDate>
		</item>
		';
	}
		$echo .= '</channel>
</rss>';
	
	$rssFile = fopen('rss.xml', 'w+');
	fwrite($rssFile, $echo, strlen($echo));
	fclose($rssFile);
	
}
if($_REQUEST['type']=='atom'){

}

?>