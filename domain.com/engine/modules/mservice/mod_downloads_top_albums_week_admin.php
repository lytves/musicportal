<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$nam_e1 = 'ТОП скачиваний музыкальных альбомов за неделю';

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ТОП скачиваний музыкальных альбомов за неделю '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'ТОП скачиваний музыкальных альбомов за неделю на '.$config['description'];
$metatags['keywords'] = 'топ скачиваний, музыкальный альбом, топ альбомы, музыкальный архив mp3, '.$config['keywords'];
  
$toptime = 604800;
//604800 секунд в неделе
$toptime = (time() - $toptime);

$db->query( "SELECT d.aid, COUNT(d.aid) as cnt, a.year as year_alb, a.album, a.artist, a.image_cover, a.genre, a.transartist, (SELECT COUNT(DISTINCT aid) FROM ".PREFIX."_mservice_downloads_albums WHERE time_php > '$toptime') as count FROM ".PREFIX."_mservice_downloads_albums d LEFT JOIN ".PREFIX."_mservice_albums a ON d.aid = a.aid WHERE d.time_php > '$toptime' GROUP BY a.aid HAVING cnt > 0 ORDER BY cnt DESC LIMIT ".$limit."," . $mscfg['album_page_lim'] );

$mtitle = '<h3 class="tit">ТОП скачиваний музыкальных альбомов за неделю</h3>';

if ( $db->num_rows( ) == 0 ) $stop[] = 'Нет альбомов для этой страницы ТОП скачиваний музыкальных альбомов за неделю';
else {
	$mcontent ='<div class="album_tracks_ramka" style="margin-bottom:5px;"><table width="100%"><tr><td colspan="2" height="15"></td></tr><tr>';
  $mcontent .= '<div id="navigation_up"></div>';
  $i = 1;
  while( $row = $db->get_row( ) ) {
		if (!$count) $count = $row['count'];
		
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

    if ( $member_id['user_group'] == 1 ) $cnt_alb = '<span style="color:#a1a1a1;">('.$row['cnt'].')</span> '; else $cnt_alb = '';
    if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
    else $year_alb = '('.$row['year_alb'].')';
    if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
  
    $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a>'.$cnt_alb.'<div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
    $i++;
    if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
  }
  $mcontent .= '</tr></table></div>';
  
	// Постраничная навигация
	$count_d = $count / $mscfg['album_page_lim'];

	for ( $t = 0; $count_d > $t; $t++ ) {
		$t2 = $t + 1;
		$plink = $config['http_home_url'].'music/downloadstopalbumsweek_admin-page-'.$t2.'.html';
  
		if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
		else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/downloadstopalbumsweek_admin.html';
					$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';
				}
			$array[$t2] = 1;
		}

	$link = $config['http_home_url'].'music/downloadstopalbumsweek_admin-page-';
	$seo_mode = '.html';

	$npage = $page - 1;
	if ( isset($array[$npage]) ) {
    if ($npage ==1 ) {
      $linkdop = $config['http_home_url'].'music/downloadstopalbumsweek_admin';
      $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';
    }
    else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';
	}
	else $prev_page = '';
	$npage = $page + 1;
	if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
	else $next_page = '';
	
	if ( $count > $mscfg['album_page_lim'] ) {
		$mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
		$albums_to_view = $count - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
		if ($albums_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
	}
}
?>