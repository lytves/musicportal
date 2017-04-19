<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting(7);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');

include ENGINE_DIR.'/data/config.php';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/rss.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = totranslit( $_COOKIE['selected_language'], false, false );

	if (@is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

$user_group = get_vars ( "usergroup" );

if (! $user_group) {
	$user_group = array ();
	
	$db->query ( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row () ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = $value;
		}
	
	}
	set_vars ( "usergroup", $user_group );
	$db->free ();
}

require_once ENGINE_DIR.'/modules/sitelogin.php';
require_once (ENGINE_DIR.'/classes/parse.class.php');

if(!$user_group[$member_id['user_group']]['admin_rss']) {die ("error");}


function get_content ($scheme, $host, $path, $query, $others=''){

 if (function_exists('curl_init')) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $scheme."://".$host.$path."?".$query);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_REFERER, $scheme."://".$host.$path.$query);
		if ($others != '') curl_setopt($ch, CURLOPT_COOKIE, $others);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		if ($data) return $data; else return false;
 } 
 else
 {
   if (!empty($others)) $others = "Cookie: ".$others."\r\n";
   else $others = "";

   $post="GET $path HTTP/1.1\r\nHost: $host\r\nContent-type: application/x-www-form-urlencoded\r\n{$others}User-Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\nContent-length: ".strlen($query)."\r\nConnection: close\r\n\r\n$query";

   $h=@fsockopen($host,80, $errno, $errstr, 30);

	if (!$h) {
	   return false;
	} 
    else 
    {
         fwrite($h,$post);
    
         for($a=0,$r='';!$a;){
            $b=fread($h,8192);
            $r.=$b;
            $a=(($b=='')?1:0);
         }

         fclose($h);
    }

  return $r;
 }

}

function convert ( $from, $to, $string ) {

     if (function_exists('iconv')) {

  	  return @iconv($from, $to, $string);

     } else {
  
	  return $string;

	 }
}

	$news_id = intval($_REQUEST['news_id']);
	$rss_id = intval($_REQUEST['rss_id']);
	$link = parse_url(urldecode($_REQUEST['link']));
	$parse = new ParseFilter(Array(), Array(), 1, 1);
	$parse->leech_mode = true;

	$rss = $db->super_query("SELECT * FROM " . PREFIX . "_rss WHERE id='$rss_id'");

	$rss['cookie'] = str_replace("\n", "; ", str_replace("\r", "", stripslashes(rtrim($rss['cookie']))));

	$content = get_content ($link[scheme], $link['host'], $link['path'], $link['query'], $rss['cookie']);

	$rss['search'] = addcslashes(stripslashes($rss['search']), "[]!-.?*\\()|");
	$rss['search'] = str_replace("{get}", "(.*)", $rss['search']);
	$rss['search'] = str_replace("{skip}", ".*", $rss['search']);
	$rss['search'] = preg_replace("![\n\r\t]!s", "", $rss['search']);
	$rss['search'] = preg_replace("!>[ ]{1,}<!s", "><", $rss['search']);

	if ($rss['search'] != "" && preg_match("!".$rss['search']."!Us", $content, $found)) {

       $temp = array();
       for($i=1; $i < sizeof($found); $i++) {
            $temp[] = $found[$i];
       }

       $content = implode("", $temp);

		if ($_POST['rss_charset'] != strtolower($config['charset']) AND $content != "") $content = convert($_POST['rss_charset'], strtolower($config['charset']), $content);

		if ($content != "") {

			$content .= "<br /><br /><i>".$lang['rss_info']." ".$link['host']."</i>";

		}

		if ($rss['text_type'])
		{
			$content = $parse->decodeBBCodes($content, false);

		}
		else
    	{
			$content = $parse->decodeBBCodes($content, true, "yes");
		}

		if ($content != "") {

			$buffer = <<<HTML
<textarea rows="15" style="width:600px;" id="full_{$news_id}" name="content[{$news_id}][full]">{$content}</textarea>
HTML;
		} else $buffer = "<font color='red'>".$lang['rss_error']."</font>";
	} else $buffer = "<font color='red'>".$lang['rss_error']."</font>";


@header("Content-type: text/css; charset=".$config['charset']);
echo $buffer;
?>