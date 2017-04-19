<?php

@error_reporting( E_ALL ^ E_NOTICE );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );
@ini_set( 'error_reporting', E_ALL ^ E_NOTICE );

if ( !count($_POST) ) die("Hacking attempt!");

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '..' );
define( 'ENGINE_DIR', dirname( __FILE__ ) );

require ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/preview.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/templates.class.php';

check_xss();

//################# Определение групп пользователей
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

if ($config['allow_registration'] == "yes") {
	
	include_once ENGINE_DIR . '/modules/sitelogin.php';
}

if (!$is_logged) $member_id['user_group'] = 5;

if ( !$user_group[$member_id['user_group']]['allow_html'] ) {

	$config['allow_site_wysiwyg'] = "no";
	$_POST['short_story'] = strip_tags ($_POST['short_story']);
	$_POST['full_story'] = strip_tags ($_POST['full_story']);

}

$tpl = new dle_template( );
$tpl->allow_php_include = false;
$tpl->dir = ROOT_DIR . '/templates/' . $config['skin'];

@header( "Cache-Control: no-cache, must-revalidate, max-age=0" );
@header( "Expires: 0" );
@header( "Content-type: text/html; charset=" . $config['charset'] );

$tpl->load_template( 'preview.css' );

echo <<<HTML
<html><title>Предварительный просмотр</title>
<style type="text/css">
{$tpl->copy_template}
</style> 
<body>
<script type="text/javascript" src="{$config['http_home_url']}engine/classes/highslide/highslide.js"></script>
<script type="text/javascript">    
    hs.graphicsDir = '{$config['http_home_url']}engine/classes/highslide/graphics/';
    hs.outlineType = 'rounded-white';
    hs.numberOfImagesToPreload = 0;
    hs.showCredits = false;
    hs.loadingText = '{$lang['ajax_info']}';
    hs.fullExpandTitle = '{$lang['thumb_expandtitle']}';
    hs.restoreTitle = '{$lang['thumb_restore']}';
    hs.focusTitle = '{$lang['thumb_focustitle']}';
    hs.loadingTitle = '{$lang['thumb_cancel']}';
</script>
HTML;

$tpl->clear();

echo <<<HTML
<script language="javascript" type="text/javascript">
<!--
function ShowBild(sPicURL) {
	window.open('{$config['http_home_url']}engine/modules/imagepreview.php?image='+sPicURL, '', 'resizable=1,HEIGHT=200,WIDTH=200, scrollbars=yes');
};

function ShowOrHide(d1) {
	  if (d1 != '') DoDiv(d1);
};

function DoDiv(id) {
	  var item = null;
	  if (document.getElementById) {
		item = document.getElementById(id);
	  } else if (document.all){
		item = document.all[id];
	  } else if (document.layers){
		item = document.layers[id];
	  }
	  if (!item) {
	  }
	  else if (item.style) {
		if (item.style.display == "none"){ item.style.display = ""; }
		else {item.style.display = "none"; }
	  }else{ item.visibility = "show"; }
};
//-->
</script>
HTML;

//####################################################################################################################
//                    Определение категорий и их параметры
//####################################################################################################################
$result_cat = $db->query( "SELECT * FROM " . PREFIX . "_category ORDER BY posi ASC" );

while ( $row = $db->get_row( $result_cat ) ) {
	$cat[$row['id']] = $row['name'];
	$cat_icon[$row['id']] = $row['icon'];
	$cat_alt_name[$row['id']] = $row['alt_name'];
	$cat_parentid[$row['id']] = $row['parentid'];

}
$db->free( $result_cat );

include_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( Array (), Array (), 1, 1 );

if( $config['allow_site_wysiwyg'] == "yes" ) {
	$title = stripslashes( $parse->process( $_POST['title'] ) );
	$parse->allow_code = false;
	$full_story = $parse->process( $_POST['full_story'] );
	$short_story = $parse->process( $_POST['short_story'] );
	
	$full_story = $parse->BB_Parse( $full_story );
	$short_story = $parse->BB_Parse( $short_story );

} else {
	$full_story = $parse->process( $_POST['full_story'] );
	$short_story = $parse->process( $_POST['short_story'] );
	$title = stripslashes( $parse->process( $_POST['title'] ) );
	$full_story = $parse->BB_Parse( $full_story, false );
	$short_story = $parse->BB_Parse( $short_story, false );
}

if( is_array( $_REQUEST['catlist'] ) ) $catlist = $_REQUEST['catlist'];
else $catlist = array ();

if( ! count( $catlist ) ) {
	$my_cat = "---";
	$my_cat_link = "---";
} else {
	
	$my_cat = array ();
	$my_cat_link = array ();
	
	foreach ( $catlist as $element ) {
		if( $element ) {
			$my_cat[] = $cat[$element];
			$my_cat_link[] = "<a href=\"#\">{$cat[$element]}</a>";
		}
	}
	$my_cat = stripslashes( implode( ', ', $my_cat ) );
	$my_cat_link = stripslashes( implode( ', ', $my_cat_link ) );
}

$dle_module = "main";
$tpl->load_template( 'shortstory.tpl' );
 
if ( $parse->not_allowed_text ) $tpl->copy_template = $lang['news_err_39'];

$tpl->set( '{title}', $title );
$tpl->set( '{views}', 0 );
$date = time();
$tpl->set( '{date}', langdate( $config['timestamp_active'], $date ) );
$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$date}')", $tpl->copy_template );
$tpl->set( '[link]', "<a href=#>" );
$tpl->set( '[/link]', "</a>" );
$tpl->set( '{comments-num}', 0 );
$tpl->set( '[full-link]', "<a href=#>" );
$tpl->set( '[/full-link]', "</a>" );
$tpl->set( '[com-link]', "<a href=#>" );
$tpl->set( '[/com-link]', "</a>" );
$tpl->set( '[day-news]', "<a href=#>");
$tpl->set( '[/day-news]', "</a>");
$tpl->set( '{rating}', "" );
$tpl->set( '{author}', "--" );
$tpl->set( '{approve}', "" );
$tpl->set( '{category}', $my_cat );
$tpl->set( '{favorites}', '' );
$tpl->set( '{link-category}', $my_cat_link );
$tpl->set( '{edit-date}', "" );
$tpl->set( '{editor}', "" );
$tpl->set( '{edit-reason}', "" );
$tpl->set_block( "'\\[edit-date\\](.*?)\\[/edit-date\\]'si", "" );
$tpl->set_block( "'\\[edit-reason\\](.*?)\\[/edit-reason\\]'si", "" );
if( $cat_icon[$category[0]] != "" ) {
	$tpl->set( '{category-icon}', $cat_icon[$category[0]] );
} else {
	$tpl->set( '{category-icon}', "{THEME}/dleimages/no_icon.gif" );
}

$tpl->set_block( "'\\[tags\\](.*?)\\[/tags\\]'si", "" );
$tpl->set( '{tags}', "" );

if ( $_POST['news_fixed'] ) {

	$tpl->set( '[fixed]', "" );
	$tpl->set( '[/fixed]', "" );
	$tpl->set_block( "'\\[not-fixed\\](.*?)\\[/not-fixed\\]'si", "" );

} else {

	$tpl->set( '[not-fixed]', "" );
	$tpl->set( '[/not-fixed]', "" );
	$tpl->set_block( "'\\[fixed\\](.*?)\\[/fixed\\]'si", "" );
}

$tpl->set( '[mail]', "" );
$tpl->set( '[/mail]', "" );
$tpl->set( '{news-id}', "ID Unknown" );
$tpl->set( '{php-self}', $PHP_SELF );

$tpl->copy_template = preg_replace( "#\\[category=(.+?)\\](.*?)\\[/category\\]#is", "\\2", $tpl->copy_template );

$tpl->set_block( "'\\[edit\\].*?\\[/edit\\]'si", "" );
$tpl->set_block( "'{banner_(.*?)}'si", "" );

$xfieldsaction = "templatereplacepreview";
$xfieldsinput = $tpl->copy_template;
include (ENGINE_DIR . '/inc/xfields.php');
$tpl->copy_template = $xfieldsoutput;

$tpl->set( '{short-story}', stripslashes( $short_story ) );
$tpl->set( '{full-story}', stripslashes( $full_story ) );

$tpl->copy_template = "<fieldset style=\"border-style:solid; border-width:1; border-color:black;\"><legend> <span style=\"font-size: 10px; font-family: Verdana\">Краткое содержание:</span> </legend>" . $tpl->copy_template . "</fieldset>";
$tpl->compile( 'shortstory' );
$tpl->result['shortstory'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['shortstory']);
$tpl->result['shortstory'] = str_replace ( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['shortstory'] );

echo $tpl->result['shortstory'];

$dle_module = "showfull";
$tpl->load_template( 'fullstory.tpl' );

if ( $parse->not_allowed_text ) $tpl->copy_template = $lang['news_err_39'];

if( strlen( $full_story ) < 13 AND strpos( $tpl->copy_template, "{short-story}" ) === false ) {
	$full_story = $short_story;
}

$tpl->set( '{title}', $title );
$tpl->set( '{views}', 0 );
$tpl->set( '{poll}', '' );
$tpl->set( '{date}', langdate( $config['timestamp_active'], $date ) );
$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$date}')", $tpl->copy_template );
$tpl->set( '[link]', "<a href=#>" );
$tpl->set( '[/link]', "</a>" );
$tpl->set( '{comments-num}', 0 );
$tpl->set( '[full-link]', "<a href=#>" );
$tpl->set( '[/full-link]', "</a>" );
$tpl->set( '[com-link]', "<a href=#>" );
$tpl->set( '[/com-link]', "</a>" );
$tpl->set( '[day-news]', "<a href=#>");
$tpl->set( '[/day-news]', "</a>");
$tpl->set( '{rating}', "" );
$tpl->set( '{author}', "--" );
$tpl->set( '{category}', $my_cat );
$tpl->set( '{link-category}', $my_cat_link );
$tpl->set( '{related-news}', "" );
if( $cat_icon[$category[0]] != "" ) {
	$tpl->set( '{category-icon}', $cat_icon[$category[0]] );
} else {
	$tpl->set( '{category-icon}', "{THEME}/dleimages/no_icon.gif" );
}
$tpl->set( '{edit-date}', "" );
$tpl->set( '{editor}', "" );
$tpl->set( '{edit-reason}', "" );
$tpl->set_block( "'\\[edit-date\\](.*?)\\[/edit-date\\]'si", "" );
$tpl->set_block( "'\\[edit-reason\\](.*?)\\[/edit-reason\\]'si", "" );
$tpl->set( '{pages}', '' );
$tpl->set( '{favorites}', '' );
$tpl->set( '[mail]', "" );
$tpl->set( '[/mail]', "" );
$tpl->set( '{news-id}', "ID Unknown" );
$tpl->set( '{php-self}', $PHP_SELF );
$tpl->set_block( "'\\[tags\\](.*?)\\[/tags\\]'si", "" );
$tpl->set( '{tags}', "" );

if ( $_POST['news_fixed'] ) {

	$tpl->set( '[fixed]', "" );
	$tpl->set( '[/fixed]', "" );
	$tpl->set_block( "'\\[not-fixed\\](.*?)\\[/not-fixed\\]'si", "" );

} else {

	$tpl->set( '[not-fixed]', "" );
	$tpl->set( '[/not-fixed]', "" );
	$tpl->set_block( "'\\[fixed\\](.*?)\\[/fixed\\]'si", "" );
}

$tpl->copy_template = preg_replace( "#\\[category=(.+?)\\](.*?)\\[/category\\]#is", "\\2", $tpl->copy_template );
$tpl->set_block( "'\\[edit\\].*?\\[/edit\\]'si", "" );

$tpl->set( '[print-link]', "<a href=#>" );
$tpl->set( '[/print-link]', "</a>" );
$tpl->set_block( "'{banner_(.*?)}'si", "" );

$xfieldsaction = "templatereplacepreview";
$xfieldsinput = $tpl->copy_template;
include (ENGINE_DIR . '/inc/xfields.php');
$tpl->copy_template = $xfieldsoutput;

$tpl->set( '{short-story}', stripslashes( $short_story ) );
$tpl->set( '{full-story}', stripslashes( $full_story ) );

$tpl->copy_template = "<fieldset style=\"border-style:solid; border-width:1; border-color:black;\"><legend> <span style=\"font-size: 10px; font-family: Verdana\">Подробное содержание:</span> </legend>" . $tpl->copy_template . "</fieldset>";
$tpl->compile( 'fullstory' );
$tpl->result['fullstory'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['fullstory']);
$tpl->result['fullstory'] = str_replace ( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['fullstory'] );

echo $tpl->result['fullstory'];

echo <<<HTML
</body></html>
HTML;

?>