<?php
error_reporting(0);
include_once (dirname(__FILE__).'/config.php');
include_once (dirname(__FILE__).'/func.php');
if ($massparam['moder']==1) {$query = mysql_query("SELECT * FROM `$table` WHERE moder=0 ORDER BY data DESC LIMIT ".$massparam['lastcomment']."");}
else {$query = mysql_query("SELECT * FROM `$table` ORDER BY data DESC LIMIT ".$massparam['lastcomment']."");}
while ($myrow = mysql_fetch_row($query))
{
$urlz=$hostsite.''.$myrow[1].'#commentit-'.$myrow[0];
$names=$myrow[2];
$comment_msg=$myrow[3];
$date=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($myrow[4]))));
$comment_msg=cuthtml($comment_msg);
$comment_msg=cutbb($comment_msg);
$comment_msg = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $comment_msg); 
$comment_msg=wordwrap($comment_msg, $massparam['wordcut'], " ", 1); 
$comment_msg=viewworld($comment_msg,$massparam['lastsum']).$massparam['lastend'];
$comment_msg=parsesmile($comment_msg);
$comment_msg = str_replace('zzzxaqwedasdsad', '<img src="/'.$wwp.'', $comment_msg );
if ($myrow[15]==1) {$names=$massparam['nameadmin'];}
echo xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/last.html");
}
?>

