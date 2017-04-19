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

echo <<<HTML
<tr>
	<td width="140" valign="top">
	<br />{$lang['nl_message']}</td>
	<td>
		<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "message",
		language : "{$lang['wysiwyg_language']}",
		width : "98%",
		height : "400",
		plugins : "safari,advhr,advimage,emotions,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,fullscreen,nonbreaking",
		relative_urls : false,
		convert_urls : false,
		force_br_newlines : true,
        forced_root_block : '',
		force_p_newlines : false,
		dialog_type : 'window',
		extended_valid_elements : "div[align|class|style|id|title]",

		// Theme options
		theme_advanced_buttons1 : "fullscreen,|,cut,copy,|,paste,pastetext,pasteword,|,search,replace,|,outdent,indent,|,undo,redo,|,link,image,media,emotions,|,nonbreaking,|,sub,sup,|,charmap,advhr,|,insertdate,inserttime,|,cleanup",
		theme_advanced_buttons2 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,removeformat,|,code",
		theme_advanced_buttons3 : "",

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
	   	plugin_insertdate_dateFormat : "%d-%m-%Y",
	    	plugin_insertdate_timeFormat : "%H:%M:%S",

		// Example content CSS (should be your site CSS)
		content_css : "{$config['http_home_url']}engine/editor/css/content.css"


	});
</script>
    <textarea id="message" name="message" rows=10 cols=100></textarea><br><br>{$lang['nl_info_1']} <b>{$lang['nl_info_2']}</b></td>
	</tr>
HTML;

?>