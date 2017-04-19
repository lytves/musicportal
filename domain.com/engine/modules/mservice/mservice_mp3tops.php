<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
@error_reporting ( E_ALL ^ E_NOTICE );

switch ( $_REQUEST['act'] ) {

//  ТОП лучших мп3 по скачиванию за текущий год ... (меняю условие каждый год)
case 'mp3top':

$nam_e1 = 'ТОП лучших mp3 2015 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП лучших mp3 2015 года '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ лучших mp3 2015 года на '.$config['description'];
$metatags['keywords'] = '2015, 2014, 2013, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2015 год, '.$config['keywords'];
// а это условие все треки только после 01.01.2015 года
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1420066801') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц ТОП лучших mp3 2015 года';

$mtitle .= '<h1 class="tit">ТОП лучших <span>mp3 2015</span> года от domain.com</h1>';
$mtitle .= '<div class="bestmp3">...а также ТОП лучших mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top2014.html" title="Скачать ТОП лучших mp3 2014 года">2014 год</a></li><li><a href="/music/mp3top2013.html" title="Скачать ТОП лучших mp3 2013 года">2013 год</a></li><li><a href="/music/mp3top2012.html" title="Скачать ТОП лучших mp3 2012 года">2012 год</a></li><li><a href="/music/mp3top2011.html" title="Скачать ТОП лучших mp3 2011 года">2011 год</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...и ещё все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li></ul></div>';

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
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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

}
$mcontent .= '</table></div>';
}
// Постраничная навигация ТОП лучших мп3 по скачиванию за всё время, установлена переменная page_lim_main в админке - 
// это число выводит столько страниц треками
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) {
  if ($npage ==1 ) {
    $linkdop = $config['http_home_url'].'music/mp3top';
    $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';
  }
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';
}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП лучших мп3 по скачиванию за текущий год ... (меняю условие каждый год)
case 'mp3top2014':

$nam_e1 = 'ТОП лучших mp3 2014 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП лучших mp3 2014 года '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ лучших mp3 2014 года на '.$config['description'];
$metatags['keywords'] = '2014, 2013, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2014 года, '.$config['keywords'];
// а это условие все треки только 2014 года
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1388530801' AND time < '1420066801') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц ТОП лучших mp3 2014 года';

$mtitle .= '<h1 class="tit">ТОП лучших <span>mp3 2014</span> года от domain.com</h1>';
$mtitle .= '<div class="bestmp3">...а также ТОП лучших mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 годов">2015 год</a></li><li><a href="/music/mp3top2013.html" title="Скачать ТОП лучших mp3 2013 года">2013 год</a></li><li><a href="/music/mp3top2012.html" title="Скачать ТОП лучших mp3 2012 года">2012 год</a></li><li><a href="/music/mp3top2011.html" title="Скачать ТОП лучших mp3 2011 года">2011 год</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...и ещё все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li></ul></div>';

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
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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

}
$mcontent .= '</table></div>';
}
// Постраничная навигация ТОП лучших мп3 по скачиванию за всё время, установлена переменная page_lim_main в админке - 
// это число выводит столько страниц треками
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2014-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2014.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2014-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) {
  if ($npage ==1 ) {
    $linkdop = $config['http_home_url'].'music/mp3top2014';
    $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';
  }
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';
}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП лучших мп3 по скачиванию за 2013 год
case 'mp3top2013':

$nam_e1 = 'ТОП лучших mp3 2013 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП лучших mp3 2013 года '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ лучших mp3 2013 на '.$config['description'];
$metatags['keywords'] = '2013, скачать бесплатно mp3, новинки, список популярных песен года, new, скачать лучшие mp3 2013, '.$config['keywords'];

// а это условие все треки только 2013 года
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1356994801' AND time < '1388530799') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц ТОП лучших mp3 2013 года';

$mtitle .= '<h1 class="tit">ТОП лучших <span>mp3 2013</span> года от domain.com</h1>';
$mtitle .= '<div class="bestmp3">...а также ТОП лучших mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 годов">2015 год</a></li><li><a href="/music/mp3top2014.html" title="Скачать ТОП лучших mp3 2014 года">2014 год</a></li><li><a href="/music/mp3top2012.html" title="Скачать ТОП лучших mp3 2012 года">2012 год</a></li><li><a href="/music/mp3top2011.html" title="Скачать ТОП лучших mp3 2011 года">2011 год</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...и ещё все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li></ul></div>';

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
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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

}
$mcontent .= '</table></div>';
}
// Постраничная навигация ТОП лучших мп3 по скачиванию за всё время, установлена переменная page_lim_main в админке - 
// это число выводит столько страниц треками
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2013-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2013.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2013-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2013';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП лучших мп3 по скачиванию за 2012 год
case 'mp3top2012':

$nam_e1 = 'ТОП лучших mp3 2012 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП лучших mp3 2012 года '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ лучших mp3 2012 на '.$config['description'];
$metatags['keywords'] = '2012, скачать бесплатно mp3, новинки, список популярных песен года, new, скачать лучшие mp3 2012, '.$config['keywords'];

// а это условие все треки только 2012 года
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1325372401' AND time < '1356994799') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц ТОП лучших mp3 2012 года';

$mtitle .= '<h1 class="tit">ТОП лучших <span>mp3 2012</span> года от domain.com</h1>';
$mtitle .= '<div class="bestmp3">...а также ТОП лучших mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 годов">2015 год</a></li><li><a href="/music/mp3top2014.html" title="Скачать ТОП лучших mp3 2014 года">2014 год</a></li><li><a href="/music/mp3top2013.html" title="Скачать ТОП лучших mp3 2013 года">2013 год</a></li><li><a href="/music/mp3top2011.html" title="Скачать ТОП лучших mp3 2011 года">2011 год</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...и ещё все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li></ul></div>';

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
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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

}
$mcontent .= '</table></div>';
}
// Постраничная навигация ТОП лучших мп3 по скачиванию за всё время, установлена переменная page_lim_main в админке - 
// это число выводит столько страниц треками
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2012-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2012.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2012-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2012';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП лучших мп3 по скачиванию за 2011 год
case 'mp3top2011':

$nam_e1 = 'ТОП лучших mp3 2011 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП лучших mp3 2011 года '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ лучших mp3 2011 на '.$config['description'];
$metatags['keywords'] = '2011, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2011, '.$config['keywords'];

// это условие это треки только 2011 года
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1293836401' AND time < '1325372399') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = 'Больше нет страниц ТОП лучших mp3 2011 года';

$mtitle .= '<h1 class="tit">ТОП лучших <span>mp3 2011</span> года от domain.com</h1>';
$mtitle .= '<div class="bestmp3">...а также ТОП лучших mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 годов">2015 год</a></li><li><a href="/music/mp3top2014.html" title="Скачать ТОП лучших mp3 2014 года">2014 год</a></li><li><a href="/music/mp3top2013.html" title="Скачать ТОП лучших mp3 2013 года">2013 год</a></li><li><a href="/music/mp3top2012.html" title="Скачать ТОП лучших mp3 2012 года">2012 год</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...и ещё все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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

}
$mcontent .= '</table></div>';
}
// Постраничная навигация ТОП лучших мп3 по скачиванию за всё время, установлена переменная page_lim_main в админке - 
// это число выводит столько страниц треками
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2011-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2011.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2011-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2011';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП 100 лучших Клубных треков (KISS FM) за 2013 год
case 'top_club2013':

$nam_e1 = 'ТОП 100 клубных треков 2013 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
if ( $page > 2 ) $stop[] = 'Больше нет страниц KISS FM ТОП 100 клубных треков 2013 года';

$metatags['title'] = 'ТОП 100 клубных треков 2013 года '.$metapage.'от KISS FM на '.$config['home_title_short'];
$metatags['description'] = 'KISS FM ТОП 100 клубных треков 2013 года на '.$config['description'];
$metatags['keywords'] = '2014, 2013, ТОП 100, клубные треки, KISS FM, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2013, '.$config['keywords'];

$mids = '44525,37271,58622,35053,51540,42848,41797,58640,58639,45155,58635,58638,44836,58637,35479,43290,38400,32786,43428,44414,40135,54520,39923,19977,43536,34020,44534,39704,44538,35480,51214,24313,28447,58641,51878,43277,44958,58642,34947,36819,50288,52011,49144,43676,33039,44369,40067,37238,38453,58636,48684,51830,29931,44144,39694,58648,35345,58535,44540,28421,43507,36008,43429,44366,58643,42578,35348,58644,58647,39132,35814,38452,42430,43538,58646,46548,39136,38023,30021,26147,43967,45120,42242,34122,36128,35188,44300,30945,49837,35802,43351,39887,58645,36662,43504,42554,33889,42855,45127,39795';
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."') DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

$mtitle .= '<h1 class="tit">KISS FM <span>ТОП 100 клубных треков</span> 2013 года</h1>';
$mtitle .= '<div class="bestmp3">...а также KISS FM ТОП 100 клубных треков за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/top_club2014.html" title="Скачать KISS FM ТОП 100 клубных треков 2014 года">2014 год</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">№</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">Время</th>
<th width="9%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
if ($page == 1) $i = 1; else $i = 51;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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
$i++;
}
$mcontent .= '</table></div>';
}
// Постраничная ТОП 100 лучших Клубных треков 2013 (только 2 страницы по 50 треков)
$count_d = 2;

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/top_club2013-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/top_club2013.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП 100 лучших Клубных треков (KISS FM) за 2014 год
case 'top_club2014':

$nam_e1 = 'ТОП 100 клубных треков 2014 года';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
if ( $page > 2 ) $stop[] = 'Больше нет страниц KISS FM ТОП 100 клубных треков 2014 года';

$metatags['title'] = 'ТОП 100 клубных треков 2014 года '.$metapage.'от KISS FM на '.$config['home_title_short'];
$metatags['description'] = 'KISS FM ТОП 100 клубных треков 2014 года на '.$config['description'];
$metatags['keywords'] = '2015, 2014, 2013, ТОП 100, клубные треки, KISS FM, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2014, '.$config['keywords'];

$mids = '55477,78724,88277,59945,88570,74805,86539,60528,102103,47058,102106,64110,94952,61173,102107,99036,68141,102108,56684,89532,53670,102110,102109,67564,57321,75646,102111,73351,60180,90534,102112,102113,102114,89905,65676,79131,77203,102115,90518,86738,102116,102117,59314,61555,60646,71771,84969,91097,65871,94270,102211,73484,68928,97519,85516,36950,89572,85964,100283,92904,80848,102212,82132,96467,76245,102213,58381,75655,102214,86543,83081,88052,102215,85117,95267,83146,83963,85671,60609,102216,64125,64386,70296,91442,52815,52277,89961,91399,80299,55757,58606,93573,102218,102220,77837,92977,67582,89950,102222,81269';
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."') LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

$mtitle .= '<h1 class="tit">KISS FM <span>ТОП 100 клубных треков</span> 2014 года</h1>';
$mtitle .= '<div class="bestmp3">...а также KISS FM ТОП 100 клубных треков за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/top_club2013.html" title="Скачать KISS FM ТОП 100 клубных треков 2013 года">2013 год</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">№</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">Время</th>
<th width="9%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
if ($page == 1) $i = 1; else $i = 51;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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
$i++;
}
$mcontent .= '</table></div>';
}
// Постраничная ТОП 100 лучших Клубных треков 2014 (только 2 страницы по 50 треков)
$count_d = 2;

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/top_club2014-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/top_club2014.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ТОП 20 продаваемых треков 2014 Beatport
case 'top_beatport2014':

$nam_e1 = 'ТОП 20 треков Beatport 2014 года';

$metatags['title'] = 'ТОП 20 продаваемых треков Beatport 2014 года на '.$config['home_title_short'];
$metatags['description'] = 'Скачать ТОП 20 лучших треков продаваемых на Beatport в 2014 году на '.$config['description'];
$metatags['keywords'] = '2014, 2013, ТОП 20, клубные треки, Beatport, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2014, '.$config['keywords'];

$mids = '92404,57880,61555,98317,98321,98323,66978,98318,98320,98319,59935,57865,80407,85764,83505,71291,80765,60496,73912,73359';

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."')" );

$mtitle .= '<h1 class="tit"><span>ТОП 20 Beatport</span> продаваемых треков  2014 года</h1>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">№</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">Время</th>
<th width="9%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
$i = 1;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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
$i++;
}
$mcontent .= '</table></div>';
}

break;

//  ТОП 50 лучших песен 2014 года по версии Rolling Stone
case 'top_rollingstone2014':

$nam_e1 = 'ТОП 50 лучших треков 2014 года по версии журнала Rolling Stone';

$metatags['title'] = 'ТОП 50 лучших треков 2014 года по версии журнала Rolling Stone на '.$config['home_title_short'];
$metatags['description'] = 'Скачать ТОП 50 лучших треков 2014 года по версии журнала Rolling Stone на '.$config['description'];
$metatags['keywords'] = '2014, 2013, ТОП 50, лцчшие треки, Rolling Stone, скачать бесплатно mp3, new, список популярных песен года, новинки, скачать лучшие mp3 2014, '.$config['keywords'];

$mids = '56276,66391,87449,61377,99514,92286,58417,75355,65248,88507,99511,68431,94208,80577,66665,59979,99505,99512,61420,99513,83426,77714,64397,74026,77591,77253,99503,92894,80617,64169,69759,85491,99498,72477,79172,99497,99501,99502,99499,99500,99496,92104,99481,62640,99473,48272,63337,99494,99495,85945';

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."')" );

$mtitle .= '<h1 class="tit"><span>ТОП 50 лучших треков</span> 2014 года по версии журнала Rolling Stone</h1>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">№</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">Время</th>
<th width="9%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
$i = 1;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
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
$i++;
}
$mcontent .= '</table></div>';
}

break;

//  ТОП 50 исполнителей
case 'top_artists':

$nam_e1 = 'ТОП 50 исполнителей на domain.com';

$metatags['title'] = 'ТОП 50 исполнителей на '.$config['home_title_short'];
$metatags['description'] = 'ТОП 50 исполнителей на '.$config['description'];
$metatags['keywords'] = 'ТОП 50 испонителей, скачать бесплатно mp3, new, список популярных исполнителей, новинки, скачать лучшие mp3, '.$config['keywords'];

if ( $mscfg['allow_letter_navig'] == 1 ) $mtitle =  ArtistLetterNavigator( );

$mcontent = '<h1 class="tit"><span>ТОП 50 исполнителей</span> на domain.com</h1>';

$db->query( "SELECT transartist, artist, COUNT(transartist) AS cnt FROM ".PREFIX."_mservice  GROUP BY artist ORDER BY cnt DESC LIMIT 0, ".$mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'НЕТ страницы ТОП 50 исполнителей!';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table width="100%">
<thead><tr>
<th width="80%" height="20">&nbsp;</th>
<th width="20%">Треков</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {
if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
else $artist = $row['artist'];

$tpl->load_template( 'mservice/artistlist.tpl' );
$tpl->set( '{artist}', $artist );
$tpl->set( '{trekov}', $row['cnt'] );
$tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );

$tpl->compile( 'artistlist' );
$mcontent .= $tpl->result['artistlist'];
$tpl->result['artistlist'] = FALSE;

}
$mcontent .= '</table>';

}

break;

}
?>
