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

if ($mod != "editnews") {
$row['id'] = "";
$row['autor'] = $member_id['name'];
}

if (!isset ($row['full_story'])) $row['full_story'] = "";

echo <<<HTML
<tr>
	<td valign="top">
	<br />$lang[addnews_full]&nbsp;<br /><span class="navigation">($lang[addnews_alt])</span></td>
	<td><br />
    <textarea id="full_story" name="full_story" rows=15 cols=100>{$row['full_story']}</textarea>
</td></tr>
HTML;

?>