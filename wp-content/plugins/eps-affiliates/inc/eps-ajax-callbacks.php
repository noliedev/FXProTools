<?php 
/*
 * ------------------------------------------------
 * get users name and id
 * ------------------------------------------------
*/
<<<<<<< HEAD
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
=======
function users_auto_complete_callback() {
	$users[] = array('value'=>'1','data'=>'u1');
	$users[] = array('value'=>'2','data'=>'u2');
	$users[] = array('value'=>'3','data'=>'u3');
	$users[] = array('value'=>'4','data'=>'u4');
	echo json_encode($users);
	die();
}
>>>>>>> a3eb117dca110ed02010bf0895e5c78cdae5e735
