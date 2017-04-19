<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

class DLE_Comments {

	var $db = false;
	var $query = false;
	var $cstart = 0;
	var $total_comments = 0;
	var $comments_per_pages = 0;
	var $intern_count = 0;
	var $extras_rules = array();

	function DLE_Comments( $db, $total_comments, $comments_per_pages ) {

		$this->db = $db;
		$this->total_comments = $total_comments;
		$this->comments_per_pages = $comments_per_pages;

		$this->cstart = intval( $_GET['cstart'] );
		
		if( $this->cstart > 0) {
			$this->cstart = $this->cstart - 1;
			$this->cstart = $this->cstart * $comments_per_pages;
		} else $this->cstart = 0;

	}

	//----------------------------------
	// Добавление дополнительных тегов в комментарии
	// $type может принимать значения 'set' или 'set_block'
	//----------------------------------
	function add_rules( $find, $replace, $type ) {

		$this->extras_rules[] = array($type, $find, $replace);

	}

	function build_comments( $template, $area ) { 
		global $config, $tpl, $is_logged, $member_id, $user_group, $lang, $dle_login_hash, $_TIME, $allow_comments_ajax;

		$tpl->load_template( $template );
		
		if( strpos( $tpl->copy_template, "[xfvalue_" ) !== false ) $xfound = true;
		else $xfound = false;
		
		if( $xfound ) $xfields = xfieldsload( true );
		
		if ($area != 'ajax')
			$tpl->copy_template = "<a name=\"comment\"></a>" . $tpl->copy_template;


		$sql_result = $this->db->query(  $this->query . " LIMIT " . $this->cstart . "," . $this->comments_per_pages );


		while ( $row = $this->db->get_row( $sql_result ) ) {

			$this->intern_count ++;

			$row['date'] = strtotime( $row['date'] );
			
			$row['gast_name'] = stripslashes( $row['gast_name'] );
			$row['gast_email'] = stripslashes( $row['gast_email'] );
			$row['name'] = stripslashes( $row['name'] );


			if( ! $row['is_register'] or $row['name'] == '' ) {

				if( $row['gast_email'] != "" ) {

					$tpl->set( '{author}', "<a href=\"mailto:".htmlspecialchars($row['gast_email'], ENT_QUOTES)."\">" . $row['gast_name'] . "</a>" );
				
				} else {
					$tpl->set( '{author}', $row['gast_name'] );
				}

				$tpl->set( '{login}', $row['gast_name'] );
				$tpl->set( '[profile]', "" );
				$tpl->set( '[/profile]', "" );

			} else {

		
				if( $config['ajax'] ) $go_page = "onclick=\"DlePage(\'subaction=userinfo&user=" . urlencode( $row['name'] ) . "\'); return false;\" ";
				else $go_page = "";
				
				if( $config['allow_alt_url'] == "yes" ) {
					
					$go_page .= "href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/\"";
					$tpl->set( '[profile]', "<a href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/\">" );

				} else {
					
					$go_page .= "href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['name'] ) . "\"";
					$tpl->set( '[profile]', "<a href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['name'] ) . "\">" );				
				}
				
				$go_page = "onclick=\"return dropdownmenu(this, event, UserMenu('" . htmlspecialchars( $go_page ) . "', '" . $row['user_id'] . "', '" . $user_group[$member_id['user_group']]['admin_editusers'] . "'), '170px')\" onmouseout=\"delayhidemenu()\"";
				
				if( $config['allow_alt_url'] == "yes" ) $tpl->set( '{author}', "<a {$go_page} href=\"" . $config['http_home_url'] . "user/" . urlencode( $row['name'] ) . "/\">" . $row['name'] . "</a>" );
				else $tpl->set( '{author}', "<a {$go_page} href=\"$PHP_SELF?subaction=userinfo&amp;user=" . urlencode( $row['name'] ) . "\">" . $row['name'] . "</a>" );

				$tpl->set( '{login}', $row['name'] );
				$tpl->set( '[/profile]', "</a>" );
			
			}

			if( $is_logged and $member_id['user_group'] == '1' ) $tpl->set( '{ip}', "IP: <a onclick=\"return dropdownmenu(this, event, IPMenu('" . $row['ip'] . "', '" . $lang['ip_info'] . "', '" . $lang['ip_tools'] . "', '" . $lang['ip_ban'] . "'), '190px')\" onmouseout=\"delayhidemenu()\" href=\"https://www.nic.ru/whois/?ip={$row['ip']}\" target=\"_blank\">{$row['ip']}</a>" );
			else $tpl->set( '{ip}', '' );
			
			if( $is_logged and (($member_id['name'] == $row['name'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_editc']) or $user_group[$member_id['user_group']]['edit_allc']) ) {
				$tpl->set( '[com-edit]', "<a onclick=\"return dropdownmenu(this, event, MenuCommBuild('" . $row['id'] . "', '" . $area . "'), '170px')\" onmouseout=\"delayhidemenu()\" href=\"" . $config['http_home_url'] . "index.php?do=comments&amp;action=comm_edit&amp;id=" . $row['id'] . "&amp;area=" . $area ."\">" );
				$tpl->set( '[/com-edit]', "</a>" );
				$allow_comments_ajax = true;
			} else
				$tpl->set_block( "'\\[com-edit\\](.*?)\\[/com-edit\\]'si", "" );


			if( $is_logged and (($member_id['name'] == $row['name'] and $row['is_register'] and $user_group[$member_id['user_group']]['allow_delc']) or $member_id['user_group'] == '1' or $user_group[$member_id['user_group']]['del_allc']) ) {
				$tpl->set( '[com-del]', "<a href=\"javascript:confirmDelete('" . $config['http_home_url'] . "index.php?do=comments&amp;action=comm_del&amp;id=" . $row['id'] . "&amp;dle_allow_hash=" . $dle_login_hash . "&amp;area=" . $area ."')\">" );
				$tpl->set( '[/com-del]', "</a>" );
			} else
				$tpl->set_block( "'\\[com-del\\](.*?)\\[/com-del\\]'si", "" );
			
			if ($area == 'lastcomments') {

				$tpl->set_block( "'\\[fast\\](.*?)\\[/fast\\]'si", "" );

			} else {

				if( ($user_group[$member_id['user_group']]['allow_addc']) and $config['allow_comments'] == "yes" ) {
					if( ! $row['is_register'] or $row['name'] == '' ) $row['name'] = $row['gast_name'];
					else $row['name'] = $row['name'];
					$tpl->set( '[fast]', "<a onmouseover=\"dle_copy_quote('" . str_replace( array (" ", "&#039;" ), array ("&nbsp;", "&amp;#039;" ), $row['name'] ) . "');\" href=\"#\" onclick=\"dle_ins('" . str_replace( array (" ", "&#039;" ), array ("&nbsp;", "&amp;#039;" ), $row['name'] ) . "'); return false;\">" );
					$tpl->set( '[/fast]', "</a>" );
				} else
					$tpl->set_block( "'\\[fast\\](.*?)\\[/fast\\]'si", "" );

			}

			$tpl->set( '{mail}', $row['email'] );
			
			if( date( Ymd, $row['date'] ) == date( Ymd, $_TIME ) ) {
				
				$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $row['date'] ) );
			
			} elseif( date( Ymd, $row['date'] ) == date( Ymd, ($_TIME - 86400) ) ) {
				
				$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $row['date'] ) );
			
			} else {
				
				$tpl->set( '{date}', langdate( $config['timestamp_comment'], $row['date'] ) );
			
			}

			$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$row['date']}')", $tpl->copy_template );

			if ($area == 'lastcomments') {

				$row['category'] = intval( $row['category'] );

				if( $config['allow_alt_url'] == "yes" ) {
					
					if( $row['flag'] and $config['seo_type'] ) {
						
						if( $row['category'] and $config['seo_type'] == 2 ) {
							
							$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['post_id'] . "-" . $row['alt_name'] . ".html";
						
						} else {
							
							$full_link = $config['http_home_url'] . $row['post_id'] . "-" . $row['alt_name'] . ".html";
						
						}
					
					} else {
						
						$full_link = $config['http_home_url'] . date( 'Y/m/d/', strtotime ($row['newsdate']) ) . $row['alt_name'] . ".html";
					}
				
				} else {
					
					$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['post_id'];
				
				}

				$tpl->set( '{news_title}', "<a href=\"" . $full_link . "\">" . stripslashes( $row['title'] ) . "</a>" );

			} else 	$tpl->set( '{news_title}', "" );


			if( $xfound ) {
				$xfieldsdata = xfieldsdataload( $row['xfields'] );
				
				foreach ( $xfields as $value ) {
					$preg_safe_name = preg_quote( $value[0], "'" );
					
					if( $value[5] != 1 or $member_id['user_group'] == 1 or ($is_logged and $row['is_register'] and $member_id['name'] == $row['name']) ) {
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
			}

			if ($area == 'ajax') {

				$tpl->set( '{comment-id}', "--" );

			} elseif($area == 'lastcomments') {

				$tpl->set( '{comment-id}', $this->total_comments - $this->cstart - $this->intern_count + 1 );

			} else {

				if( $config['comm_msort'] == "ASC" ) $tpl->set( '{comment-id}', $this->cstart + $this->intern_count );
				else $tpl->set( '{comment-id}', $this->total_comments - $this->cstart - $this->intern_count + 1 );

			}

			if( $row['foto'] ) $tpl->set( '{foto}', $config['http_home_url'] . "uploads/fotos/" . $row['foto'] );
			else $tpl->set( '{foto}', "{THEME}/images/noavatar.png" );
			
			if( $row['is_register'] and $row['icq'] ) $tpl->set( '{icq}', stripslashes( $row['icq'] ) );
			else $tpl->set( '{icq}', '--' );
			
			if( $row['is_register'] and $row['land'] ) $tpl->set( '{land}', stripslashes( $row['land'] ) );
			else $tpl->set( '{land}', '--' );
			
			if( $row['is_register'] and $row['fullname'] ) $tpl->set( '{fullname}', stripslashes( $row['fullname'] ) );
			else $tpl->set( '{fullname}', '--' );
			
			if( $row['is_register'] and $row['reg_date'] ) $tpl->set( '{registration}', langdate( "j.m.Y", $row['reg_date'] ) );
			else $tpl->set( '{registration}', '--' );
			
			if( $row['is_register'] and $row['signature'] and $user_group[$row['user_group']]['allow_signature'] ) {
				
				$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "\\1" );
				$tpl->set( '{signature}', stripslashes( $row['signature'] ) );
			
			} else {
				$tpl->set_block( "'\\[signature\\](.*?)\\[/signature\\]'si", "" );
			}

			if( ! $row['user_group'] ) $row['user_group'] = 5;
			
			if( $user_group[$row['user_group']]['icon'] ) $tpl->set( '{group-icon}', "<img src=\"" . $user_group[$row['user_group']]['icon'] . "\" border=\"0\" alt=\"\" />" );
			else $tpl->set( '{group-icon}', "" );
			
			$tpl->set( '{group-name}', $user_group[$row['user_group']]['group_prefix'].$user_group[$row['user_group']]['group_name'].$user_group[$row['user_group']]['group_suffix'] );
			
			$tpl->set( '{news-num}', intval( $row['news_num'] ) );
			$tpl->set( '{comm-num}', intval( $row['comm_num'] ) );

			if ( count($this->extras_rules) ) {

				foreach ($this->extras_rules as $rules) {

					if ($rules[0] == 'set') {

						$tpl->set( $rules[1], $rules[2] );

					} else {

						$tpl->set_block( $rules[1], $rules[2] );
					}

				}


			}

			$tpl->set( '{comment}', "<div id='comm-id-" . $row['id'] . "'>" . stripslashes( $row['text'] ) . "</div>" );
			
			$tpl->compile( 'content' );

			if( $user_group[$member_id['user_group']]['allow_hide'] ) $tpl->result['content'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['content']);
			else $tpl->result['content'] = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $tpl->result['content'] );


		}

		$tpl->clear();

		if ($area != 'ajax')		
			$tpl->result['content'] .= "\n<span id='dle-ajax-comments'></span>\n";

		$this->db->free( $sql_result );
	}

	function build_navigation( $template, $alternative_link, $link ) {
		global $tpl, $config, $lang; 

		if( $this->total_comments < $this->comments_per_pages ) return;

		if( isset( $_GET['cstart'] ) ) $this->cstart = intval( $_GET['cstart'] );
		if( !$this->cstart OR $this->cstart < 0 ) $this->cstart = 1;

		$tpl->load_template( $template );

		//----------------------------------
		// Предыдущая страница
		//----------------------------------
		if( $this->cstart > 1 ) {
			$prev = $this->cstart - 1;
			if( $config['allow_alt_url'] == "yes" AND $alternative_link) {

				$url = str_replace ("{page}", $prev, $alternative_link );
				$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"" . $url . "\">\\1</a>" );

			} else $tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "<a href=\"$PHP_SELF?cstart=" . $prev . "&amp;{$link}#comment\">\\1</a>" );
		
		} else {
			$tpl->set_block( "'\[prev-link\](.*?)\[/prev-link\]'si", "" );
			$no_prev = TRUE;
		}

		//----------------------------------
		// страницы
		//----------------------------------
		if( $this->comments_per_pages ) {
			
			$enpages_count = @ceil( $this->total_comments / $this->comments_per_pages );
			$pages = "";
			
			if( $enpages_count <= 10 ) {
				
				for($j = 1; $j <= $enpages_count; $j ++) {
					
					if( $j != $this->cstart  ) {
						
						if( $config['allow_alt_url'] == "yes" AND $alternative_link ) {

							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a href=\"" . $url . "\">$j</a> ";

						} else $pages .= "<a href=\"$PHP_SELF?cstart=$j&amp;{$link}#comment\">$j</a> ";
					
					} else {
						
						$pages .= "<span>$j</span> ";
					}
				
				}
			
			} else {
				
				$start = 1;
				$end = 10;
				$nav_prefix = "<span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
				
				if( $this->cstart  > 0 ) {
					
					if( $this->cstart  > 6 ) {
						
						$start = $this->cstart  - 4;
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
					
					if( $config['allow_alt_url'] == "yes" AND $alternative_link) {

						$url = str_replace ("{page}", "1", $alternative_link );
						$pages .= "<a href=\"" . $url . "\">1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";

					} else $pages .= "<a href=\"$PHP_SELF?cstart=1&amp;{$link}#comment\">1</a> <span class=\"nav_ext\">{$lang['nav_trennen']}</span> ";
				
				}
				
				for($j = $start; $j <= $end; $j ++) {
					
					if( $j != $this->cstart ) {
						
						if( $config['allow_alt_url'] == "yes" AND $alternative_link) {

							$url = str_replace ("{page}", $j, $alternative_link );
							$pages .= "<a href=\"" . $url . "\">$j</a> ";

						} else $pages .= "<a href=\"$PHP_SELF?cstart=$j&amp;{$link}#comment\">$j</a> ";
					
					} else {
						
						$pages .= "<span>$j</span> ";
					}
				
				}
				
				if( $this->cstart != $enpages_count ) {
					
					if( $config['allow_alt_url'] == "yes" AND $alternative_link) {

						$url = str_replace ("{page}", $enpages_count, $alternative_link );
						$pages .= $nav_prefix . "<a href=\"" . $url . "\">{$enpages_count}</a>";

					} else $pages .= $nav_prefix . "<a href=\"$PHP_SELF?cstart={$enpages_count}&amp;{$link}#comment\">{$enpages_count}</a>";
				
				} else
					$pages .= "<span>{$enpages_count}</span> ";
			
			}
			
			$tpl->set( '{pages}', $pages );
		
		}

		//----------------------------------
		// следующая страница
		//----------------------------------
		if( $this->cstart < $enpages_count ) {


			$next_page = $this->cstart + 1;

			if( $config['allow_alt_url'] == "yes" AND $alternative_link ) {

				$url = str_replace ("{page}", $next_page, $alternative_link );
				$tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"" . $url . "\">\\1</a>" );

			} else $tpl->set_block( "'\[next-link\](.*?)\[/next-link\]'si", "<a href=\"$PHP_SELF?cstart=$next_page&amp;{$link}#comment\">\\1</a>" );
		
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