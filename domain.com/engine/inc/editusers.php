<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

if( ! $user_group[$member_id['user_group']]['admin_editusers'] ) {
	msg( "error", $lang['index_denied'], $lang['index_denied'] );
}

$id = intval( $_REQUEST['id'] );

// ********************************************************************************
// Список пользователей
// ********************************************************************************
if( $action == "list" ) {
	echoheader( "users", $lang['user_head'] );
	
	echo '<script language="javascript">
    <!-- begin
    function popupedit(id){
    window.open(\'' . $PHP_SELF . '?mod=editusers&action=edituser&id=\'+id,\'User\',\'toolbar=0,location=0,status=0, left=0, top=0, menubar=0,scrollbars=yes,resizable=0,width=540,height=500\');
    }
    function confirmdelete(id, user){
    var agree=confirm("' . $lang['user_deluser'] . '");
    if (agree)
    document.location="' . $PHP_SELF . '?mod=editusers&user_hash=' . $dle_login_hash . '&action=dodeleteuser&id="+id+"&user="+user;
    }
    function clearform(frm){
    for (var i=0;i<frm.length;i++) {
      var el=frm.elements[i];
      if (el.type=="checkbox" || el.type=="radio") { el.checked=0; continue; }
      if ((el.type=="text") || (el.type=="textarea") || (el.type == "password")) { el.value=""; continue; }
      if ((el.type=="select-one") || (el.type=="select-multiple")) { el.selectedIndex=0; }
    }
    document.searchform.start_from.value="";
    }
    function list_submit(prm){
      document.searchform.start_from.value=prm;
      document.searchform.submit();
      return false;
    }
    // end -->
    </script>';
	
	$grouplist = get_groups( 4 );
	
	$search_name = $db->safesql( trim( htmlspecialchars( strip_tags( $_REQUEST['search_name'] ) ) ) );
	$search_mail = $db->safesql( trim( htmlspecialchars( strip_tags( $_REQUEST['search_mail'] ) ) ) );
	
	echo <<<HTML
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />
<script type="text/javascript" src="engine/skins/calendar.js"></script>
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>
<form method="post" action="">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['user_auser']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="140" style="padding:4px;">{$lang['user_name']}</td>
        <td><input class="edit" size="21" type="text" name="regusername"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_pass']}</td>
        <td><input class="edit" size="21" type="text" name="regpassword"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_mail']}</td>
        <td><input class="edit" size="21" type="text" name="regemail"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_acc']}</td>
        <td><select name="reglevel">
           {$grouplist}
             </select>
        </td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="padding-top:10px;"><input type="submit" class="buttons" value="{$lang['vote_new']}" style="width:100px;">
          <input type="hidden" name="action" value="adduser">
		  <input type="hidden" name="user_hash" value="$dle_login_hash" />
            <input type="hidden" name="mod" value="editusers"></td>
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
</div></form>
HTML;
	
	if( $search_reglevel ) $group_list = get_groups( $search_reglevel );
	else $group_list = get_groups();
	
	if( $search_banned == "yes" ) $ifch = "checked";
	
	$search_order_user = array ('----' => '', 'asc' => '', 'desc' => '' );
	if( ! empty( $search_order_u ) ) {
		$search_order_user[$search_order_u] = 'selected';
	} else {
		$search_order_user['----'] = 'selected';
	}
	$search_order_reg = array ('----' => '', 'asc' => '', 'desc' => '' );
	if( ! empty( $search_order_r ) ) {
		$search_order_reg[$search_order_r] = 'selected';
	} else {
		$search_order_reg['----'] = 'selected';
	}
	$search_order_last = array ('----' => '', 'asc' => '', 'desc' => '' );
	if( ! empty( $search_order_l ) ) {
		$search_order_last[$search_order_l] = 'selected';
	} else {
		$search_order_last['----'] = 'selected';
	}
	$search_order_news = array ('----' => '', 'asc' => '', 'desc' => '' );
	if( ! empty( $search_order_n ) ) {
		$search_order_news[$search_order_n] = 'selected';
	} else {
		$search_order_news['----'] = 'selected';
	}
	$search_order_coms = array ('----' => '', 'asc' => '', 'desc' => '' );
	if( ! empty( $search_order_c ) ) {
		$search_order_coms[$search_order_c] = 'selected';
	} else {
		$search_order_coms['----'] = 'selected';
	}
	
	echo <<<HTML
<form name="searchform" id="searchform" method="post" action="$PHP_SELF?mod=editusers&action=list">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['user_se']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="140" style="padding:2px;">{$lang['user_name']}</td>
        <td style="padding-bottom:4px;"><input size="21" class="edit" type="text" name="search_name" id="search_name" value="{$search_name}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_user]}', this, event, '300px')">[?]</a></td>
        <td style="padding-left:5px;">{$lang['edit_regdate']}</td>
        <td style="padding-left:5px;">{$lang['edit_fdate']}</td>
        <td><input type="text" name="fromregdate" id="fromregdate" size="17" maxlength="16" class="edit" value="{$fromregdate}">
<img src="engine/skins/images/img.gif"  align="absmiddle" id="f_trigger_reg" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "fromregdate",     // id of the input field
      ifFormat       :    "%Y-%m-%d",      // format of the input field
      button         :    "f_trigger_reg",  // trigger for the calendar (button ID)
      align          :    "Br",           // alignment 
		  timeFormat     :    "24",
		  showsTime      :    true,
      singleClick    :    true
    });
</script></td>
        <td style="padding-left:5px;">{$lang['edit_tdate']}</td>
        <td><input type="text" name="toregdate" id="toregdate" size="17" maxlength="16" class="edit" value="{$toregdate}">
<img src="engine/skins/images/img.gif"  align="absmiddle" id="t_trigger_reg" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "toregdate",     // id of the input field
      ifFormat       :    "%Y-%m-%d",      // format of the input field
      button         :    "t_trigger_reg",  // trigger for the calendar (button ID)
      align          :    "Br",           // alignment 
		  timeFormat     :    "24",
		  showsTime      :    true,
      singleClick    :    true
    });
</script></td>

    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_mail']}</td>
        <td><input size="21" class="edit" type="text" name="search_mail" id="search_mail" value="{$search_mail}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_mail]}', this, event, '300px')">[?]</a></td>

        <td style="padding-left:5px;">{$lang['edit_entedate']}</td>
        <td style="padding-left:5px;">{$lang['edit_fdate']}</td>
        <td><input type="text" name="fromentdate" id="fromentdate" size="17" maxlength="16" class="edit" value="{$fromentdate}">
<img src="engine/skins/images/img.gif"  align="absmiddle" id="f_trigger_ent" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "fromentdate",     // id of the input field
      ifFormat       :    "%Y-%m-%d",      // format of the input field
      button         :    "f_trigger_ent",  // trigger for the calendar (button ID)
      align          :    "Br",           // alignment 
		  timeFormat     :    "24",
		  showsTime      :    true,
      singleClick    :    true
    });
</script></td>
        <td style="padding-left:5px;">{$lang['edit_tdate']}</td>
        <td><input type="text" name="toentdate" id="toentdate" size="17" maxlength="16" class="edit" value="{$toentdate}">
<img src="engine/skins/images/img.gif"  align="absmiddle" id="t_trigger_ent" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "toentdate",     // id of the input field
      ifFormat       :    "%Y-%m-%d",      // format of the input field
      button         :    "t_trigger_ent",  // trigger for the calendar (button ID)
      align          :    "Br",           // alignment 
		  timeFormat     :    "24",
		  showsTime      :    true,
      singleClick    :    true
    });
</script></td>

    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_banned']}</td>
        <td><input type="checkbox" name="search_banned" id="search_banned" value="yes" $ifch></td>
        <td style="padding-left:5px;">{$lang['edit_newsnum']}</td>
        <td style="padding-left:5px;">{$lang['edit_fdate']}</td>
        <td><input class="edit" type="text" name="search_news_f" id="search_news_f" size="8" maxlength="7" value="{$search_news_f}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_newsnum]}', this, event, '300px')">[?]</a></td>
        <td style="padding-left:5px;">{$lang['edit_tdate']}</td>
        <td><input class="edit" type="text" name="search_news_t" id="search_news_t" size="8" maxlength="7" value="{$search_news_t}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_newsnum]}', this, event, '300px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:2px;">{$lang['user_acc']}</td>
        <td><select name="search_reglevel" id="search_reglevel">
           <option selected value="0">{$lang['edit_all']}</option>
           {$group_list}
            </select>
        </td>
        <td style="padding-left:5px;">{$lang['edit_comsnum']}</td>
        <td style="padding-left:5px;">{$lang['edit_fdate']}</td>
        <td><input class="edit" type="text" name="search_coms_f" id="search_coms_f" size="8" maxlength="7" value="{$search_coms_f}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_comsnum]}', this, event, '300px')">[?]</a></td>
        <td style="padding-left:5px;">{$lang['edit_tdate']}</td>
        <td><input class="edit" type="text" name="search_coms_t" id="search_coms_t" size="8" maxlength="7" value="{$search_coms_t}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_comsnum]}', this, event, '300px')">[?]</a></td>

    </tr>
    <tr>
        <td colspan="7"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td colspan="7" style="padding-left:5px;">{$lang['user_order']}</td>
    </tr>
    <tr>
        <td style="padding:5px;">{$lang['user_name']}</td>
        <td style="padding:5px;">{$lang['user_reg']}</td>
        <td style="padding:5px;">{$lang['user_last']}</td>
        <td style="padding:5px;" colspan="2">{$lang['user_news']}</td>
        <td style="padding:5px;" colspan="2">{$lang['user_coms']}</td>
    </tr>
    <tr>
        <td style="padding-left:2px;"><select name="search_order_u" id="search_order_u">
           <option {$search_order_user['----']} value="">{$lang['user_order_no']}</option>
           <option {$search_order_user['asc']} value="asc">{$lang['user_order_plus']}</option>
           <option {$search_order_user['desc']} value="desc">{$lang['user_order_minus']}</option>
            </select>
        </td>
        <td style="padding-left:2px;"><select name="search_order_r" id="search_order_r">
           <option {$search_order_reg['----']} value="">{$lang['user_order_no']}</option>
           <option {$search_order_reg['asc']} value="asc">{$lang['user_order_plus']}</option>
           <option {$search_order_reg['desc']} value="desc">{$lang['user_order_minus']}</option>
            </select>
        </td>
        <td style="padding-left:2px;"><select name="search_order_l" id="search_order_l">
           <option {$search_order_last['----']} value="">{$lang['user_order_no']}</option>
           <option {$search_order_last['asc']} value="asc">{$lang['user_order_plus']}</option>
           <option {$search_order_last['desc']} value="desc">{$lang['user_order_minus']}</option>
            </select>
        </td>
        <td style="padding-left:2px;" colspan="2"><select name="search_order_n" id="search_order_n">
           <option {$search_order_news['----']} value="">{$lang['user_order_no']}</option>
           <option {$search_order_news['asc']} value="asc">{$lang['user_order_plus']}</option>
           <option {$search_order_news['desc']} value="desc">{$lang['user_order_minus']}</option>
            </select>
        </td>
        <td style="padding-left:2px;" colspan="2"><select name="search_order_c" id="search_order_c">
           <option {$search_order_coms['----']} value="">{$lang['user_order_no']}</option>
           <option {$search_order_coms['asc']} value="asc">{$lang['user_order_plus']}</option>
           <option {$search_order_coms['desc']} value="desc">{$lang['user_order_minus']}</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="7"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="padding-top:10px;"><input type="submit" class="buttons" value="{$lang['b_find']}" style="width:100px;">
        <td style="padding-top:10px;"><input type="button" class="buttons" value="{$lang['user_breset']}" onclick="javascript:clearform(document.searchform); return false;" style="width:100px;">
        <td colspan="4" style="padding-top:10px;"><input type="reset" class="buttons" value="{$lang['user_brestore']}" style="width:100px;">
          <input type="hidden" name="action" id="action" value="list">
          <input type="hidden" name="search" id="search" value="search">
          <input type="hidden" name="start_from" id="start_from" value="{$start_from}">
          <input type="hidden" name="mod" id="mod" value="editusers"></td>
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
</div></form>
HTML;
	
	$where = array ();
	
	if( ! empty( $search ) ) {
		$where[] = "name like '$search_name%'";
	}
	if( ! empty( $search_mail ) ) {
		$where[] = "email='$search_mail'";
	}
	if( ! empty( $search_banned ) ) {
		$search_banned = $db->safesql( $search_banned );
		$where[] = "banned='$search_banned'";
	}
	if( ! empty( $fromregdate ) ) {
		$where[] = "reg_date>='" . strtotime( $fromregdate ) . "'";
	}
	if( ! empty( $toregdate ) ) {
		$where[] = "reg_date<='" . strtotime( $toregdate ) . "'";
	}
	if( ! empty( $fromentdate ) ) {
		$where[] = "lastdate>='" . strtotime( $fromentdate ) . "'";
	}
	if( ! empty( $toentdate ) ) {
		$where[] = "lastdate<='" . strtotime( $toentdate ) . "'";
	}
	if( ! empty( $search_news_f ) ) {
		$search_news_f = intval( $search_news_f );
		$where[] = "news_num>='$search_news_f'";
	}
	if( ! empty( $search_news_t ) ) {
		$search_news_t = intval( $search_news_t );
		$where[] = "news_num<'$search_news_t'";
	}
	if( ! empty( $search_coms_f ) ) {
		$search_coms_f = intval( $search_coms_f );
		$where[] = "comm_num>='$search_coms_f'";
	}
	if( ! empty( $search_coms_t ) ) {
		$search_coms_t = intval( $search_coms_t );
		$where[] = "comm_num<'$search_coms_t'";
	}
	if( $search_reglevel ) {
		$search_reglevel = intval( $search_reglevel );
		$where[] = "user_group='$search_reglevel'";
	}
	
	$where = implode( " AND ", $where );
	if( ! $where ) $where = "user_group < '4'";
	
	$order_by = array ();
	
	if( ! empty( $search_order_u ) ) {
		$order_by[] = "name $search_order_u";
	}
	if( ! empty( $search_order_r ) ) {
		$order_by[] = "reg_date $search_order_r";
	}
	if( ! empty( $search_order_l ) ) {
		$order_by[] = "lastdate $search_order_l";
	}
	if( ! empty( $search_order_n ) ) {
		$order_by[] = "news_num $search_order_n";
	}
	if( ! empty( $search_order_c ) ) {
		$order_by[] = "comm_num $search_order_c";
	}
	
	$order_by = implode( ", ", $order_by );
	if( ! $order_by ) $order_by = "reg_date asc";
	
	// ------ Запрос к базе
	$query_count = "SELECT COUNT(*) as count FROM " . USERPREFIX . "_users WHERE $where";
	$result_count = $db->super_query( $query_count );
	$all_count_news = $result_count['count'];
	
	echo <<<HTML
<script type="text/javascript" src="engine/ajax/menu.js"></script>
<script language="javascript" type="text/javascript">
<!--
function cdelete(id){
    var agree=confirm("{$lang['comm_alldelconfirm']}");
    if (agree)
    document.location='?mod=editusers&action=dodelcomments&user_hash={$dle_login_hash}&id=' + id + '';
}
function MenuBuild( m_id ){

var menu=new Array()

menu[0]='<a href="{$config['http_home_url']}index.php?do=lastcomments&userid=' + m_id + '" target="_blank">{$lang['comm_view']}</a>';
menu[1]='<a onClick="javascript:cdelete(' + m_id + '); return(false)" href="?mod=editusers&action=dodelcomments&user_hash={$dle_login_hash}&id=' + m_id + '" >{$lang['comm_del']}</a>';

return menu;
}

function ckeck_uncheck_all() {
    var frm = document.editusers;
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

//-->
</script>
<form action="" method="post" name="editusers">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['user_list']} ({$all_count_news})</div></td>
    </tr>
</table>
<div class="unterline"></div>
<table style="text-align:center;" width="100%" border="0">
    <tr bgcolor="#9fc4e1">
        <td width="130" style="padding:2px;">{$lang['user_name']}</td>
        <td width="20">user_id</td>
        <td width="120">{$lang['user_reg']}</td>
        <td width="80">vAuth</td>
        <td width="120">{$lang['user_last']}</td>
        <td width="50">Публ-ций:</td>
        <td width="50">Комм-риев:</td>
        <td width="112" align="center">{$lang['user_acc']}</td>
        <td width="103">{$lang['user_action']}</td>
        <td width="10"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()"></td>
    </tr>
	<tr><td colspan="10"><div class="hr_line"></div></td></tr>
HTML;
	
	$news_per_page = 50;
	$start_from = intval( $start_from );
	$i = $start_from;
	
	// ------ Запрос к базе
	$db->query( "SELECT user_id, name, user_group, reg_date, lastdate, news_num, comm_num, banned, fs_registered, go_registered, ma_registered, ms_registered, in_registered, gh_registered, od_registered, vk_registered, fb_registered, tw_registered, ya_registered FROM " . USERPREFIX . "_users WHERE {$where} ORDER BY {$order_by} LIMIT {$start_from},{$news_per_page}" );
	
	while ( $row = $db->get_row() ) {
		$i ++;
		
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
		$user_delete = "[<a class=maintitle onClick=\"javascript:confirmdelete('" . $row[user_id] . "', '" . $row[name] . "'); return(false)\"  href=\"" . $PHP_SELF . "?mod=editusers&user_hash={$dle_login_hash}&action=dodeleteuser&id=" . $row[user_id] . "&user=" . $row[name] . "\">" . $lang[user_del] . "</a>]";
		
		if( $row['banned'] == 'yes' ) $user_level = "<font color=\"red\">" . $lang['user_ban'] . "</font>";
		else {
      $user_level = $user_group[$row['user_group']]['group_name'];
      if( $row['user_group'] == 1 ) $user_level = '<span style="color:red; font-weight:bold;">'.$user_level.'</span>';
      elseif( $row['user_group'] == 7 ) $user_level = '<span style="color:#ff00b2; font-weight:bold;">'.$user_level."</span>";
		}
		if( $row['user_group'] == 1 ) $user_delete = "";
		
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
        <td width=133 title='' class='list' nowrap='nowrap'><nobr>
        [<a class=maintitle onClick=\"javascript:popupedit('".$row[user_id]."'); return(false)\" href=#>".$lang[user_edit]."</a>]&nbsp;".$user_delete."&nbsp;[<a class=maintitle href=\"{$config['http_home_url']}index.php?do=feedback&user=".$row[user_id]."\" target=\"_blank\">".$lang[bb_b_mail]."</a>]&nbsp;[<a class=maintitle href=\"{$config['http_home_url']}index.php?do=pm&doaction=newpm&user=".$row[user_id]."\" target=\"_blank\">".$lang[nl_pm]."</a>]
        </nobr></td>
        <td width=\"10\"><input name=\"selected_users[]\" value=\"{$row['user_id']}\" type='checkbox'></td>
        </tr><tr><td background=\"engine/skins/images/mline.gif\" height=1 colspan=10></td></tr>";
	}
	$db->free();
	
	$npp_nav = "";
	
	if( $start_from > 0 ) {
		$previous = $start_from - $news_per_page;
		$npp_nav .= "<a onClick=\"javascript:list_submit($previous); return(false)\" href=#>&lt;&lt; $lang[edit_prev]</a>";
	}
	
	// pagination
	

	if( $all_count_news > $news_per_page ) {
		$npp_nav .= " [ ";
		$enpages_count = @ceil( $all_count_news / $news_per_page );
		$enpages_start_from = 0;
		$enpages = "";
		for($j = 1; $j <= $enpages_count; $j ++) {
			if( $enpages_start_from != $start_from ) {
				$enpages .= "<a class=maintitle onClick=\"javascript:list_submit($enpages_start_from); return(false)\" href=#>$j</a> ";
			} else {
				$enpages .= "<span class=navigation> $j </span>";
			}
			$enpages_start_from += $news_per_page;
		}
		$npp_nav .= $enpages;
		$npp_nav .= " ] ";
	}
	
	// pagination
	

	if( $all_count_news > $i ) {
		$how_next = $all_count_news - $i;
		if( $how_next > $news_per_page ) {
			$how_next = $news_per_page;
		}
		$npp_nav .= "<a onClick=\"javascript:list_submit($i); return(false)\" href=#>$lang[edit_next] $how_next &gt;&gt;</a>";
	}
	
	echo <<<HTML
	<tr><td colspan="10" align="left" height="30">{$npp_nav}</td></tr>
	<tr><td colspan="10" align="right" valign="top">
<div style="margin-bottom:5px; margin-top:5px;">
<select name=action>
<option value="">{$lang['edit_selact']}</option>
<option value="mass_move_to_group">{$lang['massusers_group']}</option>
<option value="mass_move_to_ban">{$lang['massusers_banned']}</option>
<option value="mass_delete_comments">{$lang['massusers_comments']}</option>
<option value="mass_delete">{$lang['massusers_delete']}</option>
</select>
<input type=hidden name=mod value="mass_user_actions">
<input type="hidden" name="user_hash" value="$dle_login_hash" />
<input class="edit" type="submit" value="{$lang['b_start']}">
</div></td></tr>
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
</div></form>
HTML;
	
	echofooter();
} 
// ********************************************************************************
// Добавление пользователя
// ********************************************************************************
elseif( $action == "adduser" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	if( ! $_POST['regusername'] ) {
		msg( "error", $lang['user_err'], $lang['user_err_1'], "javascript:history.go(-1)" );
	}

	if( preg_match( "/[\||\'|\<|\>|\"|\!|\$|\@|\&\~\*\+]/", $_POST['regusername'] ) ) msg( "error", $lang['user_err'], $lang['user_err_1'], "javascript:history.go(-1)" );

	if( ! $_POST['regpassword'] ) {
		msg( "error", $lang['user_err'], $lang['user_err_2'], "javascript:history.go(-1)" );
	}
	if( (! preg_match('/^([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])'.'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)+$/i', $_POST['regemail'] )) or (empty( $_POST['regemail'] )) ) {
		msg( "error", $lang['user_err_1'], $lang['user_err_1'], "javascript:history.go(-1)" );
	}

	$regusername = $db->safesql($_POST['regusername']);
	$regemail    = $db->safesql($_POST['regemail']);
	
	$row = $db->super_query( "SELECT name, email FROM " . USERPREFIX . "_users WHERE name = '$regusername' OR email = '$regemail'" );
	
	if( $row['name'] ) {
		msg( "error", $lang['user_err'], $lang['user_err_3'], "javascript:history.go(-1)" );
	}
	if( $row['email'] ) {
		msg( "error", $lang['user_err'], $lang['user_err_4'], "javascript:history.go(-1)" );
	}
	
	$add_time = time() + ($config['date_adjust'] * 60);
	$regpassword = md5( md5( $_POST['regpassword'] ) );

	$reglevel = intval( $_POST['reglevel'] );

	if ( $member_id['user_group'] != 1 AND $reglevel < 2 ) $reglevel = 4;
	
	$db->query( "INSERT INTO " . USERPREFIX . "_users (name, password, email, user_group, reg_date, lastdate, info, signature, favorites, xfields) values ('$regusername', '$regpassword', '$regemail', '$reglevel', '$add_time', '$add_time','','','','')" );
	
	msg( "info", $lang['user_addok'], "$lang[user_ok] <b>$regusername</b> $lang[user_ok_1] <b>{$user_group[$reglevel]['group_name']}</b>", "$PHP_SELF?mod=editusers&action=list" );
} 
// ********************************************************************************
// Редактирование пользователя
// ********************************************************************************
elseif( $action == "edituser" ) {
	
	if( isset( $_REQUEST['user'] ) ) {
		
		$user = $db->safesql( strip_tags( urldecode( $_GET['user'] ) ) );
		
		if( $user != "" ) {
			
			$row = $db->super_query( "SELECT user_id FROM " . USERPREFIX . "_users WHERE name = '$user'" );
			
			if( ! $row['user_id'] ) die( "User not found" );
			
			header( "Location: ?mod=editusers&action=edituser&id=" . $row['user_id'] );
			die( "User not found" );
		
		}
	}
	
	$row = $db->super_query( "SELECT " . USERPREFIX . "_users.*, " . USERPREFIX . "_banned.days, " . USERPREFIX . "_banned.descr, " . USERPREFIX . "_banned.date as banned_date FROM " . USERPREFIX . "_users LEFT JOIN " . USERPREFIX . "_banned ON " . USERPREFIX . "_users.user_id=" . USERPREFIX . "_banned.users_id WHERE user_id = '$id'" );
	
	if( ! $row['user_id'] ) die( "User not found" );

	if ($row['user_id'] != 1)  $delete_link = "<input onClick=\"confirmDelete('$PHP_SELF?mod=editusers&action=dodeleteuser&popup=yes&id={$id}&user_hash={$dle_login_hash}')\" type=\"button\" class=\"buttons\" value=\"{$lang['edit_dnews']}\" style=\"width:100px;\">"; else $delete_link = "";

	if ($member_id['user_group'] != 1 AND $row['user_group'] == 1 )
		die( $lang['edit_not_admin'] );
	
	include_once ENGINE_DIR . '/classes/parse.class.php';
	
	$parse = new ParseFilter( );
	
	$row['fullname'] = $parse->decodeBBCodes( $row['fullname'], false );
	$row['icq'] = $parse->decodeBBCodes( $row['icq'], false );
	$row['land'] = $parse->decodeBBCodes( $row['land'], false );
	$row['info'] = $parse->decodeBBCodes( $row['info'], false );
	$row['signature'] = $parse->decodeBBCodes( $row['signature'], false );
	$row['descr'] = $parse->decodeBBCodes( $row['descr'], false );
	
	echo <<<HTML
<html>
<head>
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<title>{$lang['user_edhead']}</title>
<style type="text/css">
html,body{
height:100%;
margin:0px;
padding: 0px;
background: #F4F3EE;
}
input,
select,
textarea {
	outline:none;
}
form {
margin:0px;
padding: 0px;
}

table{
border:0px;
border-collapse:collapse;
}

table td{
padding:0px;
font-size: 11px;
font-family: verdana;
}

a:active,
a:visited,
a:link {
	color: #4b719e;
	text-decoration:none;
	}

a:hover {
	color: #4b719e;
	text-decoration: underline;
	}

.navigation {
	color: #999898;
	font-size: 11px;
	font-family: tahoma;
}
.unterline {
	background: url(engine/skins/images/line_bg.gif);
	width: 100%;
	height: 9px;
	font-size: 3px;
	font-family: tahoma;
	margin-bottom: 4px;
}
.hr_line {
	background: url(engine/skins/images/line.gif);
	width: 100%;
	height: 7px;
	font-size: 3px;
	font-family: tahoma;
	margin-top: 4px;
	margin-bottom: 4px;
}
.edit {
	border:1px solid #9E9E9E;
	color: #000000;
	font-size: 11px;
	font-family: Verdana; BACKGROUND-COLOR: #ffffff 
}
.buttons {
	background: #FFF;
	border: 1px solid #9E9E9E;
	color: #666666;
	font-family: Verdana, Tahoma, helvetica, sans-serif;
	padding: 0px;
	vertical-align: absmiddle;
	font-size: 11px; 
	height: 21px;
}
select {
	color: #000000;
	font-size: 11px;
	font-family: Verdana; 
	border:1px solid #9E9E9E;
}

textarea {
	border: #9E9E9E 1px solid;
	color: #000000;
	font-size: 11px;
	font-family: Verdana; 
	background-color: #ffffff 
}
#hintbox{ /*CSS for pop up hint box */
position:absolute;
top: 0;
background-color: lightyellow;
width: 150px; /*Default width of hint.*/ 
padding: 3px;
border:1px solid #787878;
font:normal 11px Verdana;
line-height:18px;
z-index:100;
border-right: 2px solid #787878;
border-bottom: 2px solid #787878;
visibility: hidden;
}

.xprofile textarea {
width:100%; height:90px; font-family:verdana; font-size:11px; border:1px solid #9E9E9E;
}
.xprofile input {
width:250px; height:18px; font-family:verdana; font-size:11px; border:1px solid #9E9E9E;
}

.hintanchor{ 
padding-left: 8px;
}
</style>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="engine/skins/calendar-blue.css" title="win2k-cold-1" />

<!-- main calendar program -->
<script type="text/javascript" src="engine/skins/calendar.js"></script>

<!-- language for the calendar -->
<script type="text/javascript" src="engine/skins/calendar-en.js"></script>

<!-- the following script defines the Calendar.setup helper function, which makes
adding a calendar a matter of 1 or 2 lines of code. -->
<script type="text/javascript" src="engine/skins/calendar-setup.js"></script>

<script type="text/javascript" src="engine/skins/default.js"></script>
</head>
<body>
<script language="javascript" type="text/javascript">
<!--

function confirmDelete(url){
    var agree=confirm("{$lang['user_deluser']}");

    if (agree) {
	    document.location=url;
	}
}

//-->
</script>
<table align="center" width="97%">
    <tr>
        <td width="4" height="16"><img src="engine/skins/images/tb_left.gif" width="4" height="16" border="0" /></td>
		<td background="engine/skins/images/tb_top.gif"><img src="engine/skins/images/tb_top.gif" width="1" height="16" border="0" /></td>
		<td width="4"><img src="engine/skins/images/tb_right.gif" width="3" height="16" border="0" /></td>
    </tr>
	<tr>
        <td width="4" background="engine/skins/images/tb_lt.gif"><img src="engine/skins/images/tb_lt.gif" width="4" height="1" border="0" /></td>
		<td valign="top" style="padding-top:8px; padding-left:8px; padding-right:8px;" bgcolor="#FFFFFF">
HTML;
	
	$last_date = langdate( "j F Y - H:i", $row['lastdate'] );
	$reg_date = langdate( "j F Y - H:i", $row['reg_date'] );
	if( $row['time_limit'] != "" ) $row['time_limit'] = date( "Y-m-d H:i", $row['time_limit'] );
	
	if( $row['foto'] ) {
		
		$avatar = $config['http_home_url'] . "uploads/fotos/" . $row['foto'];
	
	} else {
		
		$avatar = "engine/skins/images/noavatar.png";
	
	}
	
	$xfieldsaction = "admin";
	$xfieldsid = $row['xfields'];
	include (ENGINE_DIR . '/inc/userfields.php');
	$userlink = $config['http_home_url']."user/".urlencode($row['name'])."/";
	echo <<<HTML
<form action="" method="post" enctype="multipart/form-data">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">Пользователь: <a href="{$userlink}" target="_blank"><b>{$row['name']}</b></a></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="150" style="padding:4px;">user_id:</td>
        <td>{$row['user_id']}</td>
        <td rowspan="6" valign="top" align="right"><img src="{$avatar}" border="0" /></td>
    </tr>
    <tr>
        <td style="padding:4px;">IP:</td>
        <td><a href="#" onclick="window.opener.document.location='?mod=iptools&ip={$row['logged_ip']}'; return false;">{$row['logged_ip']}</a></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_news']}</td>
        <td>{$row['news_num']}</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_last']}</td>
        <td>{$last_date}</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_reg']}</td>
        <td>{$reg_date}</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_mail']}</td>
        <td><input size="20" class="edit" name="editmail" value="{$row['email']}" /></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_newlogin']}</td>
        <td colspan="2"><input size="20" name="editlogin" class="edit"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_newpass']}</td>
        <td colspan="2"><input size="20" name="editpass" class="edit"></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_acc']}</td>
        <td colspan="2"><select name="editlevel">
HTML;
	
	echo get_groups( $row[user_group] );
	
	if( $row['banned'] == "yes" ) $ifch = "checked";
	$row['days'] = intval( $row['days'] );
	
	if( $row['banned'] == "yes" and $row['days'] ) $endban = $lang['ban_edate'] . " " . langdate( "j M Y H:i", $row['banned_date'] );
	else $endban = "";
	
	$restricted_selected = array (0 => '', 1 => '', 2 => '', 3 => '' );
	$restricted_selected[$row['restricted']] = 'selected';
	
	if( $row['restricted'] and $row['restricted_days'] ) $end_restricted = $lang['edit_tdate'] . " " . langdate( "j M Y H:i", $row['restricted_date'] );
	else $end_restricted = "";
	
	if( $row['restricted'] ) $lang['restricted_none'] = $lang['restricted_clear'];
	
	if( $row['use_demo_vip_group'] == "1" ) $ifchvip = "checked";
	
	echo <<<HTML
</select></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_gtlimit']}</td>
        <td colspan="2"><input size="17" name="time_limit" id="time_limit" class="edit" value="{$row['time_limit']}"> <img src="engine/skins/images/img.gif"  align="absmiddle" id="t_trigger_ent" style="cursor: pointer; border: 0" title="{$lang['edit_ecal']}"/><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_glhel]}', this, event, '250px')">[?]</a>
<script type="text/javascript">
    Calendar.setup({
      inputField     :    "time_limit",     // id of the input field
      ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
      button         :    "t_trigger_ent",  // trigger for the calendar (button ID)
      align          :    "Br",           // alignment 
	  timeFormat     :    "24",
	  showsTime      :    true,
      singleClick    :    true
    });
</script></td>
    </tr>
    <tr>
        <td style="padding:4px;">Использовал VIP тестовый период:</td>
        <td colspan="2"><input type="checkbox" name="vip" value="yes" $ifchvip><a href="#" class="hintanchor" onMouseover="showhint('Отметьте поле, для того чтобы использовать эту одноразовую возможность.', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_banned']}</td>
        <td colspan="2"><input type="checkbox" name="banned" value="yes" $ifch><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_banned]}', this, event, '250px')">[?]</a></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['ban_date']}</td>
        <td colspan="2"><input size="5" name="banned_date" class="edit" value="{$row['days']}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_bandescr]}', this, event, '250px')">[?]</a> {$endban}</td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['ban_descr']}</td>
        <td colspan="2"><textarea style="width:100%; height:60px;" name="banned_descr">{$row['descr']}</textarea></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['restricted']}</td>
        <td colspan="2"><select name="restricted"><option value="0" $restricted_selected[0]>{$lang['restricted_none']}</option>
<option value="1" $restricted_selected[1]>{$lang['restricted_news']}</option>
<option value="2" $restricted_selected[2]>{$lang['restricted_comm']}</option>
<option value="3" $restricted_selected[3]>{$lang['restricted_all']}</option>
</select></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['restricted_date']}</td>
        <td colspan="2"><input size="5" name="restricted_days" class="edit" value="{$row['restricted_days']}"><a href="#" class="hintanchor" onMouseover="showhint('{$lang[hint_restricted]}', this, event, '250px')">[?]</a>  {$end_restricted}</td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_del_comments']}</td>
        <td colspan="2"><input type="checkbox" name="del_comments" value="yes" /></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['opt_fullname']}</td>
        <td colspan="2"><input size="20" name="editfullname" value="{$row['fullname']}" class="edit"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['opt_icq']}</td>
        <td colspan="2"><input size="20" name="editicq" value="{$row['icq']}" class="edit"></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['opt_land']}</td>
        <td colspan="2"><input size="20" name="editland" value="{$row['land']}" class="edit"></td>
    </tr>

    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_avatar']}</td>
        <td colspan="2"><input type="file" name="image" style="width:304px;" class="edit" /></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['user_del_avatar']}</td>
        <td colspan="2"><input type="checkbox" name="del_foto" value="yes" /></td>
    </tr>
    <tr>
        <td colspan="3"><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['extra_minfo']}</td>
        <td colspan="2" style="padding-bottom:4px;"><textarea style="width:100%; height:70px;" name="editinfo">{$row['info']}</textarea></td>
    </tr>
    <tr>
        <td style="padding:4px;">{$lang['extra_signature']}</td>
        <td colspan="2"><textarea style="width:100%; height:70px;" name="editsignature">{$row['signature']}</textarea></td>
    </tr>
	{$output}
    <tr>
        <td colspan="3" style="padding-top:10px;padding-bottom:10px;"><input type="submit" value="{$lang['user_save']}" class="buttons" style="width:100px;" />&nbsp;&nbsp;&nbsp;<input type="button" value="{$lang['user_can']}" onClick="window.close();" class="buttons" style="width:100px;" />&nbsp;&nbsp;&nbsp;{$delete_link}
    <input type="hidden" name="id" value="{$id}">
    <input type="hidden" name="mod" value="editusers">
    <input type="hidden" name="user_hash" value="$dle_login_hash">
    <input type="hidden" name="action" value="doedituser">
	<input type="hidden" name="prev_restricted" value="{$row['restricted_days']}"></td>
    </tr>
</table>
</form>
HTML;
	
	echo <<<HTML
		</td>
		<td width="4" background="engine/skins/images/tb_rt.gif"><img src="engine/skins/images/tb_rt.gif" width="4" height="1" border="0" /></td>
    </tr>
	<tr>
        <td height="16" background="engine/skins/images/tb_lb.gif"></td>
		<td background="engine/skins/images/tb_tb.gif"></td>
		<td background="engine/skins/images/tb_rb.gif"></td>
    </tr>
</table>
</body>

</html>
HTML;

} 
// ********************************************************************************
// Сохранение отредактированной информации
// ********************************************************************************
elseif( $action == "doedituser" ) {

	if( ! $id ) {
		die( $lang['user_nouser'] );
	}
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}

	$row = $db->super_query( "SELECT user_id, user_group FROM " . USERPREFIX . "_users WHERE user_id = '$id'" );
	
	if( ! $row['user_id'] ) die( "User not found" );

	if ($member_id['user_group'] != 1 AND $row['user_group'] == 1 )
		die( $lang['edit_not_admin'] );

	$editlevel = intval( $_POST['editlevel'] );

	if ($member_id['user_group'] != 1 AND $editlevel < 2 )
		die( $lang['admin_not_access'] );
	
	include_once ENGINE_DIR . '/classes/parse.class.php';
	
	$parse = new ParseFilter( );
	
	$editlogin = $db->safesql( $parse->process( $_POST['editlogin'] ) );
	$editfullname = $db->safesql( $parse->process( $_POST['editfullname'] ) );

	if ($_POST['editicq']) $editicq = intval( $_POST['editicq'] ); else $editicq = "";

	$editland = $db->safesql( $parse->process( $_POST['editland'] ) );
	$editinfo = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['editinfo'] ), false ) );
	$editsignature = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['editsignature'] ), false ) );
	$editmail = $db->safesql( $parse->process( $_POST['editmail'] ) );
	$time_limit = trim( $_POST['time_limit'] ) ? strtotime( $_POST['time_limit'] ) : "";

	if ( $_POST['banned'] ) $banned = "yes";
	if ( $_POST['vip'] == 'yes') $vip = "1"; else $vip = "0";
	
	if( ! $user_group[$editlevel]['time_limit'] ) $time_limit = "";
	
	$image = $_FILES['image']['tmp_name'];
	$image_name = $_FILES['image']['name'];
	$image_size = $_FILES['image']['size'];
	$img_name_arr = explode( ".", $image_name );
	$type = end( $img_name_arr );
	
	if( $image_name != "" ) $image_name = totranslit( stripslashes( $img_name_arr[0] ) ) . "." . totranslit( $type );
	
	if( is_uploaded_file( $image ) ) {
		
		if( $image_size < 100000 ) {
			
			$allowed_extensions = array ("jpg", "png", "jpe", "jpeg", "gif" );
			
			if( (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) and $image_name ) {
				include_once ENGINE_DIR . '/classes/thumb.class.php';
				
				$res = @move_uploaded_file( $image, ROOT_DIR . "/uploads/fotos/" . $id . "." . $type );
				
				if( $res ) {
					
					@chmod( ROOT_DIR . "/uploads/fotos/" . $id . "." . $type, 0666 );
					$thumb = new thumbnail( ROOT_DIR . "/uploads/fotos/" . $id . "." . $type );
					
					if( $thumb->size_auto( $user_group[$member_id['user_group']]['max_foto'] ) ) {
						$thumb->jpeg_quality( $config['jpeg_quality'] );
						$thumb->save( ROOT_DIR . "/uploads/fotos/foto_" . $id . "." . $type );
					} else {
						@rename( ROOT_DIR . "/uploads/fotos/" . $id . "." . $type, ROOT_DIR . "/uploads/fotos/foto_" . $id . "." . $type );
					}
					
					@chmod( ROOT_DIR . "/uploads/fotos/foto_" . $id . "." . $type, 0666 );
					$foto_name = "foto_" . $id . "." . $type;
					
					$db->query( "UPDATE " . USERPREFIX . "_users set foto='$foto_name' WHERE user_id='$id'" );
				
				}
			}
		
		}
		
		@unlink( ROOT_DIR . "/uploads/fotos/" . $id . "." . $type );
	}
	
	if( $_POST['del_foto'] == "yes" ) {
		$row = $db->super_query( "SELECT foto FROM " . USERPREFIX . "_users WHERE user_id='$id'" );
		$db->query( "UPDATE " . USERPREFIX . "_users set foto='' WHERE user_id='$id'" );
		
		@unlink( ROOT_DIR . "/uploads/fotos/" . $row['foto'] );
	}
	
	$xfieldsaction = "init_admin";
	include (ENGINE_DIR . '/inc/userfields.php');
	$filecontents = array ();
	
	if( ! empty( $postedxfields ) ) {
		foreach ( $postedxfields as $xfielddataname => $xfielddatavalue ) {
			if( ! $xfielddatavalue ) {
				continue;
			}
			
			$xfielddatavalue = $db->safesql( $parse->BB_Parse( htmlspecialchars( $parse->process( $xfielddatavalue ) ), false ) );
			
			$xfielddataname = $db->safesql( $xfielddataname );
			
			$xfielddataname = str_replace( "|", "&#124;", $xfielddataname );
			$xfielddatavalue = str_replace( "|", "&#124;", $xfielddatavalue );
			$filecontents[] = "$xfielddataname|$xfielddatavalue";
		}
		
		$filecontents = implode( "||", $filecontents );
	} else
		$filecontents = '';
	
	$sql_update = "UPDATE " . USERPREFIX . "_users set user_group='$editlevel', banned='$banned', icq='$editicq', land='$editland', info='$editinfo', signature='$editsignature', email='$editmail', fullname='$editfullname', time_limit='$time_limit', xfields='$filecontents', use_demo_vip_group='$vip'";
	
	if( trim( $editlogin ) != "" ) {
		
		$row = $db->super_query( "SELECT user_id FROM " . USERPREFIX . "_users WHERE name='$editlogin'" );
		
		if( ! $row['user_id'] ) {
			
			$row = $db->super_query( "SELECT name FROM " . USERPREFIX . "_users WHERE user_id='$id'" );
			$db->query( "UPDATE " . PREFIX . "_post SET autor='$editlogin' WHERE autor='{$row['name']}'" );
			$db->query( "UPDATE " . PREFIX . "_comments SET autor='$editlogin' WHERE autor='{$row['name']}' AND is_register='1'" );
			$db->query( "UPDATE " . USERPREFIX . "_pm SET user_from='$editlogin' WHERE user_from='{$row['name']}'" );
			$db->query( "UPDATE " . PREFIX . "_vote_result SET name='$editlogin' WHERE name='{$row['name']}'" );
			$db->query( "UPDATE " . PREFIX . "_images SET author='$editlogin' WHERE author='{$row['name']}'" );
			
			$sql_update .= ", name='$editlogin'";
		} else
			msg( "error", $lang['addnews_error'], $lang['user_edit_found'], "javascript:history.go(-1)" );
	}
	
	if( $_POST['restricted'] ) {
		
		$restricted = intval( $_POST['restricted'] );
		$restricted_days = intval( $_POST['restricted_days'] );
		
		$sql_update .= ", restricted='$restricted'";
		
		if( $restricted_days != $_POST['prev_restricted'] ) {
			
			$restricted_date = time() + ($config['date_adjust'] * 60);
			$restricted_date = $restricted_days ? $restricted_date + ($restricted_days * 60 * 60 * 24) : '';
			
			$sql_update .= ", restricted_days='$restricted_days', restricted_date='$restricted_date'";
		
		}
	
	} else {
		
		$sql_update .= ", restricted='0', restricted_days='0', restricted_date=''";
	
	}
	
		if( trim( $_POST['editpass'] ) != "" ) {

			include_once (ENGINE_DIR . '/modules/vauth/passhash.php');
			$pass_hash = get_passhash(@md5($_POST['editpass']));
			$editpass = md5( md5( $_POST['editpass'] ) );
			$sql_update .= ", userpassword_hash='$pass_hash', password='$editpass'";
		}
	
	$sql_update .= " WHERE user_id='$id'";
	
	$db->query( $sql_update );
	
	if( $banned ) {
		$banned_descr = $db->safesql( $parse->BB_Parse( $parse->process( $_POST['banned_descr'] ), false ) );
		$this_time = time() + ($config['date_adjust'] * 60);
		$banned_date = intval( $_POST['banned_date'] );
		$this_time = $banned_date ? $this_time + ($banned_date * 60 * 60 * 24) : 0;
		
		$row = $db->super_query( "SELECT users_id, days FROM " . USERPREFIX . "_banned WHERE users_id = '$id'" );
		
		if( ! $row['users_id'] ) $db->query( "INSERT INTO " . USERPREFIX . "_banned (users_id, descr, date, days) values ('$id', '$banned_descr', '$this_time', '$banned_date')" );
		else {
			
			if( $row['days'] != $banned_date ) $db->query( "UPDATE " . USERPREFIX . "_banned set descr='$banned_descr', days='$banned_date', date='$this_time' WHERE users_id = '$id'" );
			else $db->query( "UPDATE " . USERPREFIX . "_banned set descr='$banned_descr' WHERE users_id = '$id'" );
		
		}
		
		@unlink( ENGINE_DIR . '/cache/system/banned.php' );
	
	} else {
		
		$db->query( "DELETE FROM " . USERPREFIX . "_banned WHERE users_id = '$id'" );
		@unlink( ENGINE_DIR . '/cache/system/banned.php' );
	
	}
	
	if( $_POST['del_comments'] ) {
		
		$result = $db->query( "SELECT COUNT(*) as count, post_id FROM " . PREFIX . "_comments WHERE user_id='$id' AND is_register='1' GROUP BY post_id" );
		
		while ( $row = $db->get_array( $result ) ) {
			
			$db->query( "UPDATE " . PREFIX . "_post SET comm_num=comm_num-{$row['count']} where id='{$row['post_id']}'" );
		
		}
		$db->free( $result );
		
		$db->query( "UPDATE " . USERPREFIX . "_users set comm_num='0' where user_id ='$id'" );
		$db->query( "DELETE FROM " . PREFIX . "_comments WHERE user_id='$id' AND is_register='1'" );
	
	}
	
	header( "Location: {$_SERVER['REQUEST_URI']}" );

} 
// ********************************************************************************
// Удаление пользователя
// ********************************************************************************
elseif( $action == "dodeleteuser" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	if( ! $id ) {
		die( $lang['user_nouser'] );
	}

	if( $id == 1 ) {
		die( $lang['user_undel'] );
	}

	
	$row = $db->super_query( "SELECT user_id, user_group, name, foto FROM " . USERPREFIX . "_users WHERE user_id='$id'" );

	if( ! $row['user_id'] ) die( "User not found" );

	if ($member_id['user_group'] != 1 AND $row['user_group'] == 1 )
		die( $lang['user_undel'] );

	
	$db->query( "DELETE FROM " . USERPREFIX . "_pm WHERE user_from = '{$row['name']}' AND folder = 'outbox'" );
	
	@unlink( ROOT_DIR . "/uploads/fotos/" . $row['foto'] );
	
	$db->query( "delete FROM " . USERPREFIX . "_users WHERE user_id='$id'" );
	$db->query( "delete FROM " . USERPREFIX . "_banned WHERE users_id='$id'" );
	$db->query( "delete FROM " . USERPREFIX . "_pm WHERE user='$id'" );
	
	if ($_GET['popup'] == "yes") {

		die( "<body><script type=\"text/javascript\">window.close();</script>".$lang[user_ok]." ".$lang[user_delok_1]."</body>" );

	} else {

		msg( "info", $lang['user_delok'], "$lang[user_ok] $user $lang[user_delok_1]", "$PHP_SELF?mod=editusers&action=list" );

	}

} elseif( $action == "dodelcomments" ) {
	if( ! $id ) {
		die( $lang['user_nouser'] );
	}
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$result = $db->query( "SELECT COUNT(*) as count, post_id FROM " . PREFIX . "_comments WHERE user_id='$id' AND is_register='1' GROUP BY post_id" );
	
	while ( $row = $db->get_array( $result ) ) {
		
		$db->query( "UPDATE " . PREFIX . "_post set comm_num=comm_num-{$row['count']} where id='{$row['post_id']}'" );
	
	}
	$db->free( $result );
	
	$db->query( "UPDATE " . USERPREFIX . "_users set comm_num='0' where user_id ='$id'" );
	$db->query( "DELETE FROM " . PREFIX . "_comments WHERE user_id='$id' AND is_register='1'" );
	
	msg( "info", $lang['user_delok'], $lang['comm_alldel'], "$PHP_SELF?mod=editusers&action=list" );
}
?>