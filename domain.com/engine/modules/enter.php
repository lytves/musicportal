<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if ( $is_logged ) header("location: {$config['http_home_url']}");
else {
  if ( intval($_REQUEST['fail']) == 1 ) msgbox( $lang['login_err'], $lang['login_err_1'] );
	$tpl->load_template( 'enter.tpl' );
	$tpl->compile( 'content' );
	$tpl->clear();
}
?>