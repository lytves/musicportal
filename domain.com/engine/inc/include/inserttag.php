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
	$smiles = "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\"><tr>";

    $smilies = explode(",", $config['smilies']);
    foreach($smilies as $smile)
    {
        $i++; $smile = trim($smile);

        $smiles .= "<td style=\"padding:2px;\" align=\"center\"><a href=\"#\" onClick=\"dle_smiley(':$smile:'); return false;\"><img style=\"border: none;\" alt=\"$smile\" src=\"".$config['http_home_url']."engine/data/emoticons/$smile.gif\" /></a></td>";

		if ($i%3 == 0) $smiles .= "</tr><tr>";

    }

	$smiles .= "</tr></table>";

   if ($user_group[$member_id['user_group']]['allow_image_upload'])
   {
      $image_upload = "<div class=\"editor_button\" onclick=image_upload()><img title=\"$lang[bb_t_up]\" src=\"engine/skins/bbcodes/images/upload.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";
   }
   else $image_upload = "";

if ($mod != "editnews") {
$row['autor'] = $member_id['name'];
}

$typograf = "<div id=\"b_typograf\" class=\"editor_button\" onclick=\"tag_typograf(); return false;\"><img title=\"$lang[bb_t_t]\" src=\"engine/skins/bbcodes/images/typograf.gif\" width=\"23\" height=\"25\" border=\"0\"></div>";

$bb_code = <<<HTML
<SCRIPT type=text/javascript>
<!--

var uagent    = navigator.userAgent.toLowerCase();
var is_safari = ( (uagent.indexOf('safari') != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var is_ie     = ( (uagent.indexOf('msie') != -1) && (!is_opera) && (!is_safari) && (!is_webtv) );
var is_ie4    = ( (is_ie) && (uagent.indexOf("msie 4.") != -1) );
var is_moz    = (navigator.product == 'Gecko');
var is_ns     = ( (uagent.indexOf('compatible') == -1) && (uagent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_safari) );
var is_ns4    = ( (is_ns) && (parseInt(navigator.appVersion) == 4) );
var is_opera  = (uagent.indexOf('opera') != -1);
var is_kon    = (uagent.indexOf('konqueror') != -1);
var is_webtv  = (uagent.indexOf('webtv') != -1);

var is_win    =  ( (uagent.indexOf("win") != -1) || (uagent.indexOf("16bit") !=- 1) );
var is_mac    = ( (uagent.indexOf("mac") != -1) || (navigator.vendor == "Apple Computer, Inc.") );
var ua_vers   = parseInt(navigator.appVersion);
	
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

var b_open = 0;
var i_open = 0;
var u_open = 0;
var s_open = 0;
var quote_open = 0;
var code_open = 0;
var sql_open = 0;
var html_open = 0;
var left_open = 0;
var center_open = 0;
var right_open = 0;
var hide_open = 0;
var color_open = 0;
var spoiler_open = 0;
var ie_range_cache = '';

var selField  = "short_story";

var bbtags   = new Array();

var fombj    = document.forms[0];

function stacksize(thearray)
{
	for (i = 0 ; i < thearray.length; i++ )
	{
		if ( (thearray[i] == "") || (thearray[i] == null) || (thearray == 'undefined') )
		{
			return i;
		}
	}
	
	return thearray.length;
}

function pushstack(thearray, newval)
{
	arraysize = stacksize(thearray);
	thearray[arraysize] = newval;
}

function popstack(thearray)
{
	arraysize = stacksize(thearray);
	theval = thearray[arraysize - 1];
	delete thearray[arraysize - 1];
	return theval;
}

function setFieldName(which)
{
            if (which != selField)
            {
				allcleartags();
                selField = which;

            }
}


function cstat()
{
	var c = stacksize(bbtags);
	
	if ( (c < 1) || (c == null) ) {
		c = 0;
	}
	
	if ( ! bbtags[0] ) {
		c = 0;
	}
	

}


function closeall()
{
	if (bbtags[0])
	{
		while (bbtags[0])
		{
			tagRemove = popstack(bbtags)
			var closetags = "[/" + tagRemove + "]";

			eval ("fombj." +selField+ ".value += closetags");
			
			if ( (tagRemove != 'font') && (tagRemove != 'size') )
			{
				eval(tagRemove + "_open = 0");
				document.getElementById( 'b_' + tagRemove ).className = 'editor_button';

			}
		}
	}

	bbtags = new Array();

}

function allcleartags()
{
	if (bbtags[0])
	{
		while (bbtags[0])
		{
			tagRemove = popstack(bbtags)
			
				eval(tagRemove + "_open = 0");
				document.getElementById( 'b_' + tagRemove ).className = 'editor_button';

		}
	}

	bbtags = new Array();

}

function emoticon(theSmilie)
{
	doInsert(" " + theSmilie + " ", "", false);
}

function pagebreak()
{
	doInsert("{PAGEBREAK}", "", false);
}

function add_code(NewCode)
{
    fombj.selField.value += NewCode;
    fombj.selField.focus();
}

function simpletag(thetag)
{
	var tagOpen = eval(thetag + "_open");
	

		if (tagOpen == 0)
		{
			if(doInsert("[" + thetag + "]", "[/" + thetag + "]", true))
			{
				eval(thetag + "_open = 1");
				document.getElementById( 'b_' + thetag ).className = 'editor_buttoncl';
				
				pushstack(bbtags, thetag);
				cstat();

			}
		}
		else
		{
			lastindex = 0;
			
			for (i = 0 ; i < bbtags.length; i++ )
			{
				if ( bbtags[i] == thetag )
				{
					lastindex = i;
				}
			}
			
			while (bbtags[lastindex])
			{
				tagRemove = popstack(bbtags);
				doInsert("[/" + tagRemove + "]", "", false)


				if ( (tagRemove != 'font') && (tagRemove != 'size') )
				{
					eval(tagRemove + "_open = 0");
					document.getElementById( 'b_' + tagRemove ).className = 'editor_button';
				}
			}
			
			cstat();
		}

}

function pagelink()
{
    var FoundErrors = '';
	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='$lang[bb_bb_page]';

    if (!thesel) {
        thesel ='$lang[bb_bb_page]';
    }

    var enterURL   = prompt(text_enter_page, "1");
    var enterTITLE = prompt(text_enter_page_name, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }
    if (!enterTITLE) {
        FoundErrors += " " + error_no_title;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[page="+enterURL+"]"+enterTITLE+"[/page]", "", false);
}

function tag_url()
{
    var FoundErrors = '';
	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='My Webpage';

    if (!thesel) {
        thesel ='My Webpage';
    }

    var enterURL   = prompt(text_enter_url, "http://");
    var enterTITLE = prompt(text_enter_url_name, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }
    if (!enterTITLE) {
        FoundErrors += " " + error_no_title;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[url="+enterURL+"]"+enterTITLE+"[/url]", "", false);
}


function tag_leech()
{
    var FoundErrors = '';
	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='My Webpage';

    if (!thesel) {
        thesel ='My Webpage';
    }

    var enterURL   = prompt(text_enter_url, "http://");
    var enterTITLE = prompt(text_enter_url_name, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }
    if (!enterTITLE) {
        FoundErrors += " " + error_no_title;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[leech="+enterURL+"]"+enterTITLE+"[/leech]", "", false);
}

function tag_video()
{
    var FoundErrors = '';

	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='http://';

    if (!thesel) {
        thesel ='http://';
    }

    var enterURL = prompt(text_enter_url, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[video="+enterURL+"]", "", false);
}

function tag_audio()
{
    var FoundErrors = '';

	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='http://';

    if (!thesel) {
        thesel ='http://';
    }

    var enterURL = prompt(text_enter_url, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[audio="+enterURL+"]", "", false);
}

function tag_youtube()
{
    var FoundErrors = '';

	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='http://';

    if (!thesel) {
        thesel ='http://';
    }

    var enterURL = prompt(text_enter_url, thesel);

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

	doInsert("[youtube="+enterURL+"]", "", false);
}

function tag_flash()
{
    var FoundErrors = '';
    var enterURL   = prompt(text_enter_flash, "http://");

    var size = prompt(text_enter_size, "425,264");

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }


	doInsert("[flash="+size+"]"+enterURL+"[/flash]", "", false);

}

function tag_image()
{
    var FoundErrors = '';
    var enterURL   = prompt(text_enter_image, "http://");

    var Title = prompt(img_title, "{$config['image_align']}");

    if (!enterURL) {
        FoundErrors += " " + error_no_url;
    }

    if (FoundErrors) {
        alert("Error!"+FoundErrors);
        return;
    }

if (Title == "")
           {
	doInsert("[img]"+enterURL+"[/img]", "", false);
           }
else {
if (Title == "center") {
	doInsert("[center][img]"+enterURL+"[/img][/center]", "", false);
}
else {
	doInsert("[img="+Title+"]"+enterURL+"[/img]", "", false);
	}
 }
}

function tag_email()
{
    var emailAddress = prompt(text_enter_email, "");

    if (!emailAddress) { 
		alert(error_no_email); 
		return; 
	}

	var thesel ='';
	if ( (ua_vers >= 4) && is_ie && is_win)
	{
	thesel = document.selection.createRange().text;
	} else thesel ='';

    if (!thesel) {
        thesel ='';
    }

	var Title = prompt(email_title, thesel);

if (!Title) Title = emailAddress;

	doInsert("[email="+emailAddress+"]"+Title+"[/email]", "", false);
}

function doInsert(ibTag, ibClsTag, isSingle)
{
	var isClose = false;
	var obj_ta = eval('fombj.'+ selField);

	if ( (ua_vers >= 4) && is_ie && is_win)
	{
		if (obj_ta.isTextEdit)
		{
			obj_ta.focus();
			var sel = document.selection;
			var rng = ie_range_cache ? ie_range_cache : sel.createRange();
			rng.colapse;
			if((sel.type == "Text" || sel.type == "None") && rng != null)
			{
				if(ibClsTag != "" && rng.text.length > 0)
					ibTag += rng.text + ibClsTag;
				else if(isSingle)
					ibTag += rng.text + ibClsTag;
	
				rng.text = ibTag;
			}
		}
		else
		{
				obj_ta.value += ibTag + ibClsTag;
			
		}
		rng.select();
	ie_range_cache = null;

	}
	else if ( obj_ta.selectionEnd )
	{ 
		var ss = obj_ta.selectionStart;
		var st = obj_ta.scrollTop;
		var es = obj_ta.selectionEnd;
		
		if (es <= 2)
		{
			es = obj_ta.textLength;
		}
		
		var start  = (obj_ta.value).substring(0, ss);
		var middle = (obj_ta.value).substring(ss, es);
		var end    = (obj_ta.value).substring(es, obj_ta.textLength);
		
		if (obj_ta.selectionEnd - obj_ta.selectionStart > 0)
		{
			middle = ibTag + middle + ibClsTag;
		}
		else
		{
			middle = ibTag + middle + ibClsTag;
		}
		
		obj_ta.value = start + middle + end;
		
		var cpos = ss + (middle.length);
		
		obj_ta.selectionStart = cpos;
		obj_ta.selectionEnd   = cpos;
		obj_ta.scrollTop      = st;


	}
	else
	{
		obj_ta.value += ibTag + ibClsTag;
	}

	obj_ta.focus();
	return isClose;
}

function getOffsetTop(obj)
{
	var top = obj.offsetTop;
	
	while( (obj = obj.offsetParent) != null )
	{
		top += obj.offsetTop;
	}
	
	return top;
}

function getOffsetLeft(obj)
{
	var top = obj.offsetLeft;
	
	while( (obj = obj.offsetParent) != null )
	{
		top += obj.offsetLeft;
	}
	
	return top;
}

function ins_color()
{

	if (color_open == 0) {
		var buttonElement = document.getElementById('b_color');
		document.getElementById(selField).focus();

		if ( is_ie )
		{
			document.getElementById(selField).focus();
			ie_range_cache = document.selection.createRange();
		}

		var iLeftPos  = getOffsetLeft(buttonElement);
		var iTopPos   = getOffsetTop(buttonElement) + (buttonElement.offsetHeight + 3);

		document.getElementById('cp').style.left = (iLeftPos) + "px";
		document.getElementById('cp').style.top  = (iTopPos)  + "px";
		
		if (document.getElementById('cp').style.visibility == "hidden")
		{
			document.getElementById('cp').style.visibility = "visible";
			document.getElementById('cp').style.display    = "block";
		}
		else
		{
			document.getElementById('cp').style.visibility = "hidden";
			document.getElementById('cp').style.display    = "none";
			ie_range_cache = null;
		}
	}
	else
	{
			lastindex = 0;
			
			for (i = 0 ; i < bbtags.length; i++ )
			{
				if ( bbtags[i] == 'color' )
				{
					lastindex = i;
				}
			}
			
			while (bbtags[lastindex])
			{
				tagRemove = popstack(bbtags);
				doInsert("[/" + tagRemove + "]", "", false)
				eval(tagRemove + "_open = 0");
				document.getElementById( 'b_' + tagRemove ).className = 'editor_button';
			}
	}
}
function setColor(color)
{

		if ( doInsert("[color=" +color+ "]", "[/color]", true ) )
		{
			color_open = 1;
			document.getElementById( 'b_color' ).className = 'editor_buttoncl';
			pushstack(bbtags, "color");
		}

	document.getElementById('cp').style.visibility = "hidden";
	document.getElementById('cp').style.display    = "none";
    cstat();
}
function ins_emo()
{
		var buttonElement = document.getElementById('b_emo');
		document.getElementById(selField).focus();

		if ( is_ie )
		{
			document.getElementById(selField).focus();
			ie_range_cache = document.selection.createRange();
		}

		var iLeftPos  = getOffsetLeft(buttonElement);
		var iTopPos   = getOffsetTop(buttonElement) + (buttonElement.offsetHeight + 3);

		document.getElementById('dle_emo').style.left = (iLeftPos) + "px";
		document.getElementById('dle_emo').style.top  = (iTopPos)  + "px";
		
		if (document.getElementById('dle_emo').style.visibility == "hidden")
		{
			document.getElementById('dle_emo').style.zIndex   = 99;
			document.getElementById('dle_emo').style.visibility = "visible";
			document.getElementById('dle_emo').style.display    = "block";
		}
		else
		{
			document.getElementById('dle_emo').style.visibility = "hidden";
			document.getElementById('dle_emo').style.display    = "none";
			ie_range_cache = null;
		}

}
function dle_smiley ( text ){
	doInsert(' ' + text + ' ', '', false);

	document.getElementById('dle_emo').style.visibility = "hidden";
	document.getElementById('dle_emo').style.display    = "none";
	ie_range_cache = null;
}
function image_upload()
{

window.open('?mod=files&action=quick&area=' + selField + '&author={$row['autor']}&news_id={$id}', '_Addimage', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=640,height=550');    

}
function insert_font(value, tag)
{
    if (value == 0)
    {
    	return;
	} 

	if ( doInsert("[" +tag+ "=" +value+ "]", "[/" +tag+ "]", true ) )
	{
			pushstack(bbtags, tag);
	}
    fombj.bbfont.selectedIndex  = 0;
    fombj.bbsize.selectedIndex  = 0;
}

function tag_typograf()
	{
		var typo_ajax = new dle_ajax();
		var txt = typo_ajax.encodeVAR( document.getElementById( selField ).value );
		var varsString = "txt=" + txt;

		typo_ajax.element = selField;
		typo_ajax.method = 'POST';
		typo_ajax.requestFile = "engine/ajax/typograf.php";

		typo_ajax.onShow ('');
		typo_ajax.sendAJAX(varsString);

	}	
-->
</SCRIPT>
<div style="width:98%; height:50px; border:1px solid #BBB; background-image:url('engine/skins/bbcodes/images/bg.gif');">
<div id="b_b" class="editor_button" onclick="simpletag('b')"><img title="$lang[bb_t_b]" src="engine/skins/bbcodes/images/b.gif" width="23" height="25" border="0"></div>
<div id="b_i" class="editor_button" onclick="simpletag('i')"><img title="$lang[bb_t_i]" src="engine/skins/bbcodes/images/i.gif" width="23" height="25" border="0"></div>
<div id="b_u" class="editor_button" onclick="simpletag('u')"><img title="$lang[bb_t_u]" src="engine/skins/bbcodes/images/u.gif" width="23" height="25" border="0"></div>
<div id="b_s" class="editor_button" onclick="simpletag('s')"><img title="$lang[bb_t_s]" src="engine/skins/bbcodes/images/s.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick=tag_image()><img title="$lang[bb_b_img]" src="engine/skins/bbcodes/images/image.gif" width="23" height="25" border="0"></div>
{$image_upload}
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_emo" class="editor_button"  onclick="ins_emo();" style="width:33px;" align="center"><img title="$lang[bb_t_emo]" src="engine/skins/bbcodes/images/emo.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_url()"><img title="$lang[bb_t_url]" src="engine/skins/bbcodes/images/link.gif" width="23" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_leech()"><img title="$lang[bb_t_leech]" src="engine/skins/bbcodes/images/leech.gif" width="23" height="25" border="0"></div>
<div class="editor_button"  onclick="tag_email()"><img title="$lang[bb_t_m]" src="engine/skins/bbcodes/images/email.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick="tag_video()"><img title="$lang[bb_t_video]" src="engine/skins/bbcodes/images/mp.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="tag_audio()"><img src="engine/skins/bbcodes/images/mp3.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_hide" class="editor_button" onclick="simpletag('hide')"><img title="$lang[bb_t_hide]" src="engine/skins/bbcodes/images/hide.gif" width="23" height="25" border="0"></div>
<div id="b_quote" class="editor_button" onclick="simpletag('quote')"><img title="$lang[bb_t_quote]" src="engine/skins/bbcodes/images/quote.gif" width="23" height="25" border="0"></div>
<div id="b_code" class="editor_button" onclick="simpletag('code')"><img title="$lang[bb_t_code]" src="engine/skins/bbcodes/images/code.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div class="editor_button" onclick="pagebreak()"><img title="$lang[bb_t_br]" src="engine/skins/bbcodes/images/pbreak.gif" width="23" height="25" border="0"></div>
<div class="editor_button" onclick="pagelink()"><img title="$lang[bb_t_p]" src="engine/skins/bbcodes/images/page.gif" width="23" height="25" border="0"></div>

<div class="editbclose" onclick="closeall()"><img title="$lang[bb_t_cl]" src="engine/skins/bbcodes/images/close.gif" width="23" height="25" border="0"></div>
<div><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>

<div class="editor_button" style="padding-top:3px;width:140px;"><select name="bbfont" onchange="insert_font(this.options[this.selectedIndex].value, 'font')"><option value='0'>{$lang['bb_t_font']}</option><option value='Arial'>Arial</option><option value='Arial Black'>Arial Black</option><option value='Century Gothic'>Century Gothic</option><option value='Courier New'>Courier New</option><option value='Georgia'>Georgia</option><option value='Impact'>Impact</option><option value='System'>System</option><option value='Tahoma'>Tahoma</option><option value='Times New Roman'>Times New Roman</option><option value='Verdana'>Verdana</option></select></div>
<div class="editor_button" style="padding-top:3px;width:70px;"><select name="bbsize" onchange="insert_font(this.options[this.selectedIndex].value, 'size')"><option value='0'>{$lang['bb_t_size']}</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option></select></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_left" class="editor_button" onclick="simpletag('left')"><img title="$lang[bb_t_l]" src="engine/skins/bbcodes/images/l.gif" width="23" height="25" border="0"></div>
<div id="b_center" class="editor_button" onclick="simpletag('center')"><img title="$lang[bb_t_c]" src="engine/skins/bbcodes/images/c.gif" width="23" height="25" border="0"></div>
<div id="b_right"class="editor_button" onclick="simpletag('right')"><img title="$lang[bb_t_r]" src="engine/skins/bbcodes/images/r.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_color" class="editor_button" onclick="ins_color();"><img src="engine/skins/bbcodes/images/color.gif" width="23" height="25" border="0"></div>
<div id="b_spoiler" class="editor_button" onclick="simpletag('spoiler')"><img src="engine/skins/bbcodes/images/spoiler.gif" width="23" height="25" border="0"></div>
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
<div id="b_flash" class="editor_button" onclick="tag_flash()"><img src="engine/skins/bbcodes/images/flash.gif" width="23" height="25" border="0"></div>
<div id="b_youtube" class="editor_button" onclick="tag_youtube()"><img src="engine/skins/bbcodes/images/youtube.gif" width="23" height="25" border="0"></div>
{$typograf}
<div class="editor_button"><img src="engine/skins/bbcodes/images/brkspace.gif" width="5" height="25" border="0"></div>
</div>
<iframe width="154" height="104" id="cp" src="engine/skins/bbcodes/color.html" frameborder="0" vspace="0" hspace="0" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; display: none; position: absolute;"></iframe>
<div id="dle_emo" style="visibility:hidden; display: none; position: absolute; width:140px; height: 124px; overflow: auto; border: 1px solid #BBB; background:#E9E8F2;filter: alpha(opacity=95, enabled=1) progid:DXImageTransform.Microsoft.Shadow(color=#CACACA,direction=135,strength=3);">{$smiles}</div>
HTML;
?>