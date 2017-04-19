<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

$shortarea = <<<HTML
<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "short_story,full_story",
		language : "{$lang['wysiwyg_language']}",
		width : "98%",
		height : "400",
		plugins : "safari,advhr,advimage,emotions,inlinepopups,insertdatetime,media,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking",
		auto_focus : "short_story",
		relative_urls : false,
		convert_urls : false,
        forced_root_block : '',
		media_strict : false,
		dialog_type : 'window',
		extended_valid_elements : "noindex,div[align|class|style|id|title]",
		custom_elements : 'noindex',

		// Theme options
		theme_advanced_buttons1 : "cut,copy,|,paste,pastetext,pasteword,|,search,replace,|,outdent,indent,|,undo,redo,|,dle_upload,image,media,dle_mp,dle_tube,dle_mp3,emotions,|,dle_break,dle_page",
		theme_advanced_buttons2 : "fontselect,fontsizeselect,|,sub,sup,|,charmap,advhr,|,insertdate,inserttime,|,nonbreaking,dle_quote,dle_code,dle_hide,|,visualaid",
		theme_advanced_buttons3 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,dle_spoiler,|,link,dle_leech,|,forecolor,backcolor,|,removeformat,cleanup,|,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
	   	plugin_insertdate_dateFormat : "%d-%m-%Y",
	    	plugin_insertdate_timeFormat : "%H:%M:%S",

		// Example content CSS (should be your site CSS)
		content_css : "{$config['http_home_url']}engine/editor/css/content.css",

		setup : function(ed) {
		        // Add a custom button
			ed.addButton('dle_quote', {
			title : '{$lang['bb_t_quote']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_quote.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[quote]' + ed.selection.getContent() + '[/quote]');
			}
	           });

			ed.addButton('dle_hide', {
			title : '{$lang['bb_t_hide']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_hide.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[hide]' + ed.selection.getContent() + '[/hide]');
			}
	           });

			ed.addButton('dle_code', {
			title : '{$lang['bb_t_code']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_code.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[code]' + ed.selection.getContent() + '[/code]');
			}
	           });

			ed.addButton('dle_spoiler', {
			title : '',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_spoiler.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceReplaceContent',false,'[spoiler]' + ed.selection.getContent() + '[/spoiler]');
			}
	           });

			ed.addButton('dle_break', {
			title : '{$lang['bb_t_br']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_break.gif',
			onclick : function() {
				// Add you own code to execute something on click
				ed.execCommand('mceInsertContent',false,'{PAGEBREAK}');
			}
	           });

			ed.addButton('dle_page', {
			title : '{$lang['bb_t_p']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_page.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_page']}", "1");
				var enterTITLE = prompt("{$lang['bb_page_name']}", "");
				if (enterURL == null) enterURL = "1";
				if (enterTITLE == null) enterTITLE = "";
				ed.execCommand('mceInsertContent',false,"[page="+enterURL+"]"+enterTITLE+"[/page]");
			}
	           });

			ed.addButton('dle_leech', {
			title : '{$lang['bb_t_leech']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_leech.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceReplaceContent',false,"[leech="+enterURL+"]{\$selection}[/leech]");
			}
	           });

			ed.addButton('dle_mp', {
			title : '{$lang['bb_t_video']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[video="+enterURL+"]");
			}
	           });

			ed.addButton('dle_tube', {
			title : '{$lang['bb_t_yvideo']} (BB Codes)',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_tube.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[youtube="+enterURL+"]");
			}
	           });

			ed.addButton('dle_upload', {
			title : 'Загрузка файлов на сервер',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_upload.gif',
			onclick : function() {

				window.open('{$config['http_home_url']}engine/images.php?area=short_story&wysiwyg=yes&add_id={$id}', '_Addimage', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=640,height=550');
			}
	           });

			ed.addButton('dle_mp3', {
			title : '',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_mp3.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceInsertContent',false,"[audio="+enterURL+"]");
			}
	           });
   		 }


	});
</script>
    <textarea id="short_story" name="short_story" rows="10" cols="50">{short-story}</textarea>
HTML;

?>