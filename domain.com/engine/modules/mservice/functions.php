<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

// Алфавитный указатель исполнителей
function ArtistLetterNavigator( ) {
global $mscfg, $letter, $config;

$seo = '';
$href = $config['http_home_url'] . 'music/artist-'; $seo = '.html';


if ( $mscfg['letter_navig_english'] == 1 ) {

$template .= <<<HTML
<h3 class="tit_green">Алфавитный указатель всех исполнителей</h3>
<div class="letters"><a href="{$href}0-9{$seo}">0-9</a>
<a href="{$href}A{$seo}">A</a>
<a href="{$href}B{$seo}">B</a>
<a href="{$href}C{$seo}">C</a>
<a href="{$href}D{$seo}">D</a>
<a href="{$href}E{$seo}">E</a>
<a href="{$href}F{$seo}">F</a>
<a href="{$href}G{$seo}">G</a>
<a href="{$href}H{$seo}">H</a>
<a href="{$href}I{$seo}">I</a>
<a href="{$href}J{$seo}">J</a>
<a href="{$href}K{$seo}">K</a>
<a href="{$href}L{$seo}">L</a>
<a href="{$href}M{$seo}">M</a>
<a href="{$href}N{$seo}">N</a>
<a href="{$href}O{$seo}">O</a>
<a href="{$href}P{$seo}">P</a>
<a href="{$href}Q{$seo}">Q</a>
<a href="{$href}R{$seo}">R</a>
<a href="{$href}S{$seo}">S</a>
<a href="{$href}T{$seo}">T</a>
<a href="{$href}U{$seo}">U</a>
<a href="{$href}V{$seo}">V</a>
<a href="{$href}W{$seo}">W</a>
<a href="{$href}X{$seo}">X</a>
<a href="{$href}Y{$seo}">Y</a>
<a href="{$href}Z{$seo}">Z</a></div>
HTML;
}

if ( $mscfg['letter_navig_russian'] == 1 ) {

$template .= <<<HTML
<div class="letters"><a href="{$href}%C0{$seo}">А</a>
<a href="{$href}%C1{$seo}">Б</a>
<a href="{$href}%C2{$seo}">В</a>
<a href="{$href}%C3{$seo}">Г</a>
<a href="{$href}%C4{$seo}">Д</a>
<a href="{$href}%C5{$seo}">Е</a>
<a href="{$href}%C6{$seo}">Ж</a>
<a href="{$href}%C7{$seo}">З</a>
<a href="{$href}%C8{$seo}">И</a>
<a href="{$href}%CA{$seo}">К</a>
<a href="{$href}%CB{$seo}">Л</a>
<a href="{$href}%CC{$seo}">М</a>
<a href="{$href}%CD{$seo}">Н</a>
<a href="{$href}%CE{$seo}">О</a>
<a href="{$href}%CF{$seo}">П</a>
<a href="{$href}%D0{$seo}">Р</a>
<a href="{$href}%D1{$seo}">С</a>
<a href="{$href}%D2{$seo}">Т</a>
<a href="{$href}%D3{$seo}">У</a>
<a href="{$href}%D4{$seo}">Ф</a>
<a href="{$href}%D5{$seo}">Х</a>
<a href="{$href}%D6{$seo}">Ц</a>
<a href="{$href}%D7{$seo}">Ч</a>
<a href="{$href}%D8{$seo}">Ш</a>
<a href="{$href}%D9{$seo}">Щ</a>
<a href="{$href}%DD{$seo}">Э</a>
<a href="{$href}%DE{$seo}">Ю</a>
<a href="{$href}%DF{$seo}">Я</a></div>
HTML;

}

return '<div class="mservice_letternavig">' . $template . '</div>';
}

// используется для создания подкаталогов по дате добавления треков в папке хранения всех треков в /home/mp3base/ И /home2/mp3base/
function nameSubDir( $time, $hdd ) {
		$time = intval($time);
		$nameSubDir = @date( 'Y/m', $time );
		$nameSubDirYear = @date( 'Y', $time );
		$nameSubDirMonth = @date( 'm', $time );
		
		$hdd = intval($hdd);
		if ( ($hdd !== 2) ) $hdd = '';
		
		if(!@is_dir('/home'.$hdd.'/mp3base/'.$nameSubDir.'/')) {
			if(!@is_dir('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/')) {
				@mkdir ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/', 0777);
				@chmod ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/', 0777);
				@mkdir ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/'.$nameSubDirMonth.'/', 0777);
				@chmod ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/'.$nameSubDirMonth.'/', 0777);
			}
			else {
				@mkdir ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/'.$nameSubDirMonth.'/', 0777);
				@chmod ('/home'.$hdd.'/mp3base/'.$nameSubDirYear.'/'.$nameSubDirMonth.'/', 0777);
			}
		}
		return $nameSubDir;
}

// формирует аудио-флеш плеер, используется при открытии нового окна только с проигрыванием трека
function BuildAudioPlayer( $file, $artist, $track, $subpapka, $hdd, $autoplay = FALSE, $playning_window = FALSE, $type = FALSE ) {
global $config, $mscfg;

  $home_adress = $config['http_home_url'];
  if ( $type == FALSE ) $type = $mscfg['mfp_type'];
  if ( $autoplay == FALSE ) $autoplay = 'false'; else $autoplay = 'true';
  
  $hdd = intval($hdd);
	if ( ($hdd !== 2) ) $hdd = '';

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// добавить регуляркой стирание всех левых символов кроме букв англ. и чисел -  в $name....., пока качает нормально и без этого...
$secret = 'Edcdjhs09Sdkvc640';
$time = time() + 86400; //ссылка будет рабочей сутки
$key = str_replace("=", "", strtr(base64_encode(md5($secret.'/played'.$hdd.'/'.$subpapka.'/'.$file .$time.getenv("HTTP_X_REAL_IP"), TRUE)), "+/", "-_"));
$encoded_url = 'play'.$hdd.'/'.$subpapka.'/'.$key.'/'.$time.'/'.$file ;
$encoded_url_short = 'play'.$hdd.'/'.$subpapka.'/'.$key.'/'.$time.'/';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


if ( $type == "1" ) {

$return = <<<HTML
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" "http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="425" height="45">
<param name="movie" value="{$home_adress}engine/classes/flashplayer/flv_player.swf?config={embedded:true,playList:[{url:'{$home_adress}{$encoded_url}'}],initialScale:'fit',showMenu:false,backgroundColor:'-1',controlsOverVideo:'locked',controlBarGloss:'low',controlBarBackgroundColor:0,showFullScreenButton:false,usePlayOverlay:false,showOnLoadBegin:false,loop:false,autoRewind:true,autoBuffering:false,autoPlay:{$autoplay}}" />
<param name="allowFullScreen" value="true" />
<param name="quality" value="high" />
<param name="bgcolor" value="#000000" />
<param name="wmode" value="transparent" />
<embed src="{$home_adress}engine/classes/flashplayer/flv_player.swf?config={embedded:true,playList:[{url:'{$home_adress}{$encoded_url}'}],initialScale:'fit',showMenu:false,backgroundColor:'-1',controlsOverVideo:'locked',controlBarGloss:'low',controlBarBackgroundColor:0,showFullScreenButton:false,usePlayOverlay:false,showOnLoadBegin:false,loop:false,autoRewind:true,autoBuffering:false,autoPlay:{$autoplay}}" quality="high" bgcolor="#000000" wmode="transparent" allowFullScreen="true" width="425" height="45" align="middle" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>
HTML;
} elseif ( $type == "2" ) {
  if ( $autoplay == 'true' ) $autoplay = 1; else $autoplay = 0;
$return = <<<HTML
<object type="application/x-shockwave-flash" data="{$home_adress}engine/modules/mservice/player/player.swf" height="70" width="470">
<param name="wmode" VALUE="transparent" />
<param name="allowFullScreen" value="true" />
<param name="allowScriptAccess" value="always" />
<param name="movie" value="{$home_adress}engine/modules/mservice/player/player.swf" />
<param name="FlashVars" value="way={$home_adress}{$encoded_url}&amp;swf={$home_adress}engine/modules/mservice/player/player.swf&amp;w=470&amp;h=70&amp;time_seconds=0&amp;autoplay={$autoplay}&amp;q=&amp;skin=bb&amp;volume=80&amp;comment={$artist} - {$track}" />
</object>
HTML;
} elseif ( $type == "3" ) {
  if ( $mscfg['playning_allow_visual'] == 1 and $playning_window == TRUE ) { $plugins = "so.addVariable('plugins', 'revolt-1');"; $size = 320; } else { $plugins = ''; $size = 35; }
$return = <<<HTML
<script type='text/javascript' src='{$home_adress}engine/modules/mservice/player/swfobject.js'></script>
<div id='mediaspace'>Для отображения данного контента Вам необходимо установить / обновить <a href="http://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player</a>.</div>
<script type='text/javascript'>
  var msupd = '{$home_adress}{$encoded_url_short}';
  var so = new SWFObject('{$home_adress}engine/modules/mservice/player/player-viral.swf','ply','470','{$size}','9','#ffffff');
  so.addParam('allowfullscreen','false');
  so.addParam('allowscriptaccess','always');
  so.addParam('wmode','opaque');
  so.addVariable('displayclick','none');
  so.addVariable('file', msupd + '{$file}');
  so.addVariable('skin','{$home_adress}engine/modules/mservice/player/stylish-skin.swf');
  so.addVariable('autostart','{$autoplay}');
  {$plugins}
  so.write('mediaspace');
</script>
HTML;
}

return $return;
}

// формирует плеер MediaElement.js, новый используется на странице трека, сочетает в себе html5 иflash
function BuildPlayer( $file, $artist, $track, $subpapka, $hdd, $THEME ) {

  $hdd = intval($hdd);
	if ( ($hdd !== 2) ) $hdd = '';
	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// добавить регуляркой стирание всех левых символов кроме букв англ. и чисел -  в $name....., пока качает нормально и без этого...
$secret = 'Edcdjhs09Sdkvc640';
$time = time() + 86400; //ссылка будет рабочей сутки
$key = str_replace("=", "", strtr(base64_encode(md5($secret.'/played'.$hdd.'/'.$subpapka.'/'.$file .$time.getenv("HTTP_X_REAL_IP"), TRUE)), "+/", "-_"));
$encoded_url = 'play'.$hdd.'/'.$subpapka.'/'.$key.'/'.$time.'/'.$file ;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$return = <<<HTML
<script src="/engine/classes/js/mediaelementjs/mediaelement-and-player.min.js"></script>
<link rel="stylesheet" href="/engine/classes/js/mediaelementjs/mediaelementplayer.min.css" />
<script src="/engine/classes/js/mediaelementjs/mejs-feature-backlight.js"></script>

<audio id="player2" src="/{$encoded_url}" type="audio/mp3" controls="controls" preload="none">		
</audio>
<script>
$('audio,video').mediaelementplayer({features: ['playpause','current','progress','duration','loop','volume'], startVolume: 0.5, autosizeProgress: false});
</script>
HTML;

return $return;
}

// универсальный ф-ция формирования ссылок для проигрывания треков
function playLinkMservice( $file, $time, $hdd ) {
  $hdd = intval($hdd);
	if ( ($hdd !== 2) ) $hdd = '';
	
  $file = strtolower( $file );
  $subpapka = nameSubDir( $time, $hdd );
  $secret = 'Edcdjhs09Sdkvc640';
  //$lifetime = time() + 86400; //ссылка будет рабочей сутки
  $lifetime = time() + 43200;
  $key = str_replace("=", "", strtr(base64_encode(md5($secret.'/played'.$hdd.'/'.$subpapka.'/'.$file .$lifetime.getenv("HTTP_X_REAL_IP"), TRUE)), "+/", "-_"));
  $encoded_url = '/play'.$hdd.'/'.$subpapka.'/'.$key.'/'.$lifetime.'/'.$file ;
  
	return $encoded_url;
}

// универсальный ф-ция формирования ссылок для скачивания треков
function downloadLinkMservice( $file, $name, $artist, $title, $link_time, $subpapka, $hdd ) {
  $hdd = intval($hdd);
	if ( ($hdd !== 2) ) $hdd = '';
	
	$format = strtolower(end( explode( '.', $file ) ) );
	$file = strtolower($file);
  $name  = str_replace( '{A}', mb_convert_case(totranslit2( $artist ), MB_CASE_TITLE, "cp1251"), $name );
	$name  = str_replace( '{T}', mb_convert_case(totranslit2( $title ), MB_CASE_TITLE, "cp1251"), $name );
	$name = $name.'.'.$format;
	// добавить регуляркой стирание всех левых символов кроме букв англ. и чисел -  в $name....., пока качает нормально и без этого...
	$secret = 'Dgedkv8Jd8evt1529';
	$lifetime = time() + $link_time; //ссылка будет рабочей ... секунд
	$key = str_replace("=", "", strtr(base64_encode(md5($secret.'/download'.$hdd.'/'.$subpapka.'/'.$name.'/'.$file .$lifetime.getenv("HTTP_X_REAL_IP"), TRUE)), "+/", "-_"));
	$encoded_url = '/dl'.$hdd.'/'.$subpapka.'/'.$name.'/'.$key.'/'.$lifetime.'/'.$file;
	
	return $encoded_url;
}

// универсальный ф-ция формирования ссылок для скачивания альбомов
function downloadLinkAlbum( $aid, $link_time ) {
	
	$aid = intval($aid);
	// добавить регуляркой стирание всех левых символов кроме букв англ. и чисел -  в $name....., пока качает нормально и без этого...
	$secret = 'Crfxfnm100akm,jvjd500';
	$lifetime = time() + $link_time; //ссылка будет рабочей ... секунд
	$key = str_replace("=", "", strtr(base64_encode(md5($secret.'/adownload/'.$aid .$lifetime.getenv("HTTP_X_REAL_IP"), TRUE)), "+/", "-_"));
	$encoded_url = '/adl/'.$key.'/'.$lifetime.'/'.$aid;
	
	return $encoded_url;
}

// показ рейтинга трека (звёздочки)
function showTrackRating( $id, $rating, $vote_num, $allow = true ) {
	global $lang;

	if( $rating ) $rating = round( ($rating / $vote_num), 0 );
	else $rating = 0;
	$rating = $rating * 17;

	if ( !$allow ) {

		$rated = <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		</ul>
</div>
HTML;

		return $rated;
	}

	$rated = "<span id='ratig-layer-" . $id . "'>";

	$rated .= <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		<li><a href="#" title="{$lang['useless']}" class="r1-unit" onclick="RateTrack('1', '{$id}'); return false;">1</a></li>
		<li><a href="#" title="{$lang['poor']}" class="r2-unit" onclick="RateTrack('2', '{$id}'); return false;">2</a></li>
		<li><a href="#" title="{$lang['fair']}" class="r3-unit" onclick="RateTrack('3', '{$id}'); return false;">3</a></li>
		<li><a href="#" title="{$lang['good']}" class="r4-unit" onclick="RateTrack('4', '{$id}'); return false;">4</a></li>
		<li><a href="#" title="{$lang['excellent']}" class="r5-unit" onclick="RateTrack('5', '{$id}'); return false;">5</a></li>
		</ul>
</div>
HTML;

	$rated .= "</span>";

	return $rated;
}
// показ рейтинга альбома (звёздочки)
function showAlbumRating( $id, $rating, $vote_num, $allow = true ) {
	global $lang;

	if( $rating ) $rating = round( ($rating / $vote_num), 0 );
	else $rating = 0;
	$rating = $rating * 17;

	if ( !$allow ) {

		$rated = <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		</ul>
</div>
HTML;

		return $rated;
	}

	$rated = "<span id='ratig-layer-" . $id . "'>";

	$rated .= <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		<li><a href="#" title="{$lang['useless']}" class="r1-unit" onclick="RateAlbum('1', '{$id}'); return false;">1</a></li>
		<li><a href="#" title="{$lang['poor']}" class="r2-unit" onclick="RateAlbum('2', '{$id}'); return false;">2</a></li>
		<li><a href="#" title="{$lang['fair']}" class="r3-unit" onclick="RateAlbum('3', '{$id}'); return false;">3</a></li>
		<li><a href="#" title="{$lang['good']}" class="r4-unit" onclick="RateAlbum('4', '{$id}'); return false;">4</a></li>
		<li><a href="#" title="{$lang['excellent']}" class="r5-unit" onclick="RateAlbum('5', '{$id}'); return false;">5</a></li>
		</ul>
</div>
HTML;

	$rated .= "</span>";

	return $rated;
}
//  Показ в избранном альбом или нет на странице альбома
function showFavAlbum( $aid ) {
	global $is_logged, $member_id;
	
	if( ! $aid ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
			$favorited = <<<HTML
<div class="fav_alb">
<a href="#" title="Добавить альбом в Избранные альбомы" onclick="AddFavAlbum( '{$aid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_albums_mservice'] );
		
    if (in_array($aid, $list_fav)) {
			$favorited .= <<<HTML
<div class="fav_alb">
<div class="infav"></div>
<a href="#" title="Удалить альбом из Избранных альбомов" onclick="DelFavAlbum( '{$aid}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="fav_alb">
<a href="#" title="Добавить альбом в Избранные альбомы" onclick="AddFavAlbum( '{$aid}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном альбом или нет на страницах со списками альбомов
function ShowFavalbumList( $aid ) {
	global $is_logged, $member_id;
	
	if( ! $aid ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
			$favorited = <<<HTML
<div class="fav_alb"></div>
HTML;
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_albums_mservice'] );
		
    if (in_array($aid, $list_fav)) {
			$favorited .= <<<HTML
<div class="fav_alb">
<div class="infav"></div>
<a href="#" title="Удалить альбом из Избранных альбомов" onclick="DelFavAlbumList( '{$aid}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="fav_alb"><a href="#" title="Добавить альбом в Избранные альбомы" onclick="AddFavAlbumList( '{$aid}' ); return false;">mp3</a></div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном трек или нет на странице трека
function ShowFavorites( $id ) {
	global $is_logged, $member_id;
	
	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrack( '{$id}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
      $list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid)) {
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrack( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrack( '{$id}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
}
//  Показ в избранном трек или нет на всех страницах с треками кроме ...
function ShowFavoritesList( $id ) {
	global $is_logged, $member_id;
	
	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '';
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid)) {
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackList( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackList( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном трек или нет на страницах с TOP24hr
function ShowFavoritesListTop24hr( $id ) {
	global $is_logged, $member_id;
	
	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '';
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid)) {
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTop24hr( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTop24hr( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном трек или нет на страницах с TOPweek
function ShowFavoritesListTopWeek( $id ) {
	global $is_logged, $member_id;
	
	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '';
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid))	{
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopWeek( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopWeek( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном трек или нет на страницах с TOPmonth
function ShowFavoritesListTopMonth( $id ) {
	global $is_logged, $member_id;

	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '';
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid)) 	{
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListTopMonth( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListTopMonth( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в избранном трек или нет на страницах с DownloadsTop
function ShowFavoritesListDownloadsTop( $id ) {
	global $is_logged, $member_id;
	
	if( ! $id ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '';
		return $favorited;
	}

		$list_fav = explode( ",", $member_id['favorites_mservice'] );
		
		foreach ($list_fav as $value) {
		$list_fav_mid[] = substr($value,0,strpos($value,"-"));
		}

		if (in_array($id, $list_fav_mid)) {
			$favorited .= <<<HTML
<div class="unit-rating_fav">
<div class="current-rating"></div>
<a href="#" title="Удалить трек из Избранных mp3" onclick="DelFavTrackListDownloadsTop( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="unit-rating_fav">
<a href="#" title="Добавить трек в Избранные mp3" onclick="AddFavTrackListDownloadsTop( '{$id}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//  Показ в Любимых исполнитель или нет - на странице трека view и странице всех треков исполнителя
function showMyFavArtistsView( $transartist ) {
	global $is_logged, $member_id;
	
	if( ! $transartist ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = <<<HTML
<div class="fav_art">
<a href="#" title="Добавить в любимые исполнители" onclick="AddFavArtView( '{$transartist}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
		return $favorited;
	}

		$list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
		
		if (in_array($transartist, $list_fav_art)) {
			$favorited .= <<<HTML
<div class="fav_art">
<div class="infav"></div>
<a href="#" title="Удалить из любимых исполнителей" onclick="DelFavArtView( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="fav_art">
<a href="#" title="Добавить в любимые исполнители" onclick="AddFavArtView( '{$transartist}','$is_logged' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
}
//  Показ в любимых исполнитель или нет - на странице с myfavartist.html и ...
function ShowMyFavArtists( $transartist ) {
	global $is_logged, $member_id;
	
	if( ! $transartist ) die( "Hacking attempt!" );
	if( ! $is_logged ) {
		$favorited = '<div class="fav_art"></div>';
		return $favorited;
	}

		$list_fav_art = explode( ",", $member_id['favorites_artists_mservice'] );
		
		if (in_array($transartist, $list_fav_art)) {
			$favorited .= <<<HTML
<div class="fav_art">
<div class="infav"></div>
<a href="#" title="Удалить из любимых исполнителей" onclick="DelFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		} else {
			$favorited = <<<HTML
<div class="fav_art">
<a href="#" title="Добавить в любимые исполнители" onclick="AddFavArt( '{$transartist}' ); return false;">mp3</a>
</div>
HTML;
			return $favorited;
		}
	
}
//формирует ссылку на биографию исполнителя
function buildArtistBio( $name, $mscfg, $mscfg2 ) {
  if ( $mscfg == 1 ) return '<a href="'.str_replace( '{artist}', $name, $mscfg2 ).'" target="_blank" title="Просмотреть биографию '.$name.'">'.$name.'</a>';
    else return $name;
}

// для админа: на странице со списком треков рядом с названиеем трека показывает кол-во просмотров и скачиваний треков + ссылку на редактирование трека
function downloadsSong( $mid, $download, $view_count ) {
	global $member_id;
	if ( $member_id['user_group'] == 1 ) { @include (ENGINE_DIR . '/data/config.php');
		return ' <a href="'.$config['http_home_url'].$config['admin_path'].'?mod=mservice&act=trackedit&id='.$mid.'" target="_blank" style="color:#a1a1a1;">('.$view_count.') ('.$download.')</a>';
	}
  else return '';
}

// для админа: на странице просмотра трека показывает ссылку на редактирование трека
function viewEdit( $mid ) {
	global $member_id;
	if ( $member_id['user_group'] == 1 ) { @include (ENGINE_DIR . '/data/config.php');
		return '<tr><td class="adminedit" colspan="2"><a href="'.$config['http_home_url'].$config['admin_path'].'?mod=mservice&act=trackedit&id='.$mid.'" target="_blank">редактирование трека</a></td></tr>';
	}
	else return '';
}

// для админа: на странице просмотра альбома показывает ссылку на редактирование альбома
function viewEditAlbum( $aid ) {
	global $member_id;
	if ( $member_id['user_group'] == 1 ) { @include (ENGINE_DIR . '/data/config.php');
		return '<tr><td class="adminedit" colspan="2"><a href="'.$config['http_home_url'].$config['admin_path'].'?mod=mservice&act=albumedit&aid='.$aid.'" target="_blank">редактирование альбома</a></td></tr>';
	}
	else return '';
}

// для админа: вроде показывает сколько раз искали этот запрос
function countSearch( $count_s ) {
	global $member_id;
	if ( ($member_id['user_group'] == 1) AND ($count_s > 0)) {
		return ' ('.$count_s.')';
	}
	else return '';
}

// // для админа: на странице со списком треков рядом с названиеем трека показывает кол-во скачиваний (прослушиваний)  в своём Топе, просмотров и скачиваний треков всего + ссылку на редактирование трека
function downloadsSongDownloadTop( $mid, $download, $view_count, $cnt ) {
	global $member_id;
	if ( $member_id['user_group'] == 1 ) { @include (ENGINE_DIR . '/data/config.php');
		return ' ('.$cnt.') <a href="'.$config['http_home_url'].$config['admin_path'].'?mod=mservice&act=trackedit&id='.$mid.'" target="_blank" style="color:#a1a1a1;">('.$view_count.') ('.$download.')</a>';
	}
	else return '';
}

// классная ф-ция формирует нормальное употребление слова при использовании с числом (пример: трек, трека, треков) http://webersoft.ru/function-of-declination-of-words/
function declension($digit,$expr,$onlyword=false)
     {
         if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
         if(empty($expr[2])) $expr[2]=$expr[1];
         $i=preg_replace('/[^0-9]+/s','',$digit)%100;
         if($onlyword) $digit='';
         if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
         else
         {
             $i%=10;
             if($i==1) $res=$digit.' '.$expr[0];
             elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
             else $res=$digit.' '.$expr[2];
         }
         return trim($res);
}

// удаляет почти все ссылки при массовом добавлении (перед шагом 2) + исправляет некоторые символы и удаляет двойные пробелы и т.д.
function parseLinks( $text, $mscfg ) {
		if ( $mscfg == 1 ) {
			$text = preg_replace( "/(^|[\n ])\({0,1}([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)\){0,1}/is", "", $text );
			$text = preg_replace( "/(^|[\n ])\({0,1}([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)\){0,1}/is", "", $text );
			$text = preg_replace("/\s{2,}/",' ', $text);
			
			$patterns = array();
      $patterns[1] = '/\.\(www\.agr\.moy\.su\)/i';
      $patterns[2] = '/soundvor\.ru/i';
      $patterns[3] = '/solovey\.su/i';
      $patterns[4] = '/vk\.com\_soundvor/i';
      $patterns[5] = '/vk\.com\/soundvor/i';
      $patterns[6] = '/http\s\_soundvor\.ru\_/i';
      //$patterns[7] = '/\s\-/';
      $patterns[8] = '/kibergrad\.com/i';
      $patterns[9] = '/\s\(Prime-Music\.net\)/i';
      $patterns[10] = '/Prime\sMusic/i';
      $patterns[11] = '/zaycevi\.net/i';
      $patterns[12] = '/Prime\sMusic/i';
      $patterns[13] = '/\[Новый\sРэп\]/i';
      
			$text = preg_replace( $patterns, "", $text );
			//echo $text.'<br />';
			$patterns = array();
      $patterns[0] = '/\`/';
      $patterns[1] = '/\’/';
      $patterns[2] = '/\"/';
      $patterns[3] = '/&quot;/';
      //$patterns[4] = '/&#039;/';
      $patterns[5] = '/\“/';
      $patterns[6] = '/\”/';
      $patterns[7] = '/\‘/';
			$text = preg_replace( $patterns, "'", $text );
		}
		return trim( $text );
}

// удаляет двойные пробелы, неправильные кавычки
function parseLinksAdmin( $text ) {
			$text = preg_replace("/\s{2,}/",' ', $text);
			$patterns = array();
      $patterns[0] = '/\`/';
      $patterns[1] = '/\’/';
      $patterns[2] = '/\"/';
      $patterns[3] = '/&quot;/';
      //$patterns[4] = '/&#039;/';
      $patterns[5] = '/\“/';
      $patterns[6] = '/\”/';
      $patterns[7] = '/\‘/';
			$text = preg_replace( $patterns, "'", $text );
		return trim( $text );
}

// срезает лишние символы при слишком длинном названии исполнителя
function smartphoneStrlen( $text, $mscfg ) {
	if (strlen($text) > (1.4*$mscfg)) $text = substr($text, 0, (1.4*$mscfg) ).'...';
	return $text;
}

// срезает лишние символы при слишком длинном названии исполнителя
function artistTitleStrlen( $artist, $title, $mscfg ) {
	if ( (strlen($artist) > (2*$mscfg)) && (strlen($title) > (2*$mscfg)) ) $artist_title = '<b>'.substr($artist, 0, (2*$mscfg) ).'...</b> - '.substr($title, 0, (2*$mscfg) ).'...';
	elseif ( strlen($artist) > (2*$mscfg) ) $artist_title = '<b>'.substr($artist, 0, (2*$mscfg) ).'...</b> - '.$title;
	elseif ( strlen($title) > (2*$mscfg) ) $artist_title = '<b>'.$artist.'</b> - '.substr($title, 0, (2*$mscfg) ).'...';
	else $artist_title = '<b>'.$artist.'</b> - '.$title;
	
	return $artist_title;
}

// срезает лишние символы при слишком длинном названии исполнителя (короткая версия)
function artistTitleStrlenShort( $artist, $title ) {
	if ( (strlen($artist) > 30) && (strlen($title) > 30) ) $artist_title = '<b>'.substr($artist, 0, 30 ).'...</b> - '.substr($title, 0, 30 ).'...';
	elseif ( strlen($artist) > 30 ) $artist_title = '<b>'.substr($artist, 0, 30 ).'...</b> - '.$title;
	elseif ( strlen($title) > 30 ) $artist_title = '<b>'.$artist.'</b> - '.substr($title, 0, 30 ).'...';
	else $artist_title = '<b>'.$artist.'</b> - '.$title;
	
	return $artist_title;
}

// срезает лишние символы при слишком длинном названии исполнителя и альбома (на страницах альбомов)
function artistAlbumStrlen( $text ) {
	if ( strlen( $text ) > 50) $text = substr( $text, 0, 50 )."...";
	return $text;
}
// добавляет '+ клип' к названию трека в списках треков
function clipOnline( $clip_online ) {
  if ($clip_online != '') $plusclip = ' <font color="#F49804">+ клип</font>';
  else $plusclip = '';
  return $plusclip;
}

// замена стандартным ф-циям от ДЛЕ, т.к. они почему-то не срабатывают для mservice
function dle_cache2($prefix, $cache_id = false) {
  if( ! $cache_id ) $filename = ENGINE_DIR."/cache/".$prefix.".tmp";
  else {
    $cache_id = totranslit( $cache_id );
    $filename = ENGINE_DIR."/cache/".$prefix."_".$cache_id.".tmp";
  }
	return @file_get_contents( $filename );
}

function create_cache2($prefix, $cache_text, $cache_id = false) {
  if( ! $cache_id ) $filename = ENGINE_DIR."/cache/".$prefix.".tmp";
  else {
    $cache_id = totranslit( $cache_id );
    $filename = ENGINE_DIR."/cache/".$prefix."_".$cache_id.".tmp";
  }

	$fp = fopen( $filename, 'wb+' );
	fwrite( $fp, $cache_text );
	fclose( $fp );
	
	@chmod( $filename, 0666 );
}

function totranslit2($var, $lower = true, $punkt = true) {

	if ( is_array($var) ) return "";

	$NpjLettersFrom = "абвгдезиклмнопрстуфцыіґ";
	$NpjLettersTo = "abvgdeziklmnoprstufcyig";
	$NpjBiLetters = array ("й" => "j", "ё" => "e", "ж" => "zh", "х" => "x", "ч" => "ch", "ш" => "sh", "щ" => "shh", "э" => "ye", "ю" => "yu", "я" => "ya", "ъ" => "", "ь" => "", "ї" => "yi", "є" => "ye" );
	
	$NpjCaps = "АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЪЫЭЮЯЇЄІҐ";
	$NpjSmall = "абвгдеёжзийклмнопрстуфхцчшщьъыэюяїєіґ";
	
	$var = str_replace( ".php", "", $var );
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", "-", $var );
	$var = strtr( $var, $NpjCaps, $NpjSmall );
	$var = strtr( $var, $NpjLettersFrom, $NpjLettersTo );
	$var = strtr( $var, $NpjBiLetters );
	
	if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '_', $var );

	if ( $lower ) $var = strtolower( $var );
	
	if( strlen( $var ) > 200 ) {
		
		$var = substr( $var, 0, 200 );
		
		if( ($temp_max = strrpos( $var, '_' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}
// конец замена стандартным ф-циям от ДЛЕ

//для исправления текста, введённого ошибочной раскладкой клавиатуры
function textSwitch( $text,$arrow = 0 ){
    $str[0] = array('й' => 'q', 'ц' => 'w', 'у' => 'e', 'к' => 'r', 'е' => 't', 'н' => 'y', 'г' => 'u', 'ш' => 'i', 'щ' => 'o', 'з' => 'p', 'х' => '[', 'ъ' => ']', 'ф' => 'a', 'ы' => 's', 'в' => 'd', 'а' => 'f', 'п' => 'g', 'р' => 'h', 'о' => 'j', 'л' => 'k', 'д' => 'l', 'ж' => ';', 'э' => '\'', 'я' => 'z', 'ч' => 'x', 'с' => 'c', 'м' => 'v', 'и' => 'b', 'т' => 'n', 'ь' => 'm', 'б' => ',', 'ю' => '.', '`' => 'ё', 'Й' => 'Q', 'Ц' => 'W', 'У' => 'E', 'К' => 'R', 'Е' => 'T', 'Н' => 'Y', 'Г' => 'U', 'Ш' => 'I', 'Щ' => 'O', 'З' => 'P', 'Х' => '{', 'Ъ' => '}', 'Ф' => 'A', 'Ы' => 'S', 'В' => 'D', 'А' => 'F', 'П' => 'G', 'Р' => 'H', 'О' => 'J', 'Л' => 'K', 'Д' => 'L', 'Ж' => ':', 'Э' => '"', 'Я' => 'Z', 'Ч' => 'X', 'С' => 'C', 'М' => 'V', 'И' => 'B', 'Т' => 'N', 'Ь' => 'M', 'Б' => '<', 'Ю' => '>', '~' => 'Ё');
    $str[1] = array (  'q' => 'й', 'w' => 'ц', 'e' => 'у', 'r' => 'к', 't' => 'е', 'y' => 'н', 'u' => 'г', 'i' => 'ш', 'o' => 'щ', 'p' => 'з', '[' => 'х', ']' => 'ъ', 'a' => 'ф', 's' => 'ы', 'd' => 'в', 'f' => 'а', 'g' => 'п', 'h' => 'р', 'j' => 'о', 'k' => 'л', 'l' => 'д', ';' => 'ж', '\'' => 'э', 'z' => 'я', 'x' => 'ч', 'c' => 'с', 'v' => 'м', 'b' => 'и', 'n' => 'т', 'm' => 'ь', ',' => 'б', '.' => 'ю', 'ё' => '`', 'Q' => 'Й', 'W' => 'Ц', 'E' => 'У', 'R' => 'К', 'T' => 'Е', 'Y' => 'Н', 'U' => 'Г', 'I' => 'Ш', 'O' => 'Щ', 'P' => 'З', '{' => 'Х', '}' => 'Ъ', 'A' => 'Ф', 'S' => 'Ы', 'D' => 'В', 'F' => 'А', 'G' => 'П', 'H' => 'Р', 'J' => 'О', 'K' => 'Л', 'L' => 'Д', ':' => 'Ж', '"' => 'Э', 'Z' => 'Я', 'X' => 'Ч', 'C' => 'С', 'V' => 'М', 'B' => 'И', 'N' => 'Т', 'M' => 'Ь', '<' => 'Б', '>' => 'Ю', 'Ё' => '~');
    return strtr($text,isset( $str[$arrow] )? $str[$arrow] :array_merge($str[0],$str[1]));
}
//теперь тут стираем все ненужные(неиндексированные) слова
function textClear( $text ){
  $str = array(' ft ' => ' ', ' feat ' => ' ', ' ft. ' => ' ', ' feat. ' => ' ', ' and ' => ' ', ' vs ' => ' ', '\'' => ' ', ' vs. ' => ' ', '(2000)' => ' ', '(2001)' => ' ', '(2002)' => ' ', '(2003)' => ' ', '(2004)' => ' ', '(2005)' => ' ', '(2006)' => ' ', '(2007)' => ' ', '(2008)' => ' ', '(2009)' => ' ', '(2010)' => ' ', '(2011)' => ' ', '(2012)' => ' ', '(2013)' => ' ', '(2014)' => ' ', '(2015)' => ' ', '(2016)' => ' ', '(2017)' => ' ', '(2018)' => ' ', '(2019)' => ' ', '(2020)' => ' ', ' with ' => ' ');
  return strtr($text, $str);
}
//
function show_size_cover($size)  { 
  if($size<=1024) return $size.' bytes'; 
  else if($size<=1024*1024) return round($size/(1024),2).' Kb'; 
  else if($size<=1024*1024*1024) return round($size/(1024*1024),2).' Mb'; 
  else if($size<=1024*1024*1024*1024) return round($size/(1024*1024*1024),2).' Gb'; 
  else if($size<=1024*1024*1024*1024*1024) return round($size/(1024*1024*1024*1024),2).' Tb'; //:))) 
  else return round($size/(1024*1024*1024*1024*1024),2).' Pb'; // ;-) 
}

function musicGenre($sqlgenre) {
  $genreid = intval($sqlgenre);
  
  if ($genreid == '1') {$genre1 = 'pop'; $genre = 'Pop';}
  elseif ($genreid == '2') {$genre1 = 'club'; $genre = 'Club';}
  elseif ($genreid == '3') {$genre1 = 'rap'; $genre = 'Rap';}
  elseif ($genreid == '4') {$genre1 = 'rock'; $genre = 'Rock';}
  elseif ($genreid == '5') {$genre1 = 'shanson'; $genre = 'Шансон';}
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
  
  return array($genre1, $genre);
}
?>