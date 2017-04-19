<?php
error_reporting(0);
include_once (dirname(__FILE__).'/config.php');
include_once (dirname(__FILE__).'/func.php');
if ($massparam['moder']==1) {$querylast = mysql_query("select t1.* from `$table` as t1 join (select url, max(data) as max_date from `$table` as t0 WHERE moder=0 group by url) t2 on t1.data = t2.max_date order by t2.max_date desc, t1.url, t1.data desc limit ".$massparam['lastcomment']."");}
else {$querylast = mysql_query("select t1.* from `$table` as t1 join (select url, max(data) as max_date from `$table` as t0 group by url) t2 on t1.data = t2.max_date order by t2.max_date desc, t1.url, t1.data desc limit ".$massparam['lastcomment']."");}

echo "select t1.* from `$table` as t1 join (select url, max(data) as max_date from `$table` as t0 WHERE moder=0 group by url) t2 on t1.data = t2.max_date order by t2.max_date desc, t1.url, t1.data desc limit ".$massparam['lastcomment']."";

while ($myrowlast = mysql_fetch_row($querylast))
{
$urlz=$hostsite."".$myrowlast[1].'#commentit-'.$myrowlast[0];
$names=$myrowlast[2];
$comment_msg=$myrowlast[3];
$date=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($myrowlast[4]))));
$comment_msg=cuthtml($comment_msg);
$comment_msg=cutbb($comment_msg);
$comment_msg = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg=wordwrap($comment_msg, $massparam['wordcut'], " ", 1);
$comment_msg=viewworld($comment_msg,$massparam['lastsum']).$massparam['lastend'];
$comment_msg=parsesmile($comment_msg);
$comment_msg = str_replace('zzzxaqwedasdsad', '<img src="/'.$wwp.'', $comment_msg );
if ($myrowlast[15]==1) {$names=$massparam['nameadmin'];}
echo xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/last.html");
}
?>

