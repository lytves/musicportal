<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
$row = $db->super_query("SELECT subj, text, user_from, date FROM " . USERPREFIX . "_pm WHERE user = '$member_id[user_id]' AND folder = 'inbox' ORDER BY pm_read ASC, date DESC LIMIT 0,1");

$lang['pm_alert'] = str_replace ("{user}"  , $member_id['name'], str_replace ("{num}"  , intval($member_id['pm_unread']), $lang['pm_alert']));
$row['subj'] = substr(stripslashes($row['subj']),0,45)." ...";
$row['text'] = str_replace ("<br />", " ", $row['text']);
$row['text'] = substr(strip_tags (stripslashes($row['text']) ),0,340)." ...";

$atime = 3600;
//3600 секунд = 1час
$atime = (time() - $atime);

if (($row['date'] > $atime) && ($member_id['reg_date'] > $atime)) $pm_alert ='';
else {
$pm_alert = <<<HTML
<div id="newpm" title="{$lang['pm_atitle']}" style="display:none;" ><br />{$lang['pm_alert']}
<br /><br />
{$lang['pm_asub']} <b>{$row['subj']}</b><br />
{$lang['pm_from']} <b>{$row['user_from']}</b><br /><br /><i>{$row['text']}</i></div>
<script type="text/javascript">    
$(function(){

    $('#newpm').dialog({
		autoOpen: true,
		show: 'fade',
		hide: 'fade',
		width: 450,
		height: 270,
		buttons: {
			"{$lang['pm_close']}" : function() { 
				$(this).dialog("close");						
			}, 
			"{$lang['pm_aread']}": function() {
				document.location='{$PHP_SELF}?do=pm';			
			}
		}
	});
});
</script>
HTML;
}
?>