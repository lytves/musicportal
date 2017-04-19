<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/vote.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';

$_REQUEST['vote_skin'] = totranslit($_REQUEST['vote_skin'], false, false);

if( $_REQUEST['vote_skin'] ) {
	if( @is_dir( ROOT_DIR . '/templates/' . $_REQUEST['vote_skin'] ) ) {
		$config['skin'] = $_REQUEST['vote_skin'];
	}
}

if( $config["lang_" . $config['skin']] ) {
	
	include_once ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng';

} else {
	
	include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}
$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];

require_once ENGINE_DIR . '/classes/templates.class.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

$rid = intval( $_REQUEST['vote_id'] );
$vote_check = intval( $_REQUEST['vote_check'] );
$nick = $member_id['name'];
$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );
$vote_skin = $config['skin'];

$tpl = new dle_template( );
$tpl->dir = ROOT_DIR . '/templates/' . $vote_skin;
define( 'TEMPLATE_DIR', $tpl->dir );

if( $_REQUEST['vote_action'] == "vote" ) {
	
	if( $is_logged ) $row = $db->super_query( "SELECT count(*) as count FROM " . PREFIX . "_vote_result WHERE vote_id='$rid' AND name='$nick'" );
	else $row = $db->super_query( "SELECT count(*) as count FROM " . PREFIX . "_vote_result WHERE vote_id='$rid' AND ip='$_IP'" );
	
	if( ! $row['count'] and count( explode( ".", $_IP ) ) == 4 ) $is_voted = false;
	else $is_voted = true;
	
	if( $is_voted == false ) {
		
		if( ! $is_logged ) $nick = "guest";
		
		$db->query( "INSERT INTO " . PREFIX . "_vote_result (ip, name, vote_id, answer) VALUES ('$_IP', '$nick', '$rid', '$vote_check')" );
		
		$db->query( "UPDATE " . PREFIX . "_vote set vote_num=vote_num+1 where id='$rid'" );
		
		@unlink( ENGINE_DIR . '/cache/system/vote.php' );
	}
	
	$result = $db->super_query( "SELECT * FROM " . PREFIX . "_vote WHERE id='$rid'" );
	$title = stripslashes( $result['title'] );
	$body = stripslashes( $result['body'] );
	$body = explode( "<br />", $body );
	$max = $result['vote_num'];
	
	$db->query( "SELECT answer, count(*) as count FROM " . PREFIX . "_vote_result WHERE vote_id='$rid' GROUP BY answer" );
	$answer = array ();
	
	while ( $row = $db->get_row() ) {
		$answer[$row['answer']]['count'] = $row['count'];
	}

	$db->free();
	$pn = 0;
	
	for($i = 0; $i < sizeof( $body ); $i ++) {
		
		++ $pn;
		if( $pn > 5 ) $pn = 1;
		
		$num = $answer[$i]['count'];
		if( ! $num ) $num = 0;
		if( $max != 0 ) $proc = (100 * $num) / $max;
		else $proc = 0;
		$proc = round( $proc, 2 );
		
		$entry .= "<div class=\"vote\" align=\"left\">$body[$i] - $num ($proc%)</div>
		<div class=\"vote\" align=\"left\">
		<img src=\"{$config['http_home_url']}templates/{$vote_skin}/dleimages/poll{$pn}.gif\" height=\"10\" width=\"$proc%\" style=\"border:1px solid black\">
		</div>\n";
	}
	$entry = "<div id=\"dle-vote\">$entry</div>";
	
	$tpl->load_template( 'vote.tpl' );
	
	$tpl->set( '{list}', $entry );
	$tpl->set( '{vote_id}', $rid );
	$tpl->set( '{title}', $title );
	$tpl->set( '{votes}', $max );
	$tpl->set( '[voteresult]', '' );
	$tpl->set( '[/voteresult]', '' );
	$tpl->set_block( "'\\[votelist\\].*?\\[/votelist\\]'si", "" );
	$tpl->compile( 'vote' );
	$tpl->clear();

} elseif( $_REQUEST['vote_action'] == "results" ) {
	
	$result = $db->super_query( "SELECT * FROM " . PREFIX . "_vote WHERE id='$rid'" );
	$title = stripslashes( $result['title'] );
	$body = stripslashes( $result['body'] );
	$body = explode( "<br />", $body );
	$max = $result['vote_num'];
	
	$db->query( "SELECT answer, count(*) as count FROM " . PREFIX . "_vote_result WHERE vote_id='$rid' GROUP BY answer" );
	$answer = array ();
	
	while ( $row = $db->get_row() ) {
		$answer[$row['answer']]['count'] = $row['count'];
	}
	
	$db->free();
	$pn = 0;
	
	for($i = 0; $i < sizeof( $body ); $i ++) {
		
		$num = $answer[$i]['count'];
		
		++ $pn;
		if( $pn > 5 ) $pn = 1;
		
		if( ! $num ) $num = 0;
		
		if( $max != 0 ) $proc = (100 * $num) / $max;
		else $proc = 0;
		
		$proc = round( $proc, 2 );
		
		$entry .= "<div class=\"vote\" align=\"left\">$body[$i] - $num ($proc%)</div>
		<div class=\"vote\" align=\"left\">
		<img src=\"{$config['http_home_url']}templates/{$vote_skin}/dleimages/poll{$pn}.gif\" height=\"10\" width=\"".intval($proc)."%\" style=\"border:1px solid black\">
		</div>\n";
	}
	$entry = "<div id=\"dle-vote\">$entry</div>";
	
	$tpl->load_template( 'vote.tpl' );
	
	$tpl->set( '{list}', $entry );
	$tpl->set( '{vote_id}', $rid );
	$tpl->set( '{title}', $title );
	$tpl->set( '{votes}', $max );
	$tpl->set( '[voteresult]', '' );
	$tpl->set( '[/voteresult]', '' );
	$tpl->set_block( "'\\[votelist\\].*?\\[/votelist\\]'si", "" );
	$tpl->compile( 'vote' );
	$tpl->clear();

} else
	die( "error" );

$db->close();

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $tpl->result['vote'];
?>