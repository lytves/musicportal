<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
  die("Hacking attempt!");
}

if( ! $user_group[$member_id['user_group']]['admin_googlemap'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

if ($_POST['action'] == "create") {

	include_once ENGINE_DIR.'/classes/google.class.php';
	$map = new googlemap($config);

	$map->limit = intval($_POST['limit']);
	$map->news_priority = strip_tags(stripslashes($_POST['priority']));
	$map->stat_priority = strip_tags(stripslashes($_POST['stat_priority']));
	$map->cat_priority = strip_tags(stripslashes($_POST['cat_priority']));


	$sitemap = $map->build_map();

    $handler = fopen(ROOT_DIR. "/uploads/sitemap.xml", "wb+");
    fwrite($handler, $sitemap);
    fclose($handler);

	@chmod(ROOT_DIR. "/uploads/sitemap.xml", 0666);
}

echoheader("", "");


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
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['google_map']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding:2px;" colspan="2">
HTML;

	if(!@file_exists(ROOT_DIR. "/uploads/sitemap.xml")){ 

		echo $lang['no_google_map'];

	} else {

		$file_date = date("d.m.Y H:i", filectime(ROOT_DIR. "/uploads/sitemap.xml"));

		echo "<b>".$file_date."</b> ".$lang['google_map_info'];

		if ($config['allow_alt_url'] == "yes") {

			$map_link = $config['http_home_url']."sitemap.xml";

			echo " <a class=\"list\" href=\"".$map_link."\" target=\"_blank\">".$config['http_home_url']."sitemap.xml</a>";

		} else {

			$map_link = $config['http_home_url']."uploads/sitemap.xml";

			echo " <a class=\"list\" href=\"".$map_link."\" target=\"_blank\">".$config['http_home_url']."uploads/sitemap.xml</a>";

		}

		echo "<br /><br />[ <a class=\"list\" href=\"http://google.com/webmasters/sitemaps/ping?sitemap=".urlencode($map_link)."\" target=\"_blank\">".$lang['google_map_send']."</a> ]";

	}


echo <<<HTML
</td>
    <tr>
        <td style="padding:2px;" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding:2px;" nowrap>{$lang['google_nnum']}</td>
        <td style="padding:2px;" width="100%"><input class="edit" type="text" size="10" name="limit"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_g_num]}', this, event, '220px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:2px;" nowrap>{$lang['google_stat_priority']}</td>
        <td style="padding:2px;" width="100%"><input class="edit" type="text" size="10" name="stat_priority" value="0.5"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_g_priority]}', this, event, '220px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:2px;" nowrap>{$lang['google_priority']}</td>
        <td style="padding:2px;" width="100%"><input class="edit" type="text" size="10" name="priority" value="0.6"></td>
    </tr>
    <tr>
        <td style="padding:2px;" nowrap>{$lang['google_cat_priority']}</td>
        <td style="padding:2px;" width="100%"><input class="edit" type="text" size="10" name="cat_priority" value="0.7"></td>
    </tr>
    <tr>
        <td style="padding:2px;" colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td style="padding:2px;" colspan="2"><input type="submit" class="buttons" value="{$lang['google_create']}" style="width:250px;"><input type="hidden" name="action" value="create"></td>
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['google_main']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;">{$lang['google_info']}</td>
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


echofooter();
?>