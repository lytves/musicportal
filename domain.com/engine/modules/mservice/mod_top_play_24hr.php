<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
$toptime = 86400;
//86400 секунд в сутках
$toptime = (time() - $toptime);

$num_downloads_top_24hr = 50;
$metatags['title'] = 'Топ прослушиваний mp3 треков за сегодня (ТОП '.$num_downloads_top_24hr.') на '.$config['home_title_short'];
$metatags['description'] = 'Топ прослушиваний mp3 треков за сегодня (ТОП '.$num_downloads_top_24hr.') на '.$config['description'];
$metatags['keywords'] = 'топ прослушиваний, мп3 трек, топ '.$num_downloads_top_24hr.', музыкальный архив mp3, '.$config['keywords'];

$query_top_play_24hr = unserialize(dle_cache( "top_play_24hr", $config['skin'] ));

if ( !$query_top_play_24hr ) {
	$db->query( "SELECT d.mid, COUNT(d.mid) AS cnt, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.size, m.lenght, m.clip_online FROM ".PREFIX."_mservice_plays d LEFT JOIN ".PREFIX."_mservice m ON d.mid = m.mid WHERE d.time_php > '$toptime' GROUP BY d.mid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,$num_downloads_top_24hr" );

	while( $row = $db->get_row( ) ) {
		$query_top_play_24hr[]=$row;
	}
  create_cache( "top_play_24hr", serialize($query_top_play_24hr), $config['skin'] );
}
// начинаем формировать таблицу с общими результатами
if (count($query_top_play_24hr) >= 10 ) {

	$top_play_24hr = '<div id="playlist"><table width="100%" style="margin:15px 0 5px;">
<tr>
<th colspan="5"><h3 class="tit_green"><a href="/music/downloadstop24hr.html" title="Топ прослушиваний mp3 треков за сегодня" style="color:#3367AB;">Топ прослушиваний mp3 треков за сегодня</a> (ТОП '.$num_downloads_top_24hr.')</h3></th>
</tr>';

$top_play_24hr .= '
<tr><td colspan="5">
<div class="bestmp3">...а также Топ прослушиваний mp3 треков за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/topplayweek.html" title="ТОП прослушиваний mp3 за неделю">неделю</a></li></ul></div>
</td></tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

for( $i=0; $i<count($query_top_play_24hr); $i++ ) {

	$artist_title = artistTitleStrlen($query_top_play_24hr[$i]['artist'], $query_top_play_24hr[$i]['title'], $mscfg['track_title_substr']);
	
	$t_link = $config['http_home_url'].'music/'.$query_top_play_24hr[$i]['mid'].'-mp3-'.totranslit( $query_top_play_24hr[$i]['artist'] ).'-'.totranslit( $query_top_play_24hr[$i]['title'] ).'.html';

	$top_play_24hr .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_top_play_24hr[$i]['filename'], $query_top_play_24hr[$i]['time'], $query_top_play_24hr[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_top_play_24hr[$i]['artist'].' - '.$query_top_play_24hr[$i]['title'].'">'.$artist_title.'</a> '.downloadsSongDownloadTop( $query_top_play_24hr[$i]['mid'], $query_top_play_24hr[$i]['download'], $query_top_play_24hr[$i]['view_count'], $query_top_play_24hr[$i]['cnt'] ).clipOnline( $query_top_play_24hr[$i]['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$query_top_play_24hr[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_top_play_24hr[$i]['size'] ).'</td>';

	if ( $is_logged ) $top_play_24hr .= '<td><div id="favorited-tracks-layer-'.$query_top_play_24hr[$i]['mid'].'-dt">'.ShowFavoritesListDownloadsTop( $query_top_play_24hr[$i]['mid'] ).'</div></td>';
	else $top_play_24hr .= '<td><div id="favorited-tracks-layer-'.$query_top_play_24hr[$i]['mid'].'"></div></td>';
	
	$top_play_24hr .= '<td align="center"><a href="'.$t_link.'" title="Скачать '.$query_top_play_24hr[$i]['artist'].' - '.$query_top_play_24hr[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}
$top_play_24hr .= '</table></div>';

} else $top_play_24hr = 'Меньше 10 прослушиваний mp3 треков за сегодня!';
?>