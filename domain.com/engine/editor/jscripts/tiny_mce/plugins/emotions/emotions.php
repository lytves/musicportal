<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
define('DATALIFEENGINE', true);
define('ROOT_DIR', '../../../../../..');
define('ENGINE_DIR', '../../../../..');

error_reporting(7);
ini_set('display_errors', true);
ini_set('html_errors', false);


header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include ENGINE_DIR.'/data/config.php';

if ($config['http_home_url'] == "") {

	$config['http_home_url'] = explode("engine/editor/jscripts/tiny_mce/plugins/emotions/emotions.php", $_SERVER['PHP_SELF']);
	$config['http_home_url'] = reset($config['http_home_url']);
	$config['http_home_url'] = "http://".$_SERVER['HTTP_HOST'].$config['http_home_url'];

}

	$i = 0;
	$output = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr>";

    $smilies = explode(",", $config['smilies']);
    foreach($smilies as $smile)
    {
        $i++; $smile = trim($smile);

        $output .= "<td style=\"padding:5px;\" align=\"center\"><a href=\"#\" onClick=\"dle_smiley(' :$smile: '); return false;\"><img style=\"border: none;\" alt=\"$smile\" src=\"".$config['http_home_url']."engine/data/emoticons/$smile.gif\" /></a></td>";

		if ($i%5 == 0) $output .= "</tr><tr>";

    }

	$output .= "</tr></table>";

echo <<<HTML
<HTML>
<HEAD>
	<title>{#emotions_dlg.title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js?v=307"></script>
	<script type="text/javascript" src="js/emotions.js?v=307"></script>
	<base target="_self" />
<script language='javascript'>
    function dle_smiley(finalImage) {

		tinyMCEPopup.execCommand('mceInsertContent',false, finalImage);
		tinyMCEPopup.close();

	}
</script>
</HEAD>
<BODY bgcolor="#E9E8F2" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0">
{$output}
</BODY>
</HTML>
HTML;
?>
