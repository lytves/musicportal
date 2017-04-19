<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

	$i = 0;
	$output = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"120\"><tr>";

    $smilies = explode(",", $config['smilies']);
    foreach($smilies as $smile)
    {
        $i++; $smile = trim($smile);

        $output .= "<td style=\"padding:2px;\" align=\"center\"><a href=\"#\" onClick=\"dle_smiley(':$smile:'); return false;\"><img style=\"border: none;\" alt=\"$smile\" src=\"".$config['http_home_url']."engine/data/emoticons/$smile.gif\" /></a></td>";

		if ($i%3 == 0) $output .= "</tr><tr>";

    }

	$output .= "</tr></table>";

if ($addtype == "addnews") {

   $addform = "document.ajaxnews".$id; 
   $startform = "dleeditnews".$id;

$code = <<<HTML
<div style="width:99%; height:50px; border:1px solid #BBB; background-image:url('{THEME}/bbcodes/images/bg.gif');">
<div id="b_b" class="editor_button" onclick="simpletag('b')"><img title="$lang[bb_t_b]" src="{THEME}/bbcodes/images/b.gif" width="23" height="25" border="0"></div>
<div id="b_i" class="editor_button" onclick="simpletag('i')"><img title="$lang[bb_t_i]" src="{THEME}/bbcodes/images/i.gif" width="23" height="25" border="0"></div>
<div id="b_u" class="editor_button" onclick="simpletag('u')"><img title="$lang[bb_t_u]" src="{THEME}/bbcodes/images/u.gif" width="23" height="25" border="0"></div>
<div id="b_s" class="editor_button" onclick="simpletag('s')"><img title="$lang[bb_t_s]" src="{THEME}/bbcodes/images/s.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick=tag_image()><img title="$lang[bb_b_img]" src="{THEME}/bbcodes/images/image.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_url()"><img title="$lang[bb_t_url]" src="{THEME}/bbcodes/images/link.gif" width="23" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_leech()"><img title="$lang[bb_t_leech]" src="{THEME}/bbcodes/images/leech.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_email()"><img title="$lang[bb_t_m]" src="{THEME}/bbcodes/images/email.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="tag_video()"><img title="$lang[bb_t_video]" src="{THEME}/bbcodes/images/mp.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="tag_audio()"><img src="{THEME}/bbcodes/images/mp3.gif" width="23" height="25" border="0"></div>
<div id="b_hide" class="editor_button" onclick="simpletag('hide')"><img title="$lang[bb_t_hide]" src="{THEME}/bbcodes/images/hide.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_emo" class="editor_button"  onclick="ins_emo();"><img title="$lang[bb_t_emo]" src="{THEME}/bbcodes/images/emo.gif" width="23" height="25" border="0"></div>
<div id="b_color" class="editor_button" onclick="ins_color();"><img src="{THEME}/bbcodes/images/color.gif" width="23" height="25" border="0"></div>
<div id="b_spoiler" class="editor_button" onclick="simpletag('spoiler')"><img src="{THEME}/bbcodes/images/spoiler.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick="tag_flash()"><img src="{THEME}/bbcodes/images/flash.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editbclose" onclick="closeall()"><img title="$lang[bb_t_cl]" src="{THEME}/bbcodes/images/close.gif" width="23" height="25" border="0"></div>
<div class="editor_button_brk"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" style="padding-top:4px;width:120px;"><select name="bbfont" class="editor_button" onchange="insert_font(this.options[this.selectedIndex].value, 'font')"><option value='0'>{$lang['bb_t_font']}</option><option value='Arial'>Arial</option><option value='Arial Black'>Arial Black</option><option value='Century Gothic'>Century Gothic</option><option value='Courier New'>Courier New</option><option value='Georgia'>Georgia</option><option value='Impact'>Impact</option><option value='System'>System</option><option value='Tahoma'>Tahoma</option><option value='Times New Roman'>Times New Roman</option><option value='Verdana'>Verdana</option></select></div>
<div class="editor_button" style="padding-top:4px;width:65px;"><select name="bbsize" class="editor_button" onchange="insert_font(this.options[this.selectedIndex].value, 'size')"><option value='0'>{$lang['bb_t_size']}</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option></select></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_left" class="editor_button" onclick="simpletag('left')"><img title="$lang[bb_t_l]" src="{THEME}/bbcodes/images/l.gif" width="23" height="25" border="0"></div>
<div id="b_center" class="editor_button" onclick="simpletag('center')"><img title="$lang[bb_t_c]" src="{THEME}/bbcodes/images/c.gif" width="23" height="25" border="0"></div>
<div id="b_right" class="editor_button" onclick="simpletag('right')"><img title="$lang[bb_t_r]" src="{THEME}/bbcodes/images/r.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_quote" class="editor_button" onclick="simpletag('quote')"><img title="$lang[bb_t_quote]" src="{THEME}/bbcodes/images/quote.gif" width="23" height="25" border="0"></div>
<div id="b_code" class="editor_button" onclick="simpletag('code')"><img title="$lang[bb_t_code]" src="{THEME}/bbcodes/images/code.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick="pagebreak()"><img title="$lang[bb_t_br]" src="{THEME}/bbcodes/images/pbreak.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="pagelink()"><img title="$lang[bb_t_p]" src="{THEME}/bbcodes/images/page.gif" width="23" height="25" border="0"></div>
<div class="editor_button">&nbsp;<img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick="tag_youtube()"><img src="{THEME}/bbcodes/images/youtube.gif" width="23" height="25" border="0" alt="" /></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>
<iframe width="154" height="104" id="cp" src="{THEME}/bbcodes/color.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; display: none; position: absolute;"></iframe>
<div id="dle_emo" style="visibility:hidden; display: none; position: absolute; width:140px; height: 124px; overflow: auto; border: 1px solid #BBB; background:#E9E8F2;filter: alpha(opacity=95, enabled=1) progid:DXImageTransform.Microsoft.Shadow(color=#CACACA,direction=135,strength=3);">{$output}</div>
HTML;

}
else {

   $addform = "document.ajaxcomments".$id; 
   $startform = "dleeditcomments".$id;

   if ($user_group[$member_id['user_group']]['allow_url'])
   {
      $url_link = "<div class=\"editor_button\"  onclick=\"tag_url()\"><img title=\"$lang[bb_t_url]\" src=\"{THEME}/bbcodes/images/link.gif\" width=\"23\" height=\"25\" border=\"0\"></div><div class=\"editor_button\"  onclick=\"tag_leech()\"><img title=\"$lang[bb_t_leech]\" src=\"{THEME}/bbcodes/images/leech.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";
   } 
   else {$url_link = "";}

   if ($user_group[$member_id['user_group']]['allow_image'])
   {
      $image_link = "<div class=\"editor_button\" onclick=tag_image()><img title=\"$lang[bb_b_img]\" src=\"{THEME}/bbcodes/images/image.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";
   } 
   else $image_link = "";

$code = <<<HTML
<div style="width:99%; height:25px; border:1px solid #BBB; background-image:url('{THEME}/bbcodes/images/bg.gif')">
<div id="b_b" class="editor_button" onclick="simpletag('b')"><img title="$lang[bb_t_b]" src="{THEME}/bbcodes/images/b.gif" width="23" height="25" border="0"></div>
<div id="b_i" class="editor_button" onclick="simpletag('i')"><img title="$lang[bb_t_i]" src="{THEME}/bbcodes/images/i.gif" width="23" height="25" border="0"></div>
<div id="b_u" class="editor_button" onclick="simpletag('u')"><img title="$lang[bb_t_u]" src="{THEME}/bbcodes/images/u.gif" width="23" height="25" border="0"></div>
<div id="b_s" class="editor_button" onclick="simpletag('s')"><img title="$lang[bb_t_s]" src="{THEME}/bbcodes/images/s.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_left" class="editor_button" onclick="simpletag('left')"><img title="$lang[bb_t_l]" src="{THEME}/bbcodes/images/l.gif" width="23" height="25" border="0"></div>
<div id="b_center" class="editor_button" onclick="simpletag('center')"><img title="$lang[bb_t_c]" src="{THEME}/bbcodes/images/c.gif" width="23" height="25" border="0"></div>
<div id="b_right"class="editor_button" onclick="simpletag('right')"><img title="$lang[bb_t_r]" src="{THEME}/bbcodes/images/r.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_emo" class="editor_button"  onclick="ins_emo();"><img title="$lang[bb_t_emo]" src="{THEME}/bbcodes/images/emo.gif" width="23" height="25" border="0"></div>
{$url_link}
{$image_link}
<div class="editor_button"  onclick="tag_email()"><img title="$lang[bb_t_m]" src="{THEME}/bbcodes/images/email.gif" width="23" height="25" border="0"></div>
<div id="b_color" class="editor_button" onclick="ins_color();"><img src="{THEME}/bbcodes/images/color.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="{THEME}/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_hide" class="editor_button" onclick="simpletag('hide')"><img title="$lang[bb_t_hide]" src="{THEME}/bbcodes/images/hide.gif" width="23" height="25" border="0"></div>
<div id="b_quote" class="editor_button" onclick="simpletag('quote')"><img title="$lang[bb_t_quote]" src="{THEME}/bbcodes/images/quote.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="translit()"><img title="$lang[bb_t_translit]" src="{THEME}/bbcodes/images/translit.gif" width="23" height="25" border="0"></div>
<div id="b_spoiler" class="editor_button" onclick="simpletag('spoiler')"><img src="{THEME}/bbcodes/images/spoiler.gif" width="23" height="25" border="0"></div>
</div>
<iframe width="154" height="104" id="cp" src="{THEME}/bbcodes/color.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; display: none; position: absolute;"></iframe>
<div id="dle_emo" style="visibility:hidden; display: none; position: absolute; width:140px; height: 124px; overflow: auto; border: 1px solid #BBB; background:#E9E8F2;filter: alpha(opacity=95, enabled=1) progid:DXImageTransform.Microsoft.Shadow(color=#CACACA,direction=135,strength=3);">{$output}</div>
HTML;
}


$script_code = @file_get_contents(ENGINE_DIR."/ajax/bbcodes.js");
$script_code .= <<<HTML

-->
</SCRIPT>
HTML;


$bb_code = <<<HTML
<SCRIPT type=text/javascript>
<!--
var text_enter_url       = "$lang[bb_url]";
var text_enter_size       = "$lang[bb_flash]";
var text_enter_flash       = "$lang[bb_flash_url]";
var text_enter_page      = "$lang[bb_page]";
var text_enter_url_name  = "$lang[bb_url_name]";
var text_enter_page_name = "$lang[bb_page_name]";
var text_enter_image    = "$lang[bb_image]";
var text_enter_email    = "$lang[bb_email]";
var text_code           = "$lang[bb_code]";
var text_quote          = "$lang[bb_quote]";
var error_no_url        = "$lang[bb_no_url]";
var error_no_title      = "$lang[bb_no_title]";
var error_no_email      = "$lang[bb_no_email]";
var prompt_start        = "$lang[bb_prompt_start]";
var img_title   		= "$lang[bb_img_title]";
var email_title  	    = "$lang[bb_email_title]";
var text_pages  	    = "$lang[bb_bb_page]";
var image_align  	    = "{$config['image_align']}";

var selField  = "{$startform}";
var fombj    = {$addform};

{$script_code}
{$code}
HTML;

$bb_code = str_replace ("{THEME}", $config['http_home_url']."engine/skins", $bb_code);

?>