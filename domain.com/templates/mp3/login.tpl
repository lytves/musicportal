<?
if ($is_logged == TRUE){
    $login_panel .= <<<HTML
<style type="text/css">.script{display:none;}</style>
<script type="text/javascript" language="JavaScript">
document.write('<style type="text/css">.noscript{display:none;} .script{display:inherit;} </style>');
</script>

<div style="float:right">
	<div class="script"><ul class="reset loginbox">
		<li class="loginbtn">
			<a class="lbn" id="logbtn" href="#"><b>{$member_id['name']}</b></a>
			<a class="thide lexit" href="{$link_logout}">�����</a>
				<div id="logform" class="radial">
				<ul class="reset loginenter">
					<li><a href="{$link_profile}" title="��� ������� �� �����">��� �������</a></li>
					<li><a href="/music/massaddfiles.html" title="��������� mp3">��������� mp3</a></li>
					<li><a href="/music/mytracks.html" title="��� ����������� mp3-�����">��� ����������� mp3</a></li>
					<li><a href="/music/myfavalbums.html" title="��� ��������� �������">��� ��������� �������</a></li>
					<li><a href="/music/myfavartists.html" title="��� ������� �����������">��� ������� �����������</a></li>
HTML;
					
	if ($user_group[$member_id['user_group']]['allow_admin']) {
		$login_panel .= <<<HTML
		<li><a href="{$adminlink}" target="_blank" style="color:#cc0000; font-weight:bold">�����������</a></li>
HTML;
	} else {
		$login_panel .= <<<HTML
		<li><a href="/feedback.html" title="�������� ������ ������������� �����">�������� �����</a></li>
HTML;
	}
	
	$login_panel .= <<<HTML
			</ul>
			</div>
		</li>
		<li class="lvsep"><a href="/music/myfavtracks.html" title="��� ��������� mp3">��� ��������� mp3</a></li>
		<li class="lvsep"><a class="radial" href="{$link_pm}">{$member_id['pm_unread']} </a><a href="{$link_pm}">���������</a></li>
	</ul></div>
	</div>
	
	<noindex>
	<noscript>
	<div class="noscript"><div style="float:right">
    <ul class="reset loginbox">
      <li class="loginbtn"><a href="$link_profile" class="lbn" title="��� ������� �� �����"><b>{$member_id['name']}</b></a><a class="thide lexit" href="{$link_logout}">�����</a></li>
      <li class="lvsep"><a href="/music/myfavtracks.html" title="��� ��������� mp3">��� ��������� mp3</a></li>
      <li class="lvsep"><a class="radial" href="{$link_pm}">{$member_id['pm_unread']} </a><a href="{$link_pm}">���������</a></li></div>
    </ul>
	</div></div>
	</noscript>
	</noindex>
HTML;
} else {
    $login_panel .= <<<HTML
<script language="javascript" src="/engine/modules/vauth/styles/facebox.js"></script>
<link rel="stylesheet" type="text/css" href="/engine/modules/vauth/styles/facebox.css" />
<link media="screen" type="text/css" rel="stylesheet" href="/engine/modules/vauth/styles/vauth_loginform.css"></link>

<script language="javascript">
	$(document).ready(function() {

		$('a[rel*=facebox]').facebox();

	})
</script>

  <div style="float:right">
  <ul class="reset loginbox">	
    <li class="loginbtn">
    <script>
      document.write("<a class='lbn' href='#virtual_loginform' rel='facebox'><b style='background-position: 100% -215px; padding: 0 10px;'>���� / �����������</b></a>")
    </script>
    <noscript>
      <a class="lbn" href="/krolik.html"><b style="background-position: 100% -215px; padding: 0 10px;">����</b></a><a class="lbn" href="/password.html"><b style="background-position: 100% -215px; padding: 0 10px;">���������</b></a><a class="lbn" href="/newkrolik.html"><b style="background-position: 100% -215px; padding: 0 10px;">�����������</b></a>
    </noscript>
    </li>
		<li class="lvsep">
      <a href="/feedback.html" title="�������� ������ ������������� �����">�������� �����</a>
    </li>
  </ul>
  </div>
	
<div style="display:none;" id="virtual_loginform">
<center style="color:#0085cf; font-family:georgia, serif;font-size:14pt; padding:5px;">����� �� ���� ��� ������:</center>
<a class="vkontakte_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=vkontakte"><span class="vk_fl">�</span>��������</a>
<a class="facebook_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=facebook">facebook</a>
<a class="odnoklassniki_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=odnoklassniki"><span class="odnoklass">�������������</span></a>
<a class="mail_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=mail">���<font color="#faa61a">@</font>mail<font color="#faa61a">.ru</font></a>
<a class="twitter_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=twitter"><span class="tw_text_dialog">twitter</span><span class="bird"><img src="/engine/modules/vauth/styles/twitter_newbird_blue_small.png" /></span></a>
<a class="instagram_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=instagram"><span class="instagr_dialog">Instagram</span></a>
<a class="google_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=google">Google<b>+</b></a>
<a class="yandex_dialog buttons_hover" onclick="ShowLoading('');" href="/engine/modules/vauth/auth.php?auth_site=yandex"><span>�</span>�����</a>


<center style="color:#0085cf; font-family:georgia, serif; font-size:14pt; padding:5px; margin-top:190px;"><hr color="#0085CF" />����� �� ����������� �����������:</center>

<form action="" method="post" class="loginform_vauth">

<div class="recower" data-title="�������������� �������� ������"><a href="/password.html"><img src="/templates/mp3/images/lostpassword.png" /></a></div>

<div id="security" data-title="������ ����������� ������">
<label ><input type="checkbox" value="1" onchange="if ($('.popup #login_password').get(0).type=='text') {($('.popup #login_password').get(0).type='password'); $('.popup #login_password').focus(); $('.popup #security').attr('data-title','�������� ����������� ������');} else {($('.popup #login_password').get(0).type='text'); $('.popup #security').attr('data-title','������ ����������� ������');}" name="k"><span></span></label>
</div>

 <!--[if IE]>
<style type="text/css">
#security {display:none;}
</style>
<![endif]-->

<input name="login" type="hidden" id="login" value="submit" />
<input class="login_input" name="login_name" type="text" value="�����" onFocus='if(this.value=="�����")this.value="";' onBlur='if(this.value=="")this.value="�����";' />
<input class="login_input" name="login_password" id="login_password" type="text" value="������" autocomplete="off" onFocus='if(this.value=="������")this.value="";' onBlur='if(this.value=="")this.value="������";' />

<button class="submit2 mybutton" onclick="submit();" type="submit" title="�����"><span>�����</span></button>
</form>

<div id="register" data-title="������������������ �� ����� domain.com"><hr color="#0085CF" /><a href="/newkrolik.html">����������� �� �����</a></div>
</div>
HTML;

}
?>
