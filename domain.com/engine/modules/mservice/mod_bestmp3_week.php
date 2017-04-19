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

$toptime = 604800;
//604800 секунд в неделе
$toptime = (time() - $toptime);

$query_week = unserialize(dle_cache( "bestmp3_week", $config['skin'] ));

if ( !$query_week ) {
	$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online, (SELECT COUNT(*) FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT 0,20" );

	while( $row = $db->get_row( ) ) {
		$query_week[]=$row;
	}
	create_cache( "bestmp3_week", serialize($query_week), $config['skin'] );
}

$bestmp3_week = '<table width="100%" style="margin-bottom:20px;">
<tr>
<th colspan="5"><h3 class="tit">ТОП лучших новинок <span>mp3 за неделю</span> от domain.com</h3></th>
</tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

for( $i=0; $i<count($query_week); $i++ ) {
	if (!isset($count_week)) {
		$count_week = $query_week[$i]['cnt'];
		if ($count_week < 20) {
			$bestmp3_week = '';
			break;
		}
	}

	$artist_title = artistTitleStrlen($query_week[$i]['artist'], $query_week[$i]['title'], $mscfg['track_title_substr']);

	$t_link = $config['http_home_url'].'music/'.$query_week[$i]['mid'].'-mp3-'.totranslit( $query_week[$i]['artist'] ).'-'.totranslit( $query_week[$i]['title'] ).'.html';

	$bestmp3_week .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_week[$i]['filename'], $query_week[$i]['time'], $query_week[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_week[$i]['artist'].' - '.$query_week[$i]['title'].'">'.$artist_title.'</a> '.downloadsSong( $query_week[$i]['mid'], $query_week[$i]['download'], $query_week[$i]['view_count'] ).clipOnline( $query_week[$i]['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$query_week[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_week[$i]['size'] ).'</td>';

	if ( $is_logged ) $bestmp3_week .= '<td><div id="favorited-tracks-layer-'.$query_week[$i]['mid'].'-w">'.ShowFavoritesListTopWeek( $query_week[$i]['mid'] ).'</div></td>';
	else $bestmp3_week .= '<td><div id="favorited-tracks-layer-'.$query_week[$i]['mid'].'"></div></td>';
	
  $bestmp3_week .= '<td align="center"><a href="'.$t_link.'" title="Скачать '.$query_week[$i]['artist'].' - '.$query_week[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}

if ($count_week >= 20) $bestmp3_week .= '<tr><td colspan="5" style="padding-left:20px;"><div class="bestmp3" style="height:auto; padding:0; text-align:left;"><ul class="bestmp3s" style="padding:0; position:static;"><li><a href="/music/bestmp3_week.html" title="Смотреть и скачать бесплатно новинки mp3 за неделю">Смотреть все новинки mp3 за неделю</a></li></ul></div></td></tr></table>';

?>
