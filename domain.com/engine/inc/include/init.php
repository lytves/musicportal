<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
require_once (ENGINE_DIR . '/inc/include/functions.inc.php');  
extract( $_REQUEST, EXTR_SKIP );
require_once (ENGINE_DIR . '/data/config.php');

$auto_detect_config = false;
if( $config['http_home_url'] == "" ) {
	$config['http_home_url'] = explode( $config['admin_path'], $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];
	$auto_detect_config = true;
}

require_once (ENGINE_DIR . '/classes/mysql.php');
require_once (ENGINE_DIR . '/data/dbconfig.php');
$selected_language = $config['langs'];
if (isset( $_POST['selected_language'] )) {
	$_POST['selected_language'] = totranslit( $_POST['selected_language'], false, false );
	if (@is_dir ( ROOT_DIR . '/language/' .$_POST['selected_language'] )) {
		$selected_language = $_POST['selected_language'];
		set_cookie ("selected_language", $selected_language, 365);
		}
	} elseif (isset( $_COOKIE['selected_language'] )) {
		$_COOKIE['selected_language'] = totranslit( $_COOKIE['selected_language'], false, false );
		if (@is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
			$selected_language = $_COOKIE['selected_language'];
		}
}
require_once (ROOT_DIR . '/language/' . $selected_language . '/adminpanel.lng');
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
check_xss();

$Timer = new microTimer( );
$Timer->start();
if( $_SESSION['dle_log'] > 5 ) die( "Hacking attempt!" );
$is_loged_in = FALSE;
$member_id = array ();
$result = "";
$username = "";
$cmd5_password = "";
$allow_login = false;
$PHP_SELF = $_SERVER['PHP_SELF'];
$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
require_once (ENGINE_DIR . '/skins/default.skin.php');

if( isset( $_POST['action'] ) ) $action = $_POST['action'];  else $action = $_GET['action'];
if( isset( $_POST['mod'] ) ) $mod = $_POST['mod'];  else $mod = $_GET['mod'];
$mod = totranslit ( $mod, true, false );  $action = totranslit ( $action, false, false );


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

if( count( $cat_info ) ) {
	foreach ( $cat_info as $key ) {
		$cat[$key['id']] = $key['name'];
		$cat_parentid[$key['id']] = $key['parentid'];
	}
}

if( $_REQUEST['action'] == "logout" ) {
	
	set_cookie( "dle_user_id", "", 0 );
	set_cookie( "dle_name", "", 0 );
	set_cookie( "dle_password", "", 0 );
	set_cookie( "dle_skin", "", 0 );
	set_cookie( "dle_newpm", "", 0 );
	set_cookie( "dle_hash", "", 0 );
	set_cookie( session_name(), "", 0 );
	
	@session_unset();
	@session_destroy();
	
	if( $config['extra_login'] ) auth();	
	msg( "info", $lang['index_msge'], $lang['index_exit'] );
}

if( $check_referer ) {
	
	if( $_SERVER['HTTP_REFERER'] == '' and $_REQUEST['subaction'] != 'dologin' ) $allow_login = true;
	elseif( clean_url( $_SERVER['HTTP_REFERER'] ) == clean_url( $_SERVER['HTTP_HOST'] ) ) $allow_login = true;

} else {
	
	$allow_login = true;

}

if( $allow_login ) {
	
	if( $config['extra_login'] ) {
		
		if( ! isset( $_SERVER['PHP_AUTH_USER'] ) || ! isset( $_SERVER['PHP_AUTH_PW'] ) ) auth();
		$username = $_SERVER['PHP_AUTH_USER'];
		$cmd5_password = md5( $_SERVER['PHP_AUTH_PW'] );
		$post = true;
	
	} elseif( intval( $_SESSION['dle_user_id'] ) > 0 ) {
		
		$username = $_SESSION['dle_user_id'];
		$cmd5_password = $_SESSION['dle_password'];
		$post = false;
	
	} elseif( intval( $_COOKIE['dle_user_id'] ) > 0 ) {
		
		$username = $_COOKIE['dle_user_id'];
		$cmd5_password = $_COOKIE['dle_password'];
		$post = false;
	
	}
	
	if( $_REQUEST['subaction'] == 'dologin' ) {
		
		$username = $_POST['username'];
		$cmd5_password = md5( $_POST['password'] );
		$post = true;
	
	}

}  


if( check_login( $username, $cmd5_password, $post ) ) {
	$is_loged_in = true;
	$_SESSION['dle_log'] = 0;
	$dle_login_hash = md5( strtolower( $_SERVER['HTTP_HOST'] . $member_id['name'] . $cmd5_password . $config['key'] . date( "Ymd" ) ) );
	
	if( ! $_SESSION['dle_user_id'] and $_COOKIE['dle_user_id'] ) {
		
		$_SESSION['dle_user_id'] = $_COOKIE['dle_user_id'];
		$_SESSION['dle_password'] = $_COOKIE['dle_password'];
		$_SESSION['dle_name'] = $member_id['name'];
	}


} else {
	
	$_SESSION['dle_log'] = intval( $_SESSION['dle_log'] ) + 1;
	$dle_login_hash = "";
	
	if( $_REQUEST['subaction'] == 'dologin' ) {
		
		$result = "<font color=red>" . $lang['index_errpass'] . "</font>";
	
	} else
		$result = "";
	
	if( $config['extra_login'] ) auth();
	
	$is_loged_in = false;
}

if( $is_loged_in and ! $_SESSION['dle_xtra'] and $config['extra_login'] ) {
	$_SESSION['dle_xtra'] = true;
	$_REQUEST['subaction'] = 'dologin';
}

if( $is_loged_in and $_REQUEST['subaction'] == 'dologin' ) {

	$_SESSION['dle_user_id'] = $member_id['user_id'];
	$_SESSION['dle_password'] = $cmd5_password;
	$_SESSION['dle_name'] = $member_id['name'];

	set_cookie( "dle_user_id", $member_id['user_id'], 365 );
	set_cookie( "dle_password", $cmd5_password, 365 );

	$time_now = time() + ($config['date_adjust'] * 60);

	if( $config['log_hash'] ) {

		$salt = "abchefghjkmnpqrstuvwxyz0123456789";
		$hash = '';
		srand( ( double ) microtime() * 1000000 );

		for($i = 0; $i < 9; $i ++) {
			$hash .= $salt{rand( 0, 33 )};
		}

		$hash = md5( $hash );

		set_cookie( "dle_hash", $hash, 365 );

		$_COOKIE['dle_hash'] = $hash;
		$member_id['hash'] = $hash;

		$db->query( "UPDATE " . USERPREFIX . "_users set hash='" . $hash . "', lastdate='{$time_now}', logged_ip='" . $_IP . "' WHERE user_id='{$member_id['user_id']}'" );

	} else $db->query( "UPDATE " . USERPREFIX . "_users set lastdate='{$time_now}', logged_ip='" . $_IP . "' WHERE user_id='{$member_id['user_id']}'" );

	    /////////////////////////////////////////////////gggggggggggggggggggggggggg

	  define ( 'ROOT_DIR', '../../..' );
    define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

		//дальше отправляем письмо с текстом ошибки на два мыла
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }

    include_once ENGINE_DIR . '/classes/mail.class.php';
    require_once ENGINE_DIR . '/data/config.php';
    
    $mailsend = new dle_mail( $config );
    $mailsend->from = 'robot@domain.com';
    $mailsend->bcc[0] = 'odmin@domain.com';
    $mailsend->html_mail = true;
         
    $mailtext = "<strong style='font-size:20px;'>Вход в админку domain.com АДМИНИСТРАТОРА!</strong><br /><br />";

    $mailtext .= "<strong>Реферер:</strong> ".clean_string($_SERVER['HTTP_REFERER'])."<br />";
    $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."<br />";
    $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."<br />";
 
    $mailsend->send( "vlit@ukr.net", "Вход в админку domain.com АДМИНИСТРАТОРА!", "$mailtext" );
    /////////////////////////////////////////////////gggggggggggggggggggggggggg
    
}

if( $is_loged_in and $config['log_hash'] and (($_COOKIE['dle_hash'] != $member_id['hash']) or ($member_id['hash'] == "")) ) {

	$is_loged_in = FALSE;
}

if( $is_loged_in and $config['ip_control'] == '1' and ! check_netz( $member_id['logged_ip'], $_IP ) and $_REQUEST['subaction'] != 'dologin' ) $is_loged_in = FALSE;

	if( ! $is_loged_in ) {
	$member_id = array();
	set_cookie( "dle_user_id","",0 );
	set_cookie( "dle_name","",0 );
	set_cookie( "dle_password","",0 );
	set_cookie( "dle_hash","",0 );
	$_SESSION['dle_user_id'] = 0;
	$_SESSION['dle_password'] = "";
	$_SESSION['dle_name'] = "";
	
	if( $config['extra_login'] ) auth();
}

if ( $is_loged_in ) define( 'LOGGED_IN', $is_loged_in );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

?>
