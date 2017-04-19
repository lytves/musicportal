<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/

if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

# ���������� ���������
function cal($cal_month, $cal_year, $events) {
	global $f, $r, $year, $month, $config, $lang, $langdateshortweekdays, $PHP_SELF;
	
	$next = true;
	
	if( intval( $cal_year . $cal_month ) >= date( 'Ym' ) ) $next = false;

	$cur_date=date( 'Ymj' );
	$cal_date = $cal_year.$cal_month;

	$cal_month = intval( $cal_month );
	$cal_year = intval( $cal_year );
	
	if( $cal_month < 0 ) $cal_month = 1;
	if( $cal_year < 0 ) $cal_year = 2008;
	
	$first_of_month = mktime( 0, 0, 0, $cal_month, 7, $cal_year );
	$maxdays = date( 't', $first_of_month ) + 1; // 28-31
	$prev_of_month = mktime( 0, 0, 0, ($cal_month - 1), 7, $cal_year );
	$next_of_month = mktime( 0, 0, 0, ($cal_month + 1), 7, $cal_year );
	$cal_day = 1;
	$weekday = date( 'w', $first_of_month ); // 0-6
	

	if( $config['allow_alt_url'] == "yes" ) {
		
		$date_link['prev'] = '<a class="monthlink" onclick="doCalendar(' . date( "'m','Y'", $prev_of_month ) . '); return false;" href="' . $config['http_home_url'] . date( 'Y/m/', $prev_of_month ) . '" title="' . $lang['prev_moth'] . '">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		$date_link['next'] = '&nbsp;&nbsp;&nbsp;&nbsp;<a class="monthlink" onclick="doCalendar(' . date( "'m','Y'", $next_of_month ) . '); return false;" href="' . $config['http_home_url'] . date( 'Y/m/', $next_of_month ) . '" title="' . $lang['next_moth'] . '">&raquo;</a>';
	
	} else {
		
		$date_link['prev'] = '<a class="monthlink" onclick="doCalendar(' . date( "'m','Y'", $prev_of_month ) . '); return false;" href="' . $PHP_SELF . '?year=' . date( "Y", $prev_of_month ) . '&amp;month=' . date( "m", $prev_of_month ) . '" title="' . $lang['prev_moth'] . '">&laquo;</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		$date_link['next'] = '&nbsp;&nbsp;&nbsp;&nbsp;<a class="monthlink" onclick="doCalendar(' . date( "'m','Y'", $next_of_month ) . '); return false;" href="' . $PHP_SELF . '?year=' . date( "Y", $next_of_month ) . '&amp;month=' . date( "m", $next_of_month ) . '" title="' . $lang['next_moth'] . '">&raquo;</a>';
	
	}
	
	if( ! $next ) $date_link['next'] = "&nbsp;&nbsp;&nbsp;&nbsp;&raquo;";
	
	$buffer = '<div id="calendar-layer"><table id="calendar" cellpadding="3" class="calendar"><tr><th colspan="7" class="monthselect"><center><b>' . $date_link['prev'] . langdate( 'F', $first_of_month ) . ' ' . $cal_year . $date_link['next'] . '</b></center></th></tr><tr>';
	
	$buffer = str_replace( $f, $r, $buffer );
	
	# ��� ������: ������� ������
	for($it = 1; $it < 6; $it ++)
		$buffer .= '<th class="workday">' . $langdateshortweekdays[$it] . '</th>';
		
	# ��� ������: ��������� � ���������� ���
	$buffer .= '<th class="weekday">' . $langdateshortweekdays[6] . '</th>';
	$buffer .= '<th class="weekday">' . $langdateshortweekdays[0] . '</th>';
	
	$buffer .= '</tr><tr>';
	
	if( $weekday > 0 ) {
		$buffer .= '<td colspan="' . $weekday . '">&nbsp;</td>';
	}
	
	while ( $maxdays > $cal_day ) {

		$cal_pos = $cal_date.$cal_day;

		if( $weekday == 7 ) {
			$buffer .= '</tr><tr>';
			$weekday = 0;
		}
		
		# � ������ ���� ���� �������
		if( isset( $events[$cal_day] ) ) {
			$date['title'] = langdate( 'd F Y', $events[$cal_day] );
			
			# ���� ������� � �����������.
			if( $weekday == '5' or $weekday == '6' ) {
				
				$go_page = ($config['ajax']) ? "onclick=\"DlePage('year=" . date( "Y", $events[$cal_day] ) . "&amp;month=" . date( "m", $events[$cal_day] ) . "&day=" . date( "d", $events[$cal_day] ) . "'); return false;\" " : "";
				
				if( $config['allow_alt_url'] == "yes" ) $buffer .= '<td '.(($cal_pos==$cur_date)?' class="day-active day-current" ':' class="day-active" ').'><center><a class="day-active" ' . $go_page . 'href="' . $config['http_home_url'] . '' . date( "Y/m/d", $events[$cal_day] ) . '/" title="' . $lang['cal_post'] . ' ' . $date['title'] . '">' . $cal_day . '</a></center></td>';
				else $buffer .= '<td '.(($cal_pos==$cur_date)?' class="day-active day-current" ':' class="day-active" ').'><center><a class="day-active" ' . $go_page . 'href="' . $PHP_SELF . '?year=' . date( "Y", $events[$cal_day] ) . '&amp;month=' . date( "m", $events[$cal_day] ) . '&day=' . date( "d", $events[$cal_day] ) . '" title="' . $lang['cal_post'] . ' ' . $date['title'] . '">' . $cal_day . '</a></center></td>';
			
			} 

			# ������� ���.
			else {
				
				$go_page = ($config['ajax']) ? "onclick=\"DlePage('year=" . date( "Y", $events[$cal_day] ) . "&amp;month=" . date( "m", $events[$cal_day] ) . "&day=" . date( "d", $events[$cal_day] ) . "'); return false;\" " : "";
				
				if( $config['allow_alt_url'] == "yes" ) $buffer .= '<td '.(($cal_pos==$cur_date)?' class="day-active-v day-current" ':' class="day-active-v" ').'><center><a class="day-active-v" ' . $go_page . 'href="' . $config['http_home_url'] . '' . date( "Y/m/d", $events[$cal_day] ) . '/" title="' . $lang['cal_post'] . ' ' . $date[title] . '">' . $cal_day . '</a></center></td>';
				else $buffer .= '<td '.(($cal_pos==$cur_date)?' class="day-active-v day-current" ':' class="day-active-v" ').'><center><a class="day-active-v" ' . $go_page . 'href="' . $PHP_SELF . '?year=' . date( "Y", $events[$cal_day] ) . '&amp;month=' . date( "m", $events[$cal_day] ) . '&day=' . date( "d", $events[$cal_day] ) . '" title="' . $lang['cal_post'] . ' ' . $date[title] . '">' . $cal_day . '</a></center></td>';
			
			}
		} 

		# � ������ ���� �������� ���.
		else {
			
			# ���� ������� �����������
			if( $weekday == "5" or $weekday == "6" ) {
				$buffer .= '<td '.(($cal_pos==$cur_date)?' class="weekday day-current" ':' class="weekday" ').'><center>' . $cal_day . '</center></td>';
			} 

			# ���, ����� ������ ���
			else {
				$buffer .= '<td '.(($cal_pos==$cur_date)?' class="day day-current" ':' class="day" ').'><center>' . $cal_day . '</center></td>';
			}
		}
		
		$cal_day ++;
		$weekday ++;
	}
	
	if( $weekday != 7 ) {
		$buffer .= '<td colspan="' . (7 - $weekday) . '">&nbsp;</td>';
	}
	
	return $buffer . '</tr></table></div>';
}

# ���������
if( $config['allow_calendar'] == "yes" ) {
	
	$events = array ();
	
	$thisdate = date( "Y-m-d H:i:s", $_TIME );
	if( intval( $config['no_date'] ) ) $where_date = " AND date < '" . $thisdate . "'";
	else $where_date = "";
	
	$this_month = date( 'm', $_TIME );
	$this_year = date( 'Y', $_TIME );
	
	if( $year != '' and $month != '' ) $cache_id = $config['skin'] . $month . $year;
	else $cache_id = $config['skin'] . $this_month . $this_year;
	
	$tpl->result['calendar'] = dle_cache( "calendar", $cache_id );
	
	if( ! $tpl->result['calendar'] ) {
		
		if( $year != '' and $month != '' ) {
			
			if( ($year == $this_year and $month < $this_month) or ($year < $this_year) ) {
				$where_date = "";
				$approve = "";
			} else {
				$approve = " AND approve";
			}
			
			if( ($year == $this_year and $month > $this_month) or ($year > $this_year) ) {
				
				$sql = "";
			
			} else {
				
				$sql = "SELECT DISTINCT DAYOFMONTH(date) as day FROM " . PREFIX . "_post WHERE date >= '{$year}-{$month}-01' AND date < '{$year}-{$month}-01' + INTERVAL 1 MONTH" . $approve . $where_date;
			
			}
			
			$this_month = $month;
			$this_year = $year;
		
		} else {
			
			$sql = "SELECT DISTINCT DAYOFMONTH(date) as day FROM " . PREFIX . "_post WHERE date >= '{$this_year}-{$this_month}-01' AND date < '{$this_year}-{$this_month}-01' + INTERVAL 1 MONTH AND approve" . $where_date;
		
		}
		
		if( $sql != "" ) {
			
			$db->query( $sql );
			
			while ( $row = $db->get_row() ) {
				$events[$row['day']] = strtotime( $this_year . "-" . $this_month . "-" . $row['day'] );
			}
			
			$db->free();
		}
		
		$tpl->result['calendar'] = cal( $this_month, $this_year, $events );
		create_cache( "calendar", $tpl->result['calendar'], $cache_id );
	}

} else $tpl->result['calendar'] ='';

# ������� ������
if( $config['allow_archives'] == "yes" ) {
	
	$tpl->result['archive'] = dle_cache( "archives", $config['skin'] );
	
	if( ! $tpl->result['archive'] ) {
		
		$f2 = array ('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12' );
		$f3 = array ('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' );
		
		if( intval( $config['no_date'] ) ) {
			$thisdate = date( "Y-m-d H:i:s", $_TIME );
			$where_date = " AND date < '" . $thisdate . "'";
		} else
			$where_date = "";
		
		$db->query( "SELECT DATE_FORMAT(date,'%b %Y') AS m_date, COUNT(*) AS cnt FROM " . PREFIX . "_post WHERE approve" . $where_date . " GROUP BY m_date ORDER BY date desc" );
		
		$news_archive = array ();
		
		while ( $row = $db->get_row() ) {
			
			$arch_title['ru'] = str_replace( $f3, $r, $row['m_date'] );
			$arch_title['en'] = str_replace( $f3, $f2, $row['m_date'] );
			$arch_url = explode( " ", $arch_title['en'] );
			$arch_title['en'] = $arch_url[1] . "/" . $arch_url[0];
			
			$go_page = ($config['ajax']) ? "onclick=\"DlePage('year={$arch_url[1]}&amp;month={$arch_url[0]}'); return false;\" " : "";
			
			if( $config['allow_alt_url'] == "yes" ) $news_archive[] = '<a class="archives" ' . $go_page . 'href="' . $config['http_home_url'] . $arch_title['en'] . '/"><b>' . $arch_title['ru'] . ' (' . $row['cnt'] . ')</b></a>';
			else $news_archive[] = "<a class=\"archives\" " . $go_page . "href=\"$PHP_SELF?year=$arch_url[1]&amp;month=$arch_url[0]\"><b>" . $arch_title['ru'] . " (" . $row['cnt'] . ")</b></a>";
		
		}
		
		$db->free();
		
		$i = count( $news_archive );
		
		if( $i > 6 ) {
			$news_archive[6] = "<div id=\"dle_news_archive\" style=\"display:none;\">" . $news_archive[6];
			$news_archive[] = "</div><div id=\"dle_news_archive_link\" ><br /><a class=\"archives\" onclick=\"ShowOrHide('dle_news_archive'); document.getElementById( 'dle_news_archive_link' ).innerHTML = ''; return false;\" href=\"#\">" . $lang['show_archive'] . "</a></div>";
		}
		
		if( $i ) $tpl->result['archive'] = implode( "<br />", $news_archive );
		else $tpl->result['archive'] = "";
		
		create_cache( "archives", $tpl->result['archive'], $config['skin'] );
	}

} else $tpl->result['archive'] ='';
?>