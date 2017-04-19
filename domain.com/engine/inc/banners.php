<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( !$user_group[$member_id['user_group']]['admin_banners'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

if( isset( $_REQUEST['id'] ) ) $id = intval( $_REQUEST['id'] );
else $id = "";

function makeDropDown($options, $name, $selected) {
	$output = "<select size=1 name=\"$name\">\r\n";
	foreach ( $options as $value => $description ) {
		$output .= "<option value=\"$value\"";
		if( $selected == $value ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}

if( $_REQUEST['action'] == "doadd" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$banner_tag = totranslit( strip_tags( trim( $_POST['banner_tag'] ) ) );
	$banner_descr = $db->safesql( strip_tags( trim( $_POST['banner_descr'] ) ) );
	$grouplevel = $db->safesql( strip_tags( implode( ',', $_POST['grouplevel'] )) );
	$banner_code = $db->safesql( trim( $_POST['banner_code'] ) );
	$approve = intval( $_REQUEST['approve'] );
	$short_place = intval( $_REQUEST['short_place'] );
	$bstick = intval( $_REQUEST['bstick'] );
	$main = intval( $_REQUEST['main'] );
	$category = $db->safesql( strip_tags( implode( ',', $_POST['category'] ) ) );
	
	if( $banner_tag == "" or $banner_descr == "" ) msg( "error", $lang['addnews_error'], $lang['addnews_erstory'], "javascript:history.go(-1)" );
	
	$db->query( "INSERT INTO " . PREFIX . "_banners (banner_tag, descr, code, approve, short_place, bstick, main, category, grouplevel) values ('$banner_tag', '$banner_descr', '$banner_code', '$approve', '$short_place', '$bstick', '$main', '$category', '$grouplevel')" );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	clear_cache();
	header( "Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?mod=banners" );

}

if( $_REQUEST['action'] == "doedit" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	if (!$id) msg( "error", "ID not valid", "ID not valid" );
	
	$banner_tag = totranslit( strip_tags( trim( $_POST['banner_tag'] ) ) );
	$banner_descr = $db->safesql( strip_tags( trim( $_POST['banner_descr'] ) ) );
	$banner_code = $db->safesql( trim( $_POST['banner_code'] ) );
	$grouplevel = $db->safesql( strip_tags( implode( ',', $_POST['grouplevel'] ) ) );
	$approve = intval( $_REQUEST['approve'] );
	$short_place = intval( $_REQUEST['short_place'] );
	$bstick = intval( $_REQUEST['bstick'] );
	$main = intval( $_REQUEST['main'] );
	$category = $db->safesql( strip_tags( implode( ',', $_POST['category']) ) );
	
	if( $banner_tag == "" or $banner_descr == "" ) msg( "error", $lang['addnews_error'], $lang['addnews_erstory'], "javascript:history.go(-1)" );
	
	$db->query( "UPDATE " . PREFIX . "_banners SET banner_tag='$banner_tag', descr='$banner_descr', code='$banner_code', approve='$approve', short_place='$short_place', bstick='$bstick', main='$main', category='$category', grouplevel='$grouplevel' WHERE id='$id'" );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	clear_cache();
	header( "Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] . "?mod=banners" );
}

if( $_GET['action'] == "off" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	if (!$id) msg( "error", "ID not valid", "ID not valid" );
	
	$db->query( "UPDATE " . PREFIX . "_banners set approve='0' WHERE id='$id'" );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	clear_cache();
}
if( $_GET['action'] == "on" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	if (!$id) msg( "error", "ID not valid", "ID not valid" );
	
	$db->query( "UPDATE " . PREFIX . "_banners set approve='1' WHERE id='$id'" );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	clear_cache();
}

if( $_GET['action'] == "delete" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	if (!$id) msg( "error", "ID not valid", "ID not valid" );
	
	$db->query( "DELETE FROM " . PREFIX . "_banners WHERE id='$id'" );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	clear_cache();
}

if( $_REQUEST['action'] == "add" or $_REQUEST['action'] == "edit" ) {
	
	if( $_REQUEST['action'] == "add" ) {
		$checked = "checked";
		$doaction = "doadd";
		$all_cats = "selected";
		$check_all = "selected";
		$groups = get_groups();
	
	} else {
		
		$row = $db->super_query( "SELECT * FROM " . PREFIX . "_banners WHERE id='$id' LIMIT 0,1" );
		$banner_tag = $row['banner_tag'];
		$banner_descr = htmlspecialchars( stripslashes( $row['descr'] ) );
		$banner_code = htmlspecialchars( stripslashes( $row['code'] ) );
		$short_place = $row['short_place'];
		$checked = ($row['approve']) ? "checked" : "";
		$checked2 = ($row['allow_full']) ? "checked" : "";
		$checked3 = ($row['bstick']) ? "checked" : "";
		$checked4 = ($row['main']) ? "checked" : "";
		$doaction = "doedit";
		
		$groups = get_groups( explode( ',', $row['grouplevel'] ) );
		if( $row['grouplevel'] == "all" ) $check_all = "selected";
		else $check_all = "";
	
	}
	
	$opt_category = CategoryNewsSelection( explode( ',', $row['category'] ), 0, FALSE );
	if( ! $row['category'] ) $all_cats = "selected";
	else $all_cats = "";
	
	echoheader( "", "" );
	
	echo <<<HTML
    <form action="" method="post" name="bannersform">
      <input type="hidden" name="mod" value="banners">
      <input type="hidden" name="action" value="{$doaction}">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['banners_title']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="200" style="padding:4px;">{$lang['banners_xname']}</td>
        <td><input class=edit style="width: 200px;" type="text" name="banner_tag" value="{$banner_tag}" />&nbsp;&nbsp;&nbsp;({$lang['xf_lat']})</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['banners_xdescr']}</td>
        <td><input  class=edit style="width: 200px;" type="text" name="banner_descr" value="{$banner_descr}" /></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['addnews_cat']}</td>
        <td><select name="category[]" class="cat_select" multiple>
   <option value="0" {$all_cats}>{$lang['edit_all']}</option>
   {$opt_category}
   </select></td>
    </tr>
	<tr id="default_textarea">
        <td style="padding:4px;">{$lang['banners_code']}</td>
        <td><textarea style="width: 98%;" name="banner_code" rows="16">{$banner_code}</textarea>
        </td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_allow']}</td>
        <td style="padding:2px;"><select name="grouplevel[]" style="width:150px;height:150px;" multiple><option value="all" {$check_all}>{$lang['edit_all']}</option>{$groups}</select></td>
    </tr>
	<tr>
		<td align="center">&nbsp;</td>
        <td><div id="optional"><input type="checkbox" name="approve" value="1" {$checked} id="editbact"/>
        <label for="editbact">{$lang['banners_approve']}</label></div></td>
    </tr>
    <tr>
		<td>&nbsp;</td>
        <td><div id="optional"><input type="checkbox" value="1" name="main" {$checked4} id="main" />
    <label for="main">{$lang['banners_main']}</label></span></div></td>
    </tr>
    <tr>
		<td>&nbsp;</td>
        <td><div id="optional"><input type="checkbox" value="1" name="bstick" {$checked3} id="bstick" />
    <label for="bstick">{$lang['banners_bstick']}</label></span></div></td>
    </tr>
        <tr>
		<td>&nbsp;</td>
        <td>
        <div id="optional">
HTML;
	
	echo makeDropDown( array ("0" => $lang['banners_s_0'], "1" => $lang['banners_s_1'], "2" => $lang['banners_s_2'], "3" => $lang['banners_s_3'], "4" => $lang['banners_s_4'], "5" => $lang['banners_s_5'], "6" => $lang['banners_s_6'], "7" => $lang['banners_s_7'] ), "short_place", $short_place );
	
	echo <<<HTML
        <label for="optional">{$lang['banners_s']}</label></div></td>
        </td>
    </tr>
    <tr>
        <td colspan=2><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td colspan=2 style="padding:4px;"><input type="submit" class="buttons" value="{$lang['user_save']}"></td>
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
<input type="hidden" name="user_hash" value="$dle_login_hash" />
    </form>
HTML;
	
	echofooter();

} else {
	
	echoheader( "", "" );
	
	$db->query( "SELECT * FROM " . PREFIX . "_banners ORDER BY id ASC" );
	
	$entries = "";
	
	while ( $row = $db->get_row() ) {
		
		$row['descr'] = stripslashes( $row['descr'] );
		$row['banner_tag'] = "{banner_" . $row['banner_tag'] . "}";
		$row['code'] = stripslashes( $row['code'] );
		
		if( $row['approve'] ) {
			$status = "led_green.gif";
			$lang['led_active'] = $lang['banners_on'];
			$led_action = "off";
		} else {
			$status = "led_gray.gif";
			$lang['led_active'] = $lang['banners_off'];
			$led_action = "on";
		}
		if( $row['short_place'] ) {
			$status2 = "led_green.gif";
			$lang['led_short'] = $lang['banners_s_on'];
		} else {
			$status2 = "led_gray.gif";
			$lang['led_short'] = $lang['banners_s_off'];
		}
		
		$entries .= "
   <tr>
    <td height=22 class=\"list\">
    {$row['descr']}<br />{$lang['banners_tag']}<br />{$row['banner_tag']}</td>
    <td class=\"list\" align=\"center\" style=\"padding:2px;\">{$row['code']}</td>
    <td class=\"list\" style=\"padding:2px;\"><img src=\"engine/skins/images/" . $status . "\" title=\"" . $lang['led_active'] . "\" border=\"0\" align=\"absmiddle\"> {$lang['banners_act']}<br /><img src=\"engine/skins/images/" . $status2 . "\" title=\"" . $lang['led_short'] . "\" border=\"0\" align=\"absmiddle\"> {$lang['banners_s_a']}</td>
    <td class=\"list\" align=\"center\"><a onClick=\"return dropdownmenu(this, event, MenuBuild('" . $row['id'] . "', '" . $led_action . "'), '150px')\" href=\"#\"><img src=\"engine/skins/images/browser_action.gif\" border=\"0\"></a></td>
     </tr>
	<tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=5></td></tr>";
	}
	$db->free();
	
	echo <<<HTML
<script type="text/javascript" src="engine/ajax/menu.js"></script>
<script language="javascript" type="text/javascript">
<!--
function MenuBuild( m_id , led_action){

var menu=new Array()
var lang_action = "";

if (led_action == 'off') { lang_action = "{$lang['banners_aus']}"; } else { lang_action = "{$lang['banners_ein']}"; }


menu[0]='<a onClick="document.location=\'?mod=banners&user_hash={$dle_login_hash}&action=' + led_action + '&id=' + m_id + '\'; return(false)" href="#">' + lang_action + '</a>';
menu[1]='<a onClick="document.location=\'?mod=banners&user_hash={$dle_login_hash}&action=edit&id=' + m_id + '\'; return(false)" href="#">{$lang['group_sel1']}</a>';
menu[2]='<a onClick="javascript:confirmdelete(' + m_id + '); return(false)" href="#">{$lang['cat_del']}</a>';

return menu;
}
function confirmdelete(id){
    var agree=confirm("{$lang['banners_del']}");
    if (agree)
     document.location="?mod=banners&action=delete&user_hash={$dle_login_hash}&id="+id;
}
//-->
</script>
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['banners_list']}</div></td>
    </tr>
</table>
<div class="unterline"></div>

<table width="100%">
  <tr>
   <td width=150>{$lang['static_descr']}</td>
   <td align="center">&nbsp;</td>
   <td width=150>{$lang['banners_opt']}</td>
   <td width=80 align="center">{$lang[vote_action]}</td>
  </tr>
	<tr><td colspan="5"><div class="hr_line"></div></td></tr>
	{$entries}
	<tr><td colspan="5"><div class="hr_line"></div></td></tr>
	<tr><td colspan="5" align="right"><a class=main onClick="javascript:Help('banners')" href="#">{$lang['banners_help']}</a></td></tr>
  <tr><td colspan="5"><a href="?mod=banners&action=add"><input onclick="document.location='?mod=banners&action=add'" type="button" class="buttons" value=" {$lang['bb_create']} "></a></td></tr>
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
</div>
HTML;
	
	echofooter();

}
?>