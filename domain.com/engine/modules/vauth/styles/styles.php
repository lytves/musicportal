<?PHP

	// ** ����� ������� ������������
	$style['userinfo'] = '<link media="screen" type="text/css" rel="stylesheet" href="/engine/modules/vauth/styles/vauth_userinfo.css" />';
	
	
	// ** ����� �������	
	$style['admin'] = '<link media="screen" type="text/css" rel="stylesheet" href="/engine/modules/vauth/styles/vauth_admin.css" />';

	// ** ����� ����� ������� ������ ��� �����������
	
	$style_loginform = '<link media="screen" type="text/css" rel="stylesheet" href="/engine/modules/vauth/styles/vauth_regform.css" />';

	$user_regform['head'] = '
	
		<html><head><meta http-equiv="Content-Type" content="text/html;charset=windows-1251" />
		</head> ' . $style_loginform . ' <body><div id="wrap"><form method="post" action="/engine/modules/vauth/auth.php?auth_site=';
		
		$user_regform['head_2']= '">';
		$user_regform['login'] = '<div class="login_text">'.$vauth_text['loginform_login'].'</div><input id=\'name\' class="login" name="login">
		
		<input id="chek" title="��������� ����������� ������ ��� �����������" onclick="CheckLogin(); return false;" type=button value="��������� ���"><div id="result-registration"></div>';
		
		$user_regform['email'] = '<div class="login_text">'.$vauth_text['loginform_email'].'</div><input class="login" name="email">';
		$user_regform['password'] = '<div class="login_text">'.$vauth_text['loginform_passw'].'</div><input class="login" name="password">';
		
		$user_regform['end'] = '
			<input type="hidden" value="1" name="regform">
			<input class="send" type="submit" value="'.$vauth_text['loginform_send'].'"></form>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
			<script type="text/javascript" src="/engine/classes/js/dle_js.js"></script>

			<script language="javascript" type="text/javascript">
			<!--
			var dle_root       = "/";
			//-->
			</script>
			
			</div></body></html>';
			
	// ** ����� auth.php
		$style_3='
				<body>
				<link media="screen" type="text/css" rel="stylesheet" href="/engine/modules/vauth/styles/vauth_main.css" />
					<center>	<h1 id="hi"><div id="site">Hello, user!</div></h1>	</center><br/>
					
					<div id="login">
						<a href="/">Main page</a>
						<a href="auth.php?auth_site=vkontakte">[ vkontakte ]</a>
						<a href="auth.php?auth_site=facebook">[ facebook ]</a>
						<a href="auth.php?auth_site=twitter">[ twitter ]</a>
						<a href="auth.php?auth_site=foursquare">[ foursquare ]</a>
						<a href="auth.php?auth_site=odnoklassniki">[ odnoklassniki ]</a>
						<a href="auth.php?auth_site=instagram">[ instagram ]</a>
						<a href="auth.php?auth_site=github">[ instagram ]</a>
						<a href="auth.php?auth_site=google">[ google+ ]</a>
						<a href="auth.php?auth_site=mail">[ ��� ��� ]</a>
						<a href="auth.php?auth_site=microsoft">[ windows live ]</a>
						<a href="clearsessions.php">Clear sessions</a>
					</div>
					
					<div id="footer">
						<center>DLE VAuth 1.8 by lifeart, social networks autorization module main page. <a href="http://vk.com/vauth">Support Virtual Auth</a></center>
					</div>
					
				</body>';
				
	// ** ��� �������� ���������� ��������� (����� ��������� � ������� ac_connect.tpl)
	
		$txt_vk_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="vk" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[10].'</span></button>
		</form>';
		
		$txt_vk_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="vk_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[11].'</span></button>
		</form>';
		
		$txt_fb_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="fb" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[12].'</span></button>
		</form>';
		
		$txt_fb_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="fb_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[13].'</span></button>
		</form>';
		
		$txt_tw_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="tw" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[141].'</span></button>
		</form>';
		
		$txt_tw_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="tw_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text[142].'</span></button>
		</form>';
		
		$txt_fs_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="fs" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_fs'].'</span></button>
		</form>';
		
		$txt_fs_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="fs_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_fs'].'</span></button>
		</form>';

		$txt_od_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="od" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_od'].'</span></button>
		</form>';		
		
		$txt_od_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="od_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_od'].'</span></button>
		</form>';
		
		$txt_in_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="in" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_in'].'</span></button>
		</form>';		
		
		$txt_in_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="in_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_in'].'</span></button>
		</form>';
		
		$txt_go_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="go" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_go'].'</span></button>
		</form>';		
		
		$txt_go_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="go_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_go'].'</span></button>
		</form>';
		
		$txt_ms_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ms_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_ms'].'</span></button>
		</form>';
		
		
		$txt_ms_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ms" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_ms'].'</span></button>
		</form>';			
			
		$txt_ma_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ma_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_ma'].'</span></button>
		</form>';
		
		
		$txt_ma_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ma" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_ma'].'</span></button>
		</form>';
		
		$txt_gh_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="gh_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_gh'].'</span></button>
		</form>';
		
		
		$txt_gh_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="gh" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_gh'].'</span></button>
		</form>';
		
				$txt_ya_off	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ya_off" />
			<button class="dle_connect_off" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['di_ya'].'</span></button>
		</form>';
		
		
		$txt_ya_on	=
		
		'<form  method="post" name="ac_connect">
			<input class="dle_connect"  name="dle_connect" type="hidden" id="connect" value="ya" />
			<button class="dle_connect" onclick="ShowLoading(\'\');document.getElementById(\'ac_connect\').style.display=\'none\';submit();" type="submit" title="���������"><span>'.$vauth_text['en_ya'].'</span></button>
		</form>';
		
		
?>