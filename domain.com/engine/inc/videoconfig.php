<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( $member_id['user_group'] != 1 ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

require_once (ENGINE_DIR . '/data/videoconfig.php');



if( $action == "save" ) {

	if( $member_id['user_group'] != 1 ) {
		msg( "error", $lang['opt_denied'], $lang['opt_denied'] );
	}

	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$save_con = $_POST['save_con'];
	
	$find[] = "'\r'";
	$replace[] = "";
	$find[] = "'\n'";
	$replace[] = "";
	
	$save_con = $save_con + $video_config;
	
	$handler = fopen( ENGINE_DIR . '/data/videoconfig.php', "w" );
	
	fwrite( $handler, "<?PHP \n\n//Videoplayers Configurations\n\n\$video_config = array (\n\n" );
	foreach ( $save_con as $name => $value ) {
		
		$value = trim( stripslashes( $value ) );
		$value = htmlspecialchars( $value, ENT_QUOTES);
		$value = preg_replace( $find, $replace, $value );
			
		$name = trim( stripslashes( $name ) );
		$name = htmlspecialchars( $name, ENT_QUOTES );
		$name = preg_replace( $find, $replace, $name );
		
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
	msg( "info", $lang['opt_sysok'], "$lang[opt_sysok_1]<br /><br /><a href=$PHP_SELF?mod=videoconfig>$lang[db_prev]</a>" );
}



echoheader( "home", $lang['db_info'] );

function showRow($title = "", $description = "", $field = "") {
	   echo "<tr>
       <td style=\"padding:4px\" class=\"option\">
        <b>$title</b><br /><span class=small>$description</span>
        <td width=\"50%\" align=\"left\" >
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


echo <<<HTML
<form action="$PHP_SELF?mod=videoconfig&action=save" name="conf" id="conf" method="post">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['vconf_title']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
HTML;

	showRow( $lang['vconf_widht'], $lang['vconf_widhtd'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[width]\" value=\"{$video_config['width']}\" size=10>" );
	showRow( $lang['vconf_height'], $lang['vconf_heightd'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[height]\" value=\"{$video_config['height']}\" size=10>" );
	showRow( $lang['vconf_play'], $lang['vconf_playd'], makeDropDown( array ("true" => $lang['opt_sys_yes'], "false" => $lang['opt_sys_no'] ), "save_con[play]", "{$video_config['play']}" ) );


echo <<<HTML
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['vconf_flv_title']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
HTML;

	showRow( $lang['vconf_bcbarcolor'], $lang['vconf_bcbarcolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[backgroundBarColor]\" value=\"{$video_config['backgroundBarColor']}\" size=20>" );
	showRow( $lang['vconf_btnscolor'], $lang['vconf_btnscolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[btnsColor]\" value=\"{$video_config['btnsColor']}\" size=20>" );
	showRow( $lang['vconf_outtxtcolor'], $lang['vconf_outtxtcolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[outputTxtColor]\" value=\"{$video_config['outputTxtColor']}\" size=20>" );
	showRow( $lang['vconf_outbkgbolor'], $lang['vconf_outbkgbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[outputBkgColor]\" value=\"{$video_config['outputBkgColor']}\" size=20>" );

	showRow( $lang['vconf_lbarbolor'], $lang['vconf_lbarbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[loadingBarColor]\" value=\"{$video_config['loadingBarColor']}\" size=20>" );
	showRow( $lang['vconf_lbckbolor'], $lang['vconf_lbckbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[loadingBackgroundColor]\" value=\"{$video_config['loadingBackgroundColor']}\" size=20>" );
	showRow( $lang['vconf_prbarbolor'], $lang['vconf_prbarbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[progressBarColor]\" value=\"{$video_config['progressBarColor']}\" size=20>" );
	showRow( $lang['vconf_vstbarbolor'], $lang['vconf_vstbarbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[volumeStatusBarColor]\" value=\"{$video_config['volumeStatusBarColor']}\" size=20>" );
	showRow( $lang['vconf_vbckbolor'], $lang['vconf_vbckbolord'], "<input class=edit type=text style=\"text-align: center;\" name=\"save_con[volumeBackgroundColor]\" value=\"{$video_config['volumeBackgroundColor']}\" size=20>" );


echo <<<HTML
    <tr>
        <td style="padding-top:10px; padding-bottom:10px;padding-right:10px;" colspan="2"><span class="small">{$lang['vconf_info']}</span></td>
    </tr>
    <tr>
        <td style="padding-top:10px; padding-bottom:10px;padding-right:10px;" colspan="2">
    <input type="hidden" name="user_hash" value="$dle_login_hash" /><input type="submit" class="buttons" value="{$lang['user_save']}"></td>
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
?>