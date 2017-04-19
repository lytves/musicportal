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
//86400 ������ � ������
$toptime = (time() - $toptime);
if (intval($num_downloads_top_24hr)) {
  $metatags['title'] = '��� ���������� mp3 ������ �� ����� (��� '.$num_downloads_top_24hr.') �� '.$config['home_title_short'];
  $metatags['description'] = '��� ���������� mp3 ������ �� ����� (��� '.$num_downloads_top_24hr.') �� '.$config['description'];
  $metatags['keywords'] = '��� ����������, ��3 ����, ��� '.$num_downloads_top_24hr.', ����������� ����� mp3, '.$config['keywords'];
}
else $num_downloads_top_24hr = 20;

	$db->query( "SELECT d.mid, COUNT(d.mid) AS cnt, m.time, m.title, m.artist, m.download, m.view_count, m.filename, m.hdd, m.size, m.lenght, m.clip_online FROM ".PREFIX."_mservice_downloads d LEFT JOIN ".PREFIX."_mservice m ON d.mid = m.mid WHERE d.time_php > '$toptime' GROUP BY d.mid HAVING cnt > 0 ORDER BY cnt DESC LIMIT 0,$num_downloads_top_24hr" );

	while( $row = $db->get_row( ) ) {
		$query_downloads_top_24hr[]=$row;
	}

// �������� ����������� ������� � ������ ������������
if (count($query_downloads_top_24hr) >= 10 ) {

	$downloads_top_24hr = '<div id="playlist"><table width="100%">
<tr>
<th colspan="5"><h3 class="tit_green"><a href="/music/downloadstop24hr.html" title="��� ���������� mp3 ������ �� �����" style="color:#3367AB;">��� ���������� mp3 ������ �� �����</a> (��� '.$num_downloads_top_24hr.')</h3></th>
</tr></table>
<table>
<tr><td colspan="5">
<div class="bestmp3">...� ����� ��� ���������� mp3 ������ ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/downloadstopweek.html" title="��� ���������� mp3 ������ �� ������">������</a></li></ul></div>
</td></tr>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr>';

if ($config['skin'] == 'smartphone') $ifsmart = 'smart';

for( $i=0; $i<count($query_downloads_top_24hr); $i++ ) {

	$artist_title = artistTitleStrlen($query_downloads_top_24hr[$i]['artist'], $query_downloads_top_24hr[$i]['title'], $mscfg['track_title_substr']);
	
	$t_link = $config['http_home_url'].'music/'.$query_downloads_top_24hr[$i]['mid'].'-mp3-'.totranslit( $query_downloads_top_24hr[$i]['artist'] ).'-'.totranslit( $query_downloads_top_24hr[$i]['title'] ).'.html';

	$downloads_top_24hr .= '<tr class="hovertd"><td align="left"><div class="clear"></div><div href="'.playLinkMservice( $query_downloads_top_24hr[$i]['filename'], $query_downloads_top_24hr[$i]['time'], $query_downloads_top_24hr[$i]['hdd'] ).'" style="width:100%;" class="item"><div><div class="btn'.$ifsmart.' play"></div><div class="tracks"><a href="'.$t_link.'" title="'.$query_downloads_top_24hr[$i]['artist'].' - '.$query_downloads_top_24hr[$i]['title'].'">'.$artist_title.'</a> '.downloadsSongDownloadTop( $query_downloads_top_24hr[$i]['mid'], $query_downloads_top_24hr[$i]['download'], $query_downloads_top_24hr[$i]['view_count'], $query_downloads_top_24hr[$i]['cnt'] ).clipOnline( $query_downloads_top_24hr[$i]['clip_online'] ).'</div></div><div class="player inactive"></div>
</div></td>
<td align="center">'.$query_downloads_top_24hr[$i]['lenght'].'</td>
<td align="center">'.formatsize( $query_downloads_top_24hr[$i]['size'] ).'</td>';

	if ( $is_logged ) $downloads_top_24hr .= '<td><div id="favorited-tracks-layer-'.$query_downloads_top_24hr[$i]['mid'].'-dt">'.ShowFavoritesListDownloadsTop( $query_downloads_top_24hr[$i]['mid'] ).'</div></td>';
	else $downloads_top_24hr .= '<td><div id="favorited-tracks-layer-'.$query_downloads_top_24hr[$i]['mid'].'"></div></td>';
	
	$downloads_top_24hr .= '<td align="center"><a href="'.$t_link.'" title="������� '.$query_downloads_top_24hr[$i]['artist'].' - '.$query_downloads_top_24hr[$i]['title'].' mp3"><div class="download_tracklist"></div></a></td></tr>';
}
$downloads_top_24hr .= '</table></div>';

} else $downloads_top_24hr = '������ 10 ���������� mp3 ������ �� �����!';
?>