<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( ! $_SESSION['admin_referrer'] ) {
	
	$_SESSION['admin_referrer'] = "?mod=editnews&amp;action=list";

}

if( !$user_group[$member_id['user_group']]['admin_editnews'] OR !$user_group[$member_id['user_group']]['allow_all_edit'] ) {
	msg( "error", $lang['mass_error'], $lang['mass_ddenied'], $_SESSION['admin_referrer'] );
}

$selected_news = $_REQUEST['selected_news'];

if( ! $selected_news ) {
	msg( "error", $lang['mass_error'], $lang['mass_denied'], $_SESSION['admin_referrer'] );
}

if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
	
	die( "Hacking attempt! User not found" );

}

$action = htmlspecialchars( strip_tags( stripslashes( $_POST['action'] ) ) );

$k_mass = false;
$field = false;

if( $action == "mass_approve" ) {
	$field = "approve";
	$value = 1;
	$k_mass = true;
	$title = $lang['mass_edit_app_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_app_fr1'];
} elseif( $action == "mass_date" ) {
	$field = "date";
	$value = date( "Y-m-d H:i:s", time() + ($config['date_adjust'] * 60) );
	$k_mass = true;
	$title = $lang['mass_edit_date_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_date_fr1'];
} elseif( $action == "mass_not_approve" ) {
	$field = "approve";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_edit_app_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_app_fr2'];
} elseif( $action == "mass_fixed" ) {
	$field = "fixed";
	$value = 1;
	$k_mass = true;
	$title = $lang['mass_edit_fix_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_fix_fr1'];
} elseif( $action == "mass_not_fixed" ) {
	$field = "fixed";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_edit_fix_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_fix_fr2'];
} elseif( $action == "mass_comments" ) {
	$field = "allow_comm";
	$value = 1;
	$k_mass = true;
	$title = $lang['mass_edit_com_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_comm_fr1'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];
} elseif( $action == "mass_not_comments" ) {
	$field = "allow_comm";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_edit_com_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_comm_fr2'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];
} elseif( $action == "mass_rating" ) {
	$field = "allow_rate";
	$value = 1;
	$k_mass = true;
	$title = $lang['mass_edit_rate_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_rate_fr1'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];
} elseif( $action == "mass_not_rating" ) {
	$field = "allow_rate";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_edit_rate_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_rate_fr2'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];
} elseif( $action == "mass_main" ) {
	$field = "allow_main";
	$value = 1;
	$k_mass = true;
	$title = $lang['mass_edit_main_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_main_fr1'];
} elseif( $action == "mass_not_main" ) {
	$field = "allow_main";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_edit_main_tl'];
	$lang['mass_confirm'] = $lang['mass_edit_main_fr2'];

} elseif( $action == "mass_clear_count" ) {
	$field = "news_read";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_clear_count_2'];
	$lang['mass_confirm'] = $lang['mass_clear_count_1'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];

} elseif( $action == "mass_clear_rating" ) {
	$field = "rating";
	$value = 0;
	$k_mass = true;
	$title = $lang['mass_clear_rating_2'];
	$lang['mass_confirm'] = $lang['mass_clear_rating_1'];
	$lang[mass_confirm_1] = $lang[mass_confirm_2];
}

if( $_POST['doaction'] == "mass_update" and $field ) {
	
	foreach ( $selected_news as $id ) {
		$id = intval( $id );
		$db->query( "UPDATE " . PREFIX . "_post SET {$field}='{$value}' WHERE id='{$id}'" );
		
		if( $field == "approve" ) {
			
			if( $value ) {
				
				$db->query( "DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$id}'" );
				$row = $db->super_query( "SELECT tags FROM " . PREFIX . "_post where id = '{$id}'" );
				
				if( $row['tags'] ) {
					
					$tags = array ();
					
					$row['tags'] = explode( ",", $row['tags'] );
					
					foreach ( $row['tags'] as $tags_value ) {
						
						$tags[] = "('" . $id . "', '" . trim( $tags_value ) . "')";
					}
					
					$tags = implode( ", ", $tags );
					$db->query( "INSERT INTO " . PREFIX . "_tags (news_id, tag) VALUES " . $tags );
				
				}
			
			} else {
				
				$db->query( "DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$id}'" );
			
			}
		
		}

		if ( $field == "news_read" ) {

			$db->query( "DELETE FROM " . PREFIX . "_views WHERE news_id = '{$id}'" );

		}

		if ( $field == "rating" ) {

			$db->query( "UPDATE " . PREFIX . "_post SET vote_num='0' WHERE id='{$id}'" );
			$db->query( "DELETE FROM " . PREFIX . "_logs WHERE news_id = '{$id}'" );

		}
	
	}
	
	clear_cache();
	
	msg( "info", $lang['db_ok'], $lang['db_ok_1'], $_SESSION['admin_referrer'] );
}

if( $k_mass ) {
	
	echoheader( "options", $lang['mass_head'] );
	
	echo <<<HTML
<form action="{$PHP_SELF}" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$title}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100" align="center">{$lang['mass_confirm']}
HTML;
	
	echo " (<b>" . count( $selected_news ) . "</b>) $lang[mass_confirm_1]<br><br>
<input class=bbcodes type=submit value=\"   $lang[mass_yes]   \"> &nbsp; <input type=button class=bbcodes value=\"  $lang[mass_no]  \" onclick=\"javascript:document.location='$PHP_SELF?mod=editnews&action=list'\">
<input type=hidden name=action value=\"{$action}\">
<input type=hidden name=user_hash value=\"{$dle_login_hash}\">
<input type=hidden name=doaction value=\"mass_update\">
<input type=hidden name=mod value=\"massactions\">";
	foreach ( $selected_news as $newsid ) {
		$newsid = intval($newsid);
		echo "<input type=hidden name=selected_news[] value=\"$newsid\">\n";
	}
	
	echo <<<HTML
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
	
	echofooter();
	exit();

}
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Подтвреждение удаления
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
if( $action == "mass_delete" ) {
	
	echoheader( "options", $lang['mass_head'] );
	
	echo <<<HTML
<form action="{$PHP_SELF}" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['mass_head']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100" align="center">{$lang['mass_confirm']}
HTML;
	
	echo "(<b>" . count( $selected_news ) . "</b>) $lang[mass_confirm_1]<br><br>
<input class=bbcodes type=submit value=\"   $lang[mass_yes]   \"> &nbsp; <input type=button class=bbcodes value=\"  $lang[mass_no]  \" onclick=\"javascript:document.location='$PHP_SELF?mod=editnews&action=list'\">
<input type=hidden name=action value=\"do_mass_delete\">
<input type=hidden name=user_hash value=\"{$dle_login_hash}\">
<input type=hidden name=mod value=\"massactions\">";
	foreach ( $selected_news as $newsid ) {
		$newsid = intval($newsid);
		echo "<input type=hidden name=selected_news[] value=\"$newsid\">\n";
	}
	
	echo <<<HTML
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
	
	echofooter();
	exit();

} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Удаление новостей
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif( $action == "do_mass_delete" ) {
	
	$deleted_articles = 0;
	
	foreach ( $selected_news as $id ) {
		
		$id = intval( $id );
		$row = $db->super_query( "SELECT autor FROM " . PREFIX . "_post where id = '$id'" );
		
		$db->query( "UPDATE " . USERPREFIX . "_users set news_num=news_num-1 where name='{$row['autor']}'" );
		
		$deleted_articles ++;
		
		$db->query( "DELETE FROM " . PREFIX . "_post WHERE id='$id'" );
		
		$db->query( "DELETE FROM " . PREFIX . "_comments WHERE post_id='$id'" );
		
		$db->query( "SELECT onserver FROM " . PREFIX . "_files WHERE news_id = '$id'" );
		while ( $row = $db->get_row() ) {
			@unlink( ROOT_DIR . "/uploads/files/" . $row['onserver'] );
		}
		$db->free();
		
		$db->query( "DELETE FROM " . PREFIX . "_files WHERE news_id = '$id'" );
		$db->query( "DELETE FROM " . PREFIX . "_poll WHERE news_id = '$id'" );
		$db->query( "DELETE FROM " . PREFIX . "_poll_log WHERE news_id = '$id'" );
		$db->query( "DELETE FROM " . PREFIX . "_post_log WHERE news_id = '$id'" );
		$db->query( "DELETE FROM " . PREFIX . "_tags WHERE news_id = '$id'" );
		
		$row = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where news_id = '$id'" );
		
		$listimages = explode( "|||", $row['images'] );
		
		if( $row['images'] != "" ) foreach ( $listimages as $dataimages ) {
			$url_image = explode( "/", $dataimages );
			
			if( count( $url_image ) == 2 ) {
				
				$folder_prefix = $url_image[0] . "/";
				$dataimages = $url_image[1];
			
			} else {
				
				$folder_prefix = "";
				$dataimages = $url_image[0];
			
			}
			
			@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $dataimages );
			@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $dataimages );
		}
		
		$db->query( "DELETE FROM " . PREFIX . "_images WHERE news_id = '$id'" );
	}
	
	clear_cache();
	
	if( count( $selected_news ) == $deleted_articles ) {
		msg( "info", $lang['mass_head'], $lang['mass_delok'], $_SESSION['admin_referrer'] );
	} else {
		msg( "error", $lang['mass_notok'], "$deleted_articles $lang[mass_i] " . count( $selected_news ) . " $lang[mass_notok_1]", $_SESSION['admin_referrer'] );
	}
} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Подтвеждение смены категорий
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif( $action == "mass_move_to_cat" ) {
	
	echoheader( "options", $lang['mass_cat'] );
	
	$count = count( $selected_news );
	if( $config['allow_multi_category'] ) $category_multiple = "class=\"cat_select\" multiple";
	else $category_multiple = "";
	
	echo <<<HTML
<form action="{$PHP_SELF}" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['mass_cat_1']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100">{$lang['mass_cat_2']} (<b>{$count}</b>) {$lang['mass_cat_3']}
<select name="move_to_category[]" align="absmiddle" {$category_multiple}>
HTML;
	
	echo CategoryNewsSelection( 0, 0 );
	echo "</select>";
	
	foreach ( $selected_news as $newsid ) {
		$newsid = intval($newsid);
		echo "<input type=hidden name=selected_news[] value=\"$newsid\">";
	}
	
	echo <<<HTML
<input type=hidden name=user_hash value="{$dle_login_hash}"><input type="hidden" name="action" value="do_mass_move_to_cat"><input type="hidden" name="mod" value="massactions">&nbsp;<input type="submit" value="{$lang['b_start']}" class="buttons"></td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
	
	echofooter();
	exit();
} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Выбор символьного кода
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif( $action == "mass_edit_symbol" ) {
	
	echoheader( "options", $lang['mass_cat'] );
	
	$count = count( $selected_news );
	
	echo <<<HTML
<form action="{$PHP_SELF}" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['catalog_url']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" height="100" align="center">{$lang['catalog_url']} <input type="text" name="catalog_url" size="15"  class="edit" value="{$row['symbol']}">
HTML;
	
	foreach ( $selected_news as $newsid ) {
		$newsid = intval($newsid);
		echo "<input type=hidden name=selected_news[] value=\"$newsid\">";
	}
	
	echo <<<HTML
<input type=hidden name=user_hash value="{$dle_login_hash}"><input type="hidden" name="action" value="do_mass_edit_symbol"><input type="hidden" name="mod" value="massactions">&nbsp;<input type="submit" value="{$lang['b_start']}" class="edit"></td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div></form>
HTML;
	
	echofooter();
	exit();
} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  смена категории
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif( $action == "do_mass_move_to_cat" ) {
	
	$moved_articles = 0;
	
	$move_to_category = $db->safesql( implode( ',', $_REQUEST['move_to_category'] ) );
	
	foreach ( $selected_news as $id ) {
		$moved_articles ++;
		$id = intval( $id );
		
		$db->query( "UPDATE " . PREFIX . "_post set category='$move_to_category' WHERE id='$id'" );
	}
	
	clear_cache();
	
	if( count( $selected_news ) == $moved_articles ) {
		msg( "info", $lang['mass_cat_ok'], "$lang[mass_cat_ok] ($moved_articles)", $_SESSION['admin_referrer'] );
	} else {
		msg( "error", $lang['mass_cat_notok'], $lang['mass_cat_notok_1'], $_SESSION['admin_referrer'] );
	}
} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  смена символьного кода
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
elseif( $action == "do_mass_edit_symbol" ) {
	
	$edit_articles = 0;
	
	$catalog_url = $db->safesql( substr( htmlspecialchars( strip_tags( stripslashes( trim( $_POST['catalog_url'] ) ) ) ), 0, 3 ) );
	
	foreach ( $selected_news as $id ) {
		$edit_articles ++;
		$id = intval( $id );
		
		$db->query( "UPDATE " . PREFIX . "_post SET symbol='$catalog_url' WHERE id='$id'" );
	}
	
	clear_cache();
	
	msg( "info", $lang['mass_symbol_ok'], $lang['mass_symbol_ok'] . " ($edit_articles)", $_SESSION['admin_referrer'] );
} 
/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
  Ничего не выбрано
 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
else {
	
	msg( "info", $lang['mass_noact'], $lang['mass_noact_1'], $_SESSION['admin_referrer'] );

}
?>