<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

//################# Определение баннеров
$informers = get_vars( "informers" );

if( ! is_array( $informers ) ) {
	$informers = array ();
	
	$db->query( "SELECT * FROM " . PREFIX . "_rssinform ORDER BY id ASC" );
	
	while ( $row_b = $db->get_row() ) {
		
		$informers[$row_b['id']] = array ();
		
		foreach ( $row_b as $key => $value ) {
			$informers[$row_b['id']][$key] = stripslashes( $value );
		}
	
	}
	set_vars( "informers", $informers );
	$db->free();
}

$config['allow_cache'] = "yes";
$temp = array ();
$i = 0;

if( count( $informers ) ) {
	foreach ( $informers as $name => $value ) {
		if( $value['approve'] ) { //если активный
			

			if( $value['category'] ) {
				$value['category'] = explode( ',', $value['category'] );
				
				if( ! in_array( $category_id, $value['category'] ) ) $value['url'] = "";
			}
			
			$temp[$value['tag']][$i]['id'] = $value['id'];
			$temp[$value['tag']][$i]['tag'] = $value['tag'];
			$temp[$value['tag']][$i]['url'] = $value['url'];
			$temp[$value['tag']][$i]['template'] = $value['template'];
			$temp[$value['tag']][$i]['news_max'] = $value['news_max'];
			$temp[$value['tag']][$i]['tmax'] = $value['tmax'];
			$temp[$value['tag']][$i]['dmax'] = $value['dmax'];
			$temp[$value['tag']][$i]['rss_date_format'] = $value['rss_date_format'];
		
		}
		$i ++;
	}
	
	foreach ( $temp as $key => $value ) {
		
		$r_key = array_rand( $temp[$key] );
		$temp[$key] = $temp[$key][$r_key];
	
	}
	
	$informers = array ();
	
	foreach ( $temp as $key => $value ) {
		
		if( $value['url'] == "" ) {
			
			$informers[$value['tag']] = "";
			continue;
		
		}
		
		$buffer = dle_cache( "informer_" . $value['id'], $config['skin'] );

		if ( $buffer ) {

			$file_date = @filemtime( ENGINE_DIR.'/cache/informer_'.$value['id'].'_'.totranslit($config['skin']).'.tmp' );

			if ( $file_date ) {

				if (date ( "d-H", $file_date ) != date ( "d-H" )) {

					$buffer = false;
					@unlink( ENGINE_DIR.'/cache/informer_'.$value['id'].'_'.totranslit($config['skin']).'.tmp' );

				}

			}

		}
		
		if( ! $buffer ) {

			include_once ENGINE_DIR . '/classes/rss.class.php';
			
			$xml = new xmlParser( stripslashes( $value['url'] ), $value['news_max'] );
			
			if( $xml->rss_option == "UTF-8" ) $xml->convert( "UTF-8", strtolower( $config['charset'] ) );
			elseif( $xml->rss_charset != strtolower( $config['charset'] ) ) $xml->convert( $xml->rss_charset, strtolower( $config['charset'] ) );
			
			$xml->pre_parse( 0 );
			$i = 0;
			
			foreach ( $xml->content as $content ) {
				$xml->content[$i]['title'] = strip_tags( $xml->content[$i]['title'] );
				$xml->content[$i]['title'] = trim( $xml->content[$i]['title'] );
				$xml->content[$i]['category'] = trim( strip_tags( $xml->content[$i]['category'] ) );
				$xml->content[$i]['author'] = trim( strip_tags( $xml->content[$i]['author'] ) );
				
				if( $value['tmax'] and strlen( $xml->content[$i]['title'] ) > $value['tmax'] ) $xml->content[$i]['title'] = substr( $xml->content[$i]['title'], 0, $value['tmax'] ) . " ...";
				
				$xml->content[$i]['description'] = strip_tags( $xml->content[$i]['description'], "<br>" );
				$xml->content[$i]['description'] = str_replace( "<br>", " ", str_replace( "<br />", " ", $xml->content[$i]['description'] ) );
				$xml->content[$i]['description'] = trim( $xml->content[$i]['description'] );
				
				if( $value['dmax'] and strlen( $xml->content[$i]['description'] ) > $value['dmax'] ) {
					
					$xml->content[$i]['description'] = substr( $xml->content[$i]['description'], 0, $value['dmax'] );
					
					if( ($temp_dmax = strrpos( $xml->content[$i]['description'], ' ' )) ) $xml->content[$i]['description'] = substr( $xml->content[$i]['description'], 0, $temp_dmax );
					
					$xml->content[$i]['description'] .= " ...";
				
				}
				
				$i ++;
			}
			
			$tpl->load_template( $value['template'] . '.tpl' );
			
			foreach ( $xml->content as $content ) {
				$tpl->set( '{title}', $content['title'] );
				$tpl->set( '{news}', $content['description'] );
				$tpl->set( '[link]', "<a href=\"" . htmlspecialchars ( $content['link']) . "\" target=\"_blank\">" );
				$tpl->set( '[/link]', "</a>" );
				$tpl->set( '{category}', $content['category'] );
				$tpl->set( '{author}', $content['author'] );
				$tpl->set( '{date}', langdate( $value['rss_date_format'], $content['date'] ) );
				
				$tpl->compile( 'rss_info' );
			}
			
			$buffer = $tpl->result['rss_info'];
			$tpl->result['rss_info'] = "";
			$tpl->clear();

			create_cache( "informer_" . $value['id'], $buffer, $config['skin'] );
		
		}
		
		$informers[$value['tag']] = $buffer;
	
	}

}

$temp = array ();
unset( $temp );

?>