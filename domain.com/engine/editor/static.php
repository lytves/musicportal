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

if (!isset ($row['template'])) $row['template'] = "";

echo <<<HTML
<tr>
	<td valign="top" style="padding:2px;">{$lang['static_templ']}</td>
	<td>		<script type="text/javascript" src="engine/editor/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "template",
		language : "{$lang['wysiwyg_language']}",
		width : "98%",
		height : "400",
		plugins : "safari,layer,table,style,advhr,spellchecker,advimage,emotions,inlinepopups,insertdatetime,media,searchreplace,print,contextmenu,paste,fullscreen,nonbreaking,typograf",
		relative_urls : false,
		convert_urls : false,
		force_br_newlines : true,
        forced_root_block : '',
		force_p_newlines : false,
		dialog_type : 'window',
		extended_valid_elements : "noindex,div[align|class|style|id|title]",
		custom_elements : 'noindex',

		// Theme options
		theme_advanced_buttons1 : "fullscreen,|,cut,copy,|,paste,pastetext,pasteword,|,search,replace,|,outdent,indent,|,undo,redo,|,link,dle_leech,|,dle_upload,image,media,dle_mp,dle_mp3,emotions,dle_spoiler,|,dle_tube,dle_quote,dle_hide,dle_code,dle_break,dle_page",
		theme_advanced_buttons2 : "print,|,tablecontrols,|,hr,visualaid,|,styleprops,spellchecker,typograf,|,sub,sup,|,charmap,advhr,|,insertdate,inserttime,|,insertlayer,absolute",
		theme_advanced_buttons3 : "formatselect,fontselect,fontsizeselect,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,removeformat,cleanup,|,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
	   	plugin_insertdate_dateFormat : "%d-%m-%Y",
	    plugin_insertdate_timeFormat : "%H:%M:%S",
		spellchecker_languages : "+English=en,Russian=ru,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv",


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

				window.open('?mod=files&action=quick&area=template&wysiwyg=yes&author={$member_id['name']}&news_id={$row['id']}', '_Addimage', 'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=640,height=550');
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
    <textarea name="template" id="template" rows=10 cols=100>{$row['template']}</textarea></td></tr>
HTML;

?>