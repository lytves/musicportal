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
        
					/* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home'.$hdd.'/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
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
            $end .= 'Successfully wrote tags<br />Спамная обложка удалена';
            if (!empty($tagwriter->warnings)) {
              $end .= 'There were some warnings:<br />'.implode('<br /><br />', $tagwriter->warnings);
            }
          } else {
            $end .= 'Failed to write tags!<br />'.implode('<br /><br />', $tagwriter->errors);
          }
					/* Конец Исправление ID3 тегов файлу */

break;

case 3 :
// для uploadify.js (тоже что и case 6)
	$i = $_POST['i'];
	$numer = substr($i,12);

	if ( is_file('/home2/mp3base/temp/'.$_SESSION['mservice_files'][$numer]['file'])) { 
		unlink('/home2/mp3base/temp/'.$_SESSION['mservice_files'][$numer]['file']);
	}
	unset($_SESSION['mservice_files'][$numer]);

break;

case 4 :
// Поиск похожих треков при массовом добавлении, ...
$title = convert_unicode( $parse->process( $_POST['title'] ) );

if ( $title == '' ) $end = 'Вы не ввели название трека.';
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
        if ( $row['is_demo'] == '1' ) $is_demo = '<span style="color:#cc0000">!!! (демозапись)</span>'; else $is_demo = '';
        if ($member_id['user_id'] == 1017)  $end .= '<li>'.langdate( 'd.m.Y', $row['time'] ).' :: '.$is_demo.' <a target="_blank" href="'.$link.'">'.$row['artist'].' - '.$row['title'].'</a> <span style="color:#cc0000;">('.$row['lenght'].'мин, '.$row['bitrate'].' Кбит/с, '.formatsize( $row['size'] ).')</span></li>';
        else  $end .= '<li>'.langdate( 'd.m.Y', $row['time'] ).' :: '.$is_demo.' <a target="_blank" href="'.$link.'">'.$row['artist'].' - '.$row['title'].'</a></li>';
      }
      $end .= '</ul>';
    } else {
      if ($member_id['user_id'] == 1017)  $end = 'Похожие треки <span style="color:#cc0000;">"'.$title.'"</span> не найдены в музыкальном архиве.';
      else $end = 'Похожие треки не найдены в музыкальном архиве.';
    }
      
}

break;

case 5 :
////////////////////////////////////////////////////////////////////////////////////////////////////          СВОБОДНО
break;

case 6 :
// для massaddfiles02.html в massaddfiles02.tpl
	$filename = $_POST['file'];
	$i = $_POST['i'];

	if ( is_file ('/home2/mp3base/temp/'.$filename)) { 
		@unlink ('/home2/mp3base/temp/'.$filename);
	}
	unset($_SESSION['mservice_files'][$i]);

break;

case 7 :
//################# добавление в избранные mp3 - страница case 'view'
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrack( '{$mid}' ); return false;">mp3</a>
</div>
HTML;
	}
} else $end .= '<div class="unit-rating_fav"></div>';
break;

case 8 :
//################# удаление из избранных mp3 - страница case 'view'
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrack( '{$mid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="unit-rating_fav"></div>';

break;

case 9 :
//################# добавление в избранные mp3 - страницы с плейлистами:
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackList( {$mid} ); return false;">mp3</a>
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackList( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 10 :
//################# удаление из избранных mp3 - страницы с плейлистами
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackList( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 11 :
//################# добавление в избранные mp3 - страница с TOP24hr
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (week, month)
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 12 :
//################# удаление из избранных mp3 - страница с TOP24hr
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (week, month)
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTop24hr( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 13 :
//################# добавление в избранные mp3 - страница с TOPweek
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (24hr, month)
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 14 :
//################# удаление из избранных mp3 - страница с TOPweek
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (24hr, month)
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopWeek( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 15 :
//################# добавление в избранные mp3 - страница с TOPmonth
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (24hr, week)
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 16 :
//################# удаление из избранных mp3 - страница с TOPmonth
//   +  ещё в JS-файле ещё меняется и для остальных родственных подмодулей (24hr, week)
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopMonth( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 17 :
//################# добавление в избранные mp3 - страница с DownloadsTop
//   +  ещё в JS-файле ещё меняется и для трека из списка если он есть на этой странице
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
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
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
</div>
HTML;
	}
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 18 :
//################# удаление из избранных mp3 - страница с DownloadsTop
//   +  ещё в JS-файле ещё меняется и для трека из списка если он есть на этой странице
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
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListDownloadsTop( {$mid} ); return false;">mp3</a>
</div>
HTML;
} else 	$end .= '<div class="unit-rating_fav"></div>';

break;

case 19 :
// Вывод инфы про загружаемый файл
$lenght = intval( $_POST['lenght'] );
$beats = intval( $_POST['beats'] );
$sizetemp = formatsize( intval($_POST['sizetemp']));

$tempsec = $lenght - (floor($lenght/60)*60);
if ( $tempsec < 10 ) $tempsec = '0'.$tempsec;

$end = floor($lenght/60).':'.$tempsec.' мин; '.$beats.' Кбит/с; '.$sizetemp;

break;

case 20 :
//################# добавление в любимые исполнители - страница трека: 
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
<a href="#" title="Удалить из любимых исполнителей" onclick="DelFavArtView( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_art"></div>';
  
break;

case 21 :
//################# удаление из любимых исполнителей - страница трека
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
<a href="#" title="Добавить в любимые исполнители" onclick="AddFavArtView( '{$transartist}','$is_logged' ); return false;">mp3</a>
</div>
HTML;

  } else $end .= '<div class="fav_art"></div>';
  
break;

case 22 :
//################# добавление в любимые исполнители:
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
<a href="#" title="Удалить из любимых исполнителей" onclick="DelFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_art"></div>';
  
break;

case 23 :
//################# удаление из любимых исполнителей
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
<a href="#" title="Добавить в любимые исполнители" onclick="AddFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;

  } else $end .= '<div class="fav_art"></div>';
break;

case 24 :

	$filename = $_POST['filename'];
	
		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home2/mp3base/temp/'.$filename );
        
					/* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/temp/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
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
            $end .= 'Обложка трека удалена!';
            if (!empty($tagwriter->warnings)) {
              //$end .= 'There were some warnings:<br />'.implode('<br /><br />', $tagwriter->warnings);
              $end .= 'Обнаружены ошибки...';

            }
          } else {
            //$end .= 'Failed to write tags!<br />'.implode('<br /><br />', $tagwriter->errors);
            $end .= 'Невозможно исправить теги...';
          }
					/* Конец Исправление ID3 тегов файлу */

break;

case 25 :
  $end = dle_cache2( "best_down24hr2", $config['skin'] );

break;

case 26 :

	$image_cover = $_POST['image_cover'];
	$aid = intval($_POST['aid']);
	if ( is_file (ROOT_DIR.'/uploads/albums/'.$image_cover)) {
    if (@unlink (ROOT_DIR.'/uploads/albums/'.$image_cover)) {
      $end = 'Спамная обложка удалена';
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
						
						//150 - размер картинки в пикселях, означает 150х150
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
//################# добавление в избранные альбома - страница case 'album'
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
<a href="#" title="Удалить альбом из Избранных альбомов" onclick="DelFavAlbum( '{$aid}' ); return false;">mp3</a>
</div>
HTML;

} else $end .= '<div class="fav_alb"></div>';

break;

case 31 :
//################# удаление из избранных альбома - страница case 'album'
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
<a href="#" title="Добавить альбом в Избранные альбомы" onclick="AddFavAlbum( '{$aid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
} else $end .= '<div class="fav_alb"></div>';
} else $end .= '<div class="fav_alb"></div>';

break;

case 32 :
//################# добавление в избранные альбома на страницах-списках альбомов
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
<a href="#" title="Удалить альбом из Избранных альбомов" onclick="DelFavAlbumList( '{$aid}' ); return false;">mp3</a>
</div>
HTML;

} else 	$end .= '<div class="fav_alb"></div>';

break;

case 33 :
//################# удаление из избранных альбома на страницах-списках альбомов
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
<div class="fav_alb"><a href="#" title="Добавить альбом в Избранные альбомы" onclick="AddFavAlbumList( '{$aid}' ); return false;">mp3</a></div>
HTML;
} else $end .= '<div class="fav_alb"></div>';
} else $end .= '<div class="fav_alb"></div>';

break;

case 34 :
//################# переделка обычного альбома в альбом-сборник и обратно
$aid = intval( $_POST['aid'] );
$inout = intval( $_POST['inout'] );
if( ! $aid ) die( "Hacking attempt!" );
if( ! $inout ) die( "Hacking attempt!" );

if ($inout == 1) {
  $db->query( "UPDATE ".USERPREFIX."_mservice_albums set is_collection = '1', collection = '' where aid = '".$aid."'" );
  $end .= <<<HTML
<table width="100%" width="770"><tr><td width="305" align="center"><span style="color:#F49804;">Альбом-сборник!</span></td>

<td width="265" align="center">Добавить в этот альбом-сборник трек №</td>
<td width="195">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="massact" />
<input type="hidden" name='action' value='11' />
<input type="hidden" name='aid' value='{$aid}' />
<input type="text" value="" name="mid" class="edit" size="10" />
<input type="submit" value="  Добавить  " class="buttons" />
</form>
</td></tr></table>
HTML;

} elseif ($inout == 2)  {
  $db->query( "UPDATE ".USERPREFIX."_mservice_albums set is_collection = '0', collection = '' where aid = '".$aid."'" );
  $end .= 'Обычный альбом.';
} else $end .= 'Произошла ошибка!';

break;

case 35 :
//################# удаление трека-совместителя из альбома-сборника
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
//################# добаление трека-совместителя в альбом-сборник
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
//################# снятие трека с модерации
$mid = intval($_POST['mid']);

if ($db->query( "UPDATE ".PREFIX."_mservice SET approve = '1' WHERE mid = '".$mid."'" )) $end = "Трек снят с модерации! <a href=\"#\" title=\"Отправить трек на модерацию!\" onclick=\"addModer( ".$mid." ); return false;\">Отправить трек на модерацию!</a>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Трек не снят с модерации!</span>";
break;

case 38 :
//################# отправка трека на модерацию
$mid = intval($_POST['mid']);

if ($db->query( "UPDATE ".PREFIX."_mservice SET approve = '0' WHERE mid = '".$mid."'" )) $end = "<span style=\"color:red; font-weight:bold;\">Трек не опубликован на сайте и находится НА МОДЕРАЦИИ!</span> <a href=\"#\" title=\"Снять трек с модерации!\" onclick=\"removeModer( ".$mid." ); return false;\">Снять трек с модерации!</a>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Трек не отправлен на модерацию!</span>";
break;

case 39 :
//################# обозначение альбома как полный
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET is_notfullalbum = '0' WHERE aid = '".$aid."'" )) $end = "Альбом обозначен как полный!<br /><a style=\"background:F9A4A4#;\" href=\"#\" title=\"Обозначить альбом как НЕполный!\" onclick=\"isNotFullAlbum( ".$aid." ); return false;\">Обозначить альбом как НЕполный!</a>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Альбом НЕ обозначен как полный!";
break;

case 40 :
//################# обозначение альбома как неполный
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET is_notfullalbum = '1' WHERE aid = '".$aid."'" )) $end = "Альбом обозначен как НЕполный!<br /><a style=\"background:#B2DD54;\" href=\"#\" title=\"Обозначить альбом как полный!\" onclick=\"isFullAlbum( ".$aid." ); return false;\">Обозначить альбом как полный!</a>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Альбом НЕ обозначен как НЕполный!";
break;

case 41 :
//################# снятие альбома с модерации
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET approve = '1' WHERE aid = '".$aid."'" )) $end = "Альбом снят с модерации! <a href=\"#\" title=\"Отправить альбом на модерацию!\" onclick=\"addModerAlbum( ".$aid." ); return false;\">Отправить альбом на модерацию!</a><hr color=\"#999898\" />";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Альбом не снят с модерации!</span>";
break;

case 42 :
//################# отправка альбома на модерацию
$aid = intval($_POST['aid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET approve = '0' WHERE aid = '".$aid."'" )) $end = "Альбом не опубликован на сайте и находится НА МОДЕРАЦИИ! <a href=\"#\" title=\"Снять альбом с модерации!\" onclick=\"removeModerAlbum( ".$aid." ); return false;\">Снять альбом с модерации!</a><hr color=\"#999898\" />";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Альбом не отправлен на модерацию!</span>";
break;

case 43 :
//################# запись жанра альбома в базу
$aid = intval($_POST['aid']);
$genreid = intval($_POST['genreid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_albums SET genre = '".$genreid."' WHERE aid = '".$aid."'" )) $end = "Жанр задан альбому! <span>Записывается в базу AJAX'ом, редактировать альбом не надо!</span>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Жанр НЕ задан альбому!</span>";
break;

case 44 :
// Поиск альбомов исполнителя в админке при добавлении альбома
$artist = trim(convert_unicode( $parse->process( $_POST['artist'] ) ));

if ( $artist == '' ) $end = 'Вы не ввели имя исполнителя.';
else {
    $sql = $db->query( "SELECT aid, year, album, artist, genre, is_notfullalbum FROM ".PREFIX."_mservice_albums WHERE artist = '$artist' ORDER BY year DESC, time DESC" );
    if ( $db->num_rows( $sql ) > 0 ) {
      $end = '<ul style="margin:0px;padding-left:15px;">';
      while ( $row = $db->get_row( $sql ) ) {
        if ( $row['genre'] == '1') $alb_genre = 'Pop';
        elseif ( $row['genre'] == '2') $alb_genre = 'Club';
        elseif ( $row['genre'] == '3') $alb_genre = 'Rap';
        elseif ( $row['genre'] == '4') $alb_genre = 'Rock';
        elseif ( $row['genre'] == '5') $alb_genre = 'Шансон';
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
        
        if ( $row['is_notfullalbum'] == '1') $fullalbum = ' <span style="color:#FF1493;">(неполный альбом)</span>';
        else $fullalbum = '';
        $link = $config['admin_path'].'?mod=mservice&act=albumedit&aid='.$row['aid'];
        $end .= '<li><a href="'.$link.'" target="_blank">'.$row['artist'].' - '.$row['album'].' ('.$row['year'].')</a> #<span class="genre'.$row['genre'].'">'.$alb_genre.'</span>'.$fullalbum.'</li>';
      }
      $end .= '</ul>';
    } else $end = 'Нет альбомов исполнителя \''.$artist.'\' в музыкальном архиве.';
}

break;

case 45 :
//################# Использование тестового режима 1 возможность на 1 сутки перехода в группу VIP Посетители
$uid = intval($_POST['uid']);
$uid = ((($uid+11)/7)-1203);//соль для расшифровки uid
if (($member_id['user_id'] == $uid) && ($member_id['use_demo_vip_group']) != 1)  {
  $time_limit = time() + 60*60*24;
  if ($db->query( "UPDATE ".PREFIX."_users SET use_demo_vip_group = '1', user_group = '7', time_limit = ".$time_limit." WHERE user_id = '".$uid."'" )) {
    $end = "Вы использовали свою возможность и на 1 сутки переведены в группу <span style=\"color:#ff00b2;\">VIP Посетители</span>.";
    include_once ENGINE_DIR . '/classes/mail.class.php';
    $mailsend = new dle_mail( $config );
    $mailsend->from = 'robot@domain.com';
    $maila = langdate( 'D d F Y - H:i', time() ).' юзер <span style="color:#3367ab; font-weight:bold;">'.$member_id['name'].'</span> переведён в группу <span style="color:#ff00b2;">VIP Посетители</span> на тестовый период 1 сутки.';
		$mailsend->html_mail = true;
		$mailsend->send( "odmin@domain.com", "Новый юзер в группе VIP Посетители на сайте domain.com", "$maila" );
  }
  else $end = "Обнаружена ошибка, попробуйте повторить действие немного позже!";
} else $end = "Действие запрешено!";
break;

case 46 :
//################# Удаление обложки исполнителя в админке
	$image_cover = $_POST['image_cover'];
	$artid = intval($_POST['artid']);
	if ( is_file (ROOT_DIR.'/uploads/artists/'.$image_cover)) {
    if (@unlink (ROOT_DIR.'/uploads/artists/'.$image_cover)) {
      $end = 'Обложка удалена';
      $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover = '' WHERE artid = '".$artid."'" );
     }
  }
break;

case 47 :
//################# запись жанра исполнителя в базу в админке
$artid = intval($_POST['artid']);
$genreid = intval($_POST['genreid']);

if ($db->query( "UPDATE ".PREFIX."_mservice_artists SET genre = '".$genreid."' WHERE artid = '".$artid."'" )) $end = "Жанр задан исполнителю! <span>Записывается в базу AJAX'ом, редактировать альбом не надо!</span>";
else $end = "<span style=\"color:#cc0000;\">Ошибка! Жанр НЕ задан альбому!</span>";
break;

case 48 :
//################# Добавление обложки исполнителя в админке
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
						
						//150 - размер картинки в пикселях, означает 150х150
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
//################# Удаление обложки исполнителя 150x150 в админке
	$image_cover150 = $_POST['image_cover150'];
	$artid = intval($_POST['artid']);
	if ( is_file (ROOT_DIR.'/uploads/artists/'.$image_cover150)) {
    if (unlink (ROOT_DIR.'/uploads/artists/'.$image_cover150)) {
      $end = 'Обложка удалена';
      $db->query( "UPDATE ".PREFIX."_mservice_artists SET image_cover150 = '' WHERE artid = '".$artid."'" );
     }
  }
break;

case 50 :
//################# Добавление обложки исполнителя 150х150 в админке
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
						
						//150 - размер картинки в пикселях, означает 150х150
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
//раньше тут стояло Content-type: text/css; - это вроде неправильно
@header( "Content-type: text/html; charset=windows-1251" );
echo $end;
?>
