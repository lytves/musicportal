<?php
/*
=====================================================
 DataLife Engine Nulled by M.I.D-Team
=====================================================
*/
if( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$userid = intval( $_REQUEST['userid'] );
$_SESSION['referrer'] = $_SERVER['REQUEST_URI'];


$allow_list = explode( ',', $user_group[$member_id['user_group']]['allow_cats'] );
$where = array ();

if( $userid ) {
	$where[] = PREFIX . "_comments.user_id='$userid'";
	$user_query = "do=lastcomments&amp;userid=" . $userid;
} else
	$user_query = "do=lastcomments";

if( $allow_list[0] != "all" ) {
	
	$join = "LEFT JOIN " . PREFIX . "_post ON " . PREFIX . "_comments.post_id=" . PREFIX . "_post.id ";
	
	if( $config['allow_multi_category'] ) {
		
		$where[] = PREFIX . "_post.category regexp '[[:<:]](" . implode( '|', $allow_list ) . ")[[:>:]]'";
	
	} else {
		
		$where[] = PREFIX . "_post.category IN ('" . implode( "','", $allow_list ) . "')";
	
	}

} else {
	
	$join = "";

}

if( $config['allow_cmod'] ) {
	
	$where[] = PREFIX . "_comments.approve='1'";

}

if( count( $where ) ) {
	
	$where = implode( " AND ", $where );
	$where = "WHERE " . $where;

} else
	$where = "";



$sql_count = "SELECT COUNT(*) as count FROM " . PREFIX . "_comments " . $join . $where;
$row_count = $db->super_query( $sql_count );

if( $row_count['count'] ) {

	include_once ENGINE_DIR . '/classes/comments.class.php';

	$comments = new DLE_Comments( $db, $row_count['count'], $config['comm_nummers'] );

	$comments->query = "SELECT " . PREFIX . "_comments.id, post_id, " . PREFIX . "_comments.user_id, " . PREFIX . "_comments.date, " . PREFIX . "_comments.autor as gast_name, " . PREFIX . "_comments.email as gast_email, text, ip, is_register, name, " . USERPREFIX . "_users.email, news_num, " . USERPREFIX . "_users.comm_num, user_group, reg_date, signature, foto, fullname, land, icq, " . USERPREFIX . "_users.xfields as xprofile, " . PREFIX . "_post.title, " . PREFIX . "_post.date as newsdate, " . PREFIX . "_post.alt_name, " . PREFIX . "_post.category, " . PREFIX . "_post.flag FROM " . PREFIX . "_comments LEFT JOIN " . PREFIX . "_post ON " . PREFIX . "_comments.post_id=" . PREFIX . "_post.id LEFT JOIN " . USERPREFIX . "_users ON " . PREFIX . "_comments.user_id=" . USERPREFIX . "_users.user_id " . $where . " ORDER BY id desc";

	$comments->build_comments('comments.tpl', 'lastcomments' );

	$comments->build_navigation('navigation.tpl', false, $user_query);		

} else {

	msgbox( $lang['all_info'], $lang['err_last'] );

}

?>