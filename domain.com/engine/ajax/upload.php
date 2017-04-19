<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

@error_reporting( E_ALL ^ E_NOTICE );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );
@ini_set( 'error_reporting', E_ALL ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

if( isset( $_POST["PHPSESSID"] ) ) {
	session_id( $_POST["PHPSESSID"] );
}

session_start();

function msg_error($message, $code = 500) {


	if ($code == 401) header( "HTTP/1.1 401 Unauthorized" );
	elseif($code == 403) header( "HTTP/1.1 403 Forbidden" );
	elseif($code == 405) header( "HTTP/1.1 405 Method Not Allowed" );
	else header( "HTTP/1.1 500 Internal Server Error" );

	echo $message;
	exit( 0 );
}

if( ! isset( $_FILES['Filedata'] ) || ! is_uploaded_file( $_FILES['Filedata']['tmp_name'] ) || $_FILES['Filedata']['error'] != 0 ) {
	msg_error( "There was a problem with the upload", 405 );
}

require_once ENGINE_DIR . '/data/config.php';
require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/inc/include/functions.inc.php';

//################# Определение групп пользователей
$user_group = get_vars( "usergroup" );

if( ! $user_group ) {
	$user_group = array ();
	
	$db->query( "SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC" );
	
	while ( $row = $db->get_row() ) {
		
		$user_group[$row['id']] = array ();
		
		foreach ( $row as $key => $value ) {
			$user_group[$row['id']][$key] = $value;
		}
	
	}
	set_vars( "usergroup", $user_group );
	$db->free();
}

require_once ENGINE_DIR . '/modules/sitelogin.php';

if( ! $is_logged ) {
	msg_error( "Not Logged", 401 );
}

if( ! $user_group[$member_id['user_group']]['allow_image_upload'] ) {
	msg_error( "Not Allowed", 401 );
}

$allowed_extensions = array ("gif", "jpg", "png", "jpe", "jpeg" );
$allowed_files = explode( ',', strtolower( $config['files_type'] ) );
if( intval( $_REQUEST['news_id'] ) ) $news_id = intval( $_REQUEST['news_id'] );
else $news_id = 0;
if( isset( $_REQUEST['area'] ) ) $area = totranslit( $_REQUEST['area'] );
else $area = "";

if( isset( $_REQUEST['author'] ) ) $author = @$db->safesql( strip_tags( convert_unicode($_REQUEST['author'], $config['charset'] ) ) ); else $author = "";

if ( !$author ) $author = $member_id['name'];

if( $member_id['user_group'] < 4 ) {
	
	$config['max_image'] = $_POST['t_size'] ? $_POST['t_size'] : $config['max_image'];
	$_POST['t_seite'] = intval( $_POST['t_seite'] );
	$config['allow_watermark'] = intval( $_POST['make_watermark'] ) ? "yes" : "no";

} else {
	
	$_POST['t_seite'] = 0;
	$_POST['make_thumb'] = true;

}

$thumb_size = $config['max_image'];
$thumb_size = explode ("x", $thumb_size);

if ( count($thumb_size) == 2) {

	$thumb_size = intval($thumb_size[0]) . "x" . intval($thumb_size[1]);

} else {

	$thumb_size = intval( $thumb_size[0] );

}

$config['max_image'] = $thumb_size;


if (@ini_get( 'safe_mode' ) == 1)
	define( 'FOLDER_PREFIX', "" );
else
	define( 'FOLDER_PREFIX', date( "Y-m" ) );

if( ! is_dir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX ) ) {
	
	@mkdir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX, 0777 );
	@chmod( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX, 0777 );
	@mkdir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/thumbs", 0777 );
	@chmod( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/thumbs", 0777 );
}

if( ! is_dir( ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX ) ) {
	
	msg_error( "/uploads/posts/" . FOLDER_PREFIX . "/ cannot created.", 403 );
}

$upload_path = ROOT_DIR . "/uploads/posts/" . FOLDER_PREFIX . "/";

$file_prefix = time() + rand( 1, 100 );
$file_prefix .= "_";

$image = $_FILES['Filedata']['tmp_name'];
$image_name = $_FILES['Filedata']['name'];
$image_size = $_FILES['Filedata']['size'];

$img_name_arr = explode( ".", $image_name );
$type = totranslit( end( $img_name_arr ) );

$curr_key = key( $img_name_arr );
unset( $img_name_arr[$curr_key] );

$image_name = totranslit( implode( ".", $img_name_arr ) ) . "." . $type;

if( $config['files_allow'] == "yes" and $user_group[$member_id['user_group']]['allow_file_upload'] and in_array( strtolower( $type ), $allowed_files ) ) {
	
	if( intval( $config['max_file_size'] ) and $image_size > ($config['max_file_size'] * 1024) ) {
		
		msg_error( "File too big", 403 );
	
	}
	
	if( move_uploaded_file( $image, ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name ) ) {
		
		@chmod( ROOT_DIR . "/uploads/files/" . $file_prefix . $image_name, 0666 );
		$added_time = time() + ($config['date_adjust'] * 60);
		
		if( $area == "template" ) {
			
			$db->query( "INSERT INTO " . PREFIX . "_static_files (static_id, author, date, name, onserver) values ('$news_id', '{$author}', '$added_time', '$image_name', '{$file_prefix}{$image_name}')" );
		
		} else {
			
			$db->query( "INSERT INTO " . PREFIX . "_files (news_id, name, onserver, author, date) values ('$news_id', '$image_name', '{$file_prefix}{$image_name}', '{$author}', '$added_time')" );
		
		}
		
		$db->close();
		echo ("Ok");
	
	} else {
		
		msg_error( "Upload Error", 403 );
	
	}

} elseif( in_array( strtolower( $type ), $allowed_extensions ) and $user_group[$member_id['user_group']]['allow_image_upload'] ) {
	
	if( file_exists( $upload_path . $image_name ) ) {
		
		msg_error( "Image exist", 500 );
	
	} elseif( $image_size > ($config['max_up_size'] * 1024) and ! $config['max_up_side'] ) {
		
		msg_error( "Image too big", 500 );
	
	}
	
	if( @move_uploaded_file( $image, $upload_path . $file_prefix . $image_name ) ) {
		
		@chmod( $upload_path . $file_prefix . $image_name, 0666 );
		
		if( $area != "template" ) {
			
			$row = $db->super_query( "SELECT COUNT(*) as count FROM " . PREFIX . "_images where author = '{$author}' AND news_id = '$news_id'" );
			
			if( ! $row['count'] ) {
				
				$added_time = time() + ($config['date_adjust'] * 60);
				$inserts = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
				$db->query( "INSERT INTO " . PREFIX . "_images (images, author, news_id, date) values ('$inserts', '{$author}', '$news_id', '$added_time')" );
			
			} else {
				
				$row = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where author = '{$author}' AND news_id = '$news_id'" );
				
				if( $row['images'] == "" ) $listimages = array ();
				else $listimages = explode( "|||", $row['images'] );
				
				foreach ( $listimages as $dataimages ) {
					
					if( $dataimages == FOLDER_PREFIX . "/" . $file_prefix . $image_name ) $error_image = "stop";
				
				}
				
				if( $error_image != "stop" ) {
					
					$listimages[] = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
					$row['images'] = implode( "|||", $listimages );
					
					$db->query( "UPDATE " . PREFIX . "_images set images='{$row['images']}' where author = '{$author}' AND news_id = '$news_id'" );
				
				}
			}
		}
		
		if( $area == "template" ) {
			
			$added_time = time() + ($config['date_adjust'] * 60);
			$inserts = FOLDER_PREFIX . "/" . $file_prefix . $image_name;
			$db->query( "INSERT INTO " . PREFIX . "_static_files (static_id, author, date, name) values ('$news_id', '{$author}', '$added_time', '$inserts')" );
		
		}
		
		include_once ENGINE_DIR . '/classes/thumb.class.php';
		
		$thumb = new thumbnail( $upload_path . $file_prefix . $image_name );
		
		if( $_POST['make_thumb'] ) {
			
			if( $thumb->size_auto( $config['max_image'], $_POST['t_seite'] ) ) {
				
				$thumb->jpeg_quality( $config['jpeg_quality'] );
				
				if( $config['allow_watermark'] == "yes" ) $thumb->insert_watermark( $config['max_watermark'] );
				
				$thumb->save( $upload_path . "thumbs/" . $file_prefix . $image_name );
				
				@chmod( $upload_path . "thumbs/" . $file_prefix . $image_name, 0666 );
			}
		}
		
		if( $config['allow_watermark'] == "yes" or $config['max_up_side'] ) {
			
			$thumb = new thumbnail( $upload_path . $file_prefix . $image_name );
			$thumb->jpeg_quality( $config['jpeg_quality'] );
			
			if( $config['max_up_side'] ) $thumb->size_auto( $config['max_up_side'] );
			
			if( $config['allow_watermark'] == "yes" ) $thumb->insert_watermark( $config['max_watermark'] );
			
			$thumb->save( $upload_path . $file_prefix . $image_name );
		}
		
		$db->close();
		echo ("Ok");
	
	} else {
		
		msg_error( "Upload Error", 403 );
	
	}

} else {
	
	msg_error( "Not Allowed Type", 403 );
}

?>