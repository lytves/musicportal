<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR.'/data/mservice.php';
require_once ENGINE_DIR.'/modules/mservice/functions.php';
include_once ENGINE_DIR.'/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$mtitle = '';
$mcontent = '';
$THEME = $config['http_home_url'].'templates/'. $config['skin'];

// Проверка, включен на модуль?
if ( $mscfg['online'] != 1 ) $stop[] = 'В данный момент музыкальный архив сайта domain.com выключен'; else $stop = array();

switch ( $_REQUEST['act'] ) {

// Новая Главная страница модуля --->  сочетания ТОПов за....
default:

$tpl->load_template( 'mservice/main_mp3.tpl' );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_24hr.php';
$tpl->set ( '{bestmp3_24hr}', $bestmp3_24hr );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_week.php';
$tpl->set ( '{bestmp3_week}', $bestmp3_week );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_month.php';
$tpl->set ( '{bestmp3_month}', $bestmp3_month );

if (($bestmp3_24hr == '') AND ($bestmp3_week == '') AND ($bestmp3_month == '')) { 
	header("location: {$config['http_home_url']}music/new_mp3.html"); 
    exit; 
}

$tpl->compile( 'main_mp3.tpl' );
$mcontent .= $tpl->result['main_mp3.tpl'];
$tpl->result['main_mp3.tpl'] = FALSE;
break;

// Правила сервиса
case 'rules':
$metatags['title'] = 'Правила сайта '.$config['home_title_short'];
$metatags['description'] = 'Правила сайта '.$config['description'];
$metatags['keywords'] = 'правила, права, 2013, 2012, скачать бесплатно mp3, список популярных песен года, скачать лучшие mp3 2013, '.$config['keywords'];

$mtitle .= '<h3 class="tit">Правила сайта (далее Правила)</h3>';
$row = $db->super_query( " SELECT template FROM ".PREFIX."_static WHERE name = 'music/rules' " );
$mcontent .= $row['template'];
break;

// Все треки юзера + переделки в modules/profile.php и шаблон/userinfo
case 'mytracks':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = 'Мои загруженные mp3-файлы';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = $member_id['name'].'. Мои загруженные mp3-файлы '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Мои загруженные mp3-файлы на '.$config['description'];
$metatags['keywords'] = 'мои mp3-файлы, музыкальный архив mp3, '.$config['keywords'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'artist, title';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/mytracks.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'title, artist';
  $sortactive = 'По названию трека &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/mytracks.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = 'По рейтингу &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/mytracks/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/mytracks.html">По дате добавления &#8593;</a></li>';
}
else {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = 'По дате добавления &#8593;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/mytracks/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">По рейтингу &#8595;</a></li>';
}

$mtitle .= '<h3 class="tit"><span>Мои загруженные mp3-файлы</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$db->query( "SELECT mid, time, title, approve, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE uploader = '{$member_id[user_id]}') as cnt FROM ".PREFIX."_mservice WHERE uploader = '{$member_id[user_id]}' ORDER BY ".$sqlsort." LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
$mcontent .= '<br />К сожалению, Вы ещё не добавляли свои mp3-файлы на наш сайт. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить!</a><br />';
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

if ( $db->num_rows() != 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead>
<tr>
<th width="70%" height="20">
HTML;

if ( $db->num_rows() > 1 ) $mcontent .= <<<HTML
<dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl>
HTML;

$mcontent .= <<<HTML
</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr></thead>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {

  if (!isset($count)) $count = $row['cnt'];
  if ($row['approve'] == 1) {
    $tpl->load_template( 'mservice/tracklist.tpl' );
    $tpl->set( '{THEME}', $THEME );
    $tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
    $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
    $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
    $tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
    $tpl->set( '{mid}', $row['mid'] );
    $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
    $tpl->set( '{artistfull}', $row['artist'] );
    $tpl->set( '{titlefull}', $row['title'] );
    $tpl->set( '{lenght}', $row['lenght'] );
    $tpl->set( '{filesize}', formatsize( $row['size'] ) );
    $tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
    $tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

    $tpl->compile( 'tracklist' );
    $mcontent .= $tpl->result['tracklist'];
    $tpl->result['tracklist'] = FALSE;
  } else {
      $tpl->load_template( 'mservice/tracklist_on_moder.tpl' );
      $tpl->set( '{THEME}', $THEME );
      $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
      $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
      $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
      $tpl->set( '{lenght}', $row['lenght'] );
      $tpl->set( '{filesize}', formatsize( $row['size'] ) );
      $tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

      $tpl->compile( 'tracklist' );
      $mcontent .= $tpl->result['tracklist'];
      $tpl->result['tracklist'] = FALSE;
    }
}
$mcontent .= '</table>';
// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mytracks-page-'.$t2.$sortpage.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mytracks'.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mytracks-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mytracks';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
  $mcontent .= '</div>';
}
break;

//Избранные mp3 юзера
case 'myfavtracks':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = 'Мои избранные mp3-файлы';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = $member_id['name'].'. Мои избранные mp3-файлы '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Мои избранные mp3-файлы на '.$config['description'];
$metatags['keywords'] = 'мои любимые mp3-файлы, избранные mp3, музыкальный архив, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>Мои избранные mp3-файлы</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

if ($member_id['favorites_mservice']) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$count = count($list_fav);
	foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		$list_fav_time[] = langdate( 'j F Y, l',substr($value,strpos($value,"-") + 1));
	}
	krsort($list_fav_time);
	$i = ( count($list_fav_time) - 1 - ( ($page - 1) * $mscfg['track_page_lim'] ) );
	$list_fav_mid2 = implode( ",", $list_fav_mid );
} else $list_fav_mid2 = 0;

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online FROM ".PREFIX."_mservice WHERE mid IN (".$list_fav_mid2.") AND approve = '1' ORDER BY FIND_IN_SET(mid, '".$list_fav_mid2."') DESC LIMIT ".$limit."," .$mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
  $mcontent .= 'К сожалению, Вы ещё не добавляли треки в свои избранные mp3-файлы<br />';
  $mcontent .= '<div id="playlist">';

	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;

  $mcontent .= '</div>';
}

else {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr></thead>
<tr>
HTML;
$r = 0;
while ( $row = $db->get_row( ) ) {

  if ($r == 0) $mcontent .= <<<HTML
<td class="titfav">Добавлено: {$list_fav_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
  else {
    if ($list_fav_time[$i] !== $list_fav_time[$i+1]) {
      $mcontent .= <<<HTML
<td class="titfav">Добавлено: {$list_fav_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
    }
  }

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;
$i--;
$r++;
}
$mcontent .= '</table>';

// Постраничная навигация 
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/myfavtracks-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/myfavtracks.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/myfavtracks-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/myfavtracks';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $count > $mscfg['track_page_lim'] ) {
$mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
$tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
else $podmod = TRUE;
} if ($count < 10 ) $podmod = TRUE;
  if ($podmod == TRUE) {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
    $mcontent .= $downloads_top_24hr;
  }
$mcontent .= '</div>';
}
break;

//Избранные исполнители юзера
case 'myfavartists':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = 'Мои любимые исполнители';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = $member_id['name'].'. Мои любимые исполнители '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Мои любимые исполнители на '.$config['description'];
$metatags['keywords'] = 'мои любимые исполнители, музыкальный архив, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>Мои любимые исполнители</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

if ($member_id['favorites_artists_mservice'] == "") {
  $mcontent .= 'К сожалению, Вы ещё не добавляли любимых исполнителей<br />';
  $podmod = TRUE;
} else {
    $list_fav = explode( ",", $member_id['favorites_artists_mservice'] );
    $count = count($list_fav);
    foreach ($list_fav as $value) {
      $list_fav_art[] = 'transartist like \''.$value;
    }
    // следующ.строка - это немного сложная обработка данных из массива для предотвращения ошибок в sql-запросе
    $list_fav_art2 = substr(implode( "' or ", $list_fav_art ), 18);
    
    $db->query( "SELECT DISTINCT artist, transartist, COUNT(artist) as count FROM ".PREFIX."_mservice WHERE transartist like '".$list_fav_art2."' GROUP BY artist ORDER BY artist LIMIT ".$limit."," . $mscfg['track_page_lim'] );

  if ( $db->num_rows() != 0 ) {

  $mcontent .= <<<HTML
<table width="100%">
<thead>
<tr>
<th width="80%" height="20">&nbsp;</th>
<th width="15%">Треков</th>
<th width="5%">&nbsp;</th>
</tr></thead>
HTML;


  while ( $row = $db->get_row( ) ) {
    if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
    else $artist = $row['artist'];

    $tpl->load_template( 'mservice/artistfavlist.tpl' );
    $tpl->set( '{artist}', $artist );
    $tpl->set( '{trekov}', $row['count'] );
    $tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );
    $tpl->set( '{transartist}', $row['transartist'] );
    $tpl->set( '{favartist}', ShowMyFavArtists( $row['transartist'] ) );

    $tpl->compile( 'artistlist' );
    $mcontent .= $tpl->result['artistlist'];
    $tpl->result['artistlist'] = FALSE;

  }
  $mcontent .= '</table>';

// Постраничная навигация 
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/myfavartists-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/myfavartists.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/myfavartists-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/myfavartists';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else $podmod = TRUE;
  }
  if ($count < 10 ) $podmod = TRUE;
  } else {
      $mcontent .= 'К сожалению, Вы ещё не добавляли любимых исполнителей</a><br />';
      $podmod = TRUE;
  }
  }

if ($podmod == TRUE) {
  $mcontent .= '<div id="playlist">';
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
  $mcontent .= $downloads_top_24hr;
  $mcontent .= '</div>';
}
break;

// Страница НОВИНКИ музклипов
case 'new-music-clips':

$nam_e1 = 'Новинки музыкальных клипов';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2;

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Новинки музыкальных клипов '.$metapage.'смотреть на '.$config['home_title_short'];
$metatags['description'] = 'Новинки музыкальных клипов, смотреть онлайн на '.$config['description'];
$metatags['keywords'] = 'музыкальные новинки, новый клип, 2015, 2014, 2013, 2012, 2011, музыкальный клип, смотреть, онлайн, скачать бесплатно, новая музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2015, 2014, 2013, 2012, 2011, клубная новая музыка, хитовые новинки, музклип';

$mtitle .= '<h1 class="tit"><span>Новинки музыкальных клипов</span> на domain.com</h1>';

$db->query( "SELECT mid, title, artist, view_count, clip_online, clip_online_time FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online_time <> '') ORDER BY clip_online_time DESC LIMIT ".$limit.",".$mscfg['album_page_lim']*2 );

if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц с Новинками музыкальных клипов!';

if ( $db->num_rows() == 0 ) $stop[] = 'НЕТ такой страницы с Новинками музыкальных клипов!';

if ( count( $stop ) == 0 ) {

  $mcontent .= '<div id="navigation_up"></div>';
  $mcontent .= '<div class="clips_ramka" style="margin-top:15px;"><table width="100%"><tr>';

  $i = 1;
  while ( $row = $db->get_row( ) ) {
    if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches)) $clip_online_image = 'http://img.youtube.com/vi/'.$matches[1].'/mqdefault.jpg';
    else $clip_online_image = $THEME.'/images/music_clip.jpg';
  
    $mcontent .= '<td width="50%" style="text-align:center; padding:0 20px;" valign="top"><a title="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" class="albums" href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html">'.artistTitleStrlen($row['artist'], $row['title'], ($mscfg['track_title_substr'])).'</a><br /><a href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html" title="Смотреть музыкальный клип '.$row['artist'].' - '.$row['title'].'"><img style="margin:5px 0;" class="clip_online_image" src="'.$clip_online_image.'" style="float:left;" alt="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" title="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" /></a><br /><span style="float:left; padding-left:5px;"><img src="{THEME}/images/eye.png" align="bottom" />&nbsp;'.$row['view_count'].'</span><span style="float:right;"><img src="{THEME}/images/calendar.png" align="top" />&nbsp;'.date( 'd.m.y', $row['clip_online_time'] ).'</style></td>';
    $i++;
    if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2"><div class="line" style="margin:10px 0;"></div></td></tr><tr>';
    if ($db->num_rows() == '1') $mcontent .= '<td>&nbsp;</td>';
  }
  $mcontent .= '</tr></table></div>';
}

// Постраничная навигация к НОВИНКАМ музыкальных клипов, установлена переменная page_lim_main в админке - страниц с НОВИНКАМИ музыкальных клипов
$count_d = $mscfg['page_lim_main'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;

  $plink = $config['http_home_url'].'music/new-music-clips-page-'.$t2.'.html';
  
  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/new-music-clips.html';
  $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
  $array[$t2] = 1;
}

$link = $config['http_home_url'].'music/new-music-clips-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/new-music-clips';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
else $prev_page = '';
$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
else $next_page = '';

$mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;

break;

// Страница со всеми музклипами исполнителя
case 'artistclips':

$nam_e1 = 'Новинки музыкальных клипов';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page  * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim'];

if ( $page == 1 ) $metapage = ', ';
else $metapage = ' (страница '.$page.'), ';

$nameartist = addslashes($parse->process( $_REQUEST['nameartist'] ));

$db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist = '$nameartist' AND approve ='1'" );
while( $row = $db->get_row( ) ) $query_artist[] = $row['artist'];
if (count($query_artist) > 1 ) $artist1 = implode(", ",$query_artist);
else $artist1 = $query_artist[0];

$mtitle .= '<h1 class="tit">Все музыкальные клипы <span><a href="/music/artistracks-'.$nameartist.'.html" title="Все mp3 треки '.$artist1.'">'.$artist1.'</a></span> на domain.com</h1>';

$db->query( "SELECT mid, title, artist, view_count, clip_online, clip_online_time, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online <> '' AND transartist = '$nameartist')) as cnt FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online <> '' AND transartist = '$nameartist') ORDER BY artist, title, clip_online_time DESC LIMIT ".$limit.",".$mscfg['album_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'НЕТ музыкальных клипов этого исполнителя!';

if ( count( $stop ) == 0 ) {

  $metatags['title'] = $artist1.' - cмотреть все музыкальные клипы'.$metapage.'все новые mp3 на '.$config['home_title_short'];
  $metatags['description'] = $artist1.' - cмотреть все музыкальные клипы, все новые mp3 треки на '.$config['description'];
  $metatags['keywords'] = 'скачать все музыкальные треки '.$artist1.' mp3, новые песни, дискография, музыкальный архив, слушать онлайн, бесплатно скачать все песни '.$artist1.', new, скачать мп3 бесплатно, музыка без регистрации, все хиты '.$artist1.' mp3 бесплатно, хорошее качество, новинка, торрент, все музыкальные клипы';

  $mcontent .= '<div id="navigation_up"></div>';
  $mcontent .= '<div class="clips_ramka" style="margin-top:15px;"><table width="100%"><tr>';

  $i = 1;
  while ( $row = $db->get_row( ) ) {
    if (!isset($count)) $count = $row['cnt'];
    
    if (!$row['clip_online_time']) $row['clip_online_time'] = '1420066801';
    if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches)) $clip_online_image = 'http://img.youtube.com/vi/'.$matches[1].'/mqdefault.jpg';
    else $clip_online_image = $THEME.'/images/music_clip.jpg';
  
    $mcontent .= '<td width="50%" style="text-align:center; padding:0 20px;" valign="top"><a title="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" class="albums" href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html">'.artistTitleStrlen($row['artist'], $row['title'], ($mscfg['track_title_substr'])).'</a><br /><a href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html" title="Смотреть музыкальный клип '.$row['artist'].' - '.$row['title'].'"><img style="margin:5px 0;" class="clip_online_image" src="'.$clip_online_image.'" style="float:left;" alt="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" title="Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].'" /></a><br /><span style="float:left;"><img src="{THEME}/images/views.png" align="top" />&nbsp;Просмотров: '.$row['view_count'].'</span><span style="float:right;"><img src="{THEME}/images/calendar.png" align="top" />&nbsp;'.date( 'd.m.y', $row['clip_online_time'] ).'</style></td>';
    $i++;
    if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2"><div class="line" style="margin:10px 0;"></div></td></tr><tr>';
    if (!$tracks_to_view) $tracks_to_view = $count - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
    if ($tracks_to_view == '1') $mcontent .= '<td>&nbsp;</td>';
  }
  $mcontent .= '</tr><tr><td colspan="2"><div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$nameartist.'.html" title="Бесплатно скачать mp3, все музыкальные треки '.$artist1.'">Скачать бесплатно все мп3 треки '.$artist1.'</a></div><div style="clear:both;"></div></td></tr></table></div>';
}

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

// Постраничная навигация к НОВИНКАМ музыкальных клипов, установлена переменная page_lim_main в админке - страниц с НОВИНКАМИ музыкальных клипов
$count_d = $count / ($mscfg['album_page_lim']);
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;
  $plink = $config['http_home_url'].'music/artistclips-'.$nameartist.'-page-'.$t2.'.html';
  
  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artistclips-'.$nameartist.'.html';
  $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
  $array[$t2] = 1;
}

$link = $config['http_home_url'].'music/artistclips-'.$nameartist.'-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artistclips-'.$nameartist.'';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
else $prev_page = '';
$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
else $next_page = '';

if ( $count > $mscfg['album_page_lim'] ) {
  $mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  //выше задаю $tracks_to_view
  //$tracks_to_view = $count - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
  if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
  else {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
    $mcontent .= $downloads_top_week;
  }
}

break;

// Страница НОВИНКИ mp3
case 'new_mp3':

$nam_e1 = 'Новинки mp3';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Новинки mp3 '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Новинки mp3 на '.$config['description'];
$metatags['keywords'] = 'музыкальные новинки 2015, 2014, 2013, 2012, 2011, скачать бесплатно, новая музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2015, 2014, 2013, 2012, 2011, клубная новая музыка, хитовые новинки 2015, 2014, 2013, 2012, 2011';

$mtitle .= '<h1 class="tit"><span>Новинки mp3</span> на domain.com</h1>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE approve = '1' ORDER BY time DESC LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц с Новинками mp3!';

if ( $db->num_rows() == 0 ) $stop[] = 'НЕТ страниц с Новинками mp3!';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );


  $tpl->set( '{THEME}', $THEME );
  $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
  $tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
  $tpl->set( '{mid}', $row['mid'] );
  $tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
  $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
  $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
  $tpl->set( '{lenght}', $row['lenght'] );
  $tpl->set( '{filesize}', formatsize( $row['size'] ) );
  $tpl->set( '{artistfull}', $row['artist'] );
  $tpl->set( '{titlefull}', $row['title'] );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;
}
$mcontent .= '</table></div>';
}

// Постраничная навигация к НОВИНКАМ mp3, установлена переменная page_lim_main в админке - страниц с НОВИНКАМИ mp3
$count_d = $mscfg['page_lim_main'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/new_mp3-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/new_mp3.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/new_mp3-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/new_mp3';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';


  $mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;

if ( count( $stop ) == 0 )  $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;

break;

// Просмотр "полной новости" :))
case 'view':

$mid = intval( $_REQUEST['mid'] );
$row = $db->super_query( "SELECT m.mid, m.time, m.title, m.rating, m.is_demo, m.vote_num, m.artist, m.download, m.description, m.filename, m.hdd, m.size, m.uploader, m.view_count, m.lenght, m.bitrate, m.clip_online, u.name, a.aid as alb_aid, a.year as alb_year, a.album as alb_album, a.artist as alb_artist, a.image_cover as alb_image_cover, a.genre as alb_genre, a.transartist as alb_transartist, a.approve as alb_approve, (SELECT COUNT(aid) FROM ".PREFIX."_mservice_albums WHERE artist = m.artist AND aid != a.aid) as cnt FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id LEFT JOIN ".PREFIX."_mservice_albums a ON m.album = a.aid WHERE m.mid = '$mid' AND m.approve = '1'" );

$art = addslashes( $row['artist'] );

if ( $row['mid'] == FALSE ) {
	$stop[] = 'Запрашиваемый аудио трек не найден. Возможно он ещё не прошёл модерацию, либо его никогда и не существовало, либо он уже удалён! <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';
	$nam_e2 = $nam_e1 = 'domain.com';
	}
else {
	if ( strlen( $nam_e2 = $row['artist'].' - '.$row['title'] ) > 50 ) $nam_e1 = substr( $nam_e2, 0, 50 ).'...'; else $nam_e1 = $nam_e2;
	
  $to_keywords_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " и ", " vs ", " vs. ", " pres ", " pres. ");
  $to_keywords = str_ireplace($to_keywords_array, ", ", $row['artist']);
  $to_keywords2 = str_ireplace(' ', ", ", $row['title']);
}
if ($row['clip_online'] != '') $clip_online_metatags = ', клип';
else $clip_online_metatags = '';
if ( date('Y', $row['time']) == date('Y', time())) {$is_new = 'новая песня'; $is_new2 = ' новый трек,';}

if ( count( $stop ) == 0 ) {
if ($row['is_demo'] == 1) $is_demo = ' (демозапись)'; else $is_demo = '';

if ($member_id['user_id'] != 1017)  $db->query( "UPDATE ".PREFIX."_mservice SET view_count = view_count + 1 WHERE mid = '$mid'" );
//$db->query( "UPDATE ".PREFIX."_mservice SET view_count = view_count + 1 WHERE mid = '$mid'" );
//if ($member_id['user_id'] == 1017) $tpl->load_template( 'mservice/viewtrack_test.tpl' );
//else $tpl->load_template( 'mservice/viewtrack.tpl' );
$tpl->load_template( 'mservice/viewtrack.tpl' );

$subpapka = nameSubDir( $row['time'], $row['hdd'] );
$artist = $row['artist'];
$transartist = totranslit( $artist );

if( $config['allow_banner'] ) {
  include_once ENGINE_DIR . '/modules/banners.php';
  if ($config['skin'] == 'smartphone') $tpl->set( '{adv}', $banners['view-download-smartphone'] );
  else $tpl->set( '{adv}', $banners['view-download'] );
} else $tpl->set( '{adv}', '' );

$tpl->set( '{bitrate}', $row['bitrate'].' Кбит/с' );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{artist3}', '<a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="'.$row['artist'].' скачать все mp3 бесплатно">'.$row['artist'].'</a>' );
$tpl->set( '{title}', $row['title'] );

//             $$$$$$$$$$$$$$$$          ЕСЛИ НЕ СМАРТФОН

$mtitle .= '<h1 class="tit"><span>'.$row['artist'].' - '.$row['title'].$is_demo.'</span> скачать бесплатно mp3</h1>';

$play = $config['http_home_url'].'music/play-'.$mid.'.html';

if ( $mscfg['mfp_type'] == 2 ) $play_hg = 130; else $play_hg = 100;
if ( $mscfg['mfp_type'] == 3 and $mscfg['playning_allow_visual'] == 1 ) $play_hg = 380;

$mcontent .= <<<HTML
<script type="text/javascript">
function reload () {
var rndval = new Date().getTime();
document.getElementById('dle-captcha').innerHTML = '<a onclick="reload(); return false;" href="#" title="Нажмите, если не видно изображения"><img src="{$config[http_home_url]}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="{$lang[sec_image]}" /></a>';
}
function playTrack( ) {
  window.open( "{$play}", "playning", "location=0,status=0,scrollbars=0,width=500,height={$play_hg}" );
}
</script>
HTML;

if ($is_logged) {
  $tpl->set( '{downloadlink}', downloadLinkMservice( $row['filename'], $mscfg['downfile_name'], $row['artist'], $row['title'], $mscfg['link_time'], $subpapka, $row['hdd'] ) );
  $tpl->set( '{favorites}', '<tr><td style="padding-top:3px;"><div class="line"></div><table width="100%" height="35"><tr><td><img src="{THEME}/images/newtrack.png" align="top" /></td><td width="10"></td><td style="white-space:nowrap;">Избранные mp3:</td><td width="30"><div id="favorited-tracks-layer">'.ShowFavorites( $mid ).'</div></td><td width="100"></td>' );
  $tpl->set( '{favorites_art}', '<td><img src="{THEME}/images/favart.png" align="top" /></td><td width="5"></td><td style="white-space:nowrap">Любимый исполнитель:</td><td width="30"><div id="favart-artist">'.showMyFavArtistsView( $transartist ).'</div></td></tr></table></td></tr>' );
} else {
  $tpl->set( '{downloadlink}', '/music/download-mp3-'.$mid.'.html' );
  $tpl->set( '{favorites}', '');
  $tpl->set( '{favorites_art}', '');
}

$tpl->set( '{THEME}', $THEME );

$tpl->set( '{artist}', $row['artist'] );
$tpl->set( '{transartist}', $transartist );
$tpl->set( '{views}', $row['view_count'] );
$tpl->set( '{downcount}', $row['download'] );
$tpl->set( '{viewEdit}', viewEdit( $mid ) );

$tpl->set( '{rating}', showTrackRating( $mid, $row['rating'], $row['vote_num'], 1 ) );
$tpl->set( '{date}', langdate( 'D d F Y - H:i', $row['time'] ) );

$tpl->set( '{uploader}', '<a href="'.$config['http_home_url'].'user/'.urlencode($row['name']).'/">'.$row['name'].'</a>' );

$tpl->set( '{filetype}', strtolower( end( explode( '.', $row['filename'] ) ) ) );
$tpl->set( '{player}', BuildPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], $THEME ) );
$tpl->set( '{play}', '<a href="#" onClick="playTrack( ); return false;">Прослушать в новом окне Flash-плеер</a>' );

if ( $mscfg['allow_get_apic'] == 1 ) {

  include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
  $getID3 = new getID3;
  $track = $getID3->analyze( '/home'.$hdd .'/mp3base/'.$subpapka.'/'.$row['filename'] );
  
  if (isset($track['id3v2']['APIC'][0]['data'])) {
    $viewcover_base64 = base64_encode($track['id3v2']['APIC'][0]['data']);
    $viewcover = <<<HTML
<img src="data:{$track['id3v2']['APIC'][0]['mime']};base64,{$viewcover_base64}" class="track_logo" alt="бесплатно скачать mp3 {$row['artist']} - {$row['title']}" title="скачать бесплатно mp3 {$row['artist']} - {$row['title']}" />
HTML;
    $tpl->set( '{track-logo}', $viewcover);
  }
  else $tpl->set( '{track-logo}', '<img src="'.$config['http_home_url'].'templates/'.$config['skin'].'/images/audio_big.png" class="track_logo" alt="скачать бесплатно mp3 '.$row['artist'].' - '.$row['title'].'" title="бесплатно скачать mp3 '.$row['artist'].' - '.$row['title'].'" />' );
} else $tpl->set( '{track-logo}', '<img src="'.$config['http_home_url'].'templates/'.$config['skin'].'/images/audio_big.png" class="track_logo" alt="скачать бесплатно mp3 '.$row['artist'].' - '.$row['title'].'" title="бесплатно скачать mp3 '.$row['artist'].' - '.$row['title'].'" />' );

if ( $row['description'] != '' ) {
 $tpl->set( '{comment}', $row['description'] );
 $tpl->set_block( "'\\[comment\\](.*?)\\[/comment\\]'si", "\\1" );
} else $tpl->set_block( "'\\[comment\\](.*?)\\[/comment\\]'si", "" );

//отображение альбома,если трек входит в какой-то альбом
if (($row['alb_aid']) and ($row['alb_approve'] == '1')) {
  if ( $row['alb_year'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
  else $year_alb = ' ('.$row['alb_year'].')';
  
  $genreid = $row['alb_genre'];
  if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
  elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
  elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
  elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
  elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
  elseif ($genreid == '6') {$genre1 = 'ost'; $genre = 'OST';}
  elseif ($genreid == '7') {$genre1 = 'metal'; $genre = 'Metal';}
  elseif ($genreid == '8') {$genre1 = 'country'; $genre = 'Country';}
  elseif ($genreid == '9') {$genre1 = 'classical'; $genre = 'Classical';}
  elseif ($genreid == '10') {$genre1 = 'punk'; $genre = 'Punk';}
  elseif ($genreid == '11') {$genre1 = 'soul/r-and-b'; $genre = 'Soul/R&B';}
  elseif ($genreid == '12') {$genre1 = 'jazz'; $genre = 'Jazz';}
  elseif ($genreid == '13') {$genre1 = 'electronic'; $genre = 'Electronic';}
  elseif ($genreid == '14') {$genre1 = 'indie'; $genre = 'Indie';}
  else {$genreid = ''; $genre1 = ''; $genre = '';}
        
  if ($genre1 !== '') {
    $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
    $tpl->set( '{genre_mp3}', '<tr><td style="padding-top:3px;"><table border="0" cellspacing="0" cellpadding="0"><tr><td><img src="{THEME}/images/genre.png" align="top" /> &nbsp; Жанр: &nbsp;</td><td><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a></td></tr></table></td></tr>' );
  }
  else {
    $alb_genre_tab = '';
    $tpl->set( '{genre_mp3}', '' );
  }

  if ($row['alb_image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['alb_image_cover'];
  if (!$row['alb_transartist']) {
    $row['alb_transartist'] = $transartist;
    $row['alb_artist'] = $row['artist'];
  }
  
  $tpl->set( '{album}', '<tr><td height="10"><div class="line" style="margin:5px 0;"></div></td></tr><tr><td style="padding-top:3px;">
  <table width="100%"><tr><td valign="top" style="padding-top:3px;" width="150"><img src="{THEME}/images/viewedit.png" valign="top" /> &nbsp; Трек в альбоме:</td>
    
  <td align="right" valign="top"><a href="/music/'.$row['alb_aid'].'-album-'.$row['alb_transartist'].'-'.totranslit( $row['alb_album'] ).'.html" title="Смотреть альбом '.$row['alb_artist'].' - '.$row['alb_album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:right; margin:0 10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать альбом '.$row['alb_artist'].' - '.$row['alb_album'].'" title="скачать бесплатно альбом '.$row['alb_artist'].' - '.$row['alb_album'].'" /><b>'.$row['alb_artist'].'</b><br />'.$row['alb_album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td></tr></table></td></tr>
  
  <tr><td height="10"><div class="line" style="margin-top:10px;"></div></td></tr>' );

    // ещё альбомы если есть
  $row_alb = $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE artist = '$art' AND aid != '$row[alb_aid]' AND approve = '1' ORDER BY RAND() DESC, album LIMIT 2" );
    if ( $db->num_rows() > 0 ) {
    $toallalbums = '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">Ещё альбомы <font color="#3367AB">'.$artist.'</font></div><table width="100%"><tr>';
     while( $row_alb = $db->get_row() ) {
        $genreid = $row_alb['genre'];
        if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
        elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
        elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
        elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
        elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
        elseif ($genreid == '6') {$genre1 = 'ost'; $genre = 'OST';}
        elseif ($genreid == '7') {$genre1 = 'metal'; $genre = 'Metal';}
        elseif ($genreid == '8') {$genre1 = 'country'; $genre = 'Country';}
        elseif ($genreid == '9') {$genre1 = 'classical'; $genre = 'Classical';}
        elseif ($genreid == '10') {$genre1 = 'punk'; $genre = 'Punk';}
        elseif ($genreid == '11') {$genre1 = 'soul/r-and-b'; $genre = 'Soul/R&B';}
        elseif ($genreid == '12') {$genre1 = 'jazz'; $genre = 'Jazz';}
        elseif ($genreid == '13') {$genre1 = 'electronic'; $genre = 'Electronic';}
        elseif ($genreid == '14') {$genre1 = 'indie'; $genre = 'Indie';}
        else {$genreid = ''; $genre1 = ''; $genre = '';}
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
        if ( $row_alb['year_alb'] == '0' OR $row_alb['year_alb'] == '00' OR $row_alb['year_alb'] == '000' OR $row_alb['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row_alb['year_alb'].')';
        if ($row_alb['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row_alb['image_cover'];
        $toallalbums .= '<td width="50%" valign="top"><a href="/music/'.$row_alb['aid'].'-album-'.$row_alb['transartist'].'-'.totranslit( $row_alb['album'] ).'.html" class="albums" title="Смотреть альбом '.$row_alb['artist'].' - '.$row_alb['album'].'"><img src="/uploads/albums/'.$img_album.'" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать альбом '.$row_alb['artist'].' - '.$row_alb['album'].'" title="скачать бесплатно альбом '.$row_alb['artist'].' - '.$row_alb['album'].'" /><b>'.$row_alb['artist'].'</b><br />'.$row_alb['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
        if ($db->num_rows() == 1) $toallalbums .= '<td width="50%" valign="top"></td>';
      }

      $toallalbums .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist.'.html" title="Смотреть и скачать все альбомы '.$artist.'">Смотреть все альбомы '.$artist.'</a></div><div style="clear:both;"></div></td></div></tr></table></div>';
      $tpl->set( '{allalbums}', '<div class="mservice_viewtrack">'.$toallalbums.'</div>' );
    } else  $tpl->set( '{allalbums}', '<div class="mservice_viewtrack"><div class="allalb" style="margin:0 10px 10px;"><a href="/music/albums-'.$row['alb_transartist'].'.html" title="Скачать бесплатно все mp3 альбомы '.$row['artist'].'">Смотреть все альбомы '.$row['alb_artist'].'</a></div><div style="clear:both;"></div></div>' );
    $meta_album = ' Альбом '.$row['alb_artist'].' - '.$row['alb_album'].$year_alb.' #'.$genre.'.';
} else {
  $meta_album = '';
  $tpl->set( '{album}', '' );
  $tpl->set( '{genre_mp3}', '' );
  // альбомы есть вообще у этого артиста?
  $row_alb = $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE artist = '$art' AND approve = '1' ORDER BY  year DESC, album LIMIT 2" );
  if ( $db->num_rows() > 0 ) {
    $toallalbums = '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">Альбомы <font color="#3367AB">'.$artist.'</font></div><table width="100%"><tr>';
    while( $row_alb = $db->get_row() ) {
        $genreid = $row_alb['genre'];
        if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
        elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
        elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
        elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
        elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
        elseif ($genreid == '6') {$genre1 = 'ost'; $genre = 'OST';}
        elseif ($genreid == '7') {$genre1 = 'metal'; $genre = 'Metal';}
        elseif ($genreid == '8') {$genre1 = 'country'; $genre = 'Country';}
        elseif ($genreid == '9') {$genre1 = 'classical'; $genre = 'Classical';}
        elseif ($genreid == '10') {$genre1 = 'punk'; $genre = 'Punk';}
        elseif ($genreid == '11') {$genre1 = 'soul/r-and-b'; $genre = 'Soul/R&B';}
        elseif ($genreid == '12') {$genre1 = 'jazz'; $genre = 'Jazz';}
        elseif ($genreid == '13') {$genre1 = 'electronic'; $genre = 'Electronic';}
        elseif ($genreid == '14') {$genre1 = 'indie'; $genre = 'Indie';}
        else {$genreid = ''; $genre1 = ''; $genre = '';}
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
        if ( $row_alb['year_alb'] == '0' OR $row_alb['year_alb'] == '00' OR $row_alb['year_alb'] == '000' OR $row_alb['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row_alb['year_alb'].')';
        if ($row_alb['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row_alb['image_cover'];
        $toallalbums .= '<td width="50%" valign="top"><a href="/music/'.$row_alb['aid'].'-album-'.$row_alb['transartist'].'-'.totranslit( $row_alb['album'] ).'.html" class="albums" title="Смотреть альбом '.$row_alb['artist'].' - '.$row_alb['album'].'"><img src="/uploads/albums/'.$img_album.'" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать альбом '.$row_alb['artist'].' - '.$row_alb['album'].'" title="скачать бесплатно альбом '.$row_alb['artist'].' - '.$row_alb['album'].'" /><b>'.$row_alb['artist'].'</b><br />'.$row_alb['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
        if ($db->num_rows() == 1) $toallalbums .= '<td width="50%" valign="top"></td>';
      }

      $toallalbums .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist.'.html" title="Смотреть и скачать все альбомы '.$artist.'">Смотреть все альбомы '.$artist.'</a></div><div style="clear:both;"></div></td></div></tr></table></div>';
      $tpl->set( '{allalbums}', '<div class="mservice_viewtrack">'.$toallalbums.'</div>' );
  } else $tpl->set( '{allalbums}', '' );
}

$metatags['title'] = $nam_e2.' скачать бесплатно mp3. '.$nam_e2.' слушать онлайн '.$is_new.$clip_online_metatags.' | domain.com';
$metatags['description'] = 'Скачать бесплатно '.$nam_e2.' mp3.'.$meta_album.' Прямая ссылка, слушать онлайн '.$nam_e2.$clip_online_metatags.', '.$is_new.', хорошее качество';
$metatags['keywords'] = $to_keywords.', '.$to_keywords2.', скачать бесплатно,'.$is_new2.' mp3'.$clip_online_metatags.', новинка, слушать онлайн, мп3, музыка, хорошее качество, песня, прямая ссылка';

// отображение онлайн клипа, задаётся в админке
if ($row['clip_online'] != '') {
  $tpl->set( '{clip-title}', '<span class="tit" style="display:block; margin-bottom: 10px;">Смотреть онлайн клип  <span style="font-weight:bold;">'.$row['artist'].' - '.$row['title'].'</span>:</span>');
  //здесь отключен вывод рекламного блока на видеоклипе ====== нужны изменения косметические
  /*if (strpos($row['clip_online'],'560')) {
     $tpl->set( '{clip-title}', '<div class="mservice_viewtrack" style="margin-bottom:8px"><b>Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].':</b></div>
<script type="text/javascript">
    teasernet_blockid = 545188;
    teasernet_padid = 105877;
</script>
<script type="text/javascript" src="http://medigaly.com/inc/angular.js"></script>' );
    }
  else $tpl->set( '{clip-title}', '<div class="mservice_viewtrack" style="margin-bottom:8px"><b>Смотреть онлайн клип '.$row['artist'].' - '.$row['title'].':</b></div>
<script type="text/javascript">
    teasernet_blockid = 545183;
    teasernet_padid = 105877;
</script>
<script type="text/javascript" src="http://medigaly.com/inc/angular.js"></script>' );*/
  $tpl->set( '{clip-online}', '<div id="clip-online" style="margin-bottom:10px;"><center>'.$row['clip_online'].'</center></div>' );
  $tpl->set( '{clip-end}', '<div class="allcli" style="margin:10px;"><a href="/music/artistclips-'.$transartist.'.html" title="Бесплатно смотреть все музыкальные клипы '.$row['artist'].'">Смотреть все клипы '.$artist.'</a></div><div style="clear:both;"></div>' );
}
else {
    $tpl->set( '{clip-title}', '' );
    $tpl->set( '{clip-online}', '' );
    $tpl->set( '{clip-end}', '' );
}

///////////////// + показ ещё треков исполнителя (кол-во $mscfg['limit_tracks_of' задаётся в админке) + доделки + добавлена ссылка на "все треки исполнителя case 'artistracks'"

// Новое условие на вхождение  точек больше одной
	$dotcount = substr_count($row['artist'], ".");
	
	if ($dotcount > 1) $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE '$art%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0," . $mscfg['limit_tracks_of'] );
//частный случай для исполнителя w&w
  elseif ($row['artist'] == 'W&W')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'W&W%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//частный случай для исполнителя g&g
  elseif ($row['artist'] == 'g&g')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'g&g%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//частный случай для исполнителя A*M*E
  elseif ($row['artist'] == 'A*M*E')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'a*m*e%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//частный случай для исполнителя M.O
  elseif ($row['artist'] == 'M.O')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'M.O%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//===============         Общий случай показа ещё треков исполнителя
	else {
		$sqlartist = '"'.$art.'"';
		$db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
	}
	
	if ( $db->num_rows( ) >= 5 ) {
		$alltrackslink = '<div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="Бесплатно скачать mp3, все музыкальные треки '.$row['artist'].'">Скачать бесплатно все мп3 треки '.$artist.'</a></div><div style="clear:both;"></div>';
        
		while( $row = $db->get_row( ) ) {
			$more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="Скачать бесплатно мп3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
		}
    $tpl->set( '{more-tracks}', $more_tracks );
    $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">Скачать другие mp3 треки <span style="font-weight:bold;">'.$artist.'</span>:</span><br />' );
    $tpl->set( '{more-tracks-end}', '</div>' );
	} elseif ( ($db->num_rows( ) < 5 ) AND ($db->num_rows( ) > 0) ) {
    $alltrackslink = '<div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="Бесплатно скачать mp3, все музыкальные треки '.$row['artist'].'">Скачать бесплатно все мп3 треки '.$artist.'</a></div><div style="clear:both;"></div>';
        
		while( $row = $db->get_row( ) ) {
			$more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="Скачать бесплатно мп3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
			$mid_tracks[] = $row['mid'];
		}

    $parts_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " и ", " vs ", " vs. ", " pres ", " pres. ", ", ");
    $tempartists = str_ireplace($parts_array, "$$$$$", $artist);
    $artists_array = explode( "$$$$$", $tempartists );
    
    if ( ($tempartists != $artist) AND (count($artists_array) > 1) ) {
      $sqlartist1 = '("'.addslashes($artists_array[0]).'")';
      for($i=1; $i<count($artists_array); $i++) {
        $sqlartist1 .= ' ("'.addslashes($artists_array[$i]).'")';
     }

     $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$sqlartist1."' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );

     while( $row = $db->get_row( ) ) {
      if (!in_array($row['mid'], $mid_tracks)) $more_tracks2 .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="Скачать бесплатно мп3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
     }
    }
    if ( $more_tracks2 != '' ) {
    $more_tracks = $more_tracks.$more_tracks2;
    $artists_tmp = str_replace("$$$$$", ", ", $tempartists);
    } else $artists_tmp = $artist;
    $tpl->set( '{more-tracks}', $more_tracks );
    $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">Скачать другие mp3 треки <span style="font-weight:bold;">'.$artists_tmp.'</span>:</span><br />' );
    $tpl->set( '{more-tracks-end}', '</div>' );

	} else {
    $parts_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " и ", " vs ", " vs. ", " pres ", " pres. ", ", ");
    $tempartists = str_ireplace($parts_array, "$$$$$", $artist);
    $artists_array = explode( "$$$$$", $tempartists );
    
    if ( ($tempartists != $artist) AND (count($artists_array) > 1) ) {
      $sqlartist1 = '("'.addslashes($artists_array[0]).'")';
      for($i=1; $i<count($artists_array); $i++) {
        $sqlartist1 .= ' ("'.addslashes($artists_array[$i]).'")';
      }

      $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$sqlartist1."' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
      if ( $db->num_rows( ) > 0 ) {
        while( $row = $db->get_row( ) ) {
          $more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="Скачать бесплатно мп3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
        }
        $tpl->set( '{more-tracks}', $more_tracks );
        $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">Скачать другие mp3 треки <span style="font-weight:bold;">'.str_replace("$$$$$", ", ", $tempartists).'</span>:</span><br />' );
        $tpl->set( '{more-tracks-end}', '</div>' );
      } else  {
        $tpl->set( '{more-tracks}', '' );
        $tpl->set( '{title-more-tracks}', '<div style="margin:10px 0;">К сожалению, у <b>'.$artist.'</b> больше нет треков на нашем сайте.</div>' );
        $tpl->set( '{more-tracks-end}', '' );
      }

    } else {
      $tpl->set( '{more-tracks}', '' );
      $tpl->set( '{title-more-tracks}', '<span class="tit" style="display:block;">Скачать другие mp3 треки <span style="font-weight:bold;">'.$artist.'</span>:</span><div style="margin:10px 0;">К сожалению, у <b>'.$artist.'</b> больше нет треков на нашем сайте.</div>' );
      $tpl->set( '{more-tracks-end}', '' );
    }
	}
  if ($toptracks) $tpl->set( '{top-tracks}', $toptracks ); else $tpl->set( '{top-tracks}', '' );
  $tpl->set( '{alltrackslink}', $alltrackslink );

$tpl->compile( 'viewtrack' );

$tpl->load_template( 'mservice/commentit.tpl' ); $tpl->compile( 'commentit' );
$mcontent .= $tpl->result['viewtrack'];
$mcontent .= $tpl->result['commentit'];
$tpl->result['viewtrack'] = FALSE;

}
break;

// СТРАНИЦа со всеми треками исполнителя $nameartist
case 'artistracks':

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
if (($limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim']) < 0 ) {
  $limit = 0;
  $page = 1;
}

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$nameartist = addslashes($parse->process( $_REQUEST['nameartist'] ));

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'title, artist';
  $sortactive = 'По названию трека &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">По исполнителю &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = 'По дате добавления &#8593;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">По исполнителю &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">По рейтингу &#8595;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = 'По рейтингу &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">По исполнителю &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">По дате добавления &#8593;</a></li>';
}
else {
  $sqlsort = 'artist, title';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">По дате добавления &#8593;</a></li>';
}

$db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist = '$nameartist' AND approve ='1'" );

if ( $db->num_rows( ) == 0 ) {

  $db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist LIKE '%$nameartist%' AND approve ='1'" );
  
  if ( $db->num_rows( ) == 0 ) {
  	$metatags['title'] = 'Нет треков этого исполнителя на '.$config['home_title_short'];
    $stop[] = 'К сожалению, на нашем сайте ещё нет сольных треков этого исполнителя. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить!</a>';
  } else {
    $ok_result = TRUE;
    $ok_result2 = TRUE;
  }
} else $ok_result = TRUE;
	
if ($ok_result) {
  if ($ok_result2) $query_artist[] = str_replace("-"," ",$nameartist);
  else while( $row = $db->get_row( ) ) $query_artist[] = $row['artist'];

  if (count($query_artist) == 1 ) {
    $artist1 = $artist2 = $query_artist[0];
    $sqlartist = addslashes($artist1);
    $sqlartist = '"'.$sqlartist.'"';
    /// Новое условие на вхождение  точек больше одной
    $dotcount = substr_count($query_artist[0], ".");
    if ($dotcount > 1) $manydots = TRUE;
  } else {
    /// Новое условие на вхождение  точек больше одной
    $dotcount = substr_count($query_artist[0], ".");
    if ($dotcount > 1) {
        $manydots = TRUE;
        $artist2 = addslashes($query_artist[0]);
        for($i=1; $i<count($query_artist); $i++) {
            $artist3.= " OR artist LIKE '".addslashes($query_artist[$i])."%'";
        }
    } else {
      $manyartists = TRUE;
      $artist2 = '("'.addslashes($query_artist[0]).'")';
      for($i=1; $i<count($query_artist); $i++) {
        $artist2 .= ' ("'.addslashes($query_artist[$i]).'")';
      }
    }
    $artist1 = implode(", ",$query_artist);    
  }
if ( strlen( $artist1 ) < 41 ) {
	$nam_e1 = $artist1;
	$nam_e2 = 'cкачать бесплатно все mp3 треки, слушать онлайн, прямые ссылки';
}
elseif ( strlen( $artist1 ) < 50 ) {
	$nam_e1 = substr( $artist1, 0, 55 );
	$nam_e2 = 'cкачать бесплатно все mp3 треки, слушать онлайн';
}
else {
	$nam_e1 = substr( $artist1, 0, 50 ).'...';
	$nam_e2 = 'cкачать бесплатно все mp3 треки, слушать онлайн';
}

$metatags['title'] = $artist1.' - cкачать бесплатно все mp3 треки, все новые песни '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = $artist1.' - cкачать бесплатно все песни, все новые треки на '.$config['description'];
$metatags['keywords'] = 'скачать все музыкальные треки '.$artist1.' mp3, новые песни, дискография, музыкальный архив, слушать онлайн, бесплатно скачать все песни '.$artist1.', new, скачать мп3 бесплатно, музыка без регистрации, все хиты '.$artist1.' mp3 бесплатно, хорошее качество, новинка, торрент';

// выводим обложку исполнителя и описание
$art_artist1 = addslashes($artist1);
$row2 = $db->super_query( "SELECT image_cover, genre, description FROM ".PREFIX."_mservice_artists WHERE artist = '$art_artist1'" );

if ($row2['genre']) {
  list ($genre1_art, $genre_art) = musicGenre($row2['genre']);
          
  if ($genre1_art !== '') $art_genre_tab = '<a class="genre'.$row2['genre'].'" href="/music/genre-albums-'.$genre1_art.'.html">#'.$genre_art.'</a>';
  else $art_genre_tab = '';
}
if ($row2['image_cover']) $mtitle .= '<table width="100%"><tr><td><h1 class="tit"><font color="#3367AB">'.$artist1.' '.$art_genre_tab.'</font></h1></td></tr><tr><td style="text-align:center; padding:5px 0;"><img src="/uploads/artists/'.$row2['image_cover'].'" style="border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать все мп3 песни '.$row['artist'].'" title="бесплатно скачать все мп3 песни '.$row['artist'].'" /></td></tr></table>';

if ($row2['description']) $mtitle .= '<table width="100%"><tr><td style="padding:10px 20px;">'.$row2['description'].'</td></tr></table>';

if ($row2['image_cover'] OR $row2['description']) $mtitle .= '<table width="100%" style="margin-bottom:10px;"><tr><td style="padding:10px 0 10px 20px;">Поделиться:</td><td width="10"></td><td>

</td><td width="20"></td><td>

</td></tr></table>';

// альбомы
	$addslashesartist = addslashes($query_artist[0]);
	if (count($query_artist) > 1 ) {
    for($i = 1; $i < count($query_artist); $i++) {
      $artist3.= " OR artist = '".addslashes($query_artist[$i])."'";
    }
  }
  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1' ORDER BY year_alb DESC, album LIMIT 2" );
  
  if ( $db->num_rows() > 0 ) {
    $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">... а также альбомы <font color="#3367AB">'.$artist1.'</font></div><table width="100%"><tr>';
    while( $row = $db->get_row() ) {
        $genreid = $row['genre'];
        if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
        elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
        elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
        elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
        elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
        elseif ($genreid == '6') {$genre1 = 'ost'; $genre = 'OST';}
        elseif ($genreid == '7') {$genre1 = 'metal'; $genre = 'Metal';}
        elseif ($genreid == '8') {$genre1 = 'country'; $genre = 'Country';}
        elseif ($genreid == '9') {$genre1 = 'classical'; $genre = 'Classical';}
        elseif ($genreid == '10') {$genre1 = 'punk'; $genre = 'Punk';}
        elseif ($genreid == '11') {$genre1 = 'soul/r-and-b'; $genre = 'Soul/R&B';}
        elseif ($genreid == '12') {$genre1 = 'jazz'; $genre = 'Jazz';}
        elseif ($genreid == '13') {$genre1 = 'electronic'; $genre = 'Electronic';}
        elseif ($genreid == '14') {$genre1 = 'indie'; $genre = 'Indie';}
        else {$genreid = ''; $genre1 = ''; $genre = '';}
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
      if (!$cnt_alb) $cnt_alb = $row['cnt'];
      if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
      else $year_alb = '('.$row['year_alb'].')';
      if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
      $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.$row['artist'].'</b><br />'.$row['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
    }
    if ( $cnt_alb == 1 ) $mcontent .= '<td width="50%" valign="top"></td>';
    elseif ( $cnt_alb > 2 ) $mcontent .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$nameartist.'.html" title="Смотреть и скачать бесплатно все альбомы '.$artist1.'">Смотреть все альбомы '.$artist1.'</a></div><div style="clear:both;"></div></td></div>';
    $mcontent .= '</tr></table></div>';
	}
	$db->free( );
// конец альбомов

if ($manydots == TRUE) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE artist LIKE '$artist2%'$artist3 AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE artist LIKE '$artist2%'$artist3 AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
} elseif ($manyartists == TRUE) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$artist2."' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$artist2."' IN BOOLEAN MODE) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
} else {
  //частный случай для исполнителя w&w
  if ($nameartist == 'ww') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // конец частного случая

  //частный случай для исполнителя g&g
  elseif ($nameartist == 'gg') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // конец частного случая

  //частный случай для исполнителя A*M*E
  elseif ($nameartist == 'ame') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // конец частного случая
  
    //частный случай для исполнителя 4'k
  elseif ($nameartist == '4039k') {
    $artist2 = addslashes($query_artist[0]);
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // конец частного случая
  
  //частный случай для исполнителя M.O
  elseif ($nameartist == 'm.o') {
    $artist2 = addslashes($query_artist[0]);
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // конец частного случая

  // ====================  ОБЩИЙ  случай  ПОИСКа    треков   ИСПОЛНИТЕЛЕЙ
  else {
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
}

if ( $db->num_rows() == 0 ) $stop[] = 'Неверная страница с треками '.$artist1.'! <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';

if ( count( $stop ) == 0 ) {
  
	if ( $is_logged ) $mcontent .= '<table width="100%" height="35"><tr><td width="30%">&nbsp;</td><td width="40%"><div class="favartclass"><img src="{THEME}/images/favart.png" align="top" />Любимый исполнитель:</div></td><td><div id="favart-artist">'.showMyFavArtistsView( $nameartist ).'</div></td></tr></table>';

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<tr>
<th width="70%" height="20">
HTML;

if ( $db->num_rows() > 1 ) $mcontent .= <<<HTML
<dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl>
HTML;

$mcontent .= <<<HTML
</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) { 
	$count = $row['cnt'];
	$mtitle .= '<h1 class="tit">Все mp3 треки <font color="#3367AB">'.$artist1.'</font>. Найдено '.declension($count, array('трек', 'трека', 'треков')).'</h1>';
	$mcontent .= '<div id="navigation_up"></div>';

}

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );

$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;
}
$mcontent .= '</table>';

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/artistracks-'.$nameartist.'-page-'.$t2.$sortpage.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artistracks-'.$nameartist.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/artistracks-'.$nameartist.'-page-';
  $seo_mode = $sortpage.'.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artistracks-'.$nameartist;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $count > $mscfg['track_page_lim'] ) {
  $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
  if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
  else {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
    $mcontent .= $downloads_top_week;
  }
}
$mcontent .= '</div>';
}
}
break;

// Страница скачивания треков для незареганных юзеров по прямой ссылке для определённого IP с лимитом времени жизни ссылки
case 'download':

$mid = intval( $_REQUEST['mid'] );
$row = $db->super_query( "SELECT mid, time, artist, title, filename, hdd FROM ".PREFIX."_mservice WHERE mid = '$mid' AND approve = '1'" );
if ( $row['mid'] == FALSE ) $stop[] = 'Запрашиваемый аудио трек не найден, возможно его не когда не существовало, либо он был удалён! <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';

if ( strlen( $nam_e2 = $row['artist'].' - '.$row['title'] ) > 65 ) $nam_e1 = substr( $nam_e2, 0, 65 ).'...'; else $nam_e1 = $nam_e2;

     $metatags['title'] = $nam_e2.' mp3 страница скачивания файла';
     $metatags['description'] = 'Страница скачивания с прямой ссылкой на файл: '.$nam_e2.' mp3, без ожидания и без регистрации.';
     $metatags['keywords'] = 'скачать бесплатно, mp3 '.$nam_e2.', мп3 музыку скачать бесплатно, новинка, прямая ссылка, download mp3, new, '.$nam_e2;

if ( count( $stop ) == 0 ) {

$mtitle .= '<h3 class="tit">Скачивание <span>'.$row['artist'].' - '.$row['title'].'</span></h3>';

$play = $config['http_home_url'].'music/play-'.$mid.'.html';

if ( $mscfg['mfp_type'] == 2 ) $play_hg = 130; else $play_hg = 100;
if ( $mscfg['mfp_type'] == 3 and $mscfg['playning_allow_visual'] == 1 ) $play_hg = 380;

$mcontent .= <<<HTML
<script type="text/javascript">
function playTrack( ) {
  window.open( "{$play}", "playning", "location=0,status=0,scrollbars=0,width=500,height={$play_hg}" );
}
</script>
HTML;

if( ! $is_logged ) $member_id['user_group'] = 5;
if ( $user_group[$member_id['user_group']]['mservice_filedown'] != 1 ) $stop[] = 'У Вас не достаточно прав для скачивания файлов!';

$subpapka = nameSubDir( $row['time'], $row['hdd'] );
$transartist = totranslit( $row['artist']);
		
$tpl->load_template( 'mservice/download.tpl' );

$tpl->set( '{THEME}', $THEME );
$tpl->set( '{title}', $row['title'] );
$tpl->set( '{artist}', $row['artist'] );
$tpl->set( '{alltrackslink}', $config['http_home_url'].'music/artistracks-'.$transartist.'.html' );
$tpl->set( '{alltracks}', 'скачать бесплатно все mp3 треки '.$row['artist'] );
$tpl->set( '{view}', '/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{player}', BuildPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], $THEME ) );
$tpl->set( '{play}', '<a href="#" onClick="playTrack( ); return false;">Прослушать в новом окне Flash-плеер</a>' );
$tpl->set( '{linktime}', (intval($mscfg['link_time']/3600)));
$tpl->set( '{link}', downloadLinkMservice( $row['filename'], $mscfg['downfile_name'], $row['artist'], $row['title'], $mscfg['link_time'], $subpapka, $row['hdd'] ) );

if( $config['allow_banner'] ) {
  include_once ENGINE_DIR . '/modules/banners.php';
  if ($config['skin'] == 'smartphone') $tpl->set( '{adv}', $banners['view-download-smartphone'] );
  else $tpl->set( '{adv}', $banners['view-download'] );
} else $tpl->set( '{adv}', '' );

if( $is_logged ) $tpl->set( '{link_to_register}', '' );
else $tpl->set( '{link_to_register}', '<a href="#virtual_loginform" class="lbn" style="margin:10px 0 10px 270px;" rel="facebox"><b style="background-position: 100% -215px; padding: 0 10px;">Вход / Регистрация</b></a><div style="clear:both;"></div>' );

$tpl->compile( 'download' );
$mcontent .= $tpl->result['download'];
$tpl->result['download'] = FALSE;

include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
$mcontent .= $downloads_top_week;

}
break;

// !!! Навигационный алфавит по ИСПОЛНИТЕЛЯМ (artist)
case 'artist':

if ($letter == "Й") header("location: {$config['http_home_url']}music/artist-%C8.html"); 
elseif ($letter == "Ё") header("location: {$config['http_home_url']}music/artist-%C5.html");
elseif ($letter == "Ъ") header("location: {$config['http_home_url']}music/artist-0-9.html");
elseif ($letter == "Ы") header("location: {$config['http_home_url']}music/artist-0-9.html");
elseif ($letter == "Ь") header("location: {$config['http_home_url']}music/artist-0-9.html"); 

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$letter = $parse->process( $_REQUEST['letter'] );

if ($letter == "0-9") {
	$letter = "[^а-яёa-z]";
	$letter2 = "OR artist REGEXP '^Ъ' OR artist REGEXP '^Ы' OR artist REGEXP '^Ь'";
}
elseif ($letter == "И") {
	$letter2 = "OR artist REGEXP '^Й'";
	$letter3 = ", Й";
}
elseif ($letter == "Е") {
	$letter2 = "OR artist REGEXP '^Ё'";
	$letter3 = ", Ё";
}

if ($letter == "[^а-яёa-z]") {
$mtitle .= '<h3 class="tit">Все <span>исполнители</span> на <span>цифру (символ), Ъ, Ы, Ь</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$metatags['title'] = 'Алфавитный указатель исполнителей на цифру (символ), Ъ, Ы, Ь '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Алфавитный указатель исполнителей на цифру (символ), Ъ, Ы, Ь на '.$config['description'];
$metatags['keywords'] = 'mp3 треки, скачать песни исполнителей на буквы ъ ы ь, цифры 0 1 2 3 4 5 6 7 8 9, бесплатно и без регистрации, new, музыкальный архив мп3, все mp3 треки на цифры, новые, скачать бесплатно мп3, много mp3 песен бесплатно, новинка, торрент';
} else {
$mtitle .= '<h3 class="tit">Все <span>исполнители</span> на букву - <span>'.$letter.$letter3.'</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$metatags['title'] = 'Алфавитный указатель исполнителей на букву '.$letter.$letter3.' '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Алфавитный указатель исполнителей на букву '.$letter.$letter3.' на '.$config['description'];
$metatags['keywords'] = 'mp3 треки, скачать песни исполнителей на букву '.$letter.$letter3.', new, бесплатно и без регистрации, новые, музыкальный архив мп3, все mp3 треки на букву '.$letter.$letter3.', скачать бесплатно мп3, много mp3 песен бесплатно, новинка, торрент';
}

$db->query( "SELECT artist, transartist, COUNT(artist) as count, (SELECT COUNT(DISTINCT artist) FROM ".PREFIX."_mservice WHERE (artist REGEXP '^$letter' $letter2)) as cnt FROM ".PREFIX."_mservice WHERE (artist REGEXP '^$letter' $letter2) GROUP BY artist LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
	if ($letter == '[^а-яёa-z]') $letter = '0-9';
	$stop[] = 'Не найден ни один исполнитель на букву (цифру) - "'.$letter.'".!';
}

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table width="100%">
<thead><tr>
<th width="80%" height="20">&nbsp;</th>
<th width="20%">Треков</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) $count = $row['cnt'];
if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
else $artist = $row['artist'];

$tpl->load_template( 'mservice/artistlist.tpl' );
$tpl->set( '{artist}', $artist );
$tpl->set( '{trekov}', $row['count'] );
$tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );

$tpl->compile( 'artistlist' );
$mcontent .= $tpl->result['artistlist'];
$tpl->result['artistlist'] = FALSE;

}
$mcontent .= '</table>';

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];

if ($letter == 'А') { $letter = '%C0';
} elseif ($letter == 'Б') { $letter = '%C1';
} elseif ($letter == 'В') { $letter = '%C2';
} elseif ($letter == 'Г') { $letter = '%C3';
} elseif ($letter == 'Д') { $letter = '%C4';
} elseif ($letter == 'Е') { $letter = '%C5';
} elseif ($letter == 'Ж') { $letter = '%C6';
} elseif ($letter == 'З') { $letter = '%C7';
} elseif ($letter == 'И') { $letter = '%C8';
} elseif ($letter == 'К') { $letter = '%CA';
} elseif ($letter == 'Л') { $letter = '%CB';
} elseif ($letter == 'М') { $letter = '%CC';
} elseif ($letter == 'Н') { $letter = '%CD';
} elseif ($letter == 'О') { $letter = '%CE';
} elseif ($letter == 'П') { $letter = '%CF';
} elseif ($letter == 'Р') { $letter = '%D0';
} elseif ($letter == 'С') { $letter = '%D1';
} elseif ($letter == 'Т') { $letter = '%D2';
} elseif ($letter == 'У') { $letter = '%D3';
} elseif ($letter == 'Ф') { $letter = '%D4';
} elseif ($letter == 'Х') { $letter = '%D5';
} elseif ($letter == 'Ц') { $letter = '%D6';
} elseif ($letter == 'Ч') { $letter = '%D7';
} elseif ($letter == 'Ш') { $letter = '%D8';
} elseif ($letter == 'Щ') { $letter = '%D9';
} elseif ($letter == 'Э') { $letter = '%DD';
} elseif ($letter == 'Ю') { $letter = '%DE';
} elseif ($letter == 'Я') { $letter = '%DF';
} elseif ($letter == '[^а-яёa-z]') { $letter = '0-9';
}
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/artist-'.$letter.'-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artist-'.$letter.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$link = $config['http_home_url'].'music/artist-'.$letter.'-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artist-'.$letter;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
}
break;

// Новогодние и Рождественские песни )))
case 'happy-new-year':

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'title, artist';
  $sortactive = 'По названию трека &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">По исполнителю &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = 'По дате добавления &#8593;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">По исполнителю &#8595;</a></li><li><a href="/music/happy-new-year/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">По рейтингу &#8595;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = 'По рейтингу &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">По исполнителю &#8595;</a></li><li><a href="/music/happy-new-year/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">По дате добавления &#8593;</a></li>';
}
else {
  $sqlsort = 'artist, title';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">По дате добавления &#8593;</a></li>';
}

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$metatags['title'] = 'Новогодние и Рождественские песни '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Новогодние и Рождественские песни на '.$config['description'];
$metatags['keywords'] = 'скачать все песни про Новый Год mp3, музыкальный новый год, бесплатно скачать все треки Рождество, скачать мп3 бесплатно, новый год музыка без регистрации, все хиты Нового года mp3 бесплатно, новогодние песни';

$mtitle .= '<h1 class="tit"><span>Новогодние и Рождественские песни</span></h1>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (MATCH(title) AGAINST('(+нов* +год*) (рождеств*) (новогод*) (+new* +year*) (christmas) (ёлка) (ёло*) (різдв) (Новим Роком) (Новий Рік)' IN BOOLEAN MODE) OR mid IN (97498)) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (MATCH(title) AGAINST('(+нов* +год*) (рождеств*) (новогод*) (+new* +year*) (christmas) (ёлка) (ёло*) (різдв) (Новим Роком) (Новий Рік)' IN BOOLEAN MODE) OR mid IN (97498)) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'Неверная страница.';
if (count($stop)>0) $stop[] = '<a class=main href="javascript:history.go(-1)">Вернуться назад.</a>';

if ( count( $stop ) == 0 ) {

$mcontent .= '<div id="navigation_up"></div>';
$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<tr>
<th width="70%" height="20"><dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl></th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) $count = $row['cnt'];
$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/happy-new-year-page-'.$t2.$sortpage.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/happy-new-year'.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$link = $config['http_home_url'].'music/happy-new-year-page-';
$seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/happy-new-year';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
}

break;

// Поиск по архиву, форма
case 'search':

$metatags['title'] = 'Поиск на сайте '.$config['home_title_short'];
$metatags['description'] = 'Поиск на сайте '.$config['description'];
$metatags['keywords'] = 'поиск mp3, найти мп3 бесплатно, new, скачать музыку, новинки, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>Поиск на сайте</span> domain.com</h3>';
include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';

$mcontent .= $popular_search_artists;
$mcontent .= $popular_search_tracks;
$mcontent .= $popular_search_albums;

break;

// Обработка входящих данных и начало поиска
case 'dosearch':

$searchtype = intval( $_REQUEST['searchtype'] );
$searchtext = $parse->remove( $parse->process( $_REQUEST['searchtext'] ) );

if ( isset($_REQUEST['page']) == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

if ( (isset($_REQUEST['sort']) == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if (isset($_POST['newpoisk'])) {
	$page = 1;
	$searchtext = $parse->remove( $parse->process( $_POST['searchtext'] ) );
	$sort = 0;
	$sortpage = '';
} else $searchtext = base64_decode(str_replace('_','/',str_replace('*','+',$searchtext)));

$textbase64 = str_replace('/','_',str_replace('+','*',base64_encode($searchtext)));
//echo $searchtext.'  ';
if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$metatags['title'] = 'Результаты поиска: '.$searchtext.' '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Результаты поиска: '.$searchtext.' на '.$config['description'];
$metatags['keywords'] = 'поиск mp3, найти мп3 бесплатно, new, скачать музыку, новинки, '.$config['keywords'];

if ($searchtype == 4) {
  $limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2;

  if ( $sort == 1 ) {
    $sqlsort = 'album, artist';
    $sortactive = 'По названию альбома &#8595;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">По исполнителю &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">По году выхода &#8593;</a></li>';
  } elseif ( $sort == 2 ) {
    $sqlsort = 'year DESC, artist, album';
    $sortactive = 'По году выхода &#8593;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">По исполнителю &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">По названию альбома &#8595;</a></li>';
  } else {
  $sqlsort = 'artist, album';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">По названию альбома &#8595</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">По году выхода &#8593;</a></li>';
  }
} else {
  $limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];
  if ( $sort == 1 ) {
    $sqlsort = 'title, artist';
    $sortactive = 'По названию трека &#8595;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">По исполнителю &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">По дате добавления &#8593;</a></li>';
  } elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = 'По дате добавления &#8593;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">По исполнителю &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">По рейтингу &#8595;</a></li>';
  } elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = 'По рейтингу &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">По исполнителю &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">По дате добавления &#8593;</a></li>';
  } else {
  $sqlsort = 'artist, title';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">По названию трека &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">По дате добавления &#8593;</a></li>';
  }
}

$searchtext2 = textSwitch ($searchtext,2);
// if ( $is_logged == FALSE ) $stop[] = 'Поиск доступен только зарегистрированным пользователям!';
if ( $searchtype == 0 or $searchtype == '' ) $stop[] = 'Вы не выбрали тип поиска!';
if ( $searchtext == '' ) $stop[] = 'Вы не ввели текст для поиска!';
if ( (mb_strlen( $searchtext ) < 2) AND (mb_strlen( $searchtext2 ) < 2) ) $stop[] = 'Введённый для поиска текст не может быть короче чем 2 символа!';

if ( count( $stop ) == 0 ) {

  if ($searchtype == 2) {$where_ft ='artist'; $searchtitle = 'Поиск по исполнителям';}
  elseif ($searchtype == 3) {$where_ft = 'title'; $searchtitle = 'Поиск по трекам';}
  elseif ($searchtype == 4) {$where_ft = 'artist,album'; $searchtitle = 'Поиск по альбомам';}
  else 	{$where_ft ='artist,title'; $searchtitle = 'Общий поиск';}

  /// Новое условие на вхождение  точек больше одной
  $dotcount = substr_count($searchtext, ".");
  if (($dotcount > 1) AND ( $searchtype == 2 )) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE artist LIKE '$searchtext%' AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE artist LIKE '$searchtext%' AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );
  } else {
    //@setlocale(LC_ALL, 'ru_RU');
    // $searchtext.' ';

    // Ищем дословно исполнителя в базе и выводим перед результатами поиска
    if (($searchtype != 3) AND ( $searchtype != 4 )) {
      $searchtext_temp = trim(addslashes($searchtext));
      $searchtext_temp2 = trim(addslashes($searchtext2));
      $searchtext_temp = preg_replace("/\s{2,}/",' ', $searchtext_temp);
      $searchtext_temp2 = preg_replace("/\s{2,}/",' ', $searchtext_temp2);
      $mcontentartist0 = '';
      $mcontentartist = '';
      $row = $db->super_query( "SELECT DISTINCT artist, transartist FROM ".PREFIX."_mservice WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1' ORDER BY artist LIMIT 1" );
      if ( $row['artist'] != FALSE ) {
        $mcontentartist0 .= '<h3 class="tit_green" style="margin:0px 0px;">Результаты поиска исполнителей...</h3>';
        $link_to_alltracks = $config['http_home_url'].'music/artistracks-'.$row["transartist"].'.html';
        // отображаем обложку 150х150 исполнителя
        $row2 = $db->super_query( "SELECT image_cover150, genre FROM ".PREFIX."_mservice_artists WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2')" );
        
        if ($row2['genre']) {
          list ($genre1_art, $genre_art) = musicGenre($row2['genre']);
          
          if ($genre1_art !== '') $art_genre_tab = '<div style="height:10px;"></div><a class="genre'.$row2['genre'].'" href="/music/genre-albums-'.$genre1_art.'.html">#'.$genre_art.'</a>';
          else $art_genre_tab = '';
        }
          
        if ($row2['image_cover150'] != '') $image_cover150 = $row2['image_cover150']; else $image_cover150 = 'artist_0.jpg';
        
        $mcontentartist0 .= '<table style="margin:10px;"><tr><td width="50%" valign="top"><a href="'.$link_to_alltracks.'" title="Бесплатно скачать mp3, все музыкальные треки '.$row['artist'].'"><img src="/uploads/artists/'.$image_cover150.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать все мп3 песни '.$row['artist'].'" title="бесплатно скачать все мп3 песни '.$row['artist'].'" /><br /><strong>'.$row['artist'].'</strong></a><br />'.$art_genre_tab.'</td></tr></table>';
        
        $mcontentartist0 .= '<div class="alltra" style="margin:10px;"><a href="'.$link_to_alltracks.'" title="Бесплатно скачать mp3, все музыкальные треки '.$row['artist'].'">Смотреть все мп3 треки '.$row['artist'].'</a></div><div style="clear:both;"></div>';
     
        // выводим альбомы дословного исполнителя перед результатами поиска
        $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1' ORDER BY year_alb DESC, album LIMIT 2" );
  
        if ( $db->num_rows() > 0 ) {
          $mcontentartist .= '<div class="album_tracks_ramka" style="margin:10px 0px;"><div class="album_tracks_tit">... а также альбомов</div><table width="100%"><tr>';
          while( $row = $db->get_row() ) {
            list ($genre1, $genre) = musicGenre($row['genre']);

            if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px;"></div><a class="genre'.$row['genre'].'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
            else $alb_genre_tab = '';
        
            if (!$art_alb) {
              $art_alb = $row['artist'];
              $transartist_alb = $row['transartist'];
            }
            if ($art_alb != $row['artist']) {
              $art_alb2 = $row['artist'];
              $alb_different = TRUE;
              $transartist_alb2 = $row['transartist'];
            }

          if (!$cnt_alb) $cnt_alb = $row['cnt'];
        
          if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
          else $year_alb = '('.$row['year_alb'].')';
        
          if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        
        $mcontentartist .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.$row['artist'].'</b><br />'.$row['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
          }
          
          if ( $cnt_alb == 1 ) $mcontentartist .= '<td width="50%" valign="top"></td>';
          elseif ( $cnt_alb > 2 ) {
            if ($alb_different) $mcontentartist .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist_alb2.'.html" title="Смотреть и скачать бесплатно все mp3 альбомы '.$art_alb2.'">Смотреть все альбомы '.$art_alb2.'</a></div><div style="clear:both;"></div></td></div>';
            else $mcontentartist .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist_alb.'.html" title="Смотреть и скачать бесплатно все mp3 альбомы '.$art_alb.'">Смотреть все альбомы '.$art_alb.'</a></div><div style="clear:both;"></div></td></div>';
          }
          $mcontentartist .= '</tr></table></div>';
        }
        // конец альбомов дословных исполнителей
        $db->free( );
      }
    //конец дословных исполнителей
    }
    /* 1.обрезаем длинный запрос до 60символов 2. всё в нижний регистр 3. убираем слешы для красивого текста поиска 4.заменяем всё что не буква/цифра (кроме ...) на пробел 5-6. вырезаем также стоп-слова, которые в поиске не учавствуют 7.вырезаем слова длиной один символ, trim'аем пробелы в начале/конце подстроки */
    $searchtextsql = substr($searchtext,0,60);
    $searchtextsql = mb_strtolower($searchtextsql,'cp1251');
    $searchtext = stripslashes($searchtext);
    $searchtextsql = preg_replace("/[^\w\s\р\э\ё\Ё\є\ї\']/", " ", $searchtextsql);
    $searchtextsql  = textClear($searchtextsql);
    $searchtextsql  = str_replace(' 039', ' ', $searchtextsql );
    $searchtextsql = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $searchtextsql "));

    $searchtextsql2 = substr($searchtext2,0,60);
    $searchtextsql2 = mb_strtolower($searchtextsql2,'cp1251');
    $searchtext2 = stripslashes($searchtext2);
    $searchtextsql2 = preg_replace("/[^\w\s\р\э\ё\Ё\є\ї\']/", " ", $searchtextsql2);
    $searchtextsql2  = textClear($searchtextsql2);
    $searchtextsql2  = str_replace(' 039', ' ', $searchtextsql2 );
    $searchtextsql2 = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $searchtextsql2 "));

    //if ($member_id['user_id'] == 1017) echo $searchtextsql.' '.$searchtextsql2;
    // 1.определяем количество=$searchtextword_count слов в подстроке (включая все цифры и т.д.) 2. --||-- и скидываем все слова в массив $searchtextword_array и $searchtextword_array2 
    $list = "їЇєЄЁё0123456789'_"; 
    for ( $i = 192; $i < 256; $i++ ) {$list = $list.chr($i);}
    $searchtextword_count = str_word_count($searchtextsql,0,$list);//0 - возвращает количество найденных слов
    $searchtextword_array = str_word_count($searchtextsql,1,$list);//1 - возвращается массив, содержащий все слова, входящие в строку string
    $searchtextword_array2 = str_word_count($searchtextsql2,1,$list);//1 - возвращается массив, содержащий все слова, входящие в строку string
    /* если подстрока из одного слова и в ней есть дефис (2D-код дефиса), то заменяем его на "пробел+" и вначало подстроки идёт тоже "+", 
trim'аем пробелы в начале/конце подстроки */
    //if ($searchtextword_count == 1) $searchtextsql = trim(preg_replace("/\x2D/", " +", "+$searchtextsql"));
    /*если подстрока поиска более чем из 2ух слов то очищаем подстроку для запроса в MySQL, считываем по одному слову 
и если длина этого слова больше 1 буквы (иногда проскакивали и такие), то добавляем к подстроке для запроса нужные для 
поиска in boolean mode символы "+..*", trim'аем пробелы в начале/конце подстроки */
    if ($searchtextword_count > 1) {
      $searchtextsql = '';
      $searchtextsql2 = '';
      foreach ($searchtextword_array as $searchtextword) {
        if (strlen($searchtextword) > 1) $searchtextsql .= '+'.$searchtextword.'* ';
      }
      $searchtextsql = trim($searchtextsql);
      foreach ($searchtextword_array2 as $searchtextword) {
        if (strlen($searchtextword) > 1) $searchtextsql2 .= '+'.$searchtextword.'* ';
      }
      $searchtextsql2 = trim($searchtextsql2);
    }
    $searchtextsql = addslashes($searchtextsql);
    $searchtextsql2 = addslashes($searchtextsql2);

    //if ($member_id['user_id'] == 1017) echo $searchtextsql.' '.$searchtextsql2;
    if ($searchtype == 4) $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['album_page_lim']*2);

    else $db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );
  }

  $mcontent = <<<HTML
<div id="prev_search"></div>
HTML;

  if ($searchtype == 4) {
  //вывод альбомов, при поиске по альбомам
    if ( $db->num_rows( ) == 0 ) {
      $mcontent .= <<<HTML
<script type="text/javascript">
showPrevSearch('{$searchtext}');
</script>
HTML;
      $podmod = TRUE;
    } else {
      $i = 1;
      $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><table width="100%"><tr>';
    
      while( $row = $db->get_row() ) {
        if (!isset($count)) {
          $count = $row['cnt'];
          $mcontent .= '<h3 class="tit">'.$searchtitle.': <span>'.$searchtext.'</span>, найдено <span>'.declension($count, array('совпадение', 'совпадения', 'совпадений')).'</span></h3>';
          $mcontent .= '<div id="navigation_up"></div>';
          if ( $db->num_rows() > 1 ) $mcontent .= '<dl id="sample" class="dropdown_alb"><dt><span>'.$sortactive.'</span></dt><dd><ul>'.$sortactive_no.'</ul></dd></dl>';
          else $mcontent .= '<dl id="sample" class="dropdown_alb"></dl>';
        }
        $genreid = $row['genre'];
        if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
        elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
        elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
        elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
        elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
        elseif ($genreid == '6') {$genre1 = 'ost'; $genre = 'OST';}
        elseif ($genreid == '7') {$genre1 = 'metal'; $genre = 'Metal';}
        elseif ($genreid == '8') {$genre1 = 'country'; $genre = 'Country';}
        elseif ($genreid == '9') {$genre1 = 'classical'; $genre = 'Classical';}
        elseif ($genreid == '10') {$genre1 = 'punk'; $genre = 'Punk';}
        elseif ($genreid == '11') {$genre1 = 'soul/r-and-b'; $genre = 'Soul/R&B';}
        elseif ($genreid == '12') {$genre1 = 'jazz'; $genre = 'Jazz';}
        elseif ($genreid == '13') {$genre1 = 'electronic'; $genre = 'Electronic';}
        elseif ($genreid == '14') {$genre1 = 'indie'; $genre = 'Indie';}
        else {$genreid = ''; $genre1 = ''; $genre = '';}
        
        if ($genre1 !== '') $alb_genre_tab = '<a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div>';
        else $alb_genre_tab = '';
          
        if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row['year_alb'].')';
        
        if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        
        $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
        $i++;
        if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
      }
      $mcontent .= '</tr></table></div>';
    
      if ($count < $mscfg['album_page_lim']) $podmod = TRUE;
    
      // Постраничная навигация
      $count_d = $count / ($mscfg['album_page_lim']*2);
      $pages = '';
      for ( $t = 0; $count_d > $t; $t ++ ) {
        $t2 = $t + 1;
        $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$t2.'-'.$textbase64.$sortpage.'.html';
    
        if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
        else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$textbase64.$sortpage.'.html';
        $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
        $array[$t2] = 1;
      }

      $link = $config['http_home_url'].'music/search-'.$searchtype.'-';
      $seo_mode = '-'.$textbase64.$sortpage.'.html';

      $npage = $page - 1;
      if ( isset($array[$npage]) ) {
        if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/search-'.$searchtype;
          $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
        else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
      else $prev_page = '';
    
      $npage = $page + 1;
    
      if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
      else $next_page = '';

      if ( $count > $mscfg['album_page_lim']*2 ) {
        $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
        $albums_to_view = $count - (($page * $mscfg['album_page_lim']*2) - $mscfg['album_page_lim']*2);
        if ($albums_to_view < $mscfg['album_page_lim']) $podmod = TRUE;
        elseif ($albums_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
      }
      $db->free( );
    }
    if ($podmod == TRUE) {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
      $mcontent .= $downloads_top_albums_week;
    }
  } else {
    //вывод при всех поисках общий, по исполнителю, по треку, кроме альбомов
    if ($db->num_rows( ) > 1) $mcontent .= $mcontentartist0;
    
    $mcontent .= $mcontentartist;
    
    if ( $db->num_rows( ) == 0 ) {
      $mcontent .= <<<HTML
<script type="text/javascript">
showPrevSearch('{$searchtext}');
</script>
HTML;
      include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
      $mcontent .= $popular_search_artists;
      $mcontent .= $popular_search_tracks;
      $mcontent .= $popular_search_albums;
    } else {
      $mcontent .= '<div id="playlist"><table width="100%"><tr><th width="70%" height="20">';

      if ( $db->num_rows( ) > 1 ) $mcontent .= '<dl id="sample" class="dropdown"><dt><span>'.$sortactive.'</span></dt><dd><ul>'.$sortactive_no.'</ul></dd></dl>';
    
      $mcontent .= '</th><th width="10%">Время</th><th width="10%">Размер</th><th width="5%">&nbsp;</th><th width="5%">&nbsp;</th></tr><tr>';

      while ( $row = $db->get_row( ) ) {
        if (!isset($count)) { 
          $count = $row['cnt'];
          $mcontent .= '<h3 class="tit">'.$searchtitle.': <span>'.$searchtext.'</span>, найдено <span>'.declension($count, array('совпадение', 'совпадения', 'совпадений')).'</span></h3>';
          $mcontent .= '<div id="navigation_up"></div>';
        }

        $tpl->load_template( 'mservice/tracklist.tpl' );
        $tpl->set( '{THEME}', $THEME );
        $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
        $tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
        $tpl->set( '{mid}', $row['mid'] );
        $tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
        $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
        $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
        $tpl->set( '{artistfull}', $row['artist'] );
        $tpl->set( '{titlefull}', $row['title'] );
        $tpl->set( '{lenght}', $row['lenght'] );
        $tpl->set( '{filesize}', formatsize( $row['size'] ) );
        $tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
        $tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

        $tpl->compile( 'tracklist' );
        $mcontent .= $tpl->result['tracklist'];
        $tpl->result['tracklist'] = FALSE;
      }
      
      $mcontent .= '</table></div>';
      if ($count < 10 ) {
        include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
        $mcontent .= $popular_search_artists;
        $mcontent .= $popular_search_tracks;
      }

      // Постраничная навигация
      $count_d = $count / $mscfg['track_page_lim'];
      $pages = '';
      for ( $t = 0; $count_d > $t; $t ++ ) {
        $t2 = $t + 1;

        $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$t2.'-'.$textbase64.$sortpage.'.html';

        if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
        else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$textbase64.$sortpage.'.html';
        $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
        $array[$t2] = 1;
      }

      $link = $config['http_home_url'].'music/search-'.$searchtype.'-';
      $seo_mode = '-'.$textbase64.$sortpage.'.html';

      $npage = $page - 1;
      if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/search-'.$searchtype;
      $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
        else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
      else $prev_page = '';
      $npage = $page + 1;
      if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
      else $next_page = '';

      if ( $count > $mscfg['track_page_lim'] ) {
        $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
        $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
        if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
      }
    }
  }  

  // вставка данных поиска в таблицу для анализа поиска (if ($page == 1) = чтобы переходы по страницам поиска не засчитывались)
  //echo $searchtext;
  $time = time();
  $searchtextins = addslashes(trim($searchtext));
  //if (($page == 1) AND (isset($_POST['newpoisk']))) $db->query( "INSERT INTO ".PREFIX."_mservice_search ( time_php, search_text, user_id, search_type, count ) VALUES ( '$time', '$searchtextins', '$member_id[user_id]', '$searchtype', '$count' )" );
  if ( $page == 1)  {
    $referer = addslashes($_SERVER['HTTP_REFERER']);
    $user_agent = addslashes($_SERVER['HTTP_USER_AGENT']);
    if ($is_logged) $member_userid = $member_id['user_id']; else $member_userid = 0;
    $db->query( "INSERT INTO ".PREFIX."_mservice_search ( time_php, count, search_text, search_type, user_id, ip, user_agent, referer ) VALUES ( '$time', '$count', '$searchtextins', '$searchtype', '$member_userid', '$_SERVER[REMOTE_ADDR]', '$user_agent', '$referer' )" );
  }
}
break;

// Просмотр ТОП скачиваний альбомов за сутки (для админов)
case 'downloadstopalbums24hr_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_24hr_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр ТОП скачиваний альбомов за сутки 
case 'downloadstopalbums24hr' :

  $num_downloads_top_albums_24hr = 20;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_24hr.php';
	$mcontent .= $downloads_top_albums_24hr;

break;

// Просмотр ТОП скачиваний альбомов за неделю (для админов)
case 'downloadstopalbumsweek_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр ТОП скачиваний альбомов за неделю
case 'downloadstopalbumsweek' :

  $num_downloads_top_albums_week = 20;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
	$mcontent .= $downloads_top_albums_week;

break;

// Просмотр тревов, загруженных юзером (для админов)
case 'usertracks' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр прослушиваний треков за месяц юзера (для админов)
case 'monthuserplays' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр скачиваний за месяц юзера (для админов)
case 'monthuserdownloads' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр скачиваний альбомов за месяц юзера (для админов)
case 'monthuserdownloads-albums' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр ТОП прослушиваний за сутки - то что подставляется на страницы сайта
case 'topplay24hr' :

	include_once ENGINE_DIR.'/modules/mservice/mod_top_play_24hr.php';
	$mcontent .= $top_play_24hr;

break;

// Просмотр ТОП прослушиваний за неделю - то что подставляется на страницы сайта
case 'topplayweek' :

	include_once ENGINE_DIR.'/modules/mservice/mod_top_play_week.php';
  $mcontent .= $top_play_week;

break;

// Просмотр ТОП скачиваний за сутки (для админов)
case 'downloadstop24hr_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр ТОП скачиваний за сутки - то что подставляется на страницы сайта
case 'downloadstop24hr' :

  $num_downloads_top_24hr = 50;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;

break;

// Просмотр ТОП скачиваний за неделю (для админов)
case 'downloadstopweek_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр ТОП скачиваний за неделю - то что подставляется на страницы сайта
case 'downloadstopweek' :

  $num_downloads_top_week = 50;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
  $mcontent .= $downloads_top_week;

break;

// Просмотр Популярных поисковых запросов за неделю - по исполнителю(для админов)
case 'popularsearchalbumweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchalbum_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр Популярных поисковых запросов за неделю - по исполнителю(для админов)
case 'popularsearchartistweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchartist_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр Популярных поисковых запросов за неделю - по названию трека(для админов)
case 'popularsearchtitleweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchtitle_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// Просмотр Популярных поисковых запросов за неделю - с пустым результатом (для админов)
case 'popularsearchzero' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_search_zero_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

//Страница участников Евровидения 2011
case 'euro2011':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//Страница участников Евровидения 2012
case 'euro2012':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//Страница участников Евровидения 2013
case 'euro2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//Страница участников Евровидения 2014
case 'euro2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//Страница участников Евровидения 2015
case 'euro2015':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//  ТОП лучших мп3 по скачиванию за текущий год ... (меняю условие каждый год)
case 'mp3top':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП лучших мп3 по скачиванию за 2014 год
case 'mp3top2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП лучших мп3 по скачиванию за 2013 год
case 'mp3top2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП лучших мп3 по скачиванию за 2012 год
case 'mp3top2012':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП лучших мп3 по скачиванию за 2011 год
case 'mp3top2011':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП 100 лучших Клубных треков (KISS FM) за 2013 год
case 'top_club2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП 100 лучших Клубных треков (KISS FM) за 2014 год
case 'top_club2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП 20 продаваемых треков 2014 Beatport
case 'top_beatport2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП 50 лучших песен 2014 года по версии Rolling Stone
case 'top_rollingstone2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ТОП 50 исполнителей
case 'top_artists':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

// Страница просмотра ТОП лучших mp3 за сутки
case 'bestmp3_24hr':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// Страница просмотра ТОП лучших mp3 за неделю
case 'bestmp3_week':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// Страница просмотра ТОП лучших mp3 за месяц
case 'bestmp3_month':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// Просмотр альбома и его треков
case 'album' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// Просмотр всех альбомов исполнителя
case 'albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// Просмотр альбомов по жанрам
case 'genre-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// Просмотр всех жанров для альбомов
case 'genres-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

//Избранные альбомы юзера
case 'myfavalbums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// Просмотр всех альбомов исполнителя
case 'new-mp3-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// Форма МАССОВАЯ ЗАГРУЗКА ТРЕКОВ Uploadify
case 'massaddfiles':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;

// Временный case для очистки $_SESSION['mservice_files'] и файлов из папки temp
case 'masstemp':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;
	
// Страница МАССОВОГО редактирования аудио треков для добавления их в базу данных
case 'massaddfiles02':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;
        
// Сохранение МАССОВЫХ аудио треков и добавление их в базу данных
case 'domassaddfiles':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;

// Форма добавление нового трека 
case 'addfile':
include_once ENGINE_DIR.'/modules/mservice/mservice_addfile.php';
break;

// Сохранение аудио трека и добавление его в базу данных
case 'doaddfile':
include_once ENGINE_DIR.'/modules/mservice/mservice_addfile.php';
break;

// ^^)
case 'odmin':
include_once ENGINE_DIR.'/modules/mservice/mservice_odmin.php';
break;
// конец цикла switch ( $_REQUEST['act'] )
}

$tpl->load_template( 'mservice/global.tpl' );
$tpl->set( '{mtitle}', '<div style="margin-bottom:0px">'.$mtitle.'</div>' );

if ( count( $stop ) != 0 ) {
	for ( $e = 0; $e < count( $stop ); $e ++ ) $errors .= '<li>'.$stop[$e].'</li>';
	$mcontent = <<<HTML
	<div id="errorpage"></div>
<script type="text/javascript">
showErrorPage('{$errors}');
</script>
HTML;
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
	$mcontent .= $popular_search_artists;
  $mcontent .= $popular_search_tracks;
  $mcontent .= $popular_search_albums;
}

$tpl->set( '{mservice}', $mcontent );
$tpl->compile( 'content' );
$db->free( );
$tpl->clear( );
?>
