<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$nam_e1 = 'Топ скачиваний mp3 за сутки';

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'Топ скачиваний mp3 за сутки '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Топ скачиваний mp3 за сутки на '.$config['description'];
$metatags['keywords'] = 'музыкальные новинки 2012, скачать бесплатно, новая музыка мп3, скачать mp3, кроликов мп3 нет, новая музыка, хиты 2012, клубная новая музыка, хитовые новинки 2012';

$toptime = 86400;
//86400 секунд в сутках
$toptime = (time() - $toptime);

$db->query( "SELECT d.mid, COUNT(d.mid) AS cnt, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.size, m.lenght, m.clip_online, (SELECT COUNT(DISTINCT mid) FROM ".PREFIX."_mservice_downloads WHERE time_php > '$toptime') as count FROM ".PREFIX."_mservice_downloads d LEFT JOIN ".PREFIX."_mservice m ON d.mid = m.mid WHERE d.time_php > '$toptime' GROUP BY d.mid HAVING cnt > 0 ORDER BY cnt DESC LIMIT ".$limit."," . $mscfg['track_page_lim'] );

$mtitle = '<h3 class="tit">Топ скачиваний mp3 за сутки</h3>';

if ( $db->num_rows( ) == 0 ) $stop[] = 'Нет треков для этой страницы Топ скачиваний mp3 за сутки';
else {
	$mcontent = <<<HTML
<div id="playlist"><table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;
	$mcontent .= '<div id="navigation_up"></div>';
if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

	while( $row = $db->get_row( ) ) {
		if (!$count) $count = $row['count'];
	
		$artist_title = artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']);

		$t_link = $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html';

		$mcontent .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$row['artist'].' - '.$row['title'].'">'.$artist_title.'</a> '.downloadsSongDownloadTop( $row['mid'], $row['download'], $row['view_count'], $row['cnt'] ).clipOnline( $row['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$row['lenght'].'</td>
<td align="center">'.formatsize( $row['size'] ).'</td>';

		if ( $is_logged ) $mcontent .= '<td><div id="favorited-tracks-layer-'.$row['mid'].'-dt">'.ShowFavoritesListDownloadsTop( $row['mid'] ).'</div></td>';
		else $mcontent .= '<td><div id="favorited-tracks-layer-'.$row['mid'].'"></div></td>';
		
		$mcontent .= '<td align="center"><a href="'.$t_link.'" title="Скачать '.$row['artist'].' - '.$row['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
	}
	$mcontent .= '</table></div>';
	
	// Постраничная навигация
	$count_d = $count / $mscfg['track_page_lim'];

	for ( $t = 0; $count_d > $t; $t++ ) {
		$t2 = $t + 1;
		$plink = $config['http_home_url'].'music/downloadstop24hr_admin-page-'.$t2.'.html';
  
		if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
		else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/downloadstop24hr_admin.html';
					$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';
				}
			$array[$t2] = 1;
		}

	$link = $config['http_home_url'].'music/downloadstop24hr_admin-page-';
	$seo_mode = '.html';

	$npage = $page - 1;
	if ( isset($array[$npage]) ) {
    if ($npage ==1 ) {
      $linkdop = $config['http_home_url'].'music/downloadstop24hr_admin';
      $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';
    }
    else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';
	}
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

	}
}
?>