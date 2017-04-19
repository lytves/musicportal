<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR.'/data/mservice.php';
require_once ENGINE_DIR.'/modules/mservice/functions.php';
include_once ENGINE_DIR.'/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$mtitle = '';
$mcontent = '';
$THEME = $config['http_home_url'].'templates/'. $config['skin'];

// ��������, ������� �� ������?
if ( $mscfg['online'] != 1 ) $stop[] = '� ������ ������ ����������� ����� ����� domain.com ��������'; else $stop = array();

switch ( $_REQUEST['act'] ) {

// ����� ������� �������� ������ --->  ��������� ����� ��....
default:

$tpl->load_template( 'mservice/main_mp3.tpl' );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_24hr.php';
$tpl->set ( '{bestmp3_24hr}', $bestmp3_24hr );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_week.php';
$tpl->set ( '{bestmp3_week}', $bestmp3_week );
include_once ENGINE_DIR.'/modules/mservice/mod_bestmp3_month.php';
$tpl->set ( '{bestmp3_month}', $bestmp3_month );

if (($bestmp3_24hr == '') AND ($bestmp3_week == '') AND ($bestmp3_month == '')) { 
	header("location: {$config['http_home_url']}music/new_mp3.html"); 
    exit; 
}

$tpl->compile( 'main_mp3.tpl' );
$mcontent .= $tpl->result['main_mp3.tpl'];
$tpl->result['main_mp3.tpl'] = FALSE;
break;

// ������� �������
case 'rules':
$metatags['title'] = '������� ����� '.$config['home_title_short'];
$metatags['description'] = '������� ����� '.$config['description'];
$metatags['keywords'] = '�������, �����, 2013, 2012, ������� ��������� mp3, ������ ���������� ����� ����, ������� ������ mp3 2013, '.$config['keywords'];

$mtitle .= '<h3 class="tit">������� ����� (����� �������)</h3>';
$row = $db->super_query( " SELECT template FROM ".PREFIX."_static WHERE name = 'music/rules' " );
$mcontent .= $row['template'];
break;

// ��� ����� ����� + ��������� � modules/profile.php � ������/userinfo
case 'mytracks':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = '��� ����������� mp3-�����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = $member_id['name'].'. ��� ����������� mp3-����� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ����������� mp3-����� �� '.$config['description'];
$metatags['keywords'] = '��� mp3-�����, ����������� ����� mp3, '.$config['keywords'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'artist, title';
  $sortactive = '�� ����������� &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-2.html">�� �������� ����� &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/mytracks.html">�� ���� ���������� &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'title, artist';
  $sortactive = '�� �������� ����� &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">�� ����������� &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/mytracks.html">�� ���� ���������� &#8593;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = '�� �������� &#8595;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">�� ����������� &#8595;</a></li><li><a href="/music/mytracks/sort-2.html">�� �������� ����� &#8595;</a></li><li><a href="/music/mytracks.html">�� ���� ���������� &#8593;</a></li>';
}
else {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = '�� ���� ���������� &#8593;';
  $sortactive_no = '<li><a href="/music/mytracks/sort-1.html">�� ����������� &#8595;</a></li><li><a href="/music/mytracks/sort-2.html">�� �������� ����� &#8595;</a></li><li><a href="/music/mytracks/sort-3.html">�� �������� &#8595;</a></li>';
}

$mtitle .= '<h3 class="tit"><span>��� ����������� mp3-�����</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$db->query( "SELECT mid, time, title, approve, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE uploader = '{$member_id[user_id]}') as cnt FROM ".PREFIX."_mservice WHERE uploader = '{$member_id[user_id]}' ORDER BY ".$sqlsort." LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
$mcontent .= '<br />� ���������, �� ��� �� ��������� ���� mp3-����� �� ��� ����. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������!</a><br />';
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

if ( $db->num_rows() != 0 ) {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead>
<tr>
<th width="70%" height="20">
HTML;

if ( $db->num_rows() > 1 ) $mcontent .= <<<HTML
<dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl>
HTML;

$mcontent .= <<<HTML
</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr></thead>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {

  if (!isset($count)) $count = $row['cnt'];
  if ($row['approve'] == 1) {
    $tpl->load_template( 'mservice/tracklist.tpl' );
    $tpl->set( '{THEME}', $THEME );
    $tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
    $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
    $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
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
  } else {
      $tpl->load_template( 'mservice/tracklist_on_moder.tpl' );
      $tpl->set( '{THEME}', $THEME );
      $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
      $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
      $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
      $tpl->set( '{lenght}', $row['lenght'] );
      $tpl->set( '{filesize}', formatsize( $row['size'] ) );
      $tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );

      $tpl->compile( 'tracklist' );
      $mcontent .= $tpl->result['tracklist'];
      $tpl->result['tracklist'] = FALSE;
    }
}
$mcontent .= '</table>';
// ������������ ���������
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/mytracks-page-'.$t2.$sortpage.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/mytracks'.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/mytracks-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/mytracks';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
  $mcontent .= '</div>';
}
break;

//��������� mp3 �����
case 'myfavtracks':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = '��� ��������� mp3-�����';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = $member_id['name'].'. ��� ��������� mp3-����� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ��������� mp3-����� �� '.$config['description'];
$metatags['keywords'] = '��� ������� mp3-�����, ��������� mp3, ����������� �����, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>��� ��������� mp3-�����</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

if ($member_id['favorites_mservice']) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$count = count($list_fav);
	foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		$list_fav_time[] = langdate( 'j F Y, l',substr($value,strpos($value,"-") + 1));
	}
	krsort($list_fav_time);
	$i = ( count($list_fav_time) - 1 - ( ($page - 1) * $mscfg['track_page_lim'] ) );
	$list_fav_mid2 = implode( ",", $list_fav_mid );
} else $list_fav_mid2 = 0;

$db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online FROM ".PREFIX."_mservice WHERE mid IN (".$list_fav_mid2.") AND approve = '1' ORDER BY FIND_IN_SET(mid, '".$list_fav_mid2."') DESC LIMIT ".$limit."," .$mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
  $mcontent .= '� ���������, �� ��� �� ��������� ����� � ���� ��������� mp3-�����<br />';
  $mcontent .= '<div id="playlist">';

	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;

  $mcontent .= '</div>';
}

else {

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead>
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr></thead>
<tr>
HTML;
$r = 0;
while ( $row = $db->get_row( ) ) {

  if ($r == 0) $mcontent .= <<<HTML
<td class="titfav">���������: {$list_fav_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
  else {
    if ($list_fav_time[$i] !== $list_fav_time[$i+1]) {
      $mcontent .= <<<HTML
<td class="titfav">���������: {$list_fav_time[$i]}</td>
<td colspan="4"></td></tr>
<tr><td colspan="5" height="5"></td></tr>
<tr>
HTML;
    }
  }

$tpl->load_template( 'mservice/tracklist.tpl' );
$tpl->set( '{THEME}', $THEME );
$tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
$tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ));
$tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
$tpl->set( '{mid}', $row['mid'] );
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
$i--;
$r++;
}
$mcontent .= '</table>';

// ������������ ��������� 
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/myfavtracks-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/myfavtracks.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/myfavtracks-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/myfavtracks';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

if ( $count > $mscfg['track_page_lim'] ) {
$mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
$tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
else $podmod = TRUE;
} if ($count < 10 ) $podmod = TRUE;
  if ($podmod == TRUE) {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
    $mcontent .= $downloads_top_24hr;
  }
$mcontent .= '</div>';
}
break;

//��������� ����������� �����
case 'myfavartists':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = '��� ������� �����������';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = $member_id['name'].'. ��� ������� ����������� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '��� ������� ����������� �� '.$config['description'];
$metatags['keywords'] = '��� ������� �����������, ����������� �����, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>��� ������� �����������</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

if ($member_id['favorites_artists_mservice'] == "") {
  $mcontent .= '� ���������, �� ��� �� ��������� ������� ������������<br />';
  $podmod = TRUE;
} else {
    $list_fav = explode( ",", $member_id['favorites_artists_mservice'] );
    $count = count($list_fav);
    foreach ($list_fav as $value) {
      $list_fav_art[] = 'transartist like \''.$value;
    }
    // �������.������ - ��� ������� ������� ��������� ������ �� ������� ��� �������������� ������ � sql-�������
    $list_fav_art2 = substr(implode( "' or ", $list_fav_art ), 18);
    
    $db->query( "SELECT DISTINCT artist, transartist, COUNT(artist) as count FROM ".PREFIX."_mservice WHERE transartist like '".$list_fav_art2."' GROUP BY artist ORDER BY artist LIMIT ".$limit."," . $mscfg['track_page_lim'] );

  if ( $db->num_rows() != 0 ) {

  $mcontent .= <<<HTML
<table width="100%">
<thead>
<tr>
<th width="80%" height="20">&nbsp;</th>
<th width="15%">������</th>
<th width="5%">&nbsp;</th>
</tr></thead>
HTML;


  while ( $row = $db->get_row( ) ) {
    if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
    else $artist = $row['artist'];

    $tpl->load_template( 'mservice/artistfavlist.tpl' );
    $tpl->set( '{artist}', $artist );
    $tpl->set( '{trekov}', $row['count'] );
    $tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );
    $tpl->set( '{transartist}', $row['transartist'] );
    $tpl->set( '{favartist}', ShowMyFavArtists( $row['transartist'] ) );

    $tpl->compile( 'artistlist' );
    $mcontent .= $tpl->result['artistlist'];
    $tpl->result['artistlist'] = FALSE;

  }
  $mcontent .= '</table>';

// ������������ ��������� 
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/myfavartists-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/myfavartists.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/myfavartists-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/myfavartists';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else $podmod = TRUE;
  }
  if ($count < 10 ) $podmod = TRUE;
  } else {
      $mcontent .= '� ���������, �� ��� �� ��������� ������� ������������</a><br />';
      $podmod = TRUE;
  }
  }

if ($podmod == TRUE) {
  $mcontent .= '<div id="playlist">';
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
  $mcontent .= $downloads_top_24hr;
  $mcontent .= '</div>';
}
break;

// �������� ������� ���������
case 'new-music-clips':

$nam_e1 = '������� ����������� ������';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2;

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '������� ����������� ������ '.$metapage.'�������� �� '.$config['home_title_short'];
$metatags['description'] = '������� ����������� ������, �������� ������ �� '.$config['description'];
$metatags['keywords'] = '����������� �������, ����� ����, 2015, 2014, 2013, 2012, 2011, ����������� ����, ��������, ������, ������� ���������, ����� ������ ��3, ������� mp3, �������� ��3 ���, ����� ������, ���� 2015, 2014, 2013, 2012, 2011, ������� ����� ������, ������� �������, �������';

$mtitle .= '<h1 class="tit"><span>������� ����������� ������</span> �� domain.com</h1>';

$db->query( "SELECT mid, title, artist, view_count, clip_online, clip_online_time FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online_time <> '') ORDER BY clip_online_time DESC LIMIT ".$limit.",".$mscfg['album_page_lim']*2 );

if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� � ��������� ����������� ������!';

if ( $db->num_rows() == 0 ) $stop[] = '��� ����� �������� � ��������� ����������� ������!';

if ( count( $stop ) == 0 ) {

  $mcontent .= '<div id="navigation_up"></div>';
  $mcontent .= '<div class="clips_ramka" style="margin-top:15px;"><table width="100%"><tr>';

  $i = 1;
  while ( $row = $db->get_row( ) ) {
    if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches)) $clip_online_image = 'http://img.youtube.com/vi/'.$matches[1].'/mqdefault.jpg';
    else $clip_online_image = $THEME.'/images/music_clip.jpg';
  
    $mcontent .= '<td width="50%" style="text-align:center; padding:0 20px;" valign="top"><a title="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" class="albums" href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html">'.artistTitleStrlen($row['artist'], $row['title'], ($mscfg['track_title_substr'])).'</a><br /><a href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html" title="�������� ����������� ���� '.$row['artist'].' - '.$row['title'].'"><img style="margin:5px 0;" class="clip_online_image" src="'.$clip_online_image.'" style="float:left;" alt="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" title="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" /></a><br /><span style="float:left; padding-left:5px;"><img src="{THEME}/images/eye.png" align="bottom" />&nbsp;'.$row['view_count'].'</span><span style="float:right;"><img src="{THEME}/images/calendar.png" align="top" />&nbsp;'.date( 'd.m.y', $row['clip_online_time'] ).'</style></td>';
    $i++;
    if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2"><div class="line" style="margin:10px 0;"></div></td></tr><tr>';
    if ($db->num_rows() == '1') $mcontent .= '<td>&nbsp;</td>';
  }
  $mcontent .= '</tr></table></div>';
}

// ������������ ��������� � �������� ����������� ������, ����������� ���������� page_lim_main � ������� - ������� � ��������� ����������� ������
$count_d = $mscfg['page_lim_main'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;

  $plink = $config['http_home_url'].'music/new-music-clips-page-'.$t2.'.html';
  
  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/new-music-clips.html';
  $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
  $array[$t2] = 1;
}

$link = $config['http_home_url'].'music/new-music-clips-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/new-music-clips';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
else $prev_page = '';
$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
else $next_page = '';

$mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;

break;

// �������� �� ����� ���������� �����������
case 'artistclips':

$nam_e1 = '������� ����������� ������';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page  * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim'];

if ( $page == 1 ) $metapage = ', ';
else $metapage = ' (�������� '.$page.'), ';

$nameartist = addslashes($parse->process( $_REQUEST['nameartist'] ));

$db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist = '$nameartist' AND approve ='1'" );
while( $row = $db->get_row( ) ) $query_artist[] = $row['artist'];
if (count($query_artist) > 1 ) $artist1 = implode(", ",$query_artist);
else $artist1 = $query_artist[0];

$mtitle .= '<h1 class="tit">��� ����������� ����� <span><a href="/music/artistracks-'.$nameartist.'.html" title="��� mp3 ����� '.$artist1.'">'.$artist1.'</a></span> �� domain.com</h1>';

$db->query( "SELECT mid, title, artist, view_count, clip_online, clip_online_time, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online <> '' AND transartist = '$nameartist')) as cnt FROM ".PREFIX."_mservice WHERE (approve = '1' AND clip_online <> '' AND transartist = '$nameartist') ORDER BY artist, title, clip_online_time DESC LIMIT ".$limit.",".$mscfg['album_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = '��� ����������� ������ ����� �����������!';

if ( count( $stop ) == 0 ) {

  $metatags['title'] = $artist1.' - c������� ��� ����������� �����'.$metapage.'��� ����� mp3 �� '.$config['home_title_short'];
  $metatags['description'] = $artist1.' - c������� ��� ����������� �����, ��� ����� mp3 ����� �� '.$config['description'];
  $metatags['keywords'] = '������� ��� ����������� ����� '.$artist1.' mp3, ����� �����, �����������, ����������� �����, ������� ������, ��������� ������� ��� ����� '.$artist1.', new, ������� ��3 ���������, ������ ��� �����������, ��� ���� '.$artist1.' mp3 ���������, ������� ��������, �������, �������, ��� ����������� �����';

  $mcontent .= '<div id="navigation_up"></div>';
  $mcontent .= '<div class="clips_ramka" style="margin-top:15px;"><table width="100%"><tr>';

  $i = 1;
  while ( $row = $db->get_row( ) ) {
    if (!isset($count)) $count = $row['cnt'];
    
    if (!$row['clip_online_time']) $row['clip_online_time'] = '1420066801';
    if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $row['clip_online'], $matches)) $clip_online_image = 'http://img.youtube.com/vi/'.$matches[1].'/mqdefault.jpg';
    else $clip_online_image = $THEME.'/images/music_clip.jpg';
  
    $mcontent .= '<td width="50%" style="text-align:center; padding:0 20px;" valign="top"><a title="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" class="albums" href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html">'.artistTitleStrlen($row['artist'], $row['title'], ($mscfg['track_title_substr'])).'</a><br /><a href="/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html" title="�������� ����������� ���� '.$row['artist'].' - '.$row['title'].'"><img style="margin:5px 0;" class="clip_online_image" src="'.$clip_online_image.'" style="float:left;" alt="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" title="�������� ������ ���� '.$row['artist'].' - '.$row['title'].'" /></a><br /><span style="float:left;"><img src="{THEME}/images/views.png" align="top" />&nbsp;����������: '.$row['view_count'].'</span><span style="float:right;"><img src="{THEME}/images/calendar.png" align="top" />&nbsp;'.date( 'd.m.y', $row['clip_online_time'] ).'</style></td>';
    $i++;
    if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2"><div class="line" style="margin:10px 0;"></div></td></tr><tr>';
    if (!$tracks_to_view) $tracks_to_view = $count - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
    if ($tracks_to_view == '1') $mcontent .= '<td>&nbsp;</td>';
  }
  $mcontent .= '</tr><tr><td colspan="2"><div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$nameartist.'.html" title="��������� ������� mp3, ��� ����������� ����� '.$artist1.'">������� ��������� ��� ��3 ����� '.$artist1.'</a></div><div style="clear:both;"></div></td></tr></table></div>';
}

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

// ������������ ��������� � �������� ����������� ������, ����������� ���������� page_lim_main � ������� - ������� � ��������� ����������� ������
$count_d = $count / ($mscfg['album_page_lim']);
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;
  $plink = $config['http_home_url'].'music/artistclips-'.$nameartist.'-page-'.$t2.'.html';
  
  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artistclips-'.$nameartist.'.html';
  $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
  $array[$t2] = 1;
}

$link = $config['http_home_url'].'music/artistclips-'.$nameartist.'-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artistclips-'.$nameartist.'';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
else $prev_page = '';
$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
else $next_page = '';

if ( $count > $mscfg['album_page_lim'] ) {
  $mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  //���� ����� $tracks_to_view
  //$tracks_to_view = $count - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
  if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
  else {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
    $mcontent .= $downloads_top_week;
  }
}

break;

// �������� ������� mp3
case 'new_mp3':

$nam_e1 = '������� mp3';
if ( $_REQUEST['page'] == FALSE ) $page = 1;
  else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';
$metatags['title'] = '������� mp3 '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '������� mp3 �� '.$config['description'];
$metatags['keywords'] = '����������� ������� 2015, 2014, 2013, 2012, 2011, ������� ���������, ����� ������ ��3, ������� mp3, �������� ��3 ���, ����� ������, ���� 2015, 2014, 2013, 2012, 2011, ������� ����� ������, ������� ������� 2015, 2014, 2013, 2012, 2011';

$mtitle .= '<h1 class="tit"><span>������� mp3</span> �� domain.com</h1>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE approve = '1' ORDER BY time DESC LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $page > $mscfg['page_lim_main'] ) $stop[] = '������ ��� ������� � ��������� mp3!';

if ( $db->num_rows() == 0 ) $stop[] = '��� ������� � ��������� mp3!';

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
$tpl->set( '{view_link}', $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{plusclip}', clipOnline( $row['clip_online'] ) );


  $tpl->set( '{THEME}', $THEME );
  $tpl->set( '{downloads}', downloadsSong( $row['mid'], $row['download'], $row['view_count'] ) );
  $tpl->set( '{favorites}', ShowFavoritesList( $row['mid'] ) );
  $tpl->set( '{mid}', $row['mid'] );
  $tpl->set( '{play_file_path}', playLinkMservice( $row['filename'], $row['time'], $row['hdd'] ) );
  $tpl->set( '{date}', date( 'd.m.y', $row['time'] ) );
  $tpl->set( '{artist_title}', artistTitleStrlen($row['artist'], $row['title'], $mscfg['track_title_substr']) );
  $tpl->set( '{lenght}', $row['lenght'] );
  $tpl->set( '{filesize}', formatsize( $row['size'] ) );
  $tpl->set( '{artistfull}', $row['artist'] );
  $tpl->set( '{titlefull}', $row['title'] );

$tpl->compile( 'tracklist' );
$mcontent .= $tpl->result['tracklist'];
$tpl->result['tracklist'] = FALSE;
}
$mcontent .= '</table></div>';
}

// ������������ ��������� � �������� mp3, ����������� ���������� page_lim_main � ������� - ������� � ��������� mp3
$count_d = $mscfg['page_lim_main'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/new_mp3-page-'.$t2.'.html';
  
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/new_mp3.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/new_mp3-page-';
  $seo_mode = '.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/new_mp3';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';


  $mcontent .= <<<HTML
<br /><div class="navigation" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;

if ( count( $stop ) == 0 )  $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;

break;

// �������� "������ �������" :))
case 'view':

$mid = intval( $_REQUEST['mid'] );
$row = $db->super_query( "SELECT m.mid, m.time, m.title, m.rating, m.is_demo, m.vote_num, m.artist, m.download, m.description, m.filename, m.hdd, m.size, m.uploader, m.view_count, m.lenght, m.bitrate, m.clip_online, u.name, a.aid as alb_aid, a.year as alb_year, a.album as alb_album, a.artist as alb_artist, a.image_cover as alb_image_cover, a.genre as alb_genre, a.transartist as alb_transartist, a.approve as alb_approve, (SELECT COUNT(aid) FROM ".PREFIX."_mservice_albums WHERE artist = m.artist AND aid != a.aid) as cnt FROM ".PREFIX."_mservice m LEFT JOIN ".USERPREFIX."_users u ON m.uploader = u.user_id LEFT JOIN ".PREFIX."_mservice_albums a ON m.album = a.aid WHERE m.mid = '$mid' AND m.approve = '1'" );

$art = addslashes( $row['artist'] );

if ( $row['mid'] == FALSE ) {
	$stop[] = '������������� ����� ���� �� ������. �������� �� ��� �� ������ ���������, ���� ��� ������� � �� ������������, ���� �� ��� �����! <a href="javascript:history.go(-1)">��������� �� ���������� ��������</a>';
	$nam_e2 = $nam_e1 = 'domain.com';
	}
else {
	if ( strlen( $nam_e2 = $row['artist'].' - '.$row['title'] ) > 50 ) $nam_e1 = substr( $nam_e2, 0, 50 ).'...'; else $nam_e1 = $nam_e2;
	
  $to_keywords_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " � ", " vs ", " vs. ", " pres ", " pres. ");
  $to_keywords = str_ireplace($to_keywords_array, ", ", $row['artist']);
  $to_keywords2 = str_ireplace(' ', ", ", $row['title']);
}
if ($row['clip_online'] != '') $clip_online_metatags = ', ����';
else $clip_online_metatags = '';
if ( date('Y', $row['time']) == date('Y', time())) {$is_new = '����� �����'; $is_new2 = ' ����� ����,';}

if ( count( $stop ) == 0 ) {
if ($row['is_demo'] == 1) $is_demo = ' (����������)'; else $is_demo = '';

if ($member_id['user_id'] != 1017)  $db->query( "UPDATE ".PREFIX."_mservice SET view_count = view_count + 1 WHERE mid = '$mid'" );
//$db->query( "UPDATE ".PREFIX."_mservice SET view_count = view_count + 1 WHERE mid = '$mid'" );
//if ($member_id['user_id'] == 1017) $tpl->load_template( 'mservice/viewtrack_test.tpl' );
//else $tpl->load_template( 'mservice/viewtrack.tpl' );
$tpl->load_template( 'mservice/viewtrack.tpl' );

$subpapka = nameSubDir( $row['time'], $row['hdd'] );
$artist = $row['artist'];
$transartist = totranslit( $artist );

if( $config['allow_banner'] ) {
  include_once ENGINE_DIR . '/modules/banners.php';
  if ($config['skin'] == 'smartphone') $tpl->set( '{adv}', $banners['view-download-smartphone'] );
  else $tpl->set( '{adv}', $banners['view-download'] );
} else $tpl->set( '{adv}', '' );

$tpl->set( '{bitrate}', $row['bitrate'].' ����/�' );
$tpl->set( '{lenght}', $row['lenght'] );
$tpl->set( '{filesize}', formatsize( $row['size'] ) );
$tpl->set( '{artist3}', '<a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="'.$row['artist'].' ������� ��� mp3 ���������">'.$row['artist'].'</a>' );
$tpl->set( '{title}', $row['title'] );

//             $$$$$$$$$$$$$$$$          ���� �� ��������

$mtitle .= '<h1 class="tit"><span>'.$row['artist'].' - '.$row['title'].$is_demo.'</span> ������� ��������� mp3</h1>';

$play = $config['http_home_url'].'music/play-'.$mid.'.html';

if ( $mscfg['mfp_type'] == 2 ) $play_hg = 130; else $play_hg = 100;
if ( $mscfg['mfp_type'] == 3 and $mscfg['playning_allow_visual'] == 1 ) $play_hg = 380;

$mcontent .= <<<HTML
<script type="text/javascript">
function reload () {
var rndval = new Date().getTime();
document.getElementById('dle-captcha').innerHTML = '<a onclick="reload(); return false;" href="#" title="�������, ���� �� ����� �����������"><img src="{$config[http_home_url]}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="{$lang[sec_image]}" /></a>';
}
function playTrack( ) {
  window.open( "{$play}", "playning", "location=0,status=0,scrollbars=0,width=500,height={$play_hg}" );
}
</script>
HTML;

if ($is_logged) {
  $tpl->set( '{downloadlink}', downloadLinkMservice( $row['filename'], $mscfg['downfile_name'], $row['artist'], $row['title'], $mscfg['link_time'], $subpapka, $row['hdd'] ) );
  $tpl->set( '{favorites}', '<tr><td style="padding-top:3px;"><div class="line"></div><table width="100%" height="35"><tr><td><img src="{THEME}/images/newtrack.png" align="top" /></td><td width="10"></td><td style="white-space:nowrap;">��������� mp3:</td><td width="30"><div id="favorited-tracks-layer">'.ShowFavorites( $mid ).'</div></td><td width="100"></td>' );
  $tpl->set( '{favorites_art}', '<td><img src="{THEME}/images/favart.png" align="top" /></td><td width="5"></td><td style="white-space:nowrap">������� �����������:</td><td width="30"><div id="favart-artist">'.showMyFavArtistsView( $transartist ).'</div></td></tr></table></td></tr>' );
} else {
  $tpl->set( '{downloadlink}', '/music/download-mp3-'.$mid.'.html' );
  $tpl->set( '{favorites}', '');
  $tpl->set( '{favorites_art}', '');
}

$tpl->set( '{THEME}', $THEME );

$tpl->set( '{artist}', $row['artist'] );
$tpl->set( '{transartist}', $transartist );
$tpl->set( '{views}', $row['view_count'] );
$tpl->set( '{downcount}', $row['download'] );
$tpl->set( '{viewEdit}', viewEdit( $mid ) );

$tpl->set( '{rating}', showTrackRating( $mid, $row['rating'], $row['vote_num'], 1 ) );
$tpl->set( '{date}', langdate( 'D d F Y - H:i', $row['time'] ) );

$tpl->set( '{uploader}', '<a href="'.$config['http_home_url'].'user/'.urlencode($row['name']).'/">'.$row['name'].'</a>' );

$tpl->set( '{filetype}', strtolower( end( explode( '.', $row['filename'] ) ) ) );
$tpl->set( '{player}', BuildPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], $THEME ) );
$tpl->set( '{play}', '<a href="#" onClick="playTrack( ); return false;">���������� � ����� ���� Flash-�����</a>' );

if ( $mscfg['allow_get_apic'] == 1 ) {

  include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
  $getID3 = new getID3;
  $track = $getID3->analyze( '/home'.$hdd .'/mp3base/'.$subpapka.'/'.$row['filename'] );
  
  if (isset($track['id3v2']['APIC'][0]['data'])) {
    $viewcover_base64 = base64_encode($track['id3v2']['APIC'][0]['data']);
    $viewcover = <<<HTML
<img src="data:{$track['id3v2']['APIC'][0]['mime']};base64,{$viewcover_base64}" class="track_logo" alt="��������� ������� mp3 {$row['artist']} - {$row['title']}" title="������� ��������� mp3 {$row['artist']} - {$row['title']}" />
HTML;
    $tpl->set( '{track-logo}', $viewcover);
  }
  else $tpl->set( '{track-logo}', '<img src="'.$config['http_home_url'].'templates/'.$config['skin'].'/images/audio_big.png" class="track_logo" alt="������� ��������� mp3 '.$row['artist'].' - '.$row['title'].'" title="��������� ������� mp3 '.$row['artist'].' - '.$row['title'].'" />' );
} else $tpl->set( '{track-logo}', '<img src="'.$config['http_home_url'].'templates/'.$config['skin'].'/images/audio_big.png" class="track_logo" alt="������� ��������� mp3 '.$row['artist'].' - '.$row['title'].'" title="��������� ������� mp3 '.$row['artist'].' - '.$row['title'].'" />' );

if ( $row['description'] != '' ) {
 $tpl->set( '{comment}', $row['description'] );
 $tpl->set_block( "'\\[comment\\](.*?)\\[/comment\\]'si", "\\1" );
} else $tpl->set_block( "'\\[comment\\](.*?)\\[/comment\\]'si", "" );

//����������� �������,���� ���� ������ � �����-�� ������
if (($row['alb_aid']) and ($row['alb_approve'] == '1')) {
  if ( $row['alb_year'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
  else $year_alb = ' ('.$row['alb_year'].')';
  
  $genreid = $row['alb_genre'];
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
        
  if ($genre1 !== '') {
    $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
    $tpl->set( '{genre_mp3}', '<tr><td style="padding-top:3px;"><table border="0" cellspacing="0" cellpadding="0"><tr><td><img src="{THEME}/images/genre.png" align="top" /> &nbsp; ����: &nbsp;</td><td><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a></td></tr></table></td></tr>' );
  }
  else {
    $alb_genre_tab = '';
    $tpl->set( '{genre_mp3}', '' );
  }

  if ($row['alb_image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['alb_image_cover'];
  if (!$row['alb_transartist']) {
    $row['alb_transartist'] = $transartist;
    $row['alb_artist'] = $row['artist'];
  }
  
  $tpl->set( '{album}', '<tr><td height="10"><div class="line" style="margin:5px 0;"></div></td></tr><tr><td style="padding-top:3px;">
  <table width="100%"><tr><td valign="top" style="padding-top:3px;" width="150"><img src="{THEME}/images/viewedit.png" valign="top" /> &nbsp; ���� � �������:</td>
    
  <td align="right" valign="top"><a href="/music/'.$row['alb_aid'].'-album-'.$row['alb_transartist'].'-'.totranslit( $row['alb_album'] ).'.html" title="�������� ������ '.$row['alb_artist'].' - '.$row['alb_album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:right; margin:0 10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ������ '.$row['alb_artist'].' - '.$row['alb_album'].'" title="������� ��������� ������ '.$row['alb_artist'].' - '.$row['alb_album'].'" /><b>'.$row['alb_artist'].'</b><br />'.$row['alb_album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td></tr></table></td></tr>
  
  <tr><td height="10"><div class="line" style="margin-top:10px;"></div></td></tr>' );

    // ��� ������� ���� ����
  $row_alb = $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE artist = '$art' AND aid != '$row[alb_aid]' AND approve = '1' ORDER BY RAND() DESC, album LIMIT 2" );
    if ( $db->num_rows() > 0 ) {
    $toallalbums = '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">��� ������� <font color="#3367AB">'.$artist.'</font></div><table width="100%"><tr>';
     while( $row_alb = $db->get_row() ) {
        $genreid = $row_alb['genre'];
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
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
        if ( $row_alb['year_alb'] == '0' OR $row_alb['year_alb'] == '00' OR $row_alb['year_alb'] == '000' OR $row_alb['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row_alb['year_alb'].')';
        if ($row_alb['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row_alb['image_cover'];
        $toallalbums .= '<td width="50%" valign="top"><a href="/music/'.$row_alb['aid'].'-album-'.$row_alb['transartist'].'-'.totranslit( $row_alb['album'] ).'.html" class="albums" title="�������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'"><img src="/uploads/albums/'.$img_album.'" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'" title="������� ��������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'" /><b>'.$row_alb['artist'].'</b><br />'.$row_alb['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
        if ($db->num_rows() == 1) $toallalbums .= '<td width="50%" valign="top"></td>';
      }

      $toallalbums .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist.'.html" title="�������� � ������� ��� ������� '.$artist.'">�������� ��� ������� '.$artist.'</a></div><div style="clear:both;"></div></td></div></tr></table></div>';
      $tpl->set( '{allalbums}', '<div class="mservice_viewtrack">'.$toallalbums.'</div>' );
    } else  $tpl->set( '{allalbums}', '<div class="mservice_viewtrack"><div class="allalb" style="margin:0 10px 10px;"><a href="/music/albums-'.$row['alb_transartist'].'.html" title="������� ��������� ��� mp3 ������� '.$row['artist'].'">�������� ��� ������� '.$row['alb_artist'].'</a></div><div style="clear:both;"></div></div>' );
    $meta_album = ' ������ '.$row['alb_artist'].' - '.$row['alb_album'].$year_alb.' #'.$genre.'.';
} else {
  $meta_album = '';
  $tpl->set( '{album}', '' );
  $tpl->set( '{genre_mp3}', '' );
  // ������� ���� ������ � ����� �������?
  $row_alb = $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE artist = '$art' AND approve = '1' ORDER BY  year DESC, album LIMIT 2" );
  if ( $db->num_rows() > 0 ) {
    $toallalbums = '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">������� <font color="#3367AB">'.$artist.'</font></div><table width="100%"><tr>';
    while( $row_alb = $db->get_row() ) {
        $genreid = $row_alb['genre'];
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
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
        if ( $row_alb['year_alb'] == '0' OR $row_alb['year_alb'] == '00' OR $row_alb['year_alb'] == '000' OR $row_alb['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row_alb['year_alb'].')';
        if ($row_alb['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row_alb['image_cover'];
        $toallalbums .= '<td width="50%" valign="top"><a href="/music/'.$row_alb['aid'].'-album-'.$row_alb['transartist'].'-'.totranslit( $row_alb['album'] ).'.html" class="albums" title="�������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'"><img src="/uploads/albums/'.$img_album.'" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'" title="������� ��������� ������ '.$row_alb['artist'].' - '.$row_alb['album'].'" /><b>'.$row_alb['artist'].'</b><br />'.$row_alb['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
        if ($db->num_rows() == 1) $toallalbums .= '<td width="50%" valign="top"></td>';
      }

      $toallalbums .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist.'.html" title="�������� � ������� ��� ������� '.$artist.'">�������� ��� ������� '.$artist.'</a></div><div style="clear:both;"></div></td></div></tr></table></div>';
      $tpl->set( '{allalbums}', '<div class="mservice_viewtrack">'.$toallalbums.'</div>' );
  } else $tpl->set( '{allalbums}', '' );
}

$metatags['title'] = $nam_e2.' ������� ��������� mp3. '.$nam_e2.' ������� ������ '.$is_new.$clip_online_metatags.' | domain.com';
$metatags['description'] = '������� ��������� '.$nam_e2.' mp3.'.$meta_album.' ������ ������, ������� ������ '.$nam_e2.$clip_online_metatags.', '.$is_new.', ������� ��������';
$metatags['keywords'] = $to_keywords.', '.$to_keywords2.', ������� ���������,'.$is_new2.' mp3'.$clip_online_metatags.', �������, ������� ������, ��3, ������, ������� ��������, �����, ������ ������';

// ����������� ������ �����, ������� � �������
if ($row['clip_online'] != '') {
  $tpl->set( '{clip-title}', '<span class="tit" style="display:block; margin-bottom: 10px;">�������� ������ ����  <span style="font-weight:bold;">'.$row['artist'].' - '.$row['title'].'</span>:</span>');
  //����� �������� ����� ���������� ����� �� ���������� ====== ����� ��������� �������������
  /*if (strpos($row['clip_online'],'560')) {
     $tpl->set( '{clip-title}', '<div class="mservice_viewtrack" style="margin-bottom:8px"><b>�������� ������ ���� '.$row['artist'].' - '.$row['title'].':</b></div>
<script type="text/javascript">
    teasernet_blockid = 545188;
    teasernet_padid = 105877;
</script>
<script type="text/javascript" src="http://medigaly.com/inc/angular.js"></script>' );
    }
  else $tpl->set( '{clip-title}', '<div class="mservice_viewtrack" style="margin-bottom:8px"><b>�������� ������ ���� '.$row['artist'].' - '.$row['title'].':</b></div>
<script type="text/javascript">
    teasernet_blockid = 545183;
    teasernet_padid = 105877;
</script>
<script type="text/javascript" src="http://medigaly.com/inc/angular.js"></script>' );*/
  $tpl->set( '{clip-online}', '<div id="clip-online" style="margin-bottom:10px;"><center>'.$row['clip_online'].'</center></div>' );
  $tpl->set( '{clip-end}', '<div class="allcli" style="margin:10px;"><a href="/music/artistclips-'.$transartist.'.html" title="��������� �������� ��� ����������� ����� '.$row['artist'].'">�������� ��� ����� '.$artist.'</a></div><div style="clear:both;"></div>' );
}
else {
    $tpl->set( '{clip-title}', '' );
    $tpl->set( '{clip-online}', '' );
    $tpl->set( '{clip-end}', '' );
}

///////////////// + ����� ��� ������ ����������� (���-�� $mscfg['limit_tracks_of' ������� � �������) + ������� + ��������� ������ �� "��� ����� ����������� case 'artistracks'"

// ����� ������� �� ���������  ����� ������ �����
	$dotcount = substr_count($row['artist'], ".");
	
	if ($dotcount > 1) $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE '$art%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0," . $mscfg['limit_tracks_of'] );
//������� ������ ��� ����������� w&w
  elseif ($row['artist'] == 'W&W')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'W&W%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//������� ������ ��� ����������� g&g
  elseif ($row['artist'] == 'g&g')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'g&g%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//������� ������ ��� ����������� A*M*E
  elseif ($row['artist'] == 'A*M*E')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'a*m*e%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//������� ������ ��� ����������� M.O
  elseif ($row['artist'] == 'M.O')  $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE artist LIKE 'M.O%' AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
//===============         ����� ������ ������ ��� ������ �����������
	else {
		$sqlartist = '"'.$art.'"';
		$db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
	}
	
	if ( $db->num_rows( ) >= 5 ) {
		$alltrackslink = '<div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="��������� ������� mp3, ��� ����������� ����� '.$row['artist'].'">������� ��������� ��� ��3 ����� '.$artist.'</a></div><div style="clear:both;"></div>';
        
		while( $row = $db->get_row( ) ) {
			$more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="������� ��������� ��3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
		}
    $tpl->set( '{more-tracks}', $more_tracks );
    $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">������� ������ mp3 ����� <span style="font-weight:bold;">'.$artist.'</span>:</span><br />' );
    $tpl->set( '{more-tracks-end}', '</div>' );
	} elseif ( ($db->num_rows( ) < 5 ) AND ($db->num_rows( ) > 0) ) {
    $alltrackslink = '<div class="alltra" style="margin:10px;"><a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="��������� ������� mp3, ��� ����������� ����� '.$row['artist'].'">������� ��������� ��� ��3 ����� '.$artist.'</a></div><div style="clear:both;"></div>';
        
		while( $row = $db->get_row( ) ) {
			$more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="������� ��������� ��3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
			$mid_tracks[] = $row['mid'];
		}

    $parts_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " � ", " vs ", " vs. ", " pres ", " pres. ", ", ");
    $tempartists = str_ireplace($parts_array, "$$$$$", $artist);
    $artists_array = explode( "$$$$$", $tempartists );
    
    if ( ($tempartists != $artist) AND (count($artists_array) > 1) ) {
      $sqlartist1 = '("'.addslashes($artists_array[0]).'")';
      for($i=1; $i<count($artists_array); $i++) {
        $sqlartist1 .= ' ("'.addslashes($artists_array[$i]).'")';
     }

     $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$sqlartist1."' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );

     while( $row = $db->get_row( ) ) {
      if (!in_array($row['mid'], $mid_tracks)) $more_tracks2 .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="������� ��������� ��3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
     }
    }
    if ( $more_tracks2 != '' ) {
    $more_tracks = $more_tracks.$more_tracks2;
    $artists_tmp = str_replace("$$$$$", ", ", $tempartists);
    } else $artists_tmp = $artist;
    $tpl->set( '{more-tracks}', $more_tracks );
    $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">������� ������ mp3 ����� <span style="font-weight:bold;">'.$artists_tmp.'</span>:</span><br />' );
    $tpl->set( '{more-tracks-end}', '</div>' );

	} else {
    $parts_array = array(" ft ", " feat ", " ft. ", " feat. ", " and ", " & ", " � ", " vs ", " vs. ", " pres ", " pres. ", ", ");
    $tempartists = str_ireplace($parts_array, "$$$$$", $artist);
    $artists_array = explode( "$$$$$", $tempartists );
    
    if ( ($tempartists != $artist) AND (count($artists_array) > 1) ) {
      $sqlartist1 = '("'.addslashes($artists_array[0]).'")';
      for($i=1; $i<count($artists_array); $i++) {
        $sqlartist1 .= ' ("'.addslashes($artists_array[$i]).'")';
      }

      $db->query( "SELECT mid, artist, transartist, title, clip_online FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$sqlartist1."' IN BOOLEAN MODE) AND mid != '$mid' AND approve = '1' ORDER by time DESC LIMIT 0,".$mscfg['limit_tracks_of'] );
      if ( $db->num_rows( ) > 0 ) {
        while( $row = $db->get_row( ) ) {
          $more_tracks .= '&raquo; <a href="'.$config['http_home_url'].'music/'.$row['mid'].'-mp3-'.$row['transartist'].'-'.totranslit( $row['title'] ).'.html" title="������� ��������� ��3 '.$row['artist'].' - '.$row['title'].' mp3"><b>'.$row['artist'].'</b> - '.$row['title'].'</a>'.clipOnline( $row['clip_online'] ).'<br />';
        }
        $tpl->set( '{more-tracks}', $more_tracks );
        $tpl->set( '{title-more-tracks}', '<div class="mservice_viewtrack"><span class="tit" style="display:block;">������� ������ mp3 ����� <span style="font-weight:bold;">'.str_replace("$$$$$", ", ", $tempartists).'</span>:</span><br />' );
        $tpl->set( '{more-tracks-end}', '</div>' );
      } else  {
        $tpl->set( '{more-tracks}', '' );
        $tpl->set( '{title-more-tracks}', '<div style="margin:10px 0;">� ���������, � <b>'.$artist.'</b> ������ ��� ������ �� ����� �����.</div>' );
        $tpl->set( '{more-tracks-end}', '' );
      }

    } else {
      $tpl->set( '{more-tracks}', '' );
      $tpl->set( '{title-more-tracks}', '<span class="tit" style="display:block;">������� ������ mp3 ����� <span style="font-weight:bold;">'.$artist.'</span>:</span><div style="margin:10px 0;">� ���������, � <b>'.$artist.'</b> ������ ��� ������ �� ����� �����.</div>' );
      $tpl->set( '{more-tracks-end}', '' );
    }
	}
  if ($toptracks) $tpl->set( '{top-tracks}', $toptracks ); else $tpl->set( '{top-tracks}', '' );
  $tpl->set( '{alltrackslink}', $alltrackslink );

$tpl->compile( 'viewtrack' );

$tpl->load_template( 'mservice/commentit.tpl' ); $tpl->compile( 'commentit' );
$mcontent .= $tpl->result['viewtrack'];
$mcontent .= $tpl->result['commentit'];
$tpl->result['viewtrack'] = FALSE;

}
break;

// �������� �� ����� ������� ����������� $nameartist
case 'artistracks':

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
if (($limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim']) < 0 ) {
  $limit = 0;
  $page = 1;
}

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';

$nameartist = addslashes($parse->process( $_REQUEST['nameartist'] ));

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'title, artist';
  $sortactive = '�� �������� ����� &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">�� ����������� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = '�� ���� ���������� &#8593;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">�� ����������� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">�� �������� &#8595;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = '�� �������� &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'.html">�� ����������� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}
else {
  $sqlsort = 'artist, title';
  $sortactive = '�� ����������� &#8595;';
  $sortactive_no = '<li><a href="/music/artistracks-'.$nameartist.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/artistracks-'.$nameartist.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}

$db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist = '$nameartist' AND approve ='1'" );

if ( $db->num_rows( ) == 0 ) {

  $db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice WHERE transartist LIKE '%$nameartist%' AND approve ='1'" );
  
  if ( $db->num_rows( ) == 0 ) {
  	$metatags['title'] = '��� ������ ����� ����������� �� '.$config['home_title_short'];
    $stop[] = '� ���������, �� ����� ����� ��� ��� ������� ������ ����� �����������. <a href="'.$config['http_home_url'].'music/massaddfiles.html">�� �� ������ ��� ���������!</a>';
  } else {
    $ok_result = TRUE;
    $ok_result2 = TRUE;
  }
} else $ok_result = TRUE;
	
if ($ok_result) {
  if ($ok_result2) $query_artist[] = str_replace("-"," ",$nameartist);
  else while( $row = $db->get_row( ) ) $query_artist[] = $row['artist'];

  if (count($query_artist) == 1 ) {
    $artist1 = $artist2 = $query_artist[0];
    $sqlartist = addslashes($artist1);
    $sqlartist = '"'.$sqlartist.'"';
    /// ����� ������� �� ���������  ����� ������ �����
    $dotcount = substr_count($query_artist[0], ".");
    if ($dotcount > 1) $manydots = TRUE;
  } else {
    /// ����� ������� �� ���������  ����� ������ �����
    $dotcount = substr_count($query_artist[0], ".");
    if ($dotcount > 1) {
        $manydots = TRUE;
        $artist2 = addslashes($query_artist[0]);
        for($i=1; $i<count($query_artist); $i++) {
            $artist3.= " OR artist LIKE '".addslashes($query_artist[$i])."%'";
        }
    } else {
      $manyartists = TRUE;
      $artist2 = '("'.addslashes($query_artist[0]).'")';
      for($i=1; $i<count($query_artist); $i++) {
        $artist2 .= ' ("'.addslashes($query_artist[$i]).'")';
      }
    }
    $artist1 = implode(", ",$query_artist);    
  }
if ( strlen( $artist1 ) < 41 ) {
	$nam_e1 = $artist1;
	$nam_e2 = 'c������ ��������� ��� mp3 �����, ������� ������, ������ ������';
}
elseif ( strlen( $artist1 ) < 50 ) {
	$nam_e1 = substr( $artist1, 0, 55 );
	$nam_e2 = 'c������ ��������� ��� mp3 �����, ������� ������';
}
else {
	$nam_e1 = substr( $artist1, 0, 50 ).'...';
	$nam_e2 = 'c������ ��������� ��� mp3 �����, ������� ������';
}

$metatags['title'] = $artist1.' - c������ ��������� ��� mp3 �����, ��� ����� ����� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = $artist1.' - c������ ��������� ��� �����, ��� ����� ����� �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����������� ����� '.$artist1.' mp3, ����� �����, �����������, ����������� �����, ������� ������, ��������� ������� ��� ����� '.$artist1.', new, ������� ��3 ���������, ������ ��� �����������, ��� ���� '.$artist1.' mp3 ���������, ������� ��������, �������, �������';

// ������� ������� ����������� � ��������
$art_artist1 = addslashes($artist1);
$row2 = $db->super_query( "SELECT image_cover, genre, description FROM ".PREFIX."_mservice_artists WHERE artist = '$art_artist1'" );

if ($row2['genre']) {
  list ($genre1_art, $genre_art) = musicGenre($row2['genre']);
          
  if ($genre1_art !== '') $art_genre_tab = '<a class="genre'.$row2['genre'].'" href="/music/genre-albums-'.$genre1_art.'.html">#'.$genre_art.'</a>';
  else $art_genre_tab = '';
}
if ($row2['image_cover']) $mtitle .= '<table width="100%"><tr><td><h1 class="tit"><font color="#3367AB">'.$artist1.' '.$art_genre_tab.'</font></h1></td></tr><tr><td style="text-align:center; padding:5px 0;"><img src="/uploads/artists/'.$row2['image_cover'].'" style="border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ��� ��3 ����� '.$row['artist'].'" title="��������� ������� ��� ��3 ����� '.$row['artist'].'" /></td></tr></table>';

if ($row2['description']) $mtitle .= '<table width="100%"><tr><td style="padding:10px 20px;">'.$row2['description'].'</td></tr></table>';

if ($row2['image_cover'] OR $row2['description']) $mtitle .= '<table width="100%" style="margin-bottom:10px;"><tr><td style="padding:10px 0 10px 20px;">����������:</td><td width="10"></td><td>

</td><td width="20"></td><td>

</td></tr></table>';

// �������
	$addslashesartist = addslashes($query_artist[0]);
	if (count($query_artist) > 1 ) {
    for($i = 1; $i < count($query_artist); $i++) {
      $artist3.= " OR artist = '".addslashes($query_artist[$i])."'";
    }
  }
  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1' ORDER BY year_alb DESC, album LIMIT 2" );
  
  if ( $db->num_rows() > 0 ) {
    $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:10px;"><div class="album_tracks_tit">... � ����� ������� <font color="#3367AB">'.$artist1.'</font></div><table width="100%"><tr>';
    while( $row = $db->get_row() ) {
        $genreid = $row['genre'];
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
        
        if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
        else $alb_genre_tab = '';
        
      if (!$cnt_alb) $cnt_alb = $row['cnt'];
      if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
      else $year_alb = '('.$row['year_alb'].')';
      if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
      $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="�������� ������ '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ������ '.$row['artist'].' - '.$row['album'].'" title="������� ��������� ������ '.$row['artist'].' - '.$row['album'].'" /><b>'.$row['artist'].'</b><br />'.$row['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
    }
    if ( $cnt_alb == 1 ) $mcontent .= '<td width="50%" valign="top"></td>';
    elseif ( $cnt_alb > 2 ) $mcontent .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$nameartist.'.html" title="�������� � ������� ��������� ��� ������� '.$artist1.'">�������� ��� ������� '.$artist1.'</a></div><div style="clear:both;"></div></td></div>';
    $mcontent .= '</tr></table></div>';
	}
	$db->free( );
// ����� ��������

if ($manydots == TRUE) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE artist LIKE '$artist2%'$artist3 AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE artist LIKE '$artist2%'$artist3 AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
} elseif ($manyartists == TRUE) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$artist2."' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('".$artist2."' IN BOOLEAN MODE) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
} else {
  //������� ������ ��� ����������� w&w
  if ($nameartist == 'ww') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // ����� �������� ������

  //������� ������ ��� ����������� g&g
  elseif ($nameartist == 'gg') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // ����� �������� ������

  //������� ������ ��� ����������� A*M*E
  elseif ($nameartist == 'ame') {
    $artist2 = $query_artist[0];
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // ����� �������� ������
  
    //������� ������ ��� ����������� 4'k
  elseif ($nameartist == '4039k') {
    $artist2 = addslashes($query_artist[0]);
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // ����� �������� ������
  
  //������� ������ ��� ����������� M.O
  elseif ($nameartist == 'm.o') {
    $artist2 = addslashes($query_artist[0]);
    for($i=1; $i<count($query_artist); $i++) {
      $artist3.= " OR artist LIKE '$query_artist[$i]%'";
    }
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (artist LIKE '$artist2%'$artist3) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
  // ����� �������� ������

  // ====================  �����  ������  ������    ������   ������������
  else {
  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('$sqlartist' IN BOOLEAN MODE) AND approve = '1' ORDER by ".$sqlsort." LIMIT ".$limit.",".$mscfg['track_page_lim'] );
  }
}

if ( $db->num_rows() == 0 ) $stop[] = '�������� �������� � ������� '.$artist1.'! <a href="javascript:history.go(-1)">��������� �� ���������� ��������</a>';

if ( count( $stop ) == 0 ) {
  
	if ( $is_logged ) $mcontent .= '<table width="100%" height="35"><tr><td width="30%">&nbsp;</td><td width="40%"><div class="favartclass"><img src="{THEME}/images/favart.png" align="top" />������� �����������:</div></td><td><div id="favart-artist">'.showMyFavArtistsView( $nameartist ).'</div></td></tr></table>';

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<tr>
<th width="70%" height="20">
HTML;

if ( $db->num_rows() > 1 ) $mcontent .= <<<HTML
<dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl>
HTML;

$mcontent .= <<<HTML
</th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) { 
	$count = $row['cnt'];
	$mtitle .= '<h1 class="tit">��� mp3 ����� <font color="#3367AB">'.$artist1.'</font>. ������� '.declension($count, array('����', '�����', '������')).'</h1>';
	$mcontent .= '<div id="navigation_up"></div>';

}

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
$mcontent .= '</table>';

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}
// ������������ ���������
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/artistracks-'.$nameartist.'-page-'.$t2.$sortpage.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artistracks-'.$nameartist.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/artistracks-'.$nameartist.'-page-';
  $seo_mode = $sortpage.'.html';


$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artistracks-'.$nameartist;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

if ( $count > $mscfg['track_page_lim'] ) {
  $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
  if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
  else {
    include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
    $mcontent .= $downloads_top_week;
  }
}
$mcontent .= '</div>';
}
}
break;

// �������� ���������� ������ ��� ������������ ������ �� ������ ������ ��� ������������ IP � ������� ������� ����� ������
case 'download':

$mid = intval( $_REQUEST['mid'] );
$row = $db->super_query( "SELECT mid, time, artist, title, filename, hdd FROM ".PREFIX."_mservice WHERE mid = '$mid' AND approve = '1'" );
if ( $row['mid'] == FALSE ) $stop[] = '������������� ����� ���� �� ������, �������� ��� �� ����� �� ������������, ���� �� ��� �����! <a href="javascript:history.go(-1)">��������� �� ���������� ��������</a>';

if ( strlen( $nam_e2 = $row['artist'].' - '.$row['title'] ) > 65 ) $nam_e1 = substr( $nam_e2, 0, 65 ).'...'; else $nam_e1 = $nam_e2;

     $metatags['title'] = $nam_e2.' mp3 �������� ���������� �����';
     $metatags['description'] = '�������� ���������� � ������ ������� �� ����: '.$nam_e2.' mp3, ��� �������� � ��� �����������.';
     $metatags['keywords'] = '������� ���������, mp3 '.$nam_e2.', ��3 ������ ������� ���������, �������, ������ ������, download mp3, new, '.$nam_e2;

if ( count( $stop ) == 0 ) {

$mtitle .= '<h3 class="tit">���������� <span>'.$row['artist'].' - '.$row['title'].'</span></h3>';

$play = $config['http_home_url'].'music/play-'.$mid.'.html';

if ( $mscfg['mfp_type'] == 2 ) $play_hg = 130; else $play_hg = 100;
if ( $mscfg['mfp_type'] == 3 and $mscfg['playning_allow_visual'] == 1 ) $play_hg = 380;

$mcontent .= <<<HTML
<script type="text/javascript">
function playTrack( ) {
  window.open( "{$play}", "playning", "location=0,status=0,scrollbars=0,width=500,height={$play_hg}" );
}
</script>
HTML;

if( ! $is_logged ) $member_id['user_group'] = 5;
if ( $user_group[$member_id['user_group']]['mservice_filedown'] != 1 ) $stop[] = '� ��� �� ���������� ���� ��� ���������� ������!';

$subpapka = nameSubDir( $row['time'], $row['hdd'] );
$transartist = totranslit( $row['artist']);
		
$tpl->load_template( 'mservice/download.tpl' );

$tpl->set( '{THEME}', $THEME );
$tpl->set( '{title}', $row['title'] );
$tpl->set( '{artist}', $row['artist'] );
$tpl->set( '{alltrackslink}', $config['http_home_url'].'music/artistracks-'.$transartist.'.html' );
$tpl->set( '{alltracks}', '������� ��������� ��� mp3 ����� '.$row['artist'] );
$tpl->set( '{view}', '/music/'.$row['mid'].'-mp3-'.totranslit( $row['artist'] ).'-'.totranslit( $row['title'] ).'.html' );
$tpl->set( '{player}', BuildPlayer( $row['filename'], $row['artist'], $row['title'], $subpapka, $row['hdd'], $THEME ) );
$tpl->set( '{play}', '<a href="#" onClick="playTrack( ); return false;">���������� � ����� ���� Flash-�����</a>' );
$tpl->set( '{linktime}', (intval($mscfg['link_time']/3600)));
$tpl->set( '{link}', downloadLinkMservice( $row['filename'], $mscfg['downfile_name'], $row['artist'], $row['title'], $mscfg['link_time'], $subpapka, $row['hdd'] ) );

if( $config['allow_banner'] ) {
  include_once ENGINE_DIR . '/modules/banners.php';
  if ($config['skin'] == 'smartphone') $tpl->set( '{adv}', $banners['view-download-smartphone'] );
  else $tpl->set( '{adv}', $banners['view-download'] );
} else $tpl->set( '{adv}', '' );

if( $is_logged ) $tpl->set( '{link_to_register}', '' );
else $tpl->set( '{link_to_register}', '<a href="#virtual_loginform" class="lbn" style="margin:10px 0 10px 270px;" rel="facebox"><b style="background-position: 100% -215px; padding: 0 10px;">���� / �����������</b></a><div style="clear:both;"></div>' );

$tpl->compile( 'download' );
$mcontent .= $tpl->result['download'];
$tpl->result['download'] = FALSE;

include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
$mcontent .= $downloads_top_week;

}
break;

// !!! ������������� ������� �� ������������ (artist)
case 'artist':

if ($letter == "�") header("location: {$config['http_home_url']}music/artist-%C8.html"); 
elseif ($letter == "�") header("location: {$config['http_home_url']}music/artist-%C5.html");
elseif ($letter == "�") header("location: {$config['http_home_url']}music/artist-0-9.html");
elseif ($letter == "�") header("location: {$config['http_home_url']}music/artist-0-9.html");
elseif ($letter == "�") header("location: {$config['http_home_url']}music/artist-0-9.html"); 

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';

$letter = $parse->process( $_REQUEST['letter'] );

if ($letter == "0-9") {
	$letter = "[^�-��a-z]";
	$letter2 = "OR artist REGEXP '^�' OR artist REGEXP '^�' OR artist REGEXP '^�'";
}
elseif ($letter == "�") {
	$letter2 = "OR artist REGEXP '^�'";
	$letter3 = ", �";
}
elseif ($letter == "�") {
	$letter2 = "OR artist REGEXP '^�'";
	$letter3 = ", �";
}

if ($letter == "[^�-��a-z]") {
$mtitle .= '<h3 class="tit">��� <span>�����������</span> �� <span>����� (������), �, �, �</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$metatags['title'] = '���������� ��������� ������������ �� ����� (������), �, �, � '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '���������� ��������� ������������ �� ����� (������), �, �, � �� '.$config['description'];
$metatags['keywords'] = 'mp3 �����, ������� ����� ������������ �� ����� � � �, ����� 0 1 2 3 4 5 6 7 8 9, ��������� � ��� �����������, new, ����������� ����� ��3, ��� mp3 ����� �� �����, �����, ������� ��������� ��3, ����� mp3 ����� ���������, �������, �������';
} else {
$mtitle .= '<h3 class="tit">��� <span>�����������</span> �� ����� - <span>'.$letter.$letter3.'</span></h3>';
$mcontent .= '<div id="navigation_up"></div>';

$metatags['title'] = '���������� ��������� ������������ �� ����� '.$letter.$letter3.' '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '���������� ��������� ������������ �� ����� '.$letter.$letter3.' �� '.$config['description'];
$metatags['keywords'] = 'mp3 �����, ������� ����� ������������ �� ����� '.$letter.$letter3.', new, ��������� � ��� �����������, �����, ����������� ����� ��3, ��� mp3 ����� �� ����� '.$letter.$letter3.', ������� ��������� ��3, ����� mp3 ����� ���������, �������, �������';
}

$db->query( "SELECT artist, transartist, COUNT(artist) as count, (SELECT COUNT(DISTINCT artist) FROM ".PREFIX."_mservice WHERE (artist REGEXP '^$letter' $letter2)) as cnt FROM ".PREFIX."_mservice WHERE (artist REGEXP '^$letter' $letter2) GROUP BY artist LIMIT " . $limit . "," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) {
	if ($letter == '[^�-��a-z]') $letter = '0-9';
	$stop[] = '�� ������ �� ���� ����������� �� ����� (�����) - "'.$letter.'".!';
}

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table width="100%">
<thead><tr>
<th width="80%" height="20">&nbsp;</th>
<th width="20%">������</th>
</tr><tr><td colspan="5" height="10"></td></tr></thead>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) $count = $row['cnt'];
if( strlen( $row['artist'] ) > ($mscfg['track_artist_substr']+40) ) $artist = substr( $row['artist'], 0, ($mscfg['track_artist_substr']+40) ) . "...";
else $artist = $row['artist'];

$tpl->load_template( 'mservice/artistlist.tpl' );
$tpl->set( '{artist}', $artist );
$tpl->set( '{trekov}', $row['count'] );
$tpl->set( '{view_link}', $config['http_home_url'].'music/artistracks-'.$row['transartist'].'.html' );

$tpl->compile( 'artistlist' );
$mcontent .= $tpl->result['artistlist'];
$tpl->result['artistlist'] = FALSE;

}
$mcontent .= '</table>';

if ($count < 10) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
	$mcontent .= $downloads_top_week;
}

// ������������ ���������
$count_d = $count / $mscfg['track_page_lim'];

if ($letter == '�') { $letter = '%C0';
} elseif ($letter == '�') { $letter = '%C1';
} elseif ($letter == '�') { $letter = '%C2';
} elseif ($letter == '�') { $letter = '%C3';
} elseif ($letter == '�') { $letter = '%C4';
} elseif ($letter == '�') { $letter = '%C5';
} elseif ($letter == '�') { $letter = '%C6';
} elseif ($letter == '�') { $letter = '%C7';
} elseif ($letter == '�') { $letter = '%C8';
} elseif ($letter == '�') { $letter = '%CA';
} elseif ($letter == '�') { $letter = '%CB';
} elseif ($letter == '�') { $letter = '%CC';
} elseif ($letter == '�') { $letter = '%CD';
} elseif ($letter == '�') { $letter = '%CE';
} elseif ($letter == '�') { $letter = '%CF';
} elseif ($letter == '�') { $letter = '%D0';
} elseif ($letter == '�') { $letter = '%D1';
} elseif ($letter == '�') { $letter = '%D2';
} elseif ($letter == '�') { $letter = '%D3';
} elseif ($letter == '�') { $letter = '%D4';
} elseif ($letter == '�') { $letter = '%D5';
} elseif ($letter == '�') { $letter = '%D6';
} elseif ($letter == '�') { $letter = '%D7';
} elseif ($letter == '�') { $letter = '%D8';
} elseif ($letter == '�') { $letter = '%D9';
} elseif ($letter == '�') { $letter = '%DD';
} elseif ($letter == '�') { $letter = '%DE';
} elseif ($letter == '�') { $letter = '%DF';
} elseif ($letter == '[^�-��a-z]') { $letter = '0-9';
}
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/artist-'.$letter.'-page-'.$t2.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/artist-'.$letter.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$link = $config['http_home_url'].'music/artist-'.$letter.'-page-';
$seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/artist-'.$letter;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
}
break;

// ���������� � �������������� ����� )))
case 'happy-new-year':

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'title, artist';
  $sortactive = '�� �������� ����� &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">�� ����������� &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = '�� ���� ���������� &#8593;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">�� ����������� &#8595;</a></li><li><a href="/music/happy-new-year/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">�� �������� &#8595;</a></li>';
}
elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = '�� �������� &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year.html">�� ����������� &#8595;</a></li><li><a href="/music/happy-new-year/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}
else {
  $sqlsort = 'artist, title';
  $sortactive = '�� ����������� &#8595;';
  $sortactive_no = '<li><a href="/music/happy-new-year/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/happy-new-year/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/happy-new-year/sort-2.html">�� ���� ���������� &#8593;</a></li>';
}

if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';

$metatags['title'] = '���������� � �������������� ����� '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '���������� � �������������� ����� �� '.$config['description'];
$metatags['keywords'] = '������� ��� ����� ��� ����� ��� mp3, ����������� ����� ���, ��������� ������� ��� ����� ���������, ������� ��3 ���������, ����� ��� ������ ��� �����������, ��� ���� ������ ���� mp3 ���������, ���������� �����';

$mtitle .= '<h1 class="tit"><span>���������� � �������������� �����</span></h1>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE (MATCH(title) AGAINST('(+���* +���*) (��������*) (�������*) (+new* +year*) (christmas) (����) (���*) (����) (����� �����) (����� г�)' IN BOOLEAN MODE) OR mid IN (97498)) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE (MATCH(title) AGAINST('(+���* +���*) (��������*) (�������*) (+new* +year*) (christmas) (����) (���*) (����) (����� �����) (����� г�)' IN BOOLEAN MODE) OR mid IN (97498)) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );

if ( $db->num_rows() == 0 ) $stop[] = '�������� ��������.';
if (count($stop)>0) $stop[] = '<a class=main href="javascript:history.go(-1)">��������� �����.</a>';

if ( count( $stop ) == 0 ) {

$mcontent .= '<div id="navigation_up"></div>';
$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<tr>
<th width="70%" height="20"><dl id="sample" class="dropdown">
        <dt><span>{$sortactive}</span></dt>
        <dd>
            <ul>
              {$sortactive_no}
            </ul>
        </dd>
    </dl></th>
<th width="10%">�����</th>
<th width="10%">������</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>
<tr>
HTML;

while ( $row = $db->get_row( ) ) {
if (!isset($count)) $count = $row['cnt'];
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

// ������������ ���������
$count_d = $count / $mscfg['track_page_lim'];
$pages = '';
for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

$plink = $config['http_home_url'].'music/happy-new-year-page-'.$t2.$sortpage.'.html';

if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/happy-new-year'.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

$link = $config['http_home_url'].'music/happy-new-year-page-';
$seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/happy-new-year';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
  else $next_page = '';

  if ( $count > $mscfg['track_page_lim'] ) {
    $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
    $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
    if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
    else {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
      $mcontent .= $downloads_top_week;
    }
  }
}

break;

// ����� �� ������, �����
case 'search':

$metatags['title'] = '����� �� ����� '.$config['home_title_short'];
$metatags['description'] = '����� �� ����� '.$config['description'];
$metatags['keywords'] = '����� mp3, ����� ��3 ���������, new, ������� ������, �������, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>����� �� �����</span> domain.com</h3>';
include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';

$mcontent .= $popular_search_artists;
$mcontent .= $popular_search_tracks;
$mcontent .= $popular_search_albums;

break;

// ��������� �������� ������ � ������ ������
case 'dosearch':

$searchtype = intval( $_REQUEST['searchtype'] );
$searchtext = $parse->remove( $parse->process( $_REQUEST['searchtext'] ) );

if ( isset($_REQUEST['page']) == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

if ( (isset($_REQUEST['sort']) == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if (isset($_POST['newpoisk'])) {
	$page = 1;
	$searchtext = $parse->remove( $parse->process( $_POST['searchtext'] ) );
	$sort = 0;
	$sortpage = '';
} else $searchtext = base64_decode(str_replace('_','/',str_replace('*','+',$searchtext)));

$textbase64 = str_replace('/','_',str_replace('+','*',base64_encode($searchtext)));
//echo $searchtext.'  ';
if ( $page == 1 ) $metapage = '';
else $metapage = '(�������� '.$page.') ';

$metatags['title'] = '���������� ������: '.$searchtext.' '.$metapage.'�� '.$config['home_title_short'];
$metatags['description'] = '���������� ������: '.$searchtext.' �� '.$config['description'];
$metatags['keywords'] = '����� mp3, ����� ��3 ���������, new, ������� ������, �������, '.$config['keywords'];

if ($searchtype == 4) {
  $limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2;

  if ( $sort == 1 ) {
    $sqlsort = 'album, artist';
    $sortactive = '�� �������� ������� &#8595;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">�� ����������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">�� ���� ������ &#8593;</a></li>';
  } elseif ( $sort == 2 ) {
    $sqlsort = 'year DESC, artist, album';
    $sortactive = '�� ���� ������ &#8593;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">�� ����������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">�� �������� ������� &#8595;</a></li>';
  } else {
  $sqlsort = 'artist, album';
  $sortactive = '�� ����������� &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">�� �������� ������� &#8595</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">�� ���� ������ &#8593;</a></li>';
  }
} else {
  $limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];
  if ( $sort == 1 ) {
    $sqlsort = 'title, artist';
    $sortactive = '�� �������� ����� &#8595;';
    $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">�� ����������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
  } elseif ( $sort == 2 ) {
  $sqlsort = 'time DESC, artist, title';
  $sortactive = '�� ���� ���������� &#8593;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">�� ����������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">�� �������� &#8595;</a></li>';
  } elseif ( $sort == 3 ) {
  $sqlsort = 'download DESC, view_count DESC, artist, title';
  $sortactive = '�� �������� &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'.html">�� ����������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
  } else {
  $sqlsort = 'artist, title';
  $sortactive = '�� ����������� &#8595;';
  $sortactive_no = '<li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-1.html">�� �������� ����� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-3.html">�� �������� &#8595;</a></li><li><a href="/music/search-'.$searchtype.'-'.$textbase64.'/sort-2.html">�� ���� ���������� &#8593;</a></li>';
  }
}

$searchtext2 = textSwitch ($searchtext,2);
// if ( $is_logged == FALSE ) $stop[] = '����� �������� ������ ������������������ �������������!';
if ( $searchtype == 0 or $searchtype == '' ) $stop[] = '�� �� ������� ��� ������!';
if ( $searchtext == '' ) $stop[] = '�� �� ����� ����� ��� ������!';
if ( (mb_strlen( $searchtext ) < 2) AND (mb_strlen( $searchtext2 ) < 2) ) $stop[] = '�������� ��� ������ ����� �� ����� ���� ������ ��� 2 �������!';

if ( count( $stop ) == 0 ) {

  if ($searchtype == 2) {$where_ft ='artist'; $searchtitle = '����� �� ������������';}
  elseif ($searchtype == 3) {$where_ft = 'title'; $searchtitle = '����� �� ������';}
  elseif ($searchtype == 4) {$where_ft = 'artist,album'; $searchtitle = '����� �� ��������';}
  else 	{$where_ft ='artist,title'; $searchtitle = '����� �����';}

  /// ����� ������� �� ���������  ����� ������ �����
  $dotcount = substr_count($searchtext, ".");
  if (($dotcount > 1) AND ( $searchtype == 2 )) {
    $db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE artist LIKE '$searchtext%' AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE artist LIKE '$searchtext%' AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );
  } else {
    //@setlocale(LC_ALL, 'ru_RU');
    // $searchtext.' ';

    // ���� �������� ����������� � ���� � ������� ����� ������������ ������
    if (($searchtype != 3) AND ( $searchtype != 4 )) {
      $searchtext_temp = trim(addslashes($searchtext));
      $searchtext_temp2 = trim(addslashes($searchtext2));
      $searchtext_temp = preg_replace("/\s{2,}/",' ', $searchtext_temp);
      $searchtext_temp2 = preg_replace("/\s{2,}/",' ', $searchtext_temp2);
      $mcontentartist0 = '';
      $mcontentartist = '';
      $row = $db->super_query( "SELECT DISTINCT artist, transartist FROM ".PREFIX."_mservice WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1' ORDER BY artist LIMIT 1" );
      if ( $row['artist'] != FALSE ) {
        $mcontentartist0 .= '<h3 class="tit_green" style="margin:0px 0px;">���������� ������ ������������...</h3>';
        $link_to_alltracks = $config['http_home_url'].'music/artistracks-'.$row["transartist"].'.html';
        // ���������� ������� 150�150 �����������
        $row2 = $db->super_query( "SELECT image_cover150, genre FROM ".PREFIX."_mservice_artists WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2')" );
        
        if ($row2['genre']) {
          list ($genre1_art, $genre_art) = musicGenre($row2['genre']);
          
          if ($genre1_art !== '') $art_genre_tab = '<div style="height:10px;"></div><a class="genre'.$row2['genre'].'" href="/music/genre-albums-'.$genre1_art.'.html">#'.$genre_art.'</a>';
          else $art_genre_tab = '';
        }
          
        if ($row2['image_cover150'] != '') $image_cover150 = $row2['image_cover150']; else $image_cover150 = 'artist_0.jpg';
        
        $mcontentartist0 .= '<table style="margin:10px;"><tr><td width="50%" valign="top"><a href="'.$link_to_alltracks.'" title="��������� ������� mp3, ��� ����������� ����� '.$row['artist'].'"><img src="/uploads/artists/'.$image_cover150.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ��� ��3 ����� '.$row['artist'].'" title="��������� ������� ��� ��3 ����� '.$row['artist'].'" /><br /><strong>'.$row['artist'].'</strong></a><br />'.$art_genre_tab.'</td></tr></table>';
        
        $mcontentartist0 .= '<div class="alltra" style="margin:10px;"><a href="'.$link_to_alltracks.'" title="��������� ������� mp3, ��� ����������� ����� '.$row['artist'].'">�������� ��� ��3 ����� '.$row['artist'].'</a></div><div style="clear:both;"></div>';
     
        // ������� ������� ���������� ����������� ����� ������������ ������
        $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE (artist = '$searchtext_temp' OR artist = '$searchtext_temp2') AND approve = '1' ORDER BY year_alb DESC, album LIMIT 2" );
  
        if ( $db->num_rows() > 0 ) {
          $mcontentartist .= '<div class="album_tracks_ramka" style="margin:10px 0px;"><div class="album_tracks_tit">... � ����� ��������</div><table width="100%"><tr>';
          while( $row = $db->get_row() ) {
            list ($genre1, $genre) = musicGenre($row['genre']);

            if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px;"></div><a class="genre'.$row['genre'].'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
            else $alb_genre_tab = '';
        
            if (!$art_alb) {
              $art_alb = $row['artist'];
              $transartist_alb = $row['transartist'];
            }
            if ($art_alb != $row['artist']) {
              $art_alb2 = $row['artist'];
              $alb_different = TRUE;
              $transartist_alb2 = $row['transartist'];
            }

          if (!$cnt_alb) $cnt_alb = $row['cnt'];
        
          if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
          else $year_alb = '('.$row['year_alb'].')';
        
          if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        
        $mcontentartist .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="�������� ������ '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" width="150" height="150" style="float:left; margin-right:10px; border:1px solid #E2EDF2; -webkit-border-radius:5px; -moz-border-radius:5px; border-radius:5px;" alt="��������� ������� ������ '.$row['artist'].' - '.$row['album'].'" title="������� ��������� ������ '.$row['artist'].' - '.$row['album'].'" /><b>'.$row['artist'].'</b><br />'.$row['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
          }
          
          if ( $cnt_alb == 1 ) $mcontentartist .= '<td width="50%" valign="top"></td>';
          elseif ( $cnt_alb > 2 ) {
            if ($alb_different) $mcontentartist .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist_alb2.'.html" title="�������� � ������� ��������� ��� mp3 ������� '.$art_alb2.'">�������� ��� ������� '.$art_alb2.'</a></div><div style="clear:both;"></div></td></div>';
            else $mcontentartist .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist_alb.'.html" title="�������� � ������� ��������� ��� mp3 ������� '.$art_alb.'">�������� ��� ������� '.$art_alb.'</a></div><div style="clear:both;"></div></td></div>';
          }
          $mcontentartist .= '</tr></table></div>';
        }
        // ����� �������� ��������� ������������
        $db->free( );
      }
    //����� ��������� ������������
    }
    /* 1.�������� ������� ������ �� 60�������� 2. �� � ������ ������� 3. ������� ����� ��� ��������� ������ ������ 4.�������� �� ��� �� �����/����� (����� ...) �� ������ 5-6. �������� ����� ����-�����, ������� � ������ �� ���������� 7.�������� ����� ������ ���� ������, trim'��� ������� � ������/����� ��������� */
    $searchtextsql = substr($searchtext,0,60);
    $searchtextsql = mb_strtolower($searchtextsql,'cp1251');
    $searchtext = stripslashes($searchtext);
    $searchtextsql = preg_replace("/[^\w\s\�\�\�\�\�\�\']/", " ", $searchtextsql);
    $searchtextsql  = textClear($searchtextsql);
    $searchtextsql  = str_replace(' 039', ' ', $searchtextsql );
    $searchtextsql = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $searchtextsql "));

    $searchtextsql2 = substr($searchtext2,0,60);
    $searchtextsql2 = mb_strtolower($searchtextsql2,'cp1251');
    $searchtext2 = stripslashes($searchtext2);
    $searchtextsql2 = preg_replace("/[^\w\s\�\�\�\�\�\�\']/", " ", $searchtextsql2);
    $searchtextsql2  = textClear($searchtextsql2);
    $searchtextsql2  = str_replace(' 039', ' ', $searchtextsql2 );
    $searchtextsql2 = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $searchtextsql2 "));

    //if ($member_id['user_id'] == 1017) echo $searchtextsql.' '.$searchtextsql2;
    // 1.���������� ����������=$searchtextword_count ���� � ��������� (������� ��� ����� � �.�.) 2. --||-- � ��������� ��� ����� � ������ $searchtextword_array � $searchtextword_array2 
    $list = "������0123456789'_"; 
    for ( $i = 192; $i < 256; $i++ ) {$list = $list.chr($i);}
    $searchtextword_count = str_word_count($searchtextsql,0,$list);//0 - ���������� ���������� ��������� ����
    $searchtextword_array = str_word_count($searchtextsql,1,$list);//1 - ������������ ������, ���������� ��� �����, �������� � ������ string
    $searchtextword_array2 = str_word_count($searchtextsql2,1,$list);//1 - ������������ ������, ���������� ��� �����, �������� � ������ string
    /* ���� ��������� �� ������ ����� � � ��� ���� ����� (2D-��� ������), �� �������� ��� �� "������+" � ������� ��������� ��� ���� "+", 
trim'��� ������� � ������/����� ��������� */
    //if ($searchtextword_count == 1) $searchtextsql = trim(preg_replace("/\x2D/", " +", "+$searchtextsql"));
    /*���� ��������� ������ ����� ��� �� 2�� ���� �� ������� ��������� ��� ������� � MySQL, ��������� �� ������ ����� 
� ���� ����� ����� ����� ������ 1 ����� (������ ������������ � �����), �� ��������� � ��������� ��� ������� ������ ��� 
������ in boolean mode ������� "+..*", trim'��� ������� � ������/����� ��������� */
    if ($searchtextword_count > 1) {
      $searchtextsql = '';
      $searchtextsql2 = '';
      foreach ($searchtextword_array as $searchtextword) {
        if (strlen($searchtextword) > 1) $searchtextsql .= '+'.$searchtextword.'* ';
      }
      $searchtextsql = trim($searchtextsql);
      foreach ($searchtextword_array2 as $searchtextword) {
        if (strlen($searchtextword) > 1) $searchtextsql2 .= '+'.$searchtextword.'* ';
      }
      $searchtextsql2 = trim($searchtextsql2);
    }
    $searchtextsql = addslashes($searchtextsql);
    $searchtextsql2 = addslashes($searchtextsql2);

    //if ($member_id['user_id'] == 1017) echo $searchtextsql.' '.$searchtextsql2;
    if ($searchtype == 4) $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['album_page_lim']*2);

    else $db->query( "SELECT mid, time, title, artist, download, view_count, filename, hdd, lenght, size, clip_online, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1') as cnt FROM ".PREFIX."_mservice WHERE MATCH({$where_ft}) AGAINST('($searchtextsql*) ($searchtextsql2*)' IN BOOLEAN MODE) AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['track_page_lim'] );
  }

  $mcontent = <<<HTML
<div id="prev_search"></div>
HTML;

  if ($searchtype == 4) {
  //����� ��������, ��� ������ �� ��������
    if ( $db->num_rows( ) == 0 ) {
      $mcontent .= <<<HTML
<script type="text/javascript">
showPrevSearch('{$searchtext}');
</script>
HTML;
      $podmod = TRUE;
    } else {
      $i = 1;
      $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><table width="100%"><tr>';
    
      while( $row = $db->get_row() ) {
        if (!isset($count)) {
          $count = $row['cnt'];
          $mcontent .= '<h3 class="tit">'.$searchtitle.': <span>'.$searchtext.'</span>, ������� <span>'.declension($count, array('����������', '����������', '����������')).'</span></h3>';
          $mcontent .= '<div id="navigation_up"></div>';
          if ( $db->num_rows() > 1 ) $mcontent .= '<dl id="sample" class="dropdown_alb"><dt><span>'.$sortactive.'</span></dt><dd><ul>'.$sortactive_no.'</ul></dd></dl>';
          else $mcontent .= '<dl id="sample" class="dropdown_alb"></dl>';
        }
        $genreid = $row['genre'];
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
          
        if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row['year_alb'].')';
        
        if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        
        $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="�������� ������ '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="��������� ������� ������ '.$row['artist'].' - '.$row['album'].'" title="��������� ������� ������ '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
        $i++;
        if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
      }
      $mcontent .= '</tr></table></div>';
    
      if ($count < $mscfg['album_page_lim']) $podmod = TRUE;
    
      // ������������ ���������
      $count_d = $count / ($mscfg['album_page_lim']*2);
      $pages = '';
      for ( $t = 0; $count_d > $t; $t ++ ) {
        $t2 = $t + 1;
        $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$t2.'-'.$textbase64.$sortpage.'.html';
    
        if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
        else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$textbase64.$sortpage.'.html';
        $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
        $array[$t2] = 1;
      }

      $link = $config['http_home_url'].'music/search-'.$searchtype.'-';
      $seo_mode = '-'.$textbase64.$sortpage.'.html';

      $npage = $page - 1;
      if ( isset($array[$npage]) ) {
        if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/search-'.$searchtype;
          $prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
        else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
      else $prev_page = '';
    
      $npage = $page + 1;
    
      if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
      else $next_page = '';

      if ( $count > $mscfg['album_page_lim']*2 ) {
        $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
        $albums_to_view = $count - (($page * $mscfg['album_page_lim']*2) - $mscfg['album_page_lim']*2);
        if ($albums_to_view < $mscfg['album_page_lim']) $podmod = TRUE;
        elseif ($albums_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
      }
      $db->free( );
    }
    if ($podmod == TRUE) {
      include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
      $mcontent .= $downloads_top_albums_week;
    }
  } else {
    //����� ��� ���� ������� �����, �� �����������, �� �����, ����� ��������
    if ($db->num_rows( ) > 1) $mcontent .= $mcontentartist0;
    
    $mcontent .= $mcontentartist;
    
    if ( $db->num_rows( ) == 0 ) {
      $mcontent .= <<<HTML
<script type="text/javascript">
showPrevSearch('{$searchtext}');
</script>
HTML;
      include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
      $mcontent .= $popular_search_artists;
      $mcontent .= $popular_search_tracks;
      $mcontent .= $popular_search_albums;
    } else {
      $mcontent .= '<div id="playlist"><table width="100%"><tr><th width="70%" height="20">';

      if ( $db->num_rows( ) > 1 ) $mcontent .= '<dl id="sample" class="dropdown"><dt><span>'.$sortactive.'</span></dt><dd><ul>'.$sortactive_no.'</ul></dd></dl>';
    
      $mcontent .= '</th><th width="10%">�����</th><th width="10%">������</th><th width="5%">&nbsp;</th><th width="5%">&nbsp;</th></tr><tr>';

      while ( $row = $db->get_row( ) ) {
        if (!isset($count)) { 
          $count = $row['cnt'];
          $mcontent .= '<h3 class="tit">'.$searchtitle.': <span>'.$searchtext.'</span>, ������� <span>'.declension($count, array('����������', '����������', '����������')).'</span></h3>';
          $mcontent .= '<div id="navigation_up"></div>';
        }

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
      if ($count < 10 ) {
        include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
        $mcontent .= $popular_search_artists;
        $mcontent .= $popular_search_tracks;
      }

      // ������������ ���������
      $count_d = $count / $mscfg['track_page_lim'];
      $pages = '';
      for ( $t = 0; $count_d > $t; $t ++ ) {
        $t2 = $t + 1;

        $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$t2.'-'.$textbase64.$sortpage.'.html';

        if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
        else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/search-'.$searchtype.'-'.$textbase64.$sortpage.'.html';
        $pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
        $array[$t2] = 1;
      }

      $link = $config['http_home_url'].'music/search-'.$searchtype.'-';
      $seo_mode = '-'.$textbase64.$sortpage.'.html';

      $npage = $page - 1;
      if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/search-'.$searchtype;
      $prev_page = '<a href="'.$linkdop.$seo_mode.'">�����</a> ';}
        else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">�����</a> ';}
      else $prev_page = '';
      $npage = $page + 1;
      if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">�����</a>';
      else $next_page = '';

      if ( $count > $mscfg['track_page_lim'] ) {
        $mcontent .= <<<HTML
<br /><div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
        $tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
        if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
      }
    }
  }  

  // ������� ������ ������ � ������� ��� ������� ������ (if ($page == 1) = ����� �������� �� ��������� ������ �� �������������)
  //echo $searchtext;
  $time = time();
  $searchtextins = addslashes(trim($searchtext));
  //if (($page == 1) AND (isset($_POST['newpoisk']))) $db->query( "INSERT INTO ".PREFIX."_mservice_search ( time_php, search_text, user_id, search_type, count ) VALUES ( '$time', '$searchtextins', '$member_id[user_id]', '$searchtype', '$count' )" );
  if ( $page == 1)  {
    $referer = addslashes($_SERVER['HTTP_REFERER']);
    $user_agent = addslashes($_SERVER['HTTP_USER_AGENT']);
    if ($is_logged) $member_userid = $member_id['user_id']; else $member_userid = 0;
    $db->query( "INSERT INTO ".PREFIX."_mservice_search ( time_php, count, search_text, search_type, user_id, ip, user_agent, referer ) VALUES ( '$time', '$count', '$searchtextins', '$searchtype', '$member_userid', '$_SERVER[REMOTE_ADDR]', '$user_agent', '$referer' )" );
  }
}
break;

// �������� ��� ���������� �������� �� ����� (��� �������)
case 'downloadstopalbums24hr_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_24hr_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ��� ���������� �������� �� ����� 
case 'downloadstopalbums24hr' :

  $num_downloads_top_albums_24hr = 20;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_24hr.php';
	$mcontent .= $downloads_top_albums_24hr;

break;

// �������� ��� ���������� �������� �� ������ (��� �������)
case 'downloadstopalbumsweek_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ��� ���������� �������� �� ������
case 'downloadstopalbumsweek' :

  $num_downloads_top_albums_week = 20;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
	$mcontent .= $downloads_top_albums_week;

break;

// �������� ������, ����������� ������ (��� �������)
case 'usertracks' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ������������� ������ �� ����� ����� (��� �������)
case 'monthuserplays' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ���������� �� ����� ����� (��� �������)
case 'monthuserdownloads' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ���������� �������� �� ����� ����� (��� �������)
case 'monthuserdownloads-albums' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mservice_users.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ��� ������������� �� ����� - �� ��� ������������� �� �������� �����
case 'topplay24hr' :

	include_once ENGINE_DIR.'/modules/mservice/mod_top_play_24hr.php';
	$mcontent .= $top_play_24hr;

break;

// �������� ��� ������������� �� ������ - �� ��� ������������� �� �������� �����
case 'topplayweek' :

	include_once ENGINE_DIR.'/modules/mservice/mod_top_play_week.php';
  $mcontent .= $top_play_week;

break;

// �������� ��� ���������� �� ����� (��� �������)
case 'downloadstop24hr_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ��� ���������� �� ����� - �� ��� ������������� �� �������� �����
case 'downloadstop24hr' :

  $num_downloads_top_24hr = 50;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_24hr.php';
	$mcontent .= $downloads_top_24hr;

break;

// �������� ��� ���������� �� ������ (��� �������)
case 'downloadstopweek_admin' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ��� ���������� �� ������ - �� ��� ������������� �� �������� �����
case 'downloadstopweek' :

  $num_downloads_top_week = 50;
	include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
  $mcontent .= $downloads_top_week;

break;

// �������� ���������� ��������� �������� �� ������ - �� �����������(��� �������)
case 'popularsearchalbumweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchalbum_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ���������� ��������� �������� �� ������ - �� �����������(��� �������)
case 'popularsearchartistweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchartist_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ���������� ��������� �������� �� ������ - �� �������� �����(��� �������)
case 'popularsearchtitleweek' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_searchtitle_week_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

// �������� ���������� ��������� �������� �� ������ - � ������ ����������� (��� �������)
case 'popularsearchzero' :

if ($member_id['user_group'] == 1) {
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_search_zero_admin.php';
}
else { header("location: {$config['http_home_url']}"); 
	exit; 
}
break;

//�������� ���������� ����������� 2011
case 'euro2011':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//�������� ���������� ����������� 2012
case 'euro2012':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//�������� ���������� ����������� 2013
case 'euro2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//�������� ���������� ����������� 2014
case 'euro2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//�������� ���������� ����������� 2015
case 'euro2015':
include_once ENGINE_DIR.'/modules/mservice/mservice_euros.php';
break;

//  ��� ������ ��3 �� ���������� �� ������� ��� ... (����� ������� ������ ���)
case 'mp3top':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� ������ ��3 �� ���������� �� 2014 ���
case 'mp3top2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� ������ ��3 �� ���������� �� 2013 ���
case 'mp3top2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� ������ ��3 �� ���������� �� 2012 ���
case 'mp3top2012':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� ������ ��3 �� ���������� �� 2011 ���
case 'mp3top2011':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� 100 ������ ������� ������ (KISS FM) �� 2013 ���
case 'top_club2013':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� 100 ������ ������� ������ (KISS FM) �� 2014 ���
case 'top_club2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� 20 ����������� ������ 2014 Beatport
case 'top_beatport2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� 50 ������ ����� 2014 ���� �� ������ Rolling Stone
case 'top_rollingstone2014':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

//  ��� 50 ������������
case 'top_artists':
include_once ENGINE_DIR.'/modules/mservice/mservice_mp3tops.php';
break;

// �������� ��������� ��� ������ mp3 �� �����
case 'bestmp3_24hr':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// �������� ��������� ��� ������ mp3 �� ������
case 'bestmp3_week':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// �������� ��������� ��� ������ mp3 �� �����
case 'bestmp3_month':
include_once ENGINE_DIR.'/modules/mservice/mservice_bestmp3s.php';
break;

// �������� ������� � ��� ������
case 'album' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// �������� ���� �������� �����������
case 'albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// �������� �������� �� ������
case 'genre-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// �������� ���� ������ ��� ��������
case 'genres-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

//��������� ������� �����
case 'myfavalbums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// �������� ���� �������� �����������
case 'new-mp3-albums' :
include_once ENGINE_DIR.'/modules/mservice/mservice_albums.php';
break;

// ����� �������� �������� ������ Uploadify
case 'massaddfiles':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;

// ��������� case ��� ������� $_SESSION['mservice_files'] � ������ �� ����� temp
case 'masstemp':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;
	
// �������� ��������� �������������� ����� ������ ��� ���������� �� � ���� ������
case 'massaddfiles02':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;
        
// ���������� �������� ����� ������ � ���������� �� � ���� ������
case 'domassaddfiles':
include_once ENGINE_DIR.'/modules/mservice/mservice_massaddfiles.php';
break;

// ����� ���������� ������ ����� 
case 'addfile':
include_once ENGINE_DIR.'/modules/mservice/mservice_addfile.php';
break;

// ���������� ����� ����� � ���������� ��� � ���� ������
case 'doaddfile':
include_once ENGINE_DIR.'/modules/mservice/mservice_addfile.php';
break;

// ^^)
case 'odmin':
include_once ENGINE_DIR.'/modules/mservice/mservice_odmin.php';
break;
// ����� ����� switch ( $_REQUEST['act'] )
}

$tpl->load_template( 'mservice/global.tpl' );
$tpl->set( '{mtitle}', '<div style="margin-bottom:0px">'.$mtitle.'</div>' );

if ( count( $stop ) != 0 ) {
	for ( $e = 0; $e < count( $stop ); $e ++ ) $errors .= '<li>'.$stop[$e].'</li>';
	$mcontent = <<<HTML
	<div id="errorpage"></div>
<script type="text/javascript">
showErrorPage('{$errors}');
</script>
HTML;
	include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
	$mcontent .= $popular_search_artists;
  $mcontent .= $popular_search_tracks;
  $mcontent .= $popular_search_albums;
}

$tpl->set( '{mservice}', $mcontent );
$tpl->compile( 'content' );
$db->free( );
$tpl->clear( );
?>
