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

//  ��� ������ ��3 �� ���������� �� ������� ��� ... (����� ������� ������ ���)
case 'mp3top':

$nam_e1 = '��� ������ mp3 2015 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '��� ������ mp3 2015 ���� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������ mp3 2015 ���� �� '.$config['description'];
$metatags['keywords'] = '2015, 2014, 2013, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2015 ���, '.$config['keywords'];
// � ��� ������� ��� ����� ������ ����� 01.01.2015 ����
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1420066801') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� ��� ������ mp3 2015 ����';

$mtitle .= '<h1 class="tit">��� ������ <span>mp3 2015</span> ���� �� domain.com</h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ������ mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top2014.html" title="������� ��� ������ mp3 2014 ����">2014 ���</a></li><li><a href="/music/mp3top2013.html" title="������� ��� ������ mp3 2013 ����">2013 ���</a></li><li><a href="/music/mp3top2012.html" title="������� ��� ������ mp3 2012 ����">2012 ���</a></li><li><a href="/music/mp3top2011.html" title="������� ��� ������ mp3 2011 ����">2011 ���</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...� ��� ��� ������� mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="������� ������� mp3 �� �����">�����</a></li><li><a href="/music/bestmp3_week.html" title="������� ������� mp3 �� ������">������</a></li><li><a href="/music/bestmp3_month.html" title="������� ������� mp3 �� �����">�����</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
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
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
// ������������ ��������� ��� ������ ��3 �� ���������� �� �� �����, ����������� ���������� page_lim_main � ������� - 
// ��� ����� ������� ������� ������� �������
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) {
  if ($npage ==1 ) {
    $linkdop = $config['http_home_url'].'music/mp3top';
    $prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';
  }
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';
}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� ������ ��3 �� ���������� �� ������� ��� ... (����� ������� ������ ���)
case 'mp3top2014':

$nam_e1 = '��� ������ mp3 2014 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '��� ������ mp3 2014 ���� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������ mp3 2014 ���� �� '.$config['description'];
$metatags['keywords'] = '2014, 2013, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2014 ����, '.$config['keywords'];
// � ��� ������� ��� ����� ������ 2014 ����
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1388530801' AND time < '1420066801') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� ��� ������ mp3 2014 ����';

$mtitle .= '<h1 class="tit">��� ������ <span>mp3 2014</span> ���� �� domain.com</h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ������ mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="������� ��� ������ mp3 2015 �����">2015 ���</a></li><li><a href="/music/mp3top2013.html" title="������� ��� ������ mp3 2013 ����">2013 ���</a></li><li><a href="/music/mp3top2012.html" title="������� ��� ������ mp3 2012 ����">2012 ���</a></li><li><a href="/music/mp3top2011.html" title="������� ��� ������ mp3 2011 ����">2011 ���</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...� ��� ��� ������� mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="������� ������� mp3 �� �����">�����</a></li><li><a href="/music/bestmp3_week.html" title="������� ������� mp3 �� ������">������</a></li><li><a href="/music/bestmp3_month.html" title="������� ������� mp3 �� �����">�����</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
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
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
// ������������ ��������� ��� ������ ��3 �� ���������� �� �� �����, ����������� ���������� page_lim_main � ������� - 
// ��� ����� ������� ������� ������� �������
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2014-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2014.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2014-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) {
  if ($npage ==1 ) {
    $linkdop = $config['http_home_url'].'music/mp3top2014';
    $prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';
  }
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';
}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� ������ ��3 �� ���������� �� 2013 ���
case 'mp3top2013':

$nam_e1 = '��� ������ mp3 2013 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '��� ������ mp3 2013 ���� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������ mp3 2013 �� '.$config['description'];
$metatags['keywords'] = '2013, ������� ��������� mp3, �������, ������ ���������� ����� ����, new, ������� ������ mp3 2013, '.$config['keywords'];

// � ��� ������� ��� ����� ������ 2013 ����
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1356994801' AND time < '1388530799') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� ��� ������ mp3 2013 ����';

$mtitle .= '<h1 class="tit">��� ������ <span>mp3 2013</span> ���� �� domain.com</h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ������ mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="������� ��� ������ mp3 2015 �����">2015 ���</a></li><li><a href="/music/mp3top2014.html" title="������� ��� ������ mp3 2014 ����">2014 ���</a></li><li><a href="/music/mp3top2012.html" title="������� ��� ������ mp3 2012 ����">2012 ���</a></li><li><a href="/music/mp3top2011.html" title="������� ��� ������ mp3 2011 ����">2011 ���</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...� ��� ��� ������� mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="������� ������� mp3 �� �����">�����</a></li><li><a href="/music/bestmp3_week.html" title="������� ������� mp3 �� ������">������</a></li><li><a href="/music/bestmp3_month.html" title="������� ������� mp3 �� �����">�����</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
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
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
// ������������ ��������� ��� ������ ��3 �� ���������� �� �� �����, ����������� ���������� page_lim_main � ������� - 
// ��� ����� ������� ������� ������� �������
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2013-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2013.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2013-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2013';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� ������ ��3 �� ���������� �� 2012 ���
case 'mp3top2012':

$nam_e1 = '��� ������ mp3 2012 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '��� ������ mp3 2012 ���� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������ mp3 2012 �� '.$config['description'];
$metatags['keywords'] = '2012, ������� ��������� mp3, �������, ������ ���������� ����� ����, new, ������� ������ mp3 2012, '.$config['keywords'];

// � ��� ������� ��� ����� ������ 2012 ����
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1325372401' AND time < '1356994799') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� ��� ������ mp3 2012 ����';

$mtitle .= '<h1 class="tit">��� ������ <span>mp3 2012</span> ���� �� domain.com</h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ������ mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="������� ��� ������ mp3 2015 �����">2015 ���</a></li><li><a href="/music/mp3top2014.html" title="������� ��� ������ mp3 2014 ����">2014 ���</a></li><li><a href="/music/mp3top2013.html" title="������� ��� ������ mp3 2013 ����">2013 ���</a></li><li><a href="/music/mp3top2011.html" title="������� ��� ������ mp3 2011 ����">2011 ���</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...� ��� ��� ������� mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="������� ������� mp3 �� �����">�����</a></li><li><a href="/music/bestmp3_week.html" title="������� ������� mp3 �� ������">������</a></li><li><a href="/music/bestmp3_month.html" title="������� ������� mp3 �� �����">�����</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
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
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
// ������������ ��������� ��� ������ ��3 �� ���������� �� �� �����, ����������� ���������� page_lim_main � ������� - 
// ��� ����� ������� ������� ������� �������
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2012-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2012.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2012-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2012';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� ������ ��3 �� ���������� �� 2011 ���
case 'mp3top2011':

$nam_e1 = '��� ������ mp3 2011 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '��� ������ mp3 2011 ���� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������ mp3 2011 �� '.$config['description'];
$metatags['keywords'] = '2011, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2011, '.$config['keywords'];

// ��� ������� ��� ����� ������ 2011 ����
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND time > '1293836401' AND time < '1325372399') ORDER BY download DESC, view_count DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);


if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� ��� ������ mp3 2011 ����';

$mtitle .= '<h1 class="tit">��� ������ <span>mp3 2011</span> ���� �� domain.com</h1>';
$mtitle .= '<div class="bestmp3">...� ����� ��� ������ mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/mp3top.html" title="������� ��� ������ mp3 2015 �����">2015 ���</a></li><li><a href="/music/mp3top2014.html" title="������� ��� ������ mp3 2014 ����">2014 ���</a></li><li><a href="/music/mp3top2013.html" title="������� ��� ������ mp3 2013 ����">2013 ���</a></li><li><a href="/music/mp3top2012.html" title="������� ��� ������ mp3 2012 ����">2012 ���</a></li></ul></div>';

$mtitle .= '<div class="bestmp3">...� ��� ��� ������� mp3 ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/bestmp3_24hr.html" title="������� ������� mp3 �� �����">�����</a></li><li><a href="/music/bestmp3_week.html" title="������� ������� mp3 �� ������">������</a></li><li><a href="/music/bestmp3_month.html" title="������� ������� mp3 �� �����">�����</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
// ������������ ��������� ��� ������ ��3 �� ���������� �� �� �����, ����������� ���������� page_lim_main � ������� - 
// ��� ����� ������� ������� ������� �������
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mp3top2011-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mp3top2011.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mp3top2011-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mp3top2011';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� 100 ������ ������� ������ (KISS FM) �� 2013 ���
case 'top_club2013':

$nam_e1 = '��� 100 ������� ������ 2013 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
if ( $page > 2 ) $stop[] = '������ ��� ������� KISS FM ��� 100 ������� ������ 2013 ����';

$metatags['title'] = '��� 100 ������� ������ 2013 ���� '.$metapage.'�� KISS FM �� '.$config['home_title_short'];
$metatags['description'] = 'KISS FM ��� 100 ������� ������ 2013 ���� �� '.$config['description'];
$metatags['keywords'] = '2014, 2013, ��� 100, ������� �����, KISS FM, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2013, '.$config['keywords'];

$mids = '44525,37271,58622,35053,51540,42848,41797,58640,58639,45155,58635,58638,44836,58637,35479,43290,38400,32786,43428,44414,40135,54520,39923,19977,43536,34020,44534,39704,44538,35480,51214,24313,28447,58641,51878,43277,44958,58642,34947,36819,50288,52011,49144,43676,33039,44369,40067,37238,38453,58636,48684,51830,29931,44144,39694,58648,35345,58535,44540,28421,43507,36008,43429,44366,58643,42578,35348,58644,58647,39132,35814,38452,42430,43538,58646,46548,39136,38023,30021,26147,43967,45120,42242,34122,36128,35188,44300,30945,49837,35802,43351,39887,58645,36662,43504,42554,33889,42855,45127,39795';
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."') DESC LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

$mtitle .= '<h1 class="tit">KISS FM <span>��� 100 ������� ������</span> 2013 ����</h1>';
$mtitle .= '<div class="bestmp3">...� ����� KISS FM ��� 100 ������� ������ ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/top_club2014.html" title="������� KISS FM ��� 100 ������� ������ 2014 ����">2014 ���</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">�</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">�����</th>
<th width="9%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
if ($page == 1) $i = 1; else $i = 51;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
$i++;
}
$mcontent .= '</table></div>';
}
// ������������ ��� 100 ������ ������� ������ 2013 (������ 2 �������� �� 50 ������)
$count_d = 2;

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/top_club2013-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/top_club2013.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� 100 ������ ������� ������ (KISS FM) �� 2014 ���
case 'top_club2014':

$nam_e1 = '��� 100 ������� ������ 2014 ����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
if ( $page > 2 ) $stop[] = '������ ��� ������� KISS FM ��� 100 ������� ������ 2014 ����';

$metatags['title'] = '��� 100 ������� ������ 2014 ���� '.$metapage.'�� KISS FM �� '.$config['home_title_short'];
$metatags['description'] = 'KISS FM ��� 100 ������� ������ 2014 ���� �� '.$config['description'];
$metatags['keywords'] = '2015, 2014, 2013, ��� 100, ������� �����, KISS FM, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2014, '.$config['keywords'];

$mids = '55477,78724,88277,59945,88570,74805,86539,60528,102103,47058,102106,64110,94952,61173,102107,99036,68141,102108,56684,89532,53670,102110,102109,67564,57321,75646,102111,73351,60180,90534,102112,102113,102114,89905,65676,79131,77203,102115,90518,86738,102116,102117,59314,61555,60646,71771,84969,91097,65871,94270,102211,73484,68928,97519,85516,36950,89572,85964,100283,92904,80848,102212,82132,96467,76245,102213,58381,75655,102214,86543,83081,88052,102215,85117,95267,83146,83963,85671,60609,102216,64125,64386,70296,91442,52815,52277,89961,91399,80299,55757,58606,93573,102218,102220,77837,92977,67582,89950,102222,81269';
$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."') LIMIT  " . $limit . "," . $mscfg['track_page_lim']);

$mtitle .= '<h1 class="tit">KISS FM <span>��� 100 ������� ������</span> 2014 ����</h1>';
$mtitle .= '<div class="bestmp3">...� ����� KISS FM ��� 100 ������� ������ ��: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/top_club2013.html" title="������� KISS FM ��� 100 ������� ������ 2013 ����">2013 ���</a></li></ul></div>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="navigation_up"></div>
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">�</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">�����</th>
<th width="9%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
if ($page == 1) $i = 1; else $i = 51;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
$i++;
}
$mcontent .= '</table></div>';
}
// ������������ ��� 100 ������ ������� ������ 2014 (������ 2 �������� �� 50 ������)
$count_d = 2;

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/top_club2014-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/top_club2014.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$mcontent .= <<<HTML
<div class="navigation">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
break;

//  ��� 20 ����������� ������ 2014 Beatport
case 'top_beatport2014':

$nam_e1 = '��� 20 ������ Beatport 2014 ����';

$metatags['title'] = '��� 20 ����������� ������ Beatport 2014 ���� �� '.$config['home_title_short'];
$metatags['description'] = '������� ��� 20 ������ ������ ����������� �� Beatport � 2014 ���� �� '.$config['description'];
$metatags['keywords'] = '2014, 2013, ��� 20, ������� �����, Beatport, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2014, '.$config['keywords'];

$mids = '92404,57880,61555,98317,98321,98323,66978,98318,98320,98319,59935,57865,80407,85764,83505,71291,80765,60496,73912,73359';

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."')" );

$mtitle .= '<h1 class="tit"><span>��� 20 Beatport</span> ����������� ������  2014 ����</h1>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">�</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">�����</th>
<th width="9%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
$i = 1;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
$i++;
}
$mcontent .= '</table></div>';
}

break;

//  ��� 50 ������ ����� 2014 ���� �� ������ Rolling Stone
case 'top_rollingstone2014':

$nam_e1 = '��� 50 ������ ������ 2014 ���� �� ������ ������� Rolling Stone';

$metatags['title'] = '��� 50 ������ ������ 2014 ���� �� ������ ������� Rolling Stone �� '.$config['home_title_short'];
$metatags['description'] = '������� ��� 50 ������ ������ 2014 ���� �� ������ ������� Rolling Stone �� '.$config['description'];
$metatags['keywords'] = '2014, 2013, ��� 50, ������ �����, Rolling Stone, ������� ��������� mp3, new, ������ ���������� ����� ����, �������, ������� ������ mp3 2014, '.$config['keywords'];

$mids = '56276,66391,87449,61377,99514,92286,58417,75355,65248,88507,99511,68431,94208,80577,66665,59979,99505,99512,61420,99513,83426,77714,64397,74026,77591,77253,99503,92894,80617,64169,69759,85491,99498,72477,79172,99497,99501,99502,99499,99500,99496,92104,99481,62640,99473,48272,63337,99494,99495,85945';

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, size, lenght, clip_online FROM ".PREFIX."_mservice WHERE (approve = '1' AND mid IN (".$mids.")) ORDER BY FIND_IN_SET(mid, '".$mids."')" );

$mtitle .= '<h1 class="tit"><span>��� 50 ������ ������</span> 2014 ���� �� ������ ������� Rolling Stone</h1>';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="4%" style="border-radius: 3px 0 0 3px;">�</th>
<th width="68%" height="20">&nbsp;</th>
<th width="9%">�����</th>
<th width="9%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%" style="border-radius: 0 3px 3px 0;">&nbsp;</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
<tr><td height="5" colspan="6"></td></tr>
HTML;
$i = 1;
while ( $row = $db->get_row( ) ) {

$tpl->load_template( 'mservice/tracklist_top_club.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{position}', $i.'.' );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
$i++;
}
$mcontent .= '</table></div>';
}

break;

//  ��� 50 ������������
case 'top_artists':

$nam_e1 = '��� 50 ������������ �� domain.com';

$metatags['title'] = '��� 50 ������������ �� '.$config['home_title_short'];
$metatags['description'] = '��� 50 ������������ �� '.$config['description'];
$metatags['keywords'] = '��� 50 �����������, ������� ��������� mp3, new, ������ ���������� ������������, �������, ������� ������ mp3, '.$config['keywords'];

if ( $mscfg['allow_letter_navig'] == 1 ) $mtitle =  ArtistLetterNavigator( );

$mcontent = '<h1 class="tit"><span>��� 50 ������������</span> �� domain.com</h1>';

$db->query( "SELECT transartist, artist, COUNT(transartist) AS cnt FROM ".PREFIX."_mservice  GROUP BY artist ORDER BY cnt DESC LIMIT 0, ".$mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = '��� �������� ��� 50 ������������!';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table width="100%">
<thead><tr>
<th width="80%" height="20">&nbsp;</th>
<th width="20%">������</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {
if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
else $artist = $row['artist'];

$tpl->load_template( 'mservice/artistlist.tpl' );
$tpl->set( '{artist}', $artist );
$tpl->set( '{trekov}', $row['cnt'] );
$tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );

$tpl->compile( 'artistlist' );
$mcontent .= $tpl->result['artistlist'];
$tpl->result['artistlist'] = FALSE;

}
$mcontent .= '</table>';

}

break;

}
?>
