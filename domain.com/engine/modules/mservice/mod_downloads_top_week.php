<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
$toptime = 604800;
//604800 секунд в неделе
$toptime = (time() - $toptime);
if (intval($num_downloads_top_week)) {
  $metatags['title'] = 'Топ скачиваний mp3 треков за неделю (ТОП '.$num_downloads_top_week.') на '.$config['home_title_short'];
  $metatags['description'] = 'Топ скачиваний mp3 треков за неделю (ТОП '.$num_downloads_top_week.') на '.$config['description'];
  $metatags['keywords'] = 'топ скачиваний, мп3 трек, топ '.$num_downloads_top_week.', музыкальный архив mp3, '.$config['keywords'];
}
else $num_downloads_top_week = 20;;

	$db->query( "SELECT d.mid, COUNT(d.mid) AS cnt, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.size, m.lenght, m.clip_online FROM ".PREFIX."_mservice_downloads d LEFT JOIN ".PREFIX."_mservice m ON d.mid = m.mid WHERE d.time_php > '$toptime' GROUP BY d.mid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,$num_downloads_top_week" );

	while( $row = $db->get_row( ) ) {
		$query_downloads_top_week[]=$row;
	}

// начинаем формировать таблицу с общими результатами
if (count($query_downloads_top_week) >= 10 ) {
	
	$downloads_top_week = '<div id="playlist"><table width="100%">
<tr>
<th colspan="5"><h3 class="tit_green"><a href="/music/downloadstopweek.html" title="Топ скачиваний mp3 треков за неделю" style="color:#3367AB;">Топ скачиваний mp3 треков за неделю</a> (ТОП '.$num_downloads_top_week.')</h3></th>
</tr></table>
<table>
<tr><td colspan="5">
<div class="bestmp3">...а также Топ скачиваний mp3 треков за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/downloadstop24hr.html" title="Топ скачиваний mp3 треков за сутки">сутки</a></li></ul></div>
</td></tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

for( $i=0; $i<count($query_downloads_top_week); $i++ ) {

	$artist_title = artistTitleStrlen($query_downloads_top_week[$i]['artist'], $query_downloads_top_week[$i]['title'], $mscfg['track_title_substr']);
	
	$t_link = $config['http_home_url'].'music/'.$query_downloads_top_week[$i]['mid'].'-mp3-'.totranslit( $query_downloads_top_week[$i]['artist'] ).'-'.totranslit( $query_downloads_top_week[$i]['title'] ).'.html';

	$downloads_top_week .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_downloads_top_week[$i]['filename'], $query_downloads_top_week[$i]['time'], $query_downloads_top_week[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_downloads_top_week[$i]['artist'].' - '.$query_downloads_top_week[$i]['title'].'">'.$artist_title.'</a> '.downloadsSongDownloadTop( $query_downloads_top_week[$i]['mid'], $query_downloads_top_week[$i]['download'], $query_downloads_top_week[$i]['view_count'], $query_downloads_top_week[$i]['cnt'] ).clipOnline( $query_downloads_top_week[$i]['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$query_downloads_top_week[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_downloads_top_week[$i]['size'] ).'</td>';

	if ( $is_logged ) $downloads_top_week .= '<td><div id="favorited-tracks-layer-'.$query_downloads_top_week[$i]['mid'].'-dt">'.ShowFavoritesListDownloadsTop( $query_downloads_top_week[$i]['mid'] ).'</div></td>';
	else $downloads_top_week .= '<td><div id="favorited-tracks-layer-'.$query_downloads_top_week[$i]['mid'].'"></div></td>';
	
	$downloads_top_week .= '<td align="center"><a href="'.$t_link.'" title="Скачать '.$query_downloads_top_week[$i]['artist'].' - '.$query_downloads_top_week[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}
$downloads_top_week .= '</table></div>';

} else $downloads_top_week = 'Меньше 10 скачиваний mp3 треков за неделю!';
?>