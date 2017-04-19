<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
  die("Hacking attempt!");
}

if( ! $user_group[$member_id['user_group']]['admin_newsletter'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

if (isset ($_REQUEST['empfanger'])) $empfanger = intval($_REQUEST['empfanger']); else $empfanger = "";
if (isset ($_REQUEST['editor'])) $editor = $_REQUEST['editor']; else $editor = "";
if (isset ($_REQUEST['type'])) $type = $_REQUEST['type']; else $type = "";
if (isset ($_REQUEST['action'])) $action = $_REQUEST['action']; else $action = "";
if (isset ($_REQUEST['a_mail'])) $a_mail = intval($_REQUEST['a_mail']); else $a_mail = "";


if ($action=="send") {

	include_once ENGINE_DIR.'/classes/parse.class.php';

	$parse = new ParseFilter(Array(), Array(), 1, 1);

	$title = strip_tags(stripslashes($parse->process($_POST['title'])));
	$message = stripslashes($parse->process($_POST['message']));
	$start_from = intval($_GET['start_from']);
	$limit = intval($_GET['limit']);
	$interval = intval($_GET['interval']) * 1000;

	if ($limit < 1) {

		$limit = 20;

	}

	if ($editor == "wysiwyg"){

		$message = $parse->BB_Parse($message);

	} else {

		$message = $parse->BB_Parse($message, false);
	}

	$where = array();

	if ($empfanger != "all") $where[] = "user_group = '{$empfanger}'";
	if ($a_mail) $where[] = "allow_mail = '1'";

	if (count($where)) $where = " WHERE ".implode (" AND ", $where);
	else $where = "";

	$row = $db->super_query("SELECT COUNT(*) as count FROM " . USERPREFIX . "_users".$where);

	if ($start_from > $row['count'] OR $start_from < 0) $start_from = 0;

	if ($type == "email")
		$type_send = $lang['bb_b_mail'];
	else
		$type_send = $lang['nl_pm'];

echo <<<HTML
<html>
<head>
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<title>{$lang['nl_seng']}</title>
<style type="text/css">
html,body{
height:100%;
margin:0px;
padding: 0px;
background: #F4F3EE;
}

form {
margin:0px;
padding: 0px;
}

table{
border:0px;
border-collapse:collapse;
}

table td{
padding:0px;
font-size: 11px;
font-family: verdana;
}

a:active,
a:visited,
a:link {
	color: #4b719e;
	text-decoration:none;
	}

a:hover {
	color: #4b719e;
	text-decoration: underline;
	}

.navigation {
	color: #999898;
	font-size: 11px;
	font-family: tahoma;
}
.unterline {
	background: url(engine/skins/images/line_bg.gif);
	width: 100%;
	height: 9px;
	font-size: 3px;
	font-family: tahoma;
	margin-bottom: 4px;
}
.hr_line {
	background: url(engine/skins/images/line.gif);
	width: 100%;
	height: 7px;
	font-size: 3px;
	font-family: tahoma;
	margin-top: 4px;
	margin-bottom: 4px;
}
.edit {
	border:1px solid #9E9E9E;
	color: #000000;
	font-size: 11px;
	font-family: Verdana; BACKGROUND-COLOR: #ffffff 
}
.buttons {
	background: #FFF;
	border: 1px solid #9E9E9E;
	color: #666666;
	font-family: Verdana, Tahoma, helvetica, sans-serif;
	padding: 0px;
	vertical-align: absmiddle;
	font-size: 11px; 
	height: 21px;
}
select, option {
	color: #000000;
	font-size: 11px;
	font-family: Verdana; 
	background-color: #ffffff 
}

textarea {
	border: #9E9E9E 1px solid;
	color: #000000;
	font-size: 11px;
	font-family: Verdana; 
	background-color: #ffffff 
}
#hintbox{ /*CSS for pop up hint box */
position:absolute;
top: 0;
background-color: lightyellow;
width: 150px; /*Default width of hint.*/ 
padding: 3px;
border:1px solid #787878;
font:normal 11px Verdana;
line-height:18px;
z-index:100;
border-right: 2px solid #787878;
border-bottom: 2px solid #787878;
visibility: hidden;
}

.hintanchor{ 
padding-left: 8px;
}
</style>
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
	var ajax = new dle_ajax();
	var send_count = 0;

function whenCompleted(){

  var total = {$row['count']};

  if (ajax.response == '' || ajax.response == 'error') 
  {
     document.getElementById( 'status' ).innerHTML = '{$lang['nl_error']}';
     document.getElementById( 'gesendet' ).innerHTML = send_count;
  }
  else 
  {

      if (ajax.response == '-1') {

        document.getElementById( 'status' ).innerHTML = '{$lang['nl_mailerror']}';
	    document.getElementById( 'gesendet' ).innerHTML = send_count;

      }
      else 
      {

         if (ajax.response >= total) 
         {
              document.getElementById( 'status' ).innerHTML = '{$lang['nl_finish']}';
         }
         else 
         { 
              setTimeout("senden(" + ajax.response + ")", {$interval} );
         }
      }
  }
}

function senden( startfrom ){

	document.getElementById( 'status' ).innerHTML = '{$lang['nl_sinfo']}';

	var title = ajax.encodeVAR( document.getElementById('title').innerHTML );
	var message = ajax.encodeVAR( document.getElementById('message').innerHTML );

	var varsString = "message=" + message;

	send_count = document.getElementById('gesendet').innerHTML;

	ajax.setVar("title", title);
	ajax.setVar("type", '{$type}');
	ajax.setVar("startfrom", startfrom);
	ajax.setVar("empfanger", '{$empfanger}');
	ajax.setVar("a_mail", '{$a_mail}');
	ajax.setVar("limit", '{$limit}');

	ajax.requestFile = "engine/ajax/newsletter.php";
	ajax.element = 'gesendet';
	ajax.method = 'POST';
	ajax.onCompletion = whenCompleted;

	ajax.sendAJAX(varsString);

	return false;
}
</script>
<table align="center" width="97%">
    <tr>
        <td width="4" height="16"><img src="engine/skins/images/tb_left.gif" width="4" height="16" border="0" /></td>
		<td background="engine/skins/images/tb_top.gif"><img src="engine/skins/images/tb_top.gif" width="1" height="16" border="0" /></td>
		<td width="4"><img src="engine/skins/images/tb_right.gif" width="3" height="16" border="0" /></td>
    </tr>
	<tr>
        <td width="4" background="engine/skins/images/tb_lt.gif"><img src="engine/skins/images/tb_lt.gif" width="4" height="1" border="0" /></td>
		<td valign="top" style="padding:8px;" bgcolor="#FFFFFF">
HTML;

echo <<<HTML
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['nl_seng']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="100" style="padding:4px;">{$lang['nl_empf']}</td>
        <td>{$row['count']}</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['nl_type']}</td>
        <td>{$type_send}</td>
    </tr>
    <tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td>{$lang['nl_sendet']} <span style="color:red;" id='gesendet'>{$start_from}</span> {$lang['mass_i']} <span style="color:blue;">{$row['count']}</span> {$lang['nl_status']} <span id="status"><input type="button" value="{$lang['nl_start']}" onClick="senden('{$start_from}')" class="edit"></span></td>
    </tr>
    <tr>
        <td><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td class="navigation">{$lang['nl_info']}</td>
    </tr>
</table>
HTML;

$message = stripslashes($message);

echo <<<HTML
		</td>
		<td width="4" background="engine/skins/images/tb_rt.gif"><img src="engine/skins/images/tb_rt.gif" width="4" height="1" border="0" /></td>
    </tr>
	<tr>
        <td height="16" background="engine/skins/images/tb_lb.gif"></td>
		<td background="engine/skins/images/tb_tb.gif"></td>
		<td background="engine/skins/images/tb_rb.gif"></td>
    </tr>
</table>
<pre style="display:none;" id="title">{$title}</pre>
<pre style="display:none;" id="message">{$message}</pre>
</body>

</html>
HTML;

}
elseif ($action=="preview")
{
include_once ENGINE_DIR.'/classes/parse.class.php';

$parse = new ParseFilter(Array(), Array(), 1, 1);

$title = strip_tags(stripslashes($parse->process($_POST['title'])));
$message = stripslashes($parse->process($_POST['message']));

if ($editor == "wysiwyg"){
$message = $parse->BB_Parse($message);
} else {
$message = $parse->BB_Parse($message, false);
}

echo <<<HTML
<html><title>{$title}</title>
<meta content="text/html; charset={$config['charset']}" http-equiv=Content-Type>
<style type="text/css">
html,body{
height:100%;
margin:0px;
padding: 0px;
font-size: 11px;
font-family: verdana;
}

table{
border:0px;
border-collapse:collapse;
}

table td{
padding:0px;
font-size: 11px;
font-family: verdana;
}

a:active,
a:visited,
a:link {
	color: #4b719e;
	text-decoration:none;
	}

a:hover {
	color: #4b719e;
	text-decoration: underline;
	}
</style>
<body>
HTML;

echo "<fieldset style=\"border-style:solid; border-width:1; border-color:black;\"><legend> <span style=\"font-size: 10px; font-family: Verdana\">{$title}</span> </legend>{$message}</fieldset>";


}
elseif ($action=="message") {
  echoheader("newsletter", "");


    echo "
    <SCRIPT LANGUAGE=\"JavaScript\">
    function send(){";

	if ($editor == "wysiwyg"){
	echo "document.getElementById('message').value = tinyMCE.get('message').getContent();";
	}

	echo "if(document.addnews.message.value == '' || document.addnews.title.value == ''){ alert('$lang[vote_alert]'); }
    else{
        dd=window.open('','snd','height=210,width=480,resizable=1,scrollbars=1')
        document.addnews.action.value='send';document.addnews.target='snd'
        document.addnews.submit();dd.focus()
    }
    }
    </SCRIPT>";

    echo "
    <SCRIPT LANGUAGE=\"JavaScript\">
    function preview(){";

	if ($editor == "wysiwyg"){
	echo "document.getElementById('message').value = tinyMCE.get('message').getContent();";
	}

	echo "if(document.addnews.message.value == '' || document.addnews.title.value == ''){ alert('$lang[vote_alert]'); }
    else{
        dd=window.open('','prv','height=300,width=600,resizable=1,scrollbars=1')
        document.addnews.action.value='preview';document.addnews.target='prv'
        document.addnews.submit();dd.focus()
        setTimeout(\"document.addnews.action.value='send';document.addnews.target='_self'\",500)
    }
    }
    </SCRIPT>";

echo <<<HTML
<script type="text/javascript" src="engine/ajax/dle_ajax.js"></script>
<div id='loading-layer' style='display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000'><div style='font-weight:bold' id='loading-layer-text'>{$lang['ajax_info']}</div><br /><img src='engine/ajax/loading.gif'  border='0' /></div>
<form method="POST" name="addnews" id="addnews" action="">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['nl_main']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="150" style="padding:6px;">{$lang['edit_title']}</td>
        <td><input class="edit" type="text" size="55" name="title"></td>
    </tr>
HTML;

if ($_REQUEST['editor'] == "wysiwyg"){

include(ENGINE_DIR.'/editor/newsletter.php');

} else {

include(ENGINE_DIR.'/inc/include/inserttag.php');

echo <<<HTML
    <tr>
        <td width="140" height="29" style="padding-left:5px;">{$lang['nl_message']}</td>
        <td>
		<table width="100%"><tr><td>{$bb_code}
	<textarea rows=17 style="width:98%;" onclick=setFieldName(this.name) name="message" id="message"></textarea><br><br>{$lang['nl_info_1']} <b>{$lang['nl_info_2']}</b><script type=text/javascript>var selField  = "message";</script></td>
	</tr></table>
</td></tr>
HTML;
}

$start_from = intval($_GET['start_from']);

echo <<<HTML
    <tr>
        <td style="padding:6px;">&nbsp;</td>
        <td><input type="hidden" name="mod" value="newsletter">
		<input type="hidden" name="action" value="send">
		<input type="hidden" name="empfanger" value="{$empfanger}">
		<input type="hidden" name="type" value="{$type}">
		<input type="hidden" name="a_mail" value="{$a_mail}">
		<input type="hidden" name="editor" value="{$editor}">
		<input type="hidden" name="start_from" value="{$start_from}">
		<br /><input type="button" onClick="send(); return false;" class="buttons" value="{$lang['btn_send']}" style="width:100px;">&nbsp;
        <input onClick="preview()" type="button" class="buttons" value="{$lang['btn_preview']}" style="width:100px;"></td>
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

  echofooter();
}
else {

  echoheader("newsletter", "");
  $group_list = get_groups ();

echo <<<HTML
<form method="GET" action="">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['nl_main']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="220" style="padding:6px;">{$lang['nl_empf']}</td>
        <td><select name="empfanger">
           <option value="all">{$lang['edit_all']}</option>
           {$group_list}
		   </select></td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_type']}</td>
        <td><select name="type">
           <option value="email">{$lang['bb_b_mail']}</option>
          <option value="pm">{$lang['nl_pm']}</option></select></td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_editor']}</td>
        <td><select name="editor">
           <option value="bbcodes">BBCODES</option>
          <option value="wysiwyg">WYSIWYG</option></select></td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_startfrom']}</td>
        <td><input class="edit" type="text" size="10" name="start_from" value="0"> {$lang['nl_user']}</td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_n_mail']}</td>
        <td><input class="edit" type="text" size="10" name="limit" value="20"></td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_interval']}</td>
        <td><input class="edit" type="text" size="10" name="interval" value="3"></td>
    </tr>
    <tr>
        <td style="padding:6px;">{$lang['nl_amail']}</td>
        <td><input type="checkbox" name="a_mail" value="1"></td>
    </tr>
    <tr>
        <td style="padding:6px;">&nbsp;</td>
        <td><input type="hidden" name="mod" value="newsletter"><input type="hidden" name="action" value="message"><input type="submit" class="buttons" value="{$lang['edit_next']}" style="width:100px;"></td>
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
  echofooter();
}
?>