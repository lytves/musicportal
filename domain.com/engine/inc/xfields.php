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
if (isset ($xfieldssubactionadd))
if ($xfieldssubactionadd == "add") {
  $xfieldssubaction = $xfieldssubactionadd;
}

if (!isset($xf_inited)) $xf_inited = "";

if ($xf_inited !== true) { // Prevent "Cannot redeclare" error

function xfieldssave($data) {
	global $lang, $dle_login_hash;

	if ($_REQUEST['user_hash'] == "" OR $_REQUEST['user_hash'] != $dle_login_hash) {

		  die("Hacking attempt! User not found");

	}

    $data = array_values($data);
    foreach ($data as $index => $value) {
      $value = array_values($value);
      foreach ($value as $index2 => $value2) {
        $value2 = stripslashes($value2);
        $value2 = str_replace("|", "&#124;", $value2);
        $value2 = str_replace("\r\n", "__NEWL__", $value2);
        $filecontents .= $value2 . ($index2 < count($value) - 1 ? "|" : "");
      }
      $filecontents .= ($index < count($data) - 1 ? "\r\n" : "");
    }
  
    $filehandle = fopen(ENGINE_DIR.'/data/xfields.txt', "w+");
    if (!$filehandle)
    msg("error", $lang['xfield_error'], "$lang[xfield_err_1] \"".ENGINE_DIR."/data/xfields.txt\", $lang[xfield_err_1]");
    fwrite($filehandle, $filecontents);
    fclose($filehandle);
    header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"] .
        "?mod=xfields&xfieldsaction=configure");
    exit;
  }


////////////
// Запись данных XFields в базу данных, при добавлении, редактировании новости
function xfieldsdatasavesql($id, $data) {

	global $parse, $config, $db;

    if ($config['safe_xfield']) {
	$parse->ParseFilter();
	$parse->safe_mode = true;
    }

      foreach ($data as $xfielddataname => $xfielddatavalue) {
        if ($xfielddatavalue == "") { continue;}

		$parse->allow_code = true;
		$xfielddatavalue = $db->safesql($parse->BB_Parse($parse->process($xfielddatavalue), false));

        $xfielddataname = $db->safesql($xfielddataname);

        $xfielddataname = str_replace("|", "&#124;", $xfielddataname);
        $xfielddataname = str_replace("\r\n", "__NEWL__", $xfielddataname);
        $xfielddatavalue = str_replace("|", "&#124;", $xfielddatavalue);
        $xfielddatavalue = str_replace("\r\n", "__NEWL__", $xfielddatavalue);
		$filecontents[0][] = "$xfielddataname|$xfielddatavalue";
      }

      $filecontents[0] = implode("||", $filecontents[0]);


$db->query("UPDATE " . PREFIX . "_post set xfields = '$filecontents[0]' WHERE id='$id'");
}

////////////
// Move an array item
function array_move(&$array, $index1, $dist) {
    $index2 = $index1 + $dist;
    if ($index1 < 0 or
        $index1 > count($array) - 1 or
        $index2 < 0 or
        $index2 > count($array) - 1) {
      return false;
    }
    $value1 = $array[$index1];
  
    $array[$index1] = $array[$index2];
    $array[$index2] = $value1;
  
    return true;
  }

  $xf_inited = true;
}

$xfields = xfieldsload();
switch ($xfieldsaction) {
  case "configure":

	if( ! $user_group[$member_id['user_group']]['admin_xfields'] ) {
		msg( "error", $lang['index_denied'], $lang['index_denied'] );
	}

    switch ($xfieldssubaction) {
      case "delete":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_5'],"javascript:history.go(-1)");
        }
        msg("options", "info", "$lang[xfield_err_6]<br /><br /><a href=\"$PHP_SELF?mod=xfields&amp;xfieldsaction=configure&amp;xfieldsindex=$xfieldsindex&amp;xfieldssubaction=delete2&user_hash={$dle_login_hash}\">[$lang[opt_sys_yes]]</a>&nbsp;&nbsp;<a href=\"$PHP_SELF?mod=xfields&amp;xfieldsaction=configure\">[$lang[opt_sys_no]]</a>");
        break;
      case "delete2":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_5'],"javascript:history.go(-1)");
        }
        unset($xfields[$xfieldsindex]);
        @xfieldssave($xfields);
        break;
      case "moveup":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_7'],"javascript:history.go(-1)");
        }
        array_move($xfields, $xfieldsindex, -1);
        @xfieldssave($xfields);
        break;
      case "movedown":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_7'],"javascript:history.go(-1)");
        }
        array_move($xfields, $xfieldsindex, +1);
        @xfieldssave($xfields);
        break;
      case "add":
        $xfieldsindex = count($xfields);
        // Fall trough to edit
      case "edit":
        if (!isset($xfieldsindex)) {
          msg("error", $lang['xfield_error'], $lang['xfield_err_8'],"javascript:history.go(-1)");
        }
    
        if (!$editedxfield) {
          $editedxfield = $xfields[$xfieldsindex];
        } elseif (strlen(trim($editedxfield[0])) > 0 and
            strlen(trim($editedxfield[1])) > 0) {
          foreach ($xfields as $name => $value) {
            if ($name != $xfieldsindex and
                $value[0] == $editedxfield[0]) {
              msg("error", $lang['xfield_error'], $lang['xfield_err_9'],"javascript:history.go(-1)");
            }
          }
          $editedxfield[0] = strtolower(trim($editedxfield[0]));

		  if (!count($editedxfield[2])) $editedxfield[2][0] ="";
		  elseif (count($editedxfield[2]) > 1 AND $editedxfield[2][0] == "") unset($editedxfield[2][0]);

		  $editedxfield[2] 	= implode(',', $editedxfield[2]);

          if ($editedxfield[3] == "select") {
            $options = array();
            foreach (explode("\r\n", $editedxfield["4_select"]) as $name => $value) {
              $value = trim($value);
              if (!in_array($value, $options)) {
                $options[] = $value;
              }
            }
            if (count($options) < 2) {
            msg("error", $lang['xfield_error'], $lang['xfield_err_10'],"javascript:history.go(-1)");
            }
            $editedxfield[4] = implode("\r\n", $options);
          } else {
            $editedxfield[4] = $editedxfield["4_{$editedxfield[3]}"];
          }
          unset($editedxfield["2_custom"], $editedxfield["4_text"], $editedxfield["4_textarea"], $editedxfield["4_select"]);
          if ($editedxfield[3] == "select") {
            $editedxfield[5] = 0;
          } else {
            $editedxfield[5] = ($editedxfield[5] == "on" ? 1 : 0);
          }
          ksort($editedxfield);
          
          $xfields[$xfieldsindex] = $editedxfield;
          ksort($xfields);
          @xfieldssave($xfields);
          break;
        } else {
          msg("error", $lang['xfield_error'], $lang['xfield_err_11'],"javascript:history.go(-1)");
        }
        echoheader("options", (($xfieldssubaction == "add") ? $lang['xfield_addh'] : $lang['xfield_edith']) . " " . $lang['xfield_fih']);
        $checked = ($editedxfield[5] ? " checked" : "");
?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" name="xfieldsform">
      <script language="javascript">
      function ShowOrHideEx(id, show) {
        var item = null;
        if (document.getElementById) {
          item = document.getElementById(id);
        } else if (document.all) {
          item = document.all[id];
        } else if (document.layers){
          item = document.layers[id];
        }
        if (item && item.style) {
          item.style.display = show ? "" : "none";
        }
      }
      function onTypeChange(value) {
        ShowOrHideEx("default_text", value == "text");
        ShowOrHideEx("default_textarea", value == "textarea");
        ShowOrHideEx("select_options", value == "select");
        ShowOrHideEx("optional", value != "select");
      }
      function onCategoryChange(value) {
        ShowOrHideEx("category_custom", value == "custom");
      }
      </script>
      <input type="hidden" name="mod" value="xfields">
	  <input type="hidden" name="user_hash" value="<?php echo $dle_login_hash; ?>">
      <input type="hidden" name="xfieldsaction" value="configure">
      <input type="hidden" name="xfieldssubaction" value="edit">
      <input type="hidden" name="xfieldsindex" value="<?php echo $xfieldsindex; ?>">

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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><?php echo $lang['xfield_title']; ?></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
    <tr>
        <td width="200" style="padding:4px;"><?php echo $lang['xfield_xname']; ?></td>
        <td><input class=edit style="width: 300px;" type="text" name="editedxfield[0]" value="<? echo $editedxfield[0];?>" />&nbsp;&nbsp;&nbsp;(<?php echo $lang['xf_lat']; ?>)</td>
    </tr>
    <tr>
        <td style="padding:4px;"><?php echo $lang['xfield_xdescr']; ?></td>
        <td><input  class=edit style="width: 300px;" type="text" name="editedxfield[1]" value="<? echo $editedxfield[1];?>" /></td>
    </tr>
<?php
        $cat_options = CategoryNewsSelection(explode (',', $editedxfield[2]), 0, FALSE);
		if ($editedxfield[2] == "") $cats_value = "selected"; else $cats_value = "";

echo <<<HTML
    <tr>
        <td style="padding:4px;">{$lang['xfield_xcat']}</td>
        <td><select name="editedxfield[2][]" id="category" class="cat_select" multiple><option value="" {$cats_value}>{$lang['xfield_xall']}</option>{$cat_options}</select></td>
		</tr>
HTML;

?>
    <tr>
        <td style="padding:4px;"><?php echo $lang['xfield_xtype']; ?></td>
        <td><select name="editedxfield[3]" id="type" onchange="onTypeChange(this.value)" />
          <option value="text"<?=($editedxfield[3] != "textarea") ? " selected" : ""?>><?php echo $lang['xfield_xstr']; ?></option>
          <option value="textarea"<?=($editedxfield[3] == "textarea") ? " selected" : ""?>><?php echo $lang['xfield_xarea']; ?></option>
          <option value="select"<?=($editedxfield[3] == "select") ? " selected" : ""?>><?php echo $lang['xfield_xsel']; ?></option>
        </select></td>
    </tr>
	<tr id="default_text">
        <td style="padding:4px;"><?php echo $lang['xfield_xfaul']; ?></td>
        <td><input class=edit style="width: 320px;" type="text" name="editedxfield[4_text]" value="<?=($editedxfield[3] == "text") ? $editedxfield[4] : ""?>" /></td>
    </tr>
	<tr id="default_textarea">
        <td style="padding:4px;"><?php echo $lang['xfield_xfaul']; ?></td>
        <td><textarea style="width: 320px; height: 100px;" name="editedxfield[4_textarea]"><?=($editedxfield[3] == "textarea") ? $editedxfield[4] : ""?></textarea></td>
    </tr>
	<tr id="select_options">
        <td style="padding:4px;"><?php echo $lang['xfield_xfaul']; ?></td>
        <td><textarea style="width: 320px; height: 100px;" name="editedxfield[4_select]"><?=($editedxfield[3] == "select") ? $editedxfield[4] : ""?></textarea><br>на одной строке одно значение</td>
    </tr>
    <tr>
        <td colspan=2><div class="hr_line"></div></td>
    </tr>
    <tr>
        <td colspan=2><div id="optional">
      <span><input type="checkbox" name="editedxfield[5]"<?php echo $checked; ?> id="editxfive" />
    <label for="editxfive"> <?php echo $lang['xfield_xw']; ?></label></span></div></td>
    </tr>
    <tr>
        <td colspan=2 style="padding:4px;"><input type="submit" class="buttons" value="<?php echo $lang['user_save']; ?>"></td>
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
    </form>
    <script type="text/javascript">
    <!--
      var item_type = null;
      var item_category = null;
      if (document.getElementById) {
        item_type = document.getElementById("type");
        item_category = document.getElementById("category");
      } else if (document.all) {
        item_type = document.all["type"];
        item_category = document.all["category"];
      } else if (document.layers) {
        item_type = document.layers["type"];
        item_category = document.layers["category"];
      }
      if (item_type) {
        onTypeChange(item_type.value);
        onCategoryChange(item_category.value);
      }
    // -->
    </script>
<?php
        echofooter();
        break;

      default:
        echoheader("options", "Дополнительные поля");
?>
<form action="<? echo $_SERVER["PHP_SELF"]; ?>" method="post" name="xfieldsform">
<input type="hidden" name="mod" value="xfields">
<input type="hidden" name="xfieldsaction" value="configure">
<input type="hidden" name="xfieldssubactionadd" value="">
<input type="hidden" name="user_hash" value="<? echo $dle_login_hash; ?>">
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
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation"><?php echo $lang['xfield_xlist']; ?></div></td>
    </tr>
</table>
<div class="unterline"></div>
<table width="100%">
  <tr>
    <td style="padding:5px;">
      <B><?php echo $lang['xfield_xname']; ?></B>
    </td>
    <td>
      <B><?php echo $lang['xfield_xcat']; ?></B>
    </td>
    <td>
      <B><?php echo $lang['xfield_xtype']; ?></B>
    </td>
    <td>
      <B><?php echo $lang['xfield_xwt']; ?></B>
    </td>
    <td width=10>&nbsp;
    </td>
  </tr>
    <tr>
        <td colspan=5><div class="hr_line"></div></td>
    </tr>
<?php
        if (count($xfields) == 0) {
          echo "<tr><td colspan=\"5\" align=\"center\"><br /><br />$lang[xfield_xnof]</td></tr>";
        } else {
          foreach ($xfields as $name => $value) {
?>
        <tr>
          <td style="padding:2px;">
            <? echo $value[0]; ?>
          </td>
          <td>
            <?=(trim($value[2]) ? $value[2] : $lang['xfield_xall'])?>
          </td>
          <td>
            <?=(($value[3] == "text") ? $lang['xfield_xstr'] : "")?>
            <?=(($value[3] == "textarea") ? $lang['xfield_xarea'] : "")?>
            <?=(($value[3] == "select") ? $lang['xfield_xsel'] : "")?>
          </td>
          <td>
            <?=($value[5] != 0 ? $lang['opt_sys_yes'] : $lang['opt_sys_no'])?>
          </td>
          <td>
            <input type="radio" name="xfieldsindex" value="<?php echo $name; ?>">
          </td>
        </tr><tr><td background="engine/skins/images/mline.gif" height=1 colspan=5></td></tr>
<?php
          }
        }
?>
    <tr>
        <td colspan=5><div class="hr_line"></div></td>
    </tr>
      <tr>
		<td ><a class=main onClick="javascript:Help('xfields')" href="#"><?php echo $lang['xfield_xhelp']; ?></a></td>
        <td colspan="4" class="main" style="text-align: right; padding-top: 10px;">
          <?php if (count($xfields) > 0) { ?>
          <?php echo $lang['xfield_xact']; ?>: 
          <select name="xfieldssubaction">
            <option value="edit"><?php echo $lang['xfield_xedit']; ?></option>
            <option value="delete"><?php echo $lang['xfield_xdel']; ?></option>
            <option value="moveup"><?php echo $lang['xfield_xo']; ?></option>
            <option value="movedown"><?php echo $lang['xfield_xu']; ?></option>
          </select>
          <input type="submit" class="buttons" value="<?php echo $lang['b_start']; ?>" onclick="document.forms['xfieldsform'].xfieldssubactionadd.value = '';">
          <?php } ?>
          <input type="submit" class="buttons" value="<?php echo $lang['b_create']; ?>" onclick="document.forms['xfieldsform'].xfieldssubactionadd.value = 'add';">
        </td>
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
  </form>

<?php
      echofooter();
    }
    break;
case "list":
    $output = "";
    if (!isset($xfieldsid)) $xfieldsid = "";
    $xfieldsdata = xfieldsdataload ($xfieldsid);
    foreach ($xfields as $name => $value) {
      $fieldname = $value[0];
      if (!$xfieldsadd) {
        $fieldvalue = $xfieldsdata[$value[0]];

		if ($row['allow_br'])
        	$fieldvalue = $parse->decodeBBCodes($fieldvalue, false);
		else
        	$fieldvalue = $parse->decodeBBCodes($fieldvalue, true, "yes");

      } elseif ($value[3] != "select") {
        $fieldvalue = $value[4];
      }

      $holderid = "xfield_holder_$fieldname";

      if ($value[3] == "textarea") {      
      $output .= <<<HTML
<tr id="$holderid">
<td class=addnews>$value[1]:<br />[if-optional]({$lang['xf_not_notig']})[/if-optional]</td>
<td class=xfields colspan="2"><textarea name="xfield[$fieldname]" id="xf_$fieldname">$fieldvalue</textarea></td></tr>
HTML;
      } elseif ($value[3] == "text") {
        $output .= <<<HTML
<tr id="$holderid">
<td class=addnews>$value[1]:</td>
<td class=xfields colspan="2"><input type="text" name="xfield[$fieldname]" id="xfield[$fieldname]" value="$fieldvalue" />&nbsp;&nbsp;[if-optional]<font style="font-size:7pt">({$lang['xf_not_notig']})</font>[/if-optional]
</tr>
HTML;
      } elseif ($value[3] == "select") {
        $output .= <<<HTML

<tr id="$holderid">
<td class=addnews>$value[1]:</td>
<td class=xfields colspan="2"><select name="xfield[$fieldname]">
HTML;
        foreach (explode("\r\n", $value[4]) as $index => $value) {
		  $value = str_replace("'", "&#039;", $value);
          $output .= "<option value=\"$index\"" . ($fieldvalue == $value ? " selected" : "") . ">$value</option>\r\n";
        }

$output .= <<<HTML
</select></td>
</tr>
HTML;
      }
      $output = preg_replace("'\\[if-optional\\](.*?)\\[/if-optional\\]'s", $value[5] ? "\\1" : "", $output);
      $output = preg_replace("'\\[not-optional\\](.*?)\\[/not-optional\\]'s", $value[5] ? "" : "\\1", $output);
      $output = preg_replace("'\\[if-add\\](.*?)\\[/if-add\\]'s", ($xfieldsadd) ? "\\1" : "", $output);
      $output = preg_replace("'\\[if-edit\\](.*?)\\[/if-edit\\]'s", (!$xfieldsadd) ? "\\1" : "", $output);
    }
    $output .= <<<HTML

<script type="text/javascript">
<!--
  var item = null;
  if (document.getElementById) {
    item = document.getElementById("category");
  } else if (document.all) {
    item = document.all["category"];
  } else if (document.layers) {
    item = document.layers["category"];
  }
  if (item) {
    onCategoryChange(item.value);
  }
// -->
</script>
HTML;
    break;
  case "init":
    $postedxfields = $_POST['xfield'];
    $newpostedxfields = array();

foreach ($category as $cats_explode) {
    foreach ($xfields as $name => $value) {
      if ($value[2] != "" and !in_array($cats_explode, explode(",", $value[2]))) {
        continue;
      }
      if ($value[5] == 0 and $postedxfields[$value[0]] == "") {
		if ($add_module == "yes")
        $stop .= $lang[xfield_xerr1];
		else
        msg("error", "error", "$lang[xfield_xerr1]<br /><a href=\"javascript:history.go(-1)\">$lang[func_msg]</a>");

      }
      if ($value[3] == "select") {
        $options = explode("\r\n", $value[4]);
        $postedxfields[$value[0]] = $options[$_POST['xfield'][$value[0]]];
      }
      $newpostedxfields[$value[0]] = $postedxfields[$value[0]];
    }
}
    $postedxfields = $newpostedxfields;
    break;
  case "save": // Make sure it is first initialized
    if (!empty($postedxfields)) {
    @xfieldsdatasavesql($xfieldsid, $postedxfields);
    }
    break;
  case "delete":
    break;
  case "templatereplace":
    $xfieldsdata = xfieldsdataload ($xfieldsid);
    $xfieldsoutput = $xfieldsinput;
  
    foreach ($xfields as $value) {
      $preg_safe_name = preg_quote($value[0], "'");

      if ($value[5] != 0) {
        if (empty($xfieldsdata[$value[0]])) {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\].*?\\[/xfgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput);
        } else {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $xfieldsoutput);
        }
      }
      $xfieldsoutput = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", stripslashes($xfieldsdata[$value[0]]), $xfieldsoutput);
    }
    break;
  case "templatereplacepreview":
	if (isset ($_POST["xfield"])) $xfield = $_POST["xfield"];
    $xfieldsoutput = $xfieldsinput;

    foreach ($xfields as $value) {
      $preg_safe_name = preg_quote($value[0], "'");

      if ($value[3] == "select") {
        $options = explode("\r\n", $value[4]);
        $xfield[$value[0]] = $options[$xfield[$value[0]]];
      }

      if ($config['safe_xfield']) {
		$parse->ParseFilter();
		$parse->safe_mode = true;
      }

	  $parse->allow_code = true;
      $xfield[$value[0]] = stripslashes($parse->BB_Parse($parse->process($xfield[$value[0]]), false));

      if ($value[5] != 0) {
        if (empty($xfield[$value[0]])) {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\].*?\\[/xfgiven_{$preg_safe_name}\\]'is", "", $xfieldsoutput);
        } else {
          $xfieldsoutput = preg_replace("'\\[xfgiven_{$preg_safe_name}\\](.*?)\\[/xfgiven_{$preg_safe_name}\\]'is", "\\1", $xfieldsoutput);
        }
      }
      $xfieldsoutput = preg_replace("'\\[xfvalue_{$preg_safe_name}\\]'i", $xfield[$value[0]], $xfieldsoutput);
    }
    break;
  case "categoryfilter":
    $categoryfilter = <<<HTML
  <script type="text/javascript">
  function ShowOrHideEx(id, show) {
    var item = null;

    if (document.getElementById) {
      item = document.getElementById(id);
    } else if (document.all) {
      item = document.all[id];
    } else if (document.layers){
      item = document.layers[id];
    }
    if (item && item.style) {
      item.style.display = show ? "" : "none";
    }
  }
  function xfInsertText(text, element_id) {
    var item = null;
    if (document.getElementById) {
      item = document.getElementById(element_id);
    } else if (document.all) {
      item = document.all[element_id];
    } else if (document.layers){
      item = document.layers[element_id];
    }
    if (item) {
      item.focus();
      item.value = item.value + " " + text;
      item.focus();
    }
  }
  function onCategoryChange(value) {

HTML;
    foreach ($xfields as $value) {
      $categories = str_replace(",", "||value==", $value[2]);
      if ($categories) {
        $categoryfilter .= "ShowOrHideEx(\"xfield_holder_{$value[0]}\", value == $categories);\r\n";
      }
    }
    $categoryfilter .= "  }\r\n</script>";
    break;
  default:
  if (function_exists('msg'))
    msg("error", $lang['xfield_error'], $lang['xfield_xerr2']);
}
?>