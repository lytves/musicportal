<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
$dle_login_hash = "";
if (isset($config['date_adjust'])) $_TIME = time () + ($config['date_adjust'] * 60); else $_TIME = time ();

if(!isset($_SESSION)){
    session_start();
}


if( isset( $_REQUEST['action'] ) and $_REQUEST['action'] == "logout" ) {
	
	$dle_user_id = "";
	$dle_password = "";
	set_cookie( "dle_user_id", "", 0 );
	set_cookie( "dle_name", "", 0 );
	set_cookie( "dle_password", "", 0 );
	set_cookie( "dle_skin", "", 0 );
	set_cookie( "dle_newpm", "", 0 );
	set_cookie( "dle_hash", "", 0 );
	set_cookie( session_name(), "", 0 );
	@session_destroy();
	@session_unset();
	$is_logged = 0;
	
	header( "Location: $PHP_SELF" );
	die();
}

$is_logged = 0;
$member_id = array ();
if( isset($_SESSION['dle_log'])  AND ($_SESSION['dle_log']> 5) ) die( "Hacking attempt!" );

if( isset( $_POST['login'] ) and $_POST['login'] == "submit" ) {
	
	$_POST['login_name'] = $db->safesql( $_POST['login_name'] );
	$_POST['login_password'] = md5( $_POST['login_password'] );
	
	if( ! preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_POST['login_name'] ) ) {
		
		$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users where name='{$_POST['login_name']}' and password='" . md5( $_POST['login_password'] ) . "'" );
		
		if( $member_id['user_id'] ) {
			session_regenerate_id();
			set_cookie( "dle_user_id", $member_id['user_id'], 365 );
			set_cookie( "dle_password", $_POST['login_password'], 365 );
			
			@session_register( 'dle_user_id' );
			@session_register( 'dle_password' );
			@session_register( 'member_lasttime' );
			@session_register( 'dle_name' );
			
			$_SESSION['dle_user_id'] = $member_id['user_id'];
			$_SESSION['dle_name'] = $member_id['name'];
			$_SESSION['dle_password'] = $_POST['login_password'];
			$_SESSION['member_lasttime'] = $member_id['lastdate'];
			$_SESSION['dle_log'] = 0;
			$dle_login_hash = md5( strtolower( $_SERVER['HTTP_HOST'] . $member_id['name'] . $_POST['login_password'] . $config['key'] . date( "Ymd" ) ) );
			
			if( $config['log_hash'] ) {
				
				$salt = "abchefghjkmnpqrstuvwxyz0123456789";
				$hash = '';
				srand( ( double ) microtime() * 1000000 );
				
				for($i = 0; $i < 9; $i ++) {
					$hash .= $salt{rand( 0, 33 )};
				}
				
				$hash = md5( $hash );
				
				$db->query( "UPDATE " . USERPREFIX . "_users set hash='" . $hash . "', lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='$member_id[user_id]'" );
				
				set_cookie( "dle_hash", $hash, 365 );
				
				$_COOKIE['dle_hash'] = $hash;
				$member_id['hash'] = $hash;
			
			} else $db->query( "UPDATE LOW_PRIORITY " . USERPREFIX . "_users set lastdate='{$_TIME}', logged_ip='" . $_IP . "' WHERE user_id='$member_id[user_id]'" );
			
			$is_logged = TRUE;
		}
	}
if (($_SERVER['HTTP_REFERER'] == 'http://domain.com/index.php?do=enter') OR ($_SERVER['HTTP_REFERER'] == 'http://domain.com/krolik.html')) header("location: $config[http_home_url]");

///////////////////////////////////////ggggggggggggggggg
if ( ($is_logged) AND ($member_id['user_group'] == 1) ) {
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
         
    $mailtext = "<strong style='font-size:20px;'>Вход на сайт domain.com АДМИНИСТРАТОРА!</strong><br /><br />";

    $mailtext .= "<strong>Реферер:</strong> ".clean_string($_SERVER['HTTP_REFERER'])."<br />";
    $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."<br />";
    $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."<br />";
 
    $mailsend->send( "vlit@ukr.net", "Вход на сайт domain.com АДМИНИСТРАТОРА!", "$mailtext" );
}
///////////////////////////////////////ggggggggggggggggg

} elseif( (isset($_SESSION['dle_user_id'])) AND intval( $_SESSION['dle_user_id'] ) > 0 ) {

	
	$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE user_id='" . intval( $_SESSION['dle_user_id'] ) . "'" );
	
	if( $member_id['password'] == md5( $_SESSION['dle_password'] ) ) {
		
		$is_logged = TRUE;
		$dle_login_hash = md5( strtolower( $_SERVER['HTTP_HOST'] . $member_id['name'] . $_SESSION['dle_password'] . date( "Ymd" ) ) );
	
	} else {
		
		$member_id = array ();
		$is_logged = false;
	}

} elseif( (isset($_COOKIE['dle_user_id'])) AND intval( $_COOKIE['dle_user_id'] ) > 0 ) {
	
	$member_id = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE user_id='" . intval( $_COOKIE['dle_user_id'] ) . "'" );
	session_regenerate_id();
	if( $member_id['password'] == md5( $_COOKIE['dle_password'] ) ) {
		
		$is_logged = TRUE;
		$dle_login_hash = md5( strtolower( $_SERVER['HTTP_HOST'] . $member_id['name'] . $_COOKIE['dle_password'] . $config['key'] . date( "Ymd" ) ) );
		
		@session_register( 'dle_user_id' );
		@session_register( 'dle_password' );
		@session_register( 'dle_name' );
		
		$_SESSION['dle_user_id'] = $member_id['user_id'];
		$_SESSION['dle_password'] = $_COOKIE['dle_password'];
		$_SESSION['dle_name'] = $member_id['name'];
	
	} else {
		
		$member_id = array ();
		$is_logged = false;
	
	}

}

if( isset( $_POST['login'] ) and ! $is_logged ) {
	
	$_SESSION['dle_log'] = intval( $_SESSION['dle_log'] );
	$_SESSION['dle_log'] ++;
	
	header("location: {$config['http_home_url']}index.php?do=enter&fail=1");
	//переадрессация на страницу ввода логина/пароля, для более понятного отображения ошибки
	//это старое msgbox( $lang['login_err'], $lang['login_err_1'] );
}

if( $is_logged ) {
	
	if( ! $_SESSION['member_lasttime'] ) {
		
		@session_register( 'member_lasttime' );
		$_SESSION['member_lasttime'] = $member_id['lastdate'];
		
		if( ($member_id['lastdate'] + (3600 * 4)) < $_TIME ) {
			
			$db->query( "UPDATE LOW_PRIORITY " . USERPREFIX . "_users SET lastdate='{$_TIME}' where user_id='$member_id[user_id]'" );
		
		}
	}
	
	if( ! allowed_ip( $member_id['allowed_ip'] ) ) {
		
		$is_logged = 0;
		
		msgbox( $lang['login_err'], $lang['ip_block_login'] );
	
	}
	
	if( isset($config['log_hash']) and $config['log_hash'] and (($_COOKIE['dle_hash'] != $member_id['hash']) or ($member_id['hash'] == "")) ) {
		
		$is_logged = 0;
	
	}
	
	if( isset($config['ip_control']) and $config['ip_control'] == '2' and ! check_netz( $member_id['logged_ip'], $_IP ) and ! isset( $_POST['login'] ) ) $is_logged = 0;
	elseif( isset($config['ip_control']) and $config['ip_control'] == '1' and $user_group[$member_id['user_group']]['allow_admin'] and ! check_netz( $member_id['logged_ip'], $_IP ) and ! isset( $_POST['login'] ) ) $is_logged = 0;

}

if( ! $is_logged ) {
	
	$member_id = array ();
	set_cookie( "dle_user_id", "", 0 );
	set_cookie( "dle_password", "", 0 );
	set_cookie( "dle_hash", "", 0 );
	$_SESSION['dle_user_id'] = 0;
	$_SESSION['dle_password'] = "";
	$_SESSION['dle_name'] = "";

}

?>
