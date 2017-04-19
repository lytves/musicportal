<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
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

$_IP = $db->safesql( $_SERVER['REMOTE_ADDR'] );

if( $is_logged ) $log_id = intval( $member_id['user_id'] );
else $log_id = $_IP;

$poll = $db->super_query( "SELECT * FROM " . PREFIX . "_poll where news_id = '{$row['id']}'" );
$log = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_poll_log WHERE news_id = '{$row['id']}' AND member ='{$log_id}'" );

$poll['title'] = stripslashes( $poll['title'] );
$poll['frage'] = stripslashes( $poll['frage'] );
$body = explode( "<br />", stripslashes( $poll['body'] ) );

$tpl->load_template( 'poll.tpl' );

$tpl->set( '{title}', $poll['title'] );
$tpl->set( '{question}', $poll['frage'] );
$tpl->set( '{votes}', $poll['votes'] );

if( $log['count'] ) {
	
	$tpl->set_block( "'\\[not-voted\\](.+?)\\[/not-voted\\]'si", "" );
	$tpl->set( '[voted]', '' );
	$tpl->set( '[/voted]', '' );

} else {
	
	$tpl->set_block( "'\\[voted\\](.+?)\\[/voted\\]'si", "" );
	$tpl->set( '[not-voted]', '' );
	$tpl->set( '[/not-voted]', '' );
}

$list = "<div id=\"dle-poll-list\">";

if( ! $log['count'] and $user_group[$member_id['user_group']]['allow_poll'] ) {
	if( ! $poll['multiple'] ) {
		
		for($v = 0; $v < sizeof( $body ); $v ++) {
			if( ! $v ) $sel = "checked=\"checked\"";
			else $sel = "";
			
			$list .= <<<HTML
<div><input name="dle_poll_votes" type="radio" $sel value="{$v}" /> {$body[$v]}</div>
HTML;
		
		}
	} else {
		
		for($v = 0; $v < sizeof( $body ); $v ++) {
			
			$list .= <<<HTML
<div><input name="dle_poll_votes[]" type="checkbox" value="{$v}" /> {$body[$v]}</div>
HTML;
		
		}
	
	}
	
	$allcount = 0;

} else {
	
	$answer = get_votes( $poll['answer'] );
	$allcount = $poll['votes'];
	$pn = 0;
	
	for($v = 0; $v < sizeof( $body ); $v ++) {
		
		$num = $answer[$v];
		++ $pn;
		if( $pn > 5 ) $pn = 1;
		
		if( ! $num ) $num = 0;
		
		if( $allcount != 0 ) $proc = (100 * $num) / $allcount;
		else $proc = 0;
		
		$proc = round( $proc, 2 );
		
		$list .= <<<HTML
{$body[$v]} - {$num} ({$proc}%)<br />
<img src="{$config['http_home_url']}templates/{$config['skin']}/dleimages/poll{$pn}.gif" height="10" width="{$proc}%" style="border:1px solid black;" alt="" /><br />
HTML;
	
	}
	
	$allcount = 1;

}

$list .= "</div>";

$tpl->set( '{list}', $list );

$ajax_script = <<<HTML
<script type="text/javascript">
<!--
var dle_poll_result = 0;
var dle_poll_voted  = {$allcount};
function doPoll( event ){

    var frm = document.dlepollform;
	var vote_check = '';
	var news_id = frm.news_id.value;

  if (dle_poll_voted == 1) { return; }

  if (event != 'results' && dle_poll_result != 1) {
    for (var i=0;i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ vote_check = elmnt.value; break;}
        }
        if (elmnt.type=='checkbox') {
            if(elmnt.checked == true){ vote_check = vote_check + elmnt.value + ' ';}
        }
    }

	if (event == 'vote' && vote_check == '') { alert ('{$lang['poll_failed']}'); return; }

	dle_poll_voted  = 1;

  } else { dle_poll_result = 1; }

	if (dle_poll_result == 1 && event == 'vote') { dle_poll_result = 0; event = 'list'; }

	var ajax = new dle_ajax();
	ajax.onShow ('');
	var varsString = "";
	ajax.setVar("vote_skin", "{$config['skin']}");
	ajax.setVar("news_id", news_id );
	ajax.setVar("action", event);
	ajax.setVar("answer", vote_check);
	ajax.requestFile = dle_root + "engine/ajax/poll.php";
	ajax.method = 'POST';
	ajax.element = 'dle-poll-list';
	ajax.sendAJAX(varsString);

}
//-->
</script>
HTML;

$tpl->copy_template = $ajax_script . "<form  method=\"post\" name=\"dlepollform\" id=\"dlepollform\" action=\"\">" . $tpl->copy_template . "<input type=\"hidden\" name=\"news_id\" id=\"news_id\" value=\"" . $row['id'] . "\" /></form>";

$tpl->compile( 'poll' );
$tpl->clear();

?>