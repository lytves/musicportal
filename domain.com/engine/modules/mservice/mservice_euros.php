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

// Страница участников Евровидения 2011, выборка песен по комментарию к треку "Евровидение 2011", без постраничной навигации (только 43 песни)
case 'euro2011':

$metatags['title'] = 'Евровидение 2011 Дюссельдорф, Германия все mp3 треки на '.$config['home_title_short'];
$metatags['description'] = 'Евровидение 2011 Дюссельдорф, Германия все mp3 треки на '.$config['description'];
$metatags['keywords'] = 'скачать все песни Евровидение 2011 Дюссельдорф mp3, Германия, музыкальный евро архив, бесплатно скачать все треки Евровиденья 2011, скачать мп3 бесплатно, евро музыка без регистрации, все хиты Евровидения 2011 Дюссельдорф mp3 бесплатно';

$mtitle .= '<h1 class="tit"><span>Все треки</span> Конкурса песни <span>Евровидение 2011 Дюссельдорф, Германия</span></h1>';
$mtitle .= '<div class="bestmp3">...а также Все треки Евровидение за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="Все треки Евровидение 2015 года Вена, Австрия">2015 год</a></li><li><a href="/music/euro2014.html" title="Все треки Евровидение 2014 года Копенгаген, Дания">2014 год</a></li><li><a href="/music/euro2013.html" title="Скачать Все треки Евровидение 2013 года Мальмё, Швеция">2013 год</a></li><li><a href="/music/euro2012.html" title="Все треки Евровидение 2012 года Баку, Азербайджан">2012 год</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE 'Евровидение 2011%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = 'К сожалению, в данной категории ещё нет аудио треков. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2011.jpg" alt="Все треки Евровидение 2011 Дюссельдорф Германия" title="Все треки Евровидение 2011 Дюссельдорф Германия" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
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

// Страница участников Евровидения 2012, выборка песен по комментарию к треку "Евровидение 2012", без постраничной навигации (только 43 песни)
case 'euro2012':

$metatags['title'] = 'Евровидение 2012 Баку, Азербайджан все mp3 треки на '.$config['home_title_short'];
$metatags['description'] = 'Евровидение 2012 Баку, Азербайджан все mp3 треки на '.$config['description'];
$metatags['keywords'] = 'скачать все песни Евровидение 2012 Баку mp3, Азербайджан, музыкальный евро архив, бесплатно скачать все треки Евровиденья 2012, скачать мп3 бесплатно, евро музыка без регистрации, все хиты Евровидения 2012 mp3 Баку бесплатно';

$mtitle .= '<h1 class="tit"><span>Все треки</span> Конкурса песни <span>Евровидение 2012 Баку, Азербайджан</span></h1>';
$mtitle .= '<div class="bestmp3">...а также Все треки Евровидение за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="Все треки Евровидение 2015 года Вена, Австрия">2015 год</a></li><li><a href="/music/euro2014.html" title="Скачать Все треки Евровидение 2014 года Копенгаген, Дания">2014 год</a></li><li><a href="/music/euro2013.html" title="Все треки Евровидение 2013 года Мальмё, Швеция">2013 год</a></li><li><a href="/music/euro2011.html" title="Все треки Евровидение 2011 года Дюссельдорф, Германия">2011 год</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE 'Евровидение 2012%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = 'К сожалению, в данной категории ещё нет аудио треков. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2012.jpg" alt="Все треки Евровидение 2012 Баку Азербайджан" title="Все треки Евровидение 2012 Баку Азербайджан" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
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

// Страница участников Евровидения 2013, выборка песен по комментарию к треку "Евровидение 2013", без постраничной навигации (только ... песен)
case 'euro2013':

$metatags['title'] = 'Евровидение 2013 Мальмё, Швеция все mp3 треки на '.$config['home_title_short'];
$metatags['description'] = 'Евровидение 2013 Мальмё, Швеция все mp3 треки на '.$config['description'];
$metatags['keywords'] = 'скачать все песни Евровидение 2013 Мальмё mp3, Швеция, музыкальный евро архив, бесплатно скачать все треки Евровиденья 2013, скачать мп3 бесплатно, евро музыка без регистрации, все хиты Евровидения 2013 mp3 Мальмё бесплатно';

$mtitle .= '<h1 class="tit"><span>Все треки</span> Конкурса песни <span>Евровидение 2013 Мальмё, Швеция</span></h1>';
$mtitle .= '<div class="bestmp3">...а также Все треки Евровидение за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="Все треки Евровидение 2015 года Вена, Австрия">2015 год</a></li><li><a href="/music/euro2014.html" title="Все треки Евровидение 2014 года Копенгаген, Дания">2014 год</a></li><li><a href="/music/euro2012.html" title="Все треки Евровидение 2012 года Баку, Азербайджан">2012 год</a></li><li><a href="/music/euro2011.html" title="Все треки Евровидение 2011 года Дюссельдорф, Германия">2011 год</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE 'Евровидение 2013%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = 'К сожалению, в данной категории ещё нет аудио треков. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2013.jpg" alt="Все треки Евровидение 2013 Мальмё Швеция" title="Все треки Евровидение 2013 Мальмё Швеция" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
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

// Страница участников Евровидения 2014, выборка песен по комментарию к треку "Евровидение 2014", без постраничной навигации (только ... песен)
case 'euro2014':

$metatags['title'] = 'Евровидение 2014 Копенгаген, Дания все mp3 треки на '.$config['home_title_short'];
$metatags['description'] = 'Евровидение 2014 Копенгаген, Дания все mp3 треки, Eurovision Song Contest 2014 на '.$config['description'];
$metatags['keywords'] = 'скачать все песни Евровидение 2014 Копенгаген mp3, Дания, Eurovision Song Contest 2014, new, музыкальный евро архив, бесплатно скачать все треки Евровиденья 2014, новинки Евровидения, скачать мп3 бесплатно, евро музыка без регистрации, новые, все хиты Евровидения 2014 mp3 Копенгаген бесплатно';

$mtitle .= '<h1 class="tit"><span>Все треки</span> Конкурса песни <span>Евровидение 2014 Копенгаген, Дания</span></h1>';
$mtitle .= '<div class="bestmp3">...а также Все треки Евровидение за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2015.html" title="Все треки Евровидение 2015 года Вена, Австрия">2015 год</a></li><li><a href="/music/euro2013.html" title="Все треки Евровидение 2013 года Мальмё, Швеция">2013 год</a></li><li><a href="/music/euro2012.html" title="Все треки Евровидение 2012 года Баку, Азербайджан">2012 год</a></li><li><a href="/music/euro2011.html" title="Все треки Евровидение 2011 года Дюссельдорф, Германия">2011 год</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE 'Евровидение 2014%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = 'К сожалению, в данной категории ещё нет аудио треков. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2014.jpg" alt="Все треки Евровидение 2014 Копенгаген Дания" title="Все треки Евровидение 2014 Копенгаген Дания" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
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

// Страница участников Евровидения 2015, выборка песен по комментарию к треку "Евровидение 2015", без постраничной навигации (только ... песен)
case 'euro2015':

$metatags['title'] = 'Евровидение 2015 Вена, Австрия все mp3 треки на '.$config['home_title_short'];
$metatags['description'] = 'Евровидение 2015 Вена, Австрия все mp3 треки, Eurovision Song Contest 2015 на '.$config['description'];
$metatags['keywords'] = 'скачать все песни Евровидение 2015 Вена mp3, Австрия, Eurovision Song Contest 2015, new, музыкальный евро архив, бесплатно скачать все треки Евровиденья 2015, новинки Евровидения, скачать мп3 бесплатно, евро музыка без регистрации, новые, все хиты Евровидения 2015 mp3 Вена бесплатно';

$mtitle .= '<h1 class="tit"><span>Все треки</span> Конкурса песни <span>Евровидение 2015 Вена, Австрия</span></h1>';
$mtitle .= '<div class="bestmp3">...а также Все треки Евровидение за: <ul class="bestmp3s"><li><a style="margin-left:10px;" href="/music/euro2014.html" title="Все треки Евровидение 2014 года Копенгаген, Дания">2014 год</a></li><li><a href="/music/euro2013.html" title="Все треки Евровидение 2013 года Мальмё, Швеция">2013 год</a></li><li><a href="/music/euro2012.html" title="Все треки Евровидение 2012 года Баку, Азербайджан">2012 год</a></li><li><a href="/music/euro2011.html" title="Все треки Евровидение 2011 года Дюссельдорф, Германия">2011 год</a></li></ul></div>';

$db->query( "SELECT mid, time, title, artist, download, view_count, lenght, filename, hdd, size, clip_online FROM ".PREFIX."_mservice WHERE description LIKE 'Евровидение 2015%' AND approve = '1' ORDER BY artist" );
if ( $db->num_rows() == 0 ) $stop[] = 'К сожалению, в данной категории ещё нет аудио треков. <a href="'.$config['http_home_url'].'music/massaddfiles.html">Но Вы можете это исправить</a>.';

if ( count( $stop ) == 0 ) {

$mcontent .= <<<HTML
<table style="width:100%;">
<tr><td><div class="line"></div></td></tr>
<tr><td width="100%" align="center"><img src="{$THEME}/images/eurovision2015.jpg" alt="Все треки Евровидение 2015 Вена Австрия" title="Все треки Евровидение 2015 Вена Австрия" style="margin:10px 0px;"/></td></tr>
</table>
HTML;

$mcontent .= <<<HTML
<div id="playlist">
<table width="100%">
<thead><tr>
<th width="70%" height="20">&nbsp;</th>
<th width="10%">Время</th>
<th width="10%">Размер</th>
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