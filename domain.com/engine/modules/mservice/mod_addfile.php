<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if ( $user_group[$member_id['user_group']]['mservice_captcha'] == 1 ) {
$captcha = <<<HTML
<script language='JavaScript' type="text/javascript">
<!--
function reload () {
var rndval = new Date().getTime();
document.getElementById('dle-captcha').innerHTML = '<a onclick="reload(); return false;" href="#" title="Нажмите, если не видно изображения"><img src="{$config[http_home_url]}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="{$lang[sec_image]}" /></a>';}
//-->
</script>
<tr><td style="padding-top:10px;">Защитный код: </td><td style="padding-top:10px;"><span id="dle-captcha"><a onclick="reload(); return false;" href="#" title="Нажмите, если не видно изображения"><img src="{$config[http_home_url]}engine/modules/antibot.php" alt="{$lang[sec_image]}" border="0" /></a></span></td></tr>
<tr><td style="padding-top:10px;">Введите код: <font color="#EF5151">*</font></td><td style="padding-top:10px;"><input name="sec_code" class="f_input" style="width:119px;" maxlength="10" /></td></tr>
HTML;
} else $captcha = '';

$file_types = @str_replace( ",", ", ", $mscfg['filetypes'] );
$file_size = formatsize( $mscfg['maxfilesize'] * 1024 );

$rlnk = $config['http_home_url'].'music/rules.html';

$mcontent .= <<<HTML
<script type="text/javascript">
function viewRTracks( ) {
	var ajax = new dle_ajax();
	ajax.onShow ('');
	var varsString = '';
	ajax.setVar( "act", 4 );
	ajax.setVar( "title", document.getElementById('track_name').value );
	ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
	ajax.method = 'POST';
	ajax.element = 'related-tracks-layer';
	ajax.sendAJAX(varsString);
	ajax.onCompletion ( document.getElementById('related-tracks-layer').style.display = 'block' );
}
</script>
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="act" value="doaddfile" />
<table>
	<tr><td colspan="2"><div style="padding: 5px; background:#FBDB85; margin-top:10px; display:none; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;" id="related-tracks-layer"></div></td></tr>
	<tr><td style="padding-top:10px;">Исполнитель <font color="#EF5151">*</font></td><td style="padding-top:10px;"><input type="text" name="artist" class="f_input" size="40" /></td></tr>
	<tr><td style="padding-right:60px; padding-top:10px;">Название трека <font color="#EF5151">*</font></td><td style="padding-top:10px;"><input type="text" id="track_name" size="40" name="name" class="f_input" />
	<input style="height:18px; font-family:tahoma; font-size:11px; border:1px solid #DFDFDF; background: #FFFFFF; padding-bottom:4px;" title="Найти и отобразить похожие треки" onclick="viewRTracks( ); return false;" type="button" value="Найти похожие треки" />
</td></tr>
	<tr><td style="padding-top:10px;">Описание трека<br />(опционально)</td><td style="padding-top:10px;"><textarea cols="30" style="height:50px; resize:none;" name="comments" class="f_input"></textarea></td></tr>
	<tr><td style="padding-top:10px;">Загрузка файла <font color="#EF5151">*</font></td><td style="padding-top:10px;"><input type="file" name="file" size="44" /> <br />Разрешённые типы: {$file_types}; размер не более: {$file_size}</td></tr>
	{$captcha}
</table>
<br /><li>Время загрузки файла зависит от скорости Вашего интернет-соединения</li>
HTML;

/// юзеры из групп с id=1,2 всегда согласны с правилами ;)
	$mcontent .= <<<HTML
<br /><font color="#EF5151">*</font> Загружая файлы, Вы принимаете <a href="{$rlnk}" target="_blank">Пользовательское соглашение</a><br />(<font color="#EF5151">*</font> пункты обязательные для заполнения)
<br /><button type="submit" class="submit2" style="width:230px; margin:15px 20px;">Добавить на сайт</button></form>
HTML;
?>