<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

function formatsize($file_size) {
	if( $file_size >= 1073741824 ) {
		$file_size = round( $file_size / 1073741824 * 100 ) / 100 . " Gb";
	} elseif( $file_size >= 1024 ) {
		$file_size = round( $file_size / 1048576 * 100 ) / 100 . " Mb";
		} else {
		$file_size = $file_size . " b";
	}
	return $file_size;
}

class microTimer {
	function start() {
		global $starttime;
		$mtime = microtime();
		$mtime = explode( ' ', $mtime );
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime;
	}
	function stop() {
		global $starttime;
		$mtime = microtime();
		$mtime = explode( ' ', $mtime );
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$totaltime = round( ($endtime - $starttime), 5 );
		return $totaltime;
	}
}

function flooder($ip) {
	global $config, $db;
	
	$this_time = time() + ($config['date_adjust'] * 60) - $config['flood_time'];
	$db->query( "DELETE FROM " . PREFIX . "_flood where id < '$this_time'" );
	
	$sql_flood = "SELECT * FROM " . PREFIX . "_flood WHERE ip = '$ip'";
	
	if( $db->num_rows( $db->query( $sql_flood ) ) > 0 ) {
		$db->free();
		return TRUE;
	} else {
		$db->free();
		return FALSE;
	}
}

function totranslit($var, $lower = true, $punkt = true) {

	if ( is_array($var) ) return "";

	$NpjLettersFrom = "àáâãäåçèêëìíîïðñòóôöû³´";
	$NpjLettersTo = "abvgdeziklmnoprstufcyig";
	$NpjBiLetters = array ("é" => "j", "¸" => "e", "æ" => "zh", "õ" => "x", "÷" => "ch", "ø" => "sh", "ù" => "shh", "ý" => "ye", "þ" => "yu", "ÿ" => "ya", "ú" => "", "ü" => "", "¿" => "yi", "º" => "ye" );
	
	$NpjCaps = "ÀÁÂÃÄÅ¨ÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÛÝÞß¯ª²¥";
	$NpjSmall = "àáâãäå¸æçèéêëìíîïðñòóôõö÷øùüúûýþÿ¿º³´";
	
	$var = str_replace( ".php", "", $var );
	$var = trim( strip_tags( $var ) );
	$var = preg_replace( "/\s+/ms", "-", $var );
	$var = strtr( $var, $NpjCaps, $NpjSmall );
	$var = strtr( $var, $NpjLettersFrom, $NpjLettersTo );
	$var = strtr( $var, $NpjBiLetters );
	
	if ( $punkt ) $var = preg_replace( "/[^a-z0-9\_\-.]+/mi", "", $var );
	else $var = preg_replace( "/[^a-z0-9\_\-]+/mi", "", $var );

	$var = preg_replace( '#[\-]+#i', '-', $var );

	if ( $lower ) $var = strtolower( $var );
	
	if( strlen( $var ) > 200 ) {
		
		$var = substr( $var, 0, 200 );
		
		if( ($temp_max = strrpos( $var, '-' )) ) $var = substr( $var, 0, $temp_max );
	
	}
	
	return $var;
}

function langdate($format, $stamp) {
	global $langdate;
	
	return strtr( @date( $format, $stamp ), $langdate );

}

function msgbox($title, $text) {
	global $tpl;
	if (!class_exists('dle_template')) {
	    return;
	}
	$tpl_2 = new dle_template( );
	$tpl_2->dir = TEMPLATE_DIR;
	
	$tpl_2->load_template( 'info.tpl' );
	
	$tpl_2->set( '{error}', $text );
	$tpl_2->set( '{title}', $title );
	
	$tpl_2->compile( 'info' );
	$tpl_2->clear();
	
	$tpl->result['info'] .= $tpl_2->result['info'];
}

function ShowRating($id, $rating, $vote_num, $allow = true) {
	global $lang;
	
	if( $rating ) $rating = round( ($rating / $vote_num), 0 );
	else $rating = 0;
	$rating = $rating * 17;
	
	if( ! $allow ) {
		
		$rated = <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		</ul>
</div><div class="rating" style="float:left; padding-top:2px;">&nbsp;({$lang['voten']} {$vote_num})</div>
HTML;
		
		return $rated;
	}
	
	$rated .= <<<HTML
<div id='ratig-layer'><div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		<li><a href="#" title="{$lang['useless']}" class="r1-unit" onclick="doRate('1', '{$id}'); return false;">1</a></li>
		<li><a href="#" title="{$lang['poor']}" class="r2-unit" onclick="doRate('2', '{$id}'); return false;">2</a></li>
		<li><a href="#" title="{$lang['fair']}" class="r3-unit" onclick="doRate('3', '{$id}'); return false;">3</a></li>
		<li><a href="#" title="{$lang['good']}" class="r4-unit" onclick="doRate('4', '{$id}'); return false;">4</a></li>
		<li><a href="#" title="{$lang['excellent']}" class="r5-unit" onclick="doRate('5', '{$id}'); return false;">5</a></li>
		</ul>
</div><div class="rating" style="float:left; padding-top:2px;">&nbsp;({$lang['voten']} {$vote_num})</div></div>
HTML;
	
	return $rated;
}

function ShortRating($id, $rating, $vote_num, $allow = true) {
	global $lang;
	
	if( $rating ) $rating = round( ($rating / $vote_num), 0 );
	else $rating = 0;
	$rating = $rating * 17;
	
	if( ! $allow ) {
		
		$rated = <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		</ul>
</div>
HTML;
		
		return $rated;
	}
	
	$rated = "<div id='ratig-layer-" . $id . "'>";
	
	$rated .= <<<HTML
<div class="rating" style="float:left;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		<li><a href="#" title="{$lang['useless']}" class="r1-unit" onclick="dleRate('1', '{$id}'); return false;">1</a></li>
		<li><a href="#" title="{$lang['poor']}" class="r2-unit" onclick="dleRate('2', '{$id}'); return false;">2</a></li>
		<li><a href="#" title="{$lang['fair']}" class="r3-unit" onclick="dleRate('3', '{$id}'); return false;">3</a></li>
		<li><a href="#" title="{$lang['good']}" class="r4-unit" onclick="dleRate('4', '{$id}'); return false;">4</a></li>
		<li><a href="#" title="{$lang['excellent']}" class="r5-unit" onclick="dleRate('5', '{$id}'); return false;">5</a></li>
		</ul>
</div>
HTML;
	
	$rated .= "</div>";
	
	return $rated;
}

function userrating($user_id) {
	global $db;
	
	$row = $db->super_query( "SELECT SUM(rating) as rating, SUM(vote_num) as num FROM ".PREFIX."_mservice where uploader ='$user_id'" );
	
	if( $row['num'] ) $rating = round( ($row['rating'] / $row['num']), 0 );
	else $rating = 0;
	$rating = $rating * 17;
	
	$rated = <<<HTML
<div class="rating" style="display:inline-block; padding:5px 0 0 10px;">
		<ul class="unit-rating">
		<li class="current-rating" style="width:{$rating}px;">{$rating}</li>
		</ul>
		</div>
HTML;
	
	return $rated;
}

function CategoryNewsSelection($categoryid = 0, $parentid = 0, $nocat = TRUE, $sublevelmarker = '', $returnstring = '') {
	global $cat_info, $user_group, $member_id;
	
	$allow_list = explode( ',', $user_group[$member_id['user_group']]['allow_cats'] );
	$spec_list = explode( ',', $user_group[$member_id['user_group']]['cat_add'] );
	$root_category = array ();
	
	if( $parentid == 0 ) {
		if( $nocat ) $returnstring .= '<option value="0"></option>';
	} else {
		$sublevelmarker .= '&nbsp;&nbsp;&nbsp;';
	}
	
	if( count( $cat_info ) ) {
		
		foreach ( $cat_info as $cats ) {
			if( $cats['parentid'] == $parentid ) $root_category[] = $cats['id'];
		}
		
		if( count( $root_category ) ) {
			foreach ( $root_category as $id ) {
				
				if( $allow_list[0] == "all" or in_array( $id, $allow_list ) ) {
					
					if( $spec_list[0] == "all" or in_array( $id, $spec_list ) ) $color = "black";
					else $color = "red";
					
					$returnstring .= "<option style=\"color: {$color}\" value=\"" . $id . '" ';
					
					if( is_array( $categoryid ) ) {
						foreach ( $categoryid as $element ) {
							if( $element == $id ) $returnstring .= 'SELECTED';
						}
					} elseif( $categoryid == $id ) $returnstring .= 'SELECTED';
					
					$returnstring .= '>' . $sublevelmarker . $cat_info[$id]['name'] . '</option>';
				}
				$returnstring = CategoryNewsSelection( $categoryid, $id, $nocat, $sublevelmarker, $returnstring );
			}
		}
	}
	return $returnstring;
}

function get_ID($cat_info, $category) {
	foreach ( $cat_info as $cats ) {
		if( $cats['alt_name'] == $category ) return $cats['id'];
	}
	return false;
}

function set_vars($file, $data) {
	
	$fp = fopen( ENGINE_DIR . '/cache/system/' . $file . '.php', 'wb+' );
	fwrite( $fp, serialize( $data ) );
	fclose( $fp );
	
	@chmod( ENGINE_DIR . '/cache/system/' . $file . '.php', 0666 );
}

function get_vars($file) {
	
	return unserialize( @file_get_contents( ENGINE_DIR . '/cache/system/' . $file . '.php' ) );
}

function filesize_url($url) {
	return ($data = @file_get_contents( $url )) ? strlen( $data ) : false;
}

function dle_cache($prefix, $cache_id = false, $member_prefix = false) {
	global $config, $is_logged, $member_id;
	
	if( $config['allow_cache'] != "yes" ) return false;
	
	if( $is_logged ) $end_file = $member_id['user_group'];
	else $end_file = "0";
	
	if( ! $cache_id ) {
		
		$filename = ENGINE_DIR . '/cache/' . $prefix . '.tmp';
	
	} else {
		
		$cache_id = totranslit( $cache_id );
		
		if( $member_prefix ) $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
		else $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
	
	}
	
	return @file_get_contents( $filename );
}

function create_cache($prefix, $cache_text, $cache_id = false, $member_prefix = false) {
	global $config, $is_logged, $member_id;
	
	if( $config['allow_cache'] != "yes" ) return false;
	
	if( $is_logged ) $end_file = $member_id['user_group'];
	else $end_file = "0";
	
	if( ! $cache_id ) {
		$filename = ENGINE_DIR . '/cache/' . $prefix . '.tmp';
	} else {
		$cache_id = totranslit( $cache_id );
		
		if( $member_prefix ) $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . "_" . $end_file . ".tmp";
		else $filename = ENGINE_DIR . "/cache/" . $prefix . "_" . $cache_id . ".tmp";
	
	}
	
	$fp = fopen( $filename, 'wb+' );
	fwrite( $fp, $cache_text );
	fclose( $fp );
	
	@chmod( $filename, 0666 );

}

function clear_cache($cache_area = false) {
	
	$fdir = opendir( ENGINE_DIR . '/cache' );
	
	while ( $file = readdir( $fdir ) ) {
		if( $file != '.' and $file != '..' and $file != '.htaccess' and $file != 'system' ) {
			
			if( $cache_area ) {
				
				if( strpos( $file, $cache_area ) !== false ) @unlink( ENGINE_DIR . '/cache/' . $file );
			
			} else {
				
				@unlink( ENGINE_DIR . '/cache/' . $file );
			
			}
		}
	}
}

function ChangeSkin($dir, $skin) {
	
	$templates_list = array ();
	
	$handle = opendir( $dir );
	
	while ( false !== ($file = readdir( $handle )) ) {
		if( @is_dir( "./templates/$file" ) and ($file != "." AND $file != ".." AND $file != "smartphone") ) {
			$templates_list[] = $file;
		}
	}
	
	closedir( $handle );
	sort($templates_list);
	
	$skin_list = "<form method=\"post\" action=\"\"><select onchange=\"submit()\" name=\"skin_name\">";
	
	foreach ( $templates_list as $single_template ) {
		if( $single_template == $skin ) $selected = " selected=\"selected\"";
		else $selected = "";
		$skin_list .= "<option value=\"$single_template\"" . $selected . ">$single_template</option>";
	}
	
	$skin_list .= '</select><input type="hidden" name="action_skin_change" value="yes" /></form>';
	
	return $skin_list;
}

function custom_print($custom_category, $custom_template, $aviable, $custom_from, $custom_limit, $custom_cache, $do) {
	global $db, $is_logged, $member_id, $xf_inited, $cat_info, $config, $user_group, $category_id, $_TIME, $lang, $smartphone_detected, $dle_module;
	
	$do = $do ? $do : "main";
	$aviable = explode( '|', $aviable );
	
	if( ! (in_array( $do, $aviable )) and ($aviable[0] != "global") ) return "";
	
	$custom_category = $db->safesql( str_replace( ',', '|', $custom_category ) );
	$custom_from = intval( $custom_from );
	$custom_limit = intval( $custom_limit );
	$thisdate = date( "Y-m-d H:i:s", (time() + $config['date_adjust'] * 60) );
	
	if( intval( $config['no_date'] ) ) $where_date = " AND date < '" . $thisdate . "'";
	else $where_date = "";
	
	$tpl = new dle_template( );
	$tpl->dir = TEMPLATE_DIR;
	
	if( $custom_cache == "yes" ) $config['allow_cache'] = "yes";
	else $config['allow_cache'] = false;
	if( $is_logged and ($user_group[$member_id['user_group']]['allow_edit'] and ! $user_group[$member_id['user_group']]['allow_all_edit']) ) $config['allow_cache'] = false;
	
	$content = dle_cache( "custom", "cat_" . $custom_category . "template_" . $custom_template . "from_" . $custom_from . "limit_" . $custom_limit, true );
	
	if( $content ) {
		return $content;
	} else {
		
		$allow_list = explode( ',', $user_group[$member_id['user_group']]['allow_cats'] );
		
		if( $allow_list[0] != "all" ) {
			
			if( $config['allow_multi_category'] ) {
				
				$stop_list = "category regexp '[[:<:]](" . implode( '|', $allow_list ) . ")[[:>:]]' AND ";
			
			} else {
				
				$stop_list = "category IN ('" . implode( "','", $allow_list ) . "') AND ";
			
			}
		
		} else
			$stop_list = "";
		
		if( $user_group[$member_id['user_group']]['allow_short'] ) $stop_list = "";
		
		if( $cat_info[$custom_category]['news_sort'] != "" ) $news_sort = $cat_info[$custom_category]['news_sort']; else $news_sort = $config['news_sort'];
		if( $cat_info[$custom_category]['news_msort'] != "" ) $news_msort = $cat_info[$custom_category]['news_msort']; else $news_msort = $config['news_msort'];
		
		if( $config['allow_multi_category'] ) {
			
			$where_category = "category regexp '[[:<:]](" . $custom_category . ")[[:>:]]'";
		
		} else {
			
			$custom_category = str_replace( "|", "','", $custom_category );
			$where_category = "category IN ('" . $custom_category . "')";
		
		}
		
		$sql_select = "SELECT id, autor, date, short_story, full_story, xfields, title, category, alt_name, comm_num, allow_comm, allow_rate, rating, vote_num, news_read, flag, editdate, editor, reason, view_edit, tags FROM " . PREFIX . "_post WHERE " . $stop_list . $where_category . " AND approve" . $where_date . " ORDER BY " . $news_sort . " " . $news_msort . " LIMIT " . $custom_from . "," . $custom_limit;
		
		include (ENGINE_DIR . '/modules/show.custom.php');
		
		if( $config['files_allow'] == "yes" ) if( strpos( $tpl->result['content'], "[attachment=" ) !== false ) {
			$tpl->result['content'] = show_attach( $tpl->result['content'], $attachments );
		}
		
		create_cache( "custom", $tpl->result['content'], "cat_" . $custom_category . "template_" . $custom_template . "from_" . $custom_from . "limit_" . $custom_limit, true );
	
	}
	return $tpl->result['content'];
}

function check_ip($ips) {
	
	$_IP = $_SERVER['REMOTE_ADDR'];
	
	$blockip = FALSE;
	
	if( is_array( $ips ) ) {
		foreach ( $ips as $ip_line ) {
			
			$ip_arr = rtrim( $ip_line['ip'] );
			
			$ip_check_matches = 0;
			$db_ip_split = explode( ".", $ip_arr );
			$this_ip_split = explode( ".", $_IP );
			
			for($i_i = 0; $i_i < 4; $i_i ++) {
				if( $this_ip_split[$i_i] == $db_ip_split[$i_i] or $db_ip_split[$i_i] == '*' ) {
					$ip_check_matches += 1;
				}
			
			}
			
			if( $ip_check_matches == 4 ) {
				$blockip = $ip_line['ip'];
				break;
			}
		
		}
	}
	
	return $blockip;
}

function check_netz($ip1, $ip2) {
	
	$ip1 = explode( ".", $ip1 );
	$ip2 = explode( ".", $ip2 );
	
	if( $ip1[0] != $ip2[0] ) return false;
	if( $ip1[1] != $ip2[1] ) return false;
	
	return true;

}

function show_attach($story, $id, $static = false) {
	global $db, $config, $lang, $user_group, $member_id;
	
	if( $static ) {
		
		if( is_array( $id ) and count( $id ) ) $where = "static_id IN (" . implode( ",", $id ) . ")";
		else $where = "static_id = '$id'";
		
		$db->query( "SELECT id, name, onserver, dcount FROM " . PREFIX . "_static_files WHERE $where" );
		
		$area = "&amp;area=static";
	
	} else {
		
		if( is_array( $id ) and count( $id ) ) $where = "news_id IN (" . implode( ",", $id ) . ")";
		else $where = "news_id = '$id'";
		
		$db->query( "SELECT id, name, onserver, dcount FROM " . PREFIX . "_files WHERE $where" );
		
		$area = "";
	
	}
	
	while ( $row = $db->get_row() ) {
		
		$size = formatsize( @filesize( ROOT_DIR . '/uploads/files/' . $row['onserver'] ) );
		$row['name'] = explode( "/", $row['name'] );
		$row['name'] = end( $row['name'] );
		
		if( ! $user_group[$member_id['user_group']]['allow_files'])
$link = "<span id=\"attachment\">{$lang['att_denied']}</span>";
elseif ($config['files_count'] == 'yes')
$link = "<span id=\"attachment\"><a href=\"{$config['http_home_url']}engine/download.php?id={$row['id']}\" >{$row['name']}</a> [{$size}] ({$lang['att_dcount']} {$row['dcount']})</span>";
else
$link = "<span id=\"attachment\"><a href=\"{$config['http_home_url']}engine/download.php?id={$row['id']}\" >{$row['name']}</a> [{$size}]</span>";
		
		$story = str_replace( '[attachment=' . $row['id'] . ']', $link, $story );
	}
	$db->free();
	
	return $story;

}

function xfieldsload($profile = false) {
	global $lang;
	
	if( $profile ) $path = ENGINE_DIR . '/data/xprofile.txt';
	else $path = ENGINE_DIR . '/data/xfields.txt';
	
	$filecontents = file( $path );
	
	if( ! is_array( $filecontents ) ) msgbox( "System error", "File <b>{$path}</b> not found" );
	else {
		foreach ( $filecontents as $name => $value ) {
			$filecontents[$name] = explode( "|", trim( $value ) );
			foreach ( $filecontents[$name] as $name2 => $value2 ) {
				$value2 = str_replace( "&#124;", "|", $value2 );
				$value2 = str_replace( "__NEWL__", "\r\n", $value2 );
				$filecontents[$name][$name2] = $value2;
			}
		}
	}
	return $filecontents;
}

function xfieldsdataload($id) {
	
	if( $id == "" ) return;
	
	$xfieldsdata = explode( "||", $id );
	foreach ( $xfieldsdata as $xfielddata ) {
		list ( $xfielddataname, $xfielddatavalue ) = explode( "|", $xfielddata );
		$xfielddataname = str_replace( "&#124;", "|", $xfielddataname );
		$xfielddataname = str_replace( "__NEWL__", "\r\n", $xfielddataname );
		$xfielddatavalue = str_replace( "&#124;", "|", $xfielddatavalue );
		$xfielddatavalue = str_replace( "__NEWL__", "\r\n", $xfielddatavalue );
		$data[$xfielddataname] = $xfielddatavalue;
	}
	return $data;
}

function create_keywords($story) {
	global $metatags;
	
	$keyword_count = 20;
	$newarr = array ();
	
	$quotes = array ("\x22", "\x60", "\t", "\n", "\r", ",", ".", "/", "¬", "#", ";", ":", "@", "~", "[", "]", "{", "}", "=", "-", "+", ")", "(", "*", "^", "%", "$", "<", ">", "?", "!", '"' );
	$fastquotes = array ("\x22", "\x60", "\t", "\n", "\r", '"', "\\", '\r', '\n', "/", "{", "}", "[", "]" );
	
	$story = preg_replace( "'\[hide\](.*?)\[/hide\]'si", "", $story );
	$story = preg_replace( "'\[attachment=(.*?)\]'si", "", $story );
	$story = preg_replace( "'\[page=(.*?)\](.*?)\[/page\]'si", "", $story );
	$story = str_replace( "{PAGEBREAK}", "", $story );
	$story = str_replace( "&nbsp;", " ", $story );
	
	$story = str_replace( $fastquotes, '', trim( strip_tags( str_replace( '<br />', ' ', stripslashes( $story ) ) ) ) );
	
	$metatags['description'] = substr( $story, 0, 190 );
	
	$story = str_replace( $quotes, '', $story );
	
	$arr = explode( " ", $story );
	
	foreach ( $arr as $word ) {
		if( strlen( $word ) > 4 ) $newarr[] = $word;
	}
	
	$arr = array_count_values( $newarr );
	arsort( $arr );
	
	$arr = array_keys( $arr );
	
	$total = count( $arr );
	
	$offset = 0;
	
	$arr = array_slice( $arr, $offset, $keyword_count );
	
	$metatags['keywords'] = implode( ", ", $arr );
}

function allowed_ip($ip_array) {
	
	$ip_array = trim( $ip_array );
	
	if( $ip_array == "" ) {
		return true;
	}
	
	$ip_array = explode( "|", $ip_array );
	
	$db_ip_split = explode( ".", $_SERVER['REMOTE_ADDR'] );
	
	foreach ( $ip_array as $ip ) {
		
		$ip_check_matches = 0;
		$this_ip_split = explode( ".", trim( $ip ) );
		
		for($i_i = 0; $i_i < 4; $i_i ++) {
			if( $this_ip_split[$i_i] == $db_ip_split[$i_i] or $this_ip_split[$i_i] == '*' ) {
				$ip_check_matches += 1;
			}
		
		}
		
		if( $ip_check_matches == 4 ) return true;
	
	}
	
	return FALSE;
}

function news_permission($id) {
	
	if( $id == "" ) return;
	
	$data = array ();
	
	$groups = explode( "|", $id );
	foreach ( $groups as $group ) {
		list ( $groupid, $groupvalue ) = explode( ":", $group );
		$data[$groupid] = $groupvalue;
	}
	return $data;
}

function bannermass($fest, $massiv) {
	return $fest . $massiv[@array_rand( $massiv )]['text'];
}

function get_sub_cats($id, $subcategory = '') {
	
	global $cat_info;
	$subfound = array ();
	
	if( $subcategory == '' ) $subcategory = $id;
	
	foreach ( $cat_info as $cats ) {
		if( $cats['parentid'] == $id ) {
			$subfound[] = $cats['id'];
		}
	}
	
	foreach ( $subfound as $parentid ) {
		$subcategory .= "|" . $parentid;
		$subcategory = get_sub_cats( $parentid, $subcategory );
	}
	
	return $subcategory;

}

function check_xss() {
	
	$url = html_entity_decode( urldecode( $_SERVER['QUERY_STRING'] ) );
	
	if( $url ) {
		
		if( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, './' ) !== false) || (strpos( $url, '../' ) !== false) || (strpos( $url, '\'' ) !== false) || (strpos( $url, '.php' ) !== false) ) {
			if( $_GET['do'] != "search" or $_GET['subaction'] != "search" ) die( "Hacking attempt!" );
		}
	
	}
	
	$url = html_entity_decode( urldecode( $_SERVER['REQUEST_URI'] ) );
	
	if( $url ) {
		
		if( (strpos( $url, '<' ) !== false) || (strpos( $url, '>' ) !== false) || (strpos( $url, '"' ) !== false) || (strpos( $url, '\'' ) !== false) ) {
			if( $_GET['do'] != "search" or $_GET['subaction'] != "search" ) die( "Hacking attempt!" );
		
		}
	
	}

}

function check_category($cats, $block, $category, $action = true) {
	
	$cats = explode( ',', $cats );
	$category = explode( ',', $category );
	
	foreach ( $category as $element ) {
		
		if( $action ) {
			
			if( in_array( $element, $cats ) ) {
				
				$block = str_replace( '\"', '"', $block );
				return $block;
			}
		
		} else {
			
			if( ! in_array( $element, $cats ) ) {
				
				$block = str_replace( '\"', '"', $block  );
				return $block;
			}
		
		}
	
	}
	
	return "";
}

function clean_url($url) {
	
	if( $url == '' ) return;
	
	$url = str_replace( "http://", "", strtolower( $url ) );
	if( substr( $url, 0, 4 ) == 'www.' ) $url = substr( $url, 4 );
	$url = explode( '/', $url );
	$url = reset( $url );
	$url = explode( ':', $url );
	$url = reset( $url );
	
	return $url;
}

function get_url($id) {
	
	global $cat_info;
	
	if( ! $id ) return;
	
	$parent_id = $cat_info[$id]['parentid'];
	
	$url = $cat_info[$id]['alt_name'];
	
	while ( $parent_id ) {
		
		$url = $cat_info[$parent_id]['alt_name'] . "/" . $url;
		
		$parent_id = $cat_info[$parent_id]['parentid'];
		
		if( $cat_info[$parent_id]['parentid'] == $cat_info[$parent_id]['id'] ) break;
	
	}
	
	return $url;
}

function get_categories($id) {
	
	global $cat_info, $config, $PHP_SELF;
	
	if( ! $id ) return;
	
	$parent_id = $cat_info[$id]['parentid'];
	
	if( $config['ajax'] ) $go_page = "onclick=\"DlePage('do=cat&category={$cat_info[$id]['alt_name']}'); return false;\" ";
	else $go_page = "";
	
	if( $config['allow_alt_url'] == "yes" ) $list = "<a {$go_page}href=\"" . $config['http_home_url'] . get_url( $id ) . "/\">{$cat_info[$id]['name']}</a>";
	else $list = "<a {$go_page}href=\"$PHP_SELF?do=cat&amp;category={$cat_info[$id]['alt_name']}\">{$cat_info[$id]['name']}</a>";
	
	while ( $parent_id ) {
		
		if( $config['ajax'] ) $go_page = "onclick=\"DlePage('do=cat&category={$cat_info[$parent_id]['alt_name']}'); return false;\" ";
		else $go_page = "";
		
		if( $config['allow_alt_url'] == "yes" ) $list = "<a {$go_page}href=\"" . $config['http_home_url'] . get_url( $parent_id ) . "/\">{$cat_info[$parent_id]['name']}</a>" . " &raquo; " . $list;
		else $list = "<a {$go_page}href=\"$PHP_SELF?do=cat&amp;category={$cat_info[$parent_id]['alt_name']}\">{$cat_info[$parent_id]['name']}</a>" . " &raquo; " . $list;
		
		$parent_id = $cat_info[$parent_id]['parentid'];
		
		if( $cat_info[$parent_id]['parentid'] == $cat_info[$parent_id]['id'] ) break;
	
	}
	
	return $list;
}

$domain_cookie = explode (".", clean_url( $_SERVER['HTTP_HOST'] ));
$domain_cookie_count = count($domain_cookie);
$domain_allow_count = -2;

if ( $domain_cookie_count > 2 ) {

	if ( in_array($domain_cookie[$domain_cookie_count-2], array('com', 'net', 'org') )) $domain_allow_count = -3;
	if ( $domain_cookie[$domain_cookie_count-1] == 'ua' ) $domain_allow_count = -3;
	$domain_cookie = array_slice($domain_cookie, $domain_allow_count);
}

$domain_cookie = "." . implode (".", $domain_cookie);

define( 'DOMAIN', $domain_cookie );

function set_cookie($name, $value, $expires) {
	
	if( $expires ) {
		
		$expires = time() + ($expires * 86400);
	
	} else {
		
		$expires = FALSE;
	
	}
	
	if( PHP_VERSION < 5.2 ) {
		
		setcookie( $name, $value, $expires, "/", DOMAIN . "; HttpOnly" );
	
	} else {
		
		setcookie( $name, $value, $expires, "/", DOMAIN, NULL, TRUE );
	
	}
}

function news_sort($do) {
	
	global $config, $lang;
	
	if( ! $do ) $do = "main";
	
	$find_sort = "dle_sort_" . $do;
	$direction_sort = "dle_direction_" . $do;
	
	$find_sort = str_replace( ".", "", $find_sort );
	$direction_sort = str_replace( ".", "", $direction_sort );
	
	$sort = array ();
	$allowed_sort = array ('date', 'rating', 'news_read', 'comm_num', 'title' );
	
	$soft_by_array = array (

	'date' => array (

	'name' => $lang['sort_by_date'], 'value' => "date", 'direction' => "desc", 'image' => "" ), 

	'rating' => array (

	'name' => $lang['sort_by_rating'], 'value' => "rating", 'direction' => "desc", 'image' => "" ), 

	'news_read' => array (

	'name' => $lang['sort_by_read'], 'value' => "news_read", 'direction' => "desc", 'image' => "" ), 

	'comm_num' => array (

	'name' => $lang['sort_by_comm'], 'value' => "comm_num", 'direction' => "desc", 'image' => "" ), 

	'title' => array (

	'name' => $lang['sort_by_title'], 'value' => "title", 'direction' => "desc", 'image' => "" )

	 )

	;
	
	if( $_SESSION[$direction_sort] == "desc" or $_SESSION[$direction_sort] == "asc" ) $direction = $_SESSION[$direction_sort];
	else $direction = $config['news_msort'];
	if( $_SESSION[$find_sort] and in_array( $_SESSION[$find_sort], $allowed_sort ) ) $soft_by = $_SESSION[$find_sort];
	else $soft_by = $config['news_sort'];
	
	if( strtolower( $direction ) == "asc" ) {
		
		$soft_by_array[$soft_by]['image'] = "<img src=\"{THEME}/dleimages/asc.gif\" alt=\"\" />";
		$soft_by_array[$soft_by]['direction'] = "desc";
	
	} else {
		
		$soft_by_array[$soft_by]['image'] = "<img src=\"{THEME}/dleimages/desc.gif\" alt=\"\" />";
		$soft_by_array[$soft_by]['direction'] = "asc";
	}
	
	foreach ( $soft_by_array as $value ) {
		
		$sort[] = $value['image'] . "<a href=\"#\" onclick=\"dle_change_sort('{$value['value']}','{$value['direction']}'); return false;\">" . $value['name'] . "</a>";
	}
	
	$sort = "<form name=\"news_set_sort\" id=\"news_set_sort\" method=\"post\" action=\"\" >" . $lang['sort_main'] . "&nbsp;" . implode( " | ", $sort );
	
	$sort .= <<<HTML
<input type="hidden" name="dlenewssortby" id="dlenewssortby" value="{$config['news_sort']}" />
<input type="hidden" name="dledirection" id="dledirection" value="{$config['news_msort']}" />
<input type="hidden" name="set_new_sort" id="set_new_sort" value="{$find_sort}" />
<input type="hidden" name="set_direction_sort" id="set_direction_sort" value="{$direction_sort}" />
<script type="text/javascript" language="javascript">
<!-- begin

function dle_change_sort(sort, direction){

  var frm = document.getElementById('news_set_sort');

  frm.dlenewssortby.value=sort;
  frm.dledirection.value=direction;

  frm.submit();
  return false;
};

// end -->
</script></form>
HTML;
	
	return $sort;
}

function compare_tags($a, $b) {
	
	if( $a['tag'] == $b['tag'] ) return 0;
	
	return strcasecmp( $a['tag'], $b['tag'] );

}

function convert_unicode($t, $to = 'windows-1251') {
	$to = strtolower( $to );

	if( $to == 'utf-8' ) {
		
		return urldecode( $t );
	
	} else {
		
		if( function_exists( 'iconv' ) ) $t = iconv( "UTF-8", $to . "//IGNORE", $t );
		else $t = "The library iconv is not supported by your server";
	
	}

	return urldecode( $t );
}

function check_smartphone() {

	if ( $_SESSION['mobile_enable'] ) return true;

	$phone_array = array('iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'mobile windows', 'cellphone', 'opera mobi', 'operamobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'symbos', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'windows phone');
	$agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

	foreach ($phone_array as $value) {

		if ( strpos($agent, $value) !== false ) return true;

	}

	return false;

}

function dle_strlen($value, $charset ) {

    if ( strtolower($charset) == "utf-8") return iconv_strlen($value, "utf-8");
    else return strlen($value);

}

// êëàññíàÿ ô-öèÿ ôîðìèðóåò íîðìàëüíîå óïîòðåáëåíèå ñëîâà ïðè èñïîëüçîâàíèè ñ ÷èñëîì (ïðèìåð: òðåê, òðåêà, òðåêîâ) http://webersoft.ru/function-of-declination-of-words/
function declension2($digit,$expr,$onlyword=false)
     {
         if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
         if(empty($expr[2])) $expr[2]=$expr[1];
         $i=preg_replace('/[^0-9]+/s','',$digit)%100;
         if($onlyword) $digit='';
         if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
         else
         {
             $i%=10;
             if($i==1) $res=$digit.' '.$expr[0];
             elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
             else $res=$digit.' '.$expr[2];
         }
         return trim($res);
}

?>