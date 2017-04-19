<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
//этот список треков выводитс€ на главной странице

$toptime = 2592000;
//2592000 секунд в мес€це
$toptime = (time() - $toptime);

$query_month = unserialize(dle_cache( "bestmp3_month", $config['skin'] ));

if ( !$query_month ) {
	$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online, (SELECT COUNT(*) FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime')) as cnt FROM " . PREFIX . "_mservice WHERE (approve = '1' AND time > '$toptime') ORDER BY download DESC, view_count DESC LIMIT 0,30" );

	while( $row = $db->get_row( ) ) {
		$query_month[]=$row;
	}
	create_cache( "bestmp3_month", serialize($query_month), $config['skin'] );
}

$bestmp3_month = '<table width="100%">
<tr>
<th colspan="5"><h3 class="tit">“ќѕ лучших новинок <span>mp3 за мес€ц</span> от domain.com</h3></th>
</tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">¬рем€</th>
<th width="10%">–азмер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

for( $i=0; $i<count($query_month); $i++ ) {
	if (!isset($count_month)) {
		$count_month = $query_month[$i]['cnt'];
		if ($count_month < 30) {
			$bestmp3_month = '';
			break;
		}
	}

	$artist_title = artistTitleStrlen($query_month[$i]['artist'], $query_month[$i]['title'], $mscfg['track_title_substr']);
		
	$t_link = $config['http_home_url'].'music/'.$query_month[$i]['mid'].'-mp3-'.totranslit( $query_month[$i]['artist'] ).'-'.totranslit( $query_month[$i]['title'] ).'.html';

	$bestmp3_month .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_month[$i]['filename'], $query_month[$i]['time'], $query_month[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_month[$i]['artist'].' - '.$query_month[$i]['title'].'">'.$artist_title.'</a> '.downloadsSong( $query_month[$i]['mid'], $query_month[$i]['download'], $query_month[$i]['view_count'] ).clipOnline( $query_month[$i]['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$query_month[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_month[$i]['size'] ).'</td>';

	if ( $is_logged ) $bestmp3_month .= '<td><div id="favorited-tracks-layer-'.$query_month[$i]['mid'].'-m">'.ShowFavoritesListTopMonth( $query_month[$i]['mid'] ).'</div></td>';
	else $bestmp3_month .= '<td><div id="favorited-tracks-layer-'.$query_month[$i]['mid'].'"></div></td>';
	
  $bestmp3_month .= '<td align="center"><a href="'.$t_link.'" title="—качать '.$query_month[$i]['artist'].' - '.$query_month[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}

if ($count_month >= 30) $bestmp3_month .= '<tr><td colspan="5" style="padding-left:20px;"><div class="bestmp3" style="height:auto; padding:0; text-align:left;"><ul class="bestmp3s" style="padding:0; position:static;"><li><a href="/music/bestmp3_month.html" title="—мотреть и скачать бесплатно новинки mp3 за мес€ц">—мотреть все новинки mp3 за мес€ц</a></li></ul></div></td></tr></table>';

?>
