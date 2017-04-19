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
	
	$config['http_home_url'] = explode( "engine/ajax/editcomments.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';

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

require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/parse.class.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';


$area = totranslit($_REQUEST['area'], true, false);

if ( !$area) $area = "news";

$allowed_areas = array(

					'news' => array (
									'comments_table' => 'comments',
									),

					'ajax' => array (
									'comments_table' => 'comments',
									),

					'lastcomments' => array (
									'comments_table' => 'comments',
									),

				);

if (! is_array($allowed_areas[$area]) ) die( "error" );

$parse = new ParseFilter( );
$parse->safe_mode = true;

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

$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
$parse->allow_image = $user_group[$member_id['user_group']]['allow_image'];

if( $_REQUEST['action'] == "edit" ) {
	$row = $db->super_query( "SELECT id, text, is_register, autor FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	if( $id != $row['id'] ) die( "error" );
	
	$have_perm = 0;
	
	if( $is_logged and (($member_id['name'] == $row['autor'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_editc']) or $user_group[$member_id['user_group']]['edit_allc']) ) {
		$have_perm = 1;
	}
	
	if( ! $have_perm ) die( "error" );
	
	if( $config['allow_comments_wysiwyg'] != "yes" ) {
		
		include_once ENGINE_DIR . '/ajax/bbcode.php';
		
		$comm_txt = $parse->decodeBBCodes( $row['text'], false );
	
	} else {
		
		$comm_txt = $parse->decodeBBCodes( $row['text'], true, "yes" );
		
		if( $user_group[$member_id['user_group']]['allow_url'] ) $link_icon = "link,dle_leech,separator,";
		else $link_icon = "";
		if( $user_group[$member_id['user_group']]['allow_image'] ) $link_icon .= "image,";
		
		$bb_code = <<<HTML

<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "dleeditcomments{$id}",
		language : "{$lang['wysiwyg_language']}",
		width : "99%",
		height : "220",
		plugins : "safari,emotions,inlinepopups",
		convert_urls : false,
		force_p_newlines : false,
		force_br_newlines : true,
		forced_root_block : '',
		dialog_type : 'window',
		extended_valid_elements : "div[align|class|style|id|title]",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,separator,{$link_icon}emotions,dle_quote,dle_hide",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
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

			ed.addButton('dle_leech', {
			title : '{$lang['bb_t_leech']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_leech.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceReplaceContent',false,"[leech="+enterURL+"]{\$selection}[/leech]");
			}
	           });

   		 }


	});
</script>
HTML;
	
	}
	
	$buffer = <<<HTML
<form name="ajaxcomments{$id}" id="ajaxcomments{$id}" metod="post" action="">
<div class="editor">
{$bb_code}
<textarea name="dleeditcomments{$id}" id="dleeditcomments{$id}" onclick="setNewField(this.name, document.ajaxcomments{$id})" style="width:99%; height:150px; border:1px solid #E0E0E0">{$comm_txt}</textarea><br>
<div align="right" style="width:99%;padding-top:5px;"><input class=bbcodes title="$lang[bb_t_apply]" type=button onclick="ajax_save_comm_edit('{$id}', '{$area}'); return false;" value="$lang[bb_b_apply]">
<input class=bbcodes title="$lang[bb_t_cancel]" type=button onclick="ajax_cancel_comm_edit('{$id}'); return false;" value="$lang[bb_b_cancel]">
</div></div>
</form>
HTML;
} elseif( $_REQUEST['action'] == "save" ) {
	$row = $db->super_query( "SELECT id, post_id, text, is_register, autor, approve FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	if( $id != $row['id'] ) die( "error" );
	
	$have_perm = 0;
	
	if( $is_logged AND (($member_id['name'] == $row['autor'] AND $row['is_register'] AND $user_group[$member_id['user_group']]['allow_editc']) OR $user_group[$member_id['user_group']]['edit_allc'] OR $user_group[$member_id['user_group']]['admin_comments']) ) {
		$have_perm = 1;
	}
	
	if( ! $have_perm ) die( "error" );
	
	if( $config['allow_comments_wysiwyg'] == "yes" ) {
		
		$parse->wysiwyg = true;
		$use_html = true;
		
		$parse->ParseFilter( Array ('div','span','p','br','strong','em','ul','li','ol'), Array (), 0, 1 );
		
		if( $user_group[$member_id['user_group']]['allow_url'] ) $parse->tagsArray[] = 'a';
		if( $user_group[$member_id['user_group']]['allow_image'] ) $parse->tagsArray[] = 'img';
	
	} else
		$use_html = false;
	
	$comm_txt = trim( $parse->BB_Parse( $parse->process( convert_unicode( $_POST['comm_txt'], $config['charset'] ) ), $use_html ) );
	
	if( $parse->not_allowed_tags ) {
		die( "error" );
	}

	if( $parse->not_allowed_text ) {
		die( "error" );
	}
	
	if( strlen( $comm_txt ) > $config['comments_maxlen'] ) {
		
		die( "error" );
	
	}
	
	if( $comm_txt == "" ) {
		
		die( "error" );
	
	}
	//* Автоперенос длинных слов
	if( intval( $config['auto_wrap'] ) ) {
		
		$comm_txt = preg_split( '((>)|(<))', $comm_txt, - 1, PREG_SPLIT_DELIM_CAPTURE );
		$n = count( $comm_txt );
		
		for($i = 0; $i < $n; $i ++) {
			if( $comm_txt[$i] == "<" ) {
				$i ++;
				continue;
			}
			
			$comm_txt[$i] = preg_replace( "#([^\s\n\r]{" . intval( $config['auto_wrap'] ) . "})#i", "\\1<br />", $comm_txt[$i] );
		}
		
		$comm_txt = join( "", $comm_txt );
	
	}
	
	$comm_update = $db->safesql( $comm_txt );
	
	$db->query( "UPDATE " . PREFIX . "_{$allowed_areas[$area]['comments_table']} set text='$comm_update', approve='1' where id = '$id'" );
	
	if( ! $row['approve'] ) $db->query( "UPDATE " . PREFIX . "_post set comm_num=comm_num+1 WHERE id='{$row['post_id']}'" );
	
	$comm_txt = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $comm_txt );
	$buffer = stripslashes( $comm_txt );

} else
	die( "error" );

$db->close();

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $buffer;
?>