<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

$go_rate = intval( $_REQUEST['go_rate'] );
$news_id = intval( $_REQUEST['news_id'] );

if( $go_rate > 5 or $go_rate < 1 ) $go_rate = 0;
if( ! $go_rate or ! $news_id ) die( "Hacking attempt!" );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/rating.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

$_REQUEST['skin'] = totranslit($_REQUEST['skin'], false, false);

if( $_REQUEST['skin'] ) {
	if( @is_dir( ROOT_DIR . '/templates/' . $_REQUEST['skin'] ) ) {
		$config['skin'] = $_REQUEST['skin'];
	} else {
		die( "Hacking attempt!" );
	}
}

if( $config["lang_" . $config['skin']] ) {
	
	include_once ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng';

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];


//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = $value;
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

require_once ENGINE_DIR . '/modules/sitelogin.php';

if( ! $is_logged ) $member_id['user_group'] = 5;

if( ! $user_group[$member_id['user_group']]['allow_rating'] ) die( "Hacking attempt!" );

$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );

if( $is_logged ) $where = "member = '{$member_id['name']}'";
else $where = "ip ='{$_IP}'";

$row = $db->super_query( "SELECT news_id FROM " . PREFIX . "_logs where news_id ='$news_id' AND {$where}" );

if( ! $row['news_id'] AND count( explode( ".", $_IP ) ) == 4 ) {
	
	$db->query( "UPDATE " . PREFIX . "_post set rating=rating+'$go_rate', vote_num=vote_num+1 where id ='$news_id'" );
	
	if( $is_logged ) $user_name = $member_id['name'];
	else $user_name = "noname";
	
	$db->query( "INSERT INTO " . PREFIX . "_logs (news_id, ip, member) values ('$news_id', '$_IP', '$user_name')" );
	clear_cache( 'news_' );
}

$row = $db->super_query( "SELECT id, rating, vote_num FROM " . PREFIX . "_post where id ='$news_id'" );

if( $_REQUEST['mode'] == "short" ) {
	
	$buffer = ShortRating( $row['id'], $row['rating'], $row['vote_num'], false );

} else {
	
	$buffer = ShowRating( $row['id'], $row['rating'], $row['vote_num'], false );

}

$db->close();

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $buffer;
?>