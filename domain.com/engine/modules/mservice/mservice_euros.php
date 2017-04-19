<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}
@error_reporting ( E_ALL ^ E_NOTICE );

switch ( $_REQUEST['act'] ) {

// �������� ���������� ����������� 2011, ������� ����� �� ����������� � ����� "����������� 2011", ��� ������������ ��������� (������ 43 �����)
case 'euro2011':

$metatags['title'] = '����������� 2011 �����������, �������� ��� mp3 ����� �� '.$config['home_title_short'];
$metatags['description'] = '����������� 2011 �����������, �������� ��� mp3 ����� �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ����������� 2011 ����������� mp3, ��������, ����������� ���� �����, ��������� ������� ��� ����� ����������� 2011, ������� ��3 ���������, ���� ������ ��� �����������, ��� ���� ����������� 2011 ����������� mp3 ���������';

$mtitle .= '<h1 class="tit"><span>��� �����</span> �������� ����� <span>����������� 2011 �����������, ��������</span></h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ����� ����������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="��� ����� ����������� 2015 ���� ����, �������">2015 ���</a></li><li><a href="/music/euro2014.html" title="��� ����� ����������� 2014 ���� ����������, �����">2014 ���</a></li><li><a href="/music/euro2013.html" title="������� ��� ����� ����������� 2013 ���� �����, ������">2013 ���</a></li><li><a href="/music/euro2012.html" title="��� ����� ����������� 2012 ���� ����, �����������">2012 ���</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE '����������� 2011%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = '� ���������, � ������ ��������� ��� ��� ����� ������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2011.jpg" alt="��� ����� ����������� 2011 ����������� ��������" title="��� ����� ����������� 2011 ����������� ��������" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

}

break;

// �������� ���������� ����������� 2012, ������� ����� �� ����������� � ����� "����������� 2012", ��� ������������ ��������� (������ 43 �����)
case 'euro2012':

$metatags['title'] = '����������� 2012 ����, ����������� ��� mp3 ����� �� '.$config['home_title_short'];
$metatags['description'] = '����������� 2012 ����, ����������� ��� mp3 ����� �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ����������� 2012 ���� mp3, �����������, ����������� ���� �����, ��������� ������� ��� ����� ����������� 2012, ������� ��3 ���������, ���� ������ ��� �����������, ��� ���� ����������� 2012 mp3 ���� ���������';

$mtitle .= '<h1 class="tit"><span>��� �����</span> �������� ����� <span>����������� 2012 ����, �����������</span></h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ����� ����������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="��� ����� ����������� 2015 ���� ����, �������">2015 ���</a></li><li><a href="/music/euro2014.html" title="������� ��� ����� ����������� 2014 ���� ����������, �����">2014 ���</a></li><li><a href="/music/euro2013.html" title="��� ����� ����������� 2013 ���� �����, ������">2013 ���</a></li><li><a href="/music/euro2011.html" title="��� ����� ����������� 2011 ���� �����������, ��������">2011 ���</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE '����������� 2012%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = '� ���������, � ������ ��������� ��� ��� ����� ������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2012.jpg" alt="��� ����� ����������� 2012 ���� �����������" title="��� ����� ����������� 2012 ���� �����������" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

}

break;

// �������� ���������� ����������� 2013, ������� ����� �� ����������� � ����� "����������� 2013", ��� ������������ ��������� (������ ... �����)
case 'euro2013':

$metatags['title'] = '����������� 2013 �����, ������ ��� mp3 ����� �� '.$config['home_title_short'];
$metatags['description'] = '����������� 2013 �����, ������ ��� mp3 ����� �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ����������� 2013 ����� mp3, ������, ����������� ���� �����, ��������� ������� ��� ����� ����������� 2013, ������� ��3 ���������, ���� ������ ��� �����������, ��� ���� ����������� 2013 mp3 ����� ���������';

$mtitle .= '<h1 class="tit"><span>��� �����</span> �������� ����� <span>����������� 2013 �����, ������</span></h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ����� ����������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="��� ����� ����������� 2015 ���� ����, �������">2015 ���</a></li><li><a href="/music/euro2014.html" title="��� ����� ����������� 2014 ���� ����������, �����">2014 ���</a></li><li><a href="/music/euro2012.html" title="��� ����� ����������� 2012 ���� ����, �����������">2012 ���</a></li><li><a href="/music/euro2011.html" title="��� ����� ����������� 2011 ���� �����������, ��������">2011 ���</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE '����������� 2013%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = '� ���������, � ������ ��������� ��� ��� ����� ������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2013.jpg" alt="��� ����� ����������� 2013 ����� ������" title="��� ����� ����������� 2013 ����� ������" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

}

break;

// �������� ���������� ����������� 2014, ������� ����� �� ����������� � ����� "����������� 2014", ��� ������������ ��������� (������ ... �����)
case 'euro2014':

$metatags['title'] = '����������� 2014 ����������, ����� ��� mp3 ����� �� '.$config['home_title_short'];
$metatags['description'] = '����������� 2014 ����������, ����� ��� mp3 �����, Eurovision Song Contest 2014 �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ����������� 2014 ���������� mp3, �����, Eurovision Song Contest 2014, new, ����������� ���� �����, ��������� ������� ��� ����� ����������� 2014, ������� �����������, ������� ��3 ���������, ���� ������ ��� �����������, �����, ��� ���� ����������� 2014 mp3 ���������� ���������';

$mtitle .= '<h1 class="tit"><span>��� �����</span> �������� ����� <span>����������� 2014 ����������, �����</span></h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ����� ����������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="��� ����� ����������� 2015 ���� ����, �������">2015 ���</a></li><li><a href="/music/euro2013.html" title="��� ����� ����������� 2013 ���� �����, ������">2013 ���</a></li><li><a href="/music/euro2012.html" title="��� ����� ����������� 2012 ���� ����, �����������">2012 ���</a></li><li><a href="/music/euro2011.html" title="��� ����� ����������� 2011 ���� �����������, ��������">2011 ���</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE '����������� 2014%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = '� ���������, � ������ ��������� ��� ��� ����� ������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2014.jpg" alt="��� ����� ����������� 2014 ���������� �����" title="��� ����� ����������� 2014 ���������� �����" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

}
break;

// �������� ���������� ����������� 2015, ������� ����� �� ����������� � ����� "����������� 2015", ��� ������������ ��������� (������ ... �����)
case 'euro2015':

$metatags['title'] = '����������� 2015 ����, ������� ��� mp3 ����� �� '.$config['home_title_short'];
$metatags['description'] = '����������� 2015 ����, ������� ��� mp3 �����, Eurovision Song Contest 2015 �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ����������� 2015 ���� mp3, �������, Eurovision Song Contest 2015, new, ����������� ���� �����, ��������� ������� ��� ����� ����������� 2015, ������� �����������, ������� ��3 ���������, ���� ������ ��� �����������, �����, ��� ���� ����������� 2015 mp3 ���� ���������';

$mtitle .= '<h1 class="tit"><span>��� �����</span> �������� ����� <span>����������� 2015 ����, �������</span></h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ����� ����������� ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2014.html" title="��� ����� ����������� 2014 ���� ����������, �����">2014 ���</a></li><li><a href="/music/euro2013.html" title="��� ����� ����������� 2013 ���� �����, ������">2013 ���</a></li><li><a href="/music/euro2012.html" title="��� ����� ����������� 2012 ���� ����, �����������">2012 ���</a></li><li><a href="/music/euro2011.html" title="��� ����� ����������� 2011 ���� �����������, ��������">2011 ���</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE '����������� 2015%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = '� ���������, � ������ ��������� ��� ��� ����� ������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2015.jpg" alt="��� ����� ����������� 2015 ���� �������" title="��� ����� ����������� 2015 ���� �������" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
$tpl->set( '{artistfull}', $row['artist'] );
$tpl->set( '{titlefull}', $row['title'] );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;

}
$mcontent .= '</table></div>';

}

break;

}
?>