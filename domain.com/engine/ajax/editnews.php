<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/editnews.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

$_COOKIE['dle_skin'] = trim(totranslit( $_COOKIE['dle_skin'], false, false ));

if( $_COOKIE['dle_skin'] ) {
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

require_once ENGINE_DIR . '/classes/parse.class.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

$parse = new ParseFilter( Array (), Array (), 1, 1 );

if( ! $is_logged ) die( "error" );

$id = intval( $_REQUEST['id'] );

if( ! $id ) die( "error" );

//################# Определение групп пользователей
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

if( $_REQUEST['action'] == "edit" ) {
	$row = $db->super_query( "SELECT id, title, category, short_story, full_story, autor, allow_br, reason FROM " . PREFIX . "_post where id = '$id'" );
	
	if( $id != $row['id'] ) die( "error" );
	
	$cat_list = explode( ',', $row['category'] );
	
	$have_perm = 0;
	
	if( $user_group[$member_id['user_group']]['allow_all_edit'] ) {
		$have_perm = 1;
		
		$allow_list = explode( ',', $user_group[$member_id['user_group']]['cat_add'] );
		
		foreach ( $cat_list as $selected ) {
			if( $allow_list[0] != "all" and ! in_array( $selected, $allow_list ) ) $have_perm = 0;
		}
	}
	
	if( $user_group[$member_id['user_group']]['allow_edit'] and $row['autor'] == $member_id['name'] ) {
		$have_perm = 1;
	}
	
	if( ($member_id['user_group'] == 1) ) {
		$have_perm = 1;
	}
	
	if( ! $have_perm ) die( "error" );
	
	if( $_REQUEST['field'] == "short" ) $news_txt = $row['short_story'];
	elseif( $_REQUEST['field'] == "full" and (strlen( $row['full_story'] ) > 13) ) $news_txt = $row['full_story'];
	else $news_txt = $row['short_story'];
	
	if( $row['allow_br'] and ! $config['allow_quick_wysiwyg'] ) {
		
		$news_txt = $parse->decodeBBCodes( $news_txt, false );
		$fix_br = "checked";
	
	} else {
		
		if( $config['allow_quick_wysiwyg'] ) $news_txt = $parse->decodeBBCodes( $news_txt, true, "yes" );
		else $news_txt = $parse->decodeBBCodes( $news_txt, true, "no" );
		
		$fix_br = "";
	
	}
	
	$row['title'] = $parse->decodeBBCodes( $row['title'], false );
	
	$addtype = "addnews";
	
	if( ! $config['allow_quick_wysiwyg'] ) {
		
		include_once ENGINE_DIR . '/ajax/bbcode.php';
	
	} else {
		
		$bb_code = <<<HTML

<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "dleeditnews{$id}",
		language : "{$lang['wysiwyg_language']}",
		width : "99%",
		height : "370",
		plugins : "safari,advhr,advimage,emotions,inlinepopups,insertdatetime,media,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking",
		relative_urls : false,
		convert_urls : false,
		force_br_newlines : true,
        forced_root_block : '',
		force_p_newlines : false,
		dialog_type : 'window',
		extended_valid_elements : "div[align|class|style|id|title]",

		// Theme options
		theme_advanced_buttons1 : "cut,copy,|,paste,pastetext,pasteword,|,search,replace,|,outdent,indent,|,undo,redo,|,dle_upload,image,media,dle_mp,dle_tube,dle_mp3,emotions,|,dle_break,dle_page",
		theme_advanced_buttons2 : "fontselect,fontsizeselect,|,sub,sup,|,charmap,advhr,|,insertdate,inserttime,|,nonbreaking,dle_quote,dle_code,dle_hide,|,visualaid",
		theme_advanced_buttons3 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,dle_spoiler,|,link,dle_leech,|,forecolor,backcolor,|,removeformat,cleanup,|,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,


		// Example content CSS (should be your site CSS)
		content_css : "{$config['http_home_url']}engine/editor/css/content.css",

		setup : function(ed) {
		        // Add a custom button
			ed.addButton('dle_quote', {
			title : '{$lang['bb_t_quote']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_quote.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[quote]{\$selection}[/quote]');
			}
	           });

			ed.addButton('dle_hide', {
			title : '{$lang['bb_t_hide']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_hide.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[hide]{\$selection}[/hide]');
			}
	           });

			ed.addButton('dle_tube', {
			title : '{$lang['bb_t_yvideo']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_tube.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[youtube="+enterURL+"]");
			}
	           });

			ed.addButton('dle_code', {
			title : '{$lang['bb_t_code']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_code.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[code]{\$selection}[/code]');
			}
	           });

			ed.addButton('dle_spoiler', {
			title : '',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_spoiler.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[spoiler]{\$selection}[/spoiler]');
			}
	           });

			ed.addButton('dle_break', {
			title : '{$lang['bb_t_br']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_break.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceInsertContent',false,'{PAGEBREAK}');
			}
	           });

			ed.addButton('dle_page', {
			title : '{$lang['bb_t_p']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_page.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_page']}", "1");
				var enterTITLE = prompt("{$lang['bb_page_name']}", "");
				if (enterURL == null) enterURL = "1";
				if (enterTITLE == null) enterTITLE = "";
				ed.execCommand('mceInsertContent',false,"[page="+enterURL+"]"+enterTITLE+"[/page]");
			}
	           });

			ed.addButton('dle_leech', {
			title : '{$lang['bb_t_leech']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_leech.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceReplaceContent',false,"[leech="+enterURL+"]{\$selection}[/leech]");
			}
	           });

			ed.addButton('dle_mp', {
			title : '{$lang['bb_t_video']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[video="+enterURL+"]");
			}
	           });

			ed.addButton('dle_mp3', {
			title : '',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp3.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[audio="+enterURL+"]");
			}
	           });
   		 }


	});
</script>
HTML;
	
	}
	
	$buffer = <<<HTML
<form name="ajaxnews{$id}" id="ajaxnews{$id}" metod="post" action="">
<div style="padding-bottom:5px;"><input type="text" id='edit-title-{$id}' style="width:250px; font-family:verdana; font-size:11px; border:1px solid #E0E0E0" value="{$row['title']}" /></div>
{$bb_code}
<textarea name="dleeditnews{$id}" id="dleeditnews{$id}" onclick="setNewField(this.name, document.ajaxnews{$id})" style="width:99%; height:250px;font-family:verdana; font-size:11px; border:1px solid #E0E0E0">{$news_txt}</textarea>
<div style="padding-top:5px;">{$lang['reason']} <input type="text" id='edit-reason-{$id}' style="width:250px; font-family:verdana; font-size:11px; border:1px solid #E0E0E0" value="{$row['reason']}"></div>
<div align="right" style="width:99%;"><span style="font-family:Tahoma; font-size:11px;"><input type="checkbox" name="allow_br_{$id}" id="allow_br_{$id}" value="1" {$fix_br}>&nbsp;<label for="allow_br_{$id}">{$lang['aj_allowbr']}</label>&nbsp;</span><input class=bbcodes title="$lang[bb_t_apply]" type=button onclick="ajax_save_for_edit('{$id}', '{$_REQUEST[field]}'); return false;" value="$lang[bb_b_apply]">
<input class=bbcodes title="$lang[bb_t_cancel]" type=button onclick="ajax_cancel_for_edit('{$id}'); return false;" value="$lang[bb_b_cancel]">
</div>
</form>
HTML;

} elseif( $_REQUEST['action'] == "save" ) {
	$row = $db->super_query( "SELECT id, title, category, short_story, full_story, autor FROM " . PREFIX . "_post where id = '$id'" );
	
	if( $id != $row['id'] ) die( "error" );
	
	$cat_list = explode( ',', $row['category'] );
	
	$have_perm = 0;
	
	if( $user_group[$member_id['user_group']]['allow_all_edit'] ) {
		$have_perm = 1;
		
		$allow_list = explode( ',', $user_group[$member_id['user_group']]['cat_add'] );
		
		foreach ( $cat_list as $selected ) {
			if( $allow_list[0] != "all" and ! in_array( $selected, $allow_list ) ) $have_perm = 0;
		}
	}
	
	if( $user_group[$member_id['user_group']]['allow_edit'] and $row['autor'] == $member_id['name'] ) {
		$have_perm = 1;
	}
	
	if( ($member_id['user_group'] == 1) ) {
		$have_perm = 1;
	}
	
	if( ! $have_perm ) die( "error" );
	
	$is_full = false;
	
	if( $_REQUEST['field'] == "full" and (strlen( $row['full_story'] ) > 12) ) $is_full = true;
	
	$allow_br = intval( $_REQUEST['allow_br'] );
	
	if( $allow_br ) $use_html = false;
	else $use_html = true;

	$_POST['title'] = $db->safesql( $parse->process( trim( strip_tags (convert_unicode( $_POST['title'], $config['charset']  ) ) ) ) );

	if ( $config['allow_quick_wysiwyg'] ) $parse->allow_code = false;

	$news_txt = $parse->BB_Parse( $parse->process( convert_unicode( $_POST['news_txt'], $config['charset'] ) ), $use_html );

	$editreason = $db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( convert_unicode( $_POST['reason'], $config['charset'] ) ) ) ), ENT_QUOTES ) );
	
	if( $editreason != "" ) $view_edit = 1;
	else $view_edit = 0;
	$added_time = time() + ($config['date_adjust'] * 60);
	
	if( ! $_POST['title'] or ! $news_txt ) die( "error" );

	if ($parse->not_allowed_text ) die( "error" );
	
	$news_update = $db->safesql( $news_txt );
	
	if( ! $is_full ) $db->query( "UPDATE " . PREFIX . "_post set title='{$_POST['title']}', short_story='$news_update', allow_br='$allow_br', editdate='$added_time', editor='{$member_id['name']}', reason='$editreason', view_edit='$view_edit' WHERE id = '$id'" );
	else $db->query( "UPDATE " . PREFIX . "_post set title='{$_POST['title']}', full_story='$news_update', allow_br='$allow_br', editdate='$added_time', editor='{$member_id['name']}', reason='$editreason', view_edit='$view_edit' WHERE id = '$id'" );
	
	clear_cache( 'news_' );
	
	$news_txt = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $news_txt );
	
	$news_page = 1;
	$news_seiten = explode( "{PAGEBREAK}", $news_txt );
	$anzahl_seiten = count( $news_seiten );
	$news_txt = $news_seiten[$news_page - 1];
	
	// remove <br/> at beginning of the string
	$news_txt = preg_replace( '#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $news_txt );
	
	$replacepage = "<a href=\"" . $config['http_home_url'] . "index.php?newsid=" . $id . "&news_page=\\1\">\\2</a>";
	$news_txt = preg_replace( "'\[PAGE=(.*?)\](.*?)\[/PAGE\]'si", $replacepage, $news_txt );
	
	if( $config['files_allow'] == "yes" ) if( strpos( $news_txt, "[attachment=" ) !== false ) {
		$news_txt = show_attach( $news_txt, $id );
	}
	
	$buffer = stripslashes( $news_txt );

} else
	die( "error" );

$db->close();

$buffer = str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $buffer );

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $buffer;
?>