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

if ($user_group[$member_id['user_group']]['allow_url']) $link_icon = "link,dle_leech,separator,"; else $link_icon = "";
if ($user_group[$member_id['user_group']]['allow_image']) $link_icon .= "image,";

$wysiwyg = <<<HTML

<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "exact",
		theme : "advanced",
		elements : "comments",
		language : "{$lang['wysiwyg_language']}",
		width : "460",
		height : "220",
		plugins : "safari,emotions,inlinepopups",
		convert_urls : false,
		force_p_newlines : false,
		force_br_newlines : true,
		forced_root_block : '',
		dialog_type : 'window',
		extended_valid_elements : "div[align|class|style|id|title]",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,bullist,numlist,undo,redo,separator,{$link_icon}emotions,dle_quote,dle_hide",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,


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

			ed.addButton('dle_leech', {
			title : '{$lang['bb_t_leech']}',
			image : '{$config['http_home_url']}engine/editor/jscripts/tiny_mce/themes/advanced/img/dle_leech.gif',
			onclick : function() {

				var enterURL   = prompt("{$lang['bb_url']}", "http://");
				if (enterURL == null) enterURL = "http://";
				ed.execCommand('mceReplaceContent',false,"[leech="+enterURL+"]{\$selection}[/leech]");
			}
	           });

   		 }


	});
</script>
    <textarea id="comments" name="comments" rows="10" cols="70" style="resize:vertical;">{$text}</textarea>
HTML;

if ( $allow_subscribe ) $wysiwyg .= "<br /><input type=\"checkbox\" name=\"allow_subscribe\" id=\"allow_subscribe\" value=\"1\" /><label for=\"allow_subscribe\">" . $lang['c_subscribe'] . "</label><br />";


?>