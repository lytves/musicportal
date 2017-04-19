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

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/find_relates.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';
require_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

if( ! $is_logged ) die( "error" );

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

if( ! $user_group[$member_id['user_group']]['allow_admin'] ) die( "error" );

//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$cat_info = get_vars( "category" );

if( ! is_array( $cat_info ) ) {
	$cat_info = array ();
	
	$db->query( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
	while ( $row = $db->get_row() ) {
		
		$cat_info[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$cat_info[$row['id']][$key] = stripslashes( $value );
		}
	
	}
	set_vars( "category", $cat_info );
	$db->free();
}

$title = $db->safesql( trim( convert_unicode( $_POST['title'], $config['charset'] ) ) );

if( $title == "" ) die();

$buffer = "";

$id = intval( $_POST['id'] );

if( $id ) $where = " AND id != '" . $id . "'";
else $where = "";

$db->query( "SELECT id, title, date, category, alt_name, flag, MATCH (title, short_story, full_story, xfields) AGAINST ('$title') as score FROM " . PREFIX . "_post WHERE MATCH (title, short_story, full_story, xfields) AGAINST ('$title') AND approve='1'" . $where . " ORDER BY score DESC, date DESC LIMIT 5" );

while ( $related = $db->get_row() ) {
	
	$related['date'] = strtotime( $related['date'] );
	$related['category'] = intval( $related['category'] );
	$news_date = date( 'd-m-Y', $related['date'] );
	
	if( $config['allow_alt_url'] == "yes" ) {
		
		if( $related['flag'] and $config['seo_type'] ) {
			
			if( $related['category'] and $config['seo_type'] == 2 ) {
				
				$full_link = $config['http_home_url'] . get_url( $related['category'] ) . "/" . $related['id'] . "-" . $related['alt_name'] . ".html";
			
			} else {
				
				$full_link = $config['http_home_url'] . $related['id'] . "-" . $related['alt_name'] . ".html";
			
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . date( 'Y/m/d/', $related['date'] ) . $related['alt_name'] . ".html";
		}
	
	} else {
		
		$full_link = $config['http_home_url'] . "index.php?newsid=" . $related['id'];
	
	}
	
	$buffer .= "<li>{$news_date} - <a href=\"" . $full_link . "\" target=\"_blank\">" . stripslashes( $related['title'] ) . "</a></li>";

}
$db->close();

@header( "Content-type: text/css; charset=" . $config['charset'] );

if( $buffer ) echo "<div style=\"background: #ffc;border:1px solid #9E9E9E;padding: 5px;margin-top: 7px;margin-right: 10px;\">" . $buffer . "</div>";
else echo "<div style=\"background: #ffc;border:1px solid #9E9E9E;padding: 5px;margin-top: 7px;margin-right: 10px;\">" . $lang['related_not_found'] . "</div>";

?>