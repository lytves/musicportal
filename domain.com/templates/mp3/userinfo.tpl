<h3 class="tit">������������: {usertitle}</h3>
{userifo-style}

<table style="margin:5px 0; width:100%;">
<tr>
<td width="138" valign="top" align="center">
<br />
<img src="{foto}" border="0" />{group-icon}</td>
<td class="textprof">

<b style="margin-left:10px;">&raquo; �� �����</b>
<div class="line" style="margin-bottom:5px;"></div>
������ ���: {fullname}
<br />������: {statusonsite}
<br />���� �����������: {registration}
<br />��������� ���������: {lastdate}
<br />������:&nbsp;<span style="font-weight:bold; color:#3367AB;">{status}</span>[time_limit] � ������ ��: {time_limit}[/time_limit]

{link_vip_group}{by_odmin_use_demo_vip_group}

<p><b style="margin-left:10px;">&raquo; � ������������. �����</b>
<div class="line" style="margin-bottom:5px;"></div>
[vauth-bdate]<span class="grey">���� ��������:</span> {bdate}<br />[/vauth-bdate]
[vauth-sex]<span class="grey">���:</span> {sex}<br />[/vauth-sex]
[vauth-mobile_phone]<span class="grey">�������:</span> {mobile_phone}<br />[/vauth-mobile_phone]
[vauth-friends]<span class="grey">������:</span> {friends}<br />[/vauth-friends]
����� ����������: {land}
<br />����� ICQ: {icq}
<br />������� � ����: {info}<br />
������� ���� ������: [xfvalue_fav_music]<br />
�������: {rate}
<br />�����: {email}{pm}

<br /><br /><b style="margin-left:10px;">&raquo; ���������� �� �����</b>
<div class="line" style="margin-bottom:5px;"></div>
{mytracks} {myfavtracks} {myfavalbums} {myfavartists}
<p>���������� ����������� mp3-������: {tracks-count} 
<br />���������� ����������: {news_num}
<br />���������� ������������: {comm_num} {comments}
<br />{link_plays}{link_downloads}{link_downloads_albums}

{add_buttons}

{admin_section1}
{downloads_top_24hr_top50}{downloads_top_week_top50}
{downloads_top_24hr_admin}{downloads_top_week_admin}
{downloads_top_albums_24hr}{downloads_top_albums_week}
{downloads_top_albums_24hr_admin}{downloads_top_albums_week_admin}
{popular_searchartist_week_admin}{popular_searchtitle_week_admin}{popular_searchalbum_week_admin}{popular_search_zero_admin}

<p><b style="margin-left:10px;">&raquo; �������. ���������� ��������</b>
<div class="line" style="margin-bottom:5px;"></div>
{socakksedit}{edituser}
[vauth]{accounts}[/vauth]
</td></tr>
</table>

<div style="padding-top:5px;"></div>
[not-logged]

<div id="options" style="display:none;">

<div class="title"><div style="display:block; padding:5px 5px 5px 25px; background:#FBDB85; margin:5px 0 5px; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; text-align:center;"><img src="{THEME}/images/failed.png" align="top" alt="�������������� �������" /> <span style="vertical-align:middle;">�������������� �������</span></div></div>

        <table width="90%" class="text" style="margin-left:15px;">
                <tr valign="middle">
                  <td width="133" height="25">��� E-Mail</td>
                  <td height="25" width="443">
				<input name="email" value="{editmail}" class="f_input" style="margin:5px 0;" size="30" ><br />{hidemail}</td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="10">�</td>
                  <td height="10" width="443">�</td>
                </tr>
				<tr valign="middle">
                  <td width="133" height="25">���� ���</td>
                  <td height="25" width="443">
					<input type="text" name="fullname" value="{fullname}" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25"><nobr>����� ����������</nobr></td>
                  <td height="25" width="443">
					<input type="text" name="land" value="{land}" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">����� ICQ</td>
                  <td height="25" width="443">
					<input type="text" name="icq" value="{icq}" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">������ ������</td>
                  <td height="25" width="443">
					<input type="password" name="altpass" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">����� ������</td>
                  <td height="25" width="443">
					<input type="password" name="password1" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">��������� ����� ������</td>
                  <td height="25" width="443">
					<input type="password" name="password2" class="f_input" style="margin:5px 0;" size="29" /></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25" valign="top">���������� �� IP</td>
                  <td height="25" width="443">
					<input type="text" name="allowed_ip" value="{allowed-ip}" class="f_input" style="margin:5px 0;" size="29" /> ��� ������� IP: {ip}
					<br />
					<font style="color:#FF0000;font-size:10px">* ��������! ������ ��������� ��� ��������� ������ ���������. ������ � ������ �������� ����� �������� ������ � ���� IP ������ ��� �������, ������� �� �������. ������: 192.48.25.71 ��� 129.42.*.*</font></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="10">�</td>
                  <td height="10" width="443">�</td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">������:</td>
                  <td height="25" width="443">
					<input type="file" name="image" size="33" /><br />
				<input type="checkbox" name="del_foto" value="yes" />��������� ����������</td>
                </tr>
                </tr>
                <tr valign="middle">
                  <td width="133" height="10">�</td>
                  <td height="10" width="443">�</td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">� ����</td>
                  <td height="25" width="443">
					<textarea name="info" class="f_input" style="margin:5px 0; height:50px; resize:vertical;" cols="40">{editinfo}</textarea></td>
                </tr>
                <tr valign="middle">
                  <td width="133" height="25">�������</td>
                  <td height="25" width="443">
					<textarea name="signature" class="f_input" style="margin:5px 0; height:50px; resize:vertical;" cols="40">{editsignature}</textarea></td>
                </tr>
{xfields}
                <tr valign="middle">
                    <td height="25" width="133"></td>
                    <td height="25" width="443">
                    <button type="submit" name="I1" name="submit" class="submit2" style="margin:5px 0; width:230px;" onclick="doAddComments();return false;">�������������</button></td>
                </tr>
              </table>
<div style="padding-top:10px;"></div>
</div>
[/not-logged]