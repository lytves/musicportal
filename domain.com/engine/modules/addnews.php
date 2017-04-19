<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$allow_addnews = true;

include_once ENGINE_DIR . '/classes/parse.class.php';
$parse = new ParseFilter( Array (), Array (), 1, 1 );

if( $config['max_moderation'] and ! $user_group[$member_id['user_group']]['moderation'] ) {
	
	$stats_approve = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_post WHERE approve != '1'" );
	$stats_approve = $stats_approve['count'];
	
	if( $stats_approve >= $config['max_moderation'] ) $allow_addnews = false;

}

if( $member_id['restricted'] and $member_id['restricted_days'] and $member_id['restricted_date'] < $_TIME ) {
	
	$member_id['restricted'] = 0;
	$db->query( "UPDATE LOW_PRIORITY " . USERPREFIX . "_users SET restricted='0', restricted_days='0', restricted_date='' WHERE user_id='{$member_id['user_id']}'" );

}

if( $member_id['restricted'] == 1 or $member_id['restricted'] == 3 ) {
	
	if( $member_id['restricted_days'] ) {
		
		$lang['news_info_4'] = str_replace( '{date}', langdate( "j M Y H:i", $member_id['restricted_date'] ), $lang['news_info_4'] );
		$lang['add_err_9'] = $lang['news_info_4'];
	
	} else {
		
		$lang['add_err_9'] = $lang['news_info_5'];
	
	}
	
	$allow_addnews = false;

}

if( ! $allow_addnews ) {
	
	msgbox( $lang['all_info'], $lang['add_err_9'] . "<br /><br /><a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>" );

} else {
	
	if( isset( $_REQUEST['mod'] ) and $_REQUEST['mod'] == "addnews" and $is_logged and $user_group[$member_id['user_group']]['allow_adds'] ) {
		
		$stop = "";
		
		if( $config['sec_addnews'] ) {
			
			$id_key = $_POST[$_SESSION['id_key']];
			
			if( $id_key == "" or $id_key != $dle_login_hash ) $stop .= "<li>ANTISPAM: User ID not valid</li>";

			if (clean_url($_SERVER['HTTP_REFERER']) != clean_url($_SERVER['HTTP_HOST'])) $stop .= "<li>ANTISPAM: User ID not valid</li>";

		}

	
		$allow_comm = intval( $_POST['allow_comm'] );

		if( $user_group[$member_id['user_group']]['allow_main'] ) $allow_main = intval( $_POST['allow_main'] );
		else $allow_main = 0;
		
		$approve = intval( $_POST['approve'] );
		$allow_rating = intval( $_POST['allow_rating'] );
		
		if( $user_group[$member_id['user_group']]['allow_fixed'] ) $news_fixed = intval( $_POST['news_fixed'] );
		else $news_fixed = 0;
		
		if( ! count( $_REQUEST['catlist'] ) ) {
			$catlist = array ();
			$catlist[] = '0';
		} else
			$catlist = $_REQUEST['catlist'];
		$category_list = $db->safesql( implode( ',', $catlist ) );
		
		if( ! $config['allow_add_tags'] ) $_POST['tags'] = "";
		elseif( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_POST['tags'] ) ) $_POST['tags'] = "";
		else $_POST['tags'] = $db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $_POST['tags'] ) ) ), ENT_QUOTES ) );
		
		if( ! $user_group[$member_id['user_group']]['moderation'] ) {
			$approve = 0;
			$allow_comm = 1;
			$allow_main = 1;
			$allow_rating = 1;
			$news_fixed = 0;
		}
		
		if( $approve ) $msg = $lang['add_ok_1'];
		else $msg = $lang['add_ok_2'];
		
		$allow_list = explode( ',', $user_group[$member_id['user_group']]['cat_add'] );
		
		if( $user_group[$member_id['user_group']]['moderation'] ) {
			foreach ( $catlist as $selected ) {
				if( $allow_list[0] != "all" and ! in_array( $selected, $allow_list ) and $member_id['user_group'] != "1" ) {
					$approve = 0;
					$msg = $lang['add_ok_3'];
				}
			}
		}


		if ( !$user_group[$member_id['user_group']]['allow_html'] ) {

			$config['allow_site_wysiwyg'] = "no";
			$_POST['short_story'] = strip_tags ($_POST['short_story']);
			$_POST['full_story'] = strip_tags ($_POST['full_story']);

		}
		
		if( $config['allow_site_wysiwyg'] == "yes" ) {

			$parse->allow_code = false;			
			$full_story = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['full_story'] ) ) );
			$short_story = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['short_story'] ) ) );
			$allow_br = 0;
		
		} else {
			
			$full_story = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['full_story'] ), false ) );
			$short_story = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['short_story'] ), false ) );
			$allow_br = 1;
		
		}


		if( $parse->not_allowed_text ) {
			$stop .= "<li>" . $lang['news_err_39'] . "</li>";
		}
		
		$parse->ParseFilter();
		$title = $db->safesql( $parse->process( trim( strip_tags ($_POST['title']) ) ) );
		$alt_name = trim( $parse->process( stripslashes( $_POST['alt_name'] ) ) );
		
		$add_module = "yes";
		$xfieldsaction = "init";
		$category = $catlist;
		include (ENGINE_DIR . '/inc/xfields.php');
		
		$filecontents = array ();
		
		if( $config['safe_xfield'] ) {
			$parse->ParseFilter();
			$parse->safe_mode = true;
		} else {
			$parse = new ParseFilter( Array (), Array (), 1, 1 );
		}
		
		if( ! empty( $postedxfields ) ) {
			foreach ( $postedxfields as $xfielddataname => $xfielddatavalue ) {
				if( $xfielddatavalue == "" ) {
					continue;
				}
				$parse->allow_code = true;					
				$xfielddatavalue = $db->safesql( $parse->BB_Parse( $parse->process( $xfielddatavalue ), false ) );
				
				$xfielddataname = $db->safesql( $xfielddataname );
				
				$xfielddataname = str_replace( "|", "&#124;", $xfielddataname );
				$xfielddataname = str_replace( "\r\n", "__NEWL__", $xfielddataname );
				$xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue );
				$xfielddatavalue = str_replace( "\r\n", "__NEWL__", $xfielddatavalue );
				$filecontents[] = "$xfielddataname|$xfielddatavalue";
			}
			
			$filecontents = implode( "||", $filecontents );
		} else
			$filecontents = '';
		
		if( $alt_name == "" or ! $alt_name ) $alt_name = totranslit( stripslashes( $title ), true, false );
		else $alt_name = totranslit( $alt_name, true, false );
		
		if( $title == "" or ! $title ) $stop .= $lang['add_err_1'];
		if( strlen( $title ) > 200 ) $stop .= $lang['add_err_2'];
		if( trim( $short_story ) == "" or ! $short_story ) $stop .= $lang['add_err_5'];
		
		if( $user_group[$member_id['user_group']]['news_sec_code'] and ($_REQUEST['sec_code'] != $_SESSION['sec_code_session'] or ! $_SESSION['sec_code_session']) ) {
			
			$stop .= "<li>" . $lang['news_err_30'] . "</li>";
		
		}

		if( $stop ) {
			$stop = "<ul>" . $stop . "</ul><a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>";
			msgbox( $lang['add_err_6'], $stop  );
		}
		
		if( ! $stop ) {
			
			$_SESSION['sec_code_session'] = 0;
			$id = (isset( $_REQUEST['id'] )) ? intval( $_REQUEST['id'] ) : 0;
			$found = false;
			
			if( $id ) {
				$row = $db->super_query( "SELECT * FROM " . PREFIX . "_post where id = '$id' and approve = '0'" );
				if( $id == $row['id'] and ($member_id['name'] == $row['autor'] or $user_group[$member_id['user_group']]['allow_all_edit']) ) $found = true;
				else $found = false;
			}
			
			if( $found ) {
				
				$db->query( "UPDATE " . PREFIX . "_post set title='$title', short_story='$short_story', full_story='$full_story', xfields='$filecontents', category='$category_list', alt_name='$alt_name', allow_comm='$allow_comm', approve='$approve', allow_main='$allow_main', allow_rate='$allow_rating', fixed='$news_fixed', allow_br='$allow_br', flag='1', tags='" . $_POST['tags'] . "' WHERE id='$id'" );
				
				// Облако тегов
				if( $_POST['tags'] != $row['tags'] or $approve ) {
					$db->query( "DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$row['id']}'" );
					
					if( $_POST['tags'] != "" and $approve ) {
						
						$tags = array ();
						
						$_POST['tags'] = explode( ",", $_POST['tags'] );
						
						foreach ( $_POST['tags'] as $value ) {
							
							$tags[] = "('" . $row['id'] . "', '" . trim( $value ) . "')";
						}
						
						$tags = implode( ", ", $tags );
						$db->query( "INSERT INTO " . PREFIX . "_tags (news_id, tag) VALUES " . $tags );
					
					}
				}
			
			} else {
				
				$added_time = time() + ($config['date_adjust'] * 60);
				$thistime = date( "Y-m-d H:i:s", $added_time );
				
				$db->query( "INSERT INTO " . PREFIX . "_post (date, autor, short_story, full_story, xfields, title, keywords, category, alt_name, allow_comm, approve, allow_main, fixed, allow_rate, allow_br, flag, tags) values ('$thistime', '$member_id[name]', '$short_story', '$full_story', '$filecontents', '$title', '', '$category_list', '$alt_name', '$allow_comm', '$approve', '$allow_main', '$news_fixed', '$allow_rating', '$allow_br', '1', '" . $_POST['tags'] . "')" );
				
				$row['id'] = $db->insert_id();
				$db->query( "UPDATE " . PREFIX . "_images set news_id='{$row['id']}' where author = '$member_id[name]' AND news_id = '0'" );
				$db->query( "UPDATE " . PREFIX . "_files set news_id='{$row['id']}' where author = '$member_id[name]' AND news_id = '0'" );
				$db->query( "UPDATE " . USERPREFIX . "_users set news_num=news_num+1 where user_id='$member_id[user_id]'" );
				
				if( $_POST['tags'] != "" and $approve ) {
					
					$tags = array ();
					
					$_POST['tags'] = explode( ",", $_POST['tags'] );
					
					foreach ( $_POST['tags'] as $value ) {
						
						$tags[] = "('" . $row['id'] . "', '" . trim( $value ) . "')";
					}
					
					$tags = implode( ", ", $tags );
					$db->query( "INSERT INTO " . PREFIX . "_tags (news_id, tag) VALUES " . $tags );
				
				}
				
				if( ! $approve and $config['mail_news'] ) {
					
					include_once ENGINE_DIR . '/classes/mail.class.php';
					$mail = new dle_mail( $config );
					
					$row = $db->super_query( "SELECT template FROM " . PREFIX . "_email WHERE name='new_news' LIMIT 0,1" );
					
					$row['template'] = stripslashes( $row['template'] );
					$row['template'] = str_replace( "{%username%}", $member_id['name'], $row['template'] );
					$row['template'] = str_replace( "{%date%}", langdate( "j F Y H:i", $added_time ), $row['template'] );
					$row['template'] = str_replace( "{%title%}", stripslashes( stripslashes( $title ) ), $row['template'] );
					
					$category_list = explode( ",", $category_list );
					$my_cat = array ();
					
					foreach ( $category_list as $element ) {
						
						$my_cat[] = $cat_info[$element]['name'];
					
					}
					
					$my_cat = stripslashes( implode( ', ', $my_cat ) );
					
					$row['template'] = str_replace( "{%category%}", $my_cat, $row['template'] );
					
					$mail->send( $config['admin_mail'], $lang['mail_news'], $row['template'] );
				
				}
			
			}
			
			if( $config['allow_alt_url'] == "yes" ) msgbox( $lang['add_ok'], "{$msg} <a href=\"{$config['http_home_url']}" . "addnews.html\">$lang[add_noch]</a> $lang[add_or] <a href=\"{$config['http_home_url']}\">$lang[all_prev]</a>" );
			else msgbox( $lang['add_ok'], "{$msg} <a href=\"$PHP_SELF?do=addnews\">$lang[add_noch]</a> $lang[add_or] <a href=\"{$config['http_home_url']}\">$lang[all_prev]</a>" );
			
			if( $approve ) clear_cache();
		
		}
	
	} elseif( $is_logged and $user_group[$member_id['user_group']]['allow_adds'] ) {
		
		$tpl->load_template( 'addnews.tpl' );
		
		$addtype = "addnews";

		if ( !$user_group[$member_id['user_group']]['allow_html'] ) {

			$config['allow_site_wysiwyg'] = "no";

		}
		
		if( $config['allow_site_wysiwyg'] == "yes" ) {
			
			include_once ENGINE_DIR . '/editor/shortsite.php';
			include_once ENGINE_DIR . '/editor/fullsite.php';
			$bb_code = "";
		
		} else
			include_once ENGINE_DIR . '/modules/bbcode.php';
		
		if( $config['allow_site_wysiwyg'] != "yes" ) {
			
			$tpl->set( '[not-wysywyg]', '' );
			$tpl->set( '[/not-wysywyg]', '' );
		
		} else
			$tpl->set_block( "'\\[not-wysywyg\\].*?\\[/not-wysywyg\\]'si", '' );
		
		if( $config['allow_site_wysiwyg'] == "yes" ) {
			
			$tpl->set( '{shortarea}', $shortarea );
			$tpl->set( '{fullarea}', $fullarea );
		
		} else {
			$tpl->set( '{shortarea}', '' );
			$tpl->set( '{fullarea}', '' );
		}
		
		$id = (isset( $_REQUEST['id'] )) ? intval( $_REQUEST['id'] ) : 0;
		$found = false;
		
		if( $id ) {
			$row = $db->super_query( "SELECT * FROM " . PREFIX . "_post where id = '$id' and approve = '0'" );
			if( $id == $row['id'] and ($member_id['name'] == $row['autor'] or $user_group[$member_id['user_group']]['allow_all_edit']) ) $found = true;
			else $found = false;
		}
		
		if( $found ) {
			
			$cat_list = explode( ',', $row['category'] );
			$categories_list = CategoryNewsSelection( $cat_list, 0 );
			$tpl->set( '{title}', $parse->decodeBBCodes( $row['title'], false ) );
			$tpl->set( '{alt-name}', $row['alt_name'] );
			
			if( $config['allow_site_wysiwyg'] == "yes" or $row['allow_br'] != '1' ) {
				$row['short_story'] = $parse->decodeBBCodes( $row['short_story'], TRUE, $config['allow_site_wysiwyg'] );
				$row['full_story'] = $parse->decodeBBCodes( $row['full_story'], TRUE, $config['allow_site_wysiwyg'] );
			} else {
				$row['short_story'] = $parse->decodeBBCodes( $row['short_story'], false );
				$row['full_story'] = $parse->decodeBBCodes( $row['full_story'], false );
			}
			
			$tpl->set( '{short-story}', $row['short_story'] );
			$tpl->set( '{full-story}', $row['full_story'] );
			$tpl->set( '{tags}', $row['tags'] );
		
		} else {
			
			$categories_list = CategoryNewsSelection( 0, 0 );
			$tpl->set( '{title}', '' );
			$tpl->set( '{alt-name}', '' );
			$tpl->set( '{short-story}', '' );
			$tpl->set( '{full-story}', '' );
			$tpl->set( '{tags}', '' );
		
		}
		
		$xfieldsaction = "categoryfilter";
		include_once ENGINE_DIR . '/inc/xfields.php';
		
		if( $config['allow_multi_category'] ) {
			
			$cats = "<select name=\"catlist[]\" id=\"category\" onchange=\"onCategoryChange(this.value)\" style=\"width:316px;height:73px;\" multiple>";
		
		} else {
			
			$cats = "<select name=\"catlist[]\" id=\"category\" onchange=\"onCategoryChange(this.value)\">";
		}
		
		$cats .= $categories_list;
		$cats .= "</select>";
		
		$tpl->set( '{bbcode}', $bb_code );
		$tpl->set( '{category}', $cats );
		
		if( $user_group[$member_id['user_group']]['moderation'] ) {
			
			$admintag = "<input type=\"checkbox\" name=\"allow_comm\" id=\"allow_comm\" value=\"1\" checked=\"checked\" /><label for=\"allow_comm\">" . $lang['add_al_com'] . "</label>";
			
			if( $user_group[$member_id['user_group']]['allow_main'] ) $admintag .= "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"allow_main\" id=\"allow_main\" value=\"1\" checked=\"checked\" /><label for=\"allow_main\">" . $lang['add_al_m'] . "</label>";
			
			$admintag .= "<br /><input type=\"checkbox\" name=\"approve\" id=\"approve\" value=\"1\" checked=\"checked\" /><label for=\"approve\"> {$lang['add_al_ap']}</label><br /><input type=\"checkbox\" name=\"allow_rating\" id=\"allow_rating\" value=\"1\" checked=\"checked\" /><label for=\"allow_rating\"> {$lang['addnews_allow_rate']}</label>";
			
			if( $user_group[$member_id['user_group']]['allow_fixed'] ) $admintag .= "<br /><input type=\"checkbox\" name=\"news_fixed\" id=\"news_fixed\" value=\"1\" /><label for=\"news_fixed\"> {$lang['add_al_fix']}</label>";
			
			$tpl->set( '{admintag}', $admintag );
		
		} else
			$tpl->set( '{admintag}', '' );
		
		if( $is_logged and $member_id['user_group'] < 3 ) {
			
			$tpl->set( '[urltag]', '' );
			$tpl->set( '[/urltag]', '' );
		
		} else
			$tpl->set_block( "'\\[urltag\\].*?\\[/urltag\\]'si", "" );
		
		if( $found ) {
			
			$xfieldsaction = "list";
			$xfieldsid = $row['xfields'];
			$xfieldscat = $row['category'];
			include (ENGINE_DIR . '/inc/xfields.php');
		
		} else {
			
			$xfieldsaction = "list";
			$xfieldsadd = true;
			include (ENGINE_DIR . '/inc/xfields.php');
		
		}
		
		$tpl->set( '{xfields}', $output );
		
		if( $user_group[$member_id['user_group']]['news_sec_code'] ) {
			$tpl->set( '[sec_code]', "" );
			$tpl->set( '[/sec_code]', "" );
			$path = parse_url( $config['http_home_url'] );
			$tpl->set( '{sec_code}', "<span id=\"dle-captcha\"><img src=\"" . $path['path'] . "engine/modules/antibot.php\" alt=\"${lang['sec_image']}\" border=\"0\" alt=\"\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>" );
		} else {
			$tpl->set( '{sec_code}', "" );
			$tpl->set_block( "'\\[sec_code\\](.*?)\\[/sec_code\\]'si", "" );
		}
		
		$script = "
<script language=\"javascript\" type=\"text/javascript\">
<!--
function preview(){";
		
		if( $config['allow_site_wysiwyg'] == "yes" ) {
			
			$script .= "document.getElementById('short_story').value = tinyMCE.get('short_story').getContent();
	document.getElementById('full_story').value = tinyMCE.get('full_story').getContent();";
		
		}
		
		$script .= "if(document.entryform.short_story.value == '' || document.entryform.title.value == ''){ alert('$lang[add_err_7]'); }
    else{
        dd=window.open('','prv','height=400,width=750,resizable=0,scrollbars=1')
        document.entryform.mod.value='preview';document.entryform.action='{$config['http_home_url']}engine/preview.php';document.entryform.target='prv'
        document.entryform.submit();dd.focus()
        setTimeout(\"document.entryform.mod.value='addnews';document.entryform.action='';document.entryform.target='_self'\",500)
    }
}";
		
		$script .= <<<HTML

function reload () {

	var rndval = new Date().getTime(); 

	document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" alt="" /><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
//-->
</script>
HTML;
		
		if( $config['allow_site_wysiwyg'] == "yes" ) $script .= "<form method=post name=\"entryform\" id=\"entryform\" onsubmit=\"document.getElementById('short_story').value = tinyMCE.get('short_story').getContent(); document.getElementById('full_story').value = tinyMCE.get('full_story').getContent(); if(document.entryform.title.value == '' || document.entryform.short_story.value == ''){alert('$lang[add_err_7]');return false}\" action=\"\">";
		else $script .= "<form method=post name=\"entryform\" id=\"entryform\" onsubmit=\"if(document.entryform.title.value == '' || document.entryform.short_story.value == ''){alert('$lang[add_err_7]');return false}\" action=\"\">";
		
		if( $config['sec_addnews'] ) {
			
			$salt = "abchefghjkmnpqrstuvwxyz";
			srand( ( double ) microtime() * 1000000 );
			$random_key = "";
			
			for($i = 0; $i < 8; $i ++) {
				$random_key .= $salt{rand( 0, 23 )};
			}
			
			@session_register( 'id_key' );
			$_SESSION['id_key'] = $random_key;
			
			$random_key = "<input type=\"hidden\" name=\"{$random_key}\" value=\"{$dle_login_hash}\" />";


		
		} else
			$random_key = "";
		
		$tpl->copy_template = $categoryfilter . $script . $tpl->copy_template . "<input type=\"hidden\" name=\"mod\" value=\"addnews\" />{$random_key}</form>";
		
		$tpl->compile( 'content' );
		$tpl->clear();
	
	} else
		msgbox( $lang['all_info'], "$lang[add_err_8]<br /><a href=\"javascript:history.go(-1)\">$lang[all_prev]</a>" );

}
?>