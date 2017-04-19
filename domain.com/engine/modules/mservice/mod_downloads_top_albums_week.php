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
//604800 ������ � ������
$toptime = (time() - $toptime);
if (intval($num_downloads_top_albums_week)) {
  $metatags['title'] = '��� ���������� ����������� �������� �� ������ (��� '.$num_downloads_top_albums_week.') �� '.$config['home_title_short'];
  $metatags['description'] = '��� ���������� ����������� �������� �� ������ (��� '.$num_downloads_top_albums_week.') �� '.$config['description'];
  $metatags['keywords'] = '��� ����������, ����������� ������, ��� '.$num_downloads_top_albums_week.', ����������� ����� mp3, '.$config['keywords'];
}
else $num_downloads_top_albums_week = 10;

  $db->query( "SELECT d.aid, COUNT(d.aid) as cnt, a.year as year_alb, a.album, a.artist, a.image_cover, a.genre, a.transartist FROM ".PREFIX."_mservice_downloads_albums d LEFT JOIN ".PREFIX."_mservice_albums a ON d.aid = a.aid WHERE d.time_php > '$toptime' GROUP BY a.aid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,$num_downloads_top_albums_week" );
  
	while( $row = $db->get_row( ) ) {
		$query_downloads_top_albums_week[]=$row;
	}

// �������� ����������� ������� � ������ ������������
if (count($query_downloads_top_albums_week) >= 10 ) {

	$downloads_top_albums_week = '<div class="album_tracks_ramka" style="margin-bottom:5px;"><table width="100%"><tr><th colspan="2"><h3 class="tit_green"><a href="/music/downloadstopalbumsweek.html" title="��� ���������� ����������� �������� �� ������" style="color:#3367AB;">��� ���������� ����������� �������� �� ������</a> (��� '.$num_downloads_top_albums_week.')</h3></th></tr><tr><td colspan="2" height="5"></td></tr><tr><td colspan="5"><div class="bestmp3">...� ����� ��� ���������� ����������� �������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/downloadstopalbums24hr.html" title="��� ���������� ����������� �������� �� �����">�����</a></li></ul></div></td></tr><tr><td colspan="2" height="5"></td></tr><tr>';

for( $i=0; $i<count($query_downloads_top_albums_week); $i++ ) {

  $genreid = $query_downloads_top_albums_week[$i]['genre'];
  if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
  elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
  elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
  elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
  elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = '������';}
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

  if ( $member_id['user_group'] == 1 ) $cnt_downloads = '<span style="color:#a1a1a1;">('.$query_downloads_top_albums_week[$i]['cnt'].')</span> '; else $cnt_downloads = '';
  if ( $query_downloads_top_albums_week[$i]['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
  else $year_alb = '('.$query_downloads_top_albums_week[$i]['year_alb'].')';
  if ($query_downloads_top_albums_week[$i]['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $query_downloads_top_albums_week[$i]['image_cover'];
  
  $downloads_top_albums_week .= '<td width="50%" valign="top"><a href="/music/'.$query_downloads_top_albums_week[$i]['aid'].'-album-'.$query_downloads_top_albums_week[$i]['transartist'].'-'.totranslit( $query_downloads_top_albums_week[$i]['album'] ).'.html" class="albums" title="�������� ������ '.$query_downloads_top_albums_week[$i]['artist'].' - '.$query_downloads_top_albums_week[$i]['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="��������� ������� ������ '.$query_downloads_top_albums_week[$i]['artist'].' - '.$query_downloads_top_albums_week[$i]['album'].'" title="��������� ������� ������ '.$query_downloads_top_albums_week[$i]['artist'].' - '.$query_downloads_top_albums_week[$i]['album'].'" /><b>'.artistAlbumStrlen( $query_downloads_top_albums_week[$i]['artist'] ).'</b><br />'.artistAlbumStrlen( $query_downloads_top_albums_week[$i]['album'] ).'<br />'.$year_alb.'</a>'.$cnt_downloads.'<div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$query_downloads_top_albums_week[$i]['aid'].'">'.showFavAlbumList( $query_downloads_top_albums_week[$i]['aid'] ).'</div></td>';
  if (($i % 2) == '1') $downloads_top_albums_week .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
}
$downloads_top_albums_week .= '</tr></table></div>';

} else $downloads_top_albums_week = '������ 10 ���������� ����������� �������� �� ������!';
?>