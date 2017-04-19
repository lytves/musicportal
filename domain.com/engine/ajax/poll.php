<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();
@error_reporting( E_ALL ^ E_NOTICE );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );
@ini_set( 'error_reporting', E_ALL ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/poll.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';

require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/modules/sitelogin.php';

function votes($all, $ansid) {
	
	$data = array ();
	$alldata = array ();
	
	if( $all != "" ) {
		$all = explode( "|", $all );
		
		foreach ( $all as $vote ) {
			list ( $answerid, $answervalue ) = explode( ":", $vote );
			$data[$answerid] = intval( $answervalue );
		}
	}
	
	foreach ( $ansid as $id ) {
		$data[$id] ++;
	}
	
	foreach ( $data as $key => $value ) {
		$alldata[] = intval( $key ) . ":" . intval( $value );
	}
	
	$alldata = implode( "|", $alldata );
	
	return $alldata;
}

function get_votes($all) {
	
	$data = array ();
	
	if( $all != "" ) {
		$all = explode( "|", $all );
		
		foreach ( $all as $vote ) {
			list ( $answerid, $answervalue ) = explode( ":", $vote );
			$data[$answerid] = intval( $answervalue );
		}
	}
	
	return $data;
}

$news_id = intval( $_REQUEST['news_id'] );
$answers = explode( " ", trim( $_REQUEST['answer'] ) );

$buffer = "";
$vote_skin = strip_tags( $_REQUEST['vote_skin'] );
$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );

if( $is_logged ) $log_id = intval( $member_id['user_id'] );
else $log_id = $_IP;

$poll = $db->super_query( "SELECT * FROM " . PREFIX . "_poll WHERE news_id = '{$news_id}'" );
$log = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_poll_log WHERE news_id = '{$news_id}' AND member ='{$log_id}'" );

if( $log['count'] and $_REQUEST['action'] != "list" ) $_REQUEST['action'] = "results";
$votes = "";

if( $_REQUEST['action'] == "vote" ) {
	
	$votes = votes( $poll['answer'], $answers );
	$db->query( "UPDATE  " . PREFIX . "_poll set answer='$votes', votes=votes+" . count( $answers ) . " WHERE news_id = '{$news_id}'" );
	$db->query( "INSERT INTO " . PREFIX . "_poll_log (news_id, member) VALUES('{$news_id}', '$log_id')" );
	
	$_REQUEST['action'] = "results";
}

if( $_REQUEST['action'] == "results" ) {
	
	if( $votes == "" ) {
		$votes = $poll['answer'];
		$allcount = $poll['votes'];
	} else {
		$allcount = count( $answers ) + $poll['votes'];
	}
	
	$answer = get_votes( $votes );
	$body = explode( "<br />", stripslashes( $poll['body'] ) );
	$pn = 0;
	
	for($i = 0; $i < sizeof( $body ); $i ++) {
		
		$num = $answer[$i];
		
		if( ! $num ) $num = 0;
		
		++ $pn;
		if( $pn > 5 ) $pn = 1;
		
		if( $allcount != 0 ) $proc = (100 * $num) / $allcount;
		else $proc = 0;
		
		$proc = round( $proc, 2 );
		
		$buffer .= <<<HTML
{$body[$i]} - {$num} ({$proc}%)<br />
<img src="{$config['http_home_url']}templates/{$vote_skin}/dleimages/poll{$pn}.gif" height="10" width="{$proc}%" style="border:1px solid black;" alt="" /><br />
HTML;
	
	}

} elseif( $_REQUEST['action'] == "list" ) {
	
	$body = explode( "<br />", stripslashes( $poll['body'] ) );
	
	if( ! $poll['multiple'] ) {
		
		for($v = 0; $v < sizeof( $body ); $v ++) {
			if( ! $v ) $sel = "checked";
			else $sel = "";
			
			$buffer .= <<<HTML
<div><input name="dle_poll_votes" type="radio" $sel value="{$v}" /> {$body[$v]}</div>
HTML;
		
		}
	} else {
		
		for($v = 0; $v < sizeof( $body ); $v ++) {
			
			$buffer .= <<<HTML
<div><input name="dle_poll_votes[]" type="checkbox" value="{$v}" /> {$body[$v]}</div>
HTML;
		
		}
	
	}

} else
	die( "error" );

@header( "Content-type: text/css; charset=" . $config['charset'] );
echo $buffer;
?>