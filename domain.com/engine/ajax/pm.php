<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/pm.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/templates.class.php';
require_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;

$_REQUEST['skin'] = trim(totranslit($_REQUEST['skin'], false, false));

if( ! @is_dir( ROOT_DIR . '/templates/' . $_REQUEST['skin'] ) or $_REQUEST['skin'] == "" ) {
	die( "Hacking attempt!" );
}

function del_tpl($read) {
	global $tpl;
	$read = str_replace( '\"', '"', str_replace( "&amp;", "&", $read ) );
	$tpl->copy_template = $read;
}

$tpl = new dle_template( );
$tpl->dir = ROOT_DIR . '/templates/' . $_REQUEST['skin'];
define( 'TEMPLATE_DIR', $tpl->dir );

if( $config["lang_" . $_REQUEST['skin']] ) {
	@include_once (ROOT_DIR . '/language/' . $config["lang_" . $_REQUEST['skin']] . '/website.lng');
} else {
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';
}
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

$_POST['name'] = convert_unicode( $_POST['name'], $config['charset'] );
$_POST['subj'] = convert_unicode( $_POST['subj'], $config['charset'] );
$_POST['text'] = convert_unicode( $_POST['text'], $config['charset'] );

$name = $parse->process( trim( $_POST['name'] ) );
$subj = $parse->process( trim( $_POST['subj'] ) );

if( $config['allow_comments_wysiwyg'] != "yes" ) $text = $parse->BB_Parse( $parse->process( $_POST['text'] ), false );
else {
	$parse->wysiwyg = true;
	$parse->ParseFilter( Array ('div', 'a', 'span', 'p', 'br' ), Array (), 0, 1 );
	$text = $parse->BB_Parse( $parse->process( $_POST['text'] ) );
}

$tpl->load_template( 'pm.tpl' );

preg_replace( "'\\[readpm\\](.*?)\\[/readpm\\]'ies", "del_tpl('\\1')", $tpl->copy_template );

$tpl->set( '{subj}', $subj );
$tpl->set( '{text}', $text );
$tpl->set( '{author}', "--" );
$tpl->set( '[reply]', "<a href=\"#\">" );
$tpl->set( '[/reply]', "</a>" );
$tpl->set( '[del]', "<a href=\"#\">" );
$tpl->set( '[/del]', "</a>" );

$tpl->compile( 'content' );
$tpl->clear();

$tpl->result['content'] = str_replace( '{THEME}', $config['http_home_url'] . 'templates/' . $_REQUEST['skin'], $tpl->result['content'] );

@header( "Content-type: text/css; charset=" . $config['charset'] );

echo $tpl->result['content'];
?>