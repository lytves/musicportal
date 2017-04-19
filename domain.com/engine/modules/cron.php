<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

set_vars( "cron", $_TIME );

if( $config['cache_count'] ) {
	$result = $db->query( "SELECT COUNT(*) as count, news_id FROM " . PREFIX . "_views GROUP BY news_id" );
	
	while ( $row = $db->get_array( $result ) ) {
		
		$db->query( "UPDATE " . PREFIX . "_post set news_read=news_read+{$row['count']} where id='{$row['news_id']}'" );
	
	}
	
	$db->free( $result );
	$db->query( "TRUNCATE TABLE " . PREFIX . "_views" );
	clear_cache( 'news_' );
}

if( $cron == 2 ) {
	
	$db->query( "DELETE FROM " . USERPREFIX . "_banned WHERE days != '0' AND date < '$_TIME' AND users_id = '0'" );
	@unlink( ENGINE_DIR . '/cache/system/banned.php' );
	
	$sql_cron = $db->query( "SELECT news_id, action FROM " . PREFIX . "_post_log WHERE expires <= '" . $_TIME . "'" );
	
	while ( $row = $db->get_row( $sql_cron ) ) {

		if ( $row['action'] ) {

			$db->query( "UPDATE " . PREFIX . "_post SET approve='0' WHERE id='{$row['news_id']}'" );
	
		} else {
		
			$db->query( "DELETE FROM " . PREFIX . "_comments WHERE post_id='{$row['news_id']}'" );
			$db->query( "DELETE FROM " . PREFIX . "_poll WHERE news_id='{$row['news_id']}'" );
			$db->query( "DELETE FROM " . PREFIX . "_poll_log WHERE news_id='{$row['news_id']}'" );
			$db->query( "DELETE FROM " . PREFIX . "_tags WHERE news_id = '{$row['news_id']}'" );
			$db->query( "DELETE FROM " . PREFIX . "_post WHERE id='{$row['news_id']}'" );
			
			$row_1 = $db->super_query( "SELECT images  FROM " . PREFIX . "_images where news_id = '{$row['news_id']}'" );
			
			$listimages = explode( "|||", $row_1['images'] );
			
			if( $row_1['images'] != "" ) foreach ( $listimages as $dataimages ) {
				$url_image = explode( "/", $dataimages );
				
				if( count( $url_image ) == 2 ) {
					
					$folder_prefix = $url_image[0] . "/";
					$dataimages = $url_image[1];
				
				} else {
					
					$folder_prefix = "";
					$dataimages = $url_image[0];
				
				}
				
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $dataimages );
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $dataimages );
			}
			
			$db->query( "DELETE FROM " . PREFIX . "_images WHERE news_id = '{$row['news_id']}'" );
			
			$getfiles = $db->query( "SELECT id, onserver FROM " . PREFIX . "_files WHERE news_id = '{$row['news_id']}'" );
			
			while ( $row_1 = $db->get_row( $getfiles ) ) {
				
				@unlink( ROOT_DIR . "/uploads/files/" . $row_1['onserver'] );
			
			}
			$db->free( $getfiles );
			
			$db->query( "DELETE FROM " . PREFIX . "_files WHERE news_id = '{$row['news_id']}'" );

		}
	
	}
	
	$db->query( "DELETE FROM " . PREFIX . "_post_log WHERE expires <= '" . $_TIME . "'" );
	
	$db->free( $sql_cron );
	
	if( intval( $config['max_users_day'] ) ) {
		$thisdate = $_TIME - ($config['max_users_day'] * 3600 * 24);
		
		$sql_result = $db->query( "SELECT name, user_id, foto FROM " . USERPREFIX . "_users WHERE lastdate < '$thisdate' and user_group = '4'" );
		
		while ( $row = $db->get_row( $sql_result ) ) {

			$db->query( "DELETE FROM " . USERPREFIX . "_pm WHERE user_from = '{$row['name']}' AND folder = 'outbox'" );
			$db->query( "DELETE FROM " . USERPREFIX . "_pm WHERE user='{$row['user_id']}'" );
			$db->query( "DELETE FROM " . USERPREFIX . "_banned WHERE users_id='{$row['user_id']}'" );
			$db->query( "DELETE FROM " . USERPREFIX . "_users WHERE user_id = '{$row['user_id']}'" );
			@unlink( ROOT_DIR . "/uploads/fotos/" . $row['foto'] );
		}

		$db->free( $sql_result );
		
	}
	
	if( intval( $config['max_image_days'] ) ) {
		$thisdate = $_TIME - ($config['max_image_days'] * 3600 * 24);
		
		$db->query( "SELECT images  FROM " . PREFIX . "_images where date < '$thisdate' AND news_id = '0'" );
		
		while ( $row = $db->get_row() ) {
			
			$listimages = explode( "|||", $row['images'] );
			
			if( $row['images'] != "" ) foreach ( $listimages as $dataimages ) {
				$url_image = explode( "/", $dataimages );
				
				if( count( $url_image ) == 2 ) {
					
					$folder_prefix = $url_image[0] . "/";
					$dataimages = $url_image[1];
				
				} else {
					
					$folder_prefix = "";
					$dataimages = $url_image[0];
				
				}
				
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . $dataimages );
				@unlink( ROOT_DIR . "/uploads/posts/" . $folder_prefix . "thumbs/" . $dataimages );
			}
		
		}
		
		$db->free();
		
		$db->query( "DELETE FROM " . PREFIX . "_images where date < '$thisdate' AND news_id = '0'" );
	
	}

clear_cache();

}
?>