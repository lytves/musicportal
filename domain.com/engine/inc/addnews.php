<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( ! $user_group[$member_id['user_group']]['admin_addnews'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

if( $action == "addnews" ) {
	
	echoheader( "addnews", $lang['addnews'] );
	
	$xfieldsaction = "categoryfilter";
	include (ENGINE_DIR . '/inc/xfields.php');
	echo $categoryfilter;
	

	echo "
    <SCRIPT LANGUAGE=\"JavaScript\">
    function preview(){";
	
	if( $config['allow_admin_wysiwyg'] == "yes" ) {
		echo "document.getElementById('short_story').value = tinyMCE.get('short_story').getContent();
	document.getElementById('full_story').value = tinyMCE.get('full_story').getContent();";
	}
	
	echo "if(document.addnews.short_story.value == '' || document.addnews.title.value == ''){ alert('$lang[addnews_alert]'); }
    else{
        dd=window.open('','prv','height=400,width=750,resizable=1,scrollbars=1')
        document.addnews.mod.value='preview';document.addnews.target='prv'
        document.addnews.submit();dd.focus()
        setTimeout(\"document.addnews.mod.value='addnews';document.addnews.target='_self'\",500)
    }
    }
    onload=focus;function focus(){document.forms[0].title.focus();}

	function auto_keywords ( key )
	{
		var ajax = new dle_ajax();
		ajax.onShow ('');

		var wysiwyg = '{$config['allow_admin_wysiwyg']}';

		if (wysiwyg == \"yes\") {
			var short_txt = ajax.encodeVAR( tinyMCE.get('short_story').getContent() );
			var varsString = \"short_txt=\" + short_txt;
			ajax.setVar(\"full_txt\", ajax.encodeVAR( tinyMCE.get('full_story').getContent() ));
		} else {
			var short_txt = ajax.encodeVAR( document.getElementById('short_story').value );
			var varsString = \"short_txt=\" + short_txt;
			ajax.setVar(\"full_txt\", ajax.encodeVAR( document.getElementById('full_story').value ));
		}
		ajax.setVar(\"key\", key);
		ajax.requestFile = \"engine/ajax/keywords.php\";

		if (key == 1) { ajax.element = 'autodescr'; }
		else { ajax.element = 'keywords';}

		ajax.method = 'POST';
		ajax.sendAJAX(varsString);

		return false;
	}

	function find_relates ( )
	{
		var ajax = new dle_ajax();

		var title = ajax.encodeVAR( document.getElementById('title').value );
		var varsString = \"title=\" + title;

		ajax.onShow ('');
		ajax.requestFile = 'engine/ajax/find_relates.php';
		ajax.method = 'POST';
		ajax.element = 'related_news';
		ajax.sendAJAX(varsString);

		return false;

	};
    </SCRIPT>";
	
	if( $config['allow_admin_wysiwyg'] == "yes" ) echo "<form method=post name=\"addnews\" id=\"addnews\" onsubmit=\"document.getElementById('short_story').value = tinyMCE.get('short_story').getContent(); document.getElementById('full_story').value = tinyMCE.get('full_story').getContent(); if(document.addnews.title.value == '' || document.addnews.short_story.value == ''){alert('$lang[addnews_alert]');return false}\" action=\"$PHP_SELF\">";
	else echo "<form method=post name=\"addnews\" id=\"addnews\" onsubmit=\"if(document.addnews.title.value == '' || document.addnews.short_story.value == ''){alert('$lang[addnews_alert]');return false}\" action=\"$PHP_SELF\">";
	
	$categories_list = CategoryNewsSelection( 0, 0 );
	
	if( $config['allow_multi_category'] ) $category_multiple = "class=\"cat_select\" multiple";
	else $category_multiple = "";
	
	echo <<<HTML
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<script type="text/javascript" src="engine/skins/tabs.js"></script>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='engine/ajax/loading.gif'  border='0' /></div>
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['addnews_news']}</div></td>
    </tr>
</table>

<div class="unterline"></div>
<div id="dle_tabView1">

<div class="dle_aTab" style="display:none;">

<table width="100%">
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{$lang['addnews_title']}</td>
        <td><input class="edit" type="text" size="55" name="title" id="title"> <input class="edit" type="button" onClick="find_relates(); return false;" style="width:160px;" value="{$lang['b_find_related']}"> <a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_title]}', this, event, '220px')">[?]</a><span id="related_news"></span></td>
    </tr>
    <tr>
        <td height="29" style="padding-left:5px;">{$lang['addnews_date']}</td>
        <td><input type="text" name="newdate" id="f_date_c" size="20"  class=edit>
<img src="engine/skins/images/img.gif"  align="absmiddle" id="f_trigger_c" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>&nbsp;<input type="checkbox" name="allow_date" value="yes" checked>&nbsp;{$lang['edit_jdate']}<a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_calendar]}', this, event, '320px')">[?]</a>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "f_date_c",     // id of the input field
        ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
        button         :    "f_trigger_c",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script></td>
    </tr>
    <tr>
        <td height="29" style="padding-left:5px;">{$lang['addnews_cat']}</td>
        <td><select name="category[]" id="category" onchange="onCategoryChange(this.value)" $category_multiple>
		{$categories_list}
		</select>
		</td>
    </tr>
</table>
<div class="hr_line"></div>
<table width="100%">
HTML;
	
	if( $config['allow_admin_wysiwyg'] == "yes" ) {
		
		include (ENGINE_DIR . '/editor/shortnews.php');
	
	} else {
		
		include (ENGINE_DIR . '/inc/include/inserttag.php');
		
		echo <<<HTML
    <tr>
        <td height="29" width="140" style="padding-left:5px;">{$lang['addnews_short']}<br /><input class=bbcodes style="width: 30px;" onclick="document.addnews.short_story.rows += 5;" type=button value=" + ">&nbsp;&nbsp;<input class=bbcodes style="width: 30px;" onclick="document.addnews.short_story.rows -= 5;" type=button value=" - "></td>
        <td>{$bb_code}
	<textarea rows="13" style="width:98%; padding:0px;" onclick="setFieldName(this.name)" name="short_story" id="short_story"></textarea>
	</td></tr>
HTML;
	}
	
	if( $config['allow_admin_wysiwyg'] == "yes" ) {
		
		include (ENGINE_DIR . '/editor/fullnews.php');
	
	} else {
		
		echo <<<HTML
    <tr>
    <td height="29" style="padding-left:5px;">{$lang['addnews_full']}<br /><span class="navigation">({$lang['addnews_alt']})</span><br /><input class=bbcodes style="width: 30px;" onclick="document.addnews.full_story.rows += 5;" type=button value=" + ">&nbsp;&nbsp;<input class=bbcodes style="width: 30px;" onclick="document.addnews.full_story.rows -= 5;" type=button value=" - "></td>
    <td><textarea rows="16" onclick="setFieldName(this.name)" name="full_story" id="full_story" style="width:98%;"></textarea>
	</td></tr>
HTML;
	}
	
	// XFields Call
	$xfieldsaction = "list";
	$xfieldsadd = true;
	include (ENGINE_DIR . '/inc/xfields.php');
	// End XFields Call
	echo $output;
	
	if( $user_group[$member_id['user_group']]['allow_fixed'] and $config['allow_fixed'] ) $fix_input = "<input type=\"checkbox\" name=\"news_fixed\" value=\"1\"> $lang[addnews_fix]";
	if( $user_group[$member_id['user_group']]['allow_main'] ) $main_input = "<input type=\"checkbox\" name=\"allow_main\" value=\"1\" checked> {$lang['addnews_main']}";
	
	if( $config['allow_admin_wysiwyg'] != "yes" ) $fix_br = "<input type=\"checkbox\" name=\"allow_br\" value=\"1\" checked> {$lang['allow_br']}";
	else $fix_br = "";
	
	echo <<<HTML
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td height="29" style="padding-left:5px;">{$lang['addnews_option']}</td>
        <td><input type="checkbox" name="approve" value="1" checked> {$lang['addnews_mod']}<br /><br />

	{$main_input}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="allow_comm" value="1" checked> {$lang['addnews_comm']}<br />
	<input type="checkbox" name="allow_rating" value="1" checked> {$lang['addnews_allow_rate']}&nbsp;&nbsp;&nbsp;{$fix_input}<br /><br />{$fix_br}
</td>
	</tr>
</table>
	</div>
HTML;
	
	echo <<<HTML
	<div class="dle_aTab" style="display:none;">
<table width="100%">
    <tr>
        <td width="140" style="padding:4px;">{$lang['v_ftitle']}</td>
        <td ><input type="text" class="edit" name="vote_title" style="width:350px"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_ftitle]}', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['vote_title']}</td>
        <td><input type="text" class="edit" name="frage" style="width:350px"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_vtitle]}', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:4px;">$lang[vote_body]<br /><span class="navigation">$lang[vote_str_1]</span></td>
        <td><textarea rows="10" style="width:350px;" name="vote_body"></textarea>
    </td>
    </tr>
    <tr>
        <td style="padding:4px;">&nbsp;</td>
        <td><input type="checkbox" name="allow_m_vote" value="1"> {$lang['v_multi']}</td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
</table>
<div class="navigation">{$lang['v_info']}</div>
</div>

<div class="dle_aTab" style="display:none;">
	<table width="100%">
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{$lang['catalog_url']}</td>
        <td><input type="text" name="catalog_url" size="5"  class="edit"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[catalog_hint_url]}', this, event, '300px')">[?]</a></td>
    </tr>
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{$lang['addnews_url']}</td>
        <td><input type="text" name="alt_name" size="55"  class="edit"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_url]}', this, event, '300px')">[?]</a></td>
    </tr>
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{$lang['addnews_tags']}</td>
        <td><input type="text" name="tags" size="55"  class="edit"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_tags]}', this, event, '300px')">[?]</a></td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td height="29" style="padding-left:5px;">{$lang['date_expires']}</td>
        <td><input type="text" name="expires" id="e_date_c" size="20"  class=edit>
<img src="engine/skins/images/img.gif"  align="absmiddle" id="e_trigger_c" style="cursor: pointer; border: 0" /> {$lang['cat_action']} <select name="expires_action"><option value="0">{$lang['edit_dnews']}</option><option value="1" >{$lang['mass_edit_notapp']}</option></select><a href="#" class="hintanchor" onMouseover="showhint('{$lang['hint_expires']}', this, event, '320px')">[?]</a>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "e_date_c",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "e_trigger_c",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
        singleClick    :    true
    });
</script></td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
	    <tr>
	        <td>&nbsp;</td>
	        <td>{$lang['add_metatags']}<a href="#" class="hintanchor" onMouseover="showhint('{$lang['hint_metas']}', this, event, '220px')">[?]</a></td>
	    </tr>
	    <tr>
	        <td height="29" style="padding-left:5px;">{$lang['meta_title']}</td>
	        <td><input type="text" name="meta_title" style="width:388px;" class="edit"></td>
	    </tr>
	    <tr>
	        <td height="29" style="padding-left:5px;">{$lang['meta_descr']}</td>
	        <td><input type="text" name="descr" id="autodescr" style="width:388px;" class="edit"> ({$lang['meta_descr_max']})</td>
	    </tr>
	    <tr>
	        <td height="29" style="padding-left:5px;">{$lang['meta_keys']}</td>
	        <td><textarea name="keywords" id='keywords' style="width:388px;height:70px;"></textarea><br />
			<input onClick="auto_keywords(1)" type="button" class="buttons" value="{$lang['btn_descr']}" style="width:170px;">&nbsp;
			<input onClick="auto_keywords(2)" type="button" class="buttons" value="{$lang['btn_keyword']}" style="width:210px;">
			</td>
	    </tr>
	</table>
	</div>

	<div class="dle_aTab" style="display:none;">

<table width="100%">
HTML;
	
	if( $member_id['user_group'] < 3 ) {
		foreach ( $user_group as $group ) {
			if( $group['id'] > 1 ) {
				echo <<<HTML
    <tr>
        <td width="150" style="padding:4px;">{$group['group_name']}</td>
        <td><select name="group_extra[{$group['id']}]">
		<option value="0">{$lang['ng_group']}</option>
		<option value="1">{$lang['ng_read']}</option>
		<option value="2">{$lang['ng_all']}</option>
		<option value="3">{$lang['ng_denied']}</option>
		</select></td>
    </tr>
HTML;
			}
		}
	} else {
		
		echo <<<HTML
    <tr>
        <td style="padding:4px;"><br />{$lang['tabs_not']}</br /><br /></td>
    </tr>
HTML;
	
	}
	
	echo <<<HTML
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
</table>
<div class="navigation">{$lang['tabs_g_info']}</div>
</div>

</div>
HTML;
	
	echo <<<HTML
<div style="padding-left:150px;padding-top:5px;padding-bottom:5px;">
	<input type="submit" class="buttons" value="{$lang['btn_send']}" style="width:100px;">&nbsp;
	<input onClick="preview()" type="button" class="buttons" value="{$lang['btn_preview']}" style="width:100px;">
    <input type=hidden name=mod value=addnews>
	<input type=hidden name=action value=doaddnews>
	<input type="hidden" name="user_hash" value="$dle_login_hash" />
</div>
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
<script type="text/javascript">
initTabs('dle_tabView1',Array('{$lang['tabs_news']}','{$lang['tabs_vote']}','{$lang['tabs_extra']}','{$lang['tabs_perm']}'),0, '100%');
</script>
HTML;
	
	echofooter();

} // ********************************************************************************
// Do add News
// ********************************************************************************
elseif( $action == "doaddnews" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	include_once ENGINE_DIR . '/classes/parse.class.php';
	
	$parse = new ParseFilter( Array (), Array (), 1, 1 );
	
	$allow_comm = isset( $_POST['allow_comm'] ) ? intval( $_POST['allow_comm'] ) : 0;
	$allow_main = isset( $_POST['allow_main'] ) ? intval( $_POST['allow_main'] ) : 0;
	$approve = isset( $_POST['approve'] ) ? intval( $_POST['approve'] ) : 0;
	$allow_rating = isset( $_POST['allow_rating'] ) ? intval( $_POST['allow_rating'] ) : 0;
	$news_fixed = isset( $_POST['news_fixed'] ) ? intval( $_POST['news_fixed'] ) : 0;
	$allow_br = isset( $_POST['allow_br'] ) ? intval( $_POST['allow_br'] ) : 0;
	if( ! count( $category ) ) {
		$category = array ();
		$category[] = '0';
	}
	$category_list = $db->safesql( implode( ',', $category ) );
	
	$allow_list = explode( ',', $user_group[$member_id['user_group']]['cat_add'] );
	
	foreach ( $category as $selected ) {
		if( $allow_list[0] != "all" and ! in_array( $selected, $allow_list ) and $member_id['user_group'] != "1" ) $approve = 0;
	}

	$title = $parse->process(  trim( strip_tags ($_POST['title']) ) );

	if ( $config['allow_admin_wysiwyg'] == "yes" ) $parse->allow_code = false;
	
	$full_story = $parse->process( $_POST['full_story'] );
	$short_story = $parse->process( $_POST['short_story'] );

	if( $config['allow_admin_wysiwyg'] == "yes" or $allow_br != '1' ) {
		
		$full_story = $db->safesql( $parse->BB_Parse( $full_story ) );
		$short_story = $db->safesql( $parse->BB_Parse( $short_story ) );
	
	} else {
		
		$full_story = $db->safesql( $parse->BB_Parse( $full_story, false ) );
		$short_story = $db->safesql( $parse->BB_Parse( $short_story, false ) );
	}

	if( $parse->not_allowed_text ) {
		msg( "error", $lang['addnews_error'], $lang['news_err_39'], "javascript:history.go(-1)" );
	}
	
	$alt_name = $_POST['alt_name'];
	
	if( trim( $alt_name ) == "" or ! $alt_name ) $alt_name = totranslit( stripslashes( $title ), true, false );
	else $alt_name = totranslit( stripslashes( $alt_name ), true, false );
	
	$title = $db->safesql( $title );
	
	$metatags = create_metatags( $short_story . $full_story );
	
	$catalog_url = $db->safesql( substr( htmlspecialchars( strip_tags( stripslashes( trim( $_POST['catalog_url'] ) ) ) ), 0, 3 ) );
	
	if( preg_match( "/[\||\'|\<|\>|\"|\!|\?|\$|\@|\/|\\\|\&\~\*\+]/", $_POST['tags'] ) ) $_POST['tags'] = "";
	else $_POST['tags'] = $db->safesql( htmlspecialchars( strip_tags( stripslashes( trim( $_POST['tags'] ) ) ), ENT_QUOTES ) );
	
	// обработка опроса
	if( trim( $_POST['vote_title'] != "" ) ) {
		
		$add_vote = 1;
		$vote_title = trim( $db->safesql( $parse->process( $_POST['vote_title'] ) ) );
		$frage = trim( $db->safesql( $parse->process( $_POST['frage'] ) ) );
		$vote_body = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['vote_body'] ), false ) );
		$allow_m_vote = intval( $_POST['allow_m_vote'] );
	
	} else
		$add_vote = 0;
		
	// обработка доступа
	if( $member_id['user_group'] < 3 ) {
		
		$group_regel = array ();
		
		foreach ( $_POST['group_extra'] as $key => $value ) {
			if( $value ) $group_regel[] = intval( $key ) . ':' . intval( $value );
		}
		
		if( count( $group_regel ) ) $group_regel = implode( "||", $group_regel );
		else $group_regel = "";
	
	} else
		$group_regel = '';
	
	if( trim( $_POST['expires'] ) != "" ) {
		if( (($expires = strtotime( $_POST['expires'] )) === - 1) ) {
			msg( "error", $lang['addnews_error'], $lang['addnews_erdate'], "javascript:history.go(-1)" );
		} 
	} else $expires = '';

		
	// Обработка даты и времени
	$added_time = time() + ($config['date_adjust'] * 60);
	
	if( $allow_date != "yes" ) {
		
		if( (($newsdate = strtotime( $newdate )) === - 1) or (trim( $newdate ) == "") ) {
			msg( "error", $lang['addnews_error'], $lang['addnews_erdate'], "javascript:history.go(-1)" );
		} else {
			$thistime = date( "Y-m-d H:i:s", $newsdate );
		}
		
		if( ! intval( $config['no_date'] ) and $newsdate > $added_time ) {
			$thistime = date( "Y-m-d H:i:s", $added_time );
		}
	
	} else
		$thistime = date( "Y-m-d H:i:s", $added_time );
		////////////////////////////
	

	if( trim( $title ) == "" or ! $title ) {
		msg( "error", $lang['addnews_error'], $lang['addnews_ertitle'], "javascript:history.go(-1)" );
	}
	if( trim( $short_story ) == "" or ! $short_story ) {
		msg( "error", $lang['addnews_error'], $lang['addnews_erstory'], "javascript:history.go(-1)" );
	}
	if( strlen( $title ) > 255 ) {
		msg( "error", $lang['addnews_error'], $lang['addnews_error'], "javascript:history.go(-1)" );
	}
	
	$xfieldsid = $added_time;
	$xfieldsaction = "init";
	include (ENGINE_DIR . '/inc/xfields.php');
	$filecontents = array ();
	
	if( $config['safe_xfield'] ) {
		$parse->ParseFilter();
		$parse->safe_mode = true;
	}
	
	if( ! empty( $postedxfields ) ) {
		foreach ( $postedxfields as $xfielddataname => $xfielddatavalue ) {
			if( $xfielddatavalue == "" ) {
				continue;
			}
			
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
	
	$db->query( "INSERT INTO " . PREFIX . "_post (date, autor, short_story, full_story, xfields, title, descr, keywords, category, alt_name, allow_comm, approve, allow_main, fixed, allow_rate, allow_br, votes, access, symbol, flag, tags, metatitle) values ('$thistime', '{$member_id['name']}', '$short_story', '$full_story', '$filecontents', '$title', '{$metatags['description']}', '{$metatags['keywords']}', '$category_list', '$alt_name', '$allow_comm', '$approve', '$allow_main', '$news_fixed', '$allow_rating', '$allow_br', '$add_vote', '$group_regel', '$catalog_url', '1', '{$_POST['tags']}', '{$metatags['title']}')" );
	
	$row = $db->insert_id();
	
	if( $add_vote ) {
		$db->query( "INSERT INTO " . PREFIX . "_poll (news_id, title, frage, body, votes, multiple) VALUES('{$row}', '$vote_title', '$frage', '$vote_body', 0, '$allow_m_vote')" );
	}

	if( $expires ) {
		$expires_action = intval($_POST['expires_action']);
		$db->query( "INSERT INTO " . PREFIX . "_post_log (news_id, expires, action) VALUES('{$row}', '$expires', '$expires_action')" );
	}
	
	if( $_POST['tags'] != "" and $approve ) {
		
		$tags = array ();
		
		$_POST['tags'] = explode( ",", $_POST['tags'] );
		
		foreach ( $_POST['tags'] as $value ) {
			
			$tags[] = "('" . $row . "', '" . trim( $value ) . "')";
		}
		
		$tags = implode( ", ", $tags );
		$db->query( "INSERT INTO " . PREFIX . "_tags (news_id, tag) VALUES " . $tags );
	
	}
	
	$db->query( "UPDATE " . PREFIX . "_images set news_id='{$row}' where author = '{$member_id['name']}' AND news_id = '0'" );
	$db->query( "UPDATE " . PREFIX . "_files set news_id='{$row}' where author = '{$member_id['name']}' AND news_id = '0'" );
	$db->query( "UPDATE " . USERPREFIX . "_users set news_num=news_num+1 where user_id='{$member_id['user_id']}'" );
	
	clear_cache();
	
	msg( "info", $lang['addnews_ok'], $lang['addnews_ok_1'] . " \"" . stripslashes( stripslashes( $title ) ) . "\" " . $lang['addnews_ok_2'] );
}
?>