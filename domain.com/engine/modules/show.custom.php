<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$i = 0;
if( isset( $cstart ) ) $i = $cstart;
$news_found = FALSE;
$xfields = xfieldsload();

$tpl->load_template( $custom_template . '.tpl' );

$sql_result = $db->query( $sql_select );

while ( $row = $db->get_row( $sql_result ) ) {
	
	$news_found = TRUE;
	$attachments[] = $row['id'];
	$row['date'] = strtotime( $row['date'] );
	
	$i ++;
	
	if( ! $row['category'] ) {
		$my_cat = "---";
		$my_cat_link = "---";
	} else {
		
		$my_cat = array ();
		$my_cat_link = array ();
		$cat_list = explode( ',', $row['category'] );
		
		if( count( $cat_list ) == 1 ) {
			
			$my_cat[] = $cat_info[$cat_list[0]]['name'];
			
			$my_cat_link = get_categories( $cat_list[0] );
		
		} else {
			
			foreach ( $cat_list as $element ) {
				if( $element ) {
					$my_cat[] = $cat_info[$element]['name'];
					if( $config['ajax'] ) $go_page = "onclick=\"DlePage('do=cat&category={$cat_info[$element]['alt_name']}'); return false;\" ";
					else $go_page = "";
					if( $config['allow_alt_url'] == "yes" ) $my_cat_link[] = "<a {$go_page}href=\"" . $config['http_home_url'] . get_url( $element ) . "/\">{$cat_info[$element]['name']}</a>";
					else $my_cat_link[] = "<a {$go_page}href=\"$PHP_SELF?do=cat&category={$cat_info[$element]['alt_name']}\">{$cat_info[$element]['name']}</a>";
				}
			}
			
			$my_cat_link = implode( ', ', $my_cat_link );
		}
		
		$my_cat = implode( ', ', $my_cat );
	}
	
	$row['category'] = intval( $row['category'] );
	
	$news_find = array ('{comments-num}' => $row['comm_num'], '{views}' => $row['news_read'], '{category}' => $my_cat, '{link-category}' => $my_cat_link, '{news-id}' => $row['id'], '{php-self}' => $PHP_SELF, '{PAGEBREAK}' => '', '{rssdate}' => date( "r", $row['date'] ), '{rssauthor}' => $row['autor'], '{approve}' => '' );
	
	$tpl->set( '', $news_find );
	
	if( date( Ymd, $row['date'] ) == date( Ymd, $_TIME ) ) {
		
		$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $row['date'] ) );
	
	} elseif( date( Ymd, $row['date'] ) == date( Ymd, ($_TIME - 86400) ) ) {
		
		$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $row['date'] ) );
	
	} else {
		
		$tpl->set( '{date}', langdate( $config['timestamp_active'], $row['date'] ) );
	
	}

	$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$row['date']}')", $tpl->copy_template );

	
	if( $row['view_edit'] and $row['editdate'] ) {
		
		if( date( Ymd, $row['editdate'] ) == date( Ymd, $_TIME ) ) {
			
			$tpl->set( '{edit-date}', $lang['time_heute'] . langdate( ", H:i", $row['editdate'] ) );
		
		} elseif( date( Ymd, $row['editdate'] ) == date( Ymd, ($_TIME - 86400) ) ) {
			
			$tpl->set( '{edit-date}', $lang['time_gestern'] . langdate( ", H:i", $row['editdate'] ) );
		
		} else {
			
			$tpl->set( '{edit-date}', langdate( $config['timestamp_active'], $row['editdate'] ) );
		
		}
		
		$tpl->set( '{editor}', $row['editor'] );
		$tpl->set( '{edit-reason}', $row['reason'] );
		
		if( $row['reason'] ) {
			
			$tpl->set( '[edit-reason]', "" );
			$tpl->set( '[/edit-reason]', "" );
		
		} else
			$tpl->set_block( "'\\[edit-reason\\](.*?)\\[/edit-reason\\]'si", "" );
		
		$tpl->set( '[edit-date]', "" );
		$tpl->set( '[/edit-date]', "" );
	
	} else {
		
		$tpl->set( '{edit-date}', "" );
		$tpl->set( '{editor}', "" );
		$tpl->set( '{edit-reason}', "" );
		$tpl->set_block( "'\\[edit-date\\](.*?)\\[/edit-date\\]'si", "" );
		$tpl->set_block( "'\\[edit-reason\\](.*?)\\[/edit-reason\\]'si", "" );
	}
	
	if( $config['allow_tags'] and $row['tags'] ) {
		
		$tpl->set( '[tags]', "" );
		$tpl->set( '[/tags]', "" );
		
		$tags = array ();
		
		$row['tags'] = explode( ",", $row['tags'] );
		
		foreach ( $row['tags'] as $value ) {
			
			$value = trim( $value );
			
			$go_page = ($config['ajax']) ? "onclick=\"DlePage('do=tags&amp;tag=" . urlencode( $value ) . "'); return false;\" " : "";
			
			if( $config['allow_alt_url'] == "yes" ) $tags[] = "<a {$go_page} href=\"" . $config['http_home_url'] . "tags/" . urlencode( $value ) . "/\">" . $value . "</a>";
			else $tags[] = "<a {$go_page} href=\"$PHP_SELF?do=tags&amp;tag=" . urlencode( $value ) . "\">" . $value . "</a>";
		
		}
		
		$tpl->set( '{tags}', implode( ", ", $tags ) );
	
	} else {
		
		$tpl->set_block( "'\\[tags\\](.*?)\\[/tags\\]'si", "" );
		$tpl->set( '{tags}', "" );
	
	}
	
	if( $cat_info[$row['category']]['icon'] ) {
		
		$tpl->set( '{category-icon}', $cat_info[$row['category']]['icon'] );
	
	} else {
		
		$tpl->set( '{category-icon}', "{THEME}/dleimages/no_icon.gif" );
	
	}
	
	if( $row['allow_rate'] ) {
		
		if( $config['short_rating'] and $user_group[$member_id['user_group']]['allow_rating'] ) $tpl->set( '{rating}', ShortRating( $row['id'], $row['rating'], $row['vote_num'], 1 ) );
		else $tpl->set( '{rating}', ShortRating( $row['id'], $row['rating'], $row['vote_num'], 0 ) );
	
	} else
		$tpl->set( '{rating}', "" );
	
	if( $config['ajax'] ) {
		
		$go_page = "onclick=\"DlePage(\'subaction=userinfo&user=" . urlencode( $row['autor'] ) . "\'); return false;\" ";
		$news_page = "onclick=\"DlePage(\'subaction=allnews&user=" . urlencode( $row['autor'] ) . "\'); return false;\" ";
	
	} else {
		$go_page = "";
		$news_page = "";
	}
	
	if( $config['allow_alt_url'] == "yes" ) {
		
		$go_page .= "href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['autor'] ) . "/\"";
		$news_page .= "href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['autor'] ) . "/news/\"";

		$tpl->set( '[day-news]', "<a href=\"".$config['http_home_url'] . date( 'Y/m/d/', $row['date'])."\" >" );
	
	} else {
		
		$go_page .= "href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['autor'] ) . "\"";
		$news_page .= "href=\"$PHP_SELF?subaction=allnews&amp;user=" . urlencode( $row['autor'] ) . "\"";

		$tpl->set( '[day-news]', "<a href=\"$PHP_SELF?year=".date( 'Y', $row['date'])."&amp;month=".date( 'm', $row['date'])."&amp;day=".date( 'd', $row['date'])."\" >" );
	
	}

	$tpl->set( '[/day-news]', "</a>" );
	
	$go_page = "onClick=\"return dropdownmenu(this, event, UserNewsMenu('" . htmlspecialchars( $go_page ) . "', '" . htmlspecialchars( $news_page ) . "','" . urlencode( $row['autor'] ) . "', '" . $user_group[$member_id['user_group']]['admin_editusers'] . "'), '170px')\" onmouseout=\"delayhidemenu()\"";
	
	if( $config['allow_alt_url'] == "yes" ) $tpl->set( '{author}', "<a {$go_page} href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['autor'] ) . "/\">" . $row['autor'] . "</a>" );
	else $tpl->set( '{author}', "<a {$go_page} href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['autor'] ) . "\">" . $row['autor'] . "</a>" );
	
	if( $is_logged and (($member_id['name'] == $row['autor'] and $user_group[$member_id['user_group']]['allow_edit']) or $user_group[$member_id['user_group']]['allow_all_edit']) ) {
		$tpl->set( '[edit]', "<a href=\"" . $config['http_home_url'] . $config['admin_path'] . "?mod=editnews&action=editnews&id=" . $row['id'] . "\" target=\"_blank\">" );
		$tpl->set( '[/edit]', "</a>" );
	} else
		$tpl->set_block( "'\\[edit\\](.*?)\\[/edit\\]'si", "" );
	
	if( $config['allow_alt_url'] == "yes" ) {
		
		if( $row['flag'] and $config['seo_type'] ) {
			
			if( $row['category'] and $config['seo_type'] == 2 ) {
				
				$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
			
			} else {
				
				$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
			
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
		}
	
	} else {
		
		$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
	
	}
	
	if( (strlen( $row['full_story'] ) < 10) and $config['hide_full_link'] == "yes" ) $tpl->set_block( "'\\[full-link\\](.*?)\\[/full-link\\]'si", "" );
	else {
		
		$tpl->set( '[full-link]', "<a href=\"" . $full_link . "\">" );
		$tpl->set( '[/full-link]', "</a>" );
	}
	
	$tpl->set( '{full-link}', $full_link );
	
	if( $row['allow_comm'] ) {
		
		$tpl->set( '[com-link]', "<a href=\"" . $full_link . "#comment\">" );
		$tpl->set( '[/com-link]', "</a>" );
	
	} else
		$tpl->set_block( "'\\[com-link\\](.*?)\\[/com-link\\]'si", "" );
	
	if( strpos( $tpl->copy_template, "[category=" ) !== false ) {
		$tpl->copy_template = preg_replace( "#\\[category=(.+?)\\](.*?)\\[/category\\]#ies", "check_category('\\1', '\\2', '{$category_id}')", $tpl->copy_template );
	}
	
	if( strpos( $tpl->copy_template, "[not-category=" ) !== false ) {
		$tpl->copy_template = preg_replace( "#\\[not-category=(.+?)\\](.*?)\\[/not-category\\]#ies", "check_category('\\1', '\\2', '{$category_id}', false)", $tpl->copy_template );
	}
	
	if( $is_logged ) {
		
		if( ! strstr( $member_id['favorites'], $row['id'] ) or $config['allow_cache'] == "yes" ) $tpl->set( '{favorites}', "<a href=\"$PHP_SELF?do=favorites&amp;doaction=add&amp;id=" . $row['id'] . "\"><img src=\"" . $config['http_home_url'] . "templates/{$config['skin']}/dleimages/plus_fav.gif\" title=\"" . $lang['news_addfav'] . "\" style=\"vertical-align: middle;border: none;\" alt=\"\" /></a>" );
		else $tpl->set( '{favorites}', "<a href=\"$PHP_SELF?do=favorites&amp;doaction=del&amp;id=" . $row['id'] . "\"><img src=\"" . $config['http_home_url'] . "templates/{$config['skin']}/dleimages/minus_fav.gif\" title=\"" . $lang['news_minfav'] . "\" style=\"vertical-align: middle;border: none;\" alt=\"\" /></a>" );
	
	} else
		$tpl->set( '{favorites}', "" );
		
	// Обработка дополнительных полей
	$xfieldsdata = xfieldsdataload( $row['xfields'] );
	
	foreach ( $xfields as $value ) {
		$preg_safe_name = preg_quote( $value[0], "'" );
		
		if( empty( $xfieldsdata[$value[0]] ) ) {
			$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "", $tpl->copy_template );
		} else {
			$tpl->copy_template = preg_replace( "'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $tpl->copy_template );
		}
		
		$tpl->copy_template = str_replace( "[xfvalue_{$preg_safe_name}]", stripslashes( $xfieldsdata[$value[0]] ), $tpl->copy_template );
	}
	// Обработка дополнительных полей
	

	$tpl->set( '{title}', stripslashes( $row['title'] ) );

	if ($smartphone_detected) {

		if (!$config['allow_smart_format']) {

				$row['short_story'] = strip_tags( $row['short_story'], '<p><br><a>' );

		} else {


			if ( !$config['allow_smart_images'] ) {
	
				$row['short_story'] = preg_replace( "#<!--TBegin-->(.+?)<!--TEnd-->#is", "", $row['short_story'] );
				$row['short_story'] = preg_replace( "#<img(.+?)>#is", "", $row['short_story'] );
	
			}
	
			if ( !$config['allow_smart_video'] ) {
	
				$row['short_story'] = preg_replace( "#<!--dle_video_begin(.+?)<!--dle_video_end-->#is", "", $row['short_story'] );
				$row['short_story'] = preg_replace( "#<!--dle_audio_begin(.+?)<!--dle_audio_end-->#is", "", $row['short_story'] );
	
			}

		}

	}

	$tpl->set( '{short-story}', stripslashes( $row['short_story'] ) );
	
	$tpl->compile( 'content' );

	if( $user_group[$member_id['user_group']]['allow_hide'] ) $tpl->result['content'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['content']);
	else $tpl->result['content'] = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $tpl->result['content'] );

}

$tpl->clear();
$db->free( $sql_result );
?>