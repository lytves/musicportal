<?PHP
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

if (isset ($_REQUEST['news_id']) AND $user_group[$member_db[1]]['allow_edit'] == 1 AND $user_group[$member_db[1]]['allow_all_edit'] == 0){

 $n_id = $_REQUEST['news_id'];
 $sec = $db->super_query("SELECT autor,id FROM ".PREFIX."_post WHERE id = '$n_id'");

 if ($sec['autor'] !== $member_id['name']){

 msg( "error", "Доступ запрещен", "Вы не можете выполнять операции с чужими файлами. (1) " );

 }
 }
 if(isset ($_REQUEST['author']) AND $_REQUEST['author'] !== $member_id['name'] AND $user_group[$member_db[1]]['allow_all_edit'] == 0 AND $user_group[$member_db[1]]['allow_edit'] == 0) {

 msg( "error", "Доступ запрещен", "Вы не можете выполнять операции с файлами (2)" );

 }
 
if( ! $user_group[$member_id['user_group']]['allow_image_upload'] or ($member_id['user_group'] != 1 and $action != "quick") ) {
	msg( "error", "Доступ запрещен", "Вы не можете выполнять операции с файлами" );
}

$allowed_extensions = array ("gif", "jpg", "png", "jpe", "jpeg" );
$allowed_video = array ("avi", "mp4", "wmv", "mpg", "flv", "mp3", "swf", "m4v", "m4a", "mov", "3gp", "f4v" );
$allowed_files = explode( ',', str_replace(array("php","phtml", "htaccess", "cgi", "pl", "fcgi", "fpl", "phtml", "shtml", "php2", "php3", "php4", "php5", "asp"), md5(time() - rand(30,60)), strtolower(  $config['files_type'] )));
$img_result_th = "";
$img_result = "";

$all_ext = "*." . implode( ";*.", $allowed_extensions );

if( $config['files_allow'] == "yes" and $user_group[$member_id['user_group']]['allow_file_upload'] ) $all_ext .= ";*." . implode( ";*.", $allowed_files );

if (@ini_get( 'safe_mode' ) == 1)
	define( 'FOLDER_PREFIX', "" );
else
	define( 'FOLDER_PREFIX', date( "Y-m" ) );

if( $_REQUEST['userdir'] ) $userdir = totranslit( $_REQUEST['userdir'] ) . DIRECTORY_SEPARATOR; else $userdir = "";
if( $_REQUEST['sub_dir'] ) $sub_dir = totranslit( $_REQUEST['sub_dir'] ) . DIRECTORY_SEPARATOR; else $sub_dir = "";

if( isset( $_REQUEST['area'] ) ) $area = totranslit( $_REQUEST['area'] ); else $area = "";
if( isset( $_REQUEST['wysiwyg'] ) ) $wysiwyg = totranslit( $_REQUEST['wysiwyg'] ); else $wysiwyg = 0;
if( isset( $_REQUEST['author'] ) ) $author = @$db->safesql( strip_tags( urldecode( $_REQUEST['author'] ) ) ); else $author = "";
if( intval( $_REQUEST['news_id'] ) ) $news_id = intval( $_REQUEST['news_id'] ); else $news_id = 0;

$config_path_image_upload = ROOT_DIR . "/uploads/" . $userdir . $sub_dir;


if( $member_id['user_group'] < 4 ) {
	
	$config['max_image'] = $_POST['t_size'] ? $_POST['t_size'] : $config['max_image'];

} else {
	
	$_POST['t_seite'] = 0;

}

$thumb_size = $config['max_image'];
$thumb_size = explode ("x", $thumb_size);

if ( count($thumb_size) == 2) {

	$thumb_size = intval($thumb_size[0]) . "x" . intval($thumb_size[1]);

} else {

	$thumb_size = intval( $thumb_size[0] );

}

$config['max_image'] = $thumb_size;

if( ! @is_dir( $config_path_image_upload ) ) msg( "error", $lang['addnews_denied'], "Directory {$userdir} not found" );

if( $action == "doimagedelete" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	if( ! isset( $_POST['images'] ) ) {
		msg( "info", $lang['images_delerr'], $lang['images_delerr_1'], "$PHP_SELF?mod=files" );
	}
	
	foreach ( $_POST['images'] as $image ) {
		@unlink( $config_path_image_upload . $image );
		@unlink( $config_path_image_upload . "thumbs/" . $image );
	}
	$action = "";
}

// ********************************************************************************
// Вывод списка загруженных файлов
// ********************************************************************************


if( $action == "quick" ) {
	
	header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
	header( "Cache-Control: no-store, no-cache, must-revalidate" );
	header( "Cache-Control: post-check=0, pre-check=0", false );
	header( "Pragma: no-cache" );
	
	$sess_id = session_id();
	
	if( $user_group[$member_id['user_group']]['allow_file_upload'] ) {
		
		if( ! $config['max_file_size'] ) $max_file_size = 0;
		elseif( $config['max_file_size'] > $config['max_up_size'] ) $max_file_size = ( int ) $config['max_file_size'];
		else $max_file_size = ( int ) $config['max_up_size'];
		
		if( $max_file_size ) $max_file_size = $max_file_size . " KB";
	
	} else {
		
		$max_file_size = $config['max_up_size'] . " KB";
	
	}
	
	$config['max_file_count'] = intval( $config['max_file_count'] );
	
	echo <<<HTML
<html><head>
<base target="_self">
<BASE href="{$config['http_home_url']}">
<meta content="text/html; charset={$config['charset']}" http-equiv="content-type" />
<title>Upload</title>
<style type="text/css">
html,body{
height:100%;
margin:0px;
padding: 0px;
background: #F4F3EE;
}

form {
margin:0px;
padding: 0px;
}
input,
select,
textarea {
	outline:none;
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
	background: url({$config['http_home_url']}engine/skins/images/line_bg.gif);
	width: 100%;
	height: 9px;
	font-size: 3px;
	font-family: tahoma;
	margin-bottom: 4px;
}
.hr_line {
	background: url({$config['http_home_url']}engine/skins/images/line.gif);
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

.upload input {
	border:1px solid #9E9E9E;
	color: #000000;
	font-size: 11px;
	margin-top: 5px;
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
	background-color: #ffffff;
	border: 1px solid #9E9E9E; 
}
	.dle_tabPane{
		height:26px;	/* Height of tabs */
	}
	.dle_aTab{
		border:1px solid #CDCDCD;
		padding:5px;		
		
	}
	.dle_tabPane DIV{
		float:left;
		padding-left:3px;
		vertical-align:middle;
		background-repeat:no-repeat;
		background-position:bottom left;
		cursor:pointer;
		position:relative;
		bottom:-1px;
		margin-left:0px;
		margin-right:0px;
	}
	.dle_tabPane .tabActive{
		background-image:url('{$config['http_home_url']}engine/skins/images/tl_active.gif');
		margin-left:0px;
		margin-right:0px;	
	}
	.dle_tabPane .tabInactive{
		background-image:url('{$config['http_home_url']}engine/skins/images/tl_inactive.gif');
		margin-left:0px;
		margin-right:0px;
	}

	.dle_tabPane .inactiveTabOver{
		margin-left:0px;
		margin-right:0px;
	}
	.dle_tabPane span{
		font-family:tahoma;
		vertical-align:top;
		font-size:11px;
		line-height:26px;
		float:left;
	}
	.dle_tabPane .tabActive span{
		padding-bottom:0px;
		line-height:26px;
	}
	
	.dle_tabPane img{
		float:left;
	}

fieldset { 
	position: relative; 
	border:1px solid #CDCDCD;
	margin: 0;
	padding: 20px 10px;
	margin-bottom: 1em;
}

legend { 
	position:absolute; 
	top: -1px; 
	left: .5em; 
}

fieldset.flash {
	width: 90%;
	margin: 0;
	border-color: #CDCDCD;
}
.progressWrapper {
	width: 99%;
	overflow: hidden;
}

.progressContainer {
	margin: 5px;
	padding: 4px;
	border: solid 1px #E8E8E8;
	background-color: #F7F7F7;
	overflow: hidden;
}
/* Message */
.message {
	margin: 1em 0;
	padding: 10px 20px;
	border: solid 1px #FFDD99;
	background-color: #FFFFCC;
	overflow: hidden;
}
/* Error */
.red {
	border: solid 1px #B50000;
	background-color: #FFEBEB;
}

/* Current */
.green {
	border: solid 1px #DDF0DD;
	background-color: #EBFFEB;
}

/* Complete */
.blue {
	border: solid 1px #CEE2F2;
	background-color: #F0F5FF;
}

.progressName {
	font-size: 8pt;
	font-weight: 700;
	color: #555;
	width: 323px;
	height: 14px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
}

.progressBarInProgress,
.progressBarComplete,
.progressBarError {
	font-size: 0;
	width: 0%;
	height: 2px;
	background-color: blue;
	margin-top: 2px;
}

.progressBarComplete {
	width: 100%;
	background-color: green;
	visibility: hidden;
}

.progressBarError {
	width: 100%;
	background-color: red;
	visibility: hidden;
}

.progressBarStatus {
	margin-top: 2px;
	width: 99%;
	font-size: 7pt;
	font-family: Arial;
	text-align: left;
	white-space: nowrap;
}

a.progressCancel {
	font-size: 0;
	display: block;
	height: 14px;
	width: 14px;
	background-image: url({$config['http_home_url']}engine/classes/swfupload/cancelbutton.gif);
	background-repeat: no-repeat;
	background-position: -14px 0px;
	float: right;
}

a.progressCancel:hover {
	background-position: 0px 0px;
}


</style>
<script type="text/javascript" src="{$config['http_home_url']}engine/classes/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$config['http_home_url']}engine/classes/swfupload/swfupload.queue.js"></script>
<script type="text/javascript" src="{$config['http_home_url']}engine/classes/swfupload/fileprogress.js"></script>
<script type="text/javascript" src="{$config['http_home_url']}engine/classes/swfupload/handlers.js"></script>
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "{$config['http_home_url']}engine/classes/swfupload/swfupload.swf",
				upload_url: "{$config['http_home_url']}engine/ajax/upload.php",	// Relative to the SWF file
				post_params: {"PHPSESSID" : "{$sess_id}", "news_id" : "{$news_id}", "area" : "{$area}", "author" : "{$author}"},
				file_size_limit : "{$max_file_size}",
				file_types : "{$all_ext}",
				file_types_description : "All Files",
				file_upload_limit : {$config['max_file_count']},
				file_queue_limit : {$config['max_file_count']},
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,
				flash_container_id : "flash_container",
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
	</script>
        </head>
<body>
<script language="javascript" type="text/javascript" src="{$config['http_home_url']}engine/skins/default.js"></script>
<script type="text/javascript" src="{$config['http_home_url']}engine/skins/tabs.js"></script>
<table align="center" width="97%">
    <tr>
        <td width="4" height="16"><img src="{$config['http_home_url']}engine/skins/images/tb_left.gif" width="4" height="16" border="0" /></td>
		<td background="{$config['http_home_url']}engine/skins/images/tb_top.gif"><img src="{$config['http_home_url']}engine/skins/images/tb_top.gif" width="1" height="16" border="0" /></td>
		<td width="4"><img src="{$config['http_home_url']}engine/skins/images/tb_right.gif" width="3" height="16" border="0" /></td>
    </tr>
	<tr>
        <td width="4" background="{$config['http_home_url']}engine/skins/images/tb_lt.gif"><img src="{$config['http_home_url']}engine/skins/images/tb_lt.gif" width="4" height="1" border="0" /></td>
		<td valign="top" style="padding:8px;" bgcolor="#FFFFFF">
HTML;
	
	echo <<<JSCRIPT
<script language='javascript' type="text/javascript">
<!--

var allow_focus = true;

function ckeck_uncheck_all() {
    var frm = document.delimages;
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

function insert_all() {

    var frm = document.delimages;
    var wysiwyg = '{$wysiwyg}';

	allow_focus = false;

	if (wysiwyg == 'yes') {

		wysiwyg = '<br />';

	} else {

		wysiwyg = '\\n';

	}

    for (var i=0;i<frm.elements.length;i++) {
   
     var elmnt = frm.elements[i];
 
       if (elmnt.type=='checkbox') {

            if(elmnt.checked == true){ 

				if (elmnt.id == 'fullimage') {

					insertimage('{$config['http_home_url']}uploads/posts/' + elmnt.value);
					insertfile(wysiwyg);

				}

				if (elmnt.id == 'thumbimage') {

					insertthumb('{$config['http_home_url']}uploads/posts/' + elmnt.value);
					insertfile(wysiwyg);

				}

				if (elmnt.id == 'file') {

					insertfile('[attachment='+ elmnt.value + ']');
					insertfile(wysiwyg);

				}

				if (elmnt.id == 'fullstatic') {

					insertimage('{$config['http_home_url']}uploads/posts/' + elmnt.alt);
					insertfile(wysiwyg);

				}

				if (elmnt.id == 'thumbstatic') {

					insertthumb('{$config['http_home_url']}uploads/posts/' + elmnt.alt);
					insertfile(wysiwyg);

				}

			}
        }
    }

	window.focus();

}
-->
</script>
JSCRIPT;
	
	echo "<script language=\"javascript\" type=\"text/javascript\">
        <!--
        function insertimage(selectedImage) {

           imageAlign = document.forms['properties'].imageAlign.value;";
	
	if( ! $wysiwyg ) {
		echo "if (imageAlign == 'center') finalImage = \"[center][img]\"+ selectedImage +\"[/img][/center]\";
		else finalImage = \"[img=\"+ imageAlign +\"]\"+ selectedImage +\"[/img]\";
	     ";
	} else {
		echo "if (imageAlign == 'center') finalImage = \"<div style=\\\"text-align: center;\\\"><img src=\\\"\"+ selectedImage +\"\\\" border=0></div><div></div>\";
		else finalImage = \"<img align=\\\"\"+ imageAlign +\"\\\" src=\\\"\"+ selectedImage +\"\\\" border=0>\";";
	}
	
	if( ! $wysiwyg ) {
		echo "window.opener.doInsert(finalImage, '', false); if(allow_focus == true) { window.focus(); } ";
	} else {
		echo " window.opener.tinyMCE.execCommand('mceInsertContent',false,finalImage); if(allow_focus == true) { window.focus(); }";
	}
	
	echo "

                        }

				function ShowBild(sPicURL) {
				window.open('{$config['http_home_url']}engine/modules/imagepreview.php?image='+sPicURL, '', 'resizable=1,HEIGHT=200,WIDTH=200, scrollbars=yes');
                        }

        function insertthumb(selectedImage) {

           imageAlign = document.forms['properties'].imageAlign.value;";
	
	echo "if (imageAlign == 'center') finalImage = \"[center][thumb]\"+ selectedImage +\"[/thumb][/center]\";
		else finalImage = \"[thumb=\"+ imageAlign +\"]\"+ selectedImage +\"[/thumb]\";
	    ";
	
	if( ! $wysiwyg ) {
		echo "window.opener.doInsert(finalImage, '', false); if(allow_focus == true) { window.focus(); } ";
	} else {
		echo "	window.opener.tinyMCE.execCommand('mceInsertContent',false,finalImage); if(allow_focus == true) { window.focus(); }";
	}
	
	echo "

                        }

        function insertfile(selectedFile) {";
	
	if( ! $wysiwyg ) {
		echo "window.opener.doInsert(selectedFile, '', false); if(allow_focus == true) { window.focus(); } ";
	} else {
		echo "	window.opener.tinyMCE.execCommand('mceInsertContent',false,selectedFile); if(allow_focus == true) { window.focus(); }";
	}
	
	echo "

                        }

        //-->
        </script>";
} 

else {
	echoheader( "files", $lang['images_head'] );
	
	echo <<<HTML
<div style="padding-top:5px;padding-bottom:2px;">
<table width="100%">
    <tr>
        <td width="4"><img src="{$config['http_home_url']}engine/skins/images/tl_lo.gif" width="4" height="4" border="0"></td>
        <td background="{$config['http_home_url']}engine/skins/images/tl_oo.gif"><img src="{$config['http_home_url']}engine/skins/images/tl_oo.gif" width="1" height="4" border="0"></td>
        <td width="6"><img src="{$config['http_home_url']}engine/skins/images/tl_ro.gif" width="6" height="4" border="0"></td>
    </tr>
    <tr>
        <td background="{$config['http_home_url']}engine/skins/images/tl_lb.gif"><img src="{$config['http_home_url']}engine/skins/images/tl_lb.gif" width="4" height="1" border="0"></td>
        <td style="padding:5px;" bgcolor="#FFFFFF">
HTML;
}

if( $_REQUEST['subaction'] == "deluploads" ) {
	
	if( $_REQUEST['user_hash'] == "" or $_REQUEST['user_hash'] != $dle_login_hash ) {
		
		die( "Hacking attempt! User not found" );
	
	}
	
	$row = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where author = '$author' AND news_id = '$news_id'" );
	
	$listimages = explode( "|||", $row['images'] );
	
	if( isset( $_POST['images'] ) ) foreach ( $_POST['images'] as $image ) {
		
		$i = 0;
		
		sort( $listimages );
		reset( $listimages );
		
		foreach ( $listimages as $dataimages ) {
			
			if( $dataimages == $image ) {
				
				$url_image = explode( "/", $image );
				
				if( count( $url_image ) == 2 ) {
					
					$folder_prefix = $url_image[0] . "/";
					$image = $url_image[1];
				
				} else {
					
					$folder_prefix = "";
					$image = $url_image[0];
				
				}
				
				unset( $listimages[$i] );
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $image );
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $image );
			
			}
			
			$i ++;
		}
	}
	
	if( count( $listimages ) ) $row['images'] = implode( "|||", $listimages );
	else $row['images'] = "";
	
	$db->query( "UPDATE " . PREFIX . "_images set images='$row[images]' where author = '$author' AND news_id = '$news_id'" );
	
	if( count( $_POST['static_files'] ) ) {
		
		foreach ( $_POST['static_files'] as $file ) {
			
			$file = intval( $file );
			
			$row = $db->super_query( "SELECT id, name, onserver FROM " . PREFIX . "_static_files WHERE author = '$author' AND static_id = '$news_id' AND id='$file'" );
			
			if( $row['id'] and $row['onserver'] ) {
				
				@unlink( ROOT_DIR . "/uploads/files/" . $row['onserver'] );
				$db->query( "DELETE FROM " . PREFIX . "_static_files WHERE id='{$row['id']}'" );
			
			} else {
				
				if( $row['id'] ) {
					$url_image = explode( "/", $row['name'] );
					
					if( count( $url_image ) == 2 ) {
						
						$folder_prefix = $url_image[0] . "/";
						$image = $url_image[1];
					
					} else {
						
						$folder_prefix = "";
						$image = $url_image[0];
					
					}
					
					@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $image );
					@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $image );
					$db->query( "DELETE FROM " . PREFIX . "_static_files WHERE id='{$row['id']}'" );
				
				}
			
			}
		}
	}
	
	if( count( $_POST['files'] ) ) {
		
		foreach ( $_POST['files'] as $file ) {
			
			$file = intval( $file );
			
			$row = $db->super_query( "SELECT id, onserver FROM " . PREFIX . "_files where author = '$author' AND news_id = '$news_id' AND id='$file'" );
			
			@unlink( ROOT_DIR . "/uploads/files/" . $row['onserver'] );
			$db->query( "DELETE FROM " . PREFIX . "_files WHERE id='{$row['id']}'" );
		
		}
	
	}

}

if( $_REQUEST['subaction'] == "upload" ) {
	
	if( $action == "quick" ) {
		
		$userdir = "posts/";
		
		if( ! is_dir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX ) ) {
			
			@mkdir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX, 0777 );
			@chmod( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX, 0777 );
			@mkdir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/thumbs", 0777 );
			@chmod( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/thumbs", 0777 );
		}
		
		if( ! is_dir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX ) ) {
			
			msg( "error", $lang['opt_error'], $lang['upload_error_0']." /uploads/posts/" . FOLDER_PREFIX . "/" );
		}

		if( ! is_writable( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX ) ) {
			
			msg( "error", $lang['opt_error'], $lang['upload_error_1']." /uploads/posts/" . FOLDER_PREFIX . "/ ".$lang['upload_error_2'] );
		}

		if( ! is_writable( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/thumbs" ) ) {
			
			msg( "error", $lang['opt_error'], $lang['upload_error_1']." /uploads/posts/" . FOLDER_PREFIX . "/thumbs/ ".$lang['upload_error_2'] );
		}
		
		$config_path_image_upload = ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/";
	
	}
	
	for($image_i = 1; $image_i < ($images_number + 1); $image_i ++) {
		
		$file_prefix = time() + rand( 1, 100 );
		$file_prefix .= "_";
		
		$imageurl = trim( htmlspecialchars( strip_tags( $_POST['imageurl'] ) ) );
		
		if ($member_id['user_group'] == 1) $serverfile = trim( htmlspecialchars( strip_tags( $_POST['serverfile'] ) ) ); else $serverfile = '';
 
        if ( $serverfile != '' ) {
 
            $serverfile = str_replace( "\\", "/", $serverfile );
            $serverfile = str_replace( "..", "", $serverfile );
            $serverfile = str_replace( "/", "", $serverfile );
            $serverfile_arr = explode( ".", $serverfile );
            $type = totranslit( end( $serverfile_arr ) );
            $curr_key = key( $serverfile_arr );
            unset( $serverfile_arr[$curr_key] );
 
            if ( in_array( strtolower( $type ), $allowed_files ) ) 
                $serverfile = totranslit( implode( ".", $serverfile_arr ) ) . "." . $type; 
            else $serverfile = '';
 
        }
 
        if( $serverfile == ".htaccess") die("Hacking attempt!");
		
		if( $serverfile != '' and ! @file_exists( ROOT_DIR . "/uploads/files/" . $serverfile ) ) $serverfile = '';
		
		if( $imageurl != "" ) {
			
			$urlcopy = "yes";
			$imageurl = str_replace( "\\", "/", $imageurl );
			$image_name = explode( "/", $imageurl );
			$image_name = end( $image_name );
			
			$img_name_arr = explode( ".", $image_name );
			$image_size = @filesize_url( $imageurl );
			$type = totranslit( end( $img_name_arr ) );
			
			if( $image_name != "" ) {
				
				$curr_key = key( $img_name_arr );
				unset( $img_name_arr[$curr_key] );
				$image_name = totranslit( implode( ".", $img_name_arr ) ) . "." . $type;
			
			}
		
		} else {
			
			$urlcopy = "";
			$current_image = 'file_' . $image_i;
			$image = $_FILES[$current_image]['tmp_name'];
			$image_name = $_FILES[$current_image]['name'];
			$image_size = $_FILES[$current_image]['size'];
			$error_code = $_FILES[$current_image]['error'];

			if ($error_code !== UPLOAD_ERR_OK) {

			    switch ($error_code) { 
			        case UPLOAD_ERR_INI_SIZE: 
			            $error_code = 'PHP Error: The uploaded file exceeds the upload_max_filesize directive in php.ini'; break;
			        case UPLOAD_ERR_FORM_SIZE: 
			            $error_code = 'PHP Error: The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; break;
			        case UPLOAD_ERR_PARTIAL: 
			            $error_code = 'PHP Error: The uploaded file was only partially uploaded'; break;
			        case UPLOAD_ERR_NO_FILE: 
			            $error_code = 'PHP Error: No file was uploaded'; break;
			        case UPLOAD_ERR_NO_TMP_DIR: 
			            $error_code = 'PHP Error: Missing a PHP temporary folder'; break;
			        case UPLOAD_ERR_CANT_WRITE: 
			            $error_code = 'PHP Error: Failed to write file to disk'; break;
			        case UPLOAD_ERR_EXTENSION: 
			            $error_code = 'PHP Error: File upload stopped by extension'; break;
			        default: 
			            $error_code = 'Unknown upload error';  break;
			    } 


			}

			
			$img_name_arr = explode( ".", $image_name );
			$type = totranslit( end( $img_name_arr ) );
			
			if( $image_name != "" ) {
				
				$curr_key = key( $img_name_arr );
				unset( $img_name_arr[$curr_key] );
				
				$image_name = totranslit( implode( ".", $img_name_arr ) ) . "." . $type;
			}
		}
		
		if( $config['files_allow'] == "yes" and $user_group[$member_id['user_group']]['allow_file_upload'] and $_REQUEST['action'] == "quick" and (in_array( strtolower( $type ), $allowed_files ) or $serverfile != '') ) {
/*
=====================================================
 Загрузка файлов, но не картинок
=====================================================
*/
			if( $serverfile == '' ) {
				
				if( $urlcopy != "yes" ) @move_uploaded_file( $image, ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name ) or $img_result = "<div><font color=red>{$lang['images_uperr_3']}<br /><br />{$error_code}</font></div>";
				else @copy( $imageurl, ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name ) or $img_result = "<div><font color=red>$lang[images_uperr_3]</font></div>";
			
			} else {
				
				$file_prefix = '';
				$image_name = $serverfile;
			}
			
			if( @file_exists( ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name ) ) {
				
				if( intval( $config['max_file_size'] ) and @filesize( ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name ) > ($config['max_file_size'] * 1024) ) {
					
					@unlink( ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name );
					$img_result .= "<div><font color=red>$image_name -> $lang[files_too_big]</font></div>";
				
				} else {
					
					@chmod( ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name, 0666 );
					$img_result .= "<div><font color=green>$image_name -> $lang[files_upok]</font></div>";
					
					$added_time = time() + ($config['date_adjust'] * 60);
					
					if( $area == "template" ) {
						
						$db->query( "INSERT INTO " . PREFIX . "_static_files (static_id, author, date, name, onserver) values ('$news_id', '$author', '$added_time', '$image_name', '{$file_prefix}{$image_name}')" );
					
					} else {
						
						$db->query( "INSERT INTO " . PREFIX . "_files (news_id, name, onserver, author, date) values ('$news_id', '$image_name', '{$file_prefix}{$image_name}', '$author', '$added_time')" );
					
					}
				
				}
			
			}
		
		} elseif( $image_name == "" ) {
			
			$img_result .= "<div><font color=red>$current_image -> $lang[images_uperr]</font></div>";
		
		} elseif( ! isset( $overwrite ) and file_exists( $config_path_image_upload . $image_name ) ) {
			
			$img_result .= "<div><font color=red>$current_image -> $lang[images_uperr_1]</font></div>";
		
		} elseif( ! (in_array( $type, $allowed_extensions ) or in_array( strtolower( $type ), $allowed_extensions )) ) {
			
			$img_result .= "<div><font color=red>$current_image -> $lang[images_uperr_2]</font></div>";
		
		} elseif( $image_size > ($config['max_up_size'] * 1024) and ! $config['max_up_side'] ) {
			
			$img_result .= "<div><font color=red>$current_image -> $lang[images_big]</font></div>";
		
		} else {
/*
=====================================================
 Загрузка картинок, но не файлов
=====================================================
*/
			if( $urlcopy != "yes" ) @move_uploaded_file( $image, $config_path_image_upload . $file_prefix . $image_name ) or $img_result = "<div><font color=red>{$lang['images_uperr_3']}<br /><br />{$error_code}</font></div>";
			else @copy( $imageurl, $config_path_image_upload . $file_prefix . $image_name ) or $img_result = "<div><font color=red>$lang[images_uperr_3]</font></div>";
			
			if( @file_exists( $config_path_image_upload . $file_prefix . $image_name ) ) {
				
				@chmod( $config_path_image_upload . $file_prefix . $image_name, 0666 );
				
				$img_result .= "<div><font color=green>$image_name -> $lang[images_upok]</font></div>";
				
				if( $action == "quick" and $area != "template" ) {
					
					$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_images where author = '$author' AND news_id = '$news_id'" );
					
					if( ! $row['count'] ) {
						
						$added_time = time() + ($config['date_adjust'] * 60);
						$inserts = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
						$db->query( "INSERT INTO " . PREFIX . "_images (images, author, news_id, date) values ('$inserts', '$author', '$news_id', '$added_time')" );
					
					} else {
						
						$row = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where author = '$author' AND news_id = '$news_id'" );
						
						if( $row['images'] == "" ) $listimages = array ();
						else $listimages = explode( "|||", $row['images'] );
						
						foreach ( $listimages as $dataimages ) {
							
							if( $dataimages == FOLDER_PREFIX . "/" . $file_prefix . $image_name ) $error_image = "stop";
						
						}
						
						if( $error_image != "stop" ) {
							
							$listimages[] = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
							$row['images'] = implode( "|||", $listimages );
							
							$db->query( "UPDATE " . PREFIX . "_images set images='{$row['images']}' where author = '$author' AND news_id = '$news_id'" );
						
						}
					}
				}
				
				if( $area == "template" and $action == "quick" ) {
					
					$added_time = time() + ($config['date_adjust'] * 60);
					$inserts = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
					$db->query( "INSERT INTO " . PREFIX . "_static_files (static_id, author, date, name) values ('$news_id', '$author', '$added_time', '$inserts')" );
				
				}
				
				include_once ENGINE_DIR . '/classes/thumb.class.php';
				if( $member_id['user_group'] > 3 ) {
					$_POST['make_thumb'] = true;
					$_POST['make_watermark'] = $config['allow_watermark'];
				}
				
				if( isset( $_POST['make_thumb'] ) ) {
					
					$thumb = new thumbnail( $config_path_image_upload . $file_prefix . $image_name );
					
					if( $thumb->size_auto( $config['max_image'], $_POST['t_seite'] ) ) {
						
						$thumb->jpeg_quality( $config['jpeg_quality'] );
						
						if( $config['allow_watermark'] == "yes" and $_POST['make_watermark'] == "yes" ) $thumb->insert_watermark( $config['max_watermark'] );
						
						$thumb->save( $config_path_image_upload . "thumbs/" . $file_prefix . $image_name );
					}
					
					if( @file_exists( $config_path_image_upload . "thumbs/" . $file_prefix . $image_name ) ) $img_result_th .= "<div><font color=blue>$image_name -> $lang[images_thok]</font></div>";
					
					@chmod( $config_path_image_upload . "thumbs/" . $file_prefix . $image_name, 0666 );
				}
				
			
				if( ($config['allow_watermark'] == "yes" and $_POST['make_watermark'] == "yes") or $config['max_up_side'] ) {
					$thumb = new thumbnail( $config_path_image_upload . $file_prefix . $image_name );
					$thumb->jpeg_quality( $config['jpeg_quality'] );
					
					if( $config['max_up_side'] ) $thumb->size_auto( $config['max_up_side'] );
					
					if( $config['allow_watermark'] == "yes" and $_POST['make_watermark'] == "yes" ) $thumb->insert_watermark( $config['max_watermark'] );
					
					$thumb->save( $config_path_image_upload . $file_prefix . $image_name );
				}
			
			} //if file is uploaded succesfully
			

			if( $urlcopy == "yes" or $serverfile != '' ) break;
		
		}
	}
}

echo "<script language=\"javascript\" type=\"text/javascript\">
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
          item.style.display = show ? \"\" : \"none\";
        }
	}

	var total_allow_rows = {$config['max_file_count']};

	function AddImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;

	 if (total_allow_rows &&  lastRow == total_allow_rows ) return;

     // if there's no header row in the table, then iteration = lastRow + 1
     var iteration = lastRow+1;
     var row = tbl.insertRow(lastRow);

     var cellRight = row.insertCell(0);
     var el = document.createElement('input');
     el.setAttribute('type', 'file');
     el.setAttribute('name', 'file_' + iteration);
     el.setAttribute('size', '41');
     el.setAttribute('value', iteration);
     cellRight.appendChild(el);

     document.getElementById('images_number').value = iteration;
	}

	function RemoveImages() {
     var tbl = document.getElementById('tblSample');
     var lastRow = tbl.rows.length;
     if (lastRow > 1){
              tbl.deleteRow(lastRow - 1);
               document.getElementById('images_number').value =  document.getElementById('images_number').value - 1;
     }
	}
    </script>";
echo <<<HTML
<form action='{$_SERVER['REQUEST_URI']}' method='post' enctype="multipart/form-data" name="form" id="form">
<input type="hidden" name="subaction" value="upload">
<input type="hidden" name="area" value='{$area}'>
<input type="hidden" name="action" value='{$action}'>
<input type="hidden" name="images_number" id="images_number" value="1">
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['images_uptitle']}</div></td>
    </tr>
</table>
<div class="unterline"></div>
HTML;

if( $action == "quick" ) {
	
	echo <<<HTML
<div id="dle_tabView1">

<div class="dle_aTab" style="display:none;">
HTML;

}

echo <<<HTML
<table id="tblSample" class="upload">
 <tr id="row">
  <td>{$lang['images_uphard']}<br /><input type="file" size="41" name="file_1"></td>
</tr>
</table>
<div class="hr_line"></div>
<div>{$lang['images_upurl']}&nbsp;<input class="edit" type="text" name="imageurl" size=42></div>
<div class="hr_line"></div>
HTML;

if( $member_id['user_group'] == 1 ) {
	
	echo <<<HTML
<div><b>uploads/files/</b>&nbsp;&nbsp;&nbsp;<input class="edit" type="text" name="serverfile" size=42></div>
<div class="hr_line"></div>
HTML;

}

echo <<<HTML
<div>
<input type=button class=buttons value=' - ' style="width:30px;" title='{$lang['images_rem_tl']}' onClick="RemoveImages();return false;">
<input type=button class=buttons value=' + ' style="width:30px;" title='{$lang['images_add_tl']}' onClick="AddImages();return false;"> &nbsp;
&nbsp;&nbsp;<input type="submit" class="buttons" value="  {$lang['db_load_a']}  ">
</div>
HTML;
if( $action == "quick" ) {
	
	if( $user_group[$member_id['user_group']]['allow_file_upload'] ) {
		
		if( $config['max_file_size'] ) {
			
			$lang['files_max_info'] = $lang['files_max_info'] . " " . formatsize( $config['max_file_size'] * 1024 );
		
		} else {
			
			$lang['files_max_info'] = $lang['files_max_info_2'];
		
		}
		
		$lang['files_max_info_1'] = $lang['files_max_info'] . "<br />" . $lang['files_max_info_1'] . " " . formatsize( $config['max_up_size'] * 1024 );
	
	} else {
		
		$lang['files_max_info_1'] = $lang['files_max_info_1'] . " " . formatsize( $config['max_up_size'] * 1024 );
	
	}
	
	echo <<<HTML
</div>

<div class="dle_aTab" style="display:none;">
<br />
<fieldset class="flash" id="fsUploadProgress">
			<legend>{$lang['upload_queue']}</legend>
			</fieldset>
		<div id="divStatus">{$lang['upload_mass_info']}<br /><br />{$lang['files_max_info_1']}</div>
<br />
<div style="position: relative">
					<input id="btnBrowse" type="button" value="{$lang['upload_waehlen']}" style="width:130px;" class="edit" />&nbsp;&nbsp;<input id="btnCancel" type="button" value="  {$lang['upload_cancel']}  " onclick="swfu.cancelQueue();" disabled="disabled" class="edit" />
				  <div id="flash_container" style="width:130px; height: 20px;position:absolute;top:0;left:0px;">Flash Container</div>
				</div>
</div>
<script type="text/javascript">
initTabs('dle_tabView1',Array('{$lang['upload_standart']}', '{$lang['upload_mass']}'),0, '100%');
</script>
HTML;

}

if( $member_id['user_group'] < 4 ) {
	
	$_POST['t_seite'] = intval( $_POST['t_seite'] );
	$t_seite_selected[$_POST['t_seite']] = "selected";
	
	echo <<<HTML
<div class="hr_line"></div>
<div>{$lang['upload_t_size']}&nbsp;<input class="edit" type="text" name="t_size" id="t_size" size=9 value="{$config['max_image']}">&nbsp;px&nbsp;<select name="t_seite" id="t_seite"><option value="0" {$t_seite_selected[0]}>{$lang['upload_t_seite_1']}</option><option value="1" {$t_seite_selected[1]}>{$lang['upload_t_seite_2']}</option><option value="2" {$t_seite_selected[2]}>{$lang['upload_t_seite_3']}</option></select></div>
<div class="hr_line"></div>
HTML;

}

if( $action != "quick" ) echo "<input type=checkbox name=overwrite value=1 id=ex> <label for=ex>$lang[images_aren]</label><br />";

if( $member_id['user_group'] < 4 ) {
	
	if( ! extension_loaded( "gd" ) ) echo "<font color=\"red\"><b>$lang[images_nogd]</b></font>";
	else echo "<input type=\"checkbox\" name=\"make_thumb\" value=\"make_thumb\" id=\"make_thumb\" checked> <label for=make_thumb>$lang[images_ath]</label></b>";
	if( $config['allow_watermark'] == "yes" ) echo "<br /><input type=\"checkbox\" name=\"make_watermark\" value=\"yes\" id=\"make_watermark\" checked> <label for=make_watermark>$lang[images_water]</label></b><div class=\"hr_line\"></div>";

}

echo <<<HTML
<div style="padding:4px;">{$img_result}{$img_result_th}</div>
</form>
HTML;

if( $action == "quick" ) {
	
	$image_align = array ();
	$image_align[$config['image_align']] = "selected";
	
	echo <<<HTML
<form name="properties">
<div style="padding:4px;">
{$lang['images_align']}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name='imageAlign'>
          <option value="none" {$image_align[0]}>{$lang['opt_sys_no']}</option>
          <option value="left" {$image_align['left']}>{$lang['images_left']}</option>
          <option value="right" {$image_align['right']}>{$lang['images_right']}</option>
          <option value="center" {$image_align['center']}>{$lang['images_center']}</option>
        </select>
</div>
</form>
HTML;
}

if( $action == "quick" ) {
	
	echo <<<HTML
<form action='{$_SERVER['REQUEST_URI']}' method='post' name="delimages" id="delimages">
<input type="hidden" name="subaction" value="deluploads">
<input type="hidden" name="user_hash" value="$dle_login_hash" />
<input type="hidden" name="area" value='{$area}'>
<input type="hidden" name="action" value='{$action}'>
<table width="100%">
    <tr>
        <td bgcolor="#EFEFEF" height="29" style="padding-left:10px;"><div class="navigation">{$lang['images_iln']}</div></td>
    	<td bgcolor="#EFEFEF" width="10" align="right"><input type="checkbox" name="master_box" title="{$lang['edit_selall']}" onclick="javascript:ckeck_uncheck_all()">

    </tr>
</table>
<div class="unterline"></div><table width="100%">
HTML;
	
	$config_path_image_upload = ROOT_DIR . "/uploads/";
	
	if( $area != "template" ) {
		
		$row = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where author = '$author' AND news_id = '$news_id'" );
		
		$listimages = explode( "|||", $row['images'] );
		$i = 0;
		$this_size_2 = 0;
		$total_size = 0;
		
		if( $row['images'] != "" ) foreach ( $listimages as $dataimages ) {
			$i ++;
			
			$url_image = explode( "/", $dataimages );
			
			if( count( $url_image ) == 2 ) {
				
				$folder_prefix = $url_image[0] . "/";
				$dataimages = $url_image[1];
			
			} else {
				
				$folder_prefix = "";
				$dataimages = $url_image[0];
			
			}
			
			if( file_exists( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages ) ) {
				
				$this_size = @filesize( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages );
				$img_info = @getimagesize( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages );
				$total_size += $this_size + $this_size_2;
				
				if( file_exists( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages ) ) {
					
					$this_size_2 = @filesize( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages );
					$img_info_th = @getimagesize( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages );
					
					$thumb_link = "<a class=maintitle href=\"javascript:insertthumb('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "','')\">$dataimages</a>";
					
					$original_link = "[ <a class=maintitle href=\"javascript:insertimage('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">{$lang['img_orig_ins']}</a> ] ";
					$link_id = "thumbimage";
				
				} else {
					
					$thumb_link = "<a class=maintitle href=\"javascript:insertimage('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">$dataimages</a>";
					
					$original_link = "";
					$link_id = "fullimage";
				
				}
				
				echo "<tr>
			  <td style=\"padding:2px;\">&nbsp;$thumb_link</td>
			  <td width=150 nowrap>{$original_link}[ <a class=maintitle href=\"javascript:ShowBild('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">" . $lang['images_view'] . "</a> ]</td>
			  <td align=\"right\" width=\"60\">$img_info[0]x$img_info[1]</td>
			  <td align=\"right\" width=\"10\"><input type=\"checkbox\" id=\"" . $link_id . "\" name=\"images[" . $folder_prefix . $dataimages . "]\" value=\"" . $folder_prefix . $dataimages . "\"></td>
			  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
			}
		}
		
		$db->query( "SELECT id, name, onserver  FROM " . PREFIX . "_files where author = '$author' AND news_id = '$news_id'" );
		
		while ( $row = $db->get_row() ) {
			
			$this_size = formatsize( @filesize( ROOT_DIR . "/uploads/files/" . $row['onserver'] ) );
			$file_link = "<a class=maintitle href=\"javascript:insertfile('[attachment={$row['id']}]')\">{$row['name']}</a>";
			$file_type = explode( ".", $row['name'] );
			$file_type = totranslit( end( $file_type ) );
			
			if( in_array( $file_type, $allowed_video ) ) {
				
				if( $file_type == "mp3" ) {
					
					$video_link = "<a class=maintitle href=\"javascript:insertfile('[audio={$config['http_home_url']}uploads/files/{$row['onserver']}]')\">{$lang['inset_audio_link']}</a>";
				
				} elseif ($file_type == "swf") {

					$video_link = "<a class=maintitle href=\"javascript:insertfile('[flash=425,264]{$config['http_home_url']}uploads/files/{$row['onserver']}[/flash]')\">{$lang['inset_flash_link']}</a>";

				} else {
					
					$video_link = "<a class=maintitle href=\"javascript:insertfile('[video={$config['http_home_url']}uploads/files/{$row['onserver']}]')\">{$lang['inset_video_link']}</a>";
				}
			
			} else {
				$video_link = "";
			}
			
			echo "<tr>
	  <td style=\"padding:2px;\" width=\"100%\">&nbsp;$file_link</td>
	  <td width=\"150\" nowrap>$video_link&nbsp;</td>
	  <td align=\"right\" width=\"60\" nowrap>{$this_size}</td>
	  <td align=\"right\" width=\"10\"><input type=checkbox id=file name=files[] value=\"{$row['id']}\"></td>
	  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
		
		}
		
		$db->free();
	}
	
	if( $area == "template" ) {
		
		$db->query( "SELECT id, name FROM " . PREFIX . "_static_files WHERE static_id = '$news_id' AND onserver = ''" );
		
		while ( $row = $db->get_row() ) {
			
			$url_image = explode( "/", $row['name'] );
			
			if( count( $url_image ) == 2 ) {
				
				$folder_prefix = $url_image[0] . "/";
				$dataimages = $url_image[1];
			
			} else {
				
				$folder_prefix = "";
				$dataimages = $url_image[0];
			
			}
			
			if( file_exists( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages ) ) {
				
				$this_size = @filesize( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages );
				$img_info = @getimagesize( $config_path_image_upload . "posts/" . $folder_prefix . $dataimages );
				$total_size += $this_size + $this_size_2;
				
				if( file_exists( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages ) ) {
					
					$this_size_2 = @filesize( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages );
					$img_info_th = @getimagesize( $config_path_image_upload . "posts/" . $folder_prefix . "thumbs/" . $dataimages );
					
					$thumb_link = "<a class=maintitle href=\"javascript:insertthumb('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "','')\">$dataimages</a>";
					$original_link = "[ <a class=maintitle href=\"javascript:insertimage('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">{$lang['img_orig_ins']}</a> ] ";
					$link_id = "thumbstatic";
				
				} else {
					
					$thumb_link = "<a class=maintitle href=\"javascript:insertimage('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">$dataimages</a>";
					$link_id = "fullstatic";
					$original_link = "";
				
				}
				
				echo "<tr>
			  <td style=\"padding:2px;\">&nbsp;$thumb_link</td>
			  <td width=150>{$original_link}[ <a class=maintitle href=\"javascript:ShowBild('" . $config['http_home_url'] . "uploads/posts/" . $folder_prefix . $dataimages . "')\">" . $lang['images_view'] . "</a> ]</td>
			  <td align=right width=60>$img_info[0]x$img_info[1]</td>
			  <td align=right width=10><input type=checkbox id=\"" . $link_id . "\" name=static_files[] alt=\"" . $folder_prefix . $dataimages . "\" value=\"" . $row['id'] . "\"></td>
			  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
			}
		}
		
		$db->query( "SELECT id, name, onserver  FROM " . PREFIX . "_static_files where author = '$author' AND static_id = '$news_id' AND onserver != ''" );
		
		while ( $row = $db->get_row() ) {
			
			$this_size = formatsize( @filesize( ROOT_DIR . "/uploads/files/" . $row['onserver'] ) );
			$file_link = "<a class=maintitle href=\"javascript:insertfile('[attachment={$row['id']}]')\">{$row['name']}</a>";
			$file_type = explode( ".", $row['name'] );
			$file_type = totranslit( end( $file_type ) );
			
			if( in_array( $file_type, $allowed_video ) ) {
				
				if( $file_type == "mp3" ) {
					
					$video_link = "<a class=maintitle href=\"javascript:insertfile('[audio={$config['http_home_url']}uploads/files/{$row['onserver']}]')\">{$lang['inset_audio_link']}</a>";
				
				} else {
					
					$video_link = "<a class=maintitle href=\"javascript:insertfile('[video={$config['http_home_url']}uploads/files/{$row['onserver']}]')\">{$lang['inset_video_link']}</a>";
				}
			
			} else {
				$video_link = "";
			}
			
			echo "<tr>
	  <td style=\"padding:2px;\" width=\"100%\">&nbsp;$file_link</td>
	  <td width=\"150\" nowrap>$video_link&nbsp;</td>
	  <td align=\"right\" width=\"60\" nowrap>{$this_size}</td>
	  <td align=\"right\" width=\"10\"><input type=checkbox id=file name=static_files[] value=\"{$row['id']}\"></td>
	  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
		
		}
		
		$db->free();
	}
	
	echo "<tr>
    <td colspan=4><div class=\"hr_line\"></div></td>
    </tr>
	<tr>
    <td colspan=4 align=\"right\"><input class=\"edit\" type=\"button\" onClick=\"insert_all();\" value=' $lang[images_all_insert] '> <input class=edit type=submit value=' $lang[images_del] '>
    </tr></table></form><br>";
}

echo "<table width=\"100%\">
    <tr>
        <td bgcolor=\"#EFEFEF\" height=\"29\" style=\"padding-left:10px;\"><div class=\"navigation\">{$lang['images_lgem']}</div></td>
    </tr>
</table>
<div class=\"unterline\"></div>
<table width=100%>
    <form action='{$_SERVER['REQUEST_URI']}' METHOD='POST'>";

$img_dir = opendir( $config_path_image_upload );
$i = 0;
$total_size = 0;
$this_size_2 = 0;

while ( $file = readdir( $img_dir ) ) {
	$images_in_dir[] = $file;
}

natcasesort( $images_in_dir );
reset( $images_in_dir );

foreach ( $images_in_dir as $file ) {
	
	$img_type = explode( ".", $file );
	$img_type = end( $img_type );
	
	if( (in_array( $img_type, $allowed_extensions ) or in_array( strtolower( $img_type ), $allowed_extensions )) and $file != ".." and $file != "." and is_file( $config_path_image_upload . $file ) ) {
		
		$i ++;
		$this_size = @filesize( $config_path_image_upload . $file );
		$img_info = @getimagesize( $config_path_image_upload . $file );
		$total_size += $this_size + $this_size_2;
		
		if( file_exists( $config_path_image_upload . "thumbs/" . $file ) ) {
			
			$this_size_2 = @filesize( $config_path_image_upload . "thumbs/" . $file );
			$img_info_th = @getimagesize( $config_path_image_upload . "thumbs/" . $file );
			$preview = "<a class=maintitle target=_blank href=\"" . $config['http_home_url'] . "uploads/" . $userdir . $sub_dir . "thumbs/$file\" title=\"$img_info_th[0]x$img_info_th[1]\">" . $lang['images_thn'] . "</a>";
			
			$thumb_link = "<a class=maintitle href=\"javascript:insertthumb('" . $config['http_home_url'] . "uploads/$userdir" . $file . "','')\">$file</a>";
		
		} else {
			
			$preview = "";
			$thumb_link = "<a class=maintitle href=\"javascript:insertimage('" . $config['http_home_url'] . "uploads/$userdir" . $file . "')\">$file</a>";
		}
		
		if( $action == "quick" ) {
			
			echo "<tr>
	  <td style=\"padding:2px;\">&nbsp;$thumb_link</td>
	  <td align=center width=60><a class=maintitle href=\"javascript:ShowBild('" . $config['http_home_url'] . "uploads/$file')\">" . $lang['images_view'] . "</a></td>
	  <td align=right width=60>$img_info[0]x$img_info[1]</td>
	  <td align=right width=60><nobr>" . formatsize( $this_size ) . "</nobr></td>
	  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=4></td></tr>";
		
		} else {
			
			echo "
	  <tr>
	  <td style=\"padding:2px;\">&nbsp;<a class=maintitle target=_blank href=\"" . $config['http_home_url'] . "uploads/" . $userdir . $sub_dir . "$file\">$file</a></td>
	  <td align=center width=180>$preview&nbsp;</td>
	  <td align=right width=60>$img_info[0]x$img_info[1]</td>
	  <td align=right width=60><nobr>" . formatsize( $this_size ) . "</nobr></td>
	  <td align=center width=10>
          <input type=checkbox name=images[$file] value=\"$file\" style=\"border: 0; background: transparent;\">
	  </tr><tr><td background=\"{$config['http_home_url']}engine/skins/images/mline.gif\" height=1 colspan=6></td></tr>";
		}
	}
}

if( $i > 0 ) {
	
	echo "<tr><td height=16>";
	
	if( $action != "quick" and $member_id['user_group'] == 1 ) {
		
		echo "<td colspan=5 align=right><br><input class=edit type=submit value=' $lang[images_del] '></tr>";
	
	}
	
	echo "<tr height=1><td colspan=6><b>$lang[images_size]</b> " . formatsize( $total_size ) . '<tr>';

}

if( $member_id['user_group'] == 1 and $action != "quick" ) {
	
	echo "<input type=hidden name=action value=doimagedelete><input type=hidden name=userdir value=$userdir><input type=\"hidden\" name=\"user_hash\" value=\"$dle_login_hash\" />
<tr height=1>
<td colspan=6><b>$lang[images_listdir]</b> 
<form action='{$_SERVER['REQUEST_URI']}' method='post'>
<select onchange=\"window.open(this.options[this.selectedIndex].value,'_top')\"><option value=$PHP_SELF?mod=files>--</option>";
	
	$current_dir = opendir( ROOT_DIR . "/uploads" );
	
	while ( $entryname = readdir( $current_dir ) ) {
		
		if( is_dir( ROOT_DIR . "/uploads/$entryname" ) and ($entryname != "." and $entryname != ".." and $entryname != "files") ) {
			
			if( $userdir == $entryname . "/" ) $sel_dir = "selected";
			else $sel_dir = "";
			
			if( $entryname == "fotos" ) $listname = $lang['images_foto'];
			elseif( $entryname == "thumbs" ) $listname = $lang['images_thumb'];
			elseif( $entryname == "posts" ) $listname = $lang['images_news'];
			else $listname = $entryname;
			
			echo "<option value=\"$PHP_SELF?mod=files&userdir=" . str_replace( ' ', '%20', ${entryname} ) . "\" $sel_dir>$listname";
			echo "</option>";
		}
	}
	$current_dir = opendir( ROOT_DIR . "/uploads/posts" );
	
	while ( $entryname = readdir( $current_dir ) ) {
		
		if( is_dir( ROOT_DIR . "/uploads/posts/$entryname" ) and ($entryname != "." and $entryname != ".." and $entryname != "thumbs") ) {
			
			if( $sub_dir == $entryname . "/" ) $sel_dir = "selected";
			else $sel_dir = "";
			
			echo "<option value=\"$PHP_SELF?mod=files&userdir=posts&sub_dir=" . str_replace( ' ', '%20', $entryname ) . "\" $sel_dir>{$lang['images_news']} / $entryname";
			echo "</option>";
		}
	}
	echo "</select></form></tr>";

}

echo '</table></form>';

if( $action != "quick" ) {
	
	echo <<<HTML
</td>
        <td background="{$config['http_home_url']}engine/skins/images/tl_rb.gif"><img src="{$config['http_home_url']}engine/skins/images/tl_rb.gif" width="6" height="1" border="0"></td>
    </tr>
    <tr>
        <td><img src="{$config['http_home_url']}engine/skins/images/tl_lu.gif" width="4" height="6" border="0"></td>
        <td background="{$config['http_home_url']}engine/skins/images/tl_ub.gif"><img src="{$config['http_home_url']}engine/skins/images/tl_ub.gif" width="1" height="6" border="0"></td>
        <td><img src="{$config['http_home_url']}engine/skins/images/tl_ru.gif" width="6" height="6" border="0"></td>
    </tr>
</table>
</div>
HTML;
	
	echofooter();

} else {
	echo <<<HTML
		</td>
		<td width="4" background="{$config['http_home_url']}engine/skins/images/tb_rt.gif"><img src="{$config['http_home_url']}engine/skins/images/tb_rt.gif" width="4" height="1" border="0" /></td>
    </tr>
	<tr>
        <td height="16" background="{$config['http_home_url']}engine/skins/images/tb_lb.gif"></td>
		<td background="{$config['http_home_url']}engine/skins/images/tb_tb.gif"></td>
		<td background="{$config['http_home_url']}engine/skins/images/tb_rb.gif"></td>
    </tr>
</table>
</body>

</html>
HTML;
}
?>