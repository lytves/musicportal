<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if (! defined ( 'DATALIFEENGINE' )) {
	die ( "Hacking attempt!" );
}

@include (ENGINE_DIR . '/data/config.php');

if ($config['http_home_url'] == "") {
	
	$config['http_home_url'] = explode ( "index.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset ( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

if (! $config['version_id']) die ( "Datalife Engine not installed. Please run install.php" );

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/gzip.php';

$Timer = new microTimer ( );
$Timer->start ();

check_xss ();

$cron = false;
$_TIME = time () + ($config['date_adjust'] * 60);

$cron_time = get_vars ( "cron" );

if (date ( "Y-m-d", $cron_time ) != date ( "Y-m-d", $_TIME )) $cron = 2;
elseif ($config['cache_count'] and (($cron_time + (3600 * 2)) < $_TIME)) $cron = 1;

if ($cron) include_once ENGINE_DIR . '/modules/cron.php';

if (isset ( $_REQUEST['year'] )) $year = intval ( $_GET['year'] ); else $year = '';
if (isset ( $_REQUEST['month'] )) $month = @$db->safesql ( strip_tags ( str_replace ( '/', '', $_GET['month'] ) ) ); else $month = '';
if (isset ( $_REQUEST['day'] )) $day = @$db->safesql ( strip_tags ( str_replace ( '/', '', $_GET['day'] ) ) ); else $day = '';
if (isset ( $_REQUEST['user'] )) $user = @$db->safesql ( strip_tags ( str_replace ( '/', '', urldecode ( $_GET['user'] ) ) ) ); else $user = '';
if (isset ( $_REQUEST['news_name'] )) $news_name = @$db->safesql ( strip_tags ( str_replace ( '/', '', $_GET['news_name'] ) ) ); else $news_name = '';
if (isset ( $_REQUEST['newsid'] )) $newsid = intval ( $_GET['newsid'] ); else $newsid = 0;
if (isset ( $_REQUEST['cstart'] )) $cstart = intval ( $_GET['cstart'] ); else $cstart = 0;
if ($cstart > 9000000) { 

    header( "Location: ".str_replace("index.php","",$_SERVER['PHP_SELF']) ); 
    die(); 
} 
if (isset ( $_REQUEST['news_page'] )) $news_page = intval ( $_GET['news_page'] ); else $news_page = 0;
if (isset ( $_REQUEST['catalog'] )) $catalog = @$db->safesql ( substr ( strip_tags ( str_replace ( '/', '', urldecode ( $_GET['catalog'] ) ) ), 0, 3 ) ); else $catalog = '';

if (isset ( $_REQUEST['category'] )) {
	if (substr ( $_GET['category'], - 1, 1 ) == '/') $_GET['category'] = substr ( $_GET['category'], 0, - 1 );
	$category = explode ( '/', $_GET['category'] );
	$category = end ( $category );
	$category = $db->safesql ( strip_tags ( $category ) );
} else $category = '';

$PHP_SELF = $config['http_home_url'] . "index.php";
$pm_alert = "";
$ajax = "";
$allow_comments_ajax = false;
$_DOCUMENT_DATE = false;
$user_query = "";

$metatags = array (
				'title' => $config['home_title'], 
				'description' => $config['description'], 
				'keywords' => $config['keywords'],
				'header_title' => "" );

//################# Определение групп пользователей
$user_group = get_vars ( "usergroup" );

if (! $user_group) {
	$user_group = array ();
	
	$db->query ( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row () ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars ( "usergroup", $user_group );
	$db->free ();
}
//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$cat_info = get_vars ( "category" );

if (! is_array ( $cat_info )) {
	$cat_info = array ();
	
	$db->query ( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );
	while ( $row = $db->get_row () ) {
		
		$cat_info[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$cat_info[$row['id']][$key] = stripslashes ( $value );
		}
	
	}
	set_vars ( "category", $cat_info );
	$db->free ();
}

//####################################################################################################################
//                    Определение забаненных пользователей и IP
//####################################################################################################################
$banned_info = get_vars ( "banned" );

if (! is_array ( $banned_info )) {
	$banned_info = array ();
	
	$db->query ( "SELECT * FROM " . USERPREFIX . "_banned" );
	while ( $row = $db->get_row () ) {
		
		if ($row['users_id']) {
			
			$banned_info['users_id'][$row['users_id']] = array (
																'users_id' => $row['users_id'], 
																'descr' => stripslashes ( $row['descr'] ), 
																'date' => $row['date'] );
		
		} else {
			
			if (count ( explode ( ".", $row['ip'] ) ) == 4)
				$banned_info['ip'][$row['ip']] = array (
														'ip' => $row['ip'], 
														'descr' => stripslashes ( $row['descr'] ), 
														'date' => $row['date']
														);
			elseif (strpos ( $row['ip'], "@" ) !== false)
				$banned_info['email'][$row['ip']] = array (
															'email' => $row['ip'], 
															'descr' => stripslashes ( $row['descr'] ), 
															'date' => $row['date'] );
			else $banned_info['name'][$row['ip']] = array (
															'name' => $row['ip'], 
															'descr' => stripslashes ( $row['descr'] ), 
															'date' => $row['date'] );
		
		}
	
	}
	set_vars ( "banned", $banned_info );
	$db->free ();
}

$category_skin = "";

if ($category != '') $category_id = get_ID ( $cat_info, $category );
else $category_id = false;

if ($category_id) $category_skin = $cat_info[$category_id]['skin'];

// #################################
if ($news_name != '' or $newsid) {
	
	$allow_sql_skin = false;
	
	foreach ( $cat_info as $cats ) {
		if ($cats['skin'] != '') $allow_sql_skin = true;
	}
	
	if ($allow_sql_skin) {
		
		if (! $newsid) $sql_skin = $db->super_query ( "SELECT category FROM " . PREFIX . "_post where month(date) = '$month' AND year(date) = '$year' AND dayofmonth(date) = '$day' AND alt_name ='$news_name'" );
		else $sql_skin = $db->super_query ( "SELECT category FROM " . PREFIX . "_post where  id = '$newsid' AND approve" );
		
		$base_skin = explode ( ',', $sql_skin['category'] );
		
		$category_skin = $cat_info[$base_skin[0]]['skin'];
		
		unset ( $sql_skin );
		unset ( $base_skin );
	
	}

}

if ($_GET['do'] == "static") {
	
	$name = $db->safesql ( $_GET['page'] );
	$static_result = $db->super_query ( "SELECT * FROM " . PREFIX . "_static WHERE name='$name'" );
	$category_skin = $static_result['template_folder'];

}

if ($category_skin != "") {

	if (@is_dir ( ROOT_DIR . '/templates/' . $category_skin )) {
		$config['skin'] = $category_skin;
	}

} elseif (isset ( $_REQUEST['action_skin_change'] )) {
	
	$_REQUEST['skin_name'] = trim( totranslit($_REQUEST['skin_name'], false, false) );
	
	if ($_REQUEST['skin_name'] != '' AND @is_dir ( ROOT_DIR . '/templates/' . $_REQUEST['skin_name'] ) ) {
		$config['skin'] = $_REQUEST['skin_name'];
		set_cookie ( "dle_skin", $_REQUEST['skin_name'], 365 );
	}

} elseif (isset ( $_COOKIE['dle_skin'] ) ) {

	$_COOKIE['dle_skin'] = trim( totranslit($_COOKIE['dle_skin'], false, false) );

	if ($_COOKIE['dle_skin'] != '' AND @is_dir ( ROOT_DIR . '/templates/' . $_COOKIE['dle_skin'] )) {
		$config['skin'] = $_COOKIE['dle_skin'];
	}
}

if (isset ( $config["lang_" . $config['skin']] ) and $config["lang_" . $config['skin']] != '') {
	
	include_once ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng';

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

$smartphone_detected = false;

if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "mobiledisable" ) { $_SESSION['mobile_disable'] = 1; $_SESSION['mobile_enable'] = 0; }
if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "mobile" ) { $_SESSION['mobile_enable'] = 1; $_SESSION['mobile_disable'] = 0;}
if( !isset( $_SESSION['mobile_disable'] ) ) $_SESSION['mobile_disable'] = 0;
if( !isset( $_SESSION['mobile_enable'] ) ) $_SESSION['mobile_enable'] = 0;

require_once ENGINE_DIR . '/classes/templates.class.php';

$tpl = new dle_template();

if ( ($config['allow_smartphone'] AND !$_SESSION['mobile_disable'] AND $tpl->smartphone) OR $_SESSION['mobile_enable'] ) {

	if ( @is_dir ( ROOT_DIR . '/templates/smartphone' ) ) {

			$config['skin'] = "smartphone";
			$smartphone_detected = true;
			$config['ajax'] = false;
			$config['allow_comments_wysiwyg'] = "no";

	}

}

$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'];
define ( 'TEMPLATE_DIR', $tpl->dir );

if (isset ( $_POST['set_new_sort'] ) and $config['allow_change_sort']) {
	
	$allowed_sort = array (
							'date', 
							'rating', 
							'news_read', 
							'comm_num', 
							'title' );
	
	$find_sort = str_replace ( ".", "", totranslit ( $_POST['set_new_sort'] ) );
	$direction_sort = str_replace ( ".", "", totranslit ( $_POST['set_direction_sort'] ) );
	
	if (in_array($_POST['dlenewssortby'], $allowed_sort) AND stripos($find_sort, "dle_sort_") === 0) {
		
		if ($_POST['dledirection'] == "desc" or $_POST['dledirection'] == "asc") {
			
			@session_register ( $find_sort );
			@session_register ( $direction_sort );
			@session_register ( 'dle_no_cache' );
			
			$_SESSION[$find_sort] = $_POST['dlenewssortby'];
			$_SESSION[$direction_sort] = $_POST['dledirection'];
			$_SESSION['dle_no_cache'] = "1";
		
		}
	
	}

}

$login_panel = "";

if ($config['allow_registration'] == "yes") {
	
	include_once ENGINE_DIR . '/modules/sitelogin.php';
	
	$blockip = check_ip ( $banned_info['ip'] );
	
	if (($is_logged and $member_id['banned'] == "yes") or $blockip) include_once ENGINE_DIR . '/modules/banned.php';
	
	if ($config['allow_alt_url'] == "yes") {
		
		if ($is_logged) {
			$link_profile = ($config['ajax']) ? $config['http_home_url'] . "user/" . urlencode ( $member_id['name'] ) . "/\" onclick=\"DlePage('subaction=userinfo&user=" . urlencode ( $member_id['name'] ) . "'); return false;" : $config['http_home_url'] . "user/" . urlencode ( $member_id['name'] ) . "/";
			$link_stats = ($config['ajax']) ? $config['http_home_url'] . "statistics.html\" onclick=\"DlePage('do=stats'); return false;" : $config['http_home_url'] . "statistics.html";
			$link_addnews = $config['http_home_url'] . "addnews.html";
			$link_newposts = ($config['ajax']) ? $config['http_home_url'] . "newposts/\" onclick=\"DlePage('subaction=newposts'); return false;" : $config['http_home_url'] . "newposts/";
			$link_favorites = ($config['ajax']) ? $config['http_home_url'] . "favorites/\" onclick=\"DlePage('do=favorites'); return false;" : $config['http_home_url'] . "favorites/";
			$link_logout = $PHP_SELF . "?action=logout";
			$link_pm = ($config['ajax']) ? $PHP_SELF . "/pm/\" onclick=\"DlePage('do=pm'); return false;" : $config['http_home_url'] . "pm/";
			$adminlink = $config['http_home_url'] . $config['admin_path'] . "?mod=main";
			$adminlinkmservice = $config['http_home_url'] . $config['admin_path'] . "?mod=mservice";
		}
		$link_regist = ($config['ajax']) ? $PHP_SELF . "?do=register\" onclick=\"DlePage('do=register'); return false;" : $PHP_SELF . "?do=register";
		$link_lost = ($config['ajax']) ? $PHP_SELF . "?do=lostpassword\" onclick=\"DlePage('do=lostpassword'); return false;" : $PHP_SELF . "?do=lostpassword";
	
	} else {
		
		if ($is_logged) {
			$link_profile = ($config['ajax']) ? $PHP_SELF . "?subaction=userinfo&user=" . urlencode ( $member_id['name'] ) . "\" onclick=\"DlePage('subaction=userinfo&user=" . urlencode ( $member_id['name'] ) . "'); return false;" : $PHP_SELF . "?subaction=userinfo&user=" . urlencode ( $member_id['name'] );
			$link_stats = ($config['ajax']) ? $PHP_SELF . "?do=stats\" onclick=\"DlePage('do=stats'); return false;" : $PHP_SELF . "?do=stats";
			$link_addnews = $PHP_SELF . "?do=addnews";
			$link_favorites = ($config['ajax']) ? $PHP_SELF . "?do=favorites\" onclick=\"DlePage('do=favorites'); return false;" : $PHP_SELF . "?do=favorites";
			$link_newposts = ($config['ajax']) ? $PHP_SELF . "?subaction=newposts\" onclick=\"DlePage('subaction=newposts'); return false;" : $PHP_SELF . "?subaction=newposts";
			$link_logout = $PHP_SELF . "?action=logout";
			$adminlink = $config['admin_path'] . "?mod=main";
			$link_pm = ($config['ajax']) ? $PHP_SELF . "/pm/\" onclick=\"DlePage('do=pm'); return false;" : $config['http_home_url'] . "pm/";
		}
		$link_regist = ($config['ajax']) ? $PHP_SELF . "?do=register\" onclick=\"DlePage('do=register'); return false;" : $PHP_SELF . "?do=register";
		$link_lost = ($config['ajax']) ? $PHP_SELF . "?do=lostpassword\" onclick=\"DlePage('do=lostpassword'); return false;" : $PHP_SELF . "?do=lostpassword";
	}
	
	include_once $tpl->dir . '/login.tpl';
	
	if ($is_logged) {
		
		set_cookie ( "dle_newpm", $member_id['pm_unread'], 365 );
		
		if ($member_id['pm_unread'] > intval ( $_COOKIE['dle_newpm'] ) AND !$smartphone_detected) {
			
			include_once ENGINE_DIR . '/modules/pm_alert.php';
		
		}
	
	}
	
	if ($is_logged and $user_group[$member_id['user_group']]['time_limit']) {
		
		if ($member_id['time_limit'] != "" and (intval ( $member_id['time_limit'] ) < $_TIME)) {
			
			$db->query ( "UPDATE " . USERPREFIX . "_users set user_group='{$user_group[$member_id['user_group']]['rid']}', time_limit='' WHERE user_id='$member_id[user_id]'" );
			$member_id['user_group'] = $user_group[$member_id['user_group']]['rid'];
		
		}
	}

}

if (!$is_logged) $member_id['user_group'] = 5;

if ($config['site_offline'] == "yes") include_once ENGINE_DIR . '/modules/offline.php';

require_once ENGINE_DIR . '/modules/calendar.php';

if ($config['allow_topnews'] == "yes") { 
//include_once ENGINE_DIR . '/modules/topnews.php';
include_once ENGINE_DIR . '/modules/lastnews.php';
}

require_once ROOT_DIR . '/engine/engine.php';

if ($config['allow_votes'] == "yes") include_once ENGINE_DIR . '/modules/vote.php';

if ( !defined('BANNERS') ) {
	if ($config['allow_banner']) include_once ENGINE_DIR . '/modules/banners.php';
}

if ($config['allow_tags']) include_once ENGINE_DIR . '/modules/tagscloud.php';

if ($config['rss_informer']) include_once ENGINE_DIR . '/modules/rssinform.php';

?>