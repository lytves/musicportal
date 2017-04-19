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

$best_down24hr = dle_cache2( "best_down24hr", $config['skin'] );

if ( !$best_down24hr ) {

	$sql_result = $db->query( "SELECT d.mid, COUNT(d.mid) AS cnt, m.title, m.artist, m.transartist, m.clip_online FROM ".PREFIX."_mservice_downloads d LEFT JOIN ".PREFIX."_mservice m ON d.mid = m.mid where d.time_php > '$toptime' GROUP BY d.mid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0, " . ($mscfg['block_bt_count']*2) );

	if ( $db->num_rows($sql_result) > 0 ) {
  $i = 0;
	while( $row = $db->get_row( $sql_result ) ) {
	
    $artist_title = artistTitleStrlenShort($row['artist'], $row['title']);
    $t_link = $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html';

    $best_down24hr .= '&raquo; <a class="bestart" style="font-weight:normal;" href="'.$t_link.'" title="'.$row['artist'].' - '.$row['title'].'">'.$artist_title.'</a> '.clipOnline( $row['clip_online'] ).'<br />';

    $i++;
    if ($i == 20) break;
    }
	if ( $db->num_rows($sql_result) > 39 ) $best_down24hr .= '<div id="mservice_best_down24hr" style="padding:0 10px;"><a href="#" class="bestart_bak" style="border-bottom:0 none;" onclick="moreBestDown24hr( ); return false;">+ ещё 20 скачиваемых треков</a></div>';
  create_cache2( "best_down24hr", $best_down24hr, $config['skin'] );
  
// дальше ещё 20 треков
  if ( $db->num_rows($sql_result) > 39 ) {
     $best_down24hr2 = '';
     while( $row = $db->get_row( $sql_result ) ) {
     $artist_title = artistTitleStrlenShort($row['artist'], $row['title']);
     $t_link = $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html';

     $best_down24hr2 .= '&raquo; <a class="bestart" style="font-weight:normal;" href="'.$t_link.'" title="'.$row['artist'].' - '.$row['title'].'">'.$artist_title.'</a> '.clipOnline( $row['clip_online'] ).'<br />';
     $i++;
     if ($i == 40) break;
      }
      create_cache2( "best_down24hr2", $best_down24hr2, $config['skin'] );
  }
  } else {
    $best_down24hr = 'Нет скачиваемых треков';
    create_cache2( "best_down24hr", $best_down24hr, $config['skin'] );
  }
}

?>