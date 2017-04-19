<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$lastnews = dle_cache( "lastnews", $config['skin'] );

if( ! $lastnews ) {
	
	$db->query( "SELECT id, title, date, alt_name, category, flag FROM " . PREFIX . "_post WHERE approve='1' ORDER BY date DESC LIMIT 0,10" );
	
	while ( $row = $db->get_row() ) {
		
		$row['date'] = strtotime( $row['date'] );
		$row['category'] = intval( $row['category'] );
		
		if( $config['allow_alt_url'] == "yes" ) {
			
			if( $row['flag'] and $config['seo_type'] ) {
				
				if( $row['category'] and $config['seo_type'] == 2 ) {
					
					$full_link = $config['http_home_url'] . get_url( $row['category'] ) . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				} else {
					
					$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
				
				}
			
			} else {
				
				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
			}
		
		} else {
			
			$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
		
		}
		
		if( strlen( $row['title'] ) > 55 ) $title = substr( $row['title'], 0, 55 ) . " ...";
		else $title = $row['title'];
		
		$go_page = ($config['ajax']) ? "onclick=\"DlePage('newsid=" . $row['id'] . "'); return false;\" " : "";
		if( $config['allow_comments_wysiwyg'] == "yes" ) $go_page = '';
		
		$link = "<a {$go_page}href=\"" . $full_link . "\">" . stripslashes( $title ) . "</a>";
		
		$lastnews .= "&raquo; " . $link . "<br />";
	}
	
	$db->free();
	create_cache( "lastnews", $lastnews, $config['skin'] );
}
?>