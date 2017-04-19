<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$nam_e1 = 'ѕопул€рные поисковые запросы за неделю (по исполнителю)';

if ( $_REQUEST['page'] == FALSE ) $page = 1;
else $page = intval( $_REQUEST['page'] );

$limit = ( $page * $mscfg['track_page_lim'] ) - $mscfg['track_page_lim'];

if ( $page == 1 ) $metapage = '';
else $metapage = '(страница '.$page.') ';
$metatags['title'] = 'ѕопул€рные поисковые запросы за неделю (по исполнителю) '.$metapage.'на '.$config['home_title_short'];
$metatags['description'] = 'ѕопул€рные поисковые запросы за неделю (по исполнителю) на '.$config['description'];
$metatags['keywords'] = 'музыкальные новинки 2012, скачать бесплатно, нова€ музыка мп3, скачать mp3, кроликов мп3 нет, нова€ музыка, хиты 2012, клубна€ нова€ музыка, хитовые новинки 2012';

$toptime = 604800;
//604800 секунд в неделе
$toptime = (time() - $toptime);

$db->query( "SELECT search_text, COUNT(search_text) AS cnt, (SELECT COUNT(DISTINCT search_text) FROM ".PREFIX."_mservice_search WHERE (search_type = '2' AND count != '0' AND time_php > '$toptime')) as cnt2 FROM ".PREFIX."_mservice_search WHERE (search_type = '2' AND count != '0' AND time_php > '$toptime') GROUP BY search_text HAVING cnt > 0 ORDER BY cnt DESC LIMIT ".$limit."," . $mscfg['track_page_lim'] );

$mtitle = '<h3 class="tit">ѕопул€рные поисковые запросы за неделю (по исполнителю)</h3>';

if ( $db->num_rows( ) == 0 ) $stop[] = 'Ќет треков дл€ этой страницы ѕопул€рных поисковых запросов за неделю (по исполнителю)';
else {
	$mcontent = <<<HTML
<table width="100%">
<tr>
<th width="85%" height="20">&nbsp;</th>
<th width="15%">«апросов</th>
</tr>
HTML;

	$mcontent .= '<div id="navigation_up"></div>';

	while( $row = $db->get_row( ) ) {
		if (!$count) $count = $row['cnt2'];

		if( strlen( $row['search_text'] ) > 80 ) $search_text = substr( $row['search_text'], 0, 76 )." ...";
		else $search_text = $row['search_text'];
		
		$search_text64 = str_replace('/','_',str_replace('+','*',base64_encode($row['search_text'])));
		
		$search_text_link = '<a href="'.$config['http_home_url'].'music/search-2-1-'.$search_text64.'.html" title="'.$row['search_text'].'"><b>'.$search_text.'</b></a>';
		
		$mcontent .= '<tr><td align="left" style="background-image: url({THEME}/images/artistlist.png); background-repeat:no-repeat;" height="20"><div class="artists">'.$search_text_link.'</div><div class="dashed inactive2"></div></td>';
		
		$mcontent .= '<td><center>'.$row['cnt'].'</center></td></tr>';
	}
	
	$mcontent .= '</table>';
	
	if ($count < 10 ) {
		include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
		$mcontent .= $downloads_top_week;
	}
	// ѕостранична€ навигаци€
	$count_d = $count / $mscfg['track_page_lim'];

	for ( $t = 0; $count_d > $t; $t++ ) {
		$t2 = $t + 1;
		$plink = $config['http_home_url'].'music/popularsearchartistweek-page-'.$t2.'.html';
  
		if ( $t2 == $page ) $pages .= '<span>'.$t2.'</span> ';
		else { if ($t2 == 1) { $plink = $config['http_home_url'].'music/popularsearchartistweek.html';
					$pages .= '<a href="'.$plink.'">1</a> ';} else $pages .= '<a href="'.$plink.'">'.$t2.'</a> ';
				}
			$array[$t2] = 1;
		}

	$link = $config['http_home_url'].'music/popularsearchartistweek-page-';
	$seo_mode = '.html';

	$npage = $page - 1;
	if ( isset($array[$npage]) ) { if ($npage ==1 ) { $linkdop = $config['http_home_url'].'music/popularsearchartistweek';
										$prev_page = '<a href="'.$linkdop.$seo_mode.'">Ќазад</a> ';
									}
									else $prev_page = '<a href="'.$link.$npage.$seo_mode.'">Ќазад</a> ';
	}
	else $prev_page = '';
	$npage = $page + 1;
	if ( isset($array[$npage]) ) $next_page = ' <a href="'.$link.$npage.$seo_mode.'">ƒалее</a>';
	else $next_page = '';
	
	if ( $count > $mscfg['track_page_lim'] ) {
		$mcontent .= <<<HTML
<div class="navigation" align="center" style="margin-bottom:10px; margin-top:10px;">{$prev_page}{$pages}{$next_page}</div>
HTML;

		$tracks_to_view = $count - (($page * $mscfg['track_page_lim']) - $mscfg['track_page_lim']);
		if ($tracks_to_view >= 10) $mcontent .= <<<HTML
<script type="text/javascript">
showNavigationUp('{$prev_page}{$pages}{$next_page}');
</script>
HTML;
		else { include_once ENGINE_DIR.'/modules/mservice/mod_downloads_top_week.php';
		$mcontent .= $downloads_top_week;
		}
	}
}
?>