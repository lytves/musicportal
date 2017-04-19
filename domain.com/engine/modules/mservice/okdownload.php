<?php

$r =  $_GET['r'];
$real_ip = $_GET['ip'];
$salt = $_GET['salt'];



if ( ($r) && (preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $real_ip)) && ($salt == 'pjzzzqxt')) {

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', '../..' );

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

if( $is_logged ) $where = "user_id = '{$member_id['user_id']}'";
else $where = "ip = '{$real_ip}'";

$time = time();
$downtime = 600;
//600 секунд = 10 минут
$downtime = ($time - $downtime);

$row = $db->super_query( "SELECT mid FROM ".PREFIX."_mservice_downloads WHERE filename = '$r' AND {$where} AND time_php >= '$downtime' LIMIT 1" );

if( !$row['mid'] AND count( explode( ".", $real_ip ) ) == 4 ) {

	if( !$is_logged ) $member_id['user_id'] = 0;
  $referer = addslashes($_SERVER['HTTP_REFERER']);
  $user_agent = addslashes($_SERVER['HTTP_USER_AGENT']);
      
	$row = $db->query( "UPDATE ".PREFIX."_mservice SET download = download + 1 WHERE filename = '$r'" );
	$row = $db->query( "INSERT INTO ".PREFIX."_mservice_downloads SET time_php = '$time', filename ='$r', user_id = '$member_id[user_id]', ip= '$real_ip', mid = (SELECT mid FROM ".PREFIX."_mservice WHERE filename = '$r'), user_agent = '$user_agent', referer = '$referer'" );

}
exit();
} else {
		define ( 'DATALIFEENGINE', true );
    define ( 'ROOT_DIR', '../../..' );
    define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

		//дальше отправляем письмо с текстом ошибки на два мыла
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }

    include_once ENGINE_DIR . '/classes/mail.class.php';
    require ENGINE_DIR . '/data/config.php';
    $ref = $config['http_home_url'].substr($_SERVER['REQUEST_URI'],1); // Адрес страницы ошибки
    $mailsend = new dle_mail( $config );
    $mailsend->from = 'robot@domain.com';
    $mailsend->bcc[0] = 'odmin@domain.com';
    $mailsend->html_mail = true;
         
    $mailtext = "<strong style='font-size:20px;'>Not Found - Error 404 (Не найдено - Ошибка 404)!</strong><br /><br />";
    $mailtext .= "<strong>Страница с ошибкой:</strong> ".clean_string($ref)."<br /><br />";

    $mailtext .= "<strong>Реферер:</strong> ".clean_string($_SERVER['HTTP_REFERER'])."<br />";
    $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."<br />";
    $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."<br />";
 
    $mailsend->send( "vlit@ukr.net", "404 ошибка на сайте domain.com", "$mailtext" );
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    header("location: http://domain.com/");
}
?>
