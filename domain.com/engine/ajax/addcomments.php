<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

@session_start();

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/addcomments.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/templates.class.php';

$_REQUEST['skin'] = totranslit($_REQUEST['skin'], false, false);

if( ! @is_dir( ROOT_DIR . '/templates/' . $_REQUEST['skin'] ) ) {
	die( "Hacking attempt!" );
}

//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

if( $config["lang_" . $_REQUEST['skin']] ) {
	
	@include_once (ROOT_DIR . '/language/' . $config["lang_" . $_REQUEST['skin']] . '/website.lng');

} else {
	
	@include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR . '/modules/sitelogin.php';
if( ! $is_logged ) {
	$member_id['user_group'] = 5;
}

$tpl = new dle_template( );
$tpl->dir = ROOT_DIR . '/templates/' . $_REQUEST['skin'];
define( 'TEMPLATE_DIR', $tpl->dir );

$ajax_adds = true;

$_POST['name'] = convert_unicode( $_POST['name'], $config['charset']  );
$_POST['mail'] = convert_unicode( $_POST['mail'], $config['charset'] );
$_POST['comments'] = convert_unicode( $_POST['comments'], $config['charset'] );

require_once ENGINE_DIR . '/modules/addcomments.php';

if( $CN_HALT != TRUE ) {

	include_once ENGINE_DIR . '/classes/comments.class.php';
	$comments = new DLE_Comments( $db, 1, 1 );

	$comments->query = "SELECT " . PREFIX . "_comments.id, post_id, " . PREFIX . "_comments.user_id, date, autor as gast_name, " . PREFIX . "_comments.email as gast_email, text, ip, is_register, name, " . USERPREFIX . "_users.email, news_num, comm_num, user_group, reg_date, signature, foto, fullname, land, icq, xfields FROM " . PREFIX . "_comments LEFT JOIN " . USERPREFIX . "_users ON " . PREFIX . "_comments.user_id=" . USERPREFIX . "_users.user_id WHERE " . PREFIX . "_comments.post_id = '$post_id' order by id DESC";
	$comments->build_comments('comments.tpl', 'ajax' );

}

if( $_POST['editor_mode'] == "wysiwyg" ) {
	
	$clear_value = "tinyMCE.execInstanceCommand('comments', 'mceSetContent', false, '', false)";

} else {
	
	$clear_value = "form.comments.value = '';";

}

if( $CN_HALT ) {
	
	$stop = implode( '\n', $stop );
	
	$tpl->result['content'] = "<script language=\"JavaScript\" type=\"text/javascript\">\n";
	
	if( ! $where_approve ) $tpl->result['content'] .= "
	var form = document.getElementById('dle-comments-form');

	{$clear_value}

	if ( form.sec_code ) {
	   form.sec_code.value = ''; 
    }";
	
	$tpl->result['content'] .= "\n alert ('" . $stop . "');\n var timeval = new Date().getTime();\n

if ( document.getElementById('dle-captcha') ) {
	document.getElementById('dle-captcha').innerHTML = '<img src=\"' + dle_root + 'engine/modules/antibot.php?rand=' + timeval + '\" border=0><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a>';
}\n </script>";

} else {
	
	$tpl->result['content'] .= <<<HTML
<script language='JavaScript' type="text/javascript">
	var timeval = new Date().getTime();
	var post_box_top  = _get_obj_toppos( document.getElementById( 'dle-ajax-comments' ) );

			if ( post_box_top )
			{
				scroll( 0, post_box_top - 70 );
			}

	var form = document.getElementById('dle-comments-form');

	{$clear_value}

	if ( form.sec_code ) {
	   form.sec_code.value = ''; 
	   document.getElementById('dle-captcha').innerHTML = "<img src=\"" + dle_root + "engine/modules/antibot.php?rand=" + timeval + "\" border=0><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a>";
    }
</script>
HTML;

}

$tpl->result['content'] = str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $_REQUEST['skin'], $tpl->result['content'] );

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $tpl->result['content'];
?>