<?php
error_reporting(0);
ob_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
session_start();
@$logged_user=$_SESSION["logged_user"];
include ('config.php');
include ('lang/'.$mylang.'.php');
include ('func.php');
$confz="onClick=\"return confirm('".$langcommentit['admin_delq']."')\"";
?>
<!DOCTYPE html>
<html>
<head>
<title><?php   echo $langcommentit['admin_title'];?></title>
<script src='/<?php echo $wwp; ?>/ajax.js.php' type='text/javascript'></script>
<style type="text/css">
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td { margin: 0pt; padding: 0pt; }
body {font: 13px arial,verdana,tahoma,sans-serif;}
a.nav {
margin-bottom:5px;
text-decoration:none; 
display: block;
}
a:hover.nav {
display: block;
background-color: #F4F5FF;
text-decoration:none; 
}

a:visited.nav {
color:#000;
text-decoration:none; 
}

hr {
height:1px;
background-color:#000;
border:#000;
color:#000;
}

a {
color:#000;
text-decoration:underline;
}

a:visited {
color:#666;
text-decoration:underline;
}

a:hover {
color:#FF0000;
text-decoration:none;
}
.pages a,.pages a:visited {
font-size:14px;
color:#445;
border:#CCC 1px solid;
text-decoration:none;
padding:3px;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
-khtml-border-radius: 5px;
}

.pages a:hover {
background:#EBEBEB;
border:#000 1px solid;
}


.titlediv {left:20;position: absolute;background-color:#FFF;padding:3px 5px 0 5px;font: bold 14px Arial;}
.gendiv {padding:30px 15px 15px 15px;margin:15px; border: 1px outset black;width:450px;}
.left { float: left; }

#branding { background: url('im/admin/branding_bg.png') repeat-x scroll center top rgb(29, 54, 82); min-height: 80px;color:#CCC;font-size:24pt;}
#primary_nav { background: url('im/admin/primarynav_bg.png') no-repeat scroll 0% 0% rgb(25, 43, 64); font-size: 0.95em; border-bottom: 5px solid rgb(82, 143, 108); padding-left: 10px; height: 31px; }
#primary_nav ul li, #primary_nav ul li a { color: rgb(155, 178, 200); text-decoration: none; }
#primary_nav ul li { font-size: 0.95em; padding: 8px; margin-left: 6px; }
#primary_nav ul li.active { background: url('im/admin/tab_left.png') no-repeat scroll left top transparent; font-size: 1.05em; font-weight: bold; padding: 0pt; margin: -4px 0px auto 5px; position: relative; }
#primary_nav ul li.active a { background: url('im/admin/tab_right.png') no-repeat scroll right top transparent; padding: 10px 15px 10px 10px; margin-left: 6px; display: block; }
#primary_nav ul li a {color:#FFF;text-decoration: none; }
ol, ul { list-style: none outside none; }
#footer_utilities { background: url('im/admin/gradient_bg.png') repeat-x scroll 50% 50% rgb(25, 43, 64); color: rgb(255, 255, 255); font-size: 1.05em; min-height: 40px; }
.rounded { -moz-border-radius: 6px;-webkit-border-radius: 6px;border-radius: 6px; -khtml-border-radius: 6px;}
.clear { clear: both; }
.clearfix{ overflow: auto; }

	</style>
</head>
<body>
<table width="100%"><tr><td width="50%" bgcolor="#FFE4C4" height="29">
<div class="navigation"><span style="padding:0 5px 0 10px;">&rarr;</span><a href="/q7yfhwzn.php" style="color:#cc0000; font-size:11px; font-family:tahoma;">Главная админки</a><span style="padding:0 5px 0 10px;">&rarr;</span><a href="/q7yfhwzn.php?mod=mservice" style="color:#1E90FF; font-size:11px; font-family:tahoma;">DLE Music Service</a></div>
</td></tr></table>
<div id="branding">
<div style="padding:20px; 0 0 10px;">
CommentiIt 5.2.12 Ajax &copy 2008-2014 (обновлено всё кроме файла func.php)
</div>
</div>
<?php
function delempty()
{
global $table;
$result = mysql_query("SELECT num,rootid FROM `$table` WHERE rootid>0");
while ($myrow = mysql_fetch_array($result))
	{
	$SQL2 = "SELECT num FROM `$table` WHERE num='".$myrow['rootid']."' LIMIT 1";
    $result2 = mysql_query($SQL2);
    if (!mysql_num_rows($result2))
		{
		$row = mysql_fetch_array($result2);
		$result3 = mysql_query("DELETE FROM `$table` WHERE num=".$myrow['num']." LIMIT 1 ");
		}
	}
}
delempty();
###########
function getCommentsadm($row) {
global $massparam,$comment_msg,$names,$url,$date,$table,$wwp,$coder,$zzzzzzzzzzzzz,$langcommentit,$pass,$nums,$idcom,$divonter;
$comment_msg=$row['comm'];
$idcom=$row['num'];
$names=$row['name'];
$viewmail=$row['mail'];
$admincom=$row['admincom'];
$modz=$row['moder'];
$date=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($row['data']))));
$commentpx=$massparam['pxlevel']*$row['level']+$massparam['startpx'];
$comment_msg=cuthtml($comment_msg);
$comment_msg=cutbb($comment_msg);
$comment_msg=wordWrapIgnoreHTML($comment_msg,$massparam['wordcut']);
$comment_msg=viewworld($comment_msg,4).$massparam['lastend'];
if ($admincom==1) {$names=$massparam['nameadmin'];}

$warningmoder=''; if (($massparam['moder']==1)&&($modz)) {$warningmoder='<span title="'.$langcommentit['admin_moder'].'" style="color:#FF0000;"><b>(!!!)</b></span>';}
$treeview=$_GET['treeview']; $spsstyle=''; if ($treeview==$idcom||$_GET['repl']==$idcom||$_GET['edit']==$idcom) {$spsstyle='background-color:#9AFFB3;';}
$spsdownlevel=''; if ($commentpx>$massparam['startpx']) {$spsdownlevel='<b>&bull;</b>';}
echo '<div style="margin-left:'.$commentpx.'px;'.$spsstyle.'"><a class="nav" href="adm.php?treeview='.$idcom.'">'.$spsdownlevel.' <span style="font-size:6pt;color:#666;">'.$date.'</span><br /><b>'.$names.'</b> '.$comment_msg.' '.$warningmoder.' </a></div>';
echo "\r\n";

$q="SELECT * FROM `$table` WHERE rootid=".$idcom." ORDER BY data $dex";
 $r = mysql_query($q);  
 if(mysql_num_rows($r)>0) 
  {while($row = mysql_fetch_assoc($r)) {getCommentsadm($row);}} 
}
#######


$linkbutton="";
$picbutton="";
$panelbar="";
if ($massparam['bbpanel']==1)
{
if ($massparam['linkbb']==1) {
$linkbutton=
<<<EOF
<a title="$langcommentit[editor_url]" href="javascript:add_link();" class="pic4"></a>
EOF;
}

if ($massparam['quotebb']==1) {
$quotebutton=
<<<EOF
<a title="$langcommentit[editor_quat]" href="javascript: insert('[quote]','[/quote]');" class="pic6"></a>
EOF;
}

if ($massparam['picbb']==1) {
$picbutton=
<<<EOF
<a title="$langcommentit[editor_pic]" href="javascript: add_pic();" class="pic5"></a>
EOF;
}

if ($massparam['colorbb']==1) {
$colorbutton=
<<<EOF
<div style="position:relative;float: left;">
<a title="$langcommentit[editor_color]" href="javascript: showhide('colorpicz');" id="objcomm" class="pic7"></a>
<div style="position:absolute; z-index:50; display:none;" id="colorpicz"><script>tableCode=getColorTable();document.write(tableCode);</script></div>
</div>
EOF;
}

if ($massparam['sizebb']==1) {
$sizebutton=
<<<EOF
<select id='commentsize' size="1" onchange="getsize(this.value)">
    <option selected value="-">$langcommentit[editor_size]</option>
    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>
    <option value="6">6</option>
    <option value="7">7</option>
</select>
EOF;
}

if ($massparam['justbb']==1) {
$justbutton=
<<<EOF
<a title="$langcommentit[editor_left]" href="javascript: insert('[just=left]','[/just]');" class="pic10"></a>
<a title="$langcommentit[editor_center]" href="javascript: insert('[just=center]','[/just]');" class="pic11"></a>
<a title="$langcommentit[editor_right]" href="javascript: insert('[just=right]','[/just]');" class="pic12"></a>
EOF;
}

if ($massparam['smile']==1) {
$smilebar2=
<<<EOF
<div style="position:relative;float: left;">
<a title="$langcommentit[editor_color]" href="javascript: showhide('smilebar');" id="objcomm2" class="pic13"></a>
<div style="position:absolute; z-index:51; display:none;width:72px;background-color:#F0F0F0;" id="smilebar"><a href="javascript: put_smile(':)');"><img src="/$wwp/im/sml_1.gif" border="0" alt=" :)" title=" :)"  /></a>
<a href="javascript: put_smile('!;)');"><img src="/$wwp/im/sml_2.gif" border="0" alt="  ;)" title=" ;) " /></a>
<a href="javascript: put_smile(':D');"><img src="/$wwp/im/sml_3.gif" border="0" alt=" :D " title=" :D " /></a><br />
<a href="javascript: put_smile(':(');"><img src="/$wwp/im/sml_6.gif" border="0" alt=" :( " title=" :( " /></a>
<a href="javascript: put_smile('=)');"><img src="/$wwp/im/sml_4.gif" border="0" alt=" =) " title=" =) " /></a>
<a href="javascript: put_smile('?)');"><img src="/$wwp/im/sml_5.gif" border="0" alt=" ?) " title=" ?)"  /></a><br />
<a href="javascript: put_smile(':ups:');"><img src="/$wwp/im/sml_7.gif" border="0" alt=" :ups: " title=" :ups: " /></a>
<a href="javascript: put_smile(':cool:');"><img src="/$wwp/im/sml_8.gif" border="0" alt=" :cool: " title=" :cool: " /></a>
<a href="javascript: put_smile(':bad:');"><img src="/$wwp/im/sml_9.gif" border="0" alt=" :bad: " title=" :bad: " /></a><br />
<a href="javascript: put_smile(':like:');"><img src="/$wwp/im/sml_10.gif" border="0" alt=" :like: " title=" :like: " /></a>
<a href="javascript: put_smile(':angel:');"><img src="/$wwp/im/sml_11.gif" border="0" alt=" :angel: " title=" :angel: " /></a>
<a href="javascript: put_smile(':love:');"><img src="/$wwp/im/sml_12.gif" border="0" alt=" :love: " title=" :love: " /></a></div>
</div>
EOF;
}

$panelbar=
<<<EOF
<style>
a.pic {background-image: url(/$wwp/im/bbbold.gif);width: 24px;height: 23px;display: block;float:left;}
a:hover.pic {background-position: 0 23px;}
a.pic2 {background-image: url(/$wwp/im/bbitalic.gif);background-position: 0 0;width: 23px;height: 23px;display: block;float:left;}
a:hover.pic2 {background-position: 0 23px;}
a.pic3 {background-image: url(/$wwp/im/bbunderlin.gif);background-position: 0 0;width: 24px;height: 23px;display: block;float:left;}
a:hover.pic3 {background-position: 0 23px;}
a.pic4 {background-image: url(/$wwp/im/bburl.gif);background-position: 0 0;width: 21px;height: 23px;display: block;float:left; margin:0 3px 0 3px;}
a:hover.pic4 {background-position: 0 23px;}
a.pic5 {background-image: url(/$wwp/im/bbimage.gif);background-position: 0 0;width: 21px;height: 23px;display: block;float:left;margin:0 3px 0 3px;}
a:hover.pic5 {background-position: 0 23px;}
a.pic8 {background-image: url(/$wwp/im/bbstrike.gif);background-position: 0 0;width: 23px;height: 23px;display: block;float:left;}
a:hover.pic8 {background-position: 0 23px;}
a.pic6 {background-image: url(/$wwp/im/bbquote.gif);background-position: 0 0;width: 21px;height: 23px;display: block;float:left;margin:0 3px 0 3px;}
a:hover.pic6 {background-position: 0 23px;}
a.pic7 {background-image: url(/$wwp/im/bbcolor.gif);background-position: 0 0;width: 21px;height: 23px;display: block;float:left; margin:0 3px 0 3px;}
a:hover.pic7 {background-position: 0 23px;}
a.pic10 {background-image: url(/$wwp/im/bbjustifyleft.gif);width: 24px;height: 23px;display: block;float:left;margin:0 0 0 3px;}
a:hover.pic10 {background-position: 0 23px;}
a.pic11 {background-image: url(/$wwp/im/bbjustifycenter.gif);background-position: 0 0;width: 23px;height: 23px;display: block;float:left;}
a:hover.pic11 {background-position: 0 23px;}
a.pic12 {background-image: url(/$wwp/im/bbjustifyright.gif);background-position: 0 0;width: 24px;height: 23px;display: block;float:left;}
a:hover.pic12 {background-position: 0 23px;}
a.pic13 {background-image: url(/$wwp/im/bbsmail.gif);background-position: 0 0;width: 21px;height: 23px;display: block;float:left;margin:0 3px 0 3px;}
a:hover.pic13 {background-position: 0 23px;}
</style>
<a title="$langcommentit[editor_bold]" href="javascript: insert('[b]','[/b]');" class="pic"></a>
<a title="$langcommentit[editor_italic]" href="javascript: insert('[i]','[/i]');" class="pic2"></a>
<a title="$langcommentit[editor_strike]" href="javascript: insert('[s]','[/s]');" class="pic8"></a>
<a title="$langcommentit[editor_underlin]" href="javascript: insert('[u]','[/u]');" class="pic3"></a>
$justbutton $colorbutton $linkbutton $picbutton $quotebutton $smilebar2 $sizebutton 
EOF;
}

if ($typeadm==1){
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!=$login || $_SERVER['PHP_AUTH_PW']!=$pass)

   {
   header("WWW-Authenticate: Basic realm=\"CommentIt - ADMIN\"");
   header("HTTP/1.0 401 Unauthorized");
   exit($langcommentit['admin_no_entry']);
   }
}
else
{
if (!$logged_user) {
$submit=$_POST['submit'];
if(empty($submit)) {?> 

<table width="100%" height="80%" cellpadding="0" cellspacing="0">
<tr>
<td width="100%" height="80%" align="center" valign="middle">
<form action="" method="POST" ENCTYPE="multipart/form-data"> 
<?php  echo $langcommentit['admin_login'];?> <input type="text" name="nick" size="20"> <?php  echo $langcommentit['admin_pass'];?>  <input type="password" name="pass2" size="20"> <input type="submit" name="submit" value="<?php  echo $langcommentit['admin_button'];?>"> 
</form> 
</td></tr></table>
<?php  
exit();
}
else
{
$nick=$_POST['nick']; 
$pass2=$_POST['pass2']; 

if(empty($nick)) { 
$bad .= $langcommentit['admin_bad_login'];
$bad .= '<br />';
}
else{
if($nick!=$login) { 
$bad .= $langcommentit['admin_bad_all'];
$bad .= '<br />';
}}

if(empty($pass2))
{
$bad .= $langcommentit['admin_bad_pass'];
$bad .= '<br />';
}
else{
if($pass2!=$pass)
{
$bad .= '<br />';
$bad .= $langcommentit['admin_bad_all'];
}}


if ($bad) 
{
echo "<center>".$langcommentit['admin_bad_mes'];
echo '<br />'.$bad.'<br /><br />';
echo '<a href="javascript:history.back(1)">'.$langcommentit['admin_bad_back'].'</a></center>';
exit();
}
if (empty($bad)){
session_start ();
session_destroy();
$logged_user[login]  = $nick;
$logged_user[password] = $pass2;
session_start();
$_SESSION['logged_user']=$logged_user;

 exit("<html><head><meta http-equiv='refresh' content='1;/$wwp/adm.php'></head><body><center><br>".$langcommentit['admin_button']."</b></center> </body></html>");}
}
}

}

$outadm=$_GET['outadm'];

if ($outadm){
session_unset($logged_user);
session_destroy();
 exit("<html>
                  <head>
                    <meta http-equiv='refresh' content='1;/$wwp/adm.php'>
                  </head>
                  <body>
                    <center>
                    <br>".$langcommentit['admin_exit']."</b>
		    </center> 
                  </body>
                </html>");

}


$dex="DESC";
$edit=$_GET['edit'];
if ($_GET['onlyurl']) {$zapcount="WHERE url='".mysql_real_escape_string($_GET['onlyurl'])."'";$zapcount2='url';}
elseif ($_GET['onlyip']) {$zapcount="WHERE ip LIKE '".mysql_real_escape_string($_GET['onlyip'])."%'";$zapcount2='ip';}
elseif ($_GET['onlyname']) {$zapcount="WHERE name='".mysql_real_escape_string($_GET['onlyname'])."'";$zapcount2='name';}
$out=0;$countcomment=mysql_query("SELECT count($zapcount2) FROM `$table` $zapcount ");$out=mysql_result($countcomment,0);
?>
<div id="primary_nav">
<ul>
<li class="left <?php if (!$_GET['part']) echo 'active'; ?>"><a href="?"><?php echo $langcommentit['admin_comment']; ?></a></li>
<li class="left <?php if ($_GET['part']=='config') echo 'active'; ?>"><a href="?part=config"><?php echo $langcommentit['config']; ?></a></li>
<?php if ($typeadm==0) echo '<li class="left"> <a href="?outadm=1">'.$langcommentit['admin_exit'].' </a></li>';?>
</ul>
</div>
<?php
if ($_GET['part']!='config')
{
?>
<br />
<div class="pages">
<table width="385px" cellpadding="0" cellspacing="0">
<tr>
        <td colspan="3"><?php echo $langcommentit['admin_count'].': '.$out;?><br /><br /></td>
    </tr>
    <tr>
        <td><form name="form1" id="form1" method="get"><?php echo $langcommentit['admin_onlydate'];?></td>
        <td><input type="text" value="<?php echo date("Y-m-d");?>" name="only"></td>
        <td><a href="javascript:document.getElementById('form1').submit();"><?php echo $langcommentit['admin_find'];?></a></form></td>
    </tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr>
        <td><form name="form2" id="form2" method="get"><?php echo $langcommentit['admin_onlyurl'];?></td>
        <td><input type="text" value="<?php echo $_GET['onlyurl']?>" name="onlyurl"></td>
        <td><a href="javascript:document.getElementById('form2').submit();"><?php echo $langcommentit['admin_find'];?></a></form></td>
    </tr>
</table>
</div>
<hr />

<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td colspan="2"></td>
</tr>
<tr>
<td width="25%" valign="top" align="left" style="border-right-width:1px; border-right-color:black; border-right-style:solid;">
<?php  
$only=$_GET['only'];
$nums=$_GET['nums'];
$repl=$_GET['repl'];
$level=$_GET['level'];
$onlyurl=$_GET['onlyurl'];
$onlyip=$_GET['onlyip'];
$onlyname=$_GET['onlyname'];
$urlz=$_GET['urlz'];
$treeview=intval($_GET['treeview']);
$treevieall=intval($_GET['treevieall']);
if (!empty($only)) $onlydate="AND data LIKE '".$only."%'";
if (!empty($onlyurl)) $onlydate="AND url LIKE '".$onlyurl."'";
if (!empty($onlyip)) $onlydate="AND ip LIKE '".$onlyip."%'";
if (!empty($onlyname)) $onlydate="AND name LIKE '".$onlyname."'";

if (!$_COOKIE["nolimit"])  setcookie("nolimit", '0');

if ($_COOKIE["nolimit"]==1) 
{
$treelimit='';
$admin_viewall='<br /><center><div class="pages"><a href="?treevieall=2">'.$langcommentit['admin_hideall'].' </a></div></center><br />';
}

if ($_COOKIE["nolimit"]==0) {
$treelimit='LIMIT 0,50';
$admin_viewall='<br /><center><div class="pages"><a href="?treevieall=1">'.$langcommentit['admin_viewall'].' </a></div></center><br />';
}

if ($treevieall==1) {setcookie("nolimit", '1');header("Location: $_SERVER[HTTP_REFERER]");}
if ($treevieall==2) {setcookie("nolimit", '0');header("Location: $_SERVER[HTTP_REFERER]");}

$resultget = mysql_query("SELECT * FROM `$table` WHERE rootid=0 $onlydate ORDER BY data $dex $treelimit");
if (mysql_num_rows($resultget)){
while ($myrow = mysql_fetch_array($resultget)){getCommentsadm($myrow);}
echo $admin_viewall;
}
else {echo '<br /><center>'.$langcommentit['admin_nocomment'].'</center><br />';}
?>
</td>
<td valign="top">
<?php  
if (!empty($only)) $onlydate="WHERE data LIKE '".$only."%'";
if (!empty($onlyurl)) $onlydate="WHERE url LIKE '".$onlyurl."'";
if (!empty($onlyip)) $onlydate="WHERE ip LIKE '".$onlyip."%'";
if (!empty($onlyname)) $onlydate="WHERE name LIKE '".$onlyname."'";
if (empty($nums)) {$nums=0;}
if ($treeview) {$onlydate="WHERE num='".$treeview."' OR rootid='".$treeview."'";$dex='';}
$rpp=50;

$page=0;
$query = mysql_fetch_array(mysql_query("SELECT COUNT(num) AS kolvo FROM `$table` $onlydate ORDER BY data $dex"));
$cnt=$query['kolvo'];
$pages=ceil($cnt/$rpp);

$viewallpage='<div class="pages" style="padding-left:10px;">';
if ((50)&&(!$treeview)) {
if (!empty($pages)){ $viewallpage .= $langcommentit['admin_page']."  ";}
############
$rad=3; 
if (empty($only)&&empty($onlyurl)&&empty($onlyip)&&empty($onlyname)) $link_sc='?nums';
if (!empty($only)) $link_sc='?only='.$only.'&nums';
if (!empty($onlyurl)) $link_sc='?onlyurl='.$onlyurl.'&nums';
if (!empty($onlyip)) $link_sc='?onlyip='.$onlyip.'&nums';
if (!empty($onlyname)) $link_sc='?onlyname='.$onlyname.'&nums';
if (isset($_GET['nums'])){$page=$_GET['nums']-1; }else{$page=0;}
 $links=$rad*2+1;
if ($page>0) { $viewallpage.= "<a href=\"$link_sc=1\">".$langcommentit['skin_pages_first']."</a> | <a href=\"$link_sc=".($page)."\">".$langcommentit['skin_pages_prev']."</a> |"; }
$start=$page-$rad;
if ($start>$pages-$links) { $start=$pages-$links; }
if ($start<0) { $start=0; }
$end=$start+$links;
if ($end>$pages) { $end=$pages; }
for ($i=$start; $i<$end; $i++) {
 $viewallpage.= " ";
 
 if ($i==$page) {
  $viewallpage.= "<b>";
 } else {
  $viewallpage.= "<a href=\"$link_sc=".($i+1)."\">";
 }
 $viewallpage.= ($i+1);
 if ($i==$page) {
  $viewallpage.= "</b>";
 } else {
  $viewallpage.= "</a>";
 }
 if ($i!=($end-1)) { $viewallpage.= "&nbsp;|"; }
}
if ($pages>$links&&$page<($pages-$rad-1)) { $viewallpage.= " ... <a href=\"$link_sc=".($pages)."\">".($pages)."</a>"; }
if ($page<$pages-1) { $viewallpage.= " <a href=\"$link_sc=".($page+2)."\">".$langcommentit['skin_pages_next']."</a> | <a href=\"$link_sc=".($pages)."\">".$langcommentit['skin_pages_last']."</a>"; }
###################
}

$viewallpage .="</div><br />";
if((!empty($only))&&($only==$datehay[0])&&(empty($edit))) {echo $viewallpage;}
if((empty($only))&&(empty($edit))&&(empty($repl))) {echo $viewallpage; }
$result = mysql_query("SELECT * FROM `$table` $onlydate ORDER BY data $dex LIMIT ".$page*$rpp.",$rpp");
$izs=0;
while ($myrow = mysql_fetch_array($result))
{
$out=0;$countcomment=mysql_query("SELECT count(url) FROM `$table` WHERE url='".$myrow['url']."' ");$out=mysql_result($countcomment,0);
$datehay=explode(" ",$myrow['data']);
$comment_msg = $myrow['comm'];
$repllevel=$myrow['level'];
$comment_msg = str_replace('<br />', '_br_', $comment_msg );
$comment_msg = cuthtml($comment_msg);
$comment_msg = str_replace('_br_', '<br />', $comment_msg );
$comment_msg=wordWrapIgnoreHTML($comment_msg,$massparam['wordcut']);
if ($massparam['smile']==1) $comment_msg = parsesmile($comment_msg);
$comment_msg = str_replace('zzzxaqwedasdsad', '<img src="/'.$wwp.'', $comment_msg );
$view='';
$viewall='<div style="padding-left:10px;">';
if (($treeview)&&($izs==1)) {$viewall='<h3>'.$langcommentit['admin_spsrepl'].'</h3> <br />';}
if (($massparam['moder']==1)&&($myrow['moder'])) {$view=' - <b><font color="red">'.$langcommentit['admin_moder'].'</font></b>';}
//$myrow[1] = str_replace("/comm/comment.php?url=", "", $myrow[1] );
if ($myrow['admincom']==1) $myrow['name']=$massparam['nameadmin'];

$myrow['data']=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($myrow['data']))));
$viewall.="<b>URL:</b> <a target=_blank href=http://".$_SERVER['SERVER_NAME']."".$myrow['url'].">http://".$_SERVER['SERVER_NAME']."".$myrow['url']."</a> (".$out.") ".$view."<br />";
$viewall.="<b>".$langcommentit['admin_name']."</b> <span style=\"color:#3367AB; font-weight:bold;\">".strip_tags($myrow['name'])."</span><br />";
$viewall.="<b>".$langcommentit['admin_date']."</b> ".$myrow['data']."<br />";
$viewall.="<b>".$langcommentit['admin_mail']."</b> ".strip_tags($myrow['mail'])."<br />";
$viewall.="<b>IP:</b> <span style=\"color:#cc0000; font-weight:bold;\">".$myrow['ip']."</span><br />";
$viewall.='<b>'.$langcommentit['admin_comment'].'</b> <br /><div style="background:#F4F5FF;border:#E8E8FF 1px solid;padding:10px 3px;margin-top:10px;margin-left:2px;">'.$comment_msg.'</div><br />';
$viewall.="<div class=pages>";
$viewall.="<a href=?onlyurl=".$myrow['url'].">Все комментарии на этой странице</a> | ";
$viewall.="<a href=?onlyname=".$myrow['name'].">Все комментарии с этим <span style=\"color:#3367AB;\">именем</span></a> | ";
$viewall.="<a href=?onlyip=".$myrow['ip'].">Все комментарии с этим <span style=\"color:#cc0000;\">IP-адресом</span></a><br /><br />";
$viewall.="<a href=?edit=".$myrow['num'].">".$langcommentit['admin_edit']."</a> | ";
$viewall.="<a href=?repl=".$myrow['num']."&urlz=".str_replace('&','ZOZZ',$myrow['url'])."&level=".$repllevel.">".$langcommentit['admin_reply']."</a> | ";
$viewall.="<a $confz href=?del=".$myrow['num'].">".$langcommentit['admin_del']."</a>";
if ($myrow['admincom']==0) {
$viewall.=" | <a $confz href=?delname=".rawurlencode($myrow['name']).">".$langcommentit['admin_delneme']."</a>";
$viewall.=" | <a $confz href='?delspamer=1&date=".$datehay[0]."&ip=".$myrow['ip']."'>".$langcommentit['admin_deldate']." ".$datehay[0]." + IP: ".$myrow['ip']."</a>";
}
if (($massparam['moder']==1)&&($myrow['moder'])) {$viewall.=" | <a style=\"border-color:#33CC00\" href=?ok=".$myrow['num'].">".$langcommentit['admin_accept']."</a>";}
$viewall.="</div></div><hr />";
$izs++;
if(!empty($edit)) {break;}
if((!empty($only))&&($only==$datehay[0])&&(empty($edit))) {echo $viewall;}
if((empty($only))&&(empty($edit))&&(empty($repl))) {echo $viewall; }
}
if((!empty($only))&&($only==$datehay[0])&&(empty($edit))) {echo $viewallpage;}
if((empty($only))&&(empty($edit))&&(empty($repl))) {echo $viewallpage; }


$del=$_GET['del'];
if(!empty($del)) {
$result1 = mysql_query("DELETE FROM `$table` WHERE num=$del LIMIT 1");
delempty();
header("Location: $_SERVER[HTTP_REFERER]");
}


if(!empty($edit)) {
$result3 = mysql_query("SELECT * FROM `$table` WHERE num=$edit");
while ($myrow = mysql_fetch_array($result3))
{
$commentz=$myrow['comm'];
$namesz=$myrow['name'];
$datez=$myrow['data'];
$ipz=$myrow['ip'];
$mailz=$myrow['mail'];
if ($myrow['admincom']==1) {$namesz=$massparam['nameadmin'];}
if ($coder==1) {
$comment = iconv('UTF-8', 'windows-1251', $comment);
$namenew = iconv('UTF-8', 'windows-1251', $namenew);
$dataz = iconv('UTF-8', 'windows-1251', $dataz);
}


}

$editcom=$_POST['editcom'];
if(empty($editcom)) {
$commentz=str_replace("<br />","\r\n",$commentz);
echo '<form name="editcom" ENCTYPE="multipart/form-data" action="" method=post><br /><table border="0">';
echo '<tr><td><b>'.$langcommentit['admin_name'].'</b></td><td><input type="text" name="namenew" value="'.$namesz.'" size="20"></td> ';
echo '<tr><td><b>'.$langcommentit['admin_date'].'</b></td><td><input type="text" name="datenew" value="'.$datez.'" size="20"></td> ';
echo '<tr><td><b>IP:</b></td><td><input type="text" name="ipnew" value="'.$ipz.'" size="20"></td> ';
echo '<tr><td><b>'.$langcommentit['admin_mail'].'</b></td><td><input type="text" name="mailnew" value="'.$mailz.'" size="20"></td> </table>';
echo '<b>'.$langcommentit['admin_comment'].'</b><br />'.$panelbar.'<br /><br />';
echo '<textarea rows="15" cols="57" id="textz" name=comment>'.stripslashes($commentz).'</textarea><br /><br />';
echo '<input type="hidden" name="refer" value="'.$_SERVER['HTTP_REFERER'].'"><input type="submit" name="editcom" value="'.$langcommentit['admin_edit'].'"><input type="button" onclick="history.back(1)" value="'.$langcommentit['admin_cancel'].'"></form>';
}
else
{
$comment=$_POST['comment'];
$namenew=$_POST['namenew'];
$datenew=$_POST['datenew'];
$ipnew=$_POST['ipnew'];
$mailnew=$_POST['mailnew'];
$comment=str_replace("\r\n","<br />",$comment);
if (get_magic_quotes_gpc()) {$namenew = stripslashes($namenew);$comment=stripslashes($comment);}
 
 $comment_msg=cuthtml($comment_msg);
/*
if ($coder==1) {
$phpver=preg_replace('/[a-z-]/', '', phpversion());
$phpver=$phpver[0];
if ($phpver==5){
$comment = iconv('UTF-8', 'windows-1251', $comment);
}
else {
$comment=Utf8Win($comment,w);
}
}
*/
 if (get_magic_quotes_gpc()) {$namenew = stripslashes($namenew);$comment=stripslashes($comment);}
$query = "UPDATE `$table` SET comm='".mysql_real_escape_string($comment)."', name='".mysql_real_escape_string($namenew)."', data='".$datenew."', mail='".$mailnew."',ip='".$ipnew."' WHERE num='".$edit."';";
$sort=@mysql_query($query) or die ("$query");
 exit("<html><head><meta http-equiv='refresh' content='1;url=".$_POST['refer']."'></head><body><center><br>".$langcommentit['admin_editmes']."</b></center> </body></html>");
}
}

$delname=$_GET['delname'];
if(!empty($delname)) {
$result10 = mysql_query("DELETE FROM `$table` WHERE name='".rawurldecode($delname)."' LIMIT 1;");
delempty();
header("Location: $_SERVER[HTTP_REFERER]");
}

$ok=$_GET['ok'];
if(!empty($ok)) {
$result15 = mysql_query("UPDATE ".$table." SET moder='0' WHERE num='".$ok."' LIMIT 1;");
		if ($massparam['europing']==1) {pingmagnetik ($massparam['titlecia'],$hostsite);}
		if ($massparam['yaping']==1) {pingyandex ($massparam['titlecia'],$hostrss);}
header("Location: $_SERVER[HTTP_REFERER]");
}


if(!empty($repl)) {
$entrepl=$_POST['entrepl'];

if(empty($entrepl)) {
echo '<form name="entrepl" ENCTYPE="multipart/form-data" action="" method=post><br />';
echo '<b>'.$langcommentit['admin_enterreply'].' '.$repl.':</b><br />';
echo $panelbar;
echo '<br /><br /><textarea rows="15" cols="57" id="textz" name=comment></textarea><br /><br />';
echo '<input type="submit" name="entrepl" value="'.$langcommentit['admin_reply'].'"><input type="button" onclick="history.back(1)" value="'.$langcommentit['admin_cancel'].'"></form>';
}
else
{
$comment=$_POST['comment'];
$comment=str_replace("\r\n","<br />",$comment);
/*
if ($coder==1) {
if ($iconv<>0){
$comment = iconv('UTF-8', 'windows-1251', $comment);
$dataz = iconv('UTF-8', 'windows-1251', $dataz);
}
else {
$comment=Utf8Win($comment,w);
$dataz=Utf8Win($dataz,w);
}}
*/

$SQLurl = "SELECT url FROM `$table` WHERE num=".$repl." LIMIT 1";
$resulturl = mysql_query($SQLurl);
if (mysql_num_rows($resulturl)){
$rowxp = mysql_fetch_array($resulturl);
$urlz=$rowxp['url'];
}


if ($massparam['maxlevel']>0) {
if ($massparam['maxlevel']==$level) 
		{$level=1;} 
		else 
		{$level=$level+1;
		}
}
 if (get_magic_quotes_gpc()) {$comment=stripslashes($comment);}
$query = "INSERT INTO `$table` (`num`,`url`,`name`,`is_register`,`comm`,`data`,`ip`,`rootid`,`level`,`admincom`) VALUES (NULL, '".$urlz."', '".$login."', '1', '".mysql_real_escape_string($comment)."', '".$dataz."','".getipcomment()."','".$repl."','".$level."','1');";
$sort=@mysql_query($query) or die ("$query");

if ($massparam['replaymail']==1)
{
$SQLr = "SELECT mail FROM ".$table." WHERE num=".$repl." LIMIT 1";
 $resultr = mysql_query($SQLr);
    if (mysql_num_rows($resultr)){
    $rowr = mysql_fetch_array($resultr);
$message="".$langcommentit['mail_message_raplay1']." <b>".$nameadmin."</b>: <br />".$comment."<br /> ".$langcommentit['mail_message_raplay2']." <a href='http://".$_SERVER['SERVER_NAME']."".$urlz."'>http://".$_SERVER['SERVER_NAME']."".$urlz."</a>"; 
   $mail=$rowr['mail'];
   if ($rowr['mail']){
   $smail=new html_mime_mail();
   $smail->add_html($message);
   if ($coder==1) {$smail->build_message('w');} else {$smail->build_message('u');}
   $smail->send($_SERVER['SERVER_NAME'],$mail,"robocommentit@".$_SERVER[HTTP_HOST],$langcommentit['mail_message_raplay_title']); 
   }
}
}


 exit("<html><head><meta http-equiv='refresh' content='1;url=?only='></head><body><center><br>".$langcommentit['admin_saverepl']."</b></center></body></html>");
}
}

$delspamer=$_GET['delspamer'];
$date=$_GET['date'];
$ip=$_GET['ip'];
if(!empty($delspamer)) {
$result15 = mysql_query("DELETE FROM ".$table." WHERE `data` LIKE '".$date."%' AND ip='".$ip."';");
delempty();
header("Location: $_SERVER[HTTP_REFERER]");
}
?>
</td></tr>
</table>
<?php } else {
$go=$_POST['go'];
if(empty($go)) {
?>

<form action="?part=config" name="config" ENCTYPE="multipart/form-data" method="post">
<div class="titlediv"><?php echo $langcommentit['fast_menu']; ?></div><a name="menu"></a>
<div class="gendiv">
<a href="#1">&bull; <?php echo $langcommentit['title_bb'];?></a><br />
<a href="#2">&bull; <?php echo $langcommentit['title_cut'];?></a><br />
<a href="#3">&bull; <?php echo $langcommentit['title_mail'];?></a><br />
<a href="#4">&bull; <?php echo $langcommentit['title_other'];?></a><br />
<a href="#5">&bull; <?php echo $langcommentit['title_level'];?></a><br />
<a href="#6">&bull; <?php echo $langcommentit['title_latt'];?></a><br />
<a href="#7">&bull; <?php echo $langcommentit['title_rss'];?></a><br />
<a href="#8">&bull; <?php echo $langcommentit['title_mat'];?></a><br />
<a href="#9">&bull; <?php echo $langcommentit['title_rating'];?></a><br />
<a href="#10">&bull; <?php echo $langcommentit['title_logiza'];?></a><br />
</div>
<br />
<div style="margin-left:15px;"><input type="submit" name="go" value="<?php echo $langcommentit['button_save'];?>"></div>
<br /><br />
<?php
function selectyes ($par)
{
global $langcommentit,$massparam;
$color='enabled.gif';if ($massparam[$par]==0) {$color='disabled.gif';}
echo ''.$langcommentit[$par].'<br /><select name="'.$par.'" id="'.$par.'" size="1"><option value="0">'.$langcommentit['select_off'].'</option><option value="1">'.$langcommentit['select_on'].'</option></select>&nbsp;&nbsp;<img title="" alt="" src="im/'.$color.'" /><script>selectOptionByValue(document.getElementById(\''.$par.'\'),"'.$massparam[$par].'");</script>';
}

function selectyesico ($par)
{
global $langcommentit,$massparam;
$color='enabled.gif';if ($massparam[$par]==0) {$color='disabled.gif';}
$ico='<img src="im/'.$par.'.png" border="0" />';
echo $ico.' '.$langcommentit[$par].'<br /><select name="'.$par.'" id="'.$par.'" size="1"><option value="0">'.$langcommentit['select_off'].'</option><option value="1">'.$langcommentit['select_on'].'</option></select>&nbsp;&nbsp;<img title="" alt="" src="im/'.$color.'" /><script>selectOptionByValue(document.getElementById(\''.$par.'\'),"'.$massparam[$par].'");</script>';
}

function selectsort ($par)
{
global $langcommentit,$massparam;
echo ''.$langcommentit[$par].'<br /><select name="'.$par.'" id="'.$par.'" size="1"><option value="0">'.$langcommentit['select_sort1'].'</option><option value="1">'.$langcommentit['select_sort2'].'</option><option value="2">'.$langcommentit['select_sort3'].'</option></select><script>selectOptionByValue(document.getElementById(\''.$par.'\'),"'.$massparam[$par].'");</script>';
}

function selecteasy ($par)
{
global $langcommentit,$massparam;
echo ''.$langcommentit[$par].'<br /><select name="'.$par.'" id="'.$par.'" size="1"><option value="0">'.$langcommentit['select_light'].'</option><option value="1">'.$langcommentit['select_hard'].'</option></select><script>selectOptionByValue(document.getElementById(\''.$par.'\'),"'.$massparam[$par].'");</script>';
}

function input ($par)
{
global $langcommentit,$massparam;
echo $langcommentit[$par].'<br /><input type="text" name="'.$par.'" size="32" value="'.$massparam[$par].'"/>';
}
?>
<script>function selectOptionByValue(selObj, val){var A= selObj.options, L= A.length;while(L){if (A[--L].value== val){selObj.selectedIndex= L;L= 0;}}}</script>
<div class="titlediv"><?php echo $langcommentit['title_bb'];?><a name="1"></a></div>
<div class="gendiv">
<?php selectyes('smile'); ?><br /><br />
<?php selectyes('bbpanel'); ?><br /><br />
<?php selectyes('linkbb'); ?><br /><br />
<?php selectyes('linknofol'); ?><br /><br />
<?php input('linkredirect'); ?><br /><br />
<?php selectyes('linkpars'); ?><br /><br />
<?php selectyes('picbb'); ?><br /><br />
<?php selectyes('picpars'); ?><br /><br />
<?php selectyes('quotebb'); ?><br /><br />
<?php input('quotestyle'); ?><br /><br />
<?php selectyes('colorbb'); ?><br /><br />
<?php selectyes('sizebb'); ?><br /><br />
<?php selectyes('justbb'); ?><br /><br />
<?php input('limitsizew'); ?> px<br /><br />
<?php input('limitsizeh'); ?> px<br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_cut'];?><a name="2"></a></div>
<div class="gendiv">
<?php input('sumvl'); ?><br /><br />
<?php input('sumvlname'); ?><br /><br />
<?php input('wordcut'); ?><br /><br />
<?php selectyes('moder'); ?><br /><br />
<?php selectyes('http'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_mail'];?><a name="3"></a></div>
<div class="gendiv">
<?php selectyes('mail'); ?><br /><br />
<?php input('mailbox'); ?><br /><br />
<?php selectyes('viewentermail'); ?><br /><br />
<?php selectyes('replaymail'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_other'];?><a name="4"></a></div>
<div class="gendiv">
<?php input('listz'); ?><br /><br />
<?php selectsort('sort'); ?><br /><br />
Имя админа так задано в конце config.php, желательно не менять (или же менять сразу во всех скриптах commentit)<br />
<span style="color:#AB0000; font-weight:bold; font-family:Arial;"><?php echo $massparam['nameadmin']; ?></span><br /><br />
<?php input('formatdate'); ?><br /><br />
<?php input('correcttime'); ?><br /><br />
<?php input('specurl'); ?><br /><br />
<?php selectyes('hideaddform'); ?><br /><br />
<?php selectyes('autosize'); ?><br /><br />
<?php selectyes('useravatar'); ?><br /><br />
<?php selectyes('workcapt'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_level'];?><a name="5"></a></div>
<div class="gendiv">
<?php input('maxlevel');?><br /><br />
<?php input('pxlevel'); ?> px<br /><br />
<?php input('startpx'); ?> px<br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_latt'];?><a name="6"></a></div>
<div class="gendiv">
<?php input('lastcomment');?><br /><br />
<?php input('lastsum'); ?><br /><br />
<?php input('lastend'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_rss'];?><a name="7"></a></div>
<div class="gendiv">
<?php selectyes('rss'); ?><br /><br />
<?php input('titlecia');?><br /><br />
<?php input('descriptioncia'); ?><br /><br />
<?php selectyes('yaping'); ?><br /><br />
<?php selectyes('europing'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_mat'];?><a name="8"></a></div>
<div class="gendiv">
<?php selectyes('antimat'); ?><br /><br />
<?php selecteasy('antilevel'); ?><br /><br />
<?php input('antislovo'); ?><br /><br />
<?php input('dieslovo'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_rating'];?><a name="9"></a></div>
<div class="gendiv">
<?php selectyes('rating'); ?><br /><br />
<?php selectyes('ratingadmin'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<div class="titlediv"><?php echo $langcommentit['title_logiza'];?><a name="10"></a></div>
<div class="gendiv">
<?php echo $langcommentit['title_logiza2'];?><br />
<?php selectyes('loginzaglob'); ?><br /><br />
<?php selectyes('blockeasy'); ?><br /><br />
<?php selectyesico('vkontakte'); ?><br /><br />
<?php selectyesico('odnoklassniki'); ?><br /><br />
<?php selectyesico('facebook'); ?><br /><br />
<?php selectyesico('twitter'); ?><br /><br />
<?php selectyesico('yandex'); ?><br /><br />
<?php selectyesico('google'); ?><br /><br />
<?php selectyesico('mailru'); ?><br /><br />
<?php selectyesico('loginza'); ?><br /><br />
<?php selectyesico('myopenid'); ?><br /><br />
<?php selectyesico('openid'); ?><br /><br />
<?php selectyesico('webmoney'); ?><br /><br />
<?php selectyesico('lastfm'); ?><br /><br />
<?php selectyes('loginzavatar'); ?><br /><br />
<?php selectyes('loginzurl'); ?><br /><br />
<a href="#menu" title="<?php echo $langcommentit['top']; ?>"><b>&uarr;</b></a>
</div>

<br /><br />
<div style="margin-left:15px;"><input type="submit" name="go" value="<?php echo $langcommentit['button_save'];?>"></div>
<br />
</form>
<?php
} else {
$sqlclear = mysql_query("TRUNCATE TABLE `$table2`");
foreach ($_POST as $n=>$v)
if ($n!='go')
{
$sql='';
if ($n=='correcttime'&&!$v) {$v='+0 hours';}
$sql="INSERT INTO `$table2` (`id`, `par`, `val`, `tmp`) VALUES (NULL,'".mysql_real_escape_string($n)."','".mysql_real_escape_string($v)."','');";
$sqladd = mysql_query($sql) or die("ERROR:".mysql_error()."");
}
echo '<div style="margin:15px;">'.$langcommentit['title_save'].'</div>';
}
}
?>
<br /><br /><br /><br />
<div id="footer_utilities" class="clearfix clear rounded">
<div style="padding:10px; 0 0 10px;">
CommentiIt 5.2.12 Ajax &copy 2008-2014
</div>
</div>
</body>
</html>