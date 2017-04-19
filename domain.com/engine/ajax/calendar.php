<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
@session_start();

error_reporting( 7 );
@ini_set( 'display_errors', true );
@ini_set( 'html_errors', false );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', '../..' );
define( 'ENGINE_DIR', '..' );

include ENGINE_DIR . '/data/config.php';

if( $config['http_home_url'] == "" ) {
	
	$config['http_home_url'] = explode( "engine/ajax/calendar.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';

$PHP_SELF = $config['http_home_url'] . "index.php";

require_once ENGINE_DIR . '/modules/functions.php';

$_COOKIE['dle_skin'] = trim(totranslit( $_COOKIE['dle_skin'], false, false ));

if( $_COOKIE['dle_skin'] ) {

	if( @is_dir( ROOT_DIR . '/templates/' . $_COOKIE['dle_skin'] ) ) {
		$config['skin'] = $_COOKIE['dle_skin'];
	}
}

if( $config["lang_" . $config['skin']] ) {
	
	@include_once ROOT_DIR . '/language/' . $config["lang_" . $config['skin']] . '/website.lng';

} else {
	
	@include_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

}

$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];


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

$buffer = false;
$time = time() + ($config['date_adjust'] * 60);
$thisdate = date( "Y-m-d H:i:s", $time );
if( intval( $config['no_date'] ) ) $where_date = " AND date < '" . $thisdate . "'"; else $where_date = "";

$this_month = date( 'm', $time );
$this_year = date( 'Y', $time );

$month = $db->safesql( $_GET['month'] );
$year = intval( $_GET['year'] );

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
$db->close();

$buffer = cal( $this_month, $this_year, $events );

header( "Content-type: text/css; charset=" . $config['charset'] );
echo $buffer;

?>