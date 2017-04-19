<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start ();
@ob_start ();
@ob_implicit_flush ( 0 );

define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

//сам установил этот блок для ошибок
@error_reporting ( E_ALL ^ E_NOTICE ^ E_DEPRECATED );
ini_set('log_errors', '1');
ini_set('display_errors', '0');
ini_set('error_log', ROOT_DIR .'/domain.error.log'); 
//сам установил этот блок для ошибок

//это было в движке
//@error_reporting ( E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//@ini_set ( 'display_errors', true );
//@ini_set ( 'html_errors', false );
//@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED);

define ( 'DATALIFEENGINE', true );

$member_id = FALSE;
$is_logged = FALSE;

require_once ROOT_DIR . '/engine/init.php';

if (clean_url ( $_SERVER['HTTP_HOST'] ) != clean_url ( $config['http_home_url'] )) {
	
	$replace_url = array ();
	$replace_url[0] = clean_url ( $config['http_home_url'] );
	$replace_url[1] = clean_url ( $_SERVER['HTTP_HOST'] );

} else
	$replace_url = false;
 
$tpl->load_template ( 'main.tpl' );
$tpl->set ( '{calendar}', $tpl->result['calendar'] );
$tpl->set ( '{archives}', $tpl->result['archive'] );
$tpl->set ( '{vote}', $tpl->result['vote'] );
$tpl->set ( '{lastnews}', $lastnews );
$tpl->set ( '{topnews}', $topnews );

# DleMusic Service by Flexer by odmin
include_once ENGINE_DIR . '/data/mservice.php';
require_once ENGINE_DIR . '/modules/mservice/functions.php';
if ( $mscfg['block_bt'] == 1 ) {
	include_once ENGINE_DIR . '/modules/mservice/mod_newest_tracks.php';
	$tpl->set ( '{mservice_newest}', $newest_tracks );
	include_once ENGINE_DIR . '/modules/mservice/mod_best_down24hr.php';
	$tpl->set ( '{mservice_down24hr}', $best_down24hr );
	include_once ENGINE_DIR . '/modules/mservice/mod_vip_members.php';
	$tpl->set ( '{by_odmin_vip_members}', $vip_members );
} else { 
	$tpl->set ( '{mservice_newest}', 'Данный плагин отключен' );
	$tpl->set ( '{mservice_best}', 'Данный плагин отключен' );
	$tpl->set ( '{mservice_down24hr}', 'Данный плагин отключен' );
	$tpl->set ( '{by_odmin_vip_members}', 'Данный плагин отключен' );
}

if ($do == 'music' and $act == 'view') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно mp3, слушать онлайн, скачать мп3 без регистрации' ); 
elseif ($do == 'music' and $act == 'new_mp3') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно, мп3 музыка без регистрации, свежие новинки музыки mp3' );
elseif ($do == 'music' and $act == 'mp3top') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно, мп3 музыка без регистрации, прямые ссылки, музыкальный топ mp3' );
elseif ($do == 'music' and $act == 'bestmp3_24hr') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно, мп3 музыка без регистрации, прямые ссылки, музыкальные новинки mp3' );
elseif ($do == 'music' and $act == 'bestmp3_week') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно, мп3 музыка без регистрации, прямые ссылки, музыкальные новинки mp3' );
elseif ($do == 'music' and $act == 'bestmp3_month') $tpl->set ( '{slova}', $nam_e1.' скачать бесплатно, мп3 музыка без регистрации, прямые ссылки, музыкальные новинки mp3' );
elseif ($do == 'music' and $act == 'mytracks') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно, прямые ссылки, мп3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'myfavtracks') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно, прямые ссылки, мп3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'myfavartists') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно, прямые ссылки, мп3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'artistracks') $tpl->set ( '{slova}', $nam_e1.' '.$nam_e2 );
elseif ($do == 'music' and $act == 'euro2011') $tpl->set ( '{slova}', 'Евровидение 2011 Дюссельдорф, Германия - скачать бесплатно все мп3 треки песенного конкурса, mp3 без регистрации' );
elseif ($do == 'music' and $act == 'euro2012') $tpl->set ( '{slova}', 'Евровидение 2012 Баку, Азербайджан - скачать бесплатно все мп3 треки песенного конкурса, mp3 без регистрации' );
elseif ($do == 'music' and $act == 'euro2013') $tpl->set ( '{slova}', 'Евровидение 2013 Мальмё, Швеция - скачать бесплатно все мп3 треки песенного конкурса, mp3 без регистрации' );
elseif ($do == 'music' and $act == 'euro2014') $tpl->set ( '{slova}', 'Евровидение 2014 Копенгаген, Дания - скачать бесплатно все мп3 треки песенного конкурса, mp3 без регистрации' );
elseif ($do == 'music' and $act == 'euro2015') $tpl->set ( '{slova}', 'Евровидение 2015 Вена, Австрия - скачать бесплатно все мп3 треки песенного конкурса, mp3 без регистрации' );
elseif ($do == 'music' and $act == 'download') $tpl->set ( '{slova}', 'Скачивание '.$nam_e1.' mp3 - бесплатно, без регистрации' );
elseif ($do == 'music' and $act == 'album') $tpl->set ( '{slova}', $nam_e1.', скачать бесплатно, слушать альбом онлайн' );
elseif ($do == 'music' and $act == 'albums') $tpl->set ( '{slova}', 'Все альбомы '.$nam_e1.', скачать бесплатно, mp3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'genre-mp3') $tpl->set ( '{slova}', 'Все mp3 треки '.$nam_e1.', скачать бесплатно, mp3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'genre-albums') $tpl->set ( '{slova}', 'Все альбомы '.$nam_e1.', скачать бесплатно, mp3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'myfavalbums') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно, прямые ссылки, мп3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'top_beatport2014') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно, прямые ссылки, мп3 музыка без регистрации' );
elseif ($do == 'music' and $act == 'top_rollingstone2014') $tpl->set ( '{slova}', $nam_e1.', музыка скачать бесплатно' );
elseif ($do == 'music' and $act == 'new-music-clips') $tpl->set ( '{slova}', $nam_e1.', смотреть клип онлайн, музыка скачать бесплатно' );
else $tpl->set ( '{slova}', $config['home_title_short'] );

$tpl->set ( '{ssilko}', $_SERVER['REQUEST_URI'] );
/////////////////////////к поиску
if (isset($_REQUEST['searchtype'])) $searchtype = intval( $_REQUEST['searchtype'] ); else $searchtype = 1;
if ($searchtype == 2) {
	$select1 = '';
	$select2 = '';
	$select3 = ' checked="checked"';
	$select4 = ' class="LabelSelectedLive"';
	$select5 = '';
	$select6 = '';
	$select7 = '';
	$select8 = '';
} elseif ($searchtype == 3) {
	$select1 = '';
	$select2 = '';
	$select3 = '';
	$select4 = '';
	$select5 = ' checked="checked"';
	$select6 = ' class="LabelSelectedLive"';
	$select7 = '';
	$select8 = '';
} elseif ($searchtype == 4) {
	$select1 = '';
	$select2 = '';
	$select3 = '';
	$select4 = '';
	$select5 = '';
	$select6 = '';
	$select7 = ' checked="checked"';
	$select8 = ' class="LabelSelectedLive"';
} else  {
	$select1 = ' checked="checked"';
	$select2 = ' class="LabelSelectedLive"';
	$select3 = '';
	$select4 = '';
	$select5 = '';
	$select6 = '';
	$select7 = '';
	$select8 = '';
}
$tpl->set ( '{select1}', $select1 );$tpl->set ( '{select2}', $select2 );$tpl->set ( '{select3}', $select3 );$tpl->set ( '{select4}', $select4 );$tpl->set ( '{select5}', $select5 );$tpl->set ( '{select6}', $select6 );$tpl->set ( '{select7}', $select7 );$tpl->set ( '{select8}', $select8 );

if (isset($_REQUEST['searchtext'])) {
  include_once ENGINE_DIR.'/classes/parse.class.php';
  $parse = new ParseFilter( );
  $parse->safe_mode = true;
  
  if (isset($_POST['newpoisk'])) $searchtext = $parse->remove( $parse->process( $_POST['searchtext'] ) );
  else $searchtext =  base64_decode(str_replace('_','/',str_replace('*','+',$parse->remove( $parse->process( $_REQUEST['searchtext'] ) ))));
  $tpl->set ( '{searchtext}', $searchtext);
} else $tpl->set ( '{searchtext}', '');
# end DleMusic Service by odmin

#**********************************************************
 # Статус пользователя [подтвержение активности]
 #**********************************************************
 if ($is_logged) {
 $fuser_status = '';
 $timer_activity = 10;
 $fuser_status = ((time() + ($config['date_adjust']*60)) < ($member_id['lastdate'] + ($timer_activity*60))) ? $request_online=false : $request_online=true;

if ( $is_logged AND $request_online ) $db->query("UPDATE " . USERPREFIX . "_users SET lastdate = '".time()."' WHERE user_id = '$member_id[user_id]'");
} 
 #**********************************************************
 # Статус пользователя [подтвержение активности]
 #**********************************************************
 
$tpl->set ( '{login}', $login_panel );
$tpl->set ( '{info}', "<div id='dle-info'>" . $tpl->result['info'] . "</div>" );
$tpl->set ( '{speedbar}', $tpl->result['speedbar'] );

if ($config['allow_skin_change'] == "yes") $tpl->set ( '{changeskin}', ChangeSkin ( ROOT_DIR . '/templates', $config['skin'] ) );

if (count ( $banners ) and $config['allow_banner']) {
	
	foreach ( $banners as $name => $value ) {
		$tpl->copy_template = str_replace ( "{banner_" . $name . "}", $value, $tpl->copy_template );
	}

}

$tpl->set_block ( "'{banner_(.*?)}'si", "" );

if (count ( $informers ) and $config['rss_informer']) {
	foreach ( $informers as $name => $value ) {
		$tpl->copy_template = str_replace ( "{inform_" . $name . "}", $value, $tpl->copy_template );
	}
}

if ($allow_active_news AND $config['allow_change_sort'] AND !$config['ajax'] AND $do != "userinfo") {
	
	$tpl->set ( '[sort]', "" );
	$tpl->set ( '{sort}', news_sort ( $do ) );
	$tpl->set ( '[/sort]', "" );

} else {
	
	$tpl->set_block ( "'\\[sort\\](.*?)\\[/sort\\]'si", "" );

}

if ($dle_module == "showfull" ) {

	if (is_array($cat_list) AND count($cat_list) > 1 ) $category_id = implode(",", $cat_list);

}

if (strpos ( $tpl->copy_template, "[category=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[category=(.+?)\\](.*?)\\[/category\\]#ies", "check_category('\\1', '\\2', '{$category_id}')", $tpl->copy_template );
}

if (strpos ( $tpl->copy_template, "[not-category=" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\[not-category=(.+?)\\](.*?)\\[/not-category\\]#ies", "check_category('\\1', '\\2', '{$category_id}', false)", $tpl->copy_template );
}

if (strpos ( $tpl->copy_template, "{custom" ) !== false) {
	$tpl->copy_template = preg_replace ( "#\\{custom category=['\"](.+?)['\"] template=['\"](.+?)['\"] aviable=['\"](.+?)['\"] from=['\"](.+?)['\"] limit=['\"](.+?)['\"] cache=['\"](.+?)['\"]\\}#ies", "custom_print('\\1', '\\2', '\\3', '\\4', '\\5', '\\6', '{$dle_module}')", $tpl->copy_template );
}

$config['http_home_url'] = explode ( "index.php", strtolower ( $_SERVER['PHP_SELF'] ) );
$config['http_home_url'] = reset ( $config['http_home_url'] );

if (! $user_group[$member_id['user_group']]['allow_admin']) $config['admin_path'] = "";

$ajax .= <<<HTML
<script language="javascript" type="text/javascript">
<!--
var dle_root       = '{$config['http_home_url']}';
var dle_admin      = '{$config['admin_path']}';
var dle_login_hash = '{$dle_login_hash}';
var dle_skin       = '{$config['skin']}';
var dle_wysiwyg    = '{$config['allow_comments_wysiwyg']}';
var quick_wysiwyg  = '{$config['allow_quick_wysiwyg']}';
var menu_short     = '{$lang['menu_short']}';
var menu_full      = '{$lang['menu_full']}';
var menu_profile   = '{$lang['menu_profile']}';
var menu_fnews     = '{$lang['menu_fnews']}';
var menu_fcomments = '{$lang['menu_fcomments']}';
var menu_send      = '{$lang['menu_send']}';
var menu_uedit     = '{$lang['menu_uedit']}';
var dle_req_field  = '{$lang['comm_req_f']}';
var dle_del_agree  = '{$lang['news_delcom']}';
var dle_del_news   = '{$lang['news_delnews']}';\n
HTML;

if ($user_group[$member_id['user_group']]['allow_all_edit']) {
	
	$ajax .= <<<HTML
var allow_dle_delete_news   = true;\n
HTML;

} else {
	
	$ajax .= <<<HTML
var allow_dle_delete_news   = false;\n
HTML;

}

$ajax .= <<<HTML
//-->
</script>
<script type="text/javascript" src="{$config['http_home_url']}engine/ajax/menu.js"></script>
<script type="text/javascript" src="{$config['http_home_url']}engine/ajax/dle_ajax.js"></script>
<div id="loading-layer"><img src="{$config['http_home_url']}engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>
<script type="text/javascript" src="{$config['http_home_url']}engine/ajax/js_edit.js"></script>
HTML;

if ($allow_comments_ajax AND ($config['allow_comments_wysiwyg'] == "yes" OR $config['allow_quick_wysiwyg'])) $ajax .= <<<HTML

<script type="text/javascript" src="{$config['http_home_url']}engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>

HTML;

if (strpos ( $tpl->result['content'], "hs.expand" ) !== false or strpos ( $tpl->copy_template, "hs.expand" ) !== false or $config['ajax'] or $pm_alert != "") {
	
	if ($config['thumb_dimming'] AND !$pm_alert) $dimming = "hs.dimmingOpacity = 0.60;"; else $dimming = "";

	if ($config['thumb_gallery'] AND !$pm_alert) {

	$gallery = "
	hs.align = 'center';
	hs.transitions = ['expand', 'crossfade'];
	hs.addSlideshow({
		interval: 4000,
		repeat: false,
		useControls: true,
		fixedControls: 'fit',
		overlayOptions: {
			opacity: .75,
			position: 'bottom center',
			hideOnMouseOut: true
		}
	});";

	} else {

		$gallery = "";

	}

	
	$ajax .= <<<HTML

<script type="text/javascript" src="{$config['http_home_url']}engine/classes/highslide/highslide.js"></script>
<script language="javascript" type="text/javascript">  
<!--  
	hs.graphicsDir = '{$config['http_home_url']}engine/classes/highslide/graphics/';
	hs.outlineType = 'rounded-white';
	hs.numberOfImagesToPreload = 0;
	hs.showCredits = false;
	{$dimming}
	hs.lang = {
		loadingText :     '{$lang['loading']}',
		playTitle :       '{$lang['thumb_playtitle']}',
		pauseTitle:       '{$lang['thumb_pausetitle']}',
		previousTitle :   '{$lang['thumb_previoustitle']}',
		nextTitle :       '{$lang['thumb_nexttitle']}',
		moveTitle :       '{$lang['thumb_movetitle']}',
		closeTitle :      '{$lang['thumb_closetitle']}',
		fullExpandTitle : '{$lang['thumb_expandtitle']}',
		restoreTitle :    '{$lang['thumb_restore']}',
		focusTitle :      '{$lang['thumb_focustitle']}',
		loadingTitle :    '{$lang['thumb_cancel']}'
	};
	{$gallery}
//-->
</script>

{$pm_alert}
HTML;

}

$tpl->set ( '{AJAX}', $ajax );
$tpl->set ( '{headers}', $metatags );
$tpl->set ( '{content}', "<div id='dle-content'>" . $tpl->result['content'] . "</div>" );

$tpl->compile ( 'main' );
$tpl->result['main'] = str_replace ( '{THEME}', $config['http_home_url'] . 'templates/' . $config['skin'], $tpl->result['main'] );
if ($replace_url) $tpl->result['main'] = str_replace ( $replace_url[0]."/", $replace_url[1]."/", $tpl->result['main'] );
eval (' ?' . '>' . $tpl->result['main'] . '<' . '?php ');


$tpl->global_clear ();
$db->close ();
GzipOut(1);
?>
