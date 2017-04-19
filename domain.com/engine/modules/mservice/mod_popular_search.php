<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

///////////////////////////////////// попул€рные поисковые запросы исполнителей - берЄм из кеша или из базы
///////////////////////////////////// попул€рные поисковые запросы исполнителей - берЄм из кеша или из базы
///////////////////////////////////// попул€рные поисковые запросы исполнителей - берЄм из кеша или из базы
$query_search_artists = unserialize(dle_cache2( "popular_search_artists", $config['skin'] ));

if ( !$query_search_artists ) {

$db->query( "SELECT search_text, COUNT(search_text) AS cnt FROM " . PREFIX . "_mservice_search WHERE (search_type = '2' AND count != '0' AND time_php > '$toptime') GROUP BY search_text HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,10" );

	while( $row = $db->get_row( ) ) {
		$query_search_artists[] = $row;
	}
	create_cache2( "popular_search_artists", serialize($query_search_artists), $config['skin'] );
}
//формируем результаты
if ( count($query_search_artists) >= 10 )  {
  $popular_search_artists = '<h3 class="tit" style="background:#DD88F7; margin:10px 0;">ѕопул€рные исполнители</h3>';
  
  $popular_search_artists .= '<table width="100%" style="margin:15px 0 5px;"><tr>';
  $ii=1;
  for( $i=0; $i<count($query_search_artists); $i++ ) {

    if( strlen( $query_search_artists[$i]['search_text'] ) > 90 ) $search_text_artist = substr( $query_search_artists[$i]['search_text'], 0, 85 )." ...";
    else $search_text_artist = $query_search_artists[$i]['search_text'];
	
    $search_text64_artist = str_replace('/','_',str_replace('+','*',base64_encode($query_search_artists[$i]['search_text'])));
	
    $search_link_artist = '<a href="/music/search-2-1-'.$search_text64_artist.'.html" title="'.$query_search_artists[$i]['search_text'].'"><b>'.$search_text_artist.'</b></a>';
	
    $popular_search_artists .= '<td align="left" style="background-image: url({THEME}/images/artistlist.png); background-repeat:no-repeat;" width="50%" height="10"><div class="artists">'.$search_link_artist.countSearch( $query_search_artists[$i]['cnt'] ).'</div><div class="dashed inactive"></div></td>';
    $ii++;
    if (($ii % 2) == '1') $popular_search_artists .= '</tr><tr><td colspan="2" height="5"></td></tr><tr>';

  }
  $popular_search_artists .= '</tr></table>';
} else $popular_search_artists = '';

//////////////////////////////////////// попул€рные поисковые запросы названи€ песен - берЄм из кеша или из базы
//////////////////////////////////////// попул€рные поисковые запросы названи€ песен - берЄм из кеша или из базы
//////////////////////////////////////// попул€рные поисковые запросы названи€ песен - берЄм из кеша или из базы
$query_search_tracks = unserialize(dle_cache2( "popular_search_tracks", $config['skin'] ));

if ( !$query_search_tracks ) {

$db->query( "SELECT search_text, COUNT(search_text) AS cnt FROM " . PREFIX . "_mservice_search WHERE (search_type = '3' AND count != '0' AND time_php > '$toptime') GROUP BY search_text HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,10" );

	while( $row = $db->get_row( ) ) {
		$query_search_tracks[] = $row;
	}
	create_cache2( "popular_search_tracks", serialize($query_search_tracks), $config['skin'] );
}
//формируем результаты
if ( count($query_search_tracks) >= 10 )  {
  $popular_search_tracks = '<h3 class="tit_green">ѕопул€рные треки</h3>';
  
  $popular_search_tracks .= '<table width="100%" style="margin:15px 0 5px;"><tr>';
  $ii=1;
  for( $i=0; $i<count($query_search_tracks); $i++ ) {

    if( strlen( $query_search_tracks[$i]['search_text'] ) > 90 ) $search_text_track = substr( $query_search_tracks[$i]['search_text'], 0, 85 )." ...";
    else $search_text_track = $query_search_tracks[$i]['search_text'];
	
    $search_text64_track = str_replace('/','_',str_replace('+','*',base64_encode($query_search_tracks[$i]['search_text'])));
	
    $search_link_track = '<a href="/music/search-3-1-'.$search_text64_track.'.html" title="'.$query_search_tracks[$i]['search_text'].'"><b>'.$search_text_track.'</b></a>';
	
    $popular_search_tracks .= '<td align="left" style="background-image: url({THEME}/images/artistlist.png); background-repeat:no-repeat;" width="50%" height="10"><div class="artists">'.$search_link_track.countSearch( $query_search_tracks[$i]['cnt'] ).'</div><div class="dashed inactive"></div></td>';
    $ii++;
    if (($ii % 2) == '1') $popular_search_tracks .= '</tr><tr><td colspan="2" height="5"></td></tr><tr>';

  }
  $popular_search_tracks .= '</tr></table>';
} else $popular_search_tracks = '';

/////////////////////////////////////////////// попул€рные поисковые запросы альбомов - берЄм из кеша или из базы
/////////////////////////////////////////////// попул€рные поисковые запросы альбомов - берЄм из кеша или из базы
/////////////////////////////////////////////// попул€рные поисковые запросы альбомов - берЄм из кеша или из базы
$query_search_albums = unserialize(dle_cache2( "popular_search_albums", $config['skin'] ));

if ( !$query_search_albums ) {

$db->query( "SELECT d.aid, COUNT(d.aid) as cnt, a.year as year_alb, a.album, a.artist, a.image_cover, a.genre, a.transartist FROM " . PREFIX . "_mservice_downloads_albums d LEFT JOIN " . PREFIX . "_mservice_albums a ON d.aid = a.aid GROUP BY a.aid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,10" );

	while( $row = $db->get_row( ) ) {
		$query_search_albums[] = $row;
	}
	create_cache2( "popular_search_albums", serialize($query_search_albums), $config['skin'] );
}
//формируем результаты
if ( count($query_search_albums) >= 10 )  {
  $popular_search_albums = '<h3 class="tit" style="background:#FBDB85; margin:10px 0;">ѕопул€рные альбомы</h3>';
  
  $popular_search_albums .= '<table width="100%" style="margin:15px 0 5px;"><tr>';
  
  $ii=1;
  for( $i=0; $i<count($query_search_albums); $i++ ) {
  
        $genreid = intval($query_search_albums[$i]['genre']);
        if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
        elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
        elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
        elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
        elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Ўансон';}
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
          
        if ( $query_search_albums[$i]['year_alb'] == '0' OR $query_search_albums[$i]['year_alb'] == '00' OR $query_search_albums[$i]['year_alb'] == '000' OR $query_search_albums[$i]['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$query_search_albums[$i]['year_alb'].')';
        
        if ($query_search_albums[$i]['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $query_search_albums[$i]['image_cover'];
        
        $popular_search_albums .= '<td width="50%" valign="top"><a href="/music/'.$query_search_albums[$i]['aid'].'-album-'.$query_search_albums[$i]['transartist'].'-'.totranslit( $query_search_albums[$i]['album'] ).'.html" class="albums" title="—мотреть альбом '.$query_search_albums[$i]['artist'].' - '.$query_search_albums[$i]['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$query_search_albums[$i]['artist'].' - '.$query_search_albums[$i]['album'].'" title="бесплатно скачать альбом '.$query_search_albums[$i]['artist'].' - '.$query_search_albums[$i]['album'].'" /><b>'.artistAlbumStrlen( $query_search_albums[$i]['artist'] ).'</b><br />'.artistAlbumStrlen( $query_search_albums[$i]['album'] ).'<br />'.$year_alb.'</a><span style="color:#a1a1a1; margin-left:10px;">'.countSearch( $query_search_albums[$i]['cnt']).'</span><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$query_search_albums[$i]['aid'].'">'.showFavAlbumList( $query_search_albums[$i]['aid'] ).'</div></td>';
        $ii++;
        if (($ii % 2) == '1') $popular_search_albums .= '</tr><tr><td colspan="2" height="5"></td></tr><tr>';
      }
  $popular_search_albums .= '</tr></table></div>';
} else $popular_search_albums = '';

?>