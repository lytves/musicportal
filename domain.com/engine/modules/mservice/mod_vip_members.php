<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$vip_members = dle_cache2( "vip_members" );

if ( !$vip_members ) {

  $_TIME = time () + ($config['date_adjust'] * 60);
  
	$db->query( "SELECT user_id, name, time_limit FROM ".PREFIX."_users WHERE user_group = '7' ORDER BY name" );
	
	while( $row = $db->get_row( ) ) {
	
		if ($row['time_limit'] != "" and (intval($row['time_limit']) < $_TIME)) {
      $dell_vip_array[] = $row['user_id'];
      $to_dell_vip = TRUE;
      continue;
    }
		
		$vip_members .= '<a style="color:#ff00b2;" title="Пользователь '.$row['name'].'" href="'.$config['http_home_url'].'user/'.urlencode($row['name']).'/">'.$row['name'].'</a>, ';
  }

  create_cache2( "vip_members", substr($vip_members,0,-2));
  if ($to_dell_vip) {
    $dell_vip = implode( ",", $dell_vip_array );
    $db->query ( "UPDATE " . USERPREFIX . "_users set user_group='{$user_group[7]['rid']}', time_limit='' WHERE user_id IN ({$dell_vip})" );
  }

}

?>