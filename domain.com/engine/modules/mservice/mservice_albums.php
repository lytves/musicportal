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

// Просмотр альбома и его треков
case 'album' :

$aid = intval( $_REQUEST['aid'] );
$row = $db->super_query( "SELECT a.aid, a.time, a.year as year_alb, a.album, a.artist, a.approve, a.image_cover, a.collection, a.uploader, a.genre, a.is_notfullalbum, a.rating, a.download, a.vote_num, a.view_count, a.description, a.transartist, u.name FROM ".PREFIX."_mservice_albums a LEFT JOIN ".PREFIX."_users u ON a.uploader = u.user_id WHERE aid = '$aid' AND approve = '1'" );

$artist = $row['artist'];
$transartist = $row['transartist'];
$album = $row['album'];

if ( $row['aid'] == FALSE ) {
	$stop[] = 'Запрашиваемый альбом не найден. Возможно он ещё не прошёл модерацию, либо его никогда и не существовало, либо он уже удалён! <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';
	$nam_e2 = $nam_e1 = 'domain.com';
	}
else {
  if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) {
    $year_alb = '';
    $year_alb1 = '';
    $year_alb_meta = ',';
  } else {
    $year_alb = ' ('.$row['year_alb'].')';
    $year_alb1 = $row['year_alb'];
    $year_alb_meta = ', '.$row['year_alb'];
    
    $temptime = date('Y', time());
    if ( ($row['year_alb'] == $temptime) OR ($row['year_alb'] == $temptime+1) ) {$year_alb3 = 'Новый '; $year_alb4 = 'нового ';}
  }

	if ( strlen( $nam_e2 = $year_alb3.'Альбом '.$row['artist'].' - '.$row['album'].$year_alb ) > 75 ) $nam_e1 = substr( $nam_e2, 0, 75 ).'...'; else $nam_e1 = $nam_e2;
}
  
if ( count( $stop ) == 0 ) {
  $genreid = $row['genre'];
  if ( $genreid == '1') {$alb_genre = 'Pop'; $genre1 = 'pop'; $alb_genre_meta = ', Pop';}
  elseif ($genreid == '2') {$alb_genre = 'Club'; $genre1 = 'club'; $alb_genre_meta = ', Club';}
  elseif ($genreid == '3') {$alb_genre = 'Rap'; $genre1 = 'rap'; $alb_genre_meta = ', Rap';}
  elseif ($genreid == '4') {$alb_genre = 'Rock'; $genre1 = 'rock'; $alb_genre_meta = ', Rock';}
  elseif ($genreid == '5') {$alb_genre = 'Шансон'; $genre1 = 'shanson'; $alb_genre_meta = ', Шансон';}
  elseif ($genreid == '6') {$alb_genre = 'OST'; $genre1 = 'ost'; $alb_genre_meta = ', OST';}
  elseif ($genreid == '7') {$alb_genre = 'Metal'; $genre1 = 'metal'; $alb_genre_meta = ', Metal';}
  elseif ($genreid == '8') {$alb_genre = 'Country'; $genre1 = 'country'; $alb_genre_meta = ', Country';}
  elseif ($genreid == '9') {$alb_genre = 'Classical'; $genre1 = 'classical'; $alb_genre_meta = ', Classical';}
  elseif ($genreid == '10') {$alb_genre = 'Punk'; $genre1 = 'punk'; $alb_genre_meta = ', Punk';}
  elseif ($genreid == '11') {$alb_genre = 'Soul/R&B'; $genre1 = 'soul/r-and-b'; $alb_genre_meta = ', Soul/R&B';}
  elseif ($genreid == '12') {$alb_genre = 'Jazz'; $genre1 = 'jazz'; $alb_genre_meta = ', Jazz';}
  elseif ($genreid == '13') {$alb_genre = 'Electronic'; $genre1 = 'electronic'; $alb_genre_meta = ', Electronic';}
  elseif ($genreid == '14') {$alb_genre = 'Indie'; $genre1 = 'indie'; $alb_genre_meta = ', Indie';}
  else {$alb_genre = ''; $genreid = ''; $genre1 = '';}
  
    $metatags['title'] = $nam_e2.' cкачать бесплатно, слушать альбом онлайн, скачать все mp3 треки '.$row['artist'].', все новые мп3 песни на domain.com';
  $metatags['description'] = 'Скачать бесплатно '.$nam_e2.', слушать альбом онлайн'.$alb_genre_meta.', скачать все mp3 песни '.$row['artist'].$year_alb_meta.' дискография, новый альбом одним файлом, zip-архив, все новые мп3 треки на '.$config['description'];
  $metatags['keywords'] = 'скачать '.$nam_e2.' mp3, слушать онлайн, new, все музыкальные треки, дискография '.$row['artist'].' mp3, новый альбом'.$alb_genre_meta.', музыкальный архив, все песни'.$year_alb_meta.', скачать мп3 бесплатно, музыка без регистрации, все хиты '.$row['artist'].' бесплатно, торрент, одним файлом, zip-архив';

  $db->query( "UPDATE ".PREFIX."_mservice_albums SET view_count = view_count + 1 WHERE aid = '$aid'" );
  
  if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
  $upl_name = '<a href="/user/'.urlencode($row['name']).'">'.$row['name'].'</a>';
  $art_alb = '<a href="'.$config['http_home_url'].'music/artistracks-'.$transartist.'.html" title="'.$row['artist'].' скачать все mp3 бесплатно">'.$row['artist'].'</a>';
  $edit_alb = viewEditAlbum( $aid );
  $rating_album = showAlbumRating( $aid, $row['rating'], $row['vote_num'], 1 );
  $favartist_album = showMyFavArtistsView( $transartist );
  $favalbum = showFavAlbum( $aid );
  
  if ($alb_genre !== '') $alb_genre_tab = '<tr><td><img src="{THEME}/images/genre.png" align="top" /> &nbsp; Жанр: <a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$alb_genre.'</a></td></tr>';
  
  if ($row['is_notfullalbum'] == '1') $is_notfullalbum = '<div class="tit_green" style="margin:15px 0;">В альбоме представлены ещё не все треки!</div>';
  else $is_notfullalbum = '';
  
  if ($row['description'] == '') $desc_alb = '';
  else $desc_alb = '<div class="line"></div><table width="100%"><tr><td style="padding-top:3px;" width="100%"><img src="{THEME}/images/comment.png" align="top" /> &nbsp; <b>Описание альбома:</b></td></tr><tr><td>'.$row['description'].'</td></tr></table>';
  
  $mtitle .= '<div class="album_tracks_ramka"><h1 class="tit" style="background:#FBDB85;">'.$year_alb3.'Альбом <span>'.$row['artist'].' - '.$row['album'].'</span>'.$year_alb.'</h1>
<div class="mservice_viewtrack">
<center>На этой странице Вы можете бесплатно скачать и слушать онлайн в хорошем качестве все mp3 треки<br />из '.$year_alb4.'альбома <b>'.$row['artist'].' - '.$row['album'].$year_alb.'</b></center>
<div class="line" style="margin:5px 0;"></div>';
  $mtitle .= <<<HTML
<script type="text/javascript">
function RateAlbum( rate, aid ) {
	var ajax = new dle_ajax();
	ajax.onShow ('');
	var varsString = "go_rate=" + rate;
	ajax.setVar( "aid", aid );
	ajax.setVar( "skin", dle_skin );
	ajax.requestFile = dle_root + "engine/modules/mservice/ajax-rating-album.php";
	ajax.method = 'GET';
	ajax.element = 'ratig-layer-' + aid;
	ajax.sendAJAX(varsString);
}
function AddFavAlbum( aid,is_logged ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '30' );
ajax.setVar( "aid", aid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'fav-album-layer';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('fav-album-layer').style.display = 'block' );

if (is_logged == 1 ) $('#message_alert').text("Альбом добавлен в Избранные альбомы!");
else $('#message_alert').html("Зарегистрируйтесь или войдите в аккаунт<br />для добавления альбомов в Избранные!");

  var coor = ($(window).width()-1000)/2;
  $("#fav-album-layer").click(function(e){
    $("#message_alert").css({'top': (e.pageY-60)+'px', 'left': (e.pageX+20-coor)+'px'});
    $("#message_alert").css('display','block');
    $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
  });
}
function DelFavAlbum( aid ) {
var ajax = new dle_ajax();
ajax.onShow ( '' );
var varsString = "";
ajax.setVar( "act", '31' );
ajax.setVar( "aid", aid );
ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
ajax.method = 'POST';
ajax.element = 'fav-album-layer';
ajax.sendAJAX(varsString);
ajax.onCompletion ( document.getElementById('fav-album-layer').style.display = 'block' );

  $('#message_alert').text("Альбом удалён из Избранных альбомов!");
  var coor = ($(window).width()-1000)/2;
  $("#favorited-tracks-layer").click(function(e){
    $("#message_alert").css({'top': (e.pageY-60)+'px', 'left': (e.pageX+20-coor)+'px'});
    $("#message_alert").css('display','block');
    $("#message_alert").fadeTo(100,0.9).delay(6000).fadeOut(100);  
  });
}
</script>
<table width="100%" style="margin:10px 0 0;">
<tr><td width="160" valign="top"><img src="/uploads/albums/{$img_album}" class="track_logo" alt="бесплатно скачать альбом {$row['artist']} - {$row['album']}" title="скачать бесплатно альбом {$row['artist']} - {$row['album']}" /></td>
<td><table width="100%" cellspacing="3" cellpadding="3" border="0">
<tr><td style="padding-top:3px;"><img src="{THEME}/images/artist.png" align="top" /> &nbsp; Исполнитель: <strong>{$art_alb}</strong></td></tr>
<tr><td><img src="{THEME}/images/category.png" align="top" /> &nbsp; Альбом: <strong>{$row['album']}</strong></td></tr>
<tr><td><img src="{THEME}/images/calendar.png" align="top" /> &nbsp; Год выхода: <strong>{$year_alb1}</strong></td></tr>
{$alb_genre_tab}
<tr><td style="padding-top:3px;"><table><tr><td><img src="{THEME}/images/best20.png" align="top" /> &nbsp; Рейтинг: &nbsp;</td><td> {$rating_album}</td></tr></table></td></tr>
<tr><td style="padding-top:3px;"><!--<img src="{THEME}/images/uploader.png" align="top" /> &nbsp; Загрузил: {$upl_name}--></td></tr>
<tr><td style="padding-top:3px;"><table width="100%"><tr><td width="55%"><img src="{THEME}/images/views.png" align="top" /> &nbsp; Просмотров: {$row['view_count']}</td><td style="padding-left:3px;"><img src="{THEME}/images/downloads.png" align="top" /> &nbsp; Скачиваний: {$row['download']}</td></tr></table></td></tr>
<tr><td style="padding-top:3px;" colspan="2"><div id="album-size"></div></td></tr>

{$edit_alb}
<tr><td height="10"><div class="line" style="margin-top:10px;"></div></td></tr>
<tr><td style="padding-top:3px;"><table width="100%" height="35"><tr><td><img src="{THEME}/images/newtrack.png" align="top" /></td><td width="10"></td><td style="white-space:nowrap">Избранные альбомы:</td><td width="30"><div id="fav-album-layer">{$favalbum}</div></td><td width="100"></td>

<td><img src="{THEME}/images/favart.png" align="top" /></td><td width="5"></td><td style="white-space:nowrap">Любимый исполнитель:</td><td width="30"><div id="favart-artist">{$favartist_album}</div></td></tr></table>

<tr><td height="10"></td></tr><tr><td colspan="2" align="middle"><table><tr><td>

</td><td width="20"></td><td>

</td></tr></table>
</td></tr>

<tr><td height="10"><div class="line" style="margin-top:10px;"></div></td></tr>
<tr><td colspan="2" align="center" height="40"><span style="color:#3367AB; background:#FBDB85; padding:5px; font-size:18px; font-family:Tahoma; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;">&nbsp;Поделиться ссылкой на альбом с друзьями:&nbsp;</span>
</td></tr>
<tr><td style="padding-top:3px;" colspan="2">
<div id="message_alert"></div>
<noindex><center>
<div class="share42init"></div>
<script type="text/javascript" src="http://domain.com/engine/classes/js/share42.js"></script>
</center></noindex>
</td></tr></table></td></tr></table>
{$desc_alb}
</div>
HTML;

if ($row['collection']) $collection_mids = " OR mid IN(".$row['collection'].")";
else $collection_mids = "";

  unset($row);

  $db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE approve = '1' AND album = '$aid'$collection_mids ORDER BY artist, title LIMIT 0,50");
  $album_count_tracks = $db->num_rows();
  
  if ( $db->num_rows() > 0 ) {
  $mcontent .= '<h3 class="tit" style="margin-top:10px;">Треки из альбома <span>'.$album.'</span> ('.declension($album_count_tracks, array('трек', 'трека', 'треков')).')</h3>';
  $mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
<th width="5%">&nbsp;</th>
<th width="5%">&nbsp;</th>
</tr>
<tr>
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
    
  $album_size = $album_size + $row['size'];
      
  $tpl->compile( 'tracklist' );
  $mcontent .= $tpl->result['tracklist'];
  $tpl->result['tracklist'] = FALSE;
  }
  $mcontent .= '</table></div>';
  $mcontent .= $is_notfullalbum;

  $format_album_size = formatsize($album_size);
  if ( $format_album_size > 0) $mcontent .= <<<HTML
<script type="text/javascript">
showAlbumSize('{$format_album_size}','{$THEME}');
</script>
HTML;

  //ещё треки исполнителя
  $mcontent .= '<div class="alltra" style="margin:5px 10px;"><a href="/music/artistracks-'.$transartist.'.html" title="Смотреть и скачать все мп3 треки '.$artist.'">Смотреть все mp3 треки '.$artist.'</a></div><div style="clear:both;"></div>';

  $mcontent .= '<div class="line" style="margin-top:10px;"></div>';

if( $config['allow_banner'] ) {
  include_once ENGINE_DIR . '/modules/banners.php';
  $mcontent .= $banners['view-download'];
}
/// Создание ссылки на скачивание, регистрацию или переход в VIP группу
  if ( $is_logged AND $album_count_tracks < 2 ) $mcontent .= '';
  elseif ( $is_logged AND $album_count_tracks > 1 ) {
  
    if ($member_id['user_group'] == '7')  $mcontent .= '<div class="mservice_viewtrack"><center><a href="'.downloadLinkAlbum( $aid, $mscfg["link_time"] ).'" style="hover text-decoration:underline;" title="Скачать альбом '.$artist.' - '.$album.$year_alb.'"><img src="{THEME}/images/download_save.png" alt="download mp3, скачать mp3" style="display:block;"><b>Скачать альбом '.$artist.' - '.$album.$year_alb.'</b></a></center></div>';
  
    else {
      $row_userid = $db->super_query( "SELECT COUNT(DISTINCT aid) as cnt FROM ".PREFIX."_mservice_downloads_albums WHERE (user_id = '".$member_id['user_id']."' AND TIME > NOW( ) - INTERVAL 24 HOUR)");

      if ($row_userid['cnt'] >= $mscfg['24hr_album_limit']) $mcontent .= '<div class="album_tracks_ramka" style="border:2px solid #f97070; margin:10px 0; padding:10px; text-align:center;">Достигнут суточный лимит на скачивание 5 музыкальных альбомов. Для снятия лимита, а также для отключения рекламы воспользуйтесь возможностью перехода в группу <span style="color:#ff00b2">VIP Посетители</span>.<a href="/vip_group.html" class="reg_inf" title="Отключить рекламу и перейти в группу VIP Посетители!" style="font-family:Tahoma; font-size:18px; text-align:center; padding:10px 30px;" onMouseOver="this.style.background=\'#EF5151\';this.style.color=\'#ffffff\'" onMouseOut="this.style.background=\'#f7a8a8\';this.style.color=\'#3367ab\'">Отключить рекламу и перейти в группу VIP Посетители!</a></div>';
      else $mcontent .= '<div class="mservice_viewtrack"><center><a href="'.downloadLinkAlbum( $aid, $mscfg["link_time"] ).'" style="hover text-decoration:underline;" title="Скачать альбом '.$artist.' - '.$album.$year_alb.'"><img src="{THEME}/images/download_save.png" alt="download mp3, скачать mp3" style="display:block;"><b>Скачать альбом '.$artist.' - '.$album.$year_alb.'</b></a></center></div>';
    }
  }
  else $mcontent .= '<div class="mservice_viewtrack">
<div class="reg_inf" style="font-family:Tahoma; font-size:18px;">Скачивание музыкального альбома одним файлом (zip-архивом) бесплатно, по прямой ссылке, на максимальной скорости и без ожидания доступно только зарегистрированным пользователям!</div><div style="clear:both;"></div>
<div class="line"></div>
<div style="min-height:30px; line-height:30px; display:block; padding:5px 10px; background:#FBDB85; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; font-weight:bold; overflow:hidden; float:left; margin:10px 0; font-family:Tahoma; font-size:18px;">Зарегистрируйся <span style="color:#3367AB;">(или авторизируйся через любимую Социальную Сеть)</span> за несколько секунд и сможешь:</div><div style="clear:both;"></div>
<li style="padding-left:15px;">скачивать файлы сразу со страницы просмотра трека</li>
<li style="padding-left:15px;">скачивать музыкальные альбомы одним файлом (zip-архивом)</li>
<li style="padding-left:15px;">добавлять свои mp3 файлы на сайт</li>
<li style="padding-left:15px;">комментировать треки под своим именем</li>
<li style="padding-left:15px;">добавлять музыкальные треки в избранные mp3, альбомы в избранные альбомы и исполнителей в любимые</li>

<a href="#virtual_loginform" class="lbn" style="margin:10px 0 10px 270px;" rel="facebox"><b style="background-position: 100% -215px; padding: 0 10px;">Вход / Регистрация</b></a><div style="clear:both;"></div>
</div>';
  } else {
    $mcontent .= '<h3 class="tit" style="margin-top:10px;">Треки из альбома <span>'.$album.'</span> ('.declension($album_count_tracks, array('трек', 'трека', 'треков')).')</h3>';
    $mcontent .= '<div class="tit_green" style="margin:15px 0;">В альбоме ещё нет треков! Треки будут добавлены в ближайшее время!</div>';
    $mcontent .= '<div class="alltra" style="margin:5px 10px;"><a href="/music/artistracks-'.$transartist.'.html" title="Смотреть и скачать все мп3 треки '.$artist.'">Смотреть все mp3 треки '.$artist.'</a></div><div style="clear:both;"></div>';
  }
  // ещё альбомы если есть
  unset($row);
  $row = $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE artist = '$artist' AND aid != '$aid' AND approve = '1' ORDER BY year_alb DESC, album LIMIT 2" );
  
  if ( $db->num_rows() > 0 ) {
    $mcontent .= '<div class="album_tracks_ramka" style="margin:10px 0;"><div class="album_tracks_tit">Ещё альбомы <font color="#3367AB">'.$artist.'</font></div><table width="100%"><tr>';
     while( $row = $db->get_row() ) {
      $genreid = $row['genre'];
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
        
      if ($genre1 !== '') $alb_genre_tab = '<div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a>';
      else $alb_genre_tab = '';
        
      if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
      else $year_alb = '('.$row['year_alb'].')';
      if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
      $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.$row['artist'].'</b><br />'.$row['album'].'<br />'.$year_alb.'</a>'.$alb_genre_tab.'</td>';
      if ($db->num_rows() == 1) $mcontent .= '<td width="50%" valign="top"></td>';
     }

     $mcontent .= '</tr><tr><td colspan="2"><div class="allalb" style="margin:15px 0 0;"><a href="/music/albums-'.$transartist.'.html" title="Смотреть и скачать все альбомы '.$artist.'">Смотреть все альбомы '.$artist.'</a></div><div style="clear:both;"></div></td></div>';
     $mcontent .= '</tr></table></div>';
  }

  $tpl->load_template( 'mservice/commentit.tpl' );
  $tpl->compile( 'commentit' );
  $mcontent .= $tpl->result['commentit'];
}
$db->free( );
break;

// Просмотр всех альбомов исполнителя
case 'albums' :

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
if (($limit = ( $page * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']) < 0 ) {
  $limit = 0;
  $page = 1;
}
if ( $page == 1 ) $metapage = '';
else $metapage = ' (страница '.$page.') ';

$nameartist = $parse->process( $_REQUEST['nameartist'] );

$db->query( "SELECT DISTINCT artist FROM ".PREFIX."_mservice_albums WHERE transartist = '$nameartist' AND approve ='1'" );

if ( $db->num_rows( ) == 0 ) {
	$metatags['title'] = 'Нет альбомов этого исполнителя на '.$config['home_title_short'];
	$stop[] = 'К сожалению, на нашем сайте ещё нет альбомов этого исполнителя. <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';
} else {
	while( $row = $db->get_row( ) ) $query_artist[] = $row['artist'];
	
	$artist1 = implode(", ",$query_artist); 
	$addslashesartist = addslashes($query_artist[0]);
	
	if (count($query_artist) > 1 ) {
    for($i = 1; $i < count($query_artist); $i++) {
      $artist3.= " OR artist = '".addslashes($query_artist[$i])."'";
    }
  }

  if ( strlen( $artist1 ) < 41 )  $nam_e1 = $artist1;
  elseif ( strlen( $artist1 ) < 50 )  $nam_e1 = substr( $artist1, 0, 55 );
  else  $nam_e1 = substr( $artist1, 0, 50 ).'...';

  $metatags['title'] = 'Все альбомы '.$artist1.' - cкачать бесплатно, альбом слушать онлайн'.$metapage.', дискография, все новые песни на '.$config['home_title_short'];
  $metatags['description'] = 'Все альбомы '.$artist1.' - cкачать бесплатно, новый альбом, все песни, все новые треки, дискография, zip-архив, слушать онлайн на '.$config['description'];
  $metatags['keywords'] = 'все альбомы, бесплатно скачать '.$artist1.' mp3, новый альбом, слушать онлайн, музыкальный архив, все песни, new, бесплатно скачать все альбомы '.$artist1.', дискография, скачать мп3 бесплатно, новинка, музыка без регистрации, все хиты mp3 бесплатно, торрент, zip-архив';

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'album, artist';
  $sortactive = 'По названию альбома &#8595;';
  $sortactive_no = '<li><a href="/music/albums-'.$nameartist.'.html">По году выхода &#8593;</a></li>';
}
else {
  $sqlsort = 'year DESC, artist, album';
  $sortactive = 'По году выхода &#8593;';
  $sortactive_no = '<li><a href="/music/albums-'.$nameartist.'/sort-1.html">По названию альбома &#8595;</a></li>';
}

  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE artist = '$addslashesartist'$artist3 AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['album_page_lim']);
  
  if ( $db->num_rows() == 0 ) $stop[] = 'Неверная страница с альбомами '.$artist1.'! <a href="/music/albums-'.$nameartist.'.html">Вернуться на первую страницу c альбомами '.$artist1.'.</a>';
  if ( count( $stop ) == 0 ) {
    $i = 1;
    if ( $db->num_rows() > 0 ) {
      $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><h1 class="album_tracks_tit">Все альбомы <font color="#3367AB">'.$artist1.'</font></h1>
      <center>На этой странице представлены все альбомы <b>'.$artist1.'</b> на нашем сайте</center>
<div class="line" style="margin:5px 0;"></div>';
      
      if ( $db->num_rows() > 1 ) $mcontent .= '<dl id="sample" class="dropdown_alb">
        <dt><span>'.$sortactive.'</span></dt>
        <dd>
            <ul>
              '.$sortactive_no.'
            </ul>
        </dd>
    </dl>';
    
    $mcontent .= '<table width="100%" style="margin:10px 0 0;"><tr>';
    
      while( $row = $db->get_row() ) {
        $genreid = intval($row['genre']);
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
        
        if ($genre1 !== '') $alb_genre_tab = '<a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div>';
        else $alb_genre_tab = '';
          
        if (!$cnt_alb) $cnt_alb = $row['cnt'];
        if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row['year_alb'].')';
        if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
        $i++;
        if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
      }
      $mcontent .= '</tr></table></div>';
      
      $mcontent .= '<div class="alltra" style="margin:10px;"><a href="/music/artistracks-'.$nameartist.'.html" title="Смотреть и скачать все мп3 треки '.$artist1.'">Смотреть все mp3 треки '.$artist1.'</a></div><div style="clear:both;"></div>';
      
    }
    if ($cnt_alb < $mscfg['album_page_lim']) $podmod = TRUE;
    
// Постраничная навигация
$count_d = $cnt_alb / $mscfg['album_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/albums-'.$nameartist.'-page-'.$t2.$sortpage.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/albums-'.$nameartist.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/albums-'.$nameartist.'-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/albums-'.$nameartist;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $cnt_alb > $mscfg['album_page_lim'] ) {
  $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $albums_to_view = $cnt_alb - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
  if ($albums_to_view < $mscfg['album_page_lim']) $podmod = TRUE;
}
  $db->free( );
  }
}
if ($podmod == TRUE) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
	$mcontent .= $downloads_top_albums_week;
}
break;

// Просмотр альбомов по жанрам
case 'genre-albums' :

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
if (($limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2) < 0 ) {
  $limit = 0;
  $page = 1;
}
if ( $page == 1 ) $metapage = '';
else $metapage = ' (страница '.$page.') ';

$genre1 = $parse->process( $_REQUEST['genre'] );
if ($genre1 == 'pop') {$genreid = '1'; $genre = 'Pop';}
elseif ($genre1 == 'club') {$genreid = '2'; $genre = 'Club';}
elseif ($genre1 == 'rap') {$genreid = '3'; $genre = 'Rap';}
elseif ($genre1 == 'rock') {$genreid = '4'; $genre = 'Rock';}
elseif ($genre1 == 'shanson') {$genreid = '5'; $genre = 'Шансон';}
elseif ($genre1 == 'ost') {$genreid = '6'; $genre = 'OST';}
elseif ($genre1 == 'metal') {$genreid = '7'; $genre = 'Metal';}
elseif ($genre1 == 'country') {$genreid = '8'; $genre = 'Country';}
elseif ($genre1 == 'classical') {$genreid = '9'; $genre = 'Classical';}
elseif ($genre1 == 'punk') {$genreid = '10'; $genre = 'Punk';}
elseif ($genre1 == 'soul/r-and-b') {$genreid = '11'; $genre = 'Soul/R&B';}
elseif ($genre1 == 'jazz') {$genreid = '12'; $genre = 'Jazz';}
elseif ($genre1 == 'electronic') {$genreid = '13'; $genre = 'Electronic';}
elseif ($genre1 == 'indie') {$genreid = '14'; $genre = 'Indie';}
else $stop[] = 'Неверно задан жанр для альбомов. <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';

if ( count( $stop ) == 0 ) {
  $metatags['title'] = 'Все альбомы жанр: '.$genre.' - cкачать бесплатно, новый альбом, слушать онлайн'.$metapage.', все новые песни на '.$config['home_title_short'];
  $metatags['description'] = 'Все альбомы жанр:'.$genre.' - cкачать бесплатно, новый альбом, все песни, все новые треки, zip-архив, слушать онлайн на '.$config['description'];
  $metatags['keywords'] = 'все альбомы, бесплатно скачать, жанр '.$genre.' mp3, новый альбом, слушать онлайн, музыкальный архив, все песни, new, бесплатно скачать все альбомы '.$genre.', скачать мп3 бесплатно, новинка, музыка без регистрации, все хиты mp3 бесплатно, торрент, zip-архив';
  
  if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
  else {$sort = intval( $_REQUEST['sort'] ); $sortpage = '/sort-'.$sort;}

  if ( $sort == 1 ) {
    $sqlsort = 'album, artist, year';
    $sortactive = 'По названию альбома &#8595;';
    $sortactive_no = '<li><a href="/music/genre-albums-'.$genre1.'.html">По исполнителю &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-2.html">По рейтингу &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-3.html">По году выхода &#8593;</a></li>';
  }
  elseif ( $sort == 2 ) {
    $sqlsort = 'download DESC, view_count DESC, artist, album, year';
    $sortactive = 'По рейтингу &#8595;';
    $sortactive_no = '<li><a href="/music/genre-albums-'.$genre1.'.html">По исполнителю &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-1.html">По названию альбома &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-3.html">По году выхода &#8593;</a></li>';
  }
  elseif ( $sort == 3 ) {
    $sqlsort = 'year DESC, artist, album';
    $sortactive = 'По году выхода &#8593;';
    $sortactive_no = '<li><a href="/music/genre-albums-'.$genre1.'.html">По исполнителю &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-1.html">По названию альбома &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-2.html">По рейтингу &#8595;</a></li>';
  } else {
    $sqlsort = 'artist, album, year';
    $sortactive = 'По исполнителю &#8595;';
    $sortactive_no = '<li><a href="/music/genre-albums-'.$genre1.'/sort-1.html">По названию альбома &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-2.html">По рейтингу &#8595;</a></li><li><a href="/music/genre-albums-'.$genre1.'/sort-3.html">По году выхода &#8593;</a></li>';
  }

  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE genre = '$genreid' AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE genre = '$genreid' AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit.",".$mscfg['album_page_lim']*2);
   
   if ( $db->num_rows() == 0 ) $stop[] = 'Неверная страница с альбомами '.$genre.'! <a href="/music/genre-albums-'.$genre1.'.html">Вернуться на первую страницу c альбомами '.$genre.'.</a>';
   if ( count( $stop ) == 0 ) {
    $i = 1;
    if ( $db->num_rows() > 0 ) {
      $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><h1 class="album_tracks_tit">Все альбомы жанра <font color="#3367AB">#'.$genre.'</font></h1><div class="line" style="margin:5px 0;"></div>';
      $mcontent .= '<div id="navigation_up"></div>';
      
      if ( $db->num_rows() > 1 ) $mcontent .= '<dl id="sample" class="dropdown_alb">
        <dt><span>'.$sortactive.'</span></dt>
        <dd>
            <ul>
              '.$sortactive_no.'
            </ul>
        </dd>
    </dl>';
    
    $mcontent .= '<table width="100%" style="margin-top:15px;"><tr>';
    
      while( $row = $db->get_row() ) {
        if (!$cnt_alb) $cnt_alb = $row['cnt'];
        if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row['year_alb'].')';
        if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div><div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
        $i++;
        if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
      }
      $mcontent .= '</tr></table></div>';    
    }
    if ($cnt_alb < $mscfg['album_page_lim']) $podmod = TRUE;
// Постраничная навигация
$count_d = $cnt_alb / ($mscfg['album_page_lim']*2);

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/genre-albums-'.$genre1.'-page-'.$t2.$sortpage.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/genre-albums-'.$genre1.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/genre-albums-'.$genre1.'-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/genre-albums-'.$genre1;
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $cnt_alb > $mscfg['album_page_lim']*2 ) {
  $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $albums_to_view = $cnt_alb - (($page * $mscfg['album_page_lim']*2) - $mscfg['album_page_lim']*2);
  if ($albums_to_view < $mscfg['album_page_lim']) $podmod = TRUE;
  elseif ($albums_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
}
  $db->free( );
  }
}
if ($podmod == TRUE) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_24hr.php';
	$mcontent .= $downloads_top_albums_24hr;
}
break;

// Просмотр всех жанров для альбомов
case 'genres-albums' :

$metatags['title'] = 'Все альбомы по жанрам - cкачать бесплатно, новый альбом, слушать онлайн, все новые песни на '.$config['home_title_short'];
$metatags['description'] = 'Все альбомы по жанрам - cкачать бесплатно, новый альбом, все песни, все новые треки, zip-архив, слушать онлайн на '.$config['description'];
$metatags['keywords'] = 'все альбомы, бесплатно скачать, жанр, mp3, новый альбом, слушать онлайн, музыкальный архив, все песни, new, бесплатно скачать все альбомы, скачать мп3 бесплатно, новинка, музыка без регистрации, все хиты mp3 бесплатно, торрент, zip-архив';

$mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><h1 class="album_tracks_tit">Все музыкальные альбомы по жанрам на domain.com</h1><div class="line" style="margin:5px 0;"></div>';

$mcontent .= '<table width="100%" style="margin:10px 0 0;"><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-classical.html" class="genre9" title="Все альбомы жанра #Classical"><img src="{THEME}/images/genres/genre9.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Classical" alt="бесплатно скачать все альбомы жанра #Classical" /></a><a href="/music/genre-albums-classical.html" class="genre9" title="Все альбомы жанра #Classical" style="font: bold 15px/10 Verdana;"><b>#Classical</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-club.html" class="genre2" title="Все альбомы жанра #Club"><img src="{THEME}/images/genres/genre2.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Club" alt="бесплатно скачать все альбомы жанра #Club" /></a><a href="/music/genre-albums-club.html" class="genre2" title="Все альбомы жанра #Club" style="font: bold 15px/10 Verdana;"><b>#Club</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-country.html" class="genre8" title="Все альбомы жанра #Country"><img src="{THEME}/images/genres/genre8.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Country" alt="бесплатно скачать все альбомы жанра #Country" /></a><a href="/music/genre-albums-country.html" class="genre8" title="Все альбомы жанра #Country" style="font: bold 15px/10 Verdana;"><b>#Country</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-electronic.html" class="genre13" title="Все альбомы жанра #Electronic"><img src="{THEME}/images/genres/genre13.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Electronic" alt="бесплатно скачать все альбомы жанра #Electronic" /></a><a href="/music/genre-albums-electronic.html" class="genre13" title="Все альбомы жанра #Electronic" style="font: bold 15px/10 Verdana;"><b>#Electronic</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-indie.html" class="genre14" title="Все альбомы жанра #Indie"><img src="{THEME}/images/genres/genre14.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Indie" alt="бесплатно скачать все альбомы жанра #Indie" /></a><a href="/music/genre-albums-indie.html" class="genre14" title="Все альбомы жанра #Indie" style="font: bold 15px/10 Verdana;"><b>#Indie</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-jazz.html" class="genre12" title="Все альбомы жанра #Jazz"><img src="{THEME}/images/genres/genre12.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Jazz" alt="бесплатно скачать все альбомы жанра #Jazz" /></a><a href="/music/genre-albums-jazz.html" class="genre12" title="Все альбомы жанра #Jazz" style="font: bold 15px/10 Verdana;"><b>#Jazz</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-metal.html" class="genre7" title="Все альбомы жанра #Metal"><img src="{THEME}/images/genres/genre7.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Metal" alt="бесплатно скачать все альбомы жанра #Metal" /></a><a href="/music/genre-albums-metal.html" class="genre7" title="Все альбомы жанра #Metal" style="font: bold 15px/10 Verdana;"><b>#Metal</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-ost.html" class="genre6" title="Все альбомы жанра #OST"><img src="{THEME}/images/genres/genre6.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #OST" alt="бесплатно скачать все альбомы жанра #OST" /></a><a href="/music/genre-albums-ost.html" class="genre6" title="Все альбомы жанра #OST" style="font: bold 15px/10 Verdana;"><b>#OST</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-pop.html" class="genre1" title="Все альбомы жанра #Pop"><img src="{THEME}/images/genres/genre1.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Pop" alt="бесплатно скачать все альбомы жанра #Pop" /></a><a href="/music/genre-albums-pop.html" class="genre1" title="Все альбомы жанра #Pop" style="font: bold 15px/10 Verdana;"><b>#Pop</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-punk.html" class="genre10" title="Все альбомы жанра #Punk"><img src="{THEME}/images/genres/genre10.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Punk" alt="бесплатно скачать все альбомы жанра #Punk" /></a><a href="/music/genre-albums-punk.html" class="genre10" title="Все альбомы жанра #Punk" style="font: bold 15px/10 Verdana;"><b>#Punk</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-rap.html" class="genre3" title="Все альбомы жанра #Rap"><img src="{THEME}/images/genres/genre3.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Rap" alt="бесплатно скачать все альбомы жанра #Rap" /></a><a href="/music/genre-albums-rap.html" class="genre3" title="Все альбомы жанра #Rap" style="font: bold 15px/10 Verdana;"><b>#Rap</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-rock.html" class="genre4" title="Все альбомы жанра #Rock"><img src="{THEME}/images/genres/genre4.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Rock" alt="бесплатно скачать все альбомы жанра #Rock" /></a><a href="/music/genre-albums-rock.html" class="genre4" title="Все альбомы жанра #Rock" style="font: bold 15px/10 Verdana;"><b>#Rock</b></a></td>';

$mcontent .= '</tr><td colspan="2" height="15"></td></tr><tr>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-soul/r-and-b.html" class="genre11" title="Все альбомы жанра #Soul/R&B"><img src="{THEME}/images/genres/genre11.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Soul/R&B" alt="бесплатно скачать все альбомы жанра #Soul/R&B" /></a><a href="/music/genre-albums-soul/r-and-b.html" class="genre11" title="Все альбомы жанра #Soul/R&B" style="font: bold 15px/10 Verdana;"><b>#Soul/R&B</b></a></td>';

$mcontent .= '<td width="50%" valign="top"><a href="/music/genre-albums-shanson.html" class="genre5" title="Все альбомы жанра #Шансон"><img src="{THEME}/images/genres/genre5.jpg" class="track_logo" style="float:left;" title="бесплатно скачать все альбомы жанра #Шансон" alt="бесплатно скачать все альбомы жанра #Шансон" /></a><a href="/music/genre-albums-shanson.html" class="genre5" title="Все альбомы жанра #Шансон" style="font: bold 15px/10 Verdana;"><b>#Шансон</b></a></td>';

$mcontent .= '</tr></table></div>';
    
break;

//Избранные альбомы юзера
case 'myfavalbums':

if( !$is_logged ) {
	header("location: {$config['http_home_url']}");
	exit;
}
$nam_e1 = 'Мои избранные альбомы';

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';

$metatags['title'] = $member_id['name'].'. Мои избранные альбомы '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'Мои избранные альбомы на '.$config['description'];
$metatags['keywords'] = 'мои избранные альбомы, музыкальный архив, '.$config['keywords'];

if ( ($_REQUEST['sort'] == FALSE) OR ($_REQUEST['sort'] == 0) ) $sortpage = '';
else {
  $sort = intval( $_REQUEST['sort'] );
  $sortpage = '/sort-'.$sort;
}

if ( $sort == 1 ) {
  $sqlsort = 'album, artist';
  $sortactive = 'По названию альбома &#8595;';
  $sortactive_no = '<li><a href="/music/myfavalbums.html">По исполнителю &#8595;</a></li><li><a href="/music/myfavalbums/sort-2.html">По году выхода &#8593;</a></li>';
}
elseif ( $sort == 2 ) {
  $sqlsort = 'year DESC, artist, album';
  $sortactive = 'По году выхода &#8593;';
  $sortactive_no = '<li><a href="/music/myfavalbums.html">По исполнителю &#8595;</a></li><li><a href="/music/myfavalbums/sort-1.html">По названию альбома &#8595;</a></li>';
}
else {
  $sqlsort = 'artist, album';
  $sortactive = 'По исполнителю &#8595;';
  $sortactive_no = '<li><a href="/music/myfavalbums/sort-1.html">По названию альбома &#8595;</a></li><li><a href="/music/myfavalbums/sort-2.html">По году выхода &#8593;</a></li>';
}

$mtitle .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><h3 class="tit" style="background:#FBDB85;"><span>Мои избранные альбомы</span></h3>';

if ($member_id['favorites_albums_mservice'] == "") {
  $podmod = TRUE;
  $mtitle .= '</div>';
  $mcontent .= '<li style="margin-left:30px;">К сожалению, Вы ещё не добавляли альбомы в избранные!</li><br />';
} else  {
  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist, (SELECT count(*) FROM ".PREFIX."_mservice_albums WHERE aid IN (".$member_id['favorites_albums_mservice'].") AND approve = '1') as cnt FROM ".PREFIX."_mservice_albums WHERE aid IN (".$member_id['favorites_albums_mservice'].") AND approve = '1' ORDER BY ".$sqlsort." LIMIT ".$limit."," . $mscfg['album_page_lim'] );
  if ( $db->num_rows() != 0 ) {
    $i = 1;
    $mtitle .= '<div style="height:10px"></div>';
    
    if ( $db->num_rows() > 1 ) $mtitle .= '<dl id="sample" class="dropdown_alb">
        <dt><span>'.$sortactive.'</span></dt>
        <dd>
            <ul>
              '.$sortactive_no.'
            </ul>
        </dd>
    </dl>';
    
    $mtitle .= '<table width="100%" style="margin-top:15px;"><tr>';
    
    while( $row = $db->get_row() ) {
        $genreid = $row['genre'];
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
        
        if ($genre1 !== '') $alb_genre_tab = '<a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div>';
        else $alb_genre_tab = '';
        
      if (!$cnt_alb) $cnt_alb = $row['cnt'];
      if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
      else $year_alb = '('.$row['year_alb'].')';
      if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
      $mtitle .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div>'.$alb_genre_tab.'<div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
      $i++;
      if (($i % 2) == '1') $mtitle .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
    }
    $mtitle .= '</tr></table></div>';

    if ($cnt_alb < $mscfg['album_page_lim']) $podmod = TRUE;
    
// Постраничная навигация
$count_d = $cnt_alb / $mscfg['album_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;
$plink = $config['http_home_url'].'music/myfavalbums-page-'.$t2.$sortpage.'.html';
    
if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/myfavalbums'.$sortpage.'.html';
$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';}
$array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/myfavalbums-page-';
  $seo_mode = $sortpage.'.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/myfavalbums';
$prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
								else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';}
  else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

if ( $cnt_alb > $mscfg['album_page_lim'] ) {
  $mtitle .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;
  $albums_to_view = $cnt_alb - (($page * $mscfg['album_page_lim']) - $mscfg['album_page_lim']);
  if ($albums_to_view < $mscfg['album_page_lim']) $podmod = TRUE;
}

  } else {
    $mtitle .= '</div>';
    $stop[] = 'Неверная страница с Вашими избранными альбомами! <a href="/music/myfavalbums.html">Вернуться на первую страницу Мои избранные альбомы.</a>';
  }
$db->free( );
}
if ($podmod == TRUE) {
  include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_albums_week.php';
	$mcontent .= $downloads_top_albums_week;
}
break;

// Просмотр новинки альбомов
case 'new-mp3-albums' :

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );
if (($limit = ( $page * 2 * $mscfg['album_page_lim'] ) - $mscfg['album_page_lim']*2) < 0 ) {
  $limit = 0;
  $page = 1;
}
if ( $page == 1 ) $metapage = '';
else $metapage = ' (страница '.$page.') ';

/*
$genre1 = $parse->process( $_REQUEST['genre'] );
if ($genre1 == 'pop') {$genreid = '1'; $genre = 'Pop';}
elseif ($genre1 == 'club') {$genreid = '2'; $genre = 'Club';}
elseif ($genre1 == 'rap') {$genreid = '3'; $genre = 'Rap';}
elseif ($genre1 == 'rock') {$genreid = '4'; $genre = 'Rock';}
elseif ($genre1 == 'shanson') {$genreid = '5'; $genre = 'Шансон';}
elseif ($genre1 == 'ost') {$genreid = '6'; $genre = 'OST';}
elseif ($genre1 == 'metal') {$genreid = '7'; $genre = 'Metal';}
elseif ($genre1 == 'country') {$genreid = '8'; $genre = 'Country';}
elseif ($genre1 == 'classical') {$genreid = '9'; $genre = 'Classical';}
elseif ($genre1 == 'punk') {$genreid = '10'; $genre = 'Punk';}
elseif ($genre1 == 'soul/r-and-b') {$genreid = '11'; $genre = 'Soul/R&B';}
elseif ($genre1 == 'jazz') {$genreid = '12'; $genre = 'Jazz';}
elseif ($genre1 == 'electronic') {$genreid = '13'; $genre = 'Electronic';}
elseif ($genre1 == 'indie') {$genreid = '14'; $genre = 'Indie';}
else $stop[] = 'Неверно задан жанр для альбомов. <a href="javascript:history.go(-1)">Вернуться на предыдущую страницу</a>';
*/

if ( count( $stop ) == 0 ) {
  $metatags['title'] = 'Новинки музыкальных альбомов - cкачать бесплатно, новый альбом, слушать онлайн'.$metapage.', все новые песни на '.$config['home_title_short'];
  $metatags['description'] = 'Все новинки музыкальных альбомов - cкачать бесплатно, новый альбом, все песни, все новые треки, zip-архив, слушать онлайн на '.$config['description'];
  $metatags['keywords'] = 'новинки музыкальных альбомов, бесплатно скачать, жанр mp3, новый альбом, слушать онлайн, музыкальный архив, все песни, new, бесплатно скачать все альбомы, скачать мп3 бесплатно, новинка, музыка без регистрации, все хиты mp3 бесплатно, торрент, zip-архив';
  
  $db->query( "SELECT aid, year as year_alb, album, artist, image_cover, genre, transartist FROM ".PREFIX."_mservice_albums WHERE approve = '1' ORDER BY time DESC LIMIT ".$limit.",".$mscfg['album_page_lim']*2);
   
   if ( $db->num_rows() == 0 ) $stop[] = 'Неверная страница с новинками музыкальных альбомов. <a href="/music/new-mp3-albums.html">Вернуться на первую страницу c новинками музыкальных альбомов</a>';
   if ( count( $stop ) == 0 ) {
    $i = 1;
    if ( $db->num_rows() > 0 ) {
    
      $mcontent .= '<div class="album_tracks_ramka" style="margin-bottom:5px;"><h1 class="album_tracks_tit">Новинки музыкальных альбомов на domain.com</h1>';
      $mcontent .= '<div id="navigation_up"></div>';
      
      $mcontent .= '<table width="100%" style="margin-top:15px;"><tr>';
    
      while( $row = $db->get_row() ) {
        $genreid = $row['genre'];
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
        
        if ($genre1 !== '') $alb_genre_tab = '<a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div>';
        else $alb_genre_tab = '';
        
        if ( $row['year_alb'] == '0' OR $row['year_alb'] == '00' OR $row['year_alb'] == '000' OR $row['year_alb'] == '0000' ) $year_alb = '';
        else $year_alb = '('.$row['year_alb'].')';
        if ($row['image_cover'] == '') $img_album = 'album_0.jpg'; else $img_album = $row['image_cover'];
        
        $mcontent .= '<td width="50%" valign="top"><a href="/music/'.$row['aid'].'-album-'.$row['transartist'].'-'.totranslit( $row['album'] ).'.html" class="albums" title="Смотреть альбом '.$row['artist'].' - '.$row['album'].'"><img src="/uploads/albums/'.$img_album.'" class="track_logo" style="float:left;" alt="бесплатно скачать альбом '.$row['artist'].' - '.$row['album'].'" title="скачать бесплатно альбом '.$row['artist'].' - '.$row['album'].'" /><b>'.artistAlbumStrlen( $row['artist'] ).'</b><br />'.artistAlbumStrlen( $row['album'] ).'<br />'.$year_alb.'</a><div style="height:10px"></div><a class="genre'.$genreid.'" href="/music/genre-albums-'.$genre1.'.html">#'.$genre.'</a><div style="height:10px"></div><div id="favorited-albums-layer-'.$row['aid'].'">'.showFavAlbumList( $row['aid'] ).'</div></td>';
        $i++;
        if (($i % 2) == '1') $mcontent .= '</tr><tr><td colspan="2" height="15"></td></tr><tr>';
      }
      $mcontent .= '</tr></table></div>';    
    }

// Постраничная навигация
$count_d = $mscfg['page_lim_main'];

for ( $t = 0; $count_d > $t; $t ++ ) {
  $t2 = $t + 1;
  $plink = $config['http_home_url'].'music/new-mp3-albums-page-'.$t2.'.html';
    
  if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
  else {
    if ($t2 == 1) {$plink = $config['http_home_url'].'music/new-mp3-albums.html'; $pages .= '<a href="'.$plink.'">1</a> ';}
    else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';
  }
  $array[$t2] = 1;
}

  $link = $config['http_home_url'].'music/new-mp3-albums-page-';
  $seo_mode = '.html';

$npage = $page - 1;
if ( isset($array[$npage]) ) {
  if ($npage ==1 ) {$linkdop = $config['http_home_url'].'music/new-mp3-albums-'; $prev_page = '<a href="'.$linkdop.$seo_mode.'">Назад</a> ';}
  else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Назад</a> ';
}
else $prev_page = '';
$npage = $page + 1;
if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">Далее</a>';
  else $next_page = '';

  $mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
  }
}

break;

// конец цикла switch ( $_REQUEST['act'] )
}
?>
