<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( ! $user_group[$member_id['user_group']]['admin_editvote'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

include_once ENGINE_DIR . '/classes/parse.class.php';

$parse = new ParseFilter( );

$stop = false;
if( isset( $_REQUEST['id'] ) ) $id = intval( $_REQUEST['id'] );
else $id = "";

if( $_GET['action'] == "add" ) {
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$category = $_POST['category'];
	
	if( ! count( $category ) ) {
		$category = array ();
		$category[] = 'all';
	}
	$category = $db->safesql( strip_tags( implode( ',', $category ) ) );
	
	$title = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['title'] ), false ) );
	$body = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['body'] ), false ) );
	
	$db->query( "INSERT INTO " . PREFIX . "_vote (category, vote_num, date, title, body, approve) VALUES ('$category', 0, CURRENT_DATE(), '$title', '$body', '1')" );
	@unlink( ENGINE_DIR . '/cache/system/vote.php' );
	msg( "info", $lang['vote_str_3'], $lang['vote_str_3'], "?mod=editvote" );

} elseif( $_GET['action'] == "update" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$category = $_POST['category'];
	
	if( ! count( $category ) ) {
		$category = array ();
		$category[] = 'all';
	}
	$category = $db->safesql( strip_tags( implode( ',', $category ) ) );
	
	$title = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['title'] ), false ) );
	$body = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['body'] ), false ) );
	$id = intval( $_REQUEST['id'] );
	
	$db->query( "UPDATE " . PREFIX . "_vote set category='$category', title='$title', body='$body' where id=$id" );
	@unlink( ENGINE_DIR . '/cache/system/vote.php' );
	msg( "info", $lang['vote_str_4'], $lang['vote_str_4'], "?mod=editvote" );

} elseif( ! $stop ) {
	
	echoheader( "vote", $lang[addvote] );
	$canedit = false;
	
	// ********************************************************************************
	// Add Form
	// ********************************************************************************
	

	if( ($_GET['action'] == "edit") && $id != '' ) {
		$canedit = true;
		$row = $db->super_query( "SELECT title, body, category FROM " . PREFIX . "_vote WHERE id='$id' LIMIT 0,1" );
		
		$title = $parse->decodeBBCodes( $row['title'], false );
		$body = $parse->decodeBBCodes( $row['body'], false );
		$icategory = explode( ',', $row['category'] );
		if( $row['category'] == "all" ) $all_cats = "selected";
		else $all_cats = "";
	
	} else {
		$canedit = false;
	}
	;
	
	$opt_category = CategoryNewsSelection( $icategory, 0, FALSE );
	
	echo "<script>
     function insertext(text,area){
      if(area == \"body\")  {
         document.addvote.body.focus();
         document.addvote.body.value=document.addvote.body.value +\" \"+ text;
         document.addvote.body.focus()
      }
     }
  </script>";
	
	if( $canedit == false ) {
		echo "<form method=post action=\"?mod=addvote&action=add\" style=\"padding:0; margin:0\" name=\"addvote\" onsubmit=\"if(document.addvote.title.value == '' || document.addvote.body.value == ''){alert('$lang[vote_alert]');return false}\">";
		$button = "<input type=\"submit\" class=\"buttons\" value=\"{$lang['vote_new']}\">";
	} else {
		echo "<form method=post action=\"?mod=addvote&action=update&id=$id\" style=\"padding:0; margin:0\" name=\"addvote\" onsubmit=\"if(document.addvote.title.value == '' || document.addvote.body.value == ''){alert('$lang[vote_alert]');return false}\">";
		$button = "<input type=\"submit\" class=\"buttons\" value=\"{$lang['vote_edit']}\">";
	
	}
	
	echo <<<HTML
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_votec']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="130" style="padding:4px;">{$lang['vote_title']}</td>
        <td><input type="text" class="edit" name="title" style="width:316px" value="$title"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_vtitle]}', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['addnews_cat']}</td>
        <td style="padding-bottom:2px;"><select name="category[]" class="cat_select" multiple>
   <option value="all" {$all_cats}>{$lang['edit_all']}</option>
   {$opt_category}
   </select><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_vcat]}', this, event, '200px')">[?]</a></td>
    </tr>
HTML;
	
	include (ENGINE_DIR . '/inc/include/inserttag.php');
	
	echo <<<HTML
    <tr>
        <td style="padding:4px;">$lang[vote_body]<br /><span class="navigation">$lang[vote_str_1]</span></td>
        <td>
	<table width="100%"><tr><td>{$bb_code}
     <textarea rows=16 style="width:98%;" name="body" id="body" onclick="setFieldName(this.name)">{$body}</textarea><script type=text/javascript>var selField  = "body";</script>
    </td>
</tr></table>
	</td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">&nbsp;</td>
        <td>{$button}</td>
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
</div></form>
HTML;
	
	echofooter();
}
?>