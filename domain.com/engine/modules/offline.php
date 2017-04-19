<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

if ($member_id['user_group'] == '1' OR $user_group[$member_id['user_group']]['allow_offline']) {

	$metatags['title'] .= " (Offline)";

} else {

	$tpl->load_template('offline.tpl');

	$tpl->set('{THEME}', $config['http_home_url'].'templates/'.$config['skin']);

	$config['offline_reason'] = str_replace('&quot;', '"', $config['offline_reason']);

	$tpl->set('{reason}', $config['offline_reason']);

	$tpl->compile('main');

	echo $tpl->result['main'];

	die ();

}
?>