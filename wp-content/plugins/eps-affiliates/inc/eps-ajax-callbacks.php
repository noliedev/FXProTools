<?php 
/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
function users_auto_complete_callback($search_key = '') {
	if (isset($_POST['search_key'])) {
		$search_key = $_POST['search_key'];
	}
	$data 		= afl_get_users();
	$response = array();

	global $wpdb;
	$querystr = " SELECT * from `wp_users` WHERE `display_name` LIKE '%".$search_key."%' ;";
	$result = $wpdb->get_results($querystr) or die(mysql_error());

	foreach ($result as $key => $value) {
		$response[] = array('name'=> ($value->user_login.' ('.$value->ID.')'));
	}
	echo json_encode($response);
	die();
}
