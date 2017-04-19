<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
//этот список треков выводится на главной странице

$toptime = 86400;
//86400 секунд в сутках
$toptime = (time() - $toptime);

$query_24hr = unserialize(dle_cache( "bestmp3_24hr", $config['skin'] ));

if ( !$query_24hr ) {
	$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online, (SELECT COUNT(*) FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT 0,10" );

	while( $row = $db->get_row( ) ) {
		$query_24hr[]=$row;
	}
	create_cache( "bestmp3_24hr", serialize($query_24hr), $config['skin'] );
}

$bestmp3_24hr = '<table width="100%" style="margin-bottom:20px;">
<tr>
<th colspan="5"><h3 class="tit">ТОП лучших новинок <span>mp3 за сутки</span> от domain.com</h3></th>
</tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart'; else $ifsmart = '';

for( $i=0; $i<count($query_24hr); $i++ ) {
	if (!isset($count_24hr)) {
		$count_24hr = $query_24hr[$i]['cnt'];
		if ($count_24hr < 10) {
			$bestmp3_24hr = '';
			break;
		}
	}
	
	$artist_title = artistTitleStrlen($query_24hr[$i]['artist'], $query_24hr[$i]['title'], $mscfg['track_title_substr']);
	
	$t_link = $config['http_home_url'].'music/'.$query_24hr[$i]['mid'].'-mp3-'.totranslit( $query_24hr[$i]['artist'] ).'-'.totranslit( $query_24hr[$i]['title'] ).'.html';

	$bestmp3_24hr .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_24hr[$i]['filename'], $query_24hr[$i]['time'], $query_24hr[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_24hr[$i]['artist'].' - '.$query_24hr[$i]['title'].'">'.$artist_title.'</a> '.downloadsSong( $query_24hr[$i]['mid'], $query_24hr[$i]['download'], $query_24hr[$i]['view_count'] ).clipOnline( $query_24hr[$i]['clip_online'] ).'</div></div><div class="player inactive"></div></div></td>
<td align="center">'.$query_24hr[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_24hr[$i]['size'] ).'</td>';

	if ( $is_logged ) $bestmp3_24hr .= '<td><div id="favorited-tracks-layer-'.$query_24hr[$i]['mid'].'-h">'.ShowFavoritesListTop24hr( $query_24hr[$i]['mid'] ).'</div></td>';
	else $bestmp3_24hr .= '<td><div id="favorited-tracks-layer-'.$query_24hr[$i]['mid'].'"></div></td>';
  
  $bestmp3_24hr .= '<td align="center"><a href="'.$t_link.'" title="Скачать '.$query_24hr[$i]['artist'].' - '.$query_24hr[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}

if ($count_24hr >= 10) $bestmp3_24hr .= '<tr><td colspan="5" style="padding-left:20px;"><div class="bestmp3" style="height:auto; padding:0; text-align:left;"><ul class="bestmp3s" style="padding:0; position:static;"><li><a href="/music/bestmp3_24hr.html" title="Смотреть и скачать бесплатно новинки mp3 за сутки">Смотреть все новинки mp3 за сутки</a></li></ul></div></td></tr></table>';

?>
