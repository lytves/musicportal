<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}


$area = totranslit($_REQUEST['area'], true, false);

if ( !$area) $area = "news";

$allowed_areas = array(

					'news' => array (
									'comments_table' => 'comments',
									'counter_table' => 'post'
									),

					'ajax' => array (
									'comments_table' => 'comments',
									'counter_table' => 'post'
									),

					'lastcomments' => array (
									'comments_table' => 'comments',
									'counter_table' => 'post'
									),

				);

if (! is_array($allowed_areas[$area]) ) die ("Hacking attempt! Not Allowed Area");

require_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
$parse->allow_image = $user_group[$member_id['user_group']]['allow_image'];

$id = intval( $_REQUEST['id'] );
$action = $_REQUEST['action'];
$subaction = $_REQUEST['subaction'];

if( $id and $action == "comm_edit" and $subaction != "addcomment" ) {
	
	$row = $db->super_query( "select * from " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	$tpl->load_template( 'addcomments.tpl' );
	
	if( $is_logged and (($member_id['name'] == $row['autor'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_editc']) or $member_id['user_group'] == '1' or $user_group[$member_id['user_group']]['edit_allc']) ) {
		
		if( $config['allow_comments_wysiwyg'] != "yes" ) {
			
			include_once ENGINE_DIR . '/modules/bbcode.php';
			
			$text = $parse->decodeBBCodes( $row['text'], false );
		
		} else
			$text = $parse->decodeBBCodes( $row['text'], TRUE, $config['allow_comments_wysiwyg'] );
		
		
		if( $config['allow_comments_wysiwyg'] == "yes" ) {
			include_once ENGINE_DIR . '/editor/comments.php';
			$allow_comments_ajax = true;
			$tpl->set( '{editor}', $wysiwyg );
		} else {
			$tpl->set( '{editor}', $bb_code );
		}
		
		$tpl->set( '{text}', $text );
		$tpl->set( '{title}', $lang['comm_title'] );
		$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
		$tpl->set( '{sec_code}', "" );
		
		$tpl->set_block( "'\\[not-logged\\].*?\\[/not-logged\\]'si", "" );
		
		$tpl->copy_template = "<form  method=\"post\" name=\"dle-comments-form\" id=\"dle-comments-form\" action=\"\">" . $tpl->copy_template . "
<input type=\"hidden\" name=\"subaction\" value=\"addcomment\" />
<input type=\"hidden\" name=\"id\" value=\"$id\" /></form>";
		
		$tpl->compile( 'content' );
		$tpl->clear();
	
	} else
		msgbox( $lang['comm_err_2'], $lang['comm_err_3'] );
} elseif( $id and $action == "comm_edit" and $subaction == "addcomment" ) {
	
	$row = $db->super_query( "SELECT * FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	if( $row['autor'] and $is_logged and (($member_id['name'] == $row['autor'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_editc']) or $member_id['user_group'] == '1' or $user_group[$member_id['user_group']]['edit_allc']) ) {
		
		if( $config['allow_comments_wysiwyg'] != "yes" ) $comments = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['comments'] ), false ) );
		else {
			$parse->wysiwyg = true;
			
			$parse->ParseFilter( Array ('div', 'span', 'p', 'br', 'strong', 'em', 'ul', 'li', 'ol' ), Array (), 0, 1 );
			
			if( $user_group[$member_id['user_group']]['allow_url'] ) $parse->tagsArray[] = 'a';
			if( $user_group[$member_id['user_group']]['allow_image'] ) $parse->tagsArray[] = 'img';
			
			$comments = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['comments'] ) ) );
		}
		
		//* Автоперенос длинных слов
		if( intval( $config['auto_wrap'] ) ) {
			
			$comments = preg_split( '((>)|(<))', $comments, - 1, PREG_SPLIT_DELIM_CAPTURE );
			$n = count( $comments );
			
			for($i = 0; $i < $n; $i ++) {
				if( $comments[$i] == "<" ) {
					$i ++;
					continue;
				}
				
				$comments[$i] = preg_replace( "#([^\s\n\r]{" . intval( $config['auto_wrap'] ) . "})#i", "\\1<br />", $comments[$i] );
			}
			
			$comments = join( "", $comments );
		
		}
		
		if( strlen( $comments ) > $config['comments_maxlen'] ) {
			
			msgbox( $lang['comm_err_2'], $lang['news_err_3'] . " <a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>" );
		
		} elseif( $parse->not_allowed_tags ) {
			
			msgbox( $lang['comm_err_2'], $lang['news_err_33'] . " <a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>" );
		
		} elseif( $parse->not_allowed_text ) {
			
			msgbox( $lang['comm_err_2'], $lang['news_err_37'] . " <a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>" );
		
		} else {
			
			$db->query( "UPDATE " . PREFIX . "_{$allowed_areas[$area]['comments_table']} set text='$comments' where id='$id'" );
			
			msgbox( $lang['comm_ok'], $lang['comm_ok_1'] . " <a href=\"{$_SESSION['referrer']}\">$lang[all_prev]</a>" );
		
		}
	
	} else
		msgbox( $lang['comm_err_2'], $lang['comm_err_3'] );
} elseif( $id and $action == "comm_del" ) {
	
	$row = $db->super_query( "SELECT * FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
	
	$author = $row['autor'];
	$is_reg = $row['is_register'];
	$post_id = $row['post_id'];
	
	if( $_GET['dle_allow_hash'] != "" and $_GET['dle_allow_hash'] == $dle_login_hash and $is_logged and (($member_id['user_id'] == $row['user_id'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_delc']) or $member_id['user_group'] == '1' or $user_group[$member_id['user_group']]['del_allc']) ) {
		$db->query( "DELETE FROM " . PREFIX . "_{$allowed_areas[$area]['comments_table']} where id = '$id'" );
		
		// обновление количества комментариев у юзера 
		if( $is_reg ) {
			$db->query( "UPDATE " . USERPREFIX . "_users set comm_num=comm_num-1 where name ='$author'" );
		}
		
		// обновление количества комментариев в новостях 
		$db->query( "UPDATE " . PREFIX . "_{$allowed_areas[$area]['counter_table']} SET comm_num=comm_num-1 where id='$post_id'" );
		
		clear_cache( 'news_' );
		
		header( "Location: {$_SESSION['referrer']}" );
		die();
	
	} else
		msgbox( $lang['comm_err_2'], $lang['comm_err_4'] );
} else
	msgbox( $lang['comm_err_2'], $lang['comm_err_5'] );

?>