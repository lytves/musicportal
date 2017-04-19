<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

// смотрим массив и если для элемента в массиве уже нет такого файла на диске, то удаляем эту строку
if (count($_SESSION['mservice_files']) > 0) {
	foreach ($_SESSION['mservice_files'] as $kluch1 => $znach1) {
		foreach ($znach1 as $znach2) {
			if (!is_file('/home2/mp3base/temp/'.$znach1['file'])) unset($_SESSION['mservice_files'][$kluch1]);
		}
	}
	unset($kluch1, $znach1, $znach2);
}

if ( $user_group[$member_id['user_group']]['mservice_addfile'] != 1 ) $stop[] = 'У Вас нет прав для публикации аудио треков';

if (count($_SESSION['mservice_files']) > 0) {
	$mcontent .= '<div style="display:block; padding:5px 5px 5px 25px; background:#FBDB85; margin:10px 0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; text-align:center;"><img src="'.$THEME.'/images/failed.png" align="middle" alt="Внимание" /> <span style="vertical-align:middle;">У Вас есть уже загруженные, но не добавленные на сайт треки:</span></div>';
	
	$mcontent .= <<<HTML
<script type="text/javascript">
	function del_track(id, file) {
		var ajax = new dle_ajax();
		ajax.onShow ('');
		var varsString = '';
		ajax.setVar( "act", 6 );
		ajax.setVar( "file", file );
		ajax.setVar( "i", id );
		ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
		ajax.method = 'POST';
		ajax.sendAJAX(varsString);
  		ajax.onCompletion ( document.getElementById('tr_'+id).innerHTML = '' );
	}
</script>
<table width="100%">
<tr>
<td style="width:70%; height:25px; text-align:center; font-weight:bold">Имя файла</td>
<td style="width:30%; text-align:center; font-weight:bold">Дата загрузки</td>
</tr>
HTML;

	foreach ($_SESSION['mservice_files'] as $kluch1 => $znach1) {
		$tempfile = iconv("utf-8", "cp1251" ,urldecode($znach1['name']));
		if ( strlen($tempfile) > 50 ) $tempfile = substr( $tempfile, 0, 50 )." ...";
		$mcontent .= '<tr id="tr_'.$kluch1.'"><td style="padding-left:10px;">'.$tempfile.' <a href="#" onclick="del_track('.$kluch1.', \''.$znach1['file'].'\');return false;"><img src="'.$THEME.'/images/uploadify-cancel.png" alt="удалить трек из списка" /></a><div class="dashed inactive"></div>
</td><td style="text-align:center">'.langdate( 'j F Y H:i', $znach1['date'] ).'</td></tr>';
	}
	unset($kluch1, $znach1, $znach2);
	$mcontent .= '</table>';	
	
	$mcontent .= <<<HTML
<div style="display:block; padding:5px 5px 5px 25px; background:#F7A8A8; margin:20px 0 20px 0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; text-align:center;">Что будем делать?</div>
	<table width="100%"><tr>
	<td width="50%" valign="top"><center><button type="submit" class="submit2" onclick="location.href='/music/massaddfiles02.html'">Добавить эти треки</button></center><br /><span style="padding-left:10px;">- продолжить добавление этих треков на сайт</span></td>
	<td valign="top"><center><button type="submit" class="submit" onclick="location.href='/music/masstemp.html'">Загрузить новые mp3</button></center><br /><span style="padding-left:10px;">- эти треки будут удалены и не добавлены на сайт</span></td>
	</tr></table>
HTML;
} else {
	$file_types = strtolower(@str_replace( ",", ", ", $mscfg['filetypes'] ));
	$file_size = formatsize( $mscfg['maxfilesize'] * 1024 );

  $mcontent .= '<div class="noscript">К сожалению, в вашем браузере отключен JavaScript, значит Вы можете воспользоваться только <a href="/music/addfile.html">Одиночной Загрузкой mp3-файла!</a></div>';
  
	$mcontent .= '<div class="script"><div style="display:block; padding:5px 5px 5px 30px; background:#FBDB85; margin:10px 0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; position:relative;" id="podskazka"><b>Подсказка:</b><br /><ul style="padding-right:20px;"><li>Нажмите "+ Загрузить mp3" и выберите желаемые для загрузки файлы со своего компьютера</li><li>По окончании загрузки нажмите "Загрузка: шаг №2" для перехода к редактированию mp3-тегов</li><li>Время загрузки файлов зависит от скорости Вашего интернет соединения</li><li>Разрешённые типы файлов: '.$file_types.'. Размер не более: '.$file_size.'</li><li>Ограничение на одновременную загрузку файлов: 20 штук</li></ul><a style="background:url('.$THEME.'/images/uploadify-cancel.png) 0 0 no-repeat; top:10px; height:16px; text-indent:-9999px; width: 16px; position:absolute; right:10px;" href="#" onclick="hidePodzkazka( ); return false;">X</a></div></div>';

	$mcontent .= <<<HTML
<script type="text/javascript" src="/engine/classes/js/jquery.uploadify-3.1.js"></script>

<div id="message_errors"></div>
<div style="width:100%; margin:10px 0;">
<div id="upload_step2" style="float:right; right:220px; position:relative; display:none;"><button type="submit" class="submit2" style="width:230px;" onclick="location.href='/music/massaddfiles02.html'">Загрузка: шаг №2</button></div>
<input type="file" name="file_upload" id="file_upload" />
</div>

<script type="text/javascript">
$(function() {
	$("#file_upload").uploadify({
		'swf'           : '{$THEME}/css/swf/uploadify.swf',
		'uploader'      : 'http://domain.com/engine/modules/mservice/uploadify.php',
		'buttonText'    : '+ Загрузить mp3',
		'fileTypeDesc'    : 'Муз. файлы',
		'onFallback' : function() {
			alert('Если вы видите это сообщение, Значит вам следует включить JavaScript, обновить Adobe Flash, или воспользоваться другим Браузером (Opera, Mozilla Firefox, Chrome).');
		},
		'onQueueComplete' : function() {
            $('#upload_step2').fadeIn(100);
        },
		'onUploadStart': function(file){
			$("#file_upload").uploadify('settings', 'formData', {'swfid': file.id, 'sessId' : '<?php echo session_id(); ?>' }); } }); });
    </script>
    </div>
    HTML; } ?>
