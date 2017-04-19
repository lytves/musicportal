<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if (!isset($_POST['act'])) die('Hacking attempt!');

@error_reporting ( E_ALL ^ E_NOTICE ); 
@ini_set ( 'display_errors', true ); 
@ini_set ( 'html_errors', false ); 
@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE ); 
$member_id = FALSE; 
$is_logged = FALSE;

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', '../..' );

include ENGINE_DIR . '/data/config.php';
include ENGINE_DIR . '/data/mservice.php';
include ENGINE_DIR . '/classes/mysql.php';
include ENGINE_DIR . '/data/dbconfig.php';
include ENGINE_DIR . '/classes/parse.class.php';
require_once ROOT_DIR.'/language/' . $config['langs'] . '/website.lng';
include ENGINE_DIR . '/modules/functions.php';
include ENGINE_DIR . '/modules/mservice/functions.php';
include ENGINE_DIR . '/modules/sitelogin.php';
$parse = new ParseFilter( );

switch ( intval( $_POST['act'] ) ) {

case 2 :

	$subpapka = $_POST['subpapka'];
	$filename = $_POST['filename'];
	$hdd = intval( $_POST['hdd'] );
  if ( ($hdd !== 2) ) $hdd = '';
  
		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename );
        
					/* ����������� ID3 ����� ����� */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//��������� ���� �� ����� ��� ��������� �������
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = convert_unicode( $track['id3v2']['comments']['title'][0] );
					$TagData['artist'][] = convert_unicode( $track['id3v2']['comments']['artist'][0] );
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = convert_unicode( $track['id3v2']['comments']['comments'][0] );

					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = file_get_contents(ROOT_DIR.'/templates/mp3/images/audio_big.png');
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
          // write tags
          if ($tagwriter->WriteTags()) {
            $end .= 'Successfully wrote tags<br />������� ������� �������';
            if (!empty($tagwriter->warnings)) {
              $end .= 'There were some warnings:<br />'.implode('<br /><br />', $tagwriter->warnings);
            }
          } else {
            $end .= 'Failed to write tags!<br />'.implode('<br /><br />', $tagwriter->errors);
          }
					/* ����� ����������� ID3 ����� ����� */

break;

case 3 :
// ��� uploadify.js (���� ��� � case 6)
	$i = $_POST['i'];
	$numer = substr($i,12);

	if ( is_file('/home2/mp3base/temp/'.$_SESSION['mservice_files'][$numer]['file'])) { 
		unlink('/home2/mp3base/temp/'.$_SESSION['mservice_files'][$numer]['file']);
	}
	unset($_SESSION['mservice_files'][$numer]);

break;

case 4 :
// ����� ������� ������ ��� �������� ����������, ...
$title = convert_unicode( $parse->process( $_POST['title'] ) );

if ( $title == '' ) $end = '�� �� ����� �������� �����.';
else {
    if ($member_id['user_id'] == 1017)  {
      if ($title_temp = strstr($title,'(',TRUE)) $title = trim($title_temp);
      $sql = $db->query( "SELECT mid, time, title, is_demo, artist, lenght, bitrate, size FROM " . PREFIX . "_mservice WHERE title LIKE '$title%' AND approve = '1' ORDER BY time DESC LIMIT 0,5" );
    }
    else  $sql = $db->query( "SELECT mid, time, title, is_demo, artist FROM " . PREFIX . "_mservice WHERE title = '$title' AND approve = '1' ORDER BY time DESC LIMIT 0,5" );
    
    if ( $db->num_rows( $sql ) > 0 ) {
      $end = '<ul style="margin:0px;padding-left:15px;">';
      while ( $row = $db->get_row( $sql ) ) {
        if ( $config['allow_alt_url'] == 'yes' ) $link = $config['http_home_url'] . 'music/' . $row['mid'] . '-mp3-' . totranslit( $row['artist'] ) . '-' . totranslit( $row['title'] ) . '.html';
        if ( $row['is_demo'] == '1' ) $is_demo = '<span style="color:#cc0000">!!! (����������)</span>'; else $is_demo = '';
        if ($member_id['user_id'] == 1017)  $end .= '<li>'.langdate( 'd.m.Y', $row['time'] ).' :: '.$is_demo.' <a target="_blank" href="'.$link.'">'.$row['artist'].' - '.$row['title'].'</a> <span style="color:#cc0000;">('.$row['lenght'].'���, '.$row['bitrate'].' ����/�, '.formatsize( $row['size'] ).')</span></li>';
        else  $end .= '<li>'.langdate( 'd.m.Y', $row['time'] ).' :: '.$is_demo.' <a target="_blank" href="'.$link.'">'.$row['artist'].' - '.$row['title'].'</a></li>';
      }
      $end .= '</ul>';
    } else {
      if ($member_id['user_id'] == 1017)  $end = '������� ����� <span style="color:#cc0000;">"'.$title.'"</span> �� ������� � ����������� ������.';
      else $end = '������� ����� �� ������� � ����������� ������.';
    }
      
}

break;

case 5 :
////////////////////////////////////////////////////////////////////////////////////////////////////          ��������
break;

case 6 :
// ��� massaddfiles02.html � massaddfiles02.tpl
	$filename = $_POST['file'];
	$i = $_POST['i'];

	if ( is_file ('/home2/mp3base/temp/'.$filename)) { 
		@unlink ('/home2/mp3base/temp/'.$filename);
	}
	unset($_SESSION['mservice_files'][$i]);

break;

case 7 :
//################# ���������� � ��������� mp3 - �������� case 'view'
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
  }

	if (!in_array($mid, $list_fav_mid)) 	{
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrack( '{$mid}' ); return false;">mp3</a>
</div>
HTML;
	}
} else $end .= '<div class="unit-rating_fav"></div>';
break;

case 8 :
//################# �������� �� ��������� mp3 - �������� case 'view'
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
	$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrack( '{$mid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="unit-rating_fav"></div>';

break;

case 9 :
//################# ���������� � ��������� mp3 - �������� � �����������:
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
}

	if (in_array($mid, $list_fav_mid)) 	{
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackList( {$mid} ); return false;">mp3</a>
</div>
HTML;
	} else {
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackList( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 10 :
//################# �������� �� ��������� mp3 - �������� � �����������
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrackList( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 11 :
//################# ���������� � ��������� mp3 - �������� � TOP24hr
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (week, month)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
}

	if (in_array($mid, $list_fav_mid)) 	{
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
</div>
HTML;
	} else {
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 12 :
//################# �������� �� ��������� mp3 - �������� � TOP24hr
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (week, month)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 13 :
//################# ���������� � ��������� mp3 - �������� � TOPweek
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (24hr, month)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
}

	if (in_array($mid, $list_fav_mid)) 	{
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
</div>
HTML;
	} else {
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 14 :
//################# �������� �� ��������� mp3 - �������� � TOPweek
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (24hr, month)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 15 :
//################# ���������� � ��������� mp3 - �������� � TOPmonth
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (24hr, week)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
}

	if (in_array($mid, $list_fav_mid)) 	{
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
</div>
HTML;
	} else {
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 16 :
//################# �������� �� ��������� mp3 - �������� � TOPmonth
//   +  ��� � JS-����� ��� �������� � ��� ��������� ����������� ���������� (24hr, week)
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 17 :
//################# ���������� � ��������� mp3 - �������� � DownloadsTop
//   +  ��� � JS-����� ��� �������� � ��� ����� �� ������ ���� �� ���� �� ���� ��������
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	
	foreach ($list_fav as $value) {
	$list_fav_mid[] = substr($value,0,strpos($value,"-"));
	$list_fav_time[] = substr($value,strpos($value,"-")+1);
}

	if (in_array($mid, $list_fav_mid)) 	{
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
</div>
HTML;
	} else {
		$list_fav[] = $mid.'-'.time();
		$favorites_mservice = implode( ",", $list_fav );
		if( $member_id['favorites_mservice'] == "" ) $favorites_mservice = $mid.'-'.time();
		$member_id['favorites_mservice'] = $favorites_mservice;
		$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$favorites_mservice' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="������� ���� �� ��������� mp3" onclick="DelFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 18 :
//################# �������� �� ��������� mp3 - �������� � DownloadsTop
//   +  ��� � JS-����� ��� �������� � ��� ����� �� ������ ���� �� ���� �� ���� ��������
$mid = intval( $_POST['mid'] );
if( ! $mid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav = explode( ",", $member_id['favorites_mservice'] );
	$i = 0;
	foreach ($list_fav as $value) {
	$list_fav_mid[$i] = substr($value,0,strpos($value,"-"));
	if ($list_fav_mid[$i] == $mid) unset ($list_fav[$i]);
	$i++;
	}
	if ( count( $list_fav ) ) $member_id['favorites_mservice'] = implode( ",", $list_fav );
	else $member_id['favorites_mservice'] = "";
	
	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_mservice='$member_id[favorites_mservice]' where user_id = '$member_id[user_id]'" );
		$end .= <<<HTML
<div class="unit-rating_fav">
<a href="#" title="�������� ���� � ��������� mp3" onclick="AddFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 19 :
// ����� ���� ��� ����������� ����
$lenght = intval( $_POST['lenght'] );
$beats = intval( $_POST['beats'] );
$sizetemp = formatsize( intval($_POST['sizetemp']));

$tempsec = $lenght - (floor($lenght/60)*60);
if ( $tempsec < 10 ) $tempsec = '0'.$tempsec;

$end = floor($lenght/60).':'.$tempsec.' ���; '.$beats.' ����/�; '.$sizetemp;

break;

case 20 :
//################# ���������� � ������� ����������� - �������� �����: 
$transartist = totranslit($_POST['transartist']);
if( ! $transartist ) die( "Hacking attempt!" );

if( $is_logged ) {
	if( $member_id['favorites_artists_mservice'] == "" ) {
    $member_id['favorites_artists_mservice'] = $transartist;
   	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
  }	else {
      $list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
    
      if ( !in_array($transartist, $list_fav_art)) {
        $list_fav_art[] = $transartist;
        $member_id['favorites_artists_mservice'] = implode( ",", $list_fav_art );
        $db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
      }
  }
	$end .= <<<HTML
<div class="fav_art">
<div class="infav"></div>
<a href="#" title="������� �� ������� ������������" onclick="DelFavArtView( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_art"></div>';
  
break;

case 21 :
//################# �������� �� ������� ������������ - �������� �����
$transartist = totranslit($_POST['transartist']);
if( ! $transartist ) die( "Hacking attempt!" );

  if( $is_logged ) {

    $list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
    if (in_array($transartist, $list_fav_art)) {
      $list_fav_art = array_diff($list_fav_art, array($transartist));
      if ( count( $list_fav_art ) ) $member_id['favorites_artists_mservice'] = implode( ",", $list_fav_art );
      else $member_id['favorites_artists_mservice'] = "";
	    $db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
    }
      $end .= <<<HTML
<div class="fav_art">
<a href="#" title="�������� � ������� �����������" onclick="AddFavArtView( '{$transartist}','$is_logged' ); return false;">mp3</a>
</div>
HTML;

  } else $end .= '<div class="fav_art"></div>';
  
break;

case 22 :
//################# ���������� � ������� �����������:
$transartist = totranslit($_POST['transartist']);
if( ! $transartist ) die( "Hacking attempt!" );

if( $is_logged ) {
	if( $member_id['favorites_artists_mservice'] == "" ) {
    $member_id['favorites_artists_mservice'] = $transartist;
   	$db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
  }	else {
      $list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
    
      if ( !in_array($transartist, $list_fav_art)) {
        $list_fav_art[] = $transartist;
        $member_id['favorites_artists_mservice'] = implode( ",", $list_fav_art );
        $db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
      }
  }
	$end .= <<<HTML
<div class="fav_art">
<div class="infav"></div>
<a href="#" title="������� �� ������� ������������" onclick="DelFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_art"></div>';
  
break;

case 23 :
//################# �������� �� ������� ������������
$transartist = totranslit($_POST['transartist']);
if( ! $transartist ) die( "Hacking attempt!" );

  if( $is_logged ) {

    $list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
    if (in_array($transartist, $list_fav_art)) {
      $list_fav_art = array_diff($list_fav_art, array($transartist));
      if ( count( $list_fav_art ) ) $member_id['favorites_artists_mservice'] = implode( ",", $list_fav_art );
      else $member_id['favorites_artists_mservice'] = "";
	    $db->query( "UPDATE " . USERPREFIX . "_users set favorites_artists_mservice = '".$member_id['favorites_artists_mservice']."' where user_id = '".$member_id['user_id']."'" );
    }
      $end .= <<<HTML
<div class="fav_art">
<a href="#" title="�������� � ������� �����������" onclick="AddFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

  } else $end .= '<div class="fav_art"></div>';
break;

case 24 :

	$filename = $_POST['filename'];
	
		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home2/mp3base/temp/'.$filename );
        
					/* ����������� ID3 ����� ����� */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/temp/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//��������� ���� �� ����� ��� ��������� �������
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);

					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = convert_unicode( $track['id3v2']['comments']['title'][0] );
					$TagData['artist'][] = convert_unicode( $track['id3v2']['comments']['artist'][0] );
					$TagData['comment'][] = convert_unicode( $track['id3v2']['comments']['comments'][0] );
					
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = file_get_contents(ROOT_DIR.'/templates/mp3/images/audio_big.png');
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
          // write tags
          if ($tagwriter->WriteTags()) {
            $end .= '������� ����� �������!';
            if (!empty($tagwriter->warnings)) {
              //$end .= 'There were some warnings:<br />'.implode('<br /><br />', $tagwriter->warnings);
              $end .= '���������� ������...';

            }
          } else {
            //$end .= 'Failed to write tags!<br />'.implode('<br /><br />', $tagwriter->errors);
            $end .= '���������� ��������� ����...';
          }
					/* ����� ����������� ID3 ����� ����� */

break;

case 25 :
  $end = dle_cache2( "best_down24hr2", $config['skin'] );

break;

case 26 :

	$image_cover = $_POST['image_cover'];
	$aid = intval($_POST['aid']);
	if ( is_file (ROOT_DIR.'/uploads/albums/'.$image_cover)) {
    if (@unlink (ROOT_DIR.'/uploads/albums/'.$image_cover)) {
      $end = '������� ������� �������';
      $db->query( "UPDATE ".PREFIX."_mservice_albums SET image_cover = '', image_cover_crc32 = '' WHERE aid = '".$aid."'" );
     }
  }
break;

case 27 :

  $aid_number = intval($_POST['aid']);

  $image = $_FILES['image']['tmp_name'];
  $image_name = basename($_FILES['image']['name']);
  $image_size = $_FILES['image']['size'];
  $img_name_arr = explode( ".", $image_name );
  $type = end( $img_name_arr );
  
  if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) ) {
		
			if( $image_size < 3000000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR.'/classes/thumb.class.php';
					
					$time = time( );
          $image_name_md5 = md5($time + mt_rand( 0, 100 )).".".$type;

					$res = @move_uploaded_file( $image, ROOT_DIR."/uploads/albums/".$image_name_md5 );
					
					if( $res ) {
						
						@chmod( ROOT_DIR."/uploads/albums/".$image_name_md5, 0666 );
						$thumb = new thumbnail( ROOT_DIR."/uploads/albums/".$image_name_md5 );
						
						//150 - ������ �������� � ��������, �������� 150�150
						if( $thumb->size_auto( 150 ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR."/uploads/albums/album_".$aid_number.".".$type );
						} else {
							@rename( ROOT_DIR."/uploads/albums/".$image_name_md5, ROOT_DIR."/uploads/albums/album_".$aid_number.".".$type );
						}
						
						@chmod( ROOT_DIR."/uploads/albums/album_".$aid_number.".".$type, 0666 );
						$end = "album_".$aid_number.".".$type;
						
						$image_cover_crc32 = hash_file('crc32b', ROOT_DIR."/uploads/albums/album_".$aid_number.".".$type);
						
            $db->query( "UPDATE ".PREFIX."_mservice_albums SET image_cover = '$end', image_cover_crc32 = '$image_cover_crc32' WHERE aid = '$aid_number'" );

					} 
				} 
			}
		
		@unlink( ROOT_DIR."/uploads/albums/".$image_name_md5 );
   }
break;

case 28 :

	$mid = intval($_POST['mid']);
	$db->query( "UPDATE ".PREFIX."_mservice SET album = '0' WHERE mid = '$mid'" );
	
break;

case 29 :

	$mid = intval($_POST['mid']);
	$aid = intval($_POST['aid']);
	$db->query( "UPDATE ".PREFIX."_mservice SET album = '$aid' WHERE mid = '$mid'" );
	
break;

case 30 :
//################# ���������� � ��������� ������� - �������� case 'album'
$aid = intval( $_POST['aid'] );
if( ! $aid ) die( "Hacking attempt!" );

if( $is_logged ) {
	if ( $member_id['favorites_albums_mservice'] == "" ) {
    $member_id['favorites_albums_mservice'] = $aid;
    $db->query( "UPDATE ".USERPREFIX."_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
  }
  else {
    $list_fav_alb = explode( ",", $member_id['favorites_albums_mservice'] );
    if ( !in_array($aid, $list_fav_alb)) {
      $list_fav_alb[] = $aid;
      $member_id['favorites_albums_mservice'] = implode( ",", $list_fav_alb );
      $db->query( "UPDATE ".USERPREFIX."_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
    }
  }

	$end .= <<<HTML
<div class="fav_alb">
<div class="infav"></div>
<a href="#" title="������� ������ �� ��������� ��������" onclick="DelFavAlbum( '{$aid}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_alb"></div>';

break;

case 31 :
//################# �������� �� ��������� ������� - �������� case 'album'
$aid = intval( $_POST['aid'] );
if( ! $aid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav_alb = explode( ",", $member_id['favorites_albums_mservice'] );
    if (in_array($aid, $list_fav_alb)) {
      $list_fav_alb = array_diff($list_fav_alb, array($aid));
      if ( count( $list_fav_alb ) ) $member_id['favorites_albums_mservice'] = implode( ",", $list_fav_alb );
      else $member_id['favorites_albums_mservice'] = "";
      
      $db->query( "UPDATE " . USERPREFIX . "_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
      $end .= <<<HTML
<div class="fav_alb">
<a href="#" title="�������� ������ � ��������� �������" onclick="AddFavAlbum( '{$aid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
} else $end .= '<div class="fav_alb"></div>';
} else $end .= '<div class="fav_alb"></div>';

break;

case 32 :
//################# ���������� � ��������� ������� �� ���������-������� ��������
$aid = intval( $_POST['aid'] );
if( ! $aid ) die( "Hacking attempt!" );

if( $is_logged ) {

  if ( $member_id['favorites_albums_mservice'] == "" ) {
    $member_id['favorites_albums_mservice'] = $aid;
    $db->query( "UPDATE ".USERPREFIX."_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
  }
  else {
    $list_fav_alb = explode( ",", $member_id['favorites_albums_mservice'] );
    if ( !in_array($aid, $list_fav_alb)) {
      $list_fav_alb[] = $aid;
      $member_id['favorites_albums_mservice'] = implode( ",", $list_fav_alb );
      $db->query( "UPDATE ".USERPREFIX."_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
    }
  }
		$end .= <<<HTML
<div class="fav_alb">
<div class="infav"></div>
<a href="#" title="������� ������ �� ��������� ��������" onclick="DelFavAlbumList( '{$aid}' ); return false;">mp3</a>
</div>
HTML;

} else 	$end .= '<div class="fav_alb"></div>';

break;

case 33 :
//################# �������� �� ��������� ������� �� ���������-������� ��������
$aid = intval( $_POST['aid'] );
if( ! $aid ) die( "Hacking attempt!" );

if( $is_logged ) {

	$list_fav_alb = explode( ",", $member_id['favorites_albums_mservice'] );
    if (in_array($aid, $list_fav_alb)) {
      $list_fav_alb = array_diff($list_fav_alb, array($aid));
      if ( count( $list_fav_alb ) ) $member_id['favorites_albums_mservice'] = implode( ",", $list_fav_alb );
      else $member_id['favorites_albums_mservice'] = "";
      
      $db->query( "UPDATE ".USERPREFIX."_users set favorites_albums_mservice = '".$member_id['favorites_albums_mservice']."' where user_id = '".$member_id['user_id']."'" );
      $end .= <<<HTML
<div class="fav_alb"><a href="#" title="�������� ������ � ��������� �������" onclick="AddFavAlbumList( '{$aid}' ); return false;">mp3</a></div>
HTML;
} else $end .= '<div class="fav_alb"></div>';
} else $end .= '<div class="fav_alb"></div>';

break;

case 34 :
//################# ��������� �������� ������� � ������-������� � �������
$aid = intval( $_POST['aid'] );
$inout = intval( $_POST['inout'] );
if( ! $aid ) die( "Hacking attempt!" );
if( ! $inout ) die( "Hacking attempt!" );

if ($inout == 1) {
  $db->query( "UPDATE ".USERPREFIX."_mservice_albums set is_collection = '1', collection = '' where aid = '".$aid."'" );
  $end .= <<<HTML
<table width="100%" width="770"><tr><td width="305" align="center"><span style="color:#F49804;">������-�������!</span></td>

<td width="265" align="center">�������� � ���� ������-������� ���� �</td>
<td width="195">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input type="hidden" name='action' value='11' />
<input type="hidden" name='aid' value='{$aid}' />
<input type="text" value="" name="mid" class="edit" size="10" />
<input type="submit" value="  ��������  " class="buttons" />
</form>
</td></tr></table>
HTML;

} elseif ($inout == 2)  {
  $db->query( "UPDATE ".USERPREFIX."_mservice_albums set is_collection = '0', collection = '' where aid = '".$aid."'" );
  $end .= '������� ������.';
} else $end .= '��������� ������!';

break;

case 35 :
//################# �������� �����-������������ �� �������-��������
$mid = intval($_POST['mid']);
$aid = intval($_POST['aid']);

$row = $db->super_query( "SELECT is_collection, collection FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' LIMIT 1" );
if ($row['is_collection'] AND $row['collection']) {
  $list_collection = explode( ",", $row['collection'] );
  if (in_array($mid, $list_collection)) {
    $list_collection = array_diff($list_collection, array($mid));
    if ( count( $list_collection ) ) $to_list_collection = implode( ",", $list_collection );
    else $to_list_collection = "";
    $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$to_list_collection."' where aid = '".$aid."'" );
}
}
	
break;

case 36 :
//################# ��������� �����-������������ � ������-�������
$mid = intval($_POST['mid']);
$aid = intval($_POST['aid']);

$row = $db->super_query( "SELECT is_collection, collection FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' LIMIT 1" );
if ($row['is_collection']) {
  if ($row['collection'] == '') $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$mid."' where aid = '".$aid."'" );
  else {
    $list_collection = explode( ",", $row['collection'] );
    if ( !in_array($mid, $list_collection)) {
      $list_collection[] = $mid;
      $to_list_collection = implode( ",", $list_collection );
      $db->query( "UPDATE ".USERPREFIX."_mservice_albums set collection = '".$to_list_collection."' where aid = '".$aid."'" );
    }
  }
}
break;

case 37 :
//################# ������ ����� � ���������
$mid = intval($_POST['mid']);

if ($db->query( "UPDATE ".PREFIX."_mservice SET approve = '1' WHERE mid = '".$mid."'" )) $end = "���� ���� � ���������! <a href=\"#\" title=\"��������� ���� �� ���������!\" onclick=\"addModer( ".$mid." ); return false;\">��������� ���� �� ���������!</a>";
else $end = "<span style=\"color:#cc0000;\">������! ���� �� ���� � ���������!</span>";
break;

case 38 :
//################# �������� ����� �� ���������
$mid = intval($_POST['mid']);

if ($db->query( "UPDATE ".PREFIX."_mservice SET approve = '0' WHERE mid = '".$mid."'" )) $end = "<span style=\"color:red; font-weight:bold;\">���� �� ����������� �� ����� � ��������� �� ���������!</span> <a href=\"#\" title=\"����� ���� � ���������!\" onclick=\"removeModer( ".$mid." ); return false;\">����� ���� � ���������!</a>";
else $end = "<span style=\"color:#cc0000;\">������! ���� �� ��������� �� ���������!</span>";
break;

case 39 :
//################# ����������� ������� ��� ������
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET is_notfullalbum = '0' WHERE aid = '".$aid."'" )) $end = "������ ��������� ��� ������!<br /><a style=\"background:F9A4A4#;\" href=\"#\" title=\"���������� ������ ��� ��������!\" onclick=\"isNotFullAlbum( ".$aid." ); return false;\">���������� ������ ��� ��������!</a>";
else $end = "<span style=\"color:#cc0000;\">������! ������ �� ��������� ��� ������!";
break;

case 40 :
//################# ����������� ������� ��� ��������
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET is_notfullalbum = '1' WHERE aid = '".$aid."'" )) $end = "������ ��������� ��� ��������!<br /><a style=\"background:#B2DD54;\" href=\"#\" title=\"���������� ������ ��� ������!\" onclick=\"isFullAlbum( ".$aid." ); return false;\">���������� ������ ��� ������!</a>";
else $end = "<span style=\"color:#cc0000;\">������! ������ �� ��������� ��� ��������!";
break;

case 41 :
//################# ������ ������� � ���������
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET approve = '1' WHERE aid = '".$aid."'" )) $end = "������ ���� � ���������! <a href=\"#\" title=\"��������� ������ �� ���������!\" onclick=\"addModerAlbum( ".$aid." ); return false;\">��������� ������ �� ���������!</a><hr color=\"#999898\" />";
else $end = "<span style=\"color:#cc0000;\">������! ������ �� ���� � ���������!</span>";
break;

case 42 :
//################# �������� ������� �� ���������
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET approve = '0' WHERE aid = '".$aid."'" )) $end = "������ �� ����������� �� ����� � ��������� �� ���������! <a href=\"#\" title=\"����� ������ � ���������!\" onclick=\"removeModerAlbum( ".$aid." ); return false;\">����� ������ � ���������!</a><hr color=\"#999898\" />";
else $end = "<span style=\"color:#cc0000;\">������! ������ �� ��������� �� ���������!</span>";
break;

case 43 :
//################# ������ ����� ������� � ����
$aid = intval($_POST['aid']);
$genreid = intval($_POST['genreid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET genre = '".$genreid."' WHERE aid = '".$aid."'" )) $end = "���� ����� �������! <span>������������ � ���� AJAX'��, ������������� ������ �� ����!</span>";
else $end = "<span style=\"color:#cc0000;\">������! ���� �� ����� �������!</span>";
break;

case 44 :
// ����� �������� ����������� � ������� ��� ���������� �������
$artist = trim(convert_unicode( $parse->process( $_POST['artist'] ) ));

if ( $artist == '' ) $end = '�� �� ����� ��� �����������.';
else {
    $sql = $db->query( "SELECT aid, year, album, artist, genre, is_notfullalbum FROM ".PREFIX."_mservice_albums WHERE artist = '$artist' ORDER BY year DESC, time DESC" );
    if ( $db->num_rows( $sql ) > 0 ) {
      $end = '<ul style="margin:0px;padding-left:15px;">';
      while ( $row = $db->get_row( $sql ) ) {
        if ( $row['genre'] == '1') $alb_genre = 'Pop';
        elseif ( $row['genre'] == '2') $alb_genre = 'Club';
        elseif ( $row['genre'] == '3') $alb_genre = 'Rap';
        elseif ( $row['genre'] == '4') $alb_genre = 'Rock';
        elseif ( $row['genre'] == '5') $alb_genre = '������';
        elseif ( $row['genre'] == '6') $alb_genre = 'OST';
        elseif ( $row['genre'] == '7') $alb_genre = 'Metal';
        elseif ( $row['genre'] == '8') $alb_genre = 'Country';
        elseif ( $row['genre'] == '9') $alb_genre = 'Classical';
        elseif ( $row['genre'] == '10') $alb_genre = 'Punk';
        elseif ( $row['genre'] == '11') $alb_genre = 'Soul/R&B';
        elseif ( $row['genre'] == '12') $alb_genre = 'Jazz';
        elseif ( $row['genre'] == '13') $alb_genre = 'Electronic';
        elseif ( $row['genre'] == '14') $alb_genre = 'Indie';
        else $alb_genre = '';
        
        if ( $row['is_notfullalbum'] == '1') $fullalbum = ' <span style="color:#FF1493;">(�������� ������)</span>';
        else $fullalbum = '';
        $link = $config['admin_path'].'?mod=mservice&act=albumedit&aid='.$row['aid'];
        $end .= '<li><a href="'.$link.'" target="_blank">'.$row['artist'].' - '.$row['album'].' ('.$row['year'].')</a> #<span class="genre'.$row['genre'].'">'.$alb_genre.'</span>'.$fullalbum.'</li>';
      }
      $end .= '</ul>';
    } else $end = '��� �������� ����������� \''.$artist.'\' � ����������� ������.';
}

break;

case 45 :
//################# ������������� ��������� ������ 1 ����������� �� 1 ����� �������� � ������ VIP ����������
$uid = intval($_POST['uid']);
$uid = ((($uid+11)/7)-1203);//���� ��� ����������� uid
if (($member_id['user_id'] == $uid) && ($member_id['use_demo_vip_group']) != 1)  {
  $time_limit = time() + 60*60*24;
  if ($db->query( "UPDATE ".PREFIX."_users SET use_demo_vip_group = '1', user_group = '7', time_limit = ".$time_limit." WHERE user_id = '".$uid."'" )) {
    $end = "�� ������������ ���� ����������� � �� 1 ����� ���������� � ������ <span style=\"color:#ff00b2;\">VIP ����������</span>.";
    include_once ENGINE_DIR . '/classes/mail.class.php';
    $mailsend = new dle_mail( $config );
    $mailsend->from = 'robot@domain.com';
    $maila = langdate( 'D d F Y - H:i', time() ).' ���� <span style="color:#3367ab; font-weight:bold;">'.$member_id['name'].'</span> �������� � ������ <span style="color:#ff00b2;">VIP ����������</span> �� �������� ������ 1 �����.';
		$mailsend->html_mail = true;
		$mailsend->send( "odmin@domain.com", "����� ���� � ������ VIP ���������� �� ����� domain.com", "$maila" );
  }
  else $end = "���������� ������, ���������� ��������� �������� ������� �����!";
} else $end = "�������� ���������!";
break;

case 46 :
//################# �������� ������� ����������� � �������
	$image_cover = $_POST['image_cover'];
	$artid = intval($_POST['artid']);
	if ( is_file (ROOT_DIR.'/uploads/artists/'.$image_cover)) {
    if (@unlink (ROOT_DIR.'/uploads/artists/'.$image_cover)) {
      $end = '������� �������';
      $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover = '' WHERE artid = '".$artid."'" );
     }
  }
break;

case 47 :
//################# ������ ����� ����������� � ���� � �������
$artid = intval($_POST['artid']);
$genreid = intval($_POST['genreid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_artists SET genre = '".$genreid."' WHERE artid = '".$artid."'" )) $end = "���� ����� �����������! <span>������������ � ���� AJAX'��, ������������� ������ �� ����!</span>";
else $end = "<span style=\"color:#cc0000;\">������! ���� �� ����� �������!</span>";
break;

case 48 :
//################# ���������� ������� ����������� � �������
  $artid_number = intval($_POST['artid']);

  $image = $_FILES['image']['tmp_name'];
  $image_name = basename($_FILES['image']['name']);
  $image_size = $_FILES['image']['size'];
  $img_name_arr = explode( ".", $image_name );
  $type = end( $img_name_arr );
  
  if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) ) {
		
			if( $image_size < 3000000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR.'/classes/thumb.class.php';
					
					$time = time( );
          $image_name_md5 = md5($time + mt_rand( 0, 100 )).".".$type;

					$res = @move_uploaded_file( $image, ROOT_DIR."/uploads/artists/".$image_name_md5 );
					
					if( $res ) {
						
						@chmod( ROOT_DIR."/uploads/artists/".$image_name_md5, 0666 );
						$thumb = new thumbnail( ROOT_DIR."/uploads/artists/".$image_name_md5 );
						
						//150 - ������ �������� � ��������, �������� 150�150
						if( $thumb->size_auto( 700 ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR."/uploads/artists/artist_".$artid_number.".".$type  );
						} else {
							@rename( ROOT_DIR."/uploads/artists/".$image_name_md5, ROOT_DIR."/uploads/artists/artist_".$artid_number.".".$type );
						}
						$end = "artist_".$artid_number.".".$type;
						
            $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover = '$end' WHERE artid = '$artid_number'" );
            
					} 
				} 
			} 
		
		@unlink( ROOT_DIR."/uploads/artists/".$image_name_md5 );
   }
break;

case 49 :
//################# �������� ������� ����������� 150x150 � �������
	$image_cover150 = $_POST['image_cover150'];
	$artid = intval($_POST['artid']);
	if ( is_file (ROOT_DIR.'/uploads/artists/'.$image_cover150)) {
    if (unlink (ROOT_DIR.'/uploads/artists/'.$image_cover150)) {
      $end = '������� �������';
      $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover150 = '' WHERE artid = '".$artid."'" );
     }
  }
break;

case 50 :
//################# ���������� ������� ����������� 150�150 � �������
  $artid_number = intval($_POST['artid']);

  $image = $_FILES['image']['tmp_name'];
  $image_name = basename($_FILES['image']['name']);
  $image_size = $_FILES['image']['size'];
  $img_name_arr = explode( ".", $image_name );
  $type = end( $img_name_arr );
  
  if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) ) {
		
			if( $image_size < 3000000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR.'/classes/thumb.class.php';
					
					$time = time( );
          $image_name_md5 = md5($time + mt_rand( 0, 100 )).".".$type;

					$res = @move_uploaded_file( $image, ROOT_DIR."/uploads/artists/".$image_name_md5 );
					
					if( $res ) {
						
						@chmod( ROOT_DIR."/uploads/artists/".$image_name_md5, 0666 );
						$thumb = new thumbnail( ROOT_DIR."/uploads/artists/".$image_name_md5 );
						
						//150 - ������ �������� � ��������, �������� 150�150
						if( $thumb->size_auto( '150x150' ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR."/uploads/artists/artist_".$artid_number."_cover150.".$type  );
						} else {
							@rename( ROOT_DIR."/uploads/artists/".$image_name_md5, ROOT_DIR."/uploads/artists/artist_".$artid_number."_cover150.".$type );
						}
						$end = "artist_".$artid_number."_cover150.".$type;
						
            $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover150 = '$end' WHERE artid = '$artid_number'" );
            
					} 
				} 
			} 
		
		@unlink( ROOT_DIR."/uploads/artists/".$image_name_md5 );
   }
break;

}
//������ ��� ������ Content-type: text/css; - ��� ����� �����������
@header( "Content-type: text/html; charset=windows-1251" );
echo $end;
?>
