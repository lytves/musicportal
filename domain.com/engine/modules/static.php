<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$name = @$db->safesql( $_GET['page'] );

if( ! $static_result['id'] ) $static_result = $db->super_query( "SELECT * FROM " . PREFIX . "_static WHERE name='$name'" );

if( $static_result['id'] ) {
	
	if ($member_id['user_group'] == 1) {
    if ($static_result['id'] != 5) $db->query( "UPDATE " . PREFIX . "_static set views=views+1 where id='{$static_result['id']}'" );
	} else $db->query( "UPDATE " . PREFIX . "_static set views=views+1 where id='{$static_result['id']}'" );
	
	$static_result['grouplevel'] = explode( ',', $static_result['grouplevel'] );
	
	if( $static_result['date'] ) $_DOCUMENT_DATE = $static_result['date'];
	
	if( $static_result['grouplevel'][0] != "all" and ! in_array( $member_id['user_group'], $static_result['grouplevel'] ) ) {
		msgbox( $lang['all_err_1'], $lang['static_denied'] );
	} else {
		
		$template = stripslashes( $static_result['template'] );
		$static_descr = stripslashes( strip_tags( $static_result['descr'] ) );
		
		if( $static_result['metakeys'] == '' AND $static_result['metadescr'] == '' ) create_keywords( $template );
		else {
			$metatags['keywords'] = $static_result['metakeys'];
			$metatags['description'] = $static_result['metadescr'];
		}

		if ($static_result['metatitle']) $metatags['header_title'] = $static_result['metatitle'];
		
		if( $static_result['allow_template'] or $view_template == "print" ) {
			
			if (isset ( $view_template ) AND $view_template == "print") $tpl->load_template( 'static_print.tpl' );
			elseif( $static_result['tpl'] != '' ) $tpl->load_template( $static_result['tpl'] . '.tpl' );
			else $tpl->load_template( 'static.tpl' );
			
			if( strpos( $tpl->copy_template, "{custom" ) !== false ) {
				
				$tpl->copy_template = preg_replace( "#\\{custom category=['\"](.+?)['\"] template=['\"](.+?)['\"] aviable=['\"](.+?)['\"] from=['\"](.+?)['\"] limit=['\"](.+?)['\"] cache=['\"](.+?)['\"]\\}#ies", "custom_print('\\1', '\\2', '\\3', '\\4', '\\5', '\\6', '{$do}')", $tpl->copy_template );
			
			}
			
			if( ! $news_page ) $news_page = 1;
			
			if (isset ( $view_template ) AND $view_template == "print") {
				
				$template = str_replace( "{PAGEBREAK}", "", $template );
				$template = str_replace( "{pages}", "", $template );
				$template = preg_replace( "'\[PAGE=(.*?)\](.*?)\[/PAGE\]'si", "", $template );
			
			} else {
				
				$news_seiten = explode( "{PAGEBREAK}", $template );
				$anzahl_seiten = count( $news_seiten );
				
				if( $news_page <= 0 or $news_page > $anzahl_seiten ) {
					$news_page = 1;
				}
				
				$template = $news_seiten[$news_page - 1];
				
				$template = preg_replace( '#(\A[\s]*<br[^>]*>[\s]*|<br[^>]*>[\s]*\Z)#is', '', $template ); // remove <br/> at end of string
				

				$news_seiten = "";
				unset( $news_seiten );
				
				if( $anzahl_seiten > 1 ) {
					
					if( $news_page < $anzahl_seiten ) {
						$pages = $news_page + 1;
						if( $config['allow_alt_url'] == "yes" ) {
							$nextpage = " | <a href=\"" . $config['http_home_url'] . "page," . $pages . "," . $static_result['name'] . ".html\">" . $lang['news_next'] . "</a>";
						} else {
							$nextpage = " | <a href=\"$PHP_SELF?do=static&page=" . $static_result['name'] . "&news_page=" . $pages . "\">" . $lang['news_next'] . "</a>";
						}
					}
					
					if( $news_page > 1 ) {
						$pages = $news_page - 1;
						if( $config['allow_alt_url'] == "yes" ) {
							$prevpage = "<a href=\"" . $config['http_home_url'] . "page," . $pages . "," . $static_result['name'] . ".html\">" . $lang['news_prev'] . "</a> | ";
						} else {
							$prevpage = "<a href=\"$PHP_SELF?do=static&page=" . $static_result['name'] . "&news_page=" . $pages . "\">" . $lang['news_prev'] . "</a> | ";
						}
					}
					
					$tpl->set( '{pages}', $prevpage . $lang['news_site'] . " " . $news_page . $lang['news_iz'] . $anzahl_seiten . $nextpage );
					
					if( $config['allow_alt_url'] == "yes" ) {
						$replacepage = "<a href=\"" . $config['http_home_url'] . "page," . "\\1" . "," . $static_result['name'] . ".html\">\\2</a>";
					} else {
						$replacepage = "<a href=\"$PHP_SELF?do=static&page=" . $static_result['name'] . "&news_page=\\1\">\\2</a>";
					}
					
					$template = preg_replace( "'\[PAGE=(.*?)\](.*?)\[/PAGE\]'si", $replacepage, $template );
				
				} else {
					
					$tpl->set( '{pages}', '' );
					$template = preg_replace( "'\[PAGE=(.*?)\](.*?)\[/PAGE\]'si", "", $template );
				
				}
			
			}
			
			if( $config['allow_alt_url'] == "yes" ) $print_link = $config['http_home_url'] . "print:" . $static_result['name'] . ".html";
			else $print_link = $config['http_home_url'] . "engine/print.php?do=static&amp;page=" . $static_result['name'];

			if( @date( "Ymd", $static_result['date'] ) == date( "Ymd", $_TIME ) ) {
				
				$tpl->set( '{date}', $lang['time_heute'] . langdate( ", H:i", $static_result['date'] ) );
			
			} elseif( @date( "Ymd", $static_result['date'] ) == date( "Ymd", ($_TIME - 86400) ) ) {
				
				$tpl->set( '{date}', $lang['time_gestern'] . langdate( ", H:i", $static_result['date'] ) );
			
			} else {
				
				$tpl->set( '{date}', langdate( $config['timestamp_active'], $static_result['date'] ) );
			
			}
	
			$tpl->copy_template = preg_replace ( "#\{date=(.+?)\}#ie", "langdate('\\1', '{$static_result['date']}')", $tpl->copy_template );
			

			$tpl->set( '{description}', $static_descr );
			$tpl->set( '{static}', $template );
			$tpl->set( '{views}', $static_result['views'] );

			if ($config['allow_search_print']) {

				$tpl->set( '[print-link]', "<a href=\"" . $print_link . "\">" );
				$tpl->set( '[/print-link]', "</a>" );

			} else {

				$tpl->set( '[print-link]', "<noindex><a href=\"" . $print_link . "\" rel=\"nofollow\">" );
				$tpl->set( '[/print-link]', "</a></noindex>" );

			}
			
			if( $_GET['page'] == "dle-rules-page" ) if( $do != "register" ) {
				
				$tpl->set( '{ACCEPT-DECLINE}', "" );
			
			} else {
				
				$tpl->set( '{ACCEPT-DECLINE}', "<form  method=\"post\" name=\"registration\" id=\"registration\" action=\"" . $config['http_home_url'] . "index.php?do=register\"><input type=\"submit\" class=\"bbcodes\" value=\"{$lang['rules_accept']}\" />&nbsp;&nbsp;&nbsp;<input type=\"button\" class=\"bbcodes\" value=\"{$lang['rules_decline']}\" onclick=\"history.go(-1); return false;\" /><input name=\"dle_rules_accept\" type=\"hidden\" id=\"dle_rules_accept\" value=\"yes\" /></form>" );
			
			}
#//////////////////////////////////////////////////////////////////////////////////////////////////////////////
      if ( $static_result['id'] == 5 ) {
        if ($is_logged == TRUE) {
          $by_odmin_vip_group_link_unitpay = '<form method="post" action="https://unitpay.ru/pay/12197-6e943" accept-charset="UTF-8">
    <input id="account" type="hidden" value="'.((($member_id['user_id']+1203)*7)-11).'" name="account" />
    <select id="sum" name="sum">
    <option value="60">1 месяц - 60 рублей</option>
    <option value="120">3 месяца - 120 рублей</option>
    <option value="240">6 месяцев - 240 рублей</option>
    <option value="480">12 месяцев - 480 рублей</option>
    </select>
    <input type="hidden" name="desc" value="Переход в VIP группу на domain.com" />
    <input type="hidden" name="hideLogo" value="true" />
    <input style="border:none; padding:5px 30px; background:#F7A8A8; margin:10px 0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; position:relative; box-shadow:none; font-family:Tahoma; font-size:18px; text-align:center; margin:0 0 0 20px; height:auto; color:#3367AB; text-decoration:none; outline:none; text-shadow:none; cursor:pointer; font-weight:normal;" title="Отключить рекламу и перейти в группу VIP Посетители!" onMouseOver="this.style.background=\'#EF5151\';this.style.color=\'#ffffff\'" onMouseOut="this.style.background=\'#f7a8a8\';this.style.color=\'#3367ab\'" type="submit" value=" Оплатить " />
    </form>';
        }
        else {
          $by_odmin_vip_group_link_unitpay = '<a class="reg_inf" style="font-family:Tahoma; font-size:18px; text-align:center; padding:10px 30px; width:500px; margin:0 auto;" title="Отключить рекламу и перейти в группу VIP Посетители!" rel="facebox" href="#virtual_loginform" onMouseOver="this.style.background=\'#EF5151\';this.style.color=\'#ffffff\'" onMouseOut="this.style.background=\'#f7a8a8\';this.style.color=\'#3367ab\'">Отключить рекламу и перейти в группу VIP Посетители!</a>';
        }
        $tpl->set('{by_odmin_vip_group_link_unitpay}', $by_odmin_vip_group_link_unitpay);
        
        $alltracks_count =  unserialize(dle_cache( "alltracks_count" ));
        if ( !$alltracks_count ) {
          $alltracks_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE approve = '0') as cnt, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE is_demo = '1') as cnt2, (SELECT COUNT(*) FROM ".PREFIX."_mservice WHERE clip_online != '') as cnt3 FROM ".PREFIX."_mservice" );
          create_cache( "alltracks_count", serialize($alltracks_count) );
          $db->free( );
        }
        $tpl->set('{by_odmin_tracks}', declension2('<span>'.$alltracks_count['count'].'</span>', array('трек', 'трека', 'треков')));
        $tpl->set('{by_odmin_clips}', declension2('<span>'.$alltracks_count['cnt3'].'</span>', array('клип', 'клипа', 'клипов')));
        
        $allalbums_count =  unserialize(dle_cache( "allalbums_count" ));
        if ( !$allalbums_count ) {
          $allalbums_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE approve = '0') as cnt, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE is_notfullalbum != '0') as cnt2, (SELECT COUNT(*) FROM ".PREFIX."_mservice_albums WHERE genre = '0' OR genre = '' OR genre > '14') as cnt3 FROM ".PREFIX."_mservice_albums" );
          create_cache( "allalbums_count", serialize($allalbums_count) );
          $db->free( );
        }
        $tpl->set('{by_odmin_albums}', declension2('<span>'.$allalbums_count['count'].'</span>', array('музыкальный альбом', 'музыкальных альбома', 'музыкальных альбомов')));
        
        if ($member_id['user_group'] == 1) {$tpl->set('{by_odmin_views_vip_group}', 'Просмотров страницы: '.$static_result['views']);}
        else {$tpl->set('{by_odmin_views_vip_group}', '');}
      }
#//////////////////////////////////////////////////////////////////////////////////////////////////////////////			
			$tpl->compile( 'content' );

			if( $user_group[$member_id['user_group']]['allow_hide'] ) $tpl->result['content'] = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "\\1", $tpl->result['content']);
			else $tpl->result['content'] = preg_replace ( "'\[hide\](.*?)\[/hide\]'si", "<div class=\"quote\">" . $lang['news_regus'] . "</div>", $tpl->result['content'] );

			$tpl->clear();
		
		} else
			$tpl->result['content'] = $template;
	}
	
	if( $config['files_allow'] == "yes" ) if( strpos( $tpl->result['content'], "[attachment=" ) !== false ) {
		
		$tpl->result['content'] = show_attach( $tpl->result['content'], $static_result['id'], true );
	
	}

} else {
	
	@header( "HTTP/1.0 404 Not Found" );
	msgbox( $lang['all_err_1'], $lang['news_page_err'] );

}
?>
