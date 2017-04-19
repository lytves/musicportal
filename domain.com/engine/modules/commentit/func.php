<?php error_reporting(0); include_once (dirname(__FILE__).'/config.php');include_once (dirname(__FILE__).'/lang/'.$mylang.'.php'); session_start();
if (!$rssheader) header("Content-type: text/html; charset=$codername");
if ($massparam['quotebb']==0||$massparam['bbpanel']==0) {$langcommentit['skin_reply_que']='';}
if ($massparam['loginzaglob']==1){$massparam['http']==0;$massparam['viewentermail']==0;}
##############
/**
* Не много уличной магии. Эти строки содержат сакральные знания, полученные годами отладки предыдущей версии.
* Рационально понять этот код невозможно, поэтому менять с осторожностью!
*/
if (@$_GET['n']) {commentrating($_GET['n'],$_GET['g']);exit();}
if ($massparam['specurl']=='') {$specurl=$_SERVER["REQUEST_URI"];} else {eval("\$specurl=\"$massparam[specurl]\";");} $url='';
$dataz=date("Y-m-d H:i:s");$url=@$_POST['url'];$htmlz="";$hostsite="http://".$_SERVER['SERVER_NAME'];$hostrss=$hostsite."/".$wwp."/rss.php";if (empty($url)) $url=@$_GET['url'];$zzzzzzzzzzzzz=@$_POST['addcomment'];
$viewread=@$_GET['viewread'];if (empty($zzzzzzzzzzzzz)&&($viewread<>"true")) {$url=$specurl;}if (!empty($zzzzzzzzzzzzz)) {addcom();view2();}
$ads=@$_GET['ads'];$nums=$ads;if (empty($nums)) {$nums=0;}if (!empty($nums)||($nums==0)&&($ads<>"")) {view2();} 
$url=$urlorig=mysql_real_escape_string($url);
##############

function wordWrapIgnoreHTML($string, $length = 50, $wrapString = " ")
{
global $coder;
$c='utf-8'; if ($coder) {$c='windows-1251';}
   $wrapped = '';
    $word = '';
    $html = false;
    $string = (string) $string;
    $stringLength = mb_strlen($string,$c);
    for($i=0;$i<$stringLength;$i++)
    {

      $char = mb_substr($string, $i, 1,$c );


      /** HTML Begins */
      if($char === '<' )
      {
        if(!empty($word))
        {
          $wrapped .= $word;
          $word = '';
        }

        $html = true;
        $wrapped .= $char;
      }

      /** HTML ends */
      elseif($char === '>' )
      {
        $html = false;
        $wrapped .= $char;
      }

      /** If this is inside HTML -> append to the wrapped string */
      elseif($html)
      {
        $wrapped .= $char;
      }

      /** Whitespace characted / new line */
      elseif($char === ' ' || $char === "\t" || $char === "\n" )
      {
        $wrapped .= $word.$char;
        $word = '';
      }

      /** Check chars */
      else
      {
        $word .= $char;
        $wordLength = mb_strlen($word,$c);
        if($wordLength > $length && $char == ";" &&  mb_substr ($string, $i+1, 1,$c)== "&" )
        {

          $wrapped .= $word.$wrapString;
          $word = '';
        }
        else if ($wordLength > $length && !preg_match("/&.{1,7};/i", $word))
        {

          $wrapped .= $word.$wrapString;
          $word = '';
        }
        else if ($wordLength > $length && !preg_match("/&.{1,7};&/i", $word))
        {

          $wrapped .= $word.$wrapString;
          $word = '';
        }

      }
    }
    if($word !== '')
    {
      $wrapped .= $word;
    }

    return $wrapped;
}

function mont ($date)
{
global $coder;
if ($coder){
$date=str_replace('January', 'Января', $date);
$date=str_replace('February', 'Февраля', $date);
$date=str_replace('March', 'Марта', $date);
$date=str_replace('April', 'Апреля', $date);
$date=str_replace('May', 'Мая', $date);
$date=str_replace('June', 'Июня', $date);
$date=str_replace('July', 'Июля', $date);
$date=str_replace('August', 'Августа', $date);
$date=str_replace('September', 'Сентября', $date);
$date=str_replace('October', 'Октября', $date);
$date=str_replace('November', 'Ноября', $date);
$date=str_replace('December', 'Декабря', $date);
}
else
{
$date=str_replace('January', 'РЇРЅРІР°СЂСЏ', $date);
$date=str_replace('February', 'Р¤РµРІСЂР°Р»СЏ', $date);
$date=str_replace('March', 'РњР°СЂС‚Р°', $date);
$date=str_replace('April', 'РђРїСЂРµР»СЏ', $date);
$date=str_replace('May', 'РњР°СЏ', $date);
$date=str_replace('June', 'РСЋРЅСЏ', $date);
$date=str_replace('July', 'РСЋР»СЏ', $date);
$date=str_replace('August', 'РђРІРіСѓСЃС‚Р°', $date);
$date=str_replace('September', 'РЎРµРЅС‚СЏР±СЂСЏ', $date);
$date=str_replace('October', 'РћРєС‚СЏР±СЂСЏ', $date);
$date=str_replace('November', 'РќРѕСЏР±СЂСЏ', $date);
$date=str_replace('December', 'Р”РµРєР°Р±СЂСЏ', $date);
}
return $date;
}


function parsesmile ($comment_msg)
{
$comment_msg = str_replace(':)', 'zzzxaqwedasdsad/im/sml_1.gif" border="0" alt=" :)" title=" :)"  />', $comment_msg );
$comment_msg = str_replace('!;)', 'zzzxaqwedasdsad/im/sml_2.gif" border="0" alt="  !;)" title=" !;) " />', $comment_msg );
$comment_msg = str_replace(':D', 'zzzxaqwedasdsad/im/sml_3.gif" border="0" alt=" :D " title=" :D " />', $comment_msg );
$comment_msg = str_replace(':(', 'zzzxaqwedasdsad/im/sml_6.gif" border="0" alt=" :( " title=" :( " />', $comment_msg );
$comment_msg = str_replace('=)', 'zzzxaqwedasdsad/im/sml_4.gif" border="0" alt=" =) " title=" =) " />', $comment_msg );
$comment_msg = str_replace('?)', 'zzzxaqwedasdsad/im/sml_5.gif" border="0" alt=" ?) " title=" ?)"  />', $comment_msg );
$comment_msg = str_replace(':ups:', 'zzzxaqwedasdsad/im/sml_7.gif" border="0" alt=" :ups: " title=" :ups: " />', $comment_msg );
$comment_msg = str_replace(':cool:', 'zzzxaqwedasdsad/im/sml_8.gif" border="0" alt=" :cool: " title=" :cool: " />', $comment_msg );
$comment_msg = str_replace(':bad:', 'zzzxaqwedasdsad/im/sml_9.gif" border="0" alt=" :bad: " title=" :bad: " />', $comment_msg );
$comment_msg = str_replace(':like:', 'zzzxaqwedasdsad/im/sml_10.gif" border="0" alt=" :like: " title=" :like: " />', $comment_msg );
$comment_msg = str_replace(':angel:', 'zzzxaqwedasdsad/im/sml_11.gif" border="0" alt=" :angel: " title=" :angel: " />', $comment_msg );
$comment_msg = str_replace(':love:', 'zzzxaqwedasdsad/im/sml_12.gif" border="0" alt=" :love: " title=" :love: " />', $comment_msg );
return $comment_msg;
}

function cutbb ($comment_msg)
{
$comment_msg = preg_replace("#\[b\](.*?)\[/b\]#si", " \\1 ", $comment_msg);  
$comment_msg = preg_replace("#\[i\](.*?)\[/i\]#si", " \\1 ", $comment_msg);  
$comment_msg = preg_replace("#\[u\](.*?)\[/u\]#si", " \\1 ", $comment_msg);  
$comment_msg = preg_replace("#\[s\](.*?)\[/s\]#si", " \\1 ", $comment_msg);  
$comment_msg = preg_replace("#\[img=(.*?)\]#si", " \\1 ", $comment_msg);  
$comment_msg = preg_replace("#\[size=(.*?)\](.*?)\[/size\]#si", " \\2 ", $comment_msg); 
$comment_msg = preg_replace("#\[color=(.*?)\](.*?)\[/color\]#si", " \\2 ", $comment_msg);  
$comment_msg = preg_replace("#\[quote\](.*?)\[/quote\]#si", " ", $comment_msg); 
$comment_msg = preg_replace("#\[just=(.*?)\](.*?)\[/just\]#si", " \\2 ", $comment_msg);  
return $comment_msg;
}

function cuthtml ($comment_msg)
{
global $codername;
$comment_msg=str_replace("\x01"," ",$comment_msg);
$comment_msg=str_replace("\x02"," ",$comment_msg);
$comment_msg=str_replace("\x03"," ",$comment_msg);
$comment_msg=str_replace("\x04"," ",$comment_msg);
$comment_msg=str_replace("\x05"," ",$comment_msg);
$comment_msg=str_replace("\x07"," ",$comment_msg);
$comment_msg=str_replace("\x00"," ",$comment_msg);
$comment_msg=str_replace("\x08"," ",$comment_msg);
$comment_msg=str_replace("\x0D"," ",$comment_msg);
$comment_msg=str_replace("\x0A"," ",$comment_msg);
$comment_msg=str_replace('<br />', ' ', $comment_msg );
$comment_msg=htmlspecialchars($comment_msg, ENT_QUOTES,$codername);
return $comment_msg;
}

function viewworld($msg,$kolvo){$msg=explode(" ",$msg);$i=0;$out="";while ($i<>($kolvo+1)){$out.=$msg[$i]." ";$i++;}return $out;}

function xparse($fn,$text="",$LOCALS=array()) {
   if (empty($text)) $text=myfile($fn);
   $text=preg_replace("!^ *(#|//).*[\r\n]*!m","",$text);
   $text=preg_replace("!^  +!m"," ",$text);
   if (strpos($text,"\\=")!==false) 
      $text=preg_replace("![\\\\]=([a-f0-9]{2})!e","chr(hexdec('\\1'))",$text);
   return preg_replace("!\{\\\$([a-z_][a-z0-9_]*)(\[([a-z0-9_-]+)\])?(\[([a-z0-9_-]+)\])?\}!ie","xparse_('\\1','\\3','\\5',\$LOCALS,\$fn)",$text);
}
function myfile($name) {   $f=@fopen($name,"rb");   return @fread($f,@filesize($name)).myclose($f);}
function myclose($f) {   @fclose($f);   return "";}


function xparse_($n1,$n2,$n3,$LOCALS,$fn) {
$LOCALS=get_defined_vars();
   if ($n2=="") {
      if (isset($LOCALS[$n1])) return $LOCALS[$n1];
      if (isset($GLOBALS[$n1])) return $GLOBALS[$n1];
      return "{XPARSE_ERROR_ON_\${$n1}_$fn}";
   }
   if ($n3=="") {
      if (isset($LOCALS[$n1][$n2])) return $LOCALS[$n1][$n2];
      if (isset($GLOBALS[$n1][$n2])) return $GLOBALS[$n1][$n2];
      return "{XPARSE_ERROR_ON_\${$n1}[$n2]_$fn}";
   }
   if (isset($LOCALS[$n1][$n2][$n3])) return $LOCALS[$n1][$n2][$n3];
   if (isset($GLOBALS[$n1][$n2][$n3])) return $GLOBALS[$n1][$n2][$n3];
   return "{XPARSE_ERROR_ON_\${$n1}[$n2][$n3]_$fn}";
}


#######
function getComments($mass,$start=0,$end=999999999) {
global $massparam,$comment_msg,$names,$url,$date,$table,$wwp,$coder,$zzzzzzzzzzzzz,$langcommentit,$nums,$idcom,$divonter,$ratingcomment,$commentpx,$scravatar,$ratingval,$ratingcolor,$ratingznak,$icoservice,$staticcommentit,$is_register;
$i=0;
foreach ($mass as $row)
{
if ($row['level']==0) {if ($i<$start) {$i++;$end++;continue;}if ($i>=$end) {break;}}
$comment_msg=$row['comm'];
$idcom=$row['num'];
$names=$row['name'];
$is_register=$row['is_register'];
$viewhttp=$row['http'];
$ratingval=intval($row['raitng']);
$miniavatar=$row['avatar'];
$admincom=$row['admincom'];
if (@$massparam['http']==1&&$viewhttp<>"") {
if ($massparam['linknofol']==0) {$names='<a target="_blank" href="'.$viewhttp.'">'.$names.'</a>';}
if ($massparam['linknofol']==1) {$names='<!--noindex--><a target="_blank" rel="nofollow" href="'.$viewhttp.'">'.$names.'</a><!--/noindex-->';}
}

$date=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($row['data']))));
$commentpx=$massparam['pxlevel']*$row['level']+$massparam['startpx'];
$scravatar=''; if ($massparam['useravatar']>0) {@$scravatar='<img src="'.str_replace($_SERVER['DOCUMENT_ROOT'],'',$textava[array_rand($textava=glob($_SERVER['DOCUMENT_ROOT']."/".$wwp."/avatars/"."*.*"))]).'" border="0" />';}
$icoservice='';
if ($massparam['loginzaglob']==1&&$massparam['loginzurl']==1&&$row['service']) {
if ($massparam['linknofol']==0) {$names='<a target="_blank" href="'.$viewhttp.'">'.$names.'</a>';}
if ($massparam['linknofol']==1) {$names='<!--noindex--><a target="_blank" rel="nofollow" href="'.$viewhttp.'">'.$names.'</a><!--/noindex-->';}

if ($massparam['loginzaglob']==1&&$massparam['loginzavatar']==1&&$miniavatar){$scravatar='<img src="'.$miniavatar.'" border="0" alt="" title="" />';}

if (preg_match('@yandex@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/yandex.png" alt="Yandex" title="Yandex" />';}
if (preg_match('@google@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/google.png" alt="Google" title="Google Accounts" />';}
if (preg_match('@vk\.com@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/vkontakte.png" alt="Вконтакте" title="Вконтакте" />';}
if (preg_match('@mail@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/mailru.png" alt="Mail.ru" title="Mail.ru" />';}
if (preg_match('@twitter@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/twitter.png" alt="Twitter" title="Twitter" />';}
if (preg_match('@loginza@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/loginza.png" alt="Loginza" title="Loginza" />';}
if (preg_match('@openid@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/openid.png" alt="OpenID" title="OpenID" />';}
if (preg_match('@myopenid@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/myopenid.png" alt="MyOpenID" title="MyOpenID" />';}
if (preg_match('@webmoney@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/webmoney.png" alt="WebMoney" title="WebMoney" />';}
if (preg_match('@facebook@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/facebook.png" alt="Facebook" title="Facebook" />';}
if (preg_match('@last@smi',$row['service'])) {$icoservice='<img src="/'.$wwp.'/im/lastfm.png" alt="Lastfm" title="Lastfm" />'; $scravatar=str_replace('<img','<img style="width:50px;height:50px;" ',$scravatar);}
}

if ($is_register == 1) {
  $names=$row['name'];
  if ($names == $massparam['nameadmin']) $names='<a title="Смотреть профиль '.$names.'" href="/user/'.urlencode($names).'/" style="color:#AB0000">'.$names.'</a>';
  else $names='<a title="Смотреть профиль '.$names.'" href="/user/'.urlencode($names).'/">'.$names.'</a>';
}


$ratingcomment='';
if ($massparam['rating']==1){
$ratingcolor='#CCC';
$ratingznak='';
if ($ratingval>0) {$ratingcolor='#339900';$ratingznak='+';}
if ($ratingval<0) {$ratingcolor='#FF0000';}
$ratingcomment=xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/rating.html",'',get_defined_vars());
}

$divonter='';
if ($massparam['maxlevel']>0){
if ($massparam['hideaddform']==1){
	if ((!$zzzzzzzzzzzzz)) 
	{
	$divonter=xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/replay.html",'',get_defined_vars());
	}
	else {$divonter="";}
}
else
{
$divonter=xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/replay.html",'',get_defined_vars());
}
}
$comment_msg = str_replace('<br />', '_br_', $comment_msg );
$comment_msg=cuthtml($comment_msg);
$comment_msg = str_replace('_br_', '<br />', $comment_msg );

if ($massparam['maxlevel']>0) {$comment_msg='<div id="z'.$idcom.'">'.$comment_msg.'</div>';}

if ($massparam['bbpanel']==1)
{
$comment_msg = preg_replace("#\[b\](.*?)\[/b\]#si", "<b>\\1</b>", $comment_msg);  
$comment_msg = preg_replace("#\[i\](.*?)\[/i\]#si", "<i>\\1</i>", $comment_msg);  
$comment_msg = preg_replace("#\[u\](.*?)\[/u\]#si", "<u>\\1</u>", $comment_msg);  
$comment_msg = preg_replace("#\[s\](.*?)\[/s\]#si", "<strike>\\1</u></strike>", $comment_msg);  

if ($massparam['picbb']==1) {
$wpor=''; if ($massparam['limitsizew']) {$wpor='width="'.$massparam['limitsizew'].'"';}
$hpor=''; if ($massparam['limitsizeh']) {$hpor='height="'.$massparam['limitsizeh'].'"';}
if ($massparam['picpars']==1) {
preg_match_all("#\[img(.*?)\]#si",$comment_msg,$tmp);
foreach ($tmp[0] as $n)
{
if (preg_match("@".$_SERVER['SERVER_NAME']."@smi",$n)){$tmp2 = preg_replace("#\[img=(.*?)\]#si", "<img src=\"\\1\" $wpor $hpor border=\"0\" alt=\"\" title=\"\" />", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);}
else{$tmp2 = preg_replace("#\[img=(.*?)\]#si", "\\1", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);}
}
}
else{ $comment_msg = preg_replace("#\[img=(.*?)\]#si", "<img src=\"\\1\" $wpor $hpor border=\"0\" alt=\"\" title=\"\" />", $comment_msg);}

}

if ($massparam['sizebb']==1) {
preg_match("#\[size=(.*?)\]#si",$comment_msg,$tmp);
@$tmp[0]=strtolower($tmp[0]);
$tmp[0]=str_replace('[size=','',$tmp[0]);
$tmp[0]=str_replace(']','',$tmp[0]);
$spssize=intval($tmp[0]);
if ($spssize<1||$spssize>7) $spssize='12pt';
if ($spssize==1) $spssize='8pt';
if ($spssize==2) $spssize='10pt';
if ($spssize==3) $spssize='12pt';
if ($spssize==4) $spssize='14pt';
if ($spssize==5) $spssize='18pt';
if ($spssize==6) $spssize='24pt';
if ($spssize==7) $spssize='36pt';
$comment_msg = preg_replace("#\[size=(.*?)\](.*?)\[/size\]#si", "<span style=\"font-size:$spssize;\">\\2</span>", $comment_msg); 
}

if ($massparam['justbb']==1) {
preg_match("#\[just=(.*?)\]#si",$comment_msg,$tmp);
@$tmp[0]=strtolower($tmp[0]);
$tmp[0]=str_replace('[size=','',$tmp[0]);
$tmp[0]=str_replace(']','',$tmp[0]);
$spsjust=$tmp[0];
if ($spsjust<>'center'||$spsjust<>'right'||$spsjust<>'left') $spssize='left';
$comment_msg = preg_replace("#\[just=(.*?)\](.*?)\[/just\]#si", "<p align=\"\\1\">\\2</p>", $comment_msg); 
}

if ($massparam['colorbb']==1) {
while (strpos($comment_msg,'[/COLOR]')) 
{
$masscolor=array("black"=>"#000000","sienna"=>"#a0522d","darkolivegreen"=>"#556b2f","darkgreen"=>"#006400","darkslateblue"=>"#483d8b","navy"=>"#000080","indigo"=>"#4b0082","darkslategray"=>"#2f4f4f","darkred"=>"#8b0000","darkorange"=>"#ff8c00","olive"=>"#808000","green"=>"#008000","teal"=>"#008080","blue"=>"#0000ff","slategray"=>"#708090","dimgray"=>"#696969","red"=>"#ff0000","sandybrown"=>"#f4a460","yellowgreen"=>"#9acd32","seagreen"=>"#2e8b57","mediumturquoise"=>"#48d1cc","royalblue"=>"#4169e1","purple"=>"#800080","gray"=>"#808080","magenta"=>"#ff00ff","orange"=>"#ffa500","yellow"=>"#ffff00","lime"=>"#00ff00","cyan"=>"#00ffff","deepskyblue"=>"#00bfff","darkorchid"=>"#9932cc","silver"=>"#c0c0c0","pink"=>"#ffc0cb","wheat"=>"#f5deb3","lemonchiffon"=>"#fffacd","palegreen"=>"#98fb98","paleturquoise"=>"#afeeee","lightblue"=>"#add8e6","plum"=>"#dda0dd","white"=>"#ffffff");
preg_match("#\[color=(.*?)\]#si",$comment_msg,$tmp);
$tmp[0]=strtolower($tmp[0]);$tmp[0]=str_replace('[color=','',$tmp[0]);$oldcolor=str_replace(']','',$tmp[0]);$colorz=$masscolor[$oldcolor];if (!$colorz) $colorz='#000';
$comment_msg = preg_replace("#\[color=$oldcolor\](.*?)\[/color\]#si", "<span style=\"color:$colorz;\">\\1</span>", $comment_msg);  
}
}

if ($massparam['quotebb']==1) {
$comment_msg = preg_replace("#\[quote\](.*?)\[/quote\]#si", "<!--noindex--><div style=\"".$massparam['quotestyle']."\">\\1</div><!--/noindex-->", $comment_msg);  
}


if ($massparam['linkbb']==1) {

if ($massparam['linkpars']==1) {
preg_match_all("#\[url(.*?)url\]#si",$comment_msg,$tmp);
foreach ($tmp[0] as $n)
{
if (preg_match("@".$_SERVER['SERVER_NAME']."@smi",$n)){
$tmp2 = preg_replace("#\[url=(.*?)\](.+?)\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\2</a><!--/noindex-->", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);
$tmp2 = preg_replace("#\[url=(.*?)\]\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a><!--/noindex-->", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);
$tmp2 = preg_replace("#\[url\](.*?)\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a><!--/noindex-->", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);

}
else{
$tmp2 = preg_replace("#\[url=(.*?)\](.+?)\[/url\]#si", "\\1", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);
$tmp2 = preg_replace("#\[url=(.*?)\]\[/url\]#si", "\\1", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);
$tmp2 = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $n); $comment_msg=str_replace($n,$tmp2,$comment_msg);
}
}
}
else{
if ($massparam['linknofol']==1) {
$comment_msg = preg_replace("#\[url=(.*?)\](.+?)\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\2</a><!--/noindex-->", $comment_msg);  
$comment_msg = preg_replace("#\[url=(.*?)\]\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a><!--/noindex-->", $comment_msg);  
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "<!--noindex--><a rel=\"nofollow\" target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a><!--/noindex-->", $comment_msg);  
}
if ($massparam['linknofol']==0) {
$comment_msg = preg_replace("#\[url=(.*?)\](.+?)\[/url\]#si", "<a target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\2</a>", $comment_msg);  
$comment_msg = preg_replace("#\[url=(.*?)\]\[/url\]#si", "<a target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a>", $comment_msg); 
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "<a target=\"_blank\" href=\"".$massparam['linkredirect']."\\1\">\\1</a>", $comment_msg);  
}
}
}

}

if ($massparam['smile']==1) $comment_msg = parsesmile($comment_msg);
$comment_msg = str_replace('zzzxaqwedasdsad', '<img src="/'.$wwp.'', $comment_msg );
$comment_msg=wordWrapIgnoreHTML($comment_msg,$massparam['wordcut']);

if (isset($staticcommentit)||(@$_GET['static'])) {$divonter='';}

$adminskinz='viewcom.html';if ($admincom==1) {$adminskinz='adminviewcom.html';if ($massparam['ratingadmin']==0){$ratingcomment='';}}
$names='<span id="n'.$idcom.'">'.$names.'</span>';
echo '<a name="commentit-'.$idcom.'"></a>'.xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/".$adminskinz."",'',get_defined_vars());


if (sizeof($row['children'])>0) {
if ($massparam['sort']>=3) {$row['children']=array_reverse($row['children']);}
getComments($row['children']);
}

$i++;
}
}
#######

function build_hierarchy($arr, $id_key = 'id', $pid_key = 'parent_id') {
    $structure = array(); 
    while($elem = array_shift($arr)) {
        if(isset($structure[ $elem[$id_key] ])) {
            $elem['children'] = $structure[ $elem[$id_key] ];
            unset($structure[ $elem[$id_key] ]);
        } else
            $elem['children'] = array();
        if(isset($references[ $elem[$pid_key] ])) {
            $references[ $elem[$pid_key] ]['children'][ $elem[$id_key] ] = $elem;
            $references[ $elem[$id_key] ] =& $references[ $elem[$pid_key] ]['children'][ $elem[$id_key] ];
        } else {
            $structure[ $elem[$pid_key] ][ $elem[$id_key] ] = $elem;
            $references[ $elem[$id_key] ] =& $structure[ $elem[$pid_key] ][ $elem[$id_key] ];
        }
		asort( $references );
    }
    return array($structure);
} 

function view2(){ 
global $massparam,$comment_msg,$names,$url,$dataz,$table,$wwp,$coder,$zzzzzzzzzzzzz,$langcommentit,$nums,$staticcommentit;
$url=str_replace('|P|','&',$url);
$url=str_replace('|Z|','?',$url);
$dex='';if ($massparam['sort']==1) {$dex="DESC";}
$dexrat='data';if ($massparam['sort']==2) {$dexrat="raitng";$dex="DESC";}
$rpp=999999999999;if ($massparam['listz']<>0) {$rpp=$massparam['listz'];}
$page=0;
$moderzap='';if ($massparam['moder']==1) {$moderzap='AND moder=0';}

$resultget = mysql_query("SELECT * FROM `$table` WHERE url='$url' $moderzap ORDER BY $dexrat $dex ");
while ($myrow = mysql_fetch_array($resultget)){$bigmass[]=$myrow;}
echo '<span class="tit" style="display:block;">Комментарии:</span>';
if (count($bigmass) == 0) echo '<br/><span style="margin:0 0 0 10px;">'.$langcommentit['skin_first_post'].'</span>';
$cnt=0;
foreach ($bigmass as $n)
{

if ($massparam['moder'])
{
if ($n['level']==0&&$n['moder']==0) {$cnt++;}
}
else
{
if ($n['level']==0) {$cnt++;}
}
}
$pages=ceil($cnt/$rpp);
if (($zzzzzzzzzzzzz)&&($massparam['sort']==0)) {$nums=$pages-1;if ($nums<0) $nums=0;}
$rad=3;$page=$nums;

$bigmass=build_hierarchy($bigmass,'num','rootid');
getComments($bigmass[0][0],($nums*$rpp),$rpp);


if ($massparam['listz']<>0) {
##### Постраничная навигация #####
if (!isset($staticcommentit)) {$staticcommentit=@$_GET['static'];}
//$links=$rad*2+1;
if ($pages>1) {
$links=$pages;
$url=str_replace('&','|P|',$url);
$url=str_replace('?','|Z|',$url);
if ($pages>0) {echo '<div class="commentit_pages">';}
if ($page>0) { echo "<a onclick=\" var oldid=document.getElementById('oldid').value; makeRequest('/".$wwp."/func.php?viewread=true&ads=".($page-1)."&url=".$url."&static=".$staticcommentit."','ok',oldid)\">".$langcommentit['skin_pages_prev']."</a>"; }
$start=$page-$rad;
if ($start>$pages-$links) { $start=$pages-$links; }
if ($start<0) { $start=0; }
$end=$start+$links;
if ($end>$pages) { $end=$pages; }
for ($i=$start; $i<$end; $i++) {
 echo " ";
 
 if ($i==$page) {
  echo "<span>";
 } else {
  echo "<a onclick=\" var oldid=document.getElementById('oldid').value; makeRequest('/".$wwp."/func.php?viewread=true&ads=".($i)."&url=".$url."&static=".$staticcommentit."','ok',oldid)\">";
 }

 echo ($i+1);
 if ($i==$page) {
  echo "</span>";
 } else {
  echo "</a>";
 }
}
if ($pages>$links&&$page<($pages-$rad-1)) { echo " ... <a onclick=\" var oldid=document.getElementById('oldid').value; makeRequest('/".$wwp."/func.php?viewread=true&ads=".($pages)."&url=".$url."&static=".$staticcommentit."','ok',oldid)\">".($pages)."</a>"; }

if ($page<$pages-1) { echo " <a onclick=\" var oldid=document.getElementById('oldid').value; makeRequest('/".$wwp."/func.php?viewread=true&ads=".($page+1)."&url=".($url)."&static=".$staticcommentit."','ok',oldid)\">".$langcommentit['skin_pages_next']."</a>"; }
if ($pages>0) {echo '</div>';}
}
##### Постраничная навигация #####
}
}


function addcom(){
global $massparam,$comment,$namenew,$url,$mailz,$dataz,$table,$wwp,$coder,$idcomnow,$zzzzzzzzzzzzz,$captcha_keystring,$keystring,$hostsite,$hostrss,$langcommentit,$pass,$djos;

$f_pregmat='~'.
   '[nн][иеie][hхx][уyu][йyяij]|'.
   '[hхx][уyu][eеЁ][tlvлвт]|'.
   '[hхx][уyu][йyijoeоеёЁ]+[vwbв][oiоы]|'.
   '[pп][ieие][dдg][eaoеао][rpр]|'.
   '[scс][yuу][kк][aiuаи]|'.
   '[scс][yuу][4ч][кk]|'.  
   '[3zsз][aа][eiе][bpб][iи]|'.
   '[^н][eе][bpб][aа][lл]|'.
   'fuck|xyu|хуй|залупа|залупу|гавно|говно|гамно|гомно|ебат|ебан|ёбан|ёбат|ахуе|охуе|'.
   '[pп][iи][zsз3сs][dд][аеуоaeuo]|'.
   '[z3ж]h?[оo][pп][aаyуыiеe]'.
   '~si';

   $f_pregmat2='~'.
   ' [hхx][уyu][йyяij]|'.
   ' л *о *х |'.
   ' [бb6][лl]([яy]|ay)|'.
   ' [eiе][bpб][iи]|'.
   ' [eiе][bpб][aeаеёЁ][tlnтлн]|'.
   ' п *и *з *д|'.
   ' м *у *д *а|'.
   ' залуп'.
   '~si';

   $f_pregmatutf='~'.
   '[nРЅ][РёРµie][hС…x][Сѓyu][Р№yСЏij]|'.
   '[hС…x][Сѓyu][eРµРЃ][tlvР»РІС‚]|'.
   '[hС…x][Сѓyu][Р№yijoeРѕРµС‘РЃ]+[vwbРІ][oiРѕС‹]|'.
   '[pРї][ieРёРµ][dРґg][eaoРµР°Рѕ][rpСЂ]|'.
   '[scСЃ][yuСѓ][kРє][aiuР°Рё]|'.
   '[scСЃ][yuСѓ][4С‡][Рєk]|'.  
   '[3zsР·][aР°][eiРµ][bpР±][iРё]|'.
   '[^РЅ][eРµ][bpР±][aР°][lР»]|'.
   'fuck|xyu|С…СѓР№|'.
   '[pРї][iРё][zsР·3СЃs][dРґ][Р°РµСѓРѕaeuo]|'.
   '[z3Р¶]h?[Рѕo][pРї][aР°yСѓС‹iРµe]'.
   '~si';

   $f_pregmatuft2='~'.
   ' [hС…x][Сѓyu][Р№yСЏij]|'.
   ' Р» *Рѕ *С… |'.
   ' [Р±b6][Р»l]([СЏy]|ay)|'.
   ' [eiРµ][bpР±][iРё]|'.
   ' [eiРµ][bpР±][aeР°РµС‘РЃ][tlnС‚Р»РЅ]|'.
   ' Рї *Рё *Р· *Рґ|'.
   ' Рј *Сѓ *Рґ *Р°|'.
   ' Р·Р°Р»СѓРї'.
   '~si';

$usurl='';
$service='';
$avatar='';
if ($massparam['loginzaglob']==1){
$djos=$_SESSION['djos'];
if (empty($djos['error_message'])){
if (preg_match('@vk\.com@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['first_name'].' '.$djos['name']['last_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/vkontakte.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@twitter@smi',$djos['provider'])) {$oldname=utfcoder($djos['nickname']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/twitter.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@mail\.ru@smi',$djos['provider'])) {$oldname=str_replace('http://openid.mail.ru/mail/','',utfcoder($djos['identity']));$icoservice=str_replace('http://openid.mail.ru/mail/','',$oldname).' <img src="/'.$wwp.'/im/mailru.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';}

if (preg_match('@yandex@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);
$oldname=str_replace('openid.yandex.ru/','',$oldname);
$oldname=str_replace('/','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/yandex.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}

if (preg_match('@myopenid\.com@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);
$oldname=str_replace('.myopenid.com/','',$oldname);
$oldname=str_replace('.myopenid.com','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/myopenid.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}

if (preg_match('@google@smi',$djos['provider'])) {$oldname=utfcoder($djos['name']['first_name']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/google.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';}


if (preg_match('@loginza\.ru@smi',$djos['provider'])) {$oldname=utfcoder($djos['nickname']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/loginza.png" atl="" title="" border="0" />';
$checkavatar=str_replace('http://loginza.ru/users/avatars/','',$djos['photo']);
$langcommentit['skin_name']='';
if ($checkavatar) {$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}
}

if (preg_match('@last\.fm@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['full_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/lastfm.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img style="width:50px;height:50px;"  src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@webmoney@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);$oldname=str_replace('.webmoney.ru/','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/webmoney.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}

if (preg_match('@facebook\.com@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['full_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/facebook.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

$namenew=$oldname;
$avatar=$djos['photo'];
$service=$djos['provider'];
$usurl=$djos['identity'];
}}
else {
$usurl=@$_POST['usurl'];
$namenew=$_POST['namenew'];
}

if ($massparam['blockeasy']&&!$namenew)
{
$namenew=$_POST['namenew'];
$usurl=@$_POST['usurl'];
}

$admincom=0;if ($namenew==$pass || ($_SESSION['dle_name']==$massparam['nameadmin'])) {$admincom=1;}
$comment=$_POST['comment'];

$usmail='';$usmail=@$_POST['usmail'];
$idcomnow=intval($_POST['idcomnow']);
$level='';
if ($massparam['maxlevel']>0) {
$SQLid = "SELECT level FROM $table WHERE num=$idcomnow";
$resultid = @mysql_query($SQLid);
    if (mysql_num_rows($resultid)){
    $rowid = mysql_fetch_array($resultid);
		if ($massparam['maxlevel']==$rowid[level]) 
		{$level=1;} 
		else 
		{$level=$rowid[level]+1;
		}
	}
	else {
		 $level=0;
		}
}

$captcha_keystring=@$_SESSION['captcha_keystring'];
$keystring=@$_POST['keystring'];
$bad="";
//Блок проверки на сервере, чтобы случайно не попал спам, если даже был обход Ajax-а
if(empty($namenew)) { 
$bad=$langcommentit['bad_name'];
$bad.='<br />';
}

$commentx=preg_replace('@\[.*?\]@smi','',$comment);

if(empty($comment)||empty($commentx)) { 
$bad=$langcommentit['bad_comment'];
$bad.='<br />';
}

if($usmail) { 
if (!preg_match("/^[A-Z0-9._%-]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{1,30}$/i",$usmail))
{
$bad=$langcommentit['bad_mail'];
$bad.='<br />';
}
}
/// jquery ф-ция для выполнения после ajax-запроса
echo '$(document).ready(function() {
$("div.replydiv_commentit").css("opacity", "0");
$("div.view_com_commentit").hover(function() {
$("div.replydiv_commentit", this).stop().fadeTo("fast", 1);},
function() {
$("div.replydiv_commentit", this).stop().fadeTo("fast", .0);
});
});$##$##$';
/// конец jquery ф-ция для выполнения после ajax-запроса

if ($namenew<>$pass){
if ($massparam['workcapt']==1) {
	if(isset($captcha_keystring) && $captcha_keystring == $keystring){
	echo '<input type="hidden" name="errorcamp" id="errorcamp" value="0">';
	}
	else
	{
	echo '<input type="hidden" name="errorcamp" id="errorcamp" value="1">';
	$bad.=$langcommentit['bad_capt'];
	$bad.='<br />';
	}
}
else {echo '<input type="hidden" name="errorcamp" id="errorcamp" value="0">';}
}
else 
{
//Да Вы батенька админ
echo '<input type="hidden" name="errorcamp" id="errorcamp" value="0">';
echo '<input type="hidden" name="moder" id="moder" value="1">';
$massparam['moder']=0;
}

//if (eregi("http:",$comment)) $bad.="<br />Уберите ссылки с комментария <br />"; //Не основная функция скрипта. Но если Вас в конец задрали спамеры, уберите первые наклонные палочки //


if ($bad) 
{
echo $langcommentit['bad_echo'];
echo '<br />'.$bad;
//Подрубаем шаманский бубен
if ($idcomnow<>0) 
	{
	echo '<input type="hidden" name="commentvis" id="commentvis" value="1">';
	viewform();
	}
exit();
}
//-------------------------------------
if(empty($bad)) { 
$comment=str_replace("\n","<br />",$comment);
while ((preg_match('/(<br[^>]*>\s*)(<br[^>]*>\s*)(<br[^>]*>\s*)/i',$comment))==true) {$comment=preg_replace('/(<br[^>]*>\s*)(<br[^>]*>\s*)(<br[^>]*>\s*)/i','<br />',$comment);}
$comment = utfcoder($comment);
$dieadd=0;
if ($massparam['dieslovo'])
{
$badslova=explode(' ',$massparam['dieslovo']);
foreach ($badslova as $z)
{
if ($z) {if (preg_match('@'.$z.'@smi',$comment)) {$dieadd=1;break;}}
}
}
if (!$url) {$dieadd=1;}

if (!$dieadd){
if ($massparam['loginzaglob']==0){$namenew = utfcoder($namenew);}
if ($massparam['loginzaglob']&&$massparam['blockeasy']&&!empty($djos['error_message'])){$namenew = utfcoder($namenew);}
$dataz = utfcoder($dataz);
$usurl = utfcoder($usurl);

if ($coder==1) {
if ($massparam['antimat']==1)
{
	if ($massparam['antilevel']==0)
	{
	$comment=preg_replace($f_pregmat, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmat, $massparam['antislovo'], $namenew);
	}
	if ($massparam['antilevel']==1)
	{
	$comment=preg_replace($f_pregmat, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmat, $massparam['antislovo'], $namenew);
	$comment=preg_replace($f_pregmat2, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmat2, $massparam['antislovo'], $namenew);
	}
}
}
else 
{
if ($massparam['antimat']==1)
{
	if ($massparam['antilevel']==0)
	{
	$comment=preg_replace($f_pregmatutf, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmatutf, $massparam['antislovo'], $namenew);
	}
	if ($massparam['antilevel']==1)
	{
	$comment=preg_replace($f_pregmatutf, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmatutf, $massparam['antislovo'], $namenew);
	$comment=preg_replace($f_pregmatuft2, $massparam['antislovo'], $comment);
	$namenew=preg_replace($f_pregmatuft2, $massparam['antislovo'], $namenew);
	}
}

}
 if (get_magic_quotes_gpc()) {$namenew = stripslashes($namenew);$comment=stripslashes($comment);}

$comment=mb_substr($comment, 0, ($massparam['sumvl']));
$namenew=mb_substr($namenew, 0, ($massparam['sumvlname']));
$namenew=htmlspecialchars(strip_tags ($namenew));
$dataz=strip_tags ($dataz);
$usmail=str_replace("http://","",$usmail);
$usmail=strip_tags ($usmail);
$usurl=strip_tags ($usurl);

if ($_SESSION['dle_name'] != '') {
  $is_reg = 1;
  if ($_SESSION['dle_name'] != $namenew) $namenew = $_SESSION['dle_name'];
}
else {
  $is_reg = 0;
  $short_table = str_ireplace('_mservice_commentit','',$table);
  $SQLr = "SELECT name FROM `".$short_table."_users` WHERE name='".$namenew."' LIMIT 1"; 
  $resultr = mysql_query($SQLr); 
  if (mysql_num_rows($resultr)) $namenew='Гость_'.$namenew;
}


$query = "INSERT INTO `$table` (`num`, `url`, `name`, `is_register`, `comm`, `data`, `moder`, `ip`, `mail`,`rootid`,`level`,`http`,`service`,`avatar`,`admincom`,`raitng`,`iprating`) VALUES (NULL, '".$url."', '".mysql_real_escape_string($namenew)."','".$is_reg."','".mysql_real_escape_string($comment)."', '".$dataz."', '".$massparam['moder']."', '".getipcomment()."', '".mysql_real_escape_string($usmail)."','".$idcomnow."','".$level."','".mysql_real_escape_string($usurl)."','".$service."','".$avatar."','".$admincom."',0,'');";

$sort=@mysql_query($query) or die ("$query");
//Админ Вам письмеЦО
if ($massparam['mail']==1&&$admincom!=1) {

$message=$langcommentit['mail_message']."<a href='http://".$_SERVER['SERVER_NAME']."".$url."'>http://".$_SERVER['SERVER_NAME']."".$url."</a><br />".$langcommentit['mail_message_name']." ".$namenew."<br />".$langcommentit['mail_message_mail']." ".$usmail."<br />".$langcommentit['mail_message_write']." <br />".$comment."";

   $mail=explode(';',$massparam['mailbox']);
   $smail=new html_mime_mail();
   $smail->add_html($message);
   if ($coder==1) {$smail->build_message('w');} else {$smail->build_message('u');}
   foreach($mail as $n){if ($n) {$smail->send($_SERVER['SERVER_NAME'],$n,"robot@".$_SERVER['HTTP_HOST'],$langcommentit['mail_title']);}}

}

if ($massparam['replaymail']==1) 
{ 
$SQLr = "SELECT mail FROM `$table` WHERE num=".$idcomnow.""; 
 $resultr = mysql_query($SQLr); 
    if (mysql_num_rows($resultr)){ 
    $rowr = mysql_fetch_array($resultr); 
	if ($rowr['mail']){
if ($namenew==$pass) {$namenew=$massparam['nameadmin'];}
$message="".$langcommentit['mail_message_raplay1']." <b>".$namenew."</b>: <br />".$comment."<br /> ".$langcommentit['mail_message_raplay2']." <a href='http://".$_SERVER['SERVER_NAME']."".$url."'>http://".$_SERVER['SERVER_NAME']."".$url."</a>"; 
   $mail=$rowr['mail']; 
   $smail=new html_mime_mail(); 
   $smail->add_html($message); 
   if ($coder==1) {$smail->build_message('w');} else {$smail->build_message('u');}
   $smail->send($_SERVER['SERVER_NAME'],$mail,"robot@".$_SERVER['HTTP_HOST'],$langcommentit['mail_message_raplay_title']); 
   }
} 
}

unset($_SESSION['captcha_keystring']);
}

if ($massparam['rss']==1&&!$massparam['moder']) {
		if ($massparam['europing']==1) {pingmagnetik ($massparam['titlecia'],$hostsite);}
		if ($massparam['yaping']==1) {pingyandex ($massparam['titlecia'],$hostrss);}
}
}
}

function utfcoder($txt) {global $coder; if ($coder==1) {$txt = iconv('UTF-8', 'windows-1251//IGNORE', $txt); }return $txt;}
function utfcoder2($txt,$in='UTF-8',$out='windows-1251') {global $coder; $txt = iconv($in, $out.'//IGNORE', $txt); return $txt;} 

function viewform() {
global $massparam,$url,$urlorig,$table,$wwp,$coder,$idcomnow,$zzzzzzzzzzzzz,$langcommentit,$smilebar, $htmlz,$rssico,$width,$height,$novis,$novisurl,$titlenter,$novismail,$captsourse,$oldname,$oldmess,$oldmail,$autoarea,$bbpanel,$panelbar,$linkbutton,$picbutton,$quotebutton,$colorbutton,$sizebutton,$justbutton,$capt,$sumvlname,$oldurl,$djos,$icoservice,$miniavatar,$novisnick,$exitbut,$panelbar0;
$sumvlname=$massparam['sumvlname'];
$autoarea=''; if ($massparam['autosize']==1) {$autoarea='onkeyup="autosize(this)" onfocus="autosize(this)"';}
$countcomment=mysql_query("SELECT count(url) FROM `$table` WHERE url='".mysql_real_escape_string($urlorig)."'");$out=mysql_result($countcomment,0);if (!$out) $langcommentit['skin_post_comment']=$langcommentit['skin_first_post'];

$rssico="";
$divonter="";
$smilebar="";
$htmlz="";
$oldname="";
$oldmess="";
$oldmail="";
$linkbutton='';
$panelbar='';
$oldurl='';
$icoservice='';
$exitbut='';
$panelbar0='';
$novisnick='type="text"';
if ($massparam['loginzaglob']==1){$massparam['http']==0;
$djos=$_SESSION['djos'];
if (empty($djos['error_message'])){
$exitbut="<a href=\"javascript:exitcomment()\">".$langcommentit['skin_exit']."</a>";
$novisnick='type="hidden"';

if (preg_match('@vk\.com@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['first_name'].' '.$djos['name']['last_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/vkontakte.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@twitter@smi',$djos['provider'])) {$oldname=utfcoder($djos['nickname']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/twitter.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@mail\.ru@smi',$djos['provider'])) {$oldname=str_replace('http://openid.mail.ru/mail/','',utfcoder($djos['identity']));$icoservice=$oldname.' <img src="/'.$wwp.'/im/mailru.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';}

if (preg_match('@facebook\.com@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['full_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/facebook.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@last\.fm@smi',$djos['provider'])) {
if ($djos['nickname']) {$oldname=utfcoder($djos['nickname']);} else {$oldname=utfcoder($djos['name']['full_name']);}
$icoservice=$oldname.' <img src="/'.$wwp.'/im/lastfm.png" atl="" title="" border="0" />';$langcommentit['skin_name']='<img style="width:50px;height:50px;"  src="'.$djos['photo'].'" atl="" title="" border="0" />';}

if (preg_match('@yandex@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);
$oldname=str_replace('openid.yandex.ru/','',$oldname);
$oldname=str_replace('/','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/yandex.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}

if (preg_match('@myopenid\.com@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);$oldname=str_replace('.myopenid.com/','',$oldname);$oldname=str_replace('.myopenid.com','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/myopenid.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}

if (preg_match('@google@smi',$djos['provider'])) {$oldname=utfcoder($djos['name']['first_name']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/google.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';}

if (preg_match('@loginza\.ru@smi',$djos['provider'])) {$oldname=utfcoder($djos['nickname']);$icoservice=$oldname.' <img src="/'.$wwp.'/im/loginza.png" atl="" title="" border="0" />';
$checkavatar=str_replace('http://loginza.ru/users/avatars/','',$djos['photo']);
$langcommentit['skin_name']='';
if ($checkavatar) {$langcommentit['skin_name']='<img src="'.$djos['photo'].'" atl="" title="" border="0" />';}
}

if (preg_match('@webmoney@smi',$djos['provider'])) {
$oldname=utfcoder($djos['identity']);
$oldname=str_replace('http://','',$oldname);$oldname=str_replace('.webmoney.ru/','',$oldname);
$icoservice=$oldname.' <img src="/'.$wwp.'/im/webmoney.png" atl="" title="" border="0" />';$langcommentit['skin_name']='';
}
}}

if (@$_POST['namenew']) 
	{
$oldname = utfcoder(@$_POST['namenew']);
$oldmess = utfcoder(@$_POST['comment']);
$oldmail = utfcoder(@$_POST['usmail']);
$oldurl = utfcoder(@$_POST['usurl']);
}

if ($massparam['bbpanel']==1)
{
if ($massparam['linkbb']==1) {
$linkbutton=
<<<EOF
<a title="$langcommentit[editor_url]" href="javascript:add_link();" class="pic4"></a>
EOF;
}

if ($_SESSION['dle_name'] != '') {
$panelbar0=
<<<EOF
<tr>
<td style="width:146px;"><input type="hidden" id="nick" type="text" name="namenew" value="$_SESSION[dle_name]"></td>
<td style="width:522px;"><input type="hidden" id="usmail" $novismail name="usemail" value="$oldmail"></td>
</tr>
EOF;
}
else {
$panelbar0=
<<<EOF
<tr>
<td style="width:146px; padding-left:5px;">$langcommentit[skin_name] <font color="#F7A8A8">*</font><div style="height:5px"></div></td>
<td style="width:522px;"><input id="nick" type="text" name="namenew" maxlength="$sumvlname" value="$oldname" size="20" class="f_input">
</td>
</tr>
<tr>
<td style="width:146px; height:5px;"></td>
<td style="width:522px;"></td>
</tr>
<tr>
<td style="width:146px; padding-left:5px;">$langcommentit[skin_mail]</td>
<td style="width:522px;"><input id="usmail" $novismail name="usemail" value="$oldmail" size="20" class="f_input"></td>
</tr>
<tr>
<td colspan="2" style="height:10px;"></td>
</tr>
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

if ($massparam['smile']==1) {
$smilebar=
<<<EOF
<a href="javascript: put_smile('!;)');"><img src="/$wwp/im/sml_2.gif" border="0" alt="  ;)" title=" ;) " /></a>
<a href="javascript: put_smile(':D');"><img src="/$wwp/im/sml_3.gif" border="0" alt=" :D " title=" :D " /></a>
<a href="javascript: put_smile(':(');"><img src="/$wwp/im/sml_6.gif" border="0" alt=" :( " title=" :( " /></a>
<a href="javascript: put_smile('=)');"><img src="/$wwp/im/sml_4.gif" border="0" alt=" =) " title=" =) " /></a>
<a href="javascript: put_smile('?)');"><img src="/$wwp/im/sml_5.gif" border="0" alt=" ?) " title=" ?)"  /></a>
<a href="javascript: put_smile(':ups:');"><img src="/$wwp/im/sml_7.gif" border="0" alt=" :ups: " title=" :ups: " /></a>
<a href="javascript: put_smile(':cool:');"><img src="/$wwp/im/sml_8.gif" border="0" alt=" :cool: " title=" :cool: " /></a>
<a href="javascript: put_smile(':bad:');"><img src="/$wwp/im/sml_9.gif" border="0" alt=" :bad: " title=" :bad: " /></a>
<a href="javascript: put_smile(':like:');"><img src="/$wwp/im/sml_10.gif" border="0" alt=" :like: " title=" :like: " /></a>
<a href="javascript: put_smile(':angel:');"><img src="/$wwp/im/sml_11.gif" border="0" alt=" :angel: " title=" :angel: " /></a>
<a href="javascript: put_smile(':love:');"><img src="/$wwp/im/sml_12.gif" border="0" alt=" :love: " title=" :love: " /></a>
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
$justbutton $colorbutton $linkbutton $picbutton $quotebutton $sizebutton 

EOF;
}

$sesname=session_name().'='.session_id();
$height=($height+20)."px";
$width=$width."px";
$captsourse='<img id="capt" alt="captcha" title="captcha" src="/'.$wwp.'/capt.php?'.$sesname.'" />';
if ($massparam['workcapt']==1) {
$novis='type="text"';
$capt=xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/capt.html",'',get_defined_vars());
}
else {
$titlenter='';
$capt='<img id="capt" src="" width="1" height="1" border="0" />';
$novis='type="hidden" value="none"';
}

if ($massparam['viewentermail']==1){$novismail='type="text"';}else{$novismail='type="hidden"';$langcommentit['skin_mail']='';}
if ($massparam['http']==1){$novisurl='type="text"';}else{$novisurl='type="hidden"';$langcommentit['skin_homepage']='';}
if ($massparam['rss']==1) {$rssico='<a href="/'.$wwp.'/rss.php?url='.$urlorig.'"><img alt="RSS" title="RSS" src="/'.$wwp.'/im/rss.png" border="0" /></a>';}

if ($massparam['linkbb']==1) {
$htmlz=$langcommentit['skin_bb_code'];
}
echo xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/addcom.html",'',get_defined_vars());
}

function getipcomment()
{
  if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"),"unknown"))
    $ip = getenv("HTTP_CLIENT_IP");

  elseif (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");

  elseif (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
    $ip = getenv("REMOTE_ADDR");

  elseif (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
    $ip = $_SERVER['REMOTE_ADDR'];
  
  else
    $ip = "unknown";
  
  return($ip);
}

function pingmagnetik($blog_name, $blog_link)
{
	$xml_schema = '<?xml version="1.0"?>'."\n"; $methodcall_schema = '
    <methodCall>'."\n"; $methodname_line = '
        <methodName>weblogUpdates.ping</methodName>'."\n"; $params_schema ='
        <params>'."\n"; $blogname_line ='
            <param>
            <value>'.$blog_name.'</value>
            </param>'."\n"; $bloglink_line = '
            <param>
            <value>'.$blog_link.'</value>
            </param>'."\n"; $params_end = '</params>'."\n"; $methodcall_end = '</methodCall>'."\n"; $ping_url = 'http://rpc.pingomatic.com'; $ping_post_content = $xml_schema.$methodcall_schema.$methodname_line; $ping_post_content .= $params_schema.$blogname_line.$bloglink_line; $ping_post_content .= $params_end.$methodcall_end; $connect = curl_init(); curl_setopt($connect, CURLOPT_URL, $ping_url); curl_setopt($connect, CURLOPT_RETURNTRANSFER, TRUE); curl_setopt($connect, CURLOPT_HTTPHEADER, array('Content-Type: text/xml')); curl_setopt($connect, CURLOPT_POST, TRUE); curl_setopt($connect, CURLOPT_POSTFIELDS, $ping_post_content); $response_content = curl_exec($connect); $response = curl_getinfo($connect); curl_close($connect); return $response["http_code"]; } function pingyandex ($name, $url) { $acce = "
    <?xml version=\"1.0\"?>
        <methodCall>
            <methodName>weblogUpdates.ping</methodName>
            <params>
                <param>
                <value>".$name."</value>
                </param>
                <param>
                <value>".$url."</value>
                </param>
            </params>
        </methodCall>"; if($ping = @fsockopen("ping.blogs.yandex.ru", 80, $errno, $errstr, 15)) { fputs ($ping, "POST /rpc/ping HTTP/1.0\r\n" . "User-Agent: Radio UserLand/7.1b7 (WinNT)\r\n". "Host: rpc.weblogs.com\r\n". "Content-Type: text/xml\r\n". "Content-length: ".strlen($acce)."\r\n\r\n"); fputs ($ping, $acce); fclose ($ping); return true; } else { return false; } } class html_mime_mail { var $headers; var $multipart; var $mime; var $html; var $parts = array(); function html_mime_mail($headers="") { $this->headers=$headers; } function add_html($html="") { if (!isset($this->html)) $this->html=""; $this->html.=$html; } function build_html($orig_boundary,$kod) { $this->multipart.="--$orig_boundary\n"; if ($kod=='w' || $kod=='win' || $kod=='windows-1251') $kod='windows-1251'; if ($kod=='u' || $kod=='utf' || $kod=='UTF-8') $kod='UTF-8'; $this->multipart.="Content-Type: text/html; charset=$kod\n"; $this->multipart.="Content-Transfer-Encoding: Quot-Printed\n\n"; $this->multipart.="$this->html\n\n"; } function add_attachment($path="", $name = "", $c_type="application/octet-stream") { if (!file_exists($path.$name)) { print "File $path.$name dosn't exist."; return; } $fp=fopen($path.$name,"rb"); if (!$fp) { print "File $path.$name coudn't be read."; return; } $file=fread($fp, filesize($path.$name)); fclose($fp); $this->parts[]=array("body"=>$file, "name"=>$name,"c_type"=>$c_type); } function build_part($i) { $message_part=""; $message_part.="Content-Type: ".$this->parts[$i]["c_type"]; if ($this->parts[$i]["name"]!="") $message_part.="; name = \"".$this->parts[$i]["name"]."\"\n"; else $message_part.="\n"; $message_part.="Content-Transfer-Encoding: base64\n"; $message_part.="Content-Disposition: attachment; filename = \"". $this->parts[$i]["name"]."\"\n\n"; $message_part.=chunk_split(base64_encode($this->parts[$i]["body"]))."\n"; return $message_part; } function build_message($kod) { $boundary="=_".md5(uniqid(time())); $this->headers.="MIME-Version: 1.0\n"; $this->headers.="Content-Type: multipart/mixed; boundary=\"$boundary\"\n"; $this->multipart=""; $this->multipart.="This is a MIME encoded message.\n\n"; $this->build_html($boundary,$kod); for ($i=(count($this->parts)-1); $i>=0; $i--) $this->multipart.="--$boundary\n".$this->build_part($i); $this->mime = "$this->multipart--$boundary--\n"; } function send($server, $to, $from, $subject="", $headers="") { $server = 'domain.com'; $from = 'robot@domain.com'; $headers="From: $from\nSubject: $subject\nX-Mailer: http://$server\n$headers"; mail($to,$subject,$this->mime,$this->headers.$headers."\n"); } } function commentrating($comment,$col) { global $table; if ($_SERVER['HTTP_X_REQUESTED_WITH']=='XMLHttpRequest') { $comment=intval($comment); $col=intval($col); $SQL = "SELECT raitng,iprating FROM `$table` WHERE num='$comment' LIMIT 1"; $result = mysql_query($SQL);if (mysql_num_rows($result)){ $row = mysql_fetch_array($result);} if (!preg_match('@'.getipcomment().'@smi',$row['iprating'])){ if ($col==1) {$col='+1';$row['raitng']=$row['raitng']+1;} else {$col='-1';$row['raitng']=$row['raitng']-1;} $query = "UPDATE `$table` SET raitng=raitng".$col.", iprating='".$row['iprating'].getipcomment()."|' WHERE num='$comment' LIMIT 1;"; $sort=@mysql_query($query) or die ("$query"); if ($row['raitng']>0) $row['raitng']='+'.$row['raitng']; echo $row['raitng']; } else {echo 'z';} }else {header('HTTP/1.1 404 Not Found');header('Status: 404 Not Found');} } function loginza() { global $table,$specurl,$langcommentit,$panellozinza,$wwp,$massparam; $provader=''; $provaderurl=''; $urlxlogiza=rawurlencode('http://'.$_SERVER['HTTP_HOST'].$specurl); if ($massparam['yandex']==1) {$provader.='<img src="/'.$wwp.'/im/yandex.png" alt="Yandex" title="Yandex" />';$provaderurl.='yandex,';} if ($massparam['google']==1) {$provader.='<img src="/'.$wwp.'/im/google.png" alt="Google" title="Google Accounts" />';$provaderurl.='google,';} if ($massparam['vkontakte']==1) {$provader.='<img src="/'.$wwp.'/im/vkontakte.png" alt="VK" title="VK" />';$provaderurl.='vkontakte,';} if ($massparam['mailru']==1) {$provader.='<img src="/'.$wwp.'/im/mailru.png" alt="Mail.ru" title="Mail.ru" />';$provaderurl.='mailru,';} if ($massparam['twitter']==1) {$provader.='<img src="/'.$wwp.'/im/twitter.png" alt="Twitter" title="Twitter" />';$provaderurl.='twitter,';} if ($massparam['loginza']==1) {$provader.='<img src="/'.$wwp.'/im/loginza.png" alt="Loginza" title="Loginza" />';$provaderurl.='loginza,';} if ($massparam['myopenid']==1) {$provader.='<img src="/'.$wwp.'/im/myopenid.png" alt="MyOpenID" title="MyOpenID" />';$provaderurl.='myopenid,';} if ($massparam['openid']==1) {$provader.='<img src="/'.$wwp.'/im/openid.png" alt="OpenID" title="OpenID" />';$provaderurl.='openid,';} if ($massparam['webmoney']==1) {$provader.='<img src="/'.$wwp.'/im/webmoney.png" alt="WebMoney" title="WebMoney" />';$provaderurl.='webmoney,';} if ($massparam['facebook']==1) {$provader.='<img src="/'.$wwp.'/im/facebook.png" alt="Facebook" title="Facebook" />';$provaderurl.='facebook,';} if ($massparam['lastfm']==1) {$provader.='<img src="/'.$wwp.'/im/lastfm.png" alt="Lastfm" title="Lastfm" />';$provaderurl.='lastfm,';} $panellozinza='
        <script src="http://loginza.ru/js/widget.js" type="text/javascript"></script><a href="https://loginza.ru/api/widget?token_url='.$urlxlogiza.'&providers_set='.$provaderurl.'" class="loginza">'.$provader.'</a>'; echo xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/loginza.html",'',get_defined_vars()); } ?>
