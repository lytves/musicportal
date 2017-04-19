<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
if( $member_id['user_group'] != 1 ) {
	msg( "error", $lang['addnews_denied'], $lang['db_denied'] );
}
require ENGINE_DIR . '/data/mservice.php';
require ENGINE_DIR . '/modules/mservice/functions.php';
require ENGINE_DIR . '/classes/parse.class.php';
$parse = new ParseFilter( );
$parse->safe_mode = true;

function OpenTable( ) {
echo <<<HTML
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
HTML;
}
function CloseTable( ) {
echo <<<HTML
    </td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
HTML;
}
function EchoTableHeader( $title, $add = FALSE, $right_a = FALSE, $skobki = FALSE ) {
if ( $add != FALSE ) $add = ' [ <a href="' . $add . '" style="color:#3367AB;">вернуться назад</a> ]';
if ( $skobki = FALSE ) {$skobki_left = '[ ';$skobki_right = ' ]';}
if ( $right_a != FALSE ) $right_a = '<td bgcolor="#EFEFEF" height="29" style="padding:0 10px; text-align:right"><div class="navigation">'.$skobki_left.$right_a.$skobki_right.'</div></td>';
echo <<<HTML
<table width="100%" border="0">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$title}{$add}</div></td>
        {$right_a}
    </tr>
</table>
<div class="unterline"></div>
HTML;
}
function showRow( $title = '', $description = '', $field = '' ) {
global $config;
echo <<<HTML
<tr>
  <td style="padding:4px" class="option"><b>{$title}</b><br /><span class="small">{$description}</span></td>
  <td width="394" align="middle">{$field}</td>
</tr>
<tr>
  <td background="{$config[http_home_url]}engine/skins/images/mline.gif" height="1" colspan="2"></td>
</tr>
HTML;
}
function dle_cache($prefix, $cache_id = false, $member_prefix = false) {
	global $config, $is_logged, $member_id;
	
	if( $config['allow_cache'] != "yes" ) return false;
	
	if( $is_logged ) $end_file = $member_id['user_group'];
	else $end_file = "0";
	
	if( ! $cache_id ) {
		
		$filename = ENGINE_DIR . '/cache/' . $prefix . '.tmp';
	
	} else {
		
		$cache_id = totranslit( $cache_id );
		
		if( $member_prefix ) $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
		else $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
	
	}
	
	return @file_get_contents( $filename );
}

function create_cache($prefix, $cache_text, $cache_id = false, $member_prefix = false) {
	global $config, $is_logged, $member_id;
	
	if( $config['allow_cache'] != "yes" ) return false;
	
	if( $is_logged ) $end_file = $member_id['user_group'];
	else $end_file = "0";
	
	if( ! $cache_id ) {
		$filename = ENGINE_DIR . '/cache/' . $prefix . '.tmp';
	} else {
		$cache_id = totranslit( $cache_id );
		
		if( $member_prefix ) $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
		else $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
	
	}
	
	$fp = fopen( $filename, 'wb+' );
	fwrite( $fp, $cache_text );
	fclose( $fp );
	
	@chmod( $filename, 0666 );

}
function makeDropDown( $options, $name, $selected ) {
$output = "<select name='$name'>";
foreach( $options as $value => $description ) {
$output .= "<option value='$value'";
if ( $selected == $value ) $output .= " selected ";
$output .= ">$description</option>"; }
$output .= "</select>";
return $output;}

switch ( $_REQUEST['act'] ) {

default :

echoheader("", "");

OpenTable();
EchoTableHeader( 'Модуль DleMusic Service', '', '<a href="'.$PHP_SELF.'?mod=mservice&act=config" title="Настройка модуля"><img src="'.$config['http_home_url'].'engine/skins/images/mservice/tools.png" border="0" alt="Настройка модуля"></a>', TRUE );

echo <<<HTML
<table width="100%" border="0">
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=tracks"><img src="{$config[http_home_url]}engine/skins/images/mservice/tracks.png" border="0" align="left"><h3 style="color:#1b9ae0;">Управление треками</h3>Управление треками музыкального архива: редактирование и удаление</a></div></td>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=albums"><img src="{$config[http_home_url]}engine/skins/images/mservice/album.jpg" border="0" align="left"><h3 style="color:#1b9ae0;">Управление альбомами исполнителей</h3>Управление альбомами исполнителей музыкального архива: редактирование и удаление</a></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=addtrack"><img src="{$config[http_home_url]}engine/skins/images/mservice/addtracks.png" border="0" align="left"><h3 style="color:#1b9ae0;">Добавить новый трек</h3>Добавление нового трека в базу данных</a></div></td>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=addalbum"><img src="{$config[http_home_url]}engine/skins/images/mservice/addalbum.jpg" border="0" align="left"><h3 style="color:#1b9ae0;">Добавить новый альбом</h3>Добавление нового альбома в базу данных</a></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=artists"><img src="{$config[http_home_url]}engine/skins/images/mservice/artists.png" border="0" align="left"><h3 style="color:#1b9ae0;">Управление исполнителями</h3>Оболожка и описание исполнителей</a></div></td>
  <td width="50%"><div class="quick"></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=addartist"><img src="{$config[http_home_url]}engine/skins/images/mservice/addartists.png" border="0" align="left"><h3 style="color:#1b9ae0;">Добавить исполнителя</h3>Добавление нового исполнителя в базу данных</a></div></td>
  <td width="50%"><div class="quick"></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=uploaders"><img src="{$config[http_home_url]}engine/skins/images/mservice/uploaders.png" border="0" align="left"><h3 style="color:#1b9ae0;">Статистика uploader'ов</h3>Просмотр статистики про uploader'ов mp3 на сайт</a></div></td>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=vauth&page=info"><img src="{$config[http_home_url]}engine/skins/images/mservice/contact.png" border="0" align="left"><h3 style="color:#1b9ae0;">Модуль авторизации vAuth</h3>VAuth - модуль авторизации и регистрации пользователей через социальные сети</a></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=downloaders"><img src="{$config[http_home_url]}engine/skins/images/mservice/downloaders.png" border="0" align="left"><h3 style="color:#1b9ae0;">Статистика скачиваний юзерами (за неделю)</h3>Просмотр статистики скачиваний треков и альбомов юзерами</a></div></td>
  <td width="50%"><div class="quick"><a href="/engine/modules/commentit/adm.php" target="_blank"><img src="{$config[http_home_url]}engine/skins/images/mservice/categories.png" border="0" align="left"><h3 style="color:#1b9ae0;">Админка скрипта комментариев</h3>Вход в админку скрипта CommentIt Ajax</a></div></td>
</tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=mservice&act=downloadersip"><img src="{$config[http_home_url]}engine/skins/images/mservice/downloadersip.png" border="0" align="left"><h3 style="color:#1b9ae0;">Статистика скачиваний по IP (за неделю)</h3>Просмотр статистики скачиваний треков и альбомов по IP-адресам</a></div></td>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=unitpay"><img src="{$config[http_home_url]}engine/skins/images/mservice/unitpay.png" border="0" align="left"><h3 style="color:#1b9ae0;">UnitPay платежи</h3>Просмотр статистики от системы электронных и мобильных платежей UnitPay</a></div></td>
</tr>
</table>
HTML;

CloseTable();

OpenTable();
EchoTableHeader( 'Общая статистика архива' );

//считаем треки, треки на модерации, демотреки и клипы
$alltracks_count =  unserialize(dle_cache( "alltracks_count" ));

if ( !$alltracks_count ) {
  $alltracks_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE approve = '0') as cnt, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE is_demo = '1') as cnt2, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE clip_online != '') as cnt3 FROM ".PREFIX."_mservice" );
	create_cache( "alltracks_count", serialize($alltracks_count) );
	$db->free( );
}

if ( $alltracks_count['cnt'] > 0 ) {
  $no_approve = ' [ <a href="' . $PHP_SELF . '?mod=mservice&act=tracks&approve=0" style="color:red; font-weight:bold;">редактирование ожидающих модерации треков</a> ]';
  $track_noapprove_count = $alltracks_count['cnt'];
} else {
  $no_approve = 'нет треков для модерации';
  $track_noapprove_count = '';
}

if ( $alltracks_count['cnt2'] > 0 ) $demotracks = '<font color="#9ACD32">'.$alltracks_count['cnt2'].'</font> [ <a href="'.$PHP_SELF.'?mod=mservice&act=tracks&is_demo=1">смотреть демотреки</a> ]';
else $demotracks = 'нет демозаписей';
//считаем треки, треки на модерации, демотреки и клипы = конец

//считаем альбомы, альбомы на модерации, неполные альбомы и альбомы без жанра
$allalbums_count =  unserialize(dle_cache( "allalbums_count" ));
if ( !$allalbums_count ) {
  $allalbums_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE approve = '0') as cnt, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE is_notfullalbum != '0') as cnt2, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE genre = '0' OR genre = '' OR genre > '14') as cnt3 FROM ".PREFIX."_mservice_albums" );
  create_cache( "allalbums_count", serialize($allalbums_count) );
	$db->free( );
}

if ($allalbums_count['cnt2'] > 0) $notfullalbums = '<font color="#9ACD32">'.$allalbums_count['cnt2'].'</font> [ <a href="'.$PHP_SELF.'?mod=mservice&act=albums&notfullalbum=1">смотреть неполные альбомы</a> ]';
else $notfullalbums = 'нет неполных альбомов';

if ($allalbums_count['cnt3'] > 0) $notgenrealbums = '<font color="#9ACD32">'.$allalbums_count['cnt3'].'</font>';
else $notgenrealbums = 'нет альбомов без жанра';

if ( $allalbums_count['cnt'] > 0 ) {
  $alb_no_approve = ' [ <a href="' . $PHP_SELF . '?mod=mservice&act=albums&approve=0" style="color:red; font-weight:bold;">редактирование ожидающих модерации альбомов</a> ]';
  $alb_noapprove_count = $allalbums_count['cnt'];
} else {
  $alb_no_approve = 'нет альбомов для модерации';
  $alb_noapprove_count = '';
}
//считаем альбомы, альбомы на модерации, неполные альбомы и альбомы без жанра = конец

if ( $_REQUEST['subact'] == 'clear' ) {
  clear_cache( );
  echo '<div class="option" style="display:block; padding:5px; background-color:#f3fbff; border:1px solid #CCC;">Кеш был успешно очищен!</div><br />';
}

if ( $_REQUEST['subact'] == 'tempclear' ) {

function removedir ($directory){
$dir = opendir($directory);
while($file = readdir($dir))
{if ( is_file ($directory."/".$file))
{unlink ($directory."/".$file);}
else if ( is_dir ($directory."/".$file) && ($file != ".") && ($file != ".."))
{removedir ($directory."/".$file);}}
closedir ($dir);
rmdir ($directory);
return TRUE;}

removedir ('/home2/mp3base/temp/');
mkdir('/home2/mp3base/temp/', 0777, true);
chmod('/home2/mp3base/temp/', 0777);

echo '<div class="option" style="display:block;padding:5px;background-color:#f3fbff;border:1px solid #CCC">Папки temp успешно очищены!</div><br />';
}

$file = @fopen("/var/www/ncze83gw/data/home_mp3base_size.txt","r");
if(!$file) $mp3base_unix = "Ошибка открытия файла home_mp3base_size.txt";
else {
	$mp3base_unix = fgets($file);
	fclose($file );
}

$file = @fopen("/var/www/ncze83gw/data/home2_mp3base_size.txt","r");
if(!$file) $mp3base_unix2 = "Ошибка открытия файла home2_mp3base_size.txt";
else {
	$mp3base_unix2 = fgets($file);
	fclose($file );
}

$cashe_dir = ENGINE_DIR . '/cache/';
$temp_massmp3_dir = '/home2/mp3base/temp/';
function show_size($f,$format=true) 
{ 
        if ($format) { 
                $size=show_size($f,false); 
                if($size<=1024) return $size.' bytes'; 
                else if($size<=1024*1024) return round($size/(1024),2).' Kb'; 
                else if($size<=1024*1024*1024) return round($size/(1024*1024),2).' Mb'; 
                else if($size<=1024*1024*1024*1024) return round($size/(1024*1024*1024),2).' Gb'; 
                else if($size<=1024*1024*1024*1024*1024) return round($size/(1024*1024*1024*1024),2).' Tb'; //:))) 
                else return round($size/(1024*1024*1024*1024*1024),2).' Pb'; // ;-) 
        } else { 
                if(is_file($f)) return filesize($f); 
                $size=0; 
                $dh=@opendir($f); 
                while(($file=@readdir($dh))!==false) 
                { 
                        if($file=='.' || $file=='..') continue; 
                        if(is_file($f.'/'.$file)) $size+=@filesize($f.'/'.$file); 
                        else $size+=@show_size($f.'/'.$file,false); 
                } 
                @closedir($dh); 
                return $size+@filesize($f); // +filesize($f) for *nix directories 
        } 
}
$cache_size = @show_size($cashe_dir,$format=true);
$temp_massmp3_size = @show_size($temp_massmp3_dir,$format=true);
if ($temp_massmp3_size == '4 Kb') $temp_massmp3_size = '0 Kb';


//считаем Ошибки + логи для удаления
$allerrors_logs_count =  unserialize(dle_cache( "allerrors_logs_count" ));
if ( !$allerrors_logs_count ) {
  $allerrors_logs_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_mservice_search) as cnt, (SELECT COUNT(*) FROM ".PREFIX."_mservice_search WHERE time < ( NOW( ) - INTERVAL 1 MONTH )) as cnt2, (SELECT COUNT(*) FROM ".PREFIX."_mservice_downloads) as cnt3, (SELECT COUNT(*) FROM ".PREFIX."_mservice_downloads WHERE time < ( NOW( ) - INTERVAL 1 MONTH )) as cnt4, (SELECT COUNT(*) FROM ".PREFIX."_mservice_downloads_albums) as cnt5, (SELECT COUNT(*) FROM ".PREFIX."_mservice_downloads_albums WHERE time < ( NOW( ) - INTERVAL 1 MONTH )) as cnt6 FROM ".PREFIX."_mservice WHERE lenght = '00:00:00' OR lenght = '' OR bitrate = '0' OR bitrate = '' OR title LIKE '%.agr%' OR size = '' OR size = '0'" );
  create_cache( "allerrors_logs_count", serialize($allerrors_logs_count) );
	$db->free( );
}

if ($allerrors_logs_count['count']  > 0) $track_failed  = '<font color="#cc0000"><b>'.$allerrors_logs_count['count'].'</b></font>';
else $track_failed = $allerrors_logs_count['count'];

$mservice_search_count = $allerrors_logs_count['cnt'];
$mservice_search_todell = $allerrors_logs_count['cnt2'];

$mservice_downloads_count = $allerrors_logs_count['cnt3'];
$mservice_downloads_todell = $allerrors_logs_count['cnt4'];

$mservice_downloads_albums_count = $allerrors_logs_count['cnt5'];
$mservice_downloads_albums_todell = $allerrors_logs_count['cnt6'];
//считаем Ошибки + логи для удаления = конец

$constprefix = PREFIX;

echo <<<HTML
<script type="text/javascript">
function showMysqlSelect( pref ) {
  $('#mysqlselect').html("<span style='background-color:#FFFFCC;'>SELECT * FROM "+pref+"_mservice WHERE lenght = '00:00:00' OR lenght = '' OR bitrate = '' OR bitrate = '0' OR title LIKE '%.agr%' OR size = '' OR size = '0'</span>");
}
function showMysqlSelectSearch( pref ) {
  $('#mysqlselectsearch').html("<span style='background-color:#FFFFCC;'>DELETE FROM "+pref+"_mservice_search WHERE time < ( NOW( ) - INTERVAL 1 MONTH )</span>");
}
function showMysqlSelectDownloads( pref ) {
  $('#mysqlselectdownloads').html("<span style='background-color:#FFFFCC;'>DELETE FROM "+pref+"_mservice_downloads WHERE time < ( NOW( ) - INTERVAL 1 MONTH )</span>");
}
function showMysqlSelectDownloadsAlbums( pref ) {
  $('#mysqlselectdownloadsalbums').html("<span style='background-color:#FFFFCC;'>DELETE FROM "+pref+"_mservice_downloads_albums WHERE time < ( NOW( ) - INTERVAL 1 MONTH )</span>");
}
</script>

<table border="0" width="100%">
<tr><td style="padding-left:2px;padding-top:3px;"><b>Статистика треков:</b> <span style="color:#cc0000;">(все значения кешируются!)</span></td><td>&nbsp;</td></tr>
<tr><td style="padding-left:2px; width:50%;">Общее количество треков:</td><td>{$alltracks_count['count']} [ <a href="{$PHP_SELF}?mod=mservice&act=tracks">управление треками</a> ]</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Треков ожидающих проверки:</td><td>{$track_noapprove_count}{$no_approve}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Треков-демозаписей:</td><td>{$demotracks}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Треков с онлайн-клипами:</td><td><font color="#F49804">{$alltracks_count['cnt3']}</font></td></tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;"><b>Статистика альбомов:</b> <span style="color:#cc0000;">(все значения кешируются!)</span></td><td>&nbsp;</td></tr>
<tr><td style="padding-left:2px; width:50%;">Общее количество альбомов:</td><td>{$allalbums_count['count']} [ <a href="{$PHP_SELF}?mod=mservice&act=albums">управление альбомами</a> ]</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Альбомов ожидающих проверки:</td><td>{$alb_noapprove_count}{$alb_no_approve}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Неполных альбомов:</td><td>{$notfullalbums}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Альбомов без жанра:</td><td>{$notgenrealbums}</td></tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;"><b>Ошибки + логи для удаления:</b> <span style="color:#cc0000;">(все значения кешируются!)</span></td><td>&nbsp;</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Треков с ошибочными размером, длительностью или битрейтом,<br /> названием с '.agr':</td><td>{$track_failed}<div id="mysqlselect"><a href="#" onclick="showMysqlSelect( '{$constprefix}' ); return false;" style="color:#0000ff; text-decoration:none;">Найти эти треки</a></div></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;" colspan="2"><hr align="center" color="#efefef" width="90%" /></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Строк в таблице PREFIX_mservice_search + строк для удаления (старее 1-ой недели):</td><td>всего - {$mservice_search_count}, старых - {$mservice_search_todell}<div id="mysqlselectsearch"><a href="#" onclick="showMysqlSelectSearch( '{$constprefix}' ); return false;" style="color:#0000ff; text-decoration:none;">Очистить старые</a></div></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;" colspan="2"><hr align="center" color="#efefef" width="90%" /></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Строк в таблице PREFIX_mservice_downloads + строк для удаления (старее 1-ой недели):</td><td>всего - {$mservice_downloads_count}, старых - {$mservice_downloads_todell}<div id="mysqlselectdownloads"><a href="#" onclick="showMysqlSelectDownloads( '{$constprefix}' ); return false;" style="color:#0000ff; text-decoration:none;">Очистить старые</a></div></td></tr></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;" colspan="2"><hr align="center" color="#efefef" width="90%" /></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Строк в таблице PREFIX_mservice_downloads_albums + строк для удаления (старее 1-ой недели):</td><td>всего - {$mservice_downloads_albums_count}, старых - {$mservice_downloads_albums_todell}<div id="mysqlselectdownloadsalbums"><a href="#" onclick="showMysqlSelectDownloadsAlbums( '{$constprefix}' ); return false;" style="color:#0000ff; text-decoration:none;">Очистить старые</a></div></td></tr></td></tr>
<tr><td colspan="2"><div style="background:url(/templates/mp3/images/lin_min.gif) repeat-x; width:100%; height:10px; margin-top:5px;"></div></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;"><b>Кеш + Папки:</b></td><td>&nbsp;</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Общий размер кеша сайта (.../cache/) :</td><td><font color="#9ACD32"><b>{$cache_size}</b></font></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Размер временной папки массовой загрузки mp3 (/home2/mp3base/temp/) :</td><td><font color="#cc0000"><b>{$temp_massmp3_size}</b></font></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Общий размер mp3 базы (/home/mp3base/) - считается кроном UNIX:</td><td><font color="#d56114"><b>{$mp3base_unix}</b></font></td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Общий размер mp3 базы (/home2/mp3base/) - считается кроном UNIX:</td><td><font color="#d56114"><b>{$mp3base_unix2}</b></font></td></tr>
</table>
<br />
<input onclick="document.location='?mod=mservice&act=clear'" class="edit" style="width:180px;" type="button" value="Пересчёт всей статистики!">
<input onclick="document.location='?mod=mservice&subact=clear'" class="edit" style="width:150px;" type="button" value="Очистить кеш">
<input onclick="document.location='?mod=mservice&subact=tempclear'" class="edit" style="width:150px;" type="button" value="Очистить папку temp">
HTML;

CloseTable();

OpenTable();
EchoTableHeader( 'Автопроверка модуля' );

if ( $mscfg['online'] == 1 ) $stat = '<font color="green">Включен</font>'; else $stat = '<font color="red"><b>Выключен</b></font>';
$maxsize = formatsize( $mscfg['maxfilesize'] * 1024 );
$filetypes = @str_replace( ',', ', ', $mscfg['filetypes'] );

if ( $mscfg['mfp_type'] == 1 ) $player = 'DataLife Engine - Media Player';
elseif ( $mscfg['mfp_type'] == 2 ) $player = 'DleMusic Service - Classic Player';
elseif ( $mscfg['mfp_type'] == 3 ) $player = '<font color="#0099FF">DleMusic Service - Modern Player</font>';
$phpmax = str_replace( array ('M', 'm' ), '', @ini_get( 'upload_max_filesize' ) );

echo <<<HTML
<table>
<tr><td style="padding-left:2px;padding-right:150px;">Версия модуля:</td><td><b style="color:#FF3300">{$mscfg[version]}</b> ( от 20 сентября 2009 )</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Текущее состояние:</td><td>{$stat}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Макс. размер загружаемого трека:</td><td>{$maxsize}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">PHP может принять файл до:</td><td>{$phpmax} Mb</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Разрешённые типы файлов:</td><td>{$filetypes}</td></tr>
<tr><td style="padding-left:2px;padding-top:3px;">Тип плеера для воспр. треков:</td><td>{$player}</td></tr>
</table>
HTML;

CloseTable();
echofooter();

break;

// добавление исполнителя в базу
case 'addartist' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Добавление нового исполнителя', $PHP_SELF . '?mod=mservice' );

echo <<<HTML
<form action="" method="post"  enctype="multipart/form-data">
<input type="hidden" name="act" value="doaddartist" />
<input type="hidden" name="genre" id="genreval" value="" />

<table width="100%" border="0">
<tr><td style="padding-left:2px; padding-top:5px;">Исполнитель: <font color="#EF5151">*</font></td><td style="padding-top:5px;"><input type="text" id="add-artist" name="artist" class="edit" size="80" /></td></tr>
<tr><td height="10"></td></tr>
<tr style="background-color:#C5C5C7;"><td width="170" style="padding-left:2px;">Жанр:<br /><a id="moregenres_a" style="cursor:pointer; color:1E90FF;">(Подсказка)</a></td><td align="middle">
<ul id="genre" class="genre">
 <li><label><input id="CheckBox1" type="radio" name="cur" value="1" class="radiobuttonchange" />Pop</label></li>
 <li><label><input id="CheckBox2" type="radio" name="cur" value="2" class="radiobuttonchange" />Club</label></li>
 <li><label><input id="CheckBox3" type="radio" name="cur" value="3" class="radiobuttonchange" />Rap</label></li>
 <li><label><input id="CheckBox4" type="radio" name="cur" value="4" class="radiobuttonchange" />Rock</label></li>
 <li><label><input id="CheckBox5" type="radio" name="cur" value="5" class="radiobuttonchange" />Шансон</label></li>
 <li><label><input id="CheckBox6" type="radio" name="cur" value="6" class="radiobuttonchange" />OST</label></li>
 <li><label><input id="CheckBox7" type="radio" name="cur" value="7" class="radiobuttonchange" />Metal</label></li>
 <li><label><input id="CheckBox8" type="radio" name="cur" value="8" class="radiobuttonchange" />Country</label></li>
 <li><label><input id="CheckBox9" type="radio" name="cur" value="9" class="radiobuttonchange" />Classical</label></li><br />
 <li><label><input id="CheckBox10" type="radio" name="cur" value="10" class="radiobuttonchange" />Punk</label></li>
 <li><label><input id="CheckBox11" type="radio" name="cur" value="11" class="radiobuttonchange" />Soul/R&B</label></li>
 <li><label><input id="CheckBox12" type="radio" name="cur" value="12" class="radiobuttonchange" />Jazz</label></li>
 <li><label><input id="CheckBox13" type="radio" name="cur" value="13" class="radiobuttonchange" />Electronic</label></li>
 <li><label><input id="CheckBox14" type="radio" name="cur" value="14" class="radiobuttonchange" />Indie</label></li>
</ul>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<div id="loading-layer"><img src="/engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>

<script>
	
$('a#moregenres_a').click(function(){
  $("#moregenres").css('display','block');
  return false;
});

$('.genre input').change(function() {
var genreid = $('input[name=cur]:checked', '#genre li label').val();
$("#genreval").val(genreid);
});

$(document).ready(function(){  
   $(".radiobuttonchange").change(function(){  
        if($(this).is(":checked")){  
            if($(this).is("#CheckBox1")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox2")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox3")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox4")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox5")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox6")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox7")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox8")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox9")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox10")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox11")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox12")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox13")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox14")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }                                 
		}
    });  
}); 
</script>
</td></tr>
<tr><td></td><td><div id="moregenres"><span>Alternative</span> = Indie, <span>Indie Rock</span> = Indie, <span>Indie Pop</span> = Indie, <span>New Age</span> = Electronic, <span>New Wave</span> = Rock, <span>Reggae</span> = Soul/R&b</div>
</td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Описание исполнителя (опционально):</td><td style="padding-top:20px;"><textarea cols="80" style="height:100px; resize:vertical;" name="descriptions" class="f_input"></textarea></td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Обложка исполнителя:</td><td style="padding-top:20px;"><input type="file" name="image" size="35" accept="image/*" />
<br /><li>максимальный размер загружаемой картинки до 3Mb</li>
<li>разрешённые типы файлов: jpg, png, jpe, jpeg</li></td></tr>
<tr><td></td><td><br /><input type="submit" value="  Добавить исполнителя  " class="buttons" /></td></tr>
</table>
</form>
HTML;

CloseTable( );
echofooter( );

break;

case 'doaddartist' :

$artist = $parse->process( $parse->remove( parseLinksAdmin( $_POST['artist'] ) ) );

$roW_temp = $db->super_query( "SELECT COUNT(artist) FROM ".PREFIX."_mservice_artists WHERE artist = '$artist'" );
$description = trim( $parse->process( $parse->remove( $_POST['descriptions'] ) ) );

$genre = intval($_POST['genre']);
if ($genre == '' OR $genre < 0 OR $genre > 14) $genre = 0;

if ( $roW_temp['cnt'] > 0 ) $stop .= '<li>Такой исполнитель <strong>'.$artist.'</strong> уже есть в базе! Редактируйте его запись.</li>';

if ( $artist == '' ) $stop .= '<li>Вы не ввели имя исполнителя</li>';

if ( $stop == '' ) {

  $image = $_FILES['image']['tmp_name'];
  $image_name = basename($_FILES['image']['name']);
  $image_size = $_FILES['image']['size'];
  $img_name_arr = explode( ".", $image_name );
  $type = end( $img_name_arr );
  
	if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) and ! $stop ) {
		
			if( $image_size < 3000000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR.'/classes/thumb.class.php';
					
					$time = time( );
          $image_name_md5 = md5($time + mt_rand( 0, 100 )).".".$type;

					$res = @move_uploaded_file( $image, ROOT_DIR."/uploads/artists/".$image_name_md5 );
					
					if( $res ) {
						
						@chmod( ROOT_DIR."/uploads/artists/".$image_name_md5, 0666 );
						$thumb = new thumbnail( ROOT_DIR."/uploads/artists/".$image_name_md5 );
						
						//150 - размер картинки в пикселях, означает 150х150
						if( $thumb->size_auto( 700 ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR."/uploads/artists/artist_".$image_name_md5 );
						} else {
							@rename( ROOT_DIR."/uploads/artists/".$image_name_md5, ROOT_DIR."/uploads/artists/artist_".$image_name_md5 );
						}
						
						@chmod( ROOT_DIR."/uploads/artists/artist_".$image_name_md5, 0666 );
						$img_cover = "artist_".$image_name_md5;
					} else
						$stop .= '<li>Произошла ошибка при загрузке картинки!</li>';
				} else
					$stop .= '<li>К загрузке разрешены только файлы изображений (jpg, png, jpe, jpeg)<!/li>';
			} else
				$stop .= '<li>Максимальный размер загружаемой картинки не должен превышать 3Mb!</li>';
		
		@unlink( ROOT_DIR."/uploads/artists/".$image_name_md5 );
	}
}								

if ( $stop == '' ) {
	$transartist = totranslit( $artist );
  $strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
	$time = time();
	
  $db->query( "INSERT INTO ".PREFIX."_mservice_artists ( artist, description, image_cover, genre, transartist, strip_base64_artist ) VALUES ( '$artist', '$description', '$img_cover', '$genre', '$transartist', '$strip_base64_artist' )" );
  
  if( $img_cover != '' ) {
    $row = $db->super_query( "SELECT artid FROM ".PREFIX."_mservice_artists WHERE image_cover = '$img_cover'" );
    $artid = $row['artid'];
    $img_cover_new = 'artist_'.$artid.".".$type;
		@rename( ROOT_DIR."/uploads/artists/".$img_cover, ROOT_DIR."/uploads/artists/".$img_cover_new );
    $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover = '$img_cover_new' WHERE artid = '$artid'" );
  }
	clear_cache( );
	msg("info", "Добавление исполнителя", "Исполнитель был успешно добавлен в базу данных!<br /><br /><a href=$PHP_SELF?mod=mservice&act=artists>Вернуться назад в меню 'Управление исполнителями'</a><br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад в Главное меню</a>");
} else {
  msg("info", "Ошибка", "<br /><span style='color:#cc0000;'>Во время добавления исполнителя:</span><br /><br />{$stop}<br /><a href=$PHP_SELF?mod=mservice&act=addartist>Вернуться назад</a><br /><br />");
}

break;

//   управление   исполнителями
case 'artists' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Управление исполнителями', $PHP_SELF . '?mod=mservice', '<a href="'.$PHP_SELF.'?mod=mservice&act=addartist" style="color:#1E90FF">Добавить нового исполнителя</a>' );

if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

  $db->query( "SELECT artid, artist, description, image_cover150, genre, transartist, (SELECT COUNT(*) FROM ".PREFIX."_mservice_artists) AS cnt FROM ".PREFIX."_mservice_artists ORDER BY artid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );

if ( $db->num_rows( ) == 0 ) {
	echo 'Ещё нет исполнителей в базе((';
	CloseTable( );
	echofooter( );
	break;
}

  echo '<table width="100%">';
  $i = 1;
  while ( $row = $db->get_row( ) ) {
    if (!$count) $count = $row['cnt'];

    if ( $row['genre'] == '1') $alb_genre = 'Pop';
    elseif ( $row['genre'] == '2') $alb_genre = 'Club';
    elseif ( $row['genre'] == '3') $alb_genre = 'Rap';
    elseif ( $row['genre'] == '4') $alb_genre = 'Rock';
    elseif ( $row['genre'] == '5') $alb_genre = 'Шансон';
    elseif ( $row['genre'] == '6') $alb_genre = 'OST';
    elseif ( $row['genre'] == '7') $alb_genre = 'Metal';
    elseif ( $row['genre'] == '8') $alb_genre = 'Country';
    elseif ( $row['genre'] == '9') $alb_genre = 'Classical';
    elseif ( $row['genre'] == '10') $alb_genre = 'Punk';
    elseif ( $row['genre'] == '11') $alb_genre = 'Soul/R&B';
    elseif ( $row['genre'] == '12') $alb_genre = 'Jazz';
    elseif ( $row['genre'] == '13') $alb_genre = 'Electornic';
    elseif ( $row['genre'] == '14') $alb_genre = 'Indie';  
    else $alb_genre = '<span style="color:#c00;">-</span>';
  
    if ( $row['image_cover150'] ) $img_cover150 = $row['image_cover150'];
    else $img_cover150 = 'artist_0.jpg';
	
    echo '<td width="50%" style="padding-left:20px;" valign="top"><a href="'.$PHP_SELF.'?mod=mservice&act=artistedit&artid='.$row['artid'].'" title="Редактировать исполнителя '.$row['artist'].'"><img src="/uploads/artists/'.$img_cover150.'" style="width:150px; height:150px; margin-right:10px; border:1px solid #E2EDF2 !important; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px; float:left;" alt="Редактировать исполнителя '.$row['artist'].'" title="Редактировать исполнителя '.$row['artist'].'" /><b>'.$row['artist'].'</b><br /><br /></a><div style="height:10px;"></div><span class="genre'.$row['genre'].'">'.$alb_genre.'</span></td>';
    $i++;
    if (($i % 2) == '1') echo '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
  }

echo '</tr></table>';

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=mservice&act=artists&page={$t2}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=artists&page='.$npage.'">Назад</a> ';
  else $prev_page = '<span>Назад</span> ';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=artists&page='.$npage.'">Далее</a>';
  else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}
CloseTable( );
echofooter( );

break;
/////////////////////       Редактирование исполнителя 
case 'artistedit' :

$artid = intval( $_REQUEST['artid'] );
$row = $db->super_query( "SELECT artist, description, image_cover, image_cover150, genre, transartist FROM ".PREFIX."_mservice_artists WHERE artid = '$artid' LIMIT 1" );

echoheader( '', '' );
OpenTable( );

if ($row) $link_artist = '<a href="/music/artistracks-'.totranslit($row['artist']).'.html" style="color:#1E90FF" target="_blank">Смотреть исполнителя на сайте</a>';
else $link_artist = '';

EchoTableHeader( 'Редактирование исполнителя', $PHP_SELF . '?mod=mservice&act=artists', $link_artist );

if ( !$row ) {
	echo <<<HTML
	<table width="100%" border="0">
    <tr>
        <td height="100" align="center">Нет исполнителя с таким artid. <a class=main href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a></td>
    </tr>
</table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

$text = $row['description'];

  if ( $row['genre'] == '1') {$select1 = ' checked="checked"'; $labelselect1 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '2') {$select2 = ' checked="checked"'; $labelselect2 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '3') {$select3 = ' checked="checked"'; $labelselect3 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '4') {$select4 = ' checked="checked"'; $labelselect4 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '5') {$select5 = ' checked="checked"'; $labelselect5 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '6') {$select6 = ' checked="checked"'; $labelselect6 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '7') {$select7 = ' checked="checked"'; $labelselect7 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '8') {$select8 = ' checked="checked"'; $labelselect8 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '9') {$select9 = ' checked="checked"'; $labelselect9 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '10') {$select10 = ' checked="checked"'; $labelselect10 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '11') {$select11 = ' checked="checked"'; $labelselect11 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '12') {$select12 = ' checked="checked"'; $labelselect12 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '13') {$select13 = ' checked="checked"'; $labelselect13 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '14') {$select14 = ' checked="checked"'; $labelselect14 = ' class="LabelSelected"';}
  else $notgenre = 'Жанр не выбран! Пожалуйста выберите подходящий жанр для исполнителя!';
  
if ( $row['image_cover'] ) {
  $coversize = formatsize(filesize(ROOT_DIR.'/uploads/artists/'.$row['image_cover']));
  $viewcover = <<<HTML
<script type="text/javascript" src="engine/skins/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="engine/skins/jquery.Jcrop.min.css" type="text/css" />
<script language="Javascript">
			function showCoords(c) {
				jQuery('#w2').val(c.w);
				jQuery('#h2').val(c.h);
			};
			function checkCoords() {
				if (parseInt($('#w').val())) return true;
				alert('Please select a region for cropping image_cover150!');
				return false;
			};
      function updateCoords(c) {
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};
    jQuery(function($) {
        $('#target').Jcrop({
            onChange: showCoords,
            onSelect: updateCoords,
            aspectRatio: 1,
            bgOpacity: .5
        });
    });

</script>

  <div id="image_cover_jcrop">
    <div style="float:left; margin-top:10px;">
			<label>W <input type="text" size="4" id="w2" name="w2" /></label>
			<label>H <input type="text" size="4" id="h2" name="h2" /></label>
		</div>
		<form action="" method="post" onsubmit="return checkCoords();" style="float:left; margin:10px 0 0 30px;">
      <input type="hidden" name="act" value="massact" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
			<input type="hidden" name="image_cover" value="{$row[image_cover]}" />
      <input type='hidden' name='artid' value='{$artid}' />
      <input type="hidden" name='action' value='13' />
			<input type="submit" value="  Crop Image  " class="buttons" />
		</form>
	</div>
	
<div id="spamcover" style="margin:10px; float:left; text-align:center; color:#cc0000;"><img id="target" src="/uploads/artists/{$row[image_cover]}" style="padding: 0 0 5px 20px;" align="middle" /><br />
!!! <a href="#" onclick="delSpamCoverAlbum('{$row[image_cover]}','{$artid}'); return false;">Удаление обложки исполнителя</a> !!!<br /><span style="padding-left:70px;">{$coversize}</span></div>


HTML;
}
else $viewcover = <<<HTML
<table border="0" style="float:right;">
<tr><td width="120" align="center"><div id="uploadButton" class="button"><font>Загрузить </font><img id="load" src="/engine/skins/images/loadstop.gif"/></div></td>
<td height="50" width="350"><div id="files_types"><li>максимальный размер загружаемой картинки до 3Mb</li><li>разрешённые типы файлов: jpg, png, jpe, jpeg</li></div></td>
<tr><td colspan="2"><span id="response"></span></td></tr>
</table>
HTML;

if ( $row['image_cover150'] ) {
  $coversize150 = formatsize(filesize(ROOT_DIR.'/uploads/artists/'.$row['image_cover150']));
  $viewcover150 = <<<HTML
<div id="spamcover150" style="float:left; color:#cc0000;"><img src="/uploads/artists/{$row[image_cover150]}" style="padding: 0 0 5px 20px;" align="middle" /><br />
!!! <a href="#" onclick="delSpamCoverAlbum150('{$row[image_cover150]}','{$artid}'); return false;">Удаление обложки 150x150 исполнителя</a> !!!<br /><span style="padding-left:70px;">{$coversize150}</span></div>
HTML;
}
else $viewcover150 = <<<HTML
<table border="0" style="float:right;">
<tr><td width="120" align="center"><div id="uploadButton150" class="button"><font>Загрузить </font><img id="load150" src="/engine/skins/images/loadstop.gif"/></div></td>
<td height="50" width="350"><div id="files_types150"><li>максимальный размер загружаемой картинки до 3Mb</li><li>разрешённые типы файлов: jpg, png, jpe, jpeg</li></div></td>
<tr><td colspan="2"><span id="response150"></span></td></tr>
</table>
HTML;

echo <<<HTML
<div id="artid_number" style="display:none;">{$artid}</div>
<div id="loading-layer"><img src="/engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>

<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script type="text/javascript" src="engine/skins/ajaxupload.js"></script>
<script type="text/javascript" src="engine/skins/script_uploadimgartist.js"></script>
<script type="text/javascript" src="engine/skins/script_uploadimgartist150.js"></script>

<script type="text/javascript">
function delSpamCoverAlbum(image_cover,artid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '46' );
ajax.setVar( "image_cover", image_cover );
ajax.setVar( "artid", artid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'spamcover';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('spamcover').style.display = 'block' );
$("#image_cover_jcrop").css('display','none');
}
function delSpamCoverAlbum150(image_cover150,artid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '49' );
ajax.setVar( "image_cover150", image_cover150 );
ajax.setVar( "artid", artid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'spamcover150';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('spamcover150').style.display = 'block' );
}
function validate_form ( ) {
	valid = true;
  str = document.edit_form.comments.value;
  
  if ((-1 < str.indexOf('iframe')) || (-1 < str.indexOf('object')))
  {
    alert ( "В поле 'Описание исполнителя' нельзя использовать iframe или object!!!" );
    valid = false;
  }
  return valid;
}
</script>

<table border="0" width="1050"><tr><td width="60%" style="text-align:center; color:9ACD32; font:bold 16px Verdana; padding:5px;">{$row[artist]}</td><td width="40%" style="background:url('/engine/skins/images/blue_artid.jpg') no-repeat; text-align:left; color:#1E90FF; font:bold 16px Verdana; padding-left:100px;">{$artid}</td></tr></table>
<hr color="#999898" />
<table border="0" height="30">
<tr><td align="right">Удаление исполнителя ======></td>
<td width="200" align="right">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input name='artid' value='{$artid}' type='checkbox' />
<input type="hidden" name='action' value='9' />
<input type="submit" value="  Удалить исполнителя  " class="buttons" />
</form>
</td></tr></table>
<hr color="#999898" />

<table border="1" width="1050">
<tr><td width="250" style="padding:5px;">Основная обложка:</td><td style="padding:5px;">{$viewcover}</td></tr>
<tr><td width="250" style="padding:5px;">Обложка 150x150:<br />(можно загружать отдельно или сделать "Crop Image" от основной обложки)</td><td style="padding:5px;">{$viewcover150}</td></tr>
</table>

<form action="" method="post" enctype="multipart/form-data" name ="edit_form" onsubmit="return validate_form ( );">
<input type="hidden" name="act" value="doartistedit" />
<input type="hidden" name="artid" value="{$artid}" />
<div class="unterline"></div>

<table border="1" width="1050">
<tr><td width="250" style="padding:5px;">Исполнитель:</td><td style="padding:5px;"><input type="text" name="artist" class="edit" value="{$row[artist]}" size="80" /></td></tr>
<tr><td style="padding:5px;">Жанр:<br /><a id="moregenres_a" style="cursor:pointer; color:1E90FF;">(Подсказка)</a></td><td style="padding:5px;"><div id="is_genre">{$notgenre}</div>
<ul id="genre" class="genre">
 <li><label{$labelselect1}><input id="CheckBox1" type="radio" name="cur" value="1" class="radiobuttonchange"{$select1} />Pop</label></li>
 <li><label{$labelselect2}><input id="CheckBox2" type="radio" name="cur" value="2" class="radiobuttonchange"{$select2} />Club</label></li>
 <li><label{$labelselect3}><input id="CheckBox3" type="radio" name="cur" value="3" class="radiobuttonchange"{$select3} />Rap</label></li>
 <li><label{$labelselect4}><input id="CheckBox4" type="radio" name="cur" value="4" class="radiobuttonchange"{$select4} />Rock</label></li>
 <li><label{$labelselect5}><input id="CheckBox5" type="radio" name="cur" value="5" class="radiobuttonchange"{$select5} />Шансон</label></li>
 <li><label{$labelselect6}><input id="CheckBox6" type="radio" name="cur" value="6" class="radiobuttonchange"{$select6} />OST</label></li>
 <li><label{$labelselect7}><input id="CheckBox7" type="radio" name="cur" value="7" class="radiobuttonchange"{$select7} />Metal</label></li>
 <li><label{$labelselect8}><input id="CheckBox8" type="radio" name="cur" value="8" class="radiobuttonchange"{$select8} />Country</label></li>
 <li><label{$labelselect9}><input id="CheckBox9" type="radio" name="cur" value="9" class="radiobuttonchange"{$select9} />Classical</label></li><br />
 <li><label{$labelselect10}><input id="CheckBox10" type="radio" name="cur" value="10" class="radiobuttonchange"{$select10} />Punk</label></li>
 <li><label{$labelselect11}><input id="CheckBox11" type="radio" name="cur" value="11" class="radiobuttonchange"{$select11} />Soul/R&B</label></li>
 <li><label{$labelselect12}><input id="CheckBox12" type="radio" name="cur" value="12" class="radiobuttonchange"{$select12} />Jazz</label></li>
 <li><label{$labelselect13}><input id="CheckBox13" type="radio" name="cur" value="13" class="radiobuttonchange"{$select13} />Electronic</label></li>
 <li><label{$labelselect14}><input id="CheckBox14" type="radio" name="cur" value="14" class="radiobuttonchange"{$select14} />Indie</label></li>
</ul>
 
<script>
$('a#moregenres_a').click(function(){
  $("#moregenres").css('display','block');
  return false;
});

$('.genre input').change(function() {
var genreid = $('input[name=cur]:checked', '#genre li label').val();

var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '47' );
ajax.setVar( "artid", {$artid} );
ajax.setVar( "genreid", genreid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'is_genre';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('is_genre').style.display = 'block' );
});

$(document).ready(function(){  
   $(".radiobuttonchange").change(function(){  
        if($(this).is(":checked")){  
            if($(this).is("#CheckBox1")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox2")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox3")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox4")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox5")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox6")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox7")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox8")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox9")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox10")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox11")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox12")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox13")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox14")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
		}
    });  
}); 
</script>

</td></tr>
<tr><td></td><td><div id="moregenres"><span>Alternative</span> = Indie, <span>Indie Rock</span> = Indie, <span>Indie Pop</span> = Indie, <span>New Age</span> = Electronic, <span>New Wave</span> = Rock, <span>Reggae</span> = Soul/R&b</div>
</td></tr>
<tr><td style="padding:5px;"><font color="#1DDF04">Описание исполнителя:</font></td><td style="padding:5px;"><table><tr><td><textarea cols="80" style="height:100px; resize:vertical;" name="comments" class="f_input">{$row[description]}</textarea></td></tr></table></td></tr></table>
<br /><input type="submit" value="  Отредактировать исполнителя  " class="buttons" />
</form>
HTML;

CloseTable( );
echofooter( );

break;

/////////////////////       Редактирование исполнителя,  сами  действия 
case 'doartistedit' :

$artid = intval( $_POST['artid'] );
$artist = $parse->process( $parse->remove( parseLinksAdmin( $_POST['artist'] ) ) );

$descr = trim( $parse->remove( $parse->process( $_POST['comments'] ) ) );

$transartist = totranslit( $artist );
$strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));

if ( $artist == '' ) $stop .= '<li>Вы не ввели исполнителя</li>';

$row = $db->super_query( "SELECT COUNT(1) as count FROM ".PREFIX."_mservice_artists WHERE artist = '$artist' AND artid != '$artid '" );

if ( $row['count'] > 0 ) $stop .= '<li>Такой исполнитель уже есть в базе!</li>';

if ( $stop == '' ) {

  $db->query( "UPDATE ".PREFIX."_mservice_artists SET artist = '$artist', description = '$descr', transartist = '$transartist', strip_base64_artist ='$strip_base64_artist' WHERE artid = '$artid' LIMIT 1" );


  msg("info", "Редактирование исполнителя", "Исполнитель был успешно отредактирован!<br /><br /><a href=$PHP_SELF?mod=mservice&act=artists>Вернуться назад в меню 'Управление исполнителями'</a>");

} else msg("info", "Редактирование исполнителя", "<br />Были обнаружены следующие ошибки:<br /><br />{$stop}<br /><br /><a href='$PHP_SELF?mod=mservice&act=artistedit&artid={$artid}'>Вернуться назад на страницу редактирования исполнителя</a><br /><br />");

$db->free( );

break;

case 'addtrack' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Фотографии для ссылок на все треки исполнителей', $PHP_SELF . '?mod=mservice' );

include_once ENGINE_DIR . '/editor/comments.php';
$time = langdate( 'd.m.Y H:i', time( ) );
$file_types = @str_replace( ",", ", ", $mscfg['filetypes'] );
$file_size = formatsize( $mscfg['maxfilesize'] * 1024 );

echo <<<HTML
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>

<form action="" method="post"  enctype="multipart/form-data">
<input type="hidden" name="act" value="doaddtrack" />

<table>
<tr><td style="padding-left:2px;padding-right:170px;">Исполнитель:</td><td><input type="text" name="artist" class="edit" size="40" /></td></tr>
<tr><td style="padding-left:2px;padding-top:5px;">Название трека:</td><td style="padding-top:5px;"><input type="text" name="title" class="edit" size="40" /></td></tr>
<tr><td style="padding-left:2px;padding-top:5px;">Дата добавления:</td><td style="padding-top:5px;"><input type="text" value="{$time}" name="time" class="edit" id="time" size="30" />

<img src="engine/skins/images/img.gif" align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "time",
        ifFormat       :    "%d.%m.%Y %H:%M",
        button         :    "f_trigger_c",
        align          :    "Br",
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script>

</td></tr>

<tr><td style="padding-left:2px;padding-top:5px;">Добавил трек (пишем имя):</td><td style="padding-top:5px;"><input type="text" name="uploader" class="edit" value="{$member_id[name]}" size="40" /></td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Описание трека:</td><td style="padding-top:20px;">{$wysiwyg}</td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Аудио трек:</td><td style="padding-top:20px;"><input type="file" name="file" size="35" /><br /><li>Разрешённые типы: {$file_types}; размер не более: {$file_size}</li></td></tr>
<tr><td></td><td><br /><input type="submit" value="  Добавить трек  " class="buttons" /></td></tr>
</table>
</form>
HTML;

CloseTable( );
echofooter( );

break;

case 'doaddtrack' :

$artist = trim( $parse->process( $parse->remove( $_POST['artist'] ) ) );
$title = trim( $parse->process( $parse->remove( $_POST['title'] ) ) );
$time = $parse->process( $parse->remove( $_POST['time'] ) );
$uploader = $parse->process( $parse->remove( $_POST['uploader'] ) );
$user = $db->super_query( "SELECT user_id FROM ".USERPREFIX."_users WHERE name = '$uploader'" );
$descr = trim( $parse->BB_Parse( $parse->process( $_POST['comments'] ) ) );

if ( $artist == '' ) $stop .= '<li>Вы не ввели исполнителя трека</li>';
if ( $title == '' ) $stop .= '<li>Вы не ввели название трека</li>';
if ( $uploader == '' ) $stop .= '<li>Вы не ввели логин пользователя добавившего трек</li>';
if ( $_FILES['file']['size'] == FALSE ) $stop .= '<li>Вы не выбрали файл трека для загрузки на сервер</li>';

if ( $time != '' ) {

$time = @str_replace( ' ', '.', $time );
$time = @str_replace( ':', '.', $time );
list( $d, $m, $y, $h, $i ) = explode( '.', $time );
$time = mktime( $h, $i, 0, $m, $d, $y );

} else $time = time( );

$allowed_files = explode( ',', strtolower( $mscfg['filetypes'] ) );
$tfile = end( explode( ".", totranslit( $_FILES['file']['name'] ) ) );
$file_allow = FALSE;
for ( $f = 0; $f < count( $allowed_files ); $f ++ ) {
  if ( $tfile == $allowed_files[$f] ) $file_allow = TRUE;
}
if ( $file_allow == FALSE ) $stop .= '<li>Вы не можете загружать файлы такого типа</li>';
if ( $_FILES['file']['size'] > $mscfg['maxfilesize'] * 1024 ) $stop .= '<li>Выбранный Вами файл слишком большой</li>';

if ( $stop == '' ) {

	$for_md5 = $time + mt_rand( 0, 1000 );
	$for_md5 .= $_FILES['file']['name'];
	$filename = md5( $for_md5 ) . '.' . $tfile;
	$hdd = 2;
	$subpapka = nameSubDir( $time, $hdd );
	if (move_uploaded_file( $_FILES['file']['tmp_name'], '/home2/mp3base/'.$subpapka.'/'.$filename )) {

		include_once ENGINE_DIR . '/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home2/mp3base/'.$subpapka.'/'.$filename );
        
		$beats = intval( $track['bitrate'] / 1000 );
		$lenght = $track['playtime_string'];
		
    if ( (empty( $lenght )) || ( empty( $beats ) )) {
      $stop .= 'Файл имеет неправильный размер и/или не является аудиофайлом mp3';
      if ( is_file('/home2/mp3base/'.$subpapka.'/'.$filename))  unlink('/home2/mp3base/'.$subpapka.'/'.$filename);
      msg("info", "Добавление трека", "<font color='#cc0000'>Файл имеет неправильный размер и/или не является аудиофайлом mp3!</font><br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад</a>");
    } else {
	
//////////////////////////////////////////////////////////////////////* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = $title.' (domain.com)';
					$TagData['artist'][] = $artist;
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = $descr;
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = $ThisFileInfo['id3v2']['APIC'][0]['data'];
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
					// write tags 
					$tagwriter->WriteTags();
					unset($getID3, $tagwriter, $ThisFileInfo, $TagData);
///////////////////////////////////////////////////////////////////////////* Конец Исправление ID3 тегов файлу */

  $size = @filesize('/home2/mp3base/'.$subpapka.'/'.$filename);
  $crc32b = @hash_file('crc32b', '/home2/mp3base/'.$subpapka.'/'.$filename);
	$transartist = totranslit( $artist );
	$strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
	$strip_base64_title = str_replace('/','_',str_replace('+','*',base64_encode($title)));
			
	$db->query( "INSERT INTO " . PREFIX . "_mservice ( time, title, rating, approve, vote_num, artist, download, description, filename, hdd, size, crc32, uploader, view_count, transartist, lenght, bitrate, strip_base64_artist, strip_base64_title ) VALUES ( '$time', '$title', '0', '1', '0', '$artist', '0', '$descr', '$filename', '$hdd', '$size', '$crc32b', '$user[user_id]', '0', '$transartist', '$lenght', '$beats', '$strip_base64_artist', '$strip_base64_title' )" );

	clear_cache( );

	msg("info", "Добавление трека", "Трек был успешно добавлен в базу данных!<br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад</a>");
	}
	}
	else msg("info", "Ошибка", "<br />Во время добавления трека были обнаружены следующие ошибки:<br /><br />Невозможно сохранить файл на диске! <a href=$PHP_SELF?mod=mservice&act=addtrack>Вернуться назад</a><br /><br />");

} else {

msg("info", "Ошибка", "<br />Во время добавления трека были обнаружены следующие ошибки:<br /><br />{$stop}<br /><a href=$PHP_SELF?mod=mservice&act=addtrack>Вернуться назад</a><br /><br />");

}

break;
// __________________________________________________     ВСЕ треки музыкального архива
case 'tracks' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Управление треками', $PHP_SELF . '?mod=mservice' );

echo <<<HTML
<script language='JavaScript' type="text/javascript">
<!--
function ckeck_uncheck_all() {
    var frm = document.mservicemass;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}
-->
</script>
<table width="100%" border="0">
<tr><td style="padding-left:10px; width:50%;">Введите номер трека для редактирования&nbsp;&nbsp;&nbsp;===========================></td>
<td style="padding-top:5px; text-align:center;">
<form action="{$PHP_SELF}" method="get" enctype="multipart/form-data">
<input type="hidden" name="mod" value="mservice" />
<input type="hidden" name="act" value="trackedit" />
<input type="text" value="" name="id" class="edit" id="id" size="10" />
</td><td>
<input type="submit" value="  Редактировать трек  " class="buttons" />
</form>
</td></tr>
<tr><td style="padding-top:5px;" colspan="6"><div class="unterline"></div></td></tr>
</table>

<table width="100%" border="0" class="table">
<form action="" method="post" name="mservicemass">
<input type="hidden" name="act" value="massact" />
<thead>
<tr class="head"><th style="padding-left:10px;">Заголовок</th><th width="5%" align="center">из альбома</th><th width="10%" align="center">Загрузил</th><th width="9%" align="center">Просмотров</th><th width="9%" align="center">Скачиваний</th><th width="10%" align="center">Модерация</th><th width="4%" align="center"><input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()"></th></tr>
</thead>
<tbody>
HTML;

if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

$is_demo = intval($_REQUEST['is_demo']);
if ( $is_demo == 1 ) {
  $db->query( "SELECT m.mid, m.time, m.title, m.artist, m.view_count, m.approve, m.is_demo, m.album, m.uploader, m.download, m.clip_online, (SELECT COUNT(*) FROM " . PREFIX . "_mservice WHERE is_demo = '1') AS cnt, u.name, u.user_group FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id WHERE is_demo = '1' ORDER BY m.mid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );
} else {

  $approve = $_REQUEST['approve'];

  if ( $approve == '' ) $approve_sql = '';
  elseif ( $approve == 0 ) {
    $approve_sql = " WHERE approve = '0'";
    $approve_a = ' target="_blank"';
  }
  elseif ( $approve == 1 ) $approve_sql = " WHERE approve = '1'";

  $db->query( "SELECT m.mid, m.time, m.title, m.artist, m.view_count, m.approve, m.is_demo, m.album, m.uploader, m.download, m.clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice{$approve_sql}) AS cnt, u.name, u.user_group FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id{$approve_sql} ORDER BY m.mid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );
}
if ( $db->num_rows( ) == 0 ) {
	echo <<<HTML
<tr><td style="padding-left:10px;" colspan="6">Нет треков</td></tr></table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

while ( $row = $db->get_row( ) ) {
	if (!$count) $count = $row['cnt'];

	if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\"red\" weight=\"bold\"><b>".$row['name']."</font>";
	else $userinfocss = $row['name'];
	$date = langdate( 'd.m.Y', $row['time'] );
	if ( $row['approve'] == 1 ) $approves = 'Да'; else $approves = '<font color="red"><b>Нет</b></font>';
	if ( $row['is_demo'] == 1 ) $is_demo = ' <span style="color:#9ACD32;">(демозапись)</span>';
  else  $is_demo = '';
  
  if ( $row['album'] > 0 ) $in_album = '<img src="engine/skins/images/led_green.gif" align="absmiddle" />'; else $in_album = '';
  
	echo "<tr><td style='padding-left:3px;'>{$date} :: {$row[mid]} :: <a href='{$PHP_SELF}?mod=mservice&act=trackedit&id={$row[mid]}' title='Редактировать трек'{$approve_a}>{$row[artist]} - {$row[title]}</a>".$is_demo.clipOnline( $row['clip_online'] )."</td><td align='center'>{$in_album}</td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=usertracks&uploaderid={$row[uploader]}' title='Смотреть треки добавленные {$row[name]}'>{$userinfocss}</a></td><td align='center'>{$row[view_count]}</td><td align='center'>{$row[download]}</td><td align='center'>{$approves}</td><td align='center'>
<input name='selected_track[]' value='{$row[mid]}' type='checkbox' /></td></tr>";
}

echo <<<HTML
</tbody>
</table>
<br /><div align="right" style="padding-right:20px;">

<select name="action">

<option value="">--- Действие ---</option>
<option value="1">Отправить на модерацию</option>
<option value="2">Снять трек с модерации</option>
<option value="3">Очистить счётчик просмотров</option>
<option value="4">Очистить рейтинг трека</option>
<option value="5">Очистить счётчик скачиваний</option>
<option value="6">Установить треку uploader=10</option>
<option value="7">Удалить трек из архива</option>

</select> <input type="submit" value="  Выполнить  " class="edit" /></div>
</form>
HTML;

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $approve == '' ) $this_approve = '';
elseif ( $approve == 0 ) $this_approve = '&approve=0';
elseif ( $approve == 1 ) $this_approve = '&approve=1';
else $this_approve = '';

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=mservice&act=tracks&page={$t2}{$this_approve}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=tracks&page=' . $npage . $this_approve . '">Назад</a> ';
  else $prev_page = '<span>Назад</span> ';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=tracks&page=' . $npage . $this_approve . '">Далее</a>';
  else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}
// Конец

CloseTable( );
echofooter( );

break;

/////////////////////       Редактирование трека 
case 'trackedit' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Редактирование трека', $PHP_SELF . '?mod=mservice&act=tracks' );

$THEME = $config['http_home_url'].'templates/'. $config['skin'];

$id = intval( $_REQUEST['id'] );
$row = $db->super_query( "SELECT m.time, m.title, m.rating, m.approve, m.is_demo, m.vote_num, m.album, m.artist, m.download, m.description, m.filename, m.hdd, m.size, m.uploader, m.view_count, m.lenght, m.bitrate, m.clip_online, m.clip_online_time, u.name, a.artist as album_artist, a.album as album_title, a.genre, a.is_collection FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id LEFT JOIN ".PREFIX."_mservice_albums a ON a.aid = m.album WHERE mid = '$id' LIMIT 1" );

if ( !$row ) {
	echo <<<HTML
	<table width="100%" border="0">
    <tr>
        <td height="100" align="center">Нет трека с таким mid. <a class=main href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a></td>
    </tr>
</table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

$hdd = intval( $row['hdd'] );
if ( ($hdd !== 2) ) $hdd = '';

$date = langdate( 'd.m.Y H:i', $row['time'] );
$clip_online_time = langdate( 'd.m.Y H:i', $row['clip_online_time'] );

if (($row['clip_online'] != '') ) {
  //это мой костыль, дальше есть решение через регулярку preg_match
  //if (strpos($row['clip_online'], 'youtube')) $clip_online_image = '<img class="clip_online_image" src="http://img.youtube.com/vi/'.substr($row[clip_online],(strpos($row[clip_online], 'youtube')+18),11).'/mqdefault.jpg" />';
  if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches)) $clip_online_image = '<img class="clip_online_image" src="http://img.youtube.com/vi/'.$matches[1].'/mqdefault.jpg" />';
  else $clip_online_image = '<img class="clip_online_image" src="'.$THEME.'/images/music_clip.jpg" />';
} else $clip_online_image = '';

$text = $row['description'];
$filesize = formatsize($row['size']);
$filetype = strtolower( end( explode( '.', $row['filename'] ) ) );

if ($row['approve'] == '0') {
  $approve_message = '<span style="color:red; font-weight:bold;">Трек не опубликован на сайте и находится НА МОДЕРАЦИИ!</span> <a href="#" title="Снять трек с модерации!" onclick="removeModer( '.$id.' ); return false;">Снять трек с модерации!</a>';
  $approve_message2 = '';
} else {
  $approve_message = '';
  $approve_message2 = '<div id="track-moder2" style="left:200px; position:relative; top:-15px;"><a href="#" title="Отправить трек на модерацию!" onclick="addModer( '.$id.' ); return false;">Отправить трек на модерацию!</a></div>';
}

if ($row['album'] != '0') {
  if ($row['is_collection'] != '0') $alb_is_collection = '<span style="color:#F49804;">альбоме-сборнике</span>';
  else $alb_is_collection = 'обычном альбоме';
  
  if ( $row['genre'] == '1') $alb_genre = '#Pop';
  elseif ( $row['genre'] == '2') $alb_genre = '#Club';
  elseif ( $row['genre'] == '3') $alb_genre = '#Rap';
  elseif ( $row['genre'] == '4') $alb_genre = '#Rock';
  elseif ( $row['genre'] == '5') $alb_genre = '#Шансон';
  elseif ( $row['genre'] == '6') $alb_genre = '#OST';
  elseif ( $row['genre'] == '7') $alb_genre = '#Metal';
  elseif ( $row['genre'] == '8') $alb_genre = '#Country';
  elseif ( $row['genre'] == '9') $alb_genre = '#Classical';
  elseif ( $row['genre'] == '10') $alb_genre = '#Punk';
  elseif ( $row['genre'] == '11') $alb_genre = '#Soul/R&B';
  elseif ( $row['genre'] == '12') $alb_genre = '#Jazz';
  elseif ( $row['genre'] == '13') $alb_genre = '#Electornic';
  elseif ( $row['genre'] == '14') $alb_genre = '#Indie';  
  else $alb_genre = '<span style="color:#c00;">-</span>';
  
  $to_alb_collection = <<<HTML
<table border="0"><tr><td width="510"></td><td width="300">Добавить в альбом-сборник (aid) ======></td>
<td><form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input type="hidden" name='action' value='12' />
<input type="hidden" name='mid' value='{$id}' />
<input type="text" value="" name="aid" class="edit" size="10" />
<input type="submit" value="  Добавить  " class="buttons" />
</form>
</td></tr></table>
HTML;

  $album_message = <<<HTML
<td width="500" colspan="3" align="center" style="padding:5px;">Трек находится в {$alb_is_collection} <a href='{$PHP_SELF}?mod=mservice&act=albumedit&aid={$row[album]}' title='Редактировать альбом' target="_blank">{$row[album_artist]} - <b>{$row[album_title]}</b> (<span class="genre{$row[genre]}">{$alb_genre}</span>)</a></td>
HTML;
} else {
  $album_message = <<<HTML
<td width="320" align="right" style="padding:5px;">Добавить в альбом (aid) ======></td>
<td width="180"><form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input type="hidden" name='action' value='10' />
<input type="hidden" name='mid' value='{$id}' />
<input type="text" value="" name="aid" class="edit" size="10" />
<input type="submit" value="  Добавить  " class="buttons" />
</form></td>
HTML;
$to_alb_collection = '';
}

$file_types = @str_replace( ",", ", ", $mscfg['filetypes'] );
$file_size = formatsize( $mscfg['maxfilesize'] * 1024 );
$subpapka = nameSubDir( $row['time'], $row['hdd'] );

if( $row['rating'] ) $rating_track = round( ($row['rating'] / $row['vote_num']), 1 );
else $rating_track = 0;

if( $row['is_demo'] == 1) $is_demo = ' checked';
else $is_demo = '';

$player = BuildPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], $THEME );

if ( $mscfg['allow_get_apic'] == 1 ) {
  include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
  $getID3 = new getID3;
  $track = $getID3->analyze( '/home'.$hdd .'/mp3base/'.$subpapka.'/'.$row['filename'] );
  
  if (isset($track['id3v2']['APIC'][0]['data'])) {
    $viewcover_size = show_size_cover(strlen($track['id3v2']['APIC'][0]['data']));
    $viewcover_base64 = base64_encode($track['id3v2']['APIC'][0]['data']);
    $viewcover = <<<HTML
<div id="spamcover" style="float:right; color:#cc0000;"><img src="data:{$track['id3v2']['APIC'][0]['mime']};base64,{$viewcover_base64}" style="width:150px; height:150px; padding: 0 0 5px 20px;" align="middle"> !!!<a href="#" onclick="delSpamCover('{$subpapka}', '{$row['filename']}', '{$row['hdd']}'); return false;">Удаление спамной обложки</a> !!!<br /><span style="padding-left:70px;">{$viewcover_size}</span></div>
HTML;
  }
  else $viewcover = '';
}
echo <<<HTML
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script type="text/javascript" src="engine/skins/jquery.ezpz_tooltip.min.js"></script>

<div id="loading-layer"><img src="/engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>

<script type="text/javascript">
$(document).ready(function(){
  $("#example-target-1").ezpz_tooltip({
  contentPosition: 'aboveStatic',
  stayOnContent: true,
  offset: 0
});
});

function removeModer( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '37' );
ajax.setVar( "mid", mid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'track-moder';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('track-moder').style.display = 'block' );

$('#approvedit').html('<input type="hidden" value="1" name="approvedit" />');
}

function addModer( mid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '38' );
ajax.setVar( "mid", mid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'track-moder';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('track-moder').style.display = 'block' );

$('#approvedit').html('<input type="hidden" value="0" name="approvedit" />');
$('#track-moder2').html('').css('display', 'none');
}

function delSpamCover(subpapka, filename, hdd) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '2' );
ajax.setVar( "subpapka", subpapka );
ajax.setVar( "filename", filename );
ajax.setVar( "hdd", hdd );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'spamcover';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('spamcover').style.display = 'block' );
}
function validate_form ( ) {
	valid = true;
  str = document.edit_form.comments.value;
  str2 = document.edit_form.clip_online.value;
  
  if ((-1 < str.indexOf('iframe')) || (-1 < str.indexOf('object'))) {
    alert ( "В поле 'Описание трека' нельзя использовать iframe или object!!!" );
    valid = false;
  }
  if ( document.edit_form.clip_online.value != "" ) {
    if ((-1 == str2.indexOf('iframe')) && (-1 == str2.indexOf('object'))) {
      alert ( "В поле 'Онлайн клип' обязательно должен присутствовать iframe или object!!!" );
      valid = false;
    }
  }

return valid;
}
function chFavicon(iconHref){
icon=$(":[rel='shortcut icon']");
cache=icon.clone();
cache.attr("href", iconHref);
icon.replaceWith(cache);
}

</script>

<table border="0" width="984" style="">
<tr><td align="center" colspan="4"><div id="track-moder" style="text-align:center;">{$approve_message}</div></td></tr>
<tr><td height="25" width="200" style="padding:5px;">Удаление трека ======></td>
<td width="200" style="padding:5px;" align="left">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input name='mid' value='{$id}' type='checkbox' />
<input type="hidden" name='action' value='8' />
<input type="submit" value="  Удалить трек  " class="buttons" />
</form></td>
{$album_message}
</tr></table>{$to_alb_collection}

<form action="" method="post" enctype="multipart/form-data" name ="edit_form" onsubmit="return validate_form ( );">
<input type="hidden" name="act" value="dotrackedit" />
<input type="hidden" name="mid" value="{$id}" />
<div id="approvedit" style="display:none;"><input type="hidden" value="{$row['approve']}" name="approvedit" /></div>
<div class="unterline"></div>

<table border="1">
<tr><td width="250" style="padding:5px;">Исполнитель:</td><td widtth="710" style="padding:5px;"><input type="text" name="artist" class="edit" value="{$row[artist]}" size="40" /></td></tr>
<tr><td style="padding:5px;">Название трека:</td><td style="padding:5px;"><input type="text" name="title" class="edit" value="{$row[title]}" size="40" /> {$viewcover}</td></tr>
<tr><td style="padding:5px;"><font color="#1DDF04">Описание трека:</font></td><td style="padding:5px;"><textarea cols="45" style="height:70px; resize:vertical;" name="comments" class="f_input">{$row[description]}</textarea><span class="tooltip-target" id="example-target-1"><input style="margin-left:100px" type="checkbox" name="demotrack" value="1"{$is_demo} /> демозапись?</span><div class="tooltip-content" id="example-content-1">если отмечено, то к названию трека добавится надпись: (демозапись)</div></td></tr>
<tr><td style="padding:5px;">Дата добавления:</td><td style="padding:5px;"><input type="text" name="time" class="edit" id="time" value="{$date}" size="30" />

<img src="engine/skins/images/img.gif" align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "time",
        ifFormat       :    "%d.%m.%Y %H:%M",
        button         :    "f_trigger_c",
        align          :    "Br",
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script>

<input type="checkbox" name="current_date" value="yes" /> Текущая дата и время

</td></tr>

<tr><td style="padding:5px;">Добавил трек (User_ID + имя):</td><td style="padding:5px;"><input type="text" name="uploader" class="edit" value="{$row[uploader]}" size="10" />&nbsp;&nbsp;&nbsp;&nbsp;<a href='{$PHP_SELF}?mod=mservice&act=usertracks&uploaderid={$row[uploader]}' target="_blank" title='Смотреть все треки {$row[name]}'>{$row[name]}</a>{$approve_message2}</td></tr>

<tr><td style="padding:5px;">Информация о файле:</td><td style="padding:5px;">
  <table><tr><td style="padding:3px;">Длительность:</td><td style="padding:3px;">{$row[lenght]} мин</td><td width="50"></td><td style="padding:3px;">Размер:</td><td style="padding:3px;">{$filesize}</td></tr>
        <tr><td style="padding:3px;">Bitrate:</td><td style="padding:3px;">{$row[bitrate]} Кбит/с</td><td width="50"></td><td style="padding:3px;">Тип:</td><td style="padding:3px;">{$filetype}</td></tr>

  </table></td></tr>
  <tr><td style="padding:5px;">Статистика трека:</td><td style="padding:5px;">
  <table><tr><td style="padding:3px;">Рейтинг:</td><td colspan="4" style="padding:3px;">{$rating_track}</td></tr>
        <tr><td style="padding:3px;">Просмотров:</td><td style="padding:3px;">{$row[view_count]}</td><td width="50"></td><td style="padding:3px;">Скачиваний:</td><td style="padding:3px;">{$row[download]}</td></tr>

  </table></td></tr>
<tr><td style="padding:5px;">Прослушивание:</td><td style="padding:10px 0px;">{$player}</td></tr>


<tr><td style="padding:5px;">Аудио трек:</td><td style="padding:10px 5px;"><input type="file" name="file" size="35" /> <label for="change_file"><input type="checkbox" id="change_file" name="change_file" value="no" checked="checked" /> <span class="adddec">Не изменять</span></label><br />Разрешённые типы: {$file_types}; размер не более: {$file_size}</td>
</tr>
<tr><td style="padding:5px"><font color="#EF5151">Онлайн клип:</font></td><td style="padding:5px;"><textarea cols="100" style="height:100px; resize:vertical;" name="clip_online" class="f_input">{$row[clip_online]}</textarea></td></tr>
<tr><td style="padding:5px;">Дата добавления (изменения) клипа<br /> + обложка:</td><td style="padding:5px 10px;">{$clip_online_time}{$clip_online_image}</td></tr>
</table>
<input type="hidden" name='filename' value='{$row[filename]}' />
<input type="hidden" name='sqltime' value='{$row[time]}' />
<input type="hidden" name='hdd' value='{$row[hdd]}' />
<input type="hidden" name='clip_online_old' value='{$row[clip_online]}' />
<input type="hidden" name='clip_online_time' value='{$row[clip_online_time]}' />
<br /><input type="submit" value="  Отредактировать трек  " class="buttons" />

</form>
HTML;

CloseTable( );
echofooter( );

break;

/////////////////////       Редактирование трека,  сами  действия 
case 'dotrackedit' :

$artist = $parse->remove( $parse->process( parseLinks( $_POST['artist'], $mscfg['parse_id3_tags'] ) ) );
$title = $parse->remove( $parse->process( parseLinks( $_POST['title'], $mscfg['parse_id3_tags'] ) ) );
$mid = intval( $_POST['mid'] );
$transartist = totranslit( $artist );
$file = $_POST['filename'];
$clip_online = $_POST['clip_online'];
$clip_online_old = $_POST['clip_online_old'];

if (($clip_online != $clip_online_old) && ($clip_online != '')) $clip_online_time = time();
elseif ($clip_online == '') $clip_online_time = '';
elseif (($clip_online != '') && ($clip_online_time == '')) $clip_online_time = time();
else $clip_online_time = $_POST['clip_online_time'];

if ($_POST['approvedit'] == '1') $approvedit = '1'; else $approvedit = '0';
if (intval($_POST['demotrack']) == '1') $demotrack = '1'; else $demotrack = '0';

$time = $_POST['time'];
$sqltime = $_POST['sqltime'];
$hdd = intval( $_POST['hdd'] );
if ( $hdd != 2 ) $hdd = '';

if ( $_POST['current_date'] == 'yes' ) {
    $time = time( );
}
else {
    $time = @str_replace( ' ', '.', $time );
    $time = @str_replace( ':', '.', $time );
    list( $d, $m, $y, $h, $i ) = explode( '.', $time );
    $time = mktime( $h, $i, 0, $m, $d, $y );
}

$uploader = intval( $_POST['uploader'] );
$descr = $parse->remove( $parse->process( parseLinks( $_POST['comments'], $mscfg['parse_id3_tags'] ) ) );

if ( $artist == '' ) $stop .= '<li>Вы не ввели исполнителя трека</li>';
if ( $title == '' ) $stop .= '<li>Вы не ввели название трека</li>';
if ( $uploader == '' ) $stop .= '<li>Вы не ввели логин пользователя добавившего трека</li>';

$row = $db->super_query( "SELECT COUNT(1) as count FROM ".PREFIX."_mservice WHERE title = '$title' AND artist = '$artist' AND mid != '$mid '" );
if ( $row['count'] > 0 ) $stop .= '<li>Такой трек уже есть на сайте!</li>';
            
$strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
$strip_base64_title = str_replace('/','_',str_replace('+','*',base64_encode($title)));

if ( $_POST['change_file'] != 'no' ) {

if ( $_FILES['file']['size'] == FALSE ) $stop .= '<li>Вы не выбрали файл трека для загрузки на сервер</li>';

$allowed_files = explode( ',', strtolower( $mscfg['filetypes'] ) );
$tfile = end( explode( ".", totranslit( $_FILES['file']['name'] ) ) );
$file_allow = FALSE;
for ( $f = 0; $f < count( $allowed_files ); $f ++ ) {
  if ( $tfile == $allowed_files[$f] ) $file_allow = TRUE;
}
if ( $file_allow == FALSE ) $stop .= '<li>Вы не можете загружать файлы такого типа</li>';
if ( $_FILES['file']['size'] > $mscfg['maxfilesize'] * 1024 ) $stop .= '<li>Выбранный Вами файл слишком большой</li>';

if ( $stop == '' ) {

$for_md5 = $time + mt_rand( 0, 1000 );
$for_md5 .= $_FILES['file']['name'];
$filename = md5( $for_md5 ) . '.' . $tfile;

$oldsubpapka = nameSubDir( $sqltime, $hdd );
$subpapka = nameSubDir( $time, 2 );

unlink( '/home'.$hdd.'/mp3base/'.$oldsubpapka.'/'.$file );
move_uploaded_file( $_FILES['file']['tmp_name'], '/home2/mp3base/'.$subpapka.'/'.$filename );
$hdd = 2;
$file_replace = TRUE;

		include_once ENGINE_DIR . '/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home2/mp3base/'.$subpapka.'/'.$filename );
        
		$beats = intval( $track['bitrate'] / 1000 );
		$lenght = $track['playtime_string'];

		if ( empty( $lenght ) ) $lenght = '';
		if ( $beats <= 0 ) $beats = 128;
		
//////////////////////////////////////////////////////////////////////* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = $title.' (domain.com)';
					$TagData['artist'][] = $artist;
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = $descr;
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = $ThisFileInfo['id3v2']['APIC'][0]['data'];
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
					// write tags 
					$tagwriter->WriteTags();
					unset($getID3, $tagwriter, $ThisFileInfo, $TagData);
///////////////////////////////////////////////////////////////////////////* Конец Исправление ID3 тегов файлу */
$size = @filesize('/home2/mp3base/'.$subpapka.'/'.$filename);
$crc32b = @hash_file('crc32b', '/home2/mp3base/'.$subpapka.'/'.$filename);
}

}

if ( $stop == '' ) {

if ( $file_replace != TRUE ) {
    $subpapka = nameSubDir( $time, $hdd );
    $sqlsubpapka = nameSubDir( $sqltime, $hdd );
    
    if ($subpapka != $sqlsubpapka) {
        rename( '/home'.$hdd.'/mp3base/'.$sqlsubpapka.'/'.$file, '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$file );
    }
    $filename = $file;
		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename );
        
//////////////////////////////////////////////////////////////////////* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = $title.' (domain.com)';
					$TagData['artist'][] = $artist;
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = $descr;
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = $ThisFileInfo['id3v2']['APIC'][0]['data'];
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
					// write tags 
                    $tagwriter->WriteTags();

					unset($getID3, $tagwriter, $ThisFileInfo, $TagData);
///////////////////////////////////////////////////////////////////////////* Конец Исправление ID3 тегов файлу */
$size = @filesize('/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename);
$crc32b = @hash_file('crc32b', '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename);
      
$db->query( "UPDATE " . PREFIX . "_mservice SET artist = '$artist', title = '$title', time = '$time', uploader = '$uploader', description = '$descr', filename = '$filename', hdd = '$hdd', size = '$size', crc32 = '$crc32b', transartist = '$transartist', strip_base64_artist ='$strip_base64_artist', strip_base64_title ='$strip_base64_title', approve = '$approvedit', is_demo = '$demotrack', clip_online = '$clip_online', clip_online_time = '$clip_online_time' WHERE mid = '$mid' LIMIT 1" );

} else {

$db->query( "UPDATE " . PREFIX . "_mservice SET artist = '$artist', title = '$title', time = '$time', uploader = '$uploader', description = '$descr', filename = '$filename', hdd = '$hdd', size = '$size', crc32 = '$crc32b', transartist = '$transartist', lenght = '$lenght', bitrate ='$beats', strip_base64_artist ='$strip_base64_artist', strip_base64_title ='$strip_base64_title', approve = '$approvedit', is_demo = '$demotrack', clip_online = '$clip_online', clip_online_time = '$clip_online_time' WHERE mid = '$mid' LIMIT 1" );

}

msg("info", "Редактирование трека", "Трек был успешно отредактирован!<br /><br /><a class='main' href='javascript:history.go(-1)'>Вернуться на предыдущую страницу!</a><br /><br /><a href=$PHP_SELF?mod=mservice&act=tracks>Вернуться назад в меню 'Управление треками'</a>");

} else {

msg("info", "Редактирование трека", "<br /><span style=\"color:#cc0000;\">Были обнаружены следующие ошибки:<br /><br />{$stop}</span><br /><br /><a href='$PHP_SELF?mod=mservice&act=trackedit&id={$mid}'>Вернуться назад на страницу редактирования трека</a><br /><br /><script type=\"text/javascript\">alert ( '{$stop}' );</script>");

}

$db->free( );

break;
//      добавление   альбома
case 'addalbum' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Добавление нового альбома', $PHP_SELF . '?mod=mservice' );

echo <<<HTML
<form action="" method="post"  enctype="multipart/form-data">
<input type="hidden" name="act" value="doaddalbum" />
<input type="hidden" name="genre" id="genreval" value="" />

<table border="0" width="100%">
<tr><td style="padding-left:2px; padding-top:5px;">Исполнитель: <font color="#EF5151">*</font></td><td style="padding-top:5px;"><input type="text" id="artist-albums" name="artist" class="edit" size="40" /><input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF; margin:0px 2px 2px;" title="Найти и отобразить альбомы этого исполнителя" onclick="viewAllAlbums( ); return false;" type="button" value="Найти все альбомы исполнителя" /><div style="padding:5px; background:#FBDB85; margin:5px 0px; display:none; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;" id="all-artist-albums"></div></td></tr>
<tr><td width="190" style="padding-left:2px;">Название альбома: <font color="#EF5151">*</font></td><td style="padding-top:5px;"><input type="text" name="album" class="edit" size="40" /></td></tr>
<tr><td style="padding-left:2px;padding-top:5px;">Добавил альбом (пишем имя):</td><td style="padding-top:5px;"><input type="text" name="uploader" class="edit" value="{$member_id[name]}" size="30" /></td></tr>
<tr><td width="170" style="padding-left:2px; padding-bottom:15px;">Год выхода:</td><td style="padding-top:5px; padding-bottom:15px;"><input type="text" name="year" class="edit" size="5" /></td></tr>
<tr style="background-color:#C5C5C7;"><td width="170" style="padding-left:2px;">Жанр:<br /><a id="moregenres_a" style="cursor:pointer; color:1E90FF;">(Подсказка)</a></td><td align="middle">
<ul id="genre" class="genre">
 <li><label><input id="CheckBox1" type="radio" name="cur" value="1" class="radiobuttonchange" />Pop</label></li>
 <li><label><input id="CheckBox2" type="radio" name="cur" value="2" class="radiobuttonchange" />Club</label></li>
 <li><label><input id="CheckBox3" type="radio" name="cur" value="3" class="radiobuttonchange" />Rap</label></li>
 <li><label><input id="CheckBox4" type="radio" name="cur" value="4" class="radiobuttonchange" />Rock</label></li>
 <li><label><input id="CheckBox5" type="radio" name="cur" value="5" class="radiobuttonchange" />Шансон</label></li>
 <li><label><input id="CheckBox6" type="radio" name="cur" value="6" class="radiobuttonchange" />OST</label></li>
 <li><label><input id="CheckBox7" type="radio" name="cur" value="7" class="radiobuttonchange" />Metal</label></li>
 <li><label><input id="CheckBox8" type="radio" name="cur" value="8" class="radiobuttonchange" />Country</label></li>
 <li><label><input id="CheckBox9" type="radio" name="cur" value="9" class="radiobuttonchange" />Classical</label></li><br />
 <li><label><input id="CheckBox10" type="radio" name="cur" value="10" class="radiobuttonchange" />Punk</label></li>
 <li><label><input id="CheckBox11" type="radio" name="cur" value="11" class="radiobuttonchange" />Soul/R&B</label></li>
 <li><label><input id="CheckBox12" type="radio" name="cur" value="12" class="radiobuttonchange" />Jazz</label></li>
 <li><label><input id="CheckBox13" type="radio" name="cur" value="13" class="radiobuttonchange" />Electronic</label></li>
 <li><label><input id="CheckBox14" type="radio" name="cur" value="14" class="radiobuttonchange" />Indie</label></li>
</ul>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<div id="loading-layer"><img src="/engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>

<script>
function viewAllAlbums( ) {
				var ajax = new dle_ajax();
				ajax.onShow ('');
				var varsString = "";
				ajax.setVar( "act", '44' );
  			ajax.setVar( "artist", document.getElementById('artist-albums').value );
				ajax.requestFile = "engine/modules/mservice/ajax.php";
				ajax.method = 'POST';
				ajax.element = 'all-artist-albums';
				ajax.sendAJAX(varsString);
  			ajax.onCompletion ( document.getElementById('all-artist-albums').style.display = 'block' );
}
	
$('a#moregenres_a').click(function(){
  $("#moregenres").css('display','block');
  return false;
});

$('.genre input').change(function() {
var genreid = $('input[name=cur]:checked', '#genre li label').val();
$("#genreval").val(genreid);
});

$(document).ready(function(){  
   $(".radiobuttonchange").change(function(){  
        if($(this).is(":checked")){  
            if($(this).is("#CheckBox1")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox2")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox3")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox4")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox5")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox6")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox7")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox8")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox9")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox10")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox11")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox12")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox13")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox14")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }                                 
		}
    });  
}); 
</script>
</td></tr>
<tr><td></td><td><div id="moregenres"><span>Alternative</span> = Indie, <span>Indie Rock</span> = Indie, <span>Indie Pop</span> = Indie, <span>New Age</span> = Electronic, <span>New Wave</span> = Rock, <span>Reggae</span> = Soul/R&b</div>
</td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Описание альбома (опционально):</td><td style="padding-top:20px;"><textarea cols="40" style="height:50px; resize:vertical;" name="descriptions" class="f_input"></textarea></td></tr>
<tr><td style="padding-left:2px;padding-top:20px;">Обложка альбома:</td><td style="padding-top:20px;"><input type="file" name="image" size="35" accept="image/*" />
<br /><li>максимальный размер загружаемой картинки до 3Mb</li>
<li>разрешённые типы файлов: jpg, png, jpe, jpeg</li></td></tr>
<tr><td></td><td><br /><input type="submit" value="  Добавить альбом  " class="buttons" /></td></tr>
</table>
</form>
HTML;

CloseTable( );
echofooter( );

break;

case 'doaddalbum' :

$artist = $parse->process( $parse->remove( parseLinksAdmin( $_POST['artist'] ) ) );
$album = $parse->process( $parse->remove( parseLinksAdmin( $_POST['album'] ) ) );
$uploader = $parse->process( $parse->remove( $_POST['uploader'] ) );

$roW_temp = $db->super_query( "SELECT user_id, (SELECT COUNT( aid ) FROM ".PREFIX."_mservice_albums WHERE album = '$album' AND artist = '$artist') AS cnt FROM ".USERPREFIX."_users WHERE name = '$uploader'" );
$descr = trim( $parse->process( $parse->remove( $_POST['descriptions'] ) ) );

$genre = intval($_POST['genre']);
if ($genre == '' OR $genre < 0 OR $genre > 14) $genre = 0;

$year = abs(intval($_POST['year']));
if (( $year > date('Y')+1 ) OR ( $year < 1901 ) OR ( $year == 0 )) $year = '';

if ( $roW_temp['cnt'] != 0 ) $stop .= '<li>Альбом <strong>'.$artist.' - '.$album.'</strong> уже есть в базе!</li>';

if ( $artist == '' ) $stop .= '<li>Вы не ввели исполнителя альбома</li>';
if ( $album == '' ) $stop .= '<li>Вы не ввели название альбома</li>';
if ( $uploader == '' ) $stop .= '<li>Вы не ввели логин пользователя добавившего альбом</li>';

if ( $stop == '' ) {

  if ($roW_temp['user_id'] == 1017)  $roW_temp['user_id'] = 33934;

  $image = $_FILES['image']['tmp_name'];
  $image_name = basename($_FILES['image']['name']);
  $image_size = $_FILES['image']['size'];
  $img_name_arr = explode( ".", $image_name );
  $type = end( $img_name_arr );
  
	if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) and ! $stop ) {
		
			if( $image_size < 3000000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR.'/classes/thumb.class.php';
					
					$time = time( );
          $image_name_md5 = md5($time + mt_rand( 0, 100 )).".".$type;

					$res = @move_uploaded_file( $image, ROOT_DIR."/uploads/albums/".$image_name_md5 );
					
					if( $res ) {
						
						@chmod( ROOT_DIR."/uploads/albums/".$image_name_md5, 0666 );
						$thumb = new thumbnail( ROOT_DIR."/uploads/albums/".$image_name_md5 );
						
						//150 - размер картинки в пикселях, означает 150х150
						if( $thumb->size_auto( 150 ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR."/uploads/albums/album_".$image_name_md5 );
						} else {
							@rename( ROOT_DIR."/uploads/albums/".$image_name_md5, ROOT_DIR."/uploads/albums/album_".$image_name_md5 );
						}
						
						@chmod( ROOT_DIR."/uploads/albums/album_".$image_name_md5, 0666 );
						$img_cover = "album_".$image_name_md5;
						
					
					} else
						$stop .= '<li>Произошла ошибка при загрузке картинки!</li>';
				} else
					$stop .= '<li>К загрузке разрешены только файлы изображений (jpg, png, jpe, jpeg)<!/li>';
			} else
				$stop .= '<li>Максимальный размер загружаемой картинки не должен превышать 3Mb!</li>';
		
		@unlink( ROOT_DIR."/uploads/albums/".$image_name_md5 );
	}
}								

if ( $stop == '' ) {
	$transartist = totranslit( $artist );
  $strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
	$time = time();
	
  $db->query( "INSERT INTO ".PREFIX."_mservice_albums ( year, time, album, artist, approve, image_cover, uploader, genre, is_notfullalbum, description, transartist, strip_base64_artist ) VALUES ( '$year', '$time', '$album', '$artist', '1', '$img_cover', '$roW_temp[user_id]', '$genre', '1', '$descr', '$transartist', '$strip_base64_artist' )" );
  
  if( $img_cover != '' ) {
    $row = $db->super_query( "SELECT aid FROM ".PREFIX."_mservice_albums WHERE image_cover = '$img_cover'" );
    $aid = $row['aid'];
    $img_cover_new = 'album_'.$aid.".".$type;
		@rename( ROOT_DIR."/uploads/albums/".$img_cover, ROOT_DIR."/uploads/albums/".$img_cover_new );
		//copy( ROOT_DIR."/uploads/albums/".$img_cover_new, realpath(ROOT_DIR.'/../')."/domain.com/uploads/albums/".$img_cover_new );
		$image_cover_crc32 = hash_file('crc32b', ROOT_DIR."/uploads/albums/".$img_cover_new);
    $db->query( "UPDATE ".PREFIX."_mservice_albums SET image_cover = '$img_cover_new', image_cover_crc32 = '$image_cover_crc32' WHERE aid = '$aid'" );
  }
	clear_cache( );
	msg("info", "Добавление альбома", "Альбом был успешно добавлен в базу данных!<br /><br /><a href=$PHP_SELF?mod=mservice&act=albums>Вернуться назад в меню 'Управление альбомами'</a><br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад в Главное меню</a>");
} else {
  msg("info", "Ошибка", "<br /><span style='color:#cc0000;'>Во время добавления альбома были обнаружены следующие ошибки:</span><br /><br />{$stop}<br /><a href=$PHP_SELF?mod=mservice&act=addalbum>Вернуться назад</a><br /><br />");
}

break;
//   управление   альбомами
case 'albums' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Управление альбомами', $PHP_SELF . '?mod=mservice', '<a href="'.$PHP_SELF.'?mod=mservice&act=addalbum" style="color:#1E90FF">Добавить новый альбом</a>' );

echo <<<HTML
<script type="text/javascript" src="engine/skins/jquery.ezpz_tooltip.min.js"></script>
<script language='JavaScript' type="text/javascript">
<!--
$(document).ready(function(){
  $(".tooltip-target").ezpz_tooltip({
  contentPosition: 'rightStatic',
  stayOnContent: true,
  offset: 0
});
});
function ckeck_uncheck_all() {
    var frm = document.mservicemass;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}
-->
</script>

<table width="100%" border="0">
<tr>
<td style="padding-left:10px; width:50%;">Введите номер альбома (aid) для редактирования&nbsp;&nbsp;&nbsp;==================></td>
<td style="padding-top:5px; text-align:center;">
<form action="{$PHP_SELF}" method="get" enctype="multipart/form-data">
<input type="hidden" name="mod" value="mservice" />
<input type="hidden" name="act" value="albumedit" />
<input type="text" value="" name="aid" class="edit" id="id" size="10" />
</td><td>
<input type="submit" value="  Редактировать альбом  " class="buttons" />
</form>
</td></tr>
<tr><td style="padding-top:5px;" colspan="6"><div class="unterline"></div></td></tr></table>
<table width="100%" border="1" class="table">
<form action="" method="post" name="mservicemass">
<input type="hidden" name="act" value="massactalbums" />
<thead>
<tr class="head"><th width="10%" align="center">Дата + aid</th><th width="20%" align="center">Название альбома <img title='Редактировать альбом' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></th><th width="7%" align="center">Год</th><th width="20%" align="center">Исполнитель</th><th width="6%" align="center">Жанр</th><th width="10%" align="center">Загрузил</th><th width="5%" align="center">Треков</th><th width="5%" align="center">Моде-рация</th><th width="4%" align="center"><input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()"></th></tr>
</thead>
<tbody>
HTML;

if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

$notfullalbum = intval($_REQUEST['notfullalbum']);
if ( $notfullalbum  == 1 ) {
  $db->query( "SELECT a.aid, a.time, a.year, a.album, a.artist, a.approve, a.image_cover, a.is_collection, a.collection, a.uploader, a.genre, a.transartist, a.is_notfullalbum, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE is_notfullalbum = '1') AS cnt, u.name, u.user_group, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE album = a.aid) AS cnt2 FROM ".PREFIX."_mservice_albums a LEFT JOIN ".USERPREFIX."_users u ON a.uploader = u.user_id WHERE is_notfullalbum = '1' ORDER BY a.aid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );
} else {

//approve тут везде пока на будущее, пока что нет модерации у альбомов approve = 1 при добавлении
$approve = $_REQUEST['approve'];
if ( $approve == '' ) $approve_sql = '';
elseif ( $approve == 0 ) $approve_sql = " WHERE approve = '0'";
elseif ( $approve == 1 ) $approve_sql = " WHERE approve = '1'";

$db->query( "SELECT a.aid, a.time, a.year, a.album, a.artist, a.approve, a.image_cover, a.is_collection, a.collection, a.uploader, a.genre, a.transartist, a.is_notfullalbum, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums{$approve_sql}) AS cnt, u.name, u.user_group, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE album = a.aid) AS cnt2 FROM ".PREFIX."_mservice_albums a LEFT JOIN ".USERPREFIX."_users u ON a.uploader = u.user_id{$approve_sql} ORDER BY a.aid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );
}
if ( $db->num_rows( ) == 0 ) {
	echo <<<HTML
<tr><td style="padding-left:10px;" colspan="8">Ещё нет альбомов в базе(( или нет альбомов подходящих заданным условиям поиска!</td></tr></table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

while ( $row = $db->get_row( ) ) {
	if (!$count) $count = $row['cnt'];

	if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\"red\" weight=\"bold\"><b>".$row['name']."</font>";
	else $userinfocss = $row['name'];
	$date = langdate( 'd.m.Y', $row['time'] );
	
	if ( $row['genre'] == '1') $alb_genre = 'Pop';
  elseif ( $row['genre'] == '2') $alb_genre = 'Club';
  elseif ( $row['genre'] == '3') $alb_genre = 'Rap';
  elseif ( $row['genre'] == '4') $alb_genre = 'Rock';
  elseif ( $row['genre'] == '5') $alb_genre = 'Шансон';
  elseif ( $row['genre'] == '6') $alb_genre = 'OST';
  elseif ( $row['genre'] == '7') $alb_genre = 'Metal';
  elseif ( $row['genre'] == '8') $alb_genre = 'Country';
  elseif ( $row['genre'] == '9') $alb_genre = 'Classical';
  elseif ( $row['genre'] == '10') $alb_genre = 'Punk';
  elseif ( $row['genre'] == '11') $alb_genre = 'Soul/R&B';
  elseif ( $row['genre'] == '12') $alb_genre = 'Jazz';
  elseif ( $row['genre'] == '13') $alb_genre = 'Electornic';
  elseif ( $row['genre'] == '14') $alb_genre = 'Indie';  
  else $alb_genre = '<span style="color:#c00;">-</span>';
  
	if ( $row['year'] == '0' OR $row['year'] == '00' OR $row['year'] == '000' OR $row['year'] == '0000' OR $row['year'] == '') $year = '<span style="color:#c00;">-</span>';
	else $year = $row['year'];
  
  if ( $row['image_cover'] ) $img_cover = $row['image_cover'];
	else $img_cover = 'album_0.jpg';
	
	if ( $row['is_collection'] ) {
    $is_collection = ' <span style="color:#F49804">(сборник)</span>';
    if ($row['collection']) $count_alb_tracks = $row['cnt2'] + count(explode( ",", $row['collection'] ));
    else $count_alb_tracks = $row['cnt2'];
  } else {
    $is_collection = '';
    $count_alb_tracks = $row['cnt2'];
  }
	
	if ( $row['is_notfullalbum'] == '1') $fullalbum = ' <span style="color:#FF1493;">(неполный альбом)</span>';
	else $fullalbum = '';
	
	if ( $row['approve'] == 1 ) $approves = 'Да'; else $approves = '<font color="red"><b>Нет</b></font>';
	echo "<tr><td style='padding:0 5px;'>{$date} <a href=\"/music/{$row[aid]}-album-{$row[transartist]}-".totranslit( $row['album'] ).".html\" target=\"_blank\" style=\"font-weight:bold;\">::</a> <strong>{$row[aid]}</strong></td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=albumedit&aid={$row[aid]}' title='Редактировать альбом' class='tooltip-target' id='example-target-{$row[aid]}'>{$row[album]}</a>{$is_collection}{$fullalbum}</td><td align='center'>{$year}</td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=artistalbums&artist={$row[transartist]}' title='Смотреть все альбомы {$row[artist]}' style='font-weight:bold'>{$row[artist]}</a></td><td align='center'><span class=\"genre{$row[genre]}\">{$alb_genre}</span></td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=useralbums&uploaderid={$row[uploader]}' title='Смотреть альбомы добавленные {$row[name]}'>{$userinfocss}</a></td><td align='center'>{$count_alb_tracks}</td><td align='center'>{$approves}</td><td align='center'>
<input name='selected_track[]' value='{$row[aid]}' type='checkbox' /></td></tr><div class='tooltip-content' style=\"padding:0px;\" id='example-content-{$row[aid]}'><img src='/uploads/albums/{$img_cover}'></div>";
}

//massactalbums отдельные
echo <<<HTML
</tbody>
</table>
<br /><div align="right" style="padding-right:20px;">

<select name="action">
<option value="">--- Действие ---</option>
<option value="1">Отправить на модерацию</option>
<option value="2">Снять с модерации</option>
<option value="3">Очистить счётчик просмотров</option>
<option value="4">Очистить рейтинг альбома</option>
<option value="5">Очистить счётчик скачиваний</option>
<option value="6">Установить альбому uploader=10</option>
<option value="7">Удалить альбом из архива</option>
</select>

<input type="submit" value="  Выполнить  " class="edit" /></div>
</form>
HTML;

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $approve == '' ) $this_approve = '';
elseif ( $approve == 0 ) $this_approve = '&approve=0';
elseif ( $approve == 1 ) $this_approve = '&approve=1';
else $this_approve = '';

if ( $notfullalbum == '' ) $this_approve .= '';
elseif ( $notfullalbum == 0 ) $this_approve .= '&notfullalbum=0';
elseif ( $notfullalbum == 1 ) $this_approve .= '&notfullalbum=1';
else $this_approve .= '';

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=mservice&act=albums&page={$t2}{$this_approve}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=albums&page=' . $npage . $this_approve . '">Назад</a> ';
  else $prev_page = '<span>Назад</span> ';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=albums&page=' . $npage . $this_approve . '">Далее</a>';
  else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}
CloseTable( );
echofooter( );

break;
/////////////////////       Редактирование альбома 
case 'albumedit' :

$aid = intval( $_REQUEST['aid'] );
$row = $db->super_query( "SELECT a.time, a.year, a.album, a.artist, a.approve, a.image_cover, a.uploader, a.genre, a.is_collection, a.collection, a.rating, a.download, a.vote_num, a.view_count, a.description, a.is_notfullalbum, u.name FROM ".PREFIX."_mservice_albums a LEFT JOIN ".USERPREFIX."_users u ON a.uploader = u.user_id WHERE aid = '$aid' LIMIT 1" );

echoheader( '', '' );
OpenTable( );
if ( $row ) $link_album = '<a href="/music/'.$aid.'-album-'.totranslit($row['artist']).'-'.totranslit( $row['album'] ).'.html" style="color:#1E90FF" target="_blank">Смотреть альбом на сайте</a>';
else $link_album = '';
EchoTableHeader( 'Редактирование альбома', $PHP_SELF . '?mod=mservice&act=albums', $link_album );

if ( !$row ) {
	echo <<<HTML
	<table width="100%" border="0">
    <tr>
        <td height="100" align="center">Нет альбома с таким aid. <a class=main href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a></td>
    </tr>
</table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

$date = langdate( 'd.m.Y H:i', $row['time'] );
$text = $row['description'];

if ($row['approve'] == '0') {
  $approve_message = 'Альбом не опубликован на сайте и находится НА МОДЕРАЦИИ! <a href="#" title="Снять альбом с модерации!" onclick="removeModerAlbum( '.$aid.' ); return false;">Снять альбом с модерации!</a><hr color="#999898" />';
  $approve_message2 = '';
} else {
  $approve_message = '';
  $approve_message2 = '<div id="album-moder2" style="left:200px; position:relative; top:-15px; width:220px;"><a href="#" title="Отправить альбом на модерацию!" onclick="addModerAlbum( '.$aid.' ); return false;">Отправить альбом на модерацию!</a></div>';
}

if ( $row['rating'] ) $rating_album = round( ($row['rating'] / $row['vote_num']), 1 );
else $rating_album = 0;

if ( $row['year']== 0 OR $row['year']== 00 OR $row['year']== 000 OR $row['year']== 0000 ) {
  $year = '';
  $yeartitle = '';
} else {
  $year = $row['year'];
  $yeartitle = '('.$row['year'].')';
}
  if ( $row['genre'] == '1') {$select1 = ' checked="checked"'; $labelselect1 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '2') {$select2 = ' checked="checked"'; $labelselect2 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '3') {$select3 = ' checked="checked"'; $labelselect3 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '4') {$select4 = ' checked="checked"'; $labelselect4 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '5') {$select5 = ' checked="checked"'; $labelselect5 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '6') {$select6 = ' checked="checked"'; $labelselect6 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '7') {$select7 = ' checked="checked"'; $labelselect7 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '8') {$select8 = ' checked="checked"'; $labelselect8 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '9') {$select9 = ' checked="checked"'; $labelselect9 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '10') {$select10 = ' checked="checked"'; $labelselect10 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '11') {$select11 = ' checked="checked"'; $labelselect11 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '12') {$select12 = ' checked="checked"'; $labelselect12 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '13') {$select13 = ' checked="checked"'; $labelselect13 = ' class="LabelSelected"';}
  elseif ( $row['genre'] == '14') {$select14 = ' checked="checked"'; $labelselect14 = ' class="LabelSelected"';}
  else $notgenre = 'Жанр не выбран! Пожалуйста выберите подходящий жанр для альбома!';
	if ( $row['is_notfullalbum'] == '1') {
    $fullalbum_message = 'Это НЕполный альбом!<hr color="#999898" />';
    $fullalbum_message2 = '<div id="fullalbum2"><a style="background:#B2DD54;" href="#" title="Обозначить альбом как полный!" onclick="isFullAlbum( '.$aid.' ); return false;">Обозначить альбом как полный!</a></div>';
  }
	else {
    $fullalbum_message = '';
    $fullalbum_message2 = '<div id="fullalbum2"><a href="#" title="Обозначить альбом как НЕполный!" onclick="isNotFullAlbum( '.$aid.' ); return false;">Обозначить альбом как НЕполный!</a></div>';
  }

if ( $row['image_cover'] ) {
  $coversize = formatsize(filesize(ROOT_DIR.'/uploads/albums/'.$row['image_cover']));
  $viewcover = <<<HTML
<div id="spamcover" style="float:right; color:#cc0000;"><img src="/uploads/albums/{$row[image_cover]}" style="padding: 0 0 5px 20px;" align="middle" />
!!! <a href="#" onclick="delSpamCoverAlbum('{$row[image_cover]}','{$aid}'); return false;">Удаление спамной обложки альбома</a> !!!<br /><span style="padding-left:70px;">{$coversize}</span></div>
HTML;
}
else $viewcover = <<<HTML
<div id="aid_number" style="display:none;">{$aid}</div>
<script type="text/javascript" src="engine/skins/ajaxupload.js"></script>
<script type="text/javascript" src="engine/skins/script_uploadimgalbum.js"></script>


<table border="0" style="float:right;">
<tr><td width="120" align="center"><div id="uploadButton" class="button"><font>Загрузить </font><img id="load" src="/engine/skins/images/loadstop.gif"/></div></td>
<td height="50" width="350"><div id="files_types"><li>максимальный размер загружаемой картинки до 3Mb</li><li>разрешённые типы файлов: jpg, png, jpe, jpeg</li></div></td>
<tr><td colspan="2"><span id="response"></span></td></tr>
</table>
HTML;

if (!$row['is_collection']) $alb_type = '<div id="alb_type">Обычный альбом. <a href="#" class="tooltip-target" id="example-target-'.$aid.'" onclick="inOutAlbumCollection(\''.$aid.'\', \'1\'); return false;">Сделать альбомом-сборником!</a><div class="tooltip-content" id="example-content-'.$aid.'">В него будут входить треки, не только треки отмеченные в таблице dle_mservice в поле album=\''.$aid.'\',<br />но ещё и треки добавленные в text-поле collection списком треков)</div></div>';
else $alb_type =  <<<HTML
<div id="alb_type"><table width="770"><tr><td width="40%"><span style="color:#F49804;">Альбом-сборник!</span> <a href="#" class="tooltip-target" id="example-target-{$aid}" onclick="inOutAlbumCollection('{$aid}', '2'); return false;">Сделать обычным альбомом!</a></td>

<td width="35%" align="center">Добавить в этот альбом-сборник трек №</td>
<td width="35%">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input type="hidden" name='action' value='11' />
<input type="hidden" name='aid' value='{$aid}' />
<input type="text" value="" name="mid" class="edit" size="10" />
<input type="submit" value="  Добавить  " class="buttons" />
</form>
</td></tr></table>
<div class="tooltip-content" id="example-content-{$aid}">В него будут входить только треки отмеченные в dle_mservice album={$aid}.<br /><span style="color:cc0000;">Осторожно, text-поле collection со списком треков очистится!</span></div>
</div>
HTML;

if ($row['collection']) $Collection_mids = " OR mid IN(".$row['collection'].")";
else $Collection_mids = "";

$db->query( "SELECT mid, time, title, approve, is_demo, album, artist, size, lenght, clip_online, bitrate FROM ".PREFIX."_mservice WHERE album = '".$aid."'".$Collection_mids." ORDER BY artist, title" );

if ( $db->num_rows() == 0 ) $album_tracks = 'В этом альбоме ещё нет треков';
else {
  $i = 1;
  $album_tracks = '<table border="1" class="table"><thead><tr class="head"><th>№</th><th>Трек</th><th>длит.</th><th>битр.</th><th style="padding: 3px 0;">размер</th><th>дата</th><th>ред.</th><th>уд.</th></thead>';
  
  while ( $row2 = $db->get_row( ) ) {
  
  if ( $row2['album'] == $aid ) {
    $add_del_track = '<a href="#" onclick="delTrackFromAlbum(\''.$row2['mid'].'\',\''.$i.'\',\''.$aid.'\'); return false;"><img title="Удалить трек из альбома" src="/engine/skins/images/rss_delete.gif" border="0" /></a>';
    $alb_exclusive = '';
  }
  else {
    $add_del_track = '<a href="#" onclick="delTrackFromAlbumCollection(\''.$row2['mid'].'\',\''.$i.'\',\''.$aid.'\'); return false;"><img title="Удалить трек из альбома" src="/engine/skins/images/rss_delete.gif" border="0" /></a>';
    if ( $row2['album'] == 0 )  $alb_exclusive = ' <span style="color:#F49804;">(из text-поля collection)</span>'; 
    else  $alb_exclusive = ' <span style="color:#cc0000;">(трек из обычного альбома ...)</span>';  
  }
  
  if ( $row2['is_demo'] == 1 ) $is_demo = ' <span style="color:#9ACD32;">(демозапись)</span>';
  else  $is_demo = '';
  
  if ( $row2['approve'] == 0 ) $approve = ' <span style="color:#FF1493;">(на модерации)</span>';
  else  $approve = '';
  
  if ($row2['bitrate'] < 192) $row2['bitrate'] = '<span style="color:#cc0000; font-weight:bold;">'.$row2['bitrate'].' Кб</span>';
  else $row2['bitrate'] = $row2['bitrate'].' Кб';
  
  $album_tracks .= '<tr id="track_'.$i.'"><td width="20" height="20" align="center">'.$i.')</td><td width="460"><a href="'.$config["http_home_url"].'music/'.$row2["mid"].'-mp3-'.totranslit( $row2["artist"] ).'-'.totranslit( $row2["title"] ).'.html" title="'.$row2["artist"].' - '.$row2["title"].'" target="_blank">'.artistTitleStrlen($row2['artist'], $row2['title'], ($mscfg['track_title_substr']-7)).'</a>'.$is_demo.$alb_exclusive.$approve.clipOnline( $row2['clip_online'] ).'</td><td width="40" align="center">'.$row2['lenght'].'</td><td width="60" align="center">'.$row2['bitrate'].'</td><td width="60" align="center">'.formatsize( $row2['size'] ).'</td><td width="60" align="center">'.date( 'd.m.y', $row2['time'] ).'</td><td width="30" align="center"><a href="'.$PHP_SELF .'?mod=mservice&act=trackedit&id='.$row2["mid"].'" target="_blank"><img title="Редактирование трека" src="/engine/skins/images/user_edit2.png" border="0" /></a></td><td width="30" align="center" id="del-add_'.$i.'">'.$add_del_track.'</td></tr>';
  $i++;
  }
  $album_tracks .= '</table>';
}

echo <<<HTML
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script type="text/javascript" src="engine/skins/jquery.ezpz_tooltip.min.js"></script>

<div id="loading-layer"><img src="/engine/ajax/loading.gif"  border="0" alt="" /></div><div id="busy_layer"></div>

<script type="text/javascript">
$(document).ready(function(){
  $(".tooltip-target").ezpz_tooltip({
  contentPosition: 'rightStatic',
  stayOnContent: true,
  offset: 0
});
});
function inOutAlbumCollection(aid, inout) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '34' );
ajax.setVar( "aid", aid );
ajax.setVar( "inout", inout );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'alb_type';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('alb_type').style.display = 'block' );
}
function delSpamCoverAlbum(image_cover,aid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '26' );
ajax.setVar( "image_cover", image_cover );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'spamcover';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('spamcover').style.display = 'block' );
}
function validate_form ( )
{
	valid = true;
  str = document.edit_form.comments.value;
  
  if ((-1 < str.indexOf('iframe')) || (-1 < str.indexOf('object')))
  {
    alert ( "В поле 'Описание альбома' нельзя использовать iframe или object!!!" );
    valid = false;
  }
  return valid;
}
function delTrackFromAlbum(mid,i,aid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '28' );
ajax.setVar( "mid", mid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.sendAJAX(varsString);

$('#track_'+i).css('opacity', '0.3');
$('#del-add_'+i).html('<a href="#" onclick="addTrackToAlbum(\''+mid+'\',\''+i+'\',\''+aid+'\'); return false;"><img title="Добавить трек назад в альбом" src="engine/skins/images/add_alb.png" border="0" /></a>');
}
function addTrackToAlbum(mid,i,aid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '29' );
ajax.setVar( "mid", mid );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.sendAJAX(varsString);

$('#track_'+i).css('opacity', '1');
$('#del-add_'+i).html('<a href="#" onclick="delTrackFromAlbum(\''+mid+'\',\''+i+'\',\''+aid+'\'); return false;"><img title="Удалить трек из альбома" src="engine/skins/images/rss_delete.gif" border="0" /></a>');
}
function delTrackFromAlbumCollection(mid,i,aid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '35' );
ajax.setVar( "aid", aid );
ajax.setVar( "mid", mid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.sendAJAX(varsString);

$('#track_'+i).css('opacity', '0.3');
$('#del-add_'+i).html('<a href="#" onclick="addTrackToAlbumCollection(\''+mid+'\',\''+i+'\',\''+aid+'\'); return false;"><img title="Добавить трек назад в альбом" src="engine/skins/images/add_alb.png" border="0" /></a>');
}
function addTrackToAlbumCollection(mid,i,aid) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '36' );
ajax.setVar( "mid", mid );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.sendAJAX(varsString);

$('#track_'+i).css('opacity', '1');
$('#del-add_'+i).html('<a href="#" onclick="delTrackFromAlbumCollection(\''+mid+'\',\''+i+'\',\''+aid+'\'); return false;"><img title="Удалить трек из альбома" src="engine/skins/images/rss_delete.gif" border="0" /></a>');
}
function isFullAlbum( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '39' );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'fullalbum2';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('fullalbum2').style.display = 'block' );

$('#fullalbum').html('').css('display', 'none');
$('#fullalbum2').css('color', '#B2DD54');
}
function isNotFullAlbum( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '40' );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'fullalbum2';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('fullalbum2').style.display = 'block' );

$('#fullalbum').html('Это НЕполный альбом!<hr color="#999898" />').css('display', 'block');
$('#fullalbum2').css('color', '#F97070');
}
function removeModerAlbum( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '41' );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'album-moder';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('album-moder').style.display = 'block' );

$('#approvedit').html('<input type="hidden" value="1" name="approvedit" />');
}

function addModerAlbum( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '42' );
ajax.setVar( "aid", aid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'album-moder';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('album-moder').style.display = 'block' );

$('#approvedit').html('<input type="hidden" value="0" name="approvedit" />');
$('#album-moder2').html('').css('display', 'none');
}
</script>

<table border="0" width="100%"><tr><td width="60%" style="text-align:center; color:9ACD32; font:bold 16px Verdana; padding:5px;">{$row[artist]} - {$row[album]} {$yeartitle}</td><td width="40%" style="background:url('/engine/skins/images/blue_aid.gif') no-repeat; text-align:left; color:#1E90FF; font:bold 16px Verdana; padding-left:100px;">{$aid}</td></tr>
<tr><td colspan="2"><hr color="#999898" /></td></tr><tr><td colspan="2"><div id="fullalbum" style="text-align:center; color:#FF1493; font-size:15px; font-weight:bold;">{$fullalbum_message}</div></td></tr><tr><td colspan="2"><div id="album-moder" style="color:red; font-weight:bold; text-align:center; font-weight:bold;">{$approve_message}</div></td></tr></table>

<table border="0" height="30">
<tr><td align="right">Удаление альбома ======></td>
<td width="150" align="right">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massactalbums" />
<input name='aid' value='{$aid}' type='checkbox' />
<input type="hidden" name='action' value='8' />
<input type="submit" value="  Удалить альбом  " class="buttons" />
</form>
</td></tr></table>
<hr color="#999898" />
<table border="0"><tr><td style="padding:5px;" width="250">Тип альбома:</td><td style="padding:10px 10px;">{$alb_type}</td></tr></table>

<form action="" method="post" enctype="multipart/form-data" name ="edit_form" onsubmit="return validate_form ( );">
<input type="hidden" name="act" value="doalbumedit" />
<input type="hidden" name="aid" value="{$aid}" />
<div id="approvedit" style="display:none;"><input type="hidden" value="{$row['approve']}" name="approvedit" /></div>
<div class="unterline"></div>

<table border="1" width="1050">
<tr><td width="250" style="padding:5px;">Исполнитель:</td><td style="padding:5px;"><input type="text" name="artist" class="edit" value="{$row[artist]}" size="40" /></td></tr>
<tr><td style="padding:5px;">Название альбома:</td><td style="padding:5px;"><input style="margin-top:30px;" type="text" name="album" class="edit" value="{$row[album]}" size="40" /> {$viewcover}</td></tr>
<tr><td style="padding:5px;">Год выхода:</td><td style="padding:5px;"><input type="text" name="year" class="edit" value="{$year}" size="5" /></td></tr>
<tr><td style="padding:5px;">Жанр:<br /><a id="moregenres_a" style="cursor:pointer; color:1E90FF;">(Подсказка)</a></td><td style="padding:5px;"><div id="is_genre">{$notgenre}</div>
<ul id="genre" class="genre">
 <li><label{$labelselect1}><input id="CheckBox1" type="radio" name="cur" value="1" class="radiobuttonchange"{$select1} />Pop</label></li>
 <li><label{$labelselect2}><input id="CheckBox2" type="radio" name="cur" value="2" class="radiobuttonchange"{$select2} />Club</label></li>
 <li><label{$labelselect3}><input id="CheckBox3" type="radio" name="cur" value="3" class="radiobuttonchange"{$select3} />Rap</label></li>
 <li><label{$labelselect4}><input id="CheckBox4" type="radio" name="cur" value="4" class="radiobuttonchange"{$select4} />Rock</label></li>
 <li><label{$labelselect5}><input id="CheckBox5" type="radio" name="cur" value="5" class="radiobuttonchange"{$select5} />Шансон</label></li>
 <li><label{$labelselect6}><input id="CheckBox6" type="radio" name="cur" value="6" class="radiobuttonchange"{$select6} />OST</label></li>
 <li><label{$labelselect7}><input id="CheckBox7" type="radio" name="cur" value="7" class="radiobuttonchange"{$select7} />Metal</label></li>
 <li><label{$labelselect8}><input id="CheckBox8" type="radio" name="cur" value="8" class="radiobuttonchange"{$select8} />Country</label></li>
 <li><label{$labelselect9}><input id="CheckBox9" type="radio" name="cur" value="9" class="radiobuttonchange"{$select9} />Classical</label></li><br />
 <li><label{$labelselect10}><input id="CheckBox10" type="radio" name="cur" value="10" class="radiobuttonchange"{$select10} />Punk</label></li>
 <li><label{$labelselect11}><input id="CheckBox11" type="radio" name="cur" value="11" class="radiobuttonchange"{$select11} />Soul/R&B</label></li>
 <li><label{$labelselect12}><input id="CheckBox12" type="radio" name="cur" value="12" class="radiobuttonchange"{$select12} />Jazz</label></li>
 <li><label{$labelselect13}><input id="CheckBox13" type="radio" name="cur" value="13" class="radiobuttonchange"{$select13} />Electronic</label></li>
 <li><label{$labelselect14}><input id="CheckBox14" type="radio" name="cur" value="14" class="radiobuttonchange"{$select14} />Indie</label></li>
</ul>
 
<script>
$('a#moregenres_a').click(function(){
  $("#moregenres").css('display','block');
  return false;
});

$('.genre input').change(function() {
var genreid = $('input[name=cur]:checked', '#genre li label').val();

var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '43' );
ajax.setVar( "aid", {$aid} );
ajax.setVar( "genreid", genreid );
ajax.requestFile = "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'is_genre';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('is_genre').style.display = 'block' );
});

$(document).ready(function(){  
   $(".radiobuttonchange").change(function(){  
        if($(this).is(":checked")){  
            if($(this).is("#CheckBox1")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox2")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox3")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox4")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox5")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox6")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox7")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox8")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox9")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox10")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox11")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox12")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox13")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
            if($(this).is("#CheckBox14")){
              $("#genre .LabelSelected").removeClass("LabelSelected");
              $(this).parent().addClass("LabelSelected");
            }
		}
    });  
}); 
</script>

</td></tr>
<tr><td></td><td><div id="moregenres"><span>Alternative</span> = Indie, <span>Indie Rock</span> = Indie, <span>Indie Pop</span> = Indie, <span>New Age</span> = Electronic, <span>New Wave</span> = Rock, <span>Reggae</span> = Soul/R&b</div>
</td></tr>
<tr><td style="padding:5px;"><font color="#1DDF04">Описание альбома:</font></td><td style="padding:5px;"><table><tr><td><textarea cols="45" style="height:70px; resize:vertical;" name="comments" class="f_input">{$row[description]}</textarea></td><td width="150"></td><td>{$fullalbum_message2}</td></tr></td></tr></table>
<tr><td style="padding:5px;">Дата добавления:</td><td style="padding:5px;"><input type="text" name="time" class="edit" id="time" value="{$date}" size="30" />

<img src="engine/skins/images/img.gif" align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "time",
        ifFormat       :    "%d.%m.%Y %H:%M",
        button         :    "f_trigger_c",
        align          :    "Br",
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script>

<input type="checkbox" name="current_date" value="yes" /> Текущая дата и время

</td></tr>

<tr><td style="padding:5px;">Добавил альбом (User_ID + имя):</td><td style="padding:5px;"><input type="text" name="uploader" class="edit" value="{$row[uploader]}" size="10" />&nbsp;&nbsp;&nbsp;&nbsp;<a href='{$PHP_SELF}?mod=mservice&act=useralbums&uploaderid={$row[uploader]}' title='Смотреть все альбомы {$row[name]}'>{$row[name]}</a>{$approve_message2}</td></tr>

<tr><td style="padding:5px;">Статистика альбома:</td><td style="padding:5px;">
<table><tr><td style="padding:3px;">Рейтинг:</td><td colspan="4" style="padding:3px;">{$rating_album}</td></tr>
<tr><td style="padding:3px;">Просмотров:</td><td style="padding:3px;">{$row[view_count]}</td><td width="50"></td><td style="padding:3px;">Скачиваний:</td><td style="padding:3px;">{$row[download]}</td></tr>
</table></td></tr>
<tr><td style="padding:5px;"><font color="#FF1493">Треки альбома:</font></td><td style="padding:10px 10px;">{$album_tracks}</td></tr>
</table>
<br /><input type="submit" value="  Отредактировать альбом  " class="buttons" />

</form>
HTML;

CloseTable( );
echofooter( );

break;

/////////////////////       Редактирование альбома,  сами  действия 
case 'doalbumedit' :

$aid = intval( $_POST['aid'] );
$artist = $parse->process( $parse->remove( parseLinksAdmin( $_POST['artist'] ) ) );
$album = $parse->process( $parse->remove( parseLinksAdmin( $_POST['album'] ) ) );

$year = abs(intval($_POST['year']));
$descr = trim( $parse->remove( $parse->process( $_POST['comments'] ) ) );
$uploader = abs(intval($_POST['uploader']));

$transartist = totranslit( $artist );
$strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));

if ($_POST['approvedit'] == '1') $approvedit = '1'; else $approvedit = '0';

$time = $_POST['time'];

if ( $_POST['current_date'] == 'yes' ) {
    $time = time( );
}
else {
    $time = @str_replace( ' ', '.', $time );
    $time = @str_replace( ':', '.', $time );
    list( $d, $m, $y, $h, $i ) = explode( '.', $time );
    $time = mktime( $h, $i, 0, $m, $d, $y );
}

if ( $artist == '' ) $stop .= '<li>Вы не ввели исполнителя альбома</li>';
if ( $album == '' ) $stop .= '<li>Вы не ввели название альбома</li>';
if ( $uploader == '' ) $stop .= '<li>Вы не ввели логин пользователя добавившего альбом</li>';

$row = $db->super_query( "SELECT COUNT(1) as count FROM ".PREFIX."_mservice_albums WHERE album = '$album' AND artist = '$artist' AND aid != '$aid '" );
if ( $row['count'] > 0 ) $stop .= '<li>Такой альбом уже есть на сайте!</li>';

if (( $year > date('Y')+1) OR ( $year < 1901 ) OR ( $year == 0 )) $year = '';

if ( $stop == '' ) {

  $db->query( "UPDATE ".PREFIX."_mservice_albums SET time = '$time', year = '$year', album = '$album', artist = '$artist', approve = '$approvedit', uploader = '$uploader', description = '$descr', transartist = '$transartist', strip_base64_artist ='$strip_base64_artist' WHERE aid = '$aid' LIMIT 1" );


  msg("info", "Редактирование альбома", "Альбом был успешно отредактирован!<br /><br /><a href=$PHP_SELF?mod=mservice&act=albums>Вернуться назад в меню 'Управление альбомами'</a>");

} else msg("info", "Редактирование альбома", "<br />Были обнаружены следующие ошибки:<br /><br />{$stop}<br /><br /><a href='$PHP_SELF?mod=mservice&act=albumedit&aid={$aid}'>Вернуться назад на страницу редактирования альбома</a><br /><br />");

$db->free( );

break;
//   управление   альбомами
case 'artistalbums' :

$nameartist = $parse->process( $_REQUEST['artist'] );

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Управление альбомами исполнителя', $PHP_SELF . '?mod=mservice', '<a href="'.$PHP_SELF.'?mod=mservice&act=addalbum" style="color:#1E90FF">Добавить новый альбом</a>' );

echo <<<HTML
<script type="text/javascript" src="engine/skins/jquery.ezpz_tooltip.min.js"></script>
<script language='JavaScript' type="text/javascript">
<!--
$(document).ready(function(){
  $(".tooltip-target").ezpz_tooltip({
  contentPosition: 'rightStatic',
  stayOnContent: true,
  offset: 0
});
});
function ckeck_uncheck_all() {
    var frm = document.mservicemass;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}
-->
</script>

<table width="100%" border="1" class="table">
<form action="" method="post" name="mservicemass">
<input type="hidden" name="act" value="massactalbums" />
<thead>
<tr class="head"><th width="10%" align="center">Дата + aid</th><th width="20%" align="center">Название альбома <img title='Редактировать альбом' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></th><th width="7%" align="center">Год</th><th width="20%" align="center">Исполнитель</th><th width="6%" align="center">Жанр</th><th width="10%" align="center">Загрузил</th><th width="5%" align="center">Треков</th><th width="5%" align="center">Моде-рация</th><th width="4%" align="center"><input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()"></th></tr>
</thead>
<tbody>
HTML;
//approve тут везде пока на будущее, пока что нет модерации у альбомов approve = 1 при добавлении
$approve = $_REQUEST['approve'];
if ( $approve == '' ) $approve_sql = '';
elseif ( $approve == 0 ) $approve_sql = " AND approve = '0'";
elseif ( $approve == 1 ) $approve_sql = " AND approve = '1'";

if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

$db->query( "SELECT a.aid, a.time, a.year, a.album, a.artist, a.approve, a.image_cover, a.is_collection, a.collection, a.uploader, a.genre, a.transartist, a.is_notfullalbum, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE transartist = '{$nameartist}'{$approve_sql}) AS cnt, u.name, u.user_group, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE album = a.aid) AS cnt2 FROM ".PREFIX."_mservice_albums a LEFT JOIN ".USERPREFIX."_users u ON a.uploader = u.user_id WHERE transartist = '{$nameartist}'{$approve_sql} ORDER BY a.aid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );

if ( $db->num_rows( ) == 0 ) {
	echo <<<HTML
<tr><td style="padding-left:10px;" colspan="8">Нет альбомов этого исполнителя в базе</td></tr></table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

while ( $row = $db->get_row( ) ) {
	if (!$count) $count = $row['cnt'];

	if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\"red\" weight=\"bold\"><b>".$row['name']."</font>";
	else $userinfocss = $row['name'];
	$date = langdate( 'd.m.Y', $row['time'] );
	
  if ( $row['genre'] == '1') $alb_genre = 'Pop';
  elseif ( $row['genre'] == '2') $alb_genre = 'Club';
  elseif ( $row['genre'] == '3') $alb_genre = 'Rap';
  elseif ( $row['genre'] == '4') $alb_genre = 'Rock';
  elseif ( $row['genre'] == '5') $alb_genre = 'Шансон';
  elseif ( $row['genre'] == '6') $alb_genre = 'OST';
  elseif ( $row['genre'] == '7') $alb_genre = 'Metal';
  elseif ( $row['genre'] == '8') $alb_genre = 'Country';
  elseif ( $row['genre'] == '9') $alb_genre = 'Classical';
  elseif ( $row['genre'] == '10') $alb_genre = 'Punk';
  elseif ( $row['genre'] == '11') $alb_genre = 'Soul/R&B';
  elseif ( $row['genre'] == '12') $alb_genre = 'Jazz';
  elseif ( $row['genre'] == '13') $alb_genre = 'Electronic';
  elseif ( $row['genre'] == '14') $alb_genre = 'Indie';
  else $alb_genre = '<span style="color:#c00;">-</span>';
  
	if ( $row['year'] == '0' OR $row['year'] == '00' OR $row['year'] == '000' OR $row['year'] == '0000' ) $year = '-';
	else $year = $row['year'];
  
  if ( $row['image_cover'] ) $img_cover = $row['image_cover'];
	else $img_cover = 'album_0.jpg';
	
	if ( $row['is_collection'] ) {
    $is_collection = ' <span style="color:#F49804">(сборник)</span>';
    if ($row['collection']) $count_alb_tracks = $row['cnt2'] + count(explode( ",", $row['collection'] ));
    else $count_alb_tracks = $row['cnt2'];
  } else {
    $is_collection = '';
    $count_alb_tracks = $row['cnt2'];
  }
	
	if ( $row['is_notfullalbum'] == '1') $fullalbum = ' <span style="color:#FF1493;">(неполный альбом)</span>';
	else $fullalbum = '';
	
	if ( $row['approve'] == 1 ) $approves = 'Да'; else $approves = '<font color="red"><b>Нет</b></font>';
	echo "<tr><td style='padding:0 5px;'>{$date} <a href=\"/music/{$row[aid]}-album-{$row[transartist]}-".totranslit( $row['album'] ).".html\" target=\"_blank\" style=\"font-weight:bold;\">::</a> <strong>{$row[aid]}</strong></td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=albumedit&aid={$row[aid]}' title='Редактировать альбом' class='tooltip-target' id='example-target-{$row[aid]}'>{$row[album]}</a>{$is_collection}{$fullalbum}</td><td align='center'>{$year}</td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=artistalbums&artist={$row[transartist]}' title='Смотреть все альбомы {$row[artist]}' style='font-weight:bold'>{$row[artist]}</a></td><td align='center'><span class=\"genre{$row[genre]}\">{$alb_genre}</span></td><td align='center'><a href='{$PHP_SELF}?mod=mservice&act=useralbums&uploaderid={$row[uploader]}' title='Смотреть альбомы добавленные {$row[name]}'>{$userinfocss}</a></td><td align='center'>{$count_alb_tracks}</td><td align='center'>{$approves}</td><td align='center'>
<input name='selected_track[]' value='{$row[aid]}' type='checkbox' /></td></tr><div class='tooltip-content' style=\"padding:0px;\" id='example-content-{$row[aid]}'><img src='/uploads/albums/{$img_cover}'></div>";
}

//massactalbums отдельные
echo <<<HTML
</tbody>
</table>
<br /><div align="right" style="padding-right:20px;">

<select name="action">
<option value="">--- Действие ---</option>
<option value="1">Отправить на модерацию</option>
<option value="2">Снять с модерации</option>
<option value="3">Очистить счётчик просмотров</option>
<option value="4">Очистить рейтинг альбома</option>
<option value="5">Очистить счётчик скачиваний</option>
<option value="6">Установить альбому uploader=10</option>
<option value="7">Удалить альбом из архива</option>
</select>

<input type="submit" value="  Выполнить  " class="edit" /></div>
</form>
HTML;

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $approve == '' ) $this_approve = '';
elseif ( $approve == 0 ) $this_approve = '&approve=0';
elseif ( $approve == 1 ) $this_approve = '&approve=1';
else $this_approve = '';

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=mservice&act=artistalbums&artist={$nameartist}&page={$t2}{$this_approve}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=artistalbums&artist='.$nameartist.'&page='.$npage.$this_approve.'">Назад</a> ';
  else $prev_page = '<span>Назад</span> ';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=artistalbums&artist='.$nameartist.'&page='.$npage.$this_approve.'">Далее</a>';
  else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}
CloseTable( );
echofooter( );

break;

///////////////////////////////////////////////////////////////       @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

case 'massact' :

$action = intval( $_REQUEST['action'] );
$selected = $_REQUEST['selected_track'];
$mid = abs(intval( $_REQUEST['mid'] ));
$aid = abs(intval( $_REQUEST['aid'] ));
$artid = abs(intval( $_REQUEST['artid'] ));

function backButtons() {
  return "<br /><br /><a class=main href=\"javascript:history.go(-1)\">Вернуться на предыдущую страницу</a><br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад в меню 'Модуль DleMusic Service'</a>";
}
function backButtons_tracks() {
  return "<br /><br /><a class=main href=\"javascript:history.go(-1)\">Вернуться на предыдущую страницу</a><br /><br /><a href=$PHP_SELF?mod=mservice&act=tracks>Вернуться назад в меню 'Управление треками'</a>";
}

function backButtons_artists() {
  return "<br /><br /><a class=main href=\"javascript:history.go(-1)\">Вернуться на предыдущую страницу</a><br /><br /><a href=$PHP_SELF?mod=mservice&act=artists>Вернуться назад в меню 'Управление Исполнителями'</a>";
}

if ( $action == 1 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET approve = '0' WHERE mid IN ($sqlselected) AND approve = '1'" );
  clear_cache( );
  msg("info", "Отправка треков на модерацию", "Все выделенные треки (<font color='#cc0000'>".count($selected )."</font>) были успешно отправлены на модерацию!".backButtons_tracks());
  $db->free( );
}
elseif ( $action == 2 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET approve = '1' WHERE mid IN ($sqlselected) AND approve = '0'" );
  clear_cache( );
  msg("info", "Снятие треков с модерации", "Все выделенные треки (<font color='#cc0000'>".count($selected )."</font>) были успешно сняты с модерации!".backButtons_tracks());
  $db->free( );
}
elseif ( $action == 3 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET view_count = '0' WHERE mid IN ($sqlselected)" );
  msg("info", "Очистка счётчика просмотров", "Счётчик просмотров был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных треков!".backButtons_tracks());
  $db->free( );
}
elseif ( $action == 4 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET rating = '0', vote_num = '0' WHERE mid IN ($sqlselected)" );
  msg("info", "Очистка рейтинга трека", "Рейтинг был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных треков!".backButtons_tracks());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 5 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET download = '0' WHERE mid IN ($sqlselected)" );
  msg("info", "Очистка счётчика скачиваний", "Счётчик скачиваний был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных треков!".backButtons_tracks());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 6 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice SET uploader = '10' WHERE mid IN ($sqlselected)" );
  msg("info", "Установить треку uploader=10", "Установка uploader = 10 (Mityai) была успешно произведена для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных треков!".backButtons_tracks());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 7 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
	$db->query( "SELECT time, filename, hdd, mid, album FROM ".PREFIX."_mservice WHERE mid IN ($sqlselected)" );
	if ( $db->num_rows() > 0 ) {
		$tracksdelete = 0;
		$tracksnotdelete = 0;
		while ( $row = $db->get_row( ) ) {
      if (($row['album'] == '') || ($row['album'] == '0')) {
        $hdd = intval($row['hdd']);
        if ( ($hdd !== 2) ) $hdd = '';
        if (unlink( "/home".$hdd."/mp3base/".nameSubDir( $row['time'], $hdd )."/".$row['filename'] )) {
          $tracksdelete ++;
          $midstodelete[] = $row['mid'];
        } else $tracksnotdelete ++;
      } else $tracksnotdelete ++;
    }  
		if ($tracksdelete > 0) {
			$sqldelete = implode( ",", $midstodelete);
			$db->query( "DELETE FROM ".PREFIX."_mservice WHERE mid IN ($sqldelete)" );
		}
	}

	if ($tracksnotdelete > 0) $tracksnotdelete = '<font color="red"><b>'.$tracksnotdelete.'</b></font>';
	msg("info", "Удаление треков", "Из архива успешно удалено треков: {$tracksdelete}, не удалено треков (трек не найден на диске или находится в музыкальном альбоме): {$tracksnotdelete}".backButtons_tracks());
	$db->free( );
}
elseif ( $action == 8 ) {
		$row = $db->super_query( "SELECT time, filename, hdd, album FROM ".PREFIX."_mservice WHERE mid = '$mid'" );
		if (($row['album'] == '') || ($row['album'] == '0')) {
		    $hdd = intval($row['hdd']);
        if ( ($hdd !== 2) ) $hdd = '';
		if (unlink("/home".$hdd."/mp3base/".nameSubDir($row['time'], $hdd )."/".$row['filename'])) {
      $db->query( "DELETE FROM ".PREFIX."_mservice WHERE mid = '$mid'" );
      clear_cache( );
      msg("info", "Удаление трека", "Трек номер {$mid} был успешно удалён из архива!".backButtons_tracks());
      $db->free( );
    } else 	msg("info", "Удаление трека", "<font color='#cc0000'>Удаление Трека номер {$mid} не произошло!</font>".backButtons_tracks()."<script type=\"text/javascript\">alert ( 'Удаление трека не произошло!' );</script>");
    } else msg("info", "Удаление трека", "<font color='#cc0000'>Удаление Трека номер {$mid} не произошло!</font>".backButtons_tracks()."<script type=\"text/javascript\">alert ( 'Трек находится в музыкальном альбоме № {$row['album']}! Сначала удалите трек из альбома, а потом будет доступно удаление трека из базы сайта!' );</script>");
}
elseif ( $action == 9 ) {
  $row = $db->super_query( "SELECT artist, image_cover, image_cover150 FROM ".PREFIX."_mservice_artists WHERE artid = '$artid'" );
  
  if ( is_file (ROOT_DIR.'/uploads/artists/'.$row['image_cover'])) unlink (ROOT_DIR.'/uploads/artists/'.$row['image_cover']);
	if ( is_file (ROOT_DIR.'/uploads/artists/'.$row['image_cover150'])) unlink (ROOT_DIR.'/uploads/artists/'.$row['image_cover150']);
		
  if ($db->query( "DELETE FROM ".PREFIX."_mservice_artists WHERE artid = '$artid'" )) {
      clear_cache( );
      msg("info", "Удаление исполнителя", "Исполнитель {$row['artist']}, aid = {$artid} был успешно удалён из базы!".backButtons_artists());
      $db->free( );
   } else 	msg("info", "Удаление исполнителя", "<font color='#cc0000'>Удаление исполнителя {$row['artist']}, aid = {$artid}  не произошло!</font>".backButtons_artists()."<script type=\"text/javascript\">alert ( 'Удаление исполнителя не произошло!' );</script>");

}
elseif ( $action == 10 ) {
	$row = $db->super_query( "SELECT a.artist AS album_artist, a.album, m.artist FROM ".PREFIX."_mservice_albums a LEFT JOIN ".PREFIX."_mservice m ON m.mid = '$id' WHERE a.aid = '$aid'" );
	if ($row) {
    $find_artist = stripos($row['artist'], $row['album_artist']);
      if ($find_artist !== false) {
        $db->query( "UPDATE ".PREFIX."_mservice SET album = '$aid' WHERE mid = '$mid'" );
        msg("info", "Добавление трека в альбом", "Трек номер {$id} был успешно добавлен в альбом <b>{$row[album]}</b > ({$row[album_artist]})!".backButtons());
      } else msg("info", "Добавление трека в альбом", "<font color='#cc0000'>Добавление трека номер {$id} в альбом <b>{$row[album]}</b > ({$row[album_artist]}) не произошло! Так как исполнитель альбома не является ни одним из исполнителей трека!</font>".backButtons()."<script type=\"text/javascript\">alert ( 'Добавление трека в альбом не произошло!' );</script>");
    $db->free( );
  } else msg("info", "Добавление трека в альбом", "<font color='#cc0000'>Добавление трека номер {$id} в альбом не произошло! Ошибка добавления!</font>".backButtons());
}
elseif ( $action == 11 ) {
  $row = $db->super_query( "SELECT COUNT(mid) as cnt FROM ".PREFIX."_mservice WHERE mid = '$mid' LIMIT 1" );
  if ($row['cnt']) {
    $row2 = $db->super_query( "SELECT is_collection, collection FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' LIMIT 1" );
    if ($row2['is_collection']) {
      if (!$row2['collection']) $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$mid."' WHERE aid = '".$aid."'" );
      else {
        $list_collection = explode( ",", $row2['collection']);
        if (!in_array($mid, $list_collection)) {
          $list_collection[] = $mid;
          $to_list_collection = implode( ",", $list_collection );
          $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$to_list_collection."' where aid = '".$aid."'" );
        } else 	msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление в альбом-сборник трека номер {$mid} не произошло! Трек уже в этом альбоме-сборнике!</font>".backButtons());
      } msg("info", "Добавление в альбом-сборник", "Трек номер {$mid} был успешно добавлен в альбом-сборник!".backButtons());
      } msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление не произошло! Так как это обычный альбом, а не альбом-сборник!</font>".backButtons());
    $db->free( );
  } else 	msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление в альбом-сборник трека номер {$mid} не произошло! Нет трека с таким номером!</font>".backButtons()); 
}
elseif ( $action == 12 ) {
  $row = $db->super_query( "SELECT COUNT(aid) as cnt FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' LIMIT 1" );
  if ($row['cnt']) {
    $row2 = $db->super_query( "SELECT is_collection, collection FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' LIMIT 1" );
    if ($row2['is_collection']) {
      if (!$row2['collection']) $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$mid."' WHERE aid = '".$aid."'" );
      else {
        $list_collection = explode( ",", $row2['collection']);
        if (!in_array($mid, $list_collection)) {
          $list_collection[] = $mid;
          $to_list_collection = implode( ",", $list_collection );
          $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$to_list_collection."' where aid = '".$aid."'" );
        } else 	msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление в альбом-сборник трека номер {$mid} не произошло! Трек уже в этом альбоме-сборнике!</font>".backButtons());
      } msg("info", "Добавление в альбом-сборник", "Трек номер {$mid} был успешно добавлен в альбом-сборник!".backButtons());
      } msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление не произошло! Так как это обычный альбом, а не альбом-сборник!</font>".backButtons());
    $db->free( );
  } else 	msg("info", "Добавление в альбом-сборник", "<font color='#cc0000'>Добавление в альбом-сборник трека номер {$mid} не произошло! Нет альбома с таким номером!</font>".backButtons()); 
}
elseif ( $action == 13 ) {
  $image_cover = $_REQUEST['image_cover'];
  $x = intval($_REQUEST['x']);
  $y = intval($_REQUEST['y']);
  $w = intval($_REQUEST['w']);
  $h = intval($_REQUEST['h']);
  
  $fileParts = pathinfo(ROOT_DIR.'/uploads/artists/'.$image_cover);
  $ext = strtolower($fileParts['extension']);
  $fileTypes = array('jpg','png', 'jpe', 'jpeg'); // File extensions
  
  if (in_array($fileParts['extension'],$fileTypes)) {
  	$targ_w = $targ_h = 150;
    $jpeg_quality = 90;

    $src = ROOT_DIR.'/uploads/artists/'.$image_cover;
    if (is_file ($src)) {
      $img_r = imagecreatefromjpeg($src);
      $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

      imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
      $targ_w,$targ_h,$_POST['w'],$_POST['h']);

      //header('Content-type: image/jpeg');
      //imagejpeg($dst_r,null,$jpeg_quality);
       $image_cover150 = $fileParts['filename'].'_cover150.'.$fileParts['extension'];
      if (is_file(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpg')) {chmod(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpg', 0666 );unlink(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpg');}
      if (is_file(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.png')) {chmod(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.png', 0666 );unlink(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.png');}
      if (is_file(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpe')) {chmod(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpe', 0666 );unlink(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpe');}
      if (is_file(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpeg')) {chmod(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpeg', 0666 );unlink(ROOT_DIR.'/uploads/artists/'.$fileParts['filename'].'_cover150.jpeg');}
      
      if (imagejpeg($dst_r, ROOT_DIR.'/uploads/artists/'.$image_cover150, $jpeg_quality)) {
        $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover150 = '$image_cover150' WHERE artid = '$artid'" );
        clear_cache( );
        msg("info", "Создание обложки исполнителя 150х150", "Новая обложка добавлена!".backButtons_artists());
        $db->free( );
      }
    } else 	msg("info", "Создание обложки исполнителя 150х150", "<font color='#cc0000'>Создание обложки исполнителя 150х150 не произошло!</font>".backButtons_artists()); 

	} else 	msg("info", "Создание обложки исполнителя 150х150", "<font color='#cc0000'>Создание обложки исполнителя 150х150 не произошло!</font>".backButtons_artists()); 
	
}
else msg("info", "<font color='#cc0000'>Ошибка</font>", "<font color='#cc0000'>Вы не выбрали действие из выпадающего списка и/или трек(-и) для действий!</font>".backButtons());
break;

// massact  для  альбомов !!!
case 'massactalbums' :

$action = intval( $_REQUEST['action'] );
$selected = $_REQUEST['selected_track'];
$aid = abs(intval( $_REQUEST['aid'] ));

function backButtons() {
  return "<br /><br /><a class=main href=\"javascript:history.go(-1)\">Вернуться на предыдущую страницу</a><br /><br /><a href=$PHP_SELF?mod=mservice&act=albums>Вернуться назад в меню 'Управление альбомами'</a>";
}

if ( $action == 1 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET approve = '0' WHERE aid IN ($sqlselected) AND approve = '1'" );
  clear_cache( );
  msg("info", "Отправка альбомов на модерацию", "Все выделенные альбомы (<font color='#cc0000'>".count($selected )."</font>) отпралены на модерацию.".backButtons());
  $db->free( );
}
elseif ( $action == 2 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET approve = '1' WHERE aid IN ($sqlselected) AND approve = '0'" );
  clear_cache( );
  msg("info", "Снятие альбомов с модерации", "Все выделенные альбомы (<font color='#cc0000'>".count($selected )."</font>) сняты с модерации.".backButtons());
  $db->free( );
}
elseif ( $action == 3 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET view_count = '0' WHERE aid IN ($sqlselected)" );
  clear_cache( );
  msg("info", "Очистка счётчика просмотров", "Счётчик просмотров был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных альбомов!".backButtons());
  $db->free( );
}
elseif ( $action == 4 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET rating = '0', vote_num = '0' WHERE aid IN ($sqlselected)" );
  msg("info", "Очистка рейтинга альбома", "Рейтинг был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных альбомов!".backButtons());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 5 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET download = '0' WHERE aid IN ($sqlselected)" );
  msg("info", "Очистка счётчика скачиваний", "Счётчик скачиваний был успешно обнулён для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных альбомов!".backButtons());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 6 AND is_array($selected)) {
  $sqlselected = implode( ",", $selected );
  $db->query( "UPDATE " . PREFIX . "_mservice_albums SET uploader = '10' WHERE aid IN ($sqlselected)" );
  msg("info", "Установить альбому uploader=10", "Установка uploader = 10 (Mityai) была успешно произведена для всех (<font color='#cc0000'>".count($selected )."</font>) выделенных альбомов!".backButtons());
  clear_cache( );
  $db->free( );
}
elseif ( $action == 7 AND is_array($selected)) {
	$sqlselected = implode( ",", $selected );
	$db->query( "UPDATE ".PREFIX."_mservice SET album = '0' WHERE album IN ($sqlselected)" );
	$db->query( "SELECT image_cover FROM ".PREFIX."_mservice_albums WHERE aid IN ($sqlselected)");
	if ( $db->num_rows( ) > 0 ) {
    while ( $row = $db->get_row( ) ) {
      if ( is_file (ROOT_DIR.'/uploads/albums/'.$row['image_cover'])) @unlink (ROOT_DIR.'/uploads/albums/'.$row['image_cover']);
	}
	}
	$db->query( "DELETE FROM ".PREFIX."_mservice_albums WHERE aid IN ($sqlselected)" );
	msg("info", "Удаление альбомов", "Из архива успешно удалено альбомов: <font color='red'><b>".count($selected)."</b></font>".backButtons());
	$db->free( );
}
elseif ( $action == 8 ) {
	if ($db->query( "UPDATE ".PREFIX."_mservice SET album = '0' WHERE album = '$aid'" )) {
		$row = $db->super_query( "SELECT image_cover FROM ".PREFIX."_mservice_albums WHERE aid = '$aid'");
		if ( $row['image_cover'] ) {
      if ( is_file (ROOT_DIR.'/uploads/albums/'.$row['image_cover'])) @unlink (ROOT_DIR.'/uploads/albums/'.$row['image_cover']);
    }
    $db->query( "DELETE FROM ".PREFIX."_mservice_albums WHERE aid = '$aid'" );
		clear_cache( );

		msg("info", "Удаление альбома", "Альбом номер {$aid} был успешно удалён из архива!".backButtons());
		$db->free( );
	} else 	msg("info", "Удаление альбома", "<font color='#cc0000'>Удаление альбома номер {$aid} не произошло!</font>".backButtons());
}
elseif ( $action == 9 ) {
}
else msg("info", "<font color='#cc0000'>Ошибка</font>", "<font color='#cc0000'>Вы не выбрали действие из выпадающего списка и/или треки альбом(-ы) для действий!</font>".backButtons());

break;

case "clear" :

$db->query( "TRUNCATE TABLE " . PREFIX . "_mservice_logs" );
$db->query( "TRUNCATE TABLE " . PREFIX . "_mservice_logs_albums" );
clear_cache( );

msg("info", "Пересчёт статистики", "Все операции (пересчёт статистики, очистка кеша, очистка логов модуля) были успешно завершены!<br /><br /><a href=$PHP_SELF?mod=mservice>Вернуться назад</a>");

$db->free( );

break;


// отсюда убраны все фунуции управления категориями на сайте, отключены, так как у нас одна категория с id = 2

case 'config' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'Настройки модуля', $PHP_SELF . '?mod=mservice' );

echo <<<HTML
<script language='JavaScript' type="text/javascript">
function ChangeOption(selectedOption)
{
document.getElementById('global').style.display = "none";
document.getElementById('plugins').style.display = "none";
document.getElementById('customize').style.display = "none";
if ( selectedOption == 'global' ) document.getElementById('global').style.display = "";
if ( selectedOption == 'plugins' ) document.getElementById('plugins').style.display = "";
if ( selectedOption == 'customize' ) document.getElementById('customize').style.display = "";
}
</script>

<div style="padding-top:5px;padding-bottom:2px;">

<table width="100%">
    <tr>
        <td style="padding:2px;">
<table style="text-align:center;" width="100%" height="35px">
<tr style="vertical-align:middle;" >
  <td class=tableborder><a href="javascript:ChangeOption('global');"><img title="Основные настройки" src="{$config[http_home_url]}engine/skins/images/mservice/global.png" border="0"></a></td>
  <td class=tableborder><a href="javascript:ChangeOption('plugins');"><img title="Настройка дополнительных плагинов" src="{$config[http_home_url]}engine/skins/images/mservice/plugins.png" border="0"></a></td>
  <td class=tableborder><a href="javascript:ChangeOption('customize');"><img title="Настройки отображения" src="{$config[http_home_url]}engine/skins/images/mservice/customize.png" border="0"></a></td>
</tr>
</table>
</td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
HTML;

echo <<<HTML
<form action="" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
<tr style='' id="global"><td>
<table width="100%">
HTML;

echo <<<HTML
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Основные настройки</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;

showRow("Музыкальный архив включен", "Если Нет то никто не сможет зайти в музыкальный архив сайта пока данная опция не будет установлена в положение Да.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[online]", "{$mscfg['online']}"));
showRow("Расширение файлов, допустимых к загрузке", "Укажите через запятую расширения файлов, которые разрешено закачивать.", "<input type=text style='text-align: center;' size=30 class=edit name='save_con[filetypes]' value='{$mscfg['filetypes']}'>", "");
showRow("Максимальный размер файла допустимый к загрузке на сервер (в килобайтах)", "Введите максимальный размер файлов которые допустимо загружать на сервер. Данный размер указывается в килобайтах, например для ограничения размера файла в 2 мегабайта, в настройках указывается 2048.", "<input type=text style='text-align: center;' size=15 class=edit name='save_con[maxfilesize]' value='{$mscfg['maxfilesize']}'>", "");
showRow("Тип плеера при прослушивании трека в музыкальном архиве", "Выберите тип плеера который Вы хотите использовать для прослушивания трека в музыкальном архиве. DLE Player - плеер встроенный в DataLife Engine, DleMS Player - плеер входящий в комплект с музыкальным архивом.", makeDropDown(array("1"=>"DLE Player","2"=>"DleMS Classic", "3"=>"DleMS Modern"), "save_con[mfp_type]", "{$mscfg['mfp_type']}"));
showRow("Формат имени файла при скачивании с музыкального архива", "Введите желаемый формат файла который будет устанавливаться при скачивании его с музыкального архива. Теги: {A} - исполнитель, {T} - название трека. Обратите внимание, исполнитель и название трека печатаются латиницей. Расширение файла к имени добавляется автоматически.", "<input type=text style='text-align: center;' size=40 class=edit name='save_con[downfile_name]' value='{$mscfg['downfile_name']}'>", "");
showRow("Срезать ссылки на сайты в ID3v2 тегах аудио треков", "Если Да то при наличии в ID3v2 теге ссылки на сайт ( .зона[2-4 символа] ) содержимое текущего тега будет удаляться. Если Нет то фильтрация тегов на наличие в них адресов на сайты производиться не будет.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[parse_id3_tags]", "{$mscfg['parse_id3_tags']}"));
showRow("Включить поддержку плеером DleMS Modern визуализаций", "Если Да то при попытке прослушать трек в новом окне помимо панели управления воспроизведением будет отображена визуализация. Данная опция не имеет значения если в качестве плеера выбран не DleMS Modern. <b>Внимание!</b> Так как визуализация генерируется в зависимости от текущего участка трека - создаётся некоторая нагрузка (генерация визуализации по средствам Flash) на процессор посетителя (не на сервер).", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[playning_allow_visual]", "{$mscfg['playning_allow_visual']}"));
showRow("Разрешить отображение обложек альбомов из mp3 файлов", "Если Да то будет включена опция, которая извлекает обложки альбомов из mp3 файлов. В случае если обложка отсутствует будет выведен обычный значёк нотки. * Обложки альбомов отображаются только на странице просмотра трека.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[allow_get_apic]", "{$mscfg['allow_get_apic']}"));
showRow( 'Время жизни прямой ссылки для скачивания', 'Время в секундах спустя которое перестанет действовать ссылка для скачивания для конкретного IP адреса', "<input class=edit type=text style=\"text-align: center;\"  name='save_con[link_time]' value=\"{$mscfg['link_time']}\" size=10>" );
showRow( 'Суточный лимит на скачивание музыкальных альбомов', 'Суточный лимит на скачивание музыкальных альбомов зарегистрированными юзерами', "<input class=edit type=text style=\"text-align: center;\"  name='save_con[24hr_album_limit]' value=\"{$mscfg['24hr_album_limit']}\" size=10>" );

echo "</table></td></tr>";

echo <<<HTML
<tr style='display:none' id="plugins"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Настройка дополнительных плагинов</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;

showRow("Плагины 'Топ артистов' и 'Новинки mp3' (блоки для main.tpl) включены?", "Если Нет то плагины будут отключены, если Да то кол-во запросов в БД увеличивается на +2.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[block_bt]", "{$mscfg['block_bt']}"));
showRow("Количество треков выводимых плагинами -  'Топ артистов' и 'Новинки mp3'", "Введите количество треков которое Вы хотите отображать. Не имеет значения если плагины выключены. Оптимальное значение 10 - 20.", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[block_bt_count]' value='{$mscfg['block_bt_count']}'>", "");
showRow("Плагин - Навигационный алфавит, включен?", "Если Нет то плагин будет отключен.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[allow_letter_navig]", "{$mscfg['allow_letter_navig']}"));
showRow("Отображение английского алфавита + единая ссылка 0-9 (всё что не с русских и английских букв) в плагине - Навигационный алфавит", "Если Нет то английский алфавит не будет отображаться в плагине - Навигационный алфавит.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[letter_navig_english]", "{$mscfg['letter_navig_english']}"));
showRow("Отображение русского алфавита в плагине - Навигационный алфавит", "Если Нет то русский алфавит не будет отображаться в плагине - Навигационный алфавит.", makeDropDown(array("1"=>"Да","0"=>"Нет"), "save_con[letter_navig_russian]", "{$mscfg['letter_navig_russian']}"));
showRow("Количество треков выводимых плагином - 'Скачать другие mp3 треки...'", "Введите количество треков исполнителя которое Вы ещё хотите отображать на странице просмотра трека.", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[limit_tracks_of]' value='{$mscfg['limit_tracks_of']}'>", "");

echo "</table></td></tr>";

echo <<<HTML
<tr style='display:none' id="customize"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Настройки отображения</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;

showRow("Количество треков выводимых на одну страницу", "Введите количество треков выводимых на одну страницу.", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[track_page_lim]' value='{$mscfg['track_page_lim']}'>", "");
showRow("Количество альбомов выводимых на одну страницу", "Введите количество альбомов выводимых на одну страницу на страницах со всеми альбомами исполнителя.", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[album_page_lim]' value='{$mscfg['album_page_lim']}'>", "");
showRow("Количество СТРАНИЦ с выводимыми: 1)треками в ТоПах за разные годы; 2)треками как Новинки mp3; 3)музклипами как Новинки музклипов", "Введите количество страниц.", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[page_lim_main]' value='{$mscfg['page_lim_main']}'>", "");
showRow("Обрезать название трека если больше", "Введите количество символов после которого необходимо обрезать название трека (применимо только для коротких публикаций). Для подмодуля 'Новинки MP3' задается отдельно, прямо в файле newest_tracks.php", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[track_title_substr]' value='{$mscfg['track_title_substr']}'>", "");
showRow("Обрезать название исполнителя если больше", "Введите количество символов после которого необходимо обрезать название исполнителя (применимо только для коротких публикаций). Для подмодуля 'Новинки MP3' задается отдельно, прямо в файле newest_tracks.php", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[track_artist_substr]' value='{$mscfg['track_artist_substr']}'>", "");
showRow("Количество треков выводимых 'Быстрым поиском mp3...'", "Введите количество треков", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[livesearch_track_lim]' value='{$mscfg['livesearch_track_lim']}'>", "");
showRow("Количество треков (альбомов) выводимых на одну страницу в Админке", "Введите количество треков (альбомов) выводимых на одну страницу в Админке", "<input type=text style='text-align: center;' size=10 class=edit name='save_con[admin_track_page_lim]' value='{$mscfg['admin_track_page_lim']}'>", "");

echo "</table></td></tr>";

echo <<<HTML
    <tr>
        <td style="padding-top:10px; padding-bottom:10px;padding-right:10px;"><input type="hidden" name="mod" value="mservice">
    <input type="hidden" name="act" value="saveconfig"><input type="submit" class="buttons" value="  Сохранить и применить настройки  "></td>
    </tr>
</table>
</form>
HTML;

CloseTable( );
echofooter( );

break;

case 'saveconfig' :

$save_con['version'] = "2.0";
$save_con = $save_con + $mscfg;
$handler = fopen( ENGINE_DIR.'/data/mservice.php', "w" );
fwrite( $handler, "<?php\n\$mscfg = array(\n" );
foreach( $save_con as $name => $value ) fwrite( $handler, "'{$name}' => \"{$value}\",\n");
fwrite( $handler, ");\n?>"); fclose( $handler); clear_cache( ); msg("info", "Сохранение настроек", "Настройки были успешно сохранены!
    <br />
    <br /><a href=$PHP_SELF?mod=mservice>Вернуться назад</a>"); break; // _________________________________ Страница со всеми uploaders case 'uploaders' : echoheader( '', '' ); OpenTable( ); EchoTableHeader( 'Статистика uploader-ов', $PHP_SELF . '?mod=mservice' ); echo
    <<<HTML <script language='JavaScript' type="text/javascript">
        <!--
function popupedit(id){
    window.open('{$PHP_SELF}?mod=editusers&action=edituser&id='+id,'User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500');
}
-->
        </script>
        <table width="100%" border="1" class="table">
            <thead>
                <tr class="head">
                    <th width="5%" align="center">User ID</th>
                    <th width="30%" align="center">Пользователь</th>
                    <th width="7%" align="center">Треков</th>
                    <th width="7%" align="center">Альбомов</th>
                    <th width="7%" align="center">Новостей</th>
                    <th width="14%" align="center">Группа</th>
                    <th width="15%" align="center">Зарегистрирован</th>
                    <th width="15%" align="center">Был на сайте</th>
                </tr>
            </thead>
            <tbody>
                HTML; if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] ); $limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim']; $db->query( "SELECT m.uploader, u.name, u.news_num, u.user_group, u.lastdate, u.reg_date, u.banned, COUNT(m.uploader) as count, (SELECT COUNT(DISTINCT uploader) FROM ".PREFIX."_mservice) as cnt, (SELECT COUNT(aid) FROM ".PREFIX."_mservice_albums a WHERE a.uploader=m.uploader) as count2 FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader= u.user_id GROUP BY m.uploader ORDER BY count DESC LIMIT " . $limit . "," . $mscfg['admin_track_page_lim'] ); if ( $db->num_rows( ) == 0 ) { echo
                <<<HTML <tr>
                    <td style="padding-left:10px;" colspan="8">Нет треков пользователей</td>
                    </tr>
        </table>
        HTML; CloseTable( ); echofooter( ); break; } while ( $row = $db->get_row( ) ) { if (!$count) $count = $row['cnt']; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\ "red\" weight=\ "bold\"><b>".$row['name']."</font>"; else $userinfocss = $row['name']; $lastdate = langdate( 'd/m/Y - H:i', $row['lastdate'] ); $reg_date = langdate( 'd/m/Y - H:i', $row['reg_date'] ); if( $row['banned'] == 'yes' ) $user_level = "
        <br /><font color=\ "red\">".$lang['user_ban']."</font>"; else $user_level = ''; if ( $user_group[$row['user_group']]['id'] == 1 OR $user_group[$row['user_group']]['id'] == 2) $group_level = '<font color="red"><b>'.$user_group[$row['user_group']]['group_name'].'</b></font>'; else $group_level = $user_group[$row['user_group']]['group_name']; if ($row['count2'] > 0) $alb_count_upl = "<a href='{$PHP_SELF}?mod=mservice&act=useralbums&uploaderid={$row[uploader]}' title='Смотреть все альбомы {$row[name]}'>{$row[count2]}</a>"; else $alb_count_upl = "0"; echo "
        <tr>
            <td align='center'>{$row[uploader]}</td>
            <td style='padding-left:3px; text-align:center; padding: 6px;'><a href='/user/".urlencode($row[' name '])."/' target='_blank' title='Смотреть профиль {$row[name]}'>{$userinfocss}</a>&nbsp;&nbsp;
                <a class=maintitle onClick=\ "javascript:popupedit('{$row[uploader]}'); return(false)\" href=#><img title='Редактирование пользователя' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></a>
            </td>
            <td align='center'><a href='{$PHP_SELF}?mod=mservice&act=usertracks&uploaderid={$row[uploader]}' title='Смотреть все треки {$row[name]}'>{$row[count]}</a></td>
            <td align='center'>{$alb_count_upl}</td>
            <td align='center'>{$row[news_num]}</td>
            <td align='center'>{$group_level}{$user_level}</td>
            <td align='center'>{$reg_date}</td>
            <td align='center'>{$lastdate}</td>
        </tr>"; } echo
        <<<HTML </tbody>
            </table>
            HTML; // Постраничная навигация $count_d = $count / $mscfg['admin_track_page_lim']; for ( $t = 0; $count_d > $t; $t ++ ) { $t2 = $t + 1; if ( $t2 == $page ) $pages .= "<span>{$t2}</span> "; else $pages .= "<a href='?mod=mservice&act=uploaders&page={$t2}'>{$t2}</a> "; $array[$t2] = 1; } $npage = $page - 1; if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=uploaders&page='.$npage.'">Назад</a> '; else $prev_page = '<span>Назад</span> '; $npage = $page + 1; if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=uploaders&page='.$npage.'">Далее</a>'; else $next_page = ' <span>Далее</span>'; if ( $count > $mscfg['admin_track_page_lim'] ) { echo
            <<<HTML <br />
            <div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
            HTML; } CloseTable( ); echofooter( ); break; // _________________________________ Страница со всеми треками юзера case 'usertracks' : echoheader( '', '' ); OpenTable( ); EchoTableHeader( 'Управление треками юзера', $PHP_SELF . '?mod=mservice&act=uploaders' ); echo
            <<<HTML <script language='JavaScript' type="text/javascript">
                <!--
function ckeck_uncheck_all() {
    var frm = document.mservicemass;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}
function popupedit(id){
    window.open('{$PHP_SELF}?mod=editusers&action=edituser&id='+id,'User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500');
}
-->
                </script>

                <table width="100%" border="0" class="table">
                    <form action="" method="post" name="mservicemass">
                        <input type="hidden" name="act" value="massact" />
                        <thead>
                            <tr class="head">
                                <th style="padding-left:10px;">Заголовок</th>
                                <th width="5%" align="center">из альбома</th>
                                <th width="10%" align="center">Загрузил</th>
                                <th width="9%" align="center">Просмотров</th>
                                <th width="9%" align="center">Скачиваний</th>
                                <th width="10%" align="center">Модерация</th>
                                <th width="4%" align="center">
                                    <input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            HTML; if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] ); $limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim']; $uploaderid = intval( $_REQUEST['uploaderid'] ); $db->query( "SELECT m.mid, m.time, m.title, m.artist, m.view_count, m.approve, m.album, m.download, u.user_group, u.name, (SELECT COUNT(uploader) FROM ".PREFIX."_mservice WHERE uploader = {$uploaderid}) as count FROM " . PREFIX . "_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id WHERE m.uploader = {$uploaderid} ORDER BY m.mid DESC LIMIT $limit,".$mscfg['admin_track_page_lim'] ); if ( $db->num_rows( ) == 0 ) { echo
                            <<<HTML <tr>
                                <td style="padding-left:10px;" colspan="6">Нет треков у этого пользователя или вообще нет такого пользователя!</td>
                                </tr>
                </table>
                HTML; CloseTable( ); echofooter( ); break; } echo "
                <tr>
                    <td></td>
                    <td></td>
                    <td style='text-align:center'>
                        <a class=maintitle onClick=\ "javascript:popupedit('{$uploaderid}'); return(false)\" href=#><img title='Редактирование пользователя' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></a>
                    </td>
                    <td colspan='4'></td>
                </tr>"; while ( $row = $db->get_row( ) ) { if (!$count) $count = $row['count']; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $nameid = '<font color="red"><b>'.$row['name'].'</b></font>'; else $nameid = $row['name']; $date = langdate( 'd.m.Y', $row['time'] ); if ( $row['approve'] == 1 ) $approves = 'Да'; else $approves = '<font color="red"><b>Нет</b></font>'; if ( $row['album'] > 0 ) $in_album = '<img src="engine/skins/images/led_green.gif" align="absmiddle" />'; else $in_album = ''; echo "
                <tr>
                    <td style='padding-left:3px;'>{$date} :: {$row[mid]} :: <a href='{$PHP_SELF}?mod=mservice&act=trackedit&id={$row[mid]}' title='Редактировать публикацию'>{$row[artist]} - {$row[title]}</a></td>
                    <td align='center'>{$in_album}</td>
                    <td align='center'><a href='/user/".urlencode($row[' name '])."/' target='_blank' title='Смотреть профиль {$row[name]}'>{$nameid}</a></td>
                    <td align='center'>{$row[view_count]}</td>
                    <td align='center'>{$row[download]}</td>
                    <td align='center'>{$approves}</td>
                    <td align='center'>
                        <input name='selected_track[]' value='{$row[mid]}' type='checkbox' />
                    </td>
                </tr>"; } echo
                <<<HTML </tbody>
                    </table>
                    <br />
                    <div align="right" style="padding-right:20px;">

                        <select name="action">
                            <option value="">--- Действие ---</option>
                            <option value="1">Отправить на модерацию</option>
                            <option value="2">Снять треки с модерации</option>
                            <option value="3">Очистить счётчик просмотров</option>
                            <option value="4">Очистить рейтинг трека</option>
                            <option value="5">Очистить счётчик скачиваний</option>
                            <option value="6">Установить треку uploader=6</option>
                            <option value="7">Удалить треки из архива</option>

                        </select>
                        <input type="submit" value="  Выполнить  " class="edit" />
                    </div>
                    </form>
                    HTML; // Постраничная навигация $count_d = $count / $mscfg['admin_track_page_lim']; for ( $t = 0; $count_d > $t; $t ++ ) { $t2 = $t + 1; if ( $t2 == $page ) $pages .= "<span>{$t2}</span> "; else $pages .= "<a href='?mod=mservice&act=usertracks&uploaderid={$uploaderid}&page={$t2}'>{$t2}</a> "; $array[$t2] = 1; } $npage = $page - 1; if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=usertracks&uploaderid='.$uploaderid.'&page='.$npage.'">Назад</a> '; else $prev_page = '<span>Назад</span> '; $npage = $page + 1; if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=usertracks&uploaderid='.$uploaderid.'&page='.$npage.'">Далее</a>'; else $next_page = ' <span>Далее</span>'; if ( $count > $mscfg['admin_track_page_lim'] ) { echo
                    <<<HTML <br />
                    <div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
                    HTML; } CloseTable( ); echofooter( ); break; // _________________________________ Страница со всеми треками юзера case 'useralbums' : echoheader( '', '' ); OpenTable( ); EchoTableHeader( 'Управление альбомами юзера', $PHP_SELF . '?mod=mservice', '<a href="'.$PHP_SELF.'?mod=mservice&act=addalbum" style="color:#1E90FF">Добавить новый альбом</a>' ); echo
                    <<<HTML <script type="text/javascript" src="engine/skins/jquery.ezpz_tooltip.min.js">
                        </script>
                        <script language='JavaScript' type="text/javascript">
                            <!--
                            $(document).ready(function() {
                                $(".tooltip-target").ezpz_tooltip({
                                    contentPosition: 'rightStatic',
                                    stayOnContent: true,
                                    offset: 0
                                });
                            });

                            function ckeck_uncheck_all() {
                                var frm = document.mservicemass;
                                for (var i = 0; i < frm.elements.length; i++) {
                                    var elmnt = frm.elements[i];
                                    if (elmnt.type == 'checkbox') {
                                        if (frm.master_box.checked == true) {
                                            elmnt.checked = false;
                                        } else {
                                            elmnt.checked = true;
                                        }
                                    }
                                }
                                if (frm.master_box.checked == true) {
                                    frm.master_box.checked = false;
                                } else {
                                    frm.master_box.checked = true;
                                }
                            }

                            function popupedit(id) {
                                window.open('{$PHP_SELF}?mod=editusers&action=edituser&id=' + id, 'User', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500');
                            }
                            -->

                        </script>

                        <table width="100%" border="1" class="table">
                            <form action="" method="post" name="mservicemass">
                                <input type="hidden" name="act" value="massactalbums" />
                                <thead>
                                    <tr class="head">
                                        <th width="10%" align="center">Дата + aid</th>
                                        <th width="20%" align="center">Название альбома <img title='Редактировать альбом' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></th>
                                        <th width="7%" align="center">Год</th>
                                        <th width="20%" align="center">Исполнитель</th>
                                        <th width="6%" align="center">Жанр</th>
                                        <th width="10%" align="center">Загрузил</th>
                                        <th width="5%" align="center">Треков</th>
                                        <th width="5%" align="center">Моде-рация</th>
                                        <th width="4%" align="center">
                                            <input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    HTML; //approve тут везде пока на будущее, пока что нет модерации у альбомов approve = 1 при добавлении $approve = $_REQUEST['approve']; if ( $approve == '' ) $approve_sql = ''; elseif ( $approve == 0 ) $approve_sql = " AND approve = '0'"; elseif ( $approve == 1 ) $approve_sql = " AND approve = '1'"; if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] ); $limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim']; $uploaderid = intval( $_REQUEST['uploaderid'] ); $db->query( "SELECT a.aid, a.time, a.year, a.album, a.artist, a.approve, a.image_cover, a.genre, a.is_collection, a.collection, a.transartist, a.is_notfullalbum, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE a.uploader = '{$uploaderid}'{$approve_sql}) AS cnt, u.name, u.user_group, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE album = a.aid) AS cnt2 FROM ".PREFIX."_mservice_albums a LEFT JOIN ".USERPREFIX."_users u ON a.uploader = u.user_id WHERE a.uploader = '{$uploaderid}'{$approve_sql} ORDER BY a.aid DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" ); if ( $db->num_rows( ) == 0 ) { echo
                                    <<<HTML <tr>
                                        <td style="padding-left:10px;" colspan="8">Нет альбомов у этого пользователя или вообще нет такого пользователя!</td>
                                        </tr>
                        </table>
                        HTML; CloseTable( ); echofooter( ); break; } echo "
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style='text-align:center'>
                                <a class=maintitle onClick=\ "javascript:popupedit('{$uploaderid}'); return(false)\" href=#><img title='Редактирование пользователя' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></a>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>"; while ( $row = $db->get_row( ) ) { if (!$count) $count = $row['cnt']; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\ "red\" weight=\ "bold\"><b>".$row['name']."</font>"; else $userinfocss = $row['name']; $date = langdate( 'd.m.Y', $row['time'] ); if ( $row['genre'] == '1') $alb_genre = 'Pop'; elseif ( $row['genre'] == '2') $alb_genre = 'Club'; elseif ( $row['genre'] == '3') $alb_genre = 'Rap'; elseif ( $row['genre'] == '4') $alb_genre = 'Rock'; elseif ( $row['genre'] == '5') $alb_genre = 'Шансон'; elseif ( $row['genre'] == '6') $alb_genre = 'OST'; elseif ( $row['genre'] == '7') $alb_genre = 'Metal'; elseif ( $row['genre'] == '8') $alb_genre = 'Country'; elseif ( $row['genre'] == '9') $alb_genre = 'Classical'; elseif ( $row['genre'] == '10') $alb_genre = 'Punk'; elseif ( $row['genre'] == '11') $alb_genre = 'Soul/R&B'; elseif ( $row['genre'] == '12') $alb_genre = 'Jazz'; elseif ( $row['genre'] == '13') $alb_genre = 'Electronic'; elseif ( $row['genre'] == '14') $alb_genre = 'Indie'; else $alb_genre = '<span style="color:#c00;">-</span>'; if ( $row['year'] == '0' OR $row['year'] == '00' OR $row['year'] == '000' OR $row['year'] == '0000' ) $year = '-'; else $year = $row['year']; if ( $row['image_cover'] ) $img_cover = $row['image_cover']; else $img_cover = 'album_0.jpg'; if ( $row['is_collection'] ) { $is_collection = ' <span style="color:#F49804">(сборник)</span>'; if ($row['collection']) $count_alb_tracks = $row['cnt2'] + count(explode( ",", $row['collection'] )); else $count_alb_tracks = $row['cnt2']; } else { $is_collection = ''; $count_alb_tracks = $row['cnt2']; } if ( $row['is_notfullalbum'] == '1') $fullalbum = ' <span style="color:#FF1493;">(неполный альбом)</span>'; else $fullalbum = ''; $userlink = $config['http_home_url']."user/".urlencode($row['name'])."/"; if ( $row['approve'] == 1 ) $approves = 'Да'; else $approves = '<font color="red"><b>Нет</b></font>'; echo "
                        <tr>
                            <td style='padding:0 5px;'>{$date} <a href=\ "/music/{$row[aid]}-album-{$row[transartist]}-".totranslit( $row[ 'album'] ). ".html\" target=\ "_blank\" style=\ "font-weight:bold;\">::</a> <strong>{$row[aid]}</strong></td>
                            <td align='center'><a href='{$PHP_SELF}?mod=mservice&act=albumedit&aid={$row[aid]}' title='Редактировать альбом' class='tooltip-target' id='example-target-{$row[aid]}'>{$row[album]}</a>{$is_collection}{$fullalbum}</td>
                            <td align='center'>{$year}</td>
                            <td align='center'><a href='{$PHP_SELF}?mod=mservice&act=artistalbums&artist={$row[transartist]}' title='Смотреть все альбомы {$row[artist]}' style='font-weight:bold'>{$row[artist]}</a></td>
                            <td align='center'><span class=\ "genre{$row[genre]}\">{$alb_genre}</span></td>
                            <td align='center'><a target='_blank' href='{$userlink}' title='Смотреть альбомы добавленные {$row[name]}'>{$userinfocss}</a></td>
                            <td align='center'>{$count_alb_tracks}</td>
                            <td align='center'>{$approves}</td>
                            <td align='center'>
                                <input name='selected_track[]' value='{$row[aid]}' type='checkbox' />
                            </td>
                        </tr>
                        <div class='tooltip-content' style=\ "padding:0px;\" id='example-content-{$row[aid]}'><img src='/uploads/albums/{$img_cover}'></div>"; } //massactalbums отдельные echo
                        <<<HTML </tbody>
                            </table>
                            <br />
                            <div align="right" style="padding-right:20px;">

                                <select name="action">
                                    <option value="">--- Действие ---</option>
                                    <option value="1">Отправить на модерацию</option>
                                    <option value="2">Снять с модерации</option>
                                    <option value="3">Очистить счётчик просмотров</option>
                                    <option value="4">Очистить рейтинг альбома</option>
                                    <option value="5">Очистить счётчик скачиваний</option>
                                    <option value="6">Установить альбому uploader=10</option>
                                    <option value="7">Удалить альбом из архива</option>
                                </select>

                                <input type="submit" value="  Выполнить  " class="edit" />
                            </div>
                            </form>
                            HTML; // Постраничная навигация $count_d = $count / $mscfg['admin_track_page_lim']; for ( $t = 0; $count_d > $t; $t ++ ) { $t2 = $t + 1; if ( $approve == '' ) $this_approve = ''; elseif ( $approve == 0 ) $this_approve = '&approve=0'; elseif ( $approve == 1 ) $this_approve = '&approve=1'; else $this_approve = ''; if ( $t2 == $page ) $pages .= "<span>{$t2}</span> "; else $pages .= "<a href='?mod=mservice&act=useralbums&uploaderid={$uploaderid}&page={$t2}{$this_approve}'>{$t2}</a> "; $array[$t2] = 1; } $npage = $page - 1; if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=useralbums&uploaderid='.$uploaderid.'&page='.$npage.$this_approve.'">Назад</a> '; else $prev_page = '<span>Назад</span> '; $npage = $page + 1; if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=useralbums&uploaderid='.$uploaderid.'&page='.$npage.$this_approve.'">Далее</a>'; else $next_page = ' <span>Далее</span>'; if ( $count > $mscfg['admin_track_page_lim'] ) { echo
                            <<<HTML <br />
                            <div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
                            HTML; } CloseTable( ); echofooter( ); break; // _________________________________ Страница скачиваний треков и альбомов юзерами case 'downloaders' : echoheader( '', '' ); OpenTable( ); EchoTableHeader( 'Статистика скачиваний юзерами (за неделю)', $PHP_SELF . '?mod=mservice' ); if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = ''; else { $sort = intval( $_REQUEST['sort'] ); $sortpage = '&sort='.$sort; } if ( $sort == 1 ) { $sqlsort = 'count_a_aid DESC, count_d_mid DESC, u.name'; echo '
                            <table width="100%" border="1" class="table2">
                                <tr>
                                    <td style="background:#D4D4D4;">Сортировка:</td>
                                    <td><a href="?mod=mservice&act=downloaders">По кол-ву треков</a></td>
                                    <td style="background:#F8B80C;">По кол-ву альбомов</td>
                                    <td><a href="?mod=mservice&act=downloaders&sort=2">По имени юзера</a></td>
                                </tr>
                            </table>'; } elseif ( $sort == 2 ) { $sqlsort = 'u.name, count_a_aid DESC, count_d_mid DESC'; echo '
                            <table width="100%" border="1" class="table2">
                                <tr>
                                    <td style="background:#D4D4D4;">Сортировка:</td>
                                    <td><a href="?mod=mservice&act=downloaders">По кол-ву треков</a></td>
                                    <td><a href="?mod=mservice&act=downloaders&sort=1">По кол-ву альбомов</a></td>
                                    <td style="background:#F8B80C;">По имени юзера</td>
                                </tr>
                            </table ';
} else {
  $sqlsort = 'count_d_mid DESC, count_a_aid DESC, u.name ';
  echo '<table width="100%" border="1" class="table2">
                            <tr>
                                <td style="background:#D4D4D4;">Сортировка:</td>
                                <td style="background:#F8B80C;">По кол-ву треков</td>
                                <td><a href="?mod=mservice&act=downloaders&sort=1">По кол-ву альбомов</a></td>
                                <td><a href="?mod=mservice&act=downloaders&sort=2">По имени юзера</a></td>
                            </tr>
                            </table>'; } echo
                            <<<HTML <script language='JavaScript' type="text/javascript">
                                <!--
function popupedit(id){
    window.open('{$PHP_SELF}?mod=editusers&action=edituser&id='+id,'User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500');
}
-->
                                </script>
                                <table width="100%" border="1" class="table">
                                    <thead>
                                        <tr class="head">
                                            <th width="5%" align="center">User ID</th>
                                            <th width="37%" align="center">Пользователь</th>
                                            <th width="7%" align="center">Треков</th>
                                            <th width="7%" align="center">Альбомов</th>
                                            <th width="14%" align="center">Группа</th>
                                            <th width="15%" align="center">Зарегистрирован</th>
                                            <th width="15%" align="center">Был на сайте</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        HTML; if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] ); $limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim']; $db->query( "SELECT d.user_id, u.name, u.user_group, u.lastdate, u.reg_date, u.banned, COUNT(DISTINCT d.mid) as count_d_mid, (SELECT COUNT(DISTINCT user_id) FROM ".PREFIX."_mservice_downloads WHERE user_id
                                        <> '0') as cnt_d, (SELECT COUNT(DISTINCT aid) FROM ".PREFIX."_mservice_downloads_albums as a WHERE a.user_id = d.user_id) as count_a_aid FROM ".PREFIX."_mservice_downloads as d LEFT JOIN ".USERPREFIX."_users as u ON d.user_id = u.user_id WHERE d.user_id
                                            <> '0' GROUP BY d.user_id ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['admin_track_page_lim'] ); if ( $db->num_rows( ) == 0 ) { echo
                                                <<<HTML <tr>
                                                    <td style="padding-left:10px; height:60px;" colspan="7">Нет скачанных треков и альбомов у юзеров</td>
                                                    </tr>
                                </table>
                                HTML; CloseTable( ); echofooter( ); break; } while ( $row = $db->get_row( ) ) { if (!$count) $count = $row['cnt_d']; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\ "red\" weight=\ "bold\"><b>".$row['name']."</font>"; else $userinfocss = $row['name']; $lastdate = langdate( 'd/m/Y - H:i', $row['lastdate'] ); $reg_date = langdate( 'd/m/Y - H:i', $row['reg_date'] ); if( $row['banned'] == 'yes' ) $user_level = "
                                <br /><font color=\ "red\">".$lang['user_ban']."</font>"; else $user_level = ''; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $group_level = '<font color="red"><b>'.$user_group[$row['user_group']]['group_name'].'</b></font>'; else $group_level = $user_group[$row['user_group']]['group_name']; if ($row['count_d_mid'] > 0) $link_d_mid = "<a href='/music/monthuserdownloads-{$row[user_id]}.html' target='_blank' title='Смотреть все скачанные треки {$row[name]}'>{$row[count_d_mid]}</a>"; else $link_d_mid = ""; if ($row['count_a_aid'] > 0) $link_a_aid = "<a href='/music/monthuserdownloads-albums-{$row[user_id]}.html' target='_blank' title='Смотреть все скачанные альбомы {$row[name]}'>{$row[count_a_aid]}</a>"; else $link_a_aid = ""; echo "
                                <tr>
                                    <td align='center'>{$row[user_id]}</td>
                                    <td style='text-align:center; padding:5px;'><a href='/user/".urlencode($row[' name '])."/' target='_blank' title='Смотреть профиль {$row[name]}'>{$userinfocss}</a>&nbsp;&nbsp;
                                        <a class=maintitle onClick=\ "javascript:popupedit('{$row[user_id]}'); return(false)\" href=#><img title='Редактирование пользователя' src='{$config[http_home_url]}engine/skins/images/user_edit2.png' border='0'></a>
                                    </td>
                                    <td align='center'>{$link_d_mid}</td>
                                    <td align='center'>{$link_a_aid}</td>
                                    <td align='center'>{$group_level}{$user_level}</td>
                                    <td align='center'>{$reg_date}</td>
                                    <td align='center'>{$lastdate}</td>
                                </tr>"; } echo
                                <<<HTML </tbody>
                                    </table>
                                    HTML; // Постраничная навигация $count_d = $count / $mscfg['admin_track_page_lim']; for ( $t = 0; $count_d > $t; $t ++ ) { $t2 = $t + 1; if ( $t2 == $page ) $pages .= "<span>{$t2}</span> "; else $pages .= "<a href='?mod=mservice&act=downloaders&page={$t2}{$sortpage}'>{$t2}</a> "; $array[$t2] = 1; } $npage = $page - 1; if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=downloaders&page='.$npage.$sortpage.'">Назад</a> '; else $prev_page = '<span>Назад</span> '; $npage = $page + 1; if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=downloaders&page='.$npage.$sortpage.'">Далее</a>'; else $next_page = ' <span>Далее</span>'; if ( $count > $mscfg['admin_track_page_lim'] ) { echo
                                    <<<HTML <br />
                                    <div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
                                    HTML; } CloseTable( ); echofooter( ); break; // _________________________________ Страница скачиваний треков и альбомов по IP-адресам case 'downloadersip' : echoheader( '', '' ); OpenTable( ); EchoTableHeader( 'Статистика скачиваний по IP-адресам (за неделю)', $PHP_SELF . '?mod=mservice' ); if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = ''; else { $sort = intval( $_REQUEST['sort'] ); $sortpage = '&sort='.$sort; } if ( $sort == 1 ) { $sqlsort = 'count_a_aid DESC, count_d_mid DESC, d.ip'; echo '
                                    <table width="100%" border="1" class="table2">
                                        <tr>
                                            <td style="background:#D4D4D4;">Сортировка:</td>
                                            <td><a href="?mod=mservice&act=downloadersip">Статистика треков</a></td>
                                            <td style="background:#F8B80C;">Статистика альбомов</a>
                                            </td>
                                            <td><a href="?mod=mservice&act=downloadersip&sort=2">Статистика по IP</a></td>
                                        </tr>
                                    </table>'; } elseif ( $sort == 2 ) { $sqlsort = 'd.ip, count_d_mid DESC, count_a_aid DESC'; echo '
                                    <table width="100%" border="1" class="table2">
                                        <tr>
                                            <td style="background:#D4D4D4;">Сортировка:</td>
                                            <td><a href="?mod=mservice&act=downloadersip">Статистика треков</a></td>
                                            <td><a href="?mod=mservice&act=downloadersip&sort=1">Статистика альбомов</a></td>
                                            <td style="background:#F8B80C;">Статистика по IP</td>
                                        </tr>
                                    </table>'; } else { $sqlsort = 'count_d_mid DESC, count_a_aid DESC, d.ip'; echo '
                                    <table width="100%" border="1" class="table2">
                                        <tr>
                                            <td style="background:#D4D4D4;">Сортировка:</td>
                                            <td style="background:#F8B80C;">Статистика треков</td>
                                            <td><a href="?mod=mservice&act=downloadersip&sort=1">Статистика альбомов</a></td>
                                            <td><a href="?mod=mservice&act=downloadersip&sort=2">Статистика по IP</a></td>
                                        </tr>
                                    </table>'; } echo
                                    <<<HTML <table width="100%" border="1" class="table" style="word-break:break-all;">
                                        <thead>
                                            <tr class="head">
                                                <th width="10%" align="center">IP</th>
                                                <th width="15%" align="center">Юзер</th>
                                                <th width="8%" align="center">Треков</th>
                                                <th width="8%" align="center">Альбомов</th>
                                                <th width="30%" align="center">User_agent (один из...)</th>
                                                <th width="30%" align="center">Referer (один из...)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            HTML; if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] ); $limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim']; $db->query( "SELECT d.user_id, d.ip as ip, d.user_agent, d.referer, u.name, u.user_group, COUNT(DISTINCT d.mid) as count_d_mid, (SELECT COUNT(DISTINCT ip) FROM ".PREFIX."_mservice_downloads) as cnt_d, (SELECT COUNT(DISTINCT aid) FROM ".PREFIX."_mservice_downloads_albums as a WHERE a.ip = d.ip) as count_a_aid FROM ".PREFIX."_mservice_downloads as d LEFT JOIN ".USERPREFIX."_users as u ON d.user_id = u.user_id GROUP BY d.ip ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['admin_track_page_lim'] ); if ( $db->num_rows( ) == 0 ) { echo
                                            <<<HTML <tr>
                                                <td style="padding-left:10px; height:60px;" colspan="6">Нет скачанных треков и альбомов по IP-адресам</td>
                                                </tr>
                                                </table>
                                                HTML; CloseTable( ); echofooter( ); break; } while ( $row = $db->get_row( ) ) { if (!$count) $count = $row['cnt_d']; if ( $row['user_id'] != '0' AND $row['user_id'] != '' ) { if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $userinfocss = "<font color=\ "red\" weight=\ "bold\"><b>".$row['name']."</font>"; else $userinfocss = $row['name']; if( $row['banned'] == 'yes' ) $user_level = "
                                                <br /><font color=\ "red\">".$lang['user_ban']."</font>"; else $user_level = ''; if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $group_level = '<font color="red"><b>'.$user_group[$row['user_group']]['group_name'].'</b></font>'; else $group_level = $user_group[$row['user_group']]['group_name']; $user = '<a href="/user/'.urlencode($row['name']).'/" target="_blank" title="Смотреть профиль '.$row[name].'">'.$userinfocss.'</a>'; } else $user = ''; echo "
                                                <tr>
                                                    <td style='text-align:center; padding:5px;'>{$row[ip]}</td>
                                                    <td align='center'>{$user}</td>
                                                    <td align='center'>{$row[count_d_mid]}</td>
                                                    <td align='center'>{$row[count_a_aid]}</td>
                                                    <td align='center'>{$row[user_agent]}</td>
                                                    <td align='center'>{$row[referer]}</td>
                                                </tr>"; } echo
                                                <<<HTML </tbody>
                                                    </table>
                                                    HTML; // Постраничная навигация $count_d = $count / $mscfg['admin_track_page_lim']; for ( $t = 0; $count_d > $t; $t ++ ) { $t2 = $t + 1; if ( $t2 == $page ) $pages .= "<span>{$t2}</span> "; else $pages .= "<a href='?mod=mservice&act=downloadersip&page={$t2}{$sortpage}'>{$t2}</a> "; $array[$t2] = 1; } $npage = $page - 1; if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=mservice&act=downloadersip&page='.$npage.$sortpage.'">Назад</a> '; else $prev_page = '<span>Назад</span> '; $npage = $page + 1; if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=mservice&act=downloadersip&page='.$npage.$sortpage.'">Далее</a>'; else $next_page = ' <span>Далее</span>'; if ( $count > $mscfg['admin_track_page_lim'] ) { echo
                                                    <<<HTML <br />
                                                    <div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
                                                    HTML; } CloseTable( ); echofooter( ); break; } ?>
