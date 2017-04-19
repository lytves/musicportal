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

// Страница просмотра ТОП лучших mp3 за сутки
case 'bestmp3_24hr':

$nam_e1 = 'Все новинки mp3 за сутки';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Все новинки mp3 за сутки '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Все новинки mp3 за сутки на '.$config['description'];
$metatags['keywords'] = 'самые свежие хиты mp3 за сутки, скачать бесплатно, new, музыкальные новинки 2015, 2014, 2013, 2012, 2011, музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2015, 2014, 2013, 2012, 2011, клубная новая музыка, хитовые новинки 2014, 2013, 2012, 2011, 2015';

$mtitle .= '<h3 class="tit">Все новинки <span>mp3 за сутки</span> на domain.com</h3>';
$mtitle .= '<div class="bestmp3">...а также все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li><li><a href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 года">2015 год</a></li></ul></div>';

$mcontent .= '<div id="navigation_up"></div>';
$toptime = (time() - 86400);

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'Сегодня на сайте ещё нет загруженных mp3';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
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

if (!$count) $count = $row['cnt'];

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
}
// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/bestmp3_24hr-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/bestmp3_24hr.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/bestmp3_24hr-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/bestmp3_24hr';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $count > $mscfg['track_page_lim'] ) {
$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
HTML;
$tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
else { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;
}
}
if ($count < 10 ) { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;
}
$mcontent .= '</div>';
break;

// Страница просмотра ТОП лучших mp3 за неделю
case 'bestmp3_week':

$nam_e1 = 'Все новинки mp3 за неделю';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Все новинки mp3 за неделю '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Все новинки mp3 за неделю на '.$config['description'];
$metatags['keywords'] = 'самые свежие хиты mp3 за неделю, скачать бесплатно, new, музыкальные новинки 2015, 2014, 2013, 2012, 2011, музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2015, 2014, 2013, 2012, 2011, клубная новая музыка, хитовые новинки 2014, 2013, 2012, 2011, 2015';

$mtitle .= '<h3 class="tit">Все новинки <span>mp3 за неделю</span> на domain.com</h3>';
$mtitle .='<div class="bestmp3">...а также все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_month.html" title="Скачать новинки mp3 за месяц">месяц</a></li><li><a href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 года">2015 год</a></li></ul></div>';

$mcontent .= '<div id="navigation_up"></div>';
$toptime = (time() - 604800);

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'На этой неделе пока нет загруженных mp3 на сайт';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
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

if (!$count) $count = $row['cnt'];

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
}
// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/bestmp3_week-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/bestmp3_week.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/bestmp3_week-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/bestmp3_week';
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
else { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
}
if ($count < 10 ) { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

$mcontent .= '</div>';
break;

// Страница просмотра ТОП лучших mp3 за месяц
case 'bestmp3_month':

$nam_e1 = 'Все новинки mp3 за месяц';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Все новинки mp3 за месяц '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Все новинки mp3 за месяц на '.$config['description'];
$metatags['keywords'] = 'самые свежие хиты mp3 за месяц, скачать бесплатно, new, музыкальные новинки 2015, 2014, 2013, 2012, 2011, музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2015, 2014, 2013, 2012, 2011, клубная новая музыка, хитовые новинки 2014, 2013, 2012, 2011, 2015';

$mtitle .= '<h3 class="tit">Все новинки <span>mp3 за месяц</span> на domain.com</h3>';
$mtitle .= '<div class="bestmp3">...а также все новинки mp3 за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="Скачать новинки mp3 за сутки">сутки</a></li><li><a href="/music/bestmp3_week.html" title="Скачать новинки mp3 за неделю">неделю</a></li><li><a href="/music/mp3top.html" title="Скачать ТОП лучших mp3 2015 года">2015 год</a></li></ul></div>';

$mcontent .= '<div id="navigation_up"></div>';
$toptime = (time() - 2592000);

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = 'В этом месяце пока нет загруженных mp3 на сайт';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
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

if (!$count) $count = $row['cnt'];

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
}
// Постраничная навигация
$count_d = $count / $mscfg['track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/bestmp3_month-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/bestmp3_month.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/bestmp3_month-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/bestmp3_month';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
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
else { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
}

$mcontent .= '</div>';
break;

}
?>
