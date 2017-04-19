<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

echoheader( "home", "" );

$config['max_users_day'] = intval( $config['max_users_day'] );

if( $_GET['action'] == "clearsubscribe" ) {

	$db->query("TRUNCATE TABLE " . PREFIX . "_subscribe");

}

if( $_GET['action'] == "clear" ) {
	
	@unlink( ENGINE_DIR . '/cache/system/usergroup.php' );
	@unlink( ENGINE_DIR . '/cache/system/vote.php' );
	@unlink( ENGINE_DIR . '/cache/system/banners.php' );
	@unlink( ENGINE_DIR . '/cache/system/category.php' );
	@unlink( ENGINE_DIR . '/cache/system/banned.php' );
	@unlink( ENGINE_DIR . '/cache/system/cron.php' );
	@unlink( ENGINE_DIR . '/cache/system/informers.php' );
	
	clear_cache();
}

if( $_POST['action'] == "send_notice" ) {
	
	$row = $db->super_query( "SELECT id FROM " . PREFIX . "_notice WHERE user_id = '{$member_id['user_id']}'" );
	
	$notice = $db->safesql( $_POST['notice'] );
	
	if( $row['id'] ) {
		
		$db->query( "UPDATE " . PREFIX . "_notice SET notice='{$notice}' WHERE user_id = '{$member_id['user_id']}'" );
	
	} else {
		
		$db->query( "INSERT INTO " . PREFIX . "_notice (user_id, notice) values ('{$member_id['user_id']}', '$notice')" );
	
	}

}

// ===============================================================
// <<-- Проверка GD  -->>
// ===============================================================
function gdversion() {
	static $gd_version_number = null;
	if( $gd_version_number === null ) {
		ob_start();
		phpinfo( 8 );
		$module_info = ob_get_contents();
		ob_end_clean();
		if( preg_match( "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches ) ) {
			$gdversion_h = $matches[1];
		} else {
			$gdversion_h = 0;
		}
	}
	return $gdversion_h;
}

$maxmemory = (@ini_get( 'memory_limit' ) != '') ? @ini_get( 'memory_limit' ) : $lang['undefined'];
$disabledfunctions = (strlen( ini_get( 'disable_functions' ) ) > 1) ? @ini_get( 'disable_functions' ) : $lang['undefined'];
$disabledfunctions = str_replace( ",", ", ", $disabledfunctions );
$safemode = (@ini_get( 'safe_mode' ) == 1) ? $lang['safe_mode_on'] : $lang['safe_mode_off'];
$licence = "Тип лицензии и версию видно в D:\..\скрипты и движки\dle\ДВИЖКИ\..";
$offline = ($config['site_offline'] == "no") ? $lang['safe_mode_on'] : "<font color=\"red\">" . $lang['safe_mode_off'] . "</font>";

if( function_exists( 'apache_get_modules' ) ) {
	if( array_search( 'mod_rewrite', apache_get_modules() ) ) {
		$mod_rewrite = $lang['safe_mode_on'];
	} else {
		$mod_rewrite = "<font color=\"red\">" . $lang['safe_mode_off'] . "</font>";
	}
} else {
	$mod_rewrite = $lang['undefined'];
}
$os_version = @php_uname( "s" ) . " " . @php_uname( "r" );
$phpv = phpversion();
$gdversion = gdversion();
$maxupload = str_replace( array ('M', 'm' ), '', @ini_get( 'upload_max_filesize' ) );
$maxupload = formatsize( $maxupload * 1024 * 1024 );

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_post" );
$stats_news = $row['count'];

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_subscribe" );
$count_subscribe = $row['count'];

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_comments" );
$count_comments = $row['count'];

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_comments WHERE approve ='0'" );
$count_c_app = $row['count'];

if( $count_c_app ) {
	
	$count_c_app = $count_c_app . " [ <a href=\"?mod=cmoderation\">{$lang['stat_cmod_link']}</a> ]";

}

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . USERPREFIX . "_users" );
$stats_users = $row['count'];

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . USERPREFIX . "_users where banned='yes'" );
$stats_banned = $row['count'];

$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_post where approve = '0'" );
$approve = $row['count'];

if( $approve and $user_group[$member_id['user_group']]['allow_all_edit'] ) {
	
	$approve = $approve . " [ <a href=\"?mod=editnews&action=list&news_status=2\">{$lang['stat_medit_link']}</a> ]";

}

$db->query( "SHOW TABLE STATUS FROM `" . DBNAME . "`" );
$mysql_size = 0;
while ( $r = $db->get_array() ) {
	if( strpos( $r['Name'], PREFIX . "_" ) !== false ) $mysql_size += $r['Data_length'] + $r['Index_length'];
}
$db->free();

$mysql_size = formatsize( $mysql_size );

function dirsize($directory) {
	
	if( ! is_dir( $directory ) ) return - 1;
	
	$size = 0;
	
	if( $DIR = opendir( $directory ) ) {
		
		while ( ($dirfile = readdir( $DIR )) !== false ) {
			
			if( @is_link( $directory . '/' . $dirfile ) || $dirfile == '.' || $dirfile == '..' ) continue;
			
			if( @is_file( $directory . '/' . $dirfile ) ) $size += filesize( $directory . '/' . $dirfile );
			
			else if( @is_dir( $directory . '/' . $dirfile ) ) {
				
				$dirSize = dirsize( $directory . '/' . $dirfile );
				if( $dirSize >= 0 ) $size += $dirSize;
				else return - 1;
			
			}
		
		}
		
		closedir( $DIR );
	
	}
	
	return $size;

}

$cache_size = formatsize( dirsize( "engine/cache" ) );

$dfs = @disk_free_space( "." );
$freespace = formatsize( $dfs );

$dfs2 = @disk_free_space( "/home2/mp3base/" );
$freespace2 = formatsize( $dfs2 );

if( $member_id['user_group'] == 1 ) {
	
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['main_quick']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="50%">
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/uset.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=editusers&action=list"><h3>{$lang['opt_user']}</h3>{$lang['opt_userc']}</a></div></td>
    </tr>
</table>
</td>
        <td>
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/ads.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=banners"><h3>{$lang['opt_banner']}</h3>{$lang['opt_bannerc']}</a></div></td>
    </tr>
</table>
</td>
    </tr>
    <tr>
        <td>
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/tools.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=options&action=syscon"><h3>{$lang['opt_all']}</h3>{$lang['opt_allc']}</a></div></td>
    </tr>
</table>
</td>
        <td>
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/nset.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=newsletter"><h3>{$lang['main_newsl']}</h3>{$lang['main_newslc']}</a></div></td>
    </tr>
</table>
</td>
    </tr>
    <tr>
        <td>
<table width="100%">
    <tr>
          <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/clean.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=clean"><h3>{$lang['opt_clean']}</h3>{$lang['opt_cleanc']}</a></div></td>
    </tr>
</table>
</td>
        <td>
<table width="100%">
    <tr>
    <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/mservice_dle82.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=mservice"><h3>DleMusic Service by odmin</h3>Управление публикациями, категориями и настройками музыкального архива сайта</a></div></td>
     </tr>
</table>
</td>
    </tr>
    <tr>
        <td>
<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/shield.png" border="0"></td>
        <td valign="middle"><div class="quick"><a onclick="check_files('lokal'); return false;" href="#"><h3>{$lang['mod_anti']}</h3>{$lang['anti_descr']}</a></div></td>
    </tr>
</table>
        <td>

<table width="100%">
    <tr>
        <td width="70" height="70" valign="middle" align="center" style="padding-top:5px;padding-bottom:5px;"><img src="engine/skins/images/next.png" border="0"></td>
        <td valign="middle"><div class="quick"><a href="$PHP_SELF?mod=options&action=options"><h3>{$lang['opt_all_rublik']}</h3>{$lang['opt_all_rublikc']}</a></div></td>
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

} else {

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['main_quick']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="50%"><div class="quick"><a href="$PHP_SELF?mod=options&action=personal"><img src="engine/skins/images/pset.png" border="0" align="left"><h3>{$lang['opt_priv']}</h3>{$lang['opt_privc']}</a></div></td>
        <td><div class="quick"><a href="$PHP_SELF?mod=options&action=options"><img src="engine/skins/images/next.png" border="0" align="left"><h3>{$lang['opt_all_rublik']}</h3>{$lang['opt_all_rublikc']}</a></div></td>
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

}

if( $member_id['user_group'] == 1 ) {
	
	echo <<<HTML
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<script language="javascript" type="text/javascript">
<!--
	var ajax = new dle_ajax();

function check_files ( folder ){


	if (folder == "snap") {

	    if (!confirm('{$lang['anti_snapalert']}')) { return false; }

	}

	document.getElementById( 'main_title' ).innerHTML = '{$lang['anti_title']}';
	document.getElementById( 'main_box' ).innerHTML = '{$lang['anti_box']}';

	var varsString = "folder=" + folder;

	ajax.setVar("key", '{$config['key']}');

	ajax.requestFile = "engine/ajax/antivirus.php";
	ajax.element = 'main_box';
	ajax.method = 'POST';

	ajax.sendAJAX(varsString);

	return false;
}

function check_updates ( ){
	document.getElementById( 'main_title' ).innerHTML = '{$lang['anti_title']}';
	document.getElementById( 'main_box' ).innerHTML = '{$lang['dle_updatebox']}';

	var varsString = "versionid={$config['version_id']}";

	ajax.requestFile = "engine/ajax/updates.php";
	ajax.element = 'main_box';
	ajax.method = 'POST';

	ajax.sendAJAX(varsString);

	return false;
}
//-->
</script>
HTML;

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation" id="main_title">{$lang['stat_all']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<div id="main_box"><table width="100%">
    <tr>
        <td width="265" style="padding:2px;">{$lang['stat_allnews']}</td>
        <td>{$stats_news}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_mod']}</td>
        <td>{$approve}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_comments']}</td>
        <td>{$count_comments} [ <a href="{$config['http_home_url']}index.php?do=lastcomments" target="_blank">{$lang['last_comm']}</a> ]</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_cmod']}</td>
        <td>{$count_c_app}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_users']}</td>
        <td>{$stats_users}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_banned']}</td>
        <td><font color="red">{$stats_banned}</font></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_bd']}</td>
        <td>{$mysql_size}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['cache_size']}</td>
        <td>{$cache_size}</td>
    </tr>
</table>
HTML;

if( ! is_writable( ENGINE_DIR . "/cache/" ) or ! is_writable( ENGINE_DIR . "/cache/system/" ) ) {
	echo "<br /><table width=\"100%\" align=\"center\"><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
       $lang[stat_cache]
          </td></tr><tr><td>&nbsp;</td></tr></table>";

}

if( $member_id['user_group'] == 1 ) {
	
	echo "<br /><input onclick=\"check_updates(); return false;\" class=\"edit\" style=\"width:220px;\" type=\"button\" value=\"{$lang['dle_udate']}\">&nbsp;<a href=\"?mod=main&action=clear\"><input onclick=\"document.location='?mod=main&action=clear'\" class=\"edit\" style=\"width:150px;\" type=\"button\" value=\"{$lang['btn_clearcache']}\"></a>";

	if ($count_subscribe) echo "&nbsp;<input onclick=\"var agree=confirm('{$lang['confirm_action']}'); if (agree) document.location='?mod=main&action=clearsubscribe'\" class=\"edit\" style=\"width:300px;\" type=\"button\" value=\"{$lang['btn_clearsubscribe']}\">";

	echo "<br />";
}

echo <<<HTML
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
</div>
HTML;

if( $member_id['user_group'] == 1 ) {
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['stat_auto']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;">{$lang['dle_version']}</td>
        <td>{$config['version_id']}</td>
    </tr>
    <tr>
        <td width="265" style="padding:2px;">{$lang['licence_info']}</td>
        <td>{$licence}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['site_status']}</td>
        <td>{$offline}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_os']}</td>
        <td>{$os_version}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_php']}</td>
        <td>{$phpv}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_mysql']}</td>
        <td>{$db->mysql_version} <b>{$db->mysql_extend}</b></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_gd']}</td>
        <td>{$gdversion}</td>
    </tr>
    <tr>
        <td style="padding:2px;">Module mod_rewrite</td>
        <td>{$mod_rewrite}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_safemode']}</td>
        <td>{$safemode}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_maxmem']}</td>
        <td>{$maxmemory}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_func']}</td>
        <td>{$disabledfunctions}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['stat_maxfile']}</td>
        <td>{$maxupload}</td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['free_size']}</td>
        <td>root: {$freespace}, home2: {$freespace2}</td>
    </tr>
</table>
HTML;
	
	
	if( @file_exists( "install.php" ) ) {
		echo "<br /><table width=\"100%\" align=center><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
       $lang[stat_install]
          </td></tr><tr><td>&nbsp;</td></tr></table>";
	}
	
	if( $dfs and $dfs < 20240 ) {
		echo "<br /><table width=\"100%\" align=center><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
         $lang[stat_nofree]
         </td></tr><tr><td>&nbsp;</td></tr></table>";
	}

	if( !function_exists('iconv') ) {
		echo "<br /><table width=\"100%\" align=center><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
         {$lang['stat_not_min']} <b>iconv</b>
         </td></tr><tr><td>&nbsp;</td></tr></table>";
	}

	if( !@extension_loaded('xml') ) {
		echo "<br /><table width=\"100%\" align=center><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
         {$lang['stat_not_min']} <b>XML</b>
         </td></tr><tr><td>&nbsp;</td></tr></table>";
	}

	if( !@extension_loaded('zlib') ) {
		echo "<br /><table width=\"100%\" align=center><tr><td style='padding:3px; border:1px dashed red; background-color:lightyellow;' class=main>
         {$lang['stat_not_min']} <b>ZLib</b>
         </td></tr><tr><td>&nbsp;</td></tr></table>";
	}
	
	echo <<<HTML
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

$row = $db->super_query( "SELECT notice FROM " . PREFIX . "_notice WHERE user_id = '{$member_id['user_id']}'" );

if( $row['notice'] == "" ) {
	
	$row['notice'] = $lang['main_no_notice'];

} else {
	
	$row['notice'] = htmlspecialchars( stripslashes( $row['notice'] ) );

}

echo <<<HTML
<form method="post" action="">
<input type="hidden" name="action" value="send_notice">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['main_notice']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td class="quick"><textarea name="notice" style="width:100%;height:200px;background-color:lightyellow;">{$row['notice']}</textarea>
		<div><input type="submit" class="buttons" value="{$lang['btn_send']}" style="width:100px;"></div>
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
</form>
HTML;

echofooter();
?>