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

// Форма добавление нового трека 
case 'addfile':

$metatags['title'] = 'Добавление mp3-файла на '.$config['home_title_short'];
$metatags['description'] = 'Страница добавления своих mp3-файлов на '.$config['description'];
$metatags['keywords'] = 'добавить mp3, добавить музыку, '.$config['keywords'];

$mtitle .= '<h3 class="tit"><span>Одиночная Загрузка</span> mp3-файла</h3>';

if ( $user_group[$member_id['user_group']]['mservice_addfile'] != 1 ) $stop[] = 'У Вас нет прав для публикации аудио треков!';

include_once ENGINE_DIR.'/modules/mservice/mod_addfile.php';

include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
$mcontent .= $popular_search_artists;
$mcontent .= $popular_search_tracks;
$mcontent .= $popular_search_albums;
break;

// Сохранение аудио трека и добавление его в базу данных
case 'doaddfile':

$metatags['title'] = 'Добавление mp3-файла на '.$config['home_title_short'];
$metatags['description'] = 'Страница добавления своих mp3-файлов на '.$config['description'];
$metatags['keywords'] = 'добавить mp3, добавить музыку, '.$config['keywords'];

$mtitle .= '<h3 class="tit">Результаты <span>Одиночной Загрузки</span> mp3-файла</h3>';

$name = $parse->remove( $parse->process( parseLinks( $_POST['name'], $mscfg['parse_id3_tags'] ) ) );
$artist = $parse->remove( $parse->process( parseLinks( $_POST['artist'], $mscfg['parse_id3_tags'] ) ) );
$descr = $parse->remove( $parse->process( parseLinks( $_POST['comments'], $mscfg['parse_id3_tags'] ) ) );

$rlnk = $config['http_home_url'].'music/rules.html';

if ( $user_group[$member_id['user_group']]['mservice_addfile'] != 1 ) $stop[] = 'У Вас нет прав для публикации аудио треков!';
if ( $name == '' ) $stop[] = 'Вы не ввели название аудио трека!';
if ( $artist == '' ) $stop[] = 'Вы не ввели исполнителя аудио трека!';
if ( $_FILES['file']['size'] == FALSE ) $stop[] = 'Вы не выбрали файл трека для загрузки на сервер!';
if ( $user_group[$member_id['user_group']]['mservice_captcha'] == 1 ) {
  if ( $_POST['sec_code'] != $_SESSION['sec_code_session'] OR ! $_SESSION['sec_code_session'] ) $stop[] = 'Вы ввели неверный защитный код с изображения!';
}

$row = $db->super_query( "SELECT COUNT(1) as count FROM ".PREFIX."_mservice WHERE title = '$name' AND artist = '$artist'" );
if ( $row['count'] != 0 ) $stop[] = 'Трек этого Исполнителя с таким Названием уже есть на нашем сайте!';

$allowed_files = explode( ',', strtolower( $mscfg['filetypes'] ) );
$tfile = strtolower(end( explode( ".", totranslit( $_FILES['file']['name'] ) ) ) );
$file_allow = FALSE;
for ( $f = 0; $f < count( $allowed_files ); $f ++ ) {
  if ( $tfile == $allowed_files[$f] ) $file_allow = TRUE;
}
if ( $file_allow == FALSE ) $stop[] = 'Вы не можете загружать файлы такого типа!';
if ( $_FILES['file']['size'] > $mscfg['maxfilesize'] * 1024 ) $stop[] = 'Выбранный Вами файл слишком большой!';

if ( count( $stop ) == 0 ) {
	$mcontent .= '<div style="padding-left:15px;">';
  $time = time( );
  $for_md5 = $time + mt_rand( 0, 1000 );
  $for_md5 .= $_FILES['file']['name'];
  $filename = md5( $for_md5 ).'.'. $tfile;
  $hdd = 2;
  $subpapka = nameSubDir( $time, $hdd );
  @move_uploaded_file( $_FILES['file']['tmp_name'], '/home2/mp3base/'.$subpapka.'/'.$filename );

		include_once ENGINE_DIR.'/modules/mservice/mediatags/getid3.php';
		$mediatags = new getID3;
    $track = $mediatags->analyze( '/home2/mp3base/'.$subpapka.'/'.$filename );
        
		$beats = intval( $track['bitrate'] / 1000 );
		$lenght = $track['playtime_string'];

    if ( (empty( $lenght )) || ( empty( $beats ) )) {
      $stop[] = 'Файл имеет неправильный размер и/или не является аудиофайлом mp3!';
      if ( is_file('/home2/mp3base/'.$subpapka.'/'.$filename))  unlink('/home2/mp3base/'.$subpapka.'/'.$filename);
      break;
    }
    
		//if ( empty( $lenght ) ) $lenght = '';
		//if ( $beats <= 0 ) $beats = 128;
		
//////////////////////////////////////////////////////////////////////* Исправление ID3 тегов файлу */
					// Initialize getID3 engine
					$getID3 = new getID3;
					$getID3->encoding = $TaggingFormat;
 
					require_once(ENGINE_DIR.'/modules/mservice/mediatags/write.php');
					// Initialize getID3 tag-writing module
					$tagwriter = new getid3_writetags;
					$tagwriter->filename = '/home2/mp3base/'.$subpapka.'/'.$filename;
					$tagwriter->tagformats = array('id3v1', 'id3v2.3');
					
					//извлекаем инфу из файла для получения обложки
					$ThisFileInfo = $getID3->analyze($tagwriter->filename);
					
					// set various options (optional)
					//$tagwriter->tag_encoding = 'CP1251';
					$tagwriter->overwrite_tags = true;
					$tagwriter->remove_other_tags = false;
					
					// populate data array
					$TagData['title'][] = $name.' (domain.com)';
					$TagData['artist'][] = $artist;
					$TagData['album'][] = 'domain.com';
					$TagData['genre'][] = 'domain.com';
					$TagData['comment'][] = $descr;
					if (isset($ThisFileInfo['id3v2']['APIC'][0]['data'])) {
						$TagData['attached_picture'][0]['data'] = $ThisFileInfo['id3v2']['APIC'][0]['data'];
						$TagData['attached_picture'][0]['picturetypeid'] = $ThisFileInfo['id3v2']['APIC'][0]['picturetypeid'];
						$TagData['attached_picture'][0]['description'] = $ThisFileInfo['id3v2']['APIC'][0]['description'];
						$TagData['attached_picture'][0]['mime'] = $ThisFileInfo['id3v2']['APIC'][0]['mime'];
					}
					
					$tagwriter->tag_data = $TagData;
					
					// write tags 
					$tagwriter->WriteTags();
					unset($getID3, $tagwriter, $ThisFileInfo, $TagData);
///////////////////////////////////////////////////////////////////////////* Конец Исправление ID3 тегов файлу */

$size = @filesize('/home2/mp3base/'.$subpapka.'/'.$filename);
$crc32b = @hash_file('crc32b', '/home2/mp3base/'.$subpapka.'/'.$filename);
$transartist = totranslit( $artist );
$strip_base64_artist = str_replace('/','_',str_replace('+','*',base64_encode($artist)));
$strip_base64_title = str_replace('/','_',str_replace('+','*',base64_encode($name)));
// чтобы если грузит песни админ, прописывался другой аплоадер
$uploaderid = $member_id['user_id'];
if ($member_id['user_id'] == 1017) $uploaderid = 27606;
              
if ( $user_group[$member_id['user_group']]['mservice_newtrack_approve'] == 1 ) $approve = 1;
else $approve = 0;

$db->query( "INSERT INTO ".PREFIX."_mservice ( time, title, rating, approve, vote_num, artist, download, description, filename, hdd, size, crc32, uploader, view_count, transartist, lenght, bitrate, strip_base64_artist, strip_base64_title ) VALUES ( '$time', '$name', '0', '$approve', '0', '$artist', '0', '$descr', '$filename', '$hdd', '$size', '$crc32b', '$uploaderid', '0', '$transartist', '$lenght', '$beats', '$strip_base64_artist', '$strip_base64_title' )" );

$row = $db->super_query( "SELECT mid FROM ".PREFIX."_mservice WHERE filename = '$filename'" );

$vlnk = $config['http_home_url'].'music/'.$row['mid'].'-mp3-'.totranslit( $artist ).'-'.totranslit( $name ).'.html';

include_once ENGINE_DIR . '/classes/mail.class.php';
$mailsend = new dle_mail( $config );
$mailsend->from = 'robot@domain.com';

if ( $user_group[$member_id['user_group']]['mservice_newtrack_approve'] != 1 ) {
  $moder = ' Обратите внимание, Ваш трек будет доступен в музыкальном архиве только после того как пройдёт модерацию.';
  $maila = langdate( 'D d F Y - H:i', time() ).' юзер '.$member_id[name].' добавил новый мп3 трек '.$artist.' - '.$name.'. Трек отправлен НА МОДЕРАЦИЮ';
  $maila = wordwrap($maila, 70);
  $mailsend->send( "odmin@domain.com", "Новая песня на сайте domain.com НА МОДЕРАЦИИ", "$maila" );
}
else {
  $moder = '';
  $maila = langdate( 'D d F Y - H:i', time() ).' юзер '.$member_id[name].' добавил новый мп3 трек '.$artist.' - '.$name;
  $maila = wordwrap($maila, 70);
  $mailsend->send( "odmin@domain.com", "Новая песня на сайте domain.com", "$maila" );
}
			$mcontent .= <<<HTML
			<div id="result_add"></div>
<script type="text/javascript">
showResultAdd( '{$vlnk}', '{$artist}', '{$name}', '{$moder}' );
</script>
<br />
HTML;
}
  $mcontent .= '<h3 class="tit">Одиночная Загрузка mp3-файла</h3>';
  include_once ENGINE_DIR.'/modules/mservice/mod_addfile.php';

  include_once ENGINE_DIR.'/modules/mservice/mod_popular_search.php';
  $mcontent .= $popular_search_artists;
  $mcontent .= $popular_search_tracks;
  $mcontent .= $popular_search_albums;
	$mcontent .= '</div>';
break;

}
?>
