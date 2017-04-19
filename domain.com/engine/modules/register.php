<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if( $is_logged ) header("location: {$config['http_home_url']}");
else {

require_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$parse->allow_url = false;
$parse->allow_image = false;
$stopregistration = FALSE;

if( isset( $_REQUEST['doaction'] ) ) $doaction = $_REQUEST['doaction']; else $doaction = "";
$config['reg_group'] = intval( $config['reg_group'] ) ? intval( $config['reg_group'] ) : 4;

function check_reg ($reg_quest,$quest_id, $name, $email, $password1, $sec_code=1, $sec_code_session=1) {

	global $lang, $db, $banned_info;
	$stop = "";
	
	if( $sec_code != $sec_code_session or ! $sec_code_session ) $stop .= $lang['reg_err_19'];
	if( strlen( $password1 ) < 4 ) $stop .= $lang['reg_err_2'];
	if( strlen( $name ) > 20 ) $stop .= $lang['reg_err_3'];
	if( strlen( $name ) < 2 ) $stop .= $lang['reg_ers_3'];
	if( preg_match( "/[\||\'|\<|\>|\[|\]|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\{\+]/", $name ) ) $stop .= $lang['reg_err_4'];
	if( empty( $email ) OR strlen( $email ) > 50 OR @count(explode("@", $email)) != 2) $stop .= $lang['reg_err_6'];
	if( $name == "" ) $stop .= $lang['reg_err_7'];
	if (strpos( strtolower ($name) , '.php' ) !== false) $stop .= $lang['reg_err_4'];
	
	$quest = $db->super_query("SELECT * from ". PREFIX ."_quest Where id='{$quest_id}'");
  if ($quest_id <= '0' or !$quest['id']) die('Hacking attempt или как там? Вообщем нет такого id ))');
  if(empty($reg_quest) or $reg_quest != strtolower($quest['answer'])) $stop .= $lang['reg_err_6-2'];

	if( count( $banned_info['name'] ) ) foreach ( $banned_info['name'] as $banned ) {
		
		$banned['name'] = str_replace( '\*', '.*', preg_quote( $banned['name'], "#" ) );
		
		if( $banned['name'] and preg_match( "#^{$banned['name']}$#i", $name ) ) {
			
			if( $banned['descr'] ) {
				$lang['reg_err_21'] = str_replace( "{descr}", $lang['reg_err_22'], $lang['reg_err_21'] );
				$lang['reg_err_21'] = str_replace( "{descr}", $banned['descr'], $lang['reg_err_21'] );
			} else
				$lang['reg_err_21'] = str_replace( "{descr}", "", $lang['reg_err_21'] );
			
			$stop .= $lang['reg_err_21'];
		}
	}
	
	if( count( $banned_info['email'] ) ) foreach ( $banned_info['email'] as $banned ) {
		
		$banned['email'] = str_replace( '\*', '.*', preg_quote( $banned['email'], "#" ) );
		
		if( $banned['email'] and preg_match( "#^{$banned['email']}$#i", $email ) ) {
			
			if( $banned['descr'] ) {
				$lang['reg_err_23'] = str_replace( "{descr}", $lang['reg_err_22'], $lang['reg_err_23'] );
				$lang['reg_err_23'] = str_replace( "{descr}", $banned['descr'], $lang['reg_err_23'] );
			} else
				$lang['reg_err_23'] = str_replace( "{descr}", "", $lang['reg_err_23'] );
			
			$stop .= $lang['reg_err_23'];
		}
	}
	
	if( $stop == "" ) {
		$replace_word = array ('e' => '[eеё]', 'r' => '[rг]', 't' => '[tт]', 'y' => '[yу]', 'u' => '[uи]', 'i' => '[i1l!]', 'o' => '[oо0]', 'p' => '[pр]', 'a' => '[aа]', 's' => '[s5]', 'w' => 'w', 'q' => 'q', 'd' => 'd', 'f' => 'f', 'g' => '[gд]', 'h' => '[hн]', 'j' => 'j', 'k' => '[kк]', 'l' => '[l1i!]', 'z' => 'z', 'x' => '[xх%]', 'c' => '[cс]', 'v' => '[vuи]', 'b' => '[bвь]', 'n' => '[nпл]', 'm' => '[mм]', 'й' => '[йиu]', 'ц' => 'ц', 'у' => '[уy]', 'е' => '[еeё]', 'н' => '[нh]', 'г' => '[гr]', 'ш' => '[шwщ]', 'щ' => '[щwш]', 'з' => '[з3э]', 'х' => '[хx%]', 'ъ' => '[ъь]', 'ф' => 'ф', 'ы' => '(ы|ь[i1l!]?)', 'в' => '[вb]', 'а' => '[аa]', 'п' => '[пn]', 'р' => '[рp]', 'о' => '[оo0]', 'л' => '[лn]', 'д' => 'д', 'ж' => 'ж', 'э' => '[э3з]', 'я' => '[я]', 'ч' => '[ч4]', 'с' => '[сc]', 'м' => '[мm]', 'и' => '[иuй]', 'т' => '[тt]', 'ь' => '[ьb]', 'б' => '[б6]', 'ю' => '(ю|[!1il][oо0])', 'ё' => '[ёеe]', '1' => '[1il!]', '2' => '2', '3' => '[3зэ]', '4' => '[4ч]', '5' => '[5s]', '6' => '[6б]', '7' => '7', '8' => '8', '9' => '9', '0' => '[0оo]', '_' => '_', '#' => '#', '%' => '[%x]', '^' => '[^~]', '(' => '[(]', ')' => '[)]', '=' => '=', '.' => '[.]', '-' => '-' );
		$name = strtolower( $name );
		$search_name = strtr( $name, $replace_word );
		
		$row = $db->super_query( "SELECT COUNT(*) as count FROM " . USERPREFIX . "_users WHERE email = '$email' OR LOWER(name) REGEXP '[[:<:]]{$search_name}[[:>:]]' OR name = '$name'" );
		
		if( $row['count'] ) $stop .= $lang['reg_err_8'];
	}
	
	return $stop;

}

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . USERPREFIX . "_users" );

if( $config['allow_registration'] != "yes" ) {
	
	msgbox( $lang['all_info'], $lang['reg_err_9'] );
	$stopregistration = TRUE;

} elseif( $config['max_users'] > 0 and $row['count'] > $config['max_users'] ) {
	
	msgbox( $lang['all_info'], $lang['reg_err_10'] );
	$stopregistration = TRUE;

}

if( isset( $_POST['submit_reg'] ) ) {
	
	if( $config['allow_sec_code'] == "yes" ) {
		$sec_code = $_POST['sec_code'];
		$sec_code_session = ($_SESSION['sec_code_session'] != '') ? $_SESSION['sec_code_session'] : false;
	} else {
		$sec_code = 1;
		$sec_code_session = 1;
	}
	
	$password1 = $_POST['password1'];
	$name = $db->safesql( $parse->process( htmlspecialchars( trim( $_POST['name'] ) ) ) );
	$reg_quest = $db->safesql($parse->process(htmlspecialchars(trim(strtolower($_POST['reg_quest'])))));
  $quest_id = $db->safesql(intval($_POST['quest_id']));
	$email = $db->safesql( $parse->process( substr($_POST['email'], 0,50) ) );
	
	$reg_error = check_reg ($reg_quest,$quest_id,$name, $email, $password1, $sec_code, $sec_code_session);

	
	if( ! $reg_error ) {
		
		if( $config['registration_type'] ) {
			
			include_once ENGINE_DIR . '/classes/mail.class.php';
			$mail = new dle_mail( $config );
			
			$row = $db->super_query( "SELECT template FROM " . PREFIX . "_email where name='reg_mail' LIMIT 0,1" );
			
			$row['template'] = stripslashes( $row['template'] );
			
			$idlink = rawurlencode(base64_encode($name."||".$email."||".md5($password1)."||".md5(md5($name.$email.DBHOST.DBNAME.$config['key']))."||".$quest_id."||".$reg_quest));
			
			$row['template'] = str_replace( "{%username%}", $name, $row['template'] );
			$row['template'] = str_replace( "{%validationlink%}", $config['http_home_url'] . "index.php?do=register&doaction=validating&id=" . $idlink, $row['template'] );
			$row['template'] = str_replace( "{%password%}", $password1, $row['template'] );
			
			$mail->send( $email, $lang['reg_subj'], $row['template'] );
			
			if( $mail->send_error ) msgbox( $lang['all_info'], $mail->smtp_msg );
			else msgbox( $lang['reg_vhead'], $lang['reg_vtext'] );
			
			$_SESSION['sec_code_session'] = false;
			
			$stopregistration = TRUE;
		
		} else {
			
			$doaction = "validating";
			$_REQUEST['id'] = rawurlencode(base64_encode($name."||".$email."||".md5($password1)."||".md5(md5($name.$email.DBHOST.DBNAME.$config['key']))."||".$quest_id."||".$reg_quest));
		}
	
	} else {
		msgbox( $lang['reg_err_11'], "<ul>" . $reg_error . "</ul>" );
		
	}

}

if( $doaction != "validating" and ! $stopregistration ) {
	
	if( $_POST['dle_rules_accept'] == "yes" ) {
		
		@session_register( 'dle_rules_accept' );
		$_SESSION['dle_rules_accept'] = "1";
	
	}
	
	if( $config['registration_rules'] and ! $_SESSION['dle_rules_accept'] ) {
		
		$_GET['page'] = "dle-rules-page";
		include ENGINE_DIR . '/modules/static.php';
	
	} else {
		
		$tpl->load_template( 'registration.tpl' );
		
		$tpl->set( '[registration]', "" );
		$tpl->set( '[/registration]', "" );
		$tpl->set_block( "'\\[validation\\](.*?)\\[/validation\\]'si", "" );
		$path = parse_url( $config['http_home_url'] );
		$quest=$db->super_query("SELECT * from ". PREFIX ."_quest order by rand() limit 1");
    $tpl->set('{quest}',$quest['quest']);
		
		//// изменения для сохранения данных в формах при возврате на страницу регистрации после какой-то ошибки и выделение красным той формы
		if( $reg_error ) {
      $tpl->set('{2login}', $name);
      $tpl->set('{2password}', $password1);
      $tpl->set('{2email}', $email);
      
      $stop_err = explode( "</li>", str_replace('<li>','',$reg_error) );
    
      if (is_array($stop_err)) {
        if ( (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_3']))), $stop_err)) || (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_4']))), $stop_err)) || (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_7']))), $stop_err)) || (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_8']))), $stop_err)) )
          $tpl->set('{jquery_login}', '<script type="text/javascript">$("#name").css({\'border\':\'2px solid #cc0000\'});</script>');
        else $tpl->set('{jquery_login}', '<script type="text/javascript">$("#name").css({\'border\':\'1px solid #9FD680\'});</script>');
        
        if ( (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_2']))), $stop_err)) || (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_5']))), $stop_err)) )
          $tpl->set('{jquery_password}', '<script type="text/javascript">$("#password1").css({\'border\':\'2px solid #cc0000\'});</script>');
        else $tpl->set('{jquery_password}', '<script type="text/javascript">$("#password1").css({\'border\':\'1px solid #9FD680\'});</script>');
        
        if ( (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_6']))), $stop_err)) || (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_8']))), $stop_err)) )
          $tpl->set('{jquery_email}', '<script type="text/javascript">$("#email").css({\'border\':\'2px solid #cc0000\'});</script>');
        else $tpl->set('{jquery_email}', '<script type="text/javascript">$("#email").css({\'border\':\'1px solid #9FD680\'});</script>');
          
        if (in_array(str_replace('</li>','',(str_replace('<li>','',$lang['reg_err_6-2']))), $stop_err))
          $tpl->set('{jquery_reg_quest}', '<script type="text/javascript">$("#reg_quest").css({\'border\':\'2px solid #cc0000\'});</script>');
        else $tpl->set('{jquery_reg_quest}', '');
        
        
      } else {
        $tpl->set('{jquery_login}', '');
        $tpl->set('{jquery_password}', '');
        $tpl->set('{jquery_email}', '');
        $tpl->set('{jquery_reg_quest}', '');
      }
		} else {
      $tpl->set('{2login}', '');
      $tpl->set('{jquery_login}', '');
      $tpl->set('{2password}', '');
      $tpl->set('{jquery_password}', '');
      $tpl->set('{2email}', '');
      $tpl->set('{jquery_email}', '');
      $tpl->set('{jquery_reg_quest}', '');
		}
		//// конец изменений для сохранения данных в формах при возврате на страницу регистрации после какой-то ошибки и выделение красным той формы
		
		if( $config['allow_sec_code'] == "yes" ) {
			$tpl->set( '[sec_code]', "" );
			$tpl->set( '[/sec_code]', "" );
			$tpl->set( '{reg_code}', "<span id=\"dle-captcha\"><img src=\"" . $path['path'] . "engine/modules/antibot.php\" alt=\"{$lang['sec_image']}\" border=\"0\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>" );
		} else {
			$tpl->set( '{reg_code}', "" );
			$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
		}
		
		$tpl->copy_template = "<form  method=\"post\" name=\"registration\" onsubmit=\"if (!check_reg_daten()) {return false;};\" id=\"registration\" action=\"" . $config['http_home_url'] . "index.php?do=register\">\n" . $tpl->copy_template . "
		<input name=\"quest_id\" type=\"hidden\" id=\"quest_id\" value=\"{$quest['id']}\" />

<input name=\"submit_reg\" type=\"hidden\" id=\"submit_reg\" value=\"submit_reg\" />
</form>";
		
		$tpl->copy_template .= <<<HTML
<script language='javascript' type="text/javascript">
<!--
function reload () {

	var rndval = new Date().getTime(); 

	document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" width="120" height="50" alt="" /><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
function check_reg_daten () {

	if(document.forms.registration.name.value.length < 2) {

		alert('{$lang['reg_err_30']}');return false;

	}
	
	if(document.forms.registration.password1.value.length < 4) {

		alert('{$lang['reg_err_31']}');return false;

	}

	if(document.forms.registration.email.value == '') {

		alert('{$lang['reg_err_33']}');return false;

	}

		if(document.forms.registration.reg_quest.value == '') {

		alert('{$lang['reg_err_30-2']}');return false;

	}

return true;

};
//-->
</script>
HTML;
		
		$tpl->compile( 'content' );
		$tpl->clear();
	
	}

}

if( ($doaction == "validating") and (! $stopregistration) ) {
	
	$user_arr = explode( "||", base64_decode( @rawurldecode( $_REQUEST['id'] ) ) );
	
	$regpassword = md5( $user_arr[2] );
	$name = trim( $db->safesql( htmlspecialchars( $parse->process( $user_arr[0] ) ) ) );
	$email = trim( $db->safesql( $parse->process( $user_arr[1] ) ) );
	$quest_id=trim($db->safesql($parse->process($user_arr[4])));
$reg_quest=trim($db->safesql($parse->process($user_arr[5])));
	
	if( md5( md5( $name . $email . DBHOST . DBNAME . $config['key'] ) ) != $user_arr[3] ) die( 'ID not valid!' );
	
$reg_error = check_reg($reg_quest,$quest_id,$name, $email, $regpassword);
	
	if( $reg_error != "" ) {
		msgbox( $lang['reg_err_11'], $reg_error );
		$stopregistration = TRUE;
	} else {
		
		if( ($_REQUEST['step'] != 2) and $config['registration_type'] ) {
			$stopregistration = TRUE;
			$lang['confirm_ok'] = str_replace( '{email}', $email, $lang['confirm_ok'] );
			$lang['confirm_ok'] = str_replace( '{login}', $name, $lang['confirm_ok'] );
			msgbox( $lang['all_info'], $lang['confirm_ok'] . "<br /><br /><a href=\"" . $config['http_home_url'] . "index.php?do=register&doaction=validating&step=2&id=" . rawurlencode( $_REQUEST['id'] ) . "\">" . $lang['reg_next'] . "</a>" );
		} else {
			
			$add_time = time() + ($config['date_adjust'] * 60);
			$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
			if( intval( $config['reg_group'] ) < 3 ) $config['reg_group'] = 4;
			
			$db->query( "INSERT INTO " . USERPREFIX . "_users (name, password, email, reg_date, lastdate, user_group, info, signature, favorites, xfields, logged_ip) VALUES ('$name', '$regpassword', '$email', '$add_time', '$add_time', '" . $config['reg_group'] . "', '', '', '', '', '" . $_IP . "')" );
			$id = $db->insert_id();
			
			set_cookie( "dle_user_id", $id, 365 );
			set_cookie( "dle_password", $user_arr[2], 365 );
			
			@session_register( 'dle_user_id' );
			@session_register( 'dle_password' );
			
			$_SESSION['dle_user_id'] = $id;
			$_SESSION['dle_password'] = $user_arr[2];
			
			//отправка письма на мыло при регистрации
			$is_fakemail = (stripos($email, "@fakemail"));
			$is_fakemail2 = (stripos($email, "@odnoklassniki"));
			
			if (($is_fakemail === false) && ($is_fakemail2 === false)) {
			include_once ENGINE_DIR . '/classes/mail.class.php';
			$mail = new dle_mail( $config );
			
			$row = $db->super_query( "SELECT template FROM " . PREFIX . "_email where name='reg_sendmail' LIMIT 0,1" );
			
			$row['template'] = stripslashes( $row['template'] );
			
			$row['template'] = str_replace( "{%username%}", $name, $row['template'] );
			$row['template'] = str_replace( "{%password%}", $password1, $row['template'] );
			
			$mail->send( $email, 'Регистрация на Музыкальном сайте domain.com', $row['template'] );
			}
			//конец
			
      header("location: {$config['http_home_url']}");

    }
	
	}

}
}
?>
