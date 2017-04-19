<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', '../../..' );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

require_once ENGINE_DIR . '/data/config.php';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/data/mservice.php';
require_once ENGINE_DIR . '/modules/mservice/functions.php';
$id = intval( $_REQUEST['id'] );

$row = $db->super_query( "SELECT filename, hdd, time, artist, title FROM " . PREFIX . "_mservice WHERE mid = '$id'" );
$playartist = $row['artist'];
$playtitle = $row['title'];
if ( $row ) {
$subpapka = nameSubDir( $row['time'], $row['hdd'] );
$player = BuildAudioPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], TRUE, TRUE );

$url = $config['http_home_url'].'music/'.$id.'-mp3-'.totranslit( $playartist ).'-'.totranslit( $playtitle ).'.html';

echo <<<HTML
<html>
<head>
<title>Прослушивание трека</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<style type="text/css">
html, body { font-family: Tahoma; font-size: 12px; }
.title { font-size: 18px; color: #777777; border-bottom: 1px dashed #CCC; text-decoration: none; }
</style>
</head>
<body>
<a href="{$url}" target="_blank" class="title">{$playartist} - {$playtitle}</a><br /><br />
<center>{$player}</center>
</body>
</html>
HTML;

} else {

echo <<<HTML
<html>
<head>
<title>Прослушивание трека</title>
<style type="text/css">
html, body { font-family: Tahoma; font-size: 12px; }
</style>
</head>
<body>
<center>Трек с указанным интендификатором не найден!</center>
</body>
</html>
HTML;

}

function totranslit($var, $lower = true, $punkt = true) {

	if ( is_array($var) ) return "";

	$NpjLettersFrom = "абвгдезиклмнопрстуфцыіґ";
	$NpjLettersTo = "abvgdeziklmnoprstufcyig";
	$NpjBiLetters = array ("й" => "j", "ё" => "e", "ж" => "zh", "х" => "x", "ч" => "ch", "ш" => "sh", "щ" => "shh", "э" => "ye", "ю" => "yu", "я" => "ya", "ъ" => "", "ь" => "", "ї" => "yi", "є" => "ye" );
	
	$NpjCaps = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЪЫЭЮЯЇЄІҐ";
	$NpjSmall = "абвгдеёжзийклмнопрстуфхцчшщьъыэюяїєіґ";
	
	$var = str_replace( ".php", "", $var );
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", "-", $var );
	$var = strtr( $var, $NpjCaps, $NpjSmall );
	$var = strtr( $var, $NpjLettersFrom, $NpjLettersTo );
	$var = strtr( $var, $NpjBiLetters );
	
	if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '-', $var );

	if ( $lower ) $var = strtolower( $var );
	
	if( strlen( $var ) > 200 ) {
		
		$var = substr( $var, 0, 200 );
		
		if( ($temp_max = strrpos( $var, '-' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}

?>