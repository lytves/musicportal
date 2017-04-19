<script type="text/javascript">
	function infoTrack( lenght, beats, sizetemp, id ) {
				var ajax = new dle_ajax();
				ajax.onShow ('');
				var varsString = '';
				ajax.setVar( "act", 19 );
				ajax.setVar( "lenght", lenght );
        ajax.setVar( "beats", beats );
        ajax.setVar( "sizetemp", sizetemp );
				ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
				ajax.method = 'POST';
				ajax.element = 'info-tracks-layer'+id;
				ajax.sendAJAX(varsString);
  				ajax.onCompletion ( document.getElementById('info-tracks-layer'+id).style.display = 'block' );
	}

	function viewRTracks( id ) {
				var ajax = new dle_ajax();
				ajax.onShow ('');
				var varsString = '';
				ajax.setVar( "act", 4 );
  			ajax.setVar( "title", document.getElementById('track_name'+id).value );
				ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
				ajax.method = 'POST';
				ajax.element = 'related-tracks-layer'+id;
				ajax.sendAJAX(varsString);
  				ajax.onCompletion ( document.getElementById('related-tracks-layer'+id).style.display = 'block' );
	}
	
	function del_track( id, file ) {
		var ajax = new dle_ajax();
		ajax.onShow ('');
		var varsString = '';
		ajax.setVar( "act", 6 );
		ajax.setVar( "file", file );
		ajax.setVar( "i", id );
		ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
		ajax.method = 'POST';
		ajax.sendAJAX(varsString);
  		ajax.onCompletion ( document.getElementById('div_'+id).innerHTML = '' );
	}
	function del_apic( id, filename ) {
    var ajax = new dle_ajax();
    ajax.onShow ( '' );
    var varsString = "";
    ajax.setVar( "act", 24 );
    ajax.setVar( "filename", filename );
    ajax.requestFile = dle_root + "engine/modules/mservice/ajax.php";
    ajax.method = 'POST';
    ajax.element = 'apic_'+id;
    ajax.sendAJAX(varsString);
    ajax.onCompletion ( document.getElementById('apic_'+id).style.display = 'block' );
  }
</script>
{failed_tracks}
<form action="" method="post">
<input type="hidden" name="act" value="domassaddfiles" />
{files}
	
		[captcha]
			<script language='JavaScript' type="text/javascript">
			<!--
				function reload ()
					{
					var rndval = new Date().getTime();
					document.getElementById('dle-captcha').innerHTML = '<a onclick="reload(); return false;" href="#" title="Нажмите, если не видно изображения"><img src="{http_home_url}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="{sec_image}" /></a>';
					}
			//-->
			</script>
			<table>
			<tr>
				<td style="padding-top:10px;">Защитный код:</td>
				<td style="padding-top:10px;">
					<span id="dle-captcha">
						<a onclick="reload(); return false;" href="#" title="Нажмите, если не видно изображения">
							<img src="{http_home_url}engine/modules/antibot.php" alt="{sec_image}" border="0" />
						</a>
					</span>
				</td>
			</tr>
			<tr>
				<td style="padding-top:10px;">Введите код:</td>
				<td style="padding-top:10px;"><input name="sec_code" class="f_input" style="width:119px;" maxlength="20" /> <font color="#B60F15">*</font></td>
			</tr>
		</table>
		[/captcha]
<div style="padding:10px 30px 0;">
{rules}
<br /><button type="submit" class="submit2" style="width:230px; margin:10px 0;">Добавить на сайт</button>
</form>
</div>