<?PHP

if (!$lang['admin_logo']) $lang['admin_logo'] = "engine/skins/images/nav.jpg";

$skin_header = <<<HTML
<html>
<head>
<title>$lang[skin_title]</title>
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<script type="text/javascript" src="engine/skins/default.js"></script>
<script src="engine/skins/jquery-2.1.3.min.js" type="text/javascript"></script>
<script src="engine/skins/jquery-ui.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="engine/skins/style_adm.css"  media="all" />
<link rel="icon" href="/favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
</head>

<body>
<div id="content" class="content">
<table align="center" id="main_body" style="width:94%;">
    <tr>
        <td width="4" height="16"><img src="engine/skins/images/tb_left.gif" width="4" height="16" border="0" /></td>
		<td background="engine/skins/images/tb_top.gif"><img src="engine/skins/images/tb_top.gif" width="1" height="16" border="0" /></td>
		<td width="4"><img src="engine/skins/images/tb_right.gif" width="3" height="16" border="0" /></td>
    </tr>
	<tr>
        <td width="4" background="engine/skins/images/tb_lt.gif"><img src="engine/skins/images/tb_lt.gif" width="4" height="1" border="0" /></td>
		<td valign="top" style="padding-top:12px; padding-left:13px; padding-right:13px;" bgcolor="#FAFAFA">
		
<table width="100%">
    <tr>
        <td bgcolor="#FFE4C4" height="29"><div class="navigation"><span style="padding:0 5px 0 10px;">&rarr;</span>{$lang['skin_name']} {user} ({group})</div></td>
        <td bgcolor="#FFE4C4" height="29" align="right" style="padding-right:10px;"><div class="navigation"><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=main" class=navigation>$lang[skin_main]</a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=options&action=options" class=navigation><font color="#cc0000">$lang[opt_all_rublik]</font></a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=editnews&action=list" class=navigation>$lang[group_edit3]</a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=mservice" class=navigation><font color="#1E90FF">DLE Music Service</font></a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="{$config['http_home_url']}engine/modules/commentit/adm.php" class=navigation style="color:#F89804;">CommentIt</a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=vauth&page=info" class=navigation><font color="#9ACD32">VAuth</font></a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?mod=unitpay" class=navigation style="color:#DA6AFC;">UnitPay</a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="$PHP_SELF?action=logout" class=navigation>$lang[skin_logout]</a></div></td>
    </tr>
</table>
<div style="padding-top:5px;padding-bottom:2px;"></div>
        
       <script type="text/javascript">
$(document).ready(function(){

	zebra();

   function zebra() {
	   //var i = 0;
	   //jQuery.each($('.table tr').not('.head'), function() {

	     //if (i % 2) $(this).removeClass('int').addClass('odd');
	     //else $(this).removeClass('odd').addClass('int');

	     //i++;

	  //});
	   $('.table tr').not('.head').removeClass('odd').removeClass('int');
	   $('.table tr:odd').not('.head').addClass('odd');
	   $('.table tr:even').not('.head').addClass('int');
	     //else $(this).removeClass('odd').addClass('int');
  }

  $('body').on('click', '.delete', function() {

      $(this).parents('tr').detach();
      zebra();

  });

});
</script>
<!--MAIN area-->
HTML;

$skin_footer = <<<HTML
	 <!--MAIN area-->
<div style="padding-top:5px; padding-bottom:10px;">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="40" align="center" style="padding-right:10px;"><div class="navigation">domain.com<br />Copyright &copy; All rights reserved;)</div></td>
    </tr>
</table></div>		
		</td>
		<td width="4" background="engine/skins/images/tb_rt.gif"><img src="engine/skins/images/tb_rt.gif" width="4" height="1" border="0" /></td>
    </tr>
	<tr>
        <td height="16" background="engine/skins/images/tb_lb.gif"></td>
		<td background="engine/skins/images/tb_tb.gif"></td>
		<td background="engine/skins/images/tb_rb.gif"></td>
    </tr>
</table>
<script language="javascript" type="text/javascript">
<!--
function getClientWidth()
{
  return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;
}
var main_body_size = getClientWidth();

if (main_body_size > 1300) document.getElementById('main_body').style.width = "1200px";

//-->
</script>
</div>

 <div id="top-link">
	<a href="#top"></a>
</div>
<script type="text/javascript" src="engine/skins/button_down.js"></script>
</body>

</html>
HTML;

?>
