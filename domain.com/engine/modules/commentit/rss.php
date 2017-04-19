<?php
error_reporting(0);
header('Content-Type: text/xml; charset='.$codername.'');
include (dirname(__FILE__).'/config.php');
if ($massparam['rss']<>1) exit();
$rssheader=1;
include (dirname(__FILE__).'/func.php');
$siteurl='http://'.$_SERVER['HTTP_HOST'];
$url=$_GET['url'];
$url=mysql_real_escape_string($url);
$zap='';
if (!empty($url)) {$zap="url='$url' AND";$fulllink=$siteurl.''.$url;} else {$fulllink=$siteurl;}
//$massparam['titlecia']=utfcoder($massparam['titlecia']);
//$massparam['descriptioncia']=utfcoder($massparam['descriptioncia']);
######### Write RSS ##########
echo("<?xml version=\"1.0\" encoding=\"$codername\"?>\r\n");
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	>
	<channel>
<?
echo("<title>".$massparam['titlecia']."</title>\r\n");
echo("<link>$siteurl</link>\r\n");
echo("<description>".$massparam['descriptioncia']."</description>\r\n");
echo('<atom:link href="'.$siteurl.$_SERVER["REQUEST_URI"].'" rel="self" type="application/rss+xml" />'."\r\n");

$query = "SELECT * FROM $table WHERE $zap moder=0 ORDER BY data DESC";
$query = mysql_query($query);

while ($myrow = mysql_fetch_row($query))
{
$urlbase=$myrow[1];
if ($zip=='') {$fulllink=$siteurl.$urlbase;}
$comment_msg=$myrow[3];
$names=$myrow[2];
$date=date("Y-m-d H:i:s",strtotime($massparam['correcttime'],strtotime($myrow[4])));
$date=date("r",strtotime($date));
if ($myrow[15]==1) {$names=$massparam['nameadmin'];}
$comment_msg = str_replace('<br />', ' ', $comment_msg );
$comment_msg = cuthtml($comment_msg);
$comment_msg=cutbb($comment_msg);
$comment_msg = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg = stripslashes($comment_msg);
$comment_msg = htmlspecialchars($comment_msg);
$names = htmlspecialchars($names);
$names=$names.' - '.viewworld($comment_msg,$massparam['lastsum']).$massparam['lastend'];
	echo("\r\n<item>\r\n");
	echo("<title>".$names."</title>\r\n");
	echo("<pubDate>".$date."</pubDate>\r\n");
	echo("<link>".htmlspecialchars($fulllink)."</link>\r\n");
	echo("<description><![CDATA[ ".$comment_msg." ]]></description>\r\n");
	echo("</item>\r\n\r\n");

}
?>
</channel>
</rss>
