<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( isset( $_REQUEST['subaction'] ) ) $subaction = $_REQUEST['subaction']; else $subaction = "";
if( isset( $_REQUEST['do_template'] ) ) $do_template = $_REQUEST['do_template']; else $do_template = "";

if( $action == "options" or $action == '' ) {
	echoheader( "options", $lang['opt_head'] );
	
	//----------------------------------
	// ������ ��������
	//----------------------------------
	

	$options = array ();
	
	$options['config'] = array (
								
								array (
											'name' => $lang['opt_all'], 
											'url' => "$PHP_SELF?mod=options&action=syscon", 
											'descr' => $lang['opt_allc'], 
											'image' => "tools.png", 
											'access' => "admin" 
								), 
								
								array (
											'name' => $lang['opt_cat'], 
											'url' => "$PHP_SELF?mod=categories", 
											'descr' => $lang['opt_catc'], 
											'image' => "cats.png", 
											'access' => $user_group[$member_id['user_group']]['admin_categories'] 
								), 
								
								array (
											'name' => $lang['opt_db'], 
											'url' => "$PHP_SELF?mod=dboption", 
											'descr' => $lang['opt_dbc'], 
											'image' => "dbset.png", 
											'access' => "admin" 
								), 

								array (
											'name' => $lang['opt_vconf'], 
											'url' => "$PHP_SELF?mod=videoconfig", 
											'descr' => $lang['opt_vconfc'], 
											'image' => "video.png", 
											'access' => "admin" 
								),
								
								array (
											'name' => $lang['opt_xfil'], 
											'url' => "$PHP_SELF?mod=xfields&xfieldsaction=configure", 
											'descr' => $lang['opt_xfilc'], 
											'image' => "xfset.png", 
											'access' => $user_group[$member_id['user_group']]['admin_xfields'] 
								),
								
																array(
'name'       => "DleMusic Service by odmin",
'url'        => "$PHP_SELF?mod=mservice",
'descr'      => "���������� ������������, ����������� � ����������� ������������ ������ �����",
'image'      => "mservice_dle82.png",
'access'     => "admin"
)  
	);
	
	$options['user'] = array (
							
							array (
										'name' => $lang['opt_priv'], 
										'url' => "$PHP_SELF?mod=options&action=personal", 
										'descr' => $lang['opt_privc'], 
										'image' => "pset.png", 
										'access' => "all" 
							), 
							
							array (
										'name' => $lang['opt_user'], 
										'url' => "$PHP_SELF?mod=editusers&action=list", 
										'descr' => $lang['opt_userc'], 
										'image' => "uset.png", 
										'access' => $user_group[$member_id['user_group']]['admin_editusers'] 
							), 
							
							array (
										'name' => $lang['opt_xprof'], 
										'url' => "$PHP_SELF?mod=userfields&xfieldsaction=configure", 
										'descr' => $lang['opt_xprofd'], 
										'image' => "xprof.png", 
										'access' => $user_group[$member_id['user_group']]['admin_userfields'] 
							), 
							
							array (
										'name' => $lang['opt_group'], 
										'url' => "$PHP_SELF?mod=usergroup", 
										'descr' => $lang['opt_groupc'], 
										'image' => "usersgroup.png", 
										'access' => "admin" 
							) 
	);
	
	$options['templates'] = array (
									
									array (
											'name' => $lang['opt_t'], 
											'url' => "$PHP_SELF?mod=templates&user_hash=" . $dle_login_hash, 
											'descr' => $lang['opt_tc'], 
											'image' => "tmpl.png", 
											'access' => "admin" 
									), 
									
									array (
											'name' => $lang['opt_email'], 
											'url' => "$PHP_SELF?mod=email", 
											'descr' => $lang['opt_emailc'], 
											'image' => "mset.png", 
											'access' => "admin" 
									) 
	);

	
	
	$options['filter'] = array (
								
								array (
											'name' => $lang['opt_fil'], 
											'url' => "$PHP_SELF?mod=wordfilter", 
											'descr' => $lang['opt_filc'], 
											'image' => "fset.png", 
											'access' => $user_group[$member_id['user_group']]['admin_wordfilter'] 
								), 
								
								array (
											'name' => $lang['opt_ipban'], 
											'url' => "$PHP_SELF?mod=blockip", 
											'descr' => $lang['opt_ipbanc'], 
											'image' => "blockip.png", 
											'access' => $user_group[$member_id['user_group']]['admin_blockip'] 
								), 
								
								array (
											'name' => $lang['opt_iptools'], 
											'url' => "$PHP_SELF?mod=iptools", 
											'descr' => $lang['opt_iptoolsc'], 
											'image' => "iptools.png", 
											'access' => $user_group[$member_id['user_group']]['admin_iptools'] 
								), 
								array (
											'name' => $lang['opt_sfind'], 
											'url' => "$PHP_SELF?mod=search", 
											'descr' => $lang['opt_sfindc'], 
											'image' => "find_base.png", 
											'access' => "admin" 
								) 
	);

	
	
	$options['others'] = array (
	array(
'name'       => "���. ������� ��� �����������",
'url'        => "$PHP_SELF?mod=quest",
'descr'      => "�������������� ������� ��� �����������. ���������.)",
'image'    => "rules.png",
'access'     => "admin",
),
								array (
											'name' => $lang['opt_rules'], 
											'url' => "$PHP_SELF?mod=static&action=doedit&page=rules", 
											'descr' => $lang['opt_rulesc'], 
											'image' => "rules.png", 
											'access' => $user_group[$member_id['user_group']]['admin_static'] 
								), 
								
								array (
											'name' => $lang['opt_static'], 
											'url' => "$PHP_SELF?mod=static", 
											'descr' => $lang['opt_staticd'], 
											'image' => "spset.png", 
											'access' => $user_group[$member_id['user_group']]['admin_static'] 
								), 
								
								array (
											'name' => $lang['opt_clean'], 
											'url' => "$PHP_SELF?mod=clean", 
											'descr' => $lang['opt_cleanc'], 
											'image' => "clean.png", 
											'access' => "admin" 
								),
								
							
								array (
											'name' => $lang['main_newsl'], 
											'url' => "$PHP_SELF?mod=newsletter", 
											'descr' => $lang['main_newslc'], 
											'image' => "nset.png", 
											'access' => $user_group[$member_id['user_group']]['admin_newsletter'] 
								), 
								array (
											'name' => $lang['opt_vote'], 
											'url' => "$PHP_SELF?mod=editvote", 
											'descr' => $lang['opt_votec'], 
											'image' => "votes.png", 
											'access' => $user_group[$member_id['user_group']]['admin_editvote'] 
								), 
								
								array (
											'name' => $lang['opt_img'], 
											'url' => "$PHP_SELF?mod=files", 
											'descr' => $lang['opt_imgc'], 
											'image' => "iset.png", 
											'access' => "admin" 
								), 
								
								array (
											'name' => $lang['opt_banner'], 
											'url' => "$PHP_SELF?mod=banners&action=list", 
											'descr' => $lang['opt_bannerc'], 
											'image' => "ads.png", 
											'access' => $user_group[$member_id['user_group']]['admin_banners'] 
								), 
								array (
											'name' => $lang['opt_google'], 
											'url' => "$PHP_SELF?mod=googlemap", 
											'descr' => $lang['opt_googlec'], 
											'image' => "googlemap.png", 
											'access' => $user_group[$member_id['user_group']]['admin_googlemap'] 
								),
								array (
											'name' => $lang['opt_rss'], 
											'url' => "$PHP_SELF?mod=rss", 
											'descr' => $lang['opt_rssc'], 
											'image' => "rss_import.png", 
											'access' => $user_group[$member_id['user_group']]['admin_rss'] 
								), 
								array (
											'name' => $lang['opt_rssinform'], 
											'url' => "$PHP_SELF?mod=rssinform", 
											'descr' => $lang['opt_rssinformc'], 
											'image' => "rss_inform.png", 
											'access' => $user_group[$member_id['user_group']]['admin_rssinform'] 
								)
	);

	
	
	//------------------------------------------------
	// Cut the options for wich we don't have access
	//------------------------------------------------
	foreach ( $options as $sub_options => $value ) {
		$count_options = count( $value );
		
		for($i = 0; $i < $count_options; $i ++) {

			if ($member_id['user_group'] == 1 ) continue;

			if ($member_id['user_group'] != 1 AND  $value[$i]['access'] == "admin") unset( $options[$sub_options][$i] );

			if ( !$value[$i]['access'] ) unset( $options[$sub_options][$i] );
		}
	}
	
	$subs = 0;
	
	foreach ( $options as $sub_options ) {
		
		if( $subs == 1 ) $lang['opt_hopt'] = $lang['opt_s_acc'];
		if( $subs == 2 ) $lang['opt_hopt'] = $lang['opt_s_tem'];
		if( $subs == 3 ) $lang['opt_hopt'] = $lang['opt_s_fil'];
		if( $subs == 4 ) $lang['opt_hopt'] = $lang['opt_s_oth'];
		
		$subs ++;
		
		if( ! count( $sub_options ) ) continue;
		
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_hopt']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%"><tr>
HTML;
		
		$i = 0;
		
		foreach ( $sub_options as $option ) {
			
			if( $i > 1 ) {
				echo "</tr><tr>";
				$i = 0;
			}
			
			$i ++;

			echo <<<HTML
<td width="50%">
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/{$option['image']}" border="0"></td>
        <td valign="middle"><div class="quick"><a href="{$option['url']}"><h3>{$option['name']}</h3>{$option['descr']}</a></div></td>
    </tr>
</table>
</td>
HTML;
			
		}
		
		echo <<<HTML
</tr></table>
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
	
	}

	$db->query( "SELECT * FROM " . PREFIX . "_admin_sections" );


	$i = 0;
	$sections = "";
		
	while ( $row = $db->get_array() ) {

		if ($row['allow_groups'] != "all") {

			$groups = explode(",", $row['allow_groups']);

		if ( !in_array($member_id['user_group'], $groups) AND $member_id['user_group'] !=1 ) continue;

		}
			
		if( $i > 1 ) {
			$sections .= "</tr><tr>";
			$i = 0;
		}
			
		$i ++;

		$row['name'] = totranslit($row['name'], true, false);
		$row['icon'] = totranslit($row['icon'], false, true);

		$row['title'] = strip_tags(stripslashes($row['title']));
		$row['descr'] = strip_tags(stripslashes($row['descr']));


$sections .= "
<td width=\"50%\">
<table width=\"100%\">
    <tr>
        <td width=\"70\" height=\"70\" valign=\"middle\" align=\"center\" style=\"padding-top:5px;padding-bottom:5px;\"><img src=\"engine/skins/images/{$row['icon']}\" border=\"0\"></td>
        <td valign=\"middle\"><div class=\"quick\"><a href=\"$PHP_SELF?mod={$row['name']}\"><h3>{$row['title']}</h3>{$row['descr']}</a></div></td>
    </tr>
</table>
</td>";			

	}

	if ( $sections ) {


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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['admin_other_section']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%"><tr>
{$sections}
</tr></table>
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


	}

	echofooter();
} 

// ********************************************************************************
// �������������� ������������ ����������
// ********************************************************************************
elseif( $action == "personal" ) {
	echoheader( "user", $lang['opt_priv'] );
	
	$registrationdate = langdate( "l, j F Y - H:i", $member_id['reg_date'] ); //registration date
	if( $member_id['allow_mail'] == 0 ) $ifchecked = "Checked";
	else $ifchecked = ""; //if user wants to hide his e-mail
	

	foreach ( $member_id as $key => $value ) {
		$member_id[$key] = stripslashes( preg_replace( array (
																"'\"'", 
																"'\''" 
		), array (
					"&quot;", 
					"&#039;" 
		), $member_id[$key] ) );
	}
	
	echo <<<HTML
<form method="post" action="" name="personal">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_hprv']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="150" style="padding:2px;">{$lang['user_name']}</td>
        <td>{$member_id['name']}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_acc']}</td>
        <td >{$user_group[$member_id['user_group']]['group_name']}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_news']}</td>
        <td>{$member_id['news_num']}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_reg']}</td>
        <td>{$registrationdate}</td>
    </tr>
   <tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
        <td style="padding:2px;">{$lang['user_mail']}</td>
        <td style="padding:2px;"><input class="edit" type="text" name="editmail" value="{$member_id['email']}">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="edithidemail" {$ifchecked} id="edithidemail">&nbsp;<label for="edithidemail">{$lang['opt_hmail']}</label></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['opt_fullname']}</td>
        <td style="padding:2px;"><input class="edit" name="editfullname" value="{$member_id['fullname']}" ></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['opt_land']}</td>
        <td style="padding:2px;"><input class="edit" name="editland" value="{$member_id['land']}"></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['opt_icq']}</td>
        <td style="padding:2px;"><input class="edit" name="editicq" value="{$member_id['icq']}"></td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['opt_altpassw']}</td>
        <td style="padding:2px;"><input class="edit" name="altpass" type="password"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_pass]}', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_newpass']}</td>
        <td style="padding:2px;"><input class="edit" name="editpassword"></td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left:5px;"><input type="submit" class="buttons" value="&nbsp;{$lang['user_save']}&nbsp;">
     <input type="hidden" name="mod" value="options">
	 <input type="hidden" name="user_hash" value="$dle_login_hash" />
     <input type="hidden" name="action" value="dosavepersonal"></td>
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
} 
// ********************************************************************************
// ������ ������������ ����������
// ********************************************************************************
elseif( $action == "dosavepersonal" ) {
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$editpassword = $_POST['editpassword'];
	$edithidemail = intval( $_POST['edithidemail'] );
	$editmail = $db->safesql( $_POST['editmail'] );

	if( (! preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $editmail )) or (empty( $editmail )) ) {
		
		msg( "error", "Error !!!", "E-mail not correct", "$PHP_SELF?mod=options&action=personal" );
	}

	$editfullname = $db->safesql( $_POST['editfullname'] );

	if ( preg_match( "/[\||\'|\<|\>|\"|\!|\]|\?|\$|\@|\/|\\\|\&\~\*\+]/", $editfullname ) ) {

		$editfullname = "";
	}

	$editland = $db->safesql( $_POST['editland'] );

	if ( preg_match( "/[\||\'|\<|\>|\"|\!|\]|\?|\$|\@|\/|\\\|\&\~\*\+]/", $editland ) ) {

		$editland = "";
	}

	$editicq = intval( $_POST['editicq'] );

	if ( !$editicq ) $editicq = "";

	$altpass = md5( $_POST['altpass'] );
	
	if( $edithidemail ) {
		$edithidemail = 0;
	} else {
		$edithidemail = 1;
	}
	
	if( $editpassword != "" ) {
		
		if( $altpass == $cmd5_password ) {
			$editpassword = md5( md5( $editpassword ) );
			$sql_update = "UPDATE " . USERPREFIX . "_users SET email='$editmail', fullname='$editfullname', land='$editland', icq='$editicq', allow_mail='$edithidemail', password='$editpassword' where user_id='{$member_id['user_id']}'";
		
		} else
			msg( "error", "Error !!!", $lang['opt_errpass'], "$PHP_SELF?mod=options&action=personal" );
	
	} else {
		
		$sql_update = "UPDATE " . USERPREFIX . "_users set email='$editmail', fullname='$editfullname', land='$editland', icq='$editicq', allow_mail='$edithidemail' where user_id='{$member_id['user_id']}'";
	}
	
	$db->query( $sql_update );
	
	$personal_success = TRUE;
	
	if( $personal_success ) {
		msg( "info", $lang['user_editok'], $lang['opt_peok'], "$PHP_SELF?mod=options&action=personal" );
	} else {
		msg( "error", "Error !!!", $lang['user_nouser'], "$PHP_SELF?mod=options&action=personal" );
	}
} 
// ********************************************************************************
// ��������� �������
// ********************************************************************************
elseif( $action == "syscon" ) {
	
	if( $member_id['user_group'] != 1 ) {
		msg( "error", $lang['opt_denied'], $lang['opt_denied'] );
	}
	
	include_once ENGINE_DIR . '/classes/parse.class.php';
	$parse = new ParseFilter( Array (), Array (), 1, 1 );
	
	$config['offline_reason'] = str_replace( '&quot;', '"', $config['offline_reason'] );
	
	$config['offline_reason'] = $parse->decodeBBCodes( $config['offline_reason'], false );
	if( $auto_detect_config ) $config['http_home_url'] = "";
	
	echoheader( "options", $lang['opt_all'] );
	
	function showRow($title = "", $description = "", $field = "") {
		echo "<tr>
        <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=394 align=middle >
        $field
        </tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=2></td></tr>";
		$bg = "";
		$i ++;
	}
	
	function makeDropDown($options, $name, $selected) {
		$output = "<select name=\"$name\">\r\n";
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
	
	if( ! $handle = opendir( "./templates" ) ) {
		die( "���������� ������� ���������� ./templates" );
	}
	while ( false !== ($file = readdir( $handle )) ) {
		if( is_dir( ROOT_DIR . "/templates/$file" ) and ($file != "." and $file != "..") ) {
			$sys_con_skins_arr[$file] = $file;
		}
	}
	closedir( $handle );
	
	if( ! $handle = opendir( "./language" ) ) {
		die( "���������� ������� ���������� ./data/language/" );
	}
	while ( false !== ($file = readdir( $handle )) ) {
		if( is_dir( ROOT_DIR . "/language/$file" ) and ($file != "." and $file != "..") ) {
			$sys_con_langs_arr[$file] = $file;
		}
	}
	closedir( $handle );
	
	foreach ( $user_group as $group )
		$sys_group_arr[$group['id']] = $group['group_name'];
	
	echo <<<HTML
<script language='JavaScript' type="text/javascript">

        function ChangeOption(selectedOption) {

                document.getElementById('general').style.display = "none";
                document.getElementById('security').style.display = "none";
                document.getElementById('news').style.display = "none";
                document.getElementById('comments').style.display = "none";
                document.getElementById('optimisation').style.display = "none";
                document.getElementById('files').style.display = "none";
                document.getElementById('mail').style.display = "none";
                document.getElementById('users').style.display = "none";
                document.getElementById('imagesconf').style.display = "none";
                document.getElementById('rss').style.display = "none";
                document.getElementById('smartphone').style.display = "none";

                if(selectedOption == 'general') {document.getElementById('general').style.display = "";}
                if(selectedOption == 'security') {document.getElementById('security').style.display = "";}
                if(selectedOption == 'news') {document.getElementById('news').style.display = "";}
                if(selectedOption == 'comments') {document.getElementById('comments').style.display = "";}
                if(selectedOption == 'optimisation') {document.getElementById('optimisation').style.display = "";}
                if(selectedOption == 'files') {document.getElementById('files').style.display = "";}
                if(selectedOption == 'mail') {document.getElementById('mail').style.display = "";}
                if(selectedOption == 'users') {document.getElementById('users').style.display = "";}
                if(selectedOption == 'imagesconf') {document.getElementById('imagesconf').style.display = "";}
                if(selectedOption == 'smartphone') {document.getElementById('smartphone').style.display = "";}
                if(selectedOption == 'rss') {document.getElementById('rss').style.display = "";}


       }

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_general_sys']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;">
<table style="text-align:center;" width="100%" height="35px">
<tr style="vertical-align:middle;" >
 <td class=tableborder><a href="javascript:ChangeOption('general');"><img title="$lang[opt_allsys]" src="engine/skins/images/general.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('security');"><img title="$lang[opt_secrsys]" src="engine/skins/images/sred.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('news');"><img title="$lang[opt_newssys]" src="engine/skins/images/news.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('comments');"><img title="$lang[opt_commsys]" src="engine/skins/images/comments.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('optimisation');"><img title="$lang[opt_dbsys]" src="engine/skins/images/db_opt.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('files');"><img title="$lang[opt_filesys]" src="engine/skins/images/folder.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('mail');"><img title="$lang[opt_sys_mail]" src="engine/skins/images/email.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('users');"><img title="$lang[opt_usersys]" src="engine/skins/images/users.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('imagesconf');"><img title="$lang[opt_imagesys]" src="engine/skins/images/conf_images.png" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('smartphone');"><img title="$lang[opt_smartphone]" src="engine/skins/images/smartphone.jpg" border="0"></a>
 <td class=tableborder><a href="javascript:ChangeOption('rss');"><img title="$lang[opt_rsssys]" src="engine/skins/images/rss.gif" border="0"></a>

 </tr>
</table>
</td>
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
</div>
HTML;
	
	echo <<<HTML
<form action="" method="post">
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
HTML;
	
	echo <<<HTML
<tr style='' id="general"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_all']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_ht'], $lang['opt_sys_htd'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[home_title]\" value=\"{$config['home_title']}\" size=40>" );
	showRow( '�������� �������� - ��� ������ � ������ �� ���� ���� ��������', '����� �� �������� �������� �����', "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[home_title_short]\" value=\"{$config['home_title_short']}\" size=40>" );
	showRow( $lang['opt_sys_hu'], $lang['opt_sys_hud'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[http_home_url]\" value=\"{$config['http_home_url']}\" size=40>" );
	
	showRow( $lang['opt_sys_chars'], $lang['opt_sys_charsd'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[charset]\" value=\"{$config['charset']}\" size=30>" );
	
	showRow( $lang['opt_sys_descr'], $lang['opt_sys_descrd'], "<input class=edit type=text name=\"save_con[description]\" value=\"{$config['description']}\" size=40>" );
	showRow( $lang['opt_sys_key'], $lang['opt_sys_keyd'], "<textarea class=edit style=\"width:250px;height:50px;\" name=\"save_con[keywords]\">{$config['keywords']}</textarea>" );
	showRow( $lang['opt_sys_short_name'], $lang['opt_sys_short_named'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[short_title]\" value=\"{$config['short_title']}\" size=40>" );
	
	showRow( $lang['opt_sys_at'], $lang['opt_sys_atd']." ".date ( "d.m.Y, H:i", time () + ($config['date_adjust'] * 60) ), "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[date_adjust]\" value=\"{$config['date_adjust']}\" size=10>" );
	
	showRow( $lang['opt_sys_dc'], $lang['opt_sys_dcd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_alt_url]", "{$config['allow_alt_url']}" ) );
	showRow( $lang['opt_sys_seotype'], $lang['opt_sys_seotyped'], makeDropDown( array ("1" => $lang['opt_sys_seo_1'], "2" => $lang['opt_sys_seo_2'], "0" => $lang['opt_sys_seo_3'] ), "save_con[seo_type]", "{$config['seo_type']}" ) );
	
	showRow( $lang['opt_sys_al'], $lang['opt_sys_ald'], makeDropDown( $sys_con_langs_arr, "save_con[langs]", "{$config['langs']}" ) );
	showRow( $lang['opt_sys_as'], $lang['opt_sys_asd'], makeDropDown( $sys_con_skins_arr, "save_con[skin]", "{$config['skin']}" ) );
	
	showRow( $lang['opt_sys_ag'], $lang['opt_sys_agd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_gzip]", "{$config['allow_gzip']}" ) );
	showRow( $lang['opt_sys_wda'], $lang['opt_sys_wdad'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_admin_wysiwyg]", "{$config['allow_admin_wysiwyg']}" ) );
	showRow( $lang['opt_sys_wdst'], $lang['opt_sys_wdad'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_static_wysiwyg]", "{$config['allow_static_wysiwyg']}" ) );
	
	showRow( $lang['opt_sys_offline'], $lang['opt_sys_offlined'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[site_offline]", "{$config['site_offline']}" ) );
	showRow( $lang['opt_sys_reason'], $lang['opt_sys_reasond'], "<textarea class=edit style=\"width:350px;height:100px;\" name=\"save_con[offline_reason]\">{$config['offline_reason']}</textarea>" );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="security"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_secrsys']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_path'], $lang['opt_sys_pathd'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[admin_path]\" value=\"{$config['admin_path']}\" size=20>" );
	
	showRow( $lang['opt_sys_logextra'], $lang['opt_sys_logextrad'], makeDropDown( array ("0" => $lang['opt_sys_stdm'], "1" => $lang['opt_sys_extram'] ), "save_con[extra_login]", "{$config['extra_login']}" ) );
	
	showRow( $lang['opt_sys_ip'], $lang['opt_sys_ipd'], makeDropDown( array ("0" => $lang['opt_sys_ipn'], "1" => $lang['opt_sys_ipm'], "2" => $lang['opt_sys_iph'] ), "save_con[ip_control]", "{$config['ip_control']}" ) );
	
	showRow( $lang['opt_sys_loghash'], $lang['opt_sys_loghashd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[log_hash]", "{$config['log_hash']}" ) );
	
	showRow( $lang['opt_sys_sxfield'], $lang['opt_sys_sxfieldd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[safe_xfield]", "{$config['safe_xfield']}" ) );
	
	showRow( $lang['opt_sys_addsec'], $lang['opt_sys_addsecd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[sec_addnews]", "{$config['sec_addnews']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="news"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_newssys']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_newc'], $lang['opt_sys_newd'], "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[news_number]\" value=\"{$config['news_number']}\" size=10>" );
	showRow( $lang['opt_sys_related_num'], $lang['opt_sys_related_numd'], "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[related_number]\" value=\"{$config['related_number']}\" size=10>" );
	showRow( $lang['opt_sys_max_mod'], $lang['opt_sys_max_modd'], "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[max_moderation]\" value=\"{$config['max_moderation']}\" size=10>" );
	showRow( $lang['opt_sys_am'], $lang['opt_sys_amd'], "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[smilies]\" value=\"{$config['smilies']}\" size=40>" );
	showRow( $lang['opt_sys_an'], "<a onClick=\"javascript:Help('date')\" class=main href=\"#\">$lang[opt_sys_and]</a>", "<input class=edit type=text style=\"text-align: center;\"  name=\"save_con[timestamp_active]\" value=\"{$config['timestamp_active']}\" size=40>" );
	showRow( $lang['opt_sys_sort'], $lang['opt_sys_sortd'], makeDropDown( array ("date" => $lang['opt_sys_sdate'], "rating" => $lang['opt_sys_srate'], "news_read" => $lang['opt_sys_sview'], "title" => $lang['opt_sys_salph'] ), "save_con[news_sort]", "{$config['news_sort']}" ) );
	showRow( $lang['opt_sys_msort'], $lang['opt_sys_msortd'], makeDropDown( array ("DESC" => $lang['opt_sys_mminus'], "ASC" => $lang['opt_sys_mplus'] ), "save_con[news_msort]", "{$config['news_msort']}" ) );
	showRow( $lang['opt_sys_catsort'], $lang['opt_sys_catsortd'], makeDropDown( array ("date" => $lang['opt_sys_sdate'], "rating" => $lang['opt_sys_srate'], "news_read" => $lang['opt_sys_sview'], "title" => $lang['opt_sys_salph'] ), "save_con[catalog_sort]", "{$config['catalog_sort']}" ) );
	showRow( $lang['opt_sys_catmsort'], $lang['opt_sys_catmsortd'], makeDropDown( array ("DESC" => $lang['opt_sys_mminus'], "ASC" => $lang['opt_sys_mplus'] ), "save_con[catalog_msort]", "{$config['catalog_msort']}" ) );
	showRow( $lang['opt_sys_align'], $lang['opt_sys_alignd'], makeDropDown( array ("" => $lang['opt_sys_none'], "left" => $lang['opt_sys_left'], "center" => $lang['opt_sys_center'], "right" => $lang['opt_sys_right'] ), "save_con[image_align]", "{$config['image_align']}" ) );
	showRow( $lang['opt_sys_nmail'], $lang['opt_sys_nmaild'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[mail_news]", "{$config['mail_news']}" ) );
	showRow( $lang['opt_sys_sub'], $lang['opt_sys_subd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[show_sub_cats]", "{$config['show_sub_cats']}" ) );
	showRow( $lang['opt_sys_asrate'], $lang['opt_sys_asrated'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[short_rating]", "{$config['short_rating']}" ) );
	showRow( $lang['opt_sys_ad'], $lang['opt_sys_add'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[hide_full_link]", "{$config['hide_full_link']}" ) );
	showRow( $lang['opt_sys_asp'], $lang['opt_sys_aspd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_search_print]", "{$config['allow_search_print']}" ) );

	showRow( $lang['opt_sys_adt'], $lang['opt_sys_adtd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_add_tags]", "{$config['allow_add_tags']}" ) );
	showRow( $lang['opt_sys_wds'], $lang['opt_sys_wdsd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_site_wysiwyg]", "{$config['allow_site_wysiwyg']}" ) );
	showRow( $lang['opt_sys_wdq'], $lang['opt_sys_wdsd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_quick_wysiwyg]", "{$config['allow_quick_wysiwyg']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="comments"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_cch']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	showRow( $lang['opt_sys_alc'], $lang['opt_sys_alcd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_comments]", "{$config['allow_comments']}" ) );

	showRow( $lang['opt_sys_subs'], $lang['opt_sys_subsd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_subscribe]", "{$config['allow_subscribe']}" ) );
	showRow( $lang['opt_sys_comb'], $lang['opt_sys_combd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_combine]", "{$config['allow_combine']}" ) );
	showRow( $lang['opt_sys_mcommd'], $lang['opt_sys_mcommdd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_comments_days]' value=\"{$config['max_comments_days']}\" size=10>" );
	showRow( $lang['opt_sys_maxc'], $lang['opt_sys_maxcd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[comments_maxlen]' value=\"{$config['comments_maxlen']}\" size=10>" );
	showRow( $lang['opt_sys_cpm'], $lang['opt_sys_cpmd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[comm_nummers]' value=\"{$config['comm_nummers']}\" size=10>" );
	showRow( $lang['opt_sys_csort'], $lang['opt_sys_csortd'], makeDropDown( array ("DESC" => $lang['opt_sys_mminus'], "ASC" => $lang['opt_sys_mplus'] ), "save_con[comm_msort]", "{$config['comm_msort']}" ) );
	showRow( $lang['opt_sys_af'], $lang['opt_sys_afd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[flood_time]' value=\"{$config['flood_time']}\" size=10>" );
	showRow( $lang['opt_sys_aw'], $lang['opt_sys_awd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[auto_wrap]' value=\"{$config['auto_wrap']}\" size=10>" );
	showRow( $lang['opt_sys_ct'], "<a onClick=\"javascript:Help('date')\" class=main href=\"#\">$lang[opt_sys_and]</a>", "<input class=edit type=text style=\"text-align: center;\" name='save_con[timestamp_comment]' value=\"{$config['timestamp_comment']}\" size=40>" );
	showRow( $lang['opt_sys_asc'], $lang['opt_sys_ascd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_search_link]", "{$config['allow_search_link']}" ) );
	showRow( $lang['opt_sys_cmail'], $lang['opt_sys_cmaild'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[mail_comments]", "{$config['mail_comments']}" ) );
	showRow( $lang['opt_sys_wdcom'], $lang['opt_sys_wdscomd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_comments_wysiwyg]", "{$config['allow_comments_wysiwyg']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="optimisation"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_dch']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_search'], $lang['opt_sys_searchd'], makeDropDown( array ("1" => $lang['opt_sys_advance'], "0" => $lang['opt_sys_simple'] ), "save_con[full_search]", "{$config['full_search']}" ) );
	showRow( $lang['opt_sys_ur'], $lang['opt_sys_urd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_registration]", "{$config['allow_registration']}" ) );
	showRow( $lang['opt_sys_cac'], $lang['opt_sys_cad'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_cache]", "{$config['allow_cache']}" ) );
	showRow( $lang['opt_sys_multiple'], $lang['opt_sys_multipled'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_multi_category]", "{$config['allow_multi_category']}" ) );
	showRow( $lang['opt_sys_related'], $lang['opt_sys_relatedd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[related_news]", "{$config['related_news']}" ) );
	showRow( $lang['opt_sys_nodate'], $lang['opt_sys_nodated'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[no_date]", "{$config['no_date']}" ) );
	showRow( $lang['opt_sys_afix'], $lang['opt_sys_afixd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_fixed]", "{$config['allow_fixed']}" ) );
	
	showRow( $lang['opt_sys_sbar'], $lang['opt_sys_sbard'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[speedbar]", "{$config['speedbar']}" ) );
	showRow( $lang['opt_sys_ban'], $lang['opt_sys_band'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_banner]", "{$config['allow_banner']}" ) );
	showRow( $lang['opt_sys_cmod'], $lang['opt_sys_cmodd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_cmod]", "{$config['allow_cmod']}" ) );
	showRow( $lang['opt_sys_voc'], $lang['opt_sys_vocd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_votes]", "{$config['allow_votes']}" ) );
	showRow( $lang['opt_sys_toc'], $lang['opt_sys_tocd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_topnews]", "{$config['allow_topnews']}" ) );
	showRow( $lang['opt_sys_rn'], $lang['opt_sys_rnd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_read_count]", "{$config['allow_read_count']}" ) );
	showRow( $lang['cache_c'], $lang['cache_cd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[cache_count]", "{$config['cache_count']}" ) );
	showRow( $lang['opt_sys_dk'], $lang['opt_sys_dkd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_calendar]", "{$config['allow_calendar']}" ) );
	showRow( $lang['opt_sys_da'], $lang['opt_sys_dad'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_archives]", "{$config['allow_archives']}" ) );
	showRow( $lang['opt_sys_inform'], $lang['opt_sys_informd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[rss_informer]", "{$config['rss_informer']}" ) );
	showRow( $lang['opt_sys_tags'], $lang['opt_sys_tagsd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_tags]", "{$config['allow_tags']}" ) );
	showRow( $lang['opt_sys_change_s'], $lang['opt_sys_change_sd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_change_sort]", "{$config['allow_change_sort']}" ) );
	showRow( $lang['opt_sys_ajax'], $lang['opt_sys_ajaxd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[ajax]", "{$config['ajax']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="files"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_filesys']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_file'], $lang['opt_sys_filed'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[files_allow]", "{$config['files_allow']}" ) );
	showRow( $lang['opt_sys_file1'], $lang['opt_sys_file1d'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[files_type]' value=\"{$config['files_type']}\" size=40>" );
	
	showRow( $lang['opt_sys_maxfile'], $lang['opt_sys_maxfiled'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_file_size]' value=\"{$config['max_file_size']}\" size=10>" );
	showRow( $lang['opt_sys_maxfilec'], $lang['opt_sys_maxfilecd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_file_count]' value=\"{$config['max_file_count']}\" size=10>" );
	
	showRow( $lang['opt_sys_file4'], $lang['opt_sys_file4d'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[files_force]", "{$config['files_force']}" ) );
	showRow( $lang['opt_sys_file5'], $lang['opt_sys_file5d'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[files_max_speed]' value=\"{$config['files_max_speed']}\" size=10>" );
	showRow( $lang['opt_sys_file3'], $lang['opt_sys_file3d'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[files_antileech]", "{$config['files_antileech']}" ) );
	showRow( $lang['opt_sys_file2'], $lang['opt_sys_file2d'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[files_count]", "{$config['files_count']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="mail"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_mail']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	showRow( $lang['opt_sys_amail'], $lang['opt_sys_amaild'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[admin_mail]' value='{$config['admin_mail']}' size=30>" );
	
	showRow( $lang['opt_sys_mm'], $lang['opt_sys_mmd'], makeDropDown( array ("php" => "PHP Mail()", "smtp" => "SMTP" ), "save_con[mail_metod]", "{$config['mail_metod']}" ) );
	
	showRow( $lang['opt_sys_smtph'], $lang['opt_sys_smtphd'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[smtp_host]' value=\"{$config['smtp_host']}\" size=30>" );
	showRow( $lang['opt_sys_smtpp'], $lang['opt_sys_smtppd'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[smtp_port]' value=\"{$config['smtp_port']}\" size=30>" );
	showRow( $lang['opt_sys_smtup'], $lang['opt_sys_smtpud'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[smtp_user]' value=\"{$config['smtp_user']}\" size=30>" );
	showRow( $lang['opt_sys_smtupp'], $lang['opt_sys_smtpupd'], "<input class=edit type=text style=\"text-align: center;\" name='save_con[smtp_pass]' value=\"{$config['smtp_pass']}\" size=30>" );
	
	showRow( $lang['opt_sys_mbcc'], $lang['opt_sys_mbccd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[mail_bcc]", "{$config['mail_bcc']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="users"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_uch']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	showRow( $lang['opt_sys_reggroup'], $lang['opt_sys_reggroupd'], makeDropDown( $sys_group_arr, "save_con[reg_group]", $config['reg_group'] ) );
	
	showRow( $lang['opt_sys_ut'], $lang['opt_sys_utd'], makeDropDown( array ("0" => $lang['opt_sys_reg'], "1" => $lang['opt_sys_reg_1'] ), "save_con[registration_type]", "{$config['registration_type']}" ) );
	showRow( $lang['opt_sys_rules'], $lang['opt_sys_rulesd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[registration_rules]", "{$config['registration_rules']}" ) );
	
	showRow( $lang['opt_sys_code'], $lang['opt_sys_coded'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_sec_code]", "{$config['allow_sec_code']}" ) );
	showRow( $lang['opt_sys_sc'], $lang['opt_sys_scd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_skin_change]", "{$config['allow_skin_change']}" ) );
	showRow( $lang['opt_sys_pmail'], $lang['opt_sys_pmaild'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[mail_pm]", "{$config['mail_pm']}" ) );
	
	showRow( $lang['opt_sys_um'], $lang['opt_sys_umd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_users]' value=\"{$config['max_users']}\" size=10>" );
	showRow( $lang['opt_sys_ud'], $lang['opt_sys_udd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_users_day]' value=\"{$config['max_users_day']}\" size=10>" );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
<tr style='display:none' id="imagesconf"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_sys_ich']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_maxside'], $lang['opt_sys_maxsided'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_up_side]' value=\"{$config['max_up_side']}\" size=10>" );
	showRow( $lang['opt_sys_maxsize'], $lang['opt_sys_maxsized'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_up_size]' value=\"{$config['max_up_size']}\" size=10>" );
	showRow( $lang['opt_sys_dim'], $lang['opt_sys_dimd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_image_days]' value=\"{$config['max_image_days']}\" size=10>" );
	showRow( $lang['opt_sys_iw'], $lang['opt_sys_iwd'], makeDropDown( array ("yes" => $lang['opt_sys_yes'], "no" => $lang['opt_sys_no'] ), "save_con[allow_watermark]", "{$config['allow_watermark']}" ) );
	showRow( $lang['opt_sys_im'], $lang['opt_sys_imd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_watermark]' value=\"{$config['max_watermark']}\" size=10>" );
	showRow( $lang['opt_sys_ia'], $lang['opt_sys_iad'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[max_image]' value=\"{$config['max_image']}\" size=10>" );
	showRow( $lang['opt_sys_ij'], $lang['opt_sys_ijd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[jpeg_quality]' value=\"{$config['jpeg_quality']}\" size=10>" );
	showRow( $lang['opt_sys_imw'], $lang['opt_sys_imwd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[tag_img_width]' value=\"{$config['tag_img_width']}\" size=10>" );
	showRow( $lang['opt_sys_flvw'], $lang['opt_sys_flvwd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[flv_watermark]", "{$config['flv_watermark']}" ) );
	showRow( $lang['opt_sys_dimm'], $lang['opt_sys_dimmd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[thumb_dimming]", "{$config['thumb_dimming']}" ) );
	showRow( $lang['opt_sys_gall'], $lang['opt_sys_galld'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[thumb_gallery]", "{$config['thumb_gallery']}" ) );
	
	echo "</table></td></tr>";


	echo <<<HTML
<tr style='display:none' id="smartphone"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_smartphone']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_smart'], $lang['opt_sys_smartd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_smartphone]", "{$config['allow_smartphone']}" ) );
	showRow( $lang['opt_sys_sm_im'], $lang['opt_sys_sm_imd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_smart_images]", "{$config['allow_smart_images']}" ) );
	showRow( $lang['opt_sys_sm_iv'], $lang['opt_sys_sm_ivd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_smart_video]", "{$config['allow_smart_video']}" ) );
	showRow( $lang['opt_sys_sm_fm'], $lang['opt_sys_sm_fmd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_smart_format]", "{$config['allow_smart_format']}" ) );
	
	echo "</table></td></tr>";

	
	echo <<<HTML
<tr style='display:none' id="rss"><td>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['opt_rsssys']}</div></td>
    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	showRow( $lang['opt_sys_arss'], $lang['opt_sys_arssd'], makeDropDown( array ("1" => $lang['opt_sys_yes'], "0" => $lang['opt_sys_no'] ), "save_con[allow_rss]", "{$config['allow_rss']}" ) );
	showRow( $lang['opt_sys_trss'], $lang['opt_sys_trssd'], makeDropDown( array ("0" => $lang['opt_sys_rss_type_0'], "1" => $lang['opt_sys_rss_type_1'] ), "save_con[rss_mtype]", "{$config['rss_mtype']}" ) );
	showRow( $lang['opt_sys_nrss'], $lang['opt_sys_nrssd'], "<input class=edit type=text style=\"text-align: center;\"  name='save_con[rss_number]' value=\"{$config['rss_number']}\" size=10>" );
	showRow( $lang['opt_sys_frss'], $lang['opt_sys_frssd'], makeDropDown( array ("0" => $lang['opt_sys_rss_type_2'], "1" => $lang['opt_sys_rss_type_3'], "2" => $lang['opt_sys_rss_type_4'] ), "save_con[rss_format]", "{$config['rss_format']}" ) );
	
	echo "</table></td></tr>";
	
	echo <<<HTML
    <tr>
        <td style="padding-top:10px; padding-bottom:10px;padding-right:10px;"><input type=hidden name=mod value=options>
    <input type=hidden name=action value=dosavesyscon><input type="hidden" name="user_hash" value="$dle_login_hash" /><input type="submit" class="buttons" value="{$lang['user_save']}"></td>
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
} // ********************************************************************************
// ������ ��������
// ********************************************************************************
elseif( $action == "dosavesyscon" ) {
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$save_con = $_POST['save_con'];
	
	include_once ENGINE_DIR . '/classes/parse.class.php';
	$parse = new ParseFilter( Array (), Array (), 1, 1 );
	
	$save_con['offline_reason'] = $parse->process( stripslashes( trim( $save_con['offline_reason'] ) ) );
	$save_con['offline_reason'] = str_replace( '"', '&quot;', $parse->BB_Parse( $save_con['offline_reason'], false ) );
	
	$find[] = "'\r'";
	$replace[] = "";
	$find[] = "'\n'";
	$replace[] = "";
	
	$save_con['version_id'] = "0.0";
	if( $auto_detect_config ) $config['http_home_url'] = "";
	
	$save_con = $save_con + $config;
	
	if( $member_id['user_group'] != 1 ) {
		msg( "error", $lang['opt_denied'], $lang['opt_denied'] );
	}
	
	$handler = fopen( ENGINE_DIR . '/data/config.php', "w" );
	
	fwrite( $handler, "<?PHP \n\n//System Configurations\n\n\$config = array (\n\n" );
	foreach ( $save_con as $name => $value ) {
		
		if( $name != "offline_reason" ) {
			
			$value = trim( stripslashes( $value ) );
			$value = htmlspecialchars( $value);
			$value = preg_replace( $find, $replace, $value );
			
			$name = trim( stripslashes( $name ) );
			$name = htmlspecialchars( $name, ENT_QUOTES );
			$name = preg_replace( $find, $replace, $name );
		}
		
		$value = str_replace( "$", "&#036;", $value );
		$value = str_replace( "{", "&#123;", $value );
		$value = str_replace( "}", "&#125;", $value );
		
		$name = str_replace( "$", "&#036;", $name );
		$name = str_replace( "{", "&#123;", $name );
		$name = str_replace( "}", "&#125;", $name );
		
		fwrite( $handler, "'{$name}' => \"{$value}\",\n\n" );
	
	}
	fwrite( $handler, ");\n\n?>" );
	fclose( $handler );
	
	clear_cache();
	msg( "info", $lang['opt_sysok'], "$lang[opt_sysok_1]<br /><br /><a href=$PHP_SELF?mod=options&action=syscon>$lang[db_prev]</a>" );
}

?>