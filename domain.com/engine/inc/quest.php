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

if ( $member_id['user_group'] != 1 ) { msg( "error", $lang['addnews_denied'], $lang['db_denied'] ); }

if (isset ($_REQUEST['quest'])) $quest = $db->safesql(htmlspecialchars(strip_tags(trim($_REQUEST['quest'])))); else $quest = "";
if (isset ($_REQUEST['quest_answer'])) $quest_answer = htmlspecialchars(strip_tags(trim($_REQUEST['quest_answer']))); else $quest_answer = "";
if (isset ($_REQUEST['quest_id'])) $quest_id = intval($_REQUEST['quest_id']); else $quest_id = 0;

if($action == "add")
{

	if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {

		  die("Hacking attempt! User not found");

	}

if ($quest == "" OR $quest_answer=="") {
msg("error","Все поля обьзательны для заполнения!!","Все поля обязательны для заполнения!!", "$PHP_SELF?mod=quest"); 
}else{
	$db->query("INSERT INTO " . PREFIX . "_quest (quest, answer) values ('$quest','$quest_answer')");
}
}
elseif($action == "delete")
{

	if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {

		  die("Hacking attempt! User not found");

	}

    if(!$quest_id){ msg("error","Не забываем выбрать вопрос который нужно удалить","Не забываем выбрать вопрос который нужно удалить", "$PHP_SELF?mod=quest"); }

	$db->query("DELETE FROM " . PREFIX . "_quest WHERE id = '$quest_id'");
}



echoheader("", "");

echo <<<HTML
<form action="" method="post">
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Добавление контрольного вопроса</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td style="padding:2px;" colspan="2"></td> 
    </tr>
	<tr>
        <td colspan="2"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:2px;" width="200">Вопрос</td> 
		<td style="padding:2px;" width="100%"><input class="edit" style="width:250px;" type="text" name="quest" value=""></td>
    </tr>
    <tr>
        <td style="padding:2px;" width="200">Ответ</td> 
        <td style="padding:2px;" width="100%"><input class="edit" style="width:250px;" type="text" name="quest_answer" value=""></td>
    </tr>
        <tr>
        <td style="padding:2px;">&nbsp;</td> 
		<td style="padding:2px;"><input type="submit" value="{$lang['user_save']}" class="edit"></td>
    </tr>
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
<input type="hidden" name="action" value="add">
<input type="hidden" name="user_hash" value="$dle_login_hash">
</form>
HTML;
$count=$db->super_query("SELECT count(*) as count FROM " . PREFIX . "_quest");
echo <<<HTML
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Список вопросов ({$count['count']})</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="200" style="padding:2px;">Вопрос</td>
        <td width="250">Ответ</td>
        <td width="250">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
	<tr><td colspan="4"><div class="hr_line"></div></td></tr>
HTML;

	$db->query("SELECT * FROM " . PREFIX . "_quest ORDER BY id DESC");

$i = 0;
while($row = $db->get_row())
{
        $i++;
        echo"
        <tr>
        <td style=\"padding:3px\">
        {$row['quest']}
        </td>
        <td style=\"padding:3px\">
        {$row['answer']}
        </td>
        <td>
        [<a href=\"$PHP_SELF?mod=quest&action=delete&quest_id={$row['id']}&user_hash={$dle_login_hash}\">Удалить</a>]</td>
        </tr>
	</tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>
        ";
}

if($i == 0){     
echo"<tr>
     <td height=\"18\" colspan=\"4\">
       <p align=\"center\"><br><b>Нет вопросов значит нет ответов:()<br><br></b>
    </tr>"; }


echo <<<HTML
</table>
</td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
HTML;


echofooter();
?>