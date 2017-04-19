<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

function reset_url($url) {
	$value = str_replace ( "http://", "", $url );
	$value = str_replace ( "www.", "", $value );
	$value = explode ( "/", $value );
	$value = reset ( $value );
	return $value;
}
$url = rawurldecode ( $_GET['url'] );
$url = @base64_decode ( $url );
$url = str_replace ( "&amp;", "&", $url );

$_SERVER['HTTP_REFERER'] = reset_url ( $_SERVER['HTTP_REFERER'] );
$_SERVER['HTTP_HOST'] = reset_url ( $_SERVER['HTTP_HOST'] );

if (($_SERVER['HTTP_HOST'] != $_SERVER['HTTP_REFERER']) or $url == "") {
	@header ( 'Location: /index.php' );
	die ( "Access denied!!!<br /><br />Please visit <a href=\"/index.php\">{$_SERVER['HTTP_HOST']}</a>" );
}

@header ( 'Location: ' . $url );

die ( "Link Redirect:<br /><br />Please click <a href=\"{$url}\">here.</a>" );
?>