<?php

$r =  $_GET['r'];
$real_ip = $_GET['ip'];
$salt = $_GET['salt'];



if ( ($r) && (preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $real_ip)) && ($salt == 'cvxvoins')) {

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', '../..' );

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

$time = time();

if( !$is_logged ) $member_id['user_id'] = 0;
if (isset($_SERVER['HTTP_REFERER'])) $referer = addslashes($_SERVER['HTTP_REFERER']); else $referer = '';
if (isset($_SERVER['HTTP_USER_AGENT'])) $user_agent = addslashes($_SERVER['HTTP_USER_AGENT']); else $user_agent = '';
      
	$row = $db->query( "INSERT INTO ".PREFIX."_mservice_plays SET time_php = '$time', filename ='$r', user_id = '$member_id[user_id]', ip= '$real_ip', mid = (SELECT mid FROM ".PREFIX."_mservice WHERE filename = '$r'), user_agent = '$user_agent', referer = '$referer'" );

exit();
} else exit();
?>