<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( ! $user_group[$member_id['user_group']]['admin_blockip'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}
if( $member_id['user_group'] != 1 ) {
	msg( "error", $lang['addnews_denied'], $lang['db_denied'] );
}

require ENGINE_DIR . '/data/mservice.php';
require ENGINE_DIR . '/modules/mservice/functions.php';

function OpenTable( ) {
echo <<<HTML
<table width="100%">
    <tr>
        <td width="4"><img src="engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="engine/skins/images/tl_oo.gif"><img src="engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="engine/skins/images/tl_lb.gif"><img src="engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
HTML;
}
function CloseTable( ) {
echo <<<HTML
    </td>
        <td background="engine/skins/images/tl_rb.gif"><img src="engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="engine/skins/images/tl_ub.gif"><img src="engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
HTML;
}
function EchoTableHeader( $title, $add = FALSE, $right_a = FALSE, $skobki = FALSE ) {
if ( $add != FALSE ) $add = ' [ <a href="' . $add . '" style="color:#3367AB;">вернуться назад</a> ]';
if ( $skobki = FALSE ) {$skobki_left = '[ ';$skobki_right = ' ]';}
if ( $right_a != FALSE ) $right_a = '<td bgcolor="#EFEFEF" height="29" style="padding:0 10px; text-align:right"><div class="navigation">'.$skobki_left.$right_a.$skobki_right.'</div></td>';
echo <<<HTML
<table width="100%" border="0">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$title}{$add}</div></td>
        {$right_a}
    </tr>
</table>
<div class="unterline"></div>
HTML;
}

switch ( $_REQUEST['act'] ) {

default :

echoheader("", "");

OpenTable();
EchoTableHeader( 'UnitPay - система электронных и мобильных платежей на сайте domain.com' );

echo <<<HTML
<table width="100%">
<tr>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=unitpay&act=info"><img src="{$config[http_home_url]}engine/skins/images/votes.png" border="0" align="left"><h3 style="color:#1b9ae0;">Статистика платежей в системе Unitpay</h3>Общая статистика платежей в системе Unitpay за всё время</a></div></td>
  <td width="50%"><div class="quick"><a href="{$PHP_SELF}?mod=unitpay&act=vipusers"><img src="{$config[http_home_url]}engine/skins/images/pset.png" border="0" align="left"><h3 style="color:#1b9ae0;">Пользователи в VIP группе</h3>Список пользователей, оплативших переход в VIP группу на сайте через систему UnitPay</a></div></td>
</tr>
</table>
HTML;

CloseTable();

OpenTable();
EchoTableHeader( 'Общая статистика подмодуля <span style="color:#cc0000;">(все значения кешируются!)</span>' );

//считаем платежи
$allpays_count =  unserialize(dle_cache2( "allpays_count" ));

if ( !$allpays_count ) {
  $allpays_count = $db->super_query( "SELECT COUNT(*) as count, (SELECT COUNT(*) FROM ".PREFIX."_unitpay_payments WHERE dateComplete is not NULL) as cnt, (SELECT COUNT(*) FROM ".PREFIX."_unitpay_payments WHERE  dateComplete is NULL) as cnt2 FROM ".PREFIX."_unitpay_payments" );
	create_cache2( "allpays_count", serialize($allpays_count) );
	$db->free( );
}

echo <<<HTML
<table border="0" width="100%">
<tr><td width="30%" style="padding-left:2px;padding-top:3px;">
Всего инициированных платежей:</td>
<td><b style="color:#F49804;">{$allpays_count[count]}</b></td></tr>
<tr><td width="30%" style="padding-left:2px;padding-top:3px;">
Оплаченных платежей:</td>
<td><b style="color:#9ACD32;">{$allpays_count[cnt]}</b></td></tr>
<tr><td width="30%" style="padding-left:2px;padding-top:3px;">
Ожидает оплаты:</td>
<td><b style="color:#cc0000;">{$allpays_count[cnt2]}</b></td></tr>
</table>
HTML;

CloseTable();

echofooter();

break;

case 'info' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'UnitPay - общая статистика', $PHP_SELF . '?mod=unitpay' );

echo <<<HTML
<script language='JavaScript' type="text/javascript">
<!--
function ckeck_uncheck_all() {
    var frm = document.mservicemass;
    for (var i=0;i<frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='checkbox') {
            if(frm.master_box.checked == true){ elmnt.checked=false; }
            else{ elmnt.checked=true; }
        }
    }
    if(frm.master_box.checked == true){ frm.master_box.checked = false; }
    else{ frm.master_box.checked = true; }
}
-->
</script>
<table width="100%" border="1" class="table">
<form action="" method="post" name="mservicemass">
<input type="hidden" name="act" value="massact" />
<thead><tr class="head">
<th width="5%" align="center">ID</th><th width="15%" align="center">Дата/время заявки</th><th width="11%" align="center">UnitPayID</th><th width="16%" align="center">Пользователь</th><th width="15%" align="center">Группа</th><th width="8%" align="center">Сумма, руб.</th><th width="15%" align="center">Дата/время оплаты</th><th width="15%" align="center">Статус</th>
<th width="4%" align="center"><input type="checkbox" name="master_box" title="Выбрать все" onclick="javascript:ckeck_uncheck_all()"></th>
</tr></thead>
<tbody>
HTML;

if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

$db->query( "SELECT p.id, p.unitpayid, p.account, p.sum, p.dateCreate, p.dateComplete, p.status, (SELECT COUNT(*) FROM ".PREFIX."_unitpay_payments) AS cnt, u.name, u.user_group, g.group_name FROM ".PREFIX."_unitpay_payments p LEFT JOIN ".USERPREFIX."_users u ON p.account = u.user_id LEFT JOIN ".USERPREFIX."_usergroups g ON u.user_group = g.id ORDER BY p.id DESC LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );

if ( $db->num_rows( ) == 0 ) {
	echo <<<HTML
<tr><td style="padding-left:10px;" colspan="9">Ещё нет записей в таблице UnitPay!</td></tr></table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

while ( $row = $db->get_row( ) ) {
	if (!$count) $count = $row['cnt'];

	if ( $row['user_group'] == 1 OR $row['user_group'] == 2) $usergroup = '<span style="color:red; font-weight:bold;">'.$row['group_name'].'</span>';
	elseif ($row['user_group'] == 7) $usergroup = '<span style="color:#ff00b2; font-weight:bold;">'.$row['group_name'].'</span>';
	else $usergroup = $row['group_name'];
	
	$user_name = "<a href=\"{$config['http_home_url']}index.php?subaction=userinfo&user=" . urlencode( $row['name'] ) . "\" target=\"_blank\">" . $row[name] . "</a>";
	
	if (!$row['dateComplete']) $date2 = '-';
	
	if ($row['status'] == 0) $status = '<span style="color:red;">ожидает оплаты</span>';
	elseif ($row['status'] == 1) $status = '<span style="color:green; font-weight:bold;">оплачена</span>';	
	else $status = 'неизвестный';
	
	echo "<tr><td align='center' style='padding:0 5px;'>{$row['id']}</td><td align='center'>{$row['dateCreate']}</td><td align='center'>{$row['unitpayid']}</td><td align='center'>{$user_name}</td><td align='center'>{$usergroup}</td><td align='center'>{$row['sum']}</td><td align='center'>{$date2}</td><td align='center'>{$status}</td><td align='center'>
<input name='selected_track[]' value='{$row[id]}' type='checkbox' /></td></tr>";
}

echo <<<HTML
</tbody>
</table>
<br /><div align="right" style="padding-right:20px;">

<select name="action">
<option value="">--- Действие ---</option>
</select>
<input type="submit" value="  Выполнить  " class="edit" /></div>
</form>
HTML;

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=unitpay&act=info&page={$t2}{$this_approve}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=unitpay&act=info&page='.$npage.'">Назад</a> ';
else $prev_page = '<span>Назад</span> ';

$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=unitpay&act=info&page='.$npage.'">Далее</a>';
else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}

CloseTable( );
echofooter( );

break;

case 'vipusers' :

echoheader( '', '' );
OpenTable( );
EchoTableHeader( 'UnitPay - пользователи в VIP группе', $PHP_SELF . '?mod=unitpay' );

echo <<<HTML
<table style="text-align:center;" width="100%" border="1" class="table">
<thead><tr class="head">
        <th width="17%" style="padding:2px;">{$lang['user_name']}</th>
        <th width="7%">user_id</th>
        <th width="15%">{$lang['user_reg']}</th>
        <th width="7%">vAuth</th>
        <th width="15%">{$lang['user_last']}</th>
        <th width="7%">Публ-ций:</th>
        <th width="7%">Комм-риев:</th>
        <th width="20%" align="center">{$lang['user_acc']}</th>
        <th width="5%"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()"></th>
    </tr>
</thead>
<tbody>
HTML;


if ( $_REQUEST['page'] == FALSE ) $page = 1; else $page = intval( $_REQUEST['page'] );
$limit = ( $page * $mscfg['admin_track_page_lim'] ) - $mscfg['admin_track_page_lim'];

$db->query( "SELECT DISTINCT user_id, name, user_group, reg_date, lastdate, news_num, comm_num, banned, fs_registered, go_registered, ma_registered, ms_registered, in_registered, gh_registered, od_registered, vk_registered, fb_registered, tw_registered, ya_registered, (SELECT COUNT(DISTINCT user_id) FROM ".USERPREFIX."_users u2 RIGHT JOIN ".PREFIX."_unitpay_payments p2 ON u2.user_id = p2.account WHERE user_group = '7') as cnt FROM ".USERPREFIX."_users u RIGHT JOIN ".PREFIX."_unitpay_payments p ON u.user_id = p.account WHERE user_group = '7' LIMIT {$limit},{$mscfg[admin_track_page_lim]}" );

if ( $db->num_rows( ) == 0 ) {
	echo <<<HTML
<tr><td style="padding-left:10px;" colspan="9">Ещё нет записей в таблице UnitPay!</td></tr></table>
HTML;
	CloseTable( );
	echofooter( );
	break;
}

	while ( $row = $db->get_row() ) {
		    if (!$count) $count = $row['cnt'];
		$last_login = langdate( 'd/m/Y - H:i', $row['lastdate'] );
		$user_name = "<a href=\"{$config['http_home_url']}index.php?subaction=userinfo&user=" . urlencode( $row['name'] ) . "\" target=\"_blank\">" . $row[name] . "</a>";
		if( $row[news_num] == 0 ) {
			$news_link = "$row[news_num]";
		} else {
			$news_link = "[<a href=\"{$config['http_home_url']}index.php?subaction=allnews&user=" . urlencode( $row['name'] ) . "\" target=\"_blank\">" . $row[news_num] . "</a>]";
		}
		if( $row[comm_num] == 0 ) {
			$comms_link = $row['comm_num'];
		} else {
			$comms_link = "[<a onClick=\"return dropdownmenu(this, event, MenuBuild('" . $row['user_id'] . "'), '150px')\" href=\"#\" >" . $row[comm_num] . "</a>]";
		}
		
		if( $row['banned'] == 'yes' ) $user_level = "<font color=\"red\">" . $lang['user_ban'] . "</font>";
		else {
      $user_level = $user_group[$row['user_group']]['group_name'];
      if( $row['user_group'] == 1 ) $user_level = '<span style="color:red; font-weight:bold;">'.$user_level.'</span>';
      elseif( $row['user_group'] == 7 ) $user_level = '<span style="color:#ff00b2; font-weight:bold;">'.$user_level."</span>";
		}
		
		if ($row['fs_registered'] == 1 OR $row['go_registered'] == 1 OR $row['ma_registered'] == 1 OR $row['ms_registered'] == 1 OR $row['in_registered'] == 1 OR $row['gh_registered'] == 1 OR $row['od_registered'] == 1 OR $row['vk_registered'] == 1 OR $row['fb_registered'] == 1 OR $row['tw_registered'] == 1 OR $row['ya_registered'] == 1) $vauth_yes = "<a href=\"{$config['http_home_url']}user/" . urlencode( $row['name'] ) . "/\" target=\"_blank\">" . vAuth . "</a>";
		else $vauth_yes = "";
		
		echo "<tr>
        <td style='text-align:left; padding-left:5px;'>&nbsp;$user_name</td>
        <td width=20 align=\"center\">$row[user_id]</td>
        <td width=120 nowrap='nowrap'>";
		echo (langdate( "d/m/Y - H:i", $row['reg_date'] ));
		echo "</td>
		    <td width=80>".$vauth_yes."</td>
        <td width=120 nowrap='nowrap'>
        ".$last_login."</td>
        <td width=50 align=\"center\">
        ".$news_link."</td>
        <td width=50 align=\"center\">
        ".$comms_link."</td>
        <td width=112 align=\"center\">
        &nbsp;".$user_level."</td>
        <td width=\"10\"><input name=\"selected_users[]\" value=\"{$row['user_id']}\" type='checkbox'></td>
        </tr>";
	}
	$db->free();

echo <<<HTML
</tbody>
</table>
<br /><div align="right" style="padding-right:20px;">

<select name="action">
<option value="">--- Действие ---</option>
</select>
<input type="submit" value="  Выполнить  " class="edit" /></div>
</form>
HTML;

// Постраничная навигация
$count_d = $count / $mscfg['admin_track_page_lim'];

for ( $t = 0; $count_d > $t; $t ++ ) {
$t2 = $t + 1;

if ( $t2 == $page ) $pages .= "<span>{$t2}</span> ";
else $pages .= "<a href='?mod=unitpay&act=vipusers&page={$t2}{$this_approve}'>{$t2}</a> ";
$array[$t2] = 1;
}

$npage = $page - 1;
if ( isset($array[$npage]) ) $prev_page = ' <a href="?mod=unitpay&act=vipusers&page='.$npage.'">Назад</a> ';
else $prev_page = '<span>Назад</span> ';

$npage = $page + 1;

if ( isset($array[$npage]) ) $next_page = ' <a href="?mod=unitpay&act=vipusers&page='.$npage.'">Далее</a>';
else $next_page = ' <span>Далее</span>';

if ( $count > $mscfg['admin_track_page_lim'] ) {
echo <<<HTML
<br /><div class="mservice_navigation" align="center" style="margin:10px 0;">{$prev_page}{$pages}{$next_page}</div>
HTML;
}

CloseTable( );
echofooter( );

break;

}
?>
