<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$newest_tracks = dle_cache2( "newest_tracks", $config['skin'] );

if ( !$newest_tracks ) {

	$db->query( "SELECT mid, artist, title, clip_online FROM " . PREFIX . "_mservice WHERE approve = '1' ORDER BY time DESC LIMIT 0," . $mscfg['block_bt_count'] );

	while( $row = $db->get_row( ) ) {
		
		$artist_title = artistTitleStrlenShort($row['artist'], $row['title']);
			
		$t_link = $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html';

		$newest_tracks .= '&raquo; <a class="bestart" style="font-weight:normal;" href="'.$t_link.'" title="'.$row['artist'].' - '.$row['title'].'">'.$artist_title.'</a> '.clipOnline( $row['clip_online'] ).'<br />';
}

create_cache2( "newest_tracks", $newest_tracks, $config['skin'] );

}

?>