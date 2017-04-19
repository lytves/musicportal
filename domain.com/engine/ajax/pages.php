<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();

@error_reporting( E_ALL ^ E_NOTICE );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );
@ini_set( 'error_reporting', E_ALL ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

$member_id = FALSE;
$is_logged = FALSE;
$allow_sql_skin = false;

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/pages.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';

$PHP_SELF = $config['http_home_url'] . "index.php";
$_TIME = time() + ($config['date_adjust'] * 60);

require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/templates.class.php';

$metatags = array (
				'title' => $config['home_title'], 
				'description' => $config['description'], 
				'keywords' => $config['keywords'],
				'header_title' => "" );

check_xss();

$user_query = "";
if( isset( $_REQUEST['year'] ) ) $year = intval( $_GET['year'] ); else $year = '';
if( isset( $_REQUEST['month'] ) ) $month = $db->safesql( @strip_tags( str_replace( '/', '', $_GET['month'] ) ) ); else $month = '';
if( isset( $_REQUEST['day'] ) ) $day = $db->safesql( @strip_tags( str_replace( '/', '', $_GET['day'] ) ) ); else $day = '';
if( isset( $_REQUEST['user'] ) ) $user = $db->safesql( @strip_tags( str_replace( '/', '', urldecode( $_GET['user'] ) ) ) ); else $user = '';
if( isset( $_REQUEST['news_name'] ) ) $news_name = $db->safesql( @strip_tags( str_replace( '/', '', $_GET['news_name'] ) ) ); else $news_name = '';
if( isset( $_REQUEST['newsid'] ) ) $newsid = intval( $_GET['newsid'] ); else $newsid = 0;
if( isset( $_REQUEST['cstart'] ) ) $cstart = intval( $_GET['cstart'] ); else $cstart = 0;
if( isset( $_REQUEST['news_page'] ) ) $news_page = intval( $_GET['news_page'] ); else $news_page = 0;
if( isset( $_REQUEST['catalog'] ) ) $catalog = $db->safesql( substr( @strip_tags( str_replace( '/', '', urldecode( $_GET['catalog'] ) ) ), 0, 3 ) ); else $catalog = '';

if( isset( $_REQUEST['category'] ) ) {
	if( substr( $_GET['category'], - 1, 1 ) == '/' ) $_GET['category'] = substr( $_GET['category'], 0, - 1 );
	$category = explode( '/', $_GET['category'] );
	$category = end( $category );
	$category = $db->safesql( @strip_tags( $category ) );

} else
	$category = '';
	
//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}
//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$cat_info = get_vars( "category" );

if( ! $cat_info ) {
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

$category_skin = "";

if( $category != '' ) $category_id = get_ID( $cat_info, $category );
else $category_id = false;

if( $category_id ) $category_skin = $cat_info[$category_id]['skin'];

// #################################
if( $allow_sql_skin and ($news_name != '' or $newsid != '') ) {
	
	foreach ( $cat_info as $cats ) {
		if( $cats['skin'] != '' ) $allow_sql_skin = true;
	}
	
	if( $config['allow_alt_url'] == "yes" ) $sql_skin = $db->super_query( "SELECT category FROM " . PREFIX . "_post where month(date) = '$month' AND year(date) = '$year' AND dayofmonth(date) = '$day' AND alt_name ='$news_name'" );
	else $sql_skin = $db->super_query( "SELECT category FROM " . PREFIX . "_post where  id = '$newsid' AND approve" );
	
	$base_skin = explode( ',', $sql_skin['category'] );
	
	$category_skin = $cat_info[$base_skin[0]]['skin'];
	
	unset( $sql_skin );
	unset( $base_skin );
}

if( $category_skin != "" ) {
	if( @is_dir( ROOT_DIR . '/templates/' . $category_skin ) ) {
		$config['skin'] = $category_skin;
	}
} elseif( $_COOKIE['dle_skin'] != '' ) {
	
	if( @is_dir( ROOT_DIR . '/templates/' . $_COOKIE['dle_skin'] ) ) {
		$config['skin'] = $_COOKIE['dle_skin'];
	}
}

if( $config["lang_" . $config['skin']] ) {
	
	include_once ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng';

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

$tpl = new dle_template( );
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'];
define( 'TEMPLATE_DIR', $tpl->dir );

$allowed_sort = array ('date', 'rating', 'news_read', 'comm_num', 'title' );

require_once ENGINE_DIR . '/modules/sitelogin.php';

if( ! $is_logged ) {
	$member_id['user_group'] = 5;
}

if( $config['allow_banner'] ) include_once ENGINE_DIR . '/modules/banners.php';

require_once ROOT_DIR . '/engine/engine.php';

$db->close();

@header( "Content-type: text/css; charset=" . $config['charset'] );

if( $config['speedbar'] ) {
	
	$s_navigation = str_replace( array ("'", "&#039;" ), array ("&#039;", "&#039;" ), $s_navigation );
	
	$tpl->result['speedbar'] = <<<HTML
<script language='JavaScript' type="text/javascript">

	if ( document.getElementById('dle-speedbar') )
	{
	   document.getElementById('dle-speedbar').innerHTML = '{$s_navigation}';
    }
</script>
HTML;

}

$tpl->result['content'] = str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['content'] );
$tpl->result['info'] = str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['info'] );

echo $tpl->result['speedbar'] . $tpl->result['info'] . $tpl->result['content'];

?>