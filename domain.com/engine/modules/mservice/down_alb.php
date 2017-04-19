<?php

$aid = intval( $_GET['aid'] );
$salt = $_GET['salt'];

if (($salt == '7K70qCee') && ($aid > 0)) {

define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', '../../..' );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

@error_reporting ( E_ALL ^ E_NOTICE );
@ini_set ( 'display_errors', false );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/mservice/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

  $row = $db->super_query( "SELECT aid, year, album, image_cover, image_cover_crc32, collection, transartist FROM ".PREFIX."_mservice_albums WHERE aid = '$aid' AND approve = '1'" );
  $archivename = $row['transartist'].'_-_'.totranslit( $row['album'] ).'_('.$row['year'].')_(domain.com)'.'.zip';
  $dirinarchivename = mb_convert_case($row['transartist'].'_-_'.totranslit( $row['album'] ).'_('.$row['year'].')', MB_CASE_TITLE, "cp1251").'_(domain.com)';
  if ($row['image_cover']) {$image_cover = $row['image_cover']; $image_cover_crc32 = $row['image_cover_crc32'];}
  if ($row['collection']) $collection_mids = " OR mid IN(".$row['collection'].")";
  else $collection_mids = "";
  unset($row, $zip_files);
  
  $db->query( "SELECT time, title, filename, hdd, crc32, transartist FROM ".PREFIX."_mservice WHERE approve = '1' AND album = '$aid'$collection_mids LIMIT 0,50");
  if ( $db->num_rows() > 1) {
    while ( $row = $db->get_row( ) ) {
      $hdd = intval($row['hdd']);
      if ( ($hdd !== 2) ) $hdd = '';
      
      if ( is_file('/home'.$hdd.'/mp3base/'.nameSubDir($row["time"],$hdd).'/'.$row['filename']) ) {
      
        $format = strtolower(end( explode( '.', $row['filename'] ) ) );
        $file = mb_convert_case($row['transartist'], MB_CASE_TITLE, "cp1251").'_-_'.mb_convert_case(totranslit( $row['title'] ), MB_CASE_TITLE, "cp1251").'_(domain.com).'.$format;
        //if (!$row['crc32'] OR $row['crc32'] == 0) $row['crc32'] = '-';
        if (!$row['crc32'] OR $row['crc32'] == 0) $row['crc32'] = hash_file('crc32b', '/home'.$hdd.'/mp3base/'.nameSubDir($row["time"],$hdd).'/'.$row['filename']);
        
        $filesize = filesize('/home'.$hdd.'/mp3base/'.nameSubDir($row["time"],$hdd).'/'.$row['filename']);
        
        $zip_files[] = $row['crc32'].' '.$filesize.' /modik_zipik'.$hdd.'/'.nameSubDir($row['time'],$hdd).'/'.$row['filename'].' '.$dirinarchivename.'/'.$file;
      }
    }
    if ($image_cover) {
      if (!$image_cover_crc32 OR $image_cover_crc32 == 0) $image_cover_crc32 = hash_file('crc32b', ROOT_DIR .'/uploads/albums/'.$image_cover);
      $image_cover_size = filesize(ROOT_DIR .'/uploads/albums/'.$image_cover);
      $image_cover_name = explode( ".", $image_cover );
      $image_cover_type = end( $image_cover_name );
      $zip_files[] = $image_cover_crc32.' '.$image_cover_size.' /uploads/albums/'.$image_cover.' '.$dirinarchivename.'/cover.'.$image_cover_type;
    }
  } 

  if ( count($zip_files) > 1 ) {
    $time = time();
    $downtime = 600;
    //600 секунд = 10 минут
    $downtime = ($time - $downtime);
    
    $row = $db->super_query( "SELECT aid FROM ".PREFIX."_mservice_downloads_albums WHERE aid = '$aid' AND user_id = '$member_id[user_id]' AND (time_php >= '$downtime' OR time_php = '$time') LIMIT 1" );
    
    if ( !$row['aid'] ) {
      $referer = addslashes($_SERVER['HTTP_REFERER']);
      $user_agent = addslashes($_SERVER['HTTP_USER_AGENT']);
      $row2 = $db->query( "UPDATE ".PREFIX."_mservice_albums SET download = download + 1 WHERE aid = '$aid'" );
      $row2 = $db->query( "INSERT INTO ".PREFIX."_mservice_downloads_albums SET time_php = '$time', user_id = '$member_id[user_id]', aid = '$aid', ip ='$_SERVER[REMOTE_ADDR]', user_agent = '$user_agent', referer = '$referer' " );
  	}
  	header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename=' . $archivename);
    header('X-Archive-Files: zip');
    echo implode("\r\n", $zip_files)."\r\n";
    /*foreach ($zip_files as $key => $value) {
		echo $value.'<br />';
    }*/
    if (($member_id['user_id'] == 0) AND (!$row['aid']) AND ($_SERVER['HTTP_USER_AGENT'] != 'Download Master')) {
    		//дальше отправляем письмо с текстом на два мыла
      function clean_string($string) {
        $bad = array("content-type","bcc:","to:","cc:","href");
        return str_replace($bad,"",$string);
      }

      include_once ENGINE_DIR . '/classes/mail.class.php';
      require_once ENGINE_DIR . '/data/config.php';
      $ref = $config['http_home_url'].substr($_SERVER['REQUEST_URI'],1); // Адрес страницы ошибки
      $mailsend = new dle_mail( $config );
      $mailsend->from = 'robot@domain.com';
      $mailsend->html_mail = true;
         
      $mailtext = "<strong style='font-size:20px;'>скачан альбом с параметром member_id[user_id] = 0</strong><br /><br />";
      $mailtext .= "<strong>Страница с ошибкой:</strong> ".clean_string($ref)."<br /><br />";

      $mailtext .= "<strong>Реферер:</strong> ".clean_string($_SERVER['HTTP_REFERER'])."<br />";
      $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."<br />";
      $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."<br />";
 
      $mailsend->send( "odmin@domain.com", "скачан альбом с параметром member_id[user_id] = 0", "$mailtext" );
    }
    exit();
  } else {
    header("HTTP/1.1 301 Moved Permanently");
    header("location: http://domain.com/");
  }
  

} else {
		define ( 'DATALIFEENGINE', true );
    define ( 'ROOT_DIR', '../../..' );
    define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

		//дальше отправляем письмо с текстом ошибки на два мыла
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }

    include_once ENGINE_DIR . '/classes/mail.class.php';
    require_once ENGINE_DIR . '/data/config.php';
    $ref = $config['http_home_url'].substr($_SERVER['REQUEST_URI'],1); // Адрес страницы ошибки
    $mailsend = new dle_mail( $config );
    $mailsend->from = 'robot@domain.com';
    $mailsend->bcc[0] = 'odmin@domain.com';
    $mailsend->html_mail = true;
         
    $mailtext = "<strong style='font-size:20px;'>Not Found - Error 404 (Не найдено - Ошибка 404)!</strong><br /><br />";
    $mailtext .= "<strong>Страница с ошибкой:</strong> ".clean_string($ref)."<br /><br />";

    $mailtext .= "<strong>Реферер:</strong> ".clean_string($_SERVER['HTTP_REFERER'])."<br />";
    $mailtext .= "<strong>Браузер пользователя:</strong> ".clean_string($_SERVER['HTTP_USER_AGENT'])."<br />";
    $mailtext .= "<strong>IP-адрес пользователя:</strong> ".clean_string($_SERVER['REMOTE_ADDR'])."<br />";
 
    $mailsend->send( "vlit@ukr.net", "404 ошибка на сайте domain.com", "$mailtext" );
    header('HTTP/1.1 404 Not Found');
    header('Status: 404 Not Found');
    header("location: http://domain.com/");
}

?>
