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

// Просмотр прослушиваний треков за месяц юзера (для админов)
case 'monthuserplays' :

$user_id = abs(intval( $_REQUEST['user'] ));

$nam_e1 = 'Просмотр прослушанных треков за месяц';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Просмотр прослушанных треков за месяц '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Просмотр прослушанных треков за месяц на '.$config['description'];
$metatags['keywords'] = 'прослушанные треки, музыкальный архив mp3, '.$config['keywords'];

$user_name = $db->super_query( "SELECT name FROM ".USERPREFIX."_users WHERE user_id = '{$user_id}'");
if ( !$user_name['name'] ) $badpage = TRUE;

$db->query( "SELECT d.mid, d.time_php, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.lenght, m.size, m.clip_online, (SELECT COUNT(DISTINCT mid) FROM ".PREFIX."_mservice_plays WHERE user_id = '{$user_id}') as cnt FROM ".PREFIX."_mservice_plays as d LEFT JOIN ".PREFIX."_mservice as m ON d.mid = m.mid WHERE d.user_id = '{$user_id}' GROUP BY d.mid ORDER BY time_php DESC, artist, title LIMIT ".$limit.",".$mscfg['track_page_lim'] );
if ( $db->num_rows() == 0 ) $badpage = TRUE;

if ( $badpage ) {
  if ($user_name['name']) $user_link = '(<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')';
  else $user_link = 'пользователем.';
  $mtitle .= '<h3 class="tit"><span style="color:#4E4E4E;">Просмотр прослушанных треков за месяц '.$user_link.'</span></h3>';
  $mcontent .= 'Неверная страница либо ещё нет прослушанных треков за месяц у этого пользователя, либо нет такого пользователя.<br />';
  $podmod = TRUE;
} else  {

  $mtitle .= '<h3 class="tit"><img src="{THEME}/images/icon18.png" /> &nbsp;<span style="color:#4E4E4E;">Просмотр прослушанных треков за месяц (<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')</span></h3>';
  $mcontent .= '<div id="navigation_up"></div>';
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
  $r = 0;
  $i = 0;
  while ( $row = $db->get_row( ) ) {
    if (!$count) $count = $row['cnt'];
  
    $down_time[$i] = langdate( 'j F Y, l',$row['time_php']);
    if ($r == 0) $mcontent .= <<<HTML
<td class="titfav">Прослушано: {$down_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
    else {
      if ($down_time[$i] !== $down_time[$i-1]) {
        $mcontent .= <<<HTML
<td class="titfav">Прослушано: {$down_time[$i]}</td>
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
$r++;
$i++;
}
  $mcontent .= '</table>';
// Постраничная навигация
  $count_d = $count / $mscfg['track_page_lim'];

  for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;

  $plink = $config['http_home_url'].'music/monthuserplays-'.$user_id .'-page-'.$t2.'.html';

  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/monthuserplays-'.$user_id .'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
  }

  $link = $config['http_home_url'].'music/monthuserplays-'.$user_id .'-page-';
  $seo_mode = '.html';

  $npage = $page - 1;
  if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/monthuserplays-'.$user_id;
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
    else $podmod = TRUE;
  }
  $mcontent .= '</div>';
}
if ($podmod == TRUE)  {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
break;

// Просмотр скачиваний за месяц юзера (для админов)
case 'monthuserdownloads' :

$user_id = abs(intval( $_REQUEST['user'] ));

$nam_e1 = 'Просмотр скачанных треков за месяц';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Просмотр скачанных треков за месяц '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Просмотр скачанных треков за месяц на '.$config['description'];
$metatags['keywords'] = 'скачанные треки, музыкальный архив mp3, '.$config['keywords'];

$user_name = $db->super_query( "SELECT name FROM ".USERPREFIX."_users WHERE user_id = '{$user_id}'");
if ( !$user_name['name'] ) $badpage = TRUE;

$db->query( "SELECT d.mid, d.time_php, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.lenght, m.size, m.clip_online, (SELECT COUNT(DISTINCT mid) FROM ".PREFIX."_mservice_downloads WHERE user_id = '{$user_id}') as cnt FROM ".PREFIX."_mservice_downloads as d LEFT JOIN ".PREFIX."_mservice as m ON d.mid = m.mid WHERE d.user_id = '{$user_id}' GROUP BY d.mid ORDER BY time_php DESC, artist, title LIMIT ".$limit.",".$mscfg['track_page_lim'] );
if ( $db->num_rows() == 0 ) $badpage = TRUE;

if ( $badpage ) {
  if ($user_name['name']) $user_link = '(<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')';
  else $user_link = 'пользователем.';
  $mtitle .= '<h3 class="tit"><span style="color:#4E4E4E;">Просмотр скачанных треков за месяц '.$user_link.'</span></h3>';
  $mcontent .= 'Неверная страница либо ещё нет скачанных треков за месяц у этого пользователя, либо нет такого пользователя.<br />';
  $podmod = TRUE;
} else  {

  $mtitle .= '<h3 class="tit"><img src="{THEME}/images/downloads.png" /> &nbsp;<span style="color:#4E4E4E;">Просмотр скачанных треков за месяц (<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')</span></h3>';
  $mcontent .= '<div id="navigation_up"></div>';
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
  $r = 0;
  $i = 0;
  while ( $row = $db->get_row( ) ) {
    if (!$count) $count = $row['cnt'];
  
    $down_time[$i] = langdate( 'j F Y, l',$row['time_php']);
    if ($r == 0) $mcontent .= <<<HTML
<td class="titfav">Скачано: {$down_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
    else {
      if ($down_time[$i] !== $down_time[$i-1]) {
        $mcontent .= <<<HTML
<td class="titfav">Скачано: {$down_time[$i]}</td>
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
$r++;
$i++;
}
  $mcontent .= '</table>';
// Постраничная навигация
  $count_d = $count / $mscfg['track_page_lim'];

  for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;

  $plink = $config['http_home_url'].'music/monthuserdownloads-'.$user_id .'-page-'.$t2.'.html';

  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/monthuserdownloads-'.$user_id .'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
  }

  $link = $config['http_home_url'].'music/monthuserdownloads-'.$user_id .'-page-';
  $seo_mode = '.html';

  $npage = $page - 1;
  if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/monthuserdownloads-'.$user_id;
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
    else $podmod = TRUE;
  }
  $mcontent .= '</div>';
}
if ($podmod == TRUE)  {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
break;

// Просмотр скачиваний альбомов за месяц юзера (для админов)
case 'monthuserdownloads-albums' :

$user_id = abs(intval( $_REQUEST['user'] ));

$nam_e1 = 'Просмотр скачанных альбомов за месяц';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$metatags['title'] = 'Просмотр скачанных альбомов за месяц '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Просмотр скачанных альбомов за месяц на '.$config['description'];
$metatags['keywords'] = 'скачанные альбомы, музыкальный архив, '.$config['keywords'];

$user_name = $db->super_query( "SELECT name FROM ".USERPREFIX."_users WHERE user_id = '{$user_id}'");

$db->query( "SELECT d.aid, d.time_php, a.time, a.year as year_alb, a.album, a.artist, a.image_cover, a.genre, a.transartist, (SELECT COUNT(DISTINCT aid) FROM ".PREFIX."_mservice_downloads_albums WHERE user_id = '{$user_id}') as cnt FROM ".PREFIX."_mservice_downloads_albums as d LEFT JOIN ".PREFIX."_mservice_albums as a ON d.aid = a.aid WHERE d.user_id = '{$user_id}' GROUP BY d.aid ORDER BY d.time_php DESC LIMIT ".$limit.",".$mscfg['album_page_lim']);

if ( $db->num_rows() == 0 ) {
  $mtitle .= '<h3 class="tit" style="background:#FBDB85;"><span style="color:#4E4E4E;">Просмотр скачанных альбомов за месяц (<a href="'.$config[http_home_url].'user/'.urlencode($user_name[name]).'">'.$user_name[name].'</a>'.')</span></h3>';
  $mcontent .= 'Неверная страница либо ещё нет скачанных альбомов за месяц у этого пользователя.<br />';
  $podmod = TRUE;
} else  {

  $mtitle .= '<h3 class="tit" style="background:#FBDB85;"><img src="{THEME}/images/downloads.png" /> &nbsp;<span style="color:#4E4E4E;">Просмотр скачанных альбомов за месяц (<a href="'.$config[http_home_url].'user/'.urlencode($user_name[name]).'">'.$user_name[name].'</a>'.')</span></h3>';
  $i = 1;
  $mtitle .= '<table width="100%" style="margin:10px 0 0;"><tr>';
  $r = 0;
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
        
        if ($genre1 !== '') $alb_genre_tab = '<a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div>';
        else $alb_genre_tab = '';
        
      if (!$cnt_alb) $cnt_alb = $row['cnt'];
      if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
      else $year_alb = '('.$row['year_alb'].')';
      if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
      
      $down_time[$i-1] = langdate( 'j F Y, l',$row['time_php']);
    if ($r == 0) $mtitle .= <<<HTML
<td class="titfav" colspan="2" style="background:#E2EDF2;">Скачано: {$down_time[$i-1]}</td></tr>
<tr><td colspan="2" height="5"></td></tr>
<tr>
HTML;
    else {
      if (($down_time[$i-1] !== $down_time[$i-2]) && (($i % 2) == '1')) {
        $mtitle .= <<<HTML
<td class="titfav" colspan="2" style="background:#E2EDF2;">Скачано: {$down_time[$i-1]}</td></tr>
<tr><td colspan="2" height="5"></td></tr>
<tr>
HTML;
      }
    }

      $mtitle .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
      $i++;
      $r++;
      if (($i % 2) == '1') $mtitle .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
    }
    $mtitle .= '</tr></table></div>';

    if ($cnt_alb < 3) $podmod = TRUE;
    
// Постраничная навигация
$count_d = $cnt_alb / $mscfg['album_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/monthuserdownloads-albums-'.$user_id .'-page-'.$t2.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/monthuserdownloads-albums-'.$user_id .'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/monthuserdownloads-albums-'.$user_id .'-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/monthuserdownloads-albums-'.$user_id;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
else $next_page = '';

if ( $cnt_alb > $mscfg['album_page_lim'] ) {
  $mtitle .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $albums_to_view = $cnt_alb - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
  if ($albums_to_view < 3)  $podmod = TRUE;
}

}
if ($podmod == TRUE)  {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

break;

// Просмотр тревов, загруженных юзером (для админов)
case 'usertracks':

$user_id = abs(intval( $_REQUEST['user'] ));
$user_name = $db->super_query( "SELECT name FROM ".USERPREFIX."_users WHERE user_id = '{$user_id}'");
if ( !$user_name['name'] ) $badpage = TRUE;

$nam_e1 = 'Просмотр треков, загруженных юзером '.$user_name['name'];
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Просмотр треков пользователя '.$user_name['name'].' '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Просмотр треков, загруженных пользователем на '.$config['description'];
$metatags['keywords'] = 'загруженные треки, музыкальный архив mp3, '.$config['keywords'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'artist, title';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/usertracks-'.$user_id .'/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'title, artist';
  $sortactive = 'По названию трека &#8595;';
  $sortactive_no = '<li><a href="/music/usertracks-'.$user_id .'/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'/sort-3.html">По рейтингу &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'.html">По дате добавления &#8593;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = 'По рейтингу &#8595;';
  $sortactive_no = '<li><a href="/music/usertracks-'.$user_id .'/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'.html">По дате добавления &#8593;</a></li>';
}
else {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = 'По дате добавления &#8593;';
  $sortactive_no = '<li><a href="/music/usertracks-'.$user_id .'/sort-1.html">По исполнителю &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'/sort-2.html">По названию трека &#8595;</a></li><li><a href="/music/usertracks-'.$user_id .'/sort-3.html">По рейтингу &#8595;</a></li>';
}

$db->query( "SELECT mid, time, title, approve, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE uploader = '{$user_id}') as cnt FROM ".PREFIX."_mservice WHERE uploader = '{$user_id}' ORDER BY ".$sqlsort." LIMIT " . $limit . "," . $mscfg['track_page_lim'] );
if ( $db->num_rows() == 0 ) $badpage = TRUE;

if ( $badpage ) {
  if ($user_name['name']) $user_link = '(<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')';
  else $user_link = 'пользователем.';
  $mtitle .= '<h3 class="tit"><span style="color:#4E4E4E;">Просмотр загруженных треков '.$user_link.'</span></h3>';
  $mcontent .= 'Неверная страница либо ещё нет загруженных треков у этого пользователя, либо нет такого пользователя.<br />';
  $podmod = TRUE;
} else  {
  $mtitle .= '<h3 class="tit"><img src="{THEME}/images/uploader.png" /> &nbsp;<span style="color:#4E4E4E;">Просмотр загруженных треков (<a href="'.$config['http_home_url'].'user/'.urlencode($user_name['name']).'">'.$user_name['name'].'</a>'.')</span></h3>';
  $mcontent .= '<div id="navigation_up"></div>';

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

  if (!$count) $count = $row['cnt'];
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

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/usertracks-'.$user_id .'-page-'.$t2.$sortpage.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/usertracks-'.$user_id.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/usertracks-'.$user_id .'-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/usertracks-'.$user_id;
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

}
?>