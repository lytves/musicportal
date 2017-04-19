<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

include_once ENGINE_DIR . '/classes/parse.class.php';

//####################################################################################################################
//         Обновление информации о пользователе
//####################################################################################################################
if( $allow_userinfo and $doaction == "adduserinfo" ) {
	
	if( $_POST['dle_allow_hash'] == "" or $_POST['dle_allow_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User ID not valid" );
	
	}
	
	$parse = new ParseFilter( );
	$parse->safe_mode = true;
	$parse->allow_url = false;
	$parse->allow_image = false;
	
	$stop = false;
	
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];

	$altpass = md5( $_POST['altpass'] );
	$info = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['info'] ), false ) );
	$email = $db->safesql( $parse->process( $_POST['email'] ) );
	
	$fullname = $db->safesql( $parse->process( $_POST['fullname'] ) );
	$land = $db->safesql( $parse->process( $_POST['land'] ) );
	$icq = intval( $_POST['icq'] );
	if( ! $icq ) $icq = "";
	
	$allowed_ip = str_replace( "\r", "", trim( $_POST['allowed_ip'] ) );
	$allowed_ip = str_replace( "\n", "|", $allowed_ip );
	$allowed_ip = $db->safesql( $parse->process( $allowed_ip ) );
	
	$row = $db->super_query( "SELECT * FROM " . USERPREFIX . "_users WHERE name = '$user'" );
	$xfieldsid = stripslashes( $row['xfields'] );
	
	if( $user_group[$row['user_group']]['allow_signature'] ) {
		
		$parse->allow_url = $user_group[$member_id['user_group']]['allow_url'];
		$parse->allow_image = $user_group[$member_id['user_group']]['allow_image'];
		$signature = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['signature'] ), false ) );
	
	} else
		$signature = "";
	
	$image = $_FILES['image']['tmp_name'];
	$image_name = $_FILES['image']['name'];
	$image_size = $_FILES['image']['size'];
	$img_name_arr = explode( ".", $image_name );
	$type = end( $img_name_arr );
	
	if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( ! $is_logged or ! ($member_id['user_id'] == $row['user_id'] or $member_id['user_group'] == 1) ) {
		$stop = $lang['news_err_13'];
	}
	
	if( is_uploaded_file( $image ) and ! $stop ) {
		
		if( intval( $user_group[$member_id['user_group']]['max_foto'] ) > 0 ) {
			
			if( $image_size < 100000 ) {
				
				$allowed_extensions = array ("jpg", "png", "jpe", "jpeg", "gif" );
				
				if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
					
					include_once ENGINE_DIR . '/classes/thumb.class.php';
					
					$res = @move_uploaded_file( $image, ROOT_DIR . "/uploads/fotos/" . $row['user_id'] . "." . $type );
					
					if( $res ) {
						
						@chmod( ROOT_DIR . "/uploads/fotos/" . $row['user_id'] . "." . $type, 0666 );
						$thumb = new thumbnail( ROOT_DIR . "/uploads/fotos/" . $row['user_id'] . "." . $type );
						
						if( $thumb->size_auto( $user_group[$member_id['user_group']]['max_foto'] ) ) {
							$thumb->jpeg_quality( $config['jpeg_quality'] );
							$thumb->save( ROOT_DIR . "/uploads/fotos/foto_" . $row['user_id'] . "." . $type );
						} else {
							@rename( ROOT_DIR . "/uploads/fotos/" . $row['user_id'] . "." . $type, ROOT_DIR . "/uploads/fotos/foto_" . $row['user_id'] . "." . $type );
						}
						
						@chmod( ROOT_DIR . "/uploads/fotos/foto_" . $row['user_id'] . "." . $type, 0666 );
						$foto_name = "foto_" . $row['user_id'] . "." . $type;
						
						$db->query( "UPDATE " . USERPREFIX . "_users set foto='$foto_name' where name='$user'" );
					
					} else
						$stop .= $lang['news_err_14'];
				} else
					$stop .= $lang['news_err_15'];
			} else
				$stop .= $lang['news_err_16'];
		} else
			$stop .= $lang['news_err_32'];
		
		@unlink( ROOT_DIR . "/uploads/fotos/" . $row['user_id'] . "." . $type );
	}
	
	if( $_POST['del_foto'] == "yes" ) {
		
		@unlink( ROOT_DIR . "/uploads/fotos/" . $row['foto'] );
		$db->query( "UPDATE " . USERPREFIX . "_users set foto='' WHERE name='$user'" );
	
	}
	
	if( strlen( $password1 ) > 0 ) {
		
		$altpass = md5( $altpass );
		
		if( $altpass != $member_id['password'] ) {
			$stop .= $lang['news_err_17'];
		}
		
		if( $password1 != $password2 ) {
			$stop .= $lang['news_err_18'];
		}
		
		if( strlen( $password1 ) < 6 ) {
			$stop .= $lang['news_err_19'];
		}
	}
	
	if( !preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $email) or empty( $email ) ) {
		
		$stop .= $lang['news_err_21'];
	}
	if( intval( $user_group[$member_id['user_group']]['max_info'] ) > 0 and strlen( $info ) > $user_group[$member_id['user_group']]['max_info'] ) {
		
		$stop .= $lang['news_err_22'];
	}
	if( intval( $user_group[$member_id['user_group']]['max_signature'] ) > 0 and strlen( $signature ) > $user_group[$member_id['user_group']]['max_signature'] ) {
		
		$stop .= $lang['not_allowed_sig'];
	}
	if( strlen( $fullname ) > 100 ) {
		
		$stop .= $lang['news_err_23'];
	}
	if ( preg_match( "/[\||\'|\<|\>|\"|\!|\]|\?|\$|\@|\/|\\\|\&\~\*\+]/", $fullname ) ) {

		$stop .= $lang['news_err_35'];
	}
	if( strlen( $land ) > 100 ) {
		
		$stop .= $lang['news_err_24'];
	}
	if ( preg_match( "/[\||\'|\<|\>|\"|\!|\]|\?|\$|\@|\/|\\\|\&\~\*\+]/", $land ) ) {

		$stop .= $lang['news_err_36'];
	}
	if( strlen( $icq ) > 20 ) {
		
		$stop .= $lang['news_err_25'];
	}
	
	if( $parse->not_allowed_tags ) {
		
		$stop .= $lang['news_err_34'];
	}

	if( $parse->not_allowed_text ) {
		
		$stop .= $lang['news_err_38'];
	}
	
	$db->query( "SELECT name FROM " . USERPREFIX . "_users WHERE email = '$email' AND name != '$user'" );
	
	if( $db->num_rows() ) {
		$stop .= $lang['reg_err_8'];
	}
	
	$db->free();
	
	if( $stop ) {
		msgbox( $lang['all_err_1'], $stop );
	} else {
		
		if( $_POST['allow_mail'] ) {
			$allow_mail = 0;
		} else {
			$allow_mail = 1;
		}
		
		$xfieldsaction = "init";
		$xfieldsadd = false;
		include (ENGINE_DIR . '/inc/userfields.php');
		$filecontents = array ();
		
		if( ! empty( $postedxfields ) ) {
			foreach ( $postedxfields as $xfielddataname => $xfielddatavalue ) {
				if( ! $xfielddatavalue ) {
					continue;
				}
				
				$xfielddatavalue = $db->safesql( $parse->BB_Parse( $parse->process( $xfielddatavalue ), false ) );
				
				$xfielddataname = $db->safesql( $xfielddataname );
				
				$xfielddataname = str_replace( "|", "&#124;", $xfielddataname );
				$xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue );
				$filecontents[] = "$xfielddataname|$xfielddatavalue";
			}
			
			$filecontents = implode( "||", $filecontents );
		} else
			$filecontents = '';
		
		if( strlen( $password1 ) > 0 ) {
			
			include_once (ENGINE_DIR . '/modules/vauth/passhash.php');
			$pass_hash = get_passhash(@md5($password1));		
			$password1 = md5( md5( $password1 ) );
			$sql_user = "UPDATE " . USERPREFIX . "_users set userpassword_hash='$pass_hash', fullname='$fullname', land='$land', icq='$icq',{$mailchange} info='$info', signature='$signature', password='$password1', allow_mail='$allow_mail', xfields='$filecontents', allowed_ip='$allowed_ip' WHERE name='$user'";

		
		} else {
			
			$sql_user = "UPDATE " . USERPREFIX . "_users set fullname='$fullname', land='$land', icq='$icq', email='$email', info='$info', signature='$signature', allow_mail='$allow_mail', xfields='$filecontents', allowed_ip='$allowed_ip' where name='$user'";
		
		}
		
		$db->query( $sql_user );

		if ( $_POST['subscribe'] ) $db->query( "DELETE FROM " . PREFIX . "_subscribe WHERE user_id = '{$row['user_id']}'" );
	}

}

//####################################################################################################################
//         Просмотр профиля пользователя
//####################################################################################################################


$parse = new ParseFilter( );

$user_found = FALSE;

$sql_result = $db->query( "SELECT *, COUNT(m.mid) AS cnt FROM ".USERPREFIX."_users u LEFT JOIN ".PREFIX."_mservice m ON u.user_id = m.uploader WHERE (name = '$user' AND approve = '1')" );

$tpl->load_template( 'userinfo.tpl' );

while ( $row = $db->get_row( $sql_result ) ) {
	
	if ($row['name'] == NULL) {
    $user_found = FALSE;
    break;
   }
	else $user_found = TRUE;
	
	include_once('vauth/userinfo.php');
	
	if( $row['banned'] == 'yes' ) $user_group[$row['user_group']]['group_name'] = $lang['user_ban'];
	$to_user_salt = $row['user_id']*19 - 17;
	if( $row['allow_mail'] ) {
  
		if ( !$user_group[$member_id['user_group']]['allow_feed'] AND $row['user_group'] != 1 )
			$tpl->set( '{email}', $lang['news_mail'], $output );
		else {
      if ($is_logged) $tpl->set( '{email}', "<a href=\"$PHP_SELF?do=feedback&amp;user=$to_user_salt\">" . $lang['news_mail'] . "</a> | " );
      else $tpl->set( '{email}', "Войдите в свой аккаунт, чтобы связаться с ".$row['name'] );
    }

	} else 	$tpl->set( '{email}', $lang['news_mail'], $output );

	if ( $user_group[$member_id['user_group']]['allow_pm'] )	
		$tpl->set( '{pm}', "<a href=\"$PHP_SELF?do=pm&amp;doaction=newpm&amp;username=" . $row['name'] . "\">" . $lang['news_pmnew'] . "</a>" );
	else {
		if ($is_logged) $tpl->set( '{pm}', $lang['news_pmnew'], $output );
		else $tpl->set( '{pm}', "" );
  }
	
	if( ! $row['allow_mail'] ) $mailbox = "checked";
	else $mailbox = "";
	
	if( $row['foto'] and (file_exists( ROOT_DIR . "/uploads/fotos/" . $row['foto'] )) ) $tpl->set( '{foto}', $config['http_home_url'] . "uploads/fotos/" . $row['foto'] );
	else $tpl->set( '{foto}', "{THEME}/images/noavatar.png" );
	
	$tpl->set( '{hidemail}', "<input type=\"checkbox\" name=\"allow_mail\" value=\"1\" " . $mailbox . " /> " . $lang['news_noamail'] );
	$tpl->set( '{fullname}', stripslashes( $row['fullname'] ) );
	$tpl->set( '{icq}', stripslashes( $row['icq'] ) );
	$tpl->set( '{land}', stripslashes( $row['land'] ) );
	$tpl->set( '{info}', stripslashes( $row['info'] ) );
	$tpl->set( '{editmail}', stripslashes( $row['email'] ) );
	$tpl->set( '{comm_num}', $row['comm_num'] );
	$tpl->set( '{news_num}', $row['news_num'] );
	
//**********************************************************
//DleMusic Service by odmin	
	if ( $member_id['user_group'] == 1 ) {
    $usertitle_odmin_edit = <<<HTML
<script language='JavaScript' type="text/javascript">
<!--
function popupedit(id){
    window.open('/{$config['admin_path']}?mod=editusers&action=edituser&id='+id,'User','toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500');
}
-->
</script>
HTML;
    $tpl->set( '{usertitle}', $usertitle_odmin_edit."<a class=maintitle onClick=\"javascript:popupedit('{$row['user_id']}'); return(false)\" href=#>".stripslashes( $row['name'])."</a>" );
  }
	else {$tpl->set( '{usertitle}', stripslashes( $row['name'] ) );}
//**********************************************************
  if ( $member_id['user_group'] == 1 ) {$tpl->set( '{by_odmin_use_demo_vip_group}', '' );}
  elseif ($row['user_id'] != $member_id['user_id']) {$tpl->set( '{by_odmin_use_demo_vip_group}', '' );}
  elseif ( ($member_id['use_demo_vip_group'] == 0) && ($member_id['user_group'] != 7) ) {
    $tpl->set( '{by_odmin_use_demo_vip_group}', '<div id="test_vip_group">У каждого пользователя есть 1 возможность на 1 сутки протестировать преимущества нахождения в группе <span style="color:#ff00b2;">VIP Посетители</span>. <a href="#" onclick="testVipGroup( \''.((($member_id['user_id']+1203)*7)-11).'\' ); return false;" title="Использовать свой тестовый период в группе VIP Посетители">Использовать свой тестовый период в группе VIP Посетители</a>.</div>' );//шифровка uid с солью
  }
  elseif ( $member_id['use_demo_vip_group'] == 1 ) {$tpl->set( '{by_odmin_use_demo_vip_group}', 'Вы уже использовали свою возможность протестировать группу <span style="color:#ff00b2;">VIP Посетители</span>.' );}
  else {$tpl->set( '{by_odmin_use_demo_vip_group}', '' );}
//**********************************************************
if ( $member_id['user_group'] == 1 ) {$tpl->set( '{link_vip_group}', '' );}
elseif ($row['user_id'] != $member_id['user_id']) {$tpl->set( '{link_vip_group}', '' );}
else {$tpl->set( '{link_vip_group}', '<p><a href="/vip_group.html" class="reg_inf" title="Отключить рекламу и перейти в группу VIP Посетители!" style="font-family:Tahoma; font-size:18px; text-align:center; padding:10px 30px;" onMouseOver="this.style.background=\'#EF5151\';this.style.color=\'#ffffff\'" onMouseOut="this.style.background=\'#f7a8a8\';this.style.color=\'#3367ab\'">Отключить рекламу и перейти в группу VIP Посетители!</a>' );}
//*****************end************************************
#**********************************************************
# DleMusic Service by Flexer, odmin
if ( $row['user_id'] == $member_id['user_id'] ) {
  $tpl->set( '{mytracks}', "<a href=\"" . $config['http_home_url'] . "music/mytracks.html" . "\"><img src='{THEME}/images/uploadcount.png' alt='Мои загруженные mp3-файлы' style='display:inline; vertical-align:middle;'><span style='margin-left:5px'>Мои загруженные mp3-файлы</span></a>");
  $tpl->set( '{myfavtracks}', "<br /><a href=\"" . $config['http_home_url'] . "music/myfavtracks.html" . "\"><i style='width:25px; height:24px; background:url(\"{THEME}/images/fav_mid.png\"); display:block; float:left; background-position:25px 0px;'></i><span style='margin-left:5px; line-height:25px;'>Мои избранные mp3-файлы</span></a>");
    $tpl->set( '{myfavalbums}', "<br /><a href=\"" . $config['http_home_url'] . "music/myfavalbums.html" . "\"><i style='width:25px; height:24px; background:url(\"{THEME}/images/fav_album.png\"); display:block; float:left; background-position:25px 0px;'></i><span style='margin-left:5px; line-height:25px;'>Мои избранные альбомы</span></a>");
  $tpl->set( '{myfavartists}', "<br /><a href=\"" . $config['http_home_url'] . "music/myfavartists.html" . "\"><i style='width:25px; height:24px; background:url(\"{THEME}/images/fav_artist.png\"); display:block; float:left; background-position:25px 0px;'></i><span style='margin-left:5px; line-height:25px;'>Мои любимые исполнители</span></a>");
  $tpl->set( '{socakksedit}', "<a class=\"redprof\" style=\"width:230px; background-color:#acd373; border:1px #8dc63f solid; text-shadow:0px 0px 2px #8dc63f; margin:5px 5px 5px 0;\" href=\"/connect.html\">Управление социальными аккаунтами</a>");
  $tpl->set( '{add_buttons}', "<li><a href=\"/music/massaddfiles.html\" title=\"Загрузить mp3\">Загрузить mp3</a></li><li><a href=\"/addnews.html\" title=\"Добавить новость\">Добавить новость</a></li><li><a href=\"/favorites/\" title=\"Мои закладки\">Мои закладки</a></li>");
  if ($row['cnt'] > 0)  $tpl->set( '{tracks-count}', '<a href="/music/mytracks.html" title="Мои загруженные mp3-файлы" style="font-weight:bold;">'.$row['cnt'].'</a>');
  else $tpl->set( '{tracks-count}', $row['cnt'] );
} else {
  $tpl->set( '{mytracks}', "");
  $tpl->set( '{myfavtracks}', "");
  $tpl->set( '{myfavalbums}', "");
  $tpl->set( '{myfavartists}', "");
  $tpl->set( '{socakksedit}', "");
  $tpl->set( '{add_buttons}', "");
  if (($row['cnt'] > 0) AND ($member_id['user_group'] == 1))  $tpl->set( '{tracks-count}', '<a href="/music/usertracks-'.$row['user_id'].'.html" title="Просмотр тревов, закачанных юзером '.$row['name'].'" style="font-weight:bold;">'.$row['cnt'].'</a>');
  else  $tpl->set( '{tracks-count}', $row['cnt'] );
}

if ( ( $member_id['user_group'] == 1 ) && ( $row['user_id'] == $member_id['user_id'] ) ) {
	$tpl->set( '{admin_section1}', "<br /><b style=\"margin-left:10px; color:red; font-weight:bold;\">&raquo; Админстраницы</b><div class=\"line\" style=\"margin-bottom:5px;\"></div>");
	$tpl->set( '{downloads_top_24hr_top50}', "<li style='margin-left:10px'><a href=\"" . $config['http_home_url'] . "music/downloadstop24hr.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний mp3 за сутки (ТОП 50)</span></a></li>");
	$tpl->set( '{downloads_top_week_top50}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/downloadstopweek.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний mp3 за неделю (ТОП 50)</span></a></li><hr color=\"#e2e2e2\" align=\"left\" width=\"400\">");

	$tpl->set( '{downloads_top_24hr_admin}', "<li style='margin-left:10px'><a href=\"" . $config['http_home_url'] . "music/downloadstop24hr_admin.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний mp3 за сутки (полная версия)</span></a></li>");
	$tpl->set( '{downloads_top_week_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/downloadstopweek_admin.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний mp3 за неделю (полная версия)</span></a></li><hr color=\"#e2e2e2\" align=\"left\" width=\"400\">");
	
	$tpl->set( '{downloads_top_albums_24hr}', "<li style='margin-left:10px'><a href=\"" . $config['http_home_url'] . "music/downloadstopalbums24hr.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний музыкальных альбомов за сутки (ТОП 20)</span></a></li>");
	$tpl->set( '{downloads_top_albums_week}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/downloadstopalbumsweek.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний музыкальных альбомов за неделю (ТОП 20)</span></a></li><hr color=\"#e2e2e2\" align=\"left\" width=\"400\">");
	
	$tpl->set( '{downloads_top_albums_24hr_admin}', "<li style='margin-left:10px'><a href=\"" . $config['http_home_url'] . "music/downloadstopalbums24hr_admin.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний музыкальных альбомов за сутки (полная версия)</span></a></li>");
	$tpl->set( '{downloads_top_albums_week_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/downloadstopalbumsweek_admin.html\"\" target=\"_blank\"><span style='margin-left:5px'>Топ скачиваний музыкальных альбомов за неделю (полная версия)</span></a></li><hr color=\"#e2e2e2\" align=\"left\" width=\"400\">");
		
	$tpl->set( '{popular_searchartist_week_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/popularsearchartistweek.html\"\" target=\"_blank\"><span style='margin-left:5px'>Популярные поисковые запросы за неделю (по исполнителю)</span></a></li>");
	$tpl->set( '{popular_searchtitle_week_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/popularsearchtitleweek.html\"\" target=\"_blank\"><span style='margin-left:5px'>Популярные поисковые запросы за неделю (по названию трека)</span></a></li>");
	$tpl->set( '{popular_searchalbum_week_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/popularsearchalbumweek.html\"\" target=\"_blank\"><span style='margin-left:5px'>Популярные поисковые запросы за неделю (по названию альбома)</span></a></li>");
	$tpl->set( '{popular_search_zero_admin}', "<li style=\"margin-left:10px\"><a href=\"" . $config['http_home_url'] . "music/popularsearchzero.html\"\" target=\"_blank\"><span style='margin-left:5px'>Популярные поисковые запросы за неделю (с пустым результатом)</span></a></li>");
} else {
  $tpl->set( '{admin_section1}', "");
  $tpl->set( '{downloads_top_24hr_admin}', "");
  $tpl->set( '{downloads_top_week_admin}', "");
  $tpl->set( '{downloads_top_24hr_top50}', "");
  $tpl->set( '{downloads_top_week_top50}', "");
  $tpl->set( '{downloads_top_albums_24hr_admin}', "");
  $tpl->set( '{downloads_top_albums_week_admin}', "");
  $tpl->set( '{downloads_top_albums_24hr}', "");
  $tpl->set( '{downloads_top_albums_week}', "");
  $tpl->set( '{popular_searchartist_week_admin}', "");
  $tpl->set( '{popular_searchtitle_week_admin}', "");
  $tpl->set( '{popular_searchalbum_week_admin}', "");
  $tpl->set( '{popular_search_zero_admin}', "");
}
if ( $member_id['user_group'] == 1 ) {
  $sql_result2 = $db->super_query( "SELECT COUNT(DISTINCT mid) as cnt FROM ".PREFIX."_mservice_plays WHERE user_id = $row[user_id]" );
  if ($sql_result2['cnt'] > 0) $tpl->set( '{link_plays}', 'Прослушано треков (за месяц) - <a href="'.$config["http_home_url"].'music/monthuserplays-'.$row["user_id"].'.html" style="font-weight:bold;">'.$sql_result2['cnt'].'</a>');
  else $tpl->set( '{link_plays}', 'Прослушано треков (за месяц) - 0');
  
  $sql_result2 = $db->super_query( "SELECT COUNT(DISTINCT mid) as cnt FROM ".PREFIX."_mservice_downloads WHERE user_id = $row[user_id]" );
  if ($sql_result2['cnt'] > 0) $tpl->set( '{link_downloads}', '<br />Скачано треков (за месяц) - <a href="'.$config["http_home_url"].'music/monthuserdownloads-'.$row["user_id"].'.html" style="font-weight:bold;">'.$sql_result2['cnt'].'</a>');
  else $tpl->set( '{link_downloads}', '<br />Скачано треков (за месяц) - 0');
  
  $sql_result2 = $db->super_query( "SELECT COUNT(DISTINCT aid) as cnt2 FROM ".PREFIX."_mservice_downloads_albums WHERE user_id = $row[user_id]" );
  if ($sql_result2['cnt2'] > 0) $tpl->set( '{link_downloads_albums}', '<br />Скачано альбомов (за месяц) - <a href="'.$config["http_home_url"].'music/monthuserdownloads-albums-'.$row["user_id"].'.html" style="font-weight:bold;">'.$sql_result2['cnt2'].'</a>');
  else $tpl->set( '{link_downloads_albums}', '<br />Скачано альбомов (за месяц) - 0');  
} else  {
  $tpl->set( '{link_plays}', '');
  $tpl->set( '{link_downloads}', '');
  $tpl->set( '{link_downloads_albums}', '');
}
# end DleMusic Service by Flexer, odmin
#**********************************************************
	$tpl->set( '{status}',  $user_group[$row['user_group']]['group_prefix'].$user_group[$row['user_group']]['group_name'].$user_group[$row['user_group']]['group_suffix'] );
	$tpl->set( '{rate}', userrating( $row['user_id'] ) );
	$tpl->set( '{registration}', langdate( "j F Y H:i", $row['reg_date'] ) );
#**********************************************************
# Статус пользователя
#**********************************************************
 $fuser_status = '';
 $timer = 10;
 $dtime_1 = ( time() + ($config['date_adjust']*60) - ($timer*60) );
 $dtime_2 = ( time() + ($config['date_adjust']*60) + ($timer*60) );
 if ( $row['lastdate'] > $dtime_1 AND $row['lastdate'] < $dtime_2 ) $tpl->set('{statusonsite}', "<img src='{THEME}/images/krol_green.jpg' alt=' Пользователь онлайн' style='display:inline; vertical-align:middle;'><span style='color:green; margin-left:5px'>Онлайн</span>"); else $tpl->set('{statusonsite}', "<img src='{THEME}/images/krol_red.jpg' alt='Пользователь оффлайн' style='display:inline; vertical-align:middle;'><span style='color:#DF1801; margin-left:5px'>Оффлайн</span>");
 if ( $is_logged and ($row['user_id'] == $member_id['user_id']) ) $tpl->set('{statusonsite}', "<img src='{THEME}/images/krol_green.jpg' alt='Пользователь онлайн' style='display:inline; vertical-align:middle;'><span style='color:green; margin-left:5px'>Онлайн</span>");
#**********************************************************
# end Статус пользователя
#**********************************************************
 
	$tpl->set( '{lastdate}', langdate( "j F Y H:i", $row['lastdate'] ) );
	
	if( $user_group[$row['user_group']]['icon'] ) $tpl->set( '{group-icon}', "<img src=\"" . $user_group[$row['user_group']]['icon'] . "\" border=\"0\" />" );
	else $tpl->set( '{group-icon}', "" );
	
	if( $is_logged and $user_group[$row['user_group']]['time_limit'] and ($member_id['user_id'] == $row['user_id'] or $member_id['user_group'] < 3) ) {
		
		$tpl->set_block( "'\\[time_limit\\](.*?)\\[/time_limit\\]'si", "\\1" );
		
		if( $row['time_limit'] ) {
			
			$tpl->set( '{time_limit}', langdate( "j F Y H:i", $row['time_limit'] ) );
		
		} else {
			
			$tpl->set( '{time_limit}', $lang['no_limit'] );
		
		}
	
	} else {
		
		$tpl->set_block( "'\\[time_limit\\](.*?)\\[/time_limit\\]'si", "" );
	
	}
	
	$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
	
	$tpl->set( '{ip}', $_IP );
	$tpl->set( '{allowed-ip}', stripslashes( str_replace( "|", "\n", $row['allowed_ip'] ) ) );
	$tpl->set( '{editinfo}', $parse->decodeBBCodes( $row['info'], false ) );
	
	if( $user_group[$row['user_group']]['allow_signature'] ) $tpl->set( '{editsignature}', $parse->decodeBBCodes( $row['signature'], false ) );
	else $tpl->set( '{editsignature}', $lang['sig_not_allowed'] );
	
	if( $row['comm_num'] ) {
		
		$tpl->set( '{comments}', "<a href=\"$PHP_SELF?do=lastcomments&amp;userid=" . $row['user_id'] . "\">" . $lang['last_comm'] . "</a>" );
	
	} else {
		
		$tpl->set( '{comments}', "" );
	
	}
	
	if( $row['news_num'] ) {
		
		if( $config['allow_alt_url'] == "yes" ) {
			
			$tpl->set( '{news}', "<a href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/news/" . "\">" . $lang['all_user_news'] . "</a>" );
			$tpl->set( '[rss]', "<a href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/rss.xml" . "\" title=\"" . $lang['rss_user'] . "\">" );
			$tpl->set( '[/rss]', "</a>" );
		
		} else {
			
			$tpl->set( '{news}', "<a href=\"" . $PHP_SELF . "?subaction=allnews&amp;user=" . urlencode( $row['name'] ) . "\">" . $lang['all_user_news'] . "</a>" );
			$tpl->set( '[rss]', "<a href=\"engine/rss.php?subaction=allnews&amp;user=" . urlencode( $row['name'] ) . "\" title=\"" . $lang['rss_user'] . "\">" );
			$tpl->set( '[/rss]', "</a>" );
		}
	} else {
		
		$tpl->set( '{news}', $lang['all_user_news'] );
		$tpl->set_block( "'\\[rss\\](.*?)\\[/rss\\]'si", "" );
	
	}
	
	if( $row['signature'] and $user_group[$row['user_group']]['allow_signature'] ) {
		
		$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "\\1" );
		$tpl->set( '{signature}', stripslashes( $row['signature'] ) );
	
	} else {
		
		$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "" );
	
	}
	
	$xfieldsaction = "list";
	$xfieldsadd = false;
	$xfieldsid = $row['xfields'];
	include (ENGINE_DIR . '/inc/userfields.php');
	$tpl->set( '{xfields}', $output );
	
	// Обработка дополнительных полей
	$xfieldsdata = xfieldsdataload( $row['xfields'] );
	
	foreach ( $xfields as $value ) {
		$preg_safe_name = preg_quote( $value[0], "'" );
		
		if( $value[5] != 1 or ($is_logged and $member_id['user_group'] == 1) or ($is_logged and $member_id['user_id'] == $row['user_id']) ) {
			if( empty( $xfieldsdata[$value[0]] ) ) {
				$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
			} else {
				$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $tpl->copy_template );
			}
			$tpl->copy_template = preg_replace( "'\\[xfvalue_{$preg_safe_name}\\]'i", stripslashes( $xfieldsdata[$value[0]] ), $tpl->copy_template );
		} else {
			$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
			$tpl->copy_template = preg_replace( "'\\[xfvalue_{$preg_safe_name}\\]'i", "", $tpl->copy_template );
		}
	}
	// Обработка дополнительных полей
	if( $is_logged and ($member_id['user_id'] == $row['user_id'] or $member_id['user_group'] == 1) ) {
		$tpl->set( '{edituser}', "<span class=\"redprof\"><a href=\"javascript:ShowOrHide('options')\">" . $lang['news_option'] . "</a></span>" );
	} else
		$tpl->set( '{edituser}', "" );
	
	if( $is_logged and ($member_id['user_id'] == $row['user_id'] or $member_id['user_group'] == 1) ) {
		$tpl->set( '[not-logged]', "" );
		$tpl->set( '[/not-logged]', "" );
	} else
		$tpl->set_block( "'\\[not-logged\\](.*?)\\[/not-logged\\]'si", "<!-- profile -->" );
	
	if( $config['allow_alt_url'] == "yes" ) $link_profile = $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/";
	else $link_profile = $PHP_SELF . "?subaction=userinfo&user=" . urlencode( $row['name'] );
	
	if( $is_logged and ($member_id['user_id'] == $row['user_id'] or $member_id['user_group'] == 1) ) {
		$tpl->copy_template = "<form  method=\"post\" name=\"userinfo\" id=\"userinfo\" enctype=\"multipart/form-data\" action=\"{$link_profile}\">" . $tpl->copy_template . "
		<input type=\"hidden\" name=\"doaction\" value=\"adduserinfo\" />
		<input type=\"hidden\" name=\"dle_allow_hash\" value=\"{$dle_login_hash}\" />
		</form>";
	}
	
	$tpl->compile( 'content' );

}

$tpl->clear();
$db->free( $sql_result );

if( $user_found == FALSE ) {
	$allow_active_news = false;
	msgbox( $lang['all_err_1'], $lang['news_err_26'] );
}
?>