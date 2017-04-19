<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start ();
@ob_start ();
@ob_implicit_flush ( 0 );

if( !defined( 'E_DEPRECATED' ) ) {

	error_reporting ( E_ALL ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );

} else {

	error_reporting ( E_ALL ^ E_DEPRECATED ^ E_NOTICE );
	@ini_set ( 'error_reporting', E_ALL ^ E_DEPRECATED ^ E_NOTICE );

}

@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );

define ( 'DATALIFEENGINE', true );
define ( 'ROOT_DIR', dirname ( __FILE__ ) );
define ( 'ENGINE_DIR', ROOT_DIR . '/engine' );

//#################
$check_referer = true;
//#################

require_once (ENGINE_DIR . '/inc/include/init.php');

if ($is_loged_in == FALSE) {
	
	echoheader ( "home", "Login" );

	if( ! $handle = opendir( "./language" ) ) {
		die( "Невозможно открыть директорию ./data/language/" );
	}

	while ( false !== ($file = readdir( $handle )) ) {
		if( is_dir( ROOT_DIR . "/language/$file" ) and ($file != "." and $file != "..") ) {
			$sys_con_langs_arr[$file] = $file;
		}
	}
	closedir( $handle );

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

	$select_language = makeDropDown( $sys_con_langs_arr, "selected_language", $selected_language );
	
	echo <<<HTML
<form  name="login" action="" method="post"><input type="hidden" name="subaction" value="dologin">
<div style="padding-top:5px;">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['m_login']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="55" style="padding:5px;" rowspan="3"><img src="engine/skins/images/key.png" border="0"></td>
        <td width="140" style="padding:5px;">{$lang['user_name']}</td>
        <td><input class="edit" type="text" name="username" value='' size="20">&nbsp;&nbsp;{$result}</td>
    </tr>
    <tr>
        <td style="padding:5px;">{$lang['user_pass']}</td>
        <td><input class="edit" type="password" name="password" size="20">&nbsp;&nbsp;<input type="submit" class="edit" value="{$lang['b_login']}"></td>
    </tr>
    <tr>
        <td style="padding:5px;">{$lang['opt_sys_al']}</td>
        <td>{$select_language}</td>
    </tr>
</table>
<div class="hr_line"></div>
<div class="navigation">{$lang['index_inf']}</div>
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
	
	echofooter ();
	exit ();

} elseif ($is_loged_in == TRUE) {
	
	// ********************************************************************************
	// Include System Module
	// ********************************************************************************
	
	if ( !$mod ) {

		include (ENGINE_DIR . '/inc/main.php');

	} elseif ( @file_exists( ENGINE_DIR . '/inc/' . $mod . '.php' ) ) {
		
		include (ENGINE_DIR . '/inc/' . $mod . '.php');

	} else {
		$db->close ();
		msg ( "error", $lang['index_denied'], $lang['mod_not_found'] );
	}
}

$db->close ();

GzipOut ();
?>
