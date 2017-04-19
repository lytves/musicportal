<?php
define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../../..' );
define( 'ENGINE_DIR', '../..' );

$r =  intval($_POST['r']);

include_once ENGINE_DIR.'/classes/parse.class.php';
$parse = new ParseFilter( );
$parse->safe_mode = true;
$q =  trim(iconv("utf-8", "windows-1251", $_POST['q']));

//заменяем кавычки на пробелы!!!
$q = str_replace("'"," ",$q);

$q = $parse->remove( $parse->process( $q ) );

if(strlen($q)<2) exit;

setlocale(LC_ALL,'ru_RU');

require_once ENGINE_DIR . '/data/mservice.php';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/mservice/functions.php';

$text = '';
/// Новое условие на вхождение  точек больше одной
$dotcount = substr_count($q, ".");
if ($dotcount > 1) {
    if ($r == 2) {
	   $db->query( "SELECT artist, strip_base64_artist FROM ".PREFIX."_mservice WHERE artist LIKE '$q%' AND approve = '1' GROUP BY artist LIMIT 0,".$mscfg['livesearch_track_lim'] );
	   if ( $db->num_rows( ) > 0 ) {
	       while ( $row = $db->get_row( ) ) {
                $result = $row['artist'];
                $result_strip_base64 = $row['strip_base64_artist'];
                if ( strlen( $result ) > 60 ) $result = substr( $result, 0, 60 ) . "...";
		
                $text .= $result.'$#'.$result_strip_base64.'$#';
            }
        echo substr($text, 0, -2);
	   }
    }
 
} else {
/* 1.обрезаем до 60 символов 2.заменяем всё что не буква/цифра/пробел(/s) на пробел 3.вырезаем слова длиной один символ, 4.ф-ция удаления лишнего, 5.trim'аем пробелы в начале/конце подстроки */
$textsql = substr($q,0,60);
$textsql = mb_strtolower($textsql,'windows-1251');
$textsql = preg_replace("/[^\w\э\р\s]/", " ", $textsql);
$textsql  = textClear($textsql);
$textsql = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $textsql "));

$q2 = textSwitch ($q,2);

$textsql2 = substr($q2,0,60);
$textsql2 = mb_strtolower($textsql2,'windows-1251');
$textsql2 = preg_replace("/[^\w\э\р\s]/", " ", $textsql2);
$textsql2  = textClear($textsql2);
$textsql2 = trim(preg_replace("/\s(\S{1,1})\s/", " ", " $textsql2 "));

// 1.определяем количество=$textword_count слов в подстроке (включая все цифры и т.д.) 2. --||-- и скидываем все слова в массив $textword_array
$list = "Ёё0123456789_"; 
for ( $i = 192; $i < 256; $i++ ) { 
  $list = $list . chr($i); 
}
$textword_count = str_word_count($textsql,0,$list);
$textword_array = str_word_count($textsql,1,$list);
$textword_array2 = str_word_count($textsql2,1,$list);

/* это вроде бы ненужное условие, т.к. с дефисом это уже два слова и они нормально ищутся и так
если подстрока из одного слова и в ней есть дефис (2D), то заменяем его на "пробел+" и вначало подстроки идёт тоже "+", 
trim'аем пробелы в начале/конце подстроки 
if ($textword_count == 1) {
  $textsql = trim(preg_replace("/\x2D/", " +", "+$textsql"));
  $textsql2 = trim(preg_replace("/\x2D/", " +", "+$textsql2"));
}
*/

/*если подстрока поиска более чем из 2ух слов то очищаем подстроку для запроса в MySQL, считываем по одному слову 
и если длина этого слова больше 1 буквы (иногда проскакивали и такие), то добавляем к подстроке для запроса нужные для 
поиска in boolean mode символы "+..*", trim'аем пробелы в начале/конце подстроки */
if ($textword_count > 1) {
	$textsql = '';
	$textsql2 = '';
	foreach ($textword_array as $textword) {
		if (strlen($textword) > 1) $textsql .= '+'.$textword.'* ';
	}
	$textsql = trim($textsql);
	foreach ($textword_array2 as $textword) {
    if (strlen($textword) > 1) $textsql2 .= '+'.$textword.'* ';
	}
	$textsql2 = trim($textsql2);
}
//echo $textsql.' '.$textsql2;
//с этим mb_convert_case работает точно на сервере, на локалхосте возможно наоборот
$q = mb_convert_case($q, MB_CASE_TITLE, "cp1251");
$q2 = mb_convert_case($q2, MB_CASE_TITLE, "cp1251");

//Поиск по исполнителю
if ($r == 2) {
///////////////////////////////////////////// для вывода в начале списка точного совпадения исполнителя, если оно есть конечно
  $textart = substr($q,0,60);
  $textart = addslashes(mb_strtolower($textart,'windows-1251'));

  $q2 = textSwitch ($q,2);
  $textart2 = substr($q2,0,60);
  $textart2 = addslashes(mb_strtolower($textart2,'windows-1251'));
//echo $textart.' '.$textart2;
  $row_artist = $db->super_query( "SELECT DISTINCT artist, strip_base64_artist FROM ".PREFIX."_mservice WHERE (artist = '$textart' OR artist = '$textart2') AND approve = '1' LIMIT 1" );
  if ($row_artist) {
    $first_artist_compare = $row_artist['artist'];
    $result_artist = $row_artist['artist'];
    if ( @strlen( $result_artist ) > 60 ) $result_artist = substr( $result_artist, 0, 0 ) . "...";
    
    //$result_artist2 = @str_ireplace($q, '<span class="highlight">'.$q.'</span>', $result_artist);
		//if ($result_artist == $result_artist2 ) $result_artist2  = @str_ireplace($q2, '<span class="highlight">'.$q2.'</span>', $result_artist);
		// выше старый вариант, меняет регистр результата, делая заглавной только первую букву

      $result_lenght_a = stripos($result_artist, $q);
      $result_lenght_a2 = stripos($result_artist, $q2);
      if ($result_lenght_a !== false) {
        $result_artist2 = substr_replace($result_artist, '<span class="highlight">', $result_lenght_a, 0);
        $result_artist2 = substr_replace($result_artist2, '</span>', ($result_lenght_a+24+strlen($q)), 0);
      } elseif ($result_lenght_a2 !== false) {
          $result_artist2 = substr_replace($result_artist, '<span class="highlight">', $result_lenght_a2, 0);
          $result_artist2 = substr_replace($result_artist2, '</span>', ($result_lenght_a2+24+strlen($q2)), 0);
      } else $result_artist2 = $result_artist;
      
    $textbegin = $result_artist2.'$#'.$row_artist['strip_base64_artist'].'$#';
  } else {
    $first_artist_compare = '';
    $textbegin = '';
  }
///////////////////////////////////////////// конец вывода точного совпадения исполнителя

	$db->query( "SELECT artist, strip_base64_artist FROM ".PREFIX."_mservice WHERE MATCH(artist) AGAINST('($textsql*) ($textsql2*)' IN BOOLEAN MODE) AND approve = '1' GROUP BY artist LIMIT 0,".$mscfg['livesearch_track_lim'] );
	if ( $db->num_rows( ) > 0 ) {
		while ( $row = $db->get_row( ) ) {
			$result = $row['artist'];
			if ($result == $first_artist_compare) continue; // если нашлось точное совпадение исполнителя, то переходим на следующую итерацию цикла
      if ( @strlen( $result ) > 60 ) $result = substr( $result, 0, 60 ) . "...";
      
      //$result2  = @str_ireplace($q, '<span class="highlight">'.$q.'</span>', $result);
			//if ($result == $result2 ) $result2  = @str_ireplace($q2, '<span class="highlight">'.$q2.'</span>', $result);
			// выше старый вариант, меняет регистр результата, делая заглавной только первую букву
			
      $result_lenght = stripos($result, $q);
      $result_lenght2 = stripos($result, $q2);
      if ($result_lenght !== false) {
        $result2 = substr_replace($result, '<span class="highlight">', $result_lenght, 0);
        $result2 = substr_replace($result2, '</span>', ($result_lenght+24+strlen($q)), 0);
      } elseif ($result_lenght2 !== false) {
          $result2 = substr_replace($result, '<span class="highlight">', $result_lenght2, 0);
          $result2 = substr_replace($result2, '</span>', ($result_lenght2+24+strlen($q2)), 0);
      } else $result2 = $result;
      
			$text .= $result2.'$#'.$row['strip_base64_artist'].'$#';
		}
	echo substr($textbegin.$text, 0, -2);// объединяем с точным выводом исполнителя и режем последние $#
	}
}
//Поиск по треку
elseif ($r == 3) {
///////////////////////////////////////////// для вывода в начале списка точного совпадения трека, если оно есть конечно
  $texttrack = substr($q,0,60);
  $texttrack = addslashes(mb_strtolower($texttrack,'windows-1251'));

  $q2 = textSwitch ($q,2);
  $texttrack2 = substr($q2,0,60);
  $texttrack2 = addslashes(mb_strtolower($texttrack2,'windows-1251'));
//echo $textart.' '.$textart2;
  $row_track = $db->super_query( "SELECT DISTINCT title, strip_base64_title FROM ".PREFIX."_mservice WHERE (title = '$texttrack' OR title = '$texttrack2') AND approve = '1' LIMIT 1" );
  if ($row_track) {
    $first_track_compare = $row_track['title'];
    $result_track = $row_track['title'];
    if ( @strlen( $result_track ) > 60 ) $result_track = substr( $result_track, 0, 60 ) . "...";
    
    //$result_artist2 = @str_ireplace($q, '<span class="highlight">'.$q.'</span>', $result_artist);
		//if ($result_artist == $result_artist2 ) $result_artist2  = @str_ireplace($q2, '<span class="highlight">'.$q2.'</span>', $result_artist);
		// выше старый вариант, меняет регистр результата, делая заглавной только первую букву

      $result_lenght_t = stripos($result_track, $q);
      $result_lenght_t2 = stripos($result_track, $q2);
      if ($result_lenght_t !== false) {
        $result_track2 = substr_replace($result_track, '<span class="highlight">', $result_lenght_t, 0);
        $result_track2 = substr_replace($result_track2, '</span>', ($result_lenght_t+24+strlen($q)), 0);
      } elseif ($result_lenght_t2 !== false) {
          $result_track2 = substr_replace($result_track, '<span class="highlight">', $result_lenght_t2, 0);
          $result_track2 = substr_replace($result_track2, '</span>', ($result_lenght_t2+24+strlen($q2)), 0);
      } else $result_track2 = $result_track;
      
    $textbegin = $result_track2.'$#'.$row_track['strip_base64_title'].'$#';
  } else {
    $first_track_compare = '';
    $textbegin = '';
  }
///////////////////////////////////////////// конец вывода точного совпадения трека

	$db->query( "SELECT title, strip_base64_title FROM ".PREFIX."_mservice WHERE MATCH(title) AGAINST('($textsql*) ($textsql2*)' IN BOOLEAN MODE) AND approve = '1' GROUP BY title LIMIT 0,".$mscfg['livesearch_track_lim'] );
	if ( $db->num_rows( ) > 0 ) {
		while ( $row = $db->get_row( ) ) {
			$result = $row['title'];
			if ($result == $first_track_compare) continue; // если нашлось точное совпадение исполнителя, то переходим на следующую итерацию цикла
			if ( @strlen( $result ) > 60 ) $result = substr( $result, 0, 60 ) . "...";
			
			//$result2  = @str_ireplace($q, '<span class="highlight">'.$q.'</span>', $result);
			//if ($result == $result2 ) $result2  = @str_ireplace($q2, '<span class="highlight">'.$q2.'</span>', $result);
			// выше старый вариант, меняет регистр результата, делая заглавной только первую букву
			
			$result_lenght = stripos($result, $q);
      $result_lenght2 = stripos($result, $q2);
      if ($result_lenght !== false) {
        $result2 = substr_replace($result, '<span class="highlight">', $result_lenght, 0);
        $result2 = substr_replace($result2, '</span>', ($result_lenght+24+strlen($q)), 0);
      } elseif ($result_lenght2 !== false) {
          $result2 = substr_replace($result, '<span class="highlight">', $result_lenght2, 0);
          $result2 = substr_replace($result2, '</span>', ($result_lenght2+24+strlen($q2)), 0);
      } else $result2 = $result;
      
			$text .= $result2.'$#'.$row['strip_base64_title'].'$#';
		}
	echo substr($textbegin.$text, 0, -2);// объединяем с точным выводом трека и режем последние три $#

	}
}
//Поиск по альбому
elseif ($r == 4) {

	$db->query( "SELECT artist, album, year, strip_base64_album FROM ".PREFIX."_mservice_albums WHERE MATCH(artist,album) AGAINST('($textsql*) ($textsql2*)' IN BOOLEAN MODE) AND approve = '1' LIMIT 0,".$mscfg['livesearch_track_lim'] );
	if ( $db->num_rows( ) > 0 ) {
		while ( $row = $db->get_row( ) ) {
			$result = $row['artist'].' - '.$row['album'];
			if ( @strlen( $result ) > 60 ) $result = substr( $result, 0, 60 ) . "...";
			
			//$result2  = @str_ireplace($q, '<span class="highlight">'.$q.'</span>', $result);
			//if ($result == $result2 ) $result2  = @str_ireplace($q2, '<span class="highlight">'.$q2.'</span>', $result);
			// выше старый вариант, меняет регистр результата, делая заглавной только первую букву
			
			$result_lenght = stripos($result, $q);
      $result_lenght2 = stripos($result, $q2);
      if ($result_lenght !== false) {
        $result2 = substr_replace($result, '<span class="highlight">', $result_lenght, 0);
        $result2 = substr_replace($result2, '</span>', ($result_lenght+24+strlen($q)), 0);
      } elseif ($result_lenght2 !== false) {
          $result2 = substr_replace($result, '<span class="highlight">', $result_lenght2, 0);
          $result2 = substr_replace($result2, '</span>', ($result_lenght2+24+strlen($q2)), 0);
      } else $result2 = $result;
      
			$text .= $result2.' ('.$row['year'].')'.'$#'.str_replace('/','_',str_replace('+','*',base64_encode($result))).'$#';
		}
	echo substr($text, 0, -2);// режем последние три $#

	}
} else exit;
}
?>