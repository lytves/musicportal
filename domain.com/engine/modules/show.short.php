<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if( $allow_active_news ) {
	
	if( $config['allow_banner'] ) include_once ENGINE_DIR . '/modules/banners.php';
	
	$i = $cstart;
	$news_found = FALSE;
	
	if( isset( $view_template ) and $view_template == "rss" ) {
	} elseif( $category_id and $cat_info[$category_id]['short_tpl'] != '' ) $tpl->load_template( $cat_info[$category_id]['short_tpl'] . '.tpl' );
	else $tpl->load_template( 'shortstory.tpl' );
	
	if( strpos( $tpl->copy_template, "[xfvalue_" ) !== false ) $xfound = true;
	else $xfound = false;
	
	if( $xfound ) $xfields = xfieldsload();
	
	if( count( $banners ) AND $config['allow_banner'] AND !$smartphone_detected) {
		
		$news_c = 1;
		
		if( isset( $ban_short ) ) {
			for($indx = 0, $max = sizeof( $ban_short['top'] ), $banners_topz = ''; $indx < $max; $indx ++)
				if( $ban_short['top'][$indx]['zakr'] ) {
					$banners_topz .= $ban_short['top'][$indx]['text'];
					unset( $ban_short['top'][$indx] );
				}
			
			for($indx = 0, $max = sizeof( $ban_short['cen'] ), $banners_cenz = ''; $indx < $max; $indx ++)
				if( $ban_short['cen'][$indx]['zakr'] ) {
					$banners_cenz .= $ban_short['cen'][$indx]['text'];
					unset( $ban_short['cen'][$indx] );
				}
			
			for($indx = 0, $max = sizeof( $ban_short['down'] ), $banners_downz = ''; $indx < $max; $indx ++)
				if( $ban_short['down'][$indx]['zakr'] ) {
					$banners_downz .= $ban_short['down'][$indx]['text'];
					unset( $ban_short['down'][$indx] );
				}
			
			$middle = floor( $config['news_number'] / 2 );
			$middle_s = floor( ($middle - 1) / 2 );
			$middle_e = floor( $middle + (($config['news_number'] - $middle) / 2) + 1 );
		}
	}
	
	$sql_result = $db->query( $sql_select );
	
	if( ! isset( $view_template ) ) {
		
		$count_all = $db->super_query( $sql_count );
		$count_all = $count_all['count'];
	
	} else
		$count_all = 0;
	
	while ( $row = $db->get_row( $sql_result ) ) {
		
		$news_found = TRUE;
		$attachments[] = $row['id'];
		$row['date'] = strtotime( $row['date'] );
		
		if( isset( $middle ) ) {
			
			if( $news_c == $middle_s ) {
				$tpl->copy_template .= bannermass( $banners_topz, $ban_short['top'] );
			} else if( $news_c == $middle ) {
				$tpl->copy_template .= bannermass( $banners_cenz, $ban_short['cen'] );
			} else if( $news_c == $middle_e ) {
				$tpl->copy_template .= bannermass( $banners_downz, $ban_short['down'] );
			}
			$news_c ++;
		}
		
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
		
		$news_find = array ('{comments-num}' => $row['comm_num'], '{views}' => $row['news_read'], '{category}' => $my_cat, '{link-category}' => $my_cat_link, '{news-id}' => $row['id'], '{PAGEBREAK}' => '' );
		
		$tpl->set( '', $news_find );
		
		if( $cat_info[$row['category']]['icon'] ) {
			
			$tpl->set( '{category-icon}', $cat_info[$row['category']]['icon'] );
		
		} else {
			
			$tpl->set( '{category-icon}', "{THEME}/dleimages/no_icon.gif" );
		
		}
		
		if( date( Ymd, $row['date'] ) == date( Ymd, $_TIME ) ) {
			
			$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $row['date'] ) );
		
		} elseif( date( Ymd, $row['date'] ) == date( Ymd, ($_TIME - 86400) ) ) {
			
			$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $row['date'] ) );
		
		} else {
			
			$tpl->set( '{date}', langdate( $config['timestamp_active'], $row['date'] ) );
		
		}

		$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$row['date']}')", $tpl->copy_template );


		if ( $row['fixed'] ) {

			$tpl->set( '[fixed]', "" );
			$tpl->set( '[/fixed]', "" );
			$tpl->set_block( "'\\[not-fixed\\](.*?)\\[/not-fixed\\]'si", "" );

		} else {

			$tpl->set( '[not-fixed]', "" );
			$tpl->set( '[/not-fixed]', "" );
			$tpl->set_block( "'\\[fixed\\](.*?)\\[/fixed\\]'si", "" );
		}		


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
		
		$go_page = "onclick=\"return dropdownmenu(this, event, UserNewsMenu('" . htmlspecialchars( $go_page ) . "', '" . htmlspecialchars( $news_page ) . "','" . urlencode( $row['autor'] ) . "', '" . $user_group[$member_id['user_group']]['admin_editusers'] . "'), '170px')\" onmouseout=\"delayhidemenu()\"";
		
		if( $config['allow_alt_url'] == "yes" ) $tpl->set( '{author}', "<a {$go_page} href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['autor'] ) . "/\">" . $row['autor'] . "</a>" );
		else $tpl->set( '{author}', "<a {$go_page} href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['autor'] ) . "\">" . $row['autor'] . "</a>" );
		
		if( $allow_userinfo and ! $row['approve'] and ($member_id['name'] == $row['autor'] and ! $user_group[$member_id['user_group']]['allow_all_edit']) ) {
			$tpl->set( '[edit]', "<a href=\"" . $config['http_home_url'] . "index.php?do=addnews&id=" . $row['id'] . "\" >" );
			$tpl->set( '[/edit]', "</a>" );
		} elseif( $is_logged and (($member_id['name'] == $row['autor'] and $user_group[$member_id['user_group']]['allow_edit']) or $user_group[$member_id['user_group']]['allow_all_edit']) ) {
			
			$_SESSION['referrer'] = $_SERVER['REQUEST_URI'];
			$tpl->set( '[edit]', "<a onclick=\"return dropdownmenu(this, event, MenuNewsBuild('" . $row['id'] . "', 'short'), '170px')\" href=\"#\">" );
			$tpl->set( '[/edit]', "</a>" );
			$allow_comments_ajax = true;
		} else
			$tpl->set_block( "'\\[edit\\](.*?)\\[/edit\\]'si", "" );
		
		$go_page = ($config['ajax']) ? "onclick=\"DlePage('newsid=" . $row['id'] . "'); return false;\" " : "";
		if( $config['allow_comments_wysiwyg'] == "yes" ) $go_page = '';
		
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
		
		if( (strlen( $row['full_story'] ) < 13) and $config['hide_full_link'] == "yes" ) $tpl->set_block( "'\\[full-link\\](.*?)\\[/full-link\\]'si", "" );
		else {
			
			$tpl->set( '[full-link]', "<a {$go_page}href=\"" . $full_link . "\">" );
			
			$tpl->set( '[/full-link]', "</a>" );
		}
		
		$tpl->set( '{full-link}', $full_link );
		
		if( $row['allow_comm'] ) {
			
			$tpl->set( '[com-link]', "<a {$go_page}href=\"" . $full_link . "#comment\">" );
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
			
			$fav_arr = explode( ',', $member_id['favorites'] );
			
			if( ! in_array( $row['id'], $fav_arr ) or $config['allow_cache'] == "yes" ) $tpl->set( '{favorites}', "<a id=\"fav-id-" . $row['id'] . "\" href=\"$PHP_SELF?do=favorites&amp;doaction=add&amp;id=" . $row['id'] . "\"><img src=\"" . $config['http_home_url'] . "templates/{$config['skin']}/dleimages/plus_fav.gif\" onclick=\"doFavorites('" . $row['id'] . "', 'plus'); return false;\" title=\"" . $lang['news_addfav'] . "\" style=\"vertical-align: middle;border: none;\" alt=\"\" /></a>" );
			else $tpl->set( '{favorites}', "<a id=\"fav-id-" . $row['id'] . "\" href=\"$PHP_SELF?do=favorites&amp;doaction=del&amp;id=" . $row['id'] . "\"><img src=\"" . $config['http_home_url'] . "templates/{$config['skin']}/dleimages/minus_fav.gif\" onclick=\"doFavorites('" . $row['id'] . "', 'minus'); return false;\" title=\"" . $lang['news_minfav'] . "\" style=\"vertical-align: middle;border: none;\" alt=\"\" /></a>" );
		
		} else
			$tpl->set( '{favorites}', "" );
		
		if( $allow_userinfo and ! $row['approve'] ) {
			
			$tpl->set( '{approve}', $lang['approve'] );
		
		} else
			$tpl->set( '{approve}', "" );
			
		// Обработка дополнительных полей
		if( $xfound ) {
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
		}
		// Обработка дополнительных полей
		

		if( $view_template == "rss" ) {
			
			$tpl->set( '{rsslink}', $full_link );
			$tpl->set( '{rssauthor}', $row['autor'] );
			$tpl->set( '{rssdate}', date( "r", $row['date'] ) );
			$tpl->set( '{title}', htmlspecialchars( strip_tags( stripslashes( $row['title'] ) ) ) );
			
			if( $config['rss_format'] != 1 ) {
				
				$row['short_story'] = trim (htmlspecialchars( strip_tags( stripslashes( str_replace( "<br />", " ", $row['short_story'] ) ) ) ) );
			
			} else {
				
				$row['short_story'] = stripslashes( $row['short_story'] );
			
			}
			
			$tpl->set( '{short-story}', $row['short_story'] );
			
			if( $config['rss_format'] == 2 ) {

				$row['full_story'] = trim( htmlspecialchars( stripslashes( $row['full_story'] ), ENT_QUOTES ) );

				if( $row['full_story'] == "" ) $row['full_story'] = $row['short_story'];
				
				$tpl->set( '{full-story}', $row['full_story'] );
			
			}
		
		} else {

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
			
			$tpl->set( '{title}', stripslashes( $row['title'] ) );
			$tpl->set( '{short-story}', stripslashes( "<div id='news-id-" . $row['id'] . "'>" . $row['short_story'] . "</div>" ) );
		
		}
		
		$tpl->compile( 'content' );

		if( $user_group[$member_id['user_group']]['allow_hide'] ) $tpl->result['content'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['content']);
		else $tpl->result['content'] = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $tpl->result['content'] );

	}
	
	$tpl->clear();
	$db->free( $sql_result );
	
	if( $do == "" ) $do = $subaction;
	if( $do == "" and $year ) $do = "date";
	$ban_short = array ();
	unset( $ban_short );
	
	if( ! $news_found and $allow_userinfo and $member_id['name'] == $user and $user_group[$member_id['user_group']]['allow_adds'] ) {
		$tpl->load_template( 'info.tpl' );
		$tpl->set( '{error}', $lang['mod_list_f'] );
		$tpl->set( '{title}', $lang['all_info'] );
		$tpl->compile( 'content' );
		$tpl->clear();
	} elseif( ! $news_found and ! $allow_userinfo and $do != '' and $do != 'favorites' and $view_template != 'rss' ) {
		if ( $newsmodule ) @header( "HTTP/1.0 404 Not Found" );
		msgbox( $lang['all_err_1'], $lang['news_err_27'] );
	} elseif( ! $news_found and $catalog != "" ) {
		if ( $newsmodule ) @header( "HTTP/1.0 404 Not Found" );
		msgbox( $lang['all_err_1'], $lang['news_err_27'] );
	} elseif( ! $news_found and $do == 'favorites' ) {

		if ( $member_id['favorites'] ) $db->query( "UPDATE " . USERPREFIX . "_users SET favorites='' WHERE user_id = '{$member_id['user_id']}'" );

		msgbox( $lang['all_info'], $lang['fav_notfound'] );
	}
	
	//####################################################################################################################
	//         Навигация по новостям
	//####################################################################################################################
	if( ! isset( $view_template ) and $count_all ) {
		
		$tpl->load_template( 'navigation.tpl' );
		
		//----------------------------------
		// Previous link
		//----------------------------------
		

		$no_prev = false;
		$no_next = false;
		
		if( isset( $cstart ) and $cstart != "" and $cstart > 0 ) {
			$prev = $cstart / $config['news_number'];
			
			if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart=" . $prev . "&" . $user_query . "'); return false;\" ";
			else $go_page = "";
			
			if( $config['allow_alt_url'] == "yes" ) {
				$prev_page = $url_page . "/page/" . $prev . "/";
				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a {$go_page}href=\"" . $prev_page . "\">\\1</a>" );
			} else {
				$prev_page = $PHP_SELF . "?cstart=" . $prev . "&amp;" . $user_query;
				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a {$go_page}href=\"" . $prev_page . "\">\\1</a>" );
			}
		
		} else {
			$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "" );
			$no_prev = TRUE;
		}
		
		//----------------------------------
		// Pages
		//----------------------------------
		if( $config['news_number'] ) {
			
			if( $count_all > $config['news_number'] ) {
				
				$enpages_count = @ceil( $count_all / $config['news_number'] );
				$pages = "";
				
				$cstart = ($cstart / $config['news_number']) + 1;
				
				if( $enpages_count <= 10 ) {
					
					for($j = 1; $j <= $enpages_count; $j ++) {
						
						if( $j != $cstart ) {
							
							if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart=" . $j . "&" . $user_query . "'); return false;\" ";
							else $go_page = "";
							
							if( $config['allow_alt_url'] == "yes" ) $pages .= "<a {$go_page}href=\"" . $url_page . "/page/" . $j . "/\">$j</a> ";
							else $pages .= "<a {$go_page}href=\"$PHP_SELF?cstart=$j&amp;$user_query\">$j</a> ";
						
						} else {
							
							$pages .= "<span>$j</span> ";
						}
					
					}
				
				} else {
					
					$start = 1;
					$end = 10;
					$nav_prefix = "<span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
					
					if( $cstart > 0 ) {
						
						if( $cstart > 6 ) {
							
							$start = $cstart - 4;
							$end = $start + 8;
							
							if( $end >= $enpages_count ) {
								$start = $enpages_count - 9;
								$end = $enpages_count - 1;
								$nav_prefix = "";
							} else
								$nav_prefix = "<span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
						
						}
					
					}
					
					if( $start >= 2 ) {
						
						if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart=1&" . $user_query . "'); return false;\" ";
						else $go_page = "";
						
						if( $config['allow_alt_url'] == "yes" ) $pages .= "<a {$go_page}href=\"" . $url_page . "/page/1/\">1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
						else $pages .= "<a {$go_page}href=\"$PHP_SELF?cstart=1&amp;$user_query\">1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
					
					}
					
					for($j = $start; $j <= $end; $j ++) {
						
						if( $j != $cstart ) {
							
							if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart=" . $j . "&" . $user_query . "'); return false;\" ";
							else $go_page = "";
							
							if( $config['allow_alt_url'] == "yes" ) $pages .= "<a {$go_page}href=\"" . $url_page . "/page/" . $j . "/\">$j</a> ";
							else $pages .= "<a {$go_page}href=\"$PHP_SELF?cstart=$j&amp;$user_query\">$j</a> ";
						
						} else {
							
							$pages .= "<span>$j</span> ";
						}
					
					}
					
					if( $cstart != $enpages_count ) {
						
						if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart={$enpages_count}&" . $user_query . "'); return false;\" ";
						else $go_page = "";
						
						if( $config['allow_alt_url'] == "yes" ) $pages .= $nav_prefix . "<a {$go_page}href=\"" . $url_page . "/page/{$enpages_count}/\">{$enpages_count}</a>";
						else $pages .= $nav_prefix . "<a {$go_page}href=\"$PHP_SELF?cstart={$enpages_count}&amp;$user_query\">{$enpages_count}</a>";
					
					} else
						$pages .= "<span>{$enpages_count}</span> ";
				
				}
			
			}
			$tpl->set( '{pages}', $pages );
		}
		
		//----------------------------------
		// Next link
		//----------------------------------
		if( $config['news_number'] and $config['news_number'] < $count_all and $i < $count_all ) {
			$next_page = $i / $config['news_number'] + 1;
			
			if( $config['ajax'] ) $go_page = "onclick=\"DlePage('cstart=" . $next_page . "&" . $user_query . "'); return false;\" ";
			else $go_page = "";
			
			if( $config['allow_alt_url'] == "yes" ) {
				$next = $url_page . '/page/' . $next_page . '/';
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a {$go_page}href=\"" . $next . "\">\\1</a>" );
			} else {
				$next = $PHP_SELF . "?cstart=" . $next_page . "&amp;" . $user_query;
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a {$go_page}href=\"" . $next . "\">\\1</a>" );
			}
			;
		
		} else {
			$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "" );
			$no_next = TRUE;
		}
		
		if( ! $no_prev or ! $no_next ) {
			$tpl->compile( 'content' );
		}
		
		$tpl->clear();
	}
}
?>