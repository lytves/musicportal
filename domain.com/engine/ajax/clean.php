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

	$config['http_home_url'] = explode("engine/ajax/clean.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = trim(totranslit( $_COOKIE['selected_language'], false, false ));

	if ($_COOKIE['selected_language'] != "" AND @is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR.'/modules/sitelogin.php';

if(($member_id['user_group'] != 1)) {die ("error");}

if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {

	  die ("error");

}


if ($_REQUEST['step'] == 10) {
	$_REQUEST['step'] = 11;
	$db->query("TRUNCATE TABLE " . PREFIX . "_logs");
	$db->query("TRUNCATE TABLE " . USERPREFIX . "_lostdb");
	$db->query("TRUNCATE TABLE " . PREFIX . "_flood");
	$db->query("TRUNCATE TABLE " . PREFIX . "_poll_log");

}

if ($_REQUEST['step'] == 8) {
	$_REQUEST['step'] = 9;
	$db->query("TRUNCATE TABLE " . USERPREFIX . "_pm");
	$db->query("UPDATE " . USERPREFIX . "_users set pm_all='0', pm_unread='0'");
}

if ($_REQUEST['step'] == 6) {
		$_REQUEST['step'] = 7;

		$sql = $db->query("SELECT name, user_id, news_num, comm_num FROM " . USERPREFIX . "_users");

		while($row = $db->get_row($sql)){

			$posts = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_post WHERE autor = '{$row['name']}'");
			$comms = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_comments WHERE user_id = '{$row['user_id']}'");

		  if (($posts['count'] != $row['news_num']) OR ($comms['count'] != $row['comm_num']))
			$db->query("UPDATE " . USERPREFIX . "_users set news_num={$posts['count']}, comm_num={$comms['count']} where user_id='{$row['user_id']}'");
		}
		$db->free ($sql);
}


if ($_REQUEST['step'] == 4) {
	if ((@strtotime($_REQUEST['date']) === -1) OR (trim($_REQUEST['date']) == ""))
		$_REQUEST['step'] = 3;
	else {
		$_REQUEST['step'] = 5;
		$sql = $db->query("SELECT COUNT(*) as count, post_id FROM " . PREFIX . "_comments WHERE date < '{$_REQUEST['date']}' GROUP BY post_id");

		while($row = $db->get_row($sql)){

		$db->query("UPDATE " . PREFIX . "_post set comm_num=comm_num-{$row['count']} where id='{$row['post_id']}'");

		}

	   $db->query("DELETE FROM " . PREFIX . "_comments WHERE date < '{$_REQUEST['date']}'");
	   $db->free ($sql);
	   clear_cache();
	}
}


if ($_REQUEST['step'] == 2) {
	if ((@strtotime($_REQUEST['date']) === -1) OR (trim($_REQUEST['date']) == ""))
		$_REQUEST['step'] = 1;
	else {
		$_REQUEST['step'] = 3;

		$sql = $db->query("SELECT id FROM " . PREFIX . "_post WHERE date < '{$_REQUEST['date']}'");

		while($row = $db->get_row($sql)){

			$db->query("DELETE FROM " . PREFIX . "_comments WHERE post_id='{$row['id']}'");
			$db->query("DELETE FROM " . PREFIX . "_files WHERE news_id = '{$row['id']}'");
			$db->query("DELETE FROM " . PREFIX . "_poll WHERE news_id = '{$row['id']}'");
			$db->query("DELETE FROM " . PREFIX . "_poll_log WHERE news_id = '{$row['id']}'");
			$db->query("DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$row['id']}'" );
			$db->query("DELETE FROM " . PREFIX . "_post_log WHERE news_id = '{$row['id']}'" );

			$getfiles = $db->query("SELECT onserver FROM " . PREFIX . "_files WHERE news_id = '{$row['id']}'");

			while($file = $db->get_row($getfiles)){
				@unlink(ROOT_DIR."/uploads/files/".$file['onserver']);
			}

			$db->free ($getfiles);

			$image = $db->super_query("SELECT images  FROM " . PREFIX . "_images where news_id = '{$row['id']}'");
	
	        $listimages = explode("|||", $image['images']);
	
	   	      if ($image['images'] != "")
	           foreach ($listimages as $dataimages) {
	
				  $url_image = explode("/", $dataimages);
	
				  if (count($url_image) == 2) {
	
					$folder_prefix = $url_image[0]."/";
					$dataimages = $url_image[1];
	
				  } else {
	
					$folder_prefix = "";
					$dataimages = $url_image[0];
	
		    	  }
	
				@unlink(ROOT_DIR."/uploads/posts/".$folder_prefix.$dataimages);
				@unlink(ROOT_DIR."/uploads/posts/".$folder_prefix."thumbs/".$dataimages);
			  }
	
		    $db->query("DELETE FROM " . PREFIX . "_images WHERE news_id = '{$row['id']}'");

		}

	   $db->query("DELETE FROM " . PREFIX . "_post WHERE date < '{$_REQUEST['date']}'");
	   $db->free ($sql);
	   clear_cache();
	}
}

if ($_REQUEST['step'] == 11) {

$rs = $db->query("SHOW TABLE STATUS FROM `".DBNAME."`");
			while ($r = $db->get_array($rs)) {
			$db->query("OPTIMIZE TABLE  ". $r['Name']);
			}
$db->free ($rs);

$db->query("SHOW TABLE STATUS FROM `".DBNAME."`");
			$mysql_size = 0;
			while ($r = $db->get_array()) {
			if (strpos($r['Name'], PREFIX."_") !== false)
			$mysql_size += $r['Data_length'] + $r['Index_length'] ;
			}

$lang['clean_finish'] = str_replace ('{db-alt}', '<font color="red">'.formatsize($_REQUEST['size']).'</font>', $lang['clean_finish']);
$lang['clean_finish'] = str_replace ('{db-new}', '<font color="red">'.formatsize($mysql_size).'</font>', $lang['clean_finish']);
$lang['clean_finish'] = str_replace ('{db-compare}', '<font color="red">'.formatsize($_REQUEST['size'] - $mysql_size).'</font>', $lang['clean_finish']);

$buffer = <<<HTML
<br />{$lang['clean_finish']}
<br /><br />
HTML;

}

if ($_REQUEST['step'] == 9) {
$buffer = <<<HTML
<br />{$lang['clean_logs']}
<br /><br /><font color="red"><span id="status"></span></font><br /><br />
		<input id = "next_button" onclick="start_clean('10', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:100px;" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('11', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:150px;" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 7) {
$buffer = <<<HTML
<br />{$lang['clean_pm']}
<br /><br /><font color="red"><span id="status"></span></font><br /><br />
		<input id = "next_button" onclick="start_clean('8', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:100px;" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('9', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:150px;" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 5) {
$buffer = <<<HTML
<br />{$lang['clean_users']}
<br /><br /><font color="red"><span id="status"></span></font><br /><br />
		<input id = "next_button" onclick="start_clean('6', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:100px;" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('7', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:150px;" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 3) {
$buffer = <<<HTML
<br />{$lang['clean_comments']}<br /><br />{$lang['addnews_date']}&nbsp;<input type="text" name="date" id="f_date_c" size="20"  class=edit>
<img src="engine/skins/images/img.gif"  align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" />
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "f_date_c",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "f_trigger_c",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
        singleClick    :    true
    });
</script>
<br /><br /><font color="red"><span id="status"></span></font><br /><br />
		<input id = "next_button" onclick="start_clean('4', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:100px;" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('5', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:150px;" type="button" value="{$lang['clean_skip']}">
HTML;
}

if ($_REQUEST['step'] == 1) {
$buffer = <<<HTML
<br />{$lang['clean_news']}<br /><br />{$lang['addnews_date']}&nbsp;<input type="text" name="date" id="f_date_c" size="20"  class=edit>
<img src="engine/skins/images/img.gif"  align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" />
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "f_date_c",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "f_trigger_c",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
        singleClick    :    true
    });
</script>
<br /><br /><font color="red"><span id="status"></span></font><br /><br />
		<input id = "next_button" onclick="start_clean('2', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:100px;" type="button" value="{$lang['edit_next']}">&nbsp;
		<input id = "skip_button" onclick="start_clean('3', '{$_REQUEST['size']}'); return false;" class="buttons" style="width:150px;" type="button" value="{$lang['clean_skip']}">
HTML;
}

@header("Content-type: text/css; charset=".$config['charset']);
echo $buffer;
?>