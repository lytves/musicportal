<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting(7);
@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('DATALIFEENGINE', true);
define('ROOT_DIR', '../..');
define('ENGINE_DIR', '..');

include ENGINE_DIR.'/data/config.php';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/ajax/antivirus.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

require_once ENGINE_DIR.'/classes/mysql.php';
require_once ENGINE_DIR.'/data/dbconfig.php';
require_once ENGINE_DIR.'/inc/include/functions.inc.php';

$selected_language = $config['langs'];

if (isset( $_COOKIE['selected_language'] )) { 

	$_COOKIE['selected_language'] = trim(totranslit( $_COOKIE['selected_language'], false, false ));

	if ($_COOKIE['selected_language'] != "" AND @is_dir ( ROOT_DIR . '/language/' . $_COOKIE['selected_language'] )) {
		$selected_language = $_COOKIE['selected_language'];
	}

}

require_once ROOT_DIR.'/language/'.$selected_language.'/adminpanel.lng';

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR.'/modules/sitelogin.php';

if(($member_id['user_group'] != 1)) {die ("error");}

class antivirus
{
	var $bad_files       = array();
	var $snap_files      = array();
	var $track_files      = array();
	var $snap      		 = false;
	var $checked_folders = array();
	var $dir_split       = '/';

	var $cache_files       = array(
	"./engine/cache/system/usergroup.php",
	"./engine/cache/system/category.php",
	"./engine/cache/system/vote.php",
	"./engine/cache/system/banners.php",
	"./engine/cache/system/banned.php",
	"./engine/cache/system/cron.php",
	"./engine/cache/system/informers.php",
	"./engine/data/config.php",
	"./engine/data/videoconfig.php",
	"./engine/data/wordfilter.db.php",
	);

	var $good_files       = array(
	"./engine/ajax/vote.php",
	"./engine/ajax/find_relates.php",
	"./engine/ajax/calendar.php",
	"./engine/ajax/editcomments.php",
	"./engine/ajax/editnews.php",
	"./engine/ajax/favorites.php",
	"./engine/ajax/newsletter.php",
	"./engine/ajax/rating.php",
	"./engine/ajax/registration.php",
	"./engine/ajax/addcomments.php",
	"./engine/ajax/antivirus.php",
	"./engine/ajax/updates.php",
	"./engine/ajax/clean.php",
	"./engine/ajax/pages.php",
	"./engine/ajax/poll.php",
	"./engine/ajax/rss.php",
	"./engine/ajax/keywords.php",
	"./engine/ajax/pm.php",
	"./engine/ajax/bbcode.php",
	"./engine/ajax/upload.php",
	"./engine/ajax/typograf.php",
	"./engine/cache/system/usergroup.php",
	"./engine/cache/system/category.php",
	"./engine/cache/system/vote.php",
	"./engine/cache/system/banners.php",
	"./engine/cache/system/banned.php",
	"./engine/cache/system/cron.php",
	"./engine/cache/system/informers.php",
	"./engine/data/config.php",
	"./engine/data/videoconfig.php",
	"./engine/data/dbconfig.php",
	"./engine/data/wordfilter.db.php",
	"./engine/skins/default.skin.php",
	"./engine/editor/fullnews.php",
	"./engine/editor/fullsite.php",
	"./engine/editor/newsletter.php",
	"./engine/editor/shortnews.php",
	"./engine/editor/shortsite.php",
	"./engine/editor/comments.php",
	"./engine/editor/jscripts/tiny_mce/tiny_mce_gzip.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/rpc.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/includes/general.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/config.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/EnchantSpell.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/utils/Logger.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/utils/JSON.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/SpellChecker.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/PSpellShell.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/PSpell.php",
	"./engine/editor/jscripts/tiny_mce/plugins/spellchecker/classes/GoogleSpell.php",
	"./engine/editor/jscripts/tiny_mce/plugins/emotions/emotions.php",
	"./engine/editor/jscripts/tiny_mce/plugins/typograf/handler.php",
	"./engine/editor/jscripts/tiny_mce/plugins/typograf/typographus.php",
	"./engine/editor/static.php",
	"./engine/modules/vote.php",
	"./engine/modules/addnews.php",
	"./engine/modules/antibot.php",
	"./engine/modules/banned.php",
	"./engine/modules/bbcode.php",
	"./engine/modules/calendar.php",
	"./engine/modules/comments.php",
	"./engine/modules/favorites.php",
	"./engine/modules/feedback.php",
	"./engine/modules/functions.php",
	"./engine/modules/gzip.php",
	"./engine/modules/imagepreview.php",
	"./engine/modules/lastcomments.php",
	"./engine/modules/lostpassword.php",
	"./engine/modules/offline.php",
	"./engine/modules/pm.php",
	"./engine/modules/pm_alert.php",
	"./engine/modules/profile.php",
	"./engine/modules/register.php",
	"./engine/modules/search.php",
	"./engine/modules/show.custom.php",
	"./engine/modules/show.full.php",
	"./engine/modules/show.short.php",
	"./engine/modules/sitelogin.php",
	"./engine/modules/static.php",
	"./engine/modules/stats.php",
	"./engine/modules/topnews.php",
	"./engine/modules/lastnews.php",
	"./engine/modules/addcomments.php",
	"./engine/modules/poll.php",
	"./engine/modules/cron.php",
	"./engine/modules/banners.php",
	"./engine/modules/rssinform.php",
	"./engine/modules/fullsearch.php",
	"./engine/modules/deletenews.php",
	"./engine/modules/tagscloud.php",
	"./engine/api/api.class.php",
	"./engine/inc/iptools.php",
	"./engine/classes/mysql.class.php",
	"./engine/classes/mysqli.class.php",
	"./engine/classes/mail.class.php",
	"./engine/inc/mass_user_actions.php",
	"./engine/inc/addvote.php",
	"./engine/inc/blockip.php",
	"./engine/inc/categories.php",
	"./engine/inc/dboption.php",
	"./engine/inc/dumper.php",
	"./engine/inc/editnews.php",
	"./engine/inc/editusers.php",
	"./engine/inc/editvote.php",
	"./engine/inc/email.php",
	"./engine/inc/files.php",
	"./engine/inc/include/functions.inc.php",
	"./engine/inc/help.php",
	"./engine/inc/include/inserttag.php",
	"./engine/inc/main.php",
	"./engine/inc/videoconfig.php",
	"./engine/classes/thumb.class.php",
	"./engine/classes/comments.class.php",
	"./engine/inc/massactions.php",
	"./engine/classes/mysql.php",
	"./engine/inc/newsletter.php",
	"./engine/inc/options.php",
	"./engine/classes/parse.class.php",
	"./engine/inc/preview.php",
	"./engine/inc/static.php",
	"./engine/classes/templates.class.php",
	"./engine/inc/templates.php",
	"./engine/inc/userfields.php",
	"./engine/inc/usergroup.php",
	"./engine/inc/wordfilter.php",
	"./engine/inc/xfields.php",
	"./engine/inc/addnews.php",
	"./engine/inc/comments.php",
	"./engine/inc/banners.php",
	"./engine/inc/clean.php",
	"./engine/inc/rss.php",
	"./engine/inc/mass_static_actions.php",
	"./engine/inc/include/init.php",
	"./engine/classes/rss.class.php",
	"./engine/inc/search.php",
	"./engine/classes/download.class.php",
	"./engine/inc/cmoderation.php",
	"./engine/inc/rssinform.php",
	"./engine/classes/google.class.php",
	"./engine/inc/googlemap.php",
	"./engine/preview.php",
	"./engine/init.php",
	"./engine/opensearch.php",
	"./engine/engine.php",
	"./engine/images.php",
	"./engine/print.php",
	"./engine/rss.php",
	"./engine/download.php",
	"./engine/go.php",
	"./admin.php",
	"./index.php",
	"./autobackup.php",
	);

	function antivirus ()
	{
		if(@file_exists(ENGINE_DIR.'/data/snap.db')) {
  			$filecontents = file(ENGINE_DIR.'/data/snap.db');

	    foreach ($filecontents as $name => $value) {
    	  $filecontents[$name] = explode("|", trim($value));
    	    $this->track_files[$filecontents[$name][0]] = $filecontents[$name][1];
	    }
			$this->snap = true;

		}

	}
	
	function scan_files( $dir, $snap = false )
	{
		$this->checked_folders[] = $dir . $this->dir_split . $file;
	
		if ( $dh = @opendir( $dir ) )
		{
			while ( false !== ( $file = readdir($dh) ) )
			{
				if ( $file == '.' or $file == '..' or $file == '.svn' or $file == '.DS_store' )
				{
					continue;
				}
		
				if ( is_dir( $dir . $this->dir_split . $file ) )
				{

					if ($dir != ROOT_DIR)
					$this->scan_files( $dir . $this->dir_split . $file, $snap );
				}
				else
				{

					if ($this->snap OR $snap) $templates = "|tpl"; else $templates = "";

					if ( preg_match( "#.*\.(php|cgi|pl|perl|php3|php4|php5|php6".$templates.")#i", $file ) )
					{

					  $folder = str_replace("../..", ".",$dir);
					  $file_size = filesize($dir . $this->dir_split . $file);
					  $file_crc = strtoupper(dechex(crc32(file_get_contents($dir . $this->dir_split . $file))));
					  $file_date = date("d.m.Y H:i:s", filectime($dir . $this->dir_split . $file));

					  if ($snap) {

						$this->snap_files[] = array( 'file_path' => $folder . $this->dir_split . $file,
													 'file_crc' => $file_crc );


                      } else {

						if ($this->snap) {


							if ($this->track_files[$folder . $this->dir_split . $file] != $file_crc AND !in_array($folder . $this->dir_split . $file, $this->cache_files))
							$this->bad_files[] = array( 'file_path' => $folder . $this->dir_split . $file,
													'file_name' => $file,
													'file_date' => $file_date,
													'type' => 1,
													'file_size' => $file_size );

					    } else { 

						 if (!in_array($folder . $this->dir_split . $file, $this->good_files))
						 $this->bad_files[] = array( 'file_path' => $folder . $this->dir_split . $file,
													'file_name' => $file,
													'file_date' => $file_date,
													'type' => 0,
													'file_size' => $file_size ); 

						}

					  }
					}
				}
			}
		}
	}
}

$antivirus = new antivirus();

if ($_REQUEST['folder'] == "lokal"){

	$antivirus->scan_files( ROOT_DIR."/backup" );
	$antivirus->scan_files( ROOT_DIR."/engine" );
	$antivirus->scan_files( ROOT_DIR."/language" );
	$antivirus->scan_files( ROOT_DIR."/templates" );
	$antivirus->scan_files( ROOT_DIR."/uploads" );
	$antivirus->scan_files( ROOT_DIR."/upgrade" );
	$antivirus->scan_files( ROOT_DIR );

} elseif ($_REQUEST['folder'] == "snap") {

	$antivirus->scan_files( ROOT_DIR."/backup", true );
	$antivirus->scan_files( ROOT_DIR."/engine", true );
	$antivirus->scan_files( ROOT_DIR."/language", true );
	$antivirus->scan_files( ROOT_DIR."/templates", true );
	$antivirus->scan_files( ROOT_DIR."/uploads", true );
	$antivirus->scan_files( ROOT_DIR."/upgrade", true );
	$antivirus->scan_files( ROOT_DIR, true );

	$filecontents = "";

    foreach( $antivirus->snap_files as $idx => $data )
    {
		$filecontents .= $data['file_path']."|".$data['file_crc']."\r\n";
    }

    $filehandle = fopen(ENGINE_DIR.'/data/snap.db', "w+");
    fwrite($filehandle, $filecontents);
    fclose($filehandle);
	@chmod(ENGINE_DIR.'/data/snap.db', 0666);

} else {

	$antivirus->snap = false;
	$antivirus->scan_files( ROOT_DIR."/backup" );
	$antivirus->scan_files( ROOT_DIR."/engine" );
	$antivirus->scan_files( ROOT_DIR."/language" );
	$antivirus->scan_files( ROOT_DIR."/templates" );
	$antivirus->scan_files( ROOT_DIR."/uploads" );
	$antivirus->scan_files( ROOT_DIR."/upgrade" );
	$antivirus->scan_files( ROOT_DIR );

}

@header("Content-type: text/css; charset=".$config['charset']);

if (count($antivirus->bad_files)) {

echo <<<HTML
<table width="100%">
    <tr>
        <td colspan="2" style="padding:2px;">{$lang['anti_result']}</td>
    </tr>
    <tr>
        <td width="350" style="padding:2px;">{$lang['anti_file']}</td>
        <td width="100">{$lang['anti_size']}</td>
        <td width="150">{$lang['addnews_date']}</td>
        <td>&nbsp;</td>
    </tr>
HTML;

  foreach( $antivirus->bad_files as $idx => $data )
  { 

	if ($data['file_size'] < 50000) $color = "<font color=\"green\">";
	elseif ($data['file_size'] < 100000) $color = "<font color=\"blue\">";
	else $color = "<font color=\"red\">";

	$data['file_size'] = formatsize ($data['file_size']);
	if ($data['type']) $type = $lang['anti_modified']; else $type = $lang['anti_not'];

echo <<<HTML
    <tr>
        <td style="padding:2px;">{$color}{$data['file_path']}</font></td>
        <td>{$color}{$data['file_size']}</font></td>
        <td>{$color}{$data['file_date']}</font></td>
        <td>{$color}{$type}</font></td>
    </tr>
	<tr><td background="engine/skins/images/mline.gif" height=1 colspan=4></td></tr>
HTML;
  }
}
elseif ($_REQUEST['folder'] == "snap") {

echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;" colspan="3">{$lang['anti_creates']}</td>
    </tr>
HTML;

}
else {

echo <<<HTML
<table width="100%">
    <tr>
        <td style="padding:2px;" colspan="3">{$lang['anti_notfound']}</td>
    </tr>
HTML;

}

echo <<<HTML
    <tr>
        <td style="padding:2px;" colspan=3><input onclick="check_files('global'); return false;" type="button" class="buttons" style="width:250px;" value="{$lang['anti_global']}"> <input onclick="check_files('snap'); return false;" type="button" class="buttons" style="width:150px;" value="{$lang['anti_snap']}"></td>
    </tr></table>
HTML;
?>