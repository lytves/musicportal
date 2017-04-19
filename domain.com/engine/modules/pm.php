<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

require_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );
$parse->safe_mode = true;
$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
$parse->allow_image = false;

$stop_pm = FALSE;
if( isset( $_REQUEST['doaction'] ) ) $doaction = $_REQUEST['doaction'];
else { 
  $doaction = "";
  $pm_title = "Входящие";
}
if( ! $is_logged or ! $user_group[$member_id['user_group']]['allow_pm'] ) {
	msgbox( $lang['all_err_1'], $lang['pm_err_1'] );
	$stop_pm = TRUE;
}

if( $member_id['pm_all'] >= $user_group[$member_id['user_group']]['max_pm'] and ! $stop_pm ) {
	msgbox( $lang['all_info'], $lang['pm_err_9'] );
}

$tpl->load_template( 'pm.tpl' );

$tpl->set( '[inbox]', "<a href=\"".$config['http_home_url']."pm/\">" );
$tpl->set( '[/inbox]', "</a>" );
$tpl->set( '[outbox]', "<a style=\"color:#FF00B2;\" href=\"$PHP_SELF?do=pm&amp;doaction=outbox\">" );
$tpl->set( '[/outbox]', "</a>" );
$tpl->set( '[new_pm]', "<a style=\"color:#1ECE00;\" href=\"$PHP_SELF?do=pm&amp;doaction=newpm\">" );
$tpl->set( '[/new_pm]', "</a>" );

$tpl->copy_template = "
    <SCRIPT LANGUAGE=\"JavaScript\">
    function confirmDelete(url){
    var agree=confirm(\"" . $lang['pm_confirm'] . "\");
    if (agree)
    document.location=url;
    }
    </SCRIPT>" . $tpl->copy_template;

if( isset( $_POST['send'] ) and ! $stop_pm ) {
	
	$name = $db->safesql( $parse->process( trim( $_POST['name'] ) ) );
	$subj = $db->safesql( $parse->process( trim( $_POST['subj'] ) ) );
	
	$stop = "";
	
	if( $config['allow_comments_wysiwyg'] != "yes" ) $comments = $db->safesql( $parse->BB_Parse( $parse->process( trim( $_POST['comments'] ) ), false ) );
	else {
		$parse->wysiwyg = true;
		
		if( strlen( $_POST['comments'] ) < 18 ) $_POST['comments'] = "";
		
		if( $user_group[$member_id['user_group']]['allow_url'] ) $parse->ParseFilter( Array ('div', 'a', 'span', 'p', 'br', 'strong', 'em', 'ul', 'li', 'ol' ), Array (), 0, 1 );
		else $parse->ParseFilter( Array ('div', 'span', 'p', 'br', 'strong', 'em', 'ul', 'li', 'ol' ), Array (), 0, 1 );
		
		$comments = $db->safesql( $parse->BB_Parse( $parse->process( trim( $_POST['comments'] ) ) ) );
	}
	
	if( empty( $name ) or empty( $subj ) or $comments == "" ) $stop .= $lang['pm_err_2'];
	
	if( strlen( $subj ) > 250 ) {
		$stop .= $lang['pm_err_3'];
	}

	if( $parse->not_allowed_tags ) {
		
		$stop .= "<li>" .$lang['news_err_33']. "</li>";
	}

	if( $parse->not_allowed_text ) {
		
		$stop .= "<li>" . $lang['news_err_37']. "</li>";
	}
	
	if( $user_group[$member_id['user_group']]['captcha'] and ($_REQUEST['sec_code'] != $_SESSION['sec_code_session'] or ! $_SESSION['sec_code_session']) ) {
		
		$stop .= "<li>" . $lang['news_err_30'] . "</li>";
	
	}
	
	$db->query( "SELECT email, name, user_id, pm_all, user_group FROM " . USERPREFIX . "_users where name = '$name'" );
	
	if( ! $db->num_rows() ) $stop .= $lang['pm_err_4'];
	
	$row = $db->get_row();
	$db->free();
	
	if( $stop == "" and ($row['pm_all'] >= $user_group[$row['user_group']]['max_pm']) and $member_id['user_group'] != 1 ) {
		$stop .= $lang['pm_err_8'];
	}
	
	if( ! $stop ) {
		
		$_SESSION['sec_code_session'] = 0;
		
		$time = time() + ($config['date_adjust'] * 60);
		
		$db->query( "INSERT INTO " . USERPREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) values ('$subj', '$comments', '$row[user_id]', '$member_id[name]', '$time', 'no', 'inbox')" );
		
		$db->query( "UPDATE " . USERPREFIX . "_users set pm_all=pm_all+1, pm_unread=pm_unread+1  where user_id='$row[user_id]'" );
		
		if( intval( $_REQUEST['outboxcopy'] ) ) {
			
			$db->query( "INSERT INTO " . USERPREFIX . "_pm (subj, text, user, user_from, date, pm_read, folder) values ('$subj', '$comments', '$row[user_id]', '$member_id[name]', '$time', 'yes', 'outbox')" );
			$db->query( "UPDATE " . USERPREFIX . "_users set pm_all=pm_all+1 where user_id='$member_id[user_id]'" );
		
		}
		
		$replyid = intval( $_GET['replyid'] );
		
		if( $replyid ) {
			
			$db->query( "UPDATE " . USERPREFIX . "_pm SET reply=1 WHERE id= '$replyid'" );
		
		}
		
		if( $config['mail_pm'] ) {
			
			include_once ENGINE_DIR . '/classes/mail.class.php';
			$mail = new dle_mail( $config );
			
			$mail_template = $db->super_query( "SELECT template FROM " . PREFIX . "_email WHERE name='pm' LIMIT 0,1" );
			
			$mail_template['template'] = stripslashes( $mail_template['template'] );
			$mail_template['template'] = str_replace( "{%username%}", $row['name'], $mail_template['template'] );
			$mail_template['template'] = str_replace( "{%date%}", langdate( "j F Y H:i", $_TIME ), $mail_template['template'] );
			$mail_template['template'] = str_replace( "{%fromusername%}", $member_id['name'], $mail_template['template'] );
			$mail_template['template'] = str_replace( "{%title%}", strip_tags( stripslashes( $subj ) ), $mail_template['template'] );
			
			$body = str_replace( '\n', "", $comments );
			$body = str_replace( '\r', "", $body );
			
			$body = stripslashes( stripslashes( $body ) );
			$body = str_replace( "<br />", "\n", $body );
			$body = strip_tags( $body );
			
			$mail_template['template'] = str_replace( "{%text%}", $body, $mail_template['template'] );
			
			$mail->send( $row['email'], $lang['mail_pm'], $mail_template['template'] );
		
		}
		
		msgbox( $lang['all_info'], $lang['pm_sendok'] . " <a href=\"$PHP_SELF?do=pm&amp;doaction=newpm\">" . $lang['pm_noch'] . "</a> " . $lang['pm_or'] . " <a href=\"/pm/\">" . $lang['pm_main'] . "</a>" );
		$stop_pm = TRUE;
	
	} else
		msgbox( $lang['all_err_1'], "<ul>".$stop."</ul>" );

}

if( $doaction == "del" and ! $stop_pm ) {
	
	$delete_count = 0;
	
	if( $_REQUEST['dle_allow_hash'] == "" or $_REQUEST['dle_allow_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User ID not valid" );
	
	}
	
	if( $_GET['pmid'] ) {
		
		$pmid = intval( $_GET['pmid'] );
		$row = $db->super_query( "SELECT id, user, user_from, pm_read, folder FROM " . USERPREFIX . "_pm where id= '$pmid'" );
		
		if( ($row['user'] == $member_id['user_id'] and $row['folder'] == "inbox") or ($row['user_from'] == $member_id['name'] and $row['folder'] == "outbox") ) {
			$db->query( "DELETE FROM " . USERPREFIX . "_pm WHERE id='$row[id]'" );
			$delete_count ++;
			
			if( $row['pm_read'] != "yes" ) {
				$db->query( "UPDATE " . USERPREFIX . "_users set pm_unread=pm_unread-1 where user_id='$member_id[user_id]'" );
			}
			
			$db->query( "UPDATE " . USERPREFIX . "_users set pm_all=pm_all-1 where user_id='$member_id[user_id]'" );
		
		}
	
	} elseif( count( $_REQUEST['selected_pm'] ) ) {
		
		foreach ( $_REQUEST['selected_pm'] as $pmid ) {
			
			$pmid = intval( $pmid );
			$row = $db->super_query( "SELECT id, user, user_from, pm_read, folder FROM " . USERPREFIX . "_pm where id= '$pmid'" );
			
			if( ($row['user'] == $member_id['user_id'] and $row['folder'] == "inbox") or ($row['user_from'] == $member_id['name'] and $row['folder'] == "outbox") ) {
				$db->query( "DELETE FROM " . USERPREFIX . "_pm WHERE id='$row[id]'" );
				$delete_count ++;
				
				if( $row['pm_read'] != "yes" ) {
					$db->query( "UPDATE " . USERPREFIX . "_users set pm_unread=pm_unread-1 where user_id='$member_id[user_id]'" );
				}
				
				$db->query( "UPDATE " . USERPREFIX . "_users set pm_all=pm_all-1 where user_id='$member_id[user_id]'" );
			
			}
		
		}
	}
	
	if( $delete_count ) msgbox( $lang['all_info'], $lang['pm_delok'] . " <a href=\"$PHP_SELF?do=pm\">" . $lang['all_prev'] . "</a>." );
	else msgbox( $lang['all_err_1'], $lang['pm_err_5'] );

} elseif( $doaction == "readpm" and ! $stop_pm ) {
	
	$pmid = intval( $_GET['pmid'] );
	
	$tpl->set( '{pm_title}', "Просмотр" );
	
	$tpl->set( '[readpm]', "" );
	$tpl->set( '[/readpm]', "" );
	$tpl->set_block( "'\\[pmlist\\].*?\\[/pmlist\\]'si", "" );
	$tpl->set_block( "'\\[newpm\\].*?\\[/newpm\\]'si", "" );
	
	$db->query( "SELECT *, u.lastdate FROM " . USERPREFIX . "_pm LEFT JOIN " . USERPREFIX . "_users u ON user_from = u.name where id= '$pmid'" );
	$row = $db->get_row();
	
	if( $db->num_rows() < 1 ) {
		
		msgbox( $lang['all_err_1'], $lang['pm_err_6'] );
		$stop_pm = TRUE;
	
	} elseif( $row['user'] != $member_id['user_id'] and $row['user_from'] != $member_id['name'] ) {
		
		msgbox( $lang['all_err_1'], $lang['pm_err_7'] );
		$stop_pm = TRUE;
	
	} else {
		
		if( $row['user'] == $member_id['user_id'] and $row['pm_read'] != "yes" ) {
			
			$db->query( "UPDATE " . USERPREFIX . "_users set pm_unread=pm_unread-1  where user_id='$member_id[user_id]'" );
			
			$db->query( "UPDATE " . USERPREFIX . "_pm set pm_read='yes'  where id='$row[id]'" );
		
		}
		
		/// тут, немного выше (MySQL-запрос) и ниже ($status) изменения для онлайн статуса юзера
    if( time() < ($row['lastdate']+600))
    {
      $status = "<img src='{THEME}/images/status_green.png' title='Пользователь онлайн' alt='Пользователь онлайн' style='display:inline; vertical-align:middle;'>";
    } else
    $status = "<img src='{THEME}/images/status_red.png' title='Пользователь оффлайн' alt='Пользователь оффлайн' style='display:inline; vertical-align:middle;'>";
    ///конец статуса
    
		$tpl->set( '{subj}', stripslashes( $row['subj'] ) );
		$tpl->set( '{text}', stripslashes( $row['text'] ) );
		
		if( $config['allow_alt_url'] == "yes" ) $tpl->set( '{author}', "<a href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['user_from'] ) . "/\">" . $row['user_from'] . "</a>".$status );
		else $tpl->set( '{author}', "<a href=\"" . $config['http_home_url'] . "index.php?subaction=userinfo&amp;user=" . urlencode( $row['user_from'] ) . "\">" . $row['user_from'] . "</a>".$status );
		
		$tpl->set( '[reply]', "<a href=\"" . $config['http_home_url'] . "index.php?do=pm&amp;doaction=newpm&amp;replyid=" . $row['id'] . "\">" );
		$tpl->set( '[/reply]', "</a>" );
		
		$tpl->set( '[del]', "<a href=\"javascript:confirmDelete('" . $config['http_home_url'] . "index.php?do=pm&amp;doaction=del&amp;pmid=" . $row['id'] . "&amp;dle_allow_hash=" . $dle_login_hash . "')\">" );
		$tpl->set( '[/del]', "</a>" );
		
		$tpl->compile( 'content' );
		$tpl->clear();
	}

} elseif( $doaction == "newpm" and ! $stop_pm ) {
	
  $tpl->set( '{pm_title}', "Написать сообщение" );
	$ajax_form = <<<HTML
<span id="dle-pm-preview"></span>
<script language="javascript" type="text/javascript">
<!--
function PreviewCompleted(){
	var post_main_obj = document.getElementById( 'dle-pm-preview' );
	var post_box_top  = _get_obj_toppos( post_main_obj );

			if ( post_box_top )
			{
				scroll( 0, post_box_top - 70 );
			}

};

function dlePMPreview( ){ 

	var ajax = new dle_ajax();
	var varsString = "";

	if (dle_wysiwyg == "yes") {
		var pm_text = tinyMCE.get('comments').getContent(); 
	} else {
		var pm_text = document.getElementById('dle-comments-form').comments.value; 
	}

	if(document.getElementById('dle-comments-form').name.value == '' || document.getElementById('dle-comments-form').subj.value == '' || pm_text == '')
	{
		alert('{$lang['comm_req_f']}');return false

	}

	ajax.onShow ('');
	ajax.setVar("text", ajax.encodeVAR(pm_text));
	ajax.setVar("name", ajax.encodeVAR(document.getElementById('dle-comments-form').name.value));
	ajax.setVar("subj", ajax.encodeVAR(document.getElementById('dle-comments-form').subj.value));
	ajax.setVar("skin", dle_skin);
	ajax.requestFile = dle_root + "engine/ajax/pm.php";
	ajax.method = 'POST';
	ajax.element = 'dle-pm-preview';
	ajax.onCompletion = PreviewCompleted;
	ajax.sendAJAX(varsString);
};

function reload () {

	var rndval = new Date().getTime(); 

	document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="" /><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
//-->
</script>
HTML;
	
	$tpl->set( '[newpm]', $ajax_form );
	$tpl->set( '[/newpm]', "" );
	$tpl->set_block( "'\\[pmlist\\].*?\\[/pmlist\\]'si", "" );
	$tpl->set_block( "'\\[readpm\\].*?\\[/readpm\\]'si", "" );
	
	if( $user_group[$member_id['user_group']]['captcha'] ) {
		$tpl->set( '[sec_code]', "" );
		$tpl->set( '[/sec_code]', "" );
		$path = parse_url( $config['http_home_url'] );
		$tpl->set( '{sec_code}', "<span id=\"dle-captcha\"><img src=\"" . $path['path'] . "engine/modules/antibot.php\" alt=\"${lang['sec_image']}\" border=\"0\" alt=\"\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>" );
	} else {
		$tpl->set( '{sec_code}', "" );
		$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
	}
	
	$replyid = intval( $_GET['replyid'] );
	$user = intval( $_GET['user'] );
	if( isset( $_REQUEST['username'] ) ) $username = $db->safesql( strip_tags( urldecode( $_GET['username'] ) ) );
	else $username = '';

	$text = "";

	if( $replyid ) {
		$row = $db->super_query( "SELECT * FROM " . USERPREFIX . "_pm where id= '$replyid'" );
		
		if( ($row['user'] != $member_id['user_id']) and ($row['user_from'] != $member_id['name']) ) {
			
			msgbox( $lang['all_err_1'], $lang['pm_err_7'] );
			$stop_pm = TRUE;
		
		}
		
		if( $config['allow_comments_wysiwyg'] != "yes" ) {
			
			$text = $parse->decodeBBCodes( $row['text'], false );
			$text = "[quote]" . $text . "[/quote]\n";
		
		} else {
			
			$text = $parse->decodeBBCodes( $row['text'], TRUE, $config['allow_comments_wysiwyg'] );
			$text = "[quote]" . $text . "[/quote]<br />";
		}
		
		$tpl->set( '{author}', $row['user_from'] );

		if (strpos ( $row['subj'], "RE:" ) === false)
			$tpl->set( '{subj}', "RE: " . stripslashes( $row['subj'] ) );
		else
			$tpl->set( '{subj}', stripslashes( $row['subj'] ) );

		$row = $db->super_query( "SELECT pm_all, user_group FROM " . USERPREFIX . "_users WHERE name = '" . $db->safesql( $row['user_from'] ) . "'" );
		
		if( $row['pm_all'] >= $user_group[$row['user_group']]['max_pm'] and $member_id['user_group'] != 1 ) {
			$stop_pm = true;
		}
	
	} elseif( $user or $username != "" ) {
		
		if( $user ) $row = $db->super_query( "SELECT name, pm_all, user_group FROM " . USERPREFIX . "_users where user_id = '$user'" );
		elseif( $username != "" ) $row = $db->super_query( "SELECT name, pm_all, user_group FROM " . USERPREFIX . "_users where name='$username'" );
		
		if( $row['pm_all'] >= $user_group[$row['user_group']]['max_pm'] and $member_id['user_group'] != 1 ) {
			$stop_pm = true;
		}
		
		$tpl->set( '{author}', $row['name'] );
		$tpl->set( '{subj}', "" );
	
	} else {
		$tpl->set( '{author}', "" );
		$tpl->set( '{subj}', "" );
	
	}

	if( $config['allow_comments_wysiwyg'] == "yes" ) {
		
		include_once ENGINE_DIR . '/editor/comments.php';
		$bb_code = "";
		$allow_comments_ajax = true;
	} else
		include_once ENGINE_DIR . '/modules/bbcode.php';

	if( $config['allow_comments_wysiwyg'] == "yes" ) {
		
		$tpl->set( '{editor}', $wysiwyg );
	
	} else {
		$tpl->set( '{editor}', $bb_code );
	}

	$tpl->set( '{text}', $text );		
	
	if( $config['allow_comments_wysiwyg'] == "yes" ) $tpl->copy_template = "<form  method=\"post\" name=\"dle-comments-form\" id=\"dle-comments-form\" onsubmit=\"document.getElementById('comments').value = tinyMCE.get('comments').getContent(); if(document.getElementById('dle-comments-form').name.value == '' || document.getElementById('dle-comments-form').subj.value == '' || document.getElementById('comments').value == ''){alert('{$lang['comm_req_f']}');return false}\" action=\"\">\n" . $tpl->copy_template . "<input name=\"send\" type=\"hidden\" value=\"send\" /></form>";
	else $tpl->copy_template = "<form  method=\"post\" name=\"dle-comments-form\" id=\"dle-comments-form\" onsubmit=\"if(document.getElementById('dle-comments-form').name.value == '' || document.getElementById('dle-comments-form').subj.value == '' || document.getElementById('dle-comments-form').comments.value == ''){alert('{$lang['comm_req_f']}');return false}\" action=\"\">\n" . $tpl->copy_template . "<input name=\"send\" type=\"hidden\" value=\"send\" /></form>";
	
	if( ! $stop_pm ) {
		$tpl->compile( 'content' );
		$tpl->clear();
	} else {
		$tpl->clear();
		if( ! $tpl->result['info'] ) msgbox( $lang['all_info'], $lang['pm_err_8'] );
	}

} elseif( ! $stop_pm ) {
	
	$tpl->set( '[pmlist]', "" );
	$tpl->set( '[/pmlist]', "" );
	$tpl->set_block( "'\\[newpm\\].*?\\[/newpm\\]'si", "" );
	$tpl->set_block( "'\\[readpm\\].*?\\[/readpm\\]'si", "" );
	
	if( $member_id['pm_unread'] < 0 ) {
		
		$db->query( "UPDATE " . USERPREFIX . "_users SET pm_unread='0' WHERE user_id='{$member_id['user_id']}'" );
	
	}
	
	$pmlist = <<<HTML
<form action="$PHP_SELF?do=pm&doaction=del" method="post" name="pmlist">
<input type="hidden" name="dle_allow_hash" value="{$dle_login_hash}" />
HTML;
	
	if( $doaction == "outbox" ) {
	  $tpl->set( '{pm_title}', "Отправленные" );
		$lang['pm_from'] = $lang['pm_to'];
		$sql = "SELECT id, subj, name as user_from, date, pm_read FROM " . USERPREFIX . "_pm LEFT JOIN " . USERPREFIX . "_users ON " . USERPREFIX . "_pm.user=" . USERPREFIX . "_users.user_id WHERE user_from = '{$member_id['name']}' AND folder = 'outbox' order by date desc";
	} else {
    $tpl->set( '{pm_title}', "Входящие" );
		$sql = "SELECT id, subj, user_from, date, pm_read, reply, u.lastdate FROM " . USERPREFIX . "_pm LEFT JOIN " . USERPREFIX . "_users u ON user_from = u.name where user = '{$member_id['user_id']}' AND folder = 'inbox' order by date desc";
    }
	$pmlist .= "<table class=\"pm\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td width=\"20\">&nbsp;</td><td class=\"pm_head\">" . $lang['pm_subj'] . "</td><td width=\"150\" class=\"pm_head\">" . $lang['pm_from'] . "</td><td width=\"150\" class=\"pm_head\" align=\"center\">" . $lang['pm_date'] . "</td><td width=\"50\" class=\"pm_head\" align=\"center\"><input type=\"checkbox\" name=\"master_box\" title=\"{$lang['pm_selall']}\" onclick=\"javascript:ckeck_uncheck_all()\" /></td></tr><tr><td colspan=\"5\"><div class=\"line_oren\"></div></td></tr>";
	
	$db->query( $sql );
	$i = 0;
	
	while ( $row = $db->get_row() ) {
		/// тут, немного выше (MySQL-запрос) и ниже ($status) изменения для онлайн статуса юзера
    if( time() < ($row['lastdate']+600))
    {
      $status = "<img src='{THEME}/images/status_green.png' title='Пользователь онлайн' alt='Пользователь онлайн' style='display:inline; vertical-align:middle;'>";
    } else
    $status = "<img src='{THEME}/images/status_red.png' title='Пользователь оффлайн' alt='Пользователь оффлайн' style='display:inline; vertical-align:middle;'>";
    ///конец статуса
		$i ++;
		
		if( $config['allow_alt_url'] == "yes" ) $user_from = "<a class=\"pm_list\" href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['user_from'] ) . "/\">" . $row['user_from'] . "</a>";
		else $user_from = "<a class=\"pm_list\" href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['user_from'] ) . "\">" . $row['user_from'] . "</a>";
		
		if( $row['pm_read'] == "yes" ) {
			
			$subj = "<a class=\"pm_list\" href=\"$PHP_SELF?do=pm&amp;doaction=readpm&amp;pmid=" . $row['id'] . "\">" . stripslashes( $row['subj'] ) . "</a>";
			$icon = "{THEME}/dleimages/read.gif";
		
		} else {
			
			$subj = "<a class=\"pm_list\" href=\"$PHP_SELF?do=pm&amp;doaction=readpm&amp;pmid=" . $row['id'] . "\"><b>" . stripslashes( $row['subj'] ) . "</b></a>";
			$icon = "{THEME}/dleimages/unread.gif";
		
		}
		
		if( $row['reply'] ) $icon = "{THEME}/dleimages/send.gif";
		
		$pmlist .= "<tr><td><img src=\"{$icon}\" border=\"0\" alt=\"\" /></td><td class=\"pm_list\">{$subj}</td><td class=\"pm_list\">{$user_from}{$status}</td><td class=\"pm_list\" align=\"center\">" . langdate( "j.m.Y H:i", $row['date'] ) . "</td><td class=\"pm_list\" align=\"center\"><input name=\"selected_pm[]\" value=\"{$row['id']}\" type=\"checkbox\" /></td></tr>";
	
	}
	
	$db->free();
	
	$pmlist .= "<tr><td colspan=\"5\" height=\"5\">&nbsp;</td></tr><tr><td colspan=\"5\" align=\"right\"><fieldset><button type=\"submit\" class=\"submit2\" style=\"width:250px;\">{$lang['b_del']}</button></fieldset></td></tr></table></form>";
	
	if( $i ) $tpl->set( '{pmlist}', $pmlist );
	else $tpl->set( '{pmlist}', $lang['no_message'] );
	
	$tpl->compile( 'content' );
	$tpl->clear();
}
?>